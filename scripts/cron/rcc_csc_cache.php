<?php
include 'zend_cron_init.php';
ini_set('memory_limit','1000M');

$langArray = array(
	array('id'=>2, 'alias'=>'en'),
	array('id'=>1, 'alias'=>'ru'),
);

echo '<pre>';

$cacheArrayName = 'globalFilterCacheArray';

$file = '<?php'."\n".
		'$'.$cacheArrayName.' = array();'."\n";

Db_Lang::$globalLangId = $langArray[0]['id'];
$db = new Db_Lang_LocationRegionsData();
$select = $db->select('Db_Lang_LocationRegionsData');
$select
		->reset(ArOn_Db_TableSelect::COLUMNS)
		->columns()
		->reset(ArOn_Db_TableSelect::ORDER)
		->order('Db_Lang_LocationRegionsData.name ASC')
		->where('Db_LocationRegions.active=?',1)
		->columnsJoinOne(array('Db_LocationRegions'), array ('active'=>'active'));
$regions = $db->fetchAll($select)->toArray();
//print_r($regions);

$active = array('regions'=>array(), 'countries'=>array());
$location = array();
foreach ($regions as $region){
	Db_Lang::$globalLangId = $langArray[1]['id'];
	$db = new Db_Lang_LocationRegionsData();
	$tr = $db->fetchRow($db->getPrimary().'='.$region['id'])->toArray();
	$location[$region['id']] = array('region'=>array_merge($region, array('trName'=>$tr['name'])),'countries'=>array());
	//echo "\n-------------------\n\n".$region['id']." - ".$region['name']."\n";
	Db_Lang::$globalLangId = $langArray[0]['id'];
	$db = new Db_Lang_LocationCountriesData();
	$select = $db->select('Db_Lang_LocationCountriesData');
	$select
		->reset(ArOn_Db_TableSelect::COLUMNS)
		->columns()
		->reset(ArOn_Db_TableSelect::ORDER)
		->order('Db_Lang_LocationCountriesData.name ASC')
		->where('Db_LocationCountries.regions_id=?',$region['id'])
		->where('Db_LocationCountries.active=?',1)
		->columnsJoinOne(array('Db_LocationCountries'), array ('regions_id' => 'regions_id','active'=>'active'));
	$countries = $db->fetchAll($select)->toArray();
	//print_r($countries);

	foreach ($countries as $country){
		Db_Lang::$globalLangId = $langArray[1]['id'];
		$db = new Db_Lang_LocationCountriesData();
		$tr = $db->fetchRow($db->getPrimary().'='.$country['id'])->toArray();
		$location[$region['id']]['countries'][$country['id']] = array('country'=>array_merge($country, array('trName'=>$tr['name'])),'cities'=>array());
		//echo "\n==================\n\n\t".$country['id']." - ".$country['name']."\n";
		Db_Lang::$globalLangId = $langArray[0]['id'];
		$db = new Db_Lang_LocationCitiesData();
		$select = $db->select('Db_Lang_LocationCitiesData');
		$select
				->reset(ArOn_Db_TableSelect::COLUMNS)
				->columns()
				->reset(ArOn_Db_TableSelect::ORDER)
				->order('Db_Lang_LocationCitiesData.name ASC')
				->where('Db_LocationCities.countries_id=?',$country['id'])
				->where('Db_LocationCities.active=?',1)
				->columnsJoinOne(array('Db_LocationCities'), array ('countries_id' => 'countries_id','active'=>'active'));
		$cities = $db->fetchAll($select)->toArray();
		foreach ($cities as $key=>$city){
			$db = new Db_Events();
			$select = $db->select('Db_Events');
			$select
				->where('Db_Events.cities_id=?',$city['id'])
				->where('Db_Events.active=?',1);
			$result = $db->fetchRow($select);
			if ($result){
				$active['regions'][$region['id']] = 1;
				$active['countries'][$country['id']] = 1;
				Db_Lang::$globalLangId = $langArray[1]['id'];
				$db = new Db_Lang_LocationCitiesData();
				$tr = $db->fetchRow($db->getPrimary().'='.$city['id'])->toArray();
				$location[$region['id']]['countries'][$country['id']]['cities'][$city['id']] = array('city'=>array_merge($city, array('trName'=>$tr['name'])));
			}
		}
		//print_r($cities);
	}
}

foreach ($location as $regionId=>$region){
	if (!array_key_exists($region['region']['id'], $active['regions'])){
		unset($location[$regionId]);
		continue;
	}
	foreach($region['countries'] as $countryId=>$country){
		if (!array_key_exists($country['country']['id'], $active['countries'])){
			unset($location[$regionId]['countries'][$countryId]);
			continue;
		}
	}
}
//print_r($location); //exit();

$regionsCacheByNamesArrayName = 'regionsCacheByNames';
$countriesCacheByNamesArrayName = 'countriesCacheByNames';
$citiesCacheByNamesArrayName = 'citiesCacheByNames';

$regionsCacheByIdsArrayName = 'regionsCacheByIds';
$countriesCacheByIdsArrayName = 'countriesCacheByIds';
$citiesCacheByIdsArrayName = 'citiesCacheByIds';

$fileRegions = "\n";
$fileCountries = "\n";
$fileCities = "\n";

$fileRegions .= '$'.$cacheArrayName.'["'.$regionsCacheByNamesArrayName.'"] = array();'."\n";
$fileCountries .= '$'.$cacheArrayName.'["'.$countriesCacheByNamesArrayName.'"] = array();'."\n";
$fileCities .= '$'.$cacheArrayName.'["'.$citiesCacheByNamesArrayName.'"] = array();'."\n";

$fileRegions .= '$'.$cacheArrayName.'["'.$regionsCacheByIdsArrayName.'"] = array();'."\n";
$fileCountries .= '$'.$cacheArrayName.'["'.$countriesCacheByIdsArrayName.'"] = array();'."\n";
$fileCities .= '$'.$cacheArrayName.'["'.$citiesCacheByIdsArrayName.'"] = array();'."\n";

foreach ($location as $regionId=>$region){
	$regionAlias = Tools_View::getUrlAlias($region['region']['name']);
	$fileRegions .= '$'.$cacheArrayName.'["'.$regionsCacheByIdsArrayName.'"]["'.$region['region']['id'].'"] = $'.$cacheArrayName.'["'.$regionsCacheByNamesArrayName.'"]["'.$regionAlias.'"] = array('."\n";
	$fileRegions .= "\t".'"id"=>"'.$region['region']['id'].'",'."\n";
	$fileRegions .= "\t".'"name_'.$langArray[0]['alias'].'"=>"'.$region['region']['name'].'",'."\n";
	$fileRegions .= "\t".'"name_'.$langArray[1]['alias'].'"=>"'.$region['region']['trName'].'",'."\n";
	$fileRegions .= "\t".'"alias"=>"'.$regionAlias.'",'."\n";
	$fileRegions .= ');'."\n";

	$fileCountries .= '$'.$cacheArrayName.'["'.$countriesCacheByIdsArrayName.'"]["'.$region['region']['id'].'"] = $'.$cacheArrayName.'["'.$countriesCacheByNamesArrayName.'"]["'.$regionAlias.'"] = array();'."\n";
	$fileCities .= '$'.$cacheArrayName.'["'.$citiesCacheByIdsArrayName.'"]["'.$region['region']['id'].'"] = $'.$cacheArrayName.'["'.$citiesCacheByNamesArrayName.'"]["'.$regionAlias.'"] = array();'."\n";
	foreach($region['countries'] as $countryId=>$country){
		$countryAlias = Tools_View::getUrlAlias($country['country']['name']);
		$fileCountries .= '$'.$cacheArrayName.'["'.$countriesCacheByIdsArrayName.'"]["'.$region['region']['id'].'"]["'.$country['country']['id'].'"] = $'.$cacheArrayName.'["'.$countriesCacheByNamesArrayName.'"]["'.$regionAlias.'"]["'.$countryAlias.'"] = array('."\n";
		$fileCountries .= "\t".'"id"=>"'.$country['country']['id'].'",'."\n";
		$fileCountries .= "\t".'"name_'.$langArray[0]['alias'].'"=>"'.$country['country']['name'].'",'."\n";
		$fileCountries .= "\t".'"name_'.$langArray[1]['alias'].'"=>"'.$country['country']['trName'].'",'."\n";
		$fileCountries .= "\t".'"alias"=>"'.$countryAlias.'",'."\n";
		$fileCountries .= ');'."\n";

		$fileCities .= '$'.$cacheArrayName.'["'.$citiesCacheByIdsArrayName.'"]["'.$region['region']['id'].'"]["'.$country['country']['id'].'"] = $'.$cacheArrayName.'["'.$citiesCacheByNamesArrayName.'"]["'.$regionAlias.'"]["'.$countryAlias.'"] = array();'."\n";
		foreach($country['cities'] as $cityId=>$city){
			$cityAlias = Tools_View::getUrlAlias($city['city']['name']);
			$fileCities .= '$'.$cacheArrayName.'["'.$citiesCacheByIdsArrayName.'"]["'.$region['region']['id'].'"]["'.$country['country']['id'].'"]["'.$city['city']['id'].'"] = $'.$cacheArrayName.'["'.$citiesCacheByNamesArrayName.'"]["'.$regionAlias.'"]["'.$countryAlias.'"]["'.$cityAlias.'"] = array('."\n";
			$fileCities .= "\t".'"id"=>"'.$city['city']['id'].'",'."\n";
			$fileCities .= "\t".'"name_'.$langArray[0]['alias'].'"=>"'.$city['city']['name'].'",'."\n";
			$fileCities .= "\t".'"name_'.$langArray[1]['alias'].'"=>"'.$city['city']['trName'].'",'."\n";
			$fileCities .= "\t".'"alias"=>"'.$cityAlias.'",'."\n";
			$fileCities .= ');'."\n";
		}
	}
}

Db_Lang::$globalLangId = $langArray[0]['id'];
$db = new Db_Lang_BrandsCategoriesData();
$select = $db->select('Db_Lang_BrandsCategoriesData');
$select
		->reset(ArOn_Db_TableSelect::COLUMNS)
		->columns()
		->reset(ArOn_Db_TableSelect::ORDER)
		->order('Db_Lang_BrandsCategoriesData.name ASC')
		->where('Db_BrandsCategories.active=?',1)
		->columnsJoinOne(array('Db_BrandsCategories'), array ('active'=>'active'));
$categories = $db->fetchAll($select)->toArray();
//print_r($categories); //exit();

$brands = array();
foreach ($categories as $category){
	Db_Lang::$globalLangId = $langArray[1]['id'];
	$db = new Db_Lang_BrandsCategoriesData();
	$tr = $db->fetchRow($db->getPrimary().'='.$category['id'])->toArray();
	$brands[$category['id']] = array('category'=>array_merge($category, array('trName'=>$tr['name'])),'subcategories'=>array());
	//echo "\n-------------------\n\n".$region['id']." - ".$region['name']."\n";
	Db_Lang::$globalLangId = $langArray[0]['id'];
	$db = new Db_Lang_BrandsSubcategoriesData();
	$select = $db->select('Db_Lang_BrandsSubcategoriesData');
	$select
			->reset(ArOn_Db_TableSelect::COLUMNS)
			->columns()
			->reset(ArOn_Db_TableSelect::ORDER)
			->order('Db_Lang_BrandsSubcategoriesData.name ASC')
			->where('Db_BrandsSubcategories.parent_id=?',$category['id'])
			->where('Db_BrandsSubcategories.active=?',1)
			->columnsJoinOne(array('Db_BrandsSubcategories'), array ('parent_id' => 'parent_id','active'=>'active'));
	$subcategories = $db->fetchAll($select)->toArray();
	//print_r($subcategories); //exit();

	foreach ($subcategories as $subcategory){
		Db_Lang::$globalLangId = $langArray[1]['id'];
		$db = new Db_Lang_BrandsSubcategoriesData();
		$tr = $db->fetchRow($db->getPrimary().'='.$subcategory['id'])->toArray();
		$brands[$category['id']]['subcategories'][$subcategory['id']] = array('subcategory'=>array_merge($subcategory, array('trName'=>$tr['name'])));
	}
}

//print_r($brands); //exit();

$categoriesCacheByNamesArrayName = 'categoriesCacheByNames';
$subcategoriesCacheByNamesArrayName = 'subcategoriesCacheByNames';

$categoriesCacheByIdsArrayName = 'categoriesCacheByIds';
$subcategoriesCacheByIdsArrayName = 'subcategoriesCacheByIds';

$fileCategories = "\n";
$fileSubcategories = "\n";

$fileCategories .= '$'.$cacheArrayName.'["'.$categoriesCacheByNamesArrayName.'"] = array();'."\n";
$fileSubcategories .= '$'.$cacheArrayName.'["'.$subcategoriesCacheByNamesArrayName.'"] = array();'."\n";

$fileCategories .= '$'.$cacheArrayName.'["'.$categoriesCacheByIdsArrayName.'"] = array();'."\n";
$fileSubcategories .= '$'.$cacheArrayName.'["'.$subcategoriesCacheByIdsArrayName.'"] = array();'."\n";

foreach ($brands as $categoryId=>$category){
	$categoryAlias = Tools_View::getUrlAlias($category['category']['name']);
	$fileCategories .= '$'.$cacheArrayName.'["'.$categoriesCacheByIdsArrayName.'"]["'.$category['category']['id'].'"] = $'.$cacheArrayName.'["'.$categoriesCacheByNamesArrayName.'"]["'.$categoryAlias.'"] = array('."\n";
	$fileCategories .= "\t".'"id"=>"'.$category['category']['id'].'",'."\n";
	$fileCategories .= "\t".'"name_'.$langArray[0]['alias'].'"=>"'.$category['category']['name'].'",'."\n";
	$fileCategories .= "\t".'"name_'.$langArray[1]['alias'].'"=>"'.$category['category']['trName'].'",'."\n";
	$fileCategories .= "\t".'"alias"=>"'.$categoryAlias.'",'."\n";
	$fileCategories .= ');'."\n";

	$fileSubcategories .= '$'.$cacheArrayName.'["'.$subcategoriesCacheByIdsArrayName.'"]["'.$category['category']['id'].'"] = $'.$cacheArrayName.'["'.$subcategoriesCacheByNamesArrayName.'"]["'.$categoryAlias.'"] = array();'."\n";
	foreach($category['subcategories'] as $subcategoryId=>$subcategory){
		$subcategoryAlias = Tools_View::getUrlAlias($subcategory['subcategory']['name']);
		$fileSubcategories .= '$'.$cacheArrayName.'["'.$subcategoriesCacheByIdsArrayName.'"]["'.$category['category']['id'].'"]["'.$subcategory['subcategory']['id'].'"] = $'.$cacheArrayName.'["'.$subcategoriesCacheByNamesArrayName.'"]["'.$categoryAlias.'"]["'.$subcategoryAlias.'"] = array('."\n";
		$fileSubcategories .= "\t".'"id"=>"'.$subcategory['subcategory']['id'].'",'."\n";
		$fileSubcategories .= "\t".'"name_'.$langArray[0]['alias'].'"=>"'.$subcategory['subcategory']['name'].'",'."\n";
		$fileSubcategories .= "\t".'"name_'.$langArray[1]['alias'].'"=>"'.$subcategory['subcategory']['trName'].'",'."\n";
		$fileSubcategories .= "\t".'"alias"=>"'.$subcategoryAlias.'",'."\n";
		$fileSubcategories .= ');'."\n";
	}
}

file_put_contents(
	ROOT_PATH."/data/cache/file/rcc_csc.php",
	$file.
	$fileRegions.
	$fileCountries.
	$fileCities.
	$fileCategories.
	$fileSubcategories
	//."\n print_r(".'$'.$cacheArrayName.");"
);