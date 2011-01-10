<?php
class Tools_View{

	static function getTransliteration($str){
		$ruLetters = array(
			'а'=>'a',
			'б'=>'b',
			'в'=>'v',
			'г'=>'g',
			'д'=>'d',
			'е'=>'e',
			'ё'=>'e',
			'ж'=>'zh',
			'з'=>'z',
			'и'=>'i',
			'й'=>'j',
			'к'=>'k',
			'л'=>'l',
			'м'=>'m',
			'н'=>'n',
			'о'=>'o',
			'п'=>'p',
			'р'=>'r',
			'с'=>'s',
			'т'=>'t',
			'у'=>'u',
			'ф'=>'f',
			'х'=>'h',
			'ц'=>'c',
			'ч'=>'ch',
			'ш'=>'sh',
			'щ'=>'shch',
			'ъ'=>'\'',
			'ы'=>'y',
			'ь'=>'\'',
			'э'=>'eh',
			'ю'=>'ju',
			'я'=>'ja',
		);
		return str_replace(array_keys($ruLetters), array_values($ruLetters), $str);
	}

	static function getUrlAlias($name, $transliteration=false){
		$md5 = md5(date('Y-m-d-').$name);
		if ($transliteration) $name = self::getTransliteration(mb_strtolower($name,'UTF-8'));
		$name = preg_replace('/[^a-zA-Z]+/','-',$name);
		$name = str_replace('-',' ',$name);
		$name = trim($name);
		$name = str_replace(' ','-',$name);
		while (strpos($name,'--')!==false) $name = str_replace('--','-',$name);
		if (!$name) $name = $md5;
		return strtolower($name);
	}

	static function convertAlias2ClassName($alias){
		$alias = explode('-', $alias);
		foreach($alias as &$part){
			$part = mb_ucfirst($part,'UTF-8');
		}
		return implode('', $alias);
	}
	
	static function parse_str($urlParamStr){
		$paramArr = explode('&', $urlParamStr);
		$return = array();
		foreach ($paramArr as $param) {
			$tmp = explode('=', $param);
			if ($tmp[0])
				$return[$tmp[0]] = $tmp[1];
		}
		return $return;
	}
	
	/*
	 * function setUrlParam sets parameters values in URL
	 * $url - URL to set parameters in
	 * $paramName - array of parameters names
	 *              if one parameter to set $paramName can be string
	 * $paramValue - array of parameters values
	 *               if one parameter to set $paramValue can be string
	 * $paramName and $paramValue must be same size arrays!
	 * 
	 * if not set $paramValue - $paramName must be
	 * array of names and values: array(name1=>value1, name2=>value2)
	 */
	static function setUrlParam($url, $paramName, $paramValue = null, $urlDecode = false) {
		if (!is_array($paramName)){
			$paramName = array($paramName);
		}
		if ($paramValue !== null){
			if (!is_array($paramValue)){
				$paramValue = array($paramValue);
			}
			if (($paramsArray = array_combine($paramName, $paramValue)) === false){
				return $url;
			}
		}
		else {
			$paramsArray = $paramName;
		}
		$paramsArray['car'] = isset($paramsArray['car'])?$paramsArray['car']:null;
		$parse_url = parse_url($url);
		$url = $parse_url['scheme']?($parse_url['scheme'].'://'):''.$parse_url['host'].$parse_url['path'];
		$parse_str = self::parse_str($parse_url['query']);
		$parse_str = array_merge($parse_str, $paramsArray);
		$query = '';
		if ($query = http_build_query($parse_str)){
			$url .= '?'.$query;
		}
		if ($urlDecode) $url = urldecode($url);
		return $url;
	}

	static function parseUrlByTemplate($url, $template){
		$paramsArray = array();
		$parse_url = parse_url($url);
		$urlParts = explode('/', trim($parse_url['path'], '/'));
		$templateParts = explode('/', $template);
		foreach($templateParts as $index=>$tPart){
			if ($tPart{0}==':'){
				if ($urlParts[$index]){
					$paramsArray[substr($tPart, 1)] = $urlParts[$index];
				}
				else {
					break;
				}
			}
			elseif (!($tPart===$urlParts[$index])) {
				break;
			}
		}
		return $paramsArray;
	}

	static function setUrlByTemplate($url, $paramsArray){
		$template = $url['template'];
		list($url, $query) = explode('?', $url['url'].'?');

		$urlParamsValues = self::parseUrlByTemplate($url, $template);
		$urlParamsValues = array_merge($urlParamsValues, $paramsArray);
		$templateParts = explode('/', $template);
		$url = '/';
		foreach($templateParts as $tPart){
			if ($tPart{0}==':'){
				$key = substr($tPart, 1);
				if (array_key_exists($key, $urlParamsValues)){
					$url .= $urlParamsValues[$key];
				}
				else {
					break;
				}
			}
			else {
				$url .= $tPart;
			}
			$url .= '/';
		}
		if ($query){
			$url .= '?'.$query;
		}
		return $url;
	}
	
	static function clearUrlParam($url, $clearArray) {
		$parse_url = parse_url($url);
		$url = $parse_url['scheme']?($parse_url['scheme'].'://'):''.$parse_url['host'].$parse_url['path'];
		$parse_str = self::parse_str($parse_url['query']);
		foreach ($clearArray as $paramName){
			unset($parse_str[$paramName]);
		}
		if ($query = http_build_query($parse_str)){
			$url .= '?'.$query;
		}
		return $url;
	}

	static function clearAllUrlParams($url) {
		$parse_url = parse_url($url);
		return $parse_url['scheme']?($parse_url['scheme'].'://'):''.$parse_url['host'].$parse_url['path'];
	}
	
	static function getCanonicalLink($url, $params = null){
		if(!$params || !is_array($params)) return $url;
		
		$parse_url = parse_url($url);
		$url = $parse_url['scheme'].'://'.$parse_url['host'].$parse_url['path'];
		$parse_str = self::parse_str($parse_url['query']);
		foreach($params as $paramName => $config){
			if ($config !== false){
				if (!is_array($config)) $config = array('default'=>$config);
				if (isset($config['allowed']) && !is_array($config['allowed'])) $config['allowed'] = array($config['allowed']);
				
				if (
					(
						isset($config['default']) &&
						$parse_str[$paramName]==$config['default']
					)
					||
					(
						isset($config['allowed']) &&
						!in_array($parse_str[$paramName], $config['allowed'])
					)
				) unset($parse_str[$paramName]);
			}
			else {
				unset($parse_str[$paramName]);
			}
		}
		if ($query = http_build_query($parse_str)){
			$url .= '?'.$query;
		}
		return $url;
	}
	
	static function getPages($url, $arrayPages, $pageParamName, $setup=array()) {
		$setup = array_merge(array(
			'urlFunctionName'=>'setUrlParam',
			'imgTag'=>array('start'=>'<span>', 'end'=>'</span>'),
			'linkTag'=>array('start'=>'<span class="page_link">', 'end'=>'</span>'),
			'selectedTag'=>array('start'=>'<span class="page_now">', 'end'=>'</span>'),
			'first'=>'/img/l_end.gif', 'prev'=>'/img/l_page.gif', 'next'=>'/img/r_page.gif', 'last'=>'/img/r_end.gif',
		), $setup);
		$fnk = $setup['urlFunctionName'];
		$str = '';
		if ($setup['first']){
			if ($arrayPages['first']){
				$link  = self::$fnk($url, array($pageParamName=>$arrayPages['first']));
				$first = $setup['imgTag']['start'].'<a href="'.$link.'"><img src="'.STATIC_HOST_NAME.$setup['first'].'"/></a>'.$setup['imgTag']['end'];
			}
			else {
				$first = $setup['imgTag']['start'].'<img src="'.STATIC_HOST_NAME.$setup['first'].'"/>'.$setup['imgTag']['end'];
			}
		}
		unset($arrayPages['first']);
		if ($setup['prev']){
			if ($arrayPages['prev']){
				$link   = self::$fnk($url, array($pageParamName=>$arrayPages['prev']));
				$prev   = $setup['imgTag']['start'].'<a href="'.$link.'"><img src="'.STATIC_HOST_NAME.$setup['prev'].'"/></a>'.$setup['imgTag']['end'];
			}
			else {
				$prev   = $setup['imgTag']['start'].'<img src="'.STATIC_HOST_NAME.$setup['prev'].'"/>'.$setup['imgTag']['end'];
			}
		}
		unset($arrayPages['prev']);
		if ($setup['next']){
			if ($arrayPages['next']){
				$link   = self::$fnk($url, array($pageParamName=>$arrayPages['next']));
				$next   = $setup['imgTag']['start'].'<a href="'.$link.'"><img src="'.STATIC_HOST_NAME.$setup['next'].'"/></a>'.$setup['imgTag']['end'];
			}
			else {
				$next   = $setup['imgTag']['start'].'<img src="'.STATIC_HOST_NAME.$setup['next'].'"/>'.$setup['imgTag']['end'];
			}
		}
		unset($arrayPages['next']);
		if ($setup['last']){
			if ($arrayPages['last']){
				$link = self::$fnk($url, array($pageParamName=>$arrayPages['last']));
				$last = $setup['imgTag']['start'].'<a href="'.$link.'"><img src="'.STATIC_HOST_NAME.$setup['last'].'"/></a>'.$setup['imgTag']['end'];
			}
			else {
				$last = $setup['imgTag']['start'].'<img src="'.STATIC_HOST_NAME.$setup['last'].'"/>'.$setup['imgTag']['end'];
			}
		}
		unset($arrayPages['last']);
		$str .= $first.$prev;
		foreach($arrayPages as $page=>$status){
			if ($status == 'now'){
				$str .= $setup['selectedTag']['start'].$page.$setup['selectedTag']['end'];
			}
			else {
				$link = self::$fnk($url, array($pageParamName=>$page));
				$str .= $setup['linkTag']['start'].'<a href="'.$link.'">'.$page.'</a>'.$setup['linkTag']['end'];
			} 
		}
		$str .= $next.$last;
		return($str);
	}
}
