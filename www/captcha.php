<?php
	if (isset($_GET['id'])){
		require_once '../init.php';

		Bootstrap::setupSelfConst();
		Bootstrap::setupPhpIni();
		Bootstrap::setupRegistry ();
		Bootstrap::setupTranslation();

		$startImagesDir = ROOT_PATH.'/data/captcha/150x80';
		$startImageName = rand(1, 29).'.png';

		$fontsDir = ROOT_PATH.'/data/fonts';
		$fontName = 'font'.rand(1, 24).'.ttf';

		Zend_Captcha_Word::$VN = Zend_Captcha_Word::$CN = range(0, 9);
		$captcha = new ArOn_Zend_Captcha_Image(array(
			'startImage'=> $startImagesDir.'/'.$startImageName,
			'width'     => 150,
			'height'    => 80,
			'fsize'     => 18,
			'font'      => $fontsDir.'/'.$fontName,
			'dotNoiseLevel' => 100,
			'lineNoiseLevel'=> 10,
			'timeout'   => 1800,
			'wordlen'   => 6,
		));

		$captcha->generateImage(intval($_GET['id']));
	}
