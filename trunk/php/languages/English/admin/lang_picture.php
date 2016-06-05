<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Picture;

class Lang_Module_Picture
{
	var $data		= array();

	function Lang_Module_Picture(){
		//Picture
		$this->data['picture']						= 'Picture';
		$this->data['picture_content']				= 'Content';
		$this->data['picture_del_confirm']			= 'Are you sure to delete checked pictures?';
		$this->data['picture_error_not_check']		= 'Please check pictures!';
	}
}

?>