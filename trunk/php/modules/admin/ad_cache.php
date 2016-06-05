<?php
/* =============================================================== *\
|		Module name: Cache											|
|		Module version: 1.3											|
|		Begin: 20 November 2004										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
//Module language
$Func->import_module_language("admin/lang_cache". PHP_EX);

$AdminCache = new Admin_Cache;

class Admin_Cache
{
	function Admin_Cache(){
		global $Info, $Func;

		$this->user_perm	= $Func->get_all_perms('menu_admin_cache');

		switch ($Info->act){
			case "clean":
				$Func->check_user_perm($this->user_perm, 'del');
				$this->clean_caches();
				break;
			default:
				$this->view_caches();
		}
	}

	function view_caches(){
		global $Session, $Func, $Template, $Lang, $Info;

		$Info->tpl_main		= "cache_info";
		$dir_html			= "cache/html/";
		$dir_php			= "cache/php/";

		//Get cache info
		$html_info		= $this->get_dir_info($dir_html, '.cache');
		$php_info		= $this->get_dir_info($dir_php, '.php');

		$Template->set_vars(array(
			'S_ACTION'						=> $Session->append_sid(ACP_INDEX .'?mod=cache&act=clean'),
			"HTML_FILES"					=> $html_info['files'],
			"HTML_SIZE"						=> $Func->compile_size($html_info['size']),
			"PHP_FILES"						=> $php_info['files'],
			"PHP_SIZE"						=> $Func->compile_size($php_info['size']),
			"TOTAL_FILES"					=> $php_info['files'] + $html_info['files'],
			"TOTAL_SIZE"					=> $Func->compile_size($html_info['size'] + $php_info['size']),
			"L_PAGE_TITLE"					=> $Lang->data["menu_admin"] . $Lang->data['general_arrow'] . $Lang->data["menu_admin_cache"],
			"L_CACHE_HTML"					=> $Lang->data["cache_html"],
			"L_HTML_FILES"					=> $Lang->data["cache_html_files"],
			"L_HTML_SIZE"					=> $Lang->data["cache_html_size"],
			"L_CACHE_PHP"					=> $Lang->data["cache_php"],
			"L_PHP_FILES"					=> $Lang->data["cache_php_files"],
			"L_PHP_SIZE"					=> $Lang->data["cache_php_size"],
			"L_CACHE_TOTAL"					=> $Lang->data["cache_total"],
			"L_TOTAL_FILES"					=> $Lang->data["cache_total_files"],
			"L_TOTAL_SIZE"					=> $Lang->data["cache_total_size"],
			"L_CLEAN"						=> $Lang->data["cache_clear"],
		));
	}

	function get_dir_info($dir, $type){
		$type_length	= 0 - strlen($type);
		$dir_info		= array();
		$dir_info['files']		= 0;
		$dir_info['size']		= 0;

		$handle	= opendir($dir);
		while ( ($file = readdir($handle)) != false ) {
			if (substr($file, $type_length) == $type){
				$dir_info['files']++;
				$dir_info['size']	+= filesize($dir . $file);
			}
		}
		closedir($handle);

		return $dir_info;
	}

	function clean_dir($dir, $type){
		global $File;

		$type_length	= 0 - strlen($type);
		$handle			= opendir($dir);
		while ( ($file = readdir($handle)) != false ) {
			if (substr($file, $type_length) == $type){
				$File->delete_file($dir . $file);
			}
		}
		closedir($handle);
	}

	function clean_caches(){
		global $Session, $Func, $Template, $Lang;

		$dir_html			= "cache/html/";
		$dir_php			= "cache/php/";

		$this->clean_dir($dir_html, '.cache');
		$this->clean_dir($dir_php, '.php');

		$this->view_caches();
	}
}

?>