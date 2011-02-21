<?php
class ArOn_Zend_Captcha_Image extends Zend_Captcha_Word {
	/**
	 * URL for accessing images
	 *
	 * @var string
	 */
	protected $_imgUrl = "/images/captcha/";

	/**
	 * Image's alt tag content
	 *
	 * @var string
	 */
	protected $_imgAlt = "";

	/**
	 * Image width
	 *
	 * @var int
	 */
	protected $_width = 200;

	/**
	 * Image height
	 *
	 * @var int
	 */
	protected $_height = 50;

	/**
	 * Font size
	 *
	 * @var int
	 */
	protected $_fsize = 24;

	/**
	 * Image font file
	 *
	 * @var string
	 */
	protected $_font;

	/**
	 * Image to use as starting point
	 * Default is blank image. If ptovided, should be PNG image.
	 *
	 * @var string
	 */
	protected $_startImage;

	/**
	 * Image for button to reload captcha
	 * Default no reload button.
	 *
	 * @var string
	 */
	protected $_reloadImg = false;

	/**
	 * Number of noise dots on image
	 * Used twice - before and after transform
	 *
	 * @var int
	 */
	protected $_dotNoiseLevel = 100;

	/**
	 * Number of noise lines on image
	 * Used twice - before and after transform
	 *
	 * @var int
	 */
	protected $_lineNoiseLevel = 5;

	/**
	 * @return string
	 */
	public function getImgAlt ()
	{
		return $this->_imgAlt;
	}

	/**
	 * @return string
	 */
	public function getStartImage ()
	{
		return $this->_startImage;
	}

	/**
	 * @return string
	 */
	public function getReloadImg ()
	{
		return $this->_reloadImg;
	}

	/**
	 * @return int
	 */
	public function getDotNoiseLevel ()
	{
		return $this->_dotNoiseLevel;
	}

	/**
	 * @return int
	 */
	public function getLineNoiseLevel ()
	{
		return $this->_lineNoiseLevel;
	}

	/**
	 * Get font to use when generating captcha
	 *
	 * @return string
	 */
	public function getFont()
	{
		return $this->_font;
	}

	/**
	 * Get font size
	 *
	 * @return int
	 */
	public function getFontSize()
	{
		return $this->_fsize;
	}

	/**
	 * Get captcha image height
	 *
	 * @return int
	 */
	public function getHeight()
	{
		return $this->_height;
	}

	/**
	 * Get captcha image base URL
	 *
	 * @return string
	 */
	public function getImgUrl()
	{
		return $this->_imgUrl;
	}

	/**
	 * Get captcha image width
	 *
	 * @return int
	 */
	public function getWidth()
	{
		return $this->_width;
	}

	/**
	 * @param string $startImage
	 */
	public function setStartImage ($startImage)
	{
		$this->_startImage = $startImage;
		return $this;
	}

	/**
	 * @param string $reloadImg
	 */
	public function setReloadImg ($reloadImg)
	{
		$this->_reloadImg = $reloadImg;
		return $this;
	}

	/**
	 * @param int $dotNoiseLevel
	 */
	public function setDotNoiseLevel ($dotNoiseLevel)
	{
		$this->_dotNoiseLevel = $dotNoiseLevel;
		return $this;
	}

	/**
	 * @param int $lineNoiseLevel
	 */
	public function setLineNoiseLevel ($lineNoiseLevel)
	{
		$this->_lineNoiseLevel = $lineNoiseLevel;
		return $this;
	}

	/**
	 * Set captcha font
	 *
	 * @param  string $font
	 * @return Zend_Captcha_Image
	 */
	public function setFont($font)
	{
		$this->_font = $font;
		return $this;
	}

	/**
	 * Set captcha font size
	 *
	 * @param  int $fsize
	 * @return Zend_Captcha_Image
	 */
	public function setFontSize($fsize)
	{
		$this->_fsize = $fsize;
		return $this;
	}

	/**
	 * Set captcha image height
	 *
	 * @param  int $height
	 * @return Zend_Captcha_Image
	 */
	public function setHeight($height)
	{
		$this->_height = $height;
		return $this;
	}

	/**
	 * Set captcha image base URL
	 *
	 * @param  string $imgUrl
	 * @return Zend_Captcha_Image
	 */
	public function setImgUrl($imgUrl)
	{
		$this->_imgUrl = rtrim($imgUrl, "/\\") . '/';
		return $this;
	}

	/**
	 * @param string $imgAlt
	 */
	public function setImgAlt ($imgAlt)
	{
		$this->_imgAlt = $imgAlt;
		return $this;
	}

	/**
	 * Set captcha image width
	 *
	 * @param  int $width
	 * @return Zend_Captcha_Image
	 */
	public function setWidth($width)
	{
		$this->_width = $width;
		return $this;
	}

	/**
	 * Generate random frequency
	 *
	 * @return float
	 */
	protected function _randomFreq()
	{
		return mt_rand(700000, 1000000) / 15000000;
	}

	/**
	 * Generate random phase
	 *
	 * @return float
	 */
	protected function _randomPhase()
	{
		// random phase from 0 to pi
		return mt_rand(0, 3141592) / 1000000;
	}

	/**
	 * Generate random character size
	 *
	 * @return int
	 */
	protected function _randomSize()
	{
		return mt_rand(300, 700) / 100;
	}

	/**
	 * Generate image captcha by ID
	 *
	 * @param string $id Captcha ID
	 */
	public function generateImage($id)
	{
		$this->_setId($id);
		$word = $this->_generateWord();
		$this->_setWord($word);
		return $this->_generateImage($id, $word);
	}

	/**
	 * Generate image captcha
	 *
	 * Override this function if you want different image generator
	 * Wave transform from http://www.captcha.ru/captchas/multiwave/
	 *
	 * @param string $id Captcha ID
	 * @param string $word Captcha word
	 */
	protected function _generateImage($id, $word)
	{
		if (!extension_loaded("gd")) {
			//require_once'Zend/Captcha/Exception.php';
			throw new Zend_Captcha_Exception("Image CAPTCHA requires GD extension");
		}

		if (!function_exists("imagepng")) {
			//require_once'Zend/Captcha/Exception.php';
			throw new Zend_Captcha_Exception("Image CAPTCHA requires PNG support");
		}

		if (!function_exists("imageftbbox")) {
			//require_once'Zend/Captcha/Exception.php';
			throw new Zend_Captcha_Exception("Image CAPTCHA requires FT fonts support");
		}

		$font = $this->getFont();

		if (empty($font)) {
			//require_once'Zend/Captcha/Exception.php';
			throw new Zend_Captcha_Exception("Image CAPTCHA requires font");
		}

		$w     = $this->getWidth();
		$h     = $this->getHeight();
		$fsize = $this->getFontSize();

		if(empty($this->_startImage)) {
			$img        = imagecreatetruecolor($w, $h);
			$bg_color   = imagecolorallocate($img, 255, 255, 255);
			imagefilledrectangle($img, 0, 0, $w-1, $h-1, $bg_color);
		} else {
			$img = imagecreatefrompng($this->_startImage);
			if(!$img) {
				//require_once'Zend/Captcha/Exception.php';
				throw new Zend_Captcha_Exception("Can not load start image");
			}
			$w = imagesx($img);
			$h = imagesy($img);
		}

		$img2        = imagecreatetruecolor($w, $h);
		$bg_color   = imagecolorallocate($img2, 255, 255, 255);
		imagefilledrectangle($img2, 0, 0, $w-1, $h-1, $bg_color);
		imagecolortransparent($img2, $bg_color);
		$textBox = imageftbbox($fsize, 0, $font, $word);
		$textBoxW = $textBox[4] - $textBox[0];
		$textBoxH = $textBox[7] - $textBox[3];
		$x = ($w>$textBoxW)?($w - $textBoxW)/2:0;
		$y = ($h>$textBoxH)?($h - $textBoxH)/2:0;

		$string_array = preg_split('//', $word, -1, PREG_SPLIT_NO_EMPTY);
		foreach ($string_array as $el) {
			$textColor = imagecolorallocate($img2, rand(0, 150), rand(0, 150), rand(0, 150));
			imagefttext($img2, $fsize, 0, $x, $y, $textColor, $font, $el);
			$textBox = imageftbbox($fsize, 0, $font, $el);
			$textBoxW = $textBox[4] - $textBox[0];
			$x += $textBoxW;
		}

		$freq1 = $this->_randomFreq();
		$freq2 = $this->_randomFreq();
		$freq3 = $this->_randomFreq();
		$freq4 = $this->_randomFreq();
		$phase1 = $this->_randomPhase();
		$phase2 = $this->_randomPhase();
		$phase3 = $this->_randomPhase();
		$phase4 = $this->_randomPhase();
		$size1 = $this->_randomSize();
		$size2 = $this->_randomSize();

		$img3        = imagecreatetruecolor($w, $h);
		$bg_color   = imagecolorallocate($img3, 255, 255, 255);
		imagefilledrectangle($img3, 0, 0, $w-1, $h-1, $bg_color);
		imagecolortransparent($img3, $bg_color);
		for($x = 0; $x < $w; $x++){
			for($y = 0; $y < $h; $y++){
				$sx = $x + ( sin($x * $freq1 + $phase1) + sin($y * $freq2 + $phase2) ) * $size1;
				$sy = $y + ( sin($x * $freq3 + $phase3) + sin($y * $freq4 + $phase4) ) * $size2;

				if($sx < 0 || $sy < 0 || $sx > $w - 1 || $sy > $h - 1){
					$color = imagecolorallocate($img2, 255, 255, 255);
				}else{
					$color = imagecolorat($img2, $sx, $sy);
				}

				imagesetpixel($img3, $x, $y, $color);
			}
		}
		imagedestroy($img2);

		imagecopymerge($img, $img3, 0, 0, 0, 0, $w, $h, 80);
		imagedestroy($img3);

		for ($i=0; $i<$this->_dotNoiseLevel; $i++) {
			$lineColor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
			imagefilledellipse($img, mt_rand(0,$w-1), mt_rand(0,$h-1), 2, 2, $lineColor);
		}
		for($i=0; $i<$this->_lineNoiseLevel; $i++) {
			$lineColor = imagecolorallocate($img, rand(0, 255), rand(0, 255), rand(0, 255));
			imageline($img, mt_rand(0,$w-1), mt_rand(0,$h-1), mt_rand(0,$w-1), mt_rand(0,$h-1), $lineColor);
		}

		header("Content-Type: image/png");
		header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate");
		imagepng($img);
		imagedestroy($img);
	}

	/**
	 * Display the captcha
	 *
	 * @param Zend_View_Interface $view
	 * @param mixed $element
	 * @return string
	 */
	public function render(Zend_View_Interface $view = null, $element = null)
	{
		$id = $this->getId();
		$imgUrl = $this->getImgUrl() . '?id=' . $id;
		$reload = $this->getReloadImg();
		return '<img class="captcha" id="captcha_'.$id.'" width="'.$this->getWidth().'" height="'.$this->getHeight().'" alt="'.$this->getImgAlt().'" src="' . $imgUrl . '&r='.rand(1,1000000).'"/>'
				.($reload?'<img class="captcha_reload" onClick="javascript:document.getElementById(\'captcha_'.$id.'\').src = \''.$imgUrl.'&r=\'+Math.floor(Math.random()*1000000);" alt="" src="' . $reload . '"/>':'')
				;
	}
}

