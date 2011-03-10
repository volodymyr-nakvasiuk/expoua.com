<?php
class ArOn_Acl_Auth_Client extends ArOn_Acl_Auth {
	static function getDbAuthAdapter() {
		$db = ArOn_Db_Table::getDefaultAdapter(false);
		$tableName = 'users_companies';
		$identityColumn = 'login';
		$credentialColumn = 'passwd';
		$credentialTreatment = '(?) AND (active = 1)';
		return new ArOn_Zend_Auth_Adapter_DbTable($db, $tableName, $identityColumn, $credentialColumn, $credentialTreatment);
	}
}
?>