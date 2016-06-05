<?php
/* =============================================================== *\
|		Module name:      Help										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

$AdminHelp = new Admin_Help;

class Admin_Help
{
	function Admin_Help(){
		global $Info, $Template, $Lang, $DB;

		$Info->tpl_header	= "";
		$Info->tpl_footer	= "";

		switch ($Info->code){
			case "timezone":
				$Info->tpl_main		= "timezone";
				$Template->set_vars(array(
					'L_TIMEZONE'	=> $Lang->data['general_timezone'],
				));
				break;
			default:
				@$DB->close();
				die();
		}
	}
}

?>