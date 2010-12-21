<?PHP

abstract class Admin_ModelAbstract extends ModelAbstract {

  public function init() {
    //Сперва вызываем функцию инициализации родителя
    parent::init();

    //Устанавливаем пространство имен аутентификации админки
    Zend_Auth::getInstance()->setStorage(new Zend_Auth_Storage_Session('Auth_Admin_NS'));
  }

  /**
  * Проверяем сессию пользователя.
  * Если пользователь зарегистрирован функция возвращает его логин, иначе false;
  *
  * @return boolean|string
  */
  public function checkUserSession() {
    $identity = Zend_Auth::getInstance()->hasIdentity();

    //Сперва проверяем зарегистрирован ли пользователь
    if ($identity === true) {
      $identity = Zend_Auth::getInstance()->getIdentity();
    } else {
      return false;
    }

    //Проверяем имеет ли он соответствующие привелегии для доступа к текущему модулю и действию
    $controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
    $action = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

    #$time_start = getmicrotime();
    $acl_dp = $this->_DP("Acl_Admin");
    $acl_dp->start($identity);
    #echo getmicrotime() - $time_start;

    $acl_check_result = $acl_dp->isAllowed($identity, $controller, $action);

    if ($acl_check_result === false) {
      return false;
    }

    $user_param_id = Zend_Controller_Front::getInstance()->getRequest()->getUserParam("id", null);
    //Пишем в лог действий пользователя
    $this->_DP("List_LogActions")->insertEntry(array('admin_identity' => $identity, 'controller' => $controller, 'action' => $action, 'param_id' => $user_param_id));

    return $identity;
  }


  public function getAllowedResourcesList() {
    $acl_dp = $this->_DP("Acl_Admin");

    $identity = Zend_Auth::getInstance()->getIdentity();
    $list = $acl_dp->getAllowedResourcesList($identity);

    return $list;
  }


	public function getAdvertisersMsgList() {
		return $this->_DP('List_Banners_PblUsersMessages')->getList(1000, 1, array('answer' => '', 'type' => 'travel')); 
	}
	

	public function getOperatorsMsgList() {
		return $this->_DP('List_OperatorsMessages')->getList(1000, 1, array('answer' => '')); 
	}

}
