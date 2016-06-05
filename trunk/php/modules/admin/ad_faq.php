<?php
/* =============================================================== *\
|		Module name: FAQ											|
|		Module version: 1.2											|
|		Begin: 10 November 2004										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_miscell_faq');
define('FUNC_ACT_VIEW', 'view');
//Module language
$Func->import_module_language("admin/lang_faq". PHP_EX);

$AdminFAQ = new Admin_FAQ;

class Admin_FAQ
{
	var $data		= array();
	var	$filter		= array();
	var $page		= 1;

	var $upload_path	= "images/faqs/";

	var $user_perm		= array();

	function Admin_FAQ(){
		global $Info, $Func, $Cache;

		$this->user_perm	= $Func->get_all_perms('menu_miscell_faq');
		$this->get_filter();

		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_faq();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$Cache->clear_cache(MOD_FAQ);
				$this->do_add_faq();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_faq();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache(MOD_FAQ);
				$this->do_edit_faq();
				break;
			case "view":
				$Func->check_user_perm($this->user_perm, 'view');
				$this->view_faq();
				break;
			case "update":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache(MOD_FAQ);
				$this->update_order();
				break;
			case "resync":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->resync_faqs();
				break;
			case "enable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache(MOD_FAQ);
				$this->active_faqs(1);
				break;
			case "disable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache(MOD_FAQ);
				$this->active_faqs(0);
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$Cache->clear_cache(MOD_FAQ);
				$this->delete_faqs();
				break;
			default:
				$this->list_faqs();
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

		$this->page			= isset($_GET["page"]) ? intval($_GET["page"]) : 1;

		$Template->set_vars(array(
			"FKEYWORD"	=> stripslashes($this->filter['keyword']),
			"FSTATUS"	=> $this->filter['status'],
		));
	}

	function list_faqs(){
		global $Session, $Func, $DB, $Info, $Template, $Lang;

		$itemperpage		= $Info->option['items_per_page'];
		$Info->tpl_main		= "faq_list";

		//Filter -------------------------
		$where_sql		= " WHERE faq_id>0";
		if ( ($this->filter['status'] == SYS_ENABLED) || ($this->filter['status'] == SYS_DISABLED) ){
			$where_sql	.= " AND enabled=". $this->filter['status'];
		}
		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*", '%', $this->filter['keyword']);
			$where_sql	.= " AND (question LIKE '%". $key ."%' OR answer LIKE '%". $key ."%')";
		}
		//--------------------------------

		//Generate pages
		$DB->query("SELECT count(faq_id) AS total FROM ". $DB->prefix ."faq ". $where_sql);
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=faq"));
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query("SELECT * FROM ". $DB->prefix ."faq ". $where_sql ." ORDER BY faq_order ASC LIMIT ". $pageshow['start'] .", $itemperpage");
		if ( $DB->num_rows() ){
			$i	= 0;
			while ( $result = $DB->fetch_array() ){
				$Template->set_block_vars("faqrow", array(
					"ID"				=> $result["faq_id"],
					"QUESTION"			=> $result["question"],
					"ORDER"				=> $result["faq_order"],
					'CSS'				=> $result["enabled"] ? 'enabled' : 'disabled',
					'BG_CSS'			=> ($i % 2) ? 'tdtext2' : 'tdtext1',
					'U_VIEW'			=> $Session->append_sid(ACP_INDEX .'?mod=faq&act=view&id='. $result["faq_id"]),
					'U_EDIT'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=faq&act=preedit&id='. $result["faq_id"] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
				));
				$i++;
			}
		}
		$DB->free_result();

		$Template->set_vars(array(
			'S_ACTION_FILTER'		=> $Session->append_sid(ACP_INDEX .'?mod=faq'),
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=faq&act=update' . $this->filter['url_append'] .'&page='. $this->page),
			'U_UPDATE'				=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="javascript: updateForm2(\''. $Session->append_sid(ACP_INDEX .'?mod=faq&act=update' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/update.gif" alt="" title="'. $Lang->data['general_update'] .'" border="0" align="absbottom">'. $Lang->data['general_update'] .'</a> &nbsp; &nbsp;' : '',
			'U_ADD'					=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=faq&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="'. $Lang->data['general_add'] .'" border="0" align="absbottom">'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp;' : '',
			'U_RESYNC'				=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=faq&act=resync' . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/resync.gif" alt="" title="'. $Lang->data['general_resync'] .'" border="0" align="absbottom">'. $Lang->data['general_resync'] .'</a>' : '',
			'U_ENABLE'				=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript: updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=faq&act=enable' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/enable.gif" alt="" title="'. $Lang->data['general_enable'] .'" align="absbottom" border=0>'. $Lang->data['general_enable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DISABLE'				=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript: updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=faq&act=disable' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/disable.gif" alt="" title="'. $Lang->data['general_disable'] .'" align="absbottom" border=0>'. $Lang->data['general_disable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'				=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript: deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=faq&act=del' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" align="absbottom" border=0>'. $Lang->data['general_del'] .'</a>' : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data["menu_miscell_faq"],
			"PAGE_OUT"				=> $pageshow['page'],
			"L_QUESTION"			=> $Lang->data["faq_question"],
			"L_ORDER"				=> $Lang->data["general_order"],
			"L_SEARCH"				=> $Lang->data["button_search"],
			'L_DEL_CONFIRM'			=> $Lang->data['faq_del_confirm'],
			'L_CHOOSE_ITEM'			=> $Lang->data['faq_error_not_check'],
		));
	}

	function pre_add_faq($msg = ""){
		global $Session, $DB, $Info, $Template, $Lang;

		$Info->tpl_main		= "faq_edit";

		$this->set_lang();

		$Template->set_block_vars("addrow", array());
		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=faq&act=add'),
			"QUESTION"				=> isset($this->data['question']) ? stripslashes($this->data['question']) : '',
			"ANSWER"				=> isset($this->data['answer']) ? stripslashes($this->data['answer']) : '',
			"USED_FILES"			=> isset($this->data["used_files"]) ? stripslashes($this->data["used_files"]) : '',
			"ENABLED"				=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : '',
			"PAGE_TO"				=> isset($this->data["page_to"]) ? $this->data["page_to"] : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data["menu_miscell_faq"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			"L_BUTTON"				=> $Lang->data["button_add"],
		));
	}

	function set_lang(){
		global $Session, $Template, $Lang;

		$Template->set_vars(array(
			"L_QUESTION"			=> $Lang->data["faq_question"],
			"L_ANSWER"				=> $Lang->data["faq_answer"],
			"L_PAGE_TO"				=> $Lang->data["general_page_to"],
			"L_PAGE_ADD"			=> $Lang->data["general_page_add"],
			"L_PAGE_LIST"			=> $Lang->data["general_page_list"],
		));
	}

	function do_add_faq(){
		global $Session, $DB, $Info, $Template, $Lang, $Func, $File;

		$this->data['question']		= isset($_POST["question"]) ? htmlspecialchars($_POST["question"]) : '';
		$this->data['answer']		= isset($_POST["answer"]) ? htmlspecialchars($_POST["answer"]) : '';
		$this->data["used_files"]	= isset($_POST["used_files"]) ? $_POST["used_files"] : '';
		$this->data["enabled"]		= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;
		$this->data["page_to"]		= isset($_POST["page_to"]) ? htmlspecialchars($_POST["page_to"]) : '';

		if ( empty($this->data['question']) || empty($this->data['answer']) ){
			$this->pre_add_faq($Lang->data['general_error_not_full']);
			return false;
		}

		//Get max order
		$DB->query("SELECT max(faq_order) as max_order FROM ". $DB->prefix ."faq");
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$max_order	= $result["max_order"] + 1;
		}
		else{
			$max_order	= 1;
		}
		$DB->free_result();

		if ( !empty($this->data['used_files']) ){
			//Transfer used_files and remove temp files
			$data_info['answer']	= $this->data['answer'];
			$File->transfer_temp_files($this->data["used_files"], $this->upload_path, $data_info);
			$this->data['answer']	= $data_info['answer'];
			//-----------------------------------------
		}

		$DB->query("INSERT INTO ". $DB->prefix ."faq(question, answer, used_files, faq_order, enabled) VALUES('". $this->data['question'] ."', '". $this->data['answer'] ."', '". $this->data['used_files'] ."', $max_order, ". $this->data['enabled'] .")");
		$faq_id		= $DB->insert_id();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $faq_id, ACP_INDEX .'?mod=faq&act='. FUNC_ACT_VIEW .'&id='. $faq_id);

		if ( $this->data['page_to'] == 'pageadd' ){
			$tmp_data['page_to']	= $this->data['page_to'];
//			$this->data	= array();//Reset data
			$this->data	= $tmp_data;
			$this->pre_add_faq($Lang->data['general_success_add']);
		}
		else{
			$this->list_faqs();
		}
		return true;
	}

	function pre_edit_faq($msg = ""){
		global $Session, $DB, $Info, $Template, $Lang;

		$id					= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$Info->tpl_main		= "faq_edit";

		$DB->query("SELECT * FROM ". $DB->prefix ."faq WHERE faq_id=$id");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["faq_error_not_exist"], $Session->append_sid(ACP_INDEX .'?mod=faq'));
			return false;
		}
		$faq_info	= $DB->fetch_array();
		$DB->free_result();

		$this->set_lang();
		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=faq&act=edit&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			"QUESTION"				=> isset($this->data['question']) ? stripslashes($this->data['question']) : $faq_info['question'],
			"ANSWER"				=> isset($this->data['answer']) ? stripslashes($this->data['answer']) : $faq_info['answer'],
			"USED_FILES"			=> isset($this->data["used_files"]) ? stripslashes($this->data["used_files"]) : '',
			"ENABLED"				=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : $faq_info['answer'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data["menu_miscell_faq"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			"L_BUTTON"				=> $Lang->data["button_edit"],
		));
		return true;
	}

	function do_edit_faq(){
		global $Session, $DB, $Info, $Template, $Lang, $Func, $File;

		$id							= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$this->data['question']		= isset($_POST["question"]) ? htmlspecialchars($_POST["question"]) : '';
		$this->data['answer']		= isset($_POST["answer"]) ? htmlspecialchars($_POST["answer"]) : '';
		$this->data["used_files"]	= isset($_POST["used_files"]) ? $_POST["used_files"] : '';
		$this->data["enabled"]		= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;

		if ( empty($this->data['question']) || empty($this->data['answer']) ){
			$this->pre_edit_faq($Lang->data['general_error_not_full']);
			return false;
		}

		//Get faq info
		$DB->query('SELECT used_files FROM '. $DB->prefix .'faq WHERE faq_id='. $id);
		if ( !$DB->num_rows() ){
			$this->list_faqs();
			return false;
		}
		$faq_info		= $DB->fetch_array();

		if ( !empty($this->data['used_files']) ){
			//Transfer used_files and remove temp files
			$data_info['answer']	= $this->data['answer'];
			$File->transfer_temp_files($this->data["used_files"], $this->upload_path, $data_info);
			$this->data['answer']	= $data_info['answer'];
			//-----------------------------------------
		}

		//Clean old used files ----------
		$data_info['answer']	= $this->data['answer'];
		$File->clean_used_files($faq_info["used_files"], $this->upload_path, $data_info, $this->data['used_files']);
		$this->data['answer']		= $data_info['answer'];
		//-------------------------------

		//Delete files which are not used
		$File->delete_unused_files($this->data["used_files"], $faq_info['used_files'], $this->upload_path);

		//Update faq
		$DB->query("UPDATE ". $DB->prefix ."faq SET question='". $this->data['question'] ."', answer='". $this->data['answer'] ."', used_files='". $this->data['used_files'] ."', enabled=". $this->data['enabled'] ." WHERE faq_id=$id");

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=faq&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_faqs();
		return true;
	}

	function view_faq(){
		global $Session, $DB, $Template, $Lang, $Info;

		$id			= isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main		= "faq_view";

		$DB->query("SELECT * FROM ". $DB->prefix ."faq WHERE faq_id=$id");
		if ( !$DB->num_rows() ){
			$Template->message_die($Lang->data['faq_error_not_exist']);
			return false;
		}
		$faq_info = $DB->fetch_array();
		$DB->free_result();

		$Template->set_vars(array(
			"QUESTION"              => $faq_info["question"],
			"ANSWER"                => html_entity_decode($faq_info["answer"]),
			"L_PAGE_TITLE"			=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data['menu_miscell_faq'] . $Lang->data['general_arrow'] . $Lang->data['general_view'],
		));
		return true;
	}

	function update_order(){
		global $Session, $Template, $Lang, $DB, $Func;

		$faq_orders	= isset($_POST["faq_orders"]) ? $_POST["faq_orders"] : '';

		if ( is_array($faq_orders) ){
			reset($faq_orders);
			while ( list($id, $num) = each($faq_orders) ){
				$DB->query("UPDATE ". $DB->prefix ."faq SET faq_order=". intval($num) ." WHERE faq_id=". intval($id));
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_update');

		$this->list_faqs();
	}

	function active_faqs($enabled=0){
		global $DB, $Template, $Func;

		$faq_ids	= isset($_POST['faq_ids']) ? $_POST['faq_ids'] : '';
		$ids_info	= $Func->get_array_value($faq_ids);

		if ( sizeof($ids_info) ){
			$log_act	= $enabled ? 'log_enable' : 'log_disable';
			$str_ids	= implode(',', $ids_info);

			//Update faqs
			$DB->query("UPDATE ". $DB->prefix ."faq SET enabled=$enabled WHERE faq_id IN (". $str_ids .")");
			//Save log
			$Func->save_log(FUNC_NAME, $log_act, $str_ids);
		}

		$this->list_faqs();
	}

	function delete_faqs(){
		global $DB, $Template, $Func, $File;

		$faq_ids		= isset($_POST['faq_ids']) ? $_POST['faq_ids'] : '';
		$ids_info		= $Func->get_array_value($faq_ids);

		if ( sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);
			$where_sql	= " WHERE faq_id IN (". $str_ids .")";

			//Get and delete images ------------
			$DB->query("SELECT used_files FROM ". $DB->prefix ."faq $where_sql");
			$faq_count	= $DB->num_rows();
			$faq_data		= $DB->fetch_all_array();
			$DB->free_result();

			for ($i=0; $i<$faq_count; $i++){
				if ( !empty($faq_data[$i]['used_files']) ){
					//Delete images
					$file_info	= explode(',', $faq_data[$i]['used_files']);
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

			//Delete faqs
			$DB->query("DELETE FROM ". $DB->prefix ."faq $where_sql");
			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $str_ids);
		}

		$this->list_faqs();
	}

	function resync_faqs(){
		global $DB, $Template, $Lang, $Func;

		$DB->query('SELECT faq_id, faq_order FROM '. $DB->prefix .'faq ORDER BY faq_order ASC');
		$faq_count		= $DB->num_rows();
		$faq_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$faq_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'faq SET faq_order='. ($i + 1) .' WHERE faq_id='. $faq_data[$i]['faq_id']);
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_resync');

		$this->list_faqs();
	}
}

?>