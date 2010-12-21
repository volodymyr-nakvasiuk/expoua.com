<?PHP

abstract class FrontendModelAbstract extends ModelAbstract {

	/**
	 * Возвращает название шаблона по его id
	 *
	 * @param int $id
	 * @return string
	 */
	public function getTemplateNameById($id) {
		$template = $this->_DP('List_Templates')->getEntry($id);
		return $template['name'];
	}

}