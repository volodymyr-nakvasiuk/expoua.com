<?PHP

Zend_Loader::loadClass("Sab_Operator_ModelAbstract", PATH_MODELS);

class Sab_Operator_BrandseventsModel extends Sab_Operator_ModelAbstract {

	protected $_DP_name = 'List_Joined_Ep_BrandPlusEvent';
	
	public function init() {
		parent::init();
		$this->_DP_limit_params = array_merge($this->_DP_limit_params, array('is_last' => 1));
	}
	
	/**
	 * Устанавливает режим показа всего списка бренд+событие
	 * @return void
	 */
	public function setShowAllMode() {
		unset(
			$this->_DP_limit_params['countries_id'],
			$this->_DP_limit_params['_only_wfe'],
			$this->_DP_limit_params['is_last']
		);
	}
	
	public function getDistinctDraftsBrandsList() {
		//Zend_Debug::dump($this->_user_session->operator->id);
		return $this->_DP("List_Joined_Ep_EventsDrafts")->
			getDistinctDraftsBrandsList($this->_user_session->operator->id);
	}
	
	//Пришлось переопределить чтобы фильтр по странам работал
	public function getList($page = null, $sort = null, $search = null) {

		$search_save = $search;
		$extraParams = $this->_DP_limit_params;

		if (is_array($sort)) {
			$sortOrder = $sort;
		} else {
			$sortOrder = array();
		}

		//Подготавливаем параметры поиска
		if (!is_null($search)) {
			foreach ($search as $el) {
				if ($el['column'] == 'countries_id' && in_array($el['value'], $extraParams['countries_id'])) {
					$extraParams['countries_id'] = $el['value'];
					break;
				}
			}
			
			$search = $this->_prepareSearchConditions($search);
			$extraParams = array_merge($search, $extraParams);
			
		}
		
		if (is_null($this->forceListResults)) {
			$this->forceListResults = $this->getConfigValue("GENERAL_ELEMENTS_PER_PAGE");
		}
		
		$this->_DP_obj->getBrandDraftsCnt = true;

		return array_merge(array('search' => $search_save), $this->_DP_obj->getList($this->forceListResults, $page, $extraParams, $sortOrder));

	}

}