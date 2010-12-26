<?php
class auth {
	public static $config = array();
	public static $logged = false;
	public static $authFile = '';

	public static function run(){
		$lang = Zend_Registry::get ('Zend_Translate');

		$dir = Bootstrap::$root.'/application/config/admin';
		if (!is_dir($dir)) mkdir($dir, 0777, true);
		self::$authFile = $dir.'/'.Bootstrap::prepareFileName(php_uname()).'.ini';

		if (file_exists(self::$authFile)){
			self::$config = parse_ini_file(self::$authFile);
			if (isset($_SESSION['config_password']) && $_SESSION['config_password'] == self::$config['password']){
				self::$logged = true;
			}
		}
		else {
			self::$logged = true;
			$changePassword = isset($_POST['module']) && $_POST['module'] == 'auth' && isset($_POST['action']) && $_POST['action'] == 'config_password';
			if (!$changePassword){
				return array(
					'title'=>'<b>'.$lang->translate('Configuring access for').'</b><br/>"'.php_uname().'"',
					'module'=>'auth',
					'action'=>'config_password',
					'fieldsets'=>array(
						array(
							'title'=>$lang->translate('Administrator'),
							'fields'=>array(
								array(
									'title'=>$lang->translate('Choose a password'),
									'name'=>'config_password',
									'type'=>'password',
									'class'=>'password',
									'value'=>'',
								),
								array(
									'title'=>$lang->translate('Re-enter password'),
									'name'=>'config_password_re',
									'type'=>'password',
									'class'=>'password',
									'value'=>'',
								),
							),
						),
					),
				);
			}
		}

		$loggining = isset($_POST['module']) && $_POST['module'] == 'auth' && isset($_POST['action']) && $_POST['action'] == 'login';
		if (!self::$logged && !$loggining){
			return array(
				'title'=>'<b>'.$lang->translate('Logging to').'</b><br/>"'.php_uname().'"',
				'module'=>'auth',
				'action'=>'login',
				'fieldsets'=>array(
					array(
						'title'=>$lang->translate('Administrator'),
						'fields'=>array(
							array(
								'title'=>$lang->translate('Enter password'),
								'name'=>'password',
								'type'=>'password',
								'class'=>'password',
								'value'=>'',
							),
						),
					),
				),
			);
		}

		return false;
	}

	public static function config_password(){
		$lang = Zend_Registry::get ('Zend_Translate');
		if (self::$logged){
			if (!$_POST['config_password']){
				return $lang->translate('Password can\'t be empty.');
			}
			if ($_POST['config_password'] == $_POST['config_password_re']){
				$data = array(
					'password = '.$_POST['config_password'],
					'test = '.$_POST['config_password'],
				);
				file_put_contents(self::$authFile, implode("\r\n", $data));
			}
			else {
				return $lang->translate('Password and it\'s confirmation are not the same.');
			}
			return false;
		}
	}

	public static function login(){
		$lang = Zend_Registry::get ('Zend_Translate');
		if ($_POST['password']){
			if ($_POST['password'] == self::$config['password']){
				$_SESSION['config_password'] = $_POST['password'];
				self::$logged = true;
			}
			else {
				return $lang->translate('Wrong password.');
			}
		}
		else {
			return $lang->translate('Enter password, please.');
		}
		return false;
	}
}
