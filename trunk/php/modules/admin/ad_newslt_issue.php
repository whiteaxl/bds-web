<?php
/* =============================================================== *\
|		Module name: Newsletter Issue								|
|		Module version: 1.2											|
|		Begin: 23 April 2006										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_newslt_issue');
define('FUNC_ACT_VIEW', 'view');
//Module language
$Func->import_module_language("admin/lang_newsletter". PHP_EX);

//SMTP
include('includes/smtp'. PHP_EX);
$SMTP	= new SMTP($Info->option['smtp_host'], $Info->option['smtp_username'], $Info->option['smtp_password']);

$AdminNewsltIssue = new Admin_Newslt_Issue;

class Admin_Newslt_Issue
{
	var $filter			= array();
	var $page			= 1;
	var $upload_path	= './upload_path/newslt_issues/';

	var $cat_count		= 0;
	var $cat_data		= array();

	var $user_perm		= array();

	function Admin_Newslt_Issue(){
		global $Info, $Func, $Cache;

		$this->user_perm	= $Func->get_all_perms('menu_newslt_issue');

		$this->get_filter();
		$this->get_all_cats();
		$this->set_all_cats(0, 0);

		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_issue();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->do_add_issue();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_issue();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->do_edit_issue();
				break;
			case "presend":
				$Func->check_user_perm($this->user_perm, 'send_email');
				$this->pre_send_issue();
				break;
			case "send":
				$Func->check_user_perm($this->user_perm, 'send_email');
				$this->do_send_issue();
				break;
			case "enable":
				$Func->check_user_perm($this->user_perm, 'active');
				$this->active_issues(1);
				break;
			case "disable":
				$Func->check_user_perm($this->user_perm, 'active');
				$this->active_issues(0);
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$this->delete_issues();
				break;
			case "view":
				$Func->check_user_perm($this->user_perm, 'view');
				$this->view_issue();
				break;
			default:
				$this->list_issues();
		}
	}

	function get_filter(){
		global $Template;

		$this->filter['url_append']	= "";

		$this->filter['keyword']		= isset($_POST['fkeyword']) ? htmlspecialchars($_POST['fkeyword']) : '';
		if (empty($this->filter['keyword'])){
			$this->filter['keyword']	= isset($_GET["fkeyword"]) ? htmlspecialchars($_GET["fkeyword"]) : '';
		}
		if ( !empty($this->filter['keyword']) ){
			$this->filter['url_append']	.= '&fkeyword='. $this->filter['keyword'];
		}

		$this->filter['status']			= isset($_POST['fstatus']) ? intval($_POST['fstatus']) : -1;
		if ( $this->filter['status'] == -1 ){
			$this->filter['status']		= isset($_GET["fstatus"]) ? intval($_GET["fstatus"]) : -1;
		}
		if ( $this->filter['status'] != -1 ){
			$this->filter['url_append']	.= '&fstatus='. $this->filter['status'];
		}

		$this->filter['cat_id']			= isset($_POST['fcat_id']) ? intval($_POST['fcat_id']) : 0;
		if ( !$this->filter['cat_id'] ){
			$this->filter['cat_id']		= isset($_GET["fcat_id"]) ? intval($_GET["fcat_id"]) : 0;
		}
		if ( $this->filter['cat_id'] ){
			$this->filter['url_append']	.= '&fcat_id='. $this->filter['cat_id'];
		}

		$this->page			= isset($_GET["page"]) ? intval($_GET["page"]) : 1;

		$Template->set_vars(array(
			"FKEYWORD"	=> stripslashes($this->filter['keyword']),
			"FSTATUS"	=> $this->filter['status'],
			"FCAT_ID"	=> $this->filter['cat_id'],
		));
	}

	function list_issues(){
		global $Session, $Func, $Info, $DB, $Template, $Lang;

		$Info->tpl_main	= "newslt_issue_list";
		$itemperpage	= $Info->option['items_per_page'];
		$date_format	= $Info->option['time_format'];
		$timezone		= $Info->option['timezone'] * 3600;

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		//Filter -------------------------
		$where_sql		= " WHERE issue_id>0";
		if ( ($this->filter['status'] == SYS_ENABLED) || ($this->filter['status'] == SYS_DISABLED) ){
			$where_sql	.= " AND enabled=". $this->filter['status'];
		}
		if ( $this->filter['cat_id'] ){
			$where_sql	.= " AND cat_ids LIKE '%,". $this->filter['cat_id'] .",%'";
		}
		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*", '%', $this->filter['keyword']);
			$where_sql	.= " AND (title LIKE '%". $key ."%' OR content LIKE '%". $key ."%')";
		}
		//------------------------------------

		//Generate pages
		$DB->query("SELECT count(issue_id) AS total FROM ". $DB->prefix ."newsletter_issue $where_sql $auth_where_sql");
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=newslt_issue" . $this->filter['url_append']));
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query("SELECT * FROM ". $DB->prefix ."newsletter_issue $where_sql $auth_where_sql ORDER BY posted_date DESC LIMIT ". $pageshow['start'] .",$itemperpage");
		$issue_count	= $DB->num_rows();
		$issue_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$issue_count; $i++){
			$Template->set_block_vars("issuerow", array(
				"ID"			=> $issue_data[$i]["issue_id"],
				"CSS"			=> ($issue_data[$i]["enabled"] == SYS_ENABLED) ? "enabled" : "disabled",
				"TITLE"			=> $issue_data[$i]["title"],
				"LAST_SENT"		=> $issue_data[$i]["sent_date"] ? $Func->translate_date(gmdate($date_format, $issue_data[$i]["sent_date"] + $timezone)) : '- - -',
				'BG_CSS'		=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				'U_VIEW'		=> $Session->append_sid(ACP_INDEX .'?mod=newslt_issue&act=view&id='. $issue_data[$i]["issue_id"]),
				'U_SEND'		=> $Func->check_user_perm($this->user_perm, 'send_email', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=newslt_issue&act=presend&id='. $issue_data[$i]["issue_id"] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/send_email.gif" border=0 alt="" title="'. $Lang->data['newslt_issue_send'] .'"></a>' : '&nbsp;',
				'U_EDIT'		=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=newslt_issue&act=preedit&id='. $issue_data[$i]["issue_id"] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
			));
		}

		$Template->set_vars(array(
			"PAGE_OUT"			=> $pageshow['page'],
			'S_FILTER_ACTION'	=> $Session->append_sid(ACP_INDEX .'?mod=newslt_issue'),
			'S_LIST_ACTION'		=> $Session->append_sid(ACP_INDEX .'?mod=newslt_issue&act=update'. $this->filter['url_append']),
			'U_ADD'				=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=newslt_issue&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="{'. $Lang->data['general_add'] .'" align="absbottom" border=0>'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp; ' : '',
			'U_ENABLE'			=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=newslt_issue&act=enable' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/enable.gif" alt="" title="'. $Lang->data['general_enable'] .'" align="absbottom" border=0>'. $Lang->data['general_enable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DISABLE'			=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=newslt_issue&act=disable' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/disable.gif" alt="" title="'. $Lang->data['general_disable'] .'" align="absbottom" border=0>'. $Lang->data['general_disable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'			=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript:deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=newslt_issue&act=del' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" align="absbottom" border=0>'. $Lang->data['general_del'] .'</a> &nbsp; &nbsp;' : '',
			"L_PAGE_TITLE"		=> $Lang->data["menu_newslt"] . $Lang->data['general_arrow'] . $Lang->data["menu_newslt_issue"],
			"L_CATS"			=> $Lang->data["general_cat"],
			"L_TITLE"			=> $Lang->data["general_title"],
			'L_VIEW_DESC'		=> $Lang->data['general_view_desc'],
			"L_LAST_SENT"		=> $Lang->data["newslt_issue_last_sent"],
			"L_SEND"			=> $Lang->data["newslt_issue_send"],
			"L_SEARCH"			=> $Lang->data["button_search"],
			'L_DEL_CONFIRM'		=> $Lang->data['newslt_issue_del_confirm'],
			'L_CHOOSE_ITEM'		=> $Lang->data['newslt_error_issue_not_check'],
		));
	}

	function pre_add_issue($msg = ""){
		global $Session, $Info, $DB, $Template, $Lang;

		$Info->tpl_main	= "newslt_issue_edit";
		$this->set_lang();

		$Template->set_block_vars("addrow",array());
		$Template->set_vars(array(
			"ERROR_MSG"				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=newslt_issue&act=add'. $this->filter['url_append']),
			"TITLE"					=> isset($this->data["title"]) ? stripslashes($this->data["title"]) : '',
			"CONTENT"				=> isset($this->data["content"]) ? stripslashes($this->data["content"]) : '',
			"USED_FILES"			=> isset($this->data["used_files"]) ? stripslashes($this->data["used_files"]) : '',
			"ENABLED"				=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : '',
			"PAGE_TO"				=> isset($this->data["page_to"]) ? $this->data["page_to"] : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_newslt"] . $Lang->data['general_arrow'] . $Lang->data["menu_newslt_issue"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			"L_BUTTON"				=> $Lang->data["button_add"],
		));

		//Cat values
		if ( isset($this->data['cat_ids']) && is_array($this->data['cat_ids']) ){
			reset($this->data['cat_ids']);
			while (list(, $cat_id) = each($this->data['cat_ids'])){
				$Template->set_block_vars("catvalrow", array(
					'ID'	=> intval($cat_id)
				));
			}
		}
	}

	function set_lang(){
		global $Session, $Template, $Lang, $Info;

		$Template->set_vars(array(
			'S_IMAGE_UPLOAD'			=> $Session->append_sid('../../'. ACP_INDEX .'?mod=upload&code=image'),
			"L_CATS"					=> $Lang->data["general_cats"],
			"L_CHOOSE"					=> $Lang->data["general_choose"],
			"L_TITLE"					=> $Lang->data["newslt_issue_title"],
			"L_CONTENT"					=> $Lang->data["newslt_issue_content"],
			"L_ISSUE_NOTE"				=> $Lang->data["newslt_issue_note"],
			"L_ALL"						=> $Lang->data["general_all"],
			"L_YES"						=> $Lang->data["general_yes"],
			"L_NO"						=> $Lang->data["general_no"],
			"L_PAGE_TO"					=> $Lang->data["general_page_to"],
			"L_PAGE_ADD"				=> $Lang->data["general_page_add"],
			"L_PAGE_LIST"				=> $Lang->data["general_page_list"],
			"L_SAVE_AS"					=> $Lang->data["general_save_as"],
			"L_SAVE"					=> $Lang->data["general_save"],
			"L_COPY"					=> $Lang->data["general_copy"],
		));
	}

	function get_all_cats(){
		global $DB;

		$DB->query("SELECT * FROM ". $DB->prefix ."newsletter_category ORDER BY cat_order ASC");
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
					'PREFIX'			=> $str_prefix .$symbol,
				));
				$this->set_all_cats($this->cat_data[$i]['cat_id'], $except_cid, $level+1, $symbol, $prefix);
			}
		}
	}

	function do_add_issue(){
		global $Session, $Info, $DB, $Template, $Lang, $Func, $File;

//		$user_id						= $Info->user_info['user_id'];
		$this->data["cat_ids"]			= isset($_POST["cat_ids"]) ? $_POST["cat_ids"] : '';
		$this->data["title"]			= isset($_POST["title"]) ? htmlspecialchars(trim($_POST["title"])) : '';
		$this->data["content"]			= isset($_POST["content"]) ? htmlspecialchars($_POST["content"]) : '';
		$this->data["used_files"]		= isset($_POST["used_files"]) ? $_POST["used_files"] : '';
		$this->data["enabled"]			= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;
		$this->data["page_to"]			= isset($_POST["page_to"]) ? htmlspecialchars($_POST["page_to"]) : '';

		//Check permission ---------------
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$this->data['enabled']	= SYS_DISABLED;
			}
		}
		//--------------------------------

		if ( empty($this->data["title"]) || empty($this->data["content"]) ){
			$this->pre_add_issue($Lang->data["general_error_not_full"]);
			return false;
		}

		//Categories
		if ( is_array($this->data['cat_ids']) && sizeof($this->data['cat_ids']) ){
			$cat_ids	= array();
			reset($this->data['cat_ids']);
			while (list(, $cat_id) = each($this->data['cat_ids'])){
				$cat_id		= intval($cat_id);
				$cat_ids[]	= $cat_id;
				if ( $cat_id == -1 ){
					break;
				}
			}
			$cat_string		= ','. implode(',', $cat_ids) .',';
		}
		else{
			$cat_string		= ',0,';
		}

		//Transfer used_files and remove temp files
		$data_info['content']	= $this->data['content'];
		$File->transfer_temp_files($this->data["used_files"], $this->upload_path, $data_info);
		$this->data['content']	= $data_info['content'];
		//-----------------------------------------

		//Insert issue
		$sql		= "INSERT INTO ". $DB->prefix ."newsletter_issue(cat_ids, title, content, used_files, posted_date, sent_counter, sent_date, enabled)
								VALUES('". $cat_string ."', '". $this->data["title"]."', '". $this->data["content"]."', '". $this->data['used_files'] ."', ". CURRENT_TIME .", 0, 0, ". $this->data['enabled'] .")";
		$DB->query($sql);
		$issue_id	= $DB->insert_id();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $issue_id, ACP_INDEX .'?mod=newslt_issue&act='. FUNC_ACT_VIEW .'&id='. $issue_id);

		if ( $this->data['page_to'] == 'pageadd' ){
			$tmp_data['cat_ids']		= $this->data['cat_ids'];
			$tmp_data['enabled']		= $this->data['enabled'];
			$tmp_data['page_to']		= $this->data['page_to'];
//			$this->data	= array();//Reset data
			$this->data	= $tmp_data;
			$this->pre_add_issue($Lang->data['general_success_add']);
		}
		else{
			$this->list_issues();
		}

		return true;
	}

	function pre_edit_issue($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main	= "newslt_issue_edit";
		$this->set_lang();

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		$DB->query("SELECT * FROM ". $DB->prefix ."newsletter_issue WHERE issue_id=$id $auth_where_sql");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["newslt_error_issue_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=newslt_issue". $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
		$issue_info	= $DB->fetch_array();
		$DB->free_result();

		$Template->set_block_vars("editrow", array());
		$Template->set_vars(array(
			"ERROR_MSG"				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=newslt_issue&act=edit&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			"TITLE"					=> isset($this->data["title"]) ? stripslashes($this->data["title"]) : $issue_info['title'],
			"CONTENT"				=> isset($this->data["content"]) ? stripslashes($this->data["content"]) : $issue_info['content'],
			'USED_FILES'			=> isset($this->data["used_files"]) ? stripslashes($this->data["used_files"]) : '',
			"ENABLED"				=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : $issue_info['enabled'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_newslt"] . $Lang->data['general_arrow'] . $Lang->data["menu_newslt_issue"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			"L_BUTTON"				=> $Lang->data["button_edit"],
		));

		//Cat values ----------------
		if ( !isset($this->data["cat_ids"]) ){
			$this->data["cat_ids"]	= $issue_info["cat_ids"];
		}
		$cat_info	= explode(',', $this->data["cat_ids"]);
		reset($cat_info);
		while ( list(, $cid) = each($cat_info) ){
			if ( !empty($cid) ){
				$Template->set_block_vars('catvalrow', array(
					'ID'	=> $cid,
				));
			}
		}
		//---------------------------

		return true;
	}

	function do_edit_issue(){
		global $Session, $Info, $DB, $Template, $Lang, $Func, $File;

		$id				= isset($_GET['id']) ? intval($_GET['id']) : 0;
		$this->data["cat_ids"]			= isset($_POST["cat_ids"]) ? $_POST["cat_ids"] : '';
		$this->data["title"]			= isset($_POST["title"]) ? htmlspecialchars(trim($_POST["title"])) : '';
		$this->data["content"]			= isset($_POST["content"]) ? htmlspecialchars($_POST["content"]) : '';
		$this->data["used_files"]		= isset($_POST["used_files"]) ? $_POST["used_files"] : '';
		$this->data["enabled"]			= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;

		//Check permission ---------------
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$this->data['enabled']	= SYS_DISABLED;
			}
		}
		//--------------------------------

		if ( empty($this->data["title"]) || empty($this->data["content"]) ){
			$this->pre_edit_issue($Lang->data["general_error_not_full"]);
			return false;
		}

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		//Get old info
		$DB->query('SELECT * FROM '. $DB->prefix .'newsletter_issue WHERE issue_id='. $id . $auth_where_sql);
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data['newslt_error_issue_not_exist'], $Session->append_sid(ACP_INDEX .'?mod=newslt_issue'. $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
		$issue_info	= $DB->fetch_array();

		//Transfer used_files and remove temp files
		$data_info['content']	= $this->data['content'];
		$File->transfer_temp_files($this->data["used_files"], $this->upload_path, $data_info);
		$this->data['content']	= $data_info['content'];
		//-----------------------------------------

		//Clean old used files ----------
		$data_info['content']	= $this->data['content'];
		$File->clean_used_files($issue_info["used_files"], $this->upload_path, $data_info, $this->data['used_files']);
		$this->data['content']		= $data_info['content'];
		//-------------------------------

		//Delete files which are not used
		$File->delete_unused_files($this->data['used_files'], $issue_info['used_files'], $this->upload_path);

		//Categories
		if ( is_array($this->data['cat_ids']) && sizeof($this->data['cat_ids']) ){
			$cat_ids	= array();
			reset($this->data['cat_ids']);
			while (list(, $cat_id) = each($this->data['cat_ids'])){
				$cat_id		= intval($cat_id);
				$cat_ids[]	= $cat_id;
				if ( $cat_id == -1 ){
					break;
				}
			}
			$cat_string		= ','. implode(',', $cat_ids) .',';
		}
		else{
			$cat_string		= ',0,';
		}

		//Update issue
		$DB->query("UPDATE ". $DB->prefix ."newsletter_issue SET cat_ids='". $cat_string ."', title='". $this->data["title"] ."', content='". $this->data["content"]."', used_files='". $this->data["used_files"] ."', enabled=". $this->data['enabled'] ." WHERE issue_id=". $id);

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=newslt_issue&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_issues();
		return true;
	}

	function pre_send_issue($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$Info->tpl_main	= "newslt_issue_send";
		$this->set_lang();

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		$DB->query("SELECT * FROM ". $DB->prefix ."newsletter_issue WHERE issue_id=$id $auth_where_sql");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["newslt_error_issue_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=newslt_issue". $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
		$issue_info	= $DB->fetch_array();
		$DB->free_result();

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=newslt_issue&act=send&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			"TITLE"					=> $issue_info['title'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_newslt"] . $Lang->data['general_arrow'] . $Lang->data["menu_newslt_issue"] . $Lang->data['general_arrow'] . $Lang->data["newslt_issue_send"],
			"L_BUTTON"				=> $Lang->data["button_send"],
		));

		//Cat values ----------------
		if ( !isset($this->data["cat_ids"]) ){
			$this->data["cat_ids"]	= $issue_info["cat_ids"];
		}
		$cat_info	= explode(',', $this->data["cat_ids"]);
		reset($cat_info);
		while ( list(, $cid) = each($cat_info) ){
			if ( !empty($cid) ){
				$Template->set_block_vars('catvalrow', array(
					'ID'	=> $cid,
				));
			}
		}
		//---------------------------

		return true;
	}

	function do_send_issue(){
		global $DB, $SMTP, $Lang, $Func, $Info, $Template, $Session;

		$id				= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$newslt_id		= isset($_GET["newslt_id"]) ? intval($_GET["newslt_id"]) : 0;
		$newslt_start	= isset($_GET["newslt_start"]) ? intval($_GET["newslt_start"]) : 0;
		$newslt_limit	= 100;
		$Info->tpl_main	= "newslt_issue_send";
		$this->set_lang();

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		//Get issue info
		$DB->query("SELECT * FROM ". $DB->prefix ."newsletter_issue WHERE issue_id=$id $auth_where_sql");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["newslt_error_issue_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=newslt_issue". $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
		$issue_info	= $DB->fetch_array();
		$DB->free_result();

		//SMTP options
		$SMTP->message_charset($Lang->charset);
		$SMTP->message_subject($issue_info['title']);
		$issue_info['content']	= html_entity_decode($issue_info['content']);
		$issue_info['content']	= str_replace('src="images/', 'src="'. $Info->option['site_url'] .'images/', $issue_info['content']);
		$issue_info['content']	= str_replace("src='images/", "src='". $Info->option['site_url'] ."images/", $issue_info['content']);

		//Get email list
		$cat_info	= explode(',', $issue_info['cat_ids']);
		$where_sql	= ' AND (cat_id=0';
		reset($cat_info);
		while (list(,$cid) = each($cat_info)){
			if ( !empty($cid) ){
				if ( $cid == -1 ){
					$where_sql	= '';
					break;
				}
				else{
					$where_sql	.= ' OR cat_id='. intval($cid);
				}
			}
		}
		$where_sql	.= !empty($where_sql) ? ')' : '';

		$DB->query('SELECT newslt_id, name, email, reg_code FROM '. $DB->prefix .'newsletter WHERE enabled='. SYS_ENABLED . $where_sql .' ORDER BY newslt_id ASC LIMIT '. $newslt_start .','. ($newslt_limit + 1));
		$email_count	= $DB->num_rows();
		$email_data		= $DB->fetch_all_array();
		$DB->free_result();

		$SMTP->email_skip(SMTP_CODE_RESEND);
		$limit	= ($email_count < $newslt_limit) ? $email_count : $newslt_limit;
		for ($i=0; $i<$limit; $i++){
			//Skip sent emails
			if ( $newslt_id && ($email_data[$i]['newslt_id'] < $newslt_id) ){
				continue;
			}

			//Replace username, email in content ---------
			$content	= str_replace("{USER_NAME}", $email_data[$i]['name'], $issue_info['content']);
			$content	= str_replace("{USER_EMAIL}", $email_data[$i]['email'], $content);
			if ( $Info->option['short_url_enabled'] ){
				$url_unsubscribe	= $Info->option['site_url'] . MOD_NEWSLETTER .'/remove/'. $email_data[$i]['email'] .'/'. $email_data[$i]['reg_code'];
			}
			else{
				$url_unsubscribe	= $Info->option['site_url'] . HOME_INDEX .'?mod='. MOD_NEWSLETTER .'&act=remove&email='. $email_data[$i]['email'] .'&code='. $email_data[$i]['reg_code'];
			}
			$content	= str_replace("{URL_UNSUBSCRIBE}", $url_unsubscribe, $content);
			//--------------------------------------------

			//Send email ---------------------------------
			$SMTP->email_from($Info->option['admin_email']);
			$SMTP->email_to($email_data[$i]['email']);
			$SMTP->message_content($content);
			$result	= $SMTP->send();
			$SMTP->reset_email();
			//--------------------------------------------

			//Try to resend email if there is error ------
			if ( $result === SMTP_CODE_RESEND ){
				if ( $newslt_id == $email_data[$i]['newslt_id'] ){
					//Don't try to resend again
					continue;
				}
				else{
					//Try to resend
					$Template->page_transfer(sprintf($Lang->data['newslt_error_resend'], $SMTP->email_response), $Session->append_sid(ACP_INDEX ."?mod=newslt_issue&act=send&id=". $id ."&newslt_id=". $email_data[$i]['newslt_id'] ."&newslt_start=". $newslt_start), $SMTP->email_resend_time);
				}
				return false;
			}
			//--------------------------------------------
		}

		//Continue to send other emails
		if ( $email_count > $newslt_limit ){
			$newslt_end		= $newslt_start + $limit;
			$Template->page_transfer(sprintf($Lang->data['newslt_success_send_continue'], ($newslt_start + 1), ($newslt_end + 1)), $Session->append_sid(ACP_INDEX ."?mod=newslt_issue&act=send&newslt_start=". $newslt_end), $SMTP->email_resend_time);
			return false;
		}

		//Update sent counter
		$DB->query("UPDATE ". $DB->prefix ."newsletter_issue SET sent_counter=sent_counter+1, sent_date=". CURRENT_TIME ." WHERE issue_id=$id");

		$this->pre_send_issue($Lang->data['newslt_success_send_issue']);
		return true;
	}

	function view_issue(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$id			= isset($_GET['id']) ? intval($_GET['id']) : 0;
		$Info->tpl_main		= "newslt_issue_view";

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		$DB->query('SELECT * FROM '. $DB->prefix .'newsletter_issue WHERE issue_id='. $id . $auth_where_sql);
		if ( !$DB->num_rows() ){
			$Template->message_die($Lang->data['newslt_error_issue_not_exist']);
			return false;
		}
		$issue_info	= $DB->fetch_array();

		$Template->set_vars(array(
			'TITLE'			=> $issue_info['title'],
			'CONTENT'		=> html_entity_decode($issue_info['content']),
			'S_PAGE_ACTION'	=> $Session->append_sid(ACP_INDEX .'?mod=newslt_issue&act=view&id='. $id),
			"L_PAGE_TITLE"	=> $Lang->data["menu_newslt"] . $Lang->data['general_arrow'] . $Lang->data['menu_newslt_issue'] . $Lang->data['general_arrow'] . $Lang->data['general_view'],
			'L_PAGE'		=> $Lang->data['general_page'],
			'L_GO'			=> $Lang->data['button_go'],
			'L_CLOSE'		=> $Lang->data['general_close_window'],
		));
		return true;
	}

	function active_issues($enabled = 0){
		global $DB, $Template, $Func, $Info;

		$issue_ids		= isset($_POST['issue_ids']) ? $_POST['issue_ids'] : '';

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		if ( is_array($issue_ids) ){
			$log_act	= $enabled ? 'log_enable' : 'log_disable';
			$record_ids	= "";
			$where_sql	= "WHERE (issue_id=0";
			while ( list($id,) = each($issue_ids) ){
				$id	= intval($id);
				$where_sql	.= " OR issue_id=$id";

				if ( !empty($record_ids) ){
					$record_ids	.= ', ';
				}
				$record_ids	.= $id;
			}
			$where_sql	.= ")";

			$DB->query("UPDATE ". $DB->prefix ."newsletter_issue SET enabled=$enabled $where_sql $auth_where_sql");

			//Save log
			$Func->save_log(FUNC_NAME, $log_act, $record_ids);
		}

		$this->list_issues();
	}

	function delete_issues(){
		global $DB, $Template, $Func, $Info, $File;

		$issue_ids		= isset($_POST['issue_ids']) ? $_POST['issue_ids'] : '';

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		if ( is_array($issue_ids) ){
			$record_ids	= "";
			$where_sql	= "WHERE (issue_id=0";
			while ( list($id,) = each($issue_ids) ){
				$id		= intval($id);
				$where_sql	.= " OR issue_id=$id";
				
				if ( !empty($record_ids) ){
					$record_ids	.= ', ';
				}
				$record_ids	.= $id;
			}
			$where_sql	.= ")";

			//Get and delete image --------------
			$DB->query("SELECT * FROM ". $DB->prefix ."newsletter_issue $where_sql $auth_where_sql");
			$issue_count	= $DB->num_rows();
			$issue_data		= $DB->fetch_all_array();
			$DB->free_result();

			for ($i=0; $i<$issue_count; $i++){
				if ( !empty($issue_data[$i]['used_files']) ){
					//Delete images
					$file_info	= explode(',', $issue_data[$i]['used_files']);
					reset($file_info);
					while (list(, $filename) = each($file_info) ){
						$filename	= trim($filename);
						if ( !empty($filename) ){
							$File->delete_file($this->upload_path . $filename);
						}
					}
				}
			}
			//------------------------------------

			$DB->query("DELETE FROM ". $DB->prefix ."newsletter_issue $where_sql $auth_where_sql");

			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $record_ids);
		}

		$this->list_issues();
	}
}
?>
