<?php
class ArOn_Acl_Control_Client extends ArOn_Acl_Controlv2 {
 
	protected static $_instance = null;
 
	private function __construct()
	{}
 
	private function __clone()
	{}
 	
	protected function _initialize()
	{		
		$model = Db_AclRolePrivileges::getInstance();
		$select = $model->select();
		$select->columnsJoinOne('Db_AclPrivileges',array('acl_privilege_name'));
		$select->columnsJoinOne(array('Db_AclPrivileges','Db_AclResources'),array('acl_resource_name'));
		$select->columnsJoinOne(array('Db_AclPrivileges','Db_AclResources','Db_AclModules'),array('acl_module_name'));
		/*$roles = $db->fetchAll("SELECT
				acl_role_privilege.acl_role_id, 
				acl_module.acl_module_name,
				acl_resource.acl_resource_name,
				acl_privilege.acl_privilege_name
				FROM acl_role_privilege
				INNER JOIN acl_privilege 
				ON acl_role_privilege.acl_privilege_id = acl_privilege.acl_privilege_id
				INNER JOIN acl_resource
				ON acl_privilege.acl_resource_id = acl_resource.acl_resource_id
				INNER JOIN acl_module
				ON acl_resource.acl_module_id = acl_module.acl_module_id");*/

		$result = $model->fetchAll($select);
		if($result === null)
			return false;
		else
			$roles = $result->toArray();
			
		foreach ($roles as $role) {
			if (!$this->has($role['acl_module_name'].'_'.$role['acl_resource_name'])) {
				$this->add(new Zend_Acl_Resource($role['acl_module_name'].'_'.$role['acl_resource_name']));
			}
			if (!$this->hasRole($role['acl_role_id'])) {
				$this->addRole(new Zend_Acl_Role($role['acl_role_id']));
			}
		}
 
		$this->deny();
		//$this->allow(null, $role['acl_module_name'] . '_login');
 
		foreach ($roles as $role) {
			$this->allow($role['acl_role_id'], $role['acl_module_name'].'_'.$role['acl_resource_name'], $role['acl_privilege_name']);
		}

	}
	
	public static function toStorage($identity) {
		$storage = array();
		$model = Db_User::getInstance();
		$select = $model->select();
		$select->columnsAll();
		$select->where('login = ?',$identity);
		
		if (null === ($row = $model->fetchRow($select))) {
			throw new Exception('Wrong user! Mystical fail!');
		}
		$row = $row->toArray();
		$storage['user']['id'] = $row['id'];
		$storage['user']['name'] = $row['name'];
		$storage['user']['password'] = $row['passwd'];
		//$storage['user']['referal'] = $row['client_referal'];
		//$storage['user']['tel'] = $row['client_tel'];
		$storage['user']['email'] = $row['email'];
		//$storage['user']['url'] = $row['client_url'];
		//$storage['user']['region_id'] = $row['client_region_id'];
		//$storage['user']['place_id'] = $row['client_place_id'];
		//$storage['user']['info'] = $row['client_info'];
		//$storage['user']['addr'] = $row['client_addr'];
		//$storage['user']['photos'] = $row['client_photos'];
		//$storage['user']['priority'] = $row['client_priority'];
		$storage['user']['role'] = 3; // client
		$storage['user']['companies_id'] =  $row['companies_id'];
		
		return $storage;
	}
	
	public static function getInstance()
    {
	   if (null === self::$_instance) {
		self::$_instance = new self();
		self::$_instance->_initialize();
	   }
 
	   return self::$_instance;
    }
 
}