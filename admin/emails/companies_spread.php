<?PHP
require_once("../application/globalConfig.php");
require_once("Zend/Loader.php");

require_once("Zend/Mail.php");
require_once('Zend/Validate/EmailAddress.php');

$emailValObj = new Zend_Validate_EmailAddress();
$mailObj = new Zend_Mail("utf-8");

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=ExpoPromoter_Opt", DB_USERNAME, DB_PASS);
$pdo->exec("SET NAMES UTF8");

$query = "SELECT c.id, c.date_modify, cd.name, c.email, c.phone, c.fax, cd.address, lcd.name AS city, lcntd.name AS country, uc.login, uc.passwd
FROM companies AS c
JOIN companies_active AS ca ON (c.id=ca.id)
JOIN companies_data AS cd ON (c.id = cd.id)
LEFT JOIN location_cities AS lc ON (c.cities_id = lc.id)
LEFT JOIN location_cities_data AS lcd ON (lc.id = lcd.id AND lcd.languages_id=cd.languages_id)
LEFT JOIN location_countries_data AS lcntd ON (lc.countries_id = lcntd.id AND cd.languages_id=lcntd.languages_id)
JOIN users_sites AS uc ON (c.id=uc.companies_id)
WHERE ca.active=1 AND ca.languages_id=cd.languages_id AND cd.languages_id=1 AND c.date_modify>DATE_SUB(NOW(), INTERVAL 6 MONTH)
LIMIT 3, 9999";

$stmt_companies = $pdo->prepare($query);
$stmt_companies->execute();

while ($company = $stmt_companies->fetch(PDO::FETCH_ASSOC)) {

	$company['email'] = trim($company['email']);

	if (!$emailValObj->isValid($company['email'])) {
		continue;
	}

	$message = '<HTML><BODY>
Уважаемые пользователи сервиса On-line Expo!

<p>Сообщаем Вам о том, что с 1 октября 2008 года вводится новый классификатор товаров и услуг.
Просим Вас проверить, и в случае необходимости изменить выбранные Вами категории товаров и услуг</p>

<p>Страница Вашей компании - <a href="http://www.expotop.ru/Companies/lang/ru/id/' . $company['id'] . '/">http://www.expotop.ru/Companies/lang/ru/id/' . $company['id'] . '/</a></p>

<p>Логин: ' . $company['login'] . '<br>
Пароль: ' . $company['passwd'] . '</p>

<p>Вы получили это письмо, так как являетесь зарегистрированным пользователем сервиса On-line Expo, выставочного портала <a href="http://www.expotop.ru/">www.ExpoTop.ru</a></p>

<p>С уважением,<br>
редакторский отдел сервиса<br>
On-line Expo<br>
<a href="mailto:info@expotop.ru">info@expotop.ru</a>
</BODY></HTML>';

	$mailObj = new Zend_Mail("utf-8");
	$mailObj->setFrom("info@expotop.ru", "ExpoTop.ru");
	$mailObj->setBodyHtml($message);
	$mailObj->setBodyText(strip_tags($message));
	$mailObj->setSubject('Новости On-Line Expo');
	$mailObj->addTo($company['email'], $company['name']);

	//$mailObj->addTo("dmitry.sinev@expopromogroup.com", $company['name']);
	//$mailObj->addCc("gennadiy.netyaga@expopromogroup.com");
	//$mailObj->addCc("michael.danilkovych@expopromogroup.com", $company['name']);

	$mailObj->send();

	echo $company['id'] . ": " . $company['email'] . "\t";

	sleep(1);
}