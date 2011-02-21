<?php
class ArOn_Zend_Validate_NotEmpty extends Zend_Validate_NotEmpty {
	public function __construct(Zend_Translate $translator) {
		$this->_messageTemplates = array(
			self::IS_EMPTY => $translator->translate("Value is required and can't be empty"),
			self::INVALID  => $translator->translate("Invalid type given, value should be float, string, array, boolean or integer"),
		);
	}
}
