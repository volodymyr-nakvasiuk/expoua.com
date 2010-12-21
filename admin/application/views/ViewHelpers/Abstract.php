<?PHP

Zend_Loader::loadClass('ViewHelpers_ListInterface', PATH_VIEWS);

abstract class ViewHelpers_Abstract {

	/**
	 * Функция для удобного получения экземпляров классов провайдеров данных
	 * Дублирует одноименную в абстрактной модели
	 *
	 * @param string $name
	 * @return DataproviderAbstract
	 */
	protected final function _DP($name) {
		/*if (Zend_Registry::isRegistered($name)) {
			return Zend_Registry::get($name);
		} else {
			Zend_Loader::loadClass($name, PATH_DATAPROVIDERS);
			$DB_obj = new $name();
			Zend_Registry::set($name, $DB_obj);
			return $DB_obj;
		}*/

		return Zend_Registry::get("Shelby_ModelObj")->_DP($name);

	}

}