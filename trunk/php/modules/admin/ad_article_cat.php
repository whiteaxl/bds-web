<?php
/* =============================================================== *\
|		Module name: Category										|
|		Module version: 1.7											|
|		Begin: 12 August 2003										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_article_cat');
define('FUNC_ACT_VIEW', 'preedit');
//Module language
$Func->import_module_language("admin/lang_article". PHP_EX);
//Article global file
include("modules/admin/ad_article_global". PHP_EX);

class Admin_Category extends Admin_Article_Global
{
	var $page			= 1;
	var $parent_id		= 0;
	var	$view_mode		= "";

	var $user_perm		= array();

	function Admin_Category(){
		global $Info, $Func, $Cache;

		$this->view_mode	= htmlspecialchars($Func->get_request('view_mode', ''));
		$this->parent_id	= intval($Func->get_request('pid', 0 ,'GET'));
		$this->page			= intval($Func->get_request('page', 1, 'GET'));

		$this->user_perm	= $Func->get_all_perms('menu_article_cat');

		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_cat();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$Cache->clear_cache('all');
				$this->do_add_cat();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_cat();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all');
				$this->do_edit_cat();
				break;
			case "predel":
				$Func->check_user_perm($this->user_perm, 'del');
				$this->pre_delete_cat();
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$Cache->clear_cache('all');
				$this->do_delete_cat();
				break;
			case "update":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all');
				$this->update_cats();
				break;
			case "resync":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->resync_cats();
				$this->list_cats();
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
				$this->list_cats();
		}
	}

	function list_cats(){
		global $Session, $Func, $Info, $Lang, $Template, $DB;

		$Info->tpl_main	= "article_cat_list";

		if ( $this->view_mode == 'expand'){
			$this->get_all_cats();
			$this->set_all_cats(0, 0, 0, '&view_mode='. $this->view_mode .'&page='. $this->page);
		}
		else{
			$Template->set_block_vars('normalmode', array());
			$itemperpage	= $Info->option['items_per_page'];

			//Get parent title
			if ($this->parent_id){
				$parent_name     = "";
				$this->get_all_cats();
				$this->get_parent_names($this->parent_id, $parent_name);
				$Template->set_block_vars("parenthave", array(
					'NAME'     => $parent_name
				));
			}

			//Generate pages
			$DB->query("SELECT count(*) AS total FROM ". $DB->prefix ."article_category WHERE cat_parent_id=". $this->parent_id);
			if ( $DB->num_rows() ){
				$result		= $DB->fetch_array();
				$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=article_cat&pid=". $this->parent_id));
			}
			else{
				$pageshow['page']	= "";
				$pageshow['start']	= 0;
			}
			$DB->free_result();

			$DB->query("SELECT * FROM ". $DB->prefix ."article_category WHERE cat_parent_id=". $this->parent_id ." ORDER BY cat_order ASC LIMIT ". $pageshow['start'] .",". $itemperpage);
			if ( $DB->num_rows() ){
				$i	= 0;
				while ($result = $DB->fetch_array()){
					if ( !empty($result['redirect_url']) ){
						$result['cat_name']	= ' <span title="'. $Lang->data['general_redirect_url'] .': '. $result['redirect_url'] .'">'. $result['cat_name'] .' *</span>';
					}
					$counter	= ($result['article_counter'] || $result['children_counter'])  ?  'false' : 'true';
					$css		= ($result["enabled"] == SYS_DISABLED) ? "disabled" : "enabled";
					$Template->set_block_vars("catrow", array(
						'PREFIX'			=> '',
						'ID'				=> $result['cat_id'],
						'CODE'				=> $result['cat_code'],
						'NAME'				=> $result['children_counter'] ? '<a class="'. $css .'" href="'. $Session->append_sid(ACP_INDEX .'?mod=article_cat&pid='. $result['cat_id'] .'&page='. $this->page) .'">'. $result['cat_name'] .'</a>' : $result['cat_name'],
						'TEMPLATE'			=> $result['cat_template'],
						'ORDER'				=> $result['cat_order'],
						'ARTICLE_COUNTER'	=> $result['article_counter'] ? '<a class="'. $css .'" href="'. $Session->append_sid(ACP_INDEX .'?mod=article&fcat_id='. $result['cat_id']) .'">'. $result['article_counter'] .'</a>' : $result['article_counter'],
						'SUBCAT_COUNTER'	=> $result['children_counter'] ? '<a class="'. $css .'" href="'. $Session->append_sid(ACP_INDEX .'?mod=article_cat&pid='. $result['cat_id'] .'&page='. $this->page) .'">'. $result['children_counter'] .'</a>' : $result['children_counter'],
						'CSS'				=> $css,
						'BG_CSS'			=> ($i % 2) ? 'tdtext2' : 'tdtext1',
						'U_VIEW'			=> $Session->append_sid(ACP_INDEX .'?mod=article_cat&pid='. $result['cat_id'] .'&page='. $this->page),
						'U_EDIT'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=preedit&id='. $result['cat_id'] .'&pid='. $this->parent_id .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
						'U_DEL'				=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=predel&id='. $result['cat_id'] .'&pid='. $this->parent_id .'&page='. $this->page) .'" onclick="javascript: if ('. $counter .'){ return del_confirm(\''. $Lang->data['general_del_confirm'] .'\');}"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" border=0 alt="" title="'. $Lang->data['general_del'] .'"></a>' : '&nbsp;',
					));
					$i++;
				}
			}
		}

		$Template->set_vars(array(
			"PAGE_OUT"					=> isset($pageshow['page']) ? $pageshow['page'] : '',
			'VIEW_MODE'					=> $this->view_mode,
			'S_MODE_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=article_cat'),
			'U_ADD'						=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=preadd&pid='. $this->parent_id) .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="'. $Lang->data['general_add'] .'" border="0" align="absbottom">'. $Lang->data['general_add'] .'</a>' : '',
			'U_UPDATE'					=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="javascript: updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=update&pid='. $this->parent_id .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/order.gif" alt="" title="'. $Lang->data['general_update'] .'" border="0" align="absbottom">'. $Lang->data['general_update'] .'</a> &nbsp; &nbsp;' : '',
			'U_RESYNC'					=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=resync&pid='. $this->parent_id .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/resync.gif" alt="" title="'. $Lang->data['general_resync'] .'" border="0" align="absbottom">'. $Lang->data['general_resync'] .'</a> &nbsp; &nbsp;' : '',
			'U_MOVE'					=> $Func->check_user_perm($this->user_perm, 'move_article', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=premove&page='. $this->page .'&pid='. $this->parent_id) .'"><img src="'. $Info->option['template_path'] .'/images/admin/move.gif" alt="" title="'. $Lang->data["article_move"] .'" border="0" align="absbottom">'. $Lang->data["article_move"] .'</a>' : '',
			'U_LIST_CAT'				=> $Session->append_sid(ACP_INDEX .'?mod=article_cat'),
			"L_PAGE_TITLE"				=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_cat"],
			"L_ORDER"					=> $Lang->data["general_order"],
			"L_CODE"					=> $Lang->data["article_cat_code"],
			"L_NAME"					=> $Lang->data["general_cat_name"],
			"L_TEMPLATE"				=> $Lang->data["article_cat_template"],
			"L_CHILDS"					=> $Lang->data["general_cat_childs"],
			"L_ARTICLES"				=> $Lang->data["articles"],
			"L_NORMAL"					=> $Lang->data["general_normal"],
			"L_EXPAND"					=> $Lang->data["general_expand"],
		));
	}

	function get_parent_names($cat_id, &$str){
		global $Session;

		for ($i=0; $i<$this->cat_count; $i++){
			if ( $cat_id == $this->cat_data[$i]['cat_id'] ){
				$this->get_parent_names($this->cat_data[$i]['cat_parent_id'],  $str);
				$str .= '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_cat&pid='. $cat_id) .'">'. $this->cat_data[$i]['cat_name'] .'</a>/ ';
			}
		}
	}

	function pre_add_cat($msg = ""){
		global $Session, $Template, $Info, $Lang;

		$Info->tpl_main	= "article_cat_edit";
		$this->set_lang();
		$this->get_cat_templates();

		$this->get_all_cats();
		$this->set_all_cats(0, 0, 0);

		$Template->set_block_vars("addrow", array());
		$Template->set_vars(array(
			"ERROR_MSG"					=> $msg,
			'S_ACTION'					=> $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=add'),
			"L_PAGE_TITLE"				=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_cat"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			'L_BUTTON'					=> $Lang->data['button_add'],
			"PARENT_ID"					=> isset($this->data["parent_id"]) ? $this->data["parent_id"] : ($this->parent_id ? $this->parent_id : 0),
			"CAT_CODE"					=> isset($this->data["code"]) ? stripslashes($this->data["code"]) : '',
			"CAT_NAME"					=> isset($this->data["name"]) ? stripslashes($this->data["name"]) : '',
			"CAT_KEYWORDS"				=> isset($this->data["keywords"]) ? stripslashes($this->data["keywords"]) : '',
			"CAT_DESC"					=> isset($this->data["desc"]) ? stripslashes($this->data["desc"]) : '',
			"REDIRECT_URL"				=> isset($this->data["redirect_url"]) ? stripslashes($this->data["redirect_url"]) : '',
			"CAT_TEMPLATE"				=> isset($this->data["template"]) ? stripslashes($this->data["template"]) : 'default',
			"INDEX_DISPLAY"				=> isset($this->data["index_display"]) ? $this->data["index_display"] : '',
			"ENABLED"					=> isset($this->data["enabled"]) ? $this->data["enabled"] : '',
			"PAGETO"					=> isset($this->data["page_to"]) ? $this->data["page_to"] : '',
		));
	}

	function set_lang(){
		global $Template, $Lang;

		$Template->set_vars(array(
			'L_ROOT'					=> $Lang->data['general_cat_root'],
			'L_PARENT_CAT'				=> $Lang->data['general_cat_parent'],
			'L_CAT_CODE'				=> $Lang->data['article_cat_code'],
			'L_CAT_CODE_TIP'			=> $Lang->data['article_cat_code_tip'],
			'L_SEARCH_ENGINE_TIP'		=> $Lang->data['search_engine_tip'],
			'L_CAT_NAME'				=> $Lang->data['general_cat_name'],
			'L_CAT_KEYWORDS'			=> $Lang->data['general_meta_keywords'],
			'L_CAT_DESC'				=> $Lang->data['general_meta_desc'],
			'L_REDIRECT_URL'			=> $Lang->data['general_redirect_url'],
			'L_OPTIONAL'				=> $Lang->data['general_optional'],
			'L_CAT_TEMPLATE'			=> $Lang->data['article_cat_template'],
			'L_CAT_TEMPLATE_TIP'		=> $Lang->data['article_cat_template_tip'],
			'L_INDEX_DISPLAY'			=> $Lang->data['article_cat_index_display'],
			'L_INDEX_DISPLAY_TIP'		=> $Lang->data['article_cat_index_display_tip'],
			"L_PAGE_TO"					=> $Lang->data["general_page_to"],
			"L_PAGE_ADD"				=> $Lang->data["general_page_add"],
			"L_PAGE_LIST"				=> $Lang->data["general_page_list"],
		));
	}

	function get_cat_templates(){
		global $Template, $Info;

		$dir	= 'templates/'. $Info->option['template'] .'/category';

		$handle	= opendir($dir);
		while (($file = readdir($handle)) != false){
			if ( ($file != ".") && ($file != "..") && is_dir($dir ."/". $file) ) {
				$Template->set_block_vars("templaterow", array(
					'DIR'		=> $file,
					'PATH'		=> $Info->option['template'] .'/category/' . $file,
				));
			}
		}
		closedir($handle);
	}

	function do_add_cat(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$this->data["parent_id"]	= intval($Func->get_request('parent_id', 0, 'POST'));
		//$this->data["code"]			= str_replace(' ', '', htmlspecialchars($Func->get_request('cat_code', '', 'POST')));
		$this->data["code"]			= htmlspecialchars($Func->get_request('cat_code', '', 'POST'));
		$this->data["name"]			= htmlspecialchars($Func->get_request('cat_name', '', 'POST'));
		$this->data["keywords"]		= htmlspecialchars($Func->get_request('cat_keywords', '', 'POST'));
		$this->data["desc"]			= htmlspecialchars($Func->get_request('cat_desc', '', 'POST'));
		$this->data["redirect_url"]	= htmlspecialchars($Func->get_request('redirect_url', '', 'POST'));
		$this->data["template"]		= htmlspecialchars($Func->get_request('cat_template', '', 'POST'));
		$this->data["index_display"]= intval($Func->get_request('index_display', 0, 'POST'));
		$this->data["enabled"]		= intval($Func->get_request('enabled', 0, 'POST'));
		$this->data['page_to']		= htmlspecialchars($Func->get_request('page_to', '', 'POST'));

		if ( empty($this->data["code"]) || empty($this->data["name"]) || empty($this->data["template"]) ){
			$this->pre_add_cat($Lang->data["general_error_not_full"]);
			return false;
		}

		if ( is_numeric($this->data["code"]) ){
			$this->data["code"]		= 'c'. $this->data["code"];
		}
/*		$this->data["code"]			= str_replace('&', '_', $this->data["code"]);
		$this->data["code"]			= str_replace('/', '', $this->data["code"]);
		$this->data["code"]			= str_replace('.', '', $this->data["code"]);
		$this->data["code"]			= str_replace('-', '', $this->data["code"]);
		$this->data["code"]			= str_replace('_', '', $this->data["code"]);
		$this->data["code"]			= str_replace('+', '', $this->data["code"]);
		$this->data["code"]			= str_replace('^', '', $this->data["code"]);
		$this->data["code"]			= str_replace('~', '', $this->data["code"]);
*/
		//Check exist
		$DB->query("SELECT cat_id FROM ". $DB->prefix ."article_category WHERE cat_code='". $this->data['code'] ."'");
		if ( $DB->num_rows() ){
			$this->pre_add_cat($Lang->data["article_error_cat_exist"]);
			return false;
		}

		//Get max order
		$DB->query("SELECT max(cat_order) AS max_order FROM ". $DB->prefix ."article_category WHERE cat_parent_id=". $this->data["parent_id"]);
		if ($DB->num_rows()){
			$result					= $DB->fetch_array();
			$this->data["order"]	= $result["max_order"] + 1;
		}
		else{
			$this->data["order"] = 1;
		}
		$DB->free_result();

		$sql = "INSERT INTO ". $DB->prefix ."article_category(cat_parent_id, cat_code, cat_name, cat_keywords, cat_desc, cat_template, cat_order, children_counter, article_counter, redirect_url, index_display, enabled)
					VALUES(". $this->data["parent_id"] .",  '". strtolower($Func->title_to_code($this->data['code'])) ."', '". $this->data["name"]."', '". $this->data["keywords"] ."', '". $this->data["desc"] ."', '". $this->data["template"] ."', ". $this->data["order"] .", 0, 0, '". $this->data["redirect_url"] ."', ". $this->data['index_display'].", ". $this->data['enabled'].")";
		$DB->query($sql);
		$cat_id		= $DB->insert_id();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $cat_id, ACP_INDEX .'?mod=article_cat&act='. FUNC_ACT_VIEW .'&id='. $cat_id);

		if ($this->data['parent_id']){
			//Update counter of parent
			$DB->query('UPDATE '. $DB->prefix .'article_category SET children_counter=children_counter+1 WHERE cat_id='. $this->data['parent_id']);
		}

		if ( $this->data['page_to'] == 'pageadd' ){
			//Reset data
			$this->data['code']		= "";
			$this->data['name']		= "";
			$this->data['keywords']	= "";
			$this->data['desc']		= "";
			$this->data['redirect_url']		= "";
			$this->data['template']	= "";
			
			$this->pre_add_cat($Lang->data['general_success_add']);
		}
		else{
			$this->list_cats();
		}
		return true;
	}

	function pre_edit_cat($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$id				= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$Info->tpl_main	= "article_cat_edit";

		$DB->query("SELECT * FROM ". $DB->prefix ."article_category WHERE cat_id=$id");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["article_error_cat_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=article_cat&pid=". $this->parent_id ."&page=". $this->page));
			return false;
		}
		$cat_info = $DB->fetch_array();
		$DB->free_result();

		$this->get_all_cats();
		$this->set_all_cats(0, $id, 0);
		$this->set_lang();
		$this->get_cat_templates();

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=edit&pid='. $this->parent_id .'&id='. $id .'&page='. $this->page .'&view_mode='. $this->view_mode),
			"L_PAGE_TITLE"			=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_cat"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			'L_BUTTON'				=> $Lang->data['button_edit'],
			'PARENT_ID'				=> isset($this->data['parent_id']) ? $this->data['parent_id'] : $cat_info['cat_parent_id'],
			'CAT_CODE'				=> isset($this->data['code']) ? stripslashes($this->data['code']) : $cat_info['cat_code'],
			'CAT_NAME'				=> isset($this->data['name']) ? stripslashes($this->data['name']) : $cat_info['cat_name'],
			'CAT_KEYWORDS'			=> isset($this->data['keywords']) ? stripslashes($this->data['keywords']) : $cat_info['cat_keywords'],
			'CAT_DESC'				=> isset($this->data['desc']) ? stripslashes($this->data['desc']) : $cat_info['cat_desc'],
			'REDIRECT_URL'			=> isset($this->data['redirect_url']) ? stripslashes($this->data['redirect_url']) : $cat_info['redirect_url'],
			'CAT_TEMPLATE'			=> isset($this->data['cat_template']) ? stripslashes($this->data['cat_template']) : $cat_info['cat_template'],
			'INDEX_DISPLAY'			=> isset($this->data['index_display']) ? $this->data['index_display'] : $cat_info['index_display'],
			'ENABLED'				=> isset($this->data['enabled']) ? $this->data['enabled'] : $cat_info['enabled'],
		));
		return true;
	}

	function do_edit_cat(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$this->data["parent_id"]	= intval($Func->get_request('parent_id', 0, 'POST'));
		//$this->data["code"]			= str_replace(' ', '', htmlspecialchars($Func->get_request('cat_code', '', 'POST')));
		$this->data["code"]			= htmlspecialchars($Func->get_request('cat_code', '', 'POST'));
		$this->data["name"]			= htmlspecialchars($Func->get_request('cat_name', '', 'POST'));
		$this->data["keywords"]		= htmlspecialchars($Func->get_request('cat_keywords', '', 'POST'));
		$this->data["desc"]			= htmlspecialchars($Func->get_request('cat_desc', '', 'POST'));
		$this->data["redirect_url"]	= htmlspecialchars($Func->get_request('redirect_url', '', 'POST'));
		$this->data["template"]		= htmlspecialchars($Func->get_request('cat_template', '', 'POST'));
		$this->data["index_display"]= intval($Func->get_request('index_display', 0, 'POST'));
		$this->data["enabled"]		= intval($Func->get_request('enabled', 0, 'POST'));

		if ( empty($this->data["code"]) || empty($this->data["name"]) || empty($this->data["template"]) ){
			$this->pre_edit_cat($Lang->data["article_error_cat_not_exist"]);
			return false;
		}

		if ( is_numeric($this->data["code"]) ){
			$this->data["code"]		= 'c'. $this->data["code"];
		}
/*		$this->data["code"]			= str_replace('&', '', $this->data["code"]);
		$this->data["code"]			= str_replace('/', '', $this->data["code"]);
		$this->data["code"]			= str_replace('.', '', $this->data["code"]);
		$this->data["code"]			= str_replace('-', '', $this->data["code"]);
		$this->data["code"]			= str_replace('_', '', $this->data["code"]);
		$this->data["code"]			= str_replace('+', '', $this->data["code"]);
		$this->data["code"]			= str_replace('^', '', $this->data["code"]);
		$this->data["code"]			= str_replace('~', '', $this->data["code"]);
*/
		//Check existing
		$DB->query("SELECT cat_id FROM ". $DB->prefix ."article_category WHERE cat_code='". $this->data['code'] ."' AND cat_id!=". $id);
		if ( $DB->num_rows() ){
			$this->pre_edit_cat($Lang->data["article_error_cat_exist"]);
			return false;
		}

		//Get old info
		$DB->query("SELECT * FROM ". $DB->prefix ."article_category WHERE cat_id=". $id);
		if ( !$DB->num_rows() ){
			$this->list_cats();
			return false;
		}
		$cat_info	= $DB->fetch_array();

		//Update info
		$DB->query("UPDATE ". $DB->prefix ."article_category SET cat_parent_id=". $this->data["parent_id"] .", cat_code= '". strtolower($Func->title_to_code($this->data['code'])) ."', cat_name='". $this->data["name"] ."', cat_keywords='". $this->data["keywords"] ."', cat_desc='". $this->data["desc"] ."', cat_template='". $this->data["template"] ."', redirect_url='". $this->data["redirect_url"] ."', index_display=". $this->data["index_display"] .", enabled=". $this->data["enabled"] ." WHERE cat_id=$id");

		if ($this->data['parent_id'] != $cat_info['cat_parent_id']){
			if ( $cat_info['cat_parent_id'] > 0 ){
				//Update counter of old parent
				$DB->query('UPDATE '. $DB->prefix .'article_category SET children_counter=children_counter-1 WHERE cat_id='. $cat_info['cat_parent_id']);
			}
			if ( $this->data['parent_id'] > 0 ){
				//Update counter of new parent
				$DB->query('UPDATE '. $DB->prefix .'article_category SET children_counter=children_counter+1 WHERE cat_id='. $this->data['parent_id']);
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=article_cat&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_cats();
		return true;
	}

	function update_cats(){
		global $Session, $DB, $Template, $Lang, $Func;

		$cat_orders		= $Func->get_request('cat_orders', '', 'POST');

		if ( is_array($cat_orders) ){
			reset($cat_orders);
			while (list($id, $num) = each($cat_orders)){
				$DB->query("UPDATE ". $DB->prefix ."article_category SET cat_order=". intval($num) ." WHERE cat_id=". intval($id));
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_update');

		$this->list_cats();
	}

	function resync_cats(){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$DB->query('UPDATE '. $DB->prefix .'article_category SET article_counter=0, children_counter=0');

		//Update article_counter
		$DB->query('SELECT count(article_id) AS counter, cat_id FROM '. $DB->prefix.'article GROUP BY cat_id');
		$cat_count = $DB->num_rows();
		$cat_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$cat_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'article_category SET article_counter='. $cat_data[$i]['counter'] .' WHERE cat_id='. $cat_data[$i]['cat_id']);
		}

		//Update children_counter
		$DB->query('SELECT count(cat_id) AS counter, cat_parent_id FROM '. $DB->prefix .'article_category WHERE cat_parent_id>0 GROUP BY cat_parent_id');
		$parent_count = $DB->num_rows();
		$parent_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$parent_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'article_category SET children_counter='. $parent_data[$i]['counter'] .' WHERE cat_id='. $parent_data[$i]['cat_parent_id']);
		}

		//Update cat_order
		$DB->query('SELECT * FROM '. $DB->prefix.'article_category ORDER BY cat_order ASC');
		$cat_count = $DB->num_rows();
		$cat_data  = $DB->fetch_all_array();
		$DB->free_result();

		$order_arr = array();
		for ($i=0; $i<$cat_count; $i++){
			$parent_id	= $cat_data[$i]['cat_parent_id'];
			if ( !isset($order_arr[$parent_id]) ){
				$order_arr[$parent_id] = 1;
			}
			else{
				$order_arr[$parent_id]++;
			}
			$sql_template	= ( empty($cat_data[$i]['cat_template']) || !is_dir('templates/'. $Info->option['template'] .'/category/'. $cat_data[$i]['cat_template'])) ? ", cat_template='default'" : "";
			$DB->query('UPDATE '. $DB->prefix .'article_category SET cat_order='. $order_arr[$parent_id] . $sql_template .' WHERE cat_id='. $cat_data[$i]['cat_id']);
		}

		//Save log
//		$Func->save_log(FUNC_NAME, 'log_resync');
	}

	function pre_delete_cat(){
		global $Session, $Info, $DB, $Template, $Lang;

		$id				= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$Info->tpl_main	= "article_cat_delete";

		//Get cat info
		$DB->query('SELECT * FROM '. $DB->prefix .'article_category WHERE cat_id='. $id);
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data['article_error_cat_not_exist'], $Session->append_sid(ACP_INDEX .'?mod=article_cat&pid='. $this->parent_id .'&page='. $this->page));
			return false;
		}
		$cat_info = $DB->fetch_array();

		if ( !$cat_info['article_counter'] && !$cat_info['children_counter'] ){
			$this->do_delete_cat();
			return true;
		}

		if ($cat_info['article_counter']){
			$Template->set_block_vars("havearticle", array());
		}
		if ($cat_info['children_counter']){
			$Template->set_block_vars("havechild", array());
		}

		$this->get_all_cats();
		$this->set_all_cats(0, $id);

		$Template->set_vars(array(
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=del&id='. $id .'&pid='. $this->parent_id .'&page='. $this->page),
			"L_PAGE_TITLE"			=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_cat"] . $Lang->data['general_arrow'] . $Lang->data["general_del"],
			'L_BUTTON'				=> $Lang->data['button_delete'],
			'L_CAT_NAME'			=> $Lang->data['general_cat_name'],
			'L_CHILDS'				=> $Lang->data['general_cat_childs'],
			'L_ARTICLES'			=> $Lang->data['articles'],
			'L_DELETE_SUBCATS'		=> $Lang->data['article_cat_del_childs'],
			'L_DELETE_ARTICLES'		=> $Lang->data['article_cat_del_articles'],
			'L_MOVE_TO'				=> $Lang->data['article_cat_move_to'],
			'CAT_NAME'				=> $cat_info['cat_name'],
			'ARTICLE_COUNTER'		=> $cat_info['article_counter'],
			'CHILDREN_COUNTER'		=> $cat_info['children_counter'],
		));

		return true;
	}

	function do_delete_cat(){
		global $Session, $DB, $Template, $Lang, $Func;

		$id				= intval($Func->get_request('id', 0, 'GET'));
		$del_child		= intval($Func->get_request('del_child', 0, 'POST'));
		$del_article	= intval($Func->get_request('del_article', 0, 'POST'));
		$cat_dest_id	= intval($Func->get_request('cat_dest_id', 0, 'POST'));
		$art_dest_id	= intval($Func->get_request('art_dest_id', 0, 'POST'));

		//Get cat info
		$DB->query('SELECT * FROM '. $DB->prefix .'article_category WHERE cat_id='. $id);
		if ( !$DB->num_rows() ){
			$this->list_cats();
			return false;
		}
		$cat_info    = $DB->fetch_array();

		if ($cat_info['children_counter']){
			if ($del_child || !$cat_dest_id){
				$this->get_all_cats();
				for ($i=0; $i<$this->cat_count; $i++){
					if ($id == $this->cat_data[$i]['cat_parent_id']){
						$this->delete_childs($this->cat_data[$i]['cat_id']);
					}
				}
			}
			else{
				//Update child counter
				$DB->query('SELECT count(cat_id) AS counter FROM '. $DB->prefix .'article_category WHERE cat_parent_id='. $id);
				if ( $DB->num_rows()){
					$tmp_info = $DB->fetch_array();
					$DB->query('UPDATE '. $DB->prefix .'article_category SET children_counter=children_counter+'. $tmp_info['counter'] .' WHERE cat_id='. $cat_dest_id);
				}
				
				//Move sub category
				$DB->query('UPDATE '. $DB->prefix .'article_category SET cat_parent_id='. $cat_dest_id .' WHERE cat_parent_id='. $id);
			}
		}

		if ( $cat_info['article_counter'] ){
			if ($del_article || !$art_dest_id){
				//Select article_id
				$where_sql = " WHERE article_id=0";
				$DB->query("SELECT article_id FROM ". $DB->prefix ."article WHERE cat_id=". $id);
				if ( $DB->num_rows() ){
					while ( $result = $DB->fetch_array() ){
						$where_sql .= " OR article_id=". $result["article_id"];
					}
				}
				$DB->free_result();

				//Delete Articles Pages
				$DB->query("DELETE FROM ". $DB->prefix ."article_page_content $where_sql");
				$DB->query("DELETE FROM ". $DB->prefix ."article_page $where_sql");

				//Delete Articles
				$DB->query('DELETE FROM '. $DB->prefix .'article WHERE cat_id='. $id);
			}
			else{
				//Move articles
				$DB->query('UPDATE '. $DB->prefix .'article SET cat_id='. $art_dest_id .' WHERE cat_id='. $id);
			}
		}

		//Delete current cat
		$DB->query('DELETE FROM '. $DB->prefix .'article_category WHERE cat_id='. $id);

		//Save log
		$Func->save_log(FUNC_NAME, 'log_del', $id);

		//Resync cats
		$this->resync_cats();

		$this->list_cats();
		return true;
	}

	function delete_childs($parent_id){
		global $DB;

		for ($i=0; $i<$this->cat_count; $i++){
			if ($parent_id == $this->cat_data[$i]['cat_parent_id']){
				$this->delete_childs($this->cat_data[$i]['cat_id']);
			}
		}

		//Select article_id
		$where_sql = " WHERE article_id=0";
		$DB->query("SELECT article_id FROM ". $DB->prefix ."article WHERE cat_id=$parent_id");
		if ( $DB->num_rows() ){
			while ( $result = $DB->fetch_array() ){
				$where_sql .= " OR article_id=". $result["article_id"];
			}
		}
		$DB->free_result();

		//Delete Articles Pages
		$DB->query("DELETE FROM ". $DB->prefix ."article_page_content $where_sql");
		$DB->query("DELETE FROM ". $DB->prefix ."article_page $where_sql");

		//Delete Articles
		$DB->query("DELETE FROM ". $DB->prefix ."article WHERE cat_id=$parent_id");

		//Delete Category
		$DB->query("DELETE FROM ". $DB->prefix ."article_category WHERE cat_id=$parent_id");
	}

	function pre_move_articles($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$Info->tpl_main		= "article_cat_move";

		$this->get_all_cats();
		$this->set_all_cats(0, 0);

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'              => $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=move&page='. $this->page .'&pid='. $this->parent_id),
			"L_PAGE_TITLE"			=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_cat"] . $Lang->data['general_arrow'] . $Lang->data["article_move"],
			'L_BUTTON'				=> $Lang->data['button_move'],
			'L_SOURCE_CATS'         => $Lang->data['article_cat_source'],
			'L_DEST_CAT'            => $Lang->data['article_cat_dest'],
		));
	}

	function do_move_articles(){
		global $Session, $DB, $Template, $Lang, $Func;

		$source_id   = $Func->get_request('source_id', 0, 'POST');
		$dest_id     = intval($Func->get_request('dest_id', 0, 'POST'));

		if ( !$source_id || !$dest_id ){
			$this->pre_move_articles($Lang->data['article_error_cat_source']);
			return false;
		}

		if ( is_array($source_id) ){
			$cat_ids	= $source_id;
		}
		else{
			$cat_ids[0]	= $source_id;
		}

		$ids_info	= $Func->get_array_value($cat_ids);
		if ( sizeof($ids_info) ){
			$DB->query('UPDATE '. $DB->prefix .'article SET cat_id='. $dest_id .' WHERE cat_id IN ('. implode(',', $ids_info) .')');
			$this->resync_cats();
		}

		$this->list_cats();
		return true;
	}
}

$AdminCat = new Admin_Category;
?>