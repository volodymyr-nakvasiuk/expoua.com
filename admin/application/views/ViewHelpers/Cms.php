<?PHP

Zend_Loader::loadClass("ViewHelpers_Abstract", PATH_VIEWS);

/**
 * Класс, в котором собраны различные действия, относящиеся к контенту.
 * Выносить их в свои отдельные классы нет смысла
 *
 */
class ViewHelpers_Cms extends ViewHelpers_Abstract {

	/**
	 * Функция для получения списка элементов меню
	 * В качестве параметра можно указать начальный элемент
	 * Если параметр не указан или равен 0, выборка производится от корня
	 * Вторым и третим элементом можно передать количество выдаваемых результатов и страницу
	 *
	 * @param int $parent
	 * @param int $num
	 * @param int $page
	 * @return array
	 */
	public function getMenusList($parent = 0, $num = null, $page = 1) {
		$extraParams = array("active" => 1, 'languages_id' => Zend_Registry::get("language_id"));

		if ($parent == 0) {
			$extraParams['parent_id'] = null;
		} else {
			$extraParams['parent_id'] = intval($parent);
		}

		return $this->_DP("List_Joined_Menus")->getList(null, null, $extraParams);
	}

	public function getTrail() {
		$page = Zend_Controller_Front::getInstance()->getRequest()->getUserParam('id');

		if (empty($page)) {
			return array();
		}

		$trail_array = array();

		do {
			$entry = $this->getEntry($page);
			$trail_array[] = array('id' => $entry['id'], 'name' => $entry['name'], 'parent_id' => $entry['parent_id']);
			$page = $entry['parent_id'];
		} while ($entry['parent_id'] != 0);

		return array_reverse($trail_array);
	}

	/**
	 * Возвращает данные о контентной странице, id которой передан первым параметром
	 *
	 * @param int $id
	 * @return array
	 */
	public function getEntry($id) {
		$extraParams = array('active' => 1, 'languages_id' => Zend_Registry::get("language_id"));

		return $this->_DP("List_Joined_Pages")->getEntry($id, $extraParams);
	}

	/**
	 * Возвращает список контентных страниц в категории, указанной в первом параметре
	 * Если первый параметр опущен, возвращаются все страницы
	 * Если равен 0, возвращаются корневые категории
	 *
	 * @param int $id
	 * @param int $num
	 * @param int $page
	 * @param string $sort
	 * @return array
	 */
	public function getElementsList($id = null, $num = null, $page = 1, $sort = null) {
		$extraParams = array('active' => 1);

		if ($id === 0) {
			$extraParams['parent_id'] = null;
		}

		return $this->_DP("List_Joined_Pages")->getList($num, $page, $extraParams);
	}

}