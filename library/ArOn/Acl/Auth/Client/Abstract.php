<?php
class ArOn_Acl_Auth_Client_Abstract extends ArOn_Acl_Auth_Abstract {
	public $loginCookie = 'info_client';
	protected $userLoginField = 'login';
	protected $userPasswordField = 'passwd';
	protected $userCredentialTreatment = '(?) AND active = 1 ';
	
	protected function initUserAuthAndDb(){
		$this->userDB = Db_User::getInstance();
 		$this->userAuth = ArOn_Acl_Auth_Client::getDbAuthAdapter();
	}
 	
	protected function storageWriteControl($identity){
 		$this->getStorage()->write(ArOn_Acl_Control_Client::toStorage($identity));
 	}
}
