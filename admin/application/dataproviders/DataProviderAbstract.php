<?PHP
Zend_Loader::loadClass("Zend_Db");
Zend_Loader::loadClass("Sync_Base", PATH_DATAPROVIDERS);

abstract class DataproviderAbstract {

	/**
	 * Экземпляр объекта БД
	 *
	 * @var Zend_Db_Adapter_Abstract
	 */
	static protected $_db = null;

	/**
	 * Инициализация провайдеров данных
	 * Создается подключение к БД
	 *
	 */
	public static function init() {
		//Подключение к БД должно быть только одно
		if (self::$_db instanceof Zend_Db_Adapter_Abstract) {
			return;
		}

		$params = array(
			'host'     => DB_HOST,
			'username' => DB_USERNAME,
			'password' => DB_PASS,
			'dbname'   => DB_NAME
		);

		self::$_db = Zend_Db::factory(DB_ADAPTER, $params);
		self::$_db->query("SET NAMES UTF8");
	}

	public static function &getDatabaseObjectInstance() {
		return self::$_db;
	}

	/**
	 * Логичнее поместить эту функцию в абстраутный класс провайдера данных
	 * В этом случае к этой функции гораздо проще обратиться из любого места
	 *
	 * @param string $name
	 * @return DataproviderAbstract
	 */
	public static function _DP($name) {
		if (Zend_Registry::isRegistered($name)) {
			return Zend_Registry::get($name);
		} else {
			Zend_Loader::loadClass($name, PATH_DATAPROVIDERS);
			$DB_obj = new $name();
			Zend_Registry::set($name, $DB_obj);
			return $DB_obj;
		}
	}
}