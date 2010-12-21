<?php

require_once("../application/globalConfig.php");
require_once("Zend/Loader.php");

ini_set("display_errors", 1);
error_reporting(E_ALL);

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=ExpoPromoter_Opt", DB_USERNAME, DB_PASS);
$pdo->exec("SET NAMES UTF8");

$pr = "ExpoPromoter_Opt";

$stmt_query = $pdo->prepare("
SELECT 
  od.name AS organizer_name, 
  bd.name AS exhibition_name, 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'name') AS 'Компания', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'contact_person') AS 'Контактное лицо', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'position') AS 'Должность', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'city') AS 'Город', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'phone') AS 'Телефон', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'fax') AS 'Факс', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'url') AS 'Сайт', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'email') AS 'Email', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'address') AS 'Адрес', 
  (
    SELECT qt.name 
    FROM {$pr}.requests_data 
    LEFT JOIN ExpoPromoter.expoua_ru_query_types AS qt ON requests_data.value = qt.id
    WHERE requests_id = r.id AND `type` = 'purpose'
  ) AS 'Цель', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'message') AS 'Текст сообщения', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'details') AS 'Пордробности', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'S1') AS 'Площадь 1', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'S2') AS 'Площадь 2', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'S3') AS 'Площадь 3', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'check1') AS 'С1', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'check2') AS 'С2', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'check3') AS 'С3', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'check4') AS 'С4', 
  (SELECT value FROM {$pr}.requests_data WHERE requests_id = r.id AND `type` = 'check5') AS 'С5' 
FROM {$pr}.requests AS r 
  LEFT JOIN {$pr}.organizers_data AS od ON od.id = r.parent AND od.languages_id = 1
  LEFT JOIN {$pr}.events AS e ON e.id = r.child
  LEFT JOIN {$pr}.brands_data AS bd ON bd.id = e.brands_id AND bd.languages_id = 1
WHERE parent IN(19)
  AND (r.date_add BETWEEN '2008-09-01' AND NOW())
ORDER BY od.name, bd.name;
");

$stmt_query->execute();


$result = $stmt_query->fetchAll();

print_r($result);
