<?php
require_once("../application/globalConfig.php");
require_once("Zend/Loader.php");

Zend_Loader::loadClass("Zend_Session");
$sessObj = new Zend_Session_Namespace();

$captcha = $sessObj->captcha;

if (is_null($captcha)) {
	exit();
}

header("Content-Type: image/gif");
$string_array = preg_split('//', $captcha, -1, PREG_SPLIT_NO_EMPTY);

$im = imagecreatefromgif('images/img_checker_pattern.gif');

foreach ($string_array as $key => $el) {
	$textcolor = imagecolorallocate($im, rand(0, 150), rand(0, 150), rand(0, 150));
	imagestring($im, 5, 12 + $key*20 + rand(0,10)-5, 17 + rand(0,20)-10, $el, $textcolor);
}

$i=0;
do {
	$linecolor = imagecolorallocate($im, rand(0, 255), rand(0, 255), rand(0, 255));
	imageline($im, rand(0, 100), rand(0, 50), rand(0, 100), rand(0, 50), $linecolor);
} while ($i++<10);

imagegif($im);