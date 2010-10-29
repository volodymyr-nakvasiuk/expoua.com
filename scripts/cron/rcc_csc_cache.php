<?php
ini_set('memory_limit','1000M');
include 'zend_cron_init.php';

$db = new Db_LocationRegions();
echo '<pre>';
print_r($db->fetchAll()->toArray());