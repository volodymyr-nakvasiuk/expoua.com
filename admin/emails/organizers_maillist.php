<?php
////////////////////////////////////////////////
$lang = 'D8';

$send_mode = 
//  "fake";
//  "test";
  "real";

////////////////////////////////////////////////



require_once("../application/globalConfig.php");
require_once("Zend/Loader.php");

require_once("Zend/Mail.php");
require_once('Zend/Validate/EmailAddress.php');

$emailValObj = new Zend_Validate_EmailAddress();
$mailObj = new Zend_Mail("utf-8");

$total = $totalOrganizers = 0;


$senders = array(
  'Анастасия Шумак' => array(
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
    'skype' => "mariya.shtanhrat",
    'icq'   => "554145334<br>\nMSN - mariya.shtanhrat@hotmail.com<br />\nhttp://www.linkedin.com/pub/mariya-shtanhrat/13/110/93b",
  ),

  'Геннадий Нетяга' => array(
    'email' => "gn@expopromogroup.com",
    'skype' => "",
    'icq'   => "",
  ),

);


//////////////////////////////////////////////////////////////////////////////////////////////////////////


$template['ru'] = "<HTML><BODY>
<p>{greeting}!</p>

<p>Уважаемые господа!</p>

<p>В связи с финансово-экономическим кризисом, который повлиял на развитие выставочной отрасли в Украине, владельцами компании ExpoPromoGroup принято решение о снижении стоимости всех услуг для украинских выставочных компаний на 2010 год.</p>

<p>• Размещение мероприятий в сети ExpoPromoter будет производится по стоимости 1 кв.м. выставочной площади на соответствующей выставке.</p>

<p>• Стоимость размещения рекламных блоков и рекламных приоритетных строк в календаре мероприятий на веб-порталеwww.expoua.com снижена до 100 дол. США за месяц размещения в соответствующей выставочной тематике.</p>

<p>• Предоставление нового сервиса «онлайн регистрация посетителей» для выставок 2009-2010 года, предусматривающего проведение дополнительных мероприятий с целью привлечения посетителей выставок (байерсая программа), на бесплатных условиях.</p>

<p>Для компаний, которые произвели предоплату за услуги в 2010 году будет произведен перерасчет и предоставлены дополнительные услуги на сумму, превышающую стоимость услуг утвержденных на 2010 год.</p>

<p>Желаю Вам успехов в бизнесе!</p>

<p>С уважением,<br />
Геннадий Нетяга<br />
--------------------<br />
Kind regards,<br />
{feedback_name}<br />
Chief executive<br />
mailto:gn@expopromogroup.com<br />
<br />
EXPOPROMOGROUP LTD<br />
www.expopromogroup.com<br />
Suite 12, 2nd Floor, Queens House,<br />
180 Tottenham Court Road, London W1T 7PD<br />
Tel + 44 20 3073 1067, Fax + 44 20 79071463<br />
<br />
EXPOPROMOGROUP Ukraine<br />
p.o.b. 5, Kyiv, 04070<br />
Tel/Fax: +380 44 2000391
</p>

</BODY></HTML>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////

$template['en'] = "<HTML><BODY>
<p>{greeting},</p>

<p>If you are interested in professional event visitors from the CIS countries, consider making use of our new service called ExpoPromoter buyer program!</p>

<p>We will run your trade show advertising campaign in the CIS region:</p>
<ul>
  <li>all information translation into Russian</li>
  <li>listing trade show information in ExpoPromoter network (events’ calendar located on 300 web sites)</li>
  <li>ad blocks placement in ExpoAdvert ad network</li>
  <li>contextual advertising placement for the Russian speaking audience</li>
  <li>ad blocks placement on the web sites of your trade show’s similar industries</li>
  <li>trade show information newsletter sending out to our database subscribers (more than 100 000 subscribers)</li>
  <li>trade show information newsletter sending out to the subscribers of our web partners (more than 700 000 subscribers)</li>
  <li>and even more</li>
</ul>

<p>You will be charged only for the actual results – for each buyer registered.
In case you are interested, I will be glad to provide you with more detailed ExpoPromoter buyer program information.</p>

<p>Our congratulations on the beginning of 2009 autumn trade show season!</p>

<p>Wishing you great success and new business partners!</p>


<p>
--<br />
Kind regards,<br />
{feedback_name}<br />
Regional Project Editor<br />
ExpoPromoter - trade shows and fairs database located on 262 web-portals<br />
<br />
EXPOPROMOGROUP LTD<br />
Suite 12, 2nd Floor, Queens House, 180<br />
Tottenham Court Road, London W1T 7PD<br />
Tel + 44 20 3073 1067, Fax + 44 20 79071463<br />
Skype - {feedback_skype}<br />
ICQ - {feedback_icq}<br />
</p>

</BODY></HTML>";




$data = array(
  //////////////////////////////////////////////////////////////////////////////////////////////////////////

  'D1' => array(
    'query'      => 
     "SELECT DISTINCT
        o.id, od.name AS organizer_name, od.cont_pers_name AS contact_person, od.email, cnd.name AS country 
      FROM organizers o
        JOIN organizers_data od ON od.id = o.id AND od.languages_id = 1
        JOIN location_cities c ON c.id = o.cities_id
        JOIN location_countries cn ON cn.id = c.countries_id
        JOIN location_countries_data cnd ON cnd.id = cn.id AND cnd.languages_id = 1
      WHERE cnd.name IN('Россия', 'Беларусь', 'Украина', 'Грузия', 'Киргизия', 'Азербайджан', 'Казахстан', 'Узбекистан', 'Туркменистан', 'Таджикистан', 'Молдова', 'Армения', 'Латвия', 'Литва', 'Эстония')
        AND od.email != ''",

    'sender'  => 'Анастасия Шумак',
    
    'greeting1'  => 'Здравствуйте, {contact_person}',
    'greeting2'  => 'Здравствуйте',

    'message'    => $template['ru']
  ),


  //////////////////////////////////////////////////////////////////////////////////////////////////////////

  'D2' => array(
    'query'      => 
     "SELECT DISTINCT
        o.id, od.name AS organizer_name, od.cont_pers_name AS contact_person, od.email, cnd.name AS country 
      FROM organizers o
        JOIN organizers_data od ON od.id = o.id AND od.languages_id = 2
        JOIN location_cities c ON c.id = o.cities_id
        JOIN location_countries cn ON cn.id = c.countries_id
        JOIN location_countries_data cnd ON cnd.id = cn.id AND cnd.languages_id = 1
      WHERE cnd.name IN('Китай', 'Турция', 'Индия', 'Бангладеш', 'Вьетнам')
        AND od.email != ''",

    'sender'  => 'Mariya Shtanhrat',
    
    'greeting1'  => 'Dear {contact_person}',
    'greeting2'  => 'Hello',

    'message'    => $template['en']
  ),




  //////////////////////////////////////////////////////////////////////////////////////////////////////////

  'D3' => array(
    'query'      => 
     "SELECT DISTINCT
        o.id, od.name AS organizer_name, od.cont_pers_name AS contact_person, od.email, cnd.name AS country 
      FROM organizers o
        JOIN organizers_data od ON od.id = o.id AND od.languages_id = 2
        JOIN location_cities c ON c.id = o.cities_id
        JOIN location_countries cn ON cn.id = c.countries_id
        JOIN location_countries_data cnd ON cnd.id = cn.id AND cnd.languages_id = 1
      WHERE cnd.name IN('Бенин', 'Ботсвана', 'Габон', 'Гамбия', 'Гана', 'Египет', 'Замбия', 'Зимбабве', 'Камбоджа', 'Камерун', 'Катар', 'Кения', 'Кот-д\'Ивуар', 'Либерия', 'Мавритания', 'Мадагаскар', 'Малави', 'Марокко', 'Мозамбик', 'Намибия', 'Науру', 'Непал', 'Нигерия', 'Нигер', 'Республика Конго', 'Руанда', 'Свазиленд', 'Сейшелы', 'Сенегал', 'Соломоновы острова', 'Сомали', 'Судан', 'Суринам', 'Сьерра-Леоне', 'Танзания', 'Того', 'Токелау', 'Тонга', 'Тунис', 'Уганда', 'Центрально-Африканская Республика', 'Чад', 'Эритрея', 'Эфиопия', 'ЮАР', 'Испания', 'Португалия')
        AND od.email != ''",

    'sender'  => 'Anastasiya Shumak',
    
    'greeting1'  => 'Dear {contact_person}',
    'greeting2'  => 'Hello',

    'message'    => $template['en']
  ),




  //////////////////////////////////////////////////////////////////////////////////////////////////////////

  'D4' => array(
    'query'      => 
     "SELECT DISTINCT
        o.id, od.name AS organizer_name, od.cont_pers_name AS contact_person, od.email, cnd.name AS country 
      FROM organizers o
        JOIN organizers_data od ON od.id = o.id AND od.languages_id = 2
        JOIN location_cities c ON c.id = o.cities_id
        JOIN location_countries cn ON cn.id = c.countries_id
        JOIN location_countries_data cnd ON cnd.id = cn.id AND cnd.languages_id = 1
      WHERE cnd.name IN('Антигуа и Барбуда', 'Аргентина', 'Багамские острова', 'Барбадос', 'Белиз', 'Боливия', 'Ботсвана', 'Бразилия', 'Вануату', 'Венесуэла', 'Виргинские острова', 'Гавайи', 'Гаити', 'Гайана', 'Гватемала', 'Гренада', 'Гуам', 'Доминика', 'Доминиканская республика', 'Канада', 'Колумбия', 'Коста-Рика', 'Куба', 'Маврикий', 'Мексика', 'Никарагуа', 'Панама', 'Парагвай', 'Перу', 'Пуэрто-Рико', 'Сальвадор', 'Самоа Американское', 'Самоа Западное', 'США', 'Тринидад и Тобаго', 'Уругвай', 'Французская Гвиана', 'Французская Полинезия', 'Чили', 'Эквадор', 'Экваториальная Гвинея', 'Ямайка')
        AND od.email != ''",

    'sender'  => 'Anastasiya Shumak',
    
    'greeting1'  => 'Dear {contact_person}',
    'greeting2'  => 'Hello',

    'message'    => $template['en']
  ),





  //////////////////////////////////////////////////////////////////////////////////////////////////////////

  'D5' => array(
    'query'      => 
     "SELECT DISTINCT
        o.id, od.name AS organizer_name, od.cont_pers_name AS contact_person, od.email, cnd.name AS country 
      FROM organizers o
        JOIN organizers_data od ON od.id = o.id AND od.languages_id = 2
        JOIN location_cities c ON c.id = o.cities_id
        JOIN location_countries cn ON cn.id = c.countries_id
        JOIN location_countries_data cnd ON cnd.id = cn.id AND cnd.languages_id = 1
      WHERE cnd.name IN('Австралия', 'Афганистан', 'Бахрейн', 'Вануату', 'Индонезия', 'Иордания', 'Ирак', 'Иран', 'Йемен', 'Корея', 'Кувейт', 'Лаос', 'Ливан', 'Ливия', 'Малайзия', 'Мали', 'Монголия', 'Мьянма', 'Непал', 'Новая Зеландия', 'ОАЭ', 'Оман', 'Пакистан', 'Палау', 'Папуа Новая Гвинея', 'Сербия', 'Сингапур', 'Сирия', 'Таиланд', 'Тайвань', 'Фиджи', 'Филиппины', 'Южная Корея', 'Япония')
        AND od.email != ''",

    'sender'  => 'Anastasiya Shumak',
    
    'greeting1'  => 'Dear {contact_person}',
    'greeting2'  => 'Hello',

    'message'    => $template['en']
  ),





  //////////////////////////////////////////////////////////////////////////////////////////////////////////

  'D6' => array(
    'query'      => 
     "SELECT DISTINCT
        o.id, od.name AS organizer_name, od.cont_pers_name AS contact_person, od.email, cnd.name AS country 
      FROM organizers o
        JOIN organizers_data od ON od.id = o.id AND od.languages_id = 2
        JOIN location_cities c ON c.id = o.cities_id
        JOIN location_countries cn ON cn.id = c.countries_id
        JOIN location_countries_data cnd ON cnd.id = cn.id AND cnd.languages_id = 1
      WHERE cnd.name NOT IN('Россия', 'Беларусь', 'Украина', 'Грузия', 'Киргизия', 'Азербайджан', 'Казахстан', 'Узбекистан', 'Туркменистан', 'Таджикистан', 'Молдова', 'Армения', 'Латвия', 'Литва', 'Эстония', 'Китай', 'Турция', 'Индия', 'Бангладеш', 'Вьетнам', 'Бенин', 'Ботсвана', 'Габон', 'Гамбия', 'Гана', 'Египет', 'Замбия', 'Зимбабве', 'Камбоджа', 'Камерун', 'Катар', 'Кения', 'Кот-д\'Ивуар', 'Либерия', 'Мавритания', 'Мадагаскар', 'Малави', 'Марокко', 'Мозамбик', 'Намибия', 'Науру', 'Непал', 'Нигерия', 'Нигер', 'Республика Конго', 'Руанда', 'Свазиленд', 'Сейшелы', 'Сенегал', 'Соломоновы острова', 'Сомали', 'Судан', 'Суринам', 'Сьерра-Леоне', 'Танзания', 'Того', 'Токелау', 'Тонга', 'Тунис', 'Уганда', 'Центрально-Африканская Республика', 'Чад', 'Эритрея', 'Эфиопия', 'ЮАР', 'Испания', 'Португалия', 'Антигуа и Барбуда', 'Аргентина', 'Багамские острова', 'Барбадос', 'Белиз', 'Боливия', 'Ботсвана', 'Бразилия', 'Вануату', 'Венесуэла', 'Виргинские острова', 'Гавайи', 'Гаити', 'Гайана', 'Гватемала', 'Гренада', 'Гуам', 'Доминика', 'Доминиканская республика', 'Канада', 'Колумбия', 'Коста-Рика', 'Куба', 'Маврикий', 'Мексика', 'Никарагуа', 'Панама', 'Парагвай', 'Перу', 'Пуэрто-Рико', 'Сальвадор', 'Самоа Американское', 'Самоа Западное', 'США', 'Тринидад и Тобаго', 'Уругвай', 'Французская Гвиана', 'Французская Полинезия', 'Чили', 'Эквадор', 'Экваториальная Гвинея', 'Ямайка', 'Австралия', 'Афганистан', 'Бахрейн', 'Вануату', 'Индонезия', 'Иордания', 'Ирак', 'Иран', 'Йемен', 'Корея', 'Кувейт', 'Лаос', 'Ливан', 'Ливия', 'Малайзия', 'Мали', 'Монголия', 'Мьянма', 'Непал', 'Новая Зеландия', 'ОАЭ', 'Оман', 'Пакистан', 'Палау', 'Папуа Новая Гвинея', 'Сербия', 'Сингапур', 'Сирия', 'Таиланд', 'Тайвань', 'Фиджи', 'Филиппины', 'Южная Корея', 'Япония')
        AND od.email != ''",

    'sender'  => 'Mariya Shtanhrat',
    
    'greeting1'  => 'Dear {contact_person}',
    'greeting2'  => 'Hello',

    'message'    => $template['en']
  ),

  //////////////////////////////////////////////////////////////////////////////////////////////////////////

  'D7' => array(
    'query'      => 
     "SELECT DISTINCT
        o.id, od.name AS organizer_name, od.cont_pers_name AS contact_person, od.email, cnd.name AS country 
      FROM organizers o
        JOIN organizers_data od ON od.id = o.id AND od.languages_id = 2
        JOIN location_cities c ON c.id = o.cities_id
        JOIN location_countries cn ON cn.id = c.countries_id
        JOIN location_countries_data cnd ON cnd.id = cn.id AND cnd.languages_id = 1
      WHERE cnd.name IN('Китай')
        AND od.email != ''",

    'sender'  => 'Mariya Shtanhrat',
    
    'greeting1'  => 'Dear {contact_person}',
    'greeting2'  => 'Hello',

    'message'    => $template['en']
  ),

  //////////////////////////////////////////////////////////////////////////////////////////////////////////


  'D8' => array(
    'query'      => 
     "SELECT DISTINCT
        o.id, od.name AS organizer_name, od.cont_pers_name AS contact_person, od.email, cnd.name AS country 
      FROM organizers o
        JOIN organizers_data od ON od.id = o.id AND od.languages_id = 1
        JOIN location_cities c ON c.id = o.cities_id
        JOIN location_countries cn ON cn.id = c.countries_id
        JOIN location_countries_data cnd ON cnd.id = cn.id AND cnd.languages_id = 1
      WHERE cnd.name IN('Украина')
        AND od.email != ''",

    'sender'  => 'Геннадий Нетяга',
    
    'greeting1'  => 'Здравствуйте, {contact_person}',
    'greeting2'  => 'Здравствуйте',
    
    'subjPrefix' => 'Для компании ', 

    'message'    => $template['ru']
  ),


  //////////////////////////////////////////////////////////////////////////////////////////////////////////

);




$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=ExpoPromoter_Opt", DB_USERNAME, DB_PASS);
$pdo->exec("SET NAMES UTF8");

$subquery1 = 
 "SELECT DISTINCT email_requests
  FROM brands 
  WHERE organizers_id = :orgid
    AND dead = 0
    AND email_requests != :email
  ";

$subquery2 = 
 "SELECT DISTINCT email
  FROM users_operators 
  WHERE organizers_id = :orgid
    AND email != :email
  ";

$stmt_organizers = $pdo->prepare($data[$lang]['query'] . ($send_mode == "test" ? " LIMIT 5" : ''));
$stmt_brands     = $pdo->prepare($subquery1);
$stmt_users      = $pdo->prepare($subquery2);

$stmt_organizers->execute();

echo "** " . ($send_mode == "test" ? "TEST MODE" : ($send_mode == "fake" ? "FAKE MODE" : "REAL MODE!")) . "\n";

while ($organizer = $stmt_organizers->fetch(PDO::FETCH_ASSOC)) {
	$organizer['email'] = trim($organizer['email']);

  $totalOrganizers++;

  echo "\nOrganizer: ";
	sendMsg($organizer, $lang, $send_mode);

  $used = array();
  
  $stmt_brands->execute(array('orgid' => $organizer['id'], 'email' => $organizer['email']));
  while ($brand = $stmt_brands->fetch(PDO::FETCH_ASSOC)) {
    $brand['email'] = trim($brand['email_requests']);
    $brand['organizer_name'] = $organizer['organizer_name']; // . ' (' . $brand['email'] . ')';
    $brand['contact_person'] = '';
    $brand['id'] = $organizer['id'];
    
    $used[] = $brand['email'];
    
    echo "\n  Brand: ";
    sendMsg($brand, $lang, $send_mode);
  }

  $stmt_users->execute(array('orgid' => $organizer['id'], 'email' => $organizer['email']));
  while ($user = $stmt_users->fetch(PDO::FETCH_ASSOC)) {
    $brand['email'] = trim($brand['email']);
    $brand['organizer_name'] = $organizer['organizer_name']; // . ' (' . $brand['email'] . ')';
    $brand['contact_person'] = !empty($brand['name_fio']) ? trim($brand['name_fio']) : '';
    $brand['id'] = $organizer['id'];
    
    if (in_array($brand['email'], $used)) continue;
    
    echo "\n  User:  ";
    sendMsg($brand, $lang, $send_mode);
  }
}

echo "\n--------------\nTOTAL EMAILS SENT: " . $total . "\nTOTAL ORGANIZERS: " . $totalOrganizers . "\n";
notifyMe("\n--------------\nTOTAL EMAILS SENT: " . $total . "\nTOTAL ORGANIZERS: " . $totalOrganizers . "\n" . date("Y-m-d H:i:s"), "Maillist $lang has been sent!");


function sendMsg($edata, $lang, $send_mode) {
  global $emailValObj, $data, $total, $senders;
  
	if (!$emailValObj->isValid($edata['email'])) {
		return 0;
	}

  if ($send_mode != "fake") {
    $mailObj = new Zend_Mail("utf-8");
    
    $from = $senders[$data[$lang]['sender']];
    
    $mailObj->setFrom($from['email'], $data[$lang]['sender']);
  
    $msg = str_replace('{greeting}', ($edata['contact_person'] ? $data[$lang]['greeting1'] : $data[$lang]['greeting2']), $data[$lang]['message']);
    $msg = str_replace('{contact_person}', $edata['contact_person'], $msg);
    $msg = str_replace('{orgid}', $edata['id'], $msg);
    $msg = str_replace('{feedback_email}', $from['email'], $msg);
    $msg = str_replace('{feedback_skype}', $from['skype'], $msg);
    $msg = str_replace('{feedback_icq}',   $from['icq'],   $msg);
    $msg = str_replace('{feedback_name}',  $data[$lang]['sender'], $msg);
    $subjPrefix = isset($data[$lang]['subjPrefix']) ? $data[$lang]['subjPrefix'] : '';
    
    $mailObj->setBodyHtml($msg);
    $mailObj->setBodyText(strip_tags($msg));
    $mailObj->setSubject($subjPrefix . $edata['organizer_name']);
    
    if ($send_mode != "test") {
      $mailObj->addTo($edata['email'], $edata['contact_person']);
    } else {
      $mailObj->addTo("eugene.ivashin@expopromogroup.com", $edata['contact_person']);
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
