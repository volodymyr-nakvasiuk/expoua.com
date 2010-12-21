<?PHP

Zend_Loader::loadClass("List_Joined_Ep_Abstract", PATH_DATAPROVIDERS);

class List_Joined_Ep_EventsFiles extends List_Joined_Ep_Abstract {

	protected $_db_tables_array = array('events_files');

	protected $_db_tables_join_by = array();

	protected $_allowed_cols_array = array(
		array('id', 'events_id', 'filename', 'content_type', 'name', 'size')
	);

	protected $_select_list_cols_array = array(array('id', 'events_id', 'name', 'size'));

	protected $_sort_col = array('id' => 'DESC');

	public function insertEntry(Array $data) {
		if (!isset($_FILES) || sizeof($_FILES) == 0) {
			return 0;
		}

		$files = $_FILES;
		$files = array_pop($files);

		if ($files['error'] == 0) {

			$data = array_merge($data, array('content_type' => $files['type'], 'filename' => $files['name'], 'size' => $files['size']));

			$res = parent::insertEntry($data);

			if ($res) {
				$id = $this->getLastInsertId();

				$file_data = array("type" => 'file', 'basePath' => PATH_FRONTEND_DATA_EVENT_FILES, 'path' => '/', 'name' => (string)$id);

				$res = Zend_Registry::get("Shelby_ModelObj")->_DP("Filesystem_Files")->insertEntry($file_data);

				if (!$res) {
					$this->deleteEntry($id);
				}

				return $res;
			}
		}

		return 0;
	}

	public function deleteEntry(Array $id = array(), Array $extraParams = array()) {
		$res = parent::deleteEntry($id, $extraParams);

		if ($res) {
			Zend_Registry::get("Shelby_ModelObj")->_DP("Filesystem_Files")->deleteEntry($id, array('basePath' => PATH_FRONTEND_DATA_EVENT_FILES . "/"));
		}

		return $res;
	}

	protected function _SqlAddsList(Zend_Db_Select &$select) {
		parent::_SqlAddsList($select);

		$select->joinLeft("events", "events.id = events_files.events_id", array());
		$select->joinLeft("brands_data", "events.brands_id = brands_data.id AND brands_data.languages_id=1", array("brand_name" => 'name'));
	}

}