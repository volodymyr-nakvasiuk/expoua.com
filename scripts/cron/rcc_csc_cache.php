<?php
include 'zend_cron_init.php';
ini_set('memory_limit','1000M');

$langArray = array(
	array('id'=>2, 'alias'=>'en'),
	array('id'=>1, 'alias'=>'ru'),
);
$aliasLangId = 2;

echo '<pre>';
foreach ($langArray as $langData){
$cacheArrayName = 'globalFilterCacheArray';

$file = '<?php'."\n".
		'$'.$cacheArrayName.' = array();'."\n";

Db_Lang::$globalLangId = $langData['id'];
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
//print_r($regions); //exit();

$active = array('regions'=>array(), 'countries'=>array());
$location = array();
foreach ($regions as $region){
	Db_Lang::$globalLangId = $aliasLangId;
	$db = new Db_Lang_LocationRegionsData();
	$alias = $db->fetchRow($db->getPrimary().'='.$region['id'])->toArray();
	$location[$region['id']] = array('region'=>array_merge($region, array('alias'=>$alias['name'])),'countries'=>array());
	//echo "\n-------------------\n\n".$region['id']." - ".$region['name']."\n";
	Db_Lang::$globalLangId = $langData['id'];
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
	//print_r($countries); //exit();

	foreach ($countries as $country){
		Db_Lang::$globalLangId = $aliasLangId;
		$db = new Db_Lang_LocationCountriesData();
		$alias = $db->fetchRow($db->getPrimary().'='.$country['id'])->toArray();
		$location[$region['id']]['countries'][$country['id']] = array('country'=>array_merge($country, array('alias'=>$alias['name'])),'cities'=>array());
		//echo "\n==================\n\n\t".$country['id']." - ".$country['name']."\n";
		Db_Lang::$globalLangId = $langData['id'];
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
				Db_Lang::$globalLangId = $aliasLangId;
				$db = new Db_Lang_LocationCitiesData();
				$alias = $db->fetchRow($db->getPrimary().'='.$city['id'])->toArray();
				$location[$region['id']]['countries'][$country['id']]['cities'][$city['id']] = array('city'=>array_merge($city, array('alias'=>$alias['name'])));
			}
		}
		//print_r($cities); //exit();
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
	$regionAlias = Tools_View::getUrlAlias($region['region']['alias']);
	$fileRegions .= '$'.$cacheArrayName.'["'.$regionsCacheByIdsArrayName.'"]["'.$region['region']['id'].'"] = $'.$cacheArrayName.'["'.$regionsCacheByNamesArrayName.'"]["'.$regionAlias.'"] = array('."\n";
	$fileRegions .= "\t".'"id"=>"'.$region['region']['id'].'",'."\n";
	$fileRegions .= "\t".'"name"=>"'.$region['region']['name'].'",'."\n";
	$fileRegions .= "\t".'"alias"=>"'.$regionAlias.'",'."\n";
	$fileRegions .= ');'."\n";

	$fileCountries .= '$'.$cacheArrayName.'["'.$countriesCacheByIdsArrayName.'"]["'.$region['region']['id'].'"] = $'.$cacheArrayName.'["'.$countriesCacheByNamesArrayName.'"]["'.$regionAlias.'"] = array();'."\n";
	$fileCities .= '$'.$cacheArrayName.'["'.$citiesCacheByIdsArrayName.'"]["'.$region['region']['id'].'"] = $'.$cacheArrayName.'["'.$citiesCacheByNamesArrayName.'"]["'.$regionAlias.'"] = array();'."\n";
	foreach($region['countries'] as $countryId=>$country){
		$countryAlias = Tools_View::getUrlAlias($country['country']['alias']);
		$fileCountries .= '$'.$cacheArrayName.'["'.$countriesCacheByIdsArrayName.'"]["'.$region['region']['id'].'"]["'.$country['country']['id'].'"] = $'.$cacheArrayName.'["'.$countriesCacheByNamesArrayName.'"]["'.$regionAlias.'"]["'.$countryAlias.'"] = array('."\n";
		$fileCountries .= "\t".'"id"=>"'.$country['country']['id'].'",'."\n";
		$fileCountries .= "\t".'"name"=>"'.$country['country']['name'].'",'."\n";
		$fileCountries .= "\t".'"alias"=>"'.$countryAlias.'",'."\n";
		$fileCountries .= ');'."\n";

		$fileCities .= '$'.$cacheArrayName.'["'.$citiesCacheByIdsArrayName.'"]["'.$region['region']['id'].'"]["'.$country['country']['id'].'"] = $'.$cacheArrayName.'["'.$citiesCacheByNamesArrayName.'"]["'.$regionAlias.'"]["'.$countryAlias.'"] = array();'."\n";
		foreach($country['cities'] as $cityId=>$city){
			$cityAlias = Tools_View::getUrlAlias($city['city']['alias']);
			$fileCities .= '$'.$cacheArrayName.'["'.$citiesCacheByIdsArrayName.'"]["'.$region['region']['id'].'"]["'.$country['country']['id'].'"]["'.$city['city']['id'].'"] = $'.$cacheArrayName.'["'.$citiesCacheByNamesArrayName.'"]["'.$regionAlias.'"]["'.$countryAlias.'"]["'.$cityAlias.'"] = array('."\n";
			$fileCities .= "\t".'"id"=>"'.$city['city']['id'].'",'."\n";
			$fileCities .= "\t".'"name"=>"'.$city['city']['name'].'",'."\n";
			$fileCities .= "\t".'"alias"=>"'.$cityAlias.'",'."\n";
			$fileCities .= ');'."\n";
		}
	}
}

Db_Lang::$globalLangId = $langData['id'];
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
	Db_Lang::$globalLangId = $aliasLangId;
	$db = new Db_Lang_BrandsCategoriesData();
	$alias = $db->fetchRow($db->getPrimary().'='.$category['id'])->toArray();
	$brands[$category['id']] = array('category'=>array_merge($category, array('alias'=>$alias['name'])),'subcategories'=>array());
	//echo "\n-------------------\n\n".$region['id']." - ".$region['name']."\n";
	Db_Lang::$globalLangId = $langData['id'];
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
		Db_Lang::$globalLangId = $aliasLangId;
		$db = new Db_Lang_BrandsSubcategoriesData();
		$alias = $db->fetchRow($db->getPrimary().'='.$subcategory['id'])->toArray();
		$brands[$category['id']]['subcategories'][$subcategory['id']] = array('subcategory'=>array_merge($subcategory, array('alias'=>$alias['name'])));
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
	$categoryAlias = Tools_View::getUrlAlias($category['category']['alias']);
	$fileCategories .= '$'.$cacheArrayName.'["'.$categoriesCacheByIdsArrayName.'"]["'.$category['category']['id'].'"] = $'.$cacheArrayName.'["'.$categoriesCacheByNamesArrayName.'"]["'.$categoryAlias.'"] = array('."\n";
	$fileCategories .= "\t".'"id"=>"'.$category['category']['id'].'",'."\n";
	$fileCategories .= "\t".'"name"=>"'.$category['category']['name'].'",'."\n";
	$fileCategories .= "\t".'"alias"=>"'.$categoryAlias.'",'."\n";
	$fileCategories .= ');'."\n";

	$fileSubcategories .= '$'.$cacheArrayName.'["'.$subcategoriesCacheByIdsArrayName.'"]["'.$category['category']['id'].'"] = $'.$cacheArrayName.'["'.$subcategoriesCacheByNamesArrayName.'"]["'.$categoryAlias.'"] = array();'."\n";
	foreach($category['subcategories'] as $subcategoryId=>$subcategory){
		$subcategoryAlias = Tools_View::getUrlAlias($subcategory['subcategory']['alias']);
		$fileSubcategories .= '$'.$cacheArrayName.'["'.$subcategoriesCacheByIdsArrayName.'"]["'.$category['category']['id'].'"]["'.$subcategory['subcategory']['id'].'"] = $'.$cacheArrayName.'["'.$subcategoriesCacheByNamesArrayName.'"]["'.$categoryAlias.'"]["'.$subcategoryAlias.'"] = array('."\n";
		$fileSubcategories .= "\t".'"id"=>"'.$subcategory['subcategory']['id'].'",'."\n";
		$fileSubcategories .= "\t".'"name"=>"'.$subcategory['subcategory']['name'].'",'."\n";
		$fileSubcategories .= "\t".'"alias"=>"'.$subcategoryAlias.'",'."\n";
		$fileSubcategories .= ');'."\n";
	}
}

Db_Lang::$globalLangId = $langData['id'];
$db = new Db_Lang_ServicesCategoriesData();
$select = $db->select('Db_Lang_ServicesCategoriesData');
$select
		->reset(ArOn_Db_TableSelect::COLUMNS)
		->columns()
		->reset(ArOn_Db_TableSelect::ORDER)
		->order('Db_Lang_ServicesCategoriesData.name ASC')
		->where('Db_ServicesCategories.active=?',1)
		->columnsJoinOne(array('Db_ServicesCategories'), array ('active'=>'active'));
$serviceCategories = $db->fetchAll($select)->toArray();
	//print_r($serviceCategories); //exit();

$services = array();
foreach ($serviceCategories as $category){
	Db_Lang::$globalLangId = $aliasLangId;
	$db = new Db_Lang_ServicesCategoriesData();
	$alias = $db->fetchRow($db->getPrimary().'='.$category['id'])->toArray();
	$services[$category['id']] = array('category'=>array_merge($category, array('alias'=>$alias['name'])));
}

	//print_r($services); //exit();

$serviceCategoriesCacheByNamesArrayName = 'serviceCategoriesCacheByNames';
$serviceCategoriesCacheByIdsArrayName = 'serviceCategoriesCacheByIds';

$fileServiceCategories = "\n";
$fileServiceCategories .= '$'.$cacheArrayName.'["'.$serviceCategoriesCacheByNamesArrayName.'"] = array();'."\n";
$fileServiceCategories .= '$'.$cacheArrayName.'["'.$serviceCategoriesCacheByIdsArrayName.'"] = array();'."\n";

foreach ($services as $categoryId=>$category){
	$categoryAlias = Tools_View::getUrlAlias($category['category']['alias']);
	$fileServiceCategories .= '$'.$cacheArrayName.'["'.$serviceCategoriesCacheByIdsArrayName.'"]["'.$category['category']['id'].'"] = $'.$cacheArrayName.'["'.$serviceCategoriesCacheByNamesArrayName.'"]["'.$categoryAlias.'"] = array('."\n";
	$fileServiceCategories .= "\t".'"id"=>"'.$category['category']['id'].'",'."\n";
	$fileServiceCategories .= "\t".'"name"=>"'.$category['category']['name'].'",'."\n";
	$fileServiceCategories .= "\t".'"alias"=>"'.$categoryAlias.'",'."\n";
	$fileServiceCategories .= ');'."\n";
}

file_put_contents(
	ROOT_PATH."/data/cache/file/rcc_csc_".$langData['alias'].".php",
	$file.
	$fileRegions.
	$fileCountries.
	$fileCities.
	$fileCategories.
	$fileSubcategories.
	$fileServiceCategories
	//."\n print_r(".'$'.$cacheArrayName.");"
);
}