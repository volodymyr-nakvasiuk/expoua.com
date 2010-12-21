<?PHP
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
    array('language' => LANGUAGE_DEFAULT, 'controller' => 'sab_organizer_info', 'action' => 'whatis')
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
