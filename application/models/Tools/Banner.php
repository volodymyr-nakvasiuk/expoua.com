<?php
class Tools_Banner{
	protected $_type = false;
	protected $_module = false;
	protected $_categoryId = false;
	protected $_limit = false;

	protected $_db = false;
	protected $_params = false;
	protected $_data = false;

	function __construct($type=null, $module=null, $categoryId=null, $limit=5){
		$this->_type = $type;
		$this->_module = $module;
		$this->_categoryId = $categoryId;
		$this->_limit = $limit;
		$databases = Zend_Registry::get('databases');
		$this->_db = $databases['banners'];

		/*
bplace=pr_line1
id=EP_BP5
module=Mainpage
partner=2
lang=ru
country=52
flash=1
r=0.6580156432654425

$bplace = $_GET['bplace'];
$module = (isset($_GET['module']) ? $_GET['module']:null);
$catId = (isset($_GET['category']) ? $_GET['category']:null);

$lang_id = langid;
$publisher_id = partnerid;
$site_id = siteid;
$countryId = countryid;

$ids = getIds($lang, $bplace, $module, $site_id);
$data = getBannerData($ids, $catId, $publisher_id);

$data['url_clicker'] = PATH_CLICKER . $data['id'] . "_" . $publisher_id . "_" . $ids['lang_id'] . "_v2";

switch ($data['media']) {
	case "text":
		echo getTextBanner($data);
		break;
	case "image":
		echo getImageBanner($data);
		break;
	case "pline":
		require_once("Zend/Json/Encoder.php");

		$event = getPLineBanner($data, $lang, $ids['lang_id']);
		$json = Zend_Json_Encoder::encode(array('exhibition' => $event));

		if (!empty($_GET['callBack'])) {
			echo $_GET['callBack'] . "(" . $json . ");";
		} else {
			echo 'EPBANNER.show(' . $json . ', ' . $data['id'] . ', "' . $_GET['id'] . '");';
		}
		break;
	default:
		exit;
		//unknown banner type
}

//Статистика
updateStat($data, $ids, $publisher_id);

		 */
	}

	public function getData(){
		if (!$this->_data) $this->_setData();
		return $this->_data;
	}

	public function getParams(){
		if (!$this->_params) $this->_setParams();
		return $this->_params;
	}

	function _setParams() {
		$query = "
			SELECT
				(".
				($this->_type?"
					SELECT
						id
					FROM
						places
					WHERE
						code='" . $this->_type . "'
					":"NULL").
				") AS places_id,
				(
					SELECT
						id
					FROM
						modules
					WHERE
						code='" . $this->_module . "'
				) AS modules_id
			;
		";

		$res = $this->_db->fetchRow($query);
		$this->_params = array(
			'lang_id' => DEFAULT_LANG_ID,
			'publisher_id'=>PUBLISHER_ID,
			'places_id'=>$res['places_id'],
			'modules_id'=>$res['modules_id'],
			'partners_id' => PARTNER_ID,
			'countries_id' => COUNTRY_ID,
			'site_id' => SITE_ID,
		);
	}

	protected function _setData() {
		$this->getParams();
		$this->_data = $this->_type?$this->_getBannerData():$this->_getPblBannerData();
	}

	protected function _getBannerData() {
		$query = "
			SELECT
				p.id,
				t.media,
				t.height,
				t.width,
				b.pline_events_id,
				b.text_content,
				b.file_alt,
				b.file_name,
				p.url
			FROM (
				SELECT
					id,
					plans_id,
					(priority+FLOOR(RAND()*1000)) AS ord
				FROM
					mview_banners
				WHERE
					languages_id=" . intval($this->_params['lang_id']) . "
					AND places_id=" . intval($this->_params['places_id']) . "
					AND (modules_id IS NULL" . ($this->_params['modules_id'] ? " OR modules_id=" . intval($this->_params['modules_id']):"") . ")" . "
					AND (categories_id IS NULL " . ($this->_categoryId ? "OR categories_id=" . intval($this->_categoryId):"") . ")" . "
					AND (publishers_id IS NULL OR publishers_id=" . intval($this->_params['publisher_id']) . ")
				ORDER BY
					ord ASC
				LIMIT 1
			) AS sq
			JOIN
				banners AS b ON (sq.id = b.id)
			JOIN
				plans AS p ON (sq.plans_id = p.id)
			JOIN
				types AS t ON (b.types_id = t.id)
			;
		";

		return $this->_db->fetchRow($query);
	}

	protected function _getPblBannerData() {
		$query = "
			(
				SELECT
					b.id,
					t.media,
					t.height,
					t.width,
					b.text_content,
					b.file_alt,
					b.file_name,
					'normal' AS bt
				FROM (
					SELECT
						banners_id
					FROM
						pbl_mview_banners
					WHERE
						languages_id=" . intval($this->_params['lang_id']) ."
				 		AND (
				 			modules_id IS NULL" . ($this->_params['modules_id'] ? " OR modules_id=" . intval($this->_params['modules_id']):"") . "
				 		)
				 		AND (
				 			categories_id IS NULL" . ($this->_categoryId ? " OR categories_id=" . intval($this->_categoryId):"") . "
				 		)
						AND (
							countries_id IS NULL" . ($this->_params['countries_id'] ? " OR countries_id=" . intval($this->_params['countries_id']):"") . "
						)
					ORDER BY (
							IF(categories_id IS NULL,price,price*1000)
						) DESC
					LIMIT
						" . $this->_limit . "
				) AS sq
				JOIN
					pbl_banners AS b ON (sq.banners_id = b.id)
				JOIN
					types AS t ON (b.types_id = t.id)
			)
		UNION
			(
				SELECT
					b.id,
					t.media,
					t.height,
					t.width,
					b.text_content,
					b.file_alt,
					b.file_name,
					'gag' AS bt
				FROM
					pbl_banners_gags AS b
				JOIN
					types AS t ON (t.id = b.types_id)
				WHERE
					b.active=1
					AND b.languages_id='" . intval($this->_params['lang_id']) . "'
					AND b.partners_id='" . intval($this->_params['partners_id']) . "'
				LIMIT
					" . $this->_limit . "
			)
		LIMIT
			" . $this->_limit ."
		;";
		return $this->_db->fetchAll($query);
	}

	public function updateStat() {
		$this->getData();
		if ($this->_type){
			$this->_updateBannerStat();
		}
		else {
			$this->_updatePblBannerStat();
		}
	}

	protected function _updateBannerStat() {
		$query = "
			UPDATE
				stat_shows
			SET
				shows=shows+1
			WHERE
				date_show=CURDATE()
				AND plans_id=" . $this->_data['id'] . "
				AND langs_id=" . $this->_params['lang_id'] . "
				AND modules_id" . ($this->_params['modules_id'] ? "=" . $this->_params['modules_id']:" IS NULL") . "
				AND publishers_id = " . intval($this->_params['publisher_id']);

		$stmt = $this->_db->query($query);

		if ($stmt->rowCount() == 0) {
			$query = "
				INSERT INTO
					stat_shows (
						date_show,
						plans_id,
						langs_id,
						modules_id,
						publishers_id
					)
				VALUES (
					CURDATE(),
					'" . $this->_data['id'] . "',
					'" . $this->_params['lang_id'] . "', " .
					($this->_params['modules_id'] ? "'" . $this->_params['modules_id']."'":"NULL") . ",
					'" . intval($this->_params['publisher_id']) . "'
				)";

			$this->_db->query($query);
		}
	}

	protected function _updatePblBannerStat() {
		foreach ($this->_data as $data){
			$query = "
				UPDATE
					pbl_stat_shows
				SET
					shows=shows+1
				WHERE
					date_show = CURDATE()
					AND banners_id = " . $data['id'] . "
					AND modules_id" . ($this->_params['modules_id'] ? "=" . $this->_params['modules_id']:" IS NULL") . "
					AND sites_id = " . intval($this->_params['site_id'])."
				;
			";
			$stmt = $this->_db->query($query);

			if ($stmt->rowCount() == 0) {
				$query = "
					INSERT INTO
						pbl_stat_shows (
							date_show,
							banners_id,
							modules_id,
							sites_id
						)
						VALUES (
							CURDATE(),
							'" . $data['id'] . "',
							" .
						($this->_params['modules_id'] ? "'" . $this->_params['modules_id']."'":"NULL") . ",
							'" . intval($this->_params['site_id']) . "'
						)
					;
				";
				$this->_db->query($query);
			}
		}
	}


/*------------------ NOT EDITED -----------------------*/
	function getPLineBanner($data, $lang, $lang_id) {
		$query = "
			SELECT
				e.id,
				bd.name,
				e.period_date_from AS startDate,
				e.period_date_to AS endDate,
				lcntd.name AS countryName,
				e.countryId,
				lcd.name AS cityName,
				e.cityId, e.news,
				e.companies,
				e.videos,
				e.ads,
				IF (
					ec.show_list_logo=1,
					CONCAT(
						'http://ws.expopromoter.com/file/event_logo.php?id=',
						e.id,
						'&lang=',
						bd.languages_id
					),
					NULL
				) AS logo
			FROM
				ExpoPromoter_MViews.events_" . $lang . " AS e
			JOIN
				ExpoPromoter_Opt.events_common AS ec ON (e.id = ec.id)
			JOIN
				ExpoPromoter_Opt.brands_data AS bd ON (e.brandId = bd.id)
			JOIN
				ExpoPromoter_Opt.location_cities_data AS lcd ON (e.cityId = lcd.id)
			JOIN
				ExpoPromoter_Opt.location_countries_data AS lcntd ON (e.countryId = lcntd.id)
			WHERE
				bd.languages_id=lcd.languages_id
				AND bd.languages_id=lcntd.languages_id
				AND bd.languages_id=" . intval($lang_id) . "
				AND e.id=" . intval($data['pline_events_id']);

		$event = DB::queryRow($query);
		if (empty($event)) {
			exit();
		}

		$event['url'] = $data['url_clicker'];

		return $event;
	}

}
