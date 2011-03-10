<?php
class Tools_Banner_Local{
	static function getBanner($b){
		switch ($b['media']) {
			case "flash":
				return self::getSwfBanner($b['width'], $b['height'], $b['file_name'], $b['id']);
				break;
			case "text":
				break;
			case "image":
				return self::getImageBanner($b['width'], $b['height'], $b['file_name'], $b['id']);
				break;
		}
		return '';
	}

	static function getSwfBanner($width, $height, $file, $id){
		$swfLoader = 'http://ws.expopromoter.com/flash/preloader_'.$width."x".$height.'.swf';
		$code = '';
		$code .= '<object';
		$code .= ' width="'.$width.'"';
		$code .= ' height="'.$height.'"';
		$code .= ' type="application/x-shockwave-flash"';
		$code .= ' data="'.$swfLoader.'"';
		$code .= ' style="visibility: visible; float:left; clear:none;">';
		$code .= '<param name="menu" value="menu"/>';
		$code .= '<param name="Movie" value="'.$swfLoader.'"/>';
		$code .= '<param name="Src" value="'.$swfLoader.'"/>';
		$code .= '<param name="quality" value="high"/>';
		$code .= '<param name="scale" value="noborder"/>';
		$code .= '<param name="wmode" value="transparent"/>';
		$code .= '<param name="oop" value="false"/>';
		$code .= '<param name="Flashvars" value="&amp;flashURL='.PATH_DATA_BANNERS.$file.'&amp;clickURL='.Tools_Banner::getClickerUrl($id).'">';
		$code .= '</object>';
		return $code;
	}

	static function getImageBanner($width, $height, $file, $id){
		$code = '';
		$code .= '<a href="'.Tools_Banner::getClickerUrl($id).'">';
		$code .= '<img style="';
		$code .= ' width: '.$width.'px;'; 
		//$code .= ' height: '.$height.'px;';
		$code .= '" src="'.PATH_DATA_BANNERS.$file.'" />';
		$code .= '</a>';
		return $code;
	}
}
