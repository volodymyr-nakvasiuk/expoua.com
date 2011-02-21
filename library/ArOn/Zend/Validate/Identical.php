<?php
class ArOn_Zend_Validate_Identical extends Zend_Validate_Identical {
	public function __construct(Zend_Translate $translator, $token = null) {
		parent::__construct($token);
		$this->_messageTemplates = array(
			self::NOT_SAME      => $translator->translate("The token '%token%' does not match the given token '%value%'"),
			self::MISSING_TOKEN => $translator->translate("No token was provided to match against"),
		);
	}
}