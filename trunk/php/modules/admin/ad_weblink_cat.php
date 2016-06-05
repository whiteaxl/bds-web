<?php
/* =============================================================== *\
|		Module name: Web Link Category								|
|		Module version: 1.1											|
|		Begin: 25 May 2006											|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_weblink_cat');
define('FUNC_ACT_VIEW', 'preedit');
define('ALLOW_SUBCATS', false);
//Module language
$Func->import_module_language("admin/lang_weblink". PHP_EX);

$AdminCat = new Admin_Category;

class Admin_Category
{
	var $page			= 1;
	var $parent_id		= 0;
	var $cat_count		= 0;
	var $cat_data		= array();
	var $data			= array();
	var	$view_mode		= "";

	var $user_perm		= array();

	function Admin_Category(){
		global $Info, $Func, $Cache, $Template;

		$this->view_mode	= isset($_POST["view_mode"]) ? htmlspecialchars($_POST["view_mode"]) : '';
		if ( empty($this->view_mode) ){
			$this->view_mode	= isset($_GET["view_mode"]) ? htmlspecialchars($_GET["view_mode"]) : '';
		}

		$this->parent_id	= isset($_GET["pid"]) ? intval($_GET["pid"]) : 0;
		$this->page			= isset($_GET['page']) ? intval($_GET['page']) : 1;

		$this->user_perm	= $Func->get_all_perms('menu_weblink_cat');

		//Sub categories
		if ( ALLOW_SUBCATS ){
			$Template->set_block_vars("allow_subcats", array());
		}

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
				$Func->check_user_perm($this->user_perm, 'move_weblink');
				$this->pre_move_weblinks();
				break;
			case "move":
				$Func->check_user_perm($this->user_perm, 'move_weblink');
				$Cache->clear_cache('all');
				$this->do_move_weblinks();
				break;
			default:
				$this->list_cats();
		}
	}

	function list_cats(){
		global $Session, $Func, $Info, $Lang, $Template, $DB;

		$Info->tpl_main	= "weblink_cat_list";

		if ( $this->view_mode == 'expand'){
			$this->get_all_cats();
			$this->set_all_cats(0, 0, 0);
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
			$DB->query("SELECT count(*) AS total FROM ". $DB->prefix ."weblink_category WHERE cat_parent_id=". $this->parent_id);
			if ( $DB->num_rows() ){
				$result		= $DB->fetch_array();
				$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=weblink_cat&pid=". $this->parent_id));
			}
			else{
				$pageshow['page']	= "";
				$pageshow['start']	= 0;
			}
			$DB->free_result();

			$DB->query("SELECT * FROM ". $DB->prefix ."weblink_category WHERE cat_parent_id=". $this->parent_id ." ORDER BY cat_order ASC LIMIT ". $pageshow['start'] .",". $itemperpage);
			if ( $DB->num_rows() ){
				$i	= 0;
				while ($result = $DB->fetch_array()){
					$counter	= ($result['weblink_counter'] || $result['children_counter'])  ?  'false' : 'true';
					$css		= ($result["enabled"] == SYS_DISABLED) ? "disabled" : "enabled";
					$Template->set_block_vars("catrow", array(
						'PREFIX'			=> '',
						'ID'				=> $result['cat_id'],
						'NAME'				=> $result['children_counter'] ? '<a class="'. $css .'" href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&pid='. $result['cat_id'] .'&page='. $this->page) .'">'. $result['cat_name'] .'</a>' : $result['cat_name'],
						'ORDER'				=> $result['cat_order'],
						'WEBLINK_COUNTER'	=> $result['weblink_counter'] ? '<a class="'. $css .'" href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink&fcat_id='. $result['cat_id']) .'">'. $result['weblink_counter'] .'</a>' : $result['weblink_counter'],
						'CHILDREN_COUNTER'	=> $result['children_counter'] ? '<a class="'. $css .'" href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&pid='. $result['cat_id'] .'&page='. $this->page) .'">'. $result['children_counter'] .'</a>' : $result['children_counter'],
						"CSS"				=> $css,
						'BG_CSS'			=> ($i % 2) ? 'tdtext2' : 'tdtext1',
						'U_VIEW'			=> $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&pid='. $result['cat_id'] .'&page='. $this->page),
						'U_EDIT'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&act=preedit&id='. $result['cat_id'] .'&pid='. $this->parent_id .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
						'U_DEL'				=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&act=predel&id='. $result['cat_id'] .'&pid='. $this->parent_id .'&page='. $this->page) .'" onclick="javascript: if ('. $counter .'){ return del_confirm(\''. $Lang->data['general_del_confirm'] .'\');}"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" border=0 alt="" title="'. $Lang->data['general_del'] .'"></a>' : '&nbsp;',
					));
					$i++;
				}
			}
		}

		$Template->set_vars(array(
			"PAGE_OUT"					=> isset($pageshow['page']) ? $pageshow['page'] : '',
			'VIEW_MODE'					=> $this->view_mode,
			'S_MODE_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=weblink_cat'),
			'U_ADD'						=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&act=preadd&pid='. $this->parent_id) .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="'. $Lang->data['general_add'] .'" border="0" align="absbottom">'. $Lang->data['general_add'] .'</a>' : '',
			'U_UPDATE'					=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="javascript: updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&act=update&pid='. $this->parent_id .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/order.gif" alt="" title="'. $Lang->data['general_update'] .'" border="0" align="absbottom">'. $Lang->data['general_update'] .'</a> &nbsp; &nbsp;' : '',
			'U_RESYNC'					=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&act=resync&pid='. $this->parent_id .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/resync.gif" alt="" title="'. $Lang->data['general_resync'] .'" border="0" align="absbottom">'. $Lang->data['general_resync'] .'</a> &nbsp; &nbsp;' : '',
			'U_MOVE'					=> $Func->check_user_perm($this->user_perm, 'move_weblink', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&act=premove&page='. $this->page .'&pid='. $this->parent_id) .'"><img src="'. $Info->option['template_path'] .'/images/admin/move.gif" alt="" title="'. $Lang->data["weblink_move"] .'" border="0" align="absbottom">'. $Lang->data["weblink_move"] .'</a>' : '',
			'U_LIST_CAT'				=> $Session->append_sid(ACP_INDEX .'?mod=weblink_cat'),
			"L_PAGE_TITLE"				=> $Lang->data["menu_weblink"] . $Lang->data['general_arrow'] . $Lang->data["menu_weblink_cat"],
			"L_ORDER"					=> $Lang->data["general_order"],
			"L_NAME"					=> $Lang->data["general_cat_name"],
			"L_CHILDS"					=> $Lang->data["weblink_cat_childs"],
			"L_WEBLINKS"				=> $Lang->data["weblinks"],
			"L_NORMAL"					=> $Lang->data["general_normal"],
			"L_EXPAND"					=> $Lang->data["general_expand"],
		));
	}

	function get_parent_names($cat_id, &$str){
		global $Session;

		for ($i=0; $i<$this->cat_count; $i++){
			if ( $cat_id == $this->cat_data[$i]['cat_id'] ){
				$this->get_parent_names($this->cat_data[$i]['cat_parent_id'],  $str);
				$str .= '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&pid='. $cat_id) .'">'. $this->cat_data[$i]['cat_name'] .'</a>/ ';
			}
		}
	}

	function get_all_cats(){
		global $DB;

		$DB->query("SELECT * FROM ". $DB->prefix ."weblink_category ORDER BY cat_order ASC");
		$this->cat_count = $DB->num_rows();
		$this->cat_data  = $DB->fetch_all_array();
		$DB->free_result();
	}

	function set_all_cats($parent_id, $except_cid, $level=0, $symbol="|-- ", $prefix="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"){
		global $Session, $Template, $Func, $Info, $Lang;

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
				$counter	= ($this->cat_data[$i]['weblink_counter'] || $this->cat_data[$i]['children_counter'])  ?  'true' : 'false';
				$Template->set_block_vars("catrow",array(
					'ID'				=> $this->cat_data[$i]['cat_id'],
					'ORDER'				=> $this->cat_data[$i]['cat_order'],
					'NAME'				=> $this->cat_data[$i]['cat_name'],
					'WEBLINK_COUNTER'	=> $this->cat_data[$i]['weblink_counter'],
					'SUBCAT_COUNTER'	=> $this->cat_data[$i]['weblink_counter'],
					'PREFIX'			=> $str_prefix .$symbol,
					"CSS"				=> ($this->cat_data[$i]["enabled"] == SYS_ENABLED) ? "enabled" : "disabled",
					'U_EDIT'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&act=preedit&id='. $this->cat_data[$i]['cat_id'] .'&view_mode='. $this->view_mode) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
					'U_DEL'				=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&act=predel&id='. $this->cat_data[$i]['cat_id'] .'&view_mode='. $this->view_mode) .'" onclick="javascript: if ('. $counter .'){ return del_confirm(\''. $Lang->data['general_del_confirm'] .'\');}"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" border=0 alt="" title="'. $Lang->data['general_del'] .'"></a>' : '&nbsp;',
				));
				$this->set_all_cats($this->cat_data[$i]['cat_id'], $except_cid, $level+1, $symbol, $prefix);
			}
		}
	}

	function pre_add_cat($msg = ""){
		global $Session, $Template, $Info, $Lang;

		$Info->tpl_main	= "weblink_cat_edit";
		$this->set_lang();

		$this->get_all_cats();
		$this->set_all_cats(0, 0, 0);

		$Template->set_block_vars("addrow", array());
		$Template->set_vars(array(
			"ERROR_MSG"					=> $msg,
			'S_ACTION'					=> $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&act=add'),
			"L_PAGE_TITLE"				=> $Lang->data["menu_weblink"] . $Lang->data['general_arrow'] . $Lang->data["menu_weblink_cat"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			'L_BUTTON'					=> $Lang->data['button_add'],
			"PARENT_ID"					=> isset($this->data["parent_id"]) ? $this->data["parent_id"] : ($this->parent_id ? $this->parent_id : 0),
			"CAT_NAME"					=> isset($this->data["name"]) ? stripslashes($this->data["name"]) : '',
			"CAT_DESC"					=> isset($this->data["desc"]) ? stripslashes($this->data["desc"]) : '',
			"ENABLED"					=> isset($this->data["enabled"]) ? $this->data["enabled"] : '',
			"PAGETO"					=> isset($this->data["page_to"]) ? $this->data["page_to"] : '',
		));
	}

	function set_lang(){
		global $Template, $Lang;

		$Template->set_vars(array(
			'L_ROOT'					=> $Lang->data['general_cat_root'],
			'L_PARENT_CAT'				=> $Lang->data['general_cat_parent'],
			'L_CAT_NAME'				=> $Lang->data['general_cat_name'],
			'L_CAT_DESC'				=> $Lang->data['weblink_cat_desc'],
			"L_PAGE_TO"					=> $Lang->data["general_page_to"],
			"L_PAGE_ADD"				=> $Lang->data["general_page_add"],
			"L_PAGE_LIST"				=> $Lang->data["general_page_list"],
		));
	}

	function do_add_cat(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$this->data["parent_id"]	= isset($_POST["parent_id"]) ? intval($_POST["parent_id"]) : 0;
		$this->data["name"]			= isset($_POST["cat_name"]) ? htmlspecialchars($_POST["cat_name"]) : '';
		$this->data["desc"]			= isset($_POST["cat_desc"]) ? htmlspecialchars($_POST["cat_desc"]) : '';
		$this->data["enabled"]		= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;
		$this->data['page_to']		= isset($_POST["page_to"]) ? htmlspecialchars($_POST["page_to"]) : '';

		if ( empty($this->data["name"]) ){
			$this->pre_add_cat($Lang->data["general_error_not_full"]);
			return false;
		}

		//Check exist
		$DB->query("SELECT cat_id FROM ". $DB->prefix ."weblink_category WHERE cat_name='". $this->data['name'] ."'");
		if ( $DB->num_rows() ){
			$this->pre_add_cat($Lang->data["weblink_error_cat_exist"]);
			return false;
		}

		//Get max order
		$DB->query("SELECT max(cat_order) AS max_order FROM ". $DB->prefix ."weblink_category WHERE cat_parent_id=". $this->data["parent_id"]);
		if ($DB->num_rows()){
			$result					= $DB->fetch_array();
			$this->data["order"]	= $result["max_order"] + 1;
		}
		else{
			$this->data["order"] = 1;
		}
		$DB->free_result();

		$sql = "INSERT INTO ". $DB->prefix ."weblink_category(cat_parent_id, cat_name, cat_desc, cat_order, children_counter, weblink_counter, enabled)
					VALUES(". $this->data["parent_id"] .", '". $this->data["name"]."', '". $this->data["desc"] ."', ". $this->data["order"] .", 0, 0, ". $this->data['enabled'].")";
		$DB->query($sql);
		$cat_id		= $DB->insert_id();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $cat_id, ACP_INDEX .'?mod=weblink_cat&act='. FUNC_ACT_VIEW .'&id='. $cat_id);

		if ($this->data['parent_id']){
			//Update counter of parent
			$DB->query('UPDATE '. $DB->prefix .'weblink_category SET children_counter=children_counter+1 WHERE cat_id='. $this->data['parent_id']);
		}

		if ( $this->data['page_to'] == 'pageadd' ){
			//Reset data
			$this->data['name']		= "";
			$this->data['desc']		= "";
			
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
		$Info->tpl_main	= "weblink_cat_edit";

		$DB->query("SELECT * FROM ". $DB->prefix ."weblink_category WHERE cat_id=$id");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["weblink_error_cat_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=weblink_cat&pid=". $this->parent_id ."&page=". $this->page));
			return false;
		}
		$cat_info = $DB->fetch_array();
		$DB->free_result();

		$this->get_all_cats();
		$this->set_all_cats(0, $id, 0);
		$this->set_lang();

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&act=edit&pid='. $this->parent_id .'&id='. $id .'&page='. $this->page .'&view_mode='. $this->view_mode),
			"L_PAGE_TITLE"			=> $Lang->data["menu_weblink"] . $Lang->data['general_arrow'] . $Lang->data["menu_weblink_cat"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			'L_BUTTON'				=> $Lang->data['button_edit'],
			'PARENT_ID'				=> isset($this->data['parent_id']) ? $this->data['parent_id'] : $cat_info['cat_parent_id'],
			'CAT_NAME'				=> isset($this->data['name']) ? stripslashes($this->data['name']) : $cat_info['cat_name'],
			'CAT_DESC'				=> isset($this->data['desc']) ? stripslashes($this->data['desc']) : $cat_info['cat_desc'],
			'ENABLED'				=> isset($this->data['enabled']) ? $this->data['enabled'] : $cat_info['enabled'],
		));
		return true;
	}

	function do_edit_cat(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$this->data["parent_id"]	= isset($_POST["parent_id"]) ? intval($_POST["parent_id"]) : 0;
		$this->data["name"]			= isset($_POST["cat_name"]) ? htmlspecialchars($_POST["cat_name"]) : '';
		$this->data["desc"]			= isset($_POST["cat_desc"]) ? htmlspecialchars($_POST["cat_desc"]) : '';
		$this->data["enabled"]		= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;

		if ( empty($this->data["name"]) ){
			$this->pre_edit_cat($Lang->data["weblink_error_cat_not_exist"]);
			return false;
		}

		//Check existing
		$DB->query("SELECT * FROM ". $DB->prefix ."weblink_category WHERE cat_name='". $this->data['name'] ."' AND cat_id!=". $id);
		if ( $DB->num_rows() ){
			$this->pre_edit_cat($Lang->data["weblink_error_cat_exist"]);
			return false;
		}

		//Get old info
		$DB->query("SELECT * FROM ". $DB->prefix ."weblink_category WHERE cat_id=". $id);
		if ( !$DB->num_rows() ){
			$this->list_cats();
			return false;
		}
		$cat_info	= $DB->fetch_array();

		//Update info
		$DB->query("UPDATE ". $DB->prefix ."weblink_category SET cat_parent_id=". $this->data["parent_id"] .", cat_name='". $this->data["name"] ."', cat_desc='". $this->data["desc"] ."', enabled=". $this->data["enabled"] ." WHERE cat_id=$id");

		if ($this->data['parent_id'] != $cat_info['cat_parent_id']){
			if ( $cat_info['cat_parent_id'] > 0 ){
				//Update counter of old parent
				$DB->query('UPDATE '. $DB->prefix .'weblink_category SET children_counter=children_counter-1 WHERE cat_id='. $cat_info['cat_parent_id']);
			}
			if ( $this->data['parent_id'] > 0 ){
				//Update counter of new parent
				$DB->query('UPDATE '. $DB->prefix .'weblink_category SET children_counter=children_counter+1 WHERE cat_id='. $this->data['parent_id']);
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=weblink_cat&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_cats();
		return true;
	}

	function update_cats(){
		global $Session, $DB, $Template, $Lang, $Func;

		$cat_orders		= isset($_POST["cat_orders"]) ? $_POST["cat_orders"] : '';

		if ( is_array($cat_orders) ){
			reset($cat_orders);
			while (list($id, $num) = each($cat_orders)){
				$DB->query("UPDATE ". $DB->prefix ."weblink_category SET cat_order=". intval($num) ." WHERE cat_id=". intval($id));
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_update');

		$this->list_cats();
	}

	function resync_cats(){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$DB->query('UPDATE '. $DB->prefix .'weblink_category SET weblink_counter=0, children_counter=0');

		//Update weblink_counter
		$DB->query('SELECT count(weblink_id) AS counter, cat_id FROM '. $DB->prefix.'weblink GROUP BY cat_id');
		$cat_count = $DB->num_rows();
		$cat_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$cat_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'weblink_category SET weblink_counter='. $cat_data[$i]['counter'] .' WHERE cat_id='. $cat_data[$i]['cat_id']);
		}

		//Update children_counter
		$DB->query('SELECT count(cat_id) AS counter, cat_parent_id FROM '. $DB->prefix .'weblink_category WHERE cat_parent_id>0 GROUP BY cat_parent_id');
		$parent_count = $DB->num_rows();
		$parent_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$parent_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'weblink_category SET children_counter='. $parent_data[$i]['counter'] .' WHERE cat_id='. $parent_data[$i]['cat_parent_id']);
		}

		//Update cat_order
		$DB->query('SELECT * FROM '. $DB->prefix.'weblink_category ORDER BY cat_order ASC');
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
			$DB->query('UPDATE '. $DB->prefix .'weblink_category SET cat_order='. $order_arr[$parent_id] .' WHERE cat_id='. $cat_data[$i]['cat_id']);
		}

		//Save log
//		$Func->save_log(FUNC_NAME, 'log_resync');
	}

	function pre_delete_cat(){
		global $Session, $Info, $DB, $Template, $Lang;

		$id				= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$Info->tpl_main	= "weblink_cat_delete";

		//Get cat info
		$DB->query('SELECT * FROM '. $DB->prefix .'weblink_category WHERE cat_id='. $id);
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data['weblink_error_cat_not_exist'], $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&pid='. $this->parent_id .'&page='. $this->page));
			return false;
		}
		$cat_info = $DB->fetch_array();

		if ( !$cat_info['weblink_counter'] && !$cat_info['children_counter'] ){
			$this->do_delete_cat();
			return true;
		}

		if ($cat_info['weblink_counter']){
			$Template->set_block_vars("haveweblink", array());
		}
		if ($cat_info['children_counter']){
			$Template->set_block_vars("havechild", array());
		}

		$this->get_all_cats();
		$this->set_all_cats(0, $id);

		$Template->set_vars(array(
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&act=del&id='. $id .'&pid='. $this->parent_id .'&page='. $this->page),
			"L_PAGE_TITLE"			=> $Lang->data["menu_weblink"] . $Lang->data['general_arrow'] . $Lang->data["menu_weblink_cat"] . $Lang->data['general_arrow'] . $Lang->data["general_del"],
			'L_BUTTON'				=> $Lang->data['button_delete'],
			'L_CAT_NAME'			=> $Lang->data['general_cat_name'],
			'L_CHILDS'				=> $Lang->data['weblink_cat_childs'],
			'L_WEBLINKS'			=> $Lang->data['weblinks'],
			'L_DELETE_SUBCATS'		=> $Lang->data['weblink_cat_del_childs'],
			'L_DELETE_WEBLINKS'		=> $Lang->data['weblink_cat_del_links'],
			'L_MOVE_TO'				=> $Lang->data['weblink_cat_move_to'],
			'CAT_NAME'				=> $cat_info['cat_name'],
			'WEBLINK_COUNTER'		=> $cat_info['weblink_counter'],
			'CHILDREN_COUNTER'		=> $cat_info['children_counter'],
		));

		return true;
	}

	function do_delete_cat(){
		global $Session, $DB, $Template, $Lang, $Func;

		$id				= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$del_child		= isset($_POST["del_child"]) ? intval($_POST["del_child"]) : 0;
		$del_weblink	= isset($_POST["del_weblink"]) ? intval($_POST["del_weblink"]) : 0;
		$cat_dest_id	= isset($_POST["cat_dest_id"]) ? intval($_POST["cat_dest_id"]) : 0;
		$weblink_dest_id	= isset($_POST["weblink_dest_id"]) ? intval($_POST["weblink_dest_id"]) : 0;

		//Get cat info
		$DB->query('SELECT * FROM '. $DB->prefix .'weblink_category WHERE cat_id='. $id);
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
				$DB->query('SELECT count(cat_id) AS counter FROM '. $DB->prefix .'weblink_category WHERE cat_parent_id='. $id);
				if ( $DB->num_rows()){
					$tmp_info = $DB->fetch_array();
					$DB->query('UPDATE '. $DB->prefix .'weblink_category SET children_counter=children_counter+'. $tmp_info['counter'] .' WHERE cat_id='. $cat_dest_id);
				}
				
				//Move sub category
				$DB->query('UPDATE '. $DB->prefix .'weblink_category SET cat_parent_id='. $cat_dest_id .' WHERE cat_parent_id='. $id);
			}
		}

		if ( $cat_info['weblink_counter'] ){
			if ($del_weblink || !$weblink_dest_id){
				$DB->query('DELETE FROM '. $DB->prefix .'weblink WHERE cat_id='. $id);
			}
			else{
				//Move weblinks
				$DB->query('UPDATE '. $DB->prefix .'weblink SET cat_id='. $weblink_dest_id .' WHERE cat_id='. $id);
			}
		}

		//Delete current cat
		$DB->query('DELETE FROM '. $DB->prefix .'weblink_category WHERE cat_id='. $id);

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

		//Select weblink_id
		$where_sql = " WHERE weblink_id=0";
		$DB->query("SELECT weblink_id FROM ". $DB->prefix ."weblink WHERE cat_id=$parent_id");
		if ( $DB->num_rows() ){
			while ( $result = $DB->fetch_array() ){
				$where_sql .= " OR weblink_id=". $result["weblink_id"];
			}
		}
		$DB->free_result();

		//Delete Weblinks
		$DB->query("DELETE FROM ". $DB->prefix ."weblink WHERE cat_id=$parent_id");

		//Delete Category
		$DB->query("DELETE FROM ". $DB->prefix ."weblink_category WHERE cat_id=$parent_id");
	}

	function pre_move_weblinks($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$Info->tpl_main		= "weblink_cat_move";

		$this->get_all_cats();
		$this->set_all_cats(0, 0);

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'              => $Session->append_sid(ACP_INDEX .'?mod=weblink_cat&act=move&page='. $this->page .'&pid='. $this->parent_id),
			"L_PAGE_TITLE"			=> $Lang->data["menu_weblink"] . $Lang->data['general_arrow'] . $Lang->data["menu_weblink_cat"] . $Lang->data['general_arrow'] . $Lang->data["weblink_move"],
			'L_BUTTON'				=> $Lang->data['button_move'],
			'L_SOURCE_CATS'         => $Lang->data['weblink_cat_source'],
			'L_DEST_CAT'            => $Lang->data['weblink_cat_dest'],
		));
	}

	function do_move_weblinks(){
		global $Session, $DB, $Template, $Lang, $Func;

		$source_id   = isset($_POST['source_id']) ? $_POST['source_id'] : 0;
		$dest_id     = isset($_POST['dest_id']) ? intval($_POST['dest_id']) : 0;

		if ( !$source_id || !$dest_id ){
			$this->pre_move_weblinks($Lang->data['weblink_error_cat_source']);
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
			$DB->query('UPDATE '. $DB->prefix .'weblink SET cat_id='. $dest_id .' WHERE cat_id IN ('. implode(',', $ids_info) .')');
			$this->resync_cats();
		}
		$this->list_cats();
		return true;
	}
}
?>