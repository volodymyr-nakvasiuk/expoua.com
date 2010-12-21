<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_EventsfilesModel extends Sab_Organizer_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_EventsFiles';

	public function insertEntry(Array $data, $insertAllLangs = false) {
		return parent::insertEntry($data, false);
	}
}