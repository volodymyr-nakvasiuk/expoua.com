<?php
class Init_Services_Messages extends Init_ServicesTab {

	static $tab_name = 'messages';
	protected $_company = false;

	public function __construct($venue_id, $tab, $tab_action, $tab_id, $company=array()){
		parent::__construct($venue_id, $tab, $tab_action, $tab_id);
		$this->_company = $company;
	}

	protected function pageAction(){
		$captcha = new ArOn_Zend_Captcha_Image(array(
			'reloadImg' => '/img/reload-captcha.png',
			'imgUrl'    => '/captcha.php',
			'imgAlt'    => '',
			'width'     => 150,
			'height'    => 80,
		));

		return array('data'=>array(
			'serviceName' => $this->_company['name'],
			'captcha' => array(
				'img' => $captcha->render(),
				'id' => $captcha->getId(),
			),
		));
	}
}
