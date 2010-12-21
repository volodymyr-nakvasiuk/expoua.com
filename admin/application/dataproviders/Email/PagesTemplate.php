<?php
Zend_Loader::loadClass("Email_Abstract", PATH_DATAPROVIDERS);
require_once(PATH_VIEWS . "/Frontend/View/Console.php");

class Email_PagesTemplate extends Email_Abstract {
	
	private $_templateId = null;
	private $_data = array();
	
	public function setPageId($id) {
		$this->_templateId = $id;
	}
	
	/**
	 * Устанавливает массив с данными
	 * которые будут переданы в шаблонизатор
	 * 
	 * @param $data
	 * @return void
	 */
	public function setData(Array &$data) {
		$this->_data = &$data;
	}
	
	/**
	 * Обобщенная функция отправки письма с использованием шаблона из
	 * контентных страниц
	 * @return boolean
	 */
	public function send() {
		$tpl_data = DataproviderAbstract::_DP('List_Joined_Pages')->
			getEntry($this->_templateId, array('languages_id' => Zend_Registry::get("language_id")));

		$view = new Frontend_View_Console();

		$view->assign("_system_include_conent", "contents/" . $this->_templateId);
		$view->assign("entry", $this->_data);

		$message = $view->fetch("system/email_base");

		$this->_mailObj->setSubject($tpl_data['title']);
		$this->_mailObj->setBodyHtml($message);

		return $this->_mailObj->send();
	}
	
	/**
	 * Вспомогательная функция отправки нотификаторов пользователям
	 * Применяется для информирования пользоватлей об активации и деактивации
	 * их аккаунтов
	 * Первым параметром передается массив с данными, который передается в шаблонизатор
	 * Вторым передается id страницы-шаблона
	 * Третьим и четвертым параметрами можно передать email и имя, с которого будет
	 * отправлено сообщение
	 * Пятым строкой список адресов скрытой копии, можно через запятую несколько
	 * @param $data
	 * @param $tpl_code
	 * @param $from
	 * @param $bcc
	 * @return boolean
	 */
	public function sendChangeStatusNotification(Array &$data, $tpl_code, $from_email = "", $from_name = "", $bcc = "") {
		if ($this->isEmailValid($data['email']) == false) {
			return false;
		}
		
		$this->setPageId($tpl_code);
		
		//Выбираем язык шаблона
		$lang_id = null;
		if (isset($data['languages_id']) && is_numeric($data['languages_id'])) {
			$lang_id = $data['languages_id'];
		} else if (isset($data['countries_id'])) {
			if (in_array($data['countries_id'], array(13, 17, 26, 33, 34, 39, 45, 52, 60, 154, 162, 166, 185, 189, 190))) {
				$lang_id = 1;
			} else {
				$lang_id = 2;
			}
		}
		
		if (!is_null($lang_id)) {
			$old_lang = Zend_Registry::get("language_id");
			Zend_Registry::set("language_id", $lang_id);
		}
		
		if (empty($from_email) || $this->isEmailValid($from_email) == false) {
			$from_email = "info@expopromoter.com";
		}
		if (empty($from_name)) {
			$from_name = "EGMS ExpoPromoter.com";
		}
		
		$this->getMailObj()->setFrom($from_email, $from_name);
		$this->getMailObj()->addTo($data['email'], (!empty($data['name']) ? $data['name']:$data['login']));
		$this->addBcc($bcc);
		
		$this->setData($data);
		
		$res = $this->send();
		
		if (!is_null($lang_id)) {
			Zend_Registry::set("language_id", $old_lang);
		}
		
		return $res;
	}
	
}
