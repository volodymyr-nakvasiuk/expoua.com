<?php

/**
 * CarController
 *
 * @author
 * @version
 */

class Abstract_Controller_FrontendController extends Abstract_Controller_InitController {
	
	public $usedBanners = array();

	protected $_statModel = false;
	protected $_statModelId = false;
	
	public function init(){
		//ArOn_Core_Debug::getGenerateTime('startInit');
		//if ($this->_request->getParam('reff')) {
		//	$referal = new Tools_Referal($this->_request);
		//	$referal->addReferal($this->_request->getParam('reff'));
		//	$this->_redirect('/');	
		//}

		self::$currentAuth = ArOn_Acl_Auth_Client_Abstract::getInstance();
		
		parent::init();
		//ArOn_Core_Debug::getGenerateTime('parentInit');
		$this->setup();
		
		$this->view->activeMenu = '';
		$this->view->activeSubMenu = '';
		$this->view->currency = Zend_Registry::get('currency');
		$this->view->currency_a = Zend_Registry::get('currency_a');
		$this->view->cur_year = date('Y');
		
		//ArOn_Core_Debug::getGenerateTime('frontendInit');
		
		$this->view->googleSiteTracker = array(
			'firstTracker'  => 'UA-978984-3',
		//	'secondTracker' => 'UA-9867935-2',
		);
	}
	
	protected function initSeo(){
		if ($this->_actionId !== false){
			$seo_grid = new Crud_Grid_ExtJs_Seo(null, array('seoaction'=>$this->_actionId));
			$seo_grid->setNotCountQuery();
			$seo = $seo_grid->getData();
			if (!empty($seo['data'])){
				$seo = $seo['data'][0];
				if (isset($this->seo_tags) && $this->seo_tags) {
					$seo_search = array_keys($this->seo_tags);
					$seo_replace = array_values($this->seo_tags);
				}
				else {
					$seo_search = array();
					$seo_replace = array();
				}
				$this->view->headTitle(
					str_replace(
						$seo_search,
						$seo_replace,
						$seo['seo_title']
					)
				);
				
				$this->view->headMeta(
					str_replace(
						$seo_search,
						$seo_replace,
						$seo['seo_keywords']
					)
					, "keywords"
				);
				
				$this->view->headMeta(
					str_replace(
						$seo_search,
						$seo_replace,
						$seo['seo_description']
					)
					, "description"
				);
				
				$this->view->seo_text = str_replace(
											$seo_search,
											$seo_replace,
											$seo['seo_text']
										);
			}
		}
	}
	
	protected function initCssJs(){
		if (!empty($this->view->layouts)){
			foreach ($this->view->layouts as $box=>$layout){
				//$allInc = str_replace('inc/', '', $layout);
				$this->_appendCssJsFile('layouts/'.$box);
				
				foreach ($layout as $incFile) {
					$this->_appendCssJsFile($incFile);
				}
			}
		}
		if (!$this->_helper->viewRenderer->getNoRender()){
			$incFiles = array();
			if ($this->getRequest()->getModuleName() == 'client'){
				$incFiles[] = 'inc/client/_';
				$incFiles[] = 'inc/client/'.$this->getRequest()->getControllerName();
			}
			else {
				$incFiles[] = 'inc/'.$this->getRequest()->getControllerName().'/_';
				$incFiles[] = 'inc/'.$this->getRequest()->getControllerName().'/'.$this->getRequest()->getActionName();
			}
			foreach($incFiles as $incFile){
				$this->_appendCssJsFile($incFile);
			}
		}
	}
	
	public function postDispatch(){
		$this->view->banners = $this->_sortArray($this->view->banners);
		$this->view->layouts = $this->_sortArray($this->view->layouts);
		$this->initSeo();
		$this->initCssJs();
		//$this->updateStats();
	}
	
	protected function _sortArray($mass){
		foreach($mass as &$value){
			$arr = array();
			foreach($value as $class => $meaning){
				if (is_array($meaning) && isset($meaning[1])){
					$arr[$meaning[1]][$class] = $meaning[0];
				}
				else {
					$arr[100][$class] = is_array($meaning)?$meaning[0]:$meaning;
				}
				ksort($arr);
			}
			$newarr  = array(); 
			foreach($arr as $order => $subarr){
				foreach($subarr as $class => $mean){
					$newarr[$class] = $mean;
				}
			}
			$value = $newarr;
		}
		return $mass;
	}

	protected function _appendCssJsFile($incFile){
		$cssFile = '/css/'.$incFile.'.css';
		//if (file_exists(DOCUMENT_ROOT.$cssFile))
			$this->view->headLink()->appendStylesheet($cssFile);
		$jsFile = '/js/'.$incFile.'.js';
		//if (file_exists(DOCUMENT_ROOT.$jsFile))
			$this->view->headScript()->appendFile($jsFile);
	}
	
	public function render($action = null, $name = null, $noController = false){
		parent::render($action, $name, $noController);
		if (!$noController) $action = $this->_request->getControllerName().'/'.$action;
		$this->_appendCssJsFile('inc/'.$action);
	}
	
	protected function setup(){
		$this->initUser();
		$this->initCache();
		$this->initLayouts();
		$this->initMenu();
		$this->setupLayouts();
		$this->initStatistics();
	}
	
	protected function initMenu(){
		$lang = $this->view->lang->getLocale();
		$this->view->menuLinks = array(
			'events'=>array(
				'lang'=>array('ru','en'),
				'url'=>HOST_NAME.'/'.$lang.'/events/region-europe/country-ukraine/',
				'title'=>$this->view->lang->translate('Trade shows'),
				'submenu'=>array(
					'ukr'=>array(
						'url'=>HOST_NAME.'/'.$lang.'/events/region-europe/country-ukraine/',
						'title'=>$this->view->lang->translate('Exhibitions in Ukraine'),
					),
					'all'=>array(
						'url'=>HOST_NAME.'/'.$lang.'/events/',
						'title'=>$this->view->lang->translate('Exhibitions in the world'),
					),
				),
			),
			'online'=>array(
				'lang'=>array('ru','en'),
				'url'=>HOST_NAME.'/'.$lang.'/companies/',
				'title'=>$this->view->lang->translate('Trade show Online: Companies, Products, Services'),
				'submenu'=>array(
					'online'=>array(
						'url'=>HOST_NAME.'/'.$lang.'/companies/online/',
						'title'=>$this->view->lang->translate('Online trade show'),
					),
					'categories'=>array(
						'url'=>HOST_NAME.'/'.$lang.'/companies/',
						'title'=>$this->view->lang->translate('Companies catalog by categories'),
					),
					'search'=>array(
						'url'=>HOST_NAME.'/'.$lang.'/companies/search/',
						'title'=>$this->view->lang->translate('All companies'),
					),
				),
			),
			'gallery'=>array(
				'lang'=>array('ru','en'),
				'url'=>'http://tv.expoua.com/Tv/lang/'.$lang.'/',
				'title'=>$this->view->lang->translate('Trade shows videos'),
				'submenu'=>array(),
			),
			'market'=>array(
				'lang'=>array(),
				'url'=>HOST_NAME.'/'.$lang.'/',
				'title'=>$this->view->lang->translate('Business tours'),
				'submenu'=>array(
					//'market_balloons'=>array(
					//	'url'=>HOST_NAME.'/market/balloons/',
					//	'title'=>'Шары',
					//),
				),
			),
		);
	}
	
	protected function setupLayouts(){
		$this->view->banners = array();
	}
	
	protected function initCache() {
		include ROOT_PATH."/data/cache/file/rcc_csc.php";
		foreach($globalFilterCacheArray as $key=>$data){
			Zend_Registry::set ($key, $data);
		}
		unset ($globalFilterCacheArray);
		ArOn_Crud_Tools_Register::registerData();
	}
	
	protected function initLayouts(){
		$this->view->HTMLscript = '';
		$this->view->jsParams = array();
		
		$this->view->layouts = array();
		$this->view->layoutsData = array(
			'head'=>array(),
			'menu'=>array(),
			'top'=>array(),
			'left'=>array(),
			'center'=>array(),
			'extra'=>array(),
			'right'=>array(),
			'bottomRow'=>array(),
			'bottom'=>array(),
		);

		$this->view->layouts['head'] = array(
			"header_left_box"=>array('inc/header/logo', 100),
			"header_banner_box"=>array('inc/header/banner', 100),
			"header_user_box"=>array('inc/header/user', 100),
		);

		$this->view->layouts['menu'] = array(
			'main_menu'=>array('inc/menu/main', 100),
			'sub_menu'=>array('inc/menu/sub', 100),
		);

		$this->view->layouts['top'] = array();
		$this->view->layouts['left'] = array();
		$this->view->layouts['center'] = array();
		$this->view->layouts['extra'] = array();
		$this->view->layouts['right'] = array();
		$this->view->layouts['bottomRow'] = array();
		$this->view->layouts['bottom'] = array(
			'footer_box'=>array('inc/bottom/footer', 100),
		);
		
		$this->setMeta();
		$this->view->test = 0;
	}
	
	protected function setMeta(){
		
		$this->view->host = HOST_NAME;

		$this->view->headLink()
		->headLink(array('rel' => 'shortcut icon', 'href' => '/images/favicon.ico'), 'PREPEND')
		->appendStylesheet('/css/jquery/cupertino/theme.css')
		->appendStylesheet('/css/site.css')
		->appendStylesheet('/css/opera.css');
		
		$this->view->headScript()->appendFile('/js/js.js')
		->appendFile('/js/jquery/min.js')
		->appendFile('/js/fixie.js')
		->appendFile('/js/funcs.js')
		;
	}
	
	protected function initStatistics(){
		$statistics = new Init_Statistics();
		
		$this->view->statistics = array();
		//$this->view->statistics['today_count'] = $statistics->getTodayCount();
		$this->view->statistics['event_count'] = $statistics->getEventsCount();
	}
	
	public function resetUser(){
		$identity = $this->userData['user']['email'];
		$rememberMe = isset($_COOKIE[self::$currentAuth->loginCookie]);
		$this->forgetUser();
		$this->setupUser($identity, $rememberMe);
		$this->initUser();
	}
	
	protected function forgetUser(){
		self::$currentAuth->clearIdentity();
		Zend_Session::forgetMe();
		setcookie(self::$currentAuth->loginCookie,'',time()-3600,'/');
	}
	
	protected function setupUser($identity, $rememberMe = false){
		self::$currentAuth->getStorage()->write(ArOn_Acl_Control_Client::toStorage($identity));
		$this->userData = self::$currentAuth->getStorage()->read();
		if($rememberMe){
			$salt = (defined('PASSWORD_SALT')) ? PASSWORD_SALT: 'login';
			$life_time = (defined('COOKIE_LIFE_TIME')) ? time() + COOKIE_LIFE_TIME: TIME() + 60*60*24*7;
			$crypt = new ArOn_Crud_Tools_Crypt($salt);
			$code = $crypt->encUserId($this->userData['user']['id']);
			$myDomain = preg_replace('/^[^\.]*\.([^\.]*)\.(.*)$/i', '$1.$2',$_SERVER['HTTP_HOST']);
			setcookie(self::$currentAuth->loginCookie,$code,$life_time,'/');
		}
		Zend_Session::rememberMe();
	}

	protected function authUser($params){
		$auth = ArOn_Acl_Auth_Client_Abstract::getInstance();
		$adapter = ArOn_Acl_Auth_Client::getDbAuthAdapter();
		$adapter->setIdentity($params['login'])->setCredential($params['password']);
		$result = $auth->authenticate($adapter);
		if ($result->isValid()) {
			$auth->getStorage()->write(ArOn_Acl_Control_Client::toStorage($result->getIdentity()));
			
			$this->initUser();
		}
	}
	
	protected function initUser(){
		//$this->_isReferal = false;
		$actionName = $this->getRequest()->getActionName();
		$this->view->auth = false;
		//$referal = $this->_request->getCookie('client_referal');
		//if(!empty($referal)) $this->_isReferal = true;
		if (self::$currentAuth->hasIdentity()) {
			$this->userData = self::$currentAuth->getStorage()->read();
			$this->auth = true;
			$this->view->auth = true;
			$this->view->userData = $this->userData;
			$user_session = new Zend_Session_Namespace('client');
			$user_session->id = $this->userData['user']['id'];
			ArOn_Db_Table::$authId = $this->userData['user']['id'];
			$controllerName = $this->getRequest()->getControllerName();
			$location = '/'.$controllerName.'/'.$actionName;
			//if(!empty($this->userData['user']['referal'])){
			//	$referal = new Tools_Referal($this->_request);
			//	$referal->initReferal($this->userData['user']['referal']);
			//	$this->_isReferal = true;
			//}
		}//else{
		//	$referal = new Tools_Referal($this->_request);
		//	if($referal->initReferal())
		//		$this->_isReferal = true;
		//}
		$phpIp2Country = new Tools_Ip2Country(ArOn_Crud_Tools_IpCheck::getClientIp());
		$this->userData ['ip'] = array( 'address' => ArOn_Crud_Tools_IpCheck::getClientIp(), 'country' => $phpIp2Country->getIsoCode() );
		//$this->userData ['ip'] = array( 'address' => ArOn_Crud_Tools_IpCheck::getClientIp(), 'country' => "zz" );
		$this->view->userData = $this->userData;
	}
	
	protected function updateStats(){
		if($this->_request->isXmlHttpRequest()){
			return false;
		}
		$user_id = false;
		if($this->auth){
			$user_id = $this->userData['user']['id'];
		}
		$stats = new Crud_Form_ExtJs_Stats();
		$stats->addItem($this->_statModel,$this->_statModelId,$user_id);
	}
}
