<?PHP
require_once(PATH_CONTROLLERS . "/Admin/ListControllerAbstract.php");

class Admin_Ep_Pblbanners_StatController extends Admin_ListControllerAbstract {

  public function listAction() {
		$search = $this->getRequest()->getUserParam("search", null);
		
		if ($search) {
		  $filter = array();
		  
		  $searches = explode(";", $search);
      foreach ($searches as $el) {
        preg_match("/^([a-z_]+)([<>=~:])(.*)$/i", $el, $tmp);
        $var = $tmp[1]; $delim = $tmp[2]; $val = $tmp[3];  
        if ($var == 'date_start') { 
          $this->_view->date_start = $val;
        } else if ($var == 'date_end') {
          $this->_view->date_end   = $val;
        } else {
          $filter[$var] = $delim . $val;
        }
      }
      
      $this->_view->filter = $filter;
		}
  
    parent::listAction();
  }

}