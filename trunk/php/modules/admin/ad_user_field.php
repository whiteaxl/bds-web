<?php
/* =============================================================== *\
|		Module name: User Fields									|
|		Module version: 1.2											|
|		Begin: 10 May 2004											|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_user_field');
define('FUNC_ACT_VIEW', 'preedit');
//Module language
$Func->import_module_language("admin/lang_user". PHP_EX);

$Field = new Field;

class Field
{
	var $data			= array();
	var $page			= 1;

	var $user_perm		= array();

	function Field(){
		global $Info, $Func;

		$this->page			= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->user_perm	= $Func->get_all_perms('menu_user_field');

		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_field();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->do_add_field();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_field();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->do_edit_field();
				break;
			case "predel":
				$Func->check_user_perm($this->user_perm, 'del');
				$this->pre_delete_field();
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$this->do_delete_field();
				break;
			case "update":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->update_fields();
				break;
			default:
				$this->list_fields();
		}
	}

	function list_fields(){
		global $Session, $Func, $Info, $DB, $Template, $Lang;

		$itemperpage	= $Info->option['items_per_page'];
		$Info->tpl_main	= "user_field_list";

		//Generate pages
		$DB->query("SELECT count(field_id) AS total FROM ". $DB->prefix ."user_field");
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=user_field"));
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		//Get list
		$DB->query("SELECT * FROM ". $DB->prefix ."user_field ORDER BY field_order ASC LIMIT ". $pageshow['start'] .",$itemperpage");
		if ($DB->num_rows()){
			$yesno[0] = $Lang->data["general_no"];
			$yesno[1] = $Lang->data["general_yes"];
			$i	= 0;
			while ($result = $DB->fetch_array()){
				$Template->set_block_vars("fieldrow",array(
					"ID"			=> $result["field_id"],
					"TITLE"			=> $result["field_title"],
					"TYPE"			=> $Lang->data['field_'. $result["field_type"]],
					"ORDER"			=> $result["field_order"],
					"REQUIRED"		=> $yesno[$result["field_required"]],
					'BG_CSS'		=> ($i % 2) ? 'tdtext2' : 'tdtext1',
					'U_EDIT'		=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=user_field&act=preedit&id='. $result["field_id"] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
					'U_DEL'			=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=user_field&act=predel&id='. $result["field_id"] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" border=0 alt="" title="'. $Lang->data['general_del'] .'"></a>' : '&nbsp;',
				));
				$i++;
			}
		}
		$DB->free_result();

		$Template->set_vars(array(
			"PAGE_OUT"						=> $pageshow['page'],
			'U_ADD'							=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=user_field&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="'. $Lang->data['general_add'] .'" border="0" align="absbottom">'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp;' : '',
			'U_UPDATE'						=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="javascript: updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=user_field&act=update&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/order.gif" alt="" title="'. $Lang->data['general_update'] .'" border="0" align="absbottom">'. $Lang->data['general_update'] .'</a> &nbsp; &nbsp;' : '',
			"L_PAGE_TITLE"					=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_field'],
			"L_ORDER"						=> $Lang->data["general_order"],
			"L_TITLE"						=> $Lang->data["field_title"],
			"L_TYPE"						=> $Lang->data["field_type"],
			"L_REQUIRED"					=> $Lang->data["field_required"],
		));
	}

	function pre_add_field($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$Info->tpl_main	= "user_field_edit";
		$this->set_lang();

		$Template->set_vars(array(
			"ERROR_MSG"						=> $msg,
			'S_ACTION'						=> $Session->append_sid(ACP_INDEX .'?mod=user_field&act=add'),
			"FTITLE"						=> isset($this->data["title"]) ? $this->data["title"] : '',
			"FDESC"							=> isset($this->data["desc"]) ? $this->data["desc"] : '',
			"FTYPE"							=> isset($this->data["type"]) ? $this->data["type"] : '',
			"FSIZE"							=> (isset($this->data["size"]) && $this->data["size"]) ? $this->data["size"] : '',
			"FMAXINPUT"						=> (isset($this->data["maxinput"]) && $this->data["maxinput"]) ? $this->data["maxinput"] : '',
			"FCONTENT"						=> isset($this->data["content"]) ? $this->data["content"] : '',
			"FREQUIRED"						=> isset($this->data["required"]) ? $this->data["required"] : '',
			"L_PAGE_TITLE"					=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_field'] . $Lang->data['general_arrow'] . $Lang->data['general_add'],
			"L_BUTTON"						=> $Lang->data["button_add"],
		));
	}

	function set_lang(){
		global $Template, $Lang;

		$Template->set_vars(array(
			"L_TEXTINPUT"					=> $Lang->data["field_textinput"],
			"L_TEXTAREA"					=> $Lang->data["field_textarea"],
			"L_DROPDOWN"					=> $Lang->data["field_dropdown"],
			"L_FIELD_TITLE"					=> $Lang->data["field_title"],
			"L_FIELD_DESC"					=> $Lang->data["field_desc"],
			"L_FIELD_CONTENT"				=> $Lang->data["field_content"],
			"L_FIELD_CONTENT_DESC"			=> $Lang->data["field_content_desc"],
			"L_FIELD_TYPE"					=> $Lang->data["field_type"],
			"L_FIELD_SIZE"					=> $Lang->data["field_size"],
			"L_FIELD_SIZE_DESC"				=> $Lang->data["field_size_desc"],
			"L_MAX_INPUT"					=> $Lang->data["field_max_input"],
			"L_MAX_INPUT_DESC"				=> $Lang->data["field_max_input_desc"],
			"L_FIELD_REQUIRED"				=> $Lang->data["field_required"],
			"L_FIELD_REQUIRED_DESC"			=> $Lang->data["field_required_desc"],
			"L_YES"							=> $Lang->data["general_yes"],
			"L_NO"							=> $Lang->data["general_no"],
		));
	}

	function do_add_field(){
		global $Session, $DB, $Info, $Template, $Lang, $Func;

		$this->data["title"]		= isset($_POST["ftitle"]) ? htmlspecialchars($_POST["ftitle"]) : '';
		$this->data["desc"]			= isset($_POST["fdesc"]) ? htmlspecialchars($_POST["fdesc"]) : '';
		$this->data["type"]			= isset($_POST["ftype"]) ? htmlspecialchars($_POST["ftype"]) : '';
		$this->data["size"]			= isset($_POST["fsize"]) ? $_POST["fsize"] : '';
		$this->data["maxinput"]		= isset($_POST["fmaxinput"]) ? intval($_POST["fmaxinput"]) : 0;
		$this->data["content"]		= isset($_POST["fcontent"]) ? htmlspecialchars($_POST["fcontent"]) : '';
		$this->data["required"]		= isset($_POST["frequired"]) ? intval($_POST["frequired"]) : 0;

		if ( ($this->data["type"] != "textinput") && ($this->data["type"] != "textarea") && ($this->data["type"] != "dropdown") ){
			$this->data["type"] = "textinput";
		}

		if ($this->data["type"] == "textarea"){
			$tmp	= explode(',',$this->data["size"]);
			$cols	= isset($tmp[0]) ? intval($tmp[0]) : 0;
			$rows	= isset($tmp[1]) ? intval($tmp[1]) : 0;
			$this->data["size"]	= $cols.",".$rows;
		}
		else{
			$this->data["size"]	= intval($this->data["size"]);
		}

		if ( empty($this->data["title"]) ){
			$this->pre_add_field($Lang->data["field_error_no_title"]);
			return false;
		}

		//Check exist
		$DB->query("SELECT field_id FROM ". $DB->prefix ."user_field WHERE field_title='". $this->data['title'] ."'");
		if ( $DB->num_rows() ){
			$this->pre_add_field($Lang->data["field_error_exist_title"]);
			return false;
		}

		//Get max order
		$DB->query("SELECT max(field_order) AS maxorder FROM ". $DB->prefix ."user_field");
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$maxorder	= $result["maxorder"] + 1;
		}
		else{
			$maxorder	= 1;
		}

		$sql = "INSERT INTO ". $DB->prefix ."user_field(field_title, field_desc, field_content, field_type, field_size, field_maxchars, field_order, field_required)
				VALUES('". $this->data["title"] ."', '". $this->data["desc"] ."', '". $this->data["content"] ."', '". $this->data["type"] ."', '". $this->data["size"] ."', ". $this->data["maxinput"] .", $maxorder, ". $this->data["required"] .")";
		$DB->query($sql);
		$field_id	= $DB->insert_id();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $field_id, ACP_INDEX .'?mod=user_field&act='. FUNC_ACT_VIEW .'&id='. $field_id);

		$this->list_fields();
		return true;
	}

	function pre_edit_field($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$this->set_lang();
		$Info->tpl_main	= "user_field_edit";

		$DB->query("SELECT * FROM ".$DB->prefix."user_field WHERE field_id=$id");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["field_error_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=user_field"));
			return false;
		}
		$field_info = $DB->fetch_array();
		$DB->free_result();

		$Template->set_vars(array(
			"ERROR_MSG"						=> $msg,
			'S_ACTION'						=> $Session->append_sid(ACP_INDEX .'?mod=user_field&act=edit&id='. $id .'&page='. $this->page),
			"FTITLE"						=> isset($this->data["title"]) ? $this->data["title"] : $field_info['field_title'],
			"FDESC"							=> isset($this->data["desc"]) ? $this->data["desc"] : $field_info['field_desc'],
			"FTYPE"							=> isset($this->data["type"]) ? $this->data["type"] : $field_info['field_type'],
			"FSIZE"							=> (isset($this->data["size"]) && $this->data["size"]) ? $this->data["size"] : ($field_info['field_size'] ? $field_info['field_size'] : ''),
			"FMAXINPUT"						=> (isset($this->data["maxinput"]) && $this->data["maxinput"]) ? $this->data["maxinput"] : ($field_info['field_maxchars'] ? $field_info['field_maxchars'] : ''),
			"FCONTENT"						=> isset($this->data["content"]) ? $this->data["content"] : $field_info['field_content'],
			"FREQUIRED"						=> isset($this->data["required"]) ? $this->data["required"] : $field_info['field_required'],
			"L_PAGE_TITLE"					=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_field'] . $Lang->data['general_arrow'] . $Lang->data['general_edit'],
			"L_BUTTON"						=> $Lang->data["button_add"],
		));

		return true;
	}

	function do_edit_field(){
		global $Session, $DB, $Template, $Lang, $Func;

		$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$this->data["title"]    = isset($_POST["ftitle"]) ? htmlspecialchars($_POST["ftitle"]) : '';
		$this->data["desc"]     = isset($_POST["fdesc"]) ? htmlspecialchars($_POST["fdesc"]) : '';
		$this->data["type"]     = isset($_POST["ftype"]) ? htmlspecialchars($_POST["ftype"]) : '';
		$this->data["size"]     = isset($_POST["fsize"]) ? $_POST["fsize"] : '';
		$this->data["maxinput"] = isset($_POST["fmaxinput"]) ? intval($_POST["fmaxinput"]) : 0;
		$this->data["content"]  = isset($_POST["fcontent"]) ? htmlspecialchars($_POST["fcontent"]) : '';
		$this->data["required"] = isset($_POST["frequired"]) ? intval($_POST["frequired"]) : 0;

		if ( ($this->data["type"] != "textinput") && ($this->data["type"] != "textarea") && ($this->data["type"] != "dropdown") ){
			$this->data["type"] = "textinput";
		}

		if ($this->data["type"] == "textarea"){
			$tmp	= explode(',',$this->data["size"]);
			$cols	= isset($tmp[0]) ? intval($tmp[0]) : 0;
			$rows	= isset($tmp[1]) ? intval($tmp[1]) : 0;
			$this->data["size"]	= $cols .",". $rows;
		}
		else{
			$this->data["size"]	= intval($this->data["size"]);
		}

		if ( empty($this->data["title"]) ){
			$this->pre_edit_field($Lang->data["field_error_no_title"]);
			return false;
		}

		//Check exist
		$DB->query("SELECT field_id FROM ". $DB->prefix ."user_field WHERE field_title='". $this->data['title'] ."' AND field_id!=$id");
		if ( $DB->num_rows() ){
			$this->pre_edit_field($Lang->data["field_error_exist_title"]);
			return false;
		}

		$DB->query("UPDATE ". $DB->prefix ."user_field SET field_title='". $this->data["title"] ."', field_desc='". $this->data["desc"] ."', field_content='". $this->data["content"] ."', field_type='". $this->data["type"] ."', field_size='". $this->data["size"] ."', field_maxchars=". $this->data["maxinput"] .", field_required=". $this->data["required"] ." WHERE field_id=$id");

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=user_field&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_fields();
		return true;
	}

	function pre_delete_field(){
		global $Session, $DB, $Template, $Lang, $Info;

		$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main	= "user_field_delete";

		$DB->query("SELECT field_title FROM ". $DB->prefix ."user_field WHERE field_id=$id");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["field_error_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=user_field"));
			return false;
		}
		$result = $DB->fetch_array();
		$DB->free_result();

		$Template->set_vars(array(
			'U_DELETE'						=> $Session->append_sid(ACP_INDEX .'?mod=user_field&act=del&id='. $id .'&page='. $this->page),
			"FTITLE"						=> $result["field_title"],
			"L_DELETE_FIELD"				=> $Lang->data["field_del"],
			"L_DELETE_FIELD_DESC"			=> $Lang->data["field_del_desc"],
			"L_DELETE"						=> $Lang->data["button_delete"],
			"L_BACK"						=> $Lang->data["button_back"],
			"L_PAGE_TITLE"					=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_field'] . $Lang->data['general_arrow'] . $Lang->data['general_del'],
		));
		return true;
	}

	function do_delete_field(){
		global $Session, $DB, $Template, $Lang, $Func;

		$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		//Delete values of this field
		$DB->query("DELETE FROM ". $DB->prefix ."user_field_value WHERE field_id=$id");
		//Delete field
		$DB->query("DELETE FROM ". $DB->prefix ."user_field WHERE field_id=$id");

		//Save log
		$Func->save_log(FUNC_NAME, 'log_del', $id, ACP_INDEX .'?mod=user_field&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_fields();
	}

	function update_fields(){
		global $Session, $DB, $Template, $Lang, $Func;

		$field_orders	= isset($_POST["field_orders"]) ? $_POST["field_orders"] : '';

		if ( is_array($field_orders) ){
			reset($field_orders);
			while ( list($id, $num) = each($field_orders) ){
				$DB->query("UPDATE ". $DB->prefix ."user_field SET field_order=". intval($num) ." WHERE field_id=". intval($id));
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_update');

		$this->list_fields();
	}
}

?>