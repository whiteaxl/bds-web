<?php
/* =============================================================== *\
|		Module name: Article										|
|		Module version: 1.9											|
|		Begin: 12 August 2003										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_article_article');
define('FUNC_ACT_VIEW', 'view');

//Module language
$Func->import_module_language("admin/lang_article". PHP_EX);

//Article global file
include("modules/admin/ad_article_global". PHP_EX);

class Admin_Article extends Admin_Article_Global
{
	var $filter			= array();
	var $page			= 1;

	var $user_perm		= array();

	function Admin_Article(){
		global $Info, $Func, $Cache;

		$this->user_perm	= $Func->get_all_perms('menu_article_article');
		$this->get_filter();
		$this->get_all_cats();
		$this->set_all_cats(0, 0);

		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_article();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->do_add_article();
				$Func->set_update_schedule();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_article();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->do_edit_article();
				$Func->set_update_schedule();
				break;
			case "enable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->active_articles(1);
				$Func->set_update_schedule();
				break;
			case "disable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->active_articles(0);
				$Func->set_update_schedule();
				break;
			case "archive":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->archive_articles(1);
				$Func->set_update_schedule();
				break;
			case "unarchive":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->archive_articles(0);
				$Func->set_update_schedule();
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$Cache->clear_cache('all', 'html');
				$Cache->clear_cache('schedule', 'php');
				$this->delete_articles();
				$Func->set_update_schedule();
				break;
			case "resync":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->resync_articles();
				break;
			case "view":
				$Func->check_user_perm($this->user_perm, 'view');
				$this->view_article();
				break;
			case "premovecat":
				$Func->check_user_perm($this->user_perm, 'move_article');
				$this->pre_move_cat();
				break;
			case "movecat":
				$Func->check_user_perm($this->user_perm, 'move_article');
				$Cache->clear_cache('all', 'html');
				$this->do_move_cat();
				break;
			case "premovetopic":
				$Func->check_user_perm($this->user_perm, 'move_article');
				$this->pre_move_topic();
				break;
			case "movetopic":
				$Func->check_user_perm($this->user_perm, 'move_article');
				$Cache->clear_cache('all', 'html');
				$this->do_move_topic();
				break;
			default:
				$this->list_articles();
		}
	}

	function get_filter(){
		global $Template, $DB, $Func, $Info;

		$this->filter['url_append']	= "";

		$this->filter['keyword']		= htmlspecialchars($Func->get_request('fkeyword', ''));
		if ( !empty($this->filter['keyword']) ){
			$this->filter['url_append']	.= '&fkeyword='. $this->filter['keyword'];
		}

		$this->filter['status']			= intval($Func->get_request('fstatus', -1));
		if ( $this->filter['status'] != -1 ){
			$this->filter['url_append']	.= '&fstatus='. $this->filter['status'];
		}

		$this->filter['archived']		= intval($Func->get_request('farchived', $Info->option['archived_default']));
		if ( $this->filter['archived'] != -1 ){
			$this->filter['url_append']	.= '&farchived='. $this->filter['archived'];
		}

		$this->filter['cat_id']			= intval($Func->get_request('fcat_id', 0));
		if ( $this->filter['cat_id'] ){
			$this->filter['url_append']	.= '&fcat_id='. $this->filter['cat_id'];
		}

		$this->filter['user_id']		= intval($Func->get_request('fuser_id', 0));
		if ( $this->filter['user_id'] ){
			$this->filter['url_append']	.= '&fuser_id='. $this->filter['user_id'];
			//Get username
			$DB->query('SELECT username FROM '. $DB->prefix .'user WHERE user_id='. $this->filter['user_id']);
			if ( $DB->num_rows() ){
				$user_info	= $DB->fetch_array();
			}
		}

		$this->filter['topic_id']		= intval($Func->get_request('ftopic_id', 0));
		if ( $this->filter['topic_id'] ){
			$this->filter['url_append']	.= '&ftopic_id='. $this->filter['topic_id'];
			//Get topic
			$DB->query('SELECT topic_title FROM '. $DB->prefix .'article_topic WHERE topic_id='. $this->filter['topic_id']);
			if ( $DB->num_rows() ){
				$topic_info	= $DB->fetch_array();
			}
		}

		$this->page			= isset($_GET["page"]) ? intval($_GET["page"]) : 1;

		$Template->set_vars(array(
			"FKEYWORD"	=> stripslashes($this->filter['keyword']),
			"FSTATUS"	=> $this->filter['status'],
			"FARCHIVED"	=> $this->filter['archived'],
			"FCAT_ID"	=> $this->filter['cat_id'],
			"FUSERNAME"	=> isset($user_info['username']) ? '[<strong>'. $user_info['username'] .'</strong>]' : '',
			"FTOPIC"	=> isset($topic_info['topic_title']) ? '[<strong>'. $topic_info['topic_title'] .'</strong>]' : '',
		));
	}

	function list_articles(){
		global $Session, $Func, $Info, $DB, $Template, $Lang;

		$Info->tpl_main	= "article_list";
		$itemperpage	= $Info->option['items_per_page'];
		$date_format	= $Info->option['date_format'];
		$timezone		= $Info->option['timezone'] * 3600;

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['own']) ){
				$auth_where_sql	.= " AND A.poster_id=". $Info->user_info['user_id'];
			}

			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND A.enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND A.enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		//Filter -------------------------
		$where_sql		= " WHERE A.article_id>0";
		if ( ($this->filter['status'] == SYS_ENABLED) || ($this->filter['status'] == SYS_DISABLED) || ($this->filter['status'] == SYS_APPENDING) ){
			$where_sql	.= " AND A.enabled=". $this->filter['status'];
		}
		else if ( $this->filter['status'] == SYS_WAITING ){
			$where_sql	.= " AND A.posted_date>". CURRENT_TIME;
		}
		else if ( $this->filter['status'] == SYS_SHOWING ){
			$where_sql	.= " AND A.posted_date<=". CURRENT_TIME;
		}

		if ( $this->filter['archived'] != -1 ){
			$where_sql	.= " AND A.archived=". $this->filter['archived'];
		}

		if ( $this->filter['cat_id'] ){
			$where_sql	.= " AND A.cat_id=". $this->filter['cat_id'];
		}
		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*", '%', $this->filter['keyword']);
			if ( SEARCH_TYPE == 1 ){
				//Fulltext search
				$where_sql	= ", ". $DB->prefix."article_page_content AS P ". $where_sql ." AND A.article_id=P.article_id AND ((MATCH (A.title, A.content_desc) AGAINST ('". $key ."')) OR (MATCH (P.content_detail, P.author) AGAINST ('". $key ."')) )";
			}
			else{
				//Normal search
				$where_sql	= ", ". $DB->prefix."article_page_content AS P ". $where_sql ." AND A.article_id=P.article_id AND (A.title LIKE '%". $key ."%' OR A.content_desc LIKE '%". $key ."%' OR P.author LIKE '%". $key ."%' OR P.content_detail LIKE '%". $key ."%')";
			}
			$group_sql	= 'GROUP BY A.article_id';
		}
		else{
			$group_sql	= '';
		}

		if ( $this->filter['user_id'] ){
			$where_sql	.= " AND A.poster_id=". $this->filter['user_id'];
		}

		if ( $this->filter['topic_id'] ){
			$where_sql	.= " AND A.topic_id=". $this->filter['topic_id'];
		}
		//--------------------------------

		//Generate pages
		$DB->query("SELECT count(distinct A.article_id) AS total FROM ". $DB->prefix ."article AS A $where_sql $auth_where_sql");
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=article" . $this->filter['url_append']));
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query("SELECT A.article_id, A.cat_id, A.title, A.is_hot, A.posted_date, A.article_type, A.page_counter, A.archived, A.enabled FROM ". $DB->prefix ."article AS A $where_sql $auth_where_sql ". $group_sql ." ORDER BY A.posted_date DESC, A.article_id DESC LIMIT ". $pageshow['start'] .",$itemperpage");
		$article_count		= $DB->num_rows();
		$article_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$article_count; $i++){
			if ($article_data[$i]['archived'] == 1 ){
				$status	= '<span class="archived">'. $Lang->data['general_archived'] .'</span>';
			}
			else{
				if ($article_data[$i]['enabled'] == SYS_APPENDING){
					$status	= $Lang->data['general_appending'];
				}
				else if ($article_data[$i]['enabled'] == SYS_DISABLED ){
					$status	= $Lang->data['general_disabled'];
				}
				else if ($article_data[$i]['posted_date'] > CURRENT_TIME){
					$status	= $Lang->data['general_waiting'];
				}
				else{
					$status	= $Lang->data['general_showing'];
				}
			}
			$Template->set_block_vars("articlerow",array(
				"ID"			=> $article_data[$i]["article_id"],
				"CAT_ID"		=> $article_data[$i]["cat_id"],
				"CSS"			=> ($article_data[$i]["enabled"] == SYS_ENABLED) ? "enabled" : "disabled",
				'BG_CSS'		=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				"TITLE"			=> html_entity_decode($article_data[$i]["title"]),
				"PAGES"			=> ($article_data[$i]["article_type"] != SYS_ARTICLE_FULL) ? "" : $article_data[$i]["page_counter"],
				'STATUS'		=> $status,
				"DATE"			=> $Func->translate_date(gmdate($date_format, $article_data[$i]["posted_date"] + $timezone)),
				'ICON_HOT'		=> ($article_data[$i]["is_hot"] == SYS_ARTICLE_HOT) ? '<img src="'. $Info->option['template_path'] .'/images/admin/icon_hot.jpg" border="0">' : '',
				'U_COMMENT'		=> $Session->append_sid(ACP_INDEX .'?mod=article_comment&farticle_id='. $article_data[$i]["article_id"]),
				'U_PAGE'		=> $Session->append_sid(ACP_INDEX .'?mod=article_page&article_id='. $article_data[$i]["article_id"]),
				'U_VIEW'		=> $Session->append_sid(ACP_INDEX .'?mod=article&act=view&id='. $article_data[$i]["article_id"]),
				'U_EDIT'		=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article&act=preedit&id='. $article_data[$i]["article_id"] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
			));
		}

		$Template->set_vars(array(
			"PAGE_OUT"			=> $pageshow['page'],
			'S_FILTER_ACTION'	=> $Session->append_sid(ACP_INDEX .'?mod=article'),
			'S_LIST_ACTION'		=> $Session->append_sid(ACP_INDEX .'?mod=article&act=update'. $this->filter['url_append']),
			'U_ADD'				=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="{'. $Lang->data['general_add'] .'" align="absbottom" border=0>'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp; ' : '',
			'U_ENABLE'			=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article&act=enable' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/enable.gif" alt="" title="'. $Lang->data['general_enable'] .'" align="absbottom" border=0>'. $Lang->data['general_enable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DISABLE'			=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article&act=disable' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/disable.gif" alt="" title="'. $Lang->data['general_disable'] .'" align="absbottom" border=0>'. $Lang->data['general_disable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'			=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript:deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article&act=del' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" align="absbottom" border=0>'. $Lang->data['general_del'] .'</a> &nbsp; &nbsp;' : '',
			'U_ARCHIVE'			=> $Func->check_user_perm($this->user_perm, 'archive', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article&act=archive' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/import.gif" alt="" title="'. $Lang->data['general_archive'] .'" align="absbottom" border=0>'. $Lang->data['general_archive'] .'</a> &nbsp; &nbsp;' : '',
			'U_UNARCHIVE'		=> $Func->check_user_perm($this->user_perm, 'unarchive', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article&act=unarchive' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/export.gif" alt="" title="'. $Lang->data['general_unarchive'] .'" align="absbottom" border=0>'. $Lang->data['general_unarchive'] .'</a> &nbsp; &nbsp;' : '',
			'U_MOVE_CAT'		=> $Func->check_user_perm($this->user_perm, 'move_article', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article&act=premovecat' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/move.gif" alt="" title="'. $Lang->data["article_move_cat"] .'" align="absbottom" border=0>'. $Lang->data["article_move_cat"] .'</a> &nbsp; &nbsp; ' : '',
			'U_MOVE_TOPIC'		=> $Func->check_user_perm($this->user_perm, 'move_article', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article&act=premovetopic' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/move.gif" alt="" title="'. $Lang->data['article_move_topic'] .'" align="absbottom" border=0>'. $Lang->data['article_move_topic'] .'</a> &nbsp; &nbsp; ' : '',
			'U_RESYNC'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article&act=resync' . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/resync.gif" alt="" title="'. $Lang->data['general_resync'] .'" border="0" align="absbottom">'. $Lang->data['general_resync'] .'</a> &nbsp; &nbsp; ' : '',
			"L_PAGE_TITLE"		=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_article"],
			"L_ID"				=> $Lang->data["general_id"],
			"L_CATS"			=> $Lang->data["general_cat"],
			"L_TITLE"			=> $Lang->data["article_title"],
			"L_AUTHOR"			=> $Lang->data["article_author"],
			"L_PAGES"			=> $Lang->data["general_page_counter"],
			'L_PAGES_DESC'		=> $Lang->data['general_page_desc'],
			'L_COMMENT_DESC'	=> $Lang->data['general_comment_desc'],
			'L_VIEW_DESC'		=> $Lang->data['general_view_desc'],
			"L_DATE"			=> $Lang->data["general_date"],
			"L_SEARCH"			=> $Lang->data["button_search"],
			"L_MOVE_CAT"		=> $Lang->data["article_move_cat"],
			"L_MOVE_TOPIC"		=> $Lang->data["article_move_topic"],
			'L_DEL_CONFIRM'		=> $Lang->data['article_del_confirm'],
			'L_CHOOSE_ITEM'		=> $Lang->data['article_error_not_check'],
		));
	}

	function pre_add_article($msg = ""){
		global $Session, $Info, $DB, $Template, $Lang;

		$Info->tpl_main	= "article_edit";

		$this->get_all_topics();
		$this->set_lang();

/*
		$today = getdate();
		$month = $today['mon'];
		$day   = $today['mday'];
		$year  = $today['year'];
		$time  = $today['hours'] .":". $today['minutes'];
*/
		$date	= explode('-', gmdate('m-d-Y-H-i', CURRENT_TIME + $Info->option['timezone']*3600));
		$month	= intval($date[0]); //Remove zero from first letter
		$day	= intval($date[1]);
		$year	= intval($date[2]);
		$time	= $date[3] .":". $date[4];

		$Template->set_block_vars("addrow",array());
		$Template->set_vars(array(
			"ERROR_MSG"				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=article&act=add'. $this->filter['url_append']),
			"IS_HOT"				=> isset($this->data["is_hot"]) ? $this->data["is_hot"] : '',
			"ARTICLE_TYPE"			=> isset($this->data["article_type"]) ? $this->data["article_type"] : '',
			"CAT_ID"				=> isset($this->data["cat_id"]) ? $this->data["cat_id"] : '',
			"TOPIC_ID"				=> isset($this->data["topic_id"]) ? $this->data["topic_id"] : '',
			"TOPIC_ID_SEARCH"		=> (isset($this->data["topic_id_search"]) && $this->data["topic_id_search"]) ? stripslashes($this->data["topic_id_search"]) : '',
			"META_KEYWORDS"			=> isset($this->data["meta_keywords"]) ? stripslashes($this->data["meta_keywords"]) : '',
			"META_DESC"				=> isset($this->data["meta_desc"]) ? stripslashes($this->data["meta_desc"]) : '',
			"TITLE"					=> isset($this->data["title"]) ? stripslashes($this->data["title"]) : '',
			"CONTENT_DESC"			=> isset($this->data["content_desc"]) ? stripslashes($this->data["content_desc"]) : '',
			"CONTENT_DETAIL"		=> isset($this->data["content_detail"]) ? stripslashes($this->data["content_detail"]) : '',
			"CONTENT_URL"			=> isset($this->data["content_url"]) ? stripslashes($this->data["content_url"]) : '',
			"AUTHOR"				=> isset($this->data["author"]) ? stripslashes($this->data["author"]) : '',
			"MONTH"					=> isset($this->data["month"]) ? $this->data["month"] : $month,
			"DAY"					=> isset($this->data["day"]) ? $this->data["day"] : $day,
			"YEAR"					=> isset($this->data["year"]) ? $this->data["year"] : $year,
			"TIME"					=> isset($this->data["time"]) ? $this->data["time"] : $time,
			"USED_FILES"			=> isset($this->data["used_files"]) ? stripslashes($this->data["used_files"]) : '',
			"ENABLED"				=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : '',
			"PAGE_TO"				=> isset($this->data["page_to"]) ? $this->data["page_to"] : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_article"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			"L_BUTTON"				=> $Lang->data["button_add"],
		));
		//WYSIWYG editor for title
		if ( $Info->option['enable_article_wysiwyg_title'] ){
			$Template->set_block_vars("wysiwyg_title", array());
		}
	}

	function set_lang(){
		global $Session, $Template, $Lang, $Info;

		if ( $Info->option['thumb_large_max_width'] && $Info->option['thumb_large_max_height'] ){
			$thumb_size		= '<br>'. sprintf($Lang->data['general_pic_max_size'], $Info->option['thumb_large_max_width'], $Info->option['thumb_large_max_height']);
		}
		else if ( $Info->option['thumb_large_max_width'] ){
			$thumb_size		= '<br>'. sprintf($Lang->data['general_pic_max_width'], $Info->option['thumb_large_max_width']);
		}
		else if ( $Info->option['thumb_large_max_height'] ){
			$thumb_size		= '<br>'. sprintf($Lang->data['general_pic_max_height'], $Info->option['thumb_large_max_height']);
		}
		else{
			$thumb_size		=	 "";
		}

		$Template->set_vars(array(
			'S_IMAGE_UPLOAD'			=> $Session->append_sid('../../'. ACP_INDEX .'?mod=upload&code=image'),
			"L_PIC_THUMB"				=> $Lang->data["general_pic"],
			"L_REMOVE"					=> $Lang->data["general_pic_remove"],
			"L_THUMB_SIZE"				=> $thumb_size,
			"L_NORMAL"					=> $Lang->data["article_normal"],
			"L_HOT"						=> $Lang->data["article_hot"],
			"L_CAT"						=> $Lang->data["general_cat"],
			"L_TOPIC"					=> $Lang->data["article_topic"],
			"L_TOPIC_SEARCH"			=> $Lang->data["article_topic_search"],
			"L_TOPIC_TIP"				=> $Lang->data["article_topic_tip"],
			"L_CHOOSE"					=> $Lang->data["general_choose"],
			'L_META_KEYWORDS'			=> $Lang->data['general_meta_keywords'],
			'L_META_DESC'				=> $Lang->data['general_meta_desc'],
			"L_TITLE"					=> $Lang->data["article_title"],
			"L_CONTENT_DESC"			=> $Lang->data["article_content_desc"],
			"L_CONTENT_DETAIL"			=> $Lang->data["article_content_detail"],
			"L_CONTENT_URL"				=> $Lang->data["article_content_url"],
			"L_AUTHOR"					=> $Lang->data["article_author"],
			"L_POST_TIME"				=> $Lang->data["article_post_time"],
			"L_POST_TIME_TIP"			=> $Lang->data["article_post_time_tip"],
			"L_DATE"					=> $Lang->data["general_date"],
			"L_TIME"					=> $Lang->data["general_time"],
			"L_TIME_EXPLAIN"			=> $Lang->data["general_time_desc"],
			"L_IS_HOT"					=> $Lang->data["article_ishot"],
			"L_IS_HOT_TIP"				=> $Lang->data["article_ishot_tip"],
			"L_ARTICLE_TYPE"			=> $Lang->data["article_type"],
			"L_ARTICLE_TYPE_TIP"		=> $Lang->data["article_type_tip"],
			"L_TYPE_FULL"				=> $Lang->data["article_type_full"],
			"L_TYPE_SUMMARY"			=> $Lang->data["article_type_summary"],
			"L_TYPE_LINK"				=> $Lang->data["article_type_link"],
			"L_YES"						=> $Lang->data["general_yes"],
			"L_NO"						=> $Lang->data["general_no"],
			"L_OPTIONAL"				=> $Lang->data["general_optional"],
			"L_PAGE_TO"					=> $Lang->data["general_page_to"],
			"L_PAGE_ADD"				=> $Lang->data["general_page_add"],
			"L_PAGE_LIST"				=> $Lang->data["general_page_list"],
			"L_SAVE_AS"					=> $Lang->data["general_save_as"],
			"L_SAVE"					=> $Lang->data["general_save"],
			"L_COPY"					=> $Lang->data["general_copy"],
		));
	}

	function get_all_topics(){
		global $DB, $Template, $Info;

		$DB->query("SELECT * FROM ". $DB->prefix ."article_topic ORDER BY posted_date DESC LIMIT 0,". $Info->option['article_topic_limit']);
		$topic_count = $DB->num_rows();
		$topic_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$topic_count; $i++){
			$Template->set_block_vars("topicrow", array(
				'ID'		=> $topic_data[$i]['topic_id'],
				'TITLE'		=> $topic_data[$i]['topic_title'],
			));
		}
	}

	function do_add_article(){
		global $Session, $Info, $DB, $Template, $Lang, $Func, $Image, $File;

		$user_id						= $Info->user_info['user_id'];
		$this->data["cat_id"]			= intval($Func->get_request('cat_id', 0, 'POST'));
		$this->data["topic_id"]			= intval($Func->get_request('topic_id', 0, 'POST'));
		$this->data["topic_id_search"]	= intval($Func->get_request('topic_id_search', 0, 'POST'));
		$this->data["meta_keywords"]	= htmlspecialchars($Func->get_request('meta_keywords', '', 'POST'));
		$this->data["meta_desc"]		= htmlspecialchars($Func->get_request('meta_desc', '', 'POST'));
		$this->data["is_hot"]			= intval($Func->get_request('is_hot', 0, 'POST'));
		$this->data["article_type"]		= intval($Func->get_request('article_type', 0, 'POST'));
		$this->data["title"]			= htmlspecialchars($Func->get_request('title', '', 'POST'));
		$this->data['thumb_large']		= htmlspecialchars($Func->get_request('pic_thumb', '', 'FILES'));
		$this->data["content_desc"]		= htmlspecialchars($Func->get_request('content_desc', '', 'POST'));
		$this->data["content_detail"]	= htmlspecialchars($Func->get_request('content_detail', '', 'POST'));
		$this->data["content_url"]		= htmlspecialchars($Func->get_request('content_url', '', 'POST'));
		$this->data["used_files"]		= htmlspecialchars($Func->get_request('used_files', '', 'POST'));
		$this->data["author"]			= htmlspecialchars($Func->get_request('author', '', 'POST'));
		$this->data["enabled"]			= intval($Func->get_request('enabled', 0, 'POST'));
		$this->data["page_to"]			= htmlspecialchars($Func->get_request('page_to', '', 'POST'));

		$this->data["ptime"]			= CURRENT_TIME;
		$this->data["month"]			= intval($Func->get_request('month', 0, 'POST'));
		$this->data["day"]				= intval($Func->get_request('day', 0, 'POST'));
		$this->data["year"]				= intval($Func->get_request('year', 0, 'POST'));
		$this->data["time"]				= htmlspecialchars($Func->get_request('time', '0:0', 'POST'));
		$this->data['thumb_small']	= "";
		$this->data['thumb_icon']	= "";

		if ( !$this->data['topic_id'] && $this->data['topic_id_search'] ){
			//Check exist
			$DB->query('SELECT topic_id FROM '. $DB->prefix .'article_topic WHERE topic_id='. $this->data['topic_id_search']);
			if ( $DB->num_rows() ){
				$this->data['topic_id']		= $this->data['topic_id_search'];
			}
		}

		//Check permission ---------------
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$this->data['enabled']	= SYS_DISABLED;
			}
		}
		//--------------------------------

		if ( $this->data["month"] && $this->data["day"] && $this->data["year"] ){
			if ( checkdate($this->data["month"], $this->data["day"], $this->data["year"]) ){
				$tmp	= explode(':', $this->data["time"]);
				if ( is_array($tmp) ){
					$hour		= ( isset($tmp[0]) && ($tmp[0] >= 0) && ($tmp[0] <= 24) ) ? $tmp[0] : 0;
					$minute		= ( isset($tmp[1]) && ($tmp[1] >= 0) && ($tmp[1] <= 60) ) ? $tmp[1] : 0;
				}
				else{
					$hour		= 0;
					$minute		= 0;
				}
//				$this->data["ptime"]	= mktime($hour, $minute, 0, $this->data["month"], $this->data["day"], $this->data["year"]);
				$this->data["ptime"]	= gmmktime($hour, $minute, 0, $this->data["month"], $this->data["day"], $this->data["year"]) - $Info->option['timezone']*3600;
			}
		}

		if ( !$this->data["cat_id"] || empty($this->data["title"]) || empty($this->data["content_desc"]) ||
			 (($this->data["article_type"] == SYS_ARTICLE_FULL) && empty($this->data["content_detail"])) ){
				$this->pre_add_article($Lang->data["general_error_not_full"]);
				return false;
		}

		//Check content URL
		if ( !empty($this->data["content_url"]) ){
			if ( (strpos($this->data["content_url"], 'http://') === false) &&
				 (strpos($this->data["content_url"], 'https://') === false) &&
				 (strpos($this->data["content_url"], 'ftp://') === false) &&
				 (strpos($this->data["content_url"], 'mms://') === false) ){
				 	$this->data["content_url"]	= 'http://'. $this->data["content_url"];
			}
		}

		//Check picture
		if ( !empty($this->data['thumb_large']) ){
			//Get file type
			$start		= strrpos($this->data['thumb_large'], ".");
			$filetype	= strtolower(substr($this->data['thumb_large'], $start));
			if ( !$File->check_filetype($filetype, 'image') ){
				$this->pre_add_article(sprintf($Lang->data["upload_error_file_type"], $filetype));
				return false;
			}
		}

		//Insert article
		$sql		= "INSERT INTO ". $DB->prefix ."article(cat_id, topic_id, meta_keywords, meta_desc, thumb_large, thumb_small, thumb_icon, title, content_desc, content_url, poster_id, checker_id, posted_date, is_hot, article_type, page_counter, enabled)
								VALUES(". $this->data["cat_id"] .", ". $this->data['topic_id'] .", '". $this->data["meta_keywords"]."', '". $this->data["meta_desc"]."', '', '', '', '". $this->data["title"]."', '". $this->data["content_desc"]."', '". $this->data["content_url"]."', $user_id, 0, ". $this->data["ptime"] .", ". $this->data["is_hot"] .", ". $this->data["article_type"] .", 1, 0)";
		$DB->query($sql);
		$article_id	= $DB->insert_id();

		if ( !empty($this->data['thumb_large']) || !empty($this->data['used_files']) ){
			//Make image dir for this article
			$this->make_image_dir($this->data["ptime"], $article_id);

			//Transfer used_files and remove temp files
			$data_info['desc']		= $this->data['content_desc'];
			$data_info['detail']	= $this->data['content_detail'];
			$File->transfer_temp_files($this->data["used_files"], $this->sysdir['id'], $data_info);
			$this->data['content_desc']		= $data_info['desc'];
			$this->data['content_detail']	= $data_info['detail'];
			//-----------------------------------------

			//Update pic thumb
			if ( !empty($this->data['thumb_large']) ){
				//Thumb large -----------------------
				if ( file_exists($this->sysdir['id'] .'/'. $this->data["thumb_large"]) ){
					$count		= 1;
					$thumb_large	= str_replace(".", $count .".", $this->data["thumb_large"]);
					while ( file_exists($this->sysdir['id'] .'/'. $thumb_large) ){
						$count++;
						$thumb_large	= str_replace(".", $count .".", $this->data["thumb_large"]);
					}
					$this->data['thumb_large'] = $thumb_large;
				}
				$File->upload_file($_FILES["pic_thumb"]['tmp_name'], $this->sysdir['id'] .'/'. $this->data["thumb_large"]);
				$Image->resize_image($this->sysdir['id'] .'/'. $this->data["thumb_large"], $Info->option['thumb_large_max_width'], $Info->option['thumb_large_max_height'], 'all');
				//-----------------------------------

				//Thumb small -----------------------
				$this->data['thumb_small']	= 'small_'. $this->data['thumb_large'];
				if ( file_exists($this->sysdir['id'] .'/'. $this->data["thumb_small"]) ){
					$count		= 1;
					$thumb_small	= str_replace(".", $count .".", $this->data["thumb_small"]);
					while ( file_exists($this->sysdir['id'] .'/'. $thumb_small) ){
						$count++;
						$thumb_small	= str_replace(".", $count .".", $this->data["thumb_small"]);
					}
					$this->data['thumb_small'] = $thumb_small;
				}
				$File->copy_file($this->sysdir['id'] .'/'. $this->data["thumb_large"], $this->sysdir['id'] .'/'. $this->data["thumb_small"]);
				$Image->resize_image($this->sysdir['id'] .'/'. $this->data["thumb_small"], $Info->option['thumb_small_max_width'], $Info->option['thumb_small_max_height'], 'all');
				//-----------------------------------

				//Thumb icon ------------------------
				$this->data['thumb_icon']	= 'icon_'. $this->data['thumb_large'];
				if ( file_exists($this->sysdir['id'] .'/'. $this->data["thumb_icon"]) ){
					$count		= 1;
					$thumb_icon	= str_replace(".", $count .".", $this->data["thumb_icon"]);
					while ( file_exists($this->sysdir['id'] .'/'. $thumb_icon) ){
						$count++;
						$thumb_icon	= str_replace(".", $count .".", $this->data["thumb_icon"]);
					}
					$this->data['thumb_icon'] = $thumb_icon;
				}
				$Image->create_thumbnail($this->sysdir['id'] .'/'. $this->data["thumb_large"], $this->sysdir['id'] .'/'. $this->data["thumb_icon"], $Info->option['thumb_icon_max_width'], $Info->option['thumb_icon_max_height']);
				//-----------------------------------
			}
		}

		//Update article
		$DB->query("UPDATE ". $DB->prefix ."article SET thumb_large='". $this->data['thumb_large'] ."', thumb_small='". $this->data['thumb_small'] ."', thumb_icon='". $this->data['thumb_icon'] ."', content_desc='". $this->data['content_desc'] ."', enabled=". $this->data['enabled'] ." WHERE article_id=$article_id");

		//Insert page info
		$DB->query("INSERT INTO ". $DB->prefix ."article_page(article_id, page_title, used_files, page_order, page_enabled) VALUES($article_id, '". $this->data['title'] ."', '".$this->data["used_files"]."', 1, 1)");
		$page_id	= $DB->insert_id();
		$DB->query("INSERT INTO ". $DB->prefix ."article_page_content(page_id, article_id, content_detail, author) VALUES($page_id, $article_id, '". $this->data['content_detail'] ."', '". $this->data['author'] ."')");

		//Update category's articles
		$DB->query("UPDATE ". $DB->prefix ."article_category SET article_counter=article_counter+1 WHERE cat_id=". $this->data["cat_id"]);

		//Update user's articles
		$DB->query("UPDATE ". $DB->prefix ."user SET article_counter=article_counter+1 WHERE user_id=". $user_id);

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $article_id, ACP_INDEX .'?mod=article&act='. FUNC_ACT_VIEW .'&id='. $article_id);

		if ( $this->data['page_to'] == 'pageadd' ){
			$tmp_data['cat_id']			= $this->data['cat_id'];
			$tmp_data['topic_id']		= $this->data['topic_id'];
			$tmp_data['is_hot']			= $this->data['is_hot'];
			$tmp_data['enabled']		= $this->data['enabled'];
			$tmp_data['page_to']		= $this->data['page_to'];
			$this->data	= $tmp_data;
			$this->pre_add_article($Lang->data['general_success_add']);
		}
		else{
			$this->list_articles();
		}

		return true;
	}

	function pre_edit_article($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$Info->tpl_main	= "article_edit";
		$this->get_all_topics();
		$this->set_lang();

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['own']) ){
				$auth_where_sql	.= " AND A.poster_id=". $Info->user_info['user_id'];
			}
			
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND A.enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND A.enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		$DB->query("SELECT A.*, P.* FROM ". $DB->prefix ."article AS A, ". $DB->prefix ."article_page AS P WHERE A.article_id=$id AND P.article_id=$id $auth_where_sql ORDER BY P.page_order ASC LIMIT 0,1");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["article_error_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=article". $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
		$article_info	= $DB->fetch_array();
		$DB->free_result();

		//Get article content
		$DB->query('SELECT content_detail, author FROM '. $DB->prefix .'article_page_content WHERE page_id='. $article_info['page_id']);
		if ( $DB->num_rows() ){
			$tmp_info	= $DB->fetch_array();
			$article_info['content_detail']	= $tmp_info['content_detail'];
			$article_info['author']			= $tmp_info['author'];
		}
		else{
			$article_info['content_detail']	= "";
			$article_info['author']			= "";
		}

		$this->get_image_dir($article_info['posted_date'], $id);

		$date	= explode('-', gmdate('m-d-Y-H-i', $article_info["posted_date"] + $Info->option['timezone']*3600));
		$month	= intval($date[0]); //Remove zero from the first letter
		$day	= intval($date[1]);
		$year	= intval($date[2]);
		$time	= $date[3] .":". $date[4];

		$topic_id_search	= isset($this->data["topic_id_search"]) ? $this->data["topic_id_search"] : $article_info['topic_id'];
		if ( !empty($article_info['thumb_small']) || !empty($article_info['thumb_large']) ){
			$Template->set_block_vars("picthumb", array());
		}
		$Template->set_block_vars("editrow", array());
		$Template->set_vars(array(
			"ERROR_MSG"				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=article&act=edit&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			'PIC_THUMB'				=> !empty($article_info['thumb_small']) ? '<img src="'. $this->sysdir['id'] .'/'. $article_info['thumb_small'] .'">' : (!empty($article_info['thumb_large']) ? '<img src="'. $this->sysdir['id'] .'/'. $article_info['thumb_large'] .'">' : ''),
			'REMOVE_THUMB_CHECK'	=> (isset($this->data['remove_thumb']) && $this->data['remove_thumb']) ? 'checked' : '',
			"IS_HOT"				=> isset($this->data["is_hot"]) ? $this->data["is_hot"] : $article_info['is_hot'],
			"ARTICLE_TYPE"			=> isset($this->data["article_type"]) ? $this->data["article_type"] : $article_info['article_type'],
			"PAGE_ID"				=> $article_info['page_id'],
			"CAT_ID"				=> isset($this->data["cat_id"]) ? $this->data["cat_id"] : $article_info['cat_id'],
			"TOPIC_ID"				=> isset($this->data["topic_id"]) ? $this->data["cat_id"] : $article_info['topic_id'],
			"TOPIC_ID_SEARCH"		=> $topic_id_search ? $topic_id_search : '',
			"META_KEYWORDS"			=> isset($this->data["meta_keywords"]) ? stripslashes($this->data["meta_keywords"]) : $article_info['meta_keywords'],
			"META_DESC"				=> isset($this->data["meta_desc"]) ? stripslashes($this->data["meta_desc"]) : $article_info['meta_desc'],
			"TITLE"					=> isset($this->data["title"]) ? stripslashes($this->data["title"]) : $article_info['title'],
			"CONTENT_DESC"			=> isset($this->data["content_desc"]) ? stripslashes($this->data["content_desc"]) : $article_info['content_desc'],
			"CONTENT_DETAIL"		=> isset($this->data["content_detail"]) ? stripslashes($this->data["content_detail"]) : $article_info['content_detail'],
			"CONTENT_URL"			=> isset($this->data["content_url"]) ? stripslashes($this->data["content_url"]) : $article_info['content_url'],
			'USED_FILES'			=> isset($this->data["used_files"]) ? stripslashes($this->data["used_files"]) : '',
			"AUTHOR"				=> isset($this->data["author"]) ? stripslashes($this->data["author"]) : $article_info['author'],
			"MONTH"					=> isset($this->data["month"]) ? $this->data["month"] : $month,
			"DAY"					=> isset($this->data["day"]) ? $this->data["day"] : $day,
			"YEAR"					=> isset($this->data["year"]) ? $this->data["year"] : $year,
			"TIME"					=> isset($this->data["time"]) ? $this->data["time"] : $time,
			"ENABLED"				=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : $article_info['enabled'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_article"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			"L_BUTTON"				=> $Lang->data["button_edit"],
		));
		//WYSIWYG editor for title
		if ( $Info->option['enable_article_wysiwyg_title'] ){
			$Template->set_block_vars("wysiwyg_title", array());
		}
		return true;
	}

	function do_edit_article(){
		global $Session, $Info, $DB, $Template, $Lang, $Func, $Image, $File;

		$id				= isset($_GET['id']) ? intval($_GET['id']) : 0;
		$this->data["page_id"]			= intval($Func->get_request('page_id', 0, 'POST'));
		$this->data["cat_id"]			= intval($Func->get_request('cat_id', 0, 'POST'));
		$this->data["topic_id"]			= intval($Func->get_request('topic_id', 0, 'POST'));
		$this->data["topic_id_search"]	= intval($Func->get_request('topic_id_search', 0, 'POST'));
		$this->data["meta_keywords"]	= htmlspecialchars($Func->get_request('meta_keywords', '', 'POST'));
		$this->data["meta_desc"]		= htmlspecialchars($Func->get_request('meta_desc', '', 'POST'));
		$this->data["is_hot"]			= intval($Func->get_request('is_hot', 0, 'POST'));
		$this->data["article_type"]		= intval($Func->get_request('article_type', 0, 'POST'));
		$this->data["title"]			= htmlspecialchars($Func->get_request('title', '', 'POST'));
		$this->data['thumb_large']		= htmlspecialchars($Func->get_request('pic_thumb', '', 'FILES'));
		$this->data["remove_thumb"]		= intval($Func->get_request('remove_thumb', 0, 'POST'));
		$this->data["content_desc"]		= htmlspecialchars($Func->get_request('content_desc', '', 'POST'));
		$this->data["content_detail"]	= htmlspecialchars($Func->get_request('content_detail', '', 'POST'));
		$this->data["content_url"]		= htmlspecialchars($Func->get_request('content_url', '', 'POST'));
		$this->data["used_files"]		= htmlspecialchars($Func->get_request('used_files', '', 'POST'));
		$this->data["author"]			= htmlspecialchars($Func->get_request('author', '', 'POST'));
		$this->data["enabled"]			= intval($Func->get_request('enabled', 0, 'POST'));
		$this->data["page_to"]			= htmlspecialchars($Func->get_request('page_to', '', 'POST'));

		$this->data["ptime"]			= CURRENT_TIME;
		$this->data["month"]			= intval($Func->get_request('month', 0, 'POST'));
		$this->data["day"]				= intval($Func->get_request('day', 0, 'POST'));
		$this->data["year"]				= intval($Func->get_request('year', 0, 'POST'));
		$this->data["time"]				= htmlspecialchars($Func->get_request('time', '0:0', 'POST'));

		if ( !$this->data['topic_id'] && $this->data['topic_id_search'] ){
			//Check exist
			$DB->query('SELECT topic_id FROM '. $DB->prefix .'article_topic WHERE topic_id='. $this->data['topic_id_search']);
			if ( $DB->num_rows() ){
				$this->data['topic_id']		= $this->data['topic_id_search'];
			}
		}

		//Check permission ---------------
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$this->data['enabled']	= SYS_DISABLED;
			}
		}
		//--------------------------------

		if ( $this->data["month"] && $this->data["day"] && $this->data["year"] ){
			if ( checkdate($this->data["month"], $this->data["day"], $this->data["year"]) ){
				$tmp	= explode(':', $this->data["time"]);
				if ( is_array($tmp) ){
					$hour		= ( isset($tmp[0]) && ($tmp[0] >= 0) && ($tmp[0] <= 24) ) ? $tmp[0] : 0;
					$minute		= ( isset($tmp[1]) && ($tmp[1] >= 0) && ($tmp[1] <= 60) ) ? $tmp[1] : 0;
				}
				else{
					$hour		= 0;
					$minute		= 0;
				}
//				$this->data["ptime"]	= mktime($hour, $minute, 0, $this->data["month"], $this->data["day"], $this->data["year"]);
				$this->data["ptime"]	= gmmktime($hour, $minute, 0, $this->data["month"], $this->data["day"], $this->data["year"]) - $Info->option['timezone']*3600;
			}
		}

		if ( !$this->data["cat_id"] || empty($this->data["title"]) || empty($this->data["content_desc"]) ||
			 (($this->data["article_type"] == SYS_ARTICLE_FULL) && empty($this->data["content_detail"])) ){
				$this->pre_edit_article($Lang->data["general_error_not_full"]);
				return false;
		}

		//Check content URL
		if ( !empty($this->data["content_url"]) ){
			if ( (strpos($this->data["content_url"], 'http://') === false) &&
				 (strpos($this->data["content_url"], 'https://') === false) &&
				 (strpos($this->data["content_url"], 'ftp://') === false) &&
				 (strpos($this->data["content_url"], 'mms://') === false) ){
				 	$this->data["content_url"]	= 'http://'. $this->data["content_url"];
			}
		}

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['own']) ){
				$auth_where_sql	.= " AND A.poster_id=". $Info->user_info['user_id'];
			}
			
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND A.enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND A.enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		//Get old info
		$DB->query('SELECT A.*, P.* FROM '. $DB->prefix .'article AS A, '. $DB->prefix .'article_page AS P WHERE A.article_id='. $id .' AND P.article_id='. $id .' AND P.page_id='. $this->data['page_id'] . $auth_where_sql);
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data['article_error_not_exist'], $Session->append_sid(ACP_INDEX .'?mod=article'. $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
		$article_info	= $DB->fetch_array();
		$this->make_image_dir($article_info['posted_date'], $id);

		//Get article content
		$DB->query('SELECT content_detail, author FROM '. $DB->prefix .'article_page_content WHERE page_id='. $article_info['page_id']);
		if ( $DB->num_rows() ){
			$tmp_info	= $DB->fetch_array();
			$article_info['content_detail']	= $tmp_info['content_detail'];
			$article_info['author']			= $tmp_info['author'];
		}
		else{
			$article_info['content_detail']	= "";
			$article_info['author']			= "";
		}

		//Transfer used_files and remove temp files
		$data_info['desc']		= $this->data['content_desc'];
		$data_info['detail']	= $this->data['content_detail'];
		$File->transfer_temp_files($this->data["used_files"], $this->sysdir['id'], $data_info);
		$this->data['content_desc']		= $data_info['desc'];
		$this->data['content_detail']	= $data_info['detail'];
		//-----------------------------------------

		//Pic thumb
		$sql_thumb_large	= "";
		$sql_thumb_small	= "";
		$sql_thumb_icon		= "";
		$thumb_large		= $article_info['thumb_large'];
		$thumb_small		= $article_info['thumb_small'];
		$thumb_icon			= $article_info['thumb_icon'];

		if ( !empty($this->data['thumb_large']) ){
			//Get file type
			$start		= strrpos($this->data['thumb_large'], ".");
			$filetype	= strtolower(substr($this->data['thumb_large'], $start));
			if ( !$File->check_filetype($filetype, 'image') ){
				$this->pre_edit_article(sprintf($Lang->data["upload_error_file_type"], $filetype));
				return false;
			}

			//Delete old pic thumb
			if ( !empty($article_info['thumb_large']) ){
				$File->delete_file($this->sysdir['id'] .'/'. $article_info['thumb_large']);
			}
			if ( !empty($article_info['thumb_small']) ){
				$File->delete_file($this->sysdir['id'] .'/'. $article_info['thumb_small']);
			}
			if ( !empty($article_info['thumb_icon']) ){
				$File->delete_file($this->sysdir['id'] .'/'. $article_info['thumb_icon']);
			}

			//Thumb large -----------------------
			if ( file_exists($this->sysdir['id'] .'/'. $this->data["thumb_large"]) ){
				$count			= 1;
				$thumb_large	= str_replace(".", $count .".", $this->data["thumb_large"]);
				while ( file_exists($this->sysdir['id'] .'/'. $thumb_large) ){
					$count++;
					$thumb_large	= str_replace(".", $count .".", $this->data["thumb_large"]);
				}
				$this->data['thumb_large'] = $thumb_large;
			}
			$File->upload_file($_FILES["pic_thumb"]['tmp_name'], $this->sysdir['id'] .'/'. $this->data["thumb_large"]);
			$Image->resize_image($this->sysdir['id'] .'/'. $this->data["thumb_large"], $Info->option['thumb_large_max_width'], $Info->option['thumb_large_max_height'], 'all');
			//-----------------------------------

			//Thumb small -----------------------
			$this->data['thumb_small']	= 'small_'. $this->data['thumb_large'];
			if ( file_exists($this->sysdir['id'] .'/'. $this->data["thumb_small"]) ){
				$count		= 1;
				$thumb_small	= str_replace(".", $count .".", $this->data["thumb_small"]);
				while ( file_exists($this->sysdir['id'] .'/'. $thumb_small) ){
					$count++;
					$thumb_small	= str_replace(".", $count .".", $this->data["thumb_small"]);
				}
				$this->data['thumb_small'] = $thumb_small;
			}
			$File->copy_file($this->sysdir['id'] .'/'. $this->data["thumb_large"], $this->sysdir['id'] .'/'. $this->data["thumb_small"]);
			$Image->resize_image($this->sysdir['id'] .'/'. $this->data["thumb_small"], $Info->option['thumb_small_max_width'], $Info->option['thumb_small_max_height'], 'all');
			//-----------------------------------

			//Thumb icon ------------------------
			$this->data['thumb_icon']	= 'icon_'. $this->data['thumb_large'];
			if ( file_exists($this->sysdir['id'] .'/'. $this->data["thumb_icon"]) ){
				$count		= 1;
				$thumb_icon	= str_replace(".", $count .".", $this->data["thumb_icon"]);
				while ( file_exists($this->sysdir['id'] .'/'. $thumb_icon) ){
					$count++;
					$thumb_icon	= str_replace(".", $count .".", $this->data["thumb_icon"]);
				}
				$this->data['thumb_icon'] = $thumb_icon;
			}
			$Image->create_thumbnail($this->sysdir['id'] .'/'. $this->data["thumb_large"], $this->sysdir['id'] .'/'. $this->data["thumb_icon"], $Info->option['thumb_icon_max_width'], $Info->option['thumb_icon_max_height']);
			//-----------------------------------

			$sql_thumb_large	= ", thumb_large='". $this->data['thumb_large'] ."'";
			$sql_thumb_small	= ", thumb_small='". $this->data['thumb_small'] ."'";
			$sql_thumb_icon		= ", thumb_icon='". $this->data['thumb_icon'] ."'";
			$thumb_large		= $this->data['thumb_large'];
			$thumb_small		= $this->data['thumb_small'];
			$thumb_icon			= $this->data['thumb_icon'];
		}
		else if ( $this->data['remove_thumb'] ){
			//Delete old pic thumb
			if ( !empty($article_info['thumb_large']) ){
				$File->delete_file($this->sysdir['id'] .'/'. $article_info['thumb_large']);
			}
			if ( !empty($article_info['thumb_small']) ){
				$File->delete_file($this->sysdir['id'] .'/'. $article_info['thumb_small']);
			}
			if ( !empty($article_info['thumb_icon']) ){
				$File->delete_file($this->sysdir['id'] .'/'. $article_info['thumb_icon']);
			}

			$sql_thumb_large	= ", thumb_large=''";
			$sql_thumb_small	= ", thumb_small=''";
			$sql_thumb_icon		= ", thumb_icon=''";
			$thumb_large		= "";
			$thumb_small		= "";
			$thumb_icon			= "";
		}

		//Clean old used files ----------
		$data_info['desc']		= $this->data['content_desc'];
		$data_info['detail']	= $this->data['content_detail'];
		$File->clean_used_files($article_info["used_files"], $this->sysdir['id'], $data_info, $this->data['used_files']);
		$this->data['content_desc']		= $data_info['desc'];
		$this->data['content_detail']	= $data_info['detail'];
		//-------------------------------

		//Delete files which are not used
		$File->delete_unused_files($this->data['used_files'], $article_info['used_files'], $this->sysdir['id']);

		//Check new and old dirs --------------
		$old_sysdir	= $this->sysdir;
		$this->get_image_dir($this->data['ptime'], $id);
		$new_sysdir	= $this->sysdir;

		if ( $new_sysdir['id'] != $old_sysdir['id'] ){
			//Make dir
			$this->make_image_dir($this->data['ptime'], $id);
			//Move all files from old dir to new dir
			$File->move_dir($old_sysdir['id'], $new_sysdir['id']);

			//Replace images path in content
			$file_info	= explode(',', $this->data["used_files"]);
			reset($file_info);
			while (list(, $filename) = each($file_info)){
				$filename	= trim($filename);
				if ( !empty($filename) ){
					$this->data['content_desc']		= str_replace($old_sysdir['id'] .'/'. $filename , $new_sysdir['id'] .'/'. $filename, $this->data['content_desc']);
					$this->data['content_detail']	= str_replace($old_sysdir['id'] .'/'. $filename , $new_sysdir['id'] .'/'. $filename, $this->data['content_detail']);
				}
			}
		}
		//-------------------------------------

		//Update article
		$DB->query("UPDATE ". $DB->prefix ."article SET cat_id=". $this->data["cat_id"] .", topic_id=". $this->data['topic_id'] .", meta_keywords='". $this->data["meta_keywords"]."', meta_desc='". $this->data["meta_desc"]."'". $sql_thumb_large . $sql_thumb_small . $sql_thumb_icon .", title='". $this->data["title"]."', content_desc='". $this->data["content_desc"]."', content_url='". $this->data["content_url"]."', posted_date=". $this->data["ptime"] .", is_hot='". $this->data["is_hot"] ."', article_type='". $this->data["article_type"] ."', enabled=". $this->data['enabled']." WHERE article_id=$id");

		//Update content detail
		$DB->query("UPDATE ". $DB->prefix ."article_page SET page_title='". $this->data['title'] ."', used_files='". $this->data["used_files"] ."' WHERE page_id=". $this->data['page_id']);
		$DB->query("UPDATE ". $DB->prefix ."article_page_content SET content_detail='". $this->data['content_detail'] ."', author='". $this->data["author"]."' WHERE page_id=". $this->data['page_id']);

		if ($this->data['cat_id'] != $article_info['cat_id']){
			//Update old category's articles
			$DB->query("UPDATE ". $DB->prefix ."article_category SET article_counter=article_counter-1 WHERE cat_id=". $article_info["cat_id"]);
			//Update new category's articles
			$DB->query("UPDATE ". $DB->prefix ."article_category SET article_counter=article_counter+1 WHERE cat_id=". $this->data["cat_id"]);
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=article&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_articles();
		return true;
	}

	function view_article(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$id			= intval($Func->get_request('id', 0));
		$page_id	= intval($Func->get_request('page_id', 0));

		$Info->tpl_main		= "article_view";
		$time_format		= $Info->option['time_format'];
		$timezone			= $Info->option['timezone'] * 3600;

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['own']) ){
				$auth_where_sql	.= " AND A.poster_id=". $Info->user_info['user_id'];
			}
			
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND A.enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND A.enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		if ( $page_id ){
			$DB->query('SELECT A.*, P.* FROM '. $DB->prefix .'article AS A, '. $DB->prefix .'article_page AS P WHERE A.article_id='. $id .' AND P.article_id='. $id .' AND P.page_id='. $page_id . $auth_where_sql);
		}
		else{
			$DB->query('SELECT A.*, P.* FROM '. $DB->prefix .'article AS A, '. $DB->prefix .'article_page AS P WHERE A.article_id='. $id .' AND P.article_id='. $id . $auth_where_sql .' ORDER BY P.page_order ASC LIMIT 0,1');
		}
		if ( !$DB->num_rows() ){
			$Template->message_die($Lang->data['article_error_not_exist']);
			return false;
		}
		$article_info	= $DB->fetch_array();

		//Get article content
		$DB->query('SELECT content_detail, author FROM '. $DB->prefix .'article_page_content WHERE page_id='. $article_info['page_id']);
		if ( $DB->num_rows() ){
			$tmp_info	= $DB->fetch_array();
			$article_info['content_detail']	= $tmp_info['content_detail'];
			$article_info['author']			= $tmp_info['author'];
		}
		else{
			$article_info['content_detail']	= "";
			$article_info['author']			= "";
		}

		$Template->set_vars(array(
			'PAGE_ID'		=> $article_info['page_id'],
			'TITLE'			=> html_entity_decode($article_info['title']),
			'PAGE_TITLE'	=> html_entity_decode($article_info['page_title']),
			'AUTHOR'		=> $article_info['author'],
			'CONTENT'		=> ($article_info['article_type'] == SYS_ARTICLE_FULL) ? html_entity_decode($article_info['content_detail']) : html_entity_decode($article_info['content_desc']),
			'CONTENT_URL'	=> ($article_info['article_type'] == SYS_ARTICLE_LINK) ? '<br><br><strong>'. $Lang->data['article_content_url'] .':</strong> <a href="'. $article_info['content_url'] .'" target="_blank">'. $article_info['content_url'] .'</a>' : '',
			'DATE'			=> $article_info['posted_date'] ? $Func->translate_date(gmdate($time_format, $article_info['posted_date'] + $timezone)) : '',
			'S_PAGE_ACTION'	=> $Session->append_sid(ACP_INDEX .'?mod=article&act=view&id='. $id),
			"L_PAGE_TITLE"	=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data['menu_article_article'] . $Lang->data['general_arrow'] . $Lang->data['general_view'],
			'L_PAGE'		=> $Lang->data['general_page'],
			'L_GO'			=> $Lang->data['button_go'],
			'L_CLOSE'		=> $Lang->data['general_close_window'],
		));

		//Get all pages
		$DB->query('SELECT page_id, page_title FROM '. $DB->prefix .'article_page WHERE article_id='. $id .' ORDER BY page_order ASC');
		$page_count	= $DB->num_rows();
		$page_data	= $DB->fetch_all_array();
		$DB->free_result();

		if ( $page_count > 1 ){
			for ($i=0; $i<$page_count; $i++){
				$Template->set_block_vars("pagerow", array(
					'ID'		=> $page_data[$i]['page_id'],
					'TITLE'		=> html_entity_decode($page_data[$i]['page_title']),
				));
			}
		}

		return true;
	}

	function active_articles($enabled = 0){
		global $DB, $Template, $Func, $Info;

		$article_ids	= $Func->get_request('article_ids', '', 'POST');

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

		//Get id list
		$ids_info	= $Func->get_array_value($article_ids);
		if ( sizeof($ids_info) ){
			$log_act	= $enabled ? 'log_enable' : 'log_disable';
			$str_ids	= implode(',', $ids_info);
			$where_sql	= "WHERE article_id IN (". $str_ids .")";

			//Update articles
			$DB->query("UPDATE ". $DB->prefix ."article SET enabled=$enabled, checker_id=". $Info->user_info['user_id'] ." $where_sql $auth_where_sql");
			//Save log
			$Func->save_log(FUNC_NAME, $log_act, $str_ids);
		}

		$this->list_articles();
	}

	function archive_articles($archived = 0){
		global $DB, $Template, $Func, $Info;

		$article_ids	= $Func->get_request('article_ids', '', 'POST');

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

		//Get id list
		$ids_info	= $Func->get_array_value($article_ids);
		if ( sizeof($ids_info) ){
			$log_act	= $archived ? 'log_archive' : 'log_unarchive';
			$str_ids	= implode(',', $ids_info);
			$where_sql	= "WHERE article_id IN (". $str_ids .")";

			//Update articles
			$DB->query("UPDATE ". $DB->prefix ."article SET archived=$archived $where_sql $auth_where_sql");
			//Save log
			$Func->save_log(FUNC_NAME, $log_act, $str_ids);
		}

		$this->list_articles();
	}

	function delete_articles(){
		global $DB, $Template, $Func, $Info, $File;

		$article_ids	= $Func->get_request('article_ids', '', 'POST');

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

		//Get id list
		$ids_info	= $Func->get_array_value($article_ids);
		if ( sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);
			$where_sql	= " WHERE article_id IN (". $str_ids .")";

			//Get and delete image dirs --------
			$DB->query("SELECT article_id, posted_date FROM ". $DB->prefix ."article $where_sql $auth_where_sql");
			$article_count	= $DB->num_rows();
			$article_data	= $DB->fetch_all_array();
			$DB->free_result();

			for ($i=0; $i<$article_count; $i++){
				$this->get_image_dir($article_data[$i]['posted_date'], $article_data[$i]['article_id']);
				$File->delete_dir($this->sysdir['id']);
			}
			//------------------------------------

			$DB->query("DELETE FROM ". $DB->prefix ."article_comment $where_sql");
			$DB->query("DELETE FROM ". $DB->prefix ."article_page_content $where_sql");
			$DB->query("DELETE FROM ". $DB->prefix ."article_page $where_sql");
			$DB->query("DELETE FROM ". $DB->prefix ."article $where_sql");

			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $str_ids);
			//Resync categories' counters
			$this->resync_cats();
			//Resync topics' counters
			$this->resync_topics();
			//Resync posters' counters
			$this->resync_posters();
		}

		$this->list_articles();
	}

	function pre_move_cat(){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$article_ids	= $Func->get_request('article_ids', '', 'POST');
		$Info->tpl_main	= "article_move_cat";

		//Get id list
		$ids_info	= $Func->get_array_value($article_ids);
		if ( !sizeof($ids_info) ){
			$this->list_articles();
			return false;
		}

		$str_ids	= implode(',', $ids_info);
		$DB->query('SELECT article_id, title FROM '. $DB->prefix .'article WHERE article_id IN ('. $str_ids .')');
		$article_count	= $DB->num_rows();
		$article_data	= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$article_count; $i++){
			$Template->set_block_vars("articlerow", array(
				'TITLE'		=> strip_tags(html_entity_decode($article_data[$i]['title'])),
				'U_VIEW'	=> $Session->append_sid(ACP_INDEX .'?mod=article&act=view&id='. $article_data[$i]["article_id"]),
			));
		}

		$Template->set_vars(array(
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=article&act=movecat'. $this->filter['url_append'] .'&page='. $this->page),
			'ARTICLE_IDS'			=> $str_ids,
			'ARTICLE_COUNT'			=> $article_count,
			'L_ARTICLE_COUNT'		=> $Lang->data['articles'],
			'L_MOVE_TO_CAT'			=> $Lang->data['article_move_cat'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_article"] . $Lang->data['general_arrow'] . $Lang->data["article_move_cat"],
			"L_BUTTON"				=> $Lang->data["button_move"],
		));
		return true;
	}

	function do_move_cat(){
		global $DB, $Template, $Lang, $Info, $Func;

		$article_ids	= explode(',', $Func->get_request('article_ids', '', 'POST'));
		$cat_id			= intval($Func->get_request('cat_id', 0, 'POST'));

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

		if ( $cat_id && is_array($article_ids) ){
			$ids_info	= $Func->get_array_value($article_ids);

			if ( sizeof($ids_info) ){
				$str_ids	= implode(',', $ids_info);

				//Update articles
				$DB->query('UPDATE '. $DB->prefix .'article SET cat_id='. $cat_id .' WHERE article_id IN ('. $str_ids .') '. $auth_where_sql);
				//Update cat_counter
				$this->resync_cats();
				//Save log
				$Func->save_log(FUNC_NAME, 'log_move_cat', $str_ids);
			}
		}

		$this->list_articles();
	}

	function pre_move_topic(){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$article_ids	= $Func->get_request('article_ids', '', 'POST');
		$Info->tpl_main	= "article_move_topic";

		if ( !is_array($article_ids) ){
			$this->list_articles();
			return false;
		}

		//Get all topic
		$DB->query('SELECT topic_id, topic_title FROM '. $DB->prefix .'article_topic ORDER BY topic_id DESC');
		$topic_count	= $DB->num_rows();
		$topic_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$topic_count; $i++){
			$Template->set_block_vars("topicrow", array(
				'ID'		=> $topic_data[$i]['topic_id'],
				'TITLE'		=> $topic_data[$i]['topic_title'],
			));
		}

		//Get id list
		$ids_info	= $Func->get_array_value($article_ids);
		if ( !sizeof($ids_info) ){
			$this->list_articles();
			return false;
		}

		$str_ids	= implode(',', $ids_info);
		$DB->query('SELECT article_id, title FROM '. $DB->prefix .'article WHERE article_id IN ('. $str_ids .')');
		$article_count	= $DB->num_rows();
		$article_data	= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$article_count; $i++){
			$Template->set_block_vars("articlerow", array(
				'TITLE'		=> strip_tags(html_entity_decode($article_data[$i]['title'])),
				'U_VIEW'	=> $Session->append_sid(ACP_INDEX .'?mod=article&act=view&id='. $article_data[$i]["article_id"]),
			));
		}

		$Template->set_vars(array(
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=article&act=movetopic'. $this->filter['url_append'] .'&page='. $this->page),
			'ARTICLE_IDS'			=> $str_ids,
			'ARTICLE_COUNT'			=> $article_count,
			'L_ARTICLE_COUNT'		=> $Lang->data['articles'],
			'L_MOVE_TO_TOPIC'		=> $Lang->data['article_move_topic'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_article"] . $Lang->data['general_arrow'] . $Lang->data["article_move_topic"],
			"L_BUTTON"				=> $Lang->data["button_move"],
		));
		return true;
	}

	function do_move_topic(){
		global $DB, $Template, $Lang, $Info, $Func;

		$article_ids	= explode(',', $Func->get_request('article_ids', '', 'POST'));
		$topic_id		= intval($Func->get_request('topic_id', 0, 'POST'));

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

		if ( $topic_id && is_array($article_ids) ){
			$ids_info	= $Func->get_array_key($article_ids);

			if ( sizeof($ids_info) ){
				$str_ids	= implode(',', $ids_info);

				//Update articles
				$DB->query('UPDATE '. $DB->prefix .'article SET topic_id='. $topic_id .' WHERE article_id IN ('. $str_ids .')'. $auth_where_sql);
				//Update topic counters
				$this->resync_topics();
				//Save log
				$Func->save_log(FUNC_NAME, 'log_move_topic', $str_ids);
			}
		}

		$this->list_articles();
	}

	function resync_cats(){
		global $Session, $DB, $Template, $Lang;

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

	function resync_topics(){
		global $Session, $DB, $Template, $Lang;

		$DB->query('UPDATE '. $DB->prefix .'article_topic SET article_counter=0');

		//Update article_counter
		$DB->query('SELECT count(article_id) AS counter, topic_id FROM '. $DB->prefix.'article WHERE topic_id>0 GROUP BY topic_id');
		$topic_count = $DB->num_rows();
		$topic_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$topic_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'article_topic SET article_counter='. $topic_data[$i]['counter'] .' WHERE topic_id='. $topic_data[$i]['topic_id']);
		}
	}

	function resync_posters(){
		global $Session, $DB, $Template, $Lang;

		$DB->query('UPDATE '. $DB->prefix .'user SET article_counter=0');

		//Update article_counter
		$DB->query('SELECT count(article_id) AS counter, poster_id FROM '. $DB->prefix.'article WHERE poster_id>0 GROUP BY poster_id');
		$poster_count = $DB->num_rows();
		$poster_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$poster_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'user SET article_counter='. $poster_data[$i]['counter'] .' WHERE user_id='. $poster_data[$i]['poster_id']);
		}
	}

	function resync_articles(){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$DB->query('UPDATE '. $DB->prefix .'article SET page_counter=1');

		//Update page_counter -----------------
		$DB->query('SELECT count(page_id) AS counter, article_id FROM '. $DB->prefix.'article_page GROUP BY article_id HAVING counter!=1');
		$article_count = $DB->num_rows();
		$article_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$article_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'article SET page_counter='. $article_data[$i]['counter'] .' WHERE article_id='. $article_data[$i]['article_id']);
		}
		//-------------------------------------

		//Save log
		$Func->save_log(FUNC_NAME, 'log_resync');
		$this->list_articles();
	}
}

$AdminArticle	= new Admin_Article;
?>
