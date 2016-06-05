<?php
/* =============================================================== *\
|		Module name: User Group										|
|		Module version: 1.2											|
|		Begin: 9 May 2004											|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_user_group');
define('FUNC_ACT_VIEW', 'preedit');
//Module language
$Func->import_module_language("admin/lang_user". PHP_EX);

$Admin_Group = new Admin_Group;

class Admin_Group
{
	var $data		= array();
	var $page		= 0;

	var $user_perm		= array();

	function Admin_Group(){
		global $Info, $Func;

		$this->page = isset($_GET['page']) ? intval($_GET['page']) : 1;

		$this->user_perm	= $Func->get_all_perms('menu_user_group');

		switch ($Info->act){
			case "preadd":
					$Func->check_user_perm($this->user_perm, 'add');
					$this->pre_add_group();
					break;
			case "add":
					$Func->check_user_perm($this->user_perm, 'add');
					$this->do_add_group();
					break;
			case "preedit":
					$Func->check_user_perm($this->user_perm, 'edit');
					$this->pre_edit_group();
					break;
			case "edit":
					$Func->check_user_perm($this->user_perm, 'edit');
					$this->do_edit_group();
					break;
			case "premove":
					$Func->check_user_perm($this->user_perm, 'move_article');
					$this->pre_move_users();
					break;
			case "move":
					$Func->check_user_perm($this->user_perm, 'move_article');
					$this->do_move_users();
					break;
			case "resync":
					$Func->check_user_perm($this->user_perm, 'edit');
					$this->resync_groups();
					break;
			case "predel":
					$Func->check_user_perm($this->user_perm, 'del');
					$this->pre_delete_group();
					break;
			case "del":
					$Func->check_user_perm($this->user_perm, 'del');
					$this->do_delete_group();
					break;
			case "preperm":
					$Func->check_user_perm($this->user_perm, 'edit');
					$this->pre_set_perm();
					break;
			case "perm":
					$Func->check_user_perm($this->user_perm, 'edit');
					$this->do_set_perm();
					break;
			default:
					$this->list_groups();
		}
	}

	function list_groups(){
		global $Session, $Func, $Info, $Template, $DB, $Lang;

		$Info->tpl_main		= "user_group_list";
		$itemperpage		= $Info->option['items_per_page'];

		//Generate pages
		$sql_query  = $DB->query("SELECT count(group_id) AS total FROM ".$DB->prefix."user_group");
		if ($DB->num_rows($sql_query)){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=user_group"));
		}
		else{
			$pageshow['page'] = "";
			$pageshow['start'] = 0;
		}
		$DB->free_result($sql_query);

		$sql_query = $DB->query("SELECT * FROM ".$DB->prefix."user_group ORDER BY group_name ASC LIMIT ". $pageshow['start'] .",$itemperpage");
		if ( $DB->num_rows($sql_query) ){
			$i	= 0;
			while ( $result = $DB->fetch_array($sql_query) ){
				$Template->set_block_vars("grouprow",array(
					"ID"				=> $result["group_id"],
					'NAME'				=> $result['group_name'],
					'U_USER_COUNTER'	=> $result['user_counter'] ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=user&fgroup_id='. $result["group_id"]) .'">'. $result['user_counter'] .'</a>' : $result['user_counter'],
					'LEVEL'				=> $result['group_level'] ? $Lang->data['user_group_admin'] : $Lang->data['user_group_normal'],
					'BG_CSS'			=> ($i % 2) ? 'tdtext2' : 'tdtext1',
					"U_DEL"				=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=user_group&act=predel&id='. $result["group_id"] .'&page='. $this->page) .'" onclick="javascript: return deleteGroup('. $result['user_counter'] .');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" border=0 alt="" title="'. $Lang->data['general_del'] .'"></a>' : '&nbsp;',
					'U_EDIT'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=user_group&act=preedit&id='. $result["group_id"] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
					'U_PERM'			=> ( !$result['group_level'] && $Func->check_user_perm($this->user_perm, 'edit', 0)) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=user_group&act=preperm&id='. $result['group_id'] .'&page='. $this->page) .'"><img src="./templates/'. $Info->option['template'] .'/images/admin/perm.gif" border=0 alt="" title="'. $Lang->data['user_group_perm'] .'"></a>' : '&nbsp;',
				));
				$i++;
			}
		}
		$DB->free_result();

		$Template->set_vars(array(
			"PAGE_OUT"				=> $pageshow['page'],
			'U_ADD'					=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=user_group&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="'. $Lang->data['general_add'] .'" border="0" align="absbottom">'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp;' : '',
			'U_MOVE'				=> ( isset($this->user_perm['action']['all']) || isset($this->user_perm['action']['move_user']) ) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=user_group&act=premove') .'"><img src="'. $Info->option['template_path'] .'/images/admin/move.gif" alt="" title="'. $Lang->data["user_move"] .'" border="0" align="absbottom">'. $Lang->data["user_move"] .'</a> &nbsp; &nbsp;' : '',
			'U_RESYNC'				=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=user_group&act=resync') .'"><img src="'. $Info->option['template_path'] .'/images/admin/resync.gif" alt="" title="'. $Lang->data["general_resync"] .'" border="0" align="absbottom">'. $Lang->data["general_resync"] .'</a> &nbsp; &nbsp;' : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_group'],
			"L_GROUP_NAME"			=> $Lang->data["user_group_name"],
			'L_USER_COUNTER'		=> $Lang->data['user_group_users'],
			'L_PERM'				=> $Lang->data['user_group_perm'],
			'L_LEVEL'				=> $Lang->data['user_group_level'],
			"L_DELETE_CONFIRM"		=> $Lang->data["general_del_confirm"],
		));
	}

	function pre_add_group(){
		global $Session, $Template, $DB, $Lang, $Info;

		$Info->tpl_main		= "user_group_edit";

		$Template->set_vars(array(
			'S_ACTION'						=> $Session->append_sid(ACP_INDEX .'?mod=user_group&act=add'),
			"L_PAGE_TITLE"					=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_group'] . $Lang->data['general_arrow'] . $Lang->data['general_add'],
			"L_GROUP_NAME"					=> $Lang->data["user_group_name"],
			"L_GROUP_DESC"					=> $Lang->data["user_group_desc"],
			"L_LEVEL"						=> $Lang->data["user_group_level"],
			"L_GROUP_ADMIN"					=> $Lang->data["user_group_admin"],
			"L_GROUP_NORMAL"				=> $Lang->data["user_group_normal"],
			"L_BUTTON_UPDATE"				=> $Lang->data["button_add"],
			"ERROR_MSG"						=> isset($this->data["error"]) ? $this->data["error"] : '',
			"GROUP_NAME"					=> isset($this->data["name"]) ? stripslashes($this->data["name"]) : '',
			"GROUP_DESC"					=> isset($this->data["desc"]) ? stripslashes($this->data["desc"]) : '',
			"GROUP_LEVEL"					=> isset($this->data["level"]) ? $this->data["level"] : '',
		));
	}

	function do_add_group(){
		global $Session, $Info, $Template, $DB, $Lang, $Func;

		$this->data["name"]		= isset($_POST["group_name"]) ? htmlspecialchars($_POST["group_name"]) : '';
		$this->data["desc"]		= isset($_POST["group_desc"]) ? htmlspecialchars($_POST["group_desc"]) : '';
		$this->data["level"]	= isset($_POST["group_level"]) ? intval($_POST["group_level"]) : 0;

		if ( empty($this->data["name"]) ){
			$this->data["error"] = $Lang->data["general_error_not_full"];
			$this->pre_add_group();
			return false;
		}

		//Does this group exist?
		$DB->query("SELECT group_id FROM ". $DB->prefix ."user_group WHERE group_name='". $this->data["name"] ."'");
		if ($DB->num_rows()){
			$this->data["error"]	= sprintf($Lang->data["user_error_exist_group"], $this->data["name"]);
			$this->pre_add_group();
			return false;
		}

		$DB->query("INSERT INTO ". $DB->prefix ."user_group(group_name, group_desc, group_level, user_counter) VALUES('". $this->data["name"] ."', '". $this->data["desc"]."', ". $this->data['level'] .", 0)");
		$group_id	= $DB->insert_id();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $group_id, ACP_INDEX .'?mod=user_group&act='. FUNC_ACT_VIEW .'&id='. $group_id);

		$this->list_groups();
		return true;
	}

	function pre_edit_group(){
		global $Session, $Template, $DB, $Lang, $Info;

		$id				= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$Info->tpl_main	= "user_group_edit";

		$DB->query("SELECT * FROM ". $DB->prefix ."user_group WHERE group_id=$id");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["user_error_not_exist_group"], $Session->append_sid(ACP_INDEX ."?mod=user_group"));
			return false;
		}
		$group_info = $DB->fetch_array();
		$DB->free_result();

		$Template->set_vars(array(
			'S_ACTION'						=> $Session->append_sid(ACP_INDEX .'?mod=user_group&act=edit&id='. $id),
			"L_PAGE_TITLE"					=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_group'] . $Lang->data['general_arrow'] . $Lang->data['general_edit'],
			"L_GROUP_NAME"					=> $Lang->data["user_group_name"],
			"L_GROUP_DESC"					=> $Lang->data["user_group_desc"],
			"L_LEVEL"						=> $Lang->data["user_group_level"],
			"L_GROUP_ADMIN"					=> $Lang->data["user_group_admin"],
			"L_GROUP_NORMAL"				=> $Lang->data["user_group_normal"],
			"L_BUTTON_UPDATE"				=> $Lang->data["button_edit"],
			"ERROR_MSG"						=> isset($this->data["error"]) ? $this->data["error"] : '',
			"GROUP_NAME"					=> isset($this->data["name"]) ? stripslashes($this->data["name"]) : $group_info['group_name'],
			"GROUP_DESC"					=> isset($this->data["desc"]) ? stripslashes($this->data["desc"]) : $group_info['group_desc'],
			"GROUP_LEVEL"					=> isset($this->data["level"]) ? $this->data["level"] : $group_info['group_level'],
		));
		return true;
	}

	function do_edit_group(){
		global $Session, $Template, $DB, $Lang, $Func;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$this->data["name"]		= isset($_POST["group_name"]) ? htmlspecialchars($_POST["group_name"]) : '';
		$this->data["desc"]		= isset($_POST["group_desc"]) ? htmlspecialchars($_POST["group_desc"]) : '';
		$this->data["level"]	= isset($_POST["group_level"]) ? intval($_POST["group_level"]) : 0;

		if ( empty($this->data["name"]) ){
			$this->data["error"] = $Lang->data["general_error_not_full"];
			$this->pre_edit_group();
			return false;
		}

		//Check groupname existed ?
		$DB->query("SELECT group_id FROM ". $DB->prefix ."user_group WHERE group_name='".$this->data["name"]."' AND group_id!=$id");
		if ( $DB->num_rows() ){
			$this->data["error"]	= sprintf($Lang->data["user_error_exist_group"], $this->data["name"]);
			$this->pre_edit_group();
			return false;
		}

		$DB->query("UPDATE ". $DB->prefix ."user_group SET group_name='". $this->data["name"] ."', group_desc='". $this->data["desc"] ."', group_level=". $this->data['level'] ." WHERE group_id=$id");

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=user_group&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_groups();
		return true;
	}

	function pre_delete_group(){
		global $Session, $Template, $DB, $Info, $Lang;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main	= "user_group_delete";

		//Get group info
		$DB->query('SELECT * FROM '. $DB->prefix .'user_group WHERE group_id='. $id);
		if ( !$DB->num_rows() ){
			$this->list_groups();
			return false;
		}
		$group_info	= $DB->fetch_array();

		if ( !$group_info['user_counter'] ){
			$this->do_delete_group($id);
			return true;
		}

		//Get all groups
		$DB->query('SELECT * FROM '. $DB->prefix .'user_group WHERE group_id!='. $id .' ORDER BY group_name asc');
		$group_count	= $DB->num_rows();
		$group_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$group_count; $i++){
			$Template->set_block_vars("grouprow", array(
				'ID'			=> $group_data[$i]['group_id'],
				'NAME'			=> $group_data[$i]['group_name'],
				'USER_COUNTER'	=> $group_data[$i]['user_counter'],
			));
		}

		$Template->set_vars(array(
			'S_ACTION'						=> $Session->append_sid(ACP_INDEX .'?mod=user_group&act=del&id='. $id),
			"L_PAGE_TITLE"					=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_group'] . $Lang->data['general_arrow'] . $Lang->data['general_del'],
			'L_DELETE_CONFIRM'				=> $Lang->data['user_group_del_confirm'],
			'L_GROUP_NAME'					=> $Lang->data['user_group_name'],
			'L_GROUP_USERS'					=> $Lang->data['user_group_users'],
			'L_DELETE_MOVE'					=> $Lang->data['user_group_del_move'],
			'L_MOVE_USERS'					=> $Lang->data['user_group_move'],
			'L_DELETE_USERS'				=> $Lang->data['user_group_del_users'],
			"L_BUTTON"						=> $Lang->data["button_delete"],
			"ERROR_MSG"						=> isset($this->data["error"]) ? $this->data["error"] : '',
			"GROUP_NAME"					=> $group_info['group_name'],
			"USER_COUNTER"					=> $group_info['user_counter'],
		));

		return true;
	}

	function do_delete_group($id=0){
		global $Session, $Template, $DB, $Lang, $Func;

		if ( !$id ){
			$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		}
		$del_user	= isset($_POST['del_user']) ? intval($_POST['del_user']) : 0;
		$group_id	= isset($_POST['group_id']) ? intval($_POST['group_id']) : '';

		if ( $del_user || !$group_id ){
			//Delete user related
			$DB->query('DELETE FROM '. $DB->prefix .'user_group_ids WHERE group_id='. $id);
			
			$DB->query('SELECT U.user_id, G.group_id FROM '. $DB->prefix .'user AS U LEFT JOIN '. $DB->prefix .'user_group_ids AS G ON U.user_id=G.user_id');
			$user_count	= $DB->num_rows();
			$user_data	= $DB->fetch_all_array();
			
			$where_sql	= "WHERE user_id=0";
			for ($i=0; $i<$user_count; $i++){
				if ( !$user_data[$i]['group_id'] ){
					$where_sql	.= " OR user_id=". $user_data[$i]['user_id'];
				}
			}
			//Delete users
			$DB->query('DELETE FROM '. $DB->prefix .'user '. $where_sql);
		}
		else{
			//Move user to another group
			$DB->query('SELECT * FROM '. $DB->prefix .'user_group_ids WHERE group_id='. $id);
			$user_count	= $DB->num_rows();
			$user_data	= $DB->fetch_all_array();
			
			$where_sql	= "WHERE group_id=$group_id AND (user_id=0";
			for ($i=0; $i<$user_count; $i++){
				$where_sql	.= " OR user_id=". $user_data[$i]['user_id'];
			}
			$where_sql .= ")";
			
			//Delete if exist users in dest group
			$DB->query('DELETE FROM '. $DB->prefix .'user_group_ids '. $where_sql);
			//Update
			$DB->query('UPDATE '. $DB->prefix .'user_group_ids SET group_id='. $group_id .' WHERE group_id='. $id);
		}

		//Delete old group
		$DB->query('DELETE FROM '. $DB->prefix .'user_group WHERE group_id='. $id);

		//Save log
		$Func->save_log(FUNC_NAME, 'log_del', $id, ACP_INDEX .'?mod=user_group&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->resync_groups();
	}

	function pre_move_users(){
		global $Session, $Template, $DB, $Lang, $Info;

		$Info->tpl_main		= "user_group_move";

		//Get all groups
		$DB->query('SELECT * FROM '. $DB->prefix .'user_group ORDER BY group_name asc');
		$group_count	= $DB->num_rows();
		$group_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$group_count; $i++){
			$Template->set_block_vars("grouprow", array(
				'ID'			=> $group_data[$i]['group_id'],
				'NAME'			=> $group_data[$i]['group_name'],
				'USER_COUNTER'	=> $group_data[$i]['user_counter'],
			));
		}

		$Template->set_vars(array(
			'S_ACTION'						=> $Session->append_sid(ACP_INDEX .'?mod=user_group&act=move'),
			"L_PAGE_TITLE"					=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_group'] . $Lang->data['general_arrow'] . $Lang->data['user_move'],
			"L_SOURCE_GROUPS"				=> $Lang->data["user_group_source"],
			"L_DEST_GROUP"					=> $Lang->data["user_group_dest"],
			"L_MOVE_BUTTON"					=> $Lang->data["button_move"],
			"ERROR_MSG"						=> isset($this->data["error"]) ? $this->data["error"] : '',
		));
	}

	function do_move_users(){
		global $Session, $Info, $Template, $DB, $Lang, $Func;

		$source_id		= isset($_POST["source_id"]) ? $_POST["source_id"] : '';
		$dest_id		= isset($_POST["dest_id"]) ? $_POST["dest_id"] : 0;

		if ( $dest_id ){
			if ( is_array($source_id) ){
				$source_ids		= $source_id;
			}
			else{
				$source_ids[]	= $source_id;
			}
			$ids_info	= $Func->get_array_value($source_ids);

			if ( sizeof($ids_info) ){
				$str_ids	= implode(',', $ids_info);

				//Get users
				$DB->query('SELECT * FROM '. $DB->prefix .'user_group_ids WHERE group_id IN ('. $str_ids .')');
				$user_count	= $DB->num_rows();
				$user_data	= $DB->fetch_all_array();
				$DB->free_result();

				for ($i=0; $i<$user_count; $i++){
					$DB->query('SELECT group_id FROM '. $DB->prefix .'user_group_ids WHERE group_id='. $dest_id .' AND user_id='. $user_data[$i]['user_id']);
					if ( !$DB->num_rows() ){
						$DB->query('UPDATE '. $DB->prefix .'user_group_ids SET group_id='. $dest_id .' WHERE group_id='. $user_data[$i]['group_id'] .' AND user_id='. $user_data[$i]['user_id']);
					}
				}

				//Save log
				$Func->save_log(FUNC_NAME, 'log_move', $str_ids);
				$this->resync_groups();
				return false;
			}
		}

		$this->list_groups();
		return true;
	}

	function resync_groups(){
		global $DB, $Template, $Lang, $Func;

		$DB->query('UPDATE '. $DB->prefix .'user_group SET user_counter=0');

		$DB->query('SELECT count(user_id) AS user_counter, group_id FROM '. $DB->prefix .'user_group_ids WHERE group_id>0 GROUP BY group_id');
		$group_count	= $DB->num_rows();
		$group_data		= $DB->fetch_all_array();

		for ($i=0; $i<$group_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'user_group SET user_counter='. $group_data[$i]['user_counter'] .' WHERE group_id='. $group_data[$i]['group_id']);
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_resync');

		$this->list_groups();
	}

	function pre_set_perm(){
		global $Session, $Lang, $DB, $Template, $Info;

		$id		= isset($_GET['id']) ? intval($_GET['id']) : 0;
		$Info->tpl_main	= "user_group_perm";

		$DB->query('SELECT * FROM '. $DB->prefix .'user_group WHERE group_id='. $id);
		if ( !$DB->num_rows() ){
			$this->list_groups();
			return false;
		}
		$group_info	= $DB->fetch_array();
		if ( $group_info['group_level'] ){
			$this->list_groups();
			return false;
		}

		$Template->set_vars(array(
			'GROUP_NAME'			=> sprintf($Lang->data['user_group_set_perm'], $group_info['group_name']),
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=user_group&act=perm&id='. $id),
			"L_PAGE_TITLE"			=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_group'] . $Lang->data['general_arrow'] . $Lang->data['user_group_perm'],
			"L_BUTTON_UPDATE"		=> $Lang->data["button_edit"],
			"L_BUTTON_RESET"		=> $Lang->data["button_reset"],
			"L_FUNCTION"			=> $Lang->data["func_function"],
			"L_ACTION"				=> $Lang->data["func_action"],
			"L_ACTION_TIP"			=> $Lang->data["func_action_tip"],
			"L_ITEM"				=> $Lang->data["func_item"],
			"L_ITEM_TIP"			=> $Lang->data["func_item_tip"],
		));

		$this->list_func_perm($group_info['group_id']);
		return true;
	}

	function list_func_perm($group_id=0){
		global $Lang, $DB, $Template;

		//Get all permission
		$DB->query('SELECT * FROM '. $DB->prefix .'func_auth WHERE ugroup_id='. $group_id);
		$auth_data	= array();
		if ( $DB->num_rows() ){
			while ($result	= $DB->fetch_array()){
				$auth_data[$result['func_code']]['actions']	= $result['allow_actions'];
				$auth_data[$result['func_code']]['items']	= $result['allow_items'];
			}
		}

		//Get admin function groups
		$DB->query('SELECT * FROM '. $DB->prefix .'func_group ORDER BY fgroup_order ASC');
		$group_count = $DB->num_rows();
		$group_data  = $DB->fetch_all_array();
		$DB->free_result();

		//Get admin functions
		$DB->query('SELECT * FROM '. $DB->prefix .'func WHERE func_allow_all=0 ORDER BY func_order ASC');
		$func_count = $DB->num_rows();
		$func_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$group_count; $i++){
			$flag = false;
			for ($j=0; $j<$func_count; $j++){
				if ( $func_data[$j]['fgroup_id'] == $group_data[$i]['fgroup_id']){
					$flag = true;
					break;
				}
			}
			if ( !$flag ) continue;

			$Template->set_block_vars("fgrouprow", array(
				'NAME'         => $Lang->data[$group_data[$i]['fgroup_name']],
			));

			for ($j=0; $j<$func_count; $j++){
				if ( $func_data[$j]['fgroup_id'] == $group_data[$i]['fgroup_id']){
					$allow_actions	= isset($auth_data[$func_data[$j]['func_code']]['actions']) ? ','. $auth_data[$func_data[$j]['func_code']]['actions'] .',' : '';
					$allow_items	= isset($auth_data[$func_data[$j]['func_code']]['items']) ? ','. $auth_data[$func_data[$j]['func_code']]['items'] .',' : '';

					$Template->set_block_vars("fgrouprow:funcrow",array(
						'ID'			=> $func_data[$j]['func_id'],
						'NAME'			=> $Lang->data[$func_data[$j]['func_name']],
						'ACTIONS'		=> $this->convert_actions($func_data[$j]['func_code'], $func_data[$j]['func_actions'], $allow_actions),
						'ITEMS'			=> $this->convert_items($func_data[$j]['func_code'], $func_data[$j]['func_items'], $allow_items),
					));
				}
			}
		}
	}

	function convert_actions($func_code, $func_actions, $allow_actions){
		global $Lang;

		if ( empty($func_actions) ) return '';

		$act_string	= '';
		$actions	= explode(',', $func_actions);

		reset($actions);
		while (list(, $act) = each($actions)){
			$checked	= (strpos($allow_actions, ",$act,") !== false) ? 'checked' : '';
			if ($act == 'all'){
				$act_string	.= '<input type="checkbox" name="action_'. $func_code .'[]" value="'. $act .'" '. $checked .'><strong>'. $Lang->data['user_perm_'. $act] .'</strong><br>';
			}
			else{
				$act_string	.= '<input type="checkbox" name="action_'. $func_code .'[]" value="'. $act .'" '. $checked .'>'. $Lang->data['user_perm_'. $act] .'<br>';
			}
		}
		
		return $act_string;
	}

	function convert_items($func_code, $func_items, $allow_items){
		global $Lang;

		if ( empty($func_items) ) return '';

		$items_string	= '';
		$items	= explode(',', $func_items);
		reset($items);
		while (list(, $item) = each($items)){
			$checked	= (strpos($allow_items, ",$item,") !== false) ? 'checked' : '';
			if ($item == 'all'){
				$items_string	.= '<input type="checkbox" name="item_'. $func_code .'[]" value="'. $item .'" '. $checked .'><strong>'. $Lang->data['user_perm_'. $item] .'</strong><br>';
			}
			else{
				$items_string	.= '<input type="checkbox" name="item_'. $func_code .'[]" value="'. $item .'" '. $checked .'>'. $Lang->data['user_perm_'. $item] .'<br>';
			}
		}
		
		return $items_string;
	}

	function do_set_perm(){
		global $DB, $Template, $Lang, $Info, $Func;

		$id	= isset($_GET['id']) ? intval($_GET['id']) : 0;

		//Get admin functions
		$DB->query('SELECT * FROM '. $DB->prefix .'func WHERE func_allow_all=0 ORDER BY func_order ASC');
		$func_count = $DB->num_rows();
		$func_data  = $DB->fetch_all_array();
		$DB->free_result();

		//Delete old permission
		$DB->query('DELETE FROM '. $DB->prefix .'func_auth WHERE ugroup_id='. $id);

		for ($i=0; $i<$func_count; $i++){
			//Get permission
			$action	= isset($_POST['action_'. $func_data[$i]['func_code']]) ? $_POST['action_'. $func_data[$i]['func_code']] : '';
			$item	= isset($_POST['item_'. $func_data[$i]['func_code']]) ? $_POST['item_'. $func_data[$i]['func_code']] : '';

			if (empty($action) && empty($item)) continue;

			if ( !empty($action) ){
				if ( is_array($action) ){
					$act_string	= implode(',', $action);
				}
				else{
					$act_string	= $action;
				}
				$act_string		= htmlspecialchars(str_replace(',,', ',', $act_string));
			}
			else{
				$act_string	= "";
			}

			if ( !empty($item) ){
				if ( is_array($item) ){
					$item_string	= implode(',', $item);
				}
				else{
					$item_string	= $item;
				}
				$item_string	= htmlspecialchars(str_replace(',,', ',', $item_string));
			}
			else{
				$item_string	= "";
			}

			//Insert new permission
			$DB->query("INSERT INTO ". $DB->prefix ."func_auth(ugroup_id, func_code, allow_actions, allow_items) VALUES($id, '". $func_data[$i]['func_code'] ."', '". $act_string ."', '". $item_string ."')");

			//Save log
			$Func->save_log(FUNC_NAME, 'log_set_perm', $id);
		}

		$this->list_groups();
	}
}

?>