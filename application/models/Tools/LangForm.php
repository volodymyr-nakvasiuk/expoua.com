<?php
	class Tools_LangForm{
		static $langData = array(
			1=>array(
				'id'=>'1',
				'active'=>'1',
				'code'=>'ru',
				'locale'=>'ru_RU',
				'name'=>'Русский',
				'title'=>'Русский',
			),
			2=>array(
				'id'=>'2',
				'active'=>'1',
				'code'=>'en',
				'locale'=>'en_US',
				'name'=>'English',
				'title'=>'Английский',
			),
			3=>array(
				'id'=>'3',
				'active'=>'0',
				'code'=>'de',
				'locale'=>'de_DE',
				'name'=>'Deutsch',
				'title'=>'Немецкий',
			),
			4=>array(
				'id'=>'4',
				'active'=>'0',
				'code'=>'jp',
				'locale'=>'ja_JP',
				'name'=>'Japanese',
				'title'=>'Японский',
			),
			5=>array(
				'id'=>'5',
				'active'=>'0',
				'code'=>'it',
				'locale'=>'it_IT',
				'name'=>'Italiano',
				'title'=>'Итальянский',
			),
			6=>array(
				'id'=>'6',
				'active'=>'0',
				'code'=>'sp',
				'locale'=>'es_ES',
				'name'=>'Español',
				'title'=>'Испанский',
			),
		);

		static function getLangForm($formClass, $formParams, $langId=1){
			$actionId = isset($formParams['actionId'])?$formParams['actionId']:null;
			$filter_params = isset($formParams['filter_params'])?$formParams['filter_params']:false;
			$template = isset($formParams['template'])?$formParams['template']:false;
			$options = isset($formParams['options'])?$formParams['options']:null;
			$action = isset($formParams['action'])?$formParams['action']:null;
			$decorate = isset($formParams['decorate'])?$formParams['decorate']:true;
			$decorateClass = isset($formParams['decorateClass'])?$formParams['decorateClass']:'ArOn_Crud_Form_Decorator_Admin';
			Db_Lang::$globalLangId = $langId;
			$form = new $formClass($actionId, $filter_params, $template, $options, $action, $decorate, $decorateClass);
			if (array_key_exists('db_languages_id', $form->fields)) $form->fields['db_languages_id']->setValue ($langId);
			$form->setTitle(self::$langData[$langId]['title'])->setItemVar($langId, true);
			return $form;
		}
	}