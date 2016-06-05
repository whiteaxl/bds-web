<?php
/* =============================================================== *\
|		Module name:      Setting									|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_admin_setting');
define('FUNC_ACT_VIEW', 'preedit');
//Module language
$Func->import_module_language("admin/lang_setting". PHP_EX);

$AdminSetting = new Admin_Setting;

class Admin_Setting
{
	var $user_perm		= array();

	function Admin_Setting(){
		global $Info, $Func, $Cache, $Template, $Lang, $Session;

		$this->user_perm	= $Func->get_all_perms('menu_admin_setting');

		$Template->set_vars(array(
			'L_TAB_WEBSITE'		=> $Lang->data['setting_tab_website'],
			'L_TAB_SYSTEM'		=> $Lang->data['setting_tab_system'],
			'L_TAB_MODULES'		=> $Lang->data['setting_tab_modules'],
			'L_TAB_OPEN_CLOSE'	=> $Lang->data['setting_tab_open_close'],
			"L_TURN_ON"			=> $Lang->data["setting_turn_on"],
			"L_TURN_OFF"		=> $Lang->data["setting_turn_off"],
			'U_TAB_WEBSITE'		=> $Session->append_sid(ACP_INDEX .'?mod=setting&act=preedit_website'),
			'U_TAB_SYSTEM'		=> $Session->append_sid(ACP_INDEX .'?mod=setting&act=preedit_system'),
			'U_TAB_MODULES'		=> $Session->append_sid(ACP_INDEX .'?mod=setting&act=preedit_modules'),
			'U_TAB_OPENCLOSE'	=> $Session->append_sid(ACP_INDEX .'?mod=setting&act=preedit_openclose'),
		));

		switch ($Info->act){
			case "preedit_system":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_system();
				break;
			case "edit_system":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all', 'php');
				$Cache->clear_cache('all', 'html');
				$this->do_edit_system();
				break;
			case "preedit_modules":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_modules();
				break;
			case "edit_modules":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all', 'php');
				$Cache->clear_cache('all', 'html');
				$this->do_edit_modules();
				break;
			case "preedit_openclose":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_openclose();
				break;
			case "edit_openclose":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all', 'php');
				$Cache->clear_cache('all', 'html');
				$this->do_edit_openclose();
				break;
			case "edit_website":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all', 'php');
				$Cache->clear_cache('all', 'html');
				$this->do_edit_website();
				break;
			default:
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_website();
		}
	}

	function pre_edit_website($msg = ""){
		global $Session, $Info, $Template, $Lang, $Func;

		$Info->tpl_main	= "setting_website";

		$Info->get_config();
		$Func->show_language_dirs();
		$Func->show_template_dirs();

		$Template->set_vars(array(
			'ERROR_MSG'						=> $msg,
			'S_ACTION'						=> $Session->append_sid(ACP_INDEX .'?mod=setting&act=edit_website'),
			"U_HELP_TIMEZONE"				=> $Session->append_sid(ACP_INDEX ."?mod=help&code=timezone"),
			"L_PAGE_TITLE"					=> $Lang->data["menu_admin"] . $Lang->data['general_arrow'] . $Lang->data["menu_admin_setting"],
			"L_SITE_NAME"					=> $Lang->data["setting_site_name"],
			"L_SITE_SLOGAN"					=> $Lang->data["setting_site_slogan"],
			"L_SITE_URL"					=> $Lang->data["setting_site_url"],
			"L_SITE_PATH"					=> $Lang->data["setting_site_path"],
			"L_SITE_KEYWORDS"				=> $Lang->data["setting_site_keywords"],
			"L_SITE_DESC"					=> $Lang->data["setting_site_desc"],
			"L_ADMIN_EMAIL"					=> $Lang->data["setting_admin_email"],
			"L_LANGUAGE"					=> $Lang->data["general_language"],
			"L_TEMPLATE"					=> $Lang->data["general_template"],
			"L_TIMEZONE"					=> $Lang->data["setting_timezone"],
			"L_HELP"						=> $Lang->data["general_help"],
			"L_DATE_FORMAT"					=> $Lang->data["setting_date_format"],
			"L_TIME_FORMAT"					=> $Lang->data["setting_time_format"],
			"L_FULL_DATE_TIME_FORMAT"		=> $Lang->data["setting_full_date_time_format"],
			"L_BUTTON"						=> $Lang->data["button_edit"],
			"SITE_NAME"						=> $Info->option['site_name'],
			"SITE_SLOGAN"					=> $Info->option['site_slogan'],
			"SITE_URL"						=> $Info->option['site_url'],
			"SITE_PATH"						=> $Info->option['site_path'],
			"SITE_KEYWORDS"					=> $Info->option['site_keywords'],
			"SITE_DESC"						=> $Info->option['site_desc'],
			"ADMIN_EMAIL"					=> $Info->option['admin_email'],
			"LANGUAGE"						=> $Info->option['language'],
			"TEMPLATE"						=> $Info->option['template'],
			"TIMEZONE"						=> $Info->option['timezone'],
			"DATE_FORMAT"					=> $Info->option['date_format'],
			"TIME_FORMAT"					=> $Info->option['time_format'],
			"FULL_DATE_TIME_FORMAT"			=> $Info->option['full_date_time_format'],
		));
	}

	function do_edit_website(){
		global $Session, $Func, $Info, $DB, $Template, $Lang, $File;

		$setting = array();
		$setting["site_name"]			= isset($_POST["site_name"]) ? htmlspecialchars($_POST["site_name"]) : '';
		$setting["site_slogan"]			= isset($_POST["site_slogan"]) ? htmlspecialchars($_POST["site_slogan"]) : '';
		$setting["site_url"]			= isset($_POST["site_url"])  ? htmlspecialchars($_POST["site_url"]) : '';
		$setting["site_path"]			= isset($_POST["site_path"])  ? htmlspecialchars($_POST["site_path"]) : '';
		$setting["site_keywords"]		= isset($_POST["site_keywords"])  ? htmlspecialchars($_POST["site_keywords"]) : '';
		$setting["site_desc"]			= isset($_POST["site_desc"])  ? htmlspecialchars($_POST["site_desc"]) : '';
		$setting["admin_email"]			= isset($_POST["admin_email"])  ? htmlspecialchars($_POST["admin_email"]) : '';
		$setting["language"]			= isset($_POST["language"]) ? htmlspecialchars($_POST["language"]) : '';
		$setting["template"]			= isset($_POST["template"]) ? htmlspecialchars($_POST["template"]) : '';
		$setting["timezone"]			= isset($_POST["timezone"])  ? htmlspecialchars($_POST["timezone"]) : '';
		$setting["date_format"]			= isset($_POST["date_format"])  ? htmlspecialchars($_POST["date_format"]) : '';
		$setting["time_format"]			= isset($_POST["time_format"])  ? htmlspecialchars($_POST["time_format"]) : '';
		$setting["full_date_time_format"]	= isset($_POST["full_date_time_format"])  ? htmlspecialchars($_POST["full_date_time_format"]) : '';

		if ( !$File->check_dir($setting["template"], "templates") ){
			$this->pre_edit_website($Lang->data['setting_error_template']);
			return false;
		}

		if ( !$File->check_dir($setting["language"], "languages") ){
			$this->pre_edit_website($Lang->data['setting_error_language']);
			return false;
		}

		//We must re-get configurations from db
		$Info->get_config();

		reset($setting);
		while (list($name, $value) = each($setting) ){
			if ( isset($Info->option[$name]) && ($value != $Info->option[$name]) ){
				$DB->query("UPDATE ". $DB->prefix ."config SET config_value='". $value ."' WHERE config_name='". $name ."'");
			}
		}

		if ( ($setting['template'] != $Info->option['template']) || ($setting['language'] != $Info->option['language']) ){
			$Template->set_block_vars("reload", array());//Reload pages
			$Template->set_vars(array(
				'U_EDIT_OPTION'		=> $Session->append_sid(ACP_INDEX .'?mod=setting')
			));
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit');

		$this->pre_edit_website($Lang->data['general_success_update']);
		return true;
	}

	function pre_edit_system($msg = ""){
		global $Session, $Info, $Template, $Lang, $Func;

		$Info->tpl_main	= "setting_system";

		$Info->get_config();
		$Func->show_language_dirs();
		$Func->show_template_dirs();

		$Template->set_vars(array(
			'ERROR_MSG'						=> $msg,
			'S_ACTION'						=> $Session->append_sid(ACP_INDEX .'?mod=setting&act=edit_system'),
			"L_PAGE_TITLE"					=> $Lang->data["menu_admin"] . $Lang->data['general_arrow'] . $Lang->data["menu_admin_setting"],
			"L_ITEMS_PER_PAGE"				=> $Lang->data["setting_items_per_page"],
			"L_ITEMS_PER_PAGE_DESC"			=> $Lang->data["setting_items_per_page_desc"],
			"L_IMAGE_TYPE"					=> $Lang->data["setting_image_type"],
			"L_IMAGE_TYPE_DESC"				=> $Lang->data["setting_image_type_desc"],
			"L_IMAGE_MAX_SIZE"				=> $Lang->data["setting_image_max_size"],
			"L_IMAGE_MAX_SIZE_DESC"			=> $Lang->data["setting_image_max_size_desc"],
			"L_TIME_LOGIN"					=> $Lang->data["setting_time_login"],
			"L_TIME_LOGIN_DESC"				=> $Lang->data["setting_time_login_desc"],
			"L_COOKIE"						=> $Lang->data["setting_cookie"],
			"L_COOKIE_DOMAIN"				=> $Lang->data["setting_cookie_domain"],
			"L_COOKIE_DOMAIN_DESC"			=> $Lang->data["setting_cookie_domain_desc"],
			"L_COOKIE_PATH"					=> $Lang->data["setting_cookie_path"],
			"L_COOKIE_TIME"					=> $Lang->data["setting_cookie_time"],
			"L_COOKIE_TIME_DESC"			=> $Lang->data["setting_cookie_time_desc"],
			"L_SMTP"						=> $Lang->data["setting_smtp"],
			"L_SMTP_HOST"					=> $Lang->data["setting_smtp_host"],
			"L_SMTP_USERNAME"				=> $Lang->data["setting_smtp_username"],
			"L_SMTP_PASSWORD"				=> $Lang->data["setting_smtp_password"],
			"L_HIDDEN_DESC"					=> $Lang->data["setting_hidden_desc"],
			"L_FTP"							=> $Lang->data["setting_ftp"],
			"L_FTP_HOST"					=> $Lang->data["setting_ftp_host"],
			"L_FTP_PORT"					=> $Lang->data["setting_ftp_port"],
			"L_FTP_USERNAME"				=> $Lang->data["setting_ftp_username"],
			"L_FTP_PASSWORD"				=> $Lang->data["setting_ftp_password"],
			"L_LOG"							=> $Lang->data["setting_log"],
			"L_LOG_DAYS"					=> $Lang->data["setting_log_days"],
			"L_LOG_DAYS_DESC"				=> $Lang->data["setting_log_days_desc"],
			"L_CACHE"						=> $Lang->data["setting_cache"],
			"L_CACHE_DESC"					=> $Lang->data["setting_cache_desc"],
			"L_CACHE_EXPIRE"				=> $Lang->data["setting_cache_expire"],
			"L_CACHE_EXPIRE_DESC"			=> $Lang->data["setting_cache_expire_desc"],
			"L_SHORT_URL"					=> $Lang->data["setting_short_url"],
			"L_SHORT_URL_DESC"				=> $Lang->data["setting_short_url_desc"],
			"L_URL_SEP"						=> $Lang->data["setting_short_url_sep"],
			"L_URL_SEP_DESC"				=> $Lang->data["setting_short_url_sep_desc"],
			"L_BUTTON"						=> $Lang->data["button_edit"],
			"ITEMS_PER_PAGE"				=> $Info->option['items_per_page'],
			"IMAGE_TYPE"					=> $Info->option['image_type'],
			"IMAGE_MAX_SIZE"				=> $Info->option['image_max_size'],
			"TIME_LOGIN"					=> $Info->option['time_login'],
			"COOKIE_DOMAIN"					=> $Info->option['cookie_domain'],
			"COOKIE_PATH"					=> $Info->option['cookie_path'],
			"COOKIE_TIME"					=> $Info->option['cookie_time'],
			"SMTP_HOST"						=> $Info->option['smtp_host'],
			"SMTP_USERNAME"					=> $Info->option['smtp_username'],
			"SMTP_PASSWORD"					=> $Info->option['smtp_password'],
			"FTP_HOST"						=> $Info->option['ftp_host'],
			"FTP_PORT"						=> $Info->option['ftp_port'],
			"FTP_USERNAME"					=> $Info->option['ftp_username'],
			"FTP_PASSWORD"					=> $Info->option['ftp_password'],
			"LOG_SAVE"						=> $Info->option['log_save'],
			"LOG_DAYS"						=> $Info->option['log_days'],
			"CACHE_ENABLED"					=> $Info->option['cache_enabled'],
			"CACHE_EXPIRE"					=> $Info->option['cache_expire'],
			"SHORT_URL_ENABLED"				=> $Info->option['short_url_enabled'],
			"URL_SEP"						=> $Info->option['short_url_sep'],
		));
	}

	function do_edit_system(){
		global $Session, $Func, $Info, $DB, $Template, $Lang;

		$setting = array();
		$setting["items_per_page"]		= isset($_POST["items_per_page"]) ? intval($_POST["items_per_page"]) : 30;
		$setting["image_type"]			= isset($_POST["image_type"])  ? htmlspecialchars($_POST["image_type"]) : '';
		$setting["image_max_size"]		= isset($_POST["image_max_size"])  ? intval($_POST["image_max_size"]) : 0;
		$setting["time_login"]			= isset($_POST["time_login"])  ? intval($_POST["time_login"]) : 0;
		$setting["cookie_domain"]		= isset($_POST["cookie_domain"])  ? htmlspecialchars($_POST["cookie_domain"]) : '';
		$setting["cookie_path"]			= isset($_POST["cookie_path"])  ? htmlspecialchars($_POST["cookie_path"]) : '';
		$setting["cookie_time"]			= isset($_POST["cookie_time"])  ? intval($_POST["cookie_time"]) : 2592000;
		$setting["smtp_host"]			= isset($_POST["smtp_host"])  ? htmlspecialchars($_POST["smtp_host"]) : '';
		$setting["smtp_username"]		= isset($_POST["smtp_username"])  ? htmlspecialchars($_POST["smtp_username"]) : '';
		$smtp_password					= isset($_POST["smtp_password"])  ? htmlspecialchars($_POST["smtp_password"]) : '';
		$setting["ftp_host"]			= isset($_POST["ftp_host"])  ? htmlspecialchars($_POST["ftp_host"]) : '';
		$setting["ftp_port"]			= isset($_POST["ftp_port"])  ? htmlspecialchars($_POST["ftp_port"]) : '';
		$setting["ftp_username"]		= isset($_POST["ftp_username"])  ? htmlspecialchars($_POST["ftp_username"]) : '';
		$ftp_password					= isset($_POST["ftp_password"])  ? htmlspecialchars($_POST["ftp_password"]) : '';
		$setting["log_save"]			= isset($_POST["log_save"])  ? intval($_POST["log_save"]) : 0;
		$setting["log_days"]			= isset($_POST["log_days"])  ? intval($_POST["log_days"]) : 0;
		$setting["cache_enabled"]		= isset($_POST["cache_enabled"])  ? intval($_POST["cache_enabled"]) : 0;
		$setting["cache_expire"]		= isset($_POST["cache_expire"])  ? intval($_POST["cache_expire"]) : 0;
		$setting["short_url_enabled"]	= isset($_POST["short_url_enabled"])  ? intval($_POST["short_url_enabled"]) : 0;
		$setting["short_url_sep"]		= isset($_POST["short_url_sep"])  ? htmlspecialchars($_POST["short_url_sep"]) : '/';

		//SMTP Password
		if ( !empty($setting["smtp_host"]) ){
			if ( !empty($smtp_password) ){
				$setting['smtp_password']	= $smtp_password;
			}
		}
		else{
			$setting['smtp_password']	= "";
		}

		//FTP Password
		if ( !empty($setting["ftp_host"]) ){
			if ( !empty($ftp_password) ){
				$setting['ftp_password']	= $ftp_password;
			}
		}
		else{
			$setting['ftp_password']	= "";
		}

		//Update settings
		reset($setting);
		while (list($name, $value) = each($setting) ){
			if ( isset($Info->option[$name]) && ($value != $Info->option[$name]) ){
				$DB->query("UPDATE ". $DB->prefix ."config SET config_value='". $value ."' WHERE config_name='". $name ."'");
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit');

		$this->pre_edit_system($Lang->data['general_success_update']);
		return true;
	}

	function pre_edit_modules($msg = ""){
		global $Session, $Info, $Template, $Lang, $Func;

		$Info->tpl_main	= "setting_modules";

		$Info->get_config();
		$Func->show_language_dirs();
		$Func->show_template_dirs();

		$Template->set_vars(array(
			'ERROR_MSG'						=> $msg,
			'S_ACTION'						=> $Session->append_sid(ACP_INDEX .'?mod=setting&act=edit_modules'),
			"L_PAGE_TITLE"					=> $Lang->data["menu_admin"] . $Lang->data['general_arrow'] . $Lang->data["menu_admin_setting"],

			"L_POLL"						=> $Lang->data["setting_poll"],
			"L_POLL_OPTIONS"				=> $Lang->data["setting_poll_options"],
			"L_TIME_REVOTE"					=> $Lang->data["setting_revote_time"],
			"L_TIME_REVOTE_DESC"			=> $Lang->data["setting_revote_time_desc"],

			"L_LATEST_BOX"					=> $Lang->data["setting_latest_box"],
			"L_LATEST_BOX_TYPE"			=> $Lang->data["setting_latest_box_type"],
			"L_LATEST_BOX_ITEMS"			=> $Lang->data["setting_latest_box_items"],
			"L_LATEST_ARTICLES"			=> $Lang->data["setting_latest_articles"],
			"L_TODAY_ARTICLES"				=> $Lang->data["setting_today_articles"],
			"L_NOT_DISPLAY"					=> $Lang->data["setting_not_display"],

			"L_OTHER_BOXES"					=> $Lang->data["setting_other_boxes"],
			"L_NEWSLETTER"					=> $Lang->data["setting_newsletter"],
			"L_EVENT"						=> $Lang->data["setting_event"],
			"L_NEWSPIC"						=> $Lang->data["setting_newspic"],

			//"L_GZIP"						=> $Lang->data["setting_gzip"],
			//"L_GZIP_DESC"					=> $Lang->data["setting_gzip_desc"],
			//"L_GZIP_LEVEL"					=> $Lang->data["setting_gzip_level"],
			//"L_GZIP_LEVEL_DESC"				=> $Lang->data["setting_gzip_level_desc"],

			"L_ARTICLE"						=> $Lang->data["setting_article"],
			"L_RATING"						=> $Lang->data["setting_rating"],
			"L_RATING_DESC"					=> $Lang->data["setting_rating_desc"],
			"L_COMMENT"						=> $Lang->data["setting_comment"],
			"L_COMMENT_DESC"				=> $Lang->data["setting_comment_desc"],
			"L_WYSIWYG_TITLE"				=> $Lang->data["setting_wysiwyg_title"],
			"L_WYSIWYG_TITLE_DESC"			=> $Lang->data["setting_wysiwyg_title_desc"],
			"L_MENUCAT_LEVEL"				=> $Lang->data["setting_menucat_level"],
			"L_ARCHIVED_DEFAULT"			=> $Lang->data["setting_archived_default"],
			"L_ALL"							=> $Lang->data["general_all"],

			"L_HOME_FOCUS_LIMIT"			=> $Lang->data["setting_home_focus_limit"],
			"L_HOME_FOCUS_COLS"				=> $Lang->data["setting_home_focus_cols"],
			"L_HOME_HOT_LIMIT"				=> $Lang->data["setting_home_hot_limit"],
			"L_HOME_LATEST_LIMIT"			=> $Lang->data["setting_home_latest_limit"],
			"L_HOME_CAT_COLS"				=> $Lang->data["setting_home_cat_cols"],
			"L_HOME_CAT_ARTICLE_LIMIT"		=> $Lang->data["setting_home_cat_article_limit"],

			"L_LIMIT_HOME_NEWS"				=> $Lang->data["setting_limit_home_news"],
			"L_LIMIT_HOME_NEWS_NEXT"		=> $Lang->data["setting_limit_home_news_next"],
			"L_LIMIT_HOME_HOT"				=> $Lang->data["setting_limit_home_hot"],
			"L_LIMIT_HOME_COMMENT"			=> $Lang->data["setting_limit_home_comment"],
			"L_NEWSPIC_RAND_LIMIT"			=> $Lang->data["setting_newspic_rand_limit"],
			"L_NEWSPIC_RAND_TIME"			=> $Lang->data["setting_newspic_rand_time"],

			"L_ARTICLE_IMAGE"				=> $Lang->data["setting_article_image"],
			"L_THUMB_LARGE"					=> $Lang->data["setting_thumb_large"],
			"L_THUMB_SMALL"					=> $Lang->data["setting_thumb_small"],
			"L_THUMB_ICON"					=> $Lang->data["setting_thumb_icon"],
			"L_NEWSPIC_THUMB"				=> $Lang->data["setting_newspic_thumb"],
			"L_NEWSPIC_FULL"				=> $Lang->data["setting_newspic_full"],
			"L_MAX_WIDTH"					=> $Lang->data["setting_max_width"],
			"L_MAX_HEIGHT"					=> $Lang->data["setting_max_height"],
			"L_BUTTON"						=> $Lang->data["button_edit"],

			"POLL_ENABLED"					=> $Info->option['poll_enabled'],
			"POLL_OPTIONS"					=> $Info->option['poll_options'],
			"TIME_REVOTE"					=> $Info->option['time_revote'],

			"LATEST_BOX_TYPE"				=> $Info->option['latest_box_type'],
			"LATEST_BOX_ITEMS"				=> $Info->option['latest_box_items'],

			"NEWSLETTER_ENABLED"			=> $Info->option['newsletter_enabled'],
			"EVENT_ENABLED"					=> $Info->option['event_enabled'],
			"NEWSPIC_ENABLED"				=> $Info->option['newspic_enabled'],
			"GZIP"							=> $Info->option['gzip'],
			"GZIP_LEVEL"					=> $Info->option['gzip_level'],

			"RATING_ENABLED"				=> $Info->option['rating_enabled'],
			"COMMENT_ENABLED"				=> $Info->option['comment_enabled'],
			"ENABLE_ARTICLE_WYSIWYG_TITLE"	=> $Info->option['enable_article_wysiwyg_title'],
			"MENUCAT_LEVEL"					=> $Info->option['menucat_level'],
			"ARCHIVED_DEFAULT"				=> $Info->option['archived_default'],

			"HOME_FOCUS_LIMIT"				=> $Info->option['home_focus_limit'],
			"HOME_FOCUS_COLS"				=> $Info->option['home_focus_cols'],
			"HOME_HOT_LIMIT"				=> $Info->option['home_hot_limit'],
			"HOME_LATEST_LIMIT"			=> $Info->option['home_latest_limit'],
			"HOME_CAT_COLS"					=> $Info->option['home_cat_cols'],
			"HOME_CAT_ARTICLE_LIMIT"		=> $Info->option['home_cat_article_limit'],

			"LIMIT_HOME_NEWS"				=> $Info->option['limit_home_news'],
			"LIMIT_HOME_NEWS_NEXT"			=> $Info->option['limit_home_news_next'],
			"LIMIT_HOME_HOT"				=> $Info->option['limit_home_hot'],
			"LIMIT_HOME_COMMENT"			=> $Info->option['limit_home_comment'],
			"NEWSPIC_RAND_LIMIT"			=> $Info->option['newspic_rand_limit'],
			"NEWSPIC_RAND_TIME"				=> $Info->option['newspic_rand_time'],

			"THUMB_LARGE_MAX_WIDTH"			=> $Info->option['thumb_large_max_width'],
			"THUMB_LARGE_MAX_HEIGHT"		=> $Info->option['thumb_large_max_height'],
			"THUMB_SMALL_MAX_WIDTH"			=> $Info->option['thumb_small_max_width'],
			"THUMB_SMALL_MAX_HEIGHT"		=> $Info->option['thumb_small_max_height'],
			"THUMB_ICON_MAX_WIDTH"			=> $Info->option['thumb_icon_max_width'],
			"THUMB_ICON_MAX_HEIGHT"			=> $Info->option['thumb_icon_max_height'],
			"NEWSPIC_THUMB_MAX_WIDTH"		=> $Info->option['newspic_thumb_max_width'],
			"NEWSPIC_THUMB_MAX_HEIGHT"		=> $Info->option['newspic_thumb_max_height'],
			"NEWSPIC_FULL_MAX_WIDTH"		=> $Info->option['newspic_full_max_width'],
			"NEWSPIC_FULL_MAX_HEIGHT"		=> $Info->option['newspic_full_max_height'],
		));
	}

	function do_edit_modules(){
		global $Session, $Func, $Info, $DB, $Template, $Lang;

		$setting = array();
		$setting["poll_enabled"]		= isset($_POST["poll_enabled"])  ? intval($_POST["poll_enabled"]) : 0;
		$setting["poll_options"]		= isset($_POST["poll_options"])  ? intval($_POST["poll_options"]) : 7;
		$setting["time_revote"]			= isset($_POST["time_revote"])  ? intval($_POST["time_revote"]) : 0;

		$setting["latest_box_type"]	= isset($_POST["latest_box_type"])  ? intval($_POST["latest_box_type"]) : 0;
		$setting["latest_box_items"]	= isset($_POST["latest_box_items"])  ? intval($_POST["latest_box_items"]) : 15;

		$setting["newsletter_enabled"]	= isset($_POST["newsletter_enabled"])  ? intval($_POST["newsletter_enabled"]) : 0;
		$setting["event_enabled"]		= isset($_POST["event_enabled"])  ? intval($_POST["event_enabled"]) : 0;
		$setting["newspic_enabled"]		= isset($_POST["newspic_enabled"])  ? intval($_POST["newspic_enabled"]) : 0;

		$setting["rating_enabled"]		= isset($_POST["rating_enabled"])  ? intval($_POST["rating_enabled"]) : 0;
		$setting["comment_enabled"]		= isset($_POST["comment_enabled"])  ? intval($_POST["comment_enabled"]) : 0;
		$setting["enable_article_wysiwyg_title"]	= isset($_POST["enable_article_wysiwyg_title"])  ? intval($_POST["enable_article_wysiwyg_title"]) : 0;
		$setting["menucat_level"]			= isset($_POST["menucat_level"])  ? intval($_POST["menucat_level"]) : 1;
		$setting["archived_default"]		= isset($_POST["archived_default"])  ? intval($_POST["archived_default"]) : -1;

		$setting["home_focus_limit"]		= isset($_POST["home_focus_limit"])  ? intval($_POST["home_focus_limit"]) : 1;
		$setting["home_focus_cols"]			= isset($_POST["home_focus_cols"])  ? intval($_POST["home_focus_cols"]) : 1;
		$setting["home_hot_limit"]			= isset($_POST["home_hot_limit"])  ? intval($_POST["home_hot_limit"]) : 1;
		$setting["home_latest_limit"]		= isset($_POST["home_latest_limit"])  ? intval($_POST["home_latest_limit"]) : 1;
		$setting["home_cat_cols"]			= isset($_POST["home_cat_cols"])  ? intval($_POST["home_cat_cols"]) : 1;
		$setting["home_cat_article_limit"]	= isset($_POST["home_cat_article_limit"])  ? intval($_POST["home_cat_article_limit"]) : 1;

		$setting["limit_home_news"]			= isset($_POST["limit_home_news"])  ? intval($_POST["limit_home_news"]) : 1;
		$setting["limit_home_news_next"]	= isset($_POST["limit_home_news_next"])  ? intval($_POST["limit_home_news_next"]) : 0;
		$setting["limit_home_hot"]			= isset($_POST["limit_home_hot"])  ? intval($_POST["limit_home_hot"]) : 0;
		$setting["limit_home_comment"]		= isset($_POST["limit_home_hot"])  ? intval($_POST["limit_home_comment"]) : 0;
		$setting["newspic_rand_limit"]		= isset($_POST["newspic_rand_limit"])  ? intval($_POST["newspic_rand_limit"]) : 0;
		$setting["newspic_rand_time"]		= isset($_POST["newspic_rand_time"])  ? intval($_POST["newspic_rand_time"]) : 0;

		$setting["thumb_large_max_width"]	= isset($_POST["thumb_large_max_width"])  ? intval($_POST["thumb_large_max_width"]) : 0;
		$setting["thumb_large_max_height"]	= isset($_POST["thumb_large_max_height"])  ? intval($_POST["thumb_large_max_height"]) : 0;
		$setting["thumb_small_max_width"]	= isset($_POST["thumb_small_max_width"])  ? intval($_POST["thumb_small_max_width"]) : 0;
		$setting["thumb_small_max_height"]	= isset($_POST["thumb_small_max_height"])  ? intval($_POST["thumb_small_max_height"]) : 0;
		$setting["thumb_icon_max_width"]	= isset($_POST["thumb_icon_max_width"])  ? intval($_POST["thumb_icon_max_width"]) : 0;
		$setting["thumb_icon_max_height"]	= isset($_POST["thumb_icon_max_height"])  ? intval($_POST["thumb_icon_max_height"]) : 0;
		$setting["newspic_thumb_max_width"]	= isset($_POST["newspic_thumb_max_width"])  ? intval($_POST["newspic_thumb_max_width"]) : 0;
		$setting["newspic_thumb_max_height"]= isset($_POST["newspic_thumb_max_height"])  ? intval($_POST["newspic_thumb_max_height"]) : 0;
		$setting["newspic_full_max_width"]	= isset($_POST["newspic_full_max_width"])  ? intval($_POST["newspic_full_max_width"]) : 0;
		$setting["newspic_full_max_height"]	= isset($_POST["newspic_full_max_height"])  ? intval($_POST["newspic_full_max_height"]) : 0;

		reset($setting);
		while (list($name, $value) = each($setting) ){
			if ( isset($Info->option[$name]) && ($value != $Info->option[$name]) ){
				$DB->query("UPDATE ". $DB->prefix ."config SET config_value='". $value ."' WHERE config_name='". $name ."'");
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit');

		$this->pre_edit_modules($Lang->data['general_success_update']);
		return true;
	}

	function pre_edit_openclose($msg = ""){
		global $Session, $Info, $Template, $Lang, $Func;

		$Info->tpl_main	= "setting_openclose";

		$Info->get_config();
		$Func->show_language_dirs();
		$Func->show_template_dirs();

		$Template->set_vars(array(
			'ERROR_MSG'						=> $msg,
			'S_ACTION'						=> $Session->append_sid(ACP_INDEX .'?mod=setting&act=edit_openclose'),
			"L_PAGE_TITLE"					=> $Lang->data["menu_admin"] . $Lang->data['general_arrow'] . $Lang->data["menu_admin_setting"],
			"L_WEBSITE_CLOSE"				=> $Lang->data["setting_website_close"],
			"L_WEBSITE_CLOSE_DESC"			=> $Lang->data["setting_website_close_desc"],
			"L_WEBSITE_CLOSE_MESSAGE"		=> $Lang->data["setting_website_close_message"],
			"L_BUTTON"						=> $Lang->data["button_edit"],
			"WEBSITE_CLOSE"					=> $Info->option['website_close'],
			"WEBSITE_CLOSE_MESSAGE"			=> $Info->option['website_close_message'],
		));
	}

	function do_edit_openclose(){
		global $Session, $Func, $Info, $DB, $Template, $Lang;

		$setting = array();
		$setting["website_close"]			= isset($_POST["website_close"])  ? intval($_POST["website_close"]) : 0;
		$setting["website_close_message"]	= isset($_POST["website_close_message"])  ? htmlspecialchars($_POST["website_close_message"]) : '';

		reset($setting);
		while (list($name, $value) = each($setting) ){
			if ( isset($Info->option[$name]) && ($value != $Info->option[$name]) ){
				$DB->query("UPDATE ". $DB->prefix ."config SET config_value='". $value ."' WHERE config_name='". $name ."'");
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit');

		$this->pre_edit_openclose($Lang->data['general_success_update']);
		return true;
	}
}
?>