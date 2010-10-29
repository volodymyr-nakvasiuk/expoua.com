<?PHP
/**
* Основной класс, обрабатывающий SOAP - вызовы
*
*/
class Optimized_ExpoPromoterSOAP {

  /**
  * В этой переменной содержится отметка времени начала выполнения скрипта,
  * чтобы вдальнейшем можно было узнать время его выполнения.
  *
  * @var Double
  */
  private $startExecTime;

  /**
  * Указатель ошибки. Устанавливается в true в случае генерации исключения одной из функций.
  *
  * @var boolean
  */
  private $error = false;

  /**
  * Сообщение об ошибке. В случа ее возникновения в конструкторе содержит массив из сообщения и кода ошибки.
  *
  * @var unknown_type
  */
  private $errorMessage = null;

  /**
  * Параметры данной сессии. Устанавливается при помощи вызова EPInit.
  * Здесь же задаются параметры по-умолчанию.
  * Здесь же сохраняются данные по таргетингу для баннерки countryId - текущая страна и categoryId - текущая категория выставок.
  *
  * @var Array
  */
  protected $sessParams = Array('lang' => 'ru', 'module' => null, 'langId' => 1);

  /**
  * Допустимые значения параметров.
  * TODO: должно формироваться динамически на основе текущей структуры.
  *
  * @var Array
  */
  private $allowedParams = Array(
    'languages' => Array('ru', 'en')
  );

  /**
  * Экземпляр класса кеширования.
  *
  * @var Object
  */
  protected $cacheInst;

  /**
  * Тестовая функция.
  *
  * @param Any type $mess
  * @return Inherit type
  */
  public function ping($mess) {
    return $mess;
  }

  /**
  * Функция инициализации. Должна вызываться первой чтобы установить общие для
  * последующих вызовов в рамках данного запроса параметров. Кроме этого она
  * проверяет права доступа сайта к системе. Если права отсутсвуют, генерируется
  * исключение и возвращается сообщение об ошибке, обработка запроса прекращается.
  * Параметры:
  * key - ключ доступа клиента.
  * lang - язык, на котором будут возвращаться данные.
  * ip - IP - адрес клиента, запросившего страницу.
  * referer - Referer запроса клиента
  * request_params - параметры запроса
  *
  * @param String $key
  * @param String $lang
  * @param String $ip
  * @param String $referer
  * @param Array $request_params
  * @return Boolean
  */
  final private function EPInit($key, $lang, $ip, $referer = null, $request_params = null) {
    //Устанавливаем язык по-умолчанию для сессии.
    $this->setLanguage($lang);

    $this->sessParams['clientIP'] = $ip;
    $this->sessParams['siteIP'] = $_SERVER['REMOTE_ADDR'];
    $this->sessParams['key'] = $key;
    $this->sessParams['referer'] = $referer;
    $this->sessParams['request_params'] = $request_params;

    //Устанавливаем язык кеширования. Делаем это в первой вызываемой функции.
    //$this->cacheInst->setLanguage($this->sessParams['lang']);

    $checkRes = Optimized_EPUtils::checkSitePermissions($this->sessParams['siteIP'], $key);

    $this->sessParams['siteID'] = $checkRes;

    if ($checkRes === false) {
      throw new Exception("Site access check fail. Contact ExpoPromoter please. Remote IP: " . $this->sessParams['siteIP'], 5);
    } else {
      return true;
    }
  }

  /**
  * Функция устанавливает текущий язык сессии. Ее стоит использовать только в исключительных ситуациях. Например когда необходимо получить часть данных на одном языке, а часть на другом.
  * Возвращает установленный язык
  *
  * @param String $lang
  */
  public function setLanguage($lang) {
    if (in_array($lang, $this->allowedParams['languages'])) {
      $this->sessParams['lang'] = $lang;
    }

    $stmt = DB_PDO::getInst()->prepare("SELECT id FROM languages WHERE code = ?");
    $stmt->execute(array($this->sessParams['lang']));

    $this->sessParams['langId'] = $stmt->fetchColumn();
    Optimized_EPUtils::setLanguageId($this->sessParams['langId']);

    return $this->sessParams['lang'];
  }

  //Функции списков

  /**
  * Возвращает все выставки, удовлетворяющие заданному критерию.
  * В массиве $params передаются возможные параметры поиска:
  * yearFrom, monthFrom, dayFrom - начало периода выборки
  * yearTo, monthTo, dayTo - конец периода выборки
  * year, month - произвести выборку только за этот год или месяц.
  * regionId - номер региона
  * countryId - номер страны
  * cityId - номер города
  * categoryId - категория
  * subCategoryId - подкатегория
  * exCenter - экспоцентр
  * organizerId - организатор
  * orderBy - сортировать по: name - имени, date - дате
  * nameFilter - возвращать только выставки, содержащие в своем названии это значение
  * jumpToNow - если установлен, то функция возвращает выставки, начиная с текущей даты, автоматически выставляя year, month и page в правильные значения.
  * ids - массив, содержащий id выставок для выборки
  * returnFields - массив, содержащий имена полей, которые необходимо вернуть. Если не установлен или пустой, возвращаются все поля. Возможные значения: id, exName, ... TODO. id Возвращается всегда, даже если не указан в списке.
  *
  * @param Integer $results_num
  * @param Integer $page
  * @param Array $params
  * @return Array
  */
  protected function getExhibitionsList ($results_num, $page, $params = Array(), $params2 = null) {
	 
    $result = array();
    if (!$params || !is_array($params))
        $params = array();

    //Добавляем возможно установленные ранее в сессии параметры поиска
    if (isset($this->sessParams['getExhibitionsListParams']) && ($p = $this->sessParams['getExhibitionsListParams']) && is_array($p))
        $params = array_merge($params, $p);

    //Инициализируем вспомогательный класс
   
    Optimized_EPUtils::utilsInit('ExhibitionsList', $params, $this->sessParams['lang']);

    //Обработка флага перемещения к текущей дате
    if (isset($params['jumpToNow'])) {
      $page = Optimized_EPUtils::getJumpToNowPage($results_num, $params);
    }

    $result['reqParams'] = array('resultsNum' => $results_num, 'params' => $params);

    //Если выбрана подкатегория, то фильт по главной категории игнорируем (с) Загоруйко
    if (!empty($params['subCategoryId'])) {
      unset($params['categoryId']);
    }
  
    $subQuery = Optimized_EPUtils::createBaseQuery($params, $params2);
  

	
    $subQuery .= " ORDER BY period_date_from, organizerId";

    //Формирование запроса для получения выставок
    if (!empty($params['returnFields']) && is_array($params['returnFields'])) {
      $query = 'SELECT e.id' .
        (in_array("exName", $params['returnFields']) ? ", bd.name as exName":"");
        //.... TODO
    } else {
      $query = 'SELECT e.id,
        e.brandId,
        e.period_date_from,
        e.period_date_to,
        bd.name AS exName,
        e.categoryId,
        e.cityId,
        e.countryId,
        e.regionId,
        lcd.name AS cityName,
        lctd.name AS countryName,
        e.id AS eventId,
        e.centerId,
        exd.name AS expocenterName,
        e.news, e.companies, e.videos, e.ads, ec.user_request_types AS urts,
        IF (ed.logo AND ec.show_list_logo=1, CONCAT("http://ws.expopromoter.com/file/event_logo.php?id=",e.id,"&amp;lang=",ed.languages_id), NULL) AS logo, free_tickets';
    }
	
    $result['resultsTotal'] = DB_PDO::fetchAll(str_replace(" * ", " COUNT(*) AS cnt ", $subQuery));
    
    $result['resultsTotal'] = $result['resultsTotal'][0]['cnt'];
    $result['pagesTotal'] = ($results_num==0 ? 0:ceil($result['resultsTotal']/$results_num));
    if ($result['resultsTotal']<$results_num*$page) {
      $page = $result['pagesTotal'];
    }

    $subQueryLimit = DB::getQueryWithLimit($subQuery, $results_num, $page);
    
	 
    $query .= " FROM (" . str_replace(" * ", " evn.* AS cnt ", $subQueryLimit) . ") AS e
JOIN ExpoPromoter_Opt.events_common AS ec ON ( e.id=ec.id )
JOIN ExpoPromoter_Opt.events_data AS ed ON ( e.id = ed.id )
JOIN ExpoPromoter_Opt.brands_data AS bd ON ( e.brandId = bd.id )
JOIN ExpoPromoter_Opt.location_cities_data AS lcd ON ( e.cityId = lcd.id )
JOIN ExpoPromoter_Opt.location_countries_data AS lctd ON ( e.countryId = lctd.id )
JOIN ExpoPromoter_Opt.location_regions_data AS lrd ON ( e.regionId = lrd.id )
LEFT JOIN ExpoPromoter_Opt.expocenters_data AS exd ON (e.centerId = exd.id AND exd.languages_id = ed.languages_id)
WHERE ed.languages_id = lcd.languages_id
AND ed.languages_id = lctd.languages_id
AND ed.languages_id = lrd.languages_id
AND ed.languages_id = bd.languages_id
AND ed.languages_id = " . $this->sessParams['langId'];
	
    if (!empty($params['premium1'])) {
      $query .= " AND ec.show_list_logo = 1";
    }

   
    try {
      $result['data'] = DB_PDO::fetchAll($query);
      $result['reqParams']['page'] = $page;

/*
      if (!empty($params['nameFilter']) && strlen($params['nameFilter'])>0)
        mail('eugene.ivashin@expopromogroup.com', 'SQL', $query);
*/
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }

    return $result;
  }

  /**
  * Применяется для расширеного поиска выставок. Работа функции аналогична getExhibitionsList за исключением того, что параметры выборки в массиве $params передаются ввиде массивов значений.
  *
  * @param Integer $results_num
  * @param Integer $page
  * @param Array $params
  * @return Array
  */
  private function advancedExhibitionSearchList($results_num, $page, $params = Array()) {
    return $this->getExhibitionsList($results_num, $page, $params);
  }

  /**
  * Возвращает список доступных выставочных центров.
  * В $results_num устанавливается количество результатов на страницу.
  * $page выбирает страницу
  * В массиве $params можно задавать дополнительные параметры выборки:
  * regionId - номер региона
  * countryId - номер страны
  * cityId - номер города
  *
  * @param Integer $results_num
  * @param Integer $page
  * @param Array $params
  * @return Array
  */
  protected function getExpoCentersList ($results_num, $page, $params = Array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'params' => $params);

    if (isset($params['countryId'])) {
      //Сохраняем для таргетинга баннерки
      $this->sessParams['countryId'] = $params['countryId'];
    }

    //Инициализируем вспомогательный класс
    Optimized_EPUtils::utilsInit('expoCenters', $params, $this->sessParams['lang']);

    $baseQuery = Optimized_EPUtils::createBaseQuery($params);

    $query = 'SELECT ec.id, ecd.name, vl.countryId, vl.countryName, vl.cityId, vl.cityName' . $baseQuery . ' ORDER BY ecd.name';

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $resQuery = $split['query'];
      $result['data'] = DB_PDO::fetchAll($resQuery);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Функция возвращает список категорий выставок.
  * В $results_num устанавливается количество результатов на страницу.
  * $page выбирает страницу
  * Если необязательный параметр $parent опущен, возвращается список корневых (главных) категорий, иначе возвращается список подкатеогрий, содержащейся в категории, номер которой указан в $parent.
  * $returnEmpty указывает, что необходимо возвращать категории без выставок
  *
  * @param Integer $results_num
  * @param Integer $page
  * @param Integer $parent
  * @param Boolean $returnEmpty
  * @return Array
  */
  public function getExhibitionCategoriesList ($results_num, $page, $parent = 0, $returnEmpty = true) {
    $result['reqParams'] = array('results_num' => $results_num, 'parent' => $parent, 'resultsNum' => $results_num, 'returnEmpty' => $returnEmpty);

    $sqlFrom = ($parent==0 ? "brands_categories AS c JOIN brands_categories_data AS d ON (c.id=d.id)":"brands_subcategories AS c JOIN brands_subcategories_data AS d ON (c.id=d.id AND c.parent_id=" . intval($parent) . ")");

    if ($returnEmpty) {
      $baseQuery = " FROM " . $sqlFrom . " WHERE ";
    } else {
      $baseQuery = ' FROM ' . $sqlFrom . " WHERE ";
    }

    $query = 'SELECT c.id, d.name ' . $baseQuery . " c.active=1 AND d.languages_id=" . $this->sessParams['langId'] . " ORDER BY d.name ASC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $resQuery = $split['query'];
      $result['data'] = DB_PDO::fetchAll($resQuery);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список подкатегорий
  *
  * @param Integer $results_num
  * @param Integer $page
  * @param Integer $parent
  * @return Array
  */
  public function getExhibitionSubCategoriesList ($results_num, $page, $parent) {
    $result['reqParams'] = array('results_num' => $results_num, 'parent' => $parent, 'resultsNum' => $results_num);

    $query = 'SELECT c.id, d.name FROM brands_subcategories AS c
      JOIN brands_subcategories_data AS d ON (c.id=d.id)
      WHERE c.active=1 AND d.languages_id=' . $this->sessParams['langId'] . ' AND c.parent_id=' . intval($parent) . "
      ORDER BY d.name ASC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $resQuery = $split['query'];
      $result['data'] = DB_PDO::fetchAll($resQuery);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список регионов
  *
  * @return Array
  */
  public function getRegionsList () {
    $result = array();

    $query = "SELECT lr.id, lrd.name FROM location_regions AS lr JOIN location_regions_data AS lrd ON (lr.id=lrd.id) WHERE lr.active=1 AND lrd.languages_id=" . $this->sessParams['langId'] . " ORDER BY lrd.name ASC";

    try {
      $result['data'] = DB_PDO::fetchAll($query);

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  public function getCountryIdByName($name, $lang='en')
  {
      $query = "SELECT lc.id FROM location_countries_data lc INNER JOIN languages l ON l.id=lc.languages_id WHERE l.code='".$lang."' AND lc.name LIKE '".$name."'";

      try {
        $result = DB_PDO::fetchAll($query);
        if (is_array($result) && $result[0] && $result[0]['id'])
        {
            $id = $result[0]['id'];
            if (!isset($this->sessParams['getExhibitionsListParams']))
                $this->sessParams['getExhibitionsListParams'] = array();
            $this->sessParams['getExhibitionsListParams']['countryId'] = $id;
            return $id;
        }

        return $result;
      } catch (Exception $e) {
        $this->error = true;
        return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
      }
  }

  /**
  * Возвращает список стран в регионе, Id которого указан в $parent.
  * Если $parent = 0 или не указан, возвращаются все страны во всех регионах.
  *
  * @param Integer $results_num
  * @param Integer $page
  * @param Integer $parent
  * @return Array
  */
  public function getCountriesList ($results_num, $page, $parent = 0) {
    $result['reqParams'] = array('parent' => $parent, 'resultsNum' => $results_num);

    if ($parent != 0) {
      $whereIn = intval($parent);
    } else {
      $regions = $this->getRegionsList();
      $whereIn = '';
      foreach ($regions['data'] as $el) {
        $whereIn .= $el['id'] . ", ";
      }
      $whereIn = rtrim($whereIn, ", ");
    }

    $query = "SELECT lc.id, lc.regions_id, lcd.name FROM location_countries AS lc JOIN location_countries_data AS lcd ON (lc.id=lcd.id) WHERE lc.regions_id IN (". $whereIn .") AND lc.active=1 AND lcd.languages_id=" . $this->sessParams['langId'] . " ORDER BY lcd.name ASC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $resQuery = $split['query'];
      $result['data'] = DB_PDO::fetchAll($resQuery);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  public function getNonEmptyCountriesList ($results_num, $page, $parent = 0) {
    $result['reqParams'] = array('parent' => $parent, 'resultsNum' => $results_num);

    if ($parent != 0) {
      $whereIn = intval($parent);
    } else {
      $regions = $this->getRegionsList();
      $whereIn = '';
      foreach ($regions['data'] as $el) {
        $whereIn .= $el['id'] . ", ";
      }
      $whereIn = rtrim($whereIn, ", ");
    }
    $langId = $this->sessParams['langId'];
    $lang = $this->sessParams['lang'];

    $query = "SELECT lc.id, lc.regions_id, lcd.name
    FROM location_countries AS lc
        INNER JOIN location_countries_data AS lcd ON (lc.id=lcd.id)
    INNER JOIN
      (
        SELECT lct.countries_id
        FROM location_cities AS lct
            INNER JOIN location_cities_data AS lctd ON lctd.id=lct.id
        WHERE lct.extended = 0
            AND lctd.languages_id='$langId'
        GROUP BY lct.countries_id
      ) AS cts ON cts.countries_id=lc.id
    INNER JOIN
      (
        SELECT count(id) AS eventCount, countryId
        FROM ExpoPromoter_MViews.events_$lang
        GROUP BY countryId
      ) AS evs ON evs.countryId=lc.id
    WHERE lc.regions_id IN ($whereIn)
        AND lc.active=1
        AND lcd.languages_id='$langId'
    ORDER BY lcd.name ASC";

    try {
      $result['data'] = DB_PDO::fetchAll($query);
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список городов
  *
  * @param Integer $results_num
  * @param Integer $page
  * @param Integer $parent
  * @return Array
  */
  public function getCitiesList ($results_num, $page, $parent) {
    if ($parent < 0) {
      $parent = abs($parent);
      $extendFilter = "";
    } else {
      $extendFilter = "AND lc.extended = 0";
    }

    $result['reqParams'] = array('parent' => $parent, 'resultsNum' => $results_num);

    $query = "SELECT lc.id, lcd.name FROM location_cities AS lc JOIN location_cities_data AS lcd ON (lc.id=lcd.id) WHERE lc.countries_id='". intval($parent) ."' AND lc.active=1 {$extendFilter} AND lcd.languages_id=" . $this->sessParams['langId'] . " ORDER BY lcd.name ASC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $resQuery = $split['query'];
      $result['data'] = DB_PDO::fetchAll($resQuery);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }
  
  public function getActiveCitiesList($results_num, $page, $parent,$dateFrom,$dateTo){
	   if ($parent < 0) {
      $parent = abs($parent);
      $extendFilter = "";
    } else {
      $extendFilter = "AND lc.extended = 0";
    }
	
	 $query = "SELECT 
					lc.id, 
					lcd.name 
			   FROM 
					ExpoPromoter_Opt.location_cities AS lc 
				JOIN 
					ExpoPromoter_Opt.location_cities_data AS lcd ON (lc.id=lcd.id)
				 
				WHERE 
						lc.countries_id='". intval($parent) ."' 
					AND lc.active=1 {$extendFilter} 
					AND lcd.languages_id=" . $this->sessParams['langId'] . " 
					AND lc.id IN (
						SELECT evn.cityId 
						FROM ExpoPromoter_MViews.events_" . $this->sessParams['lang'] . " as evn
					WHERE 1";
		if(!is_null($dateFrom)) $query.= " AND evn.period_date_from >= '".preg_replace('/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/','$3-$2-$1',$dateFrom)."'";
		if(!is_null($dateTo)) $query.= " AND evn.period_date_to <= '".preg_replace('/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/','$3-$2-$1',$dateTo)."'";
		$query.= "			)
		ORDER BY lcd.name ASC";
	
    $result['reqParams'] = array('parent' => $parent, 'resultsNum' => $results_num);
    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $resQuery = $split['query'];
      $result['data'] = DB_PDO::fetchAll($resQuery);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }
  
  public function getBuyerInfo ($event_id) {
    $query = "
    SELECT CASE WHEN ((b.max_money - IFNULL(r.regs, 0)*b.money)>0) THEN b.id ELSE NULL END AS id,
        fd.description
        FROM buyers AS b 
        INNER JOIN events AS e ON e.id=b.events_id
        INNER JOIN tickets.forms AS f ON f.id_event=b.id 
        INNER JOIN tickets.forms_data AS fd USING (id_form)
        LEFT JOIN 
            ( SELECT count(*) AS regs, r.id_form
            FROM tickets.registrations as r
            LEFT JOIN tickets.registrations_cancellations as c USING (id_registration)
            WHERE NOT (c.approve_status = 1) OR c.approve_status IS NULL
            GROUP BY r.id_form
            ) AS r USING (id_form)
        WHERE b.active=1 AND b.`show`=1 AND e.date_to>=CURDATE()
        AND fd.id_language='".$this->sessParams['lang']."'
        AND b.events_id='".(int)$event_id."'";
    try {
      $result = DB_PDO::fetchAll($query);
      if ($result)
        return $result[0];
      else
        return NULL;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }      
  }

  public function getTicketsAffiliateInfo () {
    $siteID = $this->sessParams['siteID'];
    if (!$siteID)
        return array('errorCode' => 0, 'errorMessage' => 'Site not registered');
    $query = 'SELECT * FROM tickets.affiliates WHERE sites_id='.$siteID;
    try {
      $result = DB_PDO::fetchAll($query);
      return $result[0];
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  public function getBookingAffiliateInfo () {
    $siteID = $this->sessParams['siteID'];
    if (!$siteID)
        return array('errorCode' => 0, 'errorMessage' => 'Site not registered');
    $query = 'SELECT * FROM booking.affiliates WHERE sites_id='.$siteID;
    try {
      $result = DB_PDO::fetchAll($query);
      return $result[0];
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }
  
  /**
  * Возвращает локацию в формате: "City, Country" //in English!
  * @param Integer $parent
  * @return Array
  */
  public function getEventLocation ($eventId, $langCode) {
    $result['reqParams'] = array('event_id' => $eventId, 'lang_code' => $langCode);

    $query = "SELECT IFNULL(CONVERT(gc.name USING utf8), vl.cityName) AS city, vl.countryName AS country, lcn.code AS countryCode
    FROM view_location AS vl
    INNER JOIN events AS e ON e.cities_id = vl.cityId
    INNER JOIN location_cities AS lct ON lct.id=vl.cityId
    INNER JOIN location_countries AS lcn ON lcn.id=vl.countryId
    LEFT JOIN geo.cities_country AS gc ON gc.geoid=lct.geonameid
    WHERE e.id=" . intval($eventId) . " AND vl.languageId=(SELECT id FROM languages WHERE code = '".$langCode."')";
    /*$query = "SELECT lcnd.name AS country, lctd.name AS city
    FROM location_countries AS lcn
    INNER JOIN location_countries_data AS lcnd ON (lcn.id=lcnd.id)
    INNER JOIN location_cities lct ON lct.countries_id=lcn.id
    INNER JOIN location_cities_data AS lctd ON  lct.id=lctd.id
    INNER JOIN events AS e ON e.cities_id = lct.id
    WHERE e.id=" . intval($eventId) . " AND lcn.active=1 AND lct.active=1 AND
        lcnd.languages_id=lctd.languages_id AND lctd.languages_id=(SELECT id FROM languages WHERE code = '".$langCode."')";*/
//$result['reqParams']['query'] = $query;
    try {
      $result['data'] = DB_PDO::fetchAll($query);
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

    public function getExcenterLocation ($excenter_id, $langCode) {
      $result['reqParams'] = array('excenter_id' => $excenter_id, 'lang_code' => $langCode);

      $query = "SELECT IFNULL(gc.name, vl.cityName) AS city, vl.countryName AS country, lcn.code AS countryCode
      FROM view_location AS vl
      INNER JOIN expocenters ec ON ec.cities_id = vl.cityId
      INNER JOIN location_cities AS lct ON lct.id=vl.cityId
      INNER JOIN location_countries AS lcn ON lcn.id=vl.countryId
      LEFT JOIN ExpoPromoter_cms.geo_cities_min AS gc ON gc.geonameid=lct.geonameid
      WHERE ec.id=" . intval($excenter_id) . " AND vl.languageId=(SELECT id FROM languages WHERE code = '".$langCode."')";
      /*$query = "SELECT lcnd.name AS country, lctd.name AS city
      FROM location_countries AS lcn
      INNER JOIN location_countries_data AS lcnd ON (lcn.id=lcnd.id)
      INNER JOIN location_cities lct ON lct.countries_id=lcn.id
      INNER JOIN location_cities_data AS lctd ON  lct.id=lctd.id
      INNER JOIN events AS e ON e.cities_id = lct.id
      WHERE e.id=" . intval($eventId) . " AND lcn.active=1 AND lct.active=1 AND
          lcnd.languages_id=lctd.languages_id AND lctd.languages_id=(SELECT id FROM languages WHERE code = '".$langCode."')";*/
  //$result['reqParams']['query'] = $query;
      try {
        $result['data'] = DB_PDO::fetchAll($query);
        return $result;
      } catch (Exception $e) {
        $this->error = true;
        return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
      }
    }

  public function getLocation ($city_id) {
    $result['reqParams'] = array('city_id' => $city_id);

    $query = "SELECT CONCAT(lctd.name, ', ', lcnd.name) AS location
    FROM location_countries AS lcn
    INNER JOIN location_countries_data AS lcnd ON (lcn.id=lcnd.id)
    INNER JOIN location_cities lct ON lct.countries_id=lcn.id
    INNER JOIN location_cities_data AS lctd ON  lct.id=lctd.id
    WHERE lct.id=" . intval($city_id) . " AND lcn.active=1 AND lct.active=1 AND
        lcnd.languages_id=lctd.languages_id AND lctd.languages_id=" . $this->sessParams['langId'];

    try {
      $result['data'] = DB_PDO::fetchAll($query);
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Функция возвращает id города, если он есть, или создает новый город и возвращает его ID
  * Создаются все языковые версии, но они обе одинаковые.
  * Город помечается как "extended".
  *
  * @param string $name
  * @param int $country_id
  * @return int
  */

  private function _checkCity ($name, $country_id = 52) {
    $name = mysql_escape_string($name);

    $query =
      "SELECT c.id
      FROM location_cities AS c
        JOIN location_cities_data AS cd ON (cd.id = c.id)
      WHERE name = '{$name}'
      LIMIT 1
    ";

    $found = DB_PDO::fetchAll($query);

    // mail('eugene.ivashin@expopromogroup.com', 'USER DATA city found', print_r($found, true));

    if (!empty($found) && !empty($found[0])) {

      return $found[0]['id'];

    } else {

      try {
        $data = array(
          'active'       => 1,
          'extended'     => 1,
          'countries_id' => $country_id
        );

        DB_PDO::perform('location_cities', $data, 'insert');
        $city_id = DB_PDO::last_insert_id();

        $languages = $this->getLanguagesList();

        foreach($languages['data'] as $lang) {
          $data = array(
            'id'           => $city_id,
            'languages_id' => $lang['id'],
            'name'         => $name,
          );

          DB_PDO::perform('location_cities_data', $data, 'insert');
        }

        return $result;
      } catch (Exception $e) {
        return null;
      }
    }
  }


  /**
  * Функция возвращает id компании, если она есть, или создает новую компанию и возвращает ее ID
  * Создается только одна языковая версия.
  *
  * @param Array $data
  * @return int
  */

  private function _checkCompany ($data, $lang_data, $editable = false) {
    $query = "SELECT id FROM companies AS c WHERE id = '{$data['id']}' LIMIT 1";
    $found = DB_PDO::fetchAll($query);

    if (!empty($found) && !empty($found[0]) && $editable) {

      try {
        DB_PDO::perform("companies", $data, "update", "id = '{$data['id']}'");
        // mail('eugene.ivashin@expopromogroup.com', 'USER DATA company update', print_r($data, true));

        $languages = $this->getLanguagesList();

        foreach($languages['data'] as $lang) {
          //$lang_data['languages_id'] = $lang['id'];

          DB_PDO::perform("companies_data", $lang_data, "update", "id = '{$data['id']}' AND languages_id = '{$lang['id']}'");
          // mail('eugene.ivashin@expopromogroup.com', 'USER DATA company lang update', print_r($lang_data, true));
        }

        return $data['id'];
      } catch (Exception $e) {
        return null;
      }

    } else {

      try {
        DB_PDO::perform('companies', $data, 'insert');
        $company_id = DB_PDO::last_insert_id();

        $languages = $this->getLanguagesList();

        foreach($languages['data'] as $lang) {
          $lang_data['id'] = $company_id;
          $lang_data['languages_id'] = $lang['id'];

          $active_data = array(
            'id'           => $company_id,
            'languages_id' => $lang['id'],
            'active'       => 0,
          );

          DB_PDO::perform('companies_data', $lang_data, 'insert');
          DB_PDO::perform('companies_active', $active_data, 'insert');
        }

        return $company_id;
      } catch (Exception $e) {
        return null;
      }

    }
  }



  /**
  * Функция возвращает список доступных стран проведения выставок для данного списка параметров.
  * Первым параметром необходимо передать имя функции для которой производится
  * выбрка ограниченного набора стран
  * Вторым параметром передается массив ограничивающих параметров. Набор параметров
  * совпадает с набором параметры функции, имя которой передано первым параметром
  *
  * @param string $funcName
  * @param array $params
  * @return Array
  */
  protected function getLimitedCountriesList($funcName, $params) {
    $result['reqParams'] = array('functionName' => $funcName, 'params' => $params);
	if(isset($params['withBuyersOnly'])) unset($params['withBuyersOnly']);
    Optimized_EPUtils::utilsInit($funcName, $params, $this->sessParams['lang']);
	
    if (Optimized_EPUtils::checkBaseQuery()) {
      //Получаем список доступных стран проведения выставок
      $result['data'] = Optimized_EPUtils::createCountriesList($funcName);
    } else {
      $result = array('errorCode' => 4, 'errorMessage' => 'getExhibitionsList function must be called first.');
    }

    return $result;
  }

  /**
  * Функция возвращает список доступных городов проведения выставок для данного списка
  * выставок, полученного ранее при вызове функции getExhibitionsList
  *
  * @return Array
  */
  private function getLimitedCitiesList($funcName, $params) {
    $result['reqParams'] = array('functionName' => $funcName, 'params' => $params);

    Optimized_EPUtils::utilsInit($funcName, $params, $this->sessParams['lang']);

    if (Optimized_EPUtils::checkBaseQuery()) {
      //Получаем список доступных городов проведения выставок
      $result['data'] = Optimized_EPUtils::createCitiesList($funcName);
    } else {
      $result = array('errorCode' => 4, 'errorMessage' => 'getExhibitionsList function must be called first.');
    }

    return $result;
  }

  /**
  * Функция возвращает список доступных категорий выставок для данного фильтра $params и функции ограничения $funcName
  *
  * @param String $func
  * @param Array $params
  * @return Array
  */
  private function getLimitedCategoriesList($funcName, $params) {
    $result['reqParams'] = array('functionName' => $funcName, 'params' => $params);

    if ($funcName == 'companies') {

      $where = ' WHERE ';

      if (!isset($params['cities_id'])) {
        if (!isset($params['countries_id'])) {
          if (!isset($params['regions_id'])) {
            $where .= ' regions_id IS NULL';
          } else {
            $where .= ' regions_id=' . intval($params['regions_id']);
          }
          $where .= ' AND countries_id IS NULL';
        } else {
          $where .= ' countries_id=' . intval($params['countries_id']);
        }
        $where .= ' AND cities_id IS NULL';
      } else {
        $where .= 'cities_id=' . intval($params['cities_id']);
      }

      $query = "SELECT bcd.id, bcd.name, sq.companies, sq.services
      FROM (SELECT id, companies, services FROM ExpoPromoter_MViews.companies_categories_" . $this->sessParams['lang'] . $where . ") AS sq
      JOIN brands_categories_data AS bcd ON (sq.id = bcd.id)
      WHERE bcd.languages_id=" . $this->sessParams['langId'];

      #mail("dmitry.sinev@expopromogroup.com", "sql", $query);

      $result['data'] = DB_PDO::fetchAll($query);

    } else {
      Optimized_EPUtils::utilsInit($funcName, $params, $this->sessParams['lang']);

      //Получаем список доступных категорий выставок
      $result['data'] = Optimized_EPUtils::createExCategoriesList();
    }

    return $result;
  }

  /**
  * Функция возвращает список доступных годов проведения выставок для данного списка выставок, полученного ранее при вызове функции getExhibitionsList
  *
  * @return Array
  */
  private function getYearsListFromExhibitionList($params) {
    $result['reqParams'] = array('params' => $params);

    Optimized_EPUtils::utilsInit('ExhibitionsList', $params, $this->sessParams['lang']);

    if (Optimized_EPUtils::checkBaseQuery()) {
      //Получаем список доступных годов выставок
      $result['data'] = Optimized_EPUtils::createYearsList();
    } else {
      $result = array('errorCode' => 4, 'errorMessage' => 'getExhibitionsList function must be called first.');
    }
    return $result;
  }

  /**
  * Функция возвращает список доступных месяцев выставок для данного списка выставок, полученного ранее при вызове функции getExhibitionsList
  *
  * @return Array
  */
  private function getMonthesListFromExhibitonList($params) {
    $result['reqParams'] = array('params' => $params);

    Optimized_EPUtils::utilsInit('ExhibitionsList', $params, $this->sessParams['lang']);

    if (Optimized_EPUtils::checkBaseQuery()) {
      //Получаем список доступных месяцев выставок
      $result['data'] = Optimized_EPUtils::createMonthesList();
    } else {
      $result = array('errorCode' => 4, 'errorMessage' => 'getExhibitionsList function must be called first.');
    }
    return $result;
  }

  /**
  * Возвращает список общественных организаций
  * В $results_num устанавливается количество результатов на страницу.
  * $page выбирает страницу
  * В необязательном параметре $params можно указать фильтры:
  * countryId - по стране
  * cityId - по городу
  *
  * @param $results_num Interger
  * @param $page Integer
  * @param $params Array
  * @return Array
  */
  private function getSocialOrgsanisationsList($results_num, $page, $params = array()) {
    $result['reqParams'] = array('resultsNum' => $results_num, 'params' => $params);

    //Инициализируем вспомогательный класс
    Optimized_EPUtils::utilsInit('socialOrganisations', $params, $this->sessParams['lang']);

    $baseQuery = Optimized_EPUtils::createBaseQuery($params);

    $query = 'SELECT so.id, sod.name, vl.cityId, vl.cityName, vl.countryId, vl.countryName ' . $baseQuery . " ORDER BY sod.name ASC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $resQuery = $split['query'];
      $result['data'] = DB_PDO::fetchAll($resQuery);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список категорий сервисных компаний
  * В $results_num устанавливается количество результатов на страницу.
  * $page выбирает страницу
  *
  * @param Integer $results_num
  * @param Integer $page
  * @param Array $params
  * @return Array
  */
  private function getServiceCompaniesCategoriesList ($results_num, $page, $params = array()) {
    $result['reqParams'] = array('resultsNum' => $results_num);

    $sqWhere = "";

    if (!empty($params)) {
        $sqWhere = " AND scc.id IN (
             SELECT service_companies_categories_id
             FROM service_companies AS sc
             JOIN location_cities AS lc ON (sc.cities_id = lc.id) WHERE sc.active=1 " .
             (empty($params['countryId']) ? "":" AND lc.countries_id=" . intval($params['countryId'])) .
             (empty($params['cityId']) ? "":" AND sc.cities_id=" . intval($params['cityId'])) . ")";
    }

    $query = "SELECT scc.id, sccd.name
        FROM service_companies_categories AS scc
        JOIN service_companies_categories_data AS sccd ON (scc.id=sccd.id)
        WHERE scc.active=1 AND sccd.languages_id=" . $this->sessParams['langId'] . $sqWhere . "
        ORDER BY sccd.name ASC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $resQuery = $split['query'];
      $result['data'] = DB_PDO::fetchAll($resQuery);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список сервисных компаний
  * В $results_num устанавливается количество результатов на страницу.
  * $page выбирает страницу
  * $params задает различные параметры фильтрации вывода:
  * categoryId задает номер категории сервисных компаний. Если не указана, выдает все компании.
  * regionId задает выборку по региону
  * countryId - выборка по стране
  * cityId - выборка по городу
  * ids - массив, содержащий id сервисных компаний для выборки
  *
  * @param Integer $results_num
  * @param Integer $page
  * @param Array $params
  * @return Array
  */
  protected function getServiceCompaniesList($results_num, $page, $params = array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'params' => $params);

    //Инициализируем вспомогательный класс
    Optimized_EPUtils::utilsInit('serviceCompanies', $params, $this->sessParams['lang']);

    $baseQuery = Optimized_EPUtils::createBaseQuery($params);

    $query = "SELECT sc.id, sc.service_companies_categories_id AS categoryId, scd.name, scd.logo AS sc_logo,
    '' AS resume, vl.cityName, vl.cityId, vl.countryName, vl.countryId, sccd.name AS categoryName " . $baseQuery .
    " ORDER BY " . (!empty($params['categoryId']) ? "sc.sort_order_cat ASC":"sc.sort_order ASC") . ", scd.name ASC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $resQuery = $split['query'];
      $result['data'] = DB_PDO::fetchAll($resQuery);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      Optimized_EPUtils::createLogoUrl($result['data'], "service_companies", "imageLogo");
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список новостей
  * В $params можно указать параметры поиска:
  * nameFilter - фильтр по названию
  * categoryId - Id категории новостей
  * brandId - Id бренда новостей
  * eventId - Id выставки, через которого производится поиск бренда и устанавливается связь
  * countryId - Id страны, к которой привязаны новости
  * excenterId - Id выставочного центра
  * withExpocenter - флаг, указывающий, что нужно получить новости выставочных центров
  *
  * @param Interger $results_num
  * @param Interger $page
  * @param Array $params
  * @return Array
  */
  protected function getNewsList($results_num, $page, $params = array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'params' => $params);

    $baseQuery = ' FROM news AS n
      JOIN news_data AS nd ON (n.id=nd.id) ' .
      (isset($params['eventId']) ? 'JOIN events AS e ON (n.brands_id = e.brands_id AND e.id="' . intval($params['eventId']) . '")':'') .
      ' WHERE nd.active=1 AND n.date_public <= NOW() AND nd.languages_id = ' . $this->sessParams['langId'];

    if (isset($params['nameFilter']) && strlen($params['nameFilter'])>0) {
      $baseQuery .= " AND nd.name LIKE '%" . mysql_escape_string($params['nameFilter']) . "%'";
    }

    if (isset($params['categoryId']) && is_numeric($params['categoryId'])) {
      $baseQuery .= " AND n.brands_categories_id = '" . intval($params['categoryId']) . "'";
    }

    if (isset($params['brandId']) && is_numeric($params['brandId'])) {
      $baseQuery .= " AND n.brands_id = '" . intval($params['brandId']) . "'";
    }

    if (isset($params['countryId']) && is_numeric($params['countryId'])) {
      $baseQuery .= " AND n.countries_id = '" . intval($params['countryId']) . "'";
    }

    if (isset($params['excenterId']) && is_numeric($params['excenterId'])) {
      $baseQuery .= " AND n.expocenters_id = '" . intval($params['excenterId']) . "'";
    }

    if (isset($params['organizerId']) && is_numeric($params['organizerId'])) {
      $baseQuery .= " AND n.organizers_id = '" . intval($params['organizerId']) . "'";
    }

    if (isset($params['withExpocenter'])) {
      $baseQuery = ", n.expocenters_id " . $baseQuery . " AND n.expocenters_id IS NOT NULL";
    }

    $query = 'SELECT n.id, nd.name, nd.preambula, n.date_public AS date' .
        ((isset($params['fulltext_get']) && $params['fulltext_get'] == true) ? ', nd.content':'') .
        $baseQuery .' ORDER BY n.date_public DESC, n.id DESC';

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $resQuery = $split['query'];
      $result['data'] = DB_PDO::fetchAll($resQuery);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список статей
  * В $params можно указать параметры поиска
  *
  * @param Interger $results_num
  * @param Interger $page
  * @param Array $params
  * @return Array
  */
  protected function getReviewsList($results_num, $page, $params = array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'params' => $params);

    $baseQuery = ' FROM articles AS a JOIN articles_data AS ad ON (a.id=ad.id) WHERE ad.active=1 AND ad.languages_id=' . $this->sessParams['langId'];

    if (isset($params['nameFilter']) && strlen($params['nameFilter'])>0) {
      $baseQuery .= " AND ad.name LIKE '%" . mysql_escape_string($params['nameFilter']) . "%'";
    }

    $query = 'SELECT a.id, ad.name, ad.preambula, a.date_public AS date '. $baseQuery .' ORDER BY a.date_public DESC';

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $result['data'] = DB_PDO::fetchAll($split['query']);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список доступных языков
  *
  * @return Array
  */
  protected function getLanguagesList() {
    $query = "SELECT id, code, name FROM languages WHERE active=1 ORDER BY code";

    try {
      //$result['data'] = DB::query($query);
      $result['data'] = DB_PDO::fetchAll($query);
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  //Функции выборки полной информации

  /**
  * Получение карточки выставки. Возвращаются только активные выставки.
  * $id - номер выставки в базе. Если $id - массив, возвращаются все выставки,
  * номера которых указаны в массиве
  * Если во втором параметре передать true, то функция вернет id следующего
  * события для данного бренда в параметре futureEventId. Если события не существует,
  * данный параметр будет равен 0
  *
  * @param Mixed $id
  * @param boolean $getFutureId
  * @return Array
  */
  protected function getExhibition($id, $getFutureId = false) {
    $result = array();
    $result['reqParams'] = array('id' => $id);

    if (is_array($id)) {
      $whereEx = "IN ('" . EPUtils::advJoin("', '", $id, true) . "')";
    } else {
      $whereEx = "= '" . intval($id) . "'";
    }

    $query = 'SELECT e.id, e.brandId, e.period_date_from, e.period_date_to, ed.logo AS event_logo,
    bd.name_extended AS textMore, od.name AS orgName, bd.name, e.categoryId, exd.name AS centerName, 
    exd.address AS centerAddress, ex.latitude AS centerLatitude, ex.longitude AS centerLongitude, 
    bcd.name AS categoryName, e.cityId, ed.description, ed.thematic_sections AS thematicSections,
    vl.countryId, vl.regionName, vl.countryName, vl.cityName, e.regionId, e.centerId, e.organizerId,
    pd.name AS periodName, ed.email AS prgEmail, ed.web_address AS prgWebAddress, ed.phone AS prgPhone,
    ed.fax AS prgFax, ecom.show_list_logo AS premium, ecom.ticketorder_literal, e.news, e.free_tickets

    FROM ExpoPromoter_MViews.events_' . $this->sessParams['lang'] . ' AS e
    JOIN events_common AS ecom ON (e.id=ecom.id)
    JOIN events_data AS ed ON (e.id=ed.id)
    JOIN brands_data AS bd ON (e.brandId=bd.id)
    JOIN organizers_data AS od ON (e.organizerId=od.id)
    LEFT JOIN expocenters_data AS exd ON (e.centerId=exd.id AND exd.languages_id=' . $this->sessParams['langId'] . ')
    LEFT JOIN expocenters AS ex ON (e.centerId=ex.id)
    JOIN brands_categories_data AS bcd ON (e.categoryId=bcd.id)
    LEFT JOIN periods_data AS pd ON (ecom.periods_id=pd.id AND pd.languages_id=' . $this->sessParams['langId']. ')
    JOIN view_location AS vl ON (e.cityId=vl.cityId)

    WHERE e.id ' . $whereEx . " AND ed.languages_id=bd.languages_id AND ed.languages_id=od.languages_id AND ed.languages_id=bcd.languages_id AND ed.languages_id=vl.languageId AND ed.languages_id = " . $this->sessParams['langId'];
    
    try {
      $tmp = DB_PDO::fetchAll($query);

      if (empty($tmp) || !is_array($tmp)) {
        return $result;
      }

      Optimized_EPUtils::createLogoUrl($tmp, "events", "imageLogo");


      if (!is_array($id)) {
        //Обновляем счетчик посещений карточки выставки исключаяя поисковики
        $query = "SELECT ip FROM statistic.exclude_ip WHERE ip = INET_ATON('" . $this->sessParams['clientIP'] . "') LIMIT 1";
        $res = DB::query($query);

        if (empty($res)) {
          $chk_query = "SELECT COUNT(*) AS views FROM statistic.events_hits WHERE hit_time>=CURDATE() AND events_id='" . intval($id) . "' AND ip=INET_ATON('" . $this->sessParams['clientIP'] . "') AND lang='" . $this->sessParams['lang'] . "' AND site_id='" . $this->sessParams['siteID'] . "'";
          $views_today = DB::queryRow($chk_query);

          //Засчитываем только 10 просмотров по одной выставке с одного IP в сутки
          if ($views_today['views'] < 10) {
            DB_PDO::getInst()->exec("UPDATE statistic.events_counter SET view_cnt=view_cnt+1 WHERE id = " . intval($id));
            DB_PDO::getInst()->exec("INSERT INTO statistic.events_hits SET events_id='" . intval($id) . "', site_id='" . $this->sessParams['siteID'] . "', ip=INET_ATON('" . $this->sessParams['clientIP'] . "'), lang='" . $this->sessParams['lang'] . "'");
          }
        }
      }


      foreach ($tmp as $key => &$el) {
        if ($getFutureId) {
          $el['futureEventId'] = Optimized_EPUtils::getNextEventId($id);
        }

        $el['description'] = Optimized_EPUtils::fixRelativePaths($el['description']);
        $el['thematicSections'] = Optimized_EPUtils::fixRelativePaths($el['thematicSections']);
      }

      if (is_array($id)) {
        $result['data'] = $tmp;
      } else {
        $result['data'] = $tmp[0];
        $result['files'] = Optimized_EPUtils::getEventAttachedFiles($id);
        $result['gallery'] = Optimized_EPUtils::getEventGallery($id);
      }

            // Вставка для показа ссылки на вебсайт в поле "Описание"

            $result['data']['description'] = $this->_stripAllTags($result['data']['description']);
            $result['data']['thematicSections'] = $this->_stripAllTags($result['data']['thematicSections']);

      if (!in_array($result['data']['countryId'], array(13,17,26,33,34,39,45,52,60,154,162,166,185,189,190))) {
        if (isset($result['data']['prgWebAddress']) && $result['data']['prgWebAddress']) {
          if ($this->sessParams['langId'] == 1) {
            $lnk = "Перейти на веб-сайт выставки";
            $lang = 'ru';
          } else {
            $lnk = "Visit the trade show website";
            $lang = 'en';
          }

          $lnkadd = "<a href=\"http://www.expopromoter.com/Redirect/lang/{$lang}/event_id/{$result['data']['id']}/\" target=\"_blank\" alt=\"\">$lnk &gt;&gt;&gt;</a>";
        } else {
          $lnkadd = '';
        }

        $result['data']['description'] = $result['data']['description'] . "\n<p style=\"margin:10px 0;\">$lnkadd</p>";

      } else {
        $result['data']['prgWebAddress'] = '';
      }

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }




  //Функции регитсрации перехода по сайту выставки

  /**
  * Функции выборки полной информации
  *
  * @param Integer $id
  * @return Array
  */
  protected function webSiteRedirectRegister($id) {
    $result = array();
    $result['reqParams'] = array('id' => intval($id));
    $result['status'] = 1;

    $exhibition = $this->getExhibition($id);

    if (isset($exhibition['data']) && !empty($exhibition['data']['prgWebAddress'])) {
       //Обновляем счетчик посещений карточки выставки исключая поисковики
      $query = "SELECT ip FROM statistic.exclude_ip WHERE ip = INET_ATON('" . $this->sessParams['clientIP'] . "') LIMIT 1";
      $res = DB::query($query);

      if (empty($res)) {
        DB_PDO::getInst()->exec("INSERT INTO statistic.events_website_redirects SET events_id='" . intval($id) . "', referer='" . $this->sessParams['referer'] . "', ip=INET_ATON('" . $this->sessParams['clientIP'] . "'), lang='" . $this->sessParams['lang'] . "'");
        DB_PDO::getInst()->exec("UPDATE statistic.events_counter SET redir_cnt=redir_cnt+1 WHERE id = " . intval($id));
      } else {
        $result['status'] = 0;
      }
    } else {
      $result['status'] = 0;
    }

    return $result;
  }




  private function _stripAllTags($s) {
    //$s = preg_replace('#<(/?(p|div|ul|ol|li|hr))[^>]*>#i')
    $s = strip_tags($s, "<p><div><ul><ol><li><br><hr>");
    $s = preg_replace('#<(/?)(p|div|ul|ol|li|hr)[^>]*>#i', '<\1\2>', $s);

    return $s;
  }


  /**
  * Возвращает карточку общественной организации, номер которой передан в качестве параметра $id
  *
  * @param Integer $id
  * @return Array
  */
  private function getSocialOrganisation($id) {
    $result = array();
    $result['reqParams'] = array('id' => $id);

    $query = 'SELECT so.id, sod.name, sod.image_logo AS imageLogo, sod.address, sod.postcode, sod.phone, sod.fax, sod.email, sod.web_address AS webAddress, sod.description, vl.cityId, vl.countryName, vl.countryId, vl.cityName
    FROM social_organizations AS so
    JOIN social_organizations_data AS sod ON (so.id=sod.id)
    JOIN view_location AS vl ON (so.cities_id=vl.cityId)
    WHERE so.active=1 AND sod.languages_id=vl.languageId AND sod.languages_id=' . $this->sessParams['langId'] . ' AND so.id="' . intval($id) . '"';

    try {
      $data = DB_PDO::fetchAll($query);

      if (strlen($data[0]['webAddress']) > 4 && strpos($data[0]['webAddress'], "http://") === false) {
        $data[0]['webAddress'] = "http://" . $data[0]['webAddress'];
      }

      $result['data'] = $data[0];
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Функция возвращает карточку экспоцентра, номер которого передан в параметре $id
  *
  * @param Integer $id
  * @return Array
  */
  protected function getExpoCenter($id) {
    $result = array();
    $result['reqParams'] = array('id' => $id);

    $query = 'SELECT ec.id, ecd.name, ecd.logo, ecd.image_map AS imageMap, ecd.image_plan AS imagePlan, ecd.image_view AS imageView, ecd.address, ecd.postcode, ecd.phone, ecd.fax, ecd.email, ecd.web_address AS webAddress, ecd.description, vl.cityId, vl.countryName, vl.countryId, vl.cityName, ec.longitude, ec.latitude
    FROM expocenters AS ec
    JOIN expocenters_data AS ecd ON (ec.id=ecd.id)
    JOIN view_location AS vl ON (ec.cities_id=vl.cityId)
    WHERE ec.active=1 AND ecd.languages_id=vl.languageId AND ecd.languages_id=' . $this->sessParams['langId'] . ' AND ec.id="' . intval($id) . '"';

    try {
      $data = DB_PDO::fetchAll($query);

      if (empty($data)) {
        return $result;
      }

      if (strlen($data[0]['webAddress']) > 4 && strpos($data[0]['webAddress'], "http://") === false) {
        $data[0]['webAddress'] = "http://" . $data[0]['webAddress'];
      }

      Optimized_EPUtils::createLogoUrl($data, "expocenter");

      $result['data'] = $data[0];

      /*
      $basePath = URL_MAIN . "/upload/center/" . $this->sessParams['lang'] . "/";
      $result['data']['imageLogo'] = (!empty($result['data']['imageLogo']) ? $basePath . $result['data']['imageLogo']:null);
      $result['data']['imageMap'] = (!empty($result['data']['imageMap']) ? $basePath . $result['data']['imageMap']:null);
      $result['data']['imagePlan'] = (!empty($result['data']['imagePlan']) ? $basePath . $result['data']['imagePlan']:null);
      $result['data']['imageView'] = (!empty($result['data']['imageView']) ? $basePath . $result['data']['imageView']:null);
      */

      $result['data']['description'] = Optimized_EPUtils::fixRelativePaths($result['data']['description']);

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Карточка сервисной компании.
  * Номер компании указывается в параметре $id
  *
  * @param Integer $id
  * @return Array
  */
  protected function getServiceCompany($id, $onlyActive = true) {
    $result = array();
    $result['reqParams'] = array('id' => $id);

    $query = 'SELECT sc.id, scd.name, scd.logo AS sc_logo, scd.address, scd.postcode, scd.phone, scd.fax,
    scd.email, scd.web_address AS webAddress, scd.additional_info AS description, "" AS resume,
    scd.activity_forms AS activityForms, vl.cityId, vl.countryName, vl.countryId, vl.cityName,
    sc.service_companies_categories_id AS categoryId, sccd.name AS categoryName
    FROM service_companies AS sc
    JOIN service_companies_data AS scd ON (sc.id=scd.id)
    JOIN service_companies_categories_data AS sccd ON (sc.service_companies_categories_id = sccd.id)
    JOIN view_location AS vl ON (sc.cities_id=vl.cityId)
    WHERE scd.languages_id=vl.languageId AND sccd.languages_id=scd.languages_id AND
    scd.languages_id=' . $this->sessParams['langId'] . " AND " . ($onlyActive ? 'sc.active = 1 AND':'');

    if (is_array($id)) {
      $query .= ' sc.id IN ("' . EPUtils::advJoin('", "', $id, true) . '")';
    } else {
      $query .= ' sc.id = "' . intval($id) . '"';
    }

    try {
      $data = DB_PDO::fetchAll($query);

      if (empty($data)) {
        return $result;
      }

      Optimized_EPUtils::createLogoUrl($data, "service_companies", "imageLogo");

      foreach ($data as &$el) {
        $el['description'] = Optimized_EPUtils::fixRelativePaths($el['description']);
        if (strlen($data[0]['webAddress']) > 4 && strpos($el['webAddress'], "http://") === false) {
          $el['webAddress'] = "http://" . $el['webAddress'];
        }
      }

      if (is_array($id)) {
        $result['data'] = $data;
      } else {
        $result['data'] = $data[0];
      }

      $result['gallery'] = Optimized_EPUtils::getServcompGallery($id);

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }


  /**
  * Возвращает список выставочных компаний
  * В $results_num устанавливается количество результатов на страницу.
  * $page выбирает страницу
  * В необязательном параметре $params можно указать фильтры:
  * countryId - по стране
  * cityId - по городу
  *
  * @param $results_num Interger
  * @param $page Integer
  * @param $params Array
  * @return Array
  */
  protected function getOrganizersList($results_num, $page, $params = array()) {
    $result['reqParams'] = array('resultsNum' => $results_num, 'params' => $params);

    //Инициализируем вспомогательный класс
    Optimized_EPUtils::utilsInit('organizers', $params, $this->sessParams['lang']);

    $baseQuery = Optimized_EPUtils::createBaseQuery($params);

    $query = 'SELECT o.id, od.name, vl.cityId, vl.cityName, vl.countryId, vl.countryName ' . $baseQuery . " ORDER BY od.name ASC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $resQuery = $split['query'];
      $result['data'] = DB_PDO::fetchAll($resQuery);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }



  /**
  * Функция возвращает данные об организаторе выставки, id которого указано в передаваемом параметре.
  *
  * @param Integer $id
  * @return Array
  */
  protected function getOrganizer($id) {
    $result = array();
    $result['reqParams'] = array('id' => $id);

    $query = 'SELECT o.id, od.name, od.image_logo AS imageLogo, od.address, od.postcode, od.phone, od.fax, od.email, od.web_address AS webAddress, od.description, vl.cityId, vl.countryName, vl.countryId, vl.cityName
    FROM organizers AS o
    JOIN organizers_data AS od ON (o.id=od.id)
    JOIN view_location AS vl ON (vl.cityId = o.cities_id)
    WHERE o.active = 1 AND od.languages_id=vl.languageId AND od.languages_id=' . $this->sessParams['langId'] . ' AND o.id="' . intval($id) . '"';

    try {
      $data = DB_PDO::fetchAll($query);

      if (empty($data)) {
        return $result;
      }

      if (strpos(strlen($data[0]['webAddress']) > 4 && $data[0]['webAddress'], "http://") === false) {
        $data[0]['webAddress'] = "http://" . $data[0]['webAddress'];
      }
      $result['data'] = $data[0];
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }



  /**
  * Функция возвращает новость, номер которой указан в $id
  *
  * @param Integer $id
  * @return Array
  */
  private function getNews($id) {
    $result = array();
    $result['reqParams'] = array('id' => $id);

    $query = 'SELECT n.id, nd.name, nd.preambula, nd.content AS body, n.date_public AS date
    FROM news n
    JOIN news_data AS nd ON (n.id=nd.id)
    WHERE nd.active = 1 AND nd.languages_id=' . $this->sessParams['langId'] . ' AND n.id="' . intval($id) . '"';

    try {
      $data = DB_PDO::fetchAll($query);
      $data[0]['body'] = Optimized_EPUtils::fixRelativePaths($data[0]['body']);
      $result['data'] = $data[0];

      $result['data']['preambula'] = $this->_stripAllTags($result['data']['preambula']);
      //$result['data']['content']   = $this->_stripAllTags($result['data']['content']);

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает статью, номер которой указан в параметре $id
  *
  * @param Integer $id
  * @return Array
  */
  private function getReview($id) {
    $result = array();
    $result['reqParams'] = array('id' => $id);

    $query = 'SELECT a.id, ad.name, ad.preambula, ad.content AS body, a.date_public AS date
    FROM articles AS a JOIN articles_data AS ad ON (a.id=ad.id)
    WHERE ad.active=1 AND ad.languages_id="' . $this->sessParams['langId'] . '" AND a.id="' . intval($id) . '"';

    try {
      $data = DB_PDO::fetchAll($query);
      $data[0]['body'] = Optimized_EPUtils::fixRelativePaths($data[0]['body']);
      $result['data'] = $data[0];
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Функция возвращает массив баннеров.
  * Вызываемый подуль определяется автоматически. Если было вызвано несколько различных модулей, используется только первый.
  * Функцию желательно вызывать самой последней чтобы правильно срабатывали таргетинги.
  * Если $popUp = true, возвращается один баннер, который должен показываться во всплывающих окнах контактных данных
  *
  * @param Boolean $popUp
  * @return Array
  */
  private function getBanners($popUp = false) {
    //return array();
    require_once(PATH_CLASSES . "/Optimized/EPBanners.Class.php");

    $result = array('data' => array(), 'banner' => array());
    //$result['reqParams'] = array('module' => $this->sessParams['module']);

    if ($this->sessParams['clientIP'] != '91.142.161.142') {
      $query = "SELECT ip FROM statistic.exclude_ip WHERE ip = INET_ATON('" . $this->sessParams['clientIP'] . "') LIMIT 1";
      $res = DB::query($query);

      if (!empty($res)) {
        return array();
      }
    }

    try {

      Optimized_EPBanners::Init(
        $this->sessParams['lang'],
        $this->sessParams['clientIP'],
//                $this->sessParams['siteID'],
//                $this->sessParams['module'],
            (isset($this->sessParams['categoryId']) ? $this->sessParams['categoryId']:null),
            (isset($this->sessParams['countryId']) ? $this->sessParams['countryId']:null)
      );

      if ($popUp) {
        $result['banner'] = array();
      } else {
        $res = Optimized_EPBanners::getBanners();
        foreach ($res as $key => $bn) {
          $result['data']['bannerPlace_' . ($key+1)] = $bn;
        }
      }

    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }

    return $result;
  }

  /**
  * Возвращает массив, содержащий коды баннеров для текущего зарегистрированного сайта
  *
  * @return array
  */
  private function getBannerCodes() {
    //return array();

    $query = "SELECT banner180x300, banner160x80, bannertext, bannerstring FROM expoua_banners WHERE siteid = '" . $this->sessParams['siteID'] . "'";
    $result = DB::query($query);

    return (empty($result) ? array():$result[0]);
  }


  //Вспомогательные функции

  /**
  * Множественный вызов методов SOAP - класса. Применяется для всех вызовов.
  * Принимает 2 параметра: первый массив, состоит из имен вызываемых функций,
  * второй содержит массивы параметров функций. Эти массивы должны быть одинакового
  * размера, иначе генерируется исключение 1.
  * При вызове несуществующей функции генерируется исключение 3.
  * В первую очередь должна вызываться функция EPInit, иначе будет сгенерировано исключение 2.
  *
  * @param Array $funcs
  * @param Array $params
  * @return Array
  */
  public function multiCall($funcs, $params) {
    $this->startExecTime = EPUtils::getMicroTime();

    $result = array();

    //Обработка ошибки в конструкторе
    if ($this->error) {
      return $this->errorMessage;
    }

    //Проверяем входные параметры.
    try {
      if (!is_array($funcs) || !is_array($params) || count($funcs) != count($params)) {
        throw new Exception('Parameters must be arrays and same length.', 1);
      }

      if (count($funcs) == 0 || $funcs[0] != 'EPInit') {
        throw new Exception('Function EPInit must be called first.', 2);
      }
      
      

/*	
      if (in_array('getPublisher', $funcs)) {
        mail("eugene.ivashin@expopromogroup.com", "Web Partner Debug", 'Params: ' . print_r($params, true));
      }
*/


      foreach($funcs AS $fkey => $fvalue) {
        $result['reqParams']['functions'][$fvalue] = $params[$fkey];

        if (!is_callable(array($this, $fvalue))) {
          throw new Exception("Function " . $fvalue . " does not exist.", 3);
        }

        $this->bannersSaveTargeting($fvalue, $params[$fkey]);
        $result[$fvalue] = call_user_func_array(array($this, $fvalue), $params[$fkey]);
      }

      if ($this->sessParams['clientIP'] == '89.108.123.129' || $this->sessParams['clientIP'] == '89.108.123.129') {
        //mail('eugene.ivashin@expopromogroup.com', 'DATABASE THIEVES DETECTED!', "Functions:\n" . print_r($func, true) . "\n\n Params:\n" . print_r($params, true));
      }

    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }

    //Передаем id партнера
    $result['partnerId'] = $this->sessParams['siteID'];

    //Вызов функции сбора статистики
    EPStat::putClickRecord(
      $this->sessParams['siteID'],
      $this->sessParams['clientIP'],
      $this->sessParams['referer'],
      $this->sessParams['module'],
      $this->sessParams['lang'],
      $this->sessParams['request_params']);

    EPStat::commitStat();

    $result['reqParams']['execTime'] = (EPUtils::getMicroTime() - $this->startExecTime);
    return $result;
  }


  /**
  * multiCall с сериализацией данных
  *
  * @param Array $funcs
  * @param Array $params
  * @return Array
  */
  final public function multiCallSerialize($funcs, $params) {
    $this->startExecTime = EPUtils::getMicroTime();

    //Кастыль для кривых PHP-реализаций SOAP...
    $funcs = EPUtils::convertObjectToArrayRecursion($funcs);
    $params = EPUtils::convertObjectToArrayRecursion($params);

    $result = $this->multiCall($funcs, $params);

    return serialize($result);
  }

  /**
  * Вспомогательная функция для получения названий различных элеменов (стран, городов, категорий и т.п.). Используется когда известен Id элемента и нужно получить его название.
  * Массив $params может иметь вид:
  * $params = array('city' => 10, 'country' => 20);
  * Или $params = array('city' => array(10, 11, 15), 'country' => array(20, 21, 44));
  * Второй тип используется когда необходимо получить несколько значений одного типа.
  * В любом случае возвращается ассоциативные массивы, ключами выступают id записей.
  * Возможные запросы: region, country, city, category, subCategory, exhibition, socialOrganisation, expoCenter, servComp, servCompCat
  *
  * @param Array $params
  * @return Array
  */
  public function getNameById ($params) {
    $result = array('reqParams' => $params, 'data' => array());
    $data = array();

    if (!is_array($params)) {
      return $result;
    }

    foreach ($params as $key => $value) {
      $query = null;

      if (is_array($value)) {
        foreach ($value AS $elKey => $el) {
          $value[$elKey] = intval($el);
        }

        $whereId = implode(", ", $value);
      } else {
        $whereId = intval($value);
      }
      $whereId = "(" . $whereId . ")";

      switch ($key) {
        case "region":
          $query = "SELECT id, name FROM location_regions_data WHERE languages_id=" . $this->sessParams['langId'] . " AND id IN " . $whereId;
          break;
        case "country":
          $query = "SELECT id, name FROM location_countries_data WHERE languages_id=" . $this->sessParams['langId'] . " AND id IN " . $whereId;
          break;
        case "city":
          $query = "SELECT id, name FROM location_cities_data WHERE languages_id=" . $this->sessParams['langId'] . " AND id IN " . $whereId;
          break;
        case "category":
          $query = "SELECT id, name FROM brands_categories_data WHERE languages_id=" . $this->sessParams['langId'] . " AND id IN " . $whereId;
          break;
        case "subCategory":
          $query = "SELECT id, name FROM brands_subcategories_data WHERE languages_id=" . $this->sessParams['langId'] . " AND id IN " . $whereId;
          break;
        case "exhibition":
          $query = 'SELECT e.id, bd.name
            FROM events AS e
            JOIN events_data AS ed ON (e.id=ed.id)
            JOIN brands_data AS bd ON (e.brands_id=bd.id)
            WHERE ed.languages_id=bd.languages_id AND ed.languages_id=' . $this->sessParams['langId'] . ' AND e.id IN ' . $whereId;
          break;
        case "socialOrganisation":
          $query = 'SELECT id, name FROM social_organizations_data WHERE languages_id=' . $this->sessParams['langId'] . ' AND id IN ' . $whereId;
          break;
        case "expoCenter":
          $query = 'SELECT id, name FROM expocenters_data WHERE languages_id=' . $this->sessParams['langId'] . ' AND id IN ' . $whereId;
          break;
        case "servComp":
          $query = 'SELECT id, name FROM service_companies_data WHERE languages_id=' . $this->sessParams['langId'] . ' AND id IN ' . $whereId;
          break;
        case "servCompCat":
          $query = 'SELECT id, name FROM service_companies_categories_data WHERE languages_id=' . $this->sessParams['langId'] . ' AND id IN ' . $whereId;
          break;
        case "company":
          $query = "SELECT id, name FROM companies_data WHERE languages_id=" . $this->sessParams['langId'] . ' AND id IN ' . $whereId;
          break;
        case "company_srv_cats":
          $query = "SELECT id, name FROM companies_services_cats_data WHERE languages_id=" . $this->sessParams['langId'] . ' AND id IN ' . $whereId;
          break;
      }

      if (!is_null($query)) {
        try {
          $req = DB_PDO::fetchAll($query);
          foreach ($req as $res) {
            $data[$key . "_" . $res['id']] = $res['name'];
          }
        } catch (Exception $e) {
          $this->error = true;
          $data[$key] = array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
        }
      }
    }

    $result['data'] = $data;

    return $result;
  }

  /**
  * Функция аутентификации пользователя.
  * Передается $userName - имя пользователя, $userPassHash - MD5 хеш пароля.
  *
  * @param String $userName
  * @param String $userPassHash
  * @return Array
  */
  protected function userAuth($userName, $userPassHash) {
    $result['reqParams'] = array('userName' => $userName, 'userPassHash' => strlen($userPassHash));
    $result['status'] = 0;

    $query =
     "SELECT
        u.id, u.login, u.name AS FIO, u.fname, _CITY.name AS cityName,
        u.countries_id AS countryId, u.companies_id AS companyId,
        _COUNTRY.name AS countryName, c.postcode AS zipcode, cd.address AS address,
        cd.name AS companyName, u.text_dolgnost AS positionName, status, functions,
        c.phone AS phone, c.fax AS fax, u.email, u.text_sfera AS workArea, c.web_address AS webAddress,
        u.text_uznali AS howKnown, cd.description AS comment, u.check2_news AS recieveNews, photo_exists,
        c.email AS company_email, u.select_periodmail AS sendMailPeriod, u.select_exhibannounce AS exhibAnnouncePeriod,
        u.languages_id AS userLang, _LANG.code AS userLangCode, u.is_admin
      FROM users_sites AS u
        LEFT JOIN companies AS c ON c.id = u.companies_id
        LEFT JOIN companies_data AS cd ON cd.id = c.id AND cd.languages_id = '{$this->sessParams['langId']}'
        LEFT JOIN location_cities_data AS _CITY ON (_CITY.id = c.cities_id AND _CITY.languages_id = '" . $this->sessParams['langId'] . "')
        LEFT JOIN location_countries_data AS _COUNTRY ON (_COUNTRY.id = u.countries_id AND _COUNTRY.languages_id = '" . $this->sessParams['langId'] . "')
        LEFT JOIN languages AS _LANG ON _LANG.id = u.languages_id
      WHERE u.login='" . mysql_escape_string($userName) . "'
        AND MD5(u.passwd) = '" . mysql_escape_string($userPassHash) . "'
        AND u.active = 1
    ";

    try {
      $data = DB_PDO::fetchAll($query);
      if (count($data) == 1) {
        $data = $data[0];
        $data['passHash'] = $userPassHash;
        $result['status'] = 1;

        $query = "SELECT brands_categories_id AS id FROM users_sites_to_brands_categories WHERE users_sites_id = '" . intval($data['id']) . "'";
        $tmp = DB_PDO::fetchAll($query);
        $data['selCats'] = array();
        foreach ($tmp as $el) {
          $data['selCats'][] = $el['id'];
        }

        $data['functions'] = $data['functions'] ? explode(",", $data['functions']) : array();
      }
    } catch (Exception $e) {
      $this->error = true;
      $data = array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }

    $result['data'] = $data;

    return $result;
  }

  protected function activateUserAccount($coded_login, $coded_email) {
    $result['reqParams'] = array('code1' => $coded_login, 'code2' => $coded_email);
    $result['status'] = 0;

    $query =
     "SELECT u.id, u.login, u.passwd FROM users_sites AS u
      WHERE MD5(u.login)='" . mysql_escape_string($coded_login) . "'
        AND MD5(u.email) = '" . mysql_escape_string($coded_email) . "'
    ";

    try {
      $data = DB_PDO::fetchAll($query);

      if (count($data) == 1) {
        $data = $data[0];
        $data['active'] = 1;

        $result['status'] = 1;

/*
        mail(
          'eugene.ivashin@expopromogroup.com',
          'USER ACTIVATION data',
          print_r($data, true) . "\n\n"
        );

*/
        DB_PDO::perform("users_sites", $data, "update", "id = '{$data['id']}'");
        $res = $this->userAuth($data['login'], md5($data['passwd']));

        return $res;
      }
    } catch (Exception $e) {
      $this->error = true;
      $data = array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }

    $result['data'] = $data;

    return $result;
  }

  /**
  * Функция создает новый аккаунт. Поля $data['login'], $data['password'] и $data['email'] являются обязательными. $data['login']>2 и validate($data['email']) == true если это не соблюдено, status = 0 и акаунт не создается.
  * Если введенные данные не соответствуют минимальным условиям, возвращает 3
  * Если имя пользователя уже занято status выставляется равным 2
  * В случае успешного завершения операции status = 1
  * Список допустимых параметров:
  * login - имя для входа пользователя
  * password - пароль
  * userLangId - id языка по-умолчанию для пользователя
  * name - Фамилия, Имя, Отчество
  * countryId - id страны пользователя
  * cityName - город пользователя
  * companyName - название компании
  * positionName - должность пользователя
  * workArea - сфера деятельности
  * phone - телефон
  * fax - факс
  * email - адрес электронной почты
  * webAddress - адрес в интеренет
  * howKnown - как узнали о нас
  * sendMailPeriod - период рассылки новостей
  * exhibAnnouncePeriod - период за который необходимо проинформировать пользователя о начале выставки из его календаря, дней
  *
  * @param Array $data
  * @return Array
  */
  protected function userCreateAccount(Array $data, $send_confirmation = true) {
    $result = array('status' => 0);

    if (isset($data['login']) && isset($data['password']) && strlen($data['login'])>2) {
      if (Optimized_EPUtils::checkLoginExistent($data['login']) === false) {

        //Подставляем название города в случае если он не указан вручную, а выбран из списка
        if (isset($data['cityId']) && (!isset($data['cityName']) || strlen($data['cityName']) == 0)) {
          $cityName = $this->getNameById(array('city' => $data['cityId']));
          $cityName = $cityName['data']['city_' . $data['cityId']];
          $data['cityName'] = $cityName;
        }

        $insertData = Optimized_EPUtils::prepearUsersDataQuery($data);

        $insertData['login'] = $data['login'];
        $insertData['active'] = 0;
        $insertData['sites_id'] = $this->sessParams['siteID'];

        if ($data['photo'] && $data['photo']['image']) {
          $photo = $data['photo']['image'];
        }

        try {
          $companyData = array(
            'id'          => $data['company_id'],
            'local_languages_id' => isset($data['userLangId']) ? $data['userLangId'] : '1',
            'cities_id'   => $this->_checkCity($data['cityName'], isset($data['countryId']) ? $data['countryId'] : 52),
            'phone'       => isset($data['phone'])         ? $data['phone']         : '',
            'fax'         => isset($data['fax'])           ? $data['fax']           : '',
            'postcode'    => isset($data['zipcode'])       ? $data['zipcode']       : '',
            'email'       => isset($data['company_email']) ? $data['company_email'] : '',
            'web_address' => isset($data['webAddress'])    ? $data['webAddress']    : '',
          );

          $companyLangData = array(
            'id'          => $data['company_id'],
            'name'        => isset($data['companyName'])      ? $data['companyName']      : '',
            'description' => isset($data['textarea_comment']) ? $data['textarea_comment'] : '',
            'address'     => isset($data['address'])          ? $data['address']          : '',
          );

            $company_id = $this->_checkCompany($companyData, $companyLangData);
            if ($insertData['companies_id'] != $company_id) {
              $insertData['is_admin'] = 1;
              $insertData['companies_id'] = $company_id;
            }

          DB_PDO::perform('users_sites', $insertData, 'insert');
          $uid = DB_PDO::last_insert_id();

          if (empty($uid)) {
            $this->error = true;
            return array('errorCode' => 666, 'errorMessage' => 'No user record was created', 'status' => 0);
          }

          if (is_array($data['userCatIds']) && count($data['userCatIds'])>0) {
            Optimized_EPUtils::setUserNewsletterCategories($uid, $data['userCatIds']);
          }

          if (isset($photo)) {
            // /admin/htdocs/data/images/user_sites
            $save_as_base = PATH_FRONTEND_DATA_IMAGES . "/user_sites/";
            $save_as = $save_as_base . $uid . ".jpg";
            if (!file_put_contents($save_as, base64_decode($photo))) {
              DB_PDO::perform("users_sites", array('photo_exists' => 0), "update", "id = '{$uid}'");
            }
          }
        } catch (Exception $e) {
          $this->error = true;
          return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage(), 'status' => 0);
        }

        $result['status'] = 1;

        $data['password'] = strlen($data['password']);
        $result['data'] = $data;

        //Отправляем мыло с подтверждением регистрации
/*
        require_once(PATH_CLASSES . "/Email/EPEmailConfirmRegistration.Class.php");
        $email = new EPEmailConfirmRegistration();
        $email->sendConfirmationEmail($uid);
*/

        //$site_data = $this->getPublisher($this->sessParams['siteID']);

        switch ($this->sessParams['siteID']) {
          case 37:
            $data['site'] = "ExpoUA.com";
            $base_url = "http://ExpoUA.com";
            break;

          case 40:
            $data['site'] = "ExpoTop.ru";
            $base_url = "http://ExpoTop.ru";
            break;

          default:
            $data['site'] = "ExpoPromoter.com";
            $base_url = "ExpoPromoter.com";
        }

        //$base_url = trim((strpos($site_data['url'], 'http://') === 0 ? "" : 'http://') . $site_data['url'], '/');

        //$data['site'] = $site_data['url'];
        $data['confirmationLink_ru'] =
          $base_url . "/User/lang/ru/action/confirm/code/" . md5($data['login']) . '-' . md5($data['email']);
        $data['confirmationLink_en'] =
          $base_url . "/User/lang/en/action/confirm/code/" . md5($data['login']) . '-' . md5($data['email']);

/*
        mail(
          'eugene.ivashin@expopromogroup.com',
          'USER REGISTRATION data',
          print_r($data, true) . "\n\n" . print_r($site_data, true)
        );
*/

        //Отправляем мыло с подтверждением регистрации
        require_once(PATH_CLASSES . "/iMail/Notify/Registration.php");
        $email = new iMail_Notify_Registration();

        $email->siteUserNotification($data);

      } else {
        $result['status'] = 2;
      }
    } else {
      $result['status'] = 3;
    }

    return $result;
  }

  /**
  * Изменение данных пользователя. Для смены данных сперва необходимо пройти аутентификацию, передав имя пользователя и md5 пароля. В массиве $data передаются данные, которе необходимо изменить, не переданные параметры затронуты не будут.
  * Список допустимых параметров:
  * password - пароль
  * userLangId - id языка по-умолчанию для пользователя
  * name - Фамилия, Имя, Отчество
  * countryId - id страны пользователя
  * cityName - город пользователя
  * companyName - название компании
  * positionName - должность пользователя
  * workArea - сфера деятельности
  * phone - телефон
  * fax - факс
  * email - адрес электронной почты
  * webAddress - адрес в интеренет
  * howKnown - как узнали о нас
  * sendMailPeriod - период рассылки новостей
  * exhibAnnouncePeriod - период за который необходимо проинформировать пользователя о начале выставки из его календаря, дней
  *
  * Функция возвращает сради других параметров status равный 1 в случае успеха и 0 в случае ошибки
  *
  * @param String $userName
  * @param String $userPassHash
  * @param Array $data
  * @return Array
  */
  protected function userChangeData($userName, $userPassHash, $data) {
    $result['reqParams'] = array('userName' => $userName, 'userPassHash' => strlen($userPassHash));
    $result['status'] = 1;

    $authTest = $this->userAuth($userName, $userPassHash);

    if ($authTest !== false) {
      // mail('eugene.ivashin@expopromogroup.com', 'USER raw DATA check', print_r($data, true));

      $updateData = Optimized_EPUtils::prepearUsersDataQuery($data);

      // mail('eugene.ivashin@expopromogroup.com', 'USER DATA check', print_r($authTest, true));

      if (count($updateData) > 0) {
        if ($data['photo'] && $data['photo']['image']) {
          $photo = $data['photo']['image'];
        }

        try {
          $companyData = array(
            'id'          => $data['company_id'],
            'local_languages_id' => isset($data['userLangId']) ? $data['userLangId'] : '1',
            'cities_id'   => $this->_checkCity($data['cityName'], isset($data['countryId']) ? $data['countryId'] : 52),
            'phone'       => isset($data['phone'])         ? $data['phone']         : '',
            'fax'         => isset($data['fax'])           ? $data['fax']           : '',
            'postcode'    => isset($data['zipcode'])       ? $data['zipcode']       : '',
            'email'       => isset($data['company_email']) ? $data['company_email'] : '',
            'web_address' => isset($data['webAddress'])    ? $data['webAddress']    : '',
          );

          $companyLangData = array(
            'id'          => $data['company_id'],
            'name'        => isset($data['companyName'])      ? $data['companyName']      : '',
            'description' => isset($data['textarea_comment']) ? $data['textarea_comment'] : '',
            'address'     => isset($data['address'])          ? $data['address']          : '',
          );

            $company_id = $this->_checkCompany($companyData, $companyLangData, $authTest['data']['is_admin']);
            if ($updateData['companies_id'] != $company_id) {
              $updateData['is_admin'] = 1;
              $updateData['companies_id'] = $company_id;
            }

          // mail('eugene.ivashin@expopromogroup.com', 'USER DATA update', print_r($updateData, true));

          DB_PDO::perform("users_sites", $updateData, "update", "login = '" . mysql_escape_string($userName) . "'");

          if (is_array($data['userCatIds'])) {
            Optimized_EPUtils::setUserNewsletterCategories($authTest['data']['id'], $data['userCatIds']);
          }

          if (isset($photo)) {
            // /admin/htdocs/data/images/user_sites
            $save_as_base = PATH_FRONTEND_DATA_IMAGES . "/user_sites/";

            // mail('eugene.ivashin@expopromogroup.com', 'HERE IS THE PICTURE!', print_r($photo, true));

            $save_as = $save_as_base . $authTest['data']['id'] . ".jpg";
            if (!file_put_contents($save_as, base64_decode($photo))) {
              DB_PDO::perform("users_sites", array('photo_exists' => 0), "update", "id = '{$authTest['data']['id']}'");
            }
          }
        } catch (Exception $e) {
          $this->error = true;
          return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage(), 'status' => 0);
        }
      } else {
        $result['status'] = 0;
      }
    } else {
      $result['status'] = 0;
    }

    if (isset($data['password'])) {
      $data['password'] = strlen($data['password']);
    }

    $result['data'] = $data;

    return $result;
  }

  /**
  * Функция восстановления пароля зарегистрированного пользователя
  * В качестве параметра принимает либо логин, либо email, либо одновременно оба параметра
  * Если пользователь будет найден, ему на мыло прийдет его пароль
  *
  * @param string $login
  * @param string $email
  * @return int
  */
  protected function userRecoverPassword($login = '', $email = '') {

    require_once(PATH_CLASSES . "/iMail/Distribute/Company.php");
    $iMail = new iMail_Distribute_Company();
    $iMail->userPasswordRecover($login, $email);

    return 1;
  }

  public function checkExistingPartner($loginName) {
    $query = "SELECT COUNT(id) AS cnt FROM partners WHERE login = '" . mysql_escape_string($loginName) . "'";

    try {
      $res = DB_PDO::fetchAll($query);
      if ($res[0]['cnt'] == 0) {
        return false;
      } else {
        return true;
      }
    } catch (Exception $e) {
      return false;
    }
  }


  public function checkExistingVenue($loginName) {
    $query = "SELECT COUNT(id) AS cnt FROM expocenters WHERE login = '" . mysql_escape_string($loginName) . "'";

    try {
      $res = DB_PDO::fetchAll($query);
      if ($res[0]['cnt'] == 0) {
        return false;
      } else {
        return true;
      }
    } catch (Exception $e) {
      return false;
    }
  }


  public function checkExistingOrganizer($loginName) {
    $query = "SELECT COUNT(id) AS cnt FROM users_operators WHERE login = '" . mysql_escape_string($loginName) . "'";

    try {
      $res = DB_PDO::fetchAll($query);
      if ($res[0]['cnt'] == 0) {
        return false;
      } else {
        return true;
      }
    } catch (Exception $e) {
      return false;
    }
  }


  public function checkExistingAdvertiser($loginName) {
    $query = "SELECT COUNT(id) AS cnt FROM ExpoPromoter_banners.pbl_users WHERE login = '" . mysql_escape_string($loginName) . "'";

    try {
      $res = DB_PDO::fetchAll($query);
      if ($res[0]['cnt'] == 0) {
        return false;
      } else {
        return true;
      }
    } catch (Exception $e) {
      return false;
    }
  }


  public function partnerCreateAccount($data) {
    // error_reporting(E_ALL);
    $result = array('status' => 0);

    if (isset($data['captcha_match']) && !$data['captcha_match']) return 9;

    $lang = $this->sessParams['langId'];

    if (isset($data['login']) && isset($data['passwd']) && strlen($data['login'])>2) {
      if ($this->checkExistingPartner($data['login']) === false) {
        $data['comment'] = 'Website: ' . $data['url'] . "\n\n" . $data['comment'];

        unset($data['url']);
        unset($data['action']);
        unset($data['country']);
        unset($data['captcha_match']); unset($data['img_checker']);

        $data['cities_id'] = !empty($data['cities_id']) ? $data['cities_id'] : 252;

        $data['active'] = 0;
        $data['language'] = $lang;

        try {
          DB_PDO::perform('partners', $data, 'insert');
          $uid = DB_PDO::last_insert_id();
          $data['id'] = $uid;
          /* mail("eivashin@gmail.com", "TEST", print_r($data, true)); */
        } catch (Exception $e) {
          $this->error = true;
          return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage(), 'status' => 0);
        }

        $result['status'] = 1;

        // $data['passwd'] = strlen($data['passwd']);
        $result['data'] = $data;

        //Отправляем мыло с подтверждением регистрации
        require_once(PATH_CLASSES . "/iMail/Notify/Registration.php");
        $email = new iMail_Notify_Registration();

        if ($data['type'] == 'partner') {
          $email->webPartnerNotification($data);
        } else {
          $email->travelCompanyNotification($data);
        }

      } else {
        $result['status'] = 2;
      }
    } else {
      $result['status'] = 3;
    }

    return $result;
  }




  public function venueCreateAccount($data) {
    // error_reporting(E_ALL);
    $result = array('status' => 0);

    if (isset($data['captcha_match']) && !$data['captcha_match']) return 9;

    $lang = $this->sessParams['langId'];

    if (isset($data['login']) && isset($data['passwd']) && strlen($data['login'])>2) {
      if ($this->checkExistingVenue($data['login']) === false) {
        unset($data['url']);
        unset($data['action']);
        unset($data['country']);
        unset($data['captcha_match']); unset($data['img_checker']);

        $data['cities_id'] = !empty($data['cities_id']) ? $data['cities_id'] : 252;

        $data['active'] = 0;
        $data['language'] = $lang;

        $expocenter = array(
          'active'    => 0,
          'login'     => $data['login'],
          'passwd'    => $data['passwd'],
          'cities_id' => $data['cities_id'],
        );

        try {
          DB_PDO::perform('expocenters', $expocenter, 'insert');
          $uid = DB_PDO::last_insert_id();
          // mail("eivashin@gmail.com", "TEST", print_r($data, true));
        } catch (Exception $e) {
          $this->error = true;
          return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage(), 'status' => 0);
        }

        $langs = $this->getLanguagesList();

        foreach ($langs['data'] as $lang) {
          $expocenter_data = array(
            'id' => $uid,
            'languages_id' => $lang['id'],
            'name'         => $data['name'],
            'phone'        => $data['contact_phone'],
            'email'        => $data['contact_email'],
            'contact_name' => $data['contact_name'],
            'web_address'  => $data['url'],
            'description'  => $data['comment'],
          );

          try {
            DB_PDO::perform('expocenters_data', $expocenter_data, 'insert');
            $ed_id = DB_PDO::last_insert_id();
          } catch (Exception $e) {
            $this->error = true;
            return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage(), 'status' => 0);
          }
        }

        $result['status'] = 1;

        // $data['passwd'] = strlen($data['passwd']);
        $result['data'] = $data;

        //Отправляем мыло с подтверждением регистрации
        require_once(PATH_CLASSES . "/iMail/Notify/Registration.php");
        $email = new iMail_Notify_Registration();

        $email->venueNotification($data);

      } else {
        $result['status'] = 2;
      }
    } else {
      $result['status'] = 3;
    }

    return $result;
  }




  public function organizerCreateAccount($data) {
    $result = array('status' => 0);

    if (isset($data['captcha_match']) && !$data['captcha_match']) return 9;

    $lang = $this->sessParams['langId'];

    if (isset($data['login']) && isset($data['passwd']) && strlen($data['login'])>2) {
      if ($this->checkExistingOrganizer($data['login']) === false) {
        $qdata['active']   = 0;
        $qdata['super']    = 1;
        $qdata['type']     = 'organizer';
        $qdata['login']    = $data['login'];
        $qdata['passwd']   = $data['passwd'];
        $qdata['name_fio'] = $data['name'];
        $qdata['email']    = $data['email'];
        $qdata['position'] = $data['position'];
        $qdata['organizer_manual_name'] = $data['company'];

        try {
          DB_PDO::perform('users_operators', $qdata, 'insert');
          $uid = DB_PDO::last_insert_id();
          //mail("eugene.ivashin@expopromogroup.com", "TEST", print_r($data, true));
        } catch (Exception $e) {
          $this->error = true;
          return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage(), 'status' => 0);
        }

        $result['status'] = 1;

        // $qdata['passwd']   = strlen($data['passwd']);
        $data['language'] = $lang;
        $result['data']    = $qdata;

        //Отправляем мыло с подтверждением регистрации
        require_once(PATH_CLASSES . "/iMail/Notify/Registration.php");
        $email = new iMail_Notify_Registration();

        $email->organizerNotification($data);

      } else {
        $result['status'] = 2;
      }
    } else {
      $result['status'] = 3;
    }

    return $result;
  }




  public function advertiserCreateAccount($data) {
    $result = array('status' => 0);

    if (isset($data['captcha_match']) && !$data['captcha_match']) return 9;

    $lang = $this->sessParams['langId'];

    if (isset($data['login']) && isset($data['passwd']) && strlen($data['login'])>2) {
      if ($this->checkExistingAdvertiser($data['login']) === false) {
        $qdata['active']   = 0;
        $qdata['login']    = $data['login'];
        $qdata['passwd']   = $data['passwd'];
        $qdata['name']     = $data['name'];
        $qdata['company']  = $data['company'];
        $qdata['countries_id'] = $data['country'];
        $qdata['city']     = $data['city'];
        $qdata['email']    = $data['email'];
        $qdata['phone']    = $data['phone'];
        $qdata['url']      = $data['url'];

        try {
          DB_PDO::perform('ExpoPromoter_banners.pbl_users', $qdata, 'insert');
          $uid = DB_PDO::last_insert_id();
          // mail("eugene.ivashin@expopromogroup.com", "TEST", print_r($data, true));
        } catch (Exception $e) {
          $this->error = true;
          return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage(), 'status' => 0);
        }

        $result['status'] = 1;

        // $qdata['passwd']   = strlen($data['passwd']);
        $data['language'] = $lang;
        $result['data']    = $qdata;

        //Отправляем мыло с подтверждением регистрации
        require_once(PATH_CLASSES . "/iMail/Notify/Registration.php");
        $email = new iMail_Notify_Registration();

        $email->advertiserNotification($data);

      } else {
        $result['status'] = 2;
      }
    } else {
      $result['status'] = 3;
    }

    return $result;
  }





  /**
  * Отправка запроса. Кому именно адресован запрос, определяется параметром $requestType. Может приниматьзначания:
  * exhibitionExtraInfoRequest - Запрос дополнительной информации о выставке -1-
  * exhibitionParticipationRequest - Запрос экспоместа (участия) -2-
  * businessTourRequest - Заказ бизнес-тура -3-
  * exhibitionAdvertSpreadRequest - Заказать распространение рекламной продукции на выставке -5-
  * serviceCompanyRequest - Запросы к сервисным компаниям -7-
  * exhibitionCenterRequest - Запросы к выставочным центрам -8-
  * socialOrganizationRequest - Запросы к ассоциациям/союзам/федерациям -9-
  * exhibitionRemoteAttendanceRequest - Предварительный заказ заочного посещения выставки
  *
  * Массив данных $data может содержать следующие поля:
  * requestName - Заголовок запроса, берется из названия выставки, организации, ... со страницы которой был отправлен запрос
  * companyName - Название компании
  * contactPerson - ФИО контактного лица
  * phone - контактный телефон
  * email - контактный адрес электронной почты
  * address - адрес
  * extraInfo - дополнительная информация
  * requestPurposeId - номер цели запроса. Список целей пожно получить при помощи функции getUserRequestPurposes
  * countryId - id страны пользователя
  * eventId - id события
  * brandId - id бренда
  * centerId - id выставочного центра
  * orgId - id организатора
  *
  * positionName - должность
  * fax - факс
  * webAddress - URL
  * areaType1 - необорудованная площадь
  * areaType2 - оборудованная площадь
  * areaType3 - открытая площадь
  * standType1 - ТИП1: Линейный стенд (открыт в одну сторону)
  * standType2 - ТИП2: Угловой стенд (открыт в две стороны)
  * standType3 - ТИП3: Полуостров (открыт в три стороны)
  * standType4 - ТИП4: Остров (открыт в четыре стороны)
  * standType5 - Двухэтажный стенд
  * standType6 - ?
  * zType1 - Гостиница *
  * zType2 - Гостиница **
  * zType3 - Гостиница ***
  * zType4 - Гостиница ****
  * zType5 - Гостиница *****
  * checkSGL - SGL
  * checkDBL - DBL
  * checkTRPL - TRPL
  * checkPay1 - 0-50
  * checkPay2 - 50-100
  * checkPay3 - 100-150
  * checkPay4 - >150
  * checkAir - самолет
  * checkTrain - поезд
  * checkBus - автобус
  * checkAnother - другое
  * extraDetails - дополнительные пожелания
  *
  * @param String $requestType
  * @param Array $data
  * @return Array
  */
  public function sendUserRequest($requestType, $data) {
    $result['reqParams'] = array('requestType' => $requestType, 'data' => $data);
    $result['status'] = 1;

    if (!is_array($data)) {
      $result['status'] = 0;
      return $result;
    }

    $banished_emails = isset($banished_emails) ? $banished_emails : array();

    if (isset($GLOBALS['banished_emails'])) {
      if (in_array($data['email'], $GLOBALS['banished_emails'])) {
        $result['status'] = 0;
        return $result;
      }
    }

    $bl_query = "SELECT INET_NTOA(host) AS ip, note FROM ExpoPromoter_Opt.hosts_black_list WHERE host = INET_ATON('" . $this->sessParams['clientIP'] . "') LIMIT 1";
    $bl_res = DB::query($bl_query);

    if (!empty($bl_res)) {
      $result['status'] = 0;
      return $result;
    }


    //Защита от виагровцев
    foreach ($data as $el) {
      if (!is_array($el) && Optimized_EPUtils::viagraDetector($el) == true) {
        $result['status'] = 0;
        return $result;
      }
    }

    //Устанавливаем низкий уровень ошибок чтобы небыло предупреждений
    error_reporting(E_ERROR);

    require_once(PATH_CLASSES . "/iMail/Distribute/InformOrders.php");
    $iMail = new iMail_Distribute_InformOrders();

    $data['languages_id'] = $this->sessParams['langId'];

    $host = !empty($this->sessParams['clientIP']) ? $this->sessParams['clientIP'] : $_SERVER['REMOTE_ADDR'];

    //Подготавливаем запрос для сохранения в базе
    $stmt_req = DB_PDO::getInst()->prepare(
        "INSERT INTO requests SET type=:type, parent=:parent, child=:child, countries_id=:country, sites_id='" .
        $this->sessParams['siteID'] . "', languages_id='" . $this->sessParams['langId'] . "', host=INET_ATON('" . $host . "')");

    switch ($requestType) {
      case "exhibitionExtraInfoRequest":

        //Отправляем запрос на мыло
        $iMail->organizer($requestType, $data);

        //Сохраняем запрос в базе
        $res = $stmt_req->execute(array(':type' => $requestType, ':parent' => intval($data['orgId']), ':child' => intval($data['eventId']), ':country' => $data['countryId']));

        //Сохраняем данные, связанные с запросом
        if ($res) {
          $request_id = DB_PDO::getInst()->lastInsertId();
          //Подготавливаем запрос для сохранения данных, связанных с запросом
          $stmt_req_data = DB_PDO::getInst()->prepare("INSERT INTO requests_data (requests_id, type, value) VALUES ('" . $request_id . "', :type, :value)");

          $stmt_req_data->execute(array(':type' => 'name', ':value' => $data['companyName']));
          $stmt_req_data->execute(array(':type' => 'contact_person', ':value' => $data['contactPerson']));
          $stmt_req_data->execute(array(':type' => 'phone', ':value' => $data['phone']));
          $stmt_req_data->execute(array(':type' => 'email', ':value' => $data['email']));
          $stmt_req_data->execute(array(':type' => 'address', ':value' => $data['address']));
          $stmt_req_data->execute(array(':type' => 'purpose', ':value' => $data['requestPurposeId']));
          $stmt_req_data->execute(array(':type' => 'message', ':value' => $data['extraInfo']));
        }

          $this->countRequest(intval($data['eventId']));

        break;

      case "exhibitionParticipationRequest":
        $iMail->organizer($requestType, $data);

        $res = $stmt_req->execute(array(':type' => $requestType, ':parent' => intval($data['orgId']), ':child' => intval($data['eventId']), ':country' => $data['countryId']));

        if ($res) {
          $request_id = DB_PDO::getInst()->lastInsertId();
          //Подготавливаем запрос для сохранения данных, связанных с запросом
          $stmt_req_data = DB_PDO::getInst()->prepare("INSERT INTO requests_data (requests_id, type, value) VALUES ('" . $request_id . "', :type, :value)");

          $stmt_req_data->execute(array(':type' => 'name', ':value' => $data['companyName']));
          $stmt_req_data->execute(array(':type' => 'contact_person', ':value' => $data['contactPerson']));
          $stmt_req_data->execute(array(':type' => 'position', ':value' => $data['positionName']));
          $stmt_req_data->execute(array(':type' => 'city', ':value' => $data['cityName']));
          $stmt_req_data->execute(array(':type' => 'phone', ':value' => $data['phone']));
          $stmt_req_data->execute(array(':type' => 'fax', ':value' => $data['fax']));
          $stmt_req_data->execute(array(':type' => 'email', ':value' => $data['email']));
          $stmt_req_data->execute(array(':type' => 'url', ':value' => $data['webAddress']));
          $stmt_req_data->execute(array(':type' => 'address', ':value' => $data['address']));
          $stmt_req_data->execute(array(':type' => 'message', ':value' => $data['extraInfo']));
          $stmt_req_data->execute(array(':type' => 'details', ':value' => $data['extraDetails']));

          //Площадь стендов
          $stmt_req_data->execute(array(':type' => 'S1', ':value' => intval($data['areaType1'])));
          $stmt_req_data->execute(array(':type' => 'S2', ':value' => intval($data['areaType2'])));
          $stmt_req_data->execute(array(':type' => 'S3', ':value' => intval($data['areaType3'])));

          //Типы стендов
          $stmt_req_data->execute(array(':type' => 'check1', ':value' => (isset($data['standType1']) ? 1:0)));
          $stmt_req_data->execute(array(':type' => 'check2', ':value' => (isset($data['standType2']) ? 1:0)));
          $stmt_req_data->execute(array(':type' => 'check3', ':value' => (isset($data['standType3']) ? 1:0)));
          $stmt_req_data->execute(array(':type' => 'check4', ':value' => (isset($data['standType4']) ? 1:0)));
          $stmt_req_data->execute(array(':type' => 'check5', ':value' => (isset($data['standType5']) ? 1:0)));
        }

               $this->countRequest(intval($data['eventId']));

        break;

      case "businessTourRequest":
        $iMail->organizer($requestType, $data);
        break;

      case "newBusinessTourRequest":
        error_reporting(E_ALL);

        //Подготавливаем запрос для сохранения в базе
        $stmt_req2 = DB_PDO::getInst()->prepare(
         "INSERT INTO business_tour_requests SET
           events_id = :events_id,
           dest_country = :dest_country,
           dest_city    = :dest_city,
           date_from = :date_from, date_to = :date_to,
           hotel1 = :hotel1, hotel2 = :hotel2, hotel3 = :hotel3, hotel4 = :hotel4, hotel5 = :hotel5,
           persons = :persons, rooms = :rooms,
           cat_single = :cat_single, cat_double = :cat_double, cat_triple = :cat_triple,
           price1 = :price1, price2 = :price2, price3 = :price3, price4 = :price4,
           transport1 = :transport1, transport2 = :transport2,
           transport3 = :transport3, transport4 = :transport4,
           aux_transfert = :aux_transfert, aux_visa = :aux_visa,
           aux_translator = :aux_translator, aux_excursion = :aux_excursion,
           company = :company, contact_name = :contact_name, phone = :phone,
           country = :country, city = :city, email = :email, notes = :notes,
           sites_id = :site, languages_id = :lang
        ");

        $prepared_data = array(
          ':events_id'      => isset($data['events_id']) ? $data['events_id'] : null,
          ':dest_country'   => isset($data['dest_country']) ? $data['dest_country'] : '',
          ':dest_city'      => isset($data['dest_city']) ? $data['dest_city'] : '',
          ':date_from'      => isset($data['date_from']) ? $data['date_from'] : '',
          ':date_to'        => isset($data['date_to']) ? $data['date_to'] : '',
          ':hotel1'         => isset($data['hotel1']) ? 1 : 0,
          ':hotel2'         => isset($data['hotel2']) ? 1 : 0,
          ':hotel3'         => isset($data['hotel3']) ? 1 : 0,
          ':hotel4'         => isset($data['hotel4']) ? 1 : 0,
          ':hotel5'         => isset($data['hotel5']) ? 1 : 0,
          ':persons'        => isset($data['persons']) ? $data['persons'] : '',
          ':rooms'          => isset($data['rooms']) ? $data['rooms'] : '',
          ':cat_single'     => isset($data['cat_single']) ? 1 : 0,
          ':cat_double'     => isset($data['cat_double']) ? 1 : 0,
          ':cat_triple'     => isset($data['cat_triple']) ? 1 : 0,
          ':price1'         => isset($data['price1']) ? $data['price1'] : '',
          ':price2'         => isset($data['price2']) ? $data['price2'] : '',
          ':price3'         => isset($data['price3']) ? $data['price3'] : '',
          ':price4'         => isset($data['price4']) ? $data['price4'] : '',
          ':transport1'     => isset($data['transport1']) ? 1 : 0,
          ':transport2'     => isset($data['transport2']) ? 1 : 0,
          ':transport3'     => isset($data['transport3']) ? 1 : 0,
          ':transport4'     => isset($data['transport4']) ? 1 : 0,
          ':aux_transfert'  => isset($data['aux_transfert']) ? 1 : 0,
          ':aux_visa'       => isset($data['aux_visa']) ? 1 : 0,
          ':aux_translator' => isset($data['aux_translator']) ? 1 : 0,
          ':aux_excursion'  => isset($data['aux_excursion']) ? 1 : 0,
          ':company'        => isset($data['company']) ? $data['company'] : '',
          ':contact_name'   => isset($data['person']) ? $data['person'] : '',
          ':phone'          => isset($data['phone']) ? $data['phone'] : '',
          ':country'        => isset($data['country']) ? $data['country'] : '',
          ':city'           => isset($data['city']) ? $data['city'] : '',
          ':email'          => isset($data['email']) ? $data['email'] : '',
          ':notes'          => isset($data['notes']) ? $data['notes'] : '',
          ':site'           => isset($data['sites_id']) ? $data['sites_id'] : 40,
          ':lang'           => isset($this->sessParams['langId']) ? $this->sessParams['langId'] : 1,
        );

        $res = $stmt_req2->execute($prepared_data);

        if (isset($data['events_id'])) {
                DB_PDO::getInst()->exec("UPDATE statistic.events_counter SET breq_cnt=breq_cnt+1 WHERE id = " . intval($data['events_id']));
            }

        $iMail->tourOperator($requestType, $data);

        break;

      case "exhibitionCatalogAdvertRequest":
        $iMail->organizer($requestType, $data);

        $res = $stmt_req->execute(array(':type' => $requestType, ':parent' => intval($data['orgId']), ':child' => intval($data['eventId']), ':country' => $data['countryId']));

        if ($res) {
          $request_id = DB_PDO::getInst()->lastInsertId();
          //Подготавливаем запрос для сохранения данных, связанных с запросом
          $stmt_req_data = DB_PDO::getInst()->prepare("INSERT INTO requests_data (requests_id, type, value) VALUES ('" . $request_id . "', :type, :value)");

          $stmt_req_data->execute(array(':type' => 'name', ':value' => $data['companyName']));
          $stmt_req_data->execute(array(':type' => 'contact_person', ':value' => $data['contactPerson']));
          $stmt_req_data->execute(array(':type' => 'position', ':value' => $data['positionName']));
          $stmt_req_data->execute(array(':type' => 'city', ':value' => $data['cityName']));
          $stmt_req_data->execute(array(':type' => 'phone', ':value' => $data['phone']));
          $stmt_req_data->execute(array(':type' => 'fax', ':value' => $data['fax']));
          $stmt_req_data->execute(array(':type' => 'email', ':value' => $data['email']));
          $stmt_req_data->execute(array(':type' => 'url', ':value' => $data['webAddress']));
          $stmt_req_data->execute(array(':type' => 'address', ':value' => $data['address']));
          $stmt_req_data->execute(array(':type' => 'message', ':value' => $data['extraInfo']));

          //Типы рекламы
          $stmt_req_data->execute(array(':type' => 'check1', ':value' => (isset($data['standType1']) ? 1:0)));
          $stmt_req_data->execute(array(':type' => 'check2', ':value' => (isset($data['standType2']) ? 1:0)));
        }

               $this->countRequest(intval($data['eventId']));

        break;

      case "exhibitionAdvertSpreadRequest":
        $iMail->organizer($requestType, $data);

        $res = $stmt_req->execute(array(':type' => $requestType, ':parent' => intval($data['orgId']), ':child' => intval($data['eventId']), ':country' => $data['countryId']));

        if ($res) {
          $request_id = DB_PDO::getInst()->lastInsertId();
          //Подготавливаем запрос для сохранения данных, связанных с запросом
          $stmt_req_data = DB_PDO::getInst()->prepare("INSERT INTO requests_data (requests_id, type, value) VALUES ('" . $request_id . "', :type, :value)");

          $stmt_req_data->execute(array(':type' => 'name', ':value' => $data['companyName']));
          $stmt_req_data->execute(array(':type' => 'contact_person', ':value' => $data['contactPerson']));
          $stmt_req_data->execute(array(':type' => 'position', ':value' => $data['positionName']));
          $stmt_req_data->execute(array(':type' => 'city', ':value' => $data['cityName']));
          $stmt_req_data->execute(array(':type' => 'phone', ':value' => $data['phone']));
          $stmt_req_data->execute(array(':type' => 'fax', ':value' => $data['fax']));
          $stmt_req_data->execute(array(':type' => 'email', ':value' => $data['email']));
          $stmt_req_data->execute(array(':type' => 'url', ':value' => $data['webAddress']));
          $stmt_req_data->execute(array(':type' => 'address', ':value' => $data['address']));
          $stmt_req_data->execute(array(':type' => 'message', ':value' => $data['extraDetails']));

          //Количество рекламы
          $stmt_req_data->execute(array(':type' => 'S1', ':value' => intval($data['areaType1'])));
          $stmt_req_data->execute(array(':type' => 'S2', ':value' => intval($data['areaType2'])));

          //Типы рекламы
          $stmt_req_data->execute(array(':type' => 'check1', ':value' => (isset($data['standType3']) ? 1:0)));
          $stmt_req_data->execute(array(':type' => 'check2', ':value' => (isset($data['standType4']) ? 1:0)));
          $stmt_req_data->execute(array(':type' => 'check3', ':value' => (isset($data['standType5']) ? 1:0)));
          $stmt_req_data->execute(array(':type' => 'check4', ':value' => (isset($data['standType6']) ? 1:0)));
          $stmt_req_data->execute(array(':type' => 'check5', ':value' => (isset($data['standType7']) ? 1:0)));
        }

        break;
      case "serviceCompanyRequest":
        $iMail->serviceCompany($data);

        $res = $stmt_req->execute(array(':type' => $requestType, ':parent' => intval($data['brandId']), ':child' => intval($data['brandId']), ':country' => 0));

        if ($res) {
          $request_id = DB_PDO::getInst()->lastInsertId();
          //Подготавливаем запрос для сохранения данных, связанных с запросом
          $stmt_req_data = DB_PDO::getInst()->prepare("INSERT INTO requests_data (requests_id, type, value) VALUES ('" . $request_id . "', :type, :value)");

          $stmt_req_data->execute(array(':type' => 'name', ':value' => $data['companyName']));
          $stmt_req_data->execute(array(':type' => 'contact_person', ':value' => $data['contactPerson']));
          $stmt_req_data->execute(array(':type' => 'phone', ':value' => $data['phone']));
          $stmt_req_data->execute(array(':type' => 'email', ':value' => $data['email']));
          $stmt_req_data->execute(array(':type' => 'address', ':value' => $data['address']));
          $stmt_req_data->execute(array(':type' => 'message', ':value' => $data['extraInfo']));
        }

               $this->countRequest(intval($data['eventId']));

        break;

      case "exhibitionCenterRequest":
        $res = $stmt_req->execute(array(':type' => $requestType, ':parent' => intval($data['centerId']), ':child' => intval($data['centerId']), ':country' => 0));

        if ($res) {
          $request_id = DB_PDO::getInst()->lastInsertId();
          //Подготавливаем запрос для сохранения данных, связанных с запросом
          $stmt_req_data = DB_PDO::getInst()->prepare("INSERT INTO requests_data (requests_id, type, value) VALUES ('" . $request_id . "', :type, :value)");

          $stmt_req_data->execute(array(':type' => 'name', ':value' => $data['companyName']));
          $stmt_req_data->execute(array(':type' => 'contact_person', ':value' => $data['contactPerson']));
          $stmt_req_data->execute(array(':type' => 'phone', ':value' => $data['phone']));
          $stmt_req_data->execute(array(':type' => 'email', ':value' => $data['email']));
          $stmt_req_data->execute(array(':type' => 'address', ':value' => $data['address']));
          $stmt_req_data->execute(array(':type' => 'message', ':value' => $data['extraInfo']));
        }

               $this->countRequest(intval($data['eventId']));

        break;

      case "socialOrganizationRequest":
        break;

      case "exhibitionRemoteAttendanceRequest":
        $iMail->organizer($requestType, $data);

        $res = $stmt_req->execute(array(':type' => $requestType, ':parent' => intval($data['orgId']), ':child' => intval($data['eventId']), ':country' => 0));

        if ($res) {
          $request_id = DB_PDO::getInst()->lastInsertId();
          //Подготавливаем запрос для сохранения данных, связанных с запросом
          $stmt_req_data = DB_PDO::getInst()->prepare("INSERT INTO requests_data (requests_id, type, value) VALUES ('" . $request_id . "', :type, :value)");

          $stmt_req_data->execute(array(':type' => 'city', ':value' => $data['cityName']));
          $stmt_req_data->execute(array(':type' => 'contact_person', ':value' => $data['contactPerson']));
          $stmt_req_data->execute(array(':type' => 'phone', ':value' => $data['phone']));
          $stmt_req_data->execute(array(':type' => 'email', ':value' => $data['email']));
          $stmt_req_data->execute(array(':type' => 'message', ':value' => $data['extraDetails']));

          $stmt_req_data->execute(array(':type' => 'check1', ':value' => (isset($data['standType1']) ? 1:0)));
          $stmt_req_data->execute(array(':type' => 'check2', ':value' => (isset($data['standType2']) ? 1:0)));
          $stmt_req_data->execute(array(':type' => 'check3', ':value' => (isset($data['standType3']) ? 1:0)));
        }

               $this->countRequest(intval($data['eventId']));

        break;

      case "partnerBusinessTourRequest":
        $iMail->partner($requestType, $data);

        // $res = $stmt_req->execute(array(':type' => $requestType, ':parent' => intval($data['partnerId']), ':child' => intval($data['eventId']), ':country' => 0));

        //mail('eugene.ivashin@expopromogroup.com', 'BUSINESS TOUR REQUEST DEBUG', print_r($data, true));

        break;

      case "newstandardPartnerRequest":
        //mail('eugene.ivashin@expopromogroup.com', 'NEW STANDARD REQUEST DEBUG', print_r($data, true));

        $data['languages_id'] = isset($this->sessParams['langId']) ? $this->sessParams['langId'] : 1;
        $iMail->newstandard($requestType, $data);

        break;

      default:
        $result['status'] = 0;
        $result['message'] = 'Target parametr is wrong.';
        return $result;
    }

    return $result;
  }


  private function countRequest($id) {
      DB_PDO::getInst()->exec("UPDATE statistic.events_counter SET req_cnt=req_cnt+1 WHERE id = " . intval($id));
  }


  /**
  * Отправляет пользовательский запрос владельцу сайта.
  *
  * @param String $requestType
  * @param Array $data
  * @return Array
  */
  private function sendStartUserRequest($requestType, $data) {
    $result['reqParams'] = array('requestType' => $requestType, 'data' => $data);
    $result['status'] = 1;

    $headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/plain; charset="utf-8"' . "\r\n";
    $headers .= 'From: Site Form <do_not_reply@expopromoter.com>';

    $message = "Request type: " . $requestType . "\n\n";
    $message .= print_r($data, true);
    mail("dmitry.sinev@expopromogroup.com", "Site request", $message, $headers);

    return $result;
  }

  /**
  * Возвращает список возможных целей запроса для выбранного типа. Пока тип только один Exhibition, используется по-умолчанию если параметр опущен.
  * Вдальнейшем возможно будут добавлены другие типы.
  *
  * @param String $requestType
  * @return Array
  */
  private function getUserRequestPurposes($requestType = "Exhibition") {
    $result['reqParams'] = array('requestType' => $requestType);

    $query = "SELECT id, text_name AS name FROM " . DBPREFIX . $this->sessParams['lang'] . "_systemformstargetquery WHERE check_active = 1";

    try {
      $result['data'] = DB::query($query);
    } catch (Exception $e) {
      $this->error = true;
      $result['data'] = array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }

    return $result;
  }

  /**
  * Получаем дополнительный список возможных запросов для выбранной выставки.
  *
  * @param Integer $exhibitionId
  * @return Array
  */
  private function getAdditionalRequests($exhibitionId) {
    $result['reqParams'] = array('exhibitionId' => $exhibitionId);
    $result['data'] = array();

    $query =
     "SELECT o.id AS orgid, e.date_from, e.date_to, lcd.name AS city_name
      FROM events AS e
        JOIN brands AS b ON (e.brands_id=b.id)
        JOIN organizers AS o ON (b.organizers_id=o.id)
        JOIN location_cities_data AS lcd ON e.cities_id=lcd.id AND lcd.languages_id = ?
      WHERE e.id = ?
    ";

    $stmt = DB_PDO::getInst()->prepare($query);
    $stmt->execute(array($this->sessParams['langId'], $exhibitionId));
    $event = $stmt->fetchAll();
    if (empty($event)) {
        return $result;
    }
    $event = $event[0];
    $org = $event['orgid'];

    $stmt = null;

/*
    if ($org==1141) {
      return $result;
    }
*/

    $query = "SELECT user_request_types FROM events_common WHERE id=?";
    $stmt = DB_PDO::getInst()->prepare($query);
    $stmt->execute(array($exhibitionId));
    if ($stmt->rowCount()==0) {
      return $result;
    }
    $request_types = $stmt->fetchColumn();
    $stmt = null;

    if (empty($request_types)) {
      return $result;
    }

    $query = "SELECT url, name FROM requests_additional WHERE id IN (" . $request_types . ") AND id != 5 AND languages_id = ?";
    $stmt = DB_PDO::getInst()->prepare($query);
    $stmt->execute(array($this->sessParams['langId']));

    $requests = $stmt->fetchAll(2);

    foreach ($requests AS &$el) {
      $el['url'] = str_replace(array('%EID%', '%SITE%', '%DATEFROM%', '%DATETO%', '%CITY%'), array($exhibitionId, EPUtils::$siteName, $event['date_from'], $event['date_to'], str_replace(' ', '_', $event['city_name'])), $el['url']);
    }

    $result['data'] = $requests;

    return $result;
  }

  /**
  * Подписать пользователя с электронным адресом $email.
  * В случае успеха возвращает 1
  * EMail проходит проверку валидности. Если проверка не пройдена, функция возвращает -1
  * Если такой EMail уже присутствует в базе, в этом случае (при повторном вызове), происходит отписка пользователя от рассылки и функция возвращает 2
  *
  * @param String $email
  * @return Integer
  */
  private function subscribeNewsletter($email) {

    if (Optimized_EPUtils::validateEmail($email) === false) {
      //Мыло введено неверно, выходим.
      return -1;
    }

    $query = "SELECT email FROM subscribers WHERE type='news' AND email=? LIMIT 1";

    try {
      $stmt = DB_PDO::getInst()->prepare($query);
      $stmt->execute(array($email));

      if ($stmt->rowCount() > 0) {
        //Отписываем пользователя
        $query = "DELETE FROM subscribers WHERE type='news' AND email=? LIMIT 1";
        DB_PDO::getInst()->prepare($query)->execute(array($email));
        $result = 2;
      } else {
        $stmt = null;
        //Подписываем пользователя
        //$lang_id = EPUtils::validateLangCode($this->sessParams['lang']);
        $query = "INSERT INTO subscribers SET type='news', email=:email, sites_id=:site, date_last_send=NOW(), languages_id=:lang";

        $stmt = DB_PDO::getInst()->prepare($query);
        $stmt->execute(array(':email' => $email, ':site' => $this->sessParams['siteID'], ':lang' => $this->sessParams['langId']));

        $result = 1;

        $sid = DB_PDO::getInst()->lastInsertId();

        //Отправляем мыло с подтверждением подписки
        require_once(PATH_WS_CLASSES . "/iMail/Confirmation/Subscription.php");
        $email = new iMail_Confirmation_Subscription();
        $email->send($sid);
      }
    } catch (Exception $e) {
      $this->error = true;
      $result['data'] = array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }

    return $result;
  }

  private function subscribeEventStartNewsletter($event_id, $email) {
    return $this->_subscribeNewsletter($event_id, "e_start", $email);
  }

  private function subscribeEventChangeNewsletter($event_id, $email) {
    return $this->_subscribeNewsletter($event_id, "e_change", $email);
  }

  private function subscribeCompanyChangeNewsletter($company_id, $email) {
    return $this->_subscribeNewsletter($company_id, "c_change", $email);
  }

  /**
  * Функция подписки пользователя на изменения на карточке выставки или компании
  * Первым параметром передается id события или компании, на которое производится подписка
  * Вторым параметром - тип подписки, может принимать значения:
  * e_start - сообщать о начале выставки
  * e_change - сообщать об изменениях на карточке выставки
  * c_change - сообщать об изменениях на карточке компании
  * Третим - адрес пользователя. Если адрес уже присутствует в базе,
  * пользователь отписывается отписывается от рассылки
  * Функция позвращает:
  * 1 в случае успеха
  *
  * @param int $parent
  * @param string $type
  * @param string $email
  * @return int
  */
  private function _subscribeNewsletter($parent, $type, $email) {

    if (Optimized_EPUtils::validateEmail($email) === false ||
      !in_array($type, array('e_start', 'e_change', 'c_change'))) {
      //Мыло введено неверно, выходим.
      return -1;
    }

    $query = "SELECT id FROM subscribers WHERE type=:type AND email=:email AND parent=:events_id LIMIT 1";
    $stmt = DB_PDO::getInst()->prepare($query);

    $stmt->execute(array(":type" => $type, ':email' => $email, ':events_id' => $parent));

    if ($stmt->rowCount() == 0) {
      $stmt = null;

      $query = "INSERT INTO subscribers SET type=:type, email=:email, parent=:events_id, languages_id=:lang, sites_id=:site, date_last_send=NOW()";
      $stmt = DB_PDO::getInst()->prepare($query);

      $stmt->execute(array(":type" => $type, ':email' => $email, ':events_id' => $parent, ":lang" => $this->sessParams['langId'], ':site' => $this->sessParams['siteID']));

      require_once(PATH_CLASSES . "/iMail/Confirmation/Subscription.php");
      $email = new iMail_Confirmation_Subscription();

      $subscriberId = DB_PDO::getInst()->lastInsertId();

      switch ($type) {
        case "e_start":
        case "e_change":
        case "c_change":
          $email->send($subscriberId);
          break;
      }

      $result = 1;
    } else {
      $id = $stmt->fetchColumn();
      $stmt = null;

      $query = "DELETE FROM subscribers WHERE id=:id";
      $stmt = DB_PDO::getInst()->prepare($query);

      $stmt->execute(array(":id" => $id));

      $result = 2;
    }

    return $result;
  }

  /**
  * Функция предназначена для сохранения таргетинга для баннерки.
  * Эту функциональность пришлось вынести сюда из других функций, поскольку они не всегда вызываются из-за кеширования
  *
  * @param string $fname
  * @param array $params
  */
  private function bannersSaveTargeting($fname, $params) {

    switch ($fname) {
      case "getExhibitionsList":
        $params = array_slice($params, 2, 1);
        $params = current($params);
        if (isset($params['countryId'])) {
          $this->sessParams['countryId'] = $params['countryId'];
        }
        if (isset($params['categoryId'])) {
          $this->sessParams['categoryId'] = $params['categoryId'];
        }
        break;
      case "getExhibition":
        $query = 'SELECT categoryId, countryId FROM ExpoPromoter_MViews.events_' . $this->sessParams['lang'] . ' WHERE id = ' . intval(current($params));

        $res = DB_PDO::fetchAll($query);

        if (!empty($res)) {
          $this->sessParams['countryId'] = $res[0]['countryId'];
          $this->sessParams['categoryId'] = $res[0]['categoryId'];
        }

        break;
    }

    if (is_null($this->sessParams['module'])) {
      $this->sessParams['module'] = EPUtils::setCalledModule($fname);
    }

  }

  /**
  * Возвращает статистику по количеству активных записей в БД
  * events - События
  * news - Новости
  * expocenters - Выставочные центры
  * service_companies - сервисные компании
  * ads_participant - участники
  * ads_tour - объявления тур-компаний
  * ads_ad - объявления сервисных компаний
  * companies - база компаний-участников выставок
  *
  * @return array
  */
  private function getDatabaseStat() {
    $query = "SELECT
      (SELECT COUNT(*) FROM events WHERE active=1) AS events,
      (SELECT COUNT(*) FROM news_data WHERE active=1 AND languages_id=1) AS news,
      (SELECT COUNT(*) FROM expocenters WHERE active=1) AS expocenters,
      (SELECT COUNT(*) FROM service_companies WHERE active=1) AS service_companies,
      (SELECT COUNT(*) FROM events_ads WHERE active=1 AND type='participant') AS ads_participant,
      (SELECT COUNT(*) FROM events_ads WHERE active=1 AND type='tour') AS ads_tour,
      (SELECT COUNT(*) FROM events_ads WHERE active=1 AND type='ad') AS ads_ad,
      (SELECT COUNT(*) FROM companies_active WHERE active=1 AND languages_id='" . $this->sessParams['langId'] . "') AS companies,
      (SELECT COUNT(*) FROM companies_services_active WHERE active=1 AND languages_id='" . $this->sessParams['langId'] . "') AS companies_services,
      (SELECT COUNT(*) FROM companies_news_active WHERE active=1 AND languages_id='" . $this->sessParams['langId'] . "') AS companies_news,
      (SELECT COUNT(*) FROM social_organizations WHERE active=1) AS social_organizations,
      (SELECT COUNT(*) FROM organizers WHERE active=1) AS organizers,
      (SELECT COUNT(*) FROM sites WHERE active=1 AND partners_id IS NOT NULL) AS web_partners
    ";

    $res = DB_PDO::fetchAll($query);

    return $res[0];
  }

  /**
  * Возвращает последние добавленные записи в базы
  * В первом параметре передается массив, содержащий в качестве ключей данные,
  * которые нужно вернуть, а в качестве значения - количество данных
  * ads_participant, ads_tour, service_companies, service_tours, expocenters, events, participant_news
  *
  * @param array $params
  * @return array
  */
  private function getLastAddedEntries(Array $params) {
    $result['reqParams'] = array('params' => $params);

    if (isset($params['ads_participant'])) {
      $result['ads_participant'] = DB_PDO::fetchAll("SELECT ea.id, ea.events_id, ead.name,  bd.name AS brand_name
      FROM events_ads AS ea
      JOIN events_ads_data AS ead ON (ea.id=ead.id)
      JOIN events AS e ON (ea.events_id=e.id)
      JOIN brands_data AS bd ON (e.brands_id=bd.id)
      WHERE bd.languages_id=" . $this->sessParams['langId'] . " AND ea.active=1 AND ea.type='participant'
      ORDER BY ea.id DESC LIMIT " . intval($params['ads_participant']));
    }

    if (isset($params['ads_tour'])) {
      $result['ads_tour'] = DB_PDO::fetchAll("SELECT ea.id, ea.events_id, ead.name, bd.name AS brand_name
      FROM events_ads AS ea
      JOIN events_ads_data AS ead ON (ea.id=ead.id)
      JOIN events AS e ON (ea.events_id=e.id)
      JOIN brands_data AS bd ON (e.brands_id=bd.id)
      WHERE bd.languages_id=" . $this->sessParams['langId'] . " AND ea.active=1 AND ea.type='tour'
      ORDER BY ea.id DESC LIMIT " . intval($params['ads_tour']));
    }

    if (isset($params['participant_news'])) {
      $result['participant_news'] = DB_PDO::fetchAll("SELECT np.id, np.events_id, npd.title, bd.name AS brand_name
      FROM news_participants AS np
      JOIN news_participants_data AS npd ON (np.id=npd.id)
      JOIN events AS e ON (e.id=np.events_id)
      JOIN brands_data AS bd ON (e.brands_id=bd.id)
      WHERE np.active=1 AND bd.languages_id=1
      ORDER BY np.id DESC LIMIT " . intval($params['participant_news']));
    }

    if (isset($params['service_companies'])) {
      $result['service_companies'] = DB_PDO::fetchAll("SELECT sc.id, scd.name
      FROM service_companies AS sc
      JOIN service_companies_data AS scd ON ( sc.id = scd.id )
      WHERE scd.languages_id=" . $this->sessParams['langId'] . " AND sc.active=1
      ORDER BY sc.id DESC  LIMIT " . intval($params['service_companies']));
    }

    if (isset($params['service_tours'])) {
      $result['service_tours'] = DB_PDO::fetchAll("SELECT sc.id, scd.name
      FROM service_companies AS sc
      JOIN service_companies_data AS scd ON ( sc.id = scd.id )
      WHERE scd.languages_id=" . $this->sessParams['langId'] . " AND sc.active=1 AND sc.service_companies_categories_id=12172
      ORDER BY sc.id DESC  LIMIT " . intval($params['service_tours']));
    }

    if (isset($params['expocenters'])) {
      $result['expocenters'] = DB_PDO::fetchAll("SELECT ec.id, ecd.name
      FROM expocenters AS ec JOIN expocenters_data AS ecd ON ( ec.id = ecd.id )
      WHERE ecd.languages_id=" . $this->sessParams['langId'] . " AND ec.active=1
      ORDER BY ec.id DESC LIMIT " . intval($params['expocenters']));
    }

    if (isset($params['events'])) {
      $result['events'] = DB_PDO::fetchAll("SELECT e.id, ed.name
      FROM events AS ec JOIN events_data AS ed ON ( e.id = ed.id )
      WHERE ed.languages_id=" . $this->sessParams['langId'] . " AND e.active=1
      ORDER BY e.id DESC LIMIT " . intval($params['events']));
    }

    if (isset($params['companies_pop'])) {
      $result['companies_pop'] = DB_PDO::fetchAll("SELECT c.id, cd.name, lcd.name AS country_name, lcntd.name AS city_name
      FROM ExpoPromoter_MViews.companies_" . $this->sessParams['lang'] . " AS c
      JOIN companies_data AS cd ON (c.id=cd.id)
      JOIN location_countries_data AS lcd ON (c.countries_id=lcd.id)
      JOIN location_cities_data AS lcntd ON (c.cities_id=lcntd.id)
      WHERE c.views>200 AND lcd.languages_id=cd.languages_id AND lcntd.languages_id=cd.languages_id AND cd.languages_id='" . $this->sessParams['langId'] . //c.views>200 для ограничения количества данных для сортировки
      "' ORDER BY c.views DESC LIMIT " . intval($params['companies_pop']));
    }

    return $result;
  }

  //Дополнительные блоки

  protected function _getExhibAdsList($results_num, $page, $event_id, $type, $params) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'page' => $page, 'event_id' => $event_id, 'params' => $params);

    if (is_null($event_id)) {

      /*$query = "SELECT ea.id, ea.events_id, ves.period_date_from, ves.period_date_to, bd.name AS brand_name, ead.name, ead.description_short, IF( ea.date_pay_firstplace IS NULL , ea.date_pay, ea.date_pay_firstplace ) AS date_sort*/

      $query = "SELECT ea.id, ea.events_id, ves.period_date_from, ves.period_date_to, bd.name AS brand_name, ead.name, ves.regionId, ves.countryId, ves.cityId, lcd.name AS cityName, lcntd.name AS countryName, IF( ea.date_pay_firstplace IS NULL , ea.date_pay, ea.date_pay_firstplace ) AS date_sort, scd.image_logo AS sc_logo, scd.id AS service_company_id
        FROM events_ads AS ea
        JOIN events_ads_data AS ead ON ( ea.id = ead.id )
        JOIN ExpoPromoter_MViews.events_" . $this->sessParams['lang'] . " AS ves ON ( ea.events_id = ves.id ) " .
        (!empty($params['subCategoryId']) ? ' JOIN brands_to_subcategories AS b_to_sc ON (b_to_sc.brands_id=ves.brandId)':'') .
        (!empty($params['categoryId']) ? ' JOIN brands_to_categories AS b_to_c ON (b_to_c.brands_id=ves.brandId)':'') .
        " JOIN brands_data AS bd ON ( ves.brandId = bd.id )
        JOIN location_cities_data AS lcd ON (ves.cityId=lcd.id)
        JOIN location_countries_data AS lcntd ON (ves.countryId=lcntd.id)
        LEFT JOIN service_companies_data AS scd ON (ea.service_companies_id=scd.id AND scd.languages_id='" . $this->sessParams['langId'] . "')
        WHERE ea.active=1 AND ea.type = '" . mysql_escape_string($type) . "'
        AND lcd.languages_id=" . $this->sessParams['langId'] . "
        AND lcntd.languages_id=" . $this->sessParams['langId'] . "
        AND bd.languages_id=" . $this->sessParams['langId'] . " ";

      //AND ead.languages_id=" . $this->sessParams['langId'] . " - убрал

      if (is_array($params) && sizeof($params)>0) {
        $query .= Optimized_EPUtils::createBaseExhibitionWhere($params);
      }

        //$query .= " ORDER BY date_sort DESC";
        $query .= " ORDER BY ea.id DESC";
    } else {
      $query = "SELECT ea.id, ea.events_id, ead.name, ead.description_short, IF(ea.date_pay_firstplace IS NULL, ea.date_pay, ea.date_pay_firstplace) AS date_sort, ep.logo AS ep_logo, ep.id AS participant_id
        FROM events_ads AS ea
        JOIN events_ads_data AS ead ON (ea.id=ead.id)
        LEFT JOIN events_participants AS ep ON (ea.events_participants_id=ep.id)
        WHERE ea.active=1
        AND ea.events_id='" . intval($event_id) . "'
        AND ea.type='" . mysql_escape_string($type) . "'
        ORDER BY ep.logo DESC, ea.id DESC";

        //AND ead.languages_id=" . $this->sessParams['langId'] . " - убрал
    }

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $result['data'] = DB_PDO::fetchAll($split['query']);
      if (!is_null($event_id)) {
        Optimized_EPUtils::createLogoUrl($result['data'], 'event_participants');
      } else {
        Optimized_EPUtils::createLogoUrl($result['data'], 'service_companies');
      }
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список участников выставки, id которой передан третим параметром
  * Если выставка не указана, возвращается полный список
  *
  * @param int $results_num
  * @param int $page
  * @param int $event_id
  * @return array
  */
  public function getExhibitionParticipantsList($results_num, $page, $event_id = null, $params = array()) {
    return $this->_getExhibAdsList($results_num, $page, $event_id, 'participant', $params);
  }

  /**
  * Возвращает список туров на выставку, id которой передан третим параметром
  *
  * @param int $results_num
  * @param int $page
  * @param int $event_id
  * @return array
  */
  protected function getExhibitionToursList($results_num, $page, $event_id = null, $params = array()) {
    return $this->_getExhibAdsList($results_num, $page, $event_id, 'tour', $params);
  }

  /**
  * Возвращает список обявлений для выставки, id которой передан третим параметром
  *
  * @param int $results_num
  * @param int $page
  * @param int $event_id
  * @return array
  */
  protected function getExhibitionAdsList($results_num, $page, $event_id = null, $params = array()) {
    return $this->_getExhibAdsList($results_num, $page, $event_id, 'ad', $params);
  }

  /**
  * Возвращает список участников прошлой выставки
  *
  * @param int $results_num
  * @param int $page
  * @param int $event_id
  * @return array
  */
  protected function getPreviousExhibitionParticipantsList($results_num, $page, $event_id) {
    $event_id = Optimized_EPUtils::getPreviousEventId($event_id);

    return $this->_getExhibAdsList($results_num, $page, $event_id, 'participant', array());
  }

  /**
  * Возвращает список постоянных участников выставок
  * В качестве ограничивающих параметров можно указать:
  * city_id - город участника
  * category_id - категория брендов
  *
  * @param int $results_num
  * @param int $page
  * @param array $params
  * @return array
  */
  private function getPermanentExhibitionParticipantsList($results_num, $page, Array $params = array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'page' => $page, 'params' => $params);

    $query = "SELECT ep.id, ep.logo, epd.name, epd.description AS description_short
      FROM events_participants AS ep
      JOIN events_participants_data AS epd ON (ep.id=epd.id)
      WHERE epd.languages_id='" . $this->sessParams['langId'] . "' AND ep.active=1 ";

    if (isset($params['city_id'])) {
      $query .= "AND ep.cities_id='" . intval($params['city_id']) . "' ";
    }

    if (isset($params['category_id'])) {
      $query .= "AND ep.brands_categories_id='" . intval($params['category_id']) . "' ";
    }

    $query .= " ORDER BY ep.id DESC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $result['data'] = DB_PDO::fetchAll($split['query']);

      foreach ($result['data'] as &$el) {
        $el['description_short'] = mb_substr(strip_tags($el['description_short']), 0, 160, "UTF-8");
        if ($el['logo']==1) {
          $el['logo'] = "http://admin.expopromoter.com/data/images/event_participants/logo/" . $el['id'] . ".jpg";
        } else {
          unset($el['logo']);
        }
      }

      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список новостей участников выставки. Id выставки указывается третим параметром
  *
  * @param int $results_num
  * @param int $page
  * @param int $event_id
  * @return array
  */
  private function getExhibitionParticipantNewsList($results_num, $page, $event_id = null, $params = array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'page' => $page, 'event_id' => $event_id, 'params' => $params);

    if (!is_null($event_id)) {
      $query = "SELECT np.id, npd.title, npd.content AS description_short, ep.logo, np.events_participants_id
      FROM news_participants AS np
      JOIN news_participants_data AS npd ON (np.id=npd.id)
      LEFT JOIN events_participants AS ep ON (np.events_participants_id=ep.id)
      WHERE np.active=1 AND np.events_id = '" . intval($event_id) . "'
      ORDER BY np.id DESC";
    } else {
      $query = "SELECT ea.id, ea.events_id, ves.period_date_from, ves.period_date_to, bd.name AS brand_name, ead.title, ves.regionId, ves.countryId, ves.cityId, lcd.name AS cityName, lcntd.name AS countryName, ep.logo, ead.content AS description_short, ea.events_participants_id
        FROM news_participants AS ea
        JOIN news_participants_data AS ead ON ( ea.id = ead.id )
        JOIN ExpoPromoter_MViews.events_" . $this->sessParams['lang'] . " AS ves ON ( ea.events_id = ves.id ) " .
        (!empty($params['subCategoryId']) ? ' JOIN brands_to_subcategories AS b_to_sc ON (b_to_sc.brands_id=ves.brandId)':'') .
        (!empty($params['categoryId']) ? ' JOIN brands_to_categories AS b_to_c ON (b_to_c.brands_id=ves.brandId)':'') .
        " JOIN brands_data AS bd ON ( ves.brandId = bd.id )
        JOIN location_cities_data AS lcd ON (ves.cityId=lcd.id)
        JOIN location_countries_data AS lcntd ON (ves.countryId=lcntd.id)
        LEFT JOIN events_participants AS ep ON (ea.events_participants_id=ep.id)
        WHERE ea.active=1
        AND lcd.languages_id=" . $this->sessParams['langId'] . "
        AND lcntd.languages_id=" . $this->sessParams['langId'] . "
        AND bd.languages_id=" . $this->sessParams['langId'] . " ";

      if (is_array($params) && sizeof($params)>0) {
        $query .= Optimized_EPUtils::createBaseExhibitionWhere($params);
      }

        $query .= " ORDER BY ea.id DESC";

    }

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $result['data'] = DB_PDO::fetchAll($split['query']);

      foreach ($result['data'] as &$el) {
        if ($el['logo']==1) {
          $el['description_short'] = mb_substr(strip_tags($el['description_short']), 0, 40, "UTF-8");
          $el['logo'] = "http://admin.expopromoter.com/data/images/event_participants/logo/" . $el['events_participants_id'] . ".jpg";
        } else {
          unset($el['logo']);
        }
      }

      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  private function getExhibitionParticipantNews($id) {
$result = array();
    $result['reqParams'] = array('id' => $id);

    $query = "SELECT np.id, npd.title, npd.content, ep.logo, np.events_participants_id
    FROM news_participants AS np
    JOIN news_participants_data AS npd ON (np.id=npd.id)
    LEFT JOIN events_participants AS ep ON (np.events_participants_id=ep.id)
    WHERE np.active=1 AND np.id = '" . intval($id) . "'";

    try {
      $result['data'] = DB_PDO::fetchAll($query);

      if (sizeof($result['data']) == 0) {
        return $result;
      }

      $result['data'] = $result['data'][0];

      if ($result['data']['logo']==1) {
        $result['data']['logo'] = "http://admin.expopromoter.com/data/images/event_participants/logo/" . $result['data']['events_participants_id'] . ".jpg";
      } else {
        unset($result['data']['logo']);
      }

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает полную информацию о постоянном участнике выставки
  *
  * @param int $id
  * @return array
  */
  private function getPermanentExhibitionParticipant($id) {
    $result = array();
    $result['reqParams'] = array('id' => $id);

    $query = "SELECT ep.id, ep.brands_categories_id AS category_id, ep.logo, epd.name, epd.description, epd.address, epd.postcode, epd.phone, epd.fax, epd.email, epd.web_address, lc.id AS city_id, lcntd.id AS country_id, lcd.name AS city_name, lcntd.name AS country_name
      FROM events_participants AS ep
      JOIN events_participants_data AS epd ON (ep.id=epd.id)
      JOIN location_cities AS lc ON (ep.cities_id=lc.id)
      JOIN location_cities_data AS lcd ON (lc.id=lcd.id)
      JOIN location_countries_data AS lcntd ON (lc.countries_id=lcntd.id)
      WHERE epd.languages_id='" . $this->sessParams['langId'] . "' AND ep.active=1 AND ep.id='" . intval($id) . "'";

    try {
      $data = DB_PDO::fetchAll($query);

      if ($data[0]['logo']==1) {
        $data[0]['logo'] = "http://admin.expopromoter.com/data/images/event_participants/logo/" . $data[0]['id'] . ".jpg";
      } else {
        unset($data[0]['logo']);
      }

      $result['data'] = $data[0];
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает полную информацию об объявлении, id которого передано первым параметром
  *
  * @param int $id
  * @return array
  */
  private function _getExhibAd($id) {
    $result = array();
    $result['reqParams'] = array('id' => $id);

    /*Пока выключаем чтобы отображалось полное описание, включим после того как будет оплата
    $query = "SELECT ea.id, ea.events_id, ead.name, ead.description_short, IF(ea.date_pay_extra IS NULL ,  '', ead.description)  AS description
    FROM events_ads AS ea JOIN events_ads_data AS ead ON (ea.id=ead.id)
    WHERE ea.active=1 AND ea.id='" . intval($id) . "' AND ead.languages_id=" . $this->sessParams['langId'];
    */
    $query = "SELECT ea.id, ea.events_id, ead.name, ead.description_short, ead.description, ep.id AS participant_id, ep.logo AS ep_logo, ep.active AS participant_active, scd.id AS service_company_id, scd.name AS service_company_name
    FROM events_ads AS ea
    JOIN events_ads_data AS ead ON (ea.id=ead.id)
    LEFT JOIN events_participants AS ep ON (ea.events_participants_id=ep.id)
    LEFT JOIN service_companies AS sc ON (ea.service_companies_id=sc.id AND sc.active=1)
    LEFT JOIN service_companies_data AS scd ON (sc.id=scd.id AND scd.languages_id='" . $this->sessParams['langId'] . "')
    WHERE ea.active=1 AND ea.id='" . intval($id) . "' LIMIT 1";

    //AND ead.languages_id=" . $this->sessParams['langId'] - убрал

    try {
      $data = DB_PDO::fetchAll($query);
      if (empty($data)) {
        return $result;
      }

      Optimized_EPUtils::createLogoUrl($data, 'event_participants');
      $result['data'] = $data[0];
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает полную информацию об участнике выставки
  *
  * @param int $id
  * @return array
  */
  private function getExhibitionParticipant($id) {
    return $this->_getExhibAd($id);
  }

  /**
  * Возвращает полную информацию о туре на выставку
  *
  * @param int $id
  * @return array
  */
  private function getExhibitionTour($id) {
    return $this->_getExhibAd($id);
  }

  /**
  * Возвращает полную информацию об объявлении
  *
  * @param int $id
  * @return array
  */
  private function getExhibitionAd($id) {
    return $this->_getExhibAd($id);
  }

  /**
  * Отправляем сообщение участнику выставки
  * В случае успеха функция возвращает 1, в случае ошибки введенных данных -1
  *
  * @param int $id
  * @param string $email
  * @param string $message
  * @return int
  */
  private function addExhibitionAdMessage($id, $email, $message) {
    if (!Optimized_EPUtils::validateEmail($email)) {
      return -1;
    }

    if (Optimized_EPUtils::viagraDetector($message) == true) {
      return -1;
    }

    $query = "INSERT INTO events_ads_messages (events_ads_id, email, message) VALUES (:aid, :email, :mess)";
    $stmt = DB_PDO::getInst()->prepare($query);

    try {
      $stmt->execute(array(":aid" => $id, ":email" => $email, ":mess" => $message));
    } catch (Exception $e) {
      return -1;
    }

    return 1;
  }

  /**
  * Отправляем сообщение участнику, разместившему новость
  * В случае успеха функция возвращает 1, в случае ошибки введенных данных -1
  *
  * @param int $id
  * @param string $email
  * @param string $message
  * @return int
  */
  private function addParticipantNewsMessage($id, $email, $message) {
    if (!Optimized_EPUtils::validateEmail($email)) {
      return -1;
    }

    if (Optimized_EPUtils::viagraDetector($message) == true) {
      return -1;
    }

    $query = "INSERT INTO events_ads_messages (news_participants_id, email, message) VALUES (:aid, :email, :mess)";
    $stmt = DB_PDO::getInst()->prepare($query);

    try {
      $stmt->execute(array(":aid" => $id, ":email" => $email, ":mess" => $message));
    } catch (Exception $e) {
      return -1;
    }

    return 1;
  }


  //Комментарии

  /**
  * Возвращает список комментариев. Третим параметром передается массив ограничивающих параметров:
  * expocenters_id - комментарии экспоцентра
  * service_companies_id - сервисной компании
  * news_id - новости
  * articles_id - статьи
  *
  * @param int $results_num
  * @param int $page
  * @param array $params
  * @return array
  */
  private function getCommentsList($results_num, $page, Array $params) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'page' => $page, 'params' => $params);

    $query = "SELECT cd.id, cd.name, cd.message, c.date_created AS date FROM comments AS c JOIN comments_data AS cd ON (c.id=cd.id) WHERE cd.languages_id=" . $this->sessParams['langId'];

    if (isset($params['expocenters_id'])) {
      $query .= " AND c.expocenters_id=" . intval($params['expocenters_id']);
    }
    if (isset($params['service_companies_id'])) {
      $query .= " AND c.service_companies_id=" . intval($params['service_companies_id']);
    }
    if (isset($params['news_id'])) {
      $query .= " AND c.news_id=" . intval($params['news_id']);
    }
    if (isset($params['articles_id'])) {
      $query .= " AND c.articles_id=" . intval($params['articles_id']);
    }

    $query .= " ORDER BY c.id DESC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $result['data'] = DB_PDO::fetchAll($split['query']);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Добавляет комментарий к записи, тип которой устанавливается первым параметром, а id вторым:
  * expocenter, service_company, news, article
  * В случае успеха возвращает true, иначе false
  *
  * @param string $type
  * @param int $id
  * @param string $title
  * @param string $email
  * @param string $message
  * @return boolean
  */
  private function addComment($type, $id, $name, $email, $message) {

    //Защита от виагровцев
    if (Optimized_EPUtils::viagraDetector($message) == true) {
      return false;
    }

    $query = "INSERT INTO comments (";

    switch ($type) {
      case "expocenter":
        $query .= "expocenters_id";
        break;
      case "service_company":
        $query .= "service_companies_id";
        break;
      case "news":
        $query .= "news_id";
        break;
      case "article":
        $query .= "articles_id";
        break;
      default:
        return false;
    }

    $query .= ") VALUES (" . intval($id) . ")";

    DB_PDO::getInst()->beginTransaction();
    $res = DB_PDO::getInst()->exec($query);

    if (!$res) {
      DB_PDO::getInst()->rollBack();
      return false;
    }

    $id = DB_PDO::getInst()->lastInsertId();

    $query = "INSERT INTO comments_data (id, languages_id, email, name, message) VALUES (:id, :lang, :email, :name, :mess)";

    $stmt = DB_PDO::getInst()->prepare($query);

    if ($stmt===false) {
      DB_PDO::getInst()->rollBack();
      return false;
    }

    $res = $stmt->execute(array(':id' => $id, ':lang' => $this->sessParams['langId'], ':email' => $email, ':name' => $name, ':mess' => strip_tags($message)));

    if (!$res) {
      DB_PDO::getInst()->rollBack();
      return false;
    }

    DB_PDO::getInst()->commit();
    return true;
  }

  //Компании

  /**
  * Возвращает список компаний-участников выставок
  * В качестве параметров принимает:
  * cities_id - фильтр по городу
  * countries_id - фильтр по стране
  * events_id - фильтр по событию
  * categories_id - фильтр по категории бренда
  * ids - массив, содержащий id компаний для выборки
  *
  * @param int $results_num
  * @param int $page
  * @param array $params
  * @return array
  */
  protected function getCompaniesList($results_num, $page, $params = array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'page' => $page, 'params' => $params);

    Optimized_EPUtils::utilsInit('companies', $params, $this->sessParams['lang']);
    $subquery = Optimized_EPUtils::createBaseQuery($params);

    $subquery .= " ORDER BY sqc.date_modify DESC";

    $split = DB_PDO::splitPageResults($subquery, $results_num, '*', $page);

    $result['pagesTotal'] = $split['pages'];
    $result['resultsTotal'] = $split['totalResults'];
    $result['reqParams']['page'] = $split['page'];

    $query =
    "SELECT
      c.id,
      c.cities_id AS cityId,
      c.countries_id AS countryId,
      c.regions_id AS regionId,
      c.postcode,
      c.email,
      c.phone AS phoneNumber,
      c.web_address AS webAddress,
      c.logo AS company_logo,
      c.videos,
      c.news,
      c.services,
      scc.view_cnt,
      cd.name,
      lcd.name AS cityName,
      lcntd.name AS countryName,
      cd.address,
      cd.description AS description_short
    FROM (" . $split['query'] . ") AS c
      JOIN companies_data AS cd ON (c.id=cd.id)
      LEFT JOIN statistic.companies_counter AS scc ON (scc.id = c.id)
      LEFT JOIN location_cities_data AS lcd ON (c.cities_id = lcd.id AND cd.languages_id=lcd.languages_id)
      LEFT JOIN location_countries_data AS lcntd ON (lcntd.id = c.countries_id AND cd.languages_id=lcntd.languages_id)
    WHERE cd.languages_id = '" . $this->sessParams['langId'] . "'
    ";

    try {
      $result['data'] = DB_PDO::fetchAll($query);
      if (!isset($params['all'])) Optimized_EPUtils::createLogoUrl($result['data'], "companies_small");

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }



  protected function ajaxCompaniesList($str) {
    $query =
    "SELECT
      c.id,
      c.cities_id AS cityId,
      lc.countries_id AS countryId,
      c.postcode,
      c.email,
      c.phone AS phoneNumber,
      c.web_address AS webAddress,
      c.logo AS company_logo,
      cd.name,
      cd.description,
      cd.address,
      lcd.name AS cityName
    FROM ExpoPromoter_Opt.companies AS c
      JOIN ExpoPromoter_Opt.companies_data AS cd ON cd.id = c.id AND cd.languages_id = '" . $this->sessParams['langId'] . "'
      JOIN ExpoPromoter_Opt.location_cities_data AS lcd ON lcd.id = c.cities_id AND lcd.languages_id = '" . $this->sessParams['langId'] . "'
      JOIN ExpoPromoter_Opt.location_cities AS lc ON lc.id = c.cities_id
    WHERE cd.name LIKE '" . mysql_escape_string($str). "%'
    ORDER BY cd.name ASC
    ";

    try {
      $result = DB_PDO::fetchAll($query);
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }


  /**
  * Возвращает полную информацию о компании, id которой передан первым параметром
  *
  * @param int $id
  * @return array
  */
  private function getCompany($id) {
    $result = array();
    $result['reqParams'] = array('id' => $id);

    $query = "SELECT c.id, c.cities_id, c.countries_id, cc.email, cc.web_address, cc.phone, cc.fax, cc.postcode,
    c.logo AS company_logo, cd.name, cd.description, cd.address, lcd.name AS city_name, lcntd.name AS country_name
    FROM ExpoPromoter_MViews.companies_" . $this->sessParams['lang'] . " AS c
    JOIN companies AS cc ON (c.id=cc.id)
    JOIN companies_data AS cd ON (c.id=cd.id)
    LEFT JOIN location_cities_data AS lcd ON (c.cities_id = lcd.id AND cd.languages_id=lcd.languages_id)
    LEFT JOIN location_countries_data AS lcntd ON (lcntd.id = c.countries_id AND cd.languages_id=lcntd.languages_id)
    WHERE cd.languages_id = '" . $this->sessParams['langId'] . "' AND c.id = '" . intval($id) . "'";

    $query_events = "SELECT e.id, e.date_from, e.date_to, bd.name, e.cities_id, lc.countries_id, lcd.name AS city_name, lcntd.name AS country_name
    FROM companies_to_events AS c2e
    JOIN events AS e ON (c2e.events_id = e.id)
    JOIN brands_data AS bd ON (e.brands_id = bd.id)
    LEFT JOIN location_cities AS lc ON (e.cities_id = lc.id)
    LEFT JOIN location_cities_data AS lcd ON (lc.id = lcd.id AND lcd.languages_id=bd.languages_id)
    LEFT JOIN location_countries_data AS lcntd ON (lc.countries_id = lcntd.id AND lcntd.languages_id=bd.languages_id)
    WHERE e.active=1 AND bd.languages_id = '" . $this->sessParams['langId'] . "'
    AND c2e.companies_id = '" . intval($id) . "'
    ORDER BY e.date_from DESC";

    $query_count = "SELECT c.news, c.services, c.employers,
    (SELECT view_cnt FROM statistic.companies_counter AS cc WHERE cc.id=c.id) AS views
    FROM ExpoPromoter_MViews.companies_" . $this->sessParams['lang'] . " AS c WHERE c.id = '" . intval($id) . "'";

    try {
      $data = DB_PDO::fetchAll($query);
      Optimized_EPUtils::createLogoUrl($data, 'companies');
      $result['data'] = $data[0];
      $result['events'] = DB_PDO::fetchAll($query_events);
      $result['stat'] = DB_PDO::fetchAll($query_count);
      $result['stat'] = $result['stat'][0];

      //Обновляем счетчик посещений карточки компании
    DB_PDO::getInst()->exec("UPDATE statistic.companies_counter SET view_cnt=view_cnt+1 WHERE id = " . intval($id));

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список новостей компаний
  * Принимает параметры фильтра:
  * companies_id - новости только выбранной компании
  *
  * @param int $results_num
  * @param int $page
  * @param array $params
  * @return array
  */
  private function getCompaniesNewsList($results_num, $page, $params = array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'page' => $page, 'params' => $params);

    $query = "SELECT cn.id, cn.companies_id, cn.logo AS company_news_logo, cn.date_public, cnd.name, cd.name AS company_name, cnd.content AS preambula
    FROM companies_news AS cn
    JOIN ExpoPromoter_MViews.companies_" . $this->sessParams['lang'] . " AS c ON (cn.companies_id = c.id)
    JOIN companies_news_data AS cnd ON (cn.id = cnd.id)
    JOIN companies_data AS cd ON (cn.companies_id = cd.id)
    JOIN companies_news_active AS cna ON (cn.id=cna.id AND cna.languages_id=cnd.languages_id) " .
    (isset($params['brands_categories_id']) ? " JOIN companies_to_brands_categories AS c2bc ON (c.id = c2bc.companies_id AND c2bc.brands_categories_id = '" . intval($params['brands_categories_id']) . "')":"") .
    " WHERE cna.active=1 AND cn.date_public<=CURDATE() AND cd.languages_id=cnd.languages_id AND cnd.languages_id='" . $this->sessParams['langId'] . "'";

    if (isset($params['companies_id'])) {
      $query .= " AND cn.companies_id = '" . intval($params['companies_id']) . "'";
    }

    $query .= " ORDER BY cn.date_public DESC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $result['data'] = DB_PDO::fetchAll($split['query']);

      Optimized_EPUtils::createLogoUrl($result['data'], 'companies_news_small');

      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает новость компании
  *
  * @param int $id
  * @return array
  */
  private function getCompaniesNews($id) {
    $result = array();
    $result['reqParams'] = array('id' => $id);

    $query = "SELECT cn.id, cn.companies_id, cn.date_public, cnd.name, cnd.content, cd.name AS company_name, cn.logo AS company_news_logo
    FROM companies_news AS cn
    JOIN ExpoPromoter_MViews.companies_" . $this->sessParams['lang'] . " AS c ON (cn.companies_id = c.id)
    JOIN companies_news_data AS cnd ON (cn.id = cnd.id)
    JOIN companies_data AS cd ON (cn.companies_id = cd.id)
    JOIN companies_news_active AS cna ON (cn.id=cna.id AND cna.languages_id=cnd.languages_id)
    WHERE cna.active=1 AND cn.date_public<=CURDATE() AND cd.languages_id=cnd.languages_id AND cnd.languages_id='" . $this->sessParams['langId'] . "' AND cn.id='" . intval($id) . "'";

    try {
      $data = DB_PDO::fetchAll($query);
      Optimized_EPUtils::createLogoUrl($data, 'companies_news');
      $result['data'] = $data[0];
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список товаров/услуг компании. Принимает параметры:
  * companies_id - по компании
  * categories_id - по категории товаров/услуг
  * brands_categories_id - по категории брендов
  * tags_id - по тегу
  *
  * @param int $results_num
  * @param int $page
  * @param array $params
  * @return array
  */
  private function getCompaniesServicesList($results_num, $page, $params = array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'page' => $page, 'params' => $params);

    $query = "SELECT cs.id, cs.companies_id, cs.companies_services_cats_id AS categories_id, cs.type, cs.price, cs.photo AS company_serv_logo, csd.name, csd.short, cd.name AS company_name
    FROM companies_services AS cs
    JOIN ExpoPromoter_MViews.companies_" . $this->sessParams['lang'] . " AS c ON (cs.companies_id = c.id)
    JOIN companies_services_data AS csd ON (cs.id = csd.id)
    JOIN companies_data AS cd ON (cs.companies_id = cd.id)
    JOIN companies_services_active AS csa ON (cs.id=csa.id)" .
    (isset($params['brands_categories_id']) ? " JOIN companies_to_brands_categories AS c2bc ON (c.id = c2bc.companies_id AND c2bc.brands_categories_id = '" . intval($params['brands_categories_id']) . "')":"") .
    (isset($params['tags_id']) ? " JOIN ExpoPromoter_index.tags_to_comservices_" . $this->sessParams['lang'] . " AS t2cs ON (cs.id = t2cs.parent_id AND t2cs.index_words_id = '" . intval($params['tags_id']) . "')":"") .
    " WHERE csa.active=1 AND csa.languages_id=csd.languages_id AND cd.languages_id=csd.languages_id AND csd.languages_id='" . $this->sessParams['langId'] . "'";

    if (isset($params['companies_id'])) {
      $query .= " AND cs.companies_id = '" . intval($params['companies_id']) . "'";
    }
    if (isset($params['categories_id'])) {
      $query .= " AND cs.companies_services_cats_id = '" . intval($params['categories_id']) . "'";
    }

    $query .= " ORDER BY cs.id DESC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $result['data'] = DB_PDO::fetchAll($split['query']);
      Optimized_EPUtils::createLogoUrl($result['data'], "companies_services_small");
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает информацию о сервисной компании
  *
  * @param int $id
  * @return array
  */
  private function getCompaniesService($id) {
    $result = array();
    $result['reqParams'] = array('id' => $id);

    $query = "SELECT cs.id, cs.companies_id, cs.companies_services_cats_id AS categories_id, cs.type, cs.price,
    cs.photo AS company_serv_logo, csd.name, csd.content, csd.content, cd.name AS company_name, cscd.name AS category_name
    FROM companies_services AS cs
    JOIN ExpoPromoter_MViews.companies_" . $this->sessParams['lang'] . " AS c ON (cs.companies_id = c.id)
    JOIN companies_services_data AS csd ON (cs.id = csd.id)
    JOIN companies_data AS cd ON (cs.companies_id = cd.id)
    JOIN companies_services_active AS csa ON (cs.id = csa.id AND csa.languages_id = csd.languages_id)
    LEFT JOIN brands_subcategories_data AS cscd ON (cscd.id = cs.brands_subcategories_id AND cscd.languages_id=csd.languages_id) " .
    //LEFT JOIN companies_services_cats_data AS cscd ON (cscd.id = cs.companies_services_cats_id AND cscd.languages_id=csd.languages_id)
    " WHERE csa.active=1 AND cd.languages_id=csd.languages_id AND csd.languages_id='" . $this->sessParams['langId'] . "' AND cs.id='" . intval($id) . "'";

    try {
      $data = DB_PDO::fetchAll($query);
      Optimized_EPUtils::createLogoUrl($data, "companies_services");
      $result['data'] = $data[0];
      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список категорий товаров/услуг компании. Принимает параметры:
  * companies_id - фильтр по компании
  *
  * @param int $results_num
  * @param int $page
  * @param array $params
  * @return array
  */
  private function getCompaniesServicesCatsList($results_num, $page, $params = array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'page' => $page, 'params' => $params);

    $query = "SELECT csc.id, csc.companies_id, cscd.name
    FROM companies_services_cats AS csc
    JOIN ExpoPromoter_MViews.companies_" . $this->sessParams['lang'] . " AS c ON (csc.companies_id = c.id)
    JOIN companies_services_cats_data AS cscd ON (csc.id = cscd.id)
    WHERE csc.active=1 AND cscd.languages_id = '" . $this->sessParams['langId'] . "'";

    if (isset($params['companies_id'])) {
      $query .= " AND csc.companies_id = '" . intval($params['companies_id']) . "'";
    }

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $result['data'] = DB_PDO::fetchAll($split['query']);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает облако тегов
  * В качестве фильтрации используются параметры:
  * brands_categories_id - по категории брендов
  *
  * @param int $results_num
  * @param int $page
  * @param array $params
  * @return array
  */
  private function getCompaniesServicesTags($results_num, $page, $params = array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'page' => $page, 'params' => $params);

    if (!isset($params['brands_categories_id'])) {
      $params['brands_categories_id'] = 0;
    }

    $query = "SELECT t.index_words_id AS id, t.weight, iw.name
      FROM ExpoPromoter_index.tags_comservices_weight_ru AS t
      JOIN ExpoPromoter_index.index_words AS iw ON (t.index_words_id = iw.id)
      WHERE t.brands_categories_id = '" . intval($params['brands_categories_id']) . "'
      ORDER BY t.weight DESC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $result['data'] = DB_PDO::fetchAll($split['query']);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Возвращает список работников компании. Принимает параметры:
  * companies_id - работники только этой компании
  *
  * @param int $results_num
  * @param int $page
  * @param array $params
  * @return array
  */
  private function getCompaniesEmployersList($results_num, $page, $params = array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'page' => $page, 'params' => $params);

    if (isset($params['companies_id'])) {
        $query = "SELECT ce.id, ce.companies_id, ce.email, ce.phone, ced.name, ced.lastname, ced.position, ce.photo AS company_empl_logo, cd.name AS company_name
      FROM companies_employers AS ce
      JOIN ExpoPromoter_MViews.companies_" . $this->sessParams['lang'] . " AS c ON (ce.companies_id = c.id)
      JOIN companies_employers_data AS ced ON (ce.id = ced.id)
      JOIN companies_data AS cd ON (ce.companies_id = cd.id)
      JOIN companies_employers_active AS cea ON (cea.id = ce.id AND cea.languages_id=ced.languages_id) " .
      (isset($params['brands_categories_id']) ? " JOIN companies_to_brands_categories AS c2bc ON (c.id = c2bc.companies_id AND c2bc.brands_categories_id = '" . intval($params['brands_categories_id']) . "')":"") .
      " WHERE cea.active=1 AND cd.languages_id=ced.languages_id AND ced.languages_id='" . $this->sessParams['langId'] . "' AND ce.companies_id = '" . intval($params['companies_id']) . "'";
    } else {
        $query = "SELECT ce.id, ce.companies_id, ce.email, ce.phone, ced.name, ced.lastname, ced.position, ce.photo AS company_empl_logo, c.logo AS company_logo, cd.name AS company_name, cd.description AS description_short
      FROM companies_employers AS ce
      JOIN ExpoPromoter_MViews.companies_" . $this->sessParams['lang'] . " AS c ON (ce.companies_id = c.id)
      JOIN companies_employers_data AS ced ON (ce.id = ced.id)
      JOIN companies_data AS cd ON (ce.companies_id = cd.id)
      JOIN companies_employers_active AS cea ON (cea.id = ce.id AND cea.languages_id=ced.languages_id)" .
      (isset($params['brands_categories_id']) ? " JOIN companies_to_brands_categories AS c2bc ON (c.id = c2bc.companies_id AND c2bc.brands_categories_id = '" . intval($params['brands_categories_id']) . "')":"") .
      " WHERE cea.active=1 AND cd.languages_id=ced.languages_id AND ced.languages_id='" . $this->sessParams['langId'] . "'";
    }

    $query .= " ORDER BY ce.id DESC";

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $result['data'] = DB_PDO::fetchAll($split['query']);
      Optimized_EPUtils::createLogoUrl($result['data'], "companies_employers");

      if (!isset($params['companies_id'])) {
        Optimized_EPUtils::createLogoUrl($result['data'], "companies", 'logo_company');
      }

      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Полнотекстный поиск по базе компаниий.
  * Одновременно производится поиск и по всем товарам компании
  *
  * @param string $query
  * @param int $results_num
  * @param int $page
  * @return array
  */
  private function companiesSearch($query, $results_num, $page) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'page' => $page, 'query' => $query);

    require_once(PATH_CLASSES . "/Fulltext/Companies.php");

    try {
      $searchObj = new Fulltext_Companies($this->sessParams['langId'], $this->sessParams['lang']);
      $result['data'] = $searchObj->search($query, $results_num, $page);
      Optimized_EPUtils::createLogoUrl($result['data'], "companies_small");
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }

    return $result;
  }

  /**
  * Полнотекстный поиск по базе товаров и услуг компаниий.
  *
  * @param string $query
  * @param int $results_num
  * @param int $page
  * @return array
  */
  private function companiesServicesSearch($query, $results_num, $page) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'page' => $page, 'query' => $query);

    require_once(PATH_CLASSES . "/Fulltext/Companies/Services.php");

    try {
      $searchObj = new Fulltext_Companies_Services($this->sessParams['langId'], $this->sessParams['lang']);
      $result['data'] = $searchObj->search($query, $results_num, $page);
      Optimized_EPUtils::createLogoUrl($result['data'], "companies_services_small");
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }

    return $result;
  }

  /**
  * Отправляет сообщение компании, id которой передано первым параметром.
  * Вторым массивом передается сообщение, состоящее из полей:
  * name - имя отправителя
  * email - мыло
  * phone - телефон
  * message - сообщение
  *
  * В случае успеха возвращает 1, иначе 0
  *
  * @param int $id
  * @param array $data
  * @return int
  */
  private function sendCompanyMessage($id, $data) {

    $query = "INSERT INTO companies_messages SET languages_id=:lang, companies_id=:comp_id, name=:name, email=:email, phone=:phone, message=:mess";

    $stmt = DB_PDO::getInst()->prepare($query);

    $stmt->execute(array(
      ':lang' => $this->sessParams['langId'],
      ':comp_id' => $id,
      ':name' => $data['name'],
      ':email' => $data['email'],
      ':phone' => $data['phone'],
      ':mess' => $data['message']
      ));

    $data['site_name'] = Optimized_EPUtils::$siteName;

    require_once(PATH_CLASSES . "/iMail/Distribute/Company.php");
    $iMail = new iMail_Distribute_Company();
    $iMail->companyMessage($id, $data);

    return 1;
  }

  /**
  * Отправляет сообщение выбранному сотруднику, id которого передано первым параметром.
  * Вторым массивом передается сообщение, состоящее из полей:
  * name - имя отправителя
  * email - мыло
  * phone - телефон
  * message - сообщение
  *
  * В случае успеха возвращает 1, иначе 0
  *
  * @param int $id
  * @param array $data
  * @return int
  */
  private function sendCompanyEmployerMessage($id, $data) {

    $query = "INSERT INTO companies_employers_messages SET languages_id=:lang, employers_id=:empl_id, name=:name, email=:email, phone=:phone, message=:mess";

    $stmt = DB_PDO::getInst()->prepare($query);

    $stmt->execute(array(
      ':lang' => $this->sessParams['langId'],
      ':empl_id' => $id,
      ':name' => $data['name'],
      ':email' => $data['email'],
      ':phone' => $data['phone'],
      ':mess' => $data['message']
      ));

    $data['site_name'] = Optimized_EPUtils::$siteName;

    require_once(PATH_CLASSES . "/iMail/Distribute/Company.php");
    $iMail = new iMail_Distribute_Company();
    $iMail->employeeMessage($id, $data);

    return 1;
  }


  /**
  * Функция добавляет компанию в календарь пользователя.
  * Если аутентификация прошла неудачно status = 0
  * Если указанная выставка не существует или неактивна status = 2
  * В случае успеха status = 2
  *
  * @param String $userName
  * @param String $userPassHash
  * @param Integer $id
  * @return Array
  */
  protected function addCompanyToSchedule($userName, $userPassHash, $id) {
    return $this->_scheduleAddEntry($userName, $userPassHash, $id, 'companies');
  }

  protected function addCompanyServiceToSchedule($userName, $userPassHash, $id) {
    return $this->_scheduleAddEntry($userName, $userPassHash, $id, 'services');
  }

  protected function addExhibitionToSchedule($userName, $userPassHash, $id) {
    return $this->_scheduleAddEntry($userName, $userPassHash, $id, 'events');
  }

  protected function addExpocenterToSchedule($userName, $userPassHash, $id) {
    return $this->_scheduleAddEntry($userName, $userPassHash, $id, 'expocenters');
  }

  /**
  * Функция удаляет компанию из календаря пользователя, id которой указан в параметре $id
  *
  * @param String $userName
  * @param String $userPassHash
  * @param Integer $id
  * @return Array
  */
  protected function delCompanyFromSchedule($userName, $userPassHash, $id) {
    return $this->_scheduleDelEntry($userName, $userPassHash, $id, 'companies');
  }

  protected function delCompanyServiceFromSchedule($userName, $userPassHash, $id) {
    return $this->_scheduleDelEntry($userName, $userPassHash, $id, 'services');
  }

  protected function delExhibitionFromSchedule($userName, $userPassHash, $id) {
    return $this->_scheduleDelEntry($userName, $userPassHash, $id, 'events');
  }

  protected function delExpocenterFromSchedule($userName, $userPassHash, $id) {
    return $this->_scheduleDelEntry($userName, $userPassHash, $id, 'expocenters');
  }

  protected function _scheduleDelEntry($userName, $userPassHash, $id, $type) {
    $result['reqParams'] = array('userName' => $userName, 'userPassHash' => strlen($userPassHash), 'id' => $id);
    $result['status'] = 1;

    $authTest = $this->userAuth($userName, $userPassHash);

    if ($authTest !== false) {

      $query = "DELETE FROM users_sites_scheduler
        WHERE users_sites_id = '" . intval($authTest['data']['id']) . "' AND parent = '" . intval($id) . "' AND type='" . $type . "'";

      try {
        DB_PDO::getInst()->exec($query);
      } catch (Exception $e) {
        $this->error = true;
        $result = array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
      }

    } else {
      $result['status'] = 0;
    }

    return $result;
  }

  protected function _scheduleAddEntry($userName, $userPassHash, $id, $type) {
    $result['reqParams'] = array('userName' => $userName, 'userPassHash' => strlen($userPassHash), 'id' => $id);
    $result['status'] = 1;

    $authTest = $this->userAuth($userName, $userPassHash);

    if ($authTest !== false) {

      switch ($type) {
        case "companies":
          $entryTest = $this->getCompany($id);
          break;
        case "events":
          $entryTest = $this->getExhibition($id);
          break;
        case "services":
          $entryTest = $this->getCompaniesService($id);
          break;
        case "expocenters":
          $entryTest = $this->getExpoCenter($id);
          break;
        default:
          return 0;
      }

      if (isset($entryTest['data']['id']) && $entryTest['data']['id'] > 0) {

        $query = "INSERT IGNORE INTO users_sites_scheduler SET users_sites_id = '" . $authTest['data']['id'] . "',
          parent = '" . $entryTest['data']['id'] . "', type = '" . $type . "'";

        try {
          DB_PDO::getInst()->exec($query);
        } catch (Exception $e) {
          $this->error = true;
          $result = array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
        }

      } else {
        $result['status'] = 2;
      }
    } else {
      $result['status'] = 0;
    }

    return $result;
  }

  /**
  * Возвращает список компаний в календаре пользователя.
  *
  * @param Integer $results_num
  * @param Integer $page
  * @param String $userName
  * @param String $userPassHash
  * @return Array
  */
  private function getUserComaniesSchedule($results_num, $page, $userName, $userPassHash) {
    $result['reqParams'] = array('userName' => $userName, 'userPassHash' => strlen($userPassHash));
    $result['status'] = 1;
    $ids = array();

    $authTest = $this->userAuth($userName, $userPassHash);

    if ($authTest !== false) {
      $query = "SELECT c.id, c.cities_id AS cityId, c.countries_id AS countryId, cd.name, lcd.name AS cityName,
        lcntd.name AS countryName, c.logo AS company_logo, c.videos, c.news, services, cd.description AS description_short
        FROM users_sites_scheduler AS uss
        JOIN ExpoPromoter_MViews.companies_" . $this->sessParams['lang'] . " AS c ON (c.id = uss.parent)
        JOIN companies_data AS cd ON (c.id = cd.id)
        JOIN location_cities_data AS lcd ON (c.cities_id = lcd.id)
        JOIN location_countries_data AS lcntd ON (c.countries_id = lcntd.id)
        WHERE uss.type='companies' AND uss.users_sites_id = '" . $authTest['data']['id'] . "' AND
        lcd.languages_id = lcntd.languages_id AND lcd.languages_id=cd.languages_id
        AND lcd.languages_id = '" . $this->sessParams['langId'] . "'";

      try {
        $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
        $result['pagesTotal'] = $split['pages'];
        $result['resultsTotal'] = $split['totalResults'];
        $result['reqParams']['page'] = $split['page'];
        $resQuery = $split['query'];

        $result['data'] = DB_PDO::fetchAll($resQuery);

        Optimized_EPUtils::createLogoUrl($result['data'], "companies_small");
      } catch (Exception $e) {
        $this->error = true;
        $result = array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
      }

    } else {
      $result['status'] = 2;
    }

    return $result;
  }

  /**
  * Возвращает список товаров и услуг компаний в календаре пользователя.
  *
  * @param Integer $results_num
  * @param Integer $page
  * @param String $userName
  * @param String $userPassHash
  * @return Array
  */
  private function getUserComaniesServicesSchedule($results_num, $page, $userName, $userPassHash) {
    $result['reqParams'] = array('userName' => $userName, 'userPassHash' => strlen($userPassHash));
    $result['status'] = 1;
    $ids = array();

    $authTest = $this->userAuth($userName, $userPassHash);

    if ($authTest !== false) {

      $query = "SELECT cs.id, cs.companies_id, cs.companies_services_cats_id AS categories_id, cs.type, cs.price, cs.photo AS company_serv_logo, csd.name, csd.short, cd.name AS company_name
      FROM users_sites_scheduler AS uss
        JOIN companies_services AS cs ON (cs.id = uss.parent)
        JOIN ExpoPromoter_MViews.companies_" . $this->sessParams['lang'] . " AS c ON (cs.companies_id = c.id)
        JOIN companies_services_data AS csd ON (cs.id = csd.id)
        JOIN companies_data AS cd ON (cs.companies_id = cd.id)
        JOIN companies_services_active AS csa ON (cs.id=csa.id)
        WHERE uss.type='services' AND uss.users_sites_id = '" . $authTest['data']['id'] . "' AND csa.active=1 AND
        csa.languages_id=csd.languages_id AND cd.languages_id=csd.languages_id AND csd.languages_id='" . $this->sessParams['langId'] . "'";

      try {
        $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
        $result['pagesTotal'] = $split['pages'];
        $result['resultsTotal'] = $split['totalResults'];
        $result['reqParams']['page'] = $split['page'];
        $resQuery = $split['query'];

        $result['data'] = DB_PDO::fetchAll($resQuery);

        Optimized_EPUtils::createLogoUrl($result['data'], "companies_services_small");
      } catch (Exception $e) {
        $this->error = true;
        $result = array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
      }

    } else {
      $result['status'] = 2;
    }

    return $result;
  }

  /**
  * Возвращает список выставок в календаре пользователя.
  *
  * @param Integer $results_num
  * @param Integer $page
  * @param String $userName
  * @param String $userPassHash
  * @return Array
  */
  protected function getUserSchedule($results_num, $page, $userName, $userPassHash) {
    $result['reqParams'] = array('userName' => $userName, 'userPassHash' => strlen($userPassHash));
    $result['status'] = 1;
    $ids = array();

    $authTest = $this->userAuth($userName, $userPassHash);

    if ($authTest !== false) {
      $query = "SELECT e.id, e.brandId, e.period_date_from, e.period_date_to, e.cityId, e.countryId,
        bd.name, lcd.name AS cityName, lcntd.name AS countryName
        FROM users_sites_scheduler AS uss
        JOIN ExpoPromoter_MViews.events_" . $this->sessParams['lang'] . " AS e ON (e.id = uss.parent)
        JOIN brands_data AS bd ON (e.brandId = bd.id)
        JOIN location_cities_data AS lcd ON (e.cityId = lcd.id)
        JOIN location_countries_data AS lcntd ON (e.countryId = lcntd.id)
        WHERE uss.type='events' AND uss.users_sites_id = '" . $authTest['data']['id'] . "' AND
        lcd.languages_id = lcntd.languages_id AND lcd.languages_id=bd.languages_id
        AND lcd.languages_id = '" . $this->sessParams['langId'] . "'";

      try {
        $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
        $result['pagesTotal'] = $split['pages'];
        $result['resultsTotal'] = $split['totalResults'];
        $result['reqParams']['page'] = $split['page'];
        $resQuery = $split['query'];

        $result['data'] = DB_PDO::fetchAll($resQuery);
      } catch (Exception $e) {
        $this->error = true;
        $result = array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
      }

    } else {
      $result['status'] = 2;
    }

    return $result;
  }

  /**
  * Возвращает список товаров и услуг компаний в календаре пользователя.
  *
  * @param Integer $results_num
  * @param Integer $page
  * @param String $userName
  * @param String $userPassHash
  * @return Array
  */
  protected function getUserExpocentersSchedule($results_num, $page, $userName, $userPassHash) {
    $result['reqParams'] = array('userName' => $userName, 'userPassHash' => strlen($userPassHash));
    $result['status'] = 1;
    $ids = array();

    $authTest = $this->userAuth($userName, $userPassHash);

    if ($authTest !== false) {

      $query = "SELECT ec.id, ecd.name, vl.countryId, vl.countryName, vl.cityId, vl.cityName
        FROM users_sites_scheduler AS uss
        JOIN expocenters AS ec ON (ec.id = uss.parent)
        JOIN expocenters_data AS ecd ON (ec.id=ecd.id)
        JOIN view_location AS vl ON (ec.cities_id=vl.cityId)
        WHERE uss.type='expocenters' AND uss.users_sites_id = '" . $authTest['data']['id'] . "' AND ec.active=1 AND
        ecd.languages_id=vl.languageId AND ecd.languages_id='" . $this->sessParams['langId'] . "'";

      try {
        $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
        $result['pagesTotal'] = $split['pages'];
        $result['resultsTotal'] = $split['totalResults'];
        $result['reqParams']['page'] = $split['page'];
        $resQuery = $split['query'];

        $result['data'] = DB_PDO::fetchAll($resQuery);
      } catch (Exception $e) {
        $this->error = true;
        $result = array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
      }

    } else {
      $result['status'] = 2;
    }

    return $result;
  }

  /**
   * Сохраняет информацию о переходе посетителя на сайт с JavaScript-календара.
   * Эта информация используется для начисления статистики и денег за привлеченных
   * посетителей партнеру
   * 
   * @param $partner_sites_id
   * @return boolean
   */
  protected function addJsCalendarHit($partner_sites_id) {
      $query = "INSERT INTO statistic.raw_jscalendar_hits SET partner_sites_id = " .
          intval($partner_sites_id) . ", ip = INET_ATON('" . mysql_escape_string($this->sessParams['clientIP']) .
          "'), languages_id = '" . intval($this->sessParams['langId']) . "'";
          
      try {
          DB_PDO::query($query);
      } catch (Exception $e) {
          return false;
      }
      
      return true;
  }



  protected function logEventFreeTicket($userName, $userPassHash, $id) {
    $result['reqParams'] = array('userName' => $userName, 'userPassHash' => strlen($userPassHash), 'id' => $id);
    $result['status'] = 1;

    $authTest = $this->userAuth($userName, $userPassHash);

    if (!empty($authTest) && isset($authTest['data'])) {
      $entryTest = $this->getExhibition($id);

      // if (isset($entryTest['data']['id']) && $entryTest['data']['id'] > 0 && $entryTest['data']['free_tickets']) {
      if (isset($entryTest['data']['id']) && $entryTest['data']['id'] > 0) {
        $query = "INSERT INTO events_tickets SET users_id = '" . $authTest['data']['id'] . "',
          events_id = '" . $entryTest['data']['id'] . "'";

        try {
          DB_PDO::getInst()->exec($query);
        } catch (Exception $e) {
          $this->error = true;
          $result = array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
        }

      } else {
        $result['status'] = 2;
      }
    } else {
      $result['status'] = 0;
    }

    // mail('eugene.ivashin@expopromogroup.com', 'TICKETS LOG CHECK', print_r($result, true));

    return $result;
  }



  public function getPublisher($publisher) {
    if (is_numeric($publisher)) {
      $subquery = "s.id = '$publisher'";
    } else {
      $subquery = "s.id IN (SELECT ss.id FROM sites ss LEFT JOIN sites_data ssd ON ssd.id = ss.id WHERE ssd.integration_url = '$publisher' AND ss.integration = 'html')";
    }

    $publisher = (int)$publisher;
    $langId = $this->sessParams['langId'];

    $result = array();
    $result['reqParams'] = array('id' => $publisher);

    $query =
     "SELECT
        s.id, s.ip, s.access_key,
        sd.name, sd.description, s.url, sd.integration_url,
        s.countries_filter, s.categories_filter,
        s.countries_id, s.email, sd.html_header, sd.html_footer,

        tmenu_tcolor, tmenu_lcolor1, tmenu_lcolor2, tmenu_bcolor,
        filter_tcolor, filter_bcolor,
        elist_tcolor, elist_lcolor, elist_bcolor1, elist_bcolor2,
        pager_tcolor, pager_lcolor1, pager_lcolor2,
        chead_tcolor, chead_lcolor, chead_bcolor,
        cmenu_tcolor, cmenu_lcolor1, cmenu_lcolor2, cmenu_bcolor,
        content_tcolor, content_lcolor, content_bcolor,
        sidebar_tcolor, sidebar_lcolor, sidebar_bcolor,
        default_design,

        max_width, select_width1, select_width2,

        p.type,
        p.id     AS partner_id,
        p.enable_html,
        p.enable_banners,
        s.integration AS integration_type
      FROM sites s
        LEFT JOIN sites_data sd ON sd.id = s.id AND sd.languages_id = '$langId'
        LEFT JOIN partners p ON p.id = s.partners_id
      WHERE $subquery
        AND s.active = 1
        AND p.active = 1
    ";

    try {
      $data = DB_PDO::fetchAll($query);

      if (!empty($data)) {
        $result['data'] = $data[0];
      } else {
        $result['data'] = array();
      }

      $countries_filter  = explode(',', $result['data']['countries_filter']);
        $result['data']['countries_filter'] = count($countries_filter) <= 3 ? $result['data']['countries_filter'] : '';

      $categories_filter = explode(',', $result['data']['categories_filter']);
        $result['data']['categories_filter'] = count($categories_filter) <= 3 ? $result['data']['categories_filter'] : '';

      return $result;
    } catch (Exception $e) {
      $this->error = true;
      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }



  protected function getSitesList($results_num, $page, $params = array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'params' => $params);

    $lang = $this->sessParams['langId'];

    $baseQuery = '
      FROM sites AS s
        JOIN sites_data AS sd ON s.id = sd.id AND sd.languages_id  = ' . $lang . '
        JOIN partners AS p ON s.partners_id = p.id
        LEFT JOIN location_countries_data AS lcd ON lcd.id = s.countries_id AND lcd.languages_id = ' . $lang . '
      WHERE s.active=1
        AND p.active=1
    ';

    if (isset($params['type'])) {
      $baseQuery .= " AND p.type = '" . $params['type'] . "'";
    }

    $query = 'SELECT s.id, sd.name, sd.description, sd.integration_url, lcd.name AS country_name, p.type '. $baseQuery .' ORDER BY s.id ASC';

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $resQuery = $split['query'];
      $result['data'] = DB_PDO::fetchAll($resQuery);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      // mail("eugene.ivashin@expopromogroup.com", "test", print_r($result, true));

      return $result;
    } catch (Exception $e) {
      $this->error = true;

      // mail("eugene.ivashin@expopromogroup.com", "error", print_r($result, true));

      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }



  protected function getWebmasterSitesList($results_num, $page, $params = array()) {
    $result = array();
    $result['reqParams'] = array('resultsNum' => $results_num, 'params' => $params);

    $lang = $this->sessParams['langId'];

    $baseQuery = "
      FROM webmaster.resources AS s
        JOIN webmaster.resources_data AS sd ON s.id = sd.id AND sd.languages_id = (3-$lang)
      WHERE s.verified = 1
        AND s.hidden_check = 0
        AND LENGTH(s.sys_link_advance) > 3
        AND s.sys_link_advance != 'Ожидается подключение'
    ";

    $query = "SELECT s.id, sd.res_name AS name, sd.description, s.sys_link_advance AS integration_url $baseQuery ORDER BY s.id ASC";

    // mail("eugene.ivashin@expopromogroup.com", "test", $query);

    try {
      $split = DB_PDO::splitPageResults($query, $results_num, '*', $page);
      $resQuery = $split['query'];
      $result['data'] = DB_PDO::fetchAll($resQuery);
      $result['pagesTotal'] = $split['pages'];
      $result['resultsTotal'] = $split['totalResults'];
      $result['reqParams']['page'] = $split['page'];

      return $result;
    } catch (Exception $e) {
      $this->error = true;

      // mail("eugene.ivashin@expopromogroup.com", "error", print_r($result, true));

      return array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }



  /**
  * Конструктор класса.
  * Создаем подключение к БД и инициализируем параметры.
  *
  */
  public function __construct() {
    $this->startExecTime = EPUtils::getMicroTime();

    //Инициализируем класс кеширования.
    //$this->cacheInst = new EPCache;

    try {
      DB::connect(DBHOST, DBUSERNAME, DBPASSWORD, DBNAME);
      DB_PDO::connect(DBHOST, DBUSERNAME, DBPASSWORD, DBNAME);
    } catch (Exception $e) {
      $this->error = true;
      $this->errorMessage = array('errorCode' => $e->getCode(), 'errorMessage' => $e->getMessage());
    }
  }

  /**
  * Деструктор.
  * Уничтожаем объект класса статистики, вызывая его деструктор для завершающей стадии, закрываем подключение к БД.
  *
  */
  public function __destruct() {
    //Применяем статистику
    #EPStat::commitStat();

    DB::disconnect();
    DB_PDO::disconnect();
  }
}
