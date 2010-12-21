<?PHP

abstract class FrontendControllerAbstract extends ControllerAbstract {

	protected function _setCoreTemplateById($id) {
		$template = $this->_model->getTemplateNameById($id);
		$this->_view->setTemplate("pages/" . $template);
	}

	protected function _setCoreTemplateByConstant($const) {
		$this->_setCoreTemplateById($this->_model->getConfigValue($const));
	}

	protected function _pageNotFoundRedirect() {
		$this->_forward("system", "cms", null, array('id' => 404));
		return;
	}

}