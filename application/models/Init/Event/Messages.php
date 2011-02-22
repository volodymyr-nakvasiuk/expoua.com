<?php
class Init_Event_Messages extends Init_EventTab {

	static $tab_name = 'messages';
	protected $_event = false;

	public function __construct($brands_id, $event_id, $tab, $tab_action, $tab_id, $event=array()){
		parent::__construct($brands_id, $event_id, $tab, $tab_action, $tab_id);
		$this->_event = $event;
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
			'organizerId' => $this->_event['organizer_id'],
			'organizerName' => $this->_event['organizer'],
			'captcha' => array(
				'img' => $captcha->render(),
				'id' => $captcha->getId(),
			),
		));
	}
}
