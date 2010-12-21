<?php
ini_set('memory_limit','1000M');
include 'zend_cron_init.php';
echo "<pre>";
//$dbIni = parse_ini_file('../../application/config/main.ini',true);
//print_r();
$db = new ArOn_Db_Table();

$sql = "
	SELECT
		`brands`.`id` AS `brands_id`,
		`events`.`id` AS `events_id`,
		`events`.`date_from` AS `date_from`,
		`events`.`date_to` AS `date_to`
	FROM
		`brands`
	INNER JOIN
		`events`
	ON
		`brands`.`id`=`events`.`brands_id`
	;";
$result = $db->getAdapter()->fetchAll($sql);
$brands = array();
$t = time();
foreach ($result as $r){
	if ($brands[$r['brands_id']]){
		$r['dif'] = abs((strtotime($r['date_to'])+strtotime($r['date_from']))/2-$t);
		if ($r['dif']<$brands[$r['brands_id']]['dif']){
			$brands[$r['brands_id']] = $r;
		}
	}
	else {
		$r['dif'] = abs((strtotime($r['date_to'])+strtotime($r['date_from']))/2-$t);
		$brands[$r['brands_id']] = $r;
	}
}
print_r($brands);

$sql = "
	SELECT
		`id`,
		`brands_id`
	FROM
		`news`
	WHERE
		`brands_id` IS NOT NULL
	;";
$result = $db->getAdapter()->fetchAll($sql);
foreach ($result as $r){
	$db->getAdapter()->query("
		UPDATE
			`news`
		SET
			`events_id`=".$brands[$r['brands_id']]['events_id']."
		WHERE
			`id`=".$r['id']."
		;");
}