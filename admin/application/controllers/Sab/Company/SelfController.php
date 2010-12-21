<?PHP
Zend_Loader::loadClass("Sab_Company_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Company_SelfController extends Sab_Company_ControllerAbstract {

/**
* передаем данные пользователя в шаблон формы редактирования
*/
   public function editAction() {
		parent::editAction();
   	$this->_view->list_categories = $this->_model->getCategoriesList();
   }

	//Переопределяем действия для исключения их использования
	public function listAction() {}
	public function addAction() {}
	public function insertAction() {}
	public function deleteAction() {}
}