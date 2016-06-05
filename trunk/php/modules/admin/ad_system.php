<?php
/* =============================================================== *\
|		Module name:      System									|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

$AdminSystem = new Admin_System;

class Admin_System
{
	var $user_perm		= array();

	function Admin_System(){
		global $Func;

		$this->user_perm	= $Func->get_all_perms('menu_admin_system');
		$this->show_system();
	}

	function show_system(){
		global $Info;

		$Info->tpl_header = "";
		$Info->tpl_main   = "";
		$Info->tpl_footer = "";

		phpinfo();
	}
}
?>