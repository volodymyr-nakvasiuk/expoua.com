<?php
require_once(PATH_DATAPROVIDERS . "/List/Abstract.php");

abstract class List_Users_Abstract extends List_Abstract {
	
	protected $_allowed_cols = array(
    'id', 'active', 'login', 'passwd', 'name', 'fname', 'countries_id', 'text_gorod', 'zipcode', 'address', 
    'text_comp', 'text_dolgnost', 'status', 'functions', 'text_tel', 'email', 'company_email', 'text_sfera', 
    'text_fax', 'text_url', 'text_uznali', 'textarea_comment', 'check2_news', 
    'mselect_cat_1', 'select_periodmail', 'select_exhibannounce', 
    'date_lastsend', 'date_add', 'languages_id', 'companies_id', 'photo_exists', 'is_admin'
  );

	protected $_db_table = "ExpoPromoter_Opt.users_sites";

	protected $_select_list_cols = array(
    'id', 'active', 'login', 'name', 'fname', 'countries_id', 'text_gorod', 'zipcode', 'address', 
    'text_comp', 'text_dolgnost', 'status', 'functions', 'text_tel', 'email', 'company_email', 'text_sfera', 
    'text_fax', 'text_url', 'textarea_comment', 
    'date_lastsend', 'languages_id', 'companies_id', 'photo_exists', 'is_admin'
	);

	protected $_sort_col = array('id' => 'DESC');

	protected $_prepare_cols = array(
		'countries_id' => array('num', null),
		'companies_id' => array('num', null)
	);

}