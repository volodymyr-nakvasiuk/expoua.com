<?php
class ArOn_Zend_Validate_EmailAddress extends Zend_Validate_EmailAddress {

	public function __construct(Zend_Translate $translator, $allow = Zend_Validate_Hostname::ALLOW_DNS, $validateMx = false, Zend_Validate_Hostname $hostnameValidator = null){
		parent::__construct($allow, $validateMx, $hostnameValidator);
		$this->_messageTemplates = array(
			self::INVALID            => $translator->translate("Invalid type given, value should be a string"),
			self::INVALID_FORMAT     => $translator->translate("'%value%' is not a valid email address in the basic format local-part@hostname"),
			self::INVALID_HOSTNAME   => $translator->translate("'%hostname%' is not a valid hostname for email address '%value%'"),
			self::INVALID_MX_RECORD  => $translator->translate("'%hostname%' does not appear to have a valid MX record for the email address '%value%'"),
			self::DOT_ATOM           => $translator->translate("'%localPart%' not matched against dot-atom format"),
			self::QUOTED_STRING      => $translator->translate("'%localPart%' not matched against quoted-string format"),
			self::INVALID_LOCAL_PART => $translator->translate("'%localPart%' is not a valid local part for email address '%value%'"),
			self::LENGTH_EXCEEDED    => $translator->translate("'%value%' exceeds the allowed length"),
		);
	}

}