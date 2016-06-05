<?php
/* =============================================================== *\
|		Module name: Information									|
|		Module version: 1.3											|
|		Begin: 22 February 2004										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

class Info
{
	var $option				= array();
	var	$client_ip			= "";

	var	$modlist			= array();
	var $mod				= "";
	var $act				= "";
	var $code				= "";

	var $tpl_header			= "header";
	var $tpl_main			= "home";
	var $tpl_footer			= "footer";

	var	$imgpath_article	= "images/articles/";
	var	$imgpath_gallery	= "images/gallery/";

	function Info(){
		global $REMOTE_ADDR;

		$this->client_ip	= !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : ( !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : (!empty($REMOTE_ADDR) ? $REMOTE_ADDR : '' ));

		$this->get_config();
	}

	function get_common(){
		global $Func;

		if ( class_exists('Func_Home') ){
			//Normal version
			$this->mod		= $Func->get_vars('mod', 0);
		}
		else{
			//Used for Ajax version - Not use Func class for speed
			$this->mod		= isset($_GET['mod']) ? htmlspecialchars($_GET['mod']) : '';
		}

		$this->modlist = array(
			MOD_HOME			=> 'home',
			//HOME_TEDIN			=> 'home',
			MOD_ABOUT			=> "about",
			MOD_HOST			=> "hosting",
			MOD_VPS			    => "vps",
			MOD_TK			    => "tk",
			MOD_CUSTOMER		=> "customer",
			MOD_INFO			=> "info",
			MOD_ARTICLE			=> "article",
			MOD_COMMENT			=> "comment",
			MOD_POLL			=> "poll",
			MOD_FAQ				=> "faq",
			MOD_WEBLINK			=> "weblink",
			MOD_SITEMAP			=> "sitemap",
			MOD_SEARCH			=> "search",
			MOD_LOGO			=> "logo",
			MOD_CONTACT			=> "contact",
			MOD_NEWSLETTER		=> "newsletter",
			MOD_EVENT			=> "event",
			MOD_RSS				=> "rss",
			MOD_UPDATE			=> "site_update",
			MOD_TELLFRIEND		=> "tellfriend",
			MOD_PICTURE			=> "picture",
			MOD_ARCHIVE			=> "archive",
			MOD_AJAX_STATISTIC	=> "ajax_statistic",
			MOD_AJAX_RSS_IMPORT	=> "ajax_rss_import",
			MOD_SECURITY		=> "security",
		);

		if ( !isset($this->modlist[$this->mod]) ) $this->mod = MOD_HOME;
	}

	function check_website_close(){
		global $DB, $Template, $Lang;

		if ( $this->option['website_close'] ){
			$DB->close();
			$msg	= '<strong>'. nl2br($this->option['website_close_message']) .'</strong>';
			$Template->message_die($msg);
		}
	}

	function get_config(){
		global $DB;

		$DB->query("SELECT * FROM ". $DB->prefix ."config");
		if ($DB->num_rows()){
			while ( $result = $DB->fetch_array() ){
				$this->option[$result["config_name"]] = $result["config_value"];
			}
		}
		$DB->free_result();

		//Template
		$template_id	= isset($_COOKIE['global_template_id']) ? $_COOKIE['global_template_id'] : '';
		if ( !empty($template_id) ){
			$template_id	= preg_replace('/[^\w-_]/', '', $template_id);
			if ( is_dir('./templates/'. $template_id) ){
				$this->option['template']	= $template_id;
			}
		}
	}

	function get_mod(){
		$this->tpl_main = $this->modlist[$this->mod];
		return $this->modlist[$this->mod];
	}

	function halt($msg){
		echo "<b>Information Error:</b> $msg";
		die();
	}
}

?>