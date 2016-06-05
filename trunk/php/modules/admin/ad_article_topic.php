<?php
/* =============================================================== *\
|		Module name: Topic											|
|		Module version: 1.2											|
|		Begin: 15 March 2004										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_article_topic');
define('FUNC_ACT_VIEW', 'preedit');
//Module language
$Func->import_module_language("admin/lang_article". PHP_EX);

$AdminTopic = new Admin_Topic;

class Admin_Topic
{
	var $page         = 1;
	var $data         = array();

	var $user_perm		= array();

	function Admin_Topic(){
		global $Info, $Func, $Cache;

		$this->user_perm	= $Func->get_all_perms('menu_article_topic');
		$this->get_filter();

		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$Cache->clear_cache('all');
				$this->pre_add_topic();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$Cache->clear_cache('all');
				$this->do_add_topic();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_topic();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all');
				$this->do_edit_topic();
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$Cache->clear_cache('all');
				$this->do_delete_topic();
				break;
			case "update":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all');
				$this->update_topics();
				break;
			case "resync":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->resync_topics();
				break;
			case "premove":
				$Func->check_user_perm($this->user_perm, 'move_article');
				$this->pre_move_articles();
				break;
			case "move":
				$Func->check_user_perm($this->user_perm, 'move_article');
				$Cache->clear_cache('all');
				$this->do_move_articles();
				break;
			default:
				$this->list_topics();
		}
	}

	function get_filter(){
		global $Template, $Func;

		$this->filter['url_append']	= "";

		$this->filter['keyword']		= htmlspecialchars($Func->get_request('fkeyword', ''));
		if ( !empty($this->filter['keyword']) ){
			$this->filter['url_append']	.= '&fkeyword='. $this->filter['keyword'];
		}

		$this->page		= intval($Func->get_request('page', 1, 'GET'));

		$Template->set_vars(array(
			"FKEYWORD"	=> stripslashes($this->filter['keyword']),
		));
	}

	function list_topics(){
		global $Session, $Func, $Info, $Lang, $Template, $DB;

		$Info->tpl_main	= "article_topic_list";
		$itemperpage	= $Info->option['items_per_page'];

		//Filter -------------------------
		$where_sql		= " WHERE topic_id>0";
		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*", '%', $this->filter['keyword']);
			$where_sql	.= " AND (topic_title LIKE '%". $key ."%' OR topic_desc LIKE '%". $key ."%')";
		}
		//--------------------------------

		//Generate pages
		$DB->query("SELECT count(*) AS total FROM ". $DB->prefix ."article_topic ". $where_sql);
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=article_topic"));
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query("SELECT * FROM ". $DB->prefix ."article_topic ". $where_sql ." ORDER BY posted_date DESC LIMIT ". $pageshow['start'] .",". $itemperpage);
		if ( $DB->num_rows() ){
			$i	= 0;
			while ($result = $DB->fetch_array()){
				$Template->set_block_vars("topicrow", array(
					'ID'				=> $result['topic_id'],
					'TITLE'				=> $result['topic_title'],
					'ARTICLE_COUNTER'	=> $result['article_counter'] ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article&ftopic_id='. $result['topic_id']) .'">'. $result['article_counter'] .'</a>' : $result['article_counter'],
					'BG_CSS'			=> ($i % 2) ? 'tdtext2' : 'tdtext1',
					'U_VIEW'			=> $Session->append_sid(ACP_INDEX .'?mod=article_topic&pid='. $result['topic_id'] .'&page='. $this->page),
					'U_EDIT'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_topic&act=preedit&id='. $result['topic_id'] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
				));
				$i++;
			}
		}

		$Template->set_vars(array(
			"PAGE_OUT"					=> $pageshow['page'],
			'S_FILTER_ACTION'			=> $Session->append_sid(ACP_INDEX .'?mod=article_topic'),
			'U_ADD'						=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_topic&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="'. $Lang->data['general_add'] .'" border="0" align="absbottom">'. $Lang->data['general_add'] .'</a>' : '',
			'U_RESYNC'					=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_topic&act=resync' . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/resync.gif" alt="" title="'. $Lang->data['general_resync'] .'" border="0" align="absbottom">'. $Lang->data['general_resync'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'					=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript: deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article_topic&act=del' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" border="0" align="absbottom">'. $Lang->data['general_del'] .'</a> &nbsp; &nbsp;' : '',
			'U_MOVE'					=> $Func->check_user_perm($this->user_perm, 'move_article', 0) ? '<a href="javascript: updateForm2(\''. $Session->append_sid(ACP_INDEX .'?mod=article_topic&act=premove' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/move.gif" alt="" title="'. $Lang->data['article_move'] .'" border="0" align="absbottom">'. $Lang->data['article_move'] .'</a> &nbsp; &nbsp;' : '',
			"L_PAGE_TITLE"				=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_topic"],
			"L_ORDER"					=> $Lang->data["general_order"],
			"L_ID"						=> $Lang->data["general_id"],
			"L_TITLE"					=> $Lang->data["article_topic_title"],
			"L_ARTICLES"				=> $Lang->data["articles"],
			"L_SEARCH"					=> $Lang->data["button_search"],
			"L_DEL_CONFIRM"				=> $Lang->data['article_topic_del_confirm'],
			"L_CHOOSE_ITEM"				=> $Lang->data['article_error_topic_not_check'],
		));
	}

	function pre_add_topic($msg = ""){
		global $Session, $Template, $Info, $Lang;

		$Info->tpl_main	= "article_topic_edit";
		$this->set_lang();

		$Template->set_block_vars("addrow", array());
		$Template->set_vars(array(
			"ERROR_MSG"					=> $msg,
			'S_ACTION'					=> $Session->append_sid(ACP_INDEX .'?mod=article_topic&act=add'),
			"L_PAGE_TITLE"				=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_topic"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			'L_BUTTON'					=> $Lang->data['button_add'],
			"TOPIC_TITLE"				=> isset($this->data["title"]) ? stripslashes($this->data["title"]) : '',
			"TOPIC_DESC"				=> isset($this->data["desc"]) ? stripslashes($this->data["desc"]) : '',
			"PAGETO"					=> isset($this->data["page_to"]) ? $this->data["page_to"] : '',
		));
	}

	function set_lang(){
		global $Template, $Lang;

		$Template->set_vars(array(
			'L_TOPIC_TITLE'				=> $Lang->data['article_topic_title'],
			'L_TOPIC_DESC'				=> $Lang->data['article_topic_desc'],
			"L_PAGE_TO"					=> $Lang->data["general_page_to"],
			"L_PAGE_ADD"				=> $Lang->data["general_page_add"],
			"L_PAGE_LIST"				=> $Lang->data["general_page_list"],
		));
	}

	function do_add_topic(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$this->data["title"]		= htmlspecialchars($Func->get_request('topic_title', '', 'POST'));
		$this->data["desc"]			= htmlspecialchars($Func->get_request('topic_desc', '', 'POST'));
		$this->data['page_to']		= htmlspecialchars($Func->get_request('page_to', '', 'POST'));

		if ( empty($this->data["title"]) ){
			$this->pre_add_topic($Lang->data["general_error_not_full"]);
			return false;
		}

		//Check exist
		$DB->query("SELECT topic_id FROM ". $DB->prefix ."article_topic WHERE topic_title='". $this->data['title'] ."'");
		if ( $DB->num_rows() ){
			$this->pre_add_topic($Lang->data["article_error_topic_exist"]);
			return false;
		}

		$sql = "INSERT INTO ". $DB->prefix ."article_topic(topic_title, topic_desc, article_counter, posted_date)
					VALUES('". $this->data["title"]."', '".$this->data["desc"]."', 0, ". CURRENT_TIME .")";
		$DB->query($sql);
		$topic_id	= $DB->insert_id();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $topic_id, ACP_INDEX .'?mod=article_topic&act='. FUNC_ACT_VIEW .'&id='. $topic_id);

		if ( $this->data['page_to'] == 'pageadd' ){
			$tmp_data['page_to']	= $this->data['page_to'];
//			$this->data	= array();//Reset data
			$this->data	= $tmp_data;
			$this->pre_add_topic($Lang->data['general_success_add']);
		}
		else{
			$this->list_topics();
		}
		return true;
	}

	function pre_edit_topic($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$id				= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$Info->tpl_main	= "article_topic_edit";

		$DB->query("SELECT * FROM ". $DB->prefix ."article_topic WHERE topic_id=$id");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["article_error_topic_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=article_topic&page=". $this->page));
			return false;
		}
		$topic_info = $DB->fetch_array();
		$DB->free_result();

		$this->set_lang();

		//Get all article of this topic
		$DB->query('SELECT article_id, title FROM '. $DB->prefix .'article WHERE topic_id='. $id);
		$article_count	= $DB->num_rows();
		$article_data	= $DB->fetch_all_array();
		for ($i=0; $i<$article_count; $i++){
			$Template->set_block_vars("articlerow", array(
				'ID'		=> $article_data[$i]['article_id'],
				'TITLE'		=> strip_tags(html_entity_decode($article_data[$i]['title'])),
				'U_VIEW'	=> $Session->append_sid(ACP_INDEX .'?mod=article&act=view&id='. $article_data[$i]['article_id']),
			));
		}

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=article_topic&act=edit&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			"L_PAGE_TITLE"			=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_topic"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			'L_BUTTON'				=> $Lang->data['button_edit'],
			'TOPIC_TITLE'			=> isset($this->data['title']) ? stripslashes($this->data['title']) : $topic_info['topic_title'],
			'TOPIC_DESC'			=> isset($this->data['desc']) ? stripslashes($this->data['desc']) : $topic_info['topic_desc'],
			'L_ARTICLES'			=> $Lang->data['articles'],
			'L_ARTICLE_REMOVE'		=> $Lang->data['article_topic_remove'],
			'L_CHECK_ALL'			=> $Lang->data['general_checkall'],
			'L_UNCHECK_ALL'			=> $Lang->data['general_uncheckall'],
		));
		return true;
	}

	function do_edit_topic(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$this->data["title"]		= htmlspecialchars($Func->get_request('topic_title', '', 'POST'));
		$this->data["desc"]			= htmlspecialchars($Func->get_request('topic_desc', '', 'POST'));
		$this->data["article_ids"]	= $Func->get_request('article_ids', '', 'POST');

		if ( empty($this->data["title"]) ){
			$this->pre_edit_topic($Lang->data["article_error_topic_not_exist"]);
			return false;
		}

		//Check exist
		$DB->query("SELECT topic_id FROM ". $DB->prefix ."article_topic WHERE topic_title='". $this->data['title'] ."' AND topic_id!=$id");
		if ( $DB->num_rows() ){
			$this->pre_edit_topic($Lang->data["article_error_topic_exist"]);
			return false;
		}

		//Get old info
		$DB->query("SELECT * FROM ". $DB->prefix ."article_topic WHERE topic_id=$id");
		if ( !$DB->num_rows() ){
			$this->list_topics();
			return false;
		}
		$topic_info	= $DB->fetch_array();

		$ids_info	= $Func->get_array_value($this->data['article_ids']);
		//Remove article from the topic
		if ( sizeof($ids_info) ){
			$DB->query('UPDATE '. $DB->prefix .'article SET topic_id=0 WHERE article_id IN ('. implode(',', $ids_info) .')');
			$topic_info['article_counter']	= $topic_info['article_counter'] - sizeof($ids_info);
			if ( $topic_info['article_counter'] < 0){
				$topic_info['article_counter']	= 0;
			}
		}

		//Update info
		$DB->query("UPDATE ". $DB->prefix ."article_topic SET topic_title='". $this->data["title"] ."', topic_desc='". $this->data["desc"] ."', article_counter=". $topic_info['article_counter'] ." WHERE topic_id=$id");
		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=article_topic&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_topics();
		return true;
	}

	function resync_topics(){
		global $Session, $DB, $Template, $Lang, $Func;

		$DB->query('UPDATE '. $DB->prefix .'article_topic SET article_counter=0');

		//Update article_counter
		$DB->query('SELECT count(article_id) AS counter, topic_id FROM '. $DB->prefix.'article GROUP BY topic_id');
		$topic_count = $DB->num_rows();
		$topic_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$topic_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'article_topic SET article_counter='. $topic_data[$i]['counter'] .' WHERE topic_id='. $topic_data[$i]['topic_id']);
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_resync');

		$this->list_topics();
	}

	function do_delete_topic(){
		global $Session, $DB, $Template, $Lang, $Func;

		$topic_ids		= $Func->get_request('topic_ids', '', 'POST');
		$ids_info		= $Func->get_array_value($topic_ids);

		if ( sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);
			$where_sql	= " WHERE topic_id IN (". $str_ids .")";

			$DB->query("UPDATE ". $DB->prefix ."article SET topic_id=0 $where_sql");
			$DB->query("DELETE FROM ". $DB->prefix ."article_topic $where_sql");

			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $str_ids);
		}

		$this->list_topics();
	}

	function pre_move_articles($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$topic_ids			= $Func->get_request('topic_ids', '', 'POST');
		$Info->tpl_main		= "article_topic_move";

		//Get topics
		$DB->query('SELECT * FROM '. $DB->prefix .'article_topic ORDER BY posted_date DESC LIMIT 0, '. $Info->option['topic_move']);
		$topic_count	= $DB->num_rows();
		$topic_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$topic_count; $i++){
			$Template->set_block_vars("topicrow", array(
				'ID'				=> $topic_data[$i]['topic_id'],
				'TITLE'				=> $topic_data[$i]['topic_title'],
				'ARTICLE_COUNTER'	=> $topic_data[$i]['article_counter'],
				'SELECTED'			=> (is_array($topic_ids) && in_array($topic_data[$i]['topic_id'], $topic_ids)) ? 'selected' : '',
			));
		}

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'              => $Session->append_sid(ACP_INDEX .'?mod=article_topic&act=move&page='. $this->page),
			"L_PAGE_TITLE"			=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_topic"] . $Lang->data['general_arrow'] . $Lang->data["article_move"],
			'L_BUTTON'				=> $Lang->data['button_move'],
			'L_SOURCE_TOPICS'		=> $Lang->data['article_topic_source'],
			'L_DEST_TOPIC'			=> $Lang->data['article_topic_dest'],
		));
	}

	function do_move_articles(){
		global $Session, $DB, $Template, $Lang, $Func;

		$source_id   = $Func->get_request('source_id', 0, 'POST');
		$dest_id     = intval($Func->get_request('dest_id', 0, 'POST'));

		if ( !$source_id || !$dest_id ){
			$this->pre_move_articles($Lang->data['article_error_topic_source']);
			return false;
		}

		if ( is_array($source_id) ){
			$source_ids		= $source_id;
		}
		else{
			$source_ids[0]	= $source_id;
		}

		$ids_info	= $Func->get_array_value($source_ids);
		if ( sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);

			//Move articles from source topics to another topic
			$DB->query('UPDATE '. $DB->prefix .'article SET topic_id='. $dest_id .' WHERE topic_id IN ('. $str_ids .')');
			//Save log
			$Func->save_log(FUNC_NAME, 'log_move_topic', $str_ids);
		}

		$this->resync_topics();
		return true;
	}
}
?>