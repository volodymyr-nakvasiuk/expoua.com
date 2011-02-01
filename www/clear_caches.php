<?php
date_default_timezone_set('Europe/Kiev');
ini_set('display_errors',1);
error_reporting(E_ALL ^ E_NOTICE);
ini_set ( 'max_execution_time', 200000 );

$hacker = true;
if (isset($_GET['dev'])){
	if ($_GET['dev']=='on'){
		$hacker = false;
	}
}
if ($hacker){
	exit("<center><h1>You are Hacker!!!</h1><h2>Access denied.</h2></center>");
}

$cacheDir = $_SERVER['DOCUMENT_ROOT'].'/cache';

function rmDirRecursively($dir){
	$c = strlen($dir)-1;
	if ($dir{$c}=='/' || $dir{$c}=='\\') $dir = substr($dir, 0, -1);
	$result = false;
	if ($handle = opendir($dir)) {
		$result = true;
		while ($result && (false !== ($file = readdir($handle)))) {
			if ($file != "." && $file != "..") {
				$fileName = $dir.'/'.$file;
				if (is_file($fileName)){
					echo "Deleting FILE <b>".$fileName."</b><br/>\n";
					$result = @unlink($fileName);
				}
				elseif (is_dir($fileName)){
					$result = rmDirRecursively($fileName);
				}
				else{
					$result = false;
				}
			}
		}
		closedir($handle);
	}

	if ($result){
		echo "Deleting DIRECTORY <b>".$dir."</b><br/>\n";
		$result = @rmdir($dir);
	}
	return $result;
}

rmDirRecursively($cacheDir.'/html');
rmDirRecursively($cacheDir.'/js');
rmDirRecursively($cacheDir.'/css');