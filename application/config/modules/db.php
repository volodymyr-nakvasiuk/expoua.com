<?php
class db {
	public static $config = array();
	public static $configSection = array();
	public static $configFile = '';

	public static function init(){
		$lang = Zend_Registry::get ('Zend_Translate');

		self::$configSection = array(
			'title'       => array(
				'title'    => $lang->translate('Title'),
				'default'  => COOKIE_HOST_NAME,
			),
			'db.adapter'  => array(
				'title'    => $lang->translate('Adapter'),
				'default'  => 'PDO_MYSQL',
			),
			'db.host'     => array(
				'title'    => $lang->translate('Host'),
				'default'  => 'localhost',
			),
			'db.port'     => array(
				'title'    => $lang->translate('Port'),
				'default'  => '3306',
			),
			'db.username' => array(
				'title'    => $lang->translate('User name'),
				'default'  => '',
			),
			'db.password' => array(
				'title'    => $lang->translate('Password'),
				'default'  => '',
			),
			'db.dbname'   => array(
				'title'    => $lang->translate('Database name'),
				'default'  => '',
			),
		);

		$dir = Bootstrap::$root.'/application/config/db/'.APPLICATION_ENVIRONMENT;
		if (!is_dir($dir)) mkdir($dir, 0777, true);
		self::$configFile = $dir.'/'.Bootstrap::prepareFileName(php_uname()).'.ini';

		if (file_exists(self::$configFile)){
			self::$config = parse_ini_file(self::$configFile, true);
		}
	}

	public static function run(){
		$lang = Zend_Registry::get ('Zend_Translate');

		self::init();

		$fieldsets = array();
		foreach (Bootstrap::$dbConfigs as $sectionName){
			$fieldsets[$sectionName] = array(
				'title'=>'Section "'.$sectionName.'"',
				'fields'=>array(),
			);
			foreach (self::$configSection as $paramName=>$paramConfig){
				$fieldsets[$sectionName]['fields'][] = array(
					'title'=>$paramConfig['title'],
					'name'=>str_replace('.', '_', $sectionName.'___'.$paramName),
					'type'=>'text',
					'class'=>'textbox',
					'value'=>isset(self::$config[$sectionName][$paramName])?self::$config[$sectionName][$paramName]:$paramConfig['default'],
				);
			}
		}
		
		return array(
			'title'=>'<b>'.$lang->translate('Configure database for').'</b><br/>"'.php_uname().'"',
			'module'=>'db',
			'action'=>'save',
			'fieldsets'=> $fieldsets,
		);
	}

	public static function save(){
		self::init();
		$file = array();
		foreach (Bootstrap::$dbConfigs as $sectionName){
			$file[] = '['.$sectionName.']';
			foreach (self::$configSection as $paramName=>$paramConfig){
				$name = str_replace('.', '_', $sectionName.'___'.$paramName);
				$value = isset($_POST[$name])?$_POST[$name]:$paramConfig['default'];
				$file[] = $paramName.' = '.$value;
			}
			$file[] = '';
		}
		file_put_contents(self::$configFile, implode("\r\n", $file));
		return false;
	}
}
