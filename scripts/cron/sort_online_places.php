<?php
ini_set('memory_limit','1000M');
include 'zend_cron_init.php';
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
		`top` ASC,
		`left` ASC
	;";
		$result = $db->getAdapter()->fetchAll($sql);
foreach ($result as $index=>$r){
	$db->getAdapter()->query("
		UPDATE
			`online_places`
		SET
			`showrooms_order`=".($index+1)."
		WHERE
			`id`=".$r['id']."
		LIMIT
			1;
	");
}