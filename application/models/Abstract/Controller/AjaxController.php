<?php
class Abstract_Controller_AjaxController extends Abstract_Controller_FrontendController {

	public $resultJSON = false;
	protected $_redirectUrl = '/';

	public function init(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		parent::init();
	}

	protected function setup(){
		$this->initUser();
		$this->initCache();
	}

	public function preDispatch() {}

	public function postDispatch() {
		if ($this->resultJSON !== false){
			$this->_response->setHeader('Content-type', 'application/json');
			echo Zend_Json_Encoder::encode($this->resultJSON);
		}
		if (!$this->_request->isXmlHttpRequest() && !$this->_request->getParam('PHPSESSID',false)) $this->_redirect('/'.DEFAULT_LANG_CODE.$this->_redirectUrl);
	}

	public function errorAction(){
		$this->resultJSON['success'] = false;
		$this->resultJSON['message'] = $this->view->lang->translate('Access denied!');
	}

}
