<?php
/* =============================================================== *\
|		Module name:      Admin Web Link							|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_weblink_weblink');
define('FUNC_ACT_VIEW', 'preedit');
//Module language
$Func->import_module_language("admin/lang_weblink". PHP_EX);

$AdminWebLink = new Admin_WebLink;

class Admin_WebLink
{
	var $data			= array();
	var $page			= 1;
	var $upload_path	= './images/weblinks/';

	var $user_perm		= array();

	function Admin_WebLink(){
		global $Info, $Func, $Cache;

		$this->user_perm	= $Func->get_all_perms('menu_weblink_weblink');
		$this->get_filter();
		$this->get_all_cats();
		$this->set_all_cats(0, 0);

		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_weblink();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$Cache->clear_cache('all');
				$this->do_add_weblink();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_weblink();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all');
				$this->do_edit_weblink();
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$Cache->clear_cache('all');
				$this->delete_weblinks();
				break;
			case "update":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all');
				$this->update_order();
				break;
			case "resync":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->resync_weblinks();
				break;
			case "enable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all');
				$this->active_weblinks(1);
				break;
			case "disable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all');
				$this->active_weblinks(0);
				break;
			case "premovecat":
				$Func->check_user_perm($this->user_perm, 'move_email');
				$this->pre_move_cat();
				break;
			case "movecat":
				$Func->check_user_perm($this->user_perm, 'move_email');
				$this->do_move_cat();
				break;
			default:
				$this->list_weblinks();
		}
	}

	function get_filter(){
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

		$this->filter['sort_by']		= isset($_POST["fsort_by"]) ? htmlspecialchars($_POST["fsort_by"]) : '';
		if (empty($this->filter['sort_by'])){
			$this->filter['sort_by']	= isset($_GET["fsort_by"]) ? htmlspecialchars($_GET["fsort_by"]) : '';
		}
		if ( ($this->filter['sort_by'] != "weblink_order") && ($this->filter['sort_by'] != "site_name") && ($this->filter['sort_by'] != "hits")){
			$this->filter['sort_by']	= "weblink_order";
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
			'FSOFT_BY'		=> $this->filter['sort_by'],
			'FSOFT_ORDER'	=> $this->filter['sort_order'],
		));
	}

	function list_weblinks(){
		global $Session, $Func, $DB, $Info, $Template, $Lang;

		$Info->tpl_main		= "weblink_list";
		$itemperpage		= $Info->option['items_per_page'];

		//Filter -----------------------------------
		$where_sql = " WHERE weblink_id>0";
		if ( $this->filter['status'] == 'enabled' ){
			$where_sql	.= " AND enabled=". SYS_ENABLED;
		}
		else if ( $this->filter['status'] == 'disabled' ){
			$where_sql	.= " AND enabled=". SYS_DISABLED;
		}
		else if ( $this->filter['status'] == 'waiting' ){
			$where_sql	.= " AND start_date>". CURRENT_TIME;
		}
		else if ( $this->filter['status'] == 'showing' ){
			$where_sql	.= " AND start_date<=". CURRENT_TIME ." AND (end_date=0 OR end_date>=". CURRENT_TIME .")";
		}
		else if ( $this->filter['status'] == 'expired' ){
			$where_sql	.= " AND end_date>0 AND end_date<". CURRENT_TIME;
		}

		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*", "%", $this->filter['keyword']);
			$where_sql	.= " AND (site_name LIKE '%". $key ."%' OR site_url LIKE '%". $key ."%')";
		}
		//-----------------------------------------

		//Generate pages
		$DB->query("SELECT count(*) AS total FROM ". $DB->prefix ."weblink ". $where_sql);
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX .'?mod=weblink') );
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query("SELECT * FROM ". $DB->prefix ."weblink ". $where_sql ." ORDER BY ". $this->filter['sort_by'] ." ". $this->filter['sort_order'] ." LIMIT ". $pageshow['start'] .", $itemperpage");
		$weblink_count		= $DB->num_rows();
		$weblink_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$weblink_count; $i++){
			//Status
			if ($weblink_data[$i]["start_date"] > CURRENT_TIME){
				$status		= $Lang->data["general_waiting"];
			}
			else if ( $weblink_data[$i]["end_date"] && ($weblink_data[$i]["end_date"] < CURRENT_TIME) ){
				$status		= $Lang->data["general_expired"];
			}
			else{
				$status		= $Lang->data["general_showing"];
			}

			$Template->set_block_vars("weblinkrow", array(
				"ID"			=> $weblink_data[$i]["weblink_id"],
				"SITE_URL"		=> $weblink_data[$i]["site_url"],
				"SITE_NAME"		=> $weblink_data[$i]["site_name"],
				'STATUS'		=> $status,
				"HITS"			=> $weblink_data[$i]["hits"],
				"ORDER"			=> $weblink_data[$i]["weblink_order"],
				"CSS"			=> $weblink_data[$i]['enabled'] ? 'enabled' : 'disabled',
				'BG_CSS'		=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				'U_VIEW'		=> $Session->append_sid(ACP_INDEX .'?mod=weblink&act=manage&lid='. $weblink_data[$i]["weblink_id"]),
				'U_EDIT'		=>  $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink&act=preedit&id='. $weblink_data[$i]["weblink_id"] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
			));
		}

		$Template->set_vars(array(
			"PAGE_OUT"				=> $pageshow['page'],
			'S_ACTION_FILTER'		=> $Session->append_sid(ACP_INDEX .'?mod=weblink'),
			'U_UPDATE'				=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="javascript: updateForm2(\''. $Session->append_sid(ACP_INDEX .'?mod=weblink&act=update' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/update.gif" alt="" title="'. $Lang->data['general_update'] .'" border="0" align="absbottom">'. $Lang->data['general_update'] .'</a> &nbsp; &nbsp;' : '',
			'U_ADD'					=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="'. $Lang->data['general_add'] .'" border="0" align="absbottom">'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp;' : '',
			'U_RESYNC'				=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=weblink&act=resync' . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/resync.gif" alt="" title="'. $Lang->data['general_resync'] .'" border="0" align="absbottom">'. $Lang->data['general_resync'] .'</a>' : '',
			'U_ENABLE'				=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript: updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=weblink&act=enable' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/enable.gif" alt="" title="'. $Lang->data['general_enable'] .'" align="absbottom" border=0>'. $Lang->data['general_enable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DISABLE'				=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=weblink&act=disable' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/disable.gif" alt="" title="'. $Lang->data['general_disable'] .'" align="absbottom" border=0>'. $Lang->data['general_disable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'				=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript: deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=weblink&act=del' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" align="absbottom" border=0>'. $Lang->data['general_del'] .'</a>' : '',
			'U_MOVE_CAT'			=> $Func->check_user_perm($this->user_perm, 'move_weblink', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=weblink&act=premovecat' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/move.gif" alt="" title="'. $Lang->data["weblink_move_cat"] .'" align="absbottom" border=0>'. $Lang->data["weblink_move_cat"] .'</a> &nbsp; &nbsp; ' : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_weblink"] . $Lang->data['general_arrow'] . $Lang->data["menu_weblink_weblink"],
			"L_WAITING"				=> $Lang->data["general_waiting"],
			"L_SHOWING"				=> $Lang->data["general_showing"],
			"L_EXPIRED"				=> $Lang->data["general_expired"],
			"L_ASC"					=> $Lang->data["general_asc"],
			"L_DESC"				=> $Lang->data["general_desc"],
			"L_BUTTON_SEARCH"		=> $Lang->data["button_search"],
			"L_SEARCH_ORDER"		=> $Lang->data["general_search_order"],
			"L_ORDER"				=> $Lang->data["general_order"],
			"L_WEBLINK"				=> $Lang->data["weblink"],
			"L_SITE_NAME"			=> $Lang->data["weblink_site_name"],
			"L_ADDED_DATE"			=> $Lang->data["general_addded_date"],
			'L_DEL_CONFIRM'			=> $Lang->data['weblink_del_confirm'],
			'L_CHOOSE_ITEM'			=> $Lang->data['weblink_error_not_check'],
		));
	}

	function pre_add_weblink($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$Info->tpl_main		= "weblink_edit";
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

		$Template->set_block_vars("addrow", array());
		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=weblink&act=add'),
			'CAT_ID'				=> isset($this->data['cat_id']) ? $this->data['cat_id'] : '',
			'SITE_URL'				=> isset($this->data['site_url']) ? stripslashes($this->data['site_url']) : 'http://',
			'SITE_NAME'				=> isset($this->data['site_name']) ? stripslashes($this->data['site_name']) : '',
			"START_MONTH"			=> isset($this->data["startmonth"]) ? $this->data["startmonth"] : $month,
			"START_DAY"				=> isset($this->data["startday"]) ? $this->data["startday"] : $day,
			"START_YEAR"			=> isset($this->data["startyear"]) ? $this->data["startyear"] : $year,
			"START_TIME"			=> isset($this->data["starttime"]) ? $this->data["starttime"] : $time,
			"END_DATE"				=> isset($this->data["enddate"]) ? $this->data["enddate"] : 'never',
			"END_MONTH"				=> isset($this->data["endmonth"]) ? $this->data["endmonth"] : $month,
			"END_DAY"				=> isset($this->data["endday"]) ? $this->data["endday"] : $day,
			"END_YEAR"				=> isset($this->data["endyear"]) ? $this->data["endyear"] : $year,
			"END_TIME"				=> isset($this->data["endtime"]) ? $this->data["endtime"] : $time,
			"ENABLED"				=> isset($this->data["enabled"]) ? $this->data["enabled"] : '',
			"PAGE_TO"				=> isset($this->data["page_to"]) ? $this->data["page_to"] : '',			
			"L_PAGE_TITLE"			=> $Lang->data["menu_weblink"] . $Lang->data['general_arrow'] . $Lang->data["menu_weblink_weblink"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			"L_BUTTON"				=> $Lang->data["button_add"],
		));
	}

	function set_lang(){
		global $Template, $Lang;

		$Template->set_vars(array(
			"L_CAT"					=> $Lang->data["general_cat"],
			"L_SITE_URL"			=> $Lang->data["weblink_site_url"],
			"L_SITE_NAME"			=> $Lang->data["weblink_site_name"],
			"L_START_DATE"			=> $Lang->data["general_start_date"],
			"L_END_DATE"			=> $Lang->data["general_end_date"],
			"L_PAST_FUTURE"			=> $Lang->data["general_past_future"],
			"L_DATE"				=> $Lang->data["general_date"],
			"L_TIME"				=> $Lang->data["general_time"],
			"L_TIME_EXPLAIN"		=> $Lang->data["general_time_desc"],
			"L_NEVER"				=> $Lang->data["general_never"],
			"L_STATUS"				=> $Lang->data["general_status"],
			"L_CHOOSE"				=> $Lang->data["general_choose"],
			"L_PAGE_TO"				=> $Lang->data["general_page_to"],
			"L_PAGE_ADD"			=> $Lang->data["general_page_add"],
			"L_PAGE_LIST"			=> $Lang->data["general_page_list"],
		));
	}

	function get_all_cats(){
		global $DB;

		$DB->query("SELECT * FROM ". $DB->prefix ."weblink_category ORDER BY cat_order ASC");
		$this->cat_count = $DB->num_rows();
		$this->cat_data  = $DB->fetch_all_array();
		$DB->free_result();
	}

	function set_all_cats($parent_id, $except_cid, $level=0, $symbol="|-- ", $prefix="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"){
		global $Session, $Template;

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
				$Template->set_block_vars("catrow",array(
					'ID'				=> $this->cat_data[$i]['cat_id'],
					'NAME'				=> $this->cat_data[$i]['cat_name'],
					'WEBLINK_COUNTER'	=> $this->cat_data[$i]['weblink_counter'],
					'SUBCAT_COUNTER'	=> $this->cat_data[$i]['children_counter'],
					'PREFIX'			=> $str_prefix .$symbol,
					"U_EDIT_CAT"		=> $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=preedit&id='. $this->cat_data[$i]['cat_id'] .'&page='. $this->page),
					"U_DEL_CAT"			=> $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=predel&id='. $this->cat_data[$i]['cat_id'] .'&page='. $this->page),
				));
				$this->set_all_cats($this->cat_data[$i]['cat_id'], $except_cid, $level+1, $symbol, $prefix);
			}
		}
	}

	function do_add_weblink(){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$this->data['cat_id']			= isset($_POST["cat_id"]) ? intval($_POST["cat_id"]) : 0;
		$this->data['site_url']			= isset($_POST["site_url"]) ? htmlspecialchars($_POST["site_url"]) : '';
		$this->data['site_name']		= isset($_POST["site_name"]) ? htmlspecialchars($_POST["site_name"]) : '';

		$this->data["startmonth"]		= (isset($_POST["start_month"]) && ($_POST["start_month"] >= 1) && ($_POST["start_month"] <= 12) ) ? intval($_POST["start_month"]) : '';
		$this->data["startday"]			= (isset($_POST["start_day"]) && ($_POST["start_day"] >= 1) && ($_POST["start_day"] <= 31) ) ? intval($_POST["start_day"]) : '';
		$this->data["startyear"]		= isset($_POST["start_year"]) ? intval($_POST["start_year"]) : '';
		$this->data["starttime"]		= isset($_POST["start_time"]) ? htmlspecialchars($_POST["start_time"]) : '0:0';
		$this->data["enddate"]			= isset($_POST["end_date"]) ? htmlspecialchars($_POST["end_date"]) : 'never';
		$this->data["endmonth"]			= (isset($_POST["end_month"]) && ($_POST["end_month"] >= 1) && ($_POST["end_month"] <= 12) ) ? intval($_POST["end_month"]) : '';
		$this->data["endday"]			= (isset($_POST["end_day"]) && ($_POST["end_day"] >=1 ) && ($_POST["end_day"] <= 31) ) ? intval($_POST["end_day"]) : '';
		$this->data["endyear"]			= isset($_POST["end_year"]) ? intval($_POST["end_year"]) : '';
		$this->data["endtime"]			= isset($_POST["end_time"]) ? htmlspecialchars($_POST["end_time"]) : '0:0';
		$this->data["enabled"]			= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;
		$this->data["page_to"]			= isset($_POST["page_to"]) ? htmlspecialchars($_POST["page_to"]) : '';

		if ( empty($this->data['site_url']) || ($this->data['site_url'] == 'http://') ){
			$this->pre_add_weblink($Lang->data['general_error_not_full']);
			return false;
		}

		//Check and compile time ------------------------------------
		if ( !empty($this->data["startmonth"]) && !empty($this->data["startday"]) && !empty($this->data["startyear"]) && !empty($this->data["starttime"]) ){
			if ( !checkdate($this->data["startmonth"], $this->data["startday"], $this->data["startyear"]) ){
				$this->pre_add_weblink($Lang->data["general_error_startdate"]);
				return false;
			}
//			$stime	= $Func->make_mydate($this->data["startmonth"], $this->data["startday"], $this->data["startyear"], $this->data["starttime"], CURRENT_TIME);
			$stime	= $Func->make_mydate($this->data["startmonth"], $this->data["startday"], $this->data["startyear"], $this->data["starttime"], CURRENT_TIME, $Info->option['timezone']);
		}
		else{
			$stime	= CURRENT_TIME;
		}

		if ( ($this->data["enddate"] != "never") && !empty($this->data["endmonth"]) && !empty($this->data["endday"]) && !empty($this->data["endyear"]) && !empty($this->data["endtime"]) ){
			if (!checkdate($this->data["endmonth"],$this->data["endday"],$this->data["endyear"])){
				$this->pre_add_poll($Lang->data["poll_error_enddate"]);
				return false;
			}
//			$etime	= $Func->make_mydate($this->data["endmonth"], $this->data["endday"], $this->data["endyear"], $this->data["endtime"], 0);
			$etime	= $Func->make_mydate($this->data["endmonth"], $this->data["endday"], $this->data["endyear"], $this->data["endtime"], 0, $Info->option['timezone']);
		}
		else{
			$etime	= 0;//Never expire
		}
		//---------------------------------------------------------

		//Get max order
		$DB->query("SELECT max(weblink_order) AS max_order FROM ". $DB->prefix ."weblink");
		if ( $DB->num_rows() ){
			$tmp_info		= $DB->fetch_array();
			$max_order		= $tmp_info["max_order"] + 1;
		}
		else{
			$max_order		= 1;
		}
		$DB->free_result();

		$DB->query("INSERT INTO ". $DB->prefix ."weblink(cat_id, site_url, site_name, hits, start_date, end_date, weblink_order, enabled) VALUES(". $this->data['cat_id'] .", '". $this->data['site_url'] ."', '". $this->data['site_name'] ."',0 , $stime, $etime, $max_order, ". $this->data['enabled'] .")");
		$weblink_id	= $DB->insert_id();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $weblink_id, ACP_INDEX .'?mod=weblink&act='. FUNC_ACT_VIEW .'&id='. $weblink_id);

		if ( $this->data['page_to'] == 'pageadd' ){
			$this->data		= array();//Reset data
			$this->pre_add_weblink($Lang->data['general_success_add']);
		}
		else{
			$this->list_weblinks();
		}
		return true;
	}

	function pre_edit_weblink($msg = ""){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main	= "weblink_edit";
		$this->set_lang();

		$DB->query("SELECT * FROM ". $DB->prefix ."weblink WHERE weblink_id=$id");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["weblink_error_not_exist"], $Session->append_sid(ACP_INDEX .'?mod=weblink&page='. $this->page));
			return false;
		}
		$weblink_info	= $DB->fetch_array();
		$DB->free_result();

		//Get date ---------------------------------
/*
		$tmpday		= getdate($weblink_info['start_date']);
		$startmonth	= $tmpday['mon'];
		$startday	= $tmpday['mday'];
		$startyear	= $tmpday['year'];
		$starttime	= $tmpday['hours'] .":". $tmpday['minutes'];
*/
		$date		= explode('-', gmdate('m-d-Y-H-i', $weblink_info['start_date'] + $Info->option['timezone']*3600));
		$startmonth	= intval($date[0]);
		$startday	= intval($date[1]);
		$startyear	= intval($date[2]);
		$starttime	= $date[3] .":". $date[4];

/*
		if ( $weblink_info['end_date'] ){
			$tmpday	= getdate($weblink_info['end_date']);
		}
		else{
			$tmpday	= getdate(CURRENT_TIME);
		}
		$endmonth	= $tmpday['mon'];
		$endday		= $tmpday['mday'];
		$endyear	= $tmpday['year'];
		$endtime	= $tmpday['hours'] .":". $tmpday['minutes'];
*/
		if ( $weblink_info['end_date'] ){
			$date	= explode('-', gmdate('m-d-Y-H-i', $weblink_info['end_date'] + $Info->option['timezone']*3600));
		}
		else{
			$date	= explode('-', gmdate('m-d-Y-H-i', CURRENT_TIME + $Info->option['timezone']*3600));
		}
		$endmonth	= intval($date[0]);
		$endday		= intval($date[1]);
		$endyear	= intval($date[2]);
		$endtime	= $date[3] .":". $date[4];
		//------------------------------------------

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=weblink&act=edit&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			'CAT_ID'				=> isset($this->data['cat_id']) ? $this->data['cat_id'] :  $weblink_info['cat_id'],
			'SITE_URL'				=> isset($this->data['site_url']) ? $this->data['site_url'] :  $weblink_info['site_url'],
			'SITE_NAME'				=> isset($this->data['site_name']) ? stripslashes($this->data['site_name']) :  $weblink_info['site_name'],
			"START_MONTH"			=> isset($this->data["startmonth"]) ? $this->data["startmonth"] : $startmonth,
			"START_DAY"				=> isset($this->data["startday"]) ? $this->data["startday"] : $startday,
			"START_YEAR"			=> isset($this->data["startyear"]) ? $this->data["startyear"] : $startyear,
			"START_TIME"			=> isset($this->data["starttime"]) ? $this->data["starttime"] : $starttime,
			"END_DATE"				=> isset($this->data["enddate"]) ? $this->data["enddate"] : 'never',
			"END_MONTH"				=> isset($this->data["endmonth"]) ? $this->data["endmonth"] : $endmonth,
			"END_DAY"				=> isset($this->data["endday"]) ? $this->data["endday"] : $endday,
			"END_YEAR"				=> isset($this->data["endyear"]) ? $this->data["endyear"] : $endyear,
			"END_TIME"				=> isset($this->data["endtime"]) ? $this->data["endtime"] : $endtime,
			"ENABLED"				=> isset($this->data["enabled"]) ? $this->data["enabled"] : $weblink_info['enabled'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_weblink"] . $Lang->data['general_arrow'] . $Lang->data["menu_weblink_weblink"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			"L_BUTTON"				=> $Lang->data["button_edit"],
		));
		return true;
	}

	function do_edit_weblink(){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$id			= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$this->data['cat_id']			= isset($_POST["cat_id"]) ? intval($_POST["cat_id"]) : 0;
		$this->data['site_url']			= isset($_POST["site_url"]) ? htmlspecialchars($_POST["site_url"]) : '';
		$this->data['site_name']		= isset($_POST["site_name"]) ? htmlspecialchars($_POST["site_name"]) : '';

		$this->data["startmonth"]		= (isset($_POST["start_month"]) && ($_POST["start_month"] >= 1) && ($_POST["start_month"] <= 12) ) ? intval($_POST["start_month"]) : '';
		$this->data["startday"]			= (isset($_POST["start_day"]) && ($_POST["start_day"] >= 1) && ($_POST["start_day"] <= 31) ) ? intval($_POST["start_day"]) : '';
		$this->data["startyear"]		= isset($_POST["start_year"]) ? intval($_POST["start_year"]) : '';
		$this->data["starttime"]		= isset($_POST["start_time"]) ? htmlspecialchars($_POST["start_time"]) : '0:0';
		$this->data["enddate"]			= isset($_POST["end_date"]) ? htmlspecialchars($_POST["end_date"]) : 'never';
		$this->data["endmonth"]			= (isset($_POST["end_month"]) && ($_POST["end_month"] >= 1) && ($_POST["end_month"] <= 12) ) ? intval($_POST["end_month"]) : '';
		$this->data["endday"]			= (isset($_POST["end_day"]) && ($_POST["end_day"] >=1 ) && ($_POST["end_day"] <= 31) ) ? intval($_POST["end_day"]) : '';
		$this->data["endyear"]			= isset($_POST["end_year"]) ? intval($_POST["end_year"]) : '';
		$this->data["endtime"]			= isset($_POST["end_time"]) ? htmlspecialchars($_POST["end_time"]) : '0:0';
		$this->data["enabled"]			= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;

		if ( empty($this->data['site_url']) || ($this->data['site_url'] == 'http://') ){
			$this->pre_edit_weblink($Lang->data['general_error_not_full']);
			return false;
		}

		//Get old info
		$DB->query('SELECT * FROM '. $DB->prefix .'weblink WHERE weblink_id='. $id);
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data['weblink_error_not_exist'], $Session->append_sid(ACP_INDEX .'?mod=weblink&page='. $this->page));
			return false;
		}
//		$weblink_info	= $DB->fetch_array();
		$DB->free_result();

		//Check and compile time ------------------------------------
		if ( !empty($this->data["startmonth"]) && !empty($this->data["startday"]) && !empty($this->data["startyear"]) && !empty($this->data["starttime"]) ){
			if ( !checkdate($this->data["startmonth"], $this->data["startday"], $this->data["startyear"]) ){
				$this->pre_add_weblink($Lang->data["general_error_startdate"]);
				return false;
			}
//			$stime	= $Func->make_mydate($this->data["startmonth"], $this->data["startday"], $this->data["startyear"], $this->data["starttime"], CURRENT_TIME);
			$stime	= $Func->make_mydate($this->data["startmonth"], $this->data["startday"], $this->data["startyear"], $this->data["starttime"], CURRENT_TIME, $Info->option['timezone']);
		}
		else{
			$stime	= CURRENT_TIME;
		}

		if ( ($this->data["enddate"] != "never") && !empty($this->data["endmonth"]) && !empty($this->data["endday"]) && !empty($this->data["endyear"]) && !empty($this->data["endtime"]) ){
			if (!checkdate($this->data["endmonth"],$this->data["endday"],$this->data["endyear"])){
				$this->pre_add_weblink($Lang->data["weblink_error_enddate"]);
				return false;
			}
//			$etime	= $Func->make_mydate($this->data["endmonth"], $this->data["endday"], $this->data["endyear"], $this->data["endtime"], 0);
			$etime	= $Func->make_mydate($this->data["endmonth"], $this->data["endday"], $this->data["endyear"], $this->data["endtime"], 0, $Info->option['timezone']);
		}
		else{
			$etime	= 0;//Never expire
		}
		//---------------------------------------------------------

		$DB->query("UPDATE ". $DB->prefix ."weblink SET cat_id=". $this->data['cat_id'] .", site_url='". $this->data['site_url'] ."', site_name='". $this->data['site_name'] ."',start_date=$stime, end_date=$etime, enabled=". $this->data['enabled'] ." WHERE weblink_id=$id");

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=weblink&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_weblinks();
		return true;
	}

	function active_weblinks($enabled=0){
		global $DB, $Template, $Func;

		$weblink_ids	= isset($_POST['weblink_ids']) ? $_POST['weblink_ids'] : '';
		$ids_info		= $Func->get_array_value($weblink_ids);

		if ( sizeof($ids_info) ){
			$log_act	= $enabled ? 'log_enable' : 'log_disable';
			$str_ids	= implode(',', $ids_info);

			//Update weblink
			$DB->query("UPDATE ". $DB->prefix ."weblink SET enabled=$enabled WHERE weblink_id IN (". $str_ids .")");
			//Save log
			$Func->save_log(FUNC_NAME, $log_act, $str_ids);
		}
		$this->list_weblinks();
	}

	function delete_weblinks(){
		global $DB, $Template, $Func;

		$weblink_ids	= isset($_POST['weblink_ids']) ? $_POST['weblink_ids'] : '';
		$ids_info		= $Func->get_array_value($weblink_ids);

		if ( sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);

			//Delete from db
			$DB->query("DELETE FROM ". $DB->prefix ."weblink WHERE weblink_id IN (". $str_ids .")");
			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $str_ids);
		}

		$this->list_weblinks();
	}

	function pre_move_cat(){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$weblink_ids		= isset($_POST['weblink_ids']) ? $_POST['weblink_ids'] : '';
		$Info->tpl_main		= "weblink_move_cat";
		$ids_info			= $Func->get_array_value($weblink_ids);

		if ( !sizeof($ids_info) ){
			$this->list_weblinks();
			return false;
		}

		//Get weblink list
		$str_ids	= implode(',', $ids_info);
		$DB->query('SELECT weblink_id, site_url FROM '. $DB->prefix .'weblink WHERE weblink_id IN ('. $str_ids .')');
		$weblink_count	= $DB->num_rows();
		$weblink_data	= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$weblink_count; $i++){
			$Template->set_block_vars("weblinkrow", array(
				'SITE_URL'		=> $weblink_data[$i]['site_url'],
				'U_VIEW'		=> $Session->append_sid(ACP_INDEX .'?mod=weblink&act=view&id='. $weblink_data[$i]["weblink_id"]),
			));
		}

		$Template->set_vars(array(
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=weblink&act=movecat'. $this->filter['url_append'] .'&page='. $this->page),
			'WEBLINK_IDS'			=> $str_ids,
			'WEBLINK_COUNT'			=> $weblink_count,
			'L_WEBLINK_COUNT'		=> $Lang->data['weblinks'],
			'L_MOVE_TO_CAT'			=> $Lang->data['weblink_move_cat'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_weblink"] . $Lang->data['general_arrow'] . $Lang->data["menu_weblink_weblink"] . $Lang->data['general_arrow'] . $Lang->data["weblink_move_cat"],
			"L_BUTTON"				=> $Lang->data["button_move"],
		));
		return true;
	}

	function do_move_cat(){
		global $DB, $Template, $Lang, $Info, $Func;

		$weblink_ids	= isset($_POST['weblink_ids']) ? explode(',', $_POST['weblink_ids']) : '';
		$cat_id			= isset($_POST['cat_id']) ? intval($_POST['cat_id']) : 0;
		$ids_info		= $Func->get_array_value($weblink_ids);

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

		if ( $cat_id && sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);

			//Update weblink
			$DB->query('UPDATE '. $DB->prefix .'weblink SET cat_id='. $cat_id .' WHERE weblink_id IN ('. $str_ids .') '. $auth_where_sql);
			//Update cat_counter
			$this->resync_cats();
			//Save log
			$Func->save_log(FUNC_NAME, 'log_move_cat', $str_ids);
		}

		$this->list_weblinks();
	}

	function update_order(){
		global $Session, $Template, $Lang, $DB, $Func;

		$weblink_orders	= isset($_POST["weblink_orders"]) ? $_POST["weblink_orders"] : '';

		if ( is_array($weblink_orders) ){
			reset($weblink_orders);
			while ( list($id, $num) = each($weblink_orders) ){
				$DB->query("UPDATE ". $DB->prefix ."weblink SET weblink_order=". intval($num) ." WHERE weblink_id=". intval($id));
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_update');

		$this->list_weblinks();
	}

	function resync_weblinks(){
		global $DB, $Template, $Lang, $Func;

		$DB->query('SELECT weblink_id, weblink_order FROM '. $DB->prefix .'weblink ORDER BY weblink_order ASC');
		$weblink_count		= $DB->num_rows();
		$weblink_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$weblink_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'weblink SET weblink_order='. ($i + 1) .' WHERE weblink_id='. $weblink_data[$i]['weblink_id']);
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_resync');
		$this->list_weblinks();
	}

	function resync_cats(){
		global $Session, $DB, $Template, $Lang;

		$DB->query('UPDATE '. $DB->prefix .'weblink_category SET weblink_counter=0');

		//Update weblink_counter
		$DB->query('SELECT count(weblink_id) AS counter, cat_id FROM '. $DB->prefix.'weblink GROUP BY cat_id');
		$cat_count = $DB->num_rows();
		$cat_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$cat_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'weblink_category SET weblink_counter='. $cat_data[$i]['counter'] .' WHERE cat_id='. $cat_data[$i]['cat_id']);
		}
	}
}
?>