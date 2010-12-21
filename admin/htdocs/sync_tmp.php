<pre>
<?php
require_once(dirname(__FILE__) . "/../application/globalConfig.php");
require_once("Zend/Loader.php");

Zend_Loader::loadClass("Zend_Debug");
Zend_Loader::loadClass("Zend_Registry");

Zend_Loader::loadClass('DataProviderAbstract', PATH_DATAPROVIDERS);
DataProviderAbstract::init();

DataProviderAbstract::_DP("Sync_Writer")->writeToRefereeServer();
DataProviderAbstract::_DP("Sync_Reader")->readFromRefereeServer();