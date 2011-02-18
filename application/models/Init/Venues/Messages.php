<?php
class Init_Venues_Messages extends Init_VenuesTab {

	static $tab_name = 'messages';
	protected $_venue = false;

	public function __construct($venue_id, $tab, $tab_action, $tab_id, $venue=array()){
		parent::__construct($venue_id, $tab, $tab_action, $tab_id);
		$this->_venue = $venue;
	}

	protected function pageAction(){
		$captcha = new ArOn_Zend_Captcha_Image(array(
			'reloadImg' => '/img/reload-captcha.png',
			'imgUrl'    => '/captcha.php',
			'imgAlt'    => 'Капча',
			'width'     => 150,
			'height'    => 80,
		));

		return array('data'=>array(
			'venueName' => $this->_venue['name'],
			'captcha' => array(
				'img' => $captcha->render(),
				'id' => $captcha->getId(),
			),
		));
	}
}
