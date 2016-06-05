<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Logo;

class Lang_Module_Logo
{
	var $data		= array();

	function Lang_Module_Logo(){
		//Logo
		$this->data['logo_old_logo']			= 'Old Logo';
		$this->data['logo_new_logo']			= 'New Logo';
		$this->data['logo']						= 'Logo';
		$this->data['logo_order']				= 'Order';
		$this->data['logo_local']				= 'Local Image';
		$this->data['logo_remote']				= 'Remote Image';
		$this->data['logo_size']				= 'Logo Size';
		$this->data['logo_width']				= 'Width';
		$this->data['logo_height']				= 'Height';
		$this->data['logo_site_url']			= 'Site URL';
		$this->data['logo_site_name']			= 'Site name';
		$this->data['logo_del_confirm']			= 'Are you sure to delete checked logos?';

		//Mod AdsBox begin
		$this->data['logo_pos']					= 'Logo position';
		$this->data['logo_pos_left']			= 'Left box';
		$this->data['logo_pos_right']			= 'Right box';

		//Logo error
		$this->data['logo_error_not_exist']		= 'Logo not found!';
		$this->data['logo_error_not_check']		= 'Please check logos!';
	}
}

?>