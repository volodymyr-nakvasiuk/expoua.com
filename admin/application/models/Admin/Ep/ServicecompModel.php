<?PHP

Zend_Loader::loadClass("Admin_Ep_ModelAbstract", PATH_MODELS);

class Admin_Ep_ServicecompModel extends Admin_Ep_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_ServiceCompanies';

	public function insertEntry(Array $data, $insertAllLangs = false) {
        if (isset($data['web_address']) && !empty($data['web_address']) && strpos($data['web_address'], 'http://') === false) {
          $data['web_address'] = 'http://' . $data['web_address'];
        }

        $data['sort_order'] = $data['sort_order'] * 2 - 1;
        if ($data['sort_order']<1) $data['sort_order']= 65534;
        $data['sort_order_cat'] = $data['sort_order_cat'] * 2 - 1;
        if ($data['sort_order_cat']<1) $data['sort_order_cat']= 65534;

		$res = parent::insertEntry($data, true);

		if ($res) {
			$id = $this->_DP_obj->getLastInsertId();
      $this->_DP($this->_DP_name)->updatePositions($data['service_companies_categories_id']);

			$this->_DP("Filesystem_Files")->insertEntry(array('type' => 'dir', 'basePath' => PATH_FRONTEND_DATA_IMAGES, 'path' => "/service_companies/", 'name' => $id));

			$img_res = $this->updateImageLogo("service_companies", $id);

			if ($img_res) {
				$update_data = array('logo' => 1);
				$this->updateEntry($id, $update_data);
			}
		}

		return $res;
	}

	public function updateEntry($id, Array $data) {
    if (isset($data['web_address']) && !empty($data['web_address']) && strpos($data['web_address'], 'http://') === false) {
      $data['web_address'] = 'http://' . $data['web_address'];
    } 

		$img_res = $this->updateImageLogo("service_companies", $id);

		if (!is_null($img_res)) {
			if ($img_res) {
				$data['logo'] = 1;
			} else {
				$data['logo'] = 0;
			}
		}
        $data['sort_order_cat'] = $data['sort_order_cat'] * 2;
        $data['sort_order'] = $data['sort_order'] * 2;
        $item = $this->_DP($this->_DP_name)->getEntry($id);
        if ($item['sort_order_cat'] > $data['sort_order_cat']) $data['sort_order_cat']--; else $data['sort_order_cat']++;
        if ($item['sort_order'] > $data['sort_order']) $data['sort_order']--; else $data['sort_order']++;
        if ($data['sort_order_cat']<1) $data['sort_order_cat']= 65535;
        if ($data['sort_order']<1) $data['sort_order']= 65535;

        $res = parent::updateEntry($id, $data);
        $this->_DP($this->_DP_name)->updatePositions($data['service_companies_categories_id']);
		return $res;
	}

	public function deleteEntry($id) {
		$res = parent::deleteEntry($id);

		if ($res) {
			$this->_DP("Filesystem_Files")->deleteRecursive(array($id), array('basePath' => PATH_FRONTEND_DATA_IMAGES . "/service_companies/"));
		}

		return $res;
	}

	public function getCategoriesList() {
		$res = $this->_DP("List_Joined_Ep_ServiceCompCategories")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id), array("name" => "ASC"));

		return $res['data'];
	}
}