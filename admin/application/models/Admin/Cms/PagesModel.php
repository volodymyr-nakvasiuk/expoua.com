<?PHP

Zend_Loader::loadClass("Admin_ListModelAbstract", PATH_MODELS);

class Admin_Cms_PagesModel extends Admin_ListModelAbstract {

	protected $_DP_name = "List_Joined_Pages";

	public function getTemplatesList() {
		$params = array('tpl_cat_id' => 1);

		$res = $this->_DP("List_Templates")->getList(null, null, $params);

		return $res['data'];
	}

	/**
	 * Возвращает массив, содержащий путь в обратном порядке начиная от заданного элемента
	 *
	 * @param int $parent_id
	 * @return array
	 */
	public function getTrail($parent_id) {

		if ($parent_id == 0) {
			return array();
		}

		$trail_array = array();

		do {
			$entry = $this->_DP($this->_DP_name)->getEntry($parent_id);
			$trail_array[] = array('id' => $entry['id'], 'name' => $entry['name'], 'parent_id' => $entry['parent_id'], 'active' => $entry['active']);
			$parent_id = $entry['parent_id'];
		} while ($entry['parent_id'] != 0);

		return array_reverse($trail_array);
	}

	/**
	 * Возвращает дерево списка
	 *
	 * @param int $parent_id
	 * @return array
	 */
	public function getTree($parent_id) {
		$result = array();

		$extraParams = array('languages_id' => self::$_user_language_id, 'parent_id' => $parent_id);

		$list = $this->_DP_obj->getList(null ,null, $extraParams);

		$result = $list['data'];

		foreach ($list['data'] as $key => $el) {
			$res = $this->getTree($el['id']);
			if (sizeof($res) > 0) {
				$result[$key]['children'] = $res;
			}
		}

		return $result;
	}
}