<?PHP

Zend_Loader::loadClass("Sab_Servcompany_ModelAbstract", PATH_MODELS);

class Sab_Servcompany_NewsModel extends Sab_Servcompany_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_News';
		
	public function init()
	{
		parent::init();
		$this->_DP_obj = $this->_DP($this->_DP_name);
	}
	
/**
 * Enter description here...
 *
 * @param array $data_raw
 * @param unknown_type $insertAllLangs
 * @return unknown
 */
	public function insertEntry(Array $data_raw, $insertAllLangs = false) {

		if (empty($data_raw['common'])) {
			return -1;
		}

		$data_raw['common']['active'] = 0;
		$data_raw['common']['service_companies_id'] = $this->_user_session->servcompany->service_companies_id;
		

		$id = null;
		$langs_list = $this->_DP("List_Languages")->getList();
		foreach ($langs_list['data'] as $lang) 
		{

			if (empty($data_raw[$lang['code']])) {
				continue;
			}

			$data = array_merge($data_raw[$lang['code']], $data_raw['common']);
			$data = array_merge($data, $this->_DP_limit_params);
			$data['languages_id'] = $lang['id'];

			if (is_null($id)) {
				$res = $this->_DP_obj->insertEntry($data);

				if ($res != 1) {
					return $res;
				}

				$id = $this->_DP_obj->getLastInsertId();
				$data_raw['common']['id'] = $id;
			} else {
				$res = $this->_DP_obj->insertLanguageData($data);
			}

			//Добавление не удалось, прекращаем
			if (!$res) {
				return $res;
			}
		}

		return $res;
	}

	public function getBrandCategoriesList() {
		return $this->_DP("List_Joined_Ep_BrandsCategories")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id), array("name" => "ASC"));
	}

	public function getCountriesList() {
		return $this->_DP("List_Joined_Ep_Countries")->getList(null, null, array('active'=>1, 'languages_id' => self::$_user_language_id), array("name" => "ASC"));
	}
}