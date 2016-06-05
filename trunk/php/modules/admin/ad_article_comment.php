<?php
/* =============================================================== *\
|		Module name:      Admin Comment								|
|		Module version:   1.1										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_article_comment');
define('FUNC_ACT_VIEW', 'view');
//Module language
$Func->import_module_language("admin/lang_article". PHP_EX);

$AdComment	= new Admin_Comment;

class Admin_Comment
{
	var $filter			= array();
	var $page			= 1;

	var $user_perm		= array();

	function Admin_Comment(){
		global $Info, $Func, $Cache;

		$this->user_perm	= $Func->get_all_perms('menu_article_comment');
		$this->get_filter();

		switch ($Info->act){
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_comment();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->do_edit_comment();
				$Func->set_update_schedule();
				break;
			case "enable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->active_comments(1);
				$Func->set_update_schedule();
				break;
			case "disable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->active_comments(0);
				$Func->set_update_schedule();
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->delete_comments();
				$Func->set_update_schedule();
				break;
			case "view":
				$Func->check_user_perm($this->user_perm, 'view');
				$this->view_comment();
				break;
			default:
				$this->list_comments();
		}
	}

	function get_filter(){
		global $Template, $Func;

		$this->filter['url_append']	= "";

		$this->filter['article_id']		= intval($Func->get_request('farticle_id', 0));
		if ( $this->filter['article_id'] ){
			$this->filter['url_append']	.= '&farticle_id='. $this->filter['article_id'];
		}

		$this->filter['keyword']		= htmlspecialchars($Func->get_request('fkeyword', ''));
		if ( !empty($this->filter['keyword']) ){
			$this->filter['url_append']	.= '&fkeyword='. $this->filter['keyword'];
		}

		$this->filter['status']			= intval($Func->get_request('fstatus', -1));
		if ( $this->filter['status'] != -1 ){
			$this->filter['url_append']	.= '&fstatus='. $this->filter['status'];
		}

		$this->page			= intval($Func->get_request('page', 1, 'GET'));

		$Template->set_vars(array(
			"FARTICLE_ID"	=> $this->filter['article_id'] ? $this->filter['article_id'] : '',
			"FKEYWORD"		=> stripslashes($this->filter['keyword']),
			"FSTATUS"		=> $this->filter['status'],
		));
	}

	function list_comments(){
		global $Session, $Func, $Info, $DB, $Template, $Lang;

		$Info->tpl_main	= "article_comment_list";
		$itemperpage	= $Info->option['items_per_page'];
		$date_format	= $Info->option['date_format'];
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
		$where_sql		= " WHERE comment_id>0";
		if ( $this->filter['article_id'] ){
			$where_sql	.= " AND article_id=". $this->filter['article_id'];
		}
		if ( ($this->filter['status'] == SYS_ENABLED) || ($this->filter['status'] == SYS_DISABLED) ){
			$where_sql	.= " AND enabled=". $this->filter['status'];
		}
		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*", '%', $this->filter['keyword']);
			$where_sql	.= " AND (title LIKE '%". $key ."%' OR content LIKE '%". $key ."%' OR name LIKE '%". $key ."%' OR email LIKE '%". $key ."%')";
		}
		//------------------------------------

		//Generate pages
		$DB->query("SELECT count(comment_id) AS total FROM ". $DB->prefix ."article_comment $where_sql $auth_where_sql");
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=article_comment" . $this->filter['url_append']));
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query("SELECT * FROM ". $DB->prefix ."article_comment $where_sql $auth_where_sql ORDER BY posted_date DESC LIMIT ". $pageshow['start'] .",$itemperpage");
		$comment_count		= $DB->num_rows();
		$comment_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$comment_count; $i++){
			$Template->set_block_vars("commentrow",array(
				"ID"			=> $comment_data[$i]["comment_id"],
				"ARTICLE_ID"	=> $comment_data[$i]["article_id"],
				"CSS"			=> ($comment_data[$i]["enabled"] == SYS_ENABLED) ? "enabled" : "disabled",
				'BG_CSS'		=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				"NAME"			=> $comment_data[$i]["name"],
				"EMAIL"			=> $comment_data[$i]["email"],
				"TITLE"			=> $comment_data[$i]["title"],
				"DATE"			=> $Func->translate_date(gmdate($date_format, $comment_data[$i]["posted_date"] + $timezone)),
				'U_VIEW'		=> $Session->append_sid(ACP_INDEX .'?mod=article_comment&act=view&id='. $comment_data[$i]["comment_id"]),
				'U_VIEW_ARTICLE'	=> $Session->append_sid(ACP_INDEX .'?mod=article&act=view&id='. $comment_data[$i]["article_id"]),
				'U_EDIT'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_comment&act=preedit&id='. $comment_data[$i]["comment_id"] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
			));
		}

		$Template->set_vars(array(
			"PAGE_OUT"			=> $pageshow['page'],
			'S_FILTER_ACTION'	=> $Session->append_sid(ACP_INDEX .'?mod=article_comment'),
			'S_LIST_ACTION'		=> $Session->append_sid(ACP_INDEX .'?mod=article_comment&act=update'. $this->filter['url_append']),
			'U_ENABLE'			=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article_comment&act=enable' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/enable.gif" alt="" title="'. $Lang->data['general_enable'] .'" align="absbottom" border=0>'. $Lang->data['general_enable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DISABLE'			=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article_comment&act=disable' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/disable.gif" alt="" title="'. $Lang->data['general_disable'] .'" align="absbottom" border=0>'. $Lang->data['general_disable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'			=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript:deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article_comment&act=del' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" align="absbottom" border=0>'. $Lang->data['general_del'] .'</a> &nbsp; &nbsp;' : '',
			"L_PAGE_TITLE"		=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_comment"],
			"L_TITLE"			=> $Lang->data["comment_title"],
			"L_AUTHOR"			=> $Lang->data["comment_author"],
			"L_ARTICLE"			=> $Lang->data["article_id"],
			"L_DATE"			=> $Lang->data["general_date"],
			"L_SEARCH"			=> $Lang->data["button_search"],
			'L_DEL_CONFIRM'		=> $Lang->data['comment_del_confirm'],
			'L_CHOOSE_ITEM'		=> $Lang->data['comment_error_not_check'],
		));
	}

	function set_lang(){
		global $Session, $Template, $Lang, $Info;

		$Template->set_vars(array(
			"L_CHOOSE"				=> $Lang->data["general_choose"],
			"L_TITLE"				=> $Lang->data["comment_title"],
			"L_CONTENT"				=> $Lang->data["comment_content"],
			"L_NAME"				=> $Lang->data["comment_name"],
			"L_EMAIL"				=> $Lang->data["comment_email"],
			"L_YES"					=> $Lang->data["general_yes"],
			"L_NO"					=> $Lang->data["general_no"],
			"L_PAGE_TO"				=> $Lang->data["general_page_to"],
			"L_PAGE_ADD"			=> $Lang->data["general_page_add"],
			"L_PAGE_LIST"			=> $Lang->data["general_page_list"],
			"L_SAVE_AS"				=> $Lang->data["general_save_as"],
			"L_SAVE"				=> $Lang->data["general_save"],
			"L_COPY"				=> $Lang->data["general_copy"],
		));
	}

	function pre_edit_comment($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$Info->tpl_main	= "article_comment_edit";
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

		$DB->query("SELECT * FROM ". $DB->prefix ."article_comment WHERE comment_id=$id $auth_where_sql");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["comment_error_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=article_comment". $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
		$comment_info	= $DB->fetch_array();
		$DB->free_result();

		$Template->set_block_vars("editrow", array());
		$Template->set_vars(array(
			"ERROR_MSG"			=> $msg,
			'S_ACTION'			=> $Session->append_sid(ACP_INDEX .'?mod=article_comment&act=edit&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			"TITLE"				=> isset($this->data["title"]) ? stripslashes($this->data["title"]) : $comment_info['title'],
			"CONTENT"			=> isset($this->data["content"]) ? stripslashes($this->data["content"]) : $comment_info['content'],
			"NAME"				=> isset($this->data["name"]) ? stripslashes($this->data["name"]) : $comment_info['name'],
			"EMAIL"				=> isset($this->data["email"]) ? stripslashes($this->data["email"]) : $comment_info['email'],
			"ENABLED"			=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : $comment_info['enabled'],
			"L_PAGE_TITLE"		=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_comment"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			"L_BUTTON"			=> $Lang->data["button_edit"],
		));
		return true;
	}

	function do_edit_comment(){
		global $Session, $Info, $DB, $Template, $Lang, $Func;

		$id		= intval($Func->get_request('id', 0, 'GET'));
		$this->data["title"]	= htmlspecialchars($Func->get_request('title', '', 'POST'));
		$this->data["content"]	= htmlspecialchars($Func->get_request('content', '', 'POST'));
		$this->data["name"]		= htmlspecialchars($Func->get_request('name', '', 'POST'));
		$this->data["email"]	= htmlspecialchars($Func->get_request('email', '', 'POST'));
		$this->data["enabled"]	= intval($Func->get_request('enabled', 0, 'POST'));

		//Check permission ---------------
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$this->data['enabled']	= SYS_DISABLED;
			}
		}
		//--------------------------------

		if ( empty($this->data["title"]) || empty($this->data["content"]) || empty($this->data["name"]) || empty($this->data["email"]) ){
			$this->pre_edit_comment($Lang->data["general_error_not_full"]);
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
		$DB->query('SELECT * FROM '. $DB->prefix .'article_comment WHERE comment_id='. $id . $auth_where_sql);
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data['comment_error_not_exist'], $Session->append_sid(ACP_INDEX .'?mod=article_comment'. $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
//		$comment_info	= $DB->fetch_array();

		//Update comment
		$DB->query("UPDATE ". $DB->prefix ."article_comment SET title='". $this->data["title"]."', content='". $this->data["content"]."', name='". $this->data["name"]."', email='". $this->data["email"]."', enabled='". $this->data["enabled"]."' WHERE comment_id=$id");

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=article_comment&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_comments();
		return true;
	}

	function view_comment(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$id			= isset($_GET['id']) ? intval($_GET['id']) : 0;
		$Info->tpl_main	= "article_comment_view";
		$time_format	= $Info->option['time_format'];
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

		$DB->query('SELECT * FROM '. $DB->prefix .'article_comment WHERE comment_id='. $id . $auth_where_sql);
		if ( !$DB->num_rows() ){
			$Template->message_die($Lang->data['comment_error_not_exist']);
			return false;
		}
		$comment_info	= $DB->fetch_array();

		$Template->set_vars(array(
			'TITLE'			=> $comment_info['title'],
			'CONTENT'		=> $comment_info['content'],
			'NAME'			=> $comment_info['name'],
			'EMAIL'			=> $comment_info['email'],
			'DATE'			=> $comment_info['posted_date'] ? $Func->translate_date(gmdate($time_format, $comment_info['posted_date'] + $timezone)) : '',
			"L_PAGE_TITLE"	=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data['menu_article_comment'] . $Lang->data['general_arrow'] . $Lang->data['general_view'],
			'L_PAGE'		=> $Lang->data['general_page'],
			'L_GO'			=> $Lang->data['button_go'],
			'L_CLOSE'		=> $Lang->data['general_close_window'],
		));
		return true;
	}

	function active_comments($enabled = 0){
		global $DB, $Template, $Func, $Info;

		$comment_ids	= $Func->get_request('comment_ids', '', 'POST');

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

		$ids_info	= $Func->get_array_value($comment_ids);
		if ( sizeof($ids_info) ){
			$log_act	= $enabled ? 'log_enable' : 'log_disable';
			$str_ids	= implode(',', $ids_info);

			//Update comments
			$DB->query("UPDATE ". $DB->prefix ."article_comment SET enabled=$enabled WHERE comment_id IN (". $str_ids .") $auth_where_sql");
			//Save log
			$Func->save_log(FUNC_NAME, $log_act, $str_ids);
		}

		$this->list_comments();
	}

	function delete_comments(){
		global $DB, $Template, $Func, $Info;

		$comment_ids		= $Func->get_request('comment_ids', '', 'POST');

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

		$ids_info	= $Func->get_array_value($comment_ids);
		if ( sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);
			//Delete comments
			$DB->query("DELETE FROM ". $DB->prefix ."article_comment WHERE comment_id IN (". $str_ids .")");
			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $str_ids);
		}

		$this->list_comments();
	}
}
?>
