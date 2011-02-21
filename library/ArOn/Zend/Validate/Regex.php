<?php
class ArOn_Zend_Validate_Regex extends Zend_Validate_Regex {
	public function __construct(Zend_Translate $translator, $pattern) {
		parent::__construct($pattern);
		$this->_messageTemplates = array(
			self::INVALID   => $translator->translate("Invalid type given, value should be string, integer or float"),
			self::NOT_MATCH => $translator->translate("'%value%' does not match against pattern '%pattern%'"),
		);
	}
}