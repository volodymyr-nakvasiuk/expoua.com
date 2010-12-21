<?PHP

Zend_Loader::loadClass("Sab_ListControllerAbstract", PATH_CONTROLLERS);

abstract class Sab_Help_ControllerAbstract extends Sab_ListControllerAbstract {

  /**
  * Перегружаем функцию инициализации вида
  *
  */
  protected function _initView() {
    Zend_Loader::loadClass("Admin_View", PATH_VIEWS);
    $this->_view = new Admin_View();

    $this->_view->setTemplate("coreHelp.tpl");
  }

}