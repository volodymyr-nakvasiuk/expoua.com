<?PHP
//error_reporting(0);
//ini_set('display_errors', 1);

//Настройки базы данных
define("DB_HOST", "localhost:3307");
define("DB_USERNAME", "root");
define("DB_PASS", "1");
define("DB_NAME", "ExpoPromoter_cms");
define("DB_EX_NAME", "ExpoPromoter_Opt");
//define("DB_ADAPTER", "MYSQLI");
define("DB_ADAPTER", "PDO_MYSQL");

define("PROPRIETARY_BOOKING_HOST", "http://booking.expopromoter.com");
define("TICKETS_HOST", "http://tickets.expopromoter.com");
define("TOP_DOMAIN", "expopromoter.com");

define("SUPPORT_EMAIL", "support@expopromoter.com");
define("BUYER_NOTIFICATION_EMAIL", "buyer@expopromoter.com");

//URL сервера арбитра для синхронизации
define("SYNC_SERVER_PATH", "http://tendertour.com/sync.php");
//Используется как базовый путь для передачи URL изображений для скачивания при синхронизации
define("SYNC_LOCAL_DATA_PATH", "http://admin.expoua.com/data");

// -- Пути --
define("PATH_ROOT", 'D:/WebServer/home/expoua-my.com/admin');

//Путь к Zend Framework
define("PATH_LIBRARY_ZEND", PATH_ROOT . "/../library");

//Путь к Smarty
define("PATH_LIBRARY_SMARTY", PATH_ROOT . "/../library/Smarty");

define("PATH_APPLICATION", PATH_ROOT . "/application");
define("PATH_CONTROLLERS", PATH_APPLICATION . "/controllers");
define("PATH_MODELS", PATH_APPLICATION . "/models");
define("PATH_VIEWS", PATH_APPLICATION . "/views");
define("PATH_DATAPROVIDERS", PATH_APPLICATION . "/dataproviders");

//Настройки шаблонов админки
define("PATH_TEMPLATES_COMPILED", PATH_APPLICATION . "/templates_c");
define("PATH_TEMPLATES", PATH_APPLICATION . "/templates");
define("PATH_LANGUAGES", PATH_APPLICATION . "/languages");

//Путь к скриптам относительно корня веб-сервера
define("PATH_BASE", "/");

//Пути к пользовательским данным в файловой системе
define("PATH_FRONTEND_DATA", PATH_ROOT . "/htdocs/data");
define("PATH_FRONTEND_DATA_DOWNLOAD", PATH_FRONTEND_DATA . "/download");
define("PATH_FRONTEND_DATA_FLASH", PATH_FRONTEND_DATA . "/flash");
define("PATH_FRONTEND_DATA_IMAGES", PATH_FRONTEND_DATA . "/images");
define("PATH_FRONTEND_DATA_GALLERIES", PATH_FRONTEND_DATA . "/galleries");

define("PATH_FRONTEND_DATA_EVENT_FILES", PATH_FRONTEND_DATA . "/events_attachments");

//Пути к пользовательским данным относительно корня веб-сервера
define("PATH_WEB_DATA_DOWNLOAD", PATH_BASE . "data/download/");
define("PATH_WEB_DATA_FLASH", PATH_BASE . "data/flash/");
define("PATH_WEB_DATA_IMAGES", PATH_BASE . "data/images/");
define("PATH_WEB_DATA_GALLERIES", PATH_BASE . "data/galleries/");

//Путь к классам веб-сервиса
define("PATH_WS_CLASSES", PATH_ROOT . "/../ws/Classes");

//Язык по-умолчанию
define("LANGUAGE_DEFAULT", "ru");

//Режим отладки
define("SYSTEM_DEBUG", false);

ini_set("include_path", "." . PATH_SEPARATOR . PATH_LIBRARY_ZEND);

//Устанавливаем временную зону и локаль
date_default_timezone_set('Europe/Kiev');
setlocale(LC_ALL, "ru_RU.UTF-8");

//Устанавливаем кодировку функций работы с мультибайтными кодировоками
mb_internal_encoding("UTF-8");
