<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Banners_PblPublishersStat extends List_Abstract {

	protected $_allowed_cols = array('date_show', 'publishers_id', 'total', 'clicks', 'shows');

	protected $_db_table = "ExpoPromoter_banners.pbl_stat_publishers";

	protected $_select_list_cols = array('date_show', 'publishers_id', 'total', 'clicks', 'shows');
	
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
    } else {
      $result = array('page' => 1, 'pages' => 1, 'rows' => 0);
    }

    $result['sort_by'] = null;

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
      $result['data'] = self::$_db->fetchAll($select);
    } catch (Exception $e) {
      echo $e->getMessage();
    }

    return $result;
  }

	protected function _SqlAddsList(Zend_Db_Select &$select) {
	  $lang = Zend_Registry::get('language_id');

		$select->join(
		  "ExpoPromoter_Opt.sites",
		  "pbl_stat_publishers.publishers_id = sites.id",
		  array()
		);

		$select->join(
		  "ExpoPromoter_Opt.sites_data",
		  "ExpoPromoter_Opt.sites_data.id = sites.id AND ExpoPromoter_Opt.sites_data.languages_id = '$lang'",
		  array('publisher_name' => 'name')
		);

		$select->join(
		  "ExpoPromoter_Opt.partners",
		  "sites.partners_id = partners.id",
		  array('partner_name' => 'name')
		);
	}


	protected function _SqlAddsEntry(Zend_Db_Select &$select) {
		$this->_SqlAddsList($select);
	}


	protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
		parent::_SqlAddsWhere($select, $params);

		if (isset($params['partners_id'])) {
			if ($params['partners_id'] instanceof Zend_Db_Expr) {
				$select->where("sites.partners_id ?", $params['partners_id']);
			} else {
				$select->where("sites.partners_id = ?", $params['partners_id']);
			}
		}

		//Фильтр по дате
		if (isset($params['date_start'])) {
			$select->where("pbl_stat_publishers.date_show ?", $params['date_start']);
		}
		if (isset($params['date_end'])) {
			$select->where("pbl_stat_publishers.date_show ?", $params['date_end']);
		}

		//echo "<!-- " . $select->__toString() . " -->";
	}


	public function getStatList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array()) {
    $result = array();

	  $lang = Zend_Registry::get('language_id');

    $select = self::$_db->select();

    $select->from($this->_db_table, array(
    	'publishers_id',
      'total_sum'    => new Zend_Db_Expr('SUM(pbl_stat_publishers.total)'),
      'total_clicks' => new Zend_Db_Expr('SUM(pbl_stat_publishers.clicks)'),
      'total_shows'  => new Zend_Db_Expr('SUM(pbl_stat_publishers.shows)'),
      'ctr'          => new Zend_Db_Expr('SUM(pbl_stat_publishers.clicks)/SUM(pbl_stat_publishers.shows)*100'),
    ));
		$select->joinLeft("ExpoPromoter_Opt.sites_data", "ExpoPromoter_Opt.sites_data.id = pbl_stat_publishers.publishers_id AND ExpoPromoter_Opt.sites_data.languages_id = '$lang'", array('publisher_name' => 'name'));

		$select->group('pbl_stat_publishers.publishers_id');

    //В случае наличия дополнительных ограничивающих параметров, учитываем их
    if (sizeof($extraParams) >0) {
    	if (isset($extraParams['partners_id'])) {
    		$select->join("ExpoPromoter_Opt.sites", "pbl_stat_publishers.publishers_id = sites.id", array());
    		$select->group('sites.id');
    	}
      $this->_SqlAddsWhere($select, $extraParams);
    }

    //Если нужен пейджинг, вводим ограничения
    if (!is_null($results_num) && !is_null($page)) {
      $page = intval($page);
      $results_num = intval($results_num);

      //Определяемся с общим количеством записей в таблице
      $select_count = clone $select;

      $select_count->reset(Zend_Db_Select::COLUMNS);
      $select_count->reset(Zend_Db_Select::GROUP);
      $select_count->from('', new Zend_Db_Expr("COUNT(DISTINCT pbl_stat_publishers.publishers_id)"));

      //Zend_Debug::dump($select_count->__toString());

      $number_of_rows = self::$_db->fetchOne($select_count);
      $number_of_pages = ceil($number_of_rows / $results_num);

      if ($page > $number_of_pages) {
        $page = $number_of_pages;
      }

      $result = array('page' => $page, 'pages' => $number_of_pages, 'rows' => $number_of_rows);

      $select->limitPage($page, $results_num);
    } else {
      $result = array('page' => 1, 'pages' => 1, 'rows' => 0);
    }

    $result['sort_by'] = null;

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

    //Zend_Debug::dump($select->__toString());
    //mail("eugene.ivashin@expopromogroup.com", "sql", $select->__toString());

    try {
      $result['data'] = self::$_db->fetchAll($select);
    } catch (Exception $e) {
      echo $e->getMessage();
    }

    return $result;
  }

}