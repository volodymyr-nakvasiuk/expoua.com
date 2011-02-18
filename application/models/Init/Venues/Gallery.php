<?php
class Init_Venues_Gallery extends Init_VenuesTab {

	static $tab_name = 'photo';
	protected $_gallery = false;

	public function __construct($venue_id, $tab, $tab_action, $tab_id, $gallery=array()){
		parent::__construct($venue_id, $tab, $tab_action, $tab_id);
		$this->_gallery = $gallery;
	}

	protected function pageAction(){
		$data = array();
		foreach ($this->_gallery as $image){
			$image = trim($image);
			if ($image){
				$data[] = array(
					'venue_id'=>$this->_venueId,
					'image'=>$image,
				);
			}
		}
		return array('data'=>$data);
	}
}
