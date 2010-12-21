<?PHP

Zend_Loader::loadClass("ViewHelpers_Abstract", PATH_VIEWS);

/**
 * Вспомогательный класс для доступа к галлереям
 *
 */
class ViewHelpers_Gallery extends ViewHelpers_Abstract implements ViewHelpers_ListInterface {

	/**
	 * Возвращает список доступных активных галлерей
	 * Первым параметром можно указать количество результатов, во втором - страницу
	 *
	 * @param int $num
	 * @param int $page
	 * @return array
	 */
	public function getList($num = null, $page = 1, $sort = null) {
		$extraParams = array('active' => 1);

		return $this->_DP("List_Galleries")->getList($num, $page, $extraParams);
	}

	/**
	 * Возвращает информацию об указанной галлерее
	 * id галлереи передается в первом параметре
	 *
	 * @param int $id
	 * @return array
	 */
	public function getEntry($id) {
		$extraParams = array('active' => 1);

		return $this->_DP("List_Galleries")->getEntry($id, $extraParams);
	}

	/**
	 * Возвращает список изображений в галлерее
	 *
	 * @param int $id
	 * @param int $num
	 * @param int $page
	 * @return array
	 */
	public function getElementsList($id = null, $num = null, $page = 1, $sort = null) {
		$extraParams = array('active' => 1);
		if (!is_null($id)) {
			$extraParams['parent_id'] = intval($id);
		}

		$result = $this->_DP("List_Joined_GalleriesElements")->getList($num, $page, $extraParams);

		foreach ($result['data'] as $key => $el) {
			$result['data'][$key]['filename'] = PATH_WEB_DATA_GALLERIES . $el['parent_id'] . "/" . $el['filename'];
			$result['data'][$key]['thumbnail'] = PATH_WEB_DATA_GALLERIES . $el['parent_id'] . "/tb/" . $el['id'] . ".jpg";
		}

		return $result;
	}

}