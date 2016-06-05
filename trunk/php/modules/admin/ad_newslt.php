<?php
/* =============================================================== *\
|		Module name:      Newsletter								|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_newslt_email');
//Module language
$Func->import_module_language("admin/lang_newsletter". PHP_EX);

$AdminNewslt = new Admin_Newsletter;

class Admin_Newsletter
{
	var $data			= array();
	var $filter			= array();
	var $page			= 1;
	var $cat_count		= 0;
	var $cat_data		= array();

	var $user_perm		= array();

	function Admin_Newsletter(){
		global $Info, $Func;

		$this->user_perm	= $Func->get_all_perms('menu_newslt_email');
		$this->get_filter();
		$this->get_all_cats();
		$this->set_all_cats(0, 0);

		switch ($Info->act){
			case "preadd":
				$this->user_perm	= $Func->get_all_perms('menu_newslt_email');
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_email();
				break;
			case "add":
				$this->user_perm	= $Func->get_all_perms('menu_newslt_email');
				$Func->check_user_perm($this->user_perm, 'add');
				$this->do_add_email();
				break;
			case "preedit":
				$this->user_perm	= $Func->get_all_perms('menu_newslt_email');
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_email();
				break;
			case "edit":
				$this->user_perm	= $Func->get_all_perms('menu_newslt_email');
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->do_edit_email();
				break;
			case "enable":
				$this->user_perm	= $Func->get_all_perms('menu_newslt_email');
				$Func->check_user_perm($this->user_perm, 'active');
				$this->active_emails(1);
				break;
			case "disable":
				$this->user_perm	= $Func->get_all_perms('menu_newslt_email');
				$Func->check_user_perm($this->user_perm, 'active');
				$this->active_emails(0);
				break;
			case "del":
				$this->user_perm	= $Func->get_all_perms('menu_newslt_email');
				$Func->check_user_perm($this->user_perm, 'del');
				$this->delete_emails();
				break;
			case "premovecat":
				$Func->check_user_perm($this->user_perm, 'move_email');
				$this->pre_move_cat();
				break;
			case "movecat":
				$Func->check_user_perm($this->user_perm, 'move_email');
				$this->do_move_cat();
				break;
			case "preimport":
				$this->user_perm	= $Func->get_all_perms('menu_newslt_email');
				$Func->check_user_perm($this->user_perm, 'import');
				$this->pre_import();
				break;
			case "import":
				$this->user_perm	= $Func->get_all_perms('menu_newslt_email');
				$Func->check_user_perm($this->user_perm, 'import');
				$this->do_import();
				break;
			case "export":
				$this->user_perm	= $Func->get_all_perms('menu_newslt_email');
				$Func->check_user_perm($this->user_perm, 'export');
				$this->export_emails();
				break;
			default:
				$this->user_perm	= $Func->get_all_perms('menu_newslt_email');
				$this->list_emails();
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

	function list_emails(){
		global $Session, $Func, $Info, $DB, $Template, $Lang;

		$Info->tpl_main	= "newslt_list";
		$itemperpage	= $Info->option['items_per_page'];

		//Filter -------------------------
		$where_sql		= " WHERE email!=''";
		if ( ($this->filter['status'] == SYS_ENABLED) || ($this->filter['status'] == SYS_DISABLED) ){
			$where_sql	.= " AND enabled=". $this->filter['status'];
		}
		if ( $this->filter['cat_id'] ){
			$where_sql	.= " AND cat_id=". $this->filter['cat_id'];
		}
		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*", '%', $this->filter['keyword']);
			$where_sql	.= " AND (name LIKE '%". $key ."%' OR email LIKE '%". $key ."%')";
		}
		//------------------------------------

		//Generate pages
		$DB->query("SELECT count(email) AS total FROM ". $DB->prefix ."newsletter $where_sql");
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=newslt" . $this->filter['url_append']));
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query("SELECT * FROM ". $DB->prefix ."newsletter $where_sql ORDER BY email ASC LIMIT ". $pageshow['start'] .",$itemperpage");
		$email_count	= $DB->num_rows();
		$email_data		= $DB->fetch_all_array();

		for ($i=0; $i<$email_count; $i++){
			$Template->set_block_vars("emailrow",array(
				"CSS"			=> ($email_data[$i]["enabled"] == SYS_ENABLED) ? "enabled" : "disabled",
				"ID"			=> $email_data[$i]["newslt_id"],
				"NAME"			=> $email_data[$i]["name"],
				"EMAIL"			=> $email_data[$i]["email"],
				'BG_CSS'		=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				'U_EDIT'		=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=newslt&act=preedit&id='. $email_data[$i]["newslt_id"] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
			));
		}
		$DB->free_result();

		$Template->set_vars(array(
			"PAGE_OUT"				=> $pageshow['page'],
			'S_FILTER_ACTION'		=> $Session->append_sid(ACP_INDEX .'?mod=newslt'),
			'S_LIST_ACTION'			=> $Session->append_sid(ACP_INDEX .'?mod=newslt&act=update'. $this->filter['url_append']),
			'U_ADD'					=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=newslt&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="'. $Lang->data['general_add'] .'" align="absbottom" border=0>'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp; ' : '',
			'U_IMPORT'				=> $Func->check_user_perm($this->user_perm, 'import', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=newslt&act=preimport') .'"><img src="'. $Info->option['template_path'] .'/images/admin/import.gif" alt="" title="'. $Lang->data['general_import'] .'" align="absbottom" border=0>'. $Lang->data['general_import'] .'</a> &nbsp; &nbsp; ' : '',
			'U_EXPORT'				=> $Func->check_user_perm($this->user_perm, 'export', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=newslt&act=export') .'"><img src="'. $Info->option['template_path'] .'/images/admin/export.gif" alt="" title="'. $Lang->data['general_export'] .'" align="absbottom" border=0>'. $Lang->data['general_export'] .'</a> &nbsp; &nbsp; ' : '',
			'U_ENABLE'				=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=newslt&act=enable' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/enable.gif" alt="" title="'. $Lang->data['general_enable'] .'" align="absbottom" border=0>'. $Lang->data['general_enable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DISABLE'				=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=newslt&act=disable' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/disable.gif" alt="" title="'. $Lang->data['general_disable'] .'" align="absbottom" border=0>'. $Lang->data['general_disable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'				=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript:deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=newslt&act=del' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" align="absbottom" border=0>'. $Lang->data['general_del'] .'</a> &nbsp; &nbsp;' : '',
			'U_MOVE_CAT'			=> $Func->check_user_perm($this->user_perm, 'move_email', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=newslt&act=premovecat' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/move.gif" alt="" title="'. $Lang->data["newslt_move_cat"] .'" align="absbottom" border=0>'. $Lang->data["newslt_move_cat"] .'</a> &nbsp; &nbsp; ' : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_newslt"] . $Lang->data['general_arrow'] . $Lang->data["menu_newslt_email"],
			"L_CATS"				=> $Lang->data["general_cat"],
			"L_NAME"				=> $Lang->data["newslt_name"],
			"L_EMAIL"				=> $Lang->data["newslt_email"],
			"L_SEARCH"				=> $Lang->data["button_search"],
			"L_DEL_CONFIRM"			=> $Lang->data['newslt_del_confirm'],
			"L_CHOOSE_ITEM"			=> $Lang->data['newslt_error_not_check'],
		));
	}

	function pre_add_email($msg = ""){
		global $Session, $Info, $DB, $Template, $Lang;

		$Info->tpl_main	= "newslt_edit";
		$this->set_lang();

		$Template->set_block_vars("addrow",array());
		$Template->set_vars(array(
			"ERROR_MSG"			=> $msg,
			'S_ACTION'			=> $Session->append_sid(ACP_INDEX .'?mod=newslt&act=add'. $this->filter['url_append']),
			"CAT_ID"			=> isset($this->data["cat_id"]) ? $this->data["cat_id"] : '',
			"ENABLED"			=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : '',
			"PAGE_TO"			=> isset($this->data["page_to"]) ? $this->data["page_to"] : '',
			"L_PAGE_TITLE"		=> $Lang->data["menu_newslt"] . $Lang->data['general_arrow'] . $Lang->data["menu_newslt_email"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			"L_BUTTON"			=> $Lang->data["button_add"],
		));
	}

	function set_lang(){
		global $Session, $Template, $Lang, $Info;

		$Template->set_vars(array(
			"L_CAT"				=> $Lang->data["general_cat"],
			"L_NAME"			=> $Lang->data["newslt_name"],
			"L_EMAIL"			=> $Lang->data["newslt_email"],
			"L_CHOOSE"			=> $Lang->data["general_choose"],
			"L_PAGE_TO"			=> $Lang->data["general_page_to"],
			"L_PAGE_ADD"		=> $Lang->data["general_page_add"],
			"L_PAGE_LIST"		=> $Lang->data["general_page_list"],
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
					'EMAIL_COUNTER'		=> $this->cat_data[$i]['email_counter'],
					'SUBCAT_COUNTER'	=> $this->cat_data[$i]['children_counter'],
					'PREFIX'			=> $str_prefix .$symbol,
					"U_EDIT_CAT"		=> $Session->append_sid(ACP_INDEX .'?mod=newslt_cat&act=preedit&id='. $this->cat_data[$i]['cat_id'] .'&page='. $this->page),
					"U_DEL_CAT"			=> $Session->append_sid(ACP_INDEX .'?mod=newslt_cat&act=predel&id='. $this->cat_data[$i]['cat_id'] .'&page='. $this->page),
				));
				$this->set_all_cats($this->cat_data[$i]['cat_id'], $except_cid, $level+1, $symbol, $prefix);
			}
		}
	}

	function do_add_email(){
		global $Session, $Info, $DB, $Template, $Lang, $Func;

		$this->data["cat_id"]		= isset($_POST["cat_id"]) ? intval($_POST["cat_id"]) : 0;
		$this->data["names"]		= isset($_POST["names"]) ? $_POST["names"] : '';
		$this->data["emails"]		= isset($_POST["emails"]) ? $_POST["emails"] : '';
		$this->data["enabled"]		= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;
		$this->data["page_to"]		= isset($_POST["page_to"]) ? htmlspecialchars($_POST["page_to"]) : '';

		reset($this->data["emails"]);
		while ( list($key, $email) = each($this->data["emails"]) ){
			$name	= isset($this->data["names"][$key]) ? htmlspecialchars(trim($this->data["names"][$key])) : '';
			$email	= htmlspecialchars(trim($email));
			if ( !empty($email) && $Func->check_email($email) ){
				//Check exist
				$DB->query("SELECT * FROM ". $DB->prefix ."newsletter WHERE email='". $email ."'");
				if ( !$DB->num_rows() ){
					$regcode = rand(100000,999999);//6 digit number
					//Insert new email
					$DB->query("INSERT INTO ". $DB->prefix ."newsletter(cat_id, name, email, reg_code, enabled) VALUES(". $this->data['cat_id'] .", '". $name ."', '". $email ."', ". $regcode .", ". $this->data['enabled'] .")");
				}
			}
		}

		//Update cat_counter
		$this->resync_cats();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add');

		if ( $this->data['page_to'] == 'pageadd' ){
			$tmp_data['cat_id']			= $this->data['cat_id'];
			$tmp_data['enabled']		= $this->data['enabled'];
			$tmp_data['page_to']		= $this->data['page_to'];
//			$this->data	= array();//Reset data
			$this->data	= $tmp_data;
			$this->pre_add_email($Lang->data['general_success_add']);
		}
		else{
			$this->list_emails();
		}

		return true;
	}

	function pre_edit_email($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$id			= isset($_GET["id"]) ? htmlspecialchars($_GET["id"]) : '';
		$Info->tpl_main	= "newslt_edit";
		$this->set_lang();

		$DB->query("SELECT * FROM ". $DB->prefix ."newsletter WHERE newslt_id=". $id);
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["newslt_error_email_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=newslt". $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
		$email_info	= $DB->fetch_array();
		$DB->free_result();

		$Template->set_block_vars("editrow", array());
		$Template->set_vars(array(
			"ERROR_MSG"			=> $msg,
			'S_ACTION'			=> $Session->append_sid(ACP_INDEX .'?mod=newslt&act=edit&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			"CAT_ID"			=> isset($this->data["cat_id"]) ? $this->data["cat_id"] : $email_info['cat_id'],
			"NAME"				=> isset($this->data["name"]) ? stripslashes($this->data["name"]) : $email_info['name'],
			"EMAIL"				=> isset($this->data["email"]) ? stripslashes($this->data["email"]) : $email_info['email'],
			"ENABLED"			=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : $email_info['enabled'],
			"L_PAGE_TITLE"		=> $Lang->data["menu_newslt"] . $Lang->data['general_arrow'] . $Lang->data["menu_newslt_email"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			"L_BUTTON"			=> $Lang->data["button_edit"],
		));
		return true;
	}

	function do_edit_email(){
		global $Session, $Info, $DB, $Template, $Lang, $Func;

		$id		= isset($_GET['id']) ? intval($_GET['id']) : 0;
		$this->data["cat_id"]	= isset($_POST["cat_id"]) ? intval($_POST["cat_id"]) : 0;
		$this->data["name"]		= isset($_POST["name"]) ? htmlspecialchars(trim($_POST["name"])) : '';
		$this->data["email"]	= isset($_POST["email"]) ? htmlspecialchars(trim($_POST["email"])) : '';
		$this->data["enabled"]	= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;

		if ( empty($this->data["email"]) ){
			$this->pre_edit_email($Lang->data["general_error_not_full"]);
			return false;
		}

		//Check exist
		$DB->query("SELECT * FROM ". $DB->prefix ."newsletter WHERE email='". $this->data["email"]."' AND newslt_id!=". $id);
		if ( !$DB->num_rows() ){
			//Update email
			$DB->query("UPDATE ". $DB->prefix ."newsletter SET cat_id=". $this->data["cat_id"] .", name='". $this->data["name"] ."', email='". $this->data["email"]."', enabled=". $this->data['enabled']." WHERE newslt_id=". $id);
		}

		//Update cat_counter
		$this->resync_cats();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $this->data['email']);

		$this->list_emails();
		return true;
	}

	function active_emails($enabled = 0){
		global $DB, $Template, $Func;

		$newslt_ids		= isset($_POST['newslt_ids']) ? $_POST['newslt_ids'] : '';
		$ids_info		= $Func->get_array_value($newslt_ids);

		if ( sizeof($ids_info) ){
			$log_act	= $enabled ? 'log_enable' : 'log_disable';
			$str_ids	= implode(',', $ids_info);

			//Update emails
			$DB->query("UPDATE ". $DB->prefix ."newsletter SET enabled=$enabled WHERE newslt_id IN (". $str_ids .")");
			//Save log
			$Func->save_log(FUNC_NAME, $log_act, $str_ids);
		}

		$this->list_emails();
	}

	function delete_emails(){
		global $DB, $Template, $Func;

		$newslt_ids		= isset($_POST['newslt_ids']) ? $_POST['newslt_ids'] : '';
		$ids_info		= $Func->get_array_value($newslt_ids);

		if ( sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);

			//Delete emails
			$DB->query("DELETE FROM ". $DB->prefix ."newsletter WHERE newslt_id IN (". $str_ids .")");
			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $str_ids);
			//Update cat_counter
			$this->resync_cats();
		}

		$this->list_emails();
	}

	function pre_move_cat(){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$Info->tpl_main	= "newslt_move_cat";
		$newslt_ids		= isset($_POST['newslt_ids']) ? $_POST['newslt_ids'] : '';
		$ids_info		= $Func->get_array_value($newslt_ids);

		if ( !sizeof($ids_info) ){
			$this->list_emails();
			return false;
		}

		$str_ids	= implode(',', $ids_info);
		$DB->query('SELECT newslt_id, email FROM '. $DB->prefix .'newsletter WHERE newslt_id IN ('. $str_ids .')');
		$newslt_count	= $DB->num_rows();
		$newslt_data	= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$newslt_count; $i++){
			$Template->set_block_vars("newsltrow", array(
				'EMAIL'		=> $newslt_data[$i]['email'],
				'U_VIEW'	=> $Session->append_sid(ACP_INDEX .'?mod=newslt&act=view&id='. $newslt_data[$i]["newslt_id"]),
			));
		}

		$Template->set_vars(array(
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=newslt&act=movecat'. $this->filter['url_append'] .'&page='. $this->page),
			'NEWSLT_IDS'			=> $str_ids,
			'NEWSLT_COUNT'			=> $newslt_count,
			'L_NEWSLT_COUNT'		=> $Lang->data['newslt_emails'],
			'L_MOVE_TO_CAT'			=> $Lang->data['newslt_move_cat'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_newslt"] . $Lang->data['general_arrow'] . $Lang->data["menu_newslt_email"] . $Lang->data['general_arrow'] . $Lang->data["newslt_move_cat"],
			"L_BUTTON"				=> $Lang->data["button_move"],
		));
		return true;
	}

	function do_move_cat(){
		global $DB, $Template, $Lang, $Info, $Func;

		$newslt_ids	= isset($_POST['newslt_ids']) ? explode(',', $_POST['newslt_ids']) : '';
		$cat_id		= isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;

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

		$ids_info	= $Func->get_array_value($newslt_ids);
		if ( $cat_id && sizeof($ids_info) ){
			$str_ids	= implode($ids_info);

			//Update emails
			$DB->query('UPDATE '. $DB->prefix .'newsletter SET cat_id='. $cat_id .' WHERE newslt_id IN ('. $str_ids .') '. $auth_where_sql);
			//Update cat_counter
			$this->resync_cats();
			//Save log
			$Func->save_log(FUNC_NAME, 'log_move_cat', $str_ids);
		}

		$this->list_emails();
	}

	function pre_import($msg = ""){
		global $Template, $Lang, $Session, $Info;

		$Info->tpl_main	= "newslt_import";

		$Template->set_vars(array(
			'ERROR_MSG'			=> $msg,
			'S_ACTION'			=> $Session->append_sid(ACP_INDEX .'?mod=newslt&act=import'),
			'L_CHOOSE_FILE'		=> $Lang->data['newslt_choose_file'],
			"L_PAGE_TITLE"		=> $Lang->data["menu_newslt"] . $Lang->data['general_arrow'] . $Lang->data["general_import"],
			"L_BUTTON"			=> $Lang->data["button_import"],
		));
	}

	function do_import(){
		global $DB, $Template, $Lang, $Info, $Func;

		$email_list		= isset($_FILES['email_list']['name']) ? htmlspecialchars($_FILES['email_list']['name']) : '';

		if ( empty($email_list) ){
			$this->pre_import($Lang->data['general_error_not_full']);
			return false;
		}

//		$file_content		= str_replace(' ', '', implode('', file($_FILES['email_list']['tmp_name'])));
		$file_content		= implode('', file($_FILES['email_list']['tmp_name']));
		if ( !empty($file_content) ){
			//Get old emails
			$DB->query('SELECT email FROM '. $DB->prefix .'newsletter ORDER BY email ASC');
			$email_data		= array();
			if ( $DB->num_rows() ){
				while ($result	= $DB->fetch_array()){
					$email_data[]	= $result['email'];
				}
			}
			$DB->free_result();

			$email_info		= explode(';', $file_content);
			reset($email_info);
			while (list(, $email_string) = each($email_info)){
				$name_email	= explode(':', $email_string);
				$email		= isset($name_email[0]) ? trim($name_email[0]) : '';
				$name		= isset($name_email[1]) ? trim($name_email[1]) : '';
				$cat_id		= isset($name_email[2]) ? intval($name_email[2]) : 0;
				$enabled	= isset($name_email[3]) ? intval($name_email[3]) : 1;
				if ( !empty($email) && $Func->check_email($email) && !in_array($email, $email_data) ){
					//Check exist
					$DB->query("SELECT email FROM ". $DB->prefix ."newsletter WHERE email='". $email ."'");
					if ( !$DB->num_rows() ){
						$regcode	= rand(100000,999999);//6 digit number
						//Insert new email
						$DB->query("INSERT INTO ". $DB->prefix ."newsletter(cat_id, name, email, reg_code, enabled) VALUES(". $cat_id .", '". $name ."', '". $email ."', ". $regcode .", ". $enabled .")");
					}
				}
			}
			unset($email_data);
			unset($email_info);
		}

		$this->list_emails();
		return true;
	}

	function export_emails(){
		global $DB, $Template, $Lang, $Info;

		$DB->query('SELECT * FROM '. $DB->prefix .'newsletter ORDER BY email ASC');
		$email_count	= $DB->num_rows();
		$email_data		= $DB->fetch_all_array();
		$DB->free_result();

		$email_string	= "";
		for ($i=0; $i<$email_count; $i++){
			//Email
			$email_string		.= $email_data[$i]['email'];
			//Name
			$email_string	.= ':'. $email_data[$i]['name'];
			//Category
			$email_string	.= ':'. $email_data[$i]['cat_id'];
			//Status
			$email_string	.= ':'. $email_data[$i]['enabled'];
			//Separate
			if ($i < $email_count - 1){
				$email_string	.= ' ; ';
			}
		}

		@ob_start();
		@ob_implicit_flush(0);
		header('Content-Type: text/x-delimtext; name="email_list.txt.gz"');
		header('Content-disposition: attachment; filename=email_list.txt.gz');
		header("Pragma: no-cache");
		header("Expires: 0");

		echo $email_string;

		$gzip_contents	= ob_get_contents();
		ob_end_clean();

		$gzip_size		= strlen($gzip_contents);
		$gzip_crc		= crc32($gzip_contents);

		$gzip_contents	= gzcompress($gzip_contents, 9);
		$gzip_contents	= substr($gzip_contents, 0, strlen($gzip_contents) - 4);

		echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
		echo $gzip_contents;
		echo pack('V', $gzip_crc);
		echo pack('V', $gzip_size);

		@$DB->close();
		die();
	}

	function resync_cats(){
		global $Session, $DB, $Template, $Lang;

		$DB->query('UPDATE '. $DB->prefix .'newsletter_category SET email_counter=0');

		//Update newslt_counter
		$DB->query('SELECT count(newslt_id) AS counter, cat_id FROM '. $DB->prefix.'newsletter GROUP BY cat_id');
		$cat_count = $DB->num_rows();
		$cat_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$cat_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'newsletter_category SET email_counter='. $cat_data[$i]['counter'] .' WHERE cat_id='. $cat_data[$i]['cat_id']);
		}
	}
}
?>
