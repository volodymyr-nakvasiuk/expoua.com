<?PHP

Zend_Loader::loadClass("DataProviderAbstract", PATH_DATAPROVIDERS);
Zend_Loader::loadClass("Zend_Auth");
Zend_Loader::loadClass("Zend_Auth_Storage_Session");

abstract class ModelAbstract {

  /**
  * Текущий выбранный язык пользователя
  *
  * @var int
  */
  protected static $_user_language_id;

  /**
  * Инициализируем провайдер данных сразу после создания экземпляра класса модели
  *
  */
  public function __construct() {
    //Производим инициализацию провайдера данных
    DataproviderAbstract::init();
  }

  /**
  * Функция применяется когда нужно произвести первичную инициализацию в классе модели
  *
  */
  public function init() {
    // Пока нечего тут делать...
  }

  /**
  * Функция для удобного получения экземпляров классов провайдеров данных
  *
  * @param string $name
  * @return DataproviderAbstract
  */
  public final function _DP($name) {
    if (Zend_Registry::isRegistered($name)) {
      return Zend_Registry::get($name);
    } else {
      Zend_Loader::loadClass($name, PATH_DATAPROVIDERS);
      $DB_obj = new $name();
      Zend_Registry::set($name, $DB_obj);
      return $DB_obj;
    }
  }

  /**
  * Возвращает значение конфигурационной переменной, указанной в параметре
  *
  * @param string $code
  * @return string
  */
  public function getConfigValue($code) {
    return $this->_DP("List_OptionsConstants")->getValueByCode($code);
  }

  /**
  * Возвращает список доступных языков системы
  *
  */
  public function getLanguagesList() {
    $list = $this->_DP("List_Languages")->getList(null, null, array('active' => 1));
    return $list['data'];
  }

  /**
  * Сохраняет в модели текущий выбранный язык пользователя
  *
  * @param int $id
  */
  public function setCurrentLanguage($id) {
    self::$_user_language_id = $id;
    //DataproviderAbstract::$_user_lang_id = $id;
  }


  /**
  * Проверяем сессию пользователя.
  * В базовом классе функция всегда возвращает false;
  *
  * @return boolean|string
  */
  public function checkUserSession() {
    return false;
  }

  /**
  * Подготавливает опции поиска.
  * Основная задача - выделить текстовые поля и произодить поиск по маске для них.
  * Поиск по остальным полям производится сравнением
  *
  * @param array $search
  */
  protected function _prepareSearchConditions(Array $search) {

    $result = array();

    foreach ($search as $key => $el) {
      $tmp = null;

      switch ($el['type']) {
        case "=":
          $tmp = new Zend_Db_Expr("= " . DataproviderAbstract::getDatabaseObjectInstance()->quote($el['value']));
          break;
        case ">":
          $tmp = new Zend_Db_Expr("> " . DataproviderAbstract::getDatabaseObjectInstance()->quote($el['value']));
          break;
        case ">=":
          $tmp = new Zend_Db_Expr(">= " . DataproviderAbstract::getDatabaseObjectInstance()->quote($el['value']));
          break;
        case "<":
          $tmp = new Zend_Db_Expr("< " . DataproviderAbstract::getDatabaseObjectInstance()->quote($el['value']));
          break;
        case "<=":
          $tmp = new Zend_Db_Expr("<= " . DataproviderAbstract::getDatabaseObjectInstance()->quote($el['value']));
          break;
        case "~":
          $tmp = new Zend_Db_Expr("LIKE " . DataproviderAbstract::getDatabaseObjectInstance()->quote('%' . $el['value'] . '%'));
          break;
      }

      if (!is_null($tmp)) {
        $result[$el['column']] = $tmp;
      }

    }

    return $result;
  }
}