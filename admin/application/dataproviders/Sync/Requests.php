<?php
Zend_Loader::loadClass("Zend_Http_Client");
require_once(PATH_ROOT . "/../ws/config.php");

class Sync_Requests extends DataProviderAbstract {
	
	public function write() {
		$client = new Zend_Http_Client(SYNC_SERVER_PATH . "?type=requests_write",
			array(
				'maxredirects' => 0,
				'timeout' => 10 // Ждем ответа максимум 10 секунд, потом рвем соединение
			)
		);
		$client->setMethod(Zend_Http_Client::POST);

		// Получаем последний
		$query = 'SELECT sr.id, sr.type, sr.languages_id, sr.date_add, sr.host,
e.global_sync_id AS events_global_sync_id, c.global_sync_id AS countries_global_sync_id,
o.global_sync_id AS organizers_global_sync_id
FROM ExpoPromoter_Opt.sync_requests AS sr
JOIN ExpoPromoter_Opt.events AS e ON (sr.events_id = e.id)
JOIN ExpoPromoter_Opt.brands AS b ON (e.brands_id = b.id)
JOIN ExpoPromoter_Opt.organizers AS o ON (b.organizers_id = o.id)
LEFT JOIN ExpoPromoter_Opt.location_countries AS c ON (sr.countries_id = c.id)
WHERE sr.status="new" LIMIT 100';
		$data = self::$_db->fetchAll($query);

		if (empty($data)) {
			return;
		}

		$query_fields = "SELECT type, value FROM ExpoPromoter_Opt.sync_requests_data WHERE requests_id = ?";
		
		$updateIds = array();
		foreach ($data as &$element) {
			$element['fields'] = self::$_db->fetchPairs($query_fields, array($element['id']));
			$updateIds[] = $element['id'];
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

		// Обновляем статус отправленных записей
		$where = array(self::$_db->quoteInto("id IN (?)", $updateIds));
		self::$_db->update('ExpoPromoter_Opt.sync_requests', array('status' => 'success'), $where);
	}
	
	public function read() {
		$client = new Zend_Http_Client(SYNC_SERVER_PATH . "?type=requests_read",
			array(
				'maxredirects' => 0,
				'timeout' => 10 // Ждем ответа максимум 10 секунд, потом рвем соединение
			)
		);
		$client->setMethod(Zend_Http_Client::POST);
		
		$lastId = self::_DP("List_OptionsConstants")->getValueByCode("SYNC_REQ_LAST_QUEUE_ID");
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
		$events_global_sync_id_array = array();
		$countries_global_sync_id_array = array();
		$organizers_global_sync_id_array = array();
		foreach ($response_array as $element) {
			$events_global_sync_id_array[$element['events_global_sync_id']] = $element['events_global_sync_id'];
			$organizers_global_sync_id_array[$element['organizers_global_sync_id']] = $element['organizers_global_sync_id'];
			
			if (!is_null($element['countries_global_sync_id'])) {
				$countries_global_sync_id_array[$element['countries_global_sync_id']] = $element['countries_global_sync_id'];
			}
			
			$lastId = $element['id'];
		}

		self::_DP("List_OptionsConstants")->updateEntry(null,
				array('value' => $lastId),
				array('code' => 'SYNC_REQ_LAST_QUEUE_ID')
			);

		// Получаем соответствия events_global_sync_id_array => id
		$select = self::$_db->select();
		$select->from("ExpoPromoter_Opt.events", array("global_sync_id", "id"));
		$select->where("global_sync_id IN (?)", $events_global_sync_id_array);
		$events_global_sync_id_array = self::$_db->fetchPairs($select);

		// Получаем соответствия organizers_global_sync_id_array => id
		$select = self::$_db->select();
		$select->from("ExpoPromoter_Opt.organizers", array("global_sync_id", "id"));
		$select->where("global_sync_id IN (?)", $organizers_global_sync_id_array);
		$organizers_global_sync_id_array = self::$_db->fetchPairs($select);
		
		if (!empty($countries_global_sync_id_array)) {
			// Получаем соответствия countries_global_sync_id_array => id
			$select = self::$_db->select();
			$select->from("ExpoPromoter_Opt.location_countries", array("global_sync_id", "id"));
			$select->where("global_sync_id IN (?)", $countries_global_sync_id_array);
			$countries_global_sync_id_array = self::$_db->fetchPairs($select);
		}
		
		require_once(PATH_WS_CLASSES . "/iMail/Distribute/InformOrders.php");
    	$iMail = new iMail_Distribute_InformOrders();
				
		$query_requests = 'INSERT INTO ExpoPromoter_Opt.requests SET type = ?, parent = ?, child = ?,
countries_id = ?, languages_id = ?, date_add = ?, host = ?';
		$query_requests_data = 'INSERT INTO ExpoPromoter_Opt.requests_data SET requests_id = ?, type = ?, value = ?';
		$stmt_requests = self::$_db->prepare($query_requests);
		$stmt_requests_data = self::$_db->prepare($query_requests_data);
		foreach ($response_array as $element) {
			
			if (isset($events_global_sync_id_array[$element['events_global_sync_id']])) {
				$events_id = $events_global_sync_id_array[$element['events_global_sync_id']];
				$element['eventId'] = $events_id;
			} else {
				$events_id = 0;
			}
			
			if (isset($organizers_global_sync_id_array[$element['organizers_global_sync_id']])) {
				$organizers_id = $organizers_global_sync_id_array[$element['organizers_global_sync_id']];
			} else {
				$organizers_id = 0;
				$element['orgId'] = $organizers_id;
			}
			
			if (isset($countries_global_sync_id_array[$element['countries_global_sync_id']])) {
				$countries_id = $countries_global_sync_id_array[$element['countries_global_sync_id']];
			} else {
				$countries_id = 0;
				$element['countryId'] = $countries_id;
			}
			
			$organizer_data = self::_DP("List_Joined_Ep_Organizers")->getEntry($organizers_id);
			if (!Sync_Base::isCountryOwned($organizer_data['countries_id'])) {
				// Не наш клиент, пропускаем его
				echo "Skip\n";
				continue;
			}
			
			self::$_db->beginTransaction();
			try {
				$stmt_requests->execute(array(
					$element['type'], $organizers_id, $events_id, $countries_id, $element['languages_id'],
					$element['date_add'], $element['host']
				));
				$requests_id = self::$_db->lastInsertId();
				foreach ($element['fields'] as $field_key => $field_value) {
					$stmt_requests_data->execute(array(
						$requests_id,
						$field_key,
						$field_value
					));
				}
				self::$_db->commit();
				
				// Отсправляем запрос организатору на Email
				$data = $element;
				unset($data['fields']);
				$data = array_merge($data, $element['fields']);
				try {
					$iMail->organizer($element['type'], $data);
				} catch (Zend_Mail_Exception $em) {
					echo $em->getTraceAsString() . "\n";
				}
			} catch (Zend_Db_Exception $e) {
				self::$_db->rollBack();
				echo $e->getTraceAsString() . "\n";
			}
		}
	}
	
}