<?php
/* =============================================================== *\
|		Module name: Event											|
|		Module version: 1.3											|
|		Begin: 10 November 2004										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_miscell_event');
define('FUNC_ACT_VIEW', 'view');
//Module language
$Func->import_module_language("admin/lang_event". PHP_EX);

$AdminEvent = new Admin_Event;

class Admin_Event
{
	var $data			= array();
	var $filter			= array();
	var $page			= 1;

	var $cat_count		= 0;
	var $cat_data		= array();

	var $upload_path	= "images/events/";

	var $user_perm		= array();

	function Admin_Event(){
		global $Info, $Func, $Cache;

		$this->user_perm	= $Func->get_all_perms('menu_miscell_event');

		$this->get_filter();

		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_event();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$Cache->clear_cache('all', 'html');
				$this->do_add_event();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_event();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all', 'html');
				$this->do_edit_event();
				break;
			case "enable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all', 'html');
				$this->active_events(1);
				break;
			case "disable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all', 'html');
				$this->active_events(0);
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$Cache->clear_cache('all', 'html');
				$this->delete_events();
				break;
			case "view":
				$Func->check_user_perm($this->user_perm, 'view');
				$this->view_event();
				break;
			default:
				$this->list_events();
		}
	}

	function get_filter(){
		global $Template, $Lang, $Func;

		$this->filter['url_append']	= "";

		//Day
		$this->filter['day']		= intval($Func->get_request('fday', 0));
		if ( !empty($this->filter['day']) ){
			$this->filter['url_append']	.= '&fday='. $this->filter['day'];
		}

		//Month
		$this->filter['month']		= intval($Func->get_request('fmonth', 0));
		if ( !empty($this->filter['month']) ){
			$this->filter['url_append']	.= '&fmonth='. $this->filter['month'];
		}

		//Year
		$this->filter['year']		= intval($Func->get_request('fyear', 0));
		if ( !empty($this->filter['year']) ){
			$this->filter['url_append']	.= '&fyear='. $this->filter['year'];
		}

		$this->filter['keyword']		= htmlspecialchars($Func->get_request('fkeyword'));
		if ( !empty($this->filter['keyword']) ){
			$this->filter['url_append']	.= '&fkeyword='. $this->filter['keyword'];
		}

		$this->filter['status']			= intval($Func->get_request('fstatus', -1));
		if ( $this->filter['status'] != -1 ){
			$this->filter['url_append']	.= '&fstatus='. $this->filter['status'];
		}

		$this->page			= intval($Func->get_request('page', 1, 'GET'));

		$Template->set_vars(array(
			"FKEYWORD"		=> stripslashes($this->filter['keyword']),
			"FDAY"			=> $this->filter['day'],
			"FMONTH"		=> $this->filter['month'],
			"FYEAR"			=> $this->filter['year'] ? $this->filter['year'] : '- '. $Lang->data["general_year"] .' -',
			"FSTATUS"		=> $this->filter['status'],
		));
	}

	function list_events(){
		global $Session, $Func, $Info, $DB, $Template, $Lang;

		$Info->tpl_main	= "event_list";
		$itemperpage	= $Info->option['items_per_page'];
		$date_format	= $Info->option['date_format'];

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
		$where_sql		= " WHERE event_id>0";
		if ( ($this->filter['status'] == SYS_ENABLED) || ($this->filter['status'] == SYS_DISABLED) ){
			$where_sql	.= " AND enabled=". $this->filter['status'];
		}
		if ( $this->filter['year'] ){
			$where_sql	.= " AND event_year=". $this->filter['year'];
		}
		if ( $this->filter['month'] ){
			$where_sql	.= " AND event_month=". $this->filter['month'];
		}
		if ( $this->filter['day'] ){
			$where_sql	.= " AND event_day=". $this->filter['day'];
		}
		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*", '%', $this->filter['keyword']);
			$where_sql	.= " AND ( title LIKE('%". $key ."%') OR detail LIKE('%". $key ."%') )";
		}
		//------------------------------------

		//Generate pages
		$DB->query("SELECT count(event_id) AS total FROM ". $DB->prefix ."event $where_sql $auth_where_sql");
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=event" . $this->filter['url_append']));
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query("SELECT * FROM ". $DB->prefix ."event $where_sql $auth_where_sql ORDER BY event_year, event_month, event_day, event_from_hour, event_from_minute DESC LIMIT ". $pageshow['start'] .",$itemperpage");
		$event_count	= $DB->num_rows();
		$event_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$event_count; $i++){
			$from_hour		= "";
			$to_hour		= "";
			if ( ($event_data[$i]['event_from_hour'] >= 0) ){
				$from_hour	= ($event_data[$i]['event_from_minute'] > 0) ? $event_data[$i]['event_from_hour'] .'h'. $event_data[$i]['event_from_minute'] : (($event_data[$i]['event_from_minute'] == 0) ? $event_data[$i]['event_from_hour'] .'h00' : $event_data[$i]['event_from_hour'] .'h');

				if ( ($event_data[$i]['event_to_hour'] >= 0) ){
					$to_hour	= ($event_data[$i]['event_to_minute'] > 0) ? $event_data[$i]['event_to_hour'] .'h'. $event_data[$i]['event_to_minute'] : (($event_data[$i]['event_to_minute'] == 0) ? $event_data[$i]['event_to_hour'] .'h00' : $event_data[$i]['event_to_hour'] .'h');
					$to_hour	= ' - '. $to_hour;
				}
			}
			$Template->set_block_vars("eventrow",array(
				"ID"				=> $event_data[$i]["event_id"],
				"CSS"				=> ($event_data[$i]["enabled"] == SYS_ENABLED) ? "enabled" : "disabled",
				"TITLE"				=> $event_data[$i]["title"],
				"DATE"				=> (($event_data[$i]["event_year"] > 1970) && ($event_data[$i]["event_year"] < 2038)) ? $Func->translate_date(gmdate($date_format, mktime(12, 0, 0, $event_data[$i]["event_month"], $event_data[$i]["event_day"], $event_data[$i]["event_year"]))) : $Func->translate_date($event_data[$i]["event_day"] .' '. $Func->convert_month($event_data[$i]["event_month"]) .' '. $event_data[$i]["event_year"]),
//				"DATE"				=> $Func->translate_date($event_data[$i]["event_day"] .' '. $Func->convert_month($event_data[$i]["event_month"]) .' '. $event_data[$i]["event_year"]),
				'TIME'				=> $from_hour . $to_hour,
				'BG_CSS'			=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				'U_VIEW'			=> $Session->append_sid(ACP_INDEX .'?mod=event&act=view&id='. $event_data[$i]["event_id"]),
				'U_EDIT'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=event&act=preedit&id='. $event_data[$i]["event_id"] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
			));
		}
		$DB->free_result();

		$Template->set_vars(array(
			"PAGE_OUT"					=> $pageshow['page'],
			'S_FILTER_ACTION'			=> $Session->append_sid(ACP_INDEX .'?mod=event'),
			'S_LIST_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=event&act=update'. $this->filter['url_append']),
			'U_ADD'						=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=event&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="{'. $Lang->data['general_add'] .'" align="absbottom" border=0>'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp; ' : '',
			'U_ENABLE'					=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=event&act=enable' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/enable.gif" alt="" title="'. $Lang->data['general_enable'] .'" align="absbottom" border=0>'. $Lang->data['general_enable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DISABLE'					=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=event&act=disable' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/disable.gif" alt="" title="'. $Lang->data['general_disable'] .'" align="absbottom" border=0>'. $Lang->data['general_disable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'					=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript:deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=event&act=del' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" align="absbottom" border=0>'. $Lang->data['general_del'] .'</a> &nbsp; &nbsp;' : '',
			"L_PAGE_TITLE"				=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data["menu_miscell_event"],
			"L_TITLE"					=> $Lang->data["event_title"],
			"L_DATE"					=> $Lang->data["general_date"],
			"L_TIME"					=> $Lang->data["general_time"],
			"L_SEARCH"					=> $Lang->data["button_search"],
			"L_DAY"						=> $Lang->data["general_day"],
			"L_MONTH"					=> $Lang->data["general_month"],
			"L_YEAR"					=> $Lang->data["general_year"],
			"L_DEL_CONFIRM"				=> $Lang->data['event_del_confirm'],
			"L_CHOOSE_ITEM"				=> $Lang->data['event_error_not_check'],
		));
	}

	function pre_add_event($msg = ""){
		global $Session, $Info, $DB, $Template, $Lang;

		$Info->tpl_main	= "event_edit";

		$this->set_lang();

		$today = getdate();
		$month = $today['mon'];
		$day   = $today['mday'];
		$year  = $today['year'];
//		$time  = $today['hours'] .":". $today['minutes'];

		$Template->set_block_vars("addrow",array());
		$Template->set_vars(array(
			"ERROR_MSG"				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=event&act=add'. $this->filter['url_append']),
			"MONTH"					=> isset($this->data["month"]) ? $this->data["month"] : $month,
			"DAY"					=> isset($this->data["day"]) ? $this->data["day"] : $day,
			"YEAR"					=> isset($this->data["year"]) ? $this->data["year"] : $year,
			"FROM_HOUR"				=> isset($this->data["from_hour"]) ? $this->data["from_hour"] : '',
			"FROM_MINUTE"			=> isset($this->data["from_minute"]) ? $this->data["from_minute"] : '',
			"TO_HOUR"				=> isset($this->data["to_hour"]) ? $this->data["to_hour"] : '',
			"TO_MINUTE"				=> isset($this->data["to_minute"]) ? $this->data["to_minute"] : '',
			"TITLE"					=> isset($this->data["title"]) ? stripslashes($this->data["title"]) : '',
			"DETAIL"				=> isset($this->data["detail"]) ? stripslashes($this->data["detail"]) : '',
			"USED_FILES"			=> isset($this->data["used_files"]) ? stripslashes($this->data["used_files"]) : '',
			"ENABLED"				=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : '',
			"PAGE_TO"				=> isset($this->data["page_to"]) ? $this->data["page_to"] : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data["menu_miscell_event"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			"L_BUTTON"				=> $Lang->data["button_add"],
		));
	}

	function set_lang(){
		global $Session, $Template, $Lang, $Info;

		$Template->set_vars(array(
			'S_IMAGE_UPLOAD'			=> $Session->append_sid('../../'. ACP_INDEX .'?mod=upload&code=image'),
			"L_CHOOSE"					=> $Lang->data["general_choose"],
			"L_TITLE"					=> $Lang->data["event_title"],
			"L_DETAIL"					=> $Lang->data["event_detail"],
			"L_DATE"					=> $Lang->data["general_date"],
			"L_DAY"						=> $Lang->data["general_day"],
			"L_MONTH"					=> $Lang->data["general_month"],
			"L_YEAR"					=> $Lang->data["general_year"],
			"L_TIME"					=> $Lang->data["general_time"],
			"L_TO"						=> $Lang->data["general_time_to"],
			"L_HOUR"					=> $Lang->data["general_hour"],
			"L_MINUTE"					=> $Lang->data["general_minute"],
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

	function do_add_event(){
		global $Session, $Info, $DB, $Template, $Lang, $Func, $File;

		$this->data["day"]				= intval($Func->get_request('day', 0, 'POST'));
		$this->data["month"]			= intval($Func->get_request('month', 0, 'POST'));
		$this->data["year"]				= intval($Func->get_request('year', 0, 'POST'));
		$this->data["from_hour"]		= (isset($_POST["from_hour"]) && ($_POST["from_hour"] >=0 ) && ($_POST["from_hour"] <= 23) ) ? $_POST["from_hour"] : -1;
		if ( $this->data['from_hour'] != -1 ){
			$this->data["from_minute"]		= (isset($_POST["from_minute"]) && ($_POST["from_minute"] >= 0 ) && ($_POST["from_minute"] <= 59) ) ? $_POST["from_minute"] : -1;
			$this->data["to_hour"]			= (isset($_POST["to_hour"]) && ($_POST["to_hour"] >=0 ) && ($_POST["to_hour"] <= 23) ) ? $_POST["to_hour"] : -1;
			if ( $this->data['to_hour'] != -1 ){
				$this->data["to_minute"]	= (isset($_POST["to_minute"]) && ($_POST["to_minute"] >= 0 ) && ($_POST["to_minute"] <= 59) ) ? $_POST["to_minute"] : -1;
			}
			else{
				$this->data["to_minute"]	= -1;
			}
		}
		else{
			$this->data['from_minute']	= -1;
			$this->data['to_hour']		= -1;
			$this->data['to_minute']	= -1;
		}
		$this->data["title"]			= htmlspecialchars($Func->get_request('title', '', 'POST'));
		$this->data["detail"]			= htmlspecialchars($Func->get_request('detail', '', 'POST'));
		$this->data["used_files"]		= $Func->get_request('used_files', '', 'POST');
		$this->data["enabled"]			= intval($Func->get_request('enabled', 0, 'POST'));
		$this->data["page_to"]			= htmlspecialchars($Func->get_request('page_to', '', 'POST'));

		//Check permission ---------------
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$this->data['enabled']	= SYS_DISABLED;
			}
		}
		//--------------------------------

		//Check all required fields
		if ( !$this->data["day"] || !$this->data["month"] || !$this->data["year"] || empty($this->data["title"]) || empty($this->data["detail"]) ){
			$this->pre_add_event($Lang->data["general_error_not_full"]);
			return false;
		}

		//Check date
		if ( !$Func->check_anydate($this->data["month"], $this->data["day"], $this->data["year"]) ){
			$this->pre_add_event($Lang->data["general_error_date"]);
			return false;
		}

		//Check time
		if ( ($this->data['from_hour'] >= 0) && ($this->data['from_minute'] >= 0) && ($this->data['to_hour'] >= 0) && ($this->data['to_minute'] >= 0) ){
			if ( ($this->data['to_hour'] < $this->data['from_hour']) || (($this->data['to_hour'] == $this->data['from_hour']) && ($this->data['to_minute'] < $this->data['from_minute'])) ){
				$this->pre_add_event($Lang->data["event_error_time"]);
				return false;
			}
		}

		if ( !empty($this->data['used_files']) ){
			//Transfer used_files and remove temp files
			$data_info['detail']	= $this->data['detail'];
			$File->transfer_temp_files($this->data["used_files"], $this->upload_path, $data_info);
			$this->data['detail']	= $data_info['detail'];
			//-----------------------------------------
		}

		//Insert event
		$sql		= "INSERT INTO ". $DB->prefix ."event(event_year, event_month, event_day, event_from_hour, event_from_minute, event_to_hour, event_to_minute, title, detail, used_files, enabled)
								VALUES(". $this->data["year"] .", ". $this->data['month'] .", ". $this->data['day'] .", ". $this->data["from_hour"].", ". $this->data["from_minute"].", ". $this->data["to_hour"].", ". $this->data["to_minute"].", '". $this->data["title"]."', '". $this->data["detail"]."', '". $this->data['used_files'] ."', ". $this->data['enabled'] .")";
		$DB->query($sql);
		$event_id	= $DB->insert_id();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $event_id, ACP_INDEX .'?mod=event&act='. FUNC_ACT_VIEW .'&id='. $event_id);

		if ( $this->data['page_to'] == 'pageadd' ){
			$tmp_data['page_to']	= $this->data['page_to'];
//			$this->data	= array();//Reset data
			$this->data	= $tmp_data;
			$this->pre_add_event($Lang->data['general_success_add']);
		}
		else{
			$this->list_events();
		}

		return true;
	}

	function pre_edit_event($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main	= "event_edit";

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

		$DB->query("SELECT * FROM ". $DB->prefix ."event WHERE event_id=$id $auth_where_sql");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["event_error_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=event". $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
		$event_info	= $DB->fetch_array();
		$DB->free_result();

		$Template->set_block_vars("editrow", array());
		$Template->set_vars(array(
			"ERROR_MSG"				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=event&act=edit&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			"MONTH"					=> isset($this->data["month"]) ? $this->data["month"] : $event_info['event_month'],
			"DAY"					=> isset($this->data["day"]) ? $this->data["day"] : $event_info['event_day'],
			"YEAR"					=> isset($this->data["year"]) ? $this->data["year"] : $event_info['event_year'],
			"FROM_HOUR"				=> isset($this->data["from_hour"]) ? $this->data["from_hour"] : $event_info['event_from_hour'],
			"FROM_MINUTE"			=> isset($this->data["from_minute"]) ? $this->data["from_minute"] : $event_info['event_from_minute'],
			"TO_HOUR"				=> isset($this->data["to_hour"]) ? $this->data["to_hour"] : $event_info['event_to_hour'],
			"TO_MINUTE"				=> isset($this->data["to_minute"]) ? $this->data["to_minute"] : $event_info['event_to_minute'],
			"TITLE"					=> isset($this->data["title"]) ? stripslashes($this->data["title"]) : $event_info['title'],
			"DETAIL"				=> isset($this->data["detail"]) ? stripslashes($this->data["detail"]) : $event_info['detail'],
			"USED_FILES"			=> isset($this->data["used_files"]) ? stripslashes($this->data["used_files"]) : '',
			"ENABLED"				=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : $event_info['enabled'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data["menu_miscell_event"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			"L_BUTTON"				=> $Lang->data["button_edit"],
		));
		return true;
	}

	function do_edit_event(){
		global $Session, $Info, $DB, $Template, $Lang, $Func, $File;

		$id				= isset($_GET['id']) ? intval($_GET['id']) : 0;
		$this->data["day"]				= intval($Func->get_request('day', 0, 'POST'));
		$this->data["month"]			= intval($Func->get_request('month', 0, 'POST'));
		$this->data["year"]				= intval($Func->get_request('year', 0, 'POST'));
		$this->data["from_hour"]		= (isset($_POST["from_hour"]) && ($_POST["from_hour"] >=0 ) && ($_POST["from_hour"] <= 23) ) ? $_POST["from_hour"] : -1;
		if ( $this->data['from_hour'] != -1 ){
			$this->data["from_minute"]		= (isset($_POST["from_minute"]) && ($_POST["from_minute"] >= 0 ) && ($_POST["from_minute"] <= 59) ) ? $_POST["from_minute"] : -1;
			$this->data["to_hour"]			= (isset($_POST["to_hour"]) && ($_POST["to_hour"] >=0 ) && ($_POST["to_hour"] <= 23) ) ? $_POST["to_hour"] : -1;
			if ( $this->data['to_hour'] != -1 ){
				$this->data["to_minute"]	= (isset($_POST["to_minute"]) && ($_POST["to_minute"] >= 0 ) && ($_POST["to_minute"] <= 59) ) ? $_POST["to_minute"] : -1;
			}
			else{
				$this->data["to_minute"]	= -1;
			}
		}
		else{
			$this->data['from_minute']	= -1;
			$this->data['to_hour']		= -1;
			$this->data['to_minute']	= -1;
		}
		$this->data["title"]			= htmlspecialchars($Func->get_request('title', '', 'POST'));
		$this->data["detail"]			= htmlspecialchars($Func->get_request('detail', '', 'POST'));
		$this->data["used_files"]		= $Func->get_request('used_files', '', 'POST');
		$this->data["enabled"]			= intval($Func->get_request('enabled', 0, 'POST'));

		//Check permission ---------------
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$this->data['enabled']	= SYS_DISABLED;
			}
		}
		//--------------------------------

		//Check all required fields
		if ( !$this->data["day"] || !$this->data["month"] || !$this->data["year"] || empty($this->data["title"]) || empty($this->data["detail"]) ){
			$this->pre_add_event($Lang->data["general_error_not_full"]);
			return false;
		}

		//Check date
		if ( !$Func->check_anydate($this->data["month"], $this->data["day"], $this->data["year"]) ){
			$this->pre_add_event($Lang->data["general_error_date"]);
			return false;
		}

		//Check time
		if ( ($this->data['from_hour'] >= 0) && ($this->data['from_minute'] >= 0) && ($this->data['to_hour'] >= 0) && ($this->data['to_minute'] >= 0) ){
			if ( ($this->data['to_hour'] < $this->data['from_hour']) || (($this->data['to_hour'] == $this->data['from_hour']) && ($this->data['to_minute'] < $this->data['from_minute'])) ){
				$this->pre_add_event($Lang->data["event_error_time"]);
				return false;
			}
		}

		//Get event info
		$DB->query('SELECT used_files FROM '. $DB->prefix .'event WHERE event_id='. $id);
		if ( !$DB->num_rows() ){
			$this->list_events();
			return false;
		}
		$event_info		= $DB->fetch_array();

		if ( !empty($this->data['used_files']) ){
			//Transfer used_files and remove temp files
			$data_info['detail']	= $this->data['detail'];
			$File->transfer_temp_files($this->data["used_files"], $this->upload_path, $data_info);
			$this->data['detail']	= $data_info['detail'];
			//-----------------------------------------
		}

		//Clean old used files ----------
		$data_info['detail']	= $this->data['detail'];
		$File->clean_used_files($event_info["used_files"], $this->upload_path, $data_info, $this->data['used_files']);
		$this->data['detail']		= $data_info['detail'];
		//-------------------------------

		//Delete files which are not used
		$File->delete_unused_files($this->data["used_files"], $event_info['used_files'], $this->upload_path);

		//Update event
		$DB->query("UPDATE ". $DB->prefix ."event SET event_year=". $this->data["year"] .", event_month=". $this->data['month'] .", event_day=". $this->data['day'] .", event_from_hour=". $this->data["from_hour"].", event_from_minute=". $this->data["from_minute"].", event_to_hour=". $this->data["to_hour"].", event_to_minute=". $this->data["to_minute"].", title='". $this->data["title"]."', detail='". $this->data["detail"]."', used_files='". $this->data['used_files'] ."', enabled=". $this->data['enabled'] ." WHERE event_id=$id");

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=event&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_events();
		return true;
	}

	function view_event(){
		global $DB, $Template, $Lang, $Info;

		$id					= isset($_GET['id']) ? intval($_GET['id']) : 0;
		$Info->tpl_main		= "event_view";
//		$time_format		= $Info->option['time_format'];

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

		$DB->query('SELECT * FROM '. $DB->prefix .'event WHERE event_id='. $id . $auth_where_sql);
		if ( !$DB->num_rows() ){
			$Template->message_die($Lang->data['event_error_not_exist']);
			return false;
		}
		$event_info	= $DB->fetch_array();

		$Template->set_vars(array(
			'TITLE'			=> $event_info['title'],
			'DETAIL'		=> html_entity_decode($event_info['detail']),
			"L_PAGE_TITLE"	=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data['menu_miscell_event'] . $Lang->data['general_arrow'] . $Lang->data['general_view'],
			'L_CLOSE'		=> $Lang->data['general_close_window'],
		));
		return true;
	}

	function active_events($enabled = 0){
		global $DB, $Template, $Func, $Info;

		$event_ids		= $Func->get_request('event_ids', '', 'POST');

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

		$ids_info	= $Func->get_array_value($event_ids);
		if ( sizeof($ids_info) ){
			$log_act	= $enabled ? 'log_enable' : 'log_disable';
			$str_ids	= implode(',', $ids_info);

			//Update events
			$DB->query("UPDATE ". $DB->prefix ."event SET enabled=$enabled WHERE event_id IN (". $str_ids .") $auth_where_sql");
			//Save log
			$Func->save_log(FUNC_NAME, $log_act, $str_ids);
		}

		$this->list_events();
	}

	function delete_events(){
		global $DB, $Template, $Func, $File;

		$event_ids		= $Func->get_request('event_ids', '', 'POST');

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

		$ids_info	= $Func->get_array_value($event_ids);
		if ( sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);
			$where_sql	= " WHERE event_id IN (". $str_ids .")";

			//Get and delete images ------------
			$DB->query("SELECT used_files FROM ". $DB->prefix ."event $where_sql $auth_where_sql");
			$event_count	= $DB->num_rows();
			$event_data		= $DB->fetch_all_array();
			$DB->free_result();

			for ($i=0; $i<$event_count; $i++){
				if ( !empty($event_data[$i]['used_files']) ){
					//Delete images
					$file_info	= explode(',', $event_data[$i]['used_files']);
					reset($file_info);
					while (list(, $filename) = each($file_info) ){
						$filename	= trim($filename);
						if ( !empty($filename) ){
							$File->delete_file($this->upload_path . $filename);
						}
					}
				}
			}
			//------------------------------------

			//Delete events
			$DB->query("DELETE FROM ". $DB->prefix ."event $where_sql $auth_where_sql");
			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $str_ids);
		}

		$this->list_events();
	}
}
?>
