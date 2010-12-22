<?php
class Tools_Banner{
	protected $_module = false;
	protected $_categoryId = false;
	protected $_limit = false;

	protected $_db = false;
	protected $_params = false;
	protected $_data = false;

	function __construct($module=null, $categoryId=null, $limit=5){
		$this->_module = $module;
		$this->_categoryId = $categoryId;
		$this->_limit = $limit;
		$databases = Zend_Registry::get('databases');
		$this->_db = $databases['banners'];

		//$ids = getIds($module);
		//getBannersData($ids, $catId, $site_id, $num);
		//foreach ($data_array as $data) {
		//		$data['url_clicker'] = PATH_CLICKER . $data['id'] . "_" . $site_id . "_" . $ids['lang_id'];
		//
		//		if ($data['bt'] == "normal") {
		//			$data['url_clicker'] .= "_v2pbl";
		//			$data['file_name'] = PATH_DATA_PBLBANNERS . $data['file_name'];
		//		} else {
		//			$data['url_clicker'] .= "_v2pblg";
		//			$data['file_name'] = PATH_DATA_PBLGAGBANNERS . $data['file_name'];
		//		}
		//
		//		switch ($data['media']) {
		//			case "flash":
		//				if (!empty($_GET['flash']) && $_GET['flash']==1) {
		//					$banners_code .= getFlashBanner($data);
		//				} else {
		//					//Переходим к следующей итерации цикла чтобы не засчитывать показ если флеш не установлен
		//					continue;
		//				}
		//				break;
		//			case "text":
		//				$banners_code .= getTextBanner($data);
		//				break;
		//			case "image":
		//				$banners_code .= getImageBanner($data);
		//				break;
		//		}
		//	updateStat($data, $ids, $site_id);
		//}
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
					id
				FROM
					modules
				WHERE
					code='" . $this->_module . "'
			;";
		$res = $this->_db->fetchRow($query);
		$this->_params = array(
			'lang_id' => DEFAULT_LANG_ID,
			'modules_id' => $res['id'],
			'partners_id' => PARTNER_ID,
			'countries_id' => COUNTRY_ID,
			'site_id' => SITE_ID,
		);
	}

	protected function _setData() {
		$this->getParams();
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
							countries_id IS NULL" . (!empty($this->_params['countries_id']) ? " OR countries_id=" . intval($this->_params['countries_id']):"") . "
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
		$this->_data = $this->_db->fetchAll($query);
	}

	public function updateBannersStat() {
		$this->getData();
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
			$this->_db->query($query);

			if (mysql_affected_rows() == 0) {
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
/*
	function getTenderTourPartnerId($sites_id) {
		$query =
				"SELECT ttour_id FROM ExpoPromoter_Opt.partners AS p
		INNER JOIN ExpoPromoter_Opt.sites AS s ON s.partners_id = p.id
	  WHERE p.active = 1
		AND s.active = 1
		AND s.id='" . intval($sites_id) . "'
   ";

		$res = $this->_db->fetchRow($query);
		return empty($res) ? 1 : ($res['ttour_id'] ? $res['ttour_id'] : 1);
	}

	function getFlashBanner($data) {
		$flash = '<div class="banner-wrapper"><div class="banner-preheader">Advert.Expopromoter</div><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="' . $data['width'] . '" height="' . $data['height'] . '">
	<param name="movie" value="http://ws.expopromoter.com/flash/preloader_' . $data['width'] . "x" . $data['height'] . '.swf" />
	<param name="quality" value="high" />
	<param name="wmode" value="transparent">
	<param name="FlashVars" value="flashURL=' . $data['file_name'] . '&clickURL=' . $data['url_clicker'] . '">
	<embed wmode="transparent" src="http://ws.expopromoter.com/flash/preloader_' . $data['width'] . "x" . $data['height'] . '.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="' . $data['width'] . '" height="' . $data['height'] . '" FlashVars="flashURL=' . $data['file_name'] . '&clickURL=' . $data['url_clicker'] . '" />
	</object></div>';

		$out = 'document.getElementById("' . $_GET['id'] . '").style.height="' . $data['height'] . 'px";
		document.getElementById("' . $_GET['id'] . '").style.width="' . $data['width'] . 'px";
		document.getElementById("' . $_GET['id'] . '").innerHTML=\'' . prepareNewLine($flash) . '\';';

		return $out;
	}

	function getTextBanner($data) {
		return prepareNewLine('<div class="banner-wrapper"><a rel="nofollow" href="' . $data['url_clicker'] . '" target="_blank"><span class="banner-txt-image"><img src="' . $data['file_name'] . '" border="0" alt="' . prepareText($data['file_alt']) . '" /></span><span class="banner-txt-header">' . prepareText($data['file_alt']) . '</span><span class="banner-txt-content">' . prepareText($data['text_content']) . '</span></a></div>');
	}

	function getImageBanner($data) {
		return '<div class="banner-wrapper"><div class="banner-preheader">Advert.Expopromoter</div><a href="' . $data['url_clicker'] . '" target="_blank"><img src="' . $data['file_name'] . '" border="0" alt="' . $data['file_alt'] . '" height="' . $data['height'] . '" width="' . $data['width'] . '"></a></div>';
	}

	function prepareNewLine($text) {
		$text = str_replace("\r", "", $text);
		return str_replace("\n", '\n', $text);
	}

	function prepareText($data) {
		return str_replace(
			array('&', "'", '"', "<", ">"),
			array("&amp;", "&#39;", "&#34;", "&lt;", "&gt;"),
			$data);
	}
*/
}
