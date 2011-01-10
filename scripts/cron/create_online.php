<?php
ini_set('memory_limit','1000M');
include 'zend_cron_init.php';

include ROOT_PATH."/data/cache/file/rcc_csc.php";
foreach($globalFilterCacheArray as $key=>$data){
	Zend_Registry::set ($key, $data);
}
unset ($globalFilterCacheArray);

echo "<pre>";

	$db = new ArOn_Db_Table();

	$sql = "TRUNCATE `online_types`;";
	$result = $db->getAdapter()->query($sql);
	$sql = "TRUNCATE `online_showrooms`;";
	$result = $db->getAdapter()->query($sql);
	$sql = "TRUNCATE `online_places`;";
	$result = $db->getAdapter()->query($sql);

	$result = $db->getAdapter()->query($sql);
	$sql = "
		INSERT INTO `online_types`
			(`id`, `name`, `width`, `height`, `banner`, `size`)
		VALUES
			(1, 'Stand 53x53', 53, 53, 0, 3),
			(2, 'Stand 123x123', 123, 123, 0, 2),
			(3, 'Stand 248x248', 248, 248, 0, 1)
	;";
	$result = $db->getAdapter()->query($sql);

	$paramName = 'category';
	$cache = Zend_Registry::get(Tools_Events::$filterParams[$paramName].Tools_Events::$cacheSuffix['id']);

	$schema = array_filter(array_map('trim',explode(";", file_get_contents(ROOT_PATH."/scripts/sql/online_hall_1.sql"))), 'strlen');
	$id = 0;
	foreach ($cache as $id=>$category){
		$id++;
		$sql = "
			INSERT INTO `online_showrooms`
				(`id`, `brands_categories_id`, `name`, `width`, `height`, `order`)
			VALUES
				(".$id.", ".$category['id'].", '".Tools_View::convertAlias2ClassName($category['alias'])."-1', 968, 1340, 50)
		;";
		$result = $db->getAdapter()->query($sql);
		foreach ($schema as $sql){
			$result = $db->getAdapter()->query(str_replace('{{showrooms_id}}', $id, $sql).';');
		}
	}