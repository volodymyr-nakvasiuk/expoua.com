<?PHP
function getmicrotime() {
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}
$time_start = getmicrotime();
if(!function_exists('__autoload')){
	function __autoload($className) {
		require str_replace('_', '/', $className).'.php';
	}
}

//Описываем функцию-обработчик ошибок, которая будет их высылать на мыло
/*function myErrorHandler($errno, $errstr, $errfile, $errline) {
  $server_debug = var_export($_SERVER, true);
  $mess = "Error ($errno): $errstr . \nin the file $errfile, line $errline \n\n" . $server_debug;
  mail("eugene.ivashin@expopromogroup.com", 'Error in admin.expopromoter.com', $mess);
}
$old_error_handler = set_error_handler("myErrorHandler", E_ERROR | E_WARNING);    */

require_once("../application/globalConfig.php");
require_once("Zend/Loader.php");

Zend_Loader::loadClass("Zend_Controller_Front");
Zend_Loader::loadClass("Zend_Controller_Router_Rewrite");
Zend_Loader::loadClass("Zend_Debug");
Zend_Loader::loadClass("Zend_Registry");

Zend_Loader::loadClass("ControllerAbstract", PATH_CONTROLLERS);
Zend_Loader::loadClass("ModelAbstract", PATH_MODELS);
Zend_Loader::loadClass("ViewAbstract", PATH_VIEWS);

$router = new Zend_Controller_Router_Rewrite();

$router->removeDefaultRoutes();

$router->addRoute(
  "default",
  new Zend_Controller_Router_Route(
    ":language/:controller/:action/*",
    array('language' => LANGUAGE_DEFAULT, 'controller' => 'admin_index', 'action' => 'index')
  )
);

$router->addRoute(
  "operator",
  new Zend_Controller_Router_Route(
    "operator/:controller/:action/*",
    array('language' => LANGUAGE_DEFAULT, 'controller' => 'sab_operator_index', 'action' => 'index')
  )
);

$router->addRoute(
  "organizer",
  new Zend_Controller_Router_Route(
    "organizer/:controller/:action/*",
    array('language' => LANGUAGE_DEFAULT, 'controller' => 'sab_organizer_index', 'action' => 'index')
  )
);

$router->addRoute(
  "partner",
  new Zend_Controller_Router_Route(
    "partner/:controller/:action/*",
    array('language' => 'en', 'controller' => 'sab_partner_index', 'action' => 'index')
  )
);

$router->addRoute(
  "servcompany",
  new Zend_Controller_Router_Route(
    "sc/:controller/:action/*",
    array('language' => LANGUAGE_DEFAULT, 'controller' => 'sab_servcompany_index', 'action' => 'index')
  )
);

// $router->addRoute(
//   "company",
//   new Zend_Controller_Router_Route(
//     "company/:controller/:action/*",
//     array('language' => LANGUAGE_DEFAULT, 'controller' => 'sab_company_index', 'action' => 'index')
//   )
// );

$router->addRoute(
  "company",
  new Zend_Controller_Router_Route(
    "company/:language/:site/*",
    array('language' => LANGUAGE_DEFAULT, 'controller' => 'sab_company_index', 'action' => 'index', 'site' => 'expotop_ru')
  )
);


$controler = Zend_Controller_Front::getInstance();

$controler->setControllerDirectory(PATH_CONTROLLERS)->setRouter($router)->setBaseUrl(PATH_BASE);

$controler->throwExceptions(true);

$controler->setParam('noViewRenderer', true);

//Не передавать управление errorController в случае ошибки
$controler->setParam('noErrorHandler', true);

try {
  $response = $controler->dispatch();
} catch (Exception $e) {
  #echo "Exception!";
  echo $e->getMessage();
}

//echo number_format(memory_get_usage());

$time_end = getmicrotime();
$time = $time_end - $time_start;

//echo "\n". $time ."\n";