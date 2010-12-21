<?PHP
require_once(PATH_MODELS . "/Admin/ListModelAbstract.php");

class Admin_Ep_Partners_MessagesModel extends Admin_ListModelAbstract {

  protected $_DP_name = 'List_Joined_Ep_PartnersMessages';

  protected $_DP_limit_params = array('type' => 'partner');

}