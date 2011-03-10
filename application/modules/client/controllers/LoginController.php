<?php
class Client_LoginController extends Abstract_Controller_AjaxController  {
	
	public $resultJSON = array();
	protected $_redirectUrl = '/client/';

	public function indexAction(){
		$params = $this->_request->getParams();
		if ($this->getRequest()->isPost() && !empty($params['login'])) {
			$form = new Zend_Form();
			$form->addElement('text', 'login', array('id'=>'login', 'label'=>'Login'));
			$form->addElement('password', 'password', array('id'=>'password', 'label'=>'Password'));
			if ($form->isValid($params)) {
				$adapter = ArOn_Acl_Auth_Client::getDbAuthAdapter();
				$adapter->setIdentity($form->getValue('login'))->setCredential($form->getValue('password'));
				//$prev_date = "2010:01:09:16:53:01";
				//$prev_date = time() - 86400;
				//$now_date = time();
				//$adapter->setCredentialTreatment('(?) AND (active = 1 OR UNIX_TIMESTAMP(client_created_date) > '.$prev_date.')');
				$result = self::$currentAuth->authenticate($adapter);
				if ($result->isValid()) {
					$this->setupUser($result->getIdentity(), false);//!empty($params['save_login']) && $params['save_login'] == 'yes');
					$this->initUser();
					$this->resultJSON['success'] = true;
					$this->resultJSON['loginRedirect'] = '/'.DEFAULT_LANG_CODE.'/client/';
					return;
				}
				else {
					$this->resultJSON['success'] = false;
					$this->resultJSON['message'] = $this->view->lang->translate('Invalid login or password!<br/>Please, check the data and try again.'); //"Неверный логин либо пароль!\nПроверьте данные и попробуйте еще раз.";
					return;
				}
			}
			else {
				$this->resultJSON['success'] = false;
				$this->resultJSON['message'] = $this->view->lang->translate('Form is filled out wrong!<br/>Please, check the data and try again.'); //"Неверно заполненная форма!\nПроверьте данные и попробуйте еще раз.";
				return;
			}
		}
		elseif($this->_request->isXmlHttpRequest()){
			$this->resultJSON['success'] = false;
			$this->resultJSON['message'] = $this->view->lang->translate('Incorrect request!'); //"Некорректный запрос.";
			return;
		}
		$this->_helper->viewRenderer->setNoRender(false);
		$this->_forward('error', 'error', 'default');
	}

	public function logoutAction() {
		$this->forgetUser();
		$this->_redirect('/'.DEFAULT_LANG_CODE.'/');
	}

	public function loginAction() {
		setcookie('login_client','1',time()+120,'/',str_replace('http://', '.', HOST_NAME));
		$this->_redirect('/'.DEFAULT_LANG_CODE.'/');
	}

	public function errorAction(){
		$this->resultJSON['success'] = false;
		$this->resultJSON['message'] = $this->view->lang->translate('You don\'t have access to the requested section. <br/> Re-login may solve the problem!'); //'У вас нет доступа к запрашиваемому разделу.<br/>Возможо повторный вход в систему решит проблему!';
	}
}
