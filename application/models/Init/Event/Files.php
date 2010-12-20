<?php
class Init_Event_Files extends Init_EventTab {

	static $tab_name = 'files';
	static $fileTypes = array(
		"content_type"=>"",
		"application/download"=>"",
		"application/msword"=>"doc",
		"application/octet-stream"=>"",
		"application/pdf"=>"pdf",
		"application/vnd.ms-excel"=>"xls",
		"application/vnd.ms-powerpoint"=>"",
		"application/vnd.openxmlformats-o"=>"",
		"application/x-rar-compressed"=>"",
		"image/gif"=>"",
		"image/jpeg"=>"",
		"image/pjpeg"=>"",
		"image/png"=>"",
	);

	protected function pageAction(){
		$filter = array(
			'events_id'=>$this->_eventId,
			'limit'=>'all',
		);
		$grid = new Crud_Grid_EventFiles(null, $filter);
		return $grid->getData();
	}
}
