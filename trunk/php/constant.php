<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

//ini_set('include_path', 'your_website_path');

//Main files
define("HOME_INDEX", "index". PHP_EX);
define("ACP_INDEX", "admin". PHP_EX);
define("AJAX_INDEX", "ajax". PHP_EX);
define("MOD_NEWSLETTER", "newslt");

//Index pages
if ( defined('IN_INDEX') ){
	//Module files
	define("MOD_HOME", "home");
	define("MOD_ARTICLE", "article");
	define("MOD_COMMENT", "comment");
	define("MOD_SEARCH", "search");
	define("MOD_POLL", "poll");
	define("MOD_FAQ", "faq");
	define("MOD_WEBLINK", "weblink");
	define("MOD_SITEMAP", "sitemap");
	define("MOD_LOGO", "logo");
	define("MOD_CONTACT", "contact");
	define("MOD_EVENT", "event");
	define("MOD_RSS", "rss");
	define("MOD_UPDATE", "site_update");
	define("MOD_TELLFRIEND", "tellfriend");
	define("MOD_PICTURE", "picture");
	define("MOD_ARCHIVE", "archive");
	define("MOD_SECURITY", "security");

	//Cache engine will be enabled for these modules
	$cfg_mod_cache		= array(MOD_HOME, MOD_ARTICLE, MOD_FAQ, MOD_SITEMAP);
}
//Admin pages
else if ( defined('IN_ADMIN') ){
	//Module files
	define("MOD_HOME", "idx");
	define("MOD_FAQ", "faq");
}

//Ajax modules
define("MOD_AJAX_STATISTIC", "ajax_statistic");
define("MOD_AJAX_RSS_IMPORT", "ajax_rss_import");

//Article type
define("SYS_ARTICLE_NORMAL", "0");
define("SYS_ARTICLE_HOT", "1");
define("SYS_ARTICLE_FULL", "0");
define("SYS_ARTICLE_SUMMARY", "1");
define("SYS_ARTICLE_LINK", "2");

//Article status
define("SYS_DISABLED", "0");
define("SYS_ENABLED", "1");
define("SYS_APPENDING", "2");
define("SYS_SHOWING", "8");
define("SYS_WAITING", "9");

//Archive
define("SYS_ARCHIVED", "1");
define("SYS_UNARCHIVED", "0");

//Logo position
define("LOGO_POS_LEFT", "0");
define("LOGO_POS_RIGHT", "1");
define("LOGO_SWF_WIDTH", "130");
define("LOGO_SWF_HEIGHT", "60");
define("LOGO_SORT_ORDER", "asc");
//WebPage codes (similar to module codes)
$cfg_webpage_list	= array(
	//Page code			Language_key
	'home'			=> 'page_home',
	'faq'			=> 'page_faq',
	'rss'			=> 'page_rss',
	'weblink'		=> 'page_weblink',
	'sitemap'		=> 'page_sitemap',
	'contact'		=> 'page_contact',
	'event'			=> 'page_event',
);
$cfg_media_objects	= array(
	'swf'			=>	'<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="[_WIDTH_]" height="[_HEIGHT_]">
							<param name="movie" value="[_FILENAME_]">
							<param name="quality" value="high">
							<embed src="[_FILENAME_]" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="[_WIDTH_]" height="[_HEIGHT_]"></embed>
						</object>'
);

//Global constants
define('CURRENT_TIME', time());
?>