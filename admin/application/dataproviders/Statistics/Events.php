<?php
class Statistics_Events extends DataProviderAbstract {

	public function getEventsComissionList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array()) {
		$result = array('page' => 1, 'pages' => 1, 'rows' => 0, 'data' => array());

		$select = self::$_db->select();

		$select->from(array("bg" => "statistic.proc_booking_general"),
			array("date_cut", "hits", "hosts", "comission", "cnt"));
		$select->join("booking.affiliates", "affiliates.id = bg.affiliates_id",
			array('events_id'));
		$select->join("ExpoPromoter_Opt.events", "events.id = affiliates.events_id",
			array('date_from', 'date_to'));
		$select->join("ExpoPromoter_Opt.brands", "brands.id = events.brands_id", array());
		$select->join("ExpoPromoter_Opt.brands_data", "brands.id = brands_data.id",
			array("brand_name" => 'name'));

		$select->where("brands_data.languages_id = ?", $extraParams['languages_id']);

		if (isset($extraParams['organizers_id'])) {
			$select->where("brands.organizers_id = ?", $extraParams['organizers_id']);
		}
		if (isset($extraParams['brands_id'])) {
			$select->where("brands.id = ?", $extraParams['brands_id']);
		}
		if (isset($extraParams['events_id'])) {
			$select->where("events.id = ?", $extraParams['events_id']);
		}

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

			$result['page'] = $page;
			$result['pages'] = $number_of_pages;
			$result['rows'] = $number_of_rows;

			$select->limitPage($page, $results_num);
		}
		
		$select->order("bg.date_cut DESC");
		$select->order("bg.affiliates_id DESC");
		
		try {
			$result['data'] = self::$_db->fetchAll($select);
		} catch (Zend_Db_Exception $e) {
			echo $e->getMessage();
			Zend_Debug::dump($select->__toString());
		}

		return $result;
	}

}