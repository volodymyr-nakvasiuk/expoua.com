<?php
Zend_Loader::loadClass("Zend_Http_Client");

class Sync_Stat_EventsRedirects extends DataProviderAbstract {

	public function write() {
		$client = new Zend_Http_Client(SYNC_SERVER_PATH . "?type=events_redirects_write",
			array(
				'maxredirects' => 0,
				'timeout' => 10 // Ждем ответа максимум 10 секунд, потом рвем соединение
			)
		);
		$client->setMethod(Zend_Http_Client::POST);

		// Получаем последний
		$lastId = self::_DP("List_OptionsConstants")->getValueByCode("SYNC_WR_LOCAL_LAST_ID");

		$query = 'SELECT wr.id, wr.redirect_time, wr.ip, wr.referer, wr.lang, e.global_sync_id
FROM statistic.events_website_redirects AS wr
JOIN ExpoPromoter_Opt.events AS e ON (e.id = wr.events_id)
WHERE wr.source = "local" AND wr.id > ?
ORDER BY wr.id ASC LIMIT 1000';

		$data = self::$_db->fetchAll($query, array($lastId));

		if (empty($data)) {
			return;
		}

		// Выполняем отправку данных на сервер-арбитр
		$client->setRawData(serialize($data), "application/octet-stream");
			
		try {
			$response = $client->request()->getBody();
			$response_array = unserialize($response);
			if ($response_array == false || $response_array['status'] == Sync_Base::STATUS_FAIL) {
				return;
			}
		} catch (Zend_Http_Client_Exception $e) {
			// Не удалось соединиться с сервером, прекращаем попытки до следующего захода
			return;
		}

		// Сохраняем id последней записи чтобы в следующий раз продолжить
		self::_DP("List_OptionsConstants")->updateEntry(null,
			array('value' => $data[sizeof($data) - 1]['id']),
			array('code' => 'SYNC_WR_LOCAL_LAST_ID')
		);
	}

	public function read() {
		$client = new Zend_Http_Client(SYNC_SERVER_PATH . "?type=events_redirects_read",
			array(
				'maxredirects' => 0,
				'timeout' => 10 // Ждем ответа максимум 10 секунд, потом рвем соединение
			)
		);
		$client->setMethod(Zend_Http_Client::POST);
		
		$lastId = self::_DP("List_OptionsConstants")->getValueByCode("SYNC_WR_REMOTE_LAST_ID");
		$data = array('last_queue_id' => $lastId);

		$client->setRawData(serialize($data), "application/octet-stream");
		
		try {
			$response = $client->request()->getBody();
			$response_array = unserialize($response);
			
			if ($response_array === false) {
				// Десеарилизация не удалась, прекращаем
				echo $response;
				return;
			}
		} catch (Zend_Http_Client_Exception $e) {
			// Не удалось соединиться с сервером, прекращаем попытки до следующего захода
			return;
		}
		
		if (empty($response_array)) {
			return;
		}
		
		// Составляем массив уникальных global_sync_id и получаем последний id в удаленной базе
		$global_sync_id_array = array();
		foreach ($response_array as $element) {
			$global_sync_id_array[$element['global_sync_id']] = $element['global_sync_id'];
			$lastId = $element['id'];
		}

		self::_DP("List_OptionsConstants")->updateEntry(null,
				array('value' => $lastId),
				array('code' => 'SYNC_WR_REMOTE_LAST_ID')
			);

		// Получаем соответствия global_sync_id => id
		$select = self::$_db->select();
		$select->from("ExpoPromoter_Opt.events", array("global_sync_id", "id"));
		$select->where("global_sync_id IN (?)", $global_sync_id_array);
		$global_sync_id_array = self::$_db->fetchPairs($select);
		
		if (empty($global_sync_id_array)) {
			return;
		}
				
		$query_hits = 'INSERT INTO statistic.events_website_redirects SET redirect_time = ?, ip = ?, referer = ?,
lang = ?, `source` = "remote", events_id = ?';
		$query_counter = 'UPDATE statistic.events_counter SET redir_cnt=redir_cnt+1 WHERE id = ?';
		$stmt_hits = self::$_db->prepare($query_hits);
		$stmt_counter = self::$_db->prepare($query_counter);
		foreach ($response_array as $element) {
			if (isset($global_sync_id_array[$element['global_sync_id']])) {
				$events_id = $global_sync_id_array[$element['global_sync_id']];
			} else {
				continue;
			}
			
			$stmt_hits->execute(array(
				$element['redirect_time'], $element['ip'], $element['referer'], $element['lang'], $events_id
			));
			$stmt_counter->execute(array($events_id));
		}
	}

}