<?PHP
Zend_Loader::loadClass("Zend_Acl");
Zend_Loader::loadClass("Zend_Acl_Role");
Zend_Loader::loadClass("Zend_Acl_Resource");

class Acl_Admin extends DataProviderAbstract {

  /**
  * Экзкемпляр объекта Zend_Acl
  *
  * @var Zend_Acl
  */
  private $_acl;

  /**
  * Отношение группа-родитель
  * Ключ - id группы, значение - id родителя
  *
  * @var array
  */
  private $_groups_parent = array();

  private $_resources_actions = array();

  /**
  * Инициализируем систему прав доступа
  * TO-DO: кеширование
  *
  */
  public function start($username) {
    $this->_acl = new Zend_Acl();

    $resources_dp = Zend_Registry::get("Shelby_ModelObj")->_DP('List_AclResources');
    $resources_list = $resources_dp->getList(null, null, array('super' => 1, 'installed' => 1));

    //Добавляем в ACL список ресурсов
    foreach ($resources_list['data'] as $res_el) {
      $this->_acl->add(new Zend_Acl_Resource($res_el['code']));
    }

    $groups_dp = Zend_Registry::get("Shelby_ModelObj")->_DP('List_AclAdminsGroups');

    //Получаем список групп, в которые входит текущий пользователь
    $admins_dp = Zend_Registry::get("Shelby_ModelObj")->_DP('List_AclAdmins');
    $groups_list = $admins_dp->getUserGroupsNamesList($username);

    $this->_addParentGroups($groups_list);

    try {
    //Добавляем список групп и устанавливаем для каждой из них права доступа
    foreach ($groups_list as $group_el) {

      $parent_group = (is_null($group_el['parent_group_id']) ? null:$groups_list[$group_el['parent_group_id']]['name']);

      $this->_acl->addRole(new Zend_Acl_Role($group_el['name']), $parent_group);

      $group_resources = $groups_dp->getAclResourcesList($group_el['id']);
      foreach ($group_resources as $el_res_id) {

        //Получаем массив разрешенных действий
        $actions = $this->_getAllowedActionsList($el_res_id, $group_el['id']);
        //Zend_Debug::dump($actions);

        $resource_code = $resources_list['data'][$el_res_id]['code'];

        $this->_acl->allow($group_el['name'], $resource_code, (empty($actions) ? null:$actions));

        $actions = array_fill_keys($actions, true);
        if (array_key_exists($resource_code, $this->_resources_actions) && is_array($this->_resources_actions[$resource_code])) {
          $this->_resources_actions[$resource_code] = array_merge($actions, $this->_resources_actions[$resource_code]);
        } else {
          $this->_resources_actions[$resource_code] = (empty($actions) ? true:$actions);
        }
      }
    }

    } catch (Exception $e) {
      die($e->getMessage());
    }
  }

  /**
  * Возвращает Id родительской группы. Если родителя не существует, возвращает null
  *
  * @param int $id
  * @return int
  */
  public function getGroupParentId($id) {
    return (isset($this->_groups_parent[$id]) ? $this->_groups_parent[$id]:null);
  }

  /**
  * Возвращает массив, состоящий из id групп, которые являются родительской для той, что указана в качестве параметра
  * Учитывается все дерево наследования
  *
  * @param int $id
  * @return array
  */
  public function getGroupParentArray($id) {
    $result = array();

    $tmp_group_id = $id;
    while (isset($this->_groups_parent[$tmp_group_id])) {
      $tmp_group_id = $this->_groups_parent[$tmp_group_id];
      $result[] = $tmp_group_id;
    }

    return $result;
  }

  /**
  * Проверка прав доступа пользователя к запрашиваемому ресурсу
  *
  * @param string $userName
  * @param string $resourceName
  * @return boolean
  */
  public function isAllowed($userName, $resourceName, $action) {

    $res = false;

    $groups_list = Zend_Registry::get("Shelby_ModelObj")->
                  _DP('List_AclAdmins')->
                  getUserGroupsNamesList($userName);

    try {
      foreach ($groups_list as $groupName) {
        $res = $this->_acl->isAllowed($groupName['name'], $resourceName, $action);
        if ($res === true) {
          break;
        }
      }
    } catch (Zend_Acl_Exception $e) {
      echo "No such module registered! (DataProviders->Acl->Admin)";
      $res = false;
    }

    return $res;
  }

  /**
  * Возвращает список доступных пользователю $userName модулей системы и действий
  *
  * @param string $userName
  * @return array
  */
  public function getAllowedResourcesList($userName) {
    return $this->_resources_actions;
  }

  /**
  * Возвращает список доступных действий над ресурсом для текущей группы
  *
  * @param int $resource_id
  * @param int $group_id
  * @return array
  */
  private function _getAllowedActionsList($resource_id, $group_id) {
    $sub_select = self::$_db->select();

    $sub_select->from("acl_admin_resources_restricted_actions", array('resources_actions_id'));

    $sub_select->where("acl_admin_resources_restricted_actions.admin_groups_id = ?", $group_id);
    $sub_select->where("acl_admin_resources_restricted_actions.resources_id = ?", $resource_id);

    $select = self::$_db->select();

    $select->from("acl_resources_actions", array("code"));
    $select->join("acl_resources_to_actions", "acl_resources_actions.id=acl_resources_to_actions.actions_id", array());
    $select->where("acl_resources_to_actions.resources_id = ?", $resource_id);

    $subquery = $sub_select->__toString();
    $select->where("id NOT IN (" . $subquery . ")");

    //Zend_Debug::dump($select->__toString());

    return self::$_db->fetchCol($select);
  }

  /**
  * Дополняем список родительскими группами
  *
  * @param array $groups_list
  */
  private function _addParentGroups(&$groups_list) {
    //Получаем полный список групп
    $groups_dp = Zend_Registry::get("Shelby_ModelObj")->_DP('List_AclAdminsGroups');
    $groups_all_list = $groups_dp->getList();
    $groups_all_list = $groups_all_list['data'];

    //Строим массив отношений
    foreach ($groups_all_list as $group_el) {
      //Добавляем отношения в массив
      $this->_groups_parent[$group_el['id']] = $group_el['parent_group_id'];
    }

    $tmp = $groups_list;
    foreach ($tmp as $el) {
      if (!is_null($el['parent_group_id'])) {
        foreach ($this->getGroupParentArray($el['id']) as $el1) {
          $groups_list[$el1] = $groups_all_list[$el1];
        }
      }
    }

    //Необходимо добавлять в таком порядке чтобы при составлении ACL родительские группы были первыми

    $tmp = $groups_list;
    $tmp1 = $groups_list;
    $groups_list = array();

    foreach ($tmp as $el) {
      foreach ($tmp1 as $el1) {
        if (array_key_exists($el1['parent_group_id'], $groups_list) || is_null($el1['parent_group_id'])) {
          $groups_list[$el1['id']] = $el1;
          unset($tmp1[$el1['id']]);
        }
      }
    }

  }

}