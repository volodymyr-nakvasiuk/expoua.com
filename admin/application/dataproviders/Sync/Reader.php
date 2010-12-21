<?php
Zend_Loader::loadClass("Sync_Base", PATH_DATAPROVIDERS);
Zend_Loader::loadClass("Zend_Http_Client");

class Sync_Reader extends DataProviderAbstract {
	
	/**
	 * Связывается с сервером-арбитром и читает с него очередь, применяя изменения локально
	 */
	public function readFromRefereeServer() {
		$client = new Zend_Http_Client(SYNC_SERVER_PATH . "?type=read",
			array(
				'maxredirects' => 0,
				'timeout' => 10, // Ждем ответа максимум 10 секунд, потом рвем соединение
				'keepalive' => true,
			)
		);
		$client->setMethod(Zend_Http_Client::POST);
		
		$data = array(
				'last_queue_id' => self::_DP("List_OptionsConstants")->getValueByCode("SYNC_LAST_QUEUE_ID")
			);

		$client->setRawData(serialize($data), "application/octet-stream");
		
		try {
			$response = $client->request()->getBody();
			$response_array = unserialize($response);
			
			if ($response_array == false) {
				// Десеарилизация не удалась, прекращаем
				return;
			}
		} catch (Zend_Http_Client_Exception $e) {
			// Не удалось соединиться с сервером, прекращаем попытки до следующего захода
			return;
		}
		
		// Получаем список доступных языков в системе чтобы добавлять только существующие языковые записи
		$langs = $this->_DP("List_Languages")->getList();
		$langs = $langs['data'];

		foreach ($response_array as $element) {
			
			try {
				// Обязательно нужно создавать новый экзепляр класса чтобы каждый раз при вставке был
				// чистый объект, была проблема с getLastInsertId();
				$dbObjName = $element['provider'];
				Zend_Loader::loadClass($dbObjName, PATH_DATAPROVIDERS);
				$dataObj = new $dbObjName();
			} catch (Zend_Exception $e) {
				// Неизвестный тип в очереди, пропускаем
				continue;
			}
			
			// Предотвращаем распространения изменений по другим серверам
			$dataObj->allowSync = false;
			
			$element['data'] = unserialize($element['data']);

			$task = $element['data']['task'];
			$task['queue_id'] = $element['id'];
			
			if (empty($task['global_sync_id'])) {
				$global_sync_id = $element['id'];
			} else {
				$global_sync_id = $task['global_sync_id'];
			}

			// Получаем текующую запись в БД, язык не указываем, нам он не важен, берем первую запись
			$entry = $dataObj->getList(1, null, array('global_sync_id' => $global_sync_id));
			$entry = $entry['data'];
			if (!empty($entry)) {
				$entry = array_pop($entry);
			}
			
			$res = 0;
			switch ($task['type']) {
				case Sync_Base::TYPE_ADD:
					$id = null;
					foreach ($element['data']['data']['entry'] as $data_entry_key => $data_entry) {
						if (!array_key_exists('languages_id', $data_entry)) {
							// У List_EventsGalleries нет languages_id, он там не нужен
							$data_entry['languages_id'] = $data_entry_key;
						}
						if (!isset($langs[$data_entry['languages_id']])) {
							// Пропускаем если пришли данные для языка, которого нет локально
							continue;
						}
						
						$this->_substituteDataIds($data_entry, $element['data']['data']['global_ids'], $dataObj);
						
						if (is_null($id)) {
							unset($data_entry['id']);
							$data_entry['global_sync_id'] = $global_sync_id;
							
							$res = $dataObj->insertEntry($data_entry);
							if ($res != 1) {
								// Вставка не удалась, прерываемся и пишем в лог
								$this->_addToLog($task, Sync_Base::STATUS_FAIL, "Unable to insert data");
								continue 3;
							}
							$id = $dataObj->getLastInsertId();
						} else {
							$data_entry['id'] = $id;
							$dataObj->insertLanguageData($data_entry);
						}
					}
					$res1 = $this->_updateExtraData($id, $element['data']['data'], $dataObj);
					$res = max($res, $res1);
					break;
				case Sync_Base::TYPE_UPDATE:
					if (empty($entry)) {
						// Не найдена запись для обновления, пропускаем
						$this->_addToLog($task, Sync_Base::STATUS_FAIL, "Entry not found");
						continue 2;
					}
					foreach ($element['data']['data']['entry'] as $data_entry) {
						// Обновляем запись для каждого языка отдельно
						unset($data_entry['id']);
						
						$this->_substituteDataIds($data_entry, $element['data']['data']['global_ids'], $dataObj);
						
						$res1 = $dataObj->updateEntry($entry['id'], $data_entry,
							array('languages_id' => $data_entry['languages_id']));
							
						$res = max($res, $res1);
					}
					$res1 = $this->_updateExtraData($entry['id'], $element['data']['data'], $dataObj);
					$res = max($res, $res1);
					break;
				case Sync_Base::TYPE_DELETE:
					if (empty($entry)) {
						// Не найдена запись для удаления, пропускаем
						$this->_addToLog($task, Sync_Base::STATUS_FAIL, "Entry not found");
						continue 2;
					}
					$res = $dataObj->deleteEntry(array($entry['id']));
					break;
				default:
					// Неизвестное действие, пропускаем
					$this->_addToLog($task, Sync_Base::STATUS_FAIL, "Unknown type");
					continue 2;
			}
			
			if ($res == 1) {
				$this->_addToLog($task, Sync_Base::STATUS_SUCCESS);
			} else {
				// Изменения не были применены, пишем в лог об этом
				$this->_addToLog($task, Sync_Base::STATUS_FAIL, "Changes were not applied");
			}
		}
		
		if (!empty($task)) {
			self::_DP("List_OptionsConstants")->updateEntry(null,
				array('value' => $task['queue_id']),
				array('code' => 'SYNC_LAST_QUEUE_ID')
			);
		}
	}
	
	/**
	 * Производит обновление связанных данных
	 * Например категорий бренда
	 * Дополнительно загружаются внешние файлы, такие как логотипы, карты проезда и тд
	 * 
	 * @param int $id
	 * @param array $data
	 * @param List_Abstract $dpObj
	 * @return int
	 */
	private function _updateExtraData($id, Array $data, List_Abstract $dpObj) {
		$res = 0;
		switch (get_class($dpObj)) {
			case "List_Joined_Ep_Organizers":
				$socorgs = array();
				foreach ($data['socorgs'] as $so) {
					$global_sync_id = $data['global_ids']['social_organizations_id'][$so];
					if (!empty($global_sync_id)) {
						$newid = $this->_substituteId("social_organizations_id", $global_sync_id, $dpObj);
						$socorgs[] = $newid;
					}
				}
				$res = $dpObj->updateSocOrgs($id, $socorgs);
				break;
			case "List_Joined_Ep_Brands":
				$categories = array();
				foreach ($data['categories'] as $cat) {
					$global_sync_id = $data['global_ids']['brands_categories_id'][$cat];
					if (!empty($global_sync_id)) {
						$newid = $this->_substituteId("brands_categories_id", $global_sync_id, $dpObj);
						$categories[] = $newid;
					}
				}
				$res1 = $dpObj->updateCategories($id, $categories);
				
				$categories = array();
				foreach ($data['subcategories'] as $cat) {
					$global_sync_id = $data['global_ids']['brands_subcategories_id'][$cat];
					if (!empty($global_sync_id)) {
						$newid = $this->_substituteId("brands_subcategories_id", $global_sync_id, $dpObj);
						$categories[] = $newid;
					}
				}
				$res2 = $dpObj->updateSubCategories($id, $categories);
				
				$res = max($res1, $res2);
				break;
			case "List_Joined_Ep_Events":
				foreach ($data['entry'] as $lang_id => $entry) {
					if ($entry['logo'] == 1 && !empty($entry['logo_url'])) {
						$this->_downloadRemoteFile($entry['logo_url'],
							PATH_FRONTEND_DATA_IMAGES . "/events/logo/" . $lang_id . "/" . $id . ".jpg");
					}
				}
				break;
			case "List_Joined_Ep_Expocenters":
				foreach ($data['entry'] as $lang_id => $entry) {
					if ($entry['logo'] == 1 && !empty($entry['logo_url'])) {
						$this->_downloadRemoteFile($entry['logo_url'],
							PATH_FRONTEND_DATA_IMAGES . "/expocenters/logo/" . $lang_id . "/" . $id . ".jpg");
					}
					if (!empty($entry['image_map']) && !empty($entry['image_map_url'])) {
						$this->_downloadRemoteFile($entry['image_map_url'],
							PATH_FRONTEND_DATA_IMAGES . "/expocenters/" . $id . "/" . $entry['image_map']);
					}
					if (!empty($entry['image_plan']) && !empty($entry['image_plan_url'])) {
						$this->_downloadRemoteFile($entry['image_plan_url'],
							PATH_FRONTEND_DATA_IMAGES . "/expocenters/" . $id . "/" . $entry['image_plan']);
					}
					if (!empty($entry['image_view']) && !empty($entry['image_view_url'])) {
						$this->_downloadRemoteFile($entry['image_view_url'],
							PATH_FRONTEND_DATA_IMAGES . "/expocenters/" . $id . "/" . $entry['image_view']);
					}
				}
				break;
			case "List_Joined_Ep_Socorgs":
				foreach ($data['entry'] as $lang_id => $entry) {
					if ($entry['logo'] == 1 && !empty($entry['logo_url'])) {
						$this->_downloadRemoteFile($entry['logo_url'],
							PATH_FRONTEND_DATA_IMAGES . "/social_organizations/logo/" . $lang_id . "/" . $id . ".jpg");
					}
				}
				break;
			case "List_EventsGalleries":
				$data_entry = $data['entry'];
				$data_entry = array_shift($data_entry);
				$this->_downloadRemoteFile($data['image'],
					PATH_FRONTEND_DATA_IMAGES . "/events/" . $data_entry['events_id'] . "/gallery/" . $data_entry['id'] . ".jpg");
				$this->_downloadRemoteFile($data['image_tb'],
					PATH_FRONTEND_DATA_IMAGES . "/events/" . $data_entry['events_id'] . "/gallery/" . $data_entry['id'] . "_tb.jpg");
				break;
		}
		
		if ($res > 0) {
			$res = 1;
		}
		
		return $res;
	}
	
	/**
	 * Производит замену переданных удаленных id внешних ключей локальными
	 * Связь производится через массив global_ids
	 * 
	 * @param Array $element
	 * @param List_Abstract $dpObj
	 */
	private function _substituteDataIds(Array &$entry, Array $global_ids, List_Abstract $dpObj) {
		foreach ($global_ids as $key => $values) {
			if (empty($entry[$key])) {
				// Соответствующий ключ в массиве с записью не найден или null
				continue;
			}
			
			foreach ($values as $global_sync_id) {
				$entry[$key] = $this->_substituteId($key, $global_sync_id, $dpObj);
			}
		}
	}
	
	/**
	 * Производит поиск и замену значения внешнего ключа локальным значением по его global_sync_id
	 * Если значение найти не удалось, возвращает null
	 * 
	 * @param string $key
	 * @param int $global_sync_id
	 * @param array $global_ids
	 * @param List_Abstract $dpObj
	 * @return int|null
	 */
	private function _substituteId($key, $global_sync_id, List_Abstract $dpObj) {
		$tmpObj = Sync_Base::getDpByKeyName($key, $dpObj);
		if (is_null($tmpObj)) {
			return null;
		}
		
		$tmpObj->addColsToList(array('global_sync_id'));
		$list = $tmpObj->getList(null, null, array('global_sync_id' => $global_sync_id));
		
		if (empty($list['data'])) {
			return null;
		} else {
			$list = array_pop($list['data']);
			return $list['id'];
		}
	}
	
	/**
	 * Скачивает внешний файл и сохраняет его локально
	 * 
	 * @param string $url_from
	 * @param string $file_to
	 * @return boolean
	 */
	private function _downloadRemoteFile($url_from, $file_to) {
		// Создаем иерархию каталогов если она не существует.
		@mkdir(dirname($file_to), 0777, true);
		
		$fh = fopen($file_to, "w");
		if (!$fh) {
			return false;
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url_from);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
		curl_setopt($ch, CURLOPT_FILE, $fh);

		$res = curl_exec($ch);

		fclose($fh);
		curl_close($ch);
		
		return $res;
	}
	
	/**
	 * Добавляет в log запись-событие, произошедшее при получении данных с СА
	 * 
	 * @param string $provider
	 * @param string $status
	 * @param int $queue_id
	 * @param string $debug
	 */
	private function _addToLog(Array &$task, $status, $debug ='') {
		self::$_db->insert("ExpoPromoter_Opt.sync_log",
			array(
				'queue_id' => $task['queue_id'],
				'type' => $task['type'],
				'status' => $status,
				'provider' => $task['provider'],
				'debug' => $debug
			)
		);
	}
}