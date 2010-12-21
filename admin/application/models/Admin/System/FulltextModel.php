<?PHP
require_once(PATH_MODELS . "/Admin/ModelAbstract.php");

class Admin_System_FulltextModel extends Admin_ModelAbstract {

	/**
	 * Возвращает информацию о состоянии индекса для контентных страниц
	 * 
	 * @return array
	 */
	public function getCmsPagesStat() {
		$dpObj = $this->_DP("Fulltext_Info_Cms_Pages");
		
		return $this->_getGeneralStat($dpObj);
	}
	
	public function getCompaniesStat() {
		$dpObj = $this->_DP("Fulltext_Info_Companies");
		
		return $this->_getGeneralStat($dpObj);
	}

	public function getCompaniesServicesStat() {
		$dpObj = $this->_DP("Fulltext_Info_Companies_Services");
		
		return $this->_getGeneralStat($dpObj);
	}
	
	/**
	 * Стандартная функция для получения статистики
	 * В качестве параметра принимает ссылку на объект-обработчик статистики
	 * 
	 * @param Fulltext_Info_Abstract $dpObj
	 * @return array
	 */
	private function _getGeneralStat(Fulltext_Info_Abstract &$dpObj) {
		$result = array();
		
		$dpObj->setLanguage(
			Zend_Registry::get('language_code'),
			Zend_Registry::get('language_id')
		);
		
		$result['documents'] = $dpObj->getDocumentsCount();
		$result['words'] = $dpObj->getWordsCount(false);
		$result['words_distinct'] = $dpObj->getWordsCount(true);
		$result['words_top'] = $dpObj->getTopWords(20, 1);
				
		return $result;
	}

}