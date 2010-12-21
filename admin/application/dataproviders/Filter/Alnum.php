<?php
require_once 'Zend/Filter/Interface.php';

/**
 * Фильтрует все символы кроме букв, цифр и заменяет их пробелами
 * Работает с мультибайтными кодировками
 *
 */
class Filter_Alnum implements Zend_Filter_Interface {
    public function filter($value) {
    	return preg_replace('/[^\p{L}\p{N}\s]/u', ' ', (string) $value);
    }
}