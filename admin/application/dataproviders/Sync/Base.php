<?php
class Sync_Base extends DataProviderAbstract {
	
	const TYPE_ADD = "add";
	const TYPE_DELETE = "delete";
	const TYPE_UPDATE = "update";
	
	const STATUS_SUCCESS = "success";
	const STATUS_FAIL = "fail";
	const STATUS_NEW = "new";
	
	private static $countries_owned = array(
		52 => 52 // Украина
	);
	
	/**
	 * Проверяет находится ли данная страна в списке разрешенных для редактирования данных
	 * 
	 * @param int $id
	 * @return boolean
	 */
	public static function isCountryOwned($id) {
		return array_key_exists($id, self::$countries_owned);
	}
	
	/**
	 * Добавляет задачу на обновление данных в очередь
	 * Вызывается при обновлении локальных данных на сервере для дальнейшей передачи этих данных
	 * на сервер-арбитр, от куда они будут распростарнены на остальные сервера системы
	 * $provider - базовый провайдер данных, опеределяющий характер изменений
	 * $type - тип изменений, описывается константами TYPE_*
	 * $id - идентификатор измененной записи
	 * $languages_id - язык измененной записи
	 * $global_id - глобальный идентификатор при его наличии, иначе null
	 * Возвращает количество добавленных записей в очередь, в случае успеха - 1
	 * 
	 * @param string $provider
	 * @param string $type
	 * @param int $id
	 * @param int $languages_id
	 * @param int $global_id
	 * @return int
	 */
	public function addToQueue($provider, $type, $id, $languages_id, $global_id = null) {
		$data = array(
			'type' => $type,
			'provider' => $provider,
			'parents_id' => $id,
			'global_sync_id' => null,
			'languages_id' => $languages_id,
			'status' => self::STATUS_NEW
		);
		
		if (is_numeric($global_id)) {
			$data['global_sync_id'] = $global_id;
		}
				
		return self::_DP("List_Sync_Queue")->insertEntry($data);
	}
	
	/**
	 * Вспомогательная функция для поиска подходящего провайдера данных по имени внешнего ключа для замены
	 * Первым параметром передается имя ключа, вторым экземпляр объекта для которого производится
	 * поиск чтобы можно было обрабатывать ситуации с неоднозначными именами ключей
	 * Возвращает экземпляр класса или NULL если подходящий класс не найден
	 * 
	 * @param string $key
	 * @param List_Abstract $dpObj
	 * @return NULL|List_Abstract
	 */
	public static function getDpByKeyName($key, List_Abstract $dpObj) {
		$tmpObj = null;
		
		switch ($key) {
			case "organizers_id":
				$tmpObj = self::_DP("List_Joined_Ep_Organizers");
				break;
			case "brands_id":
				$tmpObj = self::_DP("List_Joined_Ep_Brands");
				break;
			case "events_id":
				$tmpObj = self::_DP("List_Joined_Ep_Events");
				break;
			case "brands_categories_id":
				$tmpObj = self::_DP("List_Joined_Ep_BrandsCategories");
				break;
			case "parent_id": // brands_subcategories.parent_id
				// Нестандартное название столбца
				if ($dpObj instanceof List_Joined_Ep_BrandsSubCategories) {
					$tmpObj = self::_DP("List_Joined_Ep_BrandsCategories");
				}
				break;
			case "brands_subcategories_id": // нет нигде, но это правильный вариант
			case "subcategories_id": // brands_to_subcategories.subcategories_id
				$tmpObj = self::_DP("List_Joined_Ep_BrandsSubCategories");
				break;
			case "regions_id":
				$tmpObj = self::_DP("List_Joined_Ep_Regions");
				break;
			case "countries_id":
				$tmpObj = self::_DP("List_Joined_Ep_Countries");
				break;
			case "cities_id":
				$tmpObj = self::_DP("List_Joined_Ep_Cities");
				break;
			case "expocenters_id":
				$tmpObj = self::_DP("List_Joined_Ep_Expocenters");
				break;
			case "social_organizations_id":
				$tmpObj = self::_DP("List_Joined_Ep_Socorgs");
				break;
		}
		
		return $tmpObj;
	}
	
}