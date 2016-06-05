<?php
/* =============================================================== *\
|		Module name: User											|
|		Module version: 1.2											|
|		Begin: 9 May 2004											|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_user_user');
define('FUNC_ACT_VIEW', 'view');
//Module language
$Func->import_module_language("admin/lang_user". PHP_EX);

$Admin_User = new Admin_User;

class Admin_User
{
	var $data		= array();
	var $filter		= array();
	var	$page		= 1;

	var $user_perm		= array();

	function Admin_User(){
		global $Info, $Func;

		$this->user_perm	= $Func->get_all_perms('menu_user_user');

		$this->get_common();

		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_user();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->do_add_user();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_user();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->do_edit_user();
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$this->delete_user();
				break;
			case "enable":
				$Func->check_user_perm($this->user_perm, 'active');
				$this->active_user(1);
				break;
			case "disable":
				$Func->check_user_perm($this->user_perm, 'active');
				$this->active_user(0);
				break;
			case "view":
				$Func->check_user_perm($this->user_perm, 'view');
				$this->view_user();
				break;
			case "kill":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->kill_user();
				break;
			case "rescure":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->rescure_user();
				break;
			case "resync":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->resync_users();
				break;
			default:
				$this->list_users();
		}
	}

	function get_common(){
		global $Template;

		$this->filter['url_append']		= "";

		$this->filter['keyword']		= isset($_POST["fkeyword"]) ? htmlspecialchars($_POST["fkeyword"]) : '';
		if ( empty($this->filter['keyword']) ){
			$this->filter['keyword']	= isset($_GET["fkeyword"]) ? htmlspecialchars(urldecode($_GET["fkeyword"])) : '';
		}
		if ( !empty($this->filter['keyword']) ){
			$this->filter['url_append']	.= '&fkeyword='. $this->filter['keyword'];
		}

		$this->filter['status']			= isset($_POST["fstatus"]) ? htmlspecialchars($_POST["fstatus"]) : '';
		if ( empty($this->filter['status']) ){
			$this->filter['status']		= isset($_GET["fstatus"]) ? htmlspecialchars($_GET["fstatus"]) : '';
		}
		if ( !empty($this->filter['status']) ){
			$this->filter['url_append']	.= '&fstatus='. $this->filter['status'];
		}

		$this->filter['group_id']		= isset($_POST["fgroup_id"]) ? intval($_POST["fgroup_id"]) : 0;
		if (!$this->filter['group_id']){
			$this->filter['group_id']	= isset($_GET["fgroup_id"]) ? intval($_GET["fgroup_id"]) : 0;
		}
		if ( $this->filter['group_id'] ){
			$this->filter['url_append']	.= '&fgroup_id='. $this->filter['group_id'];
		}

		$this->filter['sort_by']		= isset($_POST["fsort_by"]) ? htmlspecialchars($_POST["fsort_by"]) : '';
		if (empty($this->filter['sort_by'])){
			$this->filter['sort_by']	= isset($_GET["fsort_by"]) ? htmlspecialchars($_GET["fsort_by"]) : '';
		}
		if ( ($this->filter['sort_by'] != "username") && ($this->filter['sort_by'] != "user_email") && ($this->filter['sort_by'] != "user_last_login")){
			$this->filter['sort_by']	= "username";
		}
		$this->filter['url_append']	.= '&fsort_by='. $this->filter['sort_by'];

		$this->filter['sort_order']		= isset($_POST["fsort_order"]) ? htmlspecialchars($_POST["fsort_order"]) : '';
		if (empty($this->filter['sort_order'])){
			$this->filter['sort_order']	= isset($_GET["fsort_order"]) ? htmlspecialchars($_GET["fsort_order"]) : '';
		}
		if ( ($this->filter['sort_order'] != "desc") && ($this->filter['sort_order'] != "asc") ){
			$this->filter['sort_order']	= "asc";
		}
		$this->filter['url_append']	.= '&fsort_order='. $this->filter['sort_order'];

		$this->page		= (isset($_GET["page"]) && ($_GET["page"] > 0)) ? intval($_GET["page"]) : 1;

		$Template->set_vars(array(
			'FKEYWORD'		=> $this->filter['keyword'],
			'FSTATUS'		=> $this->filter['status'],
			'FGROUP_ID'		=> $this->filter['group_id'],
			'FSOFT_BY'		=> $this->filter['sort_by'],
			'FSOFT_ORDER'	=> $this->filter['sort_order'],
		));
	}

	function list_users(){
		global $Session, $Func, $Info, $Template, $Lang, $DB;

		//IP2Country
		include("./includes/ip2country". PHP_EX);
		$IP2Country  = new IP2Country;

		$itemperpage	= $Info->option['items_per_page'];
		$dateformat		= $Info->option['time_format'];
		$timezone		= $Info->option['timezone'] * 3600;
		$login_time		= $Info->option['time_login'];

		$Info->tpl_main	= "user_list";
		$this->get_all_groups();

		//Filter -----------------------------------
		$where_sql = " WHERE U.user_id=G.user_id";
		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*","%",$this->filter['keyword']);
			$where_sql	.= " AND (U.username LIKE '%".$key."%' OR U.user_email LIKE '%".$key."%')";
		}
		
		if ( $this->filter['status'] == 'enabled' ){
			$where_sql	.= " AND U.enabled=". SYS_ENABLED;
		}
		else if ( $this->filter['status'] == 'disabled' ){
			$where_sql	.= " AND U.enabled=". SYS_DISABLED;
		}
		
		if ( $this->filter['group_id'] ){
			$where_sql	.= " AND G.group_id=". $this->filter['group_id'];
		}
		//-----------------------------------------

		//Generate pagination
		$DB->query('SELECT count(distinct U.user_id) AS total FROM '. $DB->prefix .'user AS U, '. $DB->prefix .'user_group_ids AS G '. $where_sql);
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$page_url	= $Session->append_sid(ACP_INDEX .'?mod=user'. $this->filter['url_append']);
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page ,$Lang->data['general_page'] , $page_url);
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query('SELECT U.*, S.session_time, S.kicked_by, S.kicked_time FROM '. $DB->prefix .'user_group_ids AS G, '. $DB->prefix .'user AS U LEFT JOIN '. $DB->prefix .'session AS S ON U.user_id=S.user_id '. $where_sql .' GROUP BY U.user_id ORDER BY '. $this->filter['sort_by'] .' '. $this->filter['sort_order'] .' LIMIT '. $pageshow['start'] .','. $itemperpage);
		$user_count	= $DB->num_rows();
		$user_data	= $DB->fetch_all_array();

		//Get groups --------------------
		$where_sql	= " WHERE G.group_id=UG.group_id AND (UG.user_id=0";
		for ($i=0; $i<$user_count; $i++){
			$where_sql	.= " OR UG.user_id=". $user_data[$i]['user_id'];
		}
		$where_sql	.= ")";
		
		$group_arr	= array();
		$DB->query("SELECT G.*, UG.user_id FROM ". $DB->prefix ."user_group AS G, ". $DB->prefix ."user_group_ids AS UG $where_sql ORDER BY G.group_name ASC");
		if ( $DB->num_rows() ){
			while ( $result = $DB->fetch_array() ){
				$user_id	= $result['user_id'];
				if ( !isset($group_arr[$user_id]) ){
					$group_arr[$user_id]	= $result['group_name'];
				}
				else{
					$group_arr[$user_id]	.= "\n<br>". $result['group_name'];
				}
			}
		}
		//--------------------------------

		for ($i=0; $i<$user_count; $i++){
			$css	= $user_data[$i]['enabled'] ? 'enabled' : 'disabled';
			if ($user_data[$i]['session_time'] >= CURRENT_TIME - $login_time){//Online
				$country_flag	= $IP2Country->get_country_flag($user_data[$i]['user_ip'], 'left');
				$online			= '<img src="templates/'. $Info->option['template'] .'/images/admin/online.gif" align="absbottom" border="0">'. $user_data[$i]['user_ip'] . $country_flag;
				if ( $Func->check_user_perm($this->user_perm, 'edit', 0) ){
					if ( $user_data[$i]['kicked_time'] ){
						$online	= '<a class="'. $css .'" href="'. $Session->append_sid(ACP_INDEX .'?mod=user&act=rescure&id='. $user_data[$i]['user_id'] . $this->filter['url_append'] .'&page='. $this->page) .'" title="'. $Lang->data['user_rescure'] .'"><img src="templates/'. $Info->option['template'] .'/images/admin/online.gif" align="absbottom" border="0" alt="" title="'. $Lang->data['user_rescure'] .'">'. $Lang->data['user_kicked'] .'</a>';
					}
					else if ( $user_data[$i]['user_id'] != $Info->user_info['user_id'] ){
						$online	= '<a class="'. $css .'" href="'. $Session->append_sid(ACP_INDEX .'?mod=user&act=kill&id='. $user_data[$i]['user_id'] . $this->filter['url_append'] .'&page='. $this->page) .'" title="'. $Lang->data['user_kick'] .'"><img src="templates/'. $Info->option['template'] .'/images/admin/online.gif" align="absbottom" border="0" alt="" title="'. $Lang->data['user_kick'] .'">'. $user_data[$i]['user_ip'] . $country_flag .'</a>';
					}
				}
			}
			else{
				$online	= "&nbsp;";
			}
			$Template->set_block_vars("userrow", array(
				'CHECKBOX'			=> ($user_data[$i]['user_id'] != $Info->user_info['user_id']) ? '<input type="checkbox" name="userid['. $user_data[$i]['user_id'] .']" value="1">' : '&nbsp;',
				'USERNAME'			=> $user_data[$i]['username'],
				'EMAIL'				=> $user_data[$i]['user_email'],
				'ARTICLE_COUNTER'	=> $user_data[$i]['article_counter'] ? '<a class="'. $css .'" href="'. $Session->append_sid(ACP_INDEX .'?mod=article&fuser_id='. $user_data[$i]['user_id']) .'">'. $user_data[$i]['article_counter'] .'</a>' : $user_data[$i]['article_counter'],
				'LAST_LOGIN'		=> $user_data[$i]['user_last_login'] ? $Func->translate_date(gmdate($dateformat, $user_data[$i]['user_last_login'] + $timezone)) : '&nbsp;',
				'ONLINE'			=> $online,
				'GROUPS'			=> isset($group_arr[$user_data[$i]['user_id']]) ? $group_arr[$user_data[$i]['user_id']] : '&nbsp;',
				'CSS'				=> $css,
				'BG_CSS'			=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				'U_EDIT'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=user&act=preedit&id='. $user_data[$i]['user_id'] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
				'U_VIEW'			=> $Session->append_sid(ACP_INDEX .'?mod=user&act=view&id='. $user_data[$i]['user_id'] . $this->filter['url_append'] .'&page='. $this->page),
			));
		}
		$DB->free_result();

		$Template->set_vars(array(
			"PAGE_OUT"				=> $pageshow['page'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_user'],
			'S_ACTION_FILTER'		=> $Session->append_sid(ACP_INDEX .'?mod=user'),
			'U_ENABLE'				=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript: updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=user&act=enable'. $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/enable.gif" alt="" title="'. $Lang->data['general_enable'] .'" align="absbottom" border=0>'. $Lang->data['general_enable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DISABLE'				=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript: updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=user&act=disable'. $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/disable.gif" alt="" title="'. $Lang->data['general_disable'] .'" align="absbottom" border=0>'. $Lang->data['general_disable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'				=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript: deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=user&act=del'. $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" align="absbottom" border=0>'. $Lang->data['general_del'] .'</a>' : '',
			'U_ADD'					=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=user&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="'. $Lang->data['general_add'] .'" align="absbottom" border=0>'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp;' : '',
			'U_RESYNC'				=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=user&act=resync'. $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/resync.gif" alt="" title="'. $Lang->data['general_resync'] .'" align="absbottom" border=0>'. $Lang->data['general_resync'] .'</a> &nbsp; &nbsp;' : '',
			'L_USERNAME'			=> $Lang->data['user_username'],
			'L_EMAIL'				=> $Lang->data['user_email'],
			'L_ARTICLES'			=> $Lang->data['user_articles'],
			'L_LAST_LOGIN'			=> $Lang->data['user_last_login'],
			'L_ONLINE'				=> $Lang->data['user_online'],
			'L_STATUS'				=> $Lang->data['general_status'],
			'L_GROUP'				=> $Lang->data['user_group_filter'],
			'L_ASC'					=> $Lang->data['general_asc'],
			'L_DESC'				=> $Lang->data['general_desc'],
			'L_DEL_CONFIRM'			=> $Lang->data['user_del_confirm'],
			'L_CHOOSE_ITEM'			=> $Lang->data['user_error_not_check'],
			'L_BUTTON_SEARCH'		=> $Lang->data['button_search'],
		));
	}

	function get_all_groups(){
		global $Template, $DB;

		$DB->query("SELECT * FROM ". $DB->prefix ."user_group ORDER BY group_name ASC");
		if ( $DB->num_rows() ){
			while ($result = $DB->fetch_array()){
				$Template->set_block_vars("grouprow",array(
					"ID"		=> $result["group_id"],
					"NAME"		=> $result["group_name"],
				));
			}
		}
		$DB->free_result();
	}

	function pre_add_user($msg = ""){
		global $Session, $Info, $Template, $Lang, $DB, $Func;

		$Info->tpl_main	= "user_edit";
		$this->set_lang();

		$Template->set_block_vars("addrow", array());
		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			"U_HELP_TIMEZONE"		=> $Session->append_sid(ACP_INDEX ."?mod=help&code=timezone"),
			"S_ACTION"				=> $Session->append_sid(ACP_INDEX ."?mod=user&act=add"),
			"LANGUAGE"				=> isset($this->data["language"]) ? stripslashes($this->data["language"]) : '',
			"TEMPLATE"				=> isset($this->data["template"]) ? stripslashes($this->data["template"]) : '',
			"TIMEZONE"				=> isset($this->data["timezone"]) ? $this->data["timezone"] : '',
			"HIDE_EMAIL"			=> isset($this->data["hide_email"]) ? $this->data["hide_email"] : '',
			"USERNAME"				=> isset($this->data['username']) ? stripslashes($this->data['username']) : '',
			"EMAIL"					=> isset($this->data['email']) ? stripslashes($this->data['email']) : '',
			"ENABLED"				=> isset($this->data['enabled']) ? $this->data['enabled'] : '',
			"PAGETO"				=> isset($this->data['page_to']) ? $this->data['page_to'] : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_user'] . $Lang->data['general_arrow'] . $Lang->data['general_add'],
			"L_PASS"				=> $Lang->data["user_pass"] .' *',
			"L_VERIFY_PASS"			=> $Lang->data["user_verify_pass"] .' *',
			"L_BUTTON"				=> $Lang->data["button_add"],
		));

		//Set group values
		if ( isset($this->data['group_id']) && is_array($this->data['group_id']) ){
			reset($this->data['group_id']);
			while ( list(, $id) = each($this->data['group_id']) ){
				$Template->set_block_vars("groupvalrow", array(
					'ID'	=> intval($id)
				));
			}
		}

		//Set field values
		if ( isset($this->data['field']) && is_array($this->data['field']) ){
			reset($this->data['field']);
			while ( list($id, $val) = each($this->data['field']) ){
				$id	= intval($id);
				$this->data['field'][$id]	= htmlspecialchars($val);
			}
		}

		$this->get_all_groups();
		$this->get_all_fields();
		$Func->show_language_dirs();
		$Func->show_template_dirs();
	}

	function set_lang(){
		global $Template, $Lang;

		$Template->set_vars(array(
			"L_USERNAME"			=> $Lang->data["user_username"],
			"L_EMAIL"				=> $Lang->data["user_email"],
			"L_LANGUAGE"			=> $Lang->data["general_language"],
			"L_TEMPLATE"			=> $Lang->data["general_template"],
			"L_TIMEZONE"			=> $Lang->data["user_timezone"],
			"L_DEFAULT"				=> $Lang->data["general_default"],
			"L_YES"					=> $Lang->data["general_yes"],
			"L_NO"					=> $Lang->data["general_no"],
			"L_GROUP"				=> $Lang->data["user_in_group"],
			"L_PAGE_TO"				=> $Lang->data["general_page_to"],
			"L_PAGE_ADD"			=> $Lang->data["general_page_add"],
			"L_PAGE_LIST"			=> $Lang->data["general_page_list"],
			"L_SAVE_AS"				=> $Lang->data["general_save_as"],
			"L_SAVE"				=> $Lang->data["general_save"],
			"L_COPY"				=> $Lang->data["general_copy"],
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

	function do_add_user(){
		global $Session, $Info, $Template, $Lang, $DB, $Func, $File;

		$this->data['group_id']		= isset($_POST["group_id"]) ? $_POST["group_id"] : '';
		$this->data['username']		= isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : '';
		$this->data['pass']			= isset($_POST["pass"]) ? $_POST["pass"] : '';
		$this->data['verifypass']	= isset($_POST["verifypass"]) ? $_POST["verifypass"] : '';
		$this->data['email']		= isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : '';
		$this->data["language"]		= isset($_POST["language"]) ? htmlspecialchars($_POST["language"]) : '';
		$this->data["template"]		= isset($_POST["template"]) ? htmlspecialchars($_POST["template"]) : '';
		$this->data['timezone']		= isset($_POST["timezone"]) ? htmlspecialchars($_POST["timezone"]) : '0';
		$this->data['field']		= isset($_POST["field"]) ? $_POST["field"] : '';
		$this->data['enabled']		= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : '';
		$this->data['page_to']		= isset($_POST["page_to"]) ? htmlspecialchars($_POST["page_to"]) : '';

		if ( empty($this->data['group_id']) || empty($this->data['username']) || empty($this->data['pass']) || empty($this->data['verifypass']) || empty($this->data['email']) ){
			$this->pre_add_user($Lang->data['general_error_not_full']);
			return false;
		}

		//Check required fields
		if ( !$this->check_required_fields($this->data['field']) ){
			$this->pre_add_user($Lang->data['general_error_not_full']);
			return false;
		}

		if ( !$File->check_dir($this->data["template"], "templates") ){
			$this->pre_add_user($Lang->data['setting_error_template']);
			return false;
		}

		if ( !$File->check_dir($this->data["language"], "languages") ){
			$this->pre_add_user($Lang->data['setting_error_language']);
			return false;
		}

		if ($this->data['verifypass'] != $this->data['pass']){
			$this->pre_add_user($Lang->data['user_error_verifypass']);
			return false;
		}

		if ( !is_array($this->data['group_id']) ){
			$group[0] = $this->data['group_id'];
		}
		else{
			$group = $this->data['group_id'];
		}

		//Check exist
		$DB->query("SELECT user_id FROM ". $DB->prefix ."user WHERE username='". $this->data['username'] ."'");
		if ( $DB->num_rows() ){
			$this->pre_add_user($Lang->data['user_error_exist_username']);
			return false;
		}

		$sql	= "INSERT INTO ". $DB->prefix ."user(username, user_password, user_email, user_timezone, user_last_login, user_ip, user_template, user_language, article_counter, enabled)
					 VALUES('". $this->data['username'] ."', '". md5($this->data['pass']) ."', '". $this->data['email'] ."', '". $this->data['timezone'] ."', 0, '', '". $this->data['template'] ."', '". $this->data['language'] ."', 0, ". $this->data['enabled'] .")";
		$DB->query($sql);
		$user_id = $DB->insert_id();

		//Insert into users groups
		reset($group);
		while ( list(, $id) = each($group) ){
			$DB->query("INSERT INTO ". $DB->prefix ."user_group_ids(user_id, group_id) VALUES($user_id, ". intval($id) .")");
		}

		//Insert into user fields
		if ( is_array($this->data['field']) ){
			reset($this->data['field']);
			while (list($id, $val) = each($this->data['field'])){
				if ( $id && !empty($val) ){
					$DB->query("INSERT INTO ". $DB->prefix ."user_field_value(user_id, field_id, field_value) VALUES($user_id, ". intval($id) .", '". htmlspecialchars($val) ."')");
				}
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $user_id, ACP_INDEX .'?mod=user&act='. FUNC_ACT_VIEW .'&id='. $user_id);

		if ( $this->data['page_to'] == 'pageadd' ){
			$tmp_data['page_to']	= $this->data['page_to'];
//			$this->data	= array();//Reset data
			$this->data	= $tmp_data;
			$this->pre_add_user($Lang->data['general_success_add']);
		}
		else{
			$this->list_users();
		}

		return true;
	}

	function check_required_fields($field){
		global $DB;

		if ( !is_array($field) ) return true;

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

	function pre_edit_user($msg = ""){
		global $Session, $Info, $Template, $Lang, $DB, $Func;

		$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main	= "user_edit";

		//Get info
		$DB->query("SELECT * FROM ". $DB->prefix ."user WHERE user_id=$id");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["user_error_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=user"));
			return false;
		}
		$user_info = $DB->fetch_array();
		$DB->free_result();

		//Set group values
		if ( isset($this->data['group_id']) && is_array($this->data['group_id']) ){
			reset($this->data['group_id']);
			while ( list(, $id) = each($this->data['group_id']) ){
				$Template->set_block_vars("groupvalrow", array(
					'ID'	=> intval($id)
				));
			}
		}
		else{
			$this->set_group_value($user_info['user_id']);
		}

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
			$this->get_all_fields($user_info['user_id']);
		}

		$this->get_all_groups();
		$Func->show_language_dirs();
		$Func->show_template_dirs();
		$this->set_lang();

		$Template->set_block_vars("editrow", array());
		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			"U_HELP_TIMEZONE"		=> $Session->append_sid(ACP_INDEX ."?mod=help&code=timezone"),
			"S_ACTION"				=> $Session->append_sid(ACP_INDEX ."?mod=user&act=edit&id=$id". $this->filter['url_append'] .'&page='. $this->page),
			"USERNAME"				=> isset($this->data['username']) ? stripslashes($this->data['username']) : $user_info['username'],
			"EMAIL"					=> isset($this->data['email']) ? stripslashes($this->data['email']) : $user_info['user_email'],
			"LANGUAGE"				=> isset($this->data["language"]) ? stripslashes($this->data["language"]) : $user_info['user_language'],
			"TEMPLATE"				=> isset($this->data["template"]) ? stripslashes($this->data["template"]) : $user_info['user_template'],
			"TIMEZONE"				=> isset($this->data["timezone"]) ? $this->data["timezone"] : $user_info['user_timezone'],
			"ENABLED"				=> isset($this->data['enabled']) ? $this->data['enabled'] : $user_info['enabled'],
			"SAVEAS"				=> isset($this->data['save_as']) ? $this->data['save_as'] : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_user'] . $Lang->data['general_arrow'] . $Lang->data['general_edit'],
			"L_PASS"				=> $Lang->data["user_new_pass"],
			"L_VERIFY_PASS"			=> $Lang->data["user_verify_new_pass"],
			"L_BUTTON"				=> $Lang->data["button_edit"],
		));
		return true;
	}

	function set_group_value($user_id){
		global $DB, $Template;

		$DB->query("SELECT group_id FROM ". $DB->prefix ."user_group_ids WHERE user_id=$user_id");
		if ( $DB->num_rows() ){
			while ($result = $DB->fetch_array()){
				$Template->set_block_vars("groupvalrow",array(
					"ID"    => $result["group_id"]
				));
			}
		}
		$DB->free_result();
	}

	function do_edit_user(){
		global $Session, $Info, $Func, $Template, $Lang, $DB, $File;

		$id		= isset($_GET['id']) ? intval($_GET['id']) : 0;
		$this->data['group_id']		= isset($_POST["group_id"]) ? $_POST["group_id"] : '';
		$this->data['username']		= isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : '';
		$this->data['pass']			= isset($_POST["pass"]) ? $_POST["pass"] : '';
		$this->data['verifypass']	= isset($_POST["verifypass"]) ? $_POST["verifypass"] : '';
		$this->data['email']		= isset($_POST["email"]) ? htmlspecialchars($_POST["email"]) : '';
		$this->data["language"]		= isset($_POST["language"]) ? htmlspecialchars($_POST["language"]) : '';
		$this->data["template"]		= isset($_POST["template"]) ? htmlspecialchars($_POST["template"]) : '';
		$this->data['timezone']		= isset($_POST["timezone"]) ? htmlspecialchars($_POST["timezone"]) : '0';
		$this->data['field']		= isset($_POST["field"]) ? $_POST["field"] : '';
		$this->data['enabled']		= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : '';
		$this->data['save_as']		= isset($_POST["save_as"]) ? htmlspecialchars($_POST["save_as"]) : '';

		if ( empty($this->data['group_id']) || empty($this->data['username']) || empty($this->data['email']) ){
			$this->pre_edit_user($Lang->data['general_error_not_full']);
			return false;
		}

		if ( ($this->data['save_as'] == 'copy') ){
			if ( !empty($this->data['pass']) && !empty($this->data['verifypass']) ){
				$this->do_add_user();
			}
			else{
				$this->pre_edit_user($Lang->data['general_error_not_full']);
			}
			return false;
		}

		//Check required fields
		if ( !$this->check_required_fields($this->data['field']) ){
			$this->pre_edit_user($Lang->data['general_error_not_full']);
			return false;
		}

		if ( !$File->check_dir($this->data["template"], "templates") ){
			$this->pre_edit_user($Lang->data['setting_error_template']);
			return false;
		}

		if ( !$File->check_dir($this->data["language"], "languages") ){
			$this->pre_edit_user($Lang->data['setting_error_language']);
			return false;
		}

		if ( !empty($this->data['pass']) ){
			if ($this->data['verifypass'] != $this->data['pass']){
				$this->pre_edit_user($Lang->data['user_error_verifypass']);
				return false;
			}
			$newpass	= ", user_password='". md5($this->data['pass']) ."'";
		}
		else{
			$newpass	= "";
		}

		if ( !is_array($this->data['group_id']) ){
			$group[0] = $this->data['group_id'];
		}
		else{
			$group = $this->data['group_id'];
		}

		//Check exist
		$DB->query("SELECT user_id FROM ". $DB->prefix ."user WHERE username='". $this->data['username'] ."' AND user_id!=$id");
		if ( $DB->num_rows() ){
			$this->pre_edit_user($Lang->data['user_error_exist_username']);
			return false;
		}

		$sql	= "UPDATE ". $DB->prefix ."user SET username='". $this->data['username'] ."'". $newpass .", user_email='". $this->data['email'] ."', user_timezone='". $this->data['timezone'] ."', user_template='". $this->data['template'] ."', user_language='". $this->data['language'] ."', enabled=". $this->data['enabled'] ." WHERE user_id=$id";
		$DB->query($sql);

		//Delete old user groups
		$DB->query('DELETE FROM '. $DB->prefix .'user_group_ids WHERE user_id='. $id);
		
		//Insert new users groups
		reset($group);
		while ( list(, $gid) = each($group) ){
			$DB->query("INSERT INTO ". $DB->prefix ."user_group_ids(user_id, group_id) VALUES($id, ". intval($gid) .")");
		}

		//Delete old field values
		$DB->query('DELETE FROM '. $DB->prefix .'user_field_value WHERE user_id='. $id);

		//Insert into user fields
		if ( is_array($this->data['field']) ){
			reset($this->data['field']);
			while (list($fid, $val) = each($this->data['field'])){
				if ( $fid && !empty($val) ){
					$DB->query("INSERT INTO ". $DB->prefix ."user_field_value(user_id, field_id, field_value) VALUES($id, ". intval($fid) .", '". htmlspecialchars($val) ."')");
				}
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=user&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_users();
		return true;
	}

	function delete_user(){
		global $Session, $Info, $Template, $Lang, $DB, $Func;

		$userid		= isset($_POST["userid"]) ? $_POST["userid"] : '';

		if ( is_array($userid) ){
			$where_sql			= " WHERE user_id=-1";
			$where_poster_sql	= " WHERE poster_id=-1";
			$where_checker_sql	= " WHERE checker_id=-1";

			$record_ids		= "";
			reset($userid);
			while ( list($id,) = each($userid) ){
				$id	= intval($id);
				if ($id != $Info->user_info['user_id']){
					$where_sql			.= " OR user_id=$id";
					$where_poster_sql	.= " OR poster_id=$id";
					$where_checker_sql	.= " OR checker_id=$id";
					
					if ( !empty($record_ids) ){
						$record_ids	.= ', ';
					}
					$record_ids	.= $id;
				}
			}

			//Update articles of these users
			$DB->query('UPDATE '. $DB->prefix .'article SET poster_id=0 '. $where_poster_sql);
			$DB->query('UPDATE '. $DB->prefix .'article SET checker_id=0 '. $where_checker_sql);

			//Delete user groups
			$DB->query('DELETE FROM '. $DB->prefix .'user_group_ids '. $where_sql);

			//Delete user field values
			$DB->query('DELETE FROM '. $DB->prefix .'user_field_value '. $where_sql);

			//Delete users
			$DB->query('DELETE FROM '. $DB->prefix .'user '. $where_sql);

			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $record_ids);
		}
		$this->list_users();
	}

	function view_user(){
		global $Session, $Info, $DB, $Template, $Lang;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main	= "user_view";

		//Get user info
		$DB->query("SELECT * FROM ". $DB->prefix ."user WHERE user_id=$id");
		if ( !$DB->num_rows() ){
			$Template->message_die($Lang->data["user_error_not_exist"]);
			return false;
		}
		$user_info	= $DB->fetch_array();
		$DB->free_result();

		$this->list_user_groups($user_info["user_id"]);
		$this->view_field_values($user_info["user_id"]);

		$Template->set_vars(array(
			"USERNAME"				=> $user_info["username"],
			"EMAIL"					=> $user_info["user_email"],
			"TIMEZONE"				=> $user_info["user_timezone"],
			"L_PAGE_TITLE"			=> $Lang->data["menu_user"] . $Lang->data['general_arrow'] . $Lang->data['menu_user_user'] . $Lang->data['general_arrow'] . $Lang->data['general_view'],
			"L_USERNAME"			=> $Lang->data["user_username"],
			"L_EMAIL"				=> $Lang->data["user_email"],
			"L_TIMEZONE"			=> $Lang->data["user_timezone"],
			"L_IN_GROUP"			=> $Lang->data["user_in_group"],
		));
		return true;
	}

	function list_user_groups($user_id){
		global $Template, $DB;

		$DB->query("SELECT G.group_name FROM ". $DB->prefix ."user_group AS G, ". $DB->prefix ."user_group_ids AS UG WHERE UG.group_id=G.group_id AND UG.user_id=$user_id ORDER BY G.group_name ASC");
		$user_count	= $DB->num_rows();
		$user_data	= $DB->fetch_all_array();
		$DB->free_result();

		$str_group	= "";
		for ($i=0; $i<$user_count; $i++){
			$str_group		.= $user_data[$i]["group_name"];
			if ($i < $user_count - 1){
				$str_group	.= ", ";
			}
		}

		$Template->set_vars(array(
			"USER_GROUPS"        => $str_group
		));
	}

	function view_field_values($user_id){
		global $Template, $DB;

		$this->get_all_field_values($user_id);

		$DB->query("SELECT * FROM ". $DB->prefix ."user_field ORDER BY field_order ASC");
		if ( $DB->num_rows() ){
			while ($result = $DB->fetch_array()){
				if ($user_id){
					//Get profile value of this user
					$fvalue = $this->get_field_value($result["field_id"]);
				}
				else{
					$fvalue = "";
				}

				if ($result["field_type"] == "dropdown"){
					$fvalue = $this->get_dropdown_value($result["field_content"], $fvalue);
				}
				$Template->set_block_vars("fieldrow",array(
					"VALUE"            => $fvalue,
					"TITLE"            => $result["field_title"],
				));
			}
		}
		$DB->free_result();
	}

	function kill_user(){
		global $DB, $Template, $Info, $Func;

		$id		= isset($_GET['id']) ? intval($_GET['id']) : 0;

		$DB->query("UPDATE ". $DB->prefix ."session SET kicked_by='". $Info->user_info['username'] ."', kicked_time=". CURRENT_TIME ." WHERE user_id=". $id);

		//Save log
		$Func->save_log(FUNC_NAME, 'log_kill_user', $id, ACP_INDEX .'?mod=user&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_users();
	}

	function rescure_user(){
		global $DB, $Template, $Info, $Func;

		$id		= isset($_GET['id']) ? intval($_GET['id']) : 0;

		$DB->query("UPDATE ". $DB->prefix ."session SET kicked_by='', kicked_time=0 WHERE user_id=". $id);

		//Save log
		$Func->save_log(FUNC_NAME, 'log_rescure_user', $id, ACP_INDEX .'?mod=user&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_users();
	}

	function active_user($status=0){
		global $DB, $Template, $Func;

		$userid		= isset($_POST['userid']) ? $_POST['userid'] : '';

		if ( is_array($userid) ){
			$log_act	= $status ? 'log_enable' : 'log_disable';
			$record_ids	= "";
			$where_sql	= " WHERE user_id=0";
			reset($userid);
			while ( list($id,) = each($userid) ){
				$where_sql	.= " OR user_id=". intval($id);

				if ( !empty($record_ids) ){
					$record_ids	.= ', ';
				}
				$record_ids	.= $id;
			}
			$DB->query("UPDATE ". $DB->prefix ."user SET enabled=$status $where_sql");

			//Save log
			$Func->save_log(FUNC_NAME, $log_act, $record_ids);
		}

		$this->list_users();
	}

	function resync_users(){
		global $DB, $Template, $Lang, $Func;

		$DB->query('UPDATE '. $DB->prefix .'user SET article_counter=0');

		$DB->query('SELECT count(article_id) AS article_counter, poster_id FROM '. $DB->prefix .'article WHERE poster_id>0 GROUP BY poster_id');
		$user_count		= $DB->num_rows();
		$user_data		= $DB->fetch_all_array();

		for ($i=0; $i<$user_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'user SET article_counter='. $user_data[$i]['article_counter'] .' WHERE user_id='. $user_data[$i]['poster_id']);
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_resync');

		$this->list_users();
	}
}
?>