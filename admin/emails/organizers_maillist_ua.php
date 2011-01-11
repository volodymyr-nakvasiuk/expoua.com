<?php
////////////////////////////////////////////////
$lang = 'D9';

// D0 - TEST
// D1 - СНГ

// D3 - Африка, Европа
// D4 - Америка
// D5 - Азия

// D9 - Украина
// D10 - Россия

$send_mode = 
//  "fake";
//  "test";
  "real";

////////////////////////////////////////////////

require_once("../application/globalConfig.php");
require_once("Zend/Loader.php");

require_once("Zend/Mail.php");
require_once("Zend/Mail/Transport/Smtp.php");
require_once('Zend/Validate/EmailAddress.php');

$mailTransport = new Zend_Mail_Transport_Smtp('91.217.254.43');
Zend_Mail::setDefaultTransport($mailTransport);

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

 'ExpoPromoter team' => array(
   'email' => "info@expoinformer.com",
   'skype' => "",
   'icq'   => "",
 ),

 'Команда проекта ExpoPromoter' => array(
   'email' => "info@expoinformer.com",
   'skype' => "",
   'icq'   => "",
 ),

	'Команда проекта ExpoUa.com' => array(
		'email' => "info@expoua.com",
		'skype' => "",
		'icq'   => "",
	),

	'Команда проекта ExpoTop.ru' => array(
		'email' => "info@expotop.ru",
		'skype' => "",
		'icq'   => "",
	),
);


//////////////////////////////////////////////////////////////////////////////////////////////////////////

$template = array();

$template['ru'] = "<HTML><BODY>
<p>{greeting},</p>
<p>ExpoPromoter желает Вам веселого Рождества и счастливого Нового Года!</p>
<p>По ссылке <a href=\"http://expopromoter.org/report/\">http://expopromoter.org/ru/report/</a> Вы сможете ознакомиться с полезной статистикой по Выставочной Индустрии мира, основанной на данных сети Expopromoter, а также наши теплые поздравления и другие приятные подарки.</p>
<p>Хотели бы пригласить Вас на запущенный сегодня «ПАРТНЕРСКИЙ ЦЕНТР» сети ExpoPromoter - <a href=\"http://expopromoter.org/\">www.ExpoPromoter.org</a>, где Вы сможете получить саму свежую информацию о наших новых сервисах!</p>
<br>
С наилучшими пожеланиями,<br>
Команда ExpoPromoter<br>
<a href=\"http://expopromoter.org/\">www.ExpoPromoter.org</a><br>
<br>
Suite 351, 10 Great Russell St., London WC1B3BQ, UK<br>
tel: + 44 20 7043 5170,  fax: + 44 20 7043 5180, mob: +38 066 964 16 64<br>
</BODY></HTML>";

//////////////////////////////////////////////////////////////////////////////////////////////////////////

$template['en'] = "<HTML><BODY>
<p>{greeting},</p>
<p>ExpoPromoter wishes you a very Merry Christmas and a Happy New Year!</p>
<p>By link <a href=\"http://expopromoter.org/report/\">http://expopromoter.org/report/</a> you may find useful statistics on the Exhibition Industry of the world, based on data of ExpoPromoter network, our warmest congratulations and other pleasant gifts.</p>
<p>We want to invite you to visit PARTNER CENTER of ExpoPromoter network launched today - <a href=\"http://expopromoter.org/\">www.ExpoPromoter.org</a>, there you may see the most updated information about our new services!</p>
<br>
Best regards,<br>
The ExpoPromoter team<br>
<a href=\"http://expopromoter.org/\">www.ExpoPromoter.org</a><br>
<br>
Suite 351, 10 Great Russell St., London WC1B3BQ, UK<br>
tel: + 44 20 7043 5170,  fax: + 44 20 7043 5180, mob: +38 066 964 16 64<br>
</BODY></HTML>";

$template['expoua'] = '<HTML><BODY><img width="640" height="512" src="file://attached_image" /></BODY></HTML>';

$data = array(
	//////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	'D0' => array(
		'query'      =>
		"SELECT '50623' AS id, 'ExpoUa.test' AS organizer_name, 'djtheme@gmail.com' AS email, 'Украина' AS country;",

		'sender'  => 'Команда проекта ExpoPromoter',

		'greeting1'  => 'Уважаемый (ая) {contact_person}',
		'greeting2'  => 'Уважаемый коллектив компании {organizer_name}',

		'attachment'  => dirname(__FILE__).'/1.jpg',

//    'subjPrefix' => 'Информация для компании ',
		'message'    => $template['expoua'],
		'subjPrefix' => 'С наступающим Новым 2011 годом!',
	),

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

    'sender'  => 'Команда проекта ExpoPromoter',
    
    'greeting1'  => 'Уважаемый (ая) {contact_person}',
    'greeting2'  => 'Уважаемый коллектив компании {organizer_name}',

//    'attachment'  => dirname(__FILE__).'/card_ru.jpg',

//    'subjPrefix' => 'Информация для компании ',
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

    'sender'  => 'ExpoPromoter team',
    
    'greeting1'  => 'Dear {contact_person}',
    'greeting2'  => 'Hello',
//    'subjPrefix' => 'Attn: ',

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
      WHERE cnd.name IN('Бенин', 'Ботсвана', 'Габон', 'Гамбия', 'Гана', 'Египет', 'Замбия', 'Зимбабве', 'Камбоджа', 'Камерун', 'Катар', 'Кения', 'Кот-д\'Ивуар', 'Либерия', 'Мавритания', 'Мадагаскар', 'Малави', 'Марокко', 'Мозамбик', 'Намибия', 'Науру', 'Непал', 'Нигерия', 'Нигер', 'Республика Конго', 'Руанда', 'Свазиленд', 'Сейшелы', 'Сенегал', 'Соломоновы острова', 'Сомали', 'Судан', 'Суринам', 'Сьерра-Леоне', 'Танзания', 'Того', 'Токелау', 'Тонга', 'Тунис', 'Уганда', 'Центрально-Африканская Республика', 'Чад', 'Эритрея', 'Эфиопия', 'ЮАР', 'Испания', 'Португалия', 'Австрия', 'Албания', 'Андорра', 'Бельгия', 'Болгария', 'Босния и Герцеговина', 'Великобритания', 'Венгрия', 'Германия', 'Греция', 'Дания', 'Ирландия', 'Исландия', 'Испания', 'Италия', 'Лихтенштейн', 'Люксембург', 'Мальта', 'Македония', 'Монако', 'Нидерланды', 'Норвегия', 'Польша', 'Португалия', 'Румыния', 'Сан-Марино', 'Словакия', 'Словения', 'Финляндия', 'Франция', 'Хорватия', 'Черногория', 'Чехия', 'Швейцария', 'Швеция', 'Кипр')
        AND od.email != ''",

    'sender'  => 'ExpoPromoter team',
    
    'greeting1'  => 'Dear {contact_person}',
    'greeting2'  => 'Dear team of {organizer_name}',
//    'subjPrefix' => 'Attn: ',
//    'attachment'  => dirname(__FILE__).'/card_en.jpg',

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

    'sender'  => 'ExpoPromoter team',
    
    'greeting1'  => 'Dear {contact_person}',
    'greeting2'  => 'Dear team of {organizer_name}',
//    'subjPrefix' => 'Attn: ',

//    'attachment'  => dirname(__FILE__).'/card_en.jpg',

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
      WHERE cnd.name IN('Австралия', 'Афганистан', 'Бахрейн', 'Вануату', 'Индонезия', 'Иордания', 'Ирак', 'Иран', 'Йемен', 'Китай', 'Корея', 'Кувейт', 'Лаос', 'Ливан', 'Ливия', 'Малайзия', 'Мали', 'Монголия', 'Мьянма', 'Непал', 'Новая Зеландия', 'ОАЭ', 'Оман', 'Пакистан', 'Палау', 'Папуа Новая Гвинея', 'Сербия', 'Сингапур', 'Сирия', 'Таиланд', 'Тайвань', 'Фиджи', 'Филиппины', 'Южная Корея', 'Япония', 'Китай', 'Турция', 'Индия', 'Бангладеш', 'Вьетнам')
        AND od.email != ''",

    'sender'  => 'ExpoPromoter team',
    
    'greeting1'  => 'Dear {contact_person}',
    'greeting2'  => 'Hello',

//    'attachment'  => dirname(__FILE__).'/card_en.jpg',

//    'subjPrefix' => 'Attn: ',
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

    'sender'  => 'ExpoPromoter team',
    
    'greeting1'  => 'Dear {contact_person}',
    'greeting2'  => 'Hello',

    'subjPrefix' => 'Attn: ',
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

    'sender'  => 'ExpoPromoter team',
    
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
    
    'subjPrefix' => 'Информация для компании ',

    'message'    => $template['ru']
  ),


  //////////////////////////////////////////////////////////////////////////////////////////////////////////

	'D9' => array(
		'query'      =>
		"SELECT DISTINCT
        o.id, od.name AS organizer_name, od.cont_pers_name AS contact_person, od.email, cnd.name AS country
      FROM organizers o
        JOIN organizers_data od ON od.id = o.id AND od.languages_id = 1
        JOIN location_cities c ON c.id = o.cities_id
        JOIN location_countries cn ON cn.id = c.countries_id
        JOIN location_countries_data cnd ON cnd.id = cn.id AND cnd.languages_id = 1
      WHERE cnd.name = 'Украина'
        AND od.email != ''",

		'sender'  => 'Команда проекта ExpoUa.com',

		'greeting1'  => 'Уважаемый (ая) {contact_person}',
		'greeting2'  => 'Уважаемый коллектив компании {organizer_name}',

		'attachment'  => dirname(__FILE__).'/1.jpg',

//    'subjPrefix' => 'Информация для компании ',
		'message'    => $template['expoua'],
		'subjPrefix' => 'С наступающим Новым 2011 годом!',
	),


	//////////////////////////////////////////////////////////////////////////////////////////////////////////

	'D10' => array(
		'query'      =>
		"SELECT DISTINCT
        o.id, od.name AS organizer_name, od.cont_pers_name AS contact_person, od.email, cnd.name AS country
      FROM organizers o
        JOIN organizers_data od ON od.id = o.id AND od.languages_id = 1
        JOIN location_cities c ON c.id = o.cities_id
        JOIN location_countries cn ON cn.id = c.countries_id
        JOIN location_countries_data cnd ON cnd.id = cn.id AND cnd.languages_id = 1
      WHERE cnd.name = 'Россия'
        AND od.email != ''",

		'sender'  => 'Команда проекта ExpoTop.ru',

		'greeting1'  => 'Уважаемый (ая) {contact_person}',
		'greeting2'  => 'Уважаемый коллектив компании {organizer_name}',

		'attachment'  => dirname(__FILE__).'/2.jpg',

//    'subjPrefix' => 'Информация для компании ',
		'message'    => $template['expoua'],
		'subjPrefix' => 'С наступающим Новым 2011 годом!',
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

$stmt_organizers = $pdo->prepare($data[$lang]['query'] . ($send_mode == "test" ? " LIMIT 2" : ''));
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
    $msg = str_replace('{organizer_name}', $edata['organizer_name'], $msg);
    $msg = str_replace('{orgid}', $edata['id'], $msg);
    $msg = str_replace('{feedback_email}', $from['email'], $msg);
    $msg = str_replace('{feedback_skype}', $from['skype'], $msg);
    $msg = str_replace('{feedback_icq}',   $from['icq'],   $msg);
    $msg = str_replace('{feedback_name}',  $data[$lang]['sender'], $msg);
    $subjPrefix = isset($data[$lang]['subjPrefix']) ? $data[$lang]['subjPrefix'] : '';


    if(($attachmentFileName = $data[$lang]['attachment']) && is_readable($attachmentFileName))
     {
      $mailObj->setType(Zend_Mime::MULTIPART_RELATED);
      $at = $mailObj->createAttachment(file_get_contents($attachmentFileName));
      $at->type = 'image/jpeg';
      $at->disposition = Zend_Mime::DISPOSITION_INLINE;
      $at->encoding = Zend_Mime::ENCODING_BASE64;
      $at->id = 'cid_' . md5_file($attachmentFileName);
      $msg = str_replace('file://attached_image',  'cid:' . $at->id,  $msg);
     }

    $mailObj->setBodyHtml($msg);
    $mailObj->setBodyText(strip_tags($msg));
    $mailObj->setSubject($subjPrefix /*. $edata['organizer_name']*/);
    
    if ($send_mode != "test") {
      $mailObj->addTo($edata['email'], $edata['contact_person']);
    } else {
      $mailObj->addTo("yury.timoschuk@gmail.com", $edata['contact_person']);
//      $mailObj->addTo("kateryna.stepaniuk@expopromoter.com", $edata['contact_person']);
//      $mailObj->addTo("gennadiy.netyaga@expopromogroup.com", $edata['contact_person']);
    }

    try
      {
       $mailObj->send();
      }
    catch(Exception $e)
      { echo "Error: {$e->getMessage()}"; }

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
    
  $mailObj->addTo('yury.timoschuk@gmail.com', 'Karas');
  $mailObj->send();
}
