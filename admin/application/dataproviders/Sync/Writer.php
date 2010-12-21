<?php
Zend_Loader::loadClass("Sync_Base", PATH_DATAPROVIDERS);
Zend_Loader::loadClass("Zend_Http_Client");

class Sync_Writer extends DataProviderAbstract {
	
	/**
	 * Читает локальную очередь и передает записи на сервер-арбитр
	 */
	public function writeToRefereeServer($num = 20) {
		$client = new Zend_Http_Client(SYNC_SERVER_PATH . "?type=write",
			array(
				'maxredirects' => 0,
				'timeout' => 10, // Ждем ответа максимум 10 секунд, потом рвем соединение
				'keepalive' => true, // Несколько запросов будем слать поседовательно
			)
		);
		$client->setMethod(Zend_Http_Client::POST);
		
		$queueObj = self::_DP("List_Sync_Queue");
		
		$list = $queueObj->getList($num, null,
			array('status' => Sync_Base::STATUS_NEW),
			array('id' => 'ASC')
		);

		foreach ($list['data'] as $task) {
			$data = array('task' => $task, 'data' => array());
			$dataObj = null;
			
			try {
				$dataObj = self::_DP($task['provider']);
			} catch (Zend_Exception $e) {
				// Неизвестный тип в очереди, пропускаем и помечаем его как fail
				$queueObj->updateEntry($task['id'],
					array('status' => Sync_Base::STATUS_FAIL, 'debug' => 'Unknown provider'));
				continue;
			}
			
			// Предотвращаем распространения изменений по другим серверам
			$dataObj->allowSync = false;
			
			switch ($task['type']) {
				case Sync_Base::TYPE_ADD:
					$data['data'] = $this->_getDataProviderData($task['parents_id'], $dataObj);
					
					if (empty($data['data'])) {
						// Запись была удалена сразу после добавления, помечаем все записи, связанные с ней как fail
						$queueObj->updateEntry(null,
							array('status' => 'fail', 'debug' => 'Parent entry not found'),
							array(
								'provider' => $task['provider'],
								'status' => Sync_Base::STATUS_NEW,
								'parents_id' => $task['parents_id']
							)
						);
						// Прерываем выполение всех задач чтобы избежать попыток выполения помеченных как fail
						return;
					}
					break;
				case Sync_Base::TYPE_UPDATE:
					if (is_null($task['global_sync_id'])) {
						// Пропускаем с пустым глобальным id до выполения add, где этот id будет получен
						continue 2;
					}
					
					$data['data'] = $this->_getDataProviderData($task['parents_id'], $dataObj);
					if (empty($data['data'])) {
						// Запись была удалена сразу после обновления, помечаем запись в очереди как fail,
						// за ней должна следовать задача по удалению, которая будет отработана
						$queueObj->updateEntry($task['id'],
							array('status' => Sync_Base::STATUS_FAIL, 'debug' => 'Parent entry not found')
						);
						continue 2;
					}
					break;
				case Sync_Base::TYPE_DELETE:
					if (is_null($task['global_sync_id'])) {
						// Пропускаем с пустым глобальным id до выполения add, где этот id будет получен
						continue 2;
					}
					break;
				default:
					// Неизвестное действие в очереди, пропускаем и помечаем его как fail
					$queueObj->updateEntry($task['id'],
						array('status' => Sync_Base::STATUS_FAIL, 'debug' => 'Unknown type'));
					continue 2;
			}
			
			// Выполняем отправку данных на сервер-арбитр
			$client->setRawData(serialize($data), "application/octet-stream");
			
			try {
				$response = $client->request()->getBody();
				$response_array = unserialize($response);
				if ($response_array == false || $response_array['status'] == Sync_Base::STATUS_FAIL) {
					// Что-то пошло не так, пропускаем и записываем в debug ответ сервера
					$queueObj->updateEntry($task['id'], array('debug' => "Server response:\n" . $response));
				} else {
					// Все хорошо, обновляем задачу в очереди
					$queueObj->updateEntry($task['id'],
						array('status' => Sync_Base::STATUS_SUCCESS, 'queue_id' => $response_array['queue_id']));
					
					if ($task['type'] == Sync_Base::TYPE_ADD) {
						// Если была добавлена новая запись, то нужно присвоить глобальный id добавленной записи
						$dataObj->updateEntry($task['parents_id'],
							array('global_sync_id' => $response_array['queue_id']));

						// А также всем записем в очереди, которые относятся к этой записи
						$queueObj->updateEntry(null,
							array('global_sync_id' => $response_array['queue_id']),
							array(
								'provider' => $task['provider'],
								'status' => Sync_Base::STATUS_NEW,
								'parents_id' => $task['parents_id']
							)
						);
						
						// Тут стоит прерваться чтобы в следующий заход были обновленные данные
						return;
					}
				}
			} catch (Zend_Http_Client_Exception $e) {
				// Не удалось соединиться с сервером, прекращаем попытки до следующего захода
				return;
			}
		}
	}
	
	/**
	 * Возвращает все доступные языковые записи.
	 * 
	 * @param int $id
	 * @param List_interface $dpObj
	 * @return array
	 */
	private function _getDataProviderData($id, List_interface $dpObj) {
		static $langs = array();
		
		if (empty($langs)) {
			$langs = $this->_DP("List_Languages")->getList();
		}
		
		$data = array();
		$entry_save = false;
		foreach ($langs['data'] as $lang) {
			$entry = $dpObj->getEntry($id, array('languages_id' => $lang['id']));
			if ($entry != false) {
				$entry_save = $entry;
				$data['entry'][$lang['id']] = $entry;
			}
		}
		
		// Запись не найдена, прекращаем
		if ($entry_save == false) {
			return $data;
		}
		
		$data['global_ids'] = array();
		// Проходим по всем элементам и получаем глобальные id для всех внешних ключей записи
		foreach ($entry_save as $key => $el) {
			if (is_null($el)) {
				continue;
			}
			
			$tmpObj = Sync_Base::getDpByKeyName($key, $dpObj);
			if (is_null($tmpObj)) {
				continue;
			}
			
			$tmpEntry = $tmpObj->getEntry($el);
			$data['global_ids'][$key][$el] = $tmpEntry['global_sync_id'];
		}
		
		// Получаем дополнительные данные для различных провайдеров данных
		switch (get_class($dpObj)) {
			case "List_Joined_Ep_Organizers":
				$data['socorgs'] = $dpObj->getSelectedSocOrgsList($id);
				if (!empty($data['socorgs'])) {
					$tmpObj = self::_DP("List_Joined_Ep_Socorgs");
					$tmpObj->addColsToList(array("global_sync_id"));
					$tmpList = $tmpObj->getList(null, null, array("id" => $data['socorgs']));
					foreach ($tmpList['data'] as $el) {
						$data['global_ids']['social_organizations_id'][$el['id']] = $el['global_sync_id'];
					}
				}
				break;
			case "List_Joined_Ep_Brands":
				$data['categories'] = $dpObj->getSelectedCategoriesList($id);
				if (!empty($data['categories'])) {
					$tmpObj = self::_DP("List_Joined_Ep_BrandsCategories");
					$tmpObj->addColsToList(array("global_sync_id"));
					$tmpList = $tmpObj->getList(null, null, array("id" => $data['categories']));
					foreach ($tmpList['data'] as $el) {
						$data['global_ids']['brands_categories_id'][$el['id']] = $el['global_sync_id'];
					}
				}
				
				$data['subcategories'] = $dpObj->getSelectedSubCategoriesList($id);
				if (!empty($data['subcategories'])) {
					$tmpObj = self::_DP("List_Joined_Ep_BrandsSubCategories");
					$tmpObj->addColsToList(array("global_sync_id"));
					$tmpList = $tmpObj->getList(null, null, array("id" => $data['subcategories']));
					foreach ($tmpList['data'] as $el) {
						$data['global_ids']['brands_subcategories_id'][$el['id']] = $el['global_sync_id'];
					}
				}
				break;
			case "List_Joined_Ep_Events":
				foreach ($data['entry'] as $lang_id => $entry) {
					if ($entry['logo'] == 1) {
						$data['entry'][$lang_id]['logo_url'] = SYNC_LOCAL_DATA_PATH .
							"/images/events/logo/" . $lang_id . "/" . $entry['id'] . ".jpg";
					}
				}
				break;
			case "List_Joined_Ep_Expocenters":
				foreach ($data['entry'] as $lang_id => $entry) {
					if ($entry['logo'] == 1) {
						$data['entry'][$lang_id]['logo_url'] = SYNC_LOCAL_DATA_PATH .
							"/images/expocenters/logo/" . $lang_id . "/" . $entry['id'] . ".jpg";
					}
					if (!empty($entry['image_map'])) {
						$data['entry'][$lang_id]['image_map_url'] = SYNC_LOCAL_DATA_PATH .
							"/images/expocenters/" . $entry['id'] . "/" . $entry['image_map'];
					}
					if (!empty($entry['image_plan'])) {
						$data['entry'][$lang_id]['image_plan_url'] = SYNC_LOCAL_DATA_PATH .
							"/images/expocenters/" . $entry['id'] . "/" . $entry['image_plan'];
					}
					if (!empty($entry['image_view'])) {
						$data['entry'][$lang_id]['image_view_url'] = SYNC_LOCAL_DATA_PATH .
							"/images/expocenters/" . $entry['id'] . "/" . $entry['image_view'];
					}
				}
				break;
			case "List_Joined_Ep_Socorgs":
				foreach ($data['entry'] as $lang_id => $entry) {
					if ($entry['logo'] == 1) {
						$data['entry'][$lang_id]['logo_url'] = SYNC_LOCAL_DATA_PATH .
							"/images/social_organizations/logo/" . $lang_id . "/" . $entry['id'] . ".jpg";
					}
				}
				break;
			case "List_EventsGalleries":
				$entry = $data['entry'];
				$entry = array_pop($entry);
				$data['image'] = SYNC_LOCAL_DATA_PATH .
					"/images/events/" . $entry['events_id'] . "/gallery/" . $entry['id'] . ".jpg";
				$data['image_tb'] = SYNC_LOCAL_DATA_PATH .
					"/images/events/" . $entry['events_id'] . "/gallery/" . $entry['id'] . "_tb.jpg";
				break;
		}
		
		return $data;
	}
}