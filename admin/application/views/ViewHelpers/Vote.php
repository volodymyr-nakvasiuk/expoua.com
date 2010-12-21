<?PHP

Zend_Loader::loadClass("ViewHelpers_Abstract", PATH_VIEWS);

/**
 * Вспомогательный класс для досупа к голосованиям
 *
 */
class ViewHelpers_Vote extends ViewHelpers_Abstract implements ViewHelpers_ListInterface {

	/**
	 * Возвращает голосование, id которого указано в первом параметре
	 *
	 * @param int $id
	 * @return array
	 */
	public function getEntry($id) {
		$extraParams = array('active' => 1);

		return $this->_DP("List_Joined_Votes")->getEntry($id, $extraParams);
	}

	/**
	 * Возвращает список активных голосований
	 * Первым параметром можно передать количество возвращаемых результатов, если не указывать, возвращаются все.
	 * Вторым параметром можно передать страницу при выборке с ограничителем
	 *
	 * @param int $num
	 * @param int $page
	 * @return array
	 */
	public function getList($num = null, $page = 1, $sort = null) {
		$extraParams = array('active' => 1);

		return $this->_DP("List_Joined_Votes")->getList($num, $page, $extraParams);
	}

	/**
	 * Возвращает список доступных элементов голосования
	 * Id голосования указывается в первом параметре
	 * Во втором и третьем необязательных параметрах указывается количество результатов и страница
	 *
	 * @param int $id
	 * @param int $num
	 * @param int $page
	 * @return array
	 */
	public function getElementsList($id = null, $num = null, $page = 1, $sort = null) {
		$extraParams = array('active' => 1, 'parent_id' => intval($id));

		return $this->_DP("List_VotesElements")->getList($num, $page, $extraParams);
	}

}