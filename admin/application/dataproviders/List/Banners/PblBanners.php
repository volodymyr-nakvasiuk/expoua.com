<?PHP
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

class List_Banners_PblBanners extends List_Abstract {

	protected $_allowed_cols = array('id', 'active', 'deleted', 'languages_id', 'users_id', 'types_id', 'name', 'url', 'price', 'limit_daily', 'date_from', 'date_to', 'text_content', 'file_alt', 'file_name', 'date_update');

	protected $_db_table = "ExpoPromoter_banners.pbl_banners";

	protected $_select_list_cols = array('id', 'active', 'users_id', 'deleted', 'types_id', 'name', 'price', 'limit_daily', 'date_from', 'date_to', 'file_name', 'date_update');

	protected $_prepare_cols = array(
    'limit_daily' => array('num', null),
    // 'price'       => array('num', "0.10"),
    'date_from'   => array('date', null),
    'date_to'     => array('date', null)
  );

  protected $_sort_col = array('id' => 'DESC');

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

    //При установке этого флага производится проверка баннера на наличие показов
    if (isset($extraParams['_check_banners_presence'])) {
    	$select_m = self::$_db->select();
    	$select_m->from(array("sq" => $select),
    		array("*",
    			'shows' => new Zend_Db_Expr("(SELECT COUNT(*) FROM ExpoPromoter_banners.pbl_stat_shows WHERE banners_id=sq.id AND date_show>=DATE_SUB(CURDATE(), INTERVAL 1 DAY))")));
    	$select = $select_m;
    }

    try {
      $result['data'] = self::$_db->fetchAssoc($select);
    } catch (Exception $e) {
      echo $e->getMessage();
    }

    return $result;
  }

	public function getSelectedModules($id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_banners.pbl_banners_to_modules", array('modules_id', 'modules_id'));
		$select->where("banners_id = ?", $id);

		return self::$_db->fetchPairs($select);
	}

	public function getSelectedCountries($id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_banners.pbl_banners_to_countries", array('countries_id', 'countries_id'));
		$select->where("banners_id = ?", $id);

		return self::$_db->fetchPairs($select);
	}

	public function getSelectedCategories($id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_banners.pbl_banners_to_categories", array('categories_id', 'categories_id'));
		$select->where("banners_id = ?", $id);

		return self::$_db->fetchPairs($select);
	}


	public function updateSelectedModules($id, Array $modules) {
		$where = self::$_db->quoteInto("banners_id = ?", $id);
		self::$_db->delete("ExpoPromoter_banners.pbl_banners_to_modules", $where);

		$result = 0;
		foreach ($modules as $el) {
			$row = array('banners_id' => $id, 'modules_id' => intval($el));
			$result += self::$_db->insert("ExpoPromoter_banners.pbl_banners_to_modules", $row);
		}

		return $result;
	}

	public function updateSelectedCountries($id, Array $modules) {
		$where = self::$_db->quoteInto("banners_id = ?", $id);
		self::$_db->delete("ExpoPromoter_banners.pbl_banners_to_countries", $where);

		$result = 0;
		foreach ($modules as $el) {
			$row = array('banners_id' => $id, 'countries_id' => intval($el));
			$result += self::$_db->insert("ExpoPromoter_banners.pbl_banners_to_countries", $row);
		}

		return $result;
	}

	public function updateSelectedCategories($id, Array $cats) {
		$where = self::$_db->quoteInto("banners_id = ?", $id);
		self::$_db->delete("ExpoPromoter_banners.pbl_banners_to_categories", $where);

		$result = 0;
		foreach ($cats as $el) {
			$row = array('banners_id' => $id, 'categories_id' => intval($el));
			$result += self::$_db->insert("ExpoPromoter_banners.pbl_banners_to_categories", $row);
		}

		return $result;
	}

	/**
	 * Полное обновление материализованного представления для всех баннеров
	 * Не рекомендую часто запускать его
	 *
	 */
	public function updateMView() {
		$query = "TRUNCATE ExpoPromoter_banners.pbl_mview_banners";
		self::$_db->query($query);

		$query = "INSERT INTO ExpoPromoter_banners.pbl_mview_banners (`banners_id`, `languages_id`, `modules_id`, `categories_id`, `countries_id`, `price`)
SELECT b.id AS banners_id, l.id AS languages_id, b2m.modules_id, b2ca.categories_id, b2co.countries_id, b.price*100 AS price
FROM ExpoPromoter_banners.pbl_banners AS b
JOIN ExpoPromoter_banners.pbl_users AS u ON (b.users_id = u.id)
LEFT JOIN ExpoPromoter_banners.pbl_banners_to_categories AS b2ca ON (b2ca.banners_id=b.id)
LEFT JOIN ExpoPromoter_banners.pbl_banners_to_countries AS b2co ON (b2co.banners_id=b.id)
LEFT JOIN ExpoPromoter_banners.pbl_banners_to_modules AS b2m ON (b2m.banners_id=b.id)
JOIN ExpoPromoter_Opt.languages AS l ON (b.languages_id = l.id)
WHERE l.active=1 AND b.active=1 AND b.deleted=0 AND u.active=1 AND u.deposit>0 AND b.price>=0.1 AND (b.date_from IS NULL OR b.date_from<=CURDATE()) AND (b.date_to IS NULL OR b.date_to>=CURDATE()) AND (b.limit_daily IS NULL OR b.limit_daily>(SELECT IF(SUM(price) IS NULL, 0, SUM(price)) FROM ExpoPromoter_banners.pbl_stat_clicks WHERE date_click>CURDATE() AND banners_id = b.id))";

		self::$_db->query($query);
	}

	/**
	 * Обновления материализованного представления для одного указанного баннера.
	 * Работает очень быстро, рекомендуется запускать после обновления/удаления/добавления баннера
	 *
	 * @param int $id
	 */
	public function updateBannerMView($id) {
		$query = "DELETE FROM ExpoPromoter_banners.pbl_mview_banners WHERE banners_id = ?";
		self::$_db->query($query, array($id));

		$query = "INSERT INTO ExpoPromoter_banners.pbl_mview_banners (`banners_id`, `languages_id`, `modules_id`, `categories_id`, `countries_id`, `price`)
SELECT b.id AS banners_id, l.id AS languages_id, b2m.modules_id, b2ca.categories_id, b2co.countries_id, b.price*100 AS price
FROM ExpoPromoter_banners.pbl_banners AS b
JOIN ExpoPromoter_banners.pbl_users AS u ON (b.users_id = u.id)
LEFT JOIN ExpoPromoter_banners.pbl_banners_to_categories AS b2ca ON (b2ca.banners_id=b.id)
LEFT JOIN ExpoPromoter_banners.pbl_banners_to_countries AS b2co ON (b2co.banners_id=b.id)
LEFT JOIN ExpoPromoter_banners.pbl_banners_to_modules AS b2m ON (b2m.banners_id=b.id)
JOIN ExpoPromoter_Opt.languages AS l ON (b.languages_id = l.id)
WHERE l.active=1 AND b.active=1 AND b.deleted=0 AND u.active=1 AND u.deposit>0 AND b.price>=0.1 AND (b.date_from IS NULL OR b.date_from<=CURDATE()) AND (b.date_to IS NULL OR b.date_to>=CURDATE()) AND (b.limit_daily IS NULL OR b.limit_daily>(SELECT IF(SUM(price) IS NULL, 0, SUM(price)) FROM ExpoPromoter_banners.pbl_stat_clicks WHERE date_click>CURDATE() AND banners_id = b.id)) AND b.id = ?";

		self::$_db->query($query, array($id));
	}

	public function getBannersCompetition($languages_id, $countries_id, $modules_id = null, $categories_id = null) {
		$select_sq = "SELECT banners_id, price/100 AS price FROM ExpoPromoter_banners.pbl_mview_banners WHERE ";

		$select_sq .= "languages_id = '" . $languages_id . "' AND countries_id = '" . $countries_id . "' ";

		if (is_numeric($modules_id)) {
			$select_sq .= "AND (modules_id IS NULL OR modules_id = '" . $modules_id . "') ";
		} else {
			//$select_sq->where("modules_id IS NULL");
		}

		/* Категории имеют исключительный приоритет, поэтому этот блок заменяем
		Новый алгоритм рассчета стоимости по категориям учитывает только беннера с установленной
		категорией
		if (is_numeric($categories_id)) {
			$select_sq->where("categories_id IS NULL OR categories_id = ?", $categories_id);
		} else {
			$select_sq->where("categories_id IS NULL");
		}*/

		if (is_numeric($categories_id)) {
			$select_sq .= "AND categories_id = '" . $categories_id . "' ";
		} else {
			//Если категория не выбрана, то исключаем баннеры, установленные в категориях, поскольку они имеют нивысший приоритет
			$select_sq .= "AND categories_id IS NULL ";
		}

		$select_sq .= " UNION SELECT 0 AS banners_id, 0 AS price ";
		$select_sq .= " ORDER BY `price` DESC LIMIT 5";

		$select = self::$_db->select();
		$select->from(
			array('sq' => new Zend_Db_Expr("(" . $select_sq . ")")),
			array('price_max' => new Zend_Db_Expr('MAX(price)'), 'price_min' => new Zend_Db_Expr('MIN(price)') ) );

		if (is_numeric($categories_id)) {
			$select->joinLeft("ExpoPromoter_Opt.brands_categories_data", "brands_categories_data.id = " . $categories_id . " AND brands_categories_data.languages_id = '" . Zend_Registry::get('language_id') . "'", array('categories_id' => 'id', 'category_name' => 'name'));
		}

		//$select->group("sq.categories_id");

		//echo "<!-- " . $select->__toString() . " -->";

		return self::$_db->fetchRow($select);
	}


  public function getTestBanners ($moduleId, $catId, $countryId, $limit = 5) {
    $langId = Zend_Registry::get('language_id');
        
    $query = "SELECT b.id, t.media, t.height, t.width, b.text_content, b.file_alt, b.file_name, b.price, 'normal' AS bt
  FROM (SELECT banners_id FROM ExpoPromoter_banners.pbl_mview_banners
  WHERE languages_id=" . $langId .
  " AND (modules_id IS NULL" . (!empty($moduleId) ? " OR modules_id=" . intval($moduleId):"") . ")" .
  " AND (categories_id IS NULL" . (!empty($catId) ? " OR categories_id=" . intval($catId):"") . ")" .
  " AND (countries_id IS NULL" . (!empty($countryId) ? " OR countries_id=" . intval($countryId):"") . ")
  ORDER BY (IF(categories_id IS NULL,price,price*1000)) DESC
  LIMIT " . $limit . ") AS sq
  JOIN ExpoPromoter_banners.pbl_banners AS b ON (sq.banners_id = b.id)
  JOIN ExpoPromoter_banners.types AS t ON (b.types_id = t.id)
  LIMIT " . $limit;
  
    return self::$_db->fetchAll($query);
  }


	protected function _SqlAddsList(Zend_Db_Select &$select) {
    $lang = Zend_Registry::get("language_id");

		$select->join("ExpoPromoter_banners.pbl_users", "pbl_users.id = pbl_banners.users_id", array('user_login' => 'login', 'user_password' => 'passwd'));
		$select->join("ExpoPromoter_Opt.languages", "pbl_banners.languages_id = languages.id", array('language_name' => 'name'));
		$select->joinLeft("ExpoPromoter_banners.pbl_banners_to_countries", "pbl_banners.id = pbl_banners_to_countries.banners_id", array());
		$select->joinLeft("ExpoPromoter_Opt.location_countries_data", "pbl_banners_to_countries.countries_id = location_countries_data.id AND location_countries_data.languages_id = '$lang'", array('country_name' => 'name'));
  }


  protected function _SqlAddsWhere(Zend_Db_Select &$select, Array $params) {
    parent::_SqlAddsWhere($select, $params);

    if (isset($params['countries_id'])) {
      $select->where("pbl_banners_to_countries.countries_id ?", $params['countries_id']);

      //echo $select->__toString();
    }
  }

}