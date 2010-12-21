<?PHP
Zend_Loader::loadClass("Admin_View", PATH_VIEWS);

class Admin_Company_View extends Admin_View {

  public function getUrl(Array $params) {
    $site = isset($params['site']) ? $params['site'] : ($this->site_ref ? $this->site_ref : "expotop_ru");
    unset($params['site']);
    $params['site'] = $site;

    return parent::getUrl($params);
  }

}