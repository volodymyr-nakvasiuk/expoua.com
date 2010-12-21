<?PHP

class DataProviderView extends DataproviderAbstract {

	private function _getTemplate($name, $category, $cols = array()) {
		$select = self::$_db->select();

		$select->from("cms_templates", $cols);
		$select->join("cms_template_categories", "cms_templates.tpl_cat_id = cms_template_categories.id", array());

		$select->where("cms_templates.name = ?", $name);
		$select->where("cms_template_categories.name = ?", $category);

		//Zend_Debug::dump($select->__toString());

		return self::$_db->fetchRow($select);
	}

	private function _getPage($id, $cols = array()) {
		$select = self::$_db->select();

		$select->from("cms_pages_data", $cols);
		$select->join("cms_pages", "cms_pages.id = cms_pages_data.id", array());
		$select->where("cms_pages_data.id = ?", $id);
		$select->where("cms_pages.active = 1");
		$select->where("cms_pages_data.languages_id = ?", Zend_Registry::get("language_id"));

		//echo $select->__toString();

		return self::$_db->fetchRow($select);
	}

	public function getTemplateNameByPageId($id) {
		$select = self::$_db->select();

		$select->from("cms_pages", array());
		$select->join("cms_templates", "cms_pages.templates_id = cms_templates.id", array("template_name" => 'name'));

		$select->where("cms_pages.id = ?", intval($id));

		return self::$_db->fetchOne($select);
	}

	public function getTemplate($tpl_name) {
		$cols = array('content');

		$tpl_explode = explode("/", trim($tpl_name));

		//Zend_Debug::dump($tpl_explode);

		if (sizeof($tpl_explode) == 2) {
			switch ($tpl_explode[0]) {
				case "contents":
					$res = $this->_getPage($tpl_explode[1], $cols);
					break;
				default:
					$res = $this->_getTemplate($tpl_explode[1], $tpl_explode[0], $cols);
			}

			if ($res !== false) {
				return $res['content'];
			}
		}

		return false;
	}

	public function getTemplateTimestamp($tpl_name) {
		$cols = array(new Zend_Db_Expr('UNIX_TIMESTAMP(updated_time) AS updated_time'));

		$tpl_explode = explode("/", trim($tpl_name));

		if (sizeof($tpl_explode) == 2) {
			switch ($tpl_explode[0]) {
				case "contents":
					$res = $this->_getPage($tpl_explode[1], $cols);
					break;
				default:
					$res = $this->_getTemplate($tpl_explode[1], $tpl_explode[0], $cols);
			}

			if ($res !== false) {
				return $res['updated_time'];
			}
		}

		return false;
	}

}