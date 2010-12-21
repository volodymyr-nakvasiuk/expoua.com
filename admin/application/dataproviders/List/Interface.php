<?PHP

interface List_Interface {

	public function getList($results_num = null, $page = null, Array $extraParams = array(), Array $sortBy = array());

	public function insertEntry(Array $data);

	public function updateEntry($id = null, Array $data, Array $extraParams = array());

	public function deleteEntry(Array $id = array(), Array $extraParams = array());

	public function getEntry($id, Array $extraParams = array());

}