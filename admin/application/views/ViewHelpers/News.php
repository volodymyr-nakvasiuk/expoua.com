<?PHP

Zend_Loader::loadClass("ViewHelpers_Abstract", PATH_VIEWS);

/**
 * Вспомогательный класс для досупа к новостям
 *
 */
class ViewHelpers_News extends ViewHelpers_Abstract implements ViewHelpers_ListInterface {

	/**
	 * Возвращает выбранную новость
	 *
	 * @param int $id
	 * @return array
	 */
	public function getEntry($id) {
		$extraParams = array('active' => 1);

		return $this->_DP("List_Joined_News")->getEntry($id, $extraParams);
	}

	/**
	 * Возвращает список категорий новостей
	 *
	 * @param int $num
	 * @param int $page
	 * @return array
	 */
	public function getList($num = null, $page = 1, $sort = null) {
		$extraParams = array('active' => 1);

		return $this->_DP("List_NewsCategories")->getList($num, $page, $extraParams);
	}

	/**
	 * Функция для получения списка новостей из категории
	 * Первым категория. Если не указана, возвращаются все новости, если 0, то возвращаются новости, для которых не задана категория
	 * Вторым параметром указывается количество возвращаемых новостей. Если не указано, возвращаются все
	 * Третьим - страница
	 *
	 * @param int $category
	 * @param int $num
	 * @param int $page
	 * @return array
	 */
	public function getElementsList($category = null, $num = null, $page = 1, $sort = null) {
		$result = array();

		$extraParams = array('active' => 1);
		if (!is_null($category)) {
			if ($category === 0) {
				$extraParams['categories_id'] = null;
			} else {
				$extraParams['categories_id'] = intval($category);
			}
		}

		$result = $this->_DP("List_Joined_News")->getList($num, 1, $extraParams);

		return $result;
	}

}
