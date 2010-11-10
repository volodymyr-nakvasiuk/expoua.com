<?php

/**
 * CarController
 *
 * @author
 * @version
 */

class Abstract_Controller_InitController extends Zend_Controller_Action {
	
	public static $currentAuth;
	protected $Session;
	protected $userData;
	protected $auth = false;
	
	protected $_actionId = false;

	protected $_langArray = array(
		array(
			'name'=>'Русский',
			'abbr'=>'рус',
			'code'=>'ru',
			'locale'=>'ru_RU',
			'id'=>1,
		),
		array(
			'name'=>'English',
			'abbr'=>'eng',
			'code'=>'en',
			'locale'=>'en_US',
			'id'=>2,
		),
	);
	
	/**
	 * Redirector - defined for code completion
	 *
	 * @var Zend_Controller_Action_Helper_Redirector
	 */
	protected $_redirector = null;
	
	/**
	 * Zend_Controller_Request_Http object wrapping the request environment
	 * @var Zend_Controller_Request_Http
	 */
	protected $_request = null;
	
	public function __call($f, $a){}
	
	public function init(){
		$this->_setupURI();
		
		parent::init();
		$this->_redirector = $this->_helper->getHelper('Redirector');

		$this->initSession();
		$this->initViewParams();
		//ArOn_Core_Debug::getGenerateTime('params');
		$this->initActionId();
		//ArOn_Core_Debug::getGenerateTime('action');
	}

	protected function _setupURI(){
		$redirect = false;
		$uri = $this->_request->getRequestUri();
		if (strpos($uri, '//')!==false){
			$redirect = true;
			$count = 1;
			while ($count) $uri = str_replace("//", "/", $uri, $count);
		}
		$lang = $this->_request->getParam('lang');
		$langId = 2;
		if ($lang!==null){
			$isLang = false;
			foreach ($this->_langArray as $l){
				if ($lang == $l['code']){
					$isLang=true;
					$langId = $l['id'];
					break;
				}
			}
			if (!$isLang){
				$redirect = true;
				$uri = str_replace("//", "/", '/'.$this->_langArray[0]['code'].$uri.'/');
			}
		}
		//exit((($redirect)?'Have':'No').' redirect to '.$uri);
		if ($redirect) $this->_redirect($uri);
		define ('DEFAULT_LANG_ID', $langId);
		$this->view->lang->setLocale($lang);
	}
	
	protected function initSession(){
		if (!Zend_Session::isStarted()){
			Zend_Session::start();
			//$this->Session = new Zend_Session_Namespace('user');
		}
		$this->Session = Zend_Registry::get('defSession');
		$this->Session->setRequest($this->_request);
	}
	
	protected function initViewParams(){
		$this->view->module = $this->_request->getModuleName();
		$this->view->controller = $this->_request->getControllerName();
		$this->view->action = $this->_request->getActionName();
		$this->view->fullAction =   $this->_request->getModuleName().' - '.
									$this->_request->getControllerName().' - '.
									$this->_request->getActionName();
		$this->view->http_host = 'http://'.str_replace('forum.','',$_SERVER["HTTP_HOST"]);
		$this->view->requestUri = $this->_request->getRequestUri();
		$this->view->requestUrl = Tools_View::clearAllUrlParams($this->view->requestUri);
		$this->view->fullUrl = $this->view->http_host.$this->view->requestUri;
		$this->view->doctype('XHTML1_STRICT');
		$this->view->langArray = $this->_langArray;
	}
	
	protected function initActionId(){
		$extra = '';
		$extraAction = array(
		);
		$matches = explode('/', $this->view->requestUrl);
		if (isset($matches[3]) && in_array($matches[3], $extraAction)!==false){
			$extra = '/'.$matches[3];
		}
		$this->view->fullAction .= $extra;
		$params = array(
					'modulename'=>$this->view->module,
					'controllername'=>$this->view->controller,
					'actionname'=>$this->view->action.$extra,
				);
		
		$action = Crud_Grid_ExtJs_SiteActs::getInstance(null, $params);
		$action->setNotCountQuery();
		$data = $action->getData();
		
		if (!empty($data['data'])){
			$this->_actionId = $data['data'][0]['id'];
		}
		elseif ($this->view->module == 'default'){
			$this->_forward('error', 'error');
			//$this->_request->setControllerName('error');
			//$this->_request->setActionName('error');
			return;
		}
	}
	
	public function phpinfoAction(){
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		phpinfo();
		die;
	}

	protected function redirectZend($action, $controller = null, $module = null, $params = array())
	{
	
		// Redirect to 'my-action' of 'my-controller' in the current
		// module, using the params param1 => test and param2 => test2
		$this->_redirector->gotoSimple( $action,
										$controller,
										$module,
										$params
									);

		return; // never reached
	}
	
	protected function redirectElse(){
		
		 /* do some stuff */

		// Redirect to a previously registered URL, and force an exit
		// to occur when done:
		$this->_redirector->redirectAndExit();
		
		 $this->_redirector
			->gotoUrl('/my-controller/my-action/param1/test/param2/test2');

		 // Redirect to blog archive. Builds the following URL:
		// /blog/2006/4/24/42
		$this->_redirector->gotoRoute(
			array('year' => 2006,
				  'month' => 4,
				  'day' => 24,
				  'id' => 42),
			'blogArchive'
		);
	}
}