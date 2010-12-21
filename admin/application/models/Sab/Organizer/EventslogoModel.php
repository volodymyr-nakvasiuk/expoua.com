<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_EventslogoModel extends Sab_Organizer_ModelAbstract {
	protected $_DP_name = 'List_Joined_Ep_Events';

	public function getEntry($id) {
		$langs_list = $this->getLanguagesList();
		foreach ($langs_list as $el) {
			$event_entry[$el['code']] = $this->_DP("List_Joined_Ep_Events")->getEntry($id, array('languages_id' => $el['id']));
		}

		return $event_entry;
	}

	public function updateEntry($id, Array $data) {

		$img_res = null;

		//Подготовка, проверка что файл логотипа загружен
		if (isset($_FILES) && isset($_FILES['logo'])) 
		{
			$list_langs = $this->getLanguagesList();
			foreach ($list_langs as $lang) 
			{
				if ($_FILES['logo']['error'][$lang['code']]===0) 
				{

					$file_fp = $_FILES['logo']['tmp_name'][$lang['code']];
					$save_as = PATH_FRONTEND_DATA_IMAGES . "/events/logo/" . $lang['id'] . "/" . $id . ".jpg";
					$extraParams = array('image_type' => $_FILES['logo']['type'][$lang['code']]);

					$img_res = $this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, 130, 130, $extraParams);

					if ($img_res) 
					{
						$this->_DP_limit_params['languages_id'] = $lang['id'];
						$data = array('logo' => 1);
						parent::updateEntry($id, $data);
					}
				}
			}
		}

		return 1;
	}

}