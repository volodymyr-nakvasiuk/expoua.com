<?php
class Statistics_Sites extends DataProviderAbstract {
	
	/**
	 * Возвращает обобщенную статистику по заработку сайтов
	 * Включает в себя:
	 *  - показы, клики, комиссия по баннерам expoadvert
	 *  - хиты, хосты, комиссия по букингу
	 * Возможные параметры фильтрации:
	 * sites_id - id сайта
	 * partners_id - id партнера
	 * date_from - дата с
	 * date_to - дата до
	 * Сортировать можно по date [ASC|DESC]
	 * 
	 * @param $results_num
	 * @param $page
	 * @param $extraParams
	 * @param $sortBy
	 * @return array
	 */
	public function getSitesComissionList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array()) {
		
		$result = array('page' => 1, 'pages' => 1, 'rows' => 0, 'data' => array());
		
		//Букинг-часть union
		$select_booking_un = self::$_db->select();
		$select_booking_un->from("statistic.proc_booking_general", array("date_cut"));
		$select_booking_un->join("booking.affiliates", "proc_booking_general.affiliates_id = affiliates.id", array("sites_id"));
		$select_booking_un->where("proc_booking_general.comission > 0");
		if (isset($extraParams['sites_id'])) {
			$select_booking_un->where("sites_id = ?", $extraParams['sites_id']);
		}
		if (isset($extraParams['partners_id'])) {
			$select_booking_un->join("ExpoPromoter_Opt.sites", "sites.id = affiliates.sites_id", array());
			$select_booking_un->where("sites.partners_id = ?", $extraParams['partners_id']);
		}
		if (isset($extraParams['date_from'])) {
			$select_booking_un->where("date_cut >= ?", $extraParams['date_from']);
		}
		if (isset($extraParams['date_to'])) {
			$select_booking_un->where("date_cut <= ?", $extraParams['date_to']);
		}
		
		//Баннерная часть union
		$select_banners_un = self::$_db->select();
		$select_banners_un->from("ExpoPromoter_banners.pbl_stat_publishers",
			array("date_show", "publishers_id"));
		if (isset($extraParams['sites_id'])) {
			$select_banners_un->where("publishers_id = ?", $extraParams['sites_id']);
		}
		if (isset($extraParams['partners_id'])) {
			$select_banners_un->join("ExpoPromoter_Opt.sites", "sites.id = pbl_stat_publishers.publishers_id", array());
			$select_banners_un->where("sites.partners_id = ?", $extraParams['partners_id']);
		}
		if (isset($extraParams['date_from'])) {
			$select_banners_un->where("date_show >= ?", $extraParams['date_from']);
		}
		if (isset($extraParams['date_to'])) {
			$select_banners_un->where("date_show <= ?", $extraParams['date_to']);
		}
		
		//Часть JS-календаря
		$select_js_un = self::$_db->select();
		$select_js_un->from("statistic.proc_jscalendar_hits",
			array("date_cut", "sites_id"));
		if (isset($extraParams['sites_id'])) {
			$select_js_un->where("sites_id = ?", $extraParams['sites_id']);
		}
		if (isset($extraParams['partners_id'])) {
			$select_js_un->join("ExpoPromoter_Opt.sites", "sites.id = proc_jscalendar_hits.sites_id", array());
			$select_js_un->where("sites.partners_id = ?", $extraParams['partners_id']);
		}
		if (isset($extraParams['date_from'])) {
			$select_js_un->where("date_cut >= ?", $extraParams['date_from']);
		}
		if (isset($extraParams['date_to'])) {
			$select_js_un->where("date_cut <= ?", $extraParams['date_to']);
		}
		
		//Часть "баерки" из таблицы transactions (будет тормозить когда данных будет много)
		$select_buyer_un = self::$_db->select();
		$select_buyer_un->from("ExpoPromoter_Opt.transactions",
			array(
				'date_cut' => new Zend_Db_Expr("DATE(`date`)"),
				'id_site'
			));
		$select_buyer_un->where("`type` = 'payment'");
		$select_buyer_un->where("source = 'buyers'");
		if (isset($extraParams['sites_id'])) {
			$select_buyer_un->where("id_site = ?", $extraParams['sites_id']);
		}
		if (isset($extraParams['partners_id'])) {
			$select_buyer_un->join("ExpoPromoter_Opt.sites",
				"sites.id = transactions.id_site", array());
			$select_buyer_un->where("sites.partners_id = ?", $extraParams['partners_id']);
		}
		if (isset($extraParams['date_from'])) {
			$select_buyer_un->where("`date` >= ?", $extraParams['date_from']);
		}
		if (isset($extraParams['date_to'])) {
			$select_buyer_un->where("`date` <= ?", $extraParams['date_to']);
		}

		//Строим Union
		$query_union = "(" . $select_booking_un->__toString()
			. ") UNION (" .
			$select_banners_un->__toString()
			. ") UNION (" .
			$select_js_un->__toString()
			. ") UNION (" .
			$select_buyer_un->__toString() . ") ";

		if (isset($sortBy['date']) && ($sortBy['date'] == "ASC" || $sortBy['date'] == "DESC")) {
			$query_union .= " ORDER BY date_cut " . $sortBy['date'];
		}

		//Ограничение выборки
		if (!is_null($results_num) && !is_null($page)) {
			$page = intval($page);
			$results_num = intval($results_num);
			
			$select_count = self::$_db->select();
			$select_count->from(
				array("sq" => new Zend_Db_Expr("(" . $query_union . ")")),
				array(new Zend_Db_Expr("COUNT(*)"))
			);
			
			$result['rows'] = self::$_db->fetchOne($select_count);
			$result['pages'] = ceil($result['rows'] / $results_num);
			$result['page'] = $page;
			
			$query_union .= " LIMIT " . ($results_num*($page - 1)) . ", " . intval($results_num);
		} else if (!is_null($results_num)) {
			$query_union .= " LIMIT " . intval($results_num);
		}
		
		//Присоединяем данные к выборке дат
		$select = self::$_db->select();
		$select->from(array("sq" => new Zend_Db_Expr("(" . $query_union . ")")));
		
		$select->join(
			"ExpoPromoter_Opt.sites_data",
			'sites_data.id = sq.sites_id',
			array("site_name" => 'name')
		);
		
		$select->joinLeft(
			"booking.affiliates",
			"sq.sites_id = affiliates.sites_id",
			array()
		);
		
		$select->joinLeft(
			array("bg" => "statistic.proc_booking_general"),
			"bg.date_cut = sq.date_cut AND bg.affiliates_id = affiliates.id",
			array(
				"booking_hits" => new Zend_Db_Expr("IFNULL(bg.hits, 0)"),
				"booking_hosts" => new Zend_Db_Expr("IFNULL(bg.hosts, 0)"),
				"booking_comission" => new Zend_Db_Expr("IFNULL(bg.comission, 0)")
			)
		);
		$select->joinLeft(
			array("p" => "ExpoPromoter_banners.pbl_stat_publishers"),
			"p.date_show = sq.date_cut AND p.publishers_id = sq.sites_id",
			array(
				"shows" => new Zend_Db_Expr("IFNULL(p.shows, 0)"),
				"clicks" => new Zend_Db_Expr("IFNULL(p.clicks, 0)"),
				"banners_comission" => new Zend_Db_Expr("IFNULL(p.total, 0)")
			)
		);
		$select->joinLeft(
			array("js" => "statistic.proc_jscalendar_hits"),
			"js.date_cut = sq.date_cut AND js.sites_id = sq.sites_id",
			array(
				"js_hits" => "hits",
				"js_hits_total" => "hits_total",
				"js_comission" => "comission"
			)
		);
		$select->from("", array("buyers_summ" => new Zend_Db_Expr(
			"(SELECT SUM(summ) FROM ExpoPromoter_Opt.transactions
WHERE `date` >= sq.date_cut AND `date` <= DATE_ADD(sq.date_cut, INTERVAL 1 DAY) AND
`type` = 'payment' AND source = 'buyers' AND id_site = sq.sites_id)"
		)));

		if (isset($extraParams['languages_id'])) {
			$language_id = $extraParams['languages_id'];
		} else {
			$language_id = Zend_Registry::get('language_id');
		}
		
		$select->where("sites_data.languages_id = ?", $language_id);
		//echo $select->__toString();
		try {
			$result['data'] = self::$_db->fetchAll($select);
		} catch (Zend_Db_Exception $e) {
			echo $e->getMessage();
			Zend_Debug::dump($select->__toString());
		}
		return $result;
	}
	
}