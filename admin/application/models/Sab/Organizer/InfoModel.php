<?PHP
Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_InfoModel extends Sab_Organizer_ModelAbstract {
	
	/**
	 * Возвращает страницу из редактируемых страниц по id
	 * admin_cms_pages
	 * 
	 * @param $id
	 * @return array
	 */
	public function getCmsPage($id) {
		return $this->_DP("List_Joined_Pages")->getEntry($id, $this->_DP_limit_params);
	}
	
}