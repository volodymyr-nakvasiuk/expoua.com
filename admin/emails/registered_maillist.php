<?php
////////////////////////////////////////////////
$lang = 'ru';
$testmode = false;
$send_in_real = true;

$limit_start = 15000;
$limit_count = 10000; // of 42920

////////////////////////////////////////////////



require_once("../application/globalConfig.php");
require_once("Zend/Loader.php");

require_once("Zend/Mail.php");
require_once('Zend/Validate/EmailAddress.php');

$emailValObj = new Zend_Validate_EmailAddress();
$mailObj = new Zend_Mail("utf-8");

$total = $totalUsers = 0;

$usedAddresses = array();

$data = array(

  //////////////////////////////////////////////////////////////////////////////////////////////////////////

  'ru' => array(
    'query'      =>   "SELECT * FROM `email_users` WHERE disabled = 0",

    'from_name'  => 'Веб-портал www.ExpoUA.com',

    'from_email' => 'info@expoua.com',
    
    'greeting1'  => 'Здравствуйте {contact_person}!',

    'greeting2'  => 'Здравствуйте!',

    'message'    => '<HTML><BODY>
<p>Здравствуйте {contact_person}!</p>

<p>Приглашаем Вас на ближайшие выставки, которые предоставляют сервис "Бесплатные пригласительные / онлайн регистрация":</p>

<p>Выставки в Китае:</p>

<p>09.12 – 11.12.2009 "Automechanika Shanghai"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/70516/">http://www.expoua.com/Event/lang/ru/id/70516/</a></p>


<p>Выставки в России:</p>

<p>24.11. - 27.11.2009 "PharmTech"<br />
<a href="http://www.pharmtech-expo.ru/ru/visitors/registration/?expopromo">http://www.pharmtech-expo.ru/ru/visitors/registration/?expopromo</a></p>


<p>Выставки в Украине:</p>

<p>20.10 – 23.10.2009 "Дом, дача, огород"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/70369/">http://www.expoua.com/Event/lang/ru/id/70369/</a></p>

<p>21.10 – 23.10.2009 "EEBC. Telecom & Broadcasting"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/63804/">http://www.expoua.com/Event/lang/ru/id/63804/</a></p>

<p>21.10 – 23.10.2009 "Международный Черноморский Транспортный Форум"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/65444/">http://www.expoua.com/Event/lang/ru/id/65444/</a></p>

<p>21.10 – 24.10.2009 "Украинская мода"<br />
http://www.expoua.com/Event/lang/ru/id/64622/</p>

<p>22.10 – 24.10.2009 "Мебель. Строительство и Интерьер. Осень"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/68256/">http://www.expoua.com/Event/lang/ru/id/68256/</a></p>

<p>22.10 – 24.10.2009 "Строим свой дом"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/63439/">http://www.expoua.com/Event/lang/ru/id/63439/</a></p>

<p>23.10 – 24.10.2009 "Недвижимость за рубежом"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/67947/">http://www.expoua.com/Event/lang/ru/id/67947/</a></p>

<p>27.10 – 29.10.2009 "Decor & Gifts"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/67393/">http://www.expoua.com/Event/lang/ru/id/67393/</a></p>

<p>27.10 – 30.10.2009 "PRODMASH & PRODPACK"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/65660/">http://www.expoua.com/Event/lang/ru/id/65660/</a></p>

<p>27.10 – 30.10.2009 "World Food Ukraine / Весь Мир Питания Украина"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/62320/">http://www.expoua.com/Event/lang/ru/id/62320/</a></p>

<p>27.10 – 30.10.2009 "Plastex Украина"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/60326/">http://www.expoua.com/Event/lang/ru/id/60326/</a></p>

<p>27.10 – 29.10.2009 "КИП. Электроника. Энергетика"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/60805/">http://www.expoua.com/Event/lang/ru/id/60805/</a></p>

<p>27.10 – 29.10.2009  "Мебель. Интерьер и Дизайн"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/62781/">http://www.expoua.com/Event/lang/ru/id/62781/</a></p>

<p>28.10 – 28.10.2009 "Plastex Conference"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/53060/">http://www.expoua.com/Event/lang/ru/id/53060/</a></p>

<p>28.10 – 31.10.2009 "Салон меха и кожи. Чего хочет женщина?.."<br />
<a href="http://www.expoua.com/Event/lang/ru/id/69916/">http://www.expoua.com/Event/lang/ru/id/69916/</a></p>

<p>28.10 – 31.10.2009 "Мебель. Интерьер. Деревообработка"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/64624/">http://www.expoua.com/Event/lang/ru/id/64624/</a></p>

<p>29.10 – 31.10.2009 "ЗЕРКАЛО МОДЫ - Донецк"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/69845/">http://www.expoua.com/Event/lang/ru/id/69845/</a></p>

<p>29.10 – 31.10.2009 "Дентал-Украина"<br />
<a href="http://www.expoua.com/Event/lang/ru/id/63675/">http://www.expoua.com/Event/lang/ru/id/63675/</a></p>

<p>Ежедневно сервис подключают новые украинские и зарубежные выставки.<br />
Следите за изменениями на веб-портале www.ExpoUA.com</p>

<p>Желаем Вам удачных деловых контактов и продуктивной работы на выставках Украины и Мира!</p>

<p>С уважением,<br />
Команда веб-портала www.ExpoUA.com</p>

<p>Наши партнеры:<br />
Авиапартнер - компания Аэросвит (<a href="http://www.aerosvit.ua/user/book/booking_RU.html?expo">http://www.aerosvit.ua/user/book/booking_RU.html?expo</a>)<br />
Тур.партнер - туроператор ЦСТ Оптима (<a href="http://www.expoua.com/ServiceCompanies/lang/ru/servcomp/44306/">http://www.expoua.com/ServiceCompanies/lang/ru/servcomp/44306/</a>)</p>

<p>___________________________________________________<br />
Вы получили данное письмо так как являетесь зарегистрированным пользователем веб-портала www.ExpoUA.com или подписчиком на новости веб-портала.</p>
<p>В случае, если Вы не заинтересованы в получении данной информации воспользуйтесь сервисом "Подписка на новости" или произведите соответствующие настройки в Вашем профайле.</p>


</BODY></HTML>'
  ),

  //////////////////////////////////////////////////////////////////////////////////////////////////////////

);




$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=ExpoPromoter_Opt", DB_USERNAME, DB_PASS);
$pdo->exec("SET NAMES UTF8");


if ($testmode) {
  $limit_start = 0;
  $limit_count = 5;
}

$stmt_users = $pdo->prepare($data[$lang]['query'] . ($limit_start || $limit_count ? (' LIMIT ' . intval($limit_start) . ',' . intval($limit_count)) : ''));

$stmt_users->execute();

echo ($testmode ? "** TEST MODE" : "** REAL MODE!") . "\n";

$usedAddresses = array();

while ($user = $stmt_users->fetch(PDO::FETCH_ASSOC)) {
	$user['sending_email'] = trim($user['email']);

  $totalUsers++;

  echo "User: ";
	sendMsg($user, $lang, $send_in_real);
}

echo "\n--------------\nTOTAL EMAILS SENT: " . $total . "\nTOTAL USERS: " . $totalUsers . "\n";



function sendMsg ($edata, $lang, $doSend = true) {
  global $emailValObj, $data, $testmode, $total, $usedAddresses;
  
  $edata['sending_email'] = trim($edata['sending_email']);
  
	if (!$emailValObj->isValid($edata['sending_email'])) {
		echo $edata['sending_email'] . " is not a proper email address!\n";
		return 0;
	}
	
	if (isset($usedAddresses[$edata['sending_email']])) {
		echo $edata['sending_email'] . " address has been already used!!!!\n";
		return 0;
	} else {
    $usedAddresses[$edata['sending_email']] = $edata['sending_email'];
  }

  if ($doSend) {
    $mailObj = new Zend_Mail("UTF-8");
    
    $mailObj->setFrom($data[$lang]['from_email'], $data[$lang]['from_name']);
  
    $msg = str_replace('{greeting}', ($edata['contact_name'] ? $data[$lang]['greeting1'] : $data[$lang]['greeting2']), $data[$lang]['message']);
    $msg = str_replace('{contact_person}', $edata['contact_name'], $msg);
    $msg = str_replace('{feedback_email}', $data[$lang]['from_email'], $msg);
    $msg = str_replace('{feedback_name}',  $data[$lang]['from_name'], $msg);
    
    $mailObj->setBodyHtml($msg);
    $mailObj->setBodyText(strip_tags($msg));
    // $mailObj->setSubject('TenderTour для "' . $edata['company_name'] . '"');
    $mailObj->setSubject('Бесплатные билеты на выставки');
    
    if (!$testmode) {
      $mailObj->addTo($edata['sending_email'], $edata['contact_name']);
    } else {
      $mailObj->addTo("eugene.ivashin@expopromogroup.com", $edata['contact_name']);
      $mailObj->addTo("gn@expopromogroup.com", $edata['contact_name']);
      // $mailObj->addTo("netgen@mail.ru", $edata['contact_name']);
    }
  
    $mailObj->send();
  }

	echo $edata['sending_email'] . "\n"; $total++;

  if ($doSend) usleep(500000); // Задержка в полсекунды
  
  return 1;
}
