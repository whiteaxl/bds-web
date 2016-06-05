<?php
/* =============================================================== *\
|		Module name: Admin RSS Import								|
|		Module version: 1.1											|
|		Begin: 28 May 2006											|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_rss_import');
define('FUNC_ACT_VIEW', 'preedit');
//RSS reader
include('./includes/rss_reader.php');
$RSS	= new Rss_Reader;

include('./modules/admin/ad_article_global.php');
$ArticleGlobal	= new Admin_Article_Global;

//Module language
$Func->import_module_language("admin/lang_rss_import". PHP_EX);

$AdminRssImport = new Admin_Rss_Import;

class Admin_Rss_Import
{
	var $data			= array();
	var $page			= 1;

	var $user_perm		= array();

	function Admin_Rss_Import(){
		global $Info, $Func, $Cache;

		$this->user_perm	= $Func->get_all_perms('menu_rss_import');
		$this->get_filter();
		$this->get_all_cats();
		$this->set_all_cats(0, 0);

		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_rss();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->do_add_rss();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_rss();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all');
				$this->do_edit_rss();
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$this->delete_rsses();
				break;
			case "update":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->update_order();
				break;
			case "resync":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->resync_rsses();
				break;
			case "preimport":
				$Func->check_user_perm($this->user_perm, 'import');
				$this->pre_import_rss();
				break;
			case "import":
				$Func->check_user_perm($this->user_perm, 'import');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->do_import_rss();
				break;
			case "preremove":
				$Func->check_user_perm($this->user_perm, 'import');
				$this->pre_remove_rss();
				break;
			case "remove":
				$Func->check_user_perm($this->user_perm, 'import');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->do_remove_rss();
				break;
			default:
				$this->list_rsses();
		}
	}

	function get_filter(){
		global $Template;

		$this->filter['url_append']		= "";

		$this->filter['keyword']		= isset($_POST["fkeyword"]) ? htmlspecialchars($_POST["fkeyword"]) : '';
		if ( empty($this->filter['keyword']) ){
			$this->filter['keyword']	= isset($_GET["fkeyword"]) ? htmlspecialchars(urldecode($_GET["fkeyword"])) : '';
		}
		if ( !empty($this->filter['keyword']) ){
			$this->filter['url_append']	.= '&fkeyword='. $this->filter['keyword'];
		}

		$this->page		= (isset($_GET["page"]) && ($_GET["page"] > 0)) ? intval($_GET["page"]) : 1;

		$Template->set_vars(array(
			'FKEYWORD'		=> $this->filter['keyword'],
		));
	}

	function list_rsses($msg = ""){
		global $Session, $Func, $DB, $Info, $Template, $Lang;

		$Info->tpl_main		= "rss_import_list";
		$itemperpage		= $Info->option['items_per_page'];
		$date_format		= $Info->option['time_format'];
		$timezone			= $Info->option['timezone'] * 3600;

		//Filter ----------------------------------
		$where_sql = " WHERE rss_id>0";
		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*", "%", $this->filter['keyword']);
			$where_sql	.= " AND (rss_title LIKE '%". $key ."%' OR rss_url LIKE '%". $key ."%' OR article_username LIKE '%". $key ."%' OR article_author LIKE '%". $key ."%')";
		}
		//-----------------------------------------

		//Generate pages
		$DB->query("SELECT count(rss_id) AS total FROM ". $DB->prefix ."rss_import ". $where_sql);
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX .'?mod=rss_import') );
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query("SELECT * FROM ". $DB->prefix ."rss_import ". $where_sql ." ORDER BY rss_order ASC LIMIT ". $pageshow['start'] .", $itemperpage");
		$rss_count		= $DB->num_rows();
		$rss_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$rss_count; $i++){
			$Template->set_block_vars("rssrow", array(
				"ID"				=> $rss_data[$i]["rss_id"],
				"TITLE"				=> $rss_data[$i]["rss_title"],
				"URL"				=> $rss_data[$i]["rss_url"],
				"URL_AUTO_IMPORT"	=> $Info->option['site_url'] . AJAX_INDEX .'?mod='. MOD_AJAX_RSS_IMPORT .'&rss_id='. $rss_data[$i]["rss_id"] .'&rss_code='. md5($rss_data[$i]["rss_url"] .'__'.  $rss_data[$i]["posted_date"]),
				"AUTO_IMPORT_TEXT"	=> $Info->option['site_url'] . AJAX_INDEX .'?mod='. MOD_AJAX_RSS_IMPORT .'&<br>rss_id='. $rss_data[$i]["rss_id"] .'&rss_code='. md5($rss_data[$i]["rss_url"] .'__'.  $rss_data[$i]["posted_date"]),
				"ORDER"				=> $rss_data[$i]["rss_order"],
				"LAST_DATE"			=> $rss_data[$i]["last_import_date"] ? $Func->translate_date(gmdate($date_format, $rss_data[$i]["last_import_date"] + $timezone)) : '&nbsp;',
				"ARTICLES"			=> $rss_data[$i]["import_counter"],
				'BG_CSS'			=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				'U_VIEW'			=> $Session->append_sid(ACP_INDEX .'?mod=rss_import&act=manage&lid='. $rss_data[$i]["rss_id"]),
				'U_EDIT'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=rss_import&act=preedit&id='. $rss_data[$i]["rss_id"] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
			));
		}

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			"PAGE_OUT"				=> $pageshow['page'],
			'S_ACTION_FILTER'		=> $Session->append_sid(ACP_INDEX .'?mod=rss_import'),
			'U_UPDATE'				=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="javascript: updateForm2(\''. $Session->append_sid(ACP_INDEX .'?mod=rss_import&act=update' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/update.gif" alt="" title="'. $Lang->data['general_update'] .'" border="0" align="absbottom">'. $Lang->data['general_update'] .'</a> &nbsp; &nbsp;' : '',
			'U_ADD'					=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=rss_import&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="'. $Lang->data['general_add'] .'" border="0" align="absbottom">'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp;' : '',
			'U_RESYNC'				=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=rss_import&act=resync' . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/resync.gif" alt="" title="'. $Lang->data['general_resync'] .'" border="0" align="absbottom">'. $Lang->data['general_resync'] .'</a> &nbsp; &nbsp;' : '',
			'U_IMPORT_FEEDS'		=> $Func->check_user_perm($this->user_perm, 'import', 0) ? '<a href="javascript: updateForm2(\''. $Session->append_sid(ACP_INDEX .'?mod=rss_import&act=preimport' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/import.gif" alt="" title="'. $Lang->data['rss_import_feed'] .'" border="0" align="absbottom">'. $Lang->data['rss_import_feed'] .'</a> &nbsp; &nbsp;' : '',
			'U_REMOVE_FEEDS'		=> $Func->check_user_perm($this->user_perm, 'import', 0) ? '<a href="javascript: updateForm2(\''. $Session->append_sid(ACP_INDEX .'?mod=rss_import&act=preremove' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/export.gif" alt="" title="'. $Lang->data['rss_import_feed'] .'" border="0" align="absbottom">'. $Lang->data['rss_import_remove_feed'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'				=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript: deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=rss_import&act=del' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" align="absbottom" border=0>'. $Lang->data['general_del'] .'</a> &nbsp; &nbsp;' : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_rss"] . $Lang->data['general_arrow'] . $Lang->data["menu_rss_import"],
			"L_BUTTON_SEARCH"		=> $Lang->data["button_search"],
			"L_ORDER"				=> $Lang->data["general_order"],
			"L_TITLE"				=> $Lang->data["rss_import_title"],
			"L_AUTO_IMPORT"			=> $Lang->data["rss_import_auto"],
			"L_LAST_DATE"			=> $Lang->data["rss_import_last_date"],
			"L_ARTICLES"			=> $Lang->data["rss_import_articles"],
			"L_ADDED_DATE"			=> $Lang->data["general_addded_date"],
			'L_DEL_CONFIRM'			=> $Lang->data['rss_import_del_confirm'],
			'L_CHOOSE_ITEM'			=> $Lang->data['rss_import_error_not_check'],
		));
	}

	function pre_add_rss($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$Info->tpl_main		= "rss_import_edit";
		$this->set_lang();

//		$today		= getdate();
//		$month		= $today['mon'];
//		$day		= $today['mday'];
//		$year		= $today['year'];
//		$time		= $today['hours'] .":". $today['minutes'];

		$Template->set_block_vars("addrow", array());
		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=rss_import&act=add'),
			'CAT_ID'				=> isset($this->data['cat_id']) ? $this->data['cat_id'] : '',
			'RSS_TITLE'				=> isset($this->data['rss_title']) ? stripslashes($this->data['rss_title']) : '',
			'RSS_URL'				=> isset($this->data['rss_url']) ? stripslashes($this->data['rss_url']) : 'http://',
			'RSS_IMPORT_PERGO'		=> isset($this->data['rss_import_pergo']) ? $this->data['rss_import_pergo'] : 50,
			'RSS_CONVERT_CHARSET'	=> isset($this->data['rss_convert_charset']) ? $this->data['rss_convert_charset'] : '',
			'RSS_AUTH'				=> isset($this->data['rss_auth']) ? $this->data['rss_auth'] : '',
			'RSS_AUTH_USER'			=> isset($this->data['rss_auth_user']) ? stripslashes($this->data['rss_auth_user']) : '',
			'RSS_AUTH_PASS'			=> isset($this->data['rss_auth_pass']) ? stripslashes($this->data['rss_auth_pass']) : '',
			'ARTICLE_CAT_ID'		=> isset($this->data['article_cat_id']) ? $this->data['article_cat_id'] : '',
			"ARTICLE_TYPE"			=> isset($this->data["article_type"]) ? $this->data["article_type"] : '',
			'ARTICLE_REMOVE_HTML'	=> isset($this->data['article_remove_html']) ? $this->data['article_remove_html'] : '',
			'ARTICLE_SHOW_LINK'		=> isset($this->data['article_show_link']) ? $this->data['article_show_link'] : '',
			'ARTICLE_AUTHOR'		=> isset($this->data['article_author']) ? stripslashes($this->data['article_author']) : '',
			'ARTICLE_USERNAME'		=> isset($this->data['article_username']) ? stripslashes($this->data['article_username']) : $Info->user_info['username'],
			'ARTICLE_USERPOST_INCREASE'	=> isset($this->data['article_userpost_increase']) ? $this->data['article_userpost_increase'] : '',
			'ARTICLE_ENABLED'		=> isset($this->data['article_enabled']) ? $this->data['article_enabled'] : '',
			"PAGE_TO"				=> isset($this->data["page_to"]) ? $this->data["page_to"] : '',			
			"L_PAGE_TITLE"			=> $Lang->data["menu_rss"] . $Lang->data['general_arrow'] . $Lang->data["menu_rss_import"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			"L_BUTTON"				=> $Lang->data["button_add"],
		));
	}

	function set_lang(){
		global $Template, $Lang;

		$Template->set_vars(array(
			"L_RSS_IMPORT_BASICS"				=> $Lang->data["rss_import_basics"],
			"L_RSS_TITLE"						=> $Lang->data["rss_import_title"],
			"L_RSS_URL"							=> $Lang->data["rss_import_url"],
			"L_RSS_URL_DESC"					=> $Lang->data["rss_import_url_desc"],
			"L_RSS_IMPORT_PERGO"				=> $Lang->data["rss_import_pergo"],
			"L_RSS_IMPORT_PERGO_DESC"			=> $Lang->data["rss_import_pergo_desc"],
			"L_RSS_CONVERT_CHARSET"				=> $Lang->data["rss_import_convert_charset"],
			"L_RSS_CONVERT_CHARSET_DESC"		=> $Lang->data["rss_import_convert_charset_desc"],
			"L_RSS_STATUS"						=> $Lang->data["rss_import_status"],
			"L_RSS_IMPORT_AUTH"					=> $Lang->data["rss_import_auth"],
			"L_RSS_REQUIRE_AUTH"				=> $Lang->data["rss_import_require_auth"],
			"L_RSS_REQUIRE_AUTH_DESC"			=> $Lang->data["rss_import_require_auth_desc"],
			"L_RSS_AUTH_USER"					=> $Lang->data["rss_import_auth_user"],
			"L_RSS_AUTH_PASS"					=> $Lang->data["rss_import_auth_pass"],
			"L_RSS_IMPORT_CONTENTS"				=> $Lang->data["rss_import_contents"],
			"L_ARTICLE_CAT"						=> $Lang->data["rss_import_cat"],
			"L_ARTICLE_CAT_DESC"				=> $Lang->data["rss_import_cat_desc"],
			"L_ARTICLE_TYPE"					=> $Lang->data["rss_import_article_type"],
			"L_ARTICLE_TYPE_TIP"				=> $Lang->data["rss_import_article_type_tip"],
			"L_ARTICLE_TYPE_FULL"				=> $Lang->data["rss_import_article_type_full"],
			"L_ARTICLE_TYPE_SUMMARY"			=> $Lang->data["rss_import_article_type_summary"],
			"L_ARTICLE_TYPE_LINK"				=> $Lang->data["rss_import_article_type_link"],
			"L_ARTICLE_REMOVE_HTML"				=> $Lang->data["rss_import_remove_html"],
			"L_ARTICLE_REMOVE_HTML_DESC"		=> $Lang->data["rss_import_remove_html_desc"],
			"L_ARTICLE_SHOW_LINK"				=> $Lang->data["rss_import_show_link"],
			"L_ARTICLE_SHOW_LINK_DESC"			=> $Lang->data["rss_import_show_link_desc"],
			"L_ARTICLE_AUTHOR"					=> $Lang->data["rss_import_author"],
			"L_ARTICLE_AUTHOR_DESC"				=> $Lang->data["rss_import_author_desc"],
			"L_ARTICLE_USERNAME"				=> $Lang->data["rss_import_username"],
			"L_ARTICLE_USERNAME_DESC"			=> $Lang->data["rss_import_username_desc"],
			"L_ARTICLE_USERPOST_INCREASE"		=> $Lang->data["rss_import_userpost_increase"],
			"L_ARTICLE_USERPOST_INCREASE_DESC"	=> $Lang->data["rss_import_userpost_increase_desc"],
			"L_ARTICLE_STATUS"					=> $Lang->data["rss_import_article_status"],
			"L_ARTICLE_STATUS_DESC"				=> $Lang->data["rss_import_article_status_desc"],
			"L_CHOOSE"				=> $Lang->data["general_choose"],
			"L_PAGE_TO"				=> $Lang->data["general_page_to"],
			"L_PAGE_ADD"			=> $Lang->data["general_page_add"],
			"L_PAGE_LIST"			=> $Lang->data["general_page_list"],
		));
	}

	function get_all_cats(){
		global $DB;

		$DB->query("SELECT * FROM ". $DB->prefix ."article_category WHERE redirect_url='' ORDER BY cat_order ASC");
		$this->cat_count = $DB->num_rows();
		$this->cat_data  = $DB->fetch_all_array();
		$DB->free_result();
	}

	function set_all_cats($parent_id, $except_cid, $level=0, $symbol="|-- ", $prefix="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"){
		global $Session, $Template;

		if ($level){
			$str_prefix	= "";
			for ($i=0; $i<$level; $i++){
				$str_prefix	.= $prefix;
			}
		}
		else{
			$str_prefix	= "";
		}

		for ($i=0; $i<$this->cat_count; $i++){
			if ( ($parent_id == $this->cat_data[$i]['cat_parent_id']) && ($except_cid != $this->cat_data[$i]['cat_id']) ){
				$Template->set_block_vars("catrow",array(
					'ID'				=> $this->cat_data[$i]['cat_id'],
					'NAME'				=> $this->cat_data[$i]['cat_name'],
					'ARTICLE_COUNTER'	=> $this->cat_data[$i]['article_counter'],
					'SUBCAT_COUNTER'	=> $this->cat_data[$i]['children_counter'],
					'PREFIX'			=> $str_prefix .$symbol,
					"U_EDIT_CAT"		=> $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=preedit&id='. $this->cat_data[$i]['cat_id'] .'&page='. $this->page),
					"U_DEL_CAT"			=> $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=predel&id='. $this->cat_data[$i]['cat_id'] .'&page='. $this->page),
				));
				$this->set_all_cats($this->cat_data[$i]['cat_id'], $except_cid, $level+1, $symbol, $prefix);
			}
		}
	}

	function do_add_rss(){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$this->data['rss_title']			= isset($_POST["rss_title"]) ? htmlspecialchars($_POST["rss_title"]) : '';
		$this->data['rss_url']				= isset($_POST["rss_url"]) ? htmlspecialchars($_POST["rss_url"]) : '';
		$this->data['rss_import_pergo']		= isset($_POST["rss_import_pergo"]) ? intval($_POST["rss_import_pergo"]) : 0;
		$this->data['rss_convert_charset']	= isset($_POST["rss_convert_charset"]) ? intval($_POST["rss_convert_charset"]) : 0;
		$this->data['rss_auth']				= isset($_POST["rss_auth"]) ? intval($_POST["rss_auth"]) : 0;
		$this->data['rss_auth_user']		= isset($_POST["rss_auth_user"]) ? htmlspecialchars($_POST["rss_auth_user"]) : '';
		$this->data['rss_auth_pass']		= isset($_POST["rss_auth_pass"]) ? htmlspecialchars($_POST["rss_auth_pass"]) : '';
		$this->data['article_cat_id']		= isset($_POST["article_cat_id"]) ? intval($_POST["article_cat_id"]) : 0;
		$this->data["article_type"]			= isset($_POST["article_type"]) ? intval($_POST["article_type"]) : 0;
		$this->data['article_remove_html']	= isset($_POST["article_remove_html"]) ? intval($_POST["article_remove_html"]) : 0;
		$this->data['article_show_link']	= isset($_POST["article_show_link"]) ? intval($_POST["article_show_link"]) : 0;
		$this->data['article_author']		= isset($_POST["article_author"]) ? htmlspecialchars($_POST["article_author"]) : '';
		$this->data['article_username']		= isset($_POST["article_username"]) ? htmlspecialchars($_POST["article_username"]) : '';
		$this->data['article_userpost_increase']	= isset($_POST["article_userpost_increase"]) ? htmlspecialchars($_POST["article_userpost_increase"]) : 0;
		$this->data['article_enabled']		= isset($_POST["article_enabled"]) ? htmlspecialchars($_POST["article_enabled"]) : 0;
		$this->data["page_to"]				= isset($_POST["page_to"]) ? htmlspecialchars($_POST["page_to"]) : '';

		if ( empty($this->data['rss_title']) || empty($this->data['rss_url']) || ($this->data['rss_url'] == 'http://') || !$this->data['article_cat_id'] ){
			$this->pre_add_rss($Lang->data['general_error_not_full']);
			return false;
		}

		//Get max order
		$DB->query("SELECT max(rss_order) AS max_order FROM ". $DB->prefix ."rss_import");
		if ( $DB->num_rows() ){
			$tmp_info		= $DB->fetch_array();
			$max_order		= $tmp_info["max_order"] + 1;
		}
		else{
			$max_order		= 1;
		}
		$DB->free_result();

		$sql	= "INSERT INTO ". $DB->prefix ."rss_import(rss_title, rss_url, rss_import_pergo, rss_convert_charset, rss_auth, rss_auth_user, rss_auth_pass, rss_order, article_cat_id, article_type, article_remove_html, article_show_link, article_author, article_username, article_userpost_increase, article_enabled, last_import_date, posted_date) 
						VALUES('". $this->data['rss_title'] ."', '". $this->data['rss_url'] ."', ". $this->data['rss_import_pergo'] .", ". $this->data['rss_convert_charset'] .", ". $this->data['rss_auth'] .", '". $this->data['rss_auth_user'] ."', '". $this->data['rss_auth_pass'] ."', $max_order, ". $this->data['article_cat_id'] .", ". $this->data['article_type'] .", ". $this->data['article_remove_html'] .", ". $this->data['article_show_link'] .", '". $this->data['article_author'] ."', '". $this->data['article_username'] ."', ". $this->data['article_userpost_increase'] .", ". $this->data['article_enabled'] .", 0, ". CURRENT_TIME .")";
		$DB->query($sql);
		$rss_id	= $DB->insert_id();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $rss_id, ACP_INDEX .'?mod=rss_import&act='. FUNC_ACT_VIEW .'&id='. $rss_id);

		if ( $this->data['page_to'] == 'pageadd' ){
			$this->data		= array();//Reset data
			$this->pre_add_rss($Lang->data['general_success_add']);
		}
		else{
			$this->list_rsses();
		}
		return true;
	}

	function pre_edit_rss($msg = ""){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main	= "rss_import_edit";
		$this->set_lang();

		$DB->query("SELECT * FROM ". $DB->prefix ."rss_import WHERE rss_id=$id");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["rss_import_error_not_exist"], $Session->append_sid(ACP_INDEX .'?mod=rss_import&page='. $this->page));
			return false;
		}
		$rss_info	= $DB->fetch_array();
		$DB->free_result();

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=rss_import&act=edit&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			'RSS_TITLE'				=> isset($this->data['rss_title']) ? stripslashes($this->data['rss_title']) : $rss_info['rss_title'],
			'RSS_URL'				=> isset($this->data['rss_url']) ? stripslashes($this->data['rss_url']) : $rss_info['rss_url'],
			'RSS_IMPORT_PERGO'		=> isset($this->data['rss_import_pergo']) ? $this->data['rss_import_pergo'] : $rss_info['rss_import_pergo'],
			'RSS_CONVERT_CHARSET'	=> isset($this->data['rss_convert_charset']) ? $this->data['rss_convert_charset'] : $rss_info['rss_convert_charset'],
			'RSS_AUTH'				=> isset($this->data['rss_auth']) ? $this->data['rss_auth'] : $rss_info['rss_auth'],
			'RSS_AUTH_USER'			=> isset($this->data['rss_auth_user']) ? stripslashes($this->data['rss_auth_user']) : $rss_info['rss_auth_user'],
			'RSS_AUTH_PASS'			=> isset($this->data['rss_auth_pass']) ? stripslashes($this->data['rss_auth_pass']) : $rss_info['rss_auth_pass'],
			'ARTICLE_CAT_ID'		=> isset($this->data['article_cat_id']) ? $this->data['article_cat_id'] : $rss_info['article_cat_id'],
			'ARTICLE_TYPE'			=> isset($this->data['article_type']) ? $this->data['article_type'] : $rss_info['article_type'],
			'ARTICLE_REMOVE_HTML'	=> isset($this->data['article_remove_html']) ? $this->data['article_remove_html'] : $rss_info['article_remove_html'],
			'ARTICLE_SHOW_LINK'		=> isset($this->data['article_show_link']) ? $this->data['article_show_link'] : $rss_info['article_show_link'],
			'ARTICLE_AUTHOR'		=> isset($this->data['article_author']) ? stripslashes($this->data['article_author']) : $rss_info['article_author'],
			'ARTICLE_USERNAME'		=> isset($this->data['article_username']) ? stripslashes($this->data['article_username']) : $rss_info['article_username'],
			'ARTICLE_USERPOST_INCREASE'	=> isset($this->data['article_userpost_increase']) ? $this->data['article_userpost_increase'] : $rss_info['article_userpost_increase'],
			'ARTICLE_ENABLED'		=> isset($this->data['article_enabled']) ? $this->data['article_enabled'] : $rss_info['article_enabled'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_rss"] . $Lang->data['general_arrow'] . $Lang->data["menu_rss_import"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			"L_BUTTON"				=> $Lang->data["button_edit"],
		));
		return true;
	}

	function do_edit_rss(){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$id			= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$this->data['rss_title']			= isset($_POST["rss_title"]) ? htmlspecialchars($_POST["rss_title"]) : '';
		$this->data['rss_url']				= isset($_POST["rss_url"]) ? htmlspecialchars($_POST["rss_url"]) : '';
		$this->data['rss_import_pergo']		= isset($_POST["rss_import_pergo"]) ? intval($_POST["rss_import_pergo"]) : 0;
		$this->data['rss_convert_charset']	= isset($_POST["rss_convert_charset"]) ? intval($_POST["rss_convert_charset"]) : 0;
		$this->data['rss_auth']				= isset($_POST["rss_auth"]) ? intval($_POST["rss_auth"]) : 0;
		$this->data['rss_auth_user']		= isset($_POST["rss_auth_user"]) ? htmlspecialchars($_POST["rss_auth_user"]) : '';
		$this->data['rss_auth_pass']		= isset($_POST["rss_auth_pass"]) ? htmlspecialchars($_POST["rss_auth_pass"]) : '';
		$this->data['article_cat_id']		= isset($_POST["article_cat_id"]) ? intval($_POST["article_cat_id"]) : 0;
		$this->data['article_type']			= isset($_POST["article_type"]) ? intval($_POST["article_type"]) : 0;
		$this->data['article_remove_html']	= isset($_POST["article_remove_html"]) ? intval($_POST["article_remove_html"]) : 0;
		$this->data['article_show_link']	= isset($_POST["article_show_link"]) ? intval($_POST["article_show_link"]) : 0;
		$this->data['article_author']		= isset($_POST["article_author"]) ? htmlspecialchars($_POST["article_author"]) : '';
		$this->data['article_username']		= isset($_POST["article_username"]) ? htmlspecialchars($_POST["article_username"]) : '';
		$this->data['article_userpost_increase']	= isset($_POST["article_userpost_increase"]) ? htmlspecialchars($_POST["article_userpost_increase"]) : 0;
		$this->data['article_enabled']		= isset($_POST["article_enabled"]) ? htmlspecialchars($_POST["article_enabled"]) : 0;

		if ( empty($this->data['rss_title']) || empty($this->data['rss_url']) || ($this->data['rss_url'] == 'http://') || !$this->data['article_cat_id'] ){
			$this->pre_edit_rss($Lang->data['general_error_not_full']);
			return false;
		}

		//Get old info
		$DB->query('SELECT * FROM '. $DB->prefix .'rss_import WHERE rss_id='. $id);
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data['rss_import_error_not_exist'], $Session->append_sid(ACP_INDEX .'?mod=rss_import&page='. $this->page));
			return false;
		}
//		$rss_info	= $DB->fetch_array();
		$DB->free_result();

		$sql	= "UPDATE ". $DB->prefix ."rss_import SET rss_title='". $this->data['rss_title'] ."', rss_url='". $this->data['rss_url'] ."', rss_import_pergo=". $this->data['rss_import_pergo'] .", rss_convert_charset=". $this->data['rss_convert_charset'] .", rss_auth=". $this->data['rss_auth'] .", rss_auth_user='". $this->data['rss_auth_user'] ."', rss_auth_pass='". $this->data['rss_auth_pass'] ."', article_cat_id=". $this->data['article_cat_id'] .", article_type=". $this->data['article_type'] .", article_remove_html=". $this->data['article_remove_html'] .", article_show_link=". $this->data['article_show_link'] .", article_author='". $this->data['article_author'] ."', article_username='". $this->data['article_username'] ."', article_userpost_increase=". $this->data['article_userpost_increase'] .", article_enabled=". $this->data['article_enabled'] ." WHERE rss_id=". $id;
		$DB->query($sql);

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=rss_import&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_rsses();
		return true;
	}

	function delete_rsses(){
		global $DB, $Template, $Func;

		$rss_ids	= isset($_POST['rss_ids']) ? $_POST['rss_ids'] : '';
		$ids_info	= $Func->get_array_value($rss_ids);

		if ( sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);
			$where_sql	= " WHERE rss_id IN (". $str_ids .")";

			//Delete import logs
			$DB->query("DELETE FROM ". $DB->prefix ."rss_imported $where_sql");
			//Delete rss
			$DB->query("DELETE FROM ". $DB->prefix ."rss_import $where_sql");
			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $str_ids);
		}
		$this->list_rsses();
	}

	function pre_import_rss($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$Info->tpl_main		= "rss_import_import";

		if ( !isset($this->data['rss_ids']) ){
			$this->data['rss_ids']	= isset($_POST['rss_ids']) ? $_POST['rss_ids'] : '';
		}

		//Get all rss streams --------------------
		$DB->query('SELECT rss_id, rss_title, import_counter FROM '. $DB->prefix .'rss_import ORDER BY rss_order ASC');
		$rss_count	= $DB->num_rows();
		$rss_data	= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$rss_count; $i++){
			$Template->set_block_vars("rssrow", array(
				'ID'		=> $rss_data[$i]['rss_id'],
				'TITLE'		=> $rss_data[$i]['rss_title'],
				'ARTICLES'	=> $rss_data[$i]['import_counter'],
			));
		}
		//----------------------------------------

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=rss_import&act=import'),
			'IMPORT_NUMBER'			=> isset($this->data['import_number']) ? stripslashes($this->data['import_number']) : 10,
			'L_RSS_STREAMS'			=> $Lang->data["rss_import_streams"],
			'L_IMPORT_NUMBERS'		=> $Lang->data["rss_import_import_number"],
			'L_IMPORT_NUMBER_DESC'	=> $Lang->data["rss_import_import_number_desc"],
			"L_PAGE_TITLE"			=> $Lang->data["menu_rss"] . $Lang->data['general_arrow'] . $Lang->data["menu_rss_import"] . $Lang->data['general_arrow'] . $Lang->data["rss_import_import_articles"],
			"L_BUTTON"				=> $Lang->data["button_import"],
		));

		//Rss values
		if ( is_array($this->data['rss_ids']) ){
			reset($this->data['rss_ids']);
			while (list(, $rss_id) = each($this->data['rss_ids'])){
				$rss_id	= intval($rss_id);
				if ( $rss_id ){
					$Template->set_block_vars("rss_value", array(
						'ID'	=> $rss_id,
					));
				}
			}
		}
	}

	function do_import_rss(){
		global $Session, $DB, $Template, $Lang, $Func, $Info, $RSS, $ArticleGlobal, $Image, $File;

		$this->data['rss_ids']			= isset($_POST["rss_ids"]) ? $_POST["rss_ids"] : '';
		$this->data['import_number']	= isset($_POST["import_number"]) ? intval($_POST["import_number"]) : 0;
		$ids_info		= $Func->get_array_value($this->data['rss_ids']);
		$record_ids		= "";
		$total_counter	= 0;

		if ( !sizeof($ids_info) ){
			$this->list_rsses();
			return false;
		}

		//Get rss info
		$DB->query("SELECT * FROM ". $DB->prefix ."rss_import WHERE rss_id IN (". implode(',', $ids_info) .")");
		$rss_count	= $DB->num_rows();
		$rss_data	= $DB->fetch_all_array();
		$DB->free_result();

		//Get rss contents
		for ($i=0; $i<$rss_count; $i++){
			if ( !$rss_data[$i]['rss_auth'] ){
				$rss_data[$i]['rss_auth_user']	= "";
				$rss_data[$i]['rss_auth_pass']	= "";
			}
			$RSS->fetch_rss($rss_data[$i]['rss_url'], $rss_data[$i]['rss_auth_user'], $rss_data[$i]['rss_auth_pass'], $Lang->charset, "", true, $rss_data[$i]['rss_convert_charset']);

			//Get user id
			$user_id	= 0;
			if ( !empty($rss_data[$i]['article_username']) ){
				$DB->query("SELECT user_id FROM ". $DB->prefix ."user WHERE username='". $rss_data[$i]['article_username'] ."'");
				if ( $DB->num_rows() ){
					$tmp_info	= $DB->fetch_array();
					$user_id	= $tmp_info['user_id'];
				}
			}
			$post_count	= 0;

			//Import Rss
			$flag	= true;
			reset($RSS->rss_items);
			while (list(,$item_data) = each($RSS->rss_items)){
				if ( !is_array($item_data) ){
					continue;
				}
				while (list(,$item_info) = each($item_data)){
					if ( !isset($item_info['title']) || empty($item_info['title']) || !isset($item_info['desc']) || !isset($item_info['link']) || !isset($item_info['unix_date']) ){
						continue;
					}

					//Remove html tags
					if ( $rss_data[$i]['article_remove_html'] ){
						$item_info['desc']	= strip_tags($item_info['desc']);
					}
					$item_info["title"]		= addslashes($item_info["title"]);
					$item_info["desc"]		= addslashes($item_info["desc"]);
					$item_info["link"]		= addslashes($item_info["link"]);
					$item_info["thumb_large"]	= isset($item_info["enclosure_image"]) ? addslashes($item_info["enclosure_image"]) : "";
					$item_info['unix_date']	= intval($item_info['unix_date']);

					//Check exist
					$DB->query("SELECT article_id FROM ". $DB->prefix ."article WHERE title='". $item_info["title"] ."' AND cat_id=". $rss_data[$i]['article_cat_id'] ." AND content_desc LIKE '". $item_info['desc'] ."' AND content_url='". $item_info['link'] ."'");
					if ( $DB->num_rows() ){
						continue;
					}

					//Insert article
					$sql		= "INSERT INTO ". $DB->prefix ."article(cat_id, topic_id, thumb_large, thumb_small, thumb_icon, title, content_desc, content_url, poster_id, checker_id, posted_date, is_hot, article_type, page_counter, enabled)
											VALUES(". $rss_data[$i]["article_cat_id"] .", 0, '', '', '', '". $item_info["title"] ."', '". $item_info["desc"] ."', '". $item_info["link"] ."', $user_id, 0, ". $item_info["unix_date"] .", 0, ". $rss_data[$i]['article_type'] .", 1, ". $rss_data[$i]['article_enabled'] .")";

					$DB->query($sql);
					$article_id	= $DB->insert_id();
					$record_ids	.= !empty($record_ids) ? ", ". $article_id : $article_id;
					$post_count++;
					$total_counter++;

					//Insert article detail
					$DB->query("INSERT INTO ". $DB->prefix ."article_page(article_id, page_title, used_files, page_order, page_enabled) VALUES($article_id, '". $item_info['title'] ."', '', 1, 1)");
					$page_id	= $DB->insert_id();
					$DB->query("INSERT INTO ". $DB->prefix ."article_page_content(page_id, article_id, content_detail, author) VALUES($page_id, $article_id, '". $item_info['desc'] ."', '". $rss_data[$i]['article_author'] ."')");

					//Insert rss import logs
					$DB->query("INSERT INTO ". $DB->prefix ."rss_imported(rss_id, article_id, import_date) VALUES(". $rss_data[$i]['rss_id'] .", $article_id, ". CURRENT_TIME .")");

					if ( !empty($item_info['thumb_large']) ){
						$imgtype 		= substr($item_info['thumb_large'], -4);
						$allowed_types	= explode(',', $Info->option['image_type']);
						$filename		= "img". rand(100, 10000);
						if ( in_array($imgtype, $allowed_types) && @copy($item_info['thumb_large'], "./upload/". $filename) ){
							$imgsize	= getimagesize("./upload/". $filename);
							clearstatcache();
							if ( $imgsize[2] == 1 ){
								$item_info['thumb_large']	= $filename .'.gif';
							}
							else if ( $imgsize[2] == 2 ){
								$item_info['thumb_large']	= $filename .'.jpg';
							}
							else if ( $imgsize[2] == 6 ){
								$item_info['thumb_large']	= $filename .'.bmp';
							}
							else{
								$item_info['thumb_large']	= "";
								$File->delete_file("./upload/". $filename);
							}

							if ( !empty($item_info['thumb_large']) ){
								//Make image dir for this article
								$ArticleGlobal->make_image_dir($item_info["unix_date"], $article_id);

								//Update pic thumb
								//Thumb large -----------------------
								if ( file_exists($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_large"]) ){
									$count		= 1;
									$thumb_large	= str_replace(".", $count .".", $item_info["thumb_large"]);
									while ( file_exists($ArticleGlobal->sysdir['id'] .'/'. $thumb_large) ){
										$count++;
										$thumb_large	= str_replace(".", $count .".", $item_info["thumb_large"]);
									}
									$item_info['thumb_large'] = $thumb_large;
								}
								$File->copy_file("./upload/". $filename, $ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_large"]);
								$Image->resize_image($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_large"], $Info->option['thumb_large_max_width'], $Info->option['thumb_large_max_height'], 'all');
								$File->delete_file("./upload/". $filename);
								//-----------------------------------

								//Thumb small -----------------------
								$item_info['thumb_small']	= 'small_'. $item_info['thumb_large'];
								if ( file_exists($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_small"]) ){
									$count		= 1;
									$thumb_small	= str_replace(".", $count .".", $item_info["thumb_small"]);
									while ( file_exists($ArticleGlobal->sysdir['id'] .'/'. $thumb_small) ){
										$count++;
										$thumb_small	= str_replace(".", $count .".", $item_info["thumb_small"]);
									}
									$item_info['thumb_small'] = $thumb_small;
								}
								$File->copy_file($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_large"], $ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_small"]);
								$Image->resize_image($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_small"], $Info->option['thumb_small_max_width'], $Info->option['thumb_small_max_height'], 'all');
								//-----------------------------------

								//Thumb icon ------------------------
								$item_info['thumb_icon']	= 'icon_'. $item_info['thumb_large'];
								if ( file_exists($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_icon"]) ){
									$count		= 1;
									$thumb_icon	= str_replace(".", $count .".", $item_info["thumb_icon"]);
									while ( file_exists($ArticleGlobal->sysdir['id'] .'/'. $thumb_icon) ){
										$count++;
										$thumb_icon	= str_replace(".", $count .".", $item_info["thumb_icon"]);
									}
									$item_info['thumb_icon'] = $thumb_icon;
								}
								$Image->create_thumbnail($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_large"], $ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_icon"], $Info->option['thumb_icon_max_width'], $Info->option['thumb_icon_max_height']);
								//-----------------------------------

								//Update article
								$DB->query("UPDATE ". $DB->prefix ."article SET thumb_large='". $item_info['thumb_large'] ."', thumb_small='". $item_info['thumb_small'] ."', thumb_icon='". $item_info['thumb_icon'] ."' WHERE article_id=$article_id");
							}
						}
					}

					//Limit articles
					if ( $this->data['import_number'] && ($post_count >= $this->data['import_number']) ){
						$flag	= false;
						break;
					}
				}
				if ( !$flag ){
					break;
				}
			}

			//Increase author' posts
			if ( $user_id && $rss_data[$i]['article_userpost_increase'] ){
				$DB->query("UPDATE ". $DB->prefix ."user SET article_counter=article_counter+". $post_count ." WHERE user_id=". $user_id);
			}

			//Update last import date
			$DB->query("UPDATE ". $DB->prefix ."rss_import SET last_import_date=". CURRENT_TIME ." WHERE rss_id=". $rss_data[$i]['rss_id']);
		}
		//Save log
		$Func->save_log(FUNC_NAME, 'rss_log_import_articles', $record_ids);

		//Resync categories' counters
		$this->resync_cats();

		//Resync rss counters
		$this->resync_rss_counters();

		$this->list_rsses(sprintf($Lang->data['rss_import_success'], $total_counter));
		return true;
	}

	function pre_remove_rss($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$Info->tpl_main		= "rss_import_remove";

		if ( !isset($this->data['rss_ids']) ){
			$this->data['rss_ids']	= isset($_POST['rss_ids']) ? $_POST['rss_ids'] : '';
		}

		//Get all rss streams --------------------
		$DB->query('SELECT rss_id, rss_title, import_counter FROM '. $DB->prefix .'rss_import ORDER BY rss_order ASC');
		$rss_count	= $DB->num_rows();
		$rss_data	= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$rss_count; $i++){
			$Template->set_block_vars("rssrow", array(
				'ID'		=> $rss_data[$i]['rss_id'],
				'TITLE'		=> $rss_data[$i]['rss_title'],
				'ARTICLES'	=> $rss_data[$i]['import_counter'],
			));
		}
		//----------------------------------------

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=rss_import&act=remove'),
			'REMOVE_NUMBER'			=> isset($this->data['remove_number']) ? stripslashes($this->data['remove_number']) : 20,
			'L_RSS_STREAMS'			=> $Lang->data["rss_import_remove_streams"],
			'L_REMOVE_NUMBERS'		=> $Lang->data["rss_import_remove_number"],
			'L_REMOVE_NUMBER_DESC'	=> $Lang->data["rss_import_remove_number_desc"],
			"L_PAGE_TITLE"			=> $Lang->data["menu_rss"] . $Lang->data['general_arrow'] . $Lang->data["menu_rss_import"] . $Lang->data['general_arrow'] . $Lang->data["rss_import_remove_articles"],
			"L_BUTTON"				=> $Lang->data["button_remove"],
		));

		//Rss values
		if ( is_array($this->data['rss_ids']) ){
			reset($this->data['rss_ids']);
			while (list(, $rss_id) = each($this->data['rss_ids'])){
				$rss_id	= intval($rss_id);
				if ( $rss_id ){
					$Template->set_block_vars("rss_value", array(
						'ID'	=> $rss_id,
					));
				}
			}
		}
	}

	function do_remove_rss(){
		global $Session, $DB, $Template, $Lang, $Func, $Info, $File;

		$this->data['rss_ids']			= isset($_POST["rss_ids"]) ? $_POST["rss_ids"] : '';
		$this->data['remove_number']	= isset($_POST["remove_number"]) ? intval($_POST["remove_number"]) : 0;

		if ( !is_array($this->data['rss_ids']) ){
			$this->list_rsses();
			return false;
		}

		$post_counter	= 0;
		$record_ids		= "";
		$sql_limit		= $this->data['remove_number'] ? ' ORDER BY import_date LIMIT 0,'. $this->data['remove_number'] : '';

		reset($this->data['rss_ids']);
		while (list(, $rss_id) = each($this->data['rss_ids'])){
			$rss_id	= intval($rss_id);
			if ( $rss_id ){
				//Get imported article ids
				$DB->query('SELECT article_id FROM '. $DB->prefix .'rss_imported WHERE rss_id='. $rss_id . $sql_limit);
				$article_count	= $DB->num_rows();
				$article_data	= $DB->fetch_all_array();
				$DB->free_result();

				if ( !$article_count ){
					continue;
				}

				$where_sql	= "WHERE article_id=0";
				for ($i=0; $i<$article_count; $i++){
					$where_sql	.= " OR article_id=". $article_data[$i]['article_id'];
					$record_ids	.= !empty($record_ids) ? ", ". $article_data[$i]['article_id'] : $article_data[$i]['article_id'];
					$post_counter++;
				}

				//Get and delete image dirs --------
				$DB->query("SELECT article_id, posted_date FROM ". $DB->prefix ."article $where_sql");
				$article_count	= $DB->num_rows();
				$article_data	= $DB->fetch_all_array();
				$DB->free_result();

				for ($i=0; $i<$article_count; $i++){
					$article_dir	= $this->get_article_dir($article_data[$i]['posted_date'], $article_data[$i]['article_id']);
					$File->delete_dir($article_dir);
				}
				//------------------------------------

				//Delete articles
				$DB->query("DELETE FROM ". $DB->prefix ."article_comment $where_sql");
				$DB->query("DELETE FROM ". $DB->prefix ."article_page_content $where_sql");
				$DB->query("DELETE FROM ". $DB->prefix ."article_page $where_sql");
				$DB->query("DELETE FROM ". $DB->prefix ."article $where_sql");
				$DB->query("DELETE FROM ". $DB->prefix ."rss_imported $where_sql");
			}
		}

		//Resync categories' counters
		$this->resync_cats();

		//Resync counters
		$this->resync_rss_counters();

		//Save log
		if ( $post_counter ){
			$Func->save_log(FUNC_NAME, 'rss_log_remove_articles', $record_ids);
		}

		$this->list_rsses(sprintf($Lang->data['rss_import_success_remove'], $post_counter));
		return true;
	}

	function get_article_dir($time, $id){
		global $Info;

		//Get month and year
		$date	= getdate($time);
		if ($date['mon'] < 10){
			$date['mon']	= '0'. $date['mon'];
		}
		return $Info->imgpath_article . $date['year'] ."_". $date['mon'] .'/'. $id;
	}

	function update_order(){
		global $Session, $Template, $Lang, $DB, $Func;

		$rss_orders = isset($_POST["rss_orders"]) ? $_POST["rss_orders"] : '';

		if ( is_array($rss_orders) ){
			reset($rss_orders);
			while ( list($id, $num) = each($rss_orders) ){
				$DB->query("UPDATE ". $DB->prefix ."rss_import SET rss_order=". intval($num) ." WHERE rss_id=". intval($id));
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_update');
		$this->list_rsses();
	}

	function resync_rsses(){
		global $DB, $Template, $Lang, $Func;

		$DB->query('SELECT rss_id, rss_order FROM '. $DB->prefix .'rss_import ORDER BY rss_order ASC');
		$rss_count		= $DB->num_rows();
		$rss_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$rss_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'rss_import SET rss_order='. ($i + 1) .' WHERE rss_id='. $rss_data[$i]['rss_id']);
		}

		//Resync counters
		$this->resync_rss_counters();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_resync');

		$this->list_rsses();
	}

	function resync_rss_counters(){
		global $Session, $DB, $Template, $Lang;

		//Delete expired data
		$DB->query('DELETE FROM '. $DB->prefix .'rss_imported WHERE article_id NOT IN (SELECT article_id FROM '. $DB->prefix .'article)', 0);

		//Reset counters
		$DB->query('UPDATE '. $DB->prefix .'rss_import SET import_counter=0');

		//Count articles
		$DB->query('SELECT count(article_id) AS counter, rss_id FROM '. $DB->prefix.'rss_imported GROUP BY rss_id');
		$rss_count = $DB->num_rows();
		$rss_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$rss_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'rss_import SET import_counter='. $rss_data[$i]['counter'] .' WHERE rss_id='. $rss_data[$i]['rss_id']);
		}
	}

	function resync_cats(){
		global $Session, $DB, $Template, $Lang;

		//Reset counters
		$DB->query('UPDATE '. $DB->prefix .'article_category SET article_counter=0');

		//Update article_counter
		$DB->query('SELECT count(article_id) AS counter, cat_id FROM '. $DB->prefix.'article GROUP BY cat_id');
		$cat_count = $DB->num_rows();
		$cat_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$cat_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'article_category SET article_counter='. $cat_data[$i]['counter'] .' WHERE cat_id='. $cat_data[$i]['cat_id']);
		}
	}
}
?>