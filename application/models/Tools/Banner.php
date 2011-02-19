<?php
class Tools_Banner{
	protected $_types = false;
	protected $_module = false;
	protected $_noModule = true;

	protected $_categoryId = false;
	protected $_limit = false;

	protected $_db = false;
	protected $_params = false;
	protected $_data = false;

	static function getClickerUrl($id, $data=array(), $type=0){ //type [0 - simple banner, 1 - advert banner]
		if ($type){
			$subData = $data['bt']=='normal'?'v2pbl':'v2pblg';
			$publisherId = SITE_ID;
		}
		else {
			$publisherId = PUBLISHER_ID;
			$subData = 'v2';
			if ($data){
				$subData .= '_dummy?go='.HOST_NAME.'/'.DEFAULT_LANG_CODE."/event/card/".$data['id']."-".Tools_View::getUrlAlias($data['brands_name'], true)."/";
			}
		}
		return PATH_CLICKER. $id . "_" . $publisherId . "_" . DEFAULT_LANG_ID. "_".$subData;
	}

	function __construct($types=null, $module=null, $noModule=false, $categoryId=null, $limit=5){
		$this->_types = $types?(is_array($types)?$types:array($types)):null;
		$this->_module = $module;
		$this->_noModule = $noModule;
		$this->_categoryId = $categoryId;
		$this->_limit = $limit;
		$databases = Zend_Registry::get('databases');
		$this->_db = $databases['banners'];
	}

	public function getData(){
		if (!$this->_data) $this->_setData();
		return $this->_data;
	}

	public function getParams(){
		if (!$this->_params) $this->_setParams();
		return $this->_params;
	}

	protected function _setParams() {
		$places = array();
		if ($this->_types){
			//$types = array();
			//for($i=1; $i<=$this->_limit; $i++) $types[] = "'".$this->_type.$i."'";
			$query = "
					SELECT
						id AS places_id
					FROM
						places
					WHERE
						code IN ('" . implode("', '",$this->_types) . "')
				;
			";
			$res = $this->_db->fetchAll($query);
			foreach ($res as $data){
				$places[] = $data['places_id'];
			}
		}

		$query = "
				SELECT
					id AS modules_id
				FROM
					".($this->_types?"":"pbl_")."modules
				WHERE
					code='" . $this->_module . "'
			;
		";
		$res = $this->_db->fetchRow($query);

		$this->_params = array(
			'lang_id' => DEFAULT_LANG_ID,
			'publisher_id'=>PUBLISHER_ID,
			'places_id'=>$places,
			'modules_id'=>$res['modules_id'],
			'partners_id' => PARTNER_ID,
			'countries_id' => COUNTRY_ID,
			'site_id' => SITE_ID,
		);
	}

	protected function _setData() {
		$this->getParams();
		$this->_data = $this->_types?$this->_getBannerData():$this->_getPblBannerData();
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
					AND (
						places_id IN (" . implode(',', $this->_params['places_id']) . ")
					)
					AND ("
						.($this->_noModule?
							"modules_id IS NULL".($this->_params['modules_id'] ? " OR modules_id=" . intval($this->_params['modules_id']):""):
							($this->_params['modules_id'] ? "modules_id=" . intval($this->_params['modules_id']):"modules_id IS NULL")
						).

					")
					AND (
						categories_id IS NULL " . ($this->_categoryId ? "OR categories_id=" . intval($this->_categoryId):"") . "
					)
					AND (
						publishers_id IS NULL OR publishers_id=" . intval($this->_params['publisher_id']) . "
					)
				ORDER BY
					ord ASC
				LIMIT
					" . $this->_limit . "
			) AS sq
			JOIN
				banners AS b ON (sq.id = b.id)
			JOIN
				plans AS p ON (sq.plans_id = p.id)
			JOIN
				types AS t ON (b.types_id = t.id)
			;
		";
		return $this->_db->fetchAll($query);
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
					'normal' AS bt,
					u.company
				FROM (
					SELECT
						banners_id
					FROM
						pbl_mview_banners
					WHERE
						languages_id=" . intval($this->_params['lang_id']) ."
				 		AND ("
							.($this->_noModule?
								"modules_id IS NULL".($this->_params['modules_id'] ? " OR modules_id=" . intval($this->_params['modules_id']):""):
								($this->_params['modules_id'] ? "modules_id=" . intval($this->_params['modules_id']):"modules_id IS NULL")
							).
						")
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
				JOIN
					pbl_users AS u ON (b.users_id = u.id)
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
					'gag' AS bt,
					'' AS company
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
		if ($this->_types){
			$this->_updateBannerStat();
		}
		else {
			$this->_updatePblBannerStat();
		}
	}

	protected function _updateBannerStat() {
		foreach ($this->_data as $data){
			$query = "
				UPDATE
					stat_shows
				SET
					shows=shows+1
				WHERE
					date_show=CURDATE()
					AND plans_id=" . $data['id'] . "
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
						'" . $data['id'] . "',
						'" . $this->_params['lang_id'] . "', " .
						($this->_params['modules_id'] ? "'" . $this->_params['modules_id']."'":"NULL") . ",
						'" . intval($this->_params['publisher_id']) . "'
					)";

				$this->_db->query($query);
			}
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
}
