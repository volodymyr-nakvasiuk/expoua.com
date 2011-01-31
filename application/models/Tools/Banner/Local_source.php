<?php
class Tools_Banner_Local_{ /*Dirty CODE!!!!*/
	
	protected $_host;
	protected $_redirect;
	protected $_width;
	protected $_height;
	protected $_data = false;
	protected $_allowExts;
	protected $_used;
	protected $_fileArray = array(
		'180x150' => array(),
		'240x350' => array('cars1.swf', 'cars2.swf', 'cars3.swf'),
		'300x250' => array(array('cars3.swf', 'catalog'), array('cars4.swf', 'sell'), array('cars5.swf', 'buy/old/marks')),
		'468x60' => array('ref.swf'),
		'480x90' => array('aviso.swf'),
		'728x90' => array('cars4.swf')
	);
	
	public function __construct($host, $redirect, $width, $height, $used = false, $allowExts = array ('jpg', 'jpeg', 'png', 'gif', 'swf'), $fname = false, $link = HOST_NAME){
		$this->_host = $host;
		$this->_redirect = $redirect;
		$this->_width  = intval($width);
		$this->_height = intval($height);
		if (!is_array($allowExts)) $allowExts = explode(',',$allowExts);
		$this->_allowExts = $allowExts;
		$this->_setUsed($used)->setData($fname, $link);
	}
	
	public function getData(){
		if ($this->_data == false) $this->setData();
		return $this->_data;
	}
	
	protected function setData($fname = false, $link = HOST_NAME){
		if (!$fname){
			//$path= ROOT_PATH.'/www/images/banners_'.$this->_width.'x'.$this->_height;
			//$dir = @opendir($path);
			/*while (false !== ($file = readdir($dir))){
				if ($file == '.' || $file == '..') continue;
				//if (is_file($path.'/'.$file)){
					$pathinfo = pathinfo($file);
					if (in_array($pathinfo['extension'], $this->_allowExts)) {
						$file_list[] = $file;
					}
				//}
			}*/
			$key = $this->_width.'x'.$this->_height;
			$file_list = isset($this->_fileArray[$key])?$this->_fileArray[$key]:array();
			
			list($fname, $link) = $this->getRandom($file_list);
			$link = HOST_NAME.'/'.$link;
			if($fname === false) {
				$this->_data = false;
				return $this;
			}
		}
		$pathinfo = pathinfo($fname);
		$this->_data = array();
		$this->_data['ext'] = $pathinfo['extension'];
		$this->_data['file'] = '/images/banners_'.$this->_width.'x'.$this->_height.'/'.$fname;
		$this->_data['fname'] = $fname;
		$this->_data['link'] = str_replace(array('//', 'http:/'), array('/', 'http://'), $link.'/');
		return $this;
	}
	
	public function getFileName(){
		$data = $this->getData();
		return $data['file'];
	}
	
	public function getFName(){
		$data = $this->getData();
		return $data['fname'];
	}
	
	public function getWidth(){
		return $this->_width;
	}
	
	public function getHeight(){
		return $this->_height;
	}
	
	public function getJavascriptCode(){
		$data = $this->getData();
		
		$code  = '';
		$code .= '<iframe src=\'';
		$code .= $this->_host;
		$code .= '/banner/';
		$code .= '?width='.$this->_width."&height=".$this->_height."&img=".$data['fname']."&lnk=".$data['link'];
		$code .= '\' frameborder=0 vspace=0 hspace=0 " + "';
		$code .= ' width='.$this->_width;
		$code .= ' height='.$this->_height;
		$code .= ' marginwidth=0 marginheight=0 scrolling=no>';
		$code .= ' </iframe>';
		
		return $code;
	}
	
	public function getBanner(){
		$width = $this->getWidth();
		$height = $this->getHeight();
		
		$data = $this->getData();
		$html = '<div class="banner_'.$width.'x'.$height.'">';
		if($data !== false)
			if ($data['ext'] == 'swf'){
				$html .= $this->getSwfBanner($data);
			}else{
				$html .= $this->getImageBanner($data);
			}
		$html .= '</div>';
		return $html;
	}
	
	public function getSwfBanner($data){
		$width = $this->getWidth();
		$height = $this->getHeight();
		
		$code = '';
		$code .= '<object';
		$code .= ' width="'.$width.'"';
		$code .= ' height="'.$height.'"';
		$code .= ' type="application/x-shockwave-flash"';
		$code .= ' data="'.$data['file'].'"';
		$code .= ' style="visibility: visible; float:left; clear:none;">';
		$code .= '<param name="menu" value="menu"/>';
		$code .= '<param name="Movie" value="'.$data['file'].'"/>';
		$code .= '<param name="Src" value="'.$data['file'].'"/>';
		$code .= '<param name="quality" value="high"/>';
		$code .= '<param name="scale" value="noborder"/>';
		$code .= '<param name="wmode" value="transparent"/>';
		$code .= '<param name="oop" value="false"/>';
		$code .= '<param name="Flashvars" value="&amp;link1='.($data['link']?$data['link']:HOST_NAME).'">';
		$code .= '</object>';
		return $code;
	}
	
	public function getImageBanner($data){
		$width = $this->getWidth();
		$height = $this->getHeight();
		
		$code = '';
		$code .= '<a href="'.$this->_redirect.'">';
		$code .= '<img style="';
		$code .= ' width: '.$width.'px;'; 
		$code .= ' height: '.$height.'px;"';
		$code .= ' src="'.$data['file'].'" />';
		$code .= '</a>';
		return $code;
	}
	
	protected function getRandom(array $list){
		$links = array();
		foreach ($list as $key=>&$banner){
			if (is_array($banner) && isset($banner[1])){
				$links[$key] = $banner[1];
				$banner = $banner[0];
			}
			else {
				$links[$key] = '/';
			}
		}
		if(is_array($this->_used) && !empty($this->_used)){
			$list = array_diff($list,$this->_used);
		}
		if(empty($list)) return array(false, false);
		$index = array_rand($list);
		return array($list[$index], $links[$index]);
	}
	
	protected function _setUsed($list){
		if(empty($list)) return $this;
		$this->_used = array();
		if (!is_array($list)) $list = array($list);
		foreach($list as $file){
			$this->_used[] = basename($file);
		}
		return $this;
	}
}
