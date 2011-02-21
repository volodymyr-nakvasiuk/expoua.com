<?php
class ArOn_Zend_Validate_StringLength extends Zend_Validate_StringLength {
	public function __construct(Zend_Translate $translator, $min = 0, $max = null, $encoding = null) {
		parent::__construct($min, $max, $encoding);
		$this->_messageTemplates = array(
			self::INVALID   => $translator->translate("Invalid type given, value should be a string"),
			self::TOO_SHORT => $translator->translate("'%value%' is less than %min% characters long"),
			self::TOO_LONG  => $translator->translate("'%value%' is greater than %max% characters long"),
		);
	}
}