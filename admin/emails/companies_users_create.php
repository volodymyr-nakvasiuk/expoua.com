<?PHP
require_once("../application/globalConfig.php");
require_once("Zend/Loader.php");

require_once("Zend/Mail.php");
require_once('Zend/Validate/EmailAddress.php');

$emailValObj = new Zend_Validate_EmailAddress();
$mailObj = new Zend_Mail("utf-8");

$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=ExpoPromoter_Opt", DB_USERNAME, DB_PASS);
$pdo->exec("SET NAMES UTF8");

$query = "SELECT c.id, c.date_modify, cd.name, c.email, c.phone, c.fax, cd.address, lcd.name AS city, lcntd.name AS country
FROM companies AS c
JOIN companies_active AS ca ON (c.id=ca.id)
JOIN companies_data AS cd ON (c.id = cd.id)
LEFT JOIN location_cities AS lc ON (c.cities_id = lc.id)
LEFT JOIN location_cities_data AS lcd ON (lc.id = lcd.id AND lcd.languages_id=cd.languages_id)
LEFT JOIN location_countries_data AS lcntd ON (lc.countries_id = lcntd.id AND cd.languages_id=lcntd.languages_id)
LEFT JOIN users_companies AS uc ON (c.id=uc.companies_id)
WHERE ca.active=0 AND ca.languages_id=cd.languages_id AND cd.languages_id=1 AND uc.login IS NULL
AND c.date_modify BETWEEN '2008-08-29' AND '2008-09-01'";

$stmt_companies = $pdo->prepare($query);
$stmt_companies->execute();

$query = "INSERT INTO users_sites (active, login, passwd, email, companies_id, languages_id, sites_id) VALUES (1, :login, :passwd, :email, :company, 1, 40)";
$stmt_user = $pdo->prepare($query);

$query = "UPDATE companies_active SET active=1 WHERE languages_id=1 AND id=:id";
$stmt_activate = $pdo->prepare($query);

while ($company = $stmt_companies->fetch(PDO::FETCH_ASSOC)) {

	$company['email'] = trim($company['email']);

	if (!$emailValObj->isValid($company['email'])) {
		continue;
	}

	$login = "company" . $company['id'];
	$passwd = rand(1000, 9999);

	$message = '<HTML><BODY>
Компания "' . $company['name'] . '" была успешно добавлена в on-line каталог участников выставок на веб-портале <a href="http://www.expotop.ru/">ExpoTop.ru</a>.<BR>
Прямая ссылка на страницу с информацией о компании:
<a href="http://www.expotop.ru/Companies/lang/ru/id/' . $company['id'] . '/">http://www.expotop.ru/Companies/lang/ru/id/' . $company['id'] . '/</a>
<p>
Указанные контактные данные<br>
E-mail: ' . $company['email'] . '<BR>
Страна, Город: ' . $company['country'] . ', ' . $company['city'] . '<BR>
Почтовый адрес: ' . (empty($company['address']) ? "не указан":$company['address']) . '<BR>
Тел.: ' . (empty($company['phone']) ? "не указан":$company['phone']) . '<BR>
Факс: ' . (empty($company['fax']) ? "не указан":$company['fax']) . '<BR>
</p>
Добавить, отредактировать информацию о компании  Вы можете перейдя по ссылке<br>
<a href="http://www.expotop.ru/User/lang/ru/">http://www.expotop.ru/User/lang/ru/</a><br>
и войдя в систему используя Ваш логин и пароль.<br><br>
Логин: ' . $login . '<br>
Пароль: ' . $passwd . '<br>
<p>
С уважением,<br>
Анастасия Шумак<br>
редакторский отдел<br>
<a href="http://www.expotop.ru/">www.ExpoTop.ru</a> - 30 000 выставок России и мира<br>
----------------<br>
<small>Вы получили данное письмо, так как Вы или кто-то другой зарегистрировал компанию "' . $company['name'] . '" в on-line каталоге участников выставок  на сайте ExpoTop.ru  и указал для связи Ваш контактный имейл.<br>
В случае, если размещение Вашей компании произведено ошибочно или без Вашего согласия - сообщите нам и мы удалим информацию о Вашей компании из каталога.</small>
</p></BODY></HTML>';

	$mailObj = new Zend_Mail("utf-8");
	$mailObj->setFrom("info@expopromoter.com", "Expopromoter");
	$mailObj->setBodyHtml($message);
	$mailObj->setBodyText(strip_tags($message));
	$mailObj->setSubject('Ваша компания "' . $company['name'] . '" добавлена в каталог');
	$mailObj->addTo($company['email'], $company['name']);

	//$mailObj->addTo("dmitry.sinev@expopromogroup.com", $company['name']);
	//$mailObj->addCc("gennadiy.netyaga@expopromogroup.com");
	//$mailObj->addCc("michael.danilkovych@expopromogroup.com", $company['name']);

	$mailObj->send();

	echo $company['id'] . ": " . $company['email'] . "\t";

	sleep(1);

	$stmt_user->execute(array(':login' => $login, ':passwd' => $passwd, ':email' => $company['email'], ':company' => $company['id']));
	$stmt_activate->execute(array(':id' => $company['id']));
}