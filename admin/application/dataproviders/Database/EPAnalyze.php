<?PHP

class Database_EPAnalyze extends DataproviderAbstract {

	public function getBrandsWithoutFutureEvents($year = null, $country = null, $organizer = null) {
		$query = "SELECT bd.id, bd.name, od.name AS organizer_name, lcd.name AS country_name
		FROM ExpoPromoter_Opt.brands_data AS bd
		JOIN ExpoPromoter_Opt.brands AS b ON (b.id=bd.id)
		JOIN ExpoPromoter_Opt.organizers AS o ON (b.organizers_id=o.id)
		JOIN ExpoPromoter_Opt.organizers_data AS od ON (o.id=od.id)
		JOIN ExpoPromoter_Opt.location_cities AS lc ON (o.cities_id=lc.id)
		JOIN ExpoPromoter_Opt.location_countries_data AS lcd ON (lc.countries_id = lcd.id)
		WHERE b.dead=0 AND bd.languages_id=1 AND bd.languages_id=lcd.languages_id AND od.languages_id=bd.languages_id " .
		(!is_null($country) ? " AND lc.countries_id = '" . intval($country) . "'":"") .
		(!is_null($organizer) ? " AND od.name = '" . $organizer . "'":"") .
		" AND bd.id NOT IN (SELECT brands_id FROM ExpoPromoter_Opt.events WHERE " .
		(!is_null($year) ? "date_from>'" . intval($year) . "-12-31'":"date_from>NOW()") . ")
		ORDER BY bd.name ASC";

		//echo $query;

		return self::$_db->fetchAll($query);
	}

	public function getBrandsWithoutEvents() {
		$query = "SELECT bd.id, bd.name, od.name AS organizer_name FROM ExpoPromoter_Opt.brands AS b
		JOIN ExpoPromoter_Opt.brands_data AS bd ON (b.id=bd.id)
		JOIN ExpoPromoter_Opt.organizers_data AS od ON (b.organizers_id=od.id)
		WHERE bd.languages_id=1 AND od.languages_id=1 AND b.id NOT IN (SELECT brands_id FROM ExpoPromoter_Opt.events)
		ORDER BY bd.name";

		return self::$_db->fetchAll($query);
	}

	public function getOrgsWithoutBrands() {
		$query = "SELECT od.id, o.active, od.name, lcd.name AS city_name, lcntd.name AS country_name
		FROM ExpoPromoter_Opt.organizers AS o
		JOIN ExpoPromoter_Opt.organizers_data AS od ON (o.id=od.id)
		JOIN ExpoPromoter_Opt.location_cities AS lc ON (o.cities_id = lc.id)
		JOIN ExpoPromoter_Opt.location_cities_data AS lcd ON (lc.id = lcd.id)
		JOIN ExpoPromoter_Opt.location_countries_data AS lcntd ON (lcntd.id = lc.countries_id)
		WHERE od.languages_id=1 AND od.languages_id=lcd.languages_id AND od.languages_id=lcntd.languages_id
		AND o.id NOT IN (SELECT organizers_id FROM ExpoPromoter_Opt.brands)
		ORDER BY od.name";

		return self::$_db->fetchAll($query);
	}

	public function getLongEventsList() {
		$query = "SELECT id, active, date_from, date_to, DATEDIFF(date_to, date_from) AS days
		FROM ExpoPromoter_Opt.events
		WHERE date_from >= CURDATE() AND date_to >= CURDATE()
		HAVING days>10 OR date_from='0000-00-00' OR date_to='0000-00-00'";

		return self::$_db->fetchAll($query);
	}

	public function getBrandsSamenamesList($language_id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_Opt.brands", array());
		$select->join("ExpoPromoter_Opt.brands_data", "brands.id = brands_data.id",
			array('name', new Zend_Db_Expr("COUNT(brands_data.name) AS cnt")));

		$select->where("brands_data.languages_id = ?", $language_id)
			->group(array("brands.organizers_id", "brands_data.name"))
			->having("cnt>1")
			->order("brands_data.name ASC");

		return self::$_db->fetchAll($select);
	}

	public function getExpocentersSamenamesList($language_id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_Opt.expocenters", array());
		$select->join("ExpoPromoter_Opt.expocenters_data", "expocenters.id = expocenters_data.id",
			array('name', new Zend_Db_Expr("COUNT(expocenters_data.name) AS cnt")));

		$select->where("expocenters_data.languages_id = ?", $language_id)
			->group(array("expocenters.cities_id", "expocenters_data.name"))
			->having("cnt>1")
			->order("expocenters_data.name ASC");

		return self::$_db->fetchAll($select);
	}

	public function getOrganizersSamenamesList($language_id) {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_Opt.organizers", array());
		$select->join("ExpoPromoter_Opt.organizers_data", "organizers.id = organizers_data.id",
			array('name', new Zend_Db_Expr("COUNT(organizers_data.name) AS cnt")));

		$select->where("organizers_data.languages_id = ?", $language_id)
			->group(array("organizers.cities_id", "organizers_data.name"))
			->having("cnt>1")
			->order("organizers_data.name ASC");

		return self::$_db->fetchAll($select);
	}


	public function getSameEventsList() {
		$select = self::$_db->select();

		$select->from("ExpoPromoter_Opt.events", array("brands_id", "date_from", "date_to", new Zend_Db_Expr("COUNT( brands_id ) AS cnt")));
		$select->group(array("brands_id", "cities_id", "date_from"))
			->having("cnt>1")
			->order("cnt DESC");

		//Zend_Debug::dump($select->__toString());

		return self::$_db->fetchAll($select);
	}

	public function getOrgsWithoutEmails($language_id) {
		$select = self::$_db->select();
		$select->from("ExpoPromoter_Opt.organizers_data", array('id', 'name'));
		$select->where("email = '' AND languages_id = ?", $language_id);
		$select->order("name ASC");

		return self::$_db->fetchAll($select);
	}
  
  public function getDatabaseStat($language_id) {
    $query = "SELECT
      (SELECT COUNT(*) FROM ExpoPromoter_Opt.events WHERE active=1) AS events,
      (SELECT COUNT(*) FROM ExpoPromoter_Opt.news_data WHERE active=1 AND languages_id=1) AS news,
      (SELECT COUNT(*) FROM ExpoPromoter_Opt.expocenters WHERE active=1) AS expocenters,
      (SELECT COUNT(*) FROM ExpoPromoter_Opt.service_companies WHERE active=1) AS service_companies,
      (SELECT COUNT(*) FROM ExpoPromoter_Opt.events_ads WHERE active=1 AND type='participant') AS ads_participant,
      (SELECT COUNT(*) FROM ExpoPromoter_Opt.events_ads WHERE active=1 AND type='tour') AS ads_tour,
      (SELECT COUNT(*) FROM ExpoPromoter_Opt.events_ads WHERE active=1 AND type='ad') AS ads_ad,
      (SELECT COUNT(*) FROM ExpoPromoter_Opt.companies_active WHERE active=1 AND languages_id='" . $language_id . "') AS companies,
      (SELECT COUNT(*) FROM ExpoPromoter_Opt.companies_services_active WHERE active=1 AND languages_id='" . $language_id . "') AS companies_services,
      (SELECT COUNT(*) FROM ExpoPromoter_Opt.companies_news_active WHERE active=1 AND languages_id='" . $language_id . "') AS companies_news";

    $res = self::$_db->fetchAll($query);

    return $res[0];
  }
}