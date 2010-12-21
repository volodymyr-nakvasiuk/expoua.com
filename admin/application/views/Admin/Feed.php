<?PHP

abstract class Admin_Feed extends ViewAbstract {

	/**
	 * Массив, состоящий из элементов, которые не нужно выводить
	 *
	 * @var array
	 */
	protected $_skipElements = array('session_user_allow', 'list_languages', 'user_params');

	/**
	 * Помошники тут не имеют смысла
	 *
	 */
	protected function _attachHelpers() {
		//Ничего не делаем тут
	}

	/**
	 * Шаблонизатор тут использоваться не будет, поэтому переопределяем функцию инициализации шаблонизатора
	 *
	 */
	protected function _initTemplateEngine() {
		//Ничего не делаем тут
	}
}