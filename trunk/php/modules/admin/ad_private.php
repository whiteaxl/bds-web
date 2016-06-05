<?php
/* =============================================================== *\
|		Module name:      Private									|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_private_profile');
//Module language
$Func->import_module_language("admin/lang_user". PHP_EX);

$Admin_Private = new Admin_Private;

class Admin_Private
{
	var $data			= array();

	function Admin_Private(){
		global $Info;

		switch ($Info->act){
			case "prechangepwd":
				$this->pre_change_password();
				break;
			case "changepwd":
				$this->do_change_password();
				break;
			case "edit":
				$this->do_edit_profile();
				break;
			default:
				$this->pre_edit_profile();
		}
	}

	function pre_edit_profile($msg = ""){
		global $Session, $Info, $Template, $Lang, $Func;

		$Info->tpl_main	= "private_edit";

		$Func->show_language_dirs();
		$Func->show_template_dirs();

		//Set field values
		if ( isset($this->data['field']) && is_array($this->data['field']) ){
			reset($this->data['field']);
			while ( list($id, $val) = each($this->data['field']) ){
				$id	= intval($id);
				$this->data['field'][$id]	= htmlspecialchars($val);
			}
			$this->get_all_fields();
		}
		else{
			$this->get_all_fields($Info->user_info['user_id']);
		}

		$this->set_lang();

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			"S_ACTION"				=> $Session->append_sid(ACP_INDEX ."?mod=private&act=edit"),
			"U_HELP_TIMEZONE"		=> $Session->append_sid(ACP_INDEX ."?mod=help&code=timezone"),
			"USERNAME"				=> isset($this->data['username']) ? $this->data['username'] : $Info->user_info['username'],
			"EMAIL"					=> isset($this->data['email']) ? $this->data['email'] : $Info->user_info['user_email'],
			"LANGUAGE"				=> isset($this->data["language"]) ? $this->data["language"] : $Info->user_info['user_language'],
			"TEMPLATE"				=> isset($this->data["template"]) ? $this->data["template"] : $Info->user_info['user_template'],
			"TIMEZONE"				=> isset($this->data["timezone"]) ? $this->data["timezone"] : $Info->user_info['user_timezone'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_private"] . $Lang->data['general_arrow'] . $Lang->data['menu_private_profile'],
			"L_BUTTON"				=> $Lang->data["button_edit"],
		));
	}

	function set_lang(){
		global $Template, $Lang;

		$Template->set_vars(array(
			"L_USERNAME"			=> $Lang->data["user_username"],
			"L_EMAIL"				=> $Lang->data["user_email"],
			"L_LANGUAGE"			=> $Lang->data["general_language"],
			"L_TEMPLATE"			=> $Lang->data["general_template"],
			"L_TIMEZONE"			=> $Lang->data["user_timezone"],
			"L_YES"					=> $Lang->data["general_yes"],
			"L_NO"					=> $Lang->data["general_no"],
			"L_DEFAULT"				=> $Lang->data["general_default"],
			"L_HELP"				=> $Lang->data["general_help"],
		));
	}

	function get_all_fields($user_id=0){
		global $Template, $DB;

		if ($user_id){
			$this->get_all_field_values($user_id);
		}

		$sql_query = $DB->query("SELECT * FROM ". $DB->prefix ."user_field ORDER BY field_order ASC");
		if ( $DB->num_rows($sql_query) ){
			while ($result = $DB->fetch_array($sql_query)){
				//Get profile value of this user
				$fvalue = $this->get_field_value($result["field_id"]);

				if ($result["field_type"] == "textinput"){
					$finput = $this->make_input_field($result["field_id"], $result["field_size"], $fvalue);
				}
				else if ($result["field_type"] == "textarea"){
					$size = explode(',', $result["field_size"]);
					$cols = isset($size[0]) ? intval($size[0]) : 20;
					$rows = isset($size[1]) ? intval($size[1]) : 4;
					$finput	= $this->make_textarea_field($result["field_id"], $cols, $rows, $fvalue);
				}
				else if ($result["field_type"] == "dropdown"){
					$finput	= $this->make_dropdown_field($result["field_id"], $result["field_content"], $fvalue);
				}
				else{
					$finput = "";
				}
				$frequired = $result["field_required"] ? '*' : '';

				$Template->set_block_vars("fieldrow",array(
					"TITLE"            => $result['field_title'],
					"INPUT"            => $finput,
					"REQUIRED"         => $frequired,
				));
			}
		}
		$DB->free_result();
	}

	function get_all_field_values($user_id){
		global $DB;

		$DB->query("SELECT field_id, field_value FROM ". $DB->prefix ."user_field_value WHERE user_id=$user_id");
		if ( $DB->num_rows() ){
			while ( $result = $DB->fetch_array() ){
				$this->data["field"][$result["field_id"]] = $result["field_value"];
			}
		}
		$DB->free_result();
	}

	function get_field_value($field_id){
		if ( isset($this->data["field"][$field_id]) ){
			return $this->data["field"][$field_id];
		}
		return "";
	}

	function make_input_field($id, $size, $value=""){
		return '<input class=form type="text" name="field['. $id .']" value="'. $value .'" size="'. $size .'">';
	}

	function make_textarea_field($id, $cols, $rows, $value=""){
		return '<textarea class=form name="field['. $id .']" cols="'. $cols .'" rows="'. $rows .'">'.$value.'</textarea>';
	}

	function make_dropdown_field($id, $content, $valueselected=""){
		$list	= explode("\n", $content);
		$str	= "<select class=form name='field[". $id ."]' size='1'>";

		reset($list);
		while (list(,$val) = each($list)){
			$option			= explode('=',$val);
			$option_value	= isset($option[0]) ? trim($option[0]) : '';
			$option_title	= isset($option[1]) ? trim($option[1]) : '';
			$str .= "\n<option value='". $option_value ."'";
			if ( !empty($valueselected) && ($valueselected == $option_value) ){
				$str .= " selected";
			}
			$str .= ">". $option_title ."</option>";
		}
		$str .= "\n</select>";
		return $str;
	}

	function get_dropdown_value($content, $valueselected=""){
		$list = explode("\n",$content);
		reset($list);

		while (list(,$val) = each($list)){
			$option = explode('=',$val);
			$option_value = isset($option[0]) ? trim($option[0]) : '';
			$option_title = isset($option[1]) ? trim($option[1]) : '';
			if ($option_value == $valueselected){
				return $option_title;
			}
		}

		return $valueselected;
	}

	function check_required_fields($field){
		global $DB;

		if ( !is_array($field) ) return false;

		$DB->query("SELECT field_id FROM ". $DB->prefix ."user_field WHERE field_required=1");
		if (!$DB->num_rows()){
			return true;
		}

		while ($result=$DB->fetch_array()){
			$id = $result["field_id"];
			if ( !isset($field[$id])||empty($field[$id]) ){
				return false;
			}
		}
		return true;
	}

	function do_edit_profile(){
		global $Session, $Info, $Func, $Template, $Lang, $DB, $File;

		$this->data['email']		= isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : '';
		$this->data["language"]		= isset($_POST["language"]) ? htmlspecialchars($_POST["language"]) : '';
		$this->data["template"]		= isset($_POST["template"]) ? htmlspecialchars($_POST["template"]) : '';
		$this->data['timezone']		= isset($_POST["timezone"]) ? htmlspecialchars($_POST["timezone"]) : '0';
		$this->data['field']		= isset($_POST["field"]) ? $_POST["field"] : '';

		if ( empty($this->data['email']) ){
			$this->pre_edit_profile($Lang->data['general_error_not_full']);
			return false;
		}

		//Check required fields
		if ( !$this->check_required_fields($this->data['field']) ){
			$this->pre_edit_profile($Lang->data['general_error_not_full']);
			return false;
		}

		if ( !$File->check_dir($this->data["template"], "templates") ){
			$this->pre_edit_profile($Lang->data['setting_error_template']);
			return false;
		}

		if ( !$File->check_dir($this->data["language"], "languages") ){
			$this->pre_edit_profile($Lang->data['setting_error_language']);
			return false;
		}

		$sql	= "UPDATE ". $DB->prefix ."user SET user_email='". $this->data['email'] ."', user_timezone='". $this->data['timezone'] ."', user_template='". $this->data['template'] ."', user_language='". $this->data['language'] ."' WHERE user_id=". $Info->user_info['user_id'];
		$DB->query($sql);

		//Delete old field values
		$DB->query('DELETE FROM '. $DB->prefix .'user_field_value WHERE user_id='. $Info->user_info['user_id']);

		//Insert into user fields
		if ( is_array($this->data['field']) ){
			reset($this->data['field']);
			while (list($fid, $val) = each($this->data['field'])){
				if ( $fid && !empty($val) ){
					$DB->query("INSERT INTO ". $DB->prefix ."user_field_value(user_id, field_id, field_value) VALUES(". $Info->user_info['user_id'] .", ". intval($fid) .", '". htmlspecialchars($val) ."')");
				}
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit');

		if ( ($this->data['template'] != $Info->user_info['user_template']) || ($this->data['language'] != $Info->user_info['user_language']) ){
			$Template->set_block_vars("reload", array());//Reload pages
			$Template->set_vars(array(
				'U_EDIT_OPTION'		=> $Session->append_sid(ACP_INDEX .'?mod=private')
			));
		}

		$Info->get_user_info();
		$this->pre_edit_profile($Lang->data['general_success_edit']);
		return true;
	}

	function pre_change_password($msg = ""){
		global $Session, $Info, $Template, $Lang, $DB;

		$Info->tpl_main	= "private_password";

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			"S_ACTION"				=> $Session->append_sid(ACP_INDEX ."?mod=private&act=changepwd"),
			'USERNAME'				=> $Info->user_info['username'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_private"] . $Lang->data['general_arrow'] . $Lang->data['menu_private_changepass'],
			"L_USERNAME"			=> $Lang->data["user_username"],
			"L_OLD_PASS"			=> $Lang->data["user_old_pass"],
			"L_NEW_PASS"			=> $Lang->data["user_new_pass"],
			"L_VERIFY_PASS"			=> $Lang->data["user_verify_new_pass"],
			"L_BUTTON"				=> $Lang->data["button_edit"],
		));
	}

	function do_change_password(){
		global $Session, $Info, $Template, $Lang, $DB;

		$old_pass			= isset($_POST['old_pass']) ? htmlspecialchars($_POST['old_pass']) : '';
		$new_pass			= isset($_POST['new_pass']) ? htmlspecialchars($_POST['new_pass']) : '';
		$verify_pass		= isset($_POST['verify_pass']) ? htmlspecialchars($_POST['verify_pass']) : '';

		if ( empty($old_pass) || empty($new_pass) || empty($verify_pass) ){
			$this->pre_change_password($Lang->data['general_error_not_full']);
			return false;
		}

		if ( md5($old_pass) != $Info->user_info['user_password'] ){
			$this->pre_change_password($Lang->data['user_error_oldpass']);
			return false;
		}

		if ( $new_pass != $verify_pass ){
			$this->pre_change_password($Lang->data['user_error_verifypass']);
			return false;
		}

		//Update
		$DB->query("UPDATE ". $DB->prefix ."user SET user_password='". md5($new_pass) ."' WHERE user_id=". $Info->user_info['user_id']);

		$Info->get_user_info();
		$this->pre_change_password($Lang->data['general_success_update']);
		return true;
	}
}
?>