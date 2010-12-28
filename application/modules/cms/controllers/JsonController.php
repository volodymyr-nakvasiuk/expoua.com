<?php
class Cms_JsonController extends Abstract_Controller_CmsController {

	protected $parent_ids = array(
		'siteController' => 'controller_module_id',
		'siteActs' => 'action_controller_id',
		'lang_LocationCountriesData'=>'regions_id',
		'lang_LocationCitiesData'=>'countries_id',
		'lang_Companies2Data'=>'cities_id',
	);
	
	public function init(){
		parent::init();
		$this->_helper->viewRenderer->setNoRender();
		$this->_response->setHeader('Content-type', 'application/json');
	}
	
	public function __call($function,$params){
		$count = preg_match('/(.*?)Action/',$function,$matches);
		if ($count) {
			$parent_id = $this->_request->getParam('parent_id');
			if(empty($parent_id )){
				echo "{succes: true,rows: []}";
				return true;
			}
			$classname = 'Db_'.ucfirst($matches[1]);
			if(strpos($classname,'Acl_') !== false){
				$name = ucfirst($matches[1]);
				$t = $matches[1] [3];
				
				$name = str_replace('Acl_'.$t,'Acl_'.ucfirst($t),$name);
				$classname = 'Crud_Grid_ExtJs_'.ucfirst($name);
				$grid = new $classname (null,array('parent' => $parent_id));
				$grid->setLimit(1000);
				$data = $grid->getData();
				$model = $grid->getModel();
				echo $this->generateJson($data['data'],$model);
				return true;
			}
			if(!class_exists($classname)) return false;
			$model = new $classname();
			$select = $model->select();
			$parent_key = (array_key_exists($matches[1], $this->parent_ids)) ? $this->parent_ids [$matches[1]] : 'parent_id';
			$dc = preg_match('/Db_Lang_(.*?)Data$/',$classname,$dm);
			if($dc){
				$select->columnsJoinOne ('Db_'.$dm[1], array($parent_key=>$parent_key));
				$select->columns();
			}
			$select->where($parent_key . ' = ?',$parent_id);
			$order_exp = $model->getOrderExpr();
			$order_asc = $model->getOrderAsc();
			foreach ($order_exp as $i => &$key){
				if ($order_asc[$i]) $direction = 'ASC';
				else $direction = 'DESC';
				$key = $model->getTableName().'.'.$key.' '.$direction;
			}
			$select->reset(ArOn_Db_TableSelect::ORDER)->order($order_exp);
			//echo $select->__toString();exit;
			$data = $model->fetchAll($select);
			echo $this->generateJson($data,$model);
			return true;
		}
	}
	
	protected function generateJson($data,$model){
			$json = "{success: true,rows: [";
			$options = array();
			$key = $model->getPrimary();
			$name = $model->getNameExpr();
			foreach ($data as $row){
				$option = "{optionValue:'" . $row[$key] . "', displayText:'" . addslashes(trim(str_replace(array("\n", "\r", "\t", "\0"),array("", "", "", ""),$row[$name]))) . "'}";
				$options[] = $option;
			}
			$json .= implode(', ',$options) ."]}";
			return $json;
	}

}