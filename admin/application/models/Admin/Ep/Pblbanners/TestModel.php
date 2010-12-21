<?php
require_once(PATH_MODELS . "/Admin/ListModelAbstract.php");

class Admin_Ep_Pblbanners_TestModel extends Admin_ListModelAbstract {

	protected $_DP_name = 'List_Banners_PblBanners';

	public function getCategoriesList() {
		return $this->_DP('List_Joined_Ep_BrandsCategories')->
			getList(null, null, array('languages_id' => self::$_user_language_id), array('name' => 'ASC'));
	}

	public function getTestBanners($moduleId, $catId, $countryId) {
		return $this->_DP_obj->getTestBanners($moduleId, $catId, $countryId);
	}

}