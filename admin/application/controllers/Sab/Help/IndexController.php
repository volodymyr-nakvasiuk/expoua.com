<?PHP
Zend_Loader::loadClass("Sab_Help_ControllerAbstract", PATH_CONTROLLERS);

class Sab_Help_IndexController extends Sab_Help_ControllerAbstract {

	public function indexAction() {
/*
		$topic = $this->getRequest()->getUserParam('topic', null);
		$result = $this->_model->getEntry($this->_user_param_id);
		if ($topic && strpos($result['content'], '="'.$topic.'"')) {
		  $result['content'] = preg_replace('#^.*<div +id="'.$topic.'">(.*)</div>.*$#iU', '\1', $result['content']);
		} else {
		  $result['content'] = 'NO CONTEXT HELP AVAILABLE!';
		}
		
		$this->_view->entry = $result;
*/

		$this->_view->topic = 'tip_' . $this->getRequest()->getUserParam('topic', 'none');
        $config_file = $this->getRequest()->getUserParam('id');
        $language = $this->getRequest()->getParam("language");
        if (file_exists(PATH_LANGUAGES.'/'.$language.'/'.$config_file.'.conf'))
            $this->_view->config_file = $config_file;
        else
            $this->_view->config_file = false;
	}

}