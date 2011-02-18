<?php
	require_once '../init.php';

	Bootstrap::setupSelfConst();
	Bootstrap::setupPhpIni();
	Bootstrap::setupRegistry ();
	Bootstrap::setupTranslation();

	$captcha = new ArOn_Zend_Captcha_Image(array(
		'reloadImg' => '/img/reload-captcha.png',
		'imgUrl'    => '/captcha.php',
		'imgAlt'    => 'Капча',
		'width'     => 150,
		'height'    => 80,
	));

	echo $captcha->render();
