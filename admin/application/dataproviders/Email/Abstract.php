<?php
require_once("Zend/Mail.php");
require_once('Zend/Validate/EmailAddress.php');

/**
 * Вспомогательный класс для отправки почты
 * Большая часть действий производится при помощи прямой манипуляции с
 * объектом Zend_Mail через 
 * @author su
 *
 */
abstract class Email_Abstract {
	
	/**
	 * Экземпляр объекта Zend_Mail
	 * @var Zend_Mail
	 */
	protected $_mailObj;
	
	/**
	 * Экземпляр объекта валидации электронного адреса
	 * @var Zend_Validate_EmailAddress
	 */
	protected $_mailValidateObj;

	public function __construct() {
		$this->_mailObj = new Zend_Mail("utf-8");
		
		$hostValObj = new Zend_Validate_Hostname(Zend_Validate_Hostname::ALLOW_DNS, false);
	    $this->_mailValidateObj = new Zend_Validate_EmailAddress(Zend_Validate_Hostname::ALLOW_DNS, false, $hostValObj);
	}
	
	/**
	 * Добавляет адрес в скрытую копию
	 * Можно передавать список через ",", функция автоматически разобьет
	 * @param $email
	 * @return void
	 */
	public function addBcc($email) {
		//$emails_array = explode(",", $email);
		$emails_array = preg_split("/[,; ]+/", $email, PREG_SPLIT_NO_EMPTY);
		foreach ($emails_array as $el) {
			$el = trim($el);
			if (!empty($el) && $this->isEmailValid($el) == true) {
				$this->_mailObj->addBcc($el);
			}
		}
	}
	
	/**
	 * Функция, упрощающая валидацию электронного адреса
	 * @param $email
	 * @return boolean
	 */
	public function isEmailValid($email) {
		return $this->_mailValidateObj->isValid($email);
	}
	
	/**
	 * Возвращает ссылку на объект Zend_Mail
	 * @return Zend_Mail
	 */
	public function getMailObj() {
		return $this->_mailObj;
	}
	
	/**
	 * Отправляет сообщение
	 * Возвращает true в случае успеха
	 * 
	 * @return boolean
	 */
	abstract public function send();
	
}