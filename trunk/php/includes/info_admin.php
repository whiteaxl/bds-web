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
	var $option			= array();
	var $user_info		= array();
	var	$client_ip		= "";

	var	$modlist		= array();
	var $mod			= "";
	var $act			= "";
	var $code			= "";

	var $tpl_header		= "header";
	var $tpl_main		= "home";
	var $tpl_footer		= "footer";
	var	$imgpath_article	= "images/articles/";

	function Info(){
		global $REMOTE_ADDR;

		$this->mod			= isset($_GET["mod"]) ? htmlspecialchars($_GET["mod"]) : '';
		$this->act			= isset($_GET["act"]) ? htmlspecialchars($_GET["act"]) : '';
		$this->code			= isset($_GET["code"]) ? htmlspecialchars($_GET["code"]) : '';
		$this->client_ip	= !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : ( !empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : (!empty($REMOTE_ADDR) ? $REMOTE_ADDR : '' ));

		$this->get_config();

		$this->modlist = array(
			"idx"				=> "ad_index",
			"frame"				=> "ad_frame",
			"article"			=> "ad_article",
			"article_cat"		=> "ad_article_cat",
			"article_page"		=> "ad_article_page",
			"article_topic"		=> "ad_article_topic",
			"article_comment"	=> "ad_article_comment",
			"rss_export"		=> "ad_rss_export",
			"rss_import"		=> "ad_rss_import",
			"user"				=> "ad_user",
			"user_group"		=> "ad_user_group",
			"user_field"		=> "ad_user_field",
			"picture"			=> "ad_picture",
			"event"				=> "ad_event",
			"poll"				=> "ad_poll",
			"faq"				=> "ad_faq",
			"logo"				=> "ad_logo",
			"gallery"		    => "ad_gallery",
			"gallery_cat"		=> "ad_gallery_cat",
			"info"		        => "ad_info",
			"upload"			=> "ad_upload",
			"help"				=> "ad_help",
			"db"				=> "ad_database",
			"newslt_cat"		=> "ad_newslt_cat",
			"newslt"			=> "ad_newslt",
			"newslt_issue"		=> "ad_newslt_issue",
			"weblink_cat"		=> "ad_weblink_cat",
			"weblink"			=> "ad_weblink",
			"setting"			=> "ad_setting",
			"cache"				=> "ad_cache",
			"system"			=> "ad_system",
			"log"				=> "ad_acplog",
			"stat"				=> "ad_statistic",
			"private"			=> "ad_private",
		);

		if ( !isset($this->modlist[$this->mod]) ) $this->mod = MOD_HOME;
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
	}

	function get_mod(){
		$this->tpl_main = $this->modlist[$this->mod];
		return $this->modlist[$this->mod];
	}

	function get_user_info(){
		global $DB, $Session;

		if ( !empty($Session->sid) ){
			$DB->query("SELECT U.*, S.user_groups, S.auto_login FROM ". $DB->prefix ."user AS U, ". $DB->prefix ."session AS S WHERE U.user_id=S.user_id AND S.session_id='". $Session->sid ."'");
			$this->user_info	= $DB->fetch_array();

			//Update some personal options
			if ( !empty($this->user_info['user_template']) ){
				$this->option['template']	= $this->user_info['user_template'];
			}
			if ( !empty($this->user_info['user_language']) ){
				$this->option['language']	= $this->user_info['user_language'];
			}
			if ( $this->user_info['user_timezone'] < 15 ){
				$this->option['timezone']		= $this->user_info['user_timezone'];
			}
		}
	}

	function halt($msg){
		echo "<b>Information Error:</b> $msg";
		die();
	}
}

?>