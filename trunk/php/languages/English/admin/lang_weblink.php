<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Weblink;

class Lang_Module_Weblink
{
	var $data		= array();

	function Lang_Module_Weblink(){
		//Weblink
		$this->data['weblink']						= 'Link';
		$this->data['weblinks']						= 'Links';
		$this->data['weblink_site_name']			= 'Site Name';
		$this->data['weblink_site_url']				= 'Site URL';
		$this->data['weblink_del_confirm']			= 'Delete checked links?';
		$this->data['weblink_move']					= 'Move links';
		$this->data['weblink_move_cat']				= 'Move to Category';

		//Weblink Cats
		$this->data['weblink_cat_desc']				= 'Description';
		$this->data['weblink_cat_childs']			= 'Sub cats';
		$this->data['weblink_cat_del_childs']		= 'Delete sub cats';
		$this->data['weblink_cat_del_links']		= 'Delete links';
		$this->data['weblink_cat_move']				= 'Move links';
		$this->data['weblink_cat_source']			= 'Source categories';
		$this->data['weblink_cat_dest']				= 'Destination category';
		$this->data['weblink_cat_move_to']			= 'Move to Category';

		//Weblink errors
		$this->data['weblink_success_move']			= 'Move links successfully!';
		$this->data['weblink_error_not_exist']		= 'Link not found!';
		$this->data['weblink_error_not_check']		= 'Please check links!';
		$this->data['weblink_error_cat_source']		= 'Please choose source categories and destination category.';
		$this->data['weblink_error_cat_not_exist']	= 'Category not found!';
		$this->data['weblink_error_cat_exist']		= 'Category found. Please choose another name.';
	}
}

?>