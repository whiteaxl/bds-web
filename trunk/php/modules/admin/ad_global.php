<?php
/* =============================================================== *\
|		Module name:      Admin Global								|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

$AdminGlobal = new Admin_Global;

class Admin_Global
{
	function Admin_Global(){
		$this->set_global();
	}

	function set_global(){
		global $Lang, $Template, $Info;

		$Info->get_user_info();

		$Template->set_vars(array(
			'SITE_URL'				=> $Info->option['site_url'],
			'L_CHARSET'				=> $Lang->charset,
			'L_ADMIN_CONTROL'		=> $Lang->data['general_admin_control'],
			'L_STATUS'				=> $Lang->data['general_status'],
			'L_ENABLE'				=> $Lang->data['general_enable'],
			'L_DISABLE'				=> $Lang->data['general_disable'],
			'L_ENABLED'				=> $Lang->data['general_enabled'],
			'L_DISABLED'			=> $Lang->data['general_disabled'],
			'L_APPENDING'			=> $Lang->data['general_appending'],
			'L_SHOWING'				=> $Lang->data['general_showing'],
			'L_WAITING'				=> $Lang->data['general_waiting'],
			'L_ARCHIVE'				=> $Lang->data['general_archive'],
			'L_ARCHIVED'			=> $Lang->data['general_archived'],
			'L_UNARCHIVED'			=> $Lang->data['general_unarchived'],
			"L_RESYNC"				=> $Lang->data["general_resync"],
			'L_ADD'					=> $Lang->data['general_add'],
			'L_EDIT'				=> $Lang->data['general_edit'],
			'L_UPDATE'				=> $Lang->data['general_update'],
			'L_DELETE'				=> $Lang->data['general_del'],
			'L_CLOSE'				=> $Lang->data['general_close_window'],
			'L_HITS'				=> $Lang->data['general_hits'],
			'L_YES'					=> $Lang->data['general_yes'],
			'L_NO'					=> $Lang->data['general_no'],
			'SYS_ENABLED'			=> SYS_ENABLED,
			'SYS_DISABLED'			=> SYS_DISABLED,
			'SYS_APPENDING'			=> SYS_APPENDING,
			'SYS_SHOWING'			=> SYS_SHOWING,
			'SYS_WAITING'			=> SYS_WAITING,
			'SYS_ARCHIVED'			=> SYS_ARCHIVED,
			'SYS_UNARCHIVED'		=> SYS_UNARCHIVED,
			'SYS_ARTICLE_NORMAL'	=> SYS_ARTICLE_NORMAL,
			'SYS_ARTICLE_HOT'		=> SYS_ARTICLE_HOT,
			'SYS_ARTICLE_FULL'		=> SYS_ARTICLE_FULL,
			'SYS_ARTICLE_SUMMARY'	=> SYS_ARTICLE_SUMMARY,
			'SYS_ARTICLE_LINK'		=> SYS_ARTICLE_LINK,
		));
     }

}

?>