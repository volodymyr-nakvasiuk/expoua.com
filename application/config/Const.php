<?php
switch(APPLICATION_ENVIRONMENT){
	case 'production':
		define ( 'HOST_NAME', 'http://expoua.com' );
		define ( 'IMG_HOST_NAME', 'http://img1.expoua.com' );
		define ( 'STATIC_HOST_NAME', 'http://static.expoua.com' );
		define ( 'ADMIN_HOST_NAME', 'http://admin.expoua.com' );
		define ( 'COOKIE_HOST_NAME', 'expoua.com' );
		break;
	case 'development':
		define ( 'HOST_NAME', 'http://expoua-my.com' );
		define ( 'IMG_HOST_NAME', 'http://img1.expoua-my.com' );
		define ( 'STATIC_HOST_NAME', 'http://static.expoua-my.com' );
		define ( 'ADMIN_HOST_NAME', 'http://admin.expoua-my.com' );
		define ( 'COOKIE_HOST_NAME', 'expoua-my.com' );
		break;
	case 'test':
		define ( 'HOST_NAME', 'http://test.expoua.com' );
		define ( 'IMG_HOST_NAME', 'http://img1.test.expoua.com' );
		define ( 'STATIC_HOST_NAME', 'http://static.test.expoua.com' );
		define ( 'ADMIN_HOST_NAME', 'http://admin.test.expoua.com' );
		define ( 'COOKIE_HOST_NAME', 'test.expoua.com' );
		break;
	default:
		define ( 'HOST_NAME', 'http://expoua.com' );
		define ( 'IMG_HOST_NAME', 'http://img1.expoua.com' );
		define ( 'STATIC_HOST_NAME', 'http://static.expoua.com' );
		define ( 'ADMIN_HOST_NAME', 'http://admin.expoua.com' );
		define ( 'COOKIE_HOST_NAME', 'expoua.com' );
		break;
}

define ( 'DOCUMENT_ROOT' , ROOT_PATH.'/www');
define ( 'UPLOAD_ATTACH_FILE_TO_EMAIL_PATH' , DOCUMENT_ROOT.'/uploads/emails');
define ( 'APPLICATION_PATH', ROOT_PATH . '/application' );
define ( 'CACHE_ROOT', ROOT_PATH . '/data/cache' );
define ( 'LOG_ROOT', ROOT_PATH . '/data/log' );
define ( 'CACHE_FILE_PATH', CACHE_ROOT . '/file');
define ( 'CRUD_PATH', APPLICATION_PATH . '/models/Crud' );
define ( 'ARON_PATH', ROOT_PATH.'/library/ArOn' );
define ( 'UPLOAD_IMAGES_PATH', DOCUMENT_ROOT.'/uploads/images');
define ( 'TMP_UPLOAD_PATH', DOCUMENT_ROOT.'/uploads/tmp');
define ( 'UPLOAD_CMS_IMAGES_PATH', DOCUMENT_ROOT.'/cms/images');
define ( 'UPLOAD_CLIENT_IMAGES_PATH', DOCUMENT_ROOT.'/uploads/client/images');

define ( 'TAB_DEFAULT_ACTION', 'page');
define ( 'TAB_DEFAULT_ID', 1);
define ( 'DEFAULT_ROUTE', ':lang/:controller/:action/:id/:tab/:tab_action/:tab_id');
define ( 'SITE_ID', 37);
define ( 'PARTNER_ID', 536);
define ( 'PUBLISHER_ID', 2);
define ( 'COUNTRY_ID', 52);
define ( 'COUNTRY_ABBR', 'ukr');

define ( 'PATH_CLICKER', 'http://62.149.12.130/clicker/');
define ( 'PATH_DATA_BANNERS', 'http://62.149.12.130/bn/data/');
define ( 'PATH_DATA_PBLBANNERS', 'http://62.149.12.130/bn/pbldata/');
define ( 'PATH_DATA_PBLGAGBANNERS', 'http://62.149.12.130/bn/pblgdata/');

