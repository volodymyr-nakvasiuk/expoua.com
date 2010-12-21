<?PHP
class IndexController extends ControllerAbstract {

	public function indexAction() {
		//Перенаправляем всех пользователей в указанный модуль контента
		$forwardArray = $this->_model->getConfigValue("DEFAULT_CONTROLLER");

		$forwardArray = explode(":", $forwardArray);

		switch (sizeof($forwardArray)) {
			case 1:
				$forwardAction = 'index';
				$forwardController = $forwardArray[0];
				break;
			case 2:
				$forwardAction = $forwardArray[1];
				$forwardController = $forwardArray[0];
				break;
			default:
				$forwardAction = 'index';
				$forwardController = "cms";
		}

		$this->_forward($forwardAction, $forwardController);
	}

}