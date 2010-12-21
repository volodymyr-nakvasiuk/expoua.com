<?php
////////////////////////////////////////////////
$lang = 'china';

$send_mode = 
//  "fake";
//  "test";
  "real";

//$part = 1;
$shift = 14056;
$portion = 5000;

////////////////////////////////////////////////



require_once("../application/globalConfig.php");
require_once("Zend/Loader.php");

require_once("Zend/Mail.php");
require_once('Zend/Validate/EmailAddress.php');

$emailValObj = new Zend_Validate_EmailAddress();
$mailObj = new Zend_Mail("utf-8");

$total = $totalSkipped = $totalOrganizers = 0;


$senders = array(
  'Анастасия Шумак' => array(
    'email' => 'info@expoua.com',
    'icq'   => '',
    'skype' => '',
  ),

  'Анастасия Шумак1' => array(
    'email' => 'anastasiya.shumak@expopromogroup.com',
    'icq'   => '372962899',
    'skype' => 'anastasiya.shumak',
  ),

  'Anastasiya Shumak' => array(
    'email' => 'anastasiya.shumak@expopromogroup.com',
    'icq'   => '372962899',
    'skype' => 'anastasiya.shumak',
  ),

  'Anna Shalya' => array(
    'email' => 'anna.shalya@expopromogroup.com',
    'icq'   => '231968208',
    'skype' => 'anna.shalya',
  ),

  'Mariya Shtanhrat' => array(
    'email' => "mariya.shtanhrat@expopromogroup.com",
    'icq'   => "554145334<br>\nMSN - mariya.shtanhrat@hotmail.com",
    'skype' => "mariya.shtanhrat",
  ),

);


//////////////////////////////////////////////////////////////////////////////////////////////////////////


$template['china'] = file_get_contents("external_template_china.html");




$data = array(
  //////////////////////////////////////////////////////////////////////////////////////////////////////////

  'china' => array(
    'query'      => 
     "SELECT DISTINCT '' AS name, email FROM subscribers WHERE active = 1
      UNION
      SELECT DISTINCT name, email FROM `users_sites` WHERE active=1
      UNION
      SELECT 
        rd1.value AS name, rd2.value AS email
      FROM requests AS r
        LEFT JOIN requests_data AS rd1 ON rd1.requests_id = r.id AND rd1.`type` = 'contact_person'
        LEFT JOIN requests_data AS rd2 ON rd2.requests_id = r.id AND rd2.`type` = 'email'
        INNER JOIN events AS e ON e.id = r.parent
        INNER JOIN brands AS b ON b.id = e.brands_id
        INNER JOIN brands_categories AS bc ON bc.id = b.brands_categories_id
      WHERE r.languages_id = 1
        AND bc.id IN (5, 6, 7, 8, 9, 12, 13, 14, 16, 18, 26)
        AND rd2.value != '' 
        AND rd2.value IS NOT NULL 
      ",

    'sender'  => 'Анастасия Шумак',
    
    'greeting1'  => 'Здравствуйте, {contact_person}',
    'greeting2'  => 'Здравствуйте',

    'message'    => $template['china']
  ),

  //////////////////////////////////////////////////////////////////////////////////////////////////////////
);



$used = array();
  
$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=ExpoPromoter_Opt", DB_USERNAME, DB_PASS);
$pdo->exec("SET NAMES UTF8");

if ($shift && $send_mode != "test") {
  $limits = intval($shift);
  $query = $data[$lang]['query'] . " LIMIT {$limits}";

  $stmt_recipients = $pdo->prepare($query);
  $stmt_recipients->execute();
  
  echo "** SUPPRESSING PREVIOUS EMAILS\n";
  
  while ($recipient = $stmt_recipients->fetch(PDO::FETCH_ASSOC)) {
    $recipient['email'] = trim($recipient['email']);
  
    if (isset($used[$recipient['email']])) continue;
  
    echo ".";
    //echo $recipient['email'] . " "; 
    $totalSkipped++;
    $used[$recipient['email']] = 1;
  }
  
  echo "\n\n--------------------------------------------------\n";
}

//$limits = (($part - 1) * $portion) . ', ' . $portion;
$limits = intval($shift) . ', ' . intval($portion);
$query = $data[$lang]['query'] . ($send_mode == "test" ? " LIMIT 5" : " LIMIT {$limits}");

echo "\n$query\n";

$stmt_recipients = $pdo->prepare($query);
$stmt_recipients->execute();

echo "** " . ($send_mode == "test" ? "TEST MODE" : ($send_mode == "fake" ? "FAKE MODE" : "REAL MODE!")) . "\n";

while ($recipient = $stmt_recipients->fetch(PDO::FETCH_ASSOC)) {
	$recipient['email'] = trim($recipient['email']);

  if (isset($used[$recipient['email']])) continue;

  echo "\nRecipient: ";
	sendMsg($recipient, $lang, $send_mode);
  $used[$recipient['email']] = 1;
}

echo "\n--------------\nTOTAL EMAILS SENT: " . $total . "\n";
notifyMe("\n--------------\nTOTAL EMAILS SENT: " . $total . "\n" . date("Y-m-d H:i:s"), "Maillist $lang has been sent!");


function sendMsg($edata, $lang, $send_mode) {
  global $emailValObj, $data, $total, $senders;
  
	if (!$emailValObj->isValid($edata['email']) || ($edata['email'] == 'verbitskaya@expoua.com')) {
		return 0;
	}

  if ($send_mode != "fake") {
    $mailObj = new Zend_Mail("utf-8");
    
    $from = $senders[$data[$lang]['sender']];
    
    $mailObj->setFrom($from['email'], $data[$lang]['sender']);
  
    $msg = str_replace('{greeting}', ($edata['name'] ? $data[$lang]['greeting1'] : $data[$lang]['greeting2']), $data[$lang]['message']);
    $msg = str_replace('{contact_person}', $edata['name'], $msg);
    $msg = str_replace('{feedback_email}', $from['email'], $msg);
    $msg = str_replace('{feedback_skype}', $from['skype'], $msg);
    $msg = str_replace('{feedback_icq}',   $from['icq'],   $msg);
    $msg = str_replace('{feedback_name}',  $data[$lang]['sender'], $msg);
    
    $mailObj->setBodyHtml($msg);
    $mailObj->setBodyText(strip_tags($msg));
    $mailObj->setSubject("Бесплатный билет на выставку MegaShow (Китай)");
    
    if ($send_mode != "test") {
      $mailObj->addTo($edata['email'], $edata['name']);
    } else {
      $mailObj->addTo("eugene.ivashin@expopromogroup.com", $edata['name']);
      // $mailObj->addTo("mariya.shtanhrat@expopromogroup.com", $edata['name']);
      // $mailObj->addTo("cathy.lee@kenfair.hk", $edata['name']);
    }
  
    $mailObj->send();
  }

	echo $edata['email'] . " "; $total++;

  if ($send_mode == "real") sleep(1);
  
  return 1;
}


function notifyMe($msg, $subj) {
  $mailObj = new Zend_Mail("utf-8");
    
  $mailObj->setBodyHtml($msg);
  $mailObj->setBodyText(strip_tags($msg));
  $mailObj->setSubject($subj);
    
  $mailObj->addTo('eugene.ivashin@expopromogroup.com', 'Eugene Ivashin');
  $mailObj->send();
}
