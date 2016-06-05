<?php
/* =============================================================== *\
|		Module name: Article										|
|		Module version: 1.8											|
|		Begin: 12 August 2003										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_article_page');
define('FUNC_ACT_VIEW', 'view');
//Module language
$Func->import_module_language("admin/lang_article". PHP_EX);
//Article global file
include("modules/admin/ad_article_global". PHP_EX);

class Admin_Article_Page extends Admin_Article_Global
{
	var $filter			= array();
	var $page			= 1;
	var $user_perm		= array();

	function Admin_Article_Page(){
		global $Info, $Func, $Cache;

		$this->user_perm	= $Func->get_all_perms('menu_article_article');

		$this->get_filter();
		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_artpage();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->do_add_artpage();
				$Func->set_update_schedule();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_artpage();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->do_edit_artpage();
				$Func->set_update_schedule();
				break;
			case "enable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->active_artpages(1);
				$Func->set_update_schedule();
				break;
			case "disable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->active_artpages(0);
				$Func->set_update_schedule();
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->delete_artpages();
				$Func->set_update_schedule();
				break;
			case "update":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->update_order();
				break;
			case "resync":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->resync_artpages();
				break;
			case "view":
				$Func->check_user_perm($this->user_perm, 'view');
				$this->view_artpage();
				break;
			default:
				$this->list_artpages();
		}
	}

	function get_filter(){
		global $Template, $DB, $Lang, $Session, $Info, $Func;

		$this->filter['article_id']	= intval($Func->get_request('article_id', 0, 'GET'));
		$this->filter['url_append']	= "&article_id=". $this->filter['article_id'];

		$this->filter['keyword']		= htmlspecialchars($Func->get_request('fkeyword', ''));
		if ( !empty($this->filter['keyword']) ){
			$this->filter['url_append']	.= '&fkeyword='. $this->filter['keyword'];
		}

		$this->filter['status']			= intval($Func->get_request('fstatus', -1));
		if ( $this->filter['status'] != -1 ){
			$this->filter['url_append']	.= '&fstatus='. $this->filter['status'];
		}

		$this->page			= intval($Func->get_request('page', 1, 'GET'));

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['own']) ){
				$auth_where_sql	.= " AND poster_id=". $Info->user_info['user_id'];
			}

			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		//Get article info ----------------------
		$DB->query('SELECT title FROM '. $DB->prefix .'article WHERE article_id='. $this->filter['article_id'] . $auth_where_sql);
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data['article_error_not_exist'], $Session->append_sid(ACP_INDEX .'?mod=article'));
			die();
		}
		$article_info	= $DB->fetch_array();
		//---------------------------------------

		$Template->set_vars(array(
			"FKEYWORD"				=> stripslashes($this->filter['keyword']),
			"FSTATUS"				=> $this->filter['status'],
			'ARTICLE_TITLE'			=> html_entity_decode($article_info['title']),
		));
	}

	function list_artpages(){
		global $Session, $Func, $Info, $DB, $Template, $Lang;

		$Info->tpl_main	= "article_page_list";
		$itemperpage	= $Info->option['items_per_page'];
//		$date_format	= $Info->option['date_format'];
//		$timezone		= $Info->option['timezone'] * 3600;

		//Filter -------------------------
		$where_sql		= " WHERE P.article_id=". $this->filter['article_id'];
		if ( ($this->filter['status'] == SYS_ENABLED) || ($this->filter['status'] == SYS_DISABLED) || ($this->filter['status'] == SYS_APPENDING) ){
			$where_sql	.= " AND P.page_enabled=". $this->filter['status'];
		}
		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*", '%', $this->filter['keyword']);
			if ( SEARCH_TYPE == 1 ){
				//Fulltext search
				$where_sql	= ", ". $DB->prefix ."article_page_content AS PC ". $where_sql ." AND P.page_id=PC.page_id  AND ((MATCH (P.page_title) AGAINST ('". $key ."')) OR (MATCH (PC.content_detail, PC.author) AGAINST ('". $key ."')) )";
			}
			else{
				//Normal search
				$where_sql	= ", ". $DB->prefix ."article_page_content AS PC ". $where_sql  ." AND P.page_id=PC.page_id AND ( P.page_title LIKE '%". $key ."%' OR PC.content_detail LIKE '%". $key ."%' OR PC.author LIKE '%". $key ."%')";
			}
		}
		//------------------------------------

		//Generate pages
		$DB->query("SELECT count(P.page_id) AS total FROM ". $DB->prefix ."article_page AS P $where_sql");
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=article_page" . $this->filter['url_append']));
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query("SELECT P.page_id, P.page_title, P.page_order, P.page_enabled FROM ". $DB->prefix ."article_page AS P $where_sql ORDER BY P.page_order ASC LIMIT ". $pageshow['start'] .",$itemperpage");
		$artpage_count		= $DB->num_rows();
		$artpage_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$artpage_count; $i++){
			$Template->set_block_vars("artpagerow",array(
				"ID"			=> $artpage_data[$i]["page_id"],
				"ORDER"			=> $artpage_data[$i]["page_order"],
				"CSS"			=> ($artpage_data[$i]["page_enabled"] == SYS_ENABLED) ? "enabled" : "disabled",
				"TITLE"			=> html_entity_decode($artpage_data[$i]["page_title"]),
				'U_VIEW'		=> $Session->append_sid(ACP_INDEX .'?mod=article_page&act=view&article_id='. $this->filter['article_id'] .'&id='. $artpage_data[$i]["page_id"]),
				'U_EDIT'		=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_page&act=preedit&id='. $artpage_data[$i]["page_id"] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
			));
		}
		$DB->free_result();

		$Template->set_vars(array(
			"PAGE_OUT"			=> $pageshow['page'],
			'S_FILTER_ACTION'	=> $Session->append_sid(ACP_INDEX .'?mod=article_page&article_id='. $this->filter['article_id']),
			'S_LIST_ACTION'		=> $Session->append_sid(ACP_INDEX .'?mod=article_page&act=update'. $this->filter['url_append']),
			'U_ADD'				=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_page&act=preadd&article_id='. $this->filter['article_id']) .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="{'. $Lang->data['general_add'] .'" align="absbottom" border=0>'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp; ' : '',
			'U_ENABLE'			=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article_page&act=enable'. $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/enable.gif" alt="" title="'. $Lang->data['general_enable'] .'" align="absbottom" border=0>'. $Lang->data['general_enable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DISABLE'			=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article_page&act=disable'. $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/disable.gif" alt="" title="'. $Lang->data['general_disable'] .'" align="absbottom" border=0>'. $Lang->data['general_disable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'			=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript:deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article_page&act=del'. $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" align="absbottom" border=0>'. $Lang->data['general_del'] .'</a> &nbsp; &nbsp;' : '',
			'U_UPDATE'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="javascript: updateForm2(\''. $Session->append_sid(ACP_INDEX .'?mod=article_page&act=update' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/update.gif" alt="" title="'. $Lang->data['general_update'] .'" border="0" align="absbottom">'. $Lang->data['general_update'] .'</a> &nbsp; &nbsp;' : '',
			'U_RESYNC'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_page&act=resync' . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/resync.gif" alt="" title="'. $Lang->data['general_resync'] .'" border="0" align="absbottom">'. $Lang->data['general_resync'] .'</a>' : '',
			"L_PAGE_TITLE"		=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_page"],
//			"L_ID"				=> $Lang->data["page_id"],
			"L_TITLE"			=> $Lang->data["article_title"],
			"L_ARTICLE"			=> $Lang->data["article"],
			"L_PAGE_ORDER"		=> $Lang->data["article_page_order"],
			"L_DATE"			=> $Lang->data["general_date"],
			"L_SEARCH"			=> $Lang->data["button_search"],
			'L_DEL_CONFIRM'		=> $Lang->data['article_page_del_confirm'],
			'L_CHOOSE_ITEM'		=> $Lang->data['article_error_page_not_check'],
		));
	}

	function pre_add_artpage($msg = ""){
		global $Session, $Info, $DB, $Template, $Lang;

		$Info->tpl_main	= "article_page_edit";
		$this->set_lang();

		//Get max page order
		$DB->query('SELECT max(page_order) AS max_order FROM '. $DB->prefix .'article_page WHERE article_id='. $this->filter['article_id']);
		if ( $DB->num_rows() ){
			$tmp_info	= $DB->fetch_array();
			$max_order	= $tmp_info['max_order'] + 1;
		}
		else{
			$max_order	= 1;
		}

		$Template->set_block_vars("addrow",array());
		$Template->set_vars(array(
			"ERROR_MSG"				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=article_page&act=add'. $this->filter['url_append']),
			"PAGE_ORDER"			=> isset($this->data["page_order"]) ? $this->data["page_order"] : $max_order,
			"TITLE"					=> isset($this->data["title"]) ? stripslashes($this->data["title"]) : '',
			"CONTENT_DETAIL"		=> isset($this->data["content_detail"]) ? stripslashes($this->data["content_detail"]) : '',
			"USED_FILES"			=> isset($this->data["used_files"]) ? stripslashes($this->data["used_files"]) : '',
			"AUTHOR"				=> isset($this->data["author"]) ? stripslashes($this->data["author"]) : '',
			"ENABLED"				=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : '',
			"PAGE_TO"				=> isset($this->data["page_to"]) ? $this->data["page_to"] : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_page"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			"L_BUTTON"				=> $Lang->data["button_add"],
		));
		//WYSIWYG editor for title
		if ( $Info->option['enable_article_wysiwyg_title'] ){
			$Template->set_block_vars("wysiwyg_title", array());
		}
	}

	function set_lang(){
		global $Session, $Template, $Lang, $Info;

		$Template->set_vars(array(
			'S_IMAGE_UPLOAD'			=> $Session->append_sid('../../'. ACP_INDEX .'?mod=upload&code=image'),
			"L_CHOOSE"					=> $Lang->data["general_choose"],
			"L_PAGE_ORDER"				=> $Lang->data["article_page_order"],
			"L_TITLE"					=> $Lang->data["article_title"],
			"L_CONTENT_DETAIL"			=> $Lang->data["article_content_detail"],
			"L_AUTHOR"					=> $Lang->data["article_author"],
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

	function do_add_artpage(){
		global $Session, $Info, $DB, $Template, $Lang, $Func, $File;

		$this->data["page_order"]		= intval($Func->get_request('page_order', 0, 'POST'));
		$this->data["title"]			= htmlspecialchars($Func->get_request('title', '', 'POST'));
		$this->data["content_detail"]	= htmlspecialchars($Func->get_request('content_detail', '', 'POST'));
		$this->data["used_files"]		= $Func->get_request('used_files', '', 'POST');
		$this->data["author"]			= htmlspecialchars($Func->get_request('author', '', 'POST'));
		$this->data["enabled"]			= intval($Func->get_request('enabled', 0, 'POST'));
		$this->data["page_to"]			= htmlspecialchars($Func->get_request('page_to', '', 'POST'));

		if ( !$this->data["page_order"] || empty($this->data["title"]) || empty($this->data["content_detail"]) ){
			$this->pre_add_artpage($Lang->data["general_error_not_full"]);
			return false;
		}

		//Check page order
		$DB->query('SELECT page_id FROM '. $DB->prefix .'article_page WHERE article_id='. $this->filter['article_id'] .' AND page_order='. $this->data["page_order"]);
		if ( $DB->num_rows() ){
			$this->pre_add_artpage($Lang->data["article_error_page_order_exist"]);
			return false;
		}

		if ( !empty($this->data['used_files']) ){
			//Make image dir for this article
			$this->make_image_dir($this->data["ptime"], $this->filter['article_id']);

			//Transfer used_files and remove temp files
//			$data_info['desc']		= $this->data['content_desc'];
			$data_info['detail']	= $this->data['content_detail'];
			$File->transfer_temp_files($this->data["used_files"], $this->sysdir['id'], $data_info);
//			$this->data['content_desc']		= $data_info['desc'];
			$this->data['content_detail']	= $data_info['detail'];
			//-----------------------------------------
		}

		//Insert artpage
		$sql	= "INSERT INTO ". $DB->prefix ."article_page(article_id, page_title, used_files, page_order, page_enabled)
					VALUES(". $this->filter["article_id"] .", '". $this->data["title"]."', '". $this->data['used_files'] ."', ". $this->data['page_order'] .", ". $this->data['enabled'] .")";
		$DB->query($sql);
		$page_id	= $DB->insert_id();

		//Insert artpage content
		$sql	= "INSERT INTO ". $DB->prefix ."article_page_content(page_id, article_id, content_detail, author)
					VALUES($page_id, ". $this->filter["article_id"] .", '". $this->data["content_detail"] ."', '". $this->data["author"] ."')";
		$DB->query($sql);

		//Update page counter
		$DB->query("UPDATE ". $DB->prefix ."article SET page_counter=page_counter+1 WHERE article_id=". $this->filter['article_id']);

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $page_id, ACP_INDEX .'?mod=article_page&act='. FUNC_ACT_VIEW .'&article_id='. $this->filter['article_id'] .'&id='. $page_id);

		if ( $this->data['page_to'] == 'pageadd' ){
			$tmp_data['page_to']	= $this->data['page_to'];
////			$this->data	= array();//Reset data
			$this->data	= $tmp_data;
			$this->pre_add_artpage($Lang->data['general_success_add']);
		}
		else{
			$this->list_artpages();
		}

		return true;
	}

	function pre_edit_artpage($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main	= "article_page_edit";
		$this->set_lang();

		$DB->query("SELECT P.*, PC.content_detail, author FROM ". $DB->prefix ."article_page AS P, ". $DB->prefix ."article_page_content AS PC WHERE P.article_id=". $this->filter['article_id'] ." AND P.page_id=$id AND PC.page_id=$id");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["article_page_error_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=article_page". $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
		$artpage_info	= $DB->fetch_array();
		$artpage_info['topic_title']	= "";
		$DB->free_result();

		$Template->set_block_vars("editrow", array());
		$Template->set_vars(array(
			"ERROR_MSG"				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=article_page&act=edit&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			"PAGE_ORDER"			=> isset($this->data["page_order"]) ? stripslashes($this->data["page_order"]) : $artpage_info['page_order'],
			"TITLE"					=> isset($this->data["title"]) ? stripslashes($this->data["title"]) : $artpage_info['page_title'],
			"CONTENT_DETAIL"		=> isset($this->data["content_detail"]) ? stripslashes($this->data["content_detail"]) : $artpage_info['content_detail'],
			'USED_FILES'			=> isset($this->data["used_files"]) ? stripslashes($this->data["used_files"]) : '',
			"AUTHOR"				=> isset($this->data["author"]) ? stripslashes($this->data["author"]) : $artpage_info['author'],
			"ENABLED"				=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : $artpage_info['page_enabled'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_page"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			"L_BUTTON"				=> $Lang->data["button_edit"],
		));
		//WYSIWYG editor for title
		if ( $Info->option['enable_article_wysiwyg_title'] ){
			$Template->set_block_vars("wysiwyg_title", array());
		}
		return true;
	}

	function do_edit_artpage(){
		global $Session, $Info, $DB, $Template, $Lang, $Func, $File;

		$id				= isset($_GET['id']) ? intval($_GET['id']) : 0;

		$this->data["page_order"]		= intval($Func->get_request('page_order', 0, 'POST'));
		$this->data["title"]			= htmlspecialchars($Func->get_request('title', '', 'POST'));
		$this->data["content_detail"]	= htmlspecialchars($Func->get_request('content_detail', '', 'POST'));
		$this->data["used_files"]		= $Func->get_request('used_files', '', 'POST');
		$this->data["author"]			= htmlspecialchars($Func->get_request('author', '', 'POST'));
		$this->data["enabled"]			= intval($Func->get_request('enabled', 0, 'POST'));
		$this->data["page_to"]			= htmlspecialchars($Func->get_request('page_to', '', 'POST'));

		if ( empty($this->data["title"]) || empty($this->data["content_detail"]) ){
			$this->pre_edit_artpage($Lang->data["general_error_not_full"]);
			return false;
		}

		//Check page order
		$DB->query('SELECT page_id FROM '. $DB->prefix .'article_page WHERE article_id='. $this->filter['article_id'] .' AND page_order='. $this->data["page_order"] .' AND page_id!='. $id);
		if ( $DB->num_rows() ){
			$this->pre_edit_artpage($Lang->data["article_error_page_order_exist"]);
			return false;
		}

		//Get old info
		$DB->query('SELECT A.posted_date, P.* FROM '. $DB->prefix .'article AS A, '. $DB->prefix .'article_page AS P WHERE A.article_id='. $this->filter['article_id'] .' AND P.article_id='. $this->filter['article_id'] .' AND P.page_id='. $id);
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data['artpage_error_page_not_exist'], $Session->append_sid(ACP_INDEX .'?mod=article_page'. $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
		$artpage_info	= $DB->fetch_array();
		$this->make_image_dir($artpage_info['posted_date'], $this->filter['article_id']);

		//Get artpage content
		$DB->query('SELECT content_detail, author FROM '. $DB->prefix .'article_page_content WHERE page_id='. $id);
		if ( $DB->num_rows() ){
			$tmp_info	= $DB->fetch_array();
			$artpage_info['content_detail']	= $tmp_info['content_detail'];
			$artpage_info['author']			= $tmp_info['author'];
		}
		else{
			$artpage_info['content_detail']	= "";
			$artpage_info['author']			= "";
		}

		//Transfer used_files and remove temp files
//		$data_info['desc']		= $this->data['content_desc'];
		$data_info['detail']	= $this->data['content_detail'];
		$File->transfer_temp_files($this->data["used_files"], $this->sysdir['id'], $data_info);
//		$this->data['content_desc']		= $data_info['desc'];
		$this->data['content_detail']	= $data_info['detail'];
		//-----------------------------------------

		//Clean old used files ----------
		$data_info['detail']	= $this->data['content_detail'];
		$File->clean_used_files($artpage_info["used_files"], $this->sysdir['id'], $data_info, $this->data['used_files']);
		$this->data['content_detail']	= $data_info['detail'];
		//-------------------------------

		//Delete files which are not used
		$File->delete_unused_files($this->data['used_files'], $artpage_info['used_files'], $this->sysdir['id']);

		//Update artpage
		$DB->query("UPDATE ". $DB->prefix ."article_page SET page_title='". $this->data["title"]."', used_files='". $this->data["used_files"] ."', page_order=". $this->data['page_order'] .", page_enabled=". $this->data['enabled']." WHERE page_id=$id");
		$DB->query("UPDATE ". $DB->prefix ."article_page_content SET content_detail='". $this->data["content_detail"]."', author='". $this->data["author"]."' WHERE page_id=$id");

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=article_page&act='. FUNC_ACT_VIEW .'article_id='. $this->filter['article_id'] .'&id='. $id);

		$this->list_artpages();
		return true;
	}

	function view_artpage(){
		global $DB, $Template, $Lang, $Info, $Func;

		$id					= isset($_GET['id']) ? intval($_GET['id']) : 0;

		$Info->tpl_main		= "article_page_view";
//		$time_format		= $Info->option['time_format'];
//		$timezone			= $Info->option['timezone'] * 3600;

		$DB->query('SELECT P.*, PC.content_detail, PC.author FROM '. $DB->prefix .'article_page AS P, '. $DB->prefix .'article_page_content AS PC WHERE P.article_id='. $this->filter['article_id'] .' AND P.page_id='. $id .' AND PC.page_id='. $id);
		if ( !$DB->num_rows() ){
			$Template->message_die($Lang->data['article_error_page_not_exist']);
			return false;
		}
		$artpage_info	= $DB->fetch_array();

		$Template->set_vars(array(
			'TITLE'			=> html_entity_decode($artpage_info['page_title']),
			'AUTHOR'		=> $artpage_info['author'],
			'CONTENT'		=> html_entity_decode($artpage_info['content_detail']),
			"L_PAGE_TITLE"	=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data['menu_article_article'] . $Lang->data['general_arrow'] . $Lang->data['menu_article_page'] . $Lang->data['general_arrow'] . $Lang->data['general_view'],
			'L_CLOSE'		=> $Lang->data['general_close_window'],
		));
		return true;
	}

	function active_artpages($enabled = 0){
		global $DB, $Template, $Func, $Info;

		$page_ids	= $Func->get_request('ids', '', 'POST');
		$ids_info	= $Func->get_array_value($page_ids);

		if ( sizeof($ids_info) ){
			$log_act	= $enabled ? 'log_enable' : 'log_disable';
			$str_ids	= implode(',', $ids_info);

			//Update pages
			$DB->query("UPDATE ". $DB->prefix ."article_page SET page_enabled=$enabled WHERE article_id=". $this->filter['article_id'] ." AND page_id IN (". $str_ids .")");
			//Save log
			$Func->save_log(FUNC_NAME, $log_act, $str_ids);
		}

		$this->list_artpages();
	}

	function delete_artpages(){
		global $DB, $Template, $Func, $Info, $File;

		$page_ids	= $Func->get_request('ids', '', 'POST');
		$ids_info	= $Func->get_array_value($page_ids);

		if ( sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);
			$where_sql	= " WHERE article_id=". $this->filter['article_id'] ." AND page_id IN (". $str_ids .")";

			//Get and delete images -------------
			$DB->query("SELECT used_files FROM ". $DB->prefix ."article_page $where_sql");
			$artpage_count	= $DB->num_rows();
			$artpage_data	= $DB->fetch_all_array();
			$DB->free_result();

			for ($i=0; $i<$artpage_count; $i++){
				if ( !empty($artpage_info['used_files']) ){
					$this->get_image_dir($artpage_data[$i]['posted_date'], $artpage_data[$i]['page_id']);
					$File->delete_unused_files("", $artpage_info['used_files'], $this->sysdir['id']);
				}
			}
			//------------------------------------

			//Delete artpages
			$DB->query("DELETE FROM ". $DB->prefix ."article_page_content $where_sql");
			$DB->query("DELETE FROM ". $DB->prefix ."article_page $where_sql");

			//Update page counter
			$DB->query("UPDATE ". $DB->prefix ."article SET page_counter=page_counter-$artpage_count WHERE article_id=". $this->filter['article_id']);

			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $str_ids);
		}

		$this->list_artpages();
	}

	function update_order(){
		global $Session, $Template, $Lang, $DB, $Func;

		$page_orders	= $Func->get_request('page_orders', '', 'POST');

		if ( is_array($page_orders) ){
			reset($page_orders);
			while ( list($id, $num) = each($page_orders) ){
				$DB->query("UPDATE ". $DB->prefix ."article_page SET page_order=". intval($num) ." WHERE page_id=". intval($id));
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_update');

		$this->list_artpages();
	}

	function resync_artpages(){
		global $DB, $Template, $Lang, $Func;

		//Resync orders -----------
		$DB->query('SELECT page_id FROM '. $DB->prefix .'article_page WHERE article_id='. $this->filter['article_id'] .' ORDER BY page_order ASC');
		$page_count		= $DB->num_rows();
		$page_data		= $DB->fetch_all_array();
		$DB->free_result();

		$count	= 1;
		for ($i=0; $i<$page_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'article_page SET page_order='. $count .' WHERE page_id='. $page_data[$i]['page_id']);
			$count++;
		}
		//-------------------------

		//Resync page counter -----
		$DB->query('UPDATE '. $DB->prefix .'article SET page_counter='. $page_count .' WHERE article_id='. $this->filter['article_id']);
		//-------------------------

		//Save log
		$Func->save_log(FUNC_NAME, 'log_resync');

		$this->list_artpages();
	}
}

$AdArticlePage	= new Admin_Article_Page;
?>
