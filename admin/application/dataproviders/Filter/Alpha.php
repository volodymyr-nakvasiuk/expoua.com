<?php
require_once 'Zend/Filter/Interface.php';

/**
 * Фильтрует все символы кроме буквенных
 * Работает с мультибайтными кодировками
 *
 */
class Filter_Alpha implements Zend_Filter_Interface {
    public function filter($value) {
    	return preg_replace('/[^\p{L}\s]/u', '', (string) $value);
    }
}