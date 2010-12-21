<?PHP

Zend_Loader::loadClass("Sab_Organizer_ModelAbstract", PATH_MODELS);

class Sab_Organizer_GalleryModel extends Sab_Organizer_ModelAbstract {

	protected $_DP_name = 'List_EventsGalleries';
	
	public $_event_id = null;

	
	public function getEventsList($page = null, $sort = null, $search = null) {
	  $DP = $this->_DP_obj;
    $this->_DP_obj = $this->_DP('List_Joined_Ep_BrandPlusEvent');

		$search[] = array('column' => 'date_to', 'value' => date("Y-m-d"), 'type' => '>=');

		$res = parent::getList($page, $sort, $search);
		
	  $this->_DP_obj = $DP;

		return $res;
	}


	public function getEventEntry($id) {
	  $DP = $this->_DP_obj;
    $this->_DP_obj = $this->_DP('List_Joined_Ep_Events');

		$res = parent::getEntry($id);
		
	  $this->_DP_obj = $DP;

		return $res;
	}


	public function getList($page = null, $sort = null, $search = null) {
	  
    $this->checkUserEventPermission($this->_event_id);
    
    $list = $this->_DP_obj->getList(1000, $page, array('events_id' => $this->_event_id));
		return $list;
	}


	public function getEntry($id) {
		return $this->_DP_obj->getEntry($id, array('events_id' => $this->_event_id));
	}


	public function insertEntry(Array $data, $insertAllLangs = false) {
		if (!isset($_FILES) || !isset($_FILES['image']) || $_FILES['image']['error']!=0) {
			return -1;
		}

		$data['events_id'] = intval($data['parent_id']);
		unset($data['parent_id']);

		$res = parent::insertEntry($data);

		if (!$res) {
			return $res;
		}

		$gallery_id = $this->_DP_obj->getLastInsertId();
		$file_fp = $_FILES['image']['tmp_name'];
		$save_as_base = PATH_FRONTEND_DATA_IMAGES . "/events/" . $data['events_id'] . "/";
		$this->_DP("Filesystem_Images")->createRecursive(array('basePath' => $save_as_base, 'path' => "gallery/"));

		$save_as_base = $save_as_base . "gallery/";

		$save_as = $save_as_base . $gallery_id . "_tb.jpg";
		$extraParams = array('image_type' => $_FILES['image']['type']);
		$img_res = $this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, 100, 100, $extraParams);

		$save_as = $save_as_base . $gallery_id . ".jpg";
		$this->_DP("Filesystem_Images")->thumbnailCreate($file_fp, $save_as, 640, 480, $extraParams);

    // Запись в лог активности организатора
    $this->_DP("List_OrganizersLog")->insertEntry(
      array(
        'type'               => 'gallery_add', 
        'description'        => 'Добавлена картинка ' . $save_as, 
        'users_operators_id' => $this->_user_session->operator->id
      )
    );
		
		return $res;
	}


	public function deleteEntry($id) {
		//$entry = $this->getEntry($id);

    // Zend_Debug::dump($id);

/*
		if (empty($entry)) {
			return 0;
		}
*/

		$res = parent::deleteEntry($id);

		if ($res) {
			//Удаляем изображения
			$path = PATH_FRONTEND_DATA_IMAGES . "/events/" . $this->_event_id . "/gallery/";
			$this->_DP("Filesystem_Images")->deleteEntry(array($id . ".jpg"), array('basePath' => $path));
			$this->_DP("Filesystem_Images")->deleteEntry(array($id . "_tb.jpg"), array('basePath' => $path));

      // Запись в лог активности организатора
      $this->_DP("List_OrganizersLog")->insertEntry(
        array(
          'type'               => 'gallery_delete', 
          'description'        => 'Удалена картинка ' . $path . $id . ".jpg", 
          'users_operators_id' => $this->_user_session->operator->id
        )
      );
		}

		return $res;
	}
}
