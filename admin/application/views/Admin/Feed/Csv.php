<?PHP

Zend_Loader::loadClass("Admin_Feed", PATH_VIEWS);

class Admin_Feed_Csv extends Admin_Feed {

  private $_data = array();

  protected function init() {
    Zend_Controller_Front::getInstance()->getResponse()->setHeader("Content-Type", "text/csv; charset=utf-8", true);
    Zend_Controller_Front::getInstance()->getResponse()->setHeader("Content-Disposition", "attachment; filename=\"list.csv\"", true);
  }

  public function assign($spec, $value = null) {
    if (!in_array($spec, $this->_skipElements)) {
      $this->_data[$spec] = $value;
    }
  }

  public function render($name) {
    $out = fopen('php://output', 'w');

    $delim = ";";
    $quote = '"';
  
    if (isset($this->_data['list']) && !empty($this->_data['list']['data'])) {
      $list = array_values($this->_data['list']['data']);
      $headers = array_keys($list[0]);
      
      if (isset($this->_data['list_interpret']) && !empty($this->_data['list_interpret'])) {
        $list_interpret = $this->_data['list_interpret'];
      } else {
        $list_interpret = array();
        foreach ($headers as $header) $list_interpret[$header] = array('title' => $header);
      }

      // Вывод заголовка CSV файла
      $csv_list = array();
      foreach ($list_interpret as $header => $el) {
        $csv_list[] = mb_convert_encoding($el['title'], "CP1251");
      }
      fputcsv($out, $csv_list, $delim, $quote);
      
      // Вывод тела CSV файла
      foreach ($list as $line) {
        $csv_list = array();

        foreach ($list_interpret as $key => $rule) {
          $field = $line[$key];

          if (isset($rule['substitute'])) $field = $rule['substitute'][$field];
          
          $csv_list[] = mb_convert_encoding($field, "CP1251");
        }
  
        fputcsv($out, $csv_list, $delim, $quote);
      }
    }
  }

}