<?PHP

Zend_Loader::loadClass("Sab_Help_ModelAbstract", PATH_MODELS);

class Sab_Help_IndexModel extends Sab_Help_ModelAbstract {

	protected $_DP_name = "List_Joined_Pages";

	public function getEntry($id) {
		$result = parent::getEntry($id);
		return $result;
	}

}