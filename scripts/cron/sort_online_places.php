<?php
ini_set('memory_limit','1000M');
include_once 'zend_cron_init.php';
echo "<pre>";

		$db = new ArOn_Db_Table();

		$sql = "
	SELECT
		`id`,
		`showrooms_id`,
		`types_id`,
		`companies_id`,
		`top`,
		`left`,
		`showrooms_order`
	FROM
		`online_places`
	ORDER BY
		`showrooms_id` ASC,
		`top` ASC,
		`left` ASC
	;";
		$result = $db->getAdapter()->fetchAll($sql);
$showrooms_id = -1;
foreach ($result as $index=>$r){
	if ($r['showrooms_id']!=$showrooms_id){
		$showrooms_id = $r['showrooms_id'];
		$order = 0;
	}
	$order++;
	$db->getAdapter()->query("
		UPDATE
			`online_places`
		SET
			`showrooms_order`=".$order."
		WHERE
			`id`=".$r['id']."
		LIMIT
			1;
	");
}