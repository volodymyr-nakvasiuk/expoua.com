<?php
class Init_Services{
	protected $_modulesData = false;
	protected $_modules = false;
	protected $_data = false;

	public function __construct($modules=array()){
		$modules = is_array($modules)?$modules:array($modules);
		$translator = Zend_Registry::get ( 'Zend_Translate');
		$this->_modulesData=array(
			'Hotels_Advert'=>array(
				'title'=>$translator->translate('Hotels'),
			),
			'Stands_Advert'=>array(
				'title'=>$translator->translate('Stands'),
			),
			'Translators_Advert'=>array(
				'title'=>$translator->translate('Translators'),
			),
			'Polygraphy_Advert'=>array(
				'title'=>$translator->translate('Polygraphy'),
			),
		);

		$this->_modules = array();
		foreach ($modules as $module){
			$key = $module.'_Advert';
			if (array_key_exists($key, $this->_modulesData)){
				$this->_modules[$key] = $this->_modulesData[$key];
			}
		}
	}

	public function getData(){
		if (!$this->_data) $this->_setData();
		return $this->_data;
	}

	protected function _setData() {
		$this->_data = array();
		foreach ($this->_modules as $module=>$moduleData){
			$tool = new Tools_Banner(null, $module);
			$this->_data[$module] = array('module'=>$moduleData, 'data'=>$tool->getData());
			$tool->updateStat();
		}
	}
}
