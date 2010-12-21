<?PHP

Zend_Loader::loadClass("List_Interface", PATH_DATAPROVIDERS);

/**
 * Абстрактный класс провайдера данных для списков.
 * Идеально подходит когда выборка производится толко из одной таблицы.
 *
 */
abstract class List_Abstract extends DataProviderAbstract implements List_interface {

	/**
	 * Список допустимых названий столбцов для таблицы.
	 * Должен переопределяться для каждого подкласса, навледующего DataProvider_Abstract
	 *
	 * @var Array
	 */
	protected $_allowed_cols = array();

	/**
	 * Перечень столбцов, которые нужно извлечь в списке
	 *
	 * @var array
	 */
	protected $_select_list_cols = array();

	/**
	 * Имя столбца для сортировки результатов по-умолчанию в списке
	 * Представляет из себя массив, содержащий в качестве ключа название столбца для сорировки, а в качестве значения - направление сортировки ASC или DESC
	 * Сортировка применяется в том направлении как указаны в массиве
	 *
	 * @var Array
	 */
	protected $_sort_col = array();

	/**
	 * Массив содержащий правила подготовки данных.
	 * В качестве ключа укаывается название столбца, в качестве значения - массив из двух элементов:
	 *  - в первом элементе указывается тип: num, text, date
	 *  - во втором, значение, принимаемое в случае пустого либо неверного значения
	 * Если второй элемент массива не установлен, данные удаляются в случае неверного значения
	 *
	 * @var array
	 */
	protected $_prepare_cols = array();

	/**
	 * Основная таблица БД для данного провайдера данных
	 *
	 * @var string
	 */
	protected $_db_table = null;
	
	/**
	 * Определяет должны ли изменения в провайдере данных автоматически синхронизироваться
	 * с другими серверами.
	 * 
	 * @var boolean
	 */
	public $allowSync = true;

	/**
	 * Возвращает список, удовлетворяющий условиям, переданным в качестве 3-его параметра.
	 * Ограничивающие параметры могут быть одного из типов:
	 *  - null: производится вставка IS NULL
	 *  - array: производится вставка IN (...)
	 *  - other: обычное сравнение =
	 * Список можно ограничить по количеству возвращаемых элементов первым параметром $results_num,
	 * а также указать страницу выбоки вторым параметром $page.
	 * По-умолчанию список сортируется по колонке, указанной в $this->_sort_col
	 * Для переопределения сортировки, в четвертом параметре можно передать массив, ключами которого
	 * выступают названия колоники, а значением направление сортировки ASC или DESC
	 *
	 * @param int $results_num
	 * @param int $page
	 * @param array $extraParams
	 * @param array $sortBy
	 * @return array
	 */
	public function getList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array()) {
		$result = array();

		$select = self::$_db->select();

		$select->from($this->_db_table, $this->_select_list_cols);
		$this->_SqlAddsList($select);

		//В случае наличия дополнительных ограничивающих параметров, учитываем их
		if (sizeof($extraParams) >0) {
			$this->_SqlAddsWhere($select, $extraParams);
		}

		//Если нужен пейджинг, вводим ограничения
		if (!is_null($results_num) && !is_null($page)) {
			$page = intval($page);
			$results_num = intval($results_num);

			//Определяемся с общим количеством записей в таблице
			$select_count = clone $select;

			$select_count->reset(Zend_Db_Select::COLUMNS);
			$select_count->from('', new Zend_Db_Expr("COUNT(*)"));

			//Zend_Debug::dump($select_count->__toString());

			$number_of_rows = self::$_db->fetchOne($select_count);
			$number_of_pages = ceil($number_of_rows / $results_num);

			if ($page > $number_of_pages) {
				$page = $number_of_pages;
			}

			$result = array('page' => $page, 'pages' => $number_of_pages, 'rows' => $number_of_rows);

			$select->limitPage($page, $results_num);
		} else if (!is_null($results_num)) {
			$select->limit($results_num);
		} else {
			$result = array('page' => 1, 'pages' => 1, 'rows' => 0);
		}

		$result['sort_by'] = null;

		$this->_SqlAttachFields($select);

		//Если нужно, сортируем результат
		if (sizeof($sortBy)>0) {
			$result['sort_by'] = $this->_SqlAddsSort($select, $sortBy);
		}

		if (is_null($result['sort_by']) && sizeof($this->_sort_col) > 0) {
			$result['sort_by'][key($this->_sort_col)] = current($this->_sort_col);
			foreach ($this->_sort_col as $key => $el) {
				$select->order($this->_db_table . "." . $key . " " . $el);
			}
		}

		$this->_SqlAddsDebug($select);

		try {
			$result['data'] = self::$_db->fetchAssoc($select);
		} catch (Exception $e) {
			echo $e->getMessage();
		}

		return $result;
	}

	/**
	 * Добавляем новую запись в таблицу
	 * Возвращает фактическое количество добавленных столбцов
	 *
	 * @param array $data
	 * @return int
	 */
	public function insertEntry(Array $data) {
		$this->_prepareDataArray($data);
		try {
			$res = self::$_db->insert($this->_db_table, $data);
		} catch (Exception $e) {
			echo "Insert into " . $this->_db_table . ": " . $e->getMessage() . "\n";
			$res = -1;
		}

		return $res;
	}

	/**
	 * Обновление данных в таблице.
	 * Обновление может производиться по первичному ключу - $id и/или по дополнительным параметрам $extraParams
	 * в массиве $data передаются данные для обновления
	 * функция возвращает фактическое количество обновленных столбцов
	 *
	 * @param id $id
	 * @param array $data
	 * @param array $extraParams
	 * @return int
	 */
	public function updateEntry($id = null, Array $data, Array $extraParams = array()) {
		$this->_prepareDataArray($data);

		if (sizeof($data) == 0) {
			return 0;
		}

		$where = array();

		if (!is_null($id)) {
			$where[] = self::$_db->quoteInto("id = ?", intval($id));
		}

		if (sizeof($extraParams) > 0) {
			$this->_prepareDataArray($extraParams);
			foreach ($extraParams as $key => $el) {
				$where[] = self::$_db->quoteInto("`" . $key . "` = ?", $el);
			}
		}

		try {
			$res = self::$_db->update($this->_db_table, $data, $where);
		} catch (Exception $e) {
			echo "Update " . $id . " in " . $this->_db_table . ": " . $e->getMessage() . "\n";
			$res = -1;
		}

		return $res;
	}

	/**
	 * Удаление столбца/ов в таблице
	 * Удаление может быть произведено как по первичному ключу $id, так и при помощи ограничивающиъ параметров массива $extraParams
	 * Функция возвращает количество фактически удаленных столбцов
	 *
	 * @param int $id
	 * @param array $extraParams
	 * @return int
	 */
	public function deleteEntry(Array $id = array(), Array $extraParams = array()) {

		$where = array();

		if (is_numeric($id)) {
			$where[] = self::$_db->quoteInto("id = ?", intval($id));
		} elseif (is_array($id) && sizeof($id) > 0) {
			$where[] = self::$_db->quoteInto("id IN (?)", $id);
		}

		if (sizeof($extraParams) > 0) {
			$this->_prepareDataArray($extraParams);
			foreach ($extraParams as $key => $el) {
				$where[] = self::$_db->quoteInto($key . " = ?", $el);
			}
		}

		//Пердотвращаем удаление всего сразу
		if (sizeof($where) == 0) {
			return 0;
		}

		try {
			$result = self::$_db->delete($this->_db_table, $where);
		} catch (Exception $e) {
			echo "Delete " . $id[0] . " from " . $this->_db_table . ": " . $e->getMessage() . "\n";
			$result = -1;
		}

		return $result;
	}

	/**
	 * Возвращает указанную запись, id которой указан первым параметром
	 * Во втором параметре можно указать набор ограничивающих параметров.
	 * Удобно передавать в нем id текущего пользователя, таким образом, обеспечив защиту от доступа одних пользователей к данным других
	 *
	 * @param int $id
	 * @param array $extraParams
	 * @return array
	 */
	public function getEntry($id, Array $extraParams = array()) {
		$select = self::$_db->select();

		$select->from($this->_db_table, $this->_allowed_cols)
			->where($this->_db_table . ".id = ?", intval($id));

		if (sizeof($extraParams) > 0) {
			$this->_SqlAddsWhere($select, $extraParams);
		}

		$this->_SqlAddsEntry($select);

		return self::$_db->fetchRow($select);
	}

	/**
	 * Возвращает Id записи последней операции добавления
	 *
	 * @return int
	 */
	public function getLastInsertId() {
		return self::$_db->lastInsertId();
	}

	/**
	 * Функция добавляет указанный массив колонок к списку, возвращаемом функцией getList
	 *
	 * @param array $cols
	 */
	public function addColsToList(Array $cols) {
		foreach ($cols as $c) {
			if (in_array($c, $this->_allowed_cols)) {
				$this->_select_list_cols[] = $c;
			}
		}
	}

	/**
	 * Функция предназначена для подготовки входных данных перед вставкой в таблицу БД или при подготовке ограничивающих параметров.
	 * Должна вызываться в начале каждой функции, которая производит изменение данных в таблице.
	 * Она сравнивает ключи переданного массива с разрешенными полями и удаляет все несовпадения.
	 * Кроме того, производится проверка значений по правилам, описанным в $this->_prepare_cols
	 *
	 * @param array $data
	 */
	protected function _prepareDataArray(Array &$data) {
		foreach ($data as $key => $el) {
			if (!in_array($key, $this->_allowed_cols)) {

				unset($data[$key]);

			} elseif (isset($this->_prepare_cols[$key]) && !$el instanceof Zend_Db_Expr) {

				$valid = true;
				switch ($this->_prepare_cols[$key][0]) {
					case "num":
						if (!is_numeric($el)) {
							$valid = false;
						}
						break;
					case "text":
						if (empty($el)) {
							$valid = false;
						}
						break;
					case "date":
						if (empty($el) || strtotime($el) === false) {
							$valid = false;
						}
						break;
				}

				if (!$valid) {
					//Использовать isset нельзя, поскольку он возвращает false на null-значениях
					if (!array_key_exists(1, $this->_prepare_cols[$key])) {
						unset($data[$key]);
					} else {
						$data[$key] = $this->_prepare_cols[$key][1];
					}
				}

			}
		}
	}

	/**
	 * Используется для добавления нужных Join-ов в каждом конкретном случае в списках.
	 * Если они требуются, достаточно переопределить эту функцию в классе, который наследует данный
	 *
	 * @param Zend_Db_Select $select
	 */
	protected function _SqlAddsList(Zend_Db_Select &$select) {
		//Ничего не делаем в методе по-умлочанию
	}


	/**
	 * Используется для добавления информационных Join-ов в каждом конкретном случае в списках.
	 * Добавление происходит ПОСЛЕ вычисления количества строк, следовательно, применяется только
	 * к записям на одной странице!
	 *
	 * @param Zend_Db_Select $select
	 */
	protected function _SqlAttachFields(Zend_Db_Select &$select) {
		//Ничего не делаем в методе по-умлочанию
	}


	/**
	 * Используется для добавления нужных Join-ов в каждом конкретном случае.
	 * Если они требуются, достаточно переопределить эту функцию в классе, который наследует данный
	 *
	 * @param Zend_Db_Select $select
	 */
	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		//Ничего не делаем в методе по-умлочанию
	}

	/**
	 * Обработка дополнительных ограничивающих параметров для сложных связей
	 *
	 * @param Zend_Db_Select $select
	 * @param array $params
	 */
	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		$this->_prepareDataArray($params);

		foreach ($params as $key => $el) {
			$column = $this->_db_table . "." . $key;
			if (is_null($el)) {
				$select->where($column . " IS NULL");
			} else if (is_array($el)) {
				$select->where($column . self::$_db->quoteInto(" IN (?)", $el));
			} else if ($el instanceof Zend_Db_Expr) {
				$select->where($column . " ?", $el);
			} else {
				$select->where($column . " = ?", $el);
			}
		}
	}

	/**
	 * Функция добавления сортировок по столбцам
	 *
	 * @param Zend_Db_Select $select
	 * @param array $sort
	 */
	protected function _SqlAddsSort(Zend_Db_Select &$select, Array $sort) {
		$this->_prepareDataArray($sort);

		$result = array();

		if (sizeof($sort)>0) {
			reset($sort);
			$result[key($sort)] = current($sort);
			foreach ($sort as $key => $el) {
				$select->order($this->_db_table . "." . $key . " " . $el);
			}
		} else {
			return null;
		}

		//Zend_Debug::dump($select->__toString());

		return $result;
	}


	/**
	 * Функция отладки, точка входа после формирования SQL выражения и до его выполения
	 *
	 * @param Zend_Db_Select $select
	 */
	protected function _SqlAddsDebug(Zend_Db_Select &$select) {
	}
	
	/**
	 * Вспомогательнная функция для добавления задачи в очередь
	 * Вызывается в функциях insertEntry, deleteEntry, addEntry провайдеров данных,
	 * которые требуется синхронизировать
	 * Функция принимает решение о синхронизации с фильтром по стране используя ключ sync_countries_id
	 * Если такой ключ отсутсвует, но присутсвует sync_cities_id, с его помощью определяется страна
	 * автоматически
	 * 
	 * @param $table
	 * @param $type
	 * @param $data
	 * @return int
	 */
	protected function _AddSyncQueueTask($type, Array &$data) {
		if ($this->allowSync == false) {
			return 0;
		}

		$baseQueueObj = self::_DP("Sync_Base");
		
		if (isset($data['sync_cities_id'])) {
			$city = self::_DP("List_Joined_Ep_Cities")->getEntry($data['sync_cities_id']);
			if (!empty($city)) {
				$data['sync_countries_id'] = $city['countries_id'];
			}
		}
		if (isset($data['sync_countries_id']) && Sync_Base::isCountryOwned($data['sync_countries_id']) == false) {
			return 0;
		}
		
		if (!isset($data['global_sync_id'])) {
			$data['global_sync_id'] = null;
		}
		
		return $baseQueueObj->addToQueue(get_class($this), $type, $data['id'], $data['languages_id'], $data['global_sync_id']);
	}

}