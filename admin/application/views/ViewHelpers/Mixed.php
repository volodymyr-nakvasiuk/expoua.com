<?PHP
Zend_Loader::loadClass("ViewHelpers_Abstract", PATH_VIEWS);
require_once(PATH_DATAPROVIDERS . "/Sync/Base.php");

class ViewHelpers_Mixed extends ViewHelpers_Abstract {

	/**
	 * Функция получения значения сортировки для колонки
	 * Передается имя колонки и текущее значение сортировки
	 *
	 * @param string $colName
	 * @param string $currentSort
	 * @return string
	 */
	public function getSortOrder($colName, $currentSort) {
		if (is_array($currentSort) && array_key_exists($colName, $currentSort)) {
			$sort = ($currentSort[$colName]=='DESC' ? 'ASC':'DESC');
		} else {
			$sort = 'DESC';
		}
		return $colName . ":" . $sort;
	}

	/**
	 * Возвращает значение конфигурационной константы
	 *
	 * @param string $code
	 * @return string
	 */
	public function getConfigConstValue($code) {
		return $this->_DP("List_OptionsConstants")->getValueByCode($code);
	}

	/**
	 * Возвращает распечатку переденных данных
	 * Полезна при отладке
	 *
	 * @param mixed $data
	 * @return string
	 */
	public function dump($data) {
		return Zend_Debug::dump($data, null, false);
	}

	/**
	 * Кодирует данные в их Json-представление
	 *
	 * @param mixed $data
	 * @return string
	 */
	public function toJson($data) {
		require_once("Zend/Json/Encoder.php");
		return Zend_Json_Encoder::encode($data);
	}
	
	/**
	 * Проверяет можно ли изменять данные для этой страны
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public function isCountryOwned($id) {
		return Sync_Base::isCountryOwned($id);
	}

}