<?php
////////////////////////////////////////////////
$lang = 'ru2';
$testmode = false;
$send_in_real = true;
////////////////////////////////////////////////



require_once("../application/globalConfig.php");
require_once("Zend/Loader.php");

require_once("Zend/Mail.php");
require_once('Zend/Validate/EmailAddress.php');

$emailValObj = new Zend_Validate_EmailAddress();
$mailObj = new Zend_Mail("utf-8");

$total = $totalPartners = 0;

$data = array(

  //////////////////////////////////////////////////////////////////////////////////////////////////////////

  'ru' => array(
    'query'      => 
       "SELECT 
          r.url, r.res_name, r.sys_email, u.name AS contact_person, u.email
        FROM resources AS r 
          LEFT JOIN users AS u ON u.id = r.users_id AND u.languages_id = 2
        WHERE (r.sys_email != '' OR u.email != '')",

    'from_name'  => 'Геннадий',

    'from_email' => 'support@expopromogroup.com',
    
    'greeting1'  => 'Здравствуйте {contact_person}!',

    'greeting2'  => 'Здравствуйте!',

    'message'    => "<HTML><BODY>
<p>Здравствуйте {contact_person}!</p>

<p>Ваш контакт указан в регистрационных данных веб-партнера сервиса ExpoPromoter, установившего раздел Выставки.  В случае, если Вы не установили раздел Выставки и не заинтересованы в будущем использовать сервис ExpoPromoter, сообщите нам об этом для удаления Вашей записи из базы веб-партнеров.</p>

<p>Сообщаем Вам об изменениях в веб-сервисе www.ExpoPromoter.com.</p>

<p>29.01.2009  начинается смена платформы веб-сервиса www.ExpoPromoter.com.  Вам предлагаются более удобные способы интеграции раздела Выставки на Вашем сайте, которыми Вы можете управлять в специальной Панели администрирования.  Теперь создание раздела Выставки занимает не более 2 часов рабочего времени.</p>

<p>Производится запуск рекламной сети ExpoAdvert в рамках раздела Выставки. Обратите внимание: Вы  получаете  доход от рекламы, размещаемой в разделе Выставки.  Контроль начисления Вашего дохода теперь производится в \"Панели администрирования Веб-партнера\" в разделе \"Ваш доход\".</p>

<p>Для получения доступа в новую Панель администрирования необходимо заново пройти регистрацию на www.ExpoPromoter.com. Для этого прошу Вас посетить страницу www.expopromoter.com/Static/lang/ru/page/registration/type/webPartner/ и дождаться активации.</p>

<p>До тех пор, пока Вы не перешли на новую платформу, проверьте, пожалуйста, Ваш раздел Выставки на наличие ошибок.  В случае, если Вы заметили какие-либо проблемы с отображением Вашего раздела Выставки, сообщите нам об этом для их устранения.</p>

<p>30.03.2009 будет произведен полный переход на новую платформу веб-сервиса ExpoPromoter, после чего выставочные разделы, не прошедшие повторную регистрацию, будут автоматически отключены. Прошу Вас уделить время и установить новую версию раздела Выставки. Наш технический отдел готов предоставить Вам полную поддержку в установке новой версии.</p>

<p>Приносим Вам свои извинения за неудобства, связанные с переходом на новую версию веб-сервиса ExpoPromoter.</p>

<p>
С уважением,<br>
{feedback_name}<br>
------------------------------<br>
Технический отдел<br>
<br>
EXPOPROMOGROUP LTD<br>
Suite 12, 2nd Floor, Queens House, 180<br>
Tottenham Court Road, London W1T 7PD<br>
Tel + 44 20 7907 1460, Fax + 44 20 79071463<br>
Mailto: <a href=\"mailto:{feedback_email}\">{feedback_email}</a>
</p>
</BODY></HTML>"
  ),

  //////////////////////////////////////////////////////////////////////////////////////////////////////////


  'ru2' => array(
    'query'      => 
       "SELECT 
          r.url, r.res_name, r.sys_email, u.name AS contact_person, u.email
        FROM resources AS r 
          LEFT JOIN users AS u ON u.id = r.users_id AND u.languages_id = 2
        WHERE (r.sys_email != '' OR u.email != '')",

    'from_name'  => 'Геннадий',

    'from_email' => 'support@expopromogroup.com',
    
    'greeting1'  => 'Здравствуйте {contact_person}!',

    'greeting2'  => 'Здравствуйте!',

    'message'    => "<HTML><BODY>
<p>Здравствуйте {contact_person}!</p>

<p>Сообщаем  Вам о том, что доход от участия в коммерческой программе www.ExpoPromoter.com (размещение рекламы в разделе Выставки) будет начисляться и выплачиваться только в новой версии экспорта календаря выставок.  Старая версия экспорта будет отключена 15 марта 2009 года.</p>

<p>В связи с этим, прошу Вас пройти регистрацию в новой версии веб-сервиса www.ExpoPromoter.com и установить обновленный раздел Выставки.</p>

<p>Наша служба поддержки всегда готова помочь Вам решить все технические вопросы и предоставить любые консультации.</p>

<p>Извините за причиненные неудобства, связанные с переходом на новую версию веб-сервиса ExpoPromoter.</p>

<p>
С уважением,<br>
{feedback_name}<br>
------------------------------<br>
Технический отдел<br>
<br>
EXPOPROMOGROUP LTD<br>
Suite 12, 2nd Floor, Queens House, 180<br>
Tottenham Court Road, London W1T 7PD<br>
Tel + 44 20 7907 1460, Fax + 44 20 79071463<br>
Mailto: <a href=\"mailto:{feedback_email}\">{feedback_email}</a>
</p>
</BODY></HTML>"
  ),

  //////////////////////////////////////////////////////////////////////////////////////////////////////////

);




$pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=webmaster", DB_USERNAME, DB_PASS);
$pdo->exec("SET NAMES UTF8");

/*
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
*/

$stmt_partners = $pdo->prepare($data[$lang]['query'] . ($testmode ? " LIMIT 5" : ''));
/*
$stmt_brands     = $pdo->prepare($subquery1);
$stmt_users      = $pdo->prepare($subquery2);
*/

$stmt_partners->execute();

echo ($testmode ? "** TEST MODE" : "** REAL MODE!") . "\n";

while ($partner = $stmt_partners->fetch(PDO::FETCH_ASSOC)) {
	$partner['sending_email'] = trim($partner['sys_email']);
	$partner['email'] = trim($partner['email']);

  $totalPartners++;

  echo "Web partner: ";
	sendMsg($partner, $lang, $send_in_real);

  if (!empty($partner['email']) && $partner['email'] !== $partner['sending_email']) {
    $partner['sending_email'] = trim($partner['email']);
  
    echo "             ";
    sendMsg($partner, $lang, $send_in_real);
  }
}

echo "\n--------------\nTOTAL EMAILS SENT: " . $total . "\nTOTAL PARTNERS: " . $totalPartners . "\n";



function sendMsg ($edata, $lang, $doSend = true) {
  global $emailValObj, $data, $testmode, $total;
  
	if (!$emailValObj->isValid($edata['sending_email'])) {
		echo $edata['sending_email'] . " is not a proper email address!\n";
		return 0;
	}

  if ($doSend) {
    $mailObj = new Zend_Mail("utf-8");
    
    $mailObj->setFrom($data[$lang]['from_email'], $data[$lang]['from_name']);
  
    $msg = str_replace('{greeting}', ($edata['contact_person'] ? $data[$lang]['greeting1'] : $data[$lang]['greeting2']), $data[$lang]['message']);
    $msg = str_replace('{contact_person}', $edata['contact_person'], $msg);
    $msg = str_replace('{feedback_email}', $data[$lang]['from_email'], $msg);
    $msg = str_replace('{feedback_name}',  $data[$lang]['from_name'], $msg);
    
    $mailObj->setBodyHtml($msg);
    $mailObj->setBodyText(strip_tags($msg));
    $mailObj->setSubject("Для веб партнера " . $edata['url']);
    
    if (!$testmode) {
      $mailObj->addTo($edata['sending_email'], $edata['contact_person']);
    } else {
      $mailObj->addTo("eugene.ivashin@expopromogroup.com", $edata['contact_person']);
    }
  
    $mailObj->send();
  }

	echo $edata['sending_email'] . "\n"; $total++;

  if ($doSend) sleep(1);
  
  return 1;
}
