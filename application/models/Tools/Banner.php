<?php
	class Tools_Banner{
		protected $_db = false;

		function __construct(){
			$databases = Zend_Registry::get('databases');
			$this->_db = $databases['banners'];
		}

		function updateStat(Array $data, Array $ids, $site_id) {
			$query = "UPDATE pbl_stat_shows SET shows=shows+1
			WHERE date_show=CURDATE() AND banners_id=" . $data['id'] . "
			AND modules_id" . (!empty($ids['modules_id']) ? "=" . $ids['modules_id']:" IS NULL") . "
			AND sites_id = " . intval($site_id);

			$this->_db->query($query);

			if (mysql_affected_rows() == 0) {
				$query = "INSERT INTO pbl_stat_shows (date_show, banners_id, modules_id, sites_id)
			VALUES (CURDATE(), '" . $data['id'] . "', " .
						(!empty($ids['modules_id']) ? "'" . $ids['modules_id']."'":"NULL") . ", '" . intval($site_id) . "')";

				$this->_db->query($query);
			}
		}

		function getBannersData(Array $ids, $catId, $site_id, $limit = 5) {
			$query = "(SELECT b.id, t.media, t.height, t.width, b.text_content, b.file_alt, b.file_name, 'normal' AS bt
	FROM (SELECT banners_id FROM pbl_mview_banners
	WHERE languages_id=" . intval($ids['lang_id']) .
					" AND (modules_id IS NULL" . (!empty($ids['modules_id']) ? " OR modules_id=" . intval($ids['modules_id']):"") . ")" .
					" AND (categories_id IS NULL" . (!empty($catId) ? " OR categories_id=" . intval($catId):"") . ")" .
					" AND (countries_id IS NULL" . (!empty($ids['countries_id']) ? " OR countries_id=" . intval($ids['countries_id']):"") . ")
	ORDER BY (IF(categories_id IS NULL,price,price*1000)) DESC
	LIMIT " . $limit . ") AS sq
	JOIN pbl_banners AS b ON (sq.banners_id = b.id)
	JOIN types AS t ON (b.types_id = t.id))

	UNION

	(SELECT b.id, t.media, t.height, t.width, b.text_content, b.file_alt, b.file_name, 'gag' AS bt
	FROM pbl_banners_gags AS b
	JOIN ExpoPromoter_Opt.sites AS s ON (b.partners_id = s.partners_id)
	JOIN types AS t ON (t.id = b.types_id)
	WHERE b.active=1 AND b.languages_id='" . intval($ids['lang_id']) . "' AND s.id='" . intval($site_id) . "'
	LIMIT " . $limit . ")

	LIMIT " . $limit;

			return $this->_db->query($query);
		}

		function getIds($lang, $module, $sites_id) {
			$query = "SELECT
	(SELECT id FROM ExpoPromoter_Opt.languages WHERE code='" . mysql_escape_string($lang) . "') AS lang_id,
	(SELECT id FROM ExpoPromoter_banners.modules WHERE code='" . mysql_escape_string($module) . "') AS modules_id,
	(SELECT partners_id FROM ExpoPromoter_Opt.sites WHERE id='" . intval($sites_id) . "') AS partners_id,
	(SELECT countries_id FROM ExpoPromoter_Opt.sites WHERE id='" . intval($sites_id) . "') AS countries_id";

			$res = $this->_db->queryRow($query);
			return $res;
		}

		function getTenderTourPartnerId($sites_id) {
			$query =
					"SELECT ttour_id FROM ExpoPromoter_Opt.partners AS p
			INNER JOIN ExpoPromoter_Opt.sites AS s ON s.partners_id = p.id
		  WHERE p.active = 1
			AND s.active = 1
			AND s.id='" . intval($sites_id) . "'
	   ";

			$res = $this->_db->queryRow($query);
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
	}