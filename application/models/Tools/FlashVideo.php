<?php
class Tools_FlashVideo {

	static function getTemplate($parrams){
		return "<div class=\"flash_video_box\">
						<object height=\"".$parrams['height']."px\" width=\"".$parrams['width']."px\" type=\"application/x-shockwave-flash\" data=\"/cms/swf/flowplayer-3.2.6.swf\">
							<param value=\"true\" name=\"allowfullscreen\">
							<param value=\"always\" name=\"allowscriptaccess\">
							<param name=\"Movie\" value=\"/cms/swf/flowplayer-3.2.6.swf\"/>
							<param name=\"Src\" value=\"".$parrams['url']."\"/>
							<param value=\"high\" name=\"quality\">
							<param value=\"false\" name=\"cachebusting\">
							<param value=\"#000000\" name=\"bgcolor\">
							<param name=\"oop\" value=\"false\"/>
							<param value=\"config={
								'clip':{
									'autoPlay':false,
									'autoBuffering':false,
									'url':'".$parrams['url']."'
								},
								'plugins':{
									'controls':{
										'autoHide':'always',
										'hideDelay':2000,'stop':true
									}
								},
								'playlist':[{
										'autoPlay':false,
										'autoBuffering':false,
										'url':'".$parrams['url']."'
									}]
							}\" name=\"flashvars\">
						</object>
					</div>";
 	}
	
}