<?php
/* =============================================================== *\
|		Module name:      Poll										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_miscell_poll');
define('FUNC_ACT_VIEW', 'view');
//Module language
$Func->import_module_language("admin/lang_poll". PHP_EX);

$AdminPoll = new Admin_Poll;

class Admin_Poll
{
	var $data		= array();
	var $cat		= array();
	var	$filter		= array();
	var $page		= 1;

	var $user_perm		= array();

	function Admin_Poll(){
		global $Info, $Func;

		$this->user_perm	= $Func->get_all_perms('menu_miscell_poll');
		$this->get_filter();

		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_poll();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->do_add_poll();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_poll();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->do_edit_poll();
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$this->delete_polls();
				break;
			case "update":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->update_order();
				break;
			case "resync":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->resync_polls();
				break;
			case "enable":
				$Func->check_user_perm($this->user_perm, 'active');
				$this->active_polls(1);
				break;
			case "disable":
				$Func->check_user_perm($this->user_perm, 'active');
				$this->active_polls(0);
				break;
			case "view":
				$Func->check_user_perm($this->user_perm, 'view');
				$this->view_poll();
				break;
			default:
				$this->list_polls();
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

		$this->filter['cat_id']			= isset($_POST["fcat_id"]) ? intval($_POST["fcat_id"]) : 0;
		if (!$this->filter['cat_id']){
			$this->filter['cat_id']	= isset($_GET["fcat_id"]) ? intval($_GET["fcat_id"]) : 0;
		}
		if ( $this->filter['cat_id'] ){
			$this->filter['url_append']	.= '&fcat_id='. $this->filter['cat_id'];
		}

		$this->filter['sort_by']		= isset($_POST["fsort_by"]) ? htmlspecialchars($_POST["fsort_by"]) : '';
		if (empty($this->filter['sort_by'])){
			$this->filter['sort_by']	= isset($_GET["fsort_by"]) ? htmlspecialchars($_GET["fsort_by"]) : '';
		}
		if ( ($this->filter['sort_by'] != "poll_order") && ($this->filter['sort_by'] != "question") && ($this->filter['sort_by'] != "total_hits")){
			$this->filter['sort_by']	= "poll_order";
		}
		$this->filter['url_append']	.= '&fsort_by='. $this->filter['sort_by'];

		$this->filter['sort_order']		= isset($_POST["fsort_order"]) ? htmlspecialchars($_POST["fsort_order"]) : '';
		if (empty($this->filter['sort_order'])){
			$this->filter['sort_order']	= isset($_GET["fsort_order"]) ? htmlspecialchars($_GET["fsort_order"]) : '';
		}
		if ( ($this->filter['sort_order'] != "desc") && ($this->filter['sort_order'] != "asc") ){
			$this->filter['sort_order']	= "desc";
		}
		$this->filter['url_append']	.= '&fsort_order='. $this->filter['sort_order'];

		$this->page		= (isset($_GET["page"]) && ($_GET["page"] > 0)) ? intval($_GET["page"]) : 1;

		$Template->set_vars(array(
			'FKEYWORD'		=> $this->filter['keyword'],
			'FSTATUS'		=> $this->filter['status'],
			'FCAT_ID'		=> $this->filter['cat_id'],
			'FSOFT_BY'		=> $this->filter['sort_by'],
			'FSOFT_ORDER'	=> $this->filter['sort_order'],
		));
	}

	function list_polls(){
		global $Session, $Func, $Info, $DB, $Template, $Lang;

		$itemperpage		= $Info->option['items_per_page'];
		$date_format		= $Info->option['date_format'];
		$timezone			= $Info->option['timezone'] * 3600;
		$Info->tpl_main		= "poll_list";

		$this->get_all_cats();
		$this->set_all_cats(0, 0);

		//Filter -----------------------------------
		$where_sql = " WHERE P.poll_id=O.poll_id";
		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*","%",$this->filter['keyword']);
			$where_sql	.= " AND (P.question LIKE '%".$key."%' OR O.option_title LIKE '%".$key."%')";
		}
		
		if ( $this->filter['status'] == 'enabled' ){
			$where_sql	.= " AND P.enabled=". SYS_ENABLED;
		}
		else if ( $this->filter['status'] == 'disabled' ){
			$where_sql	.= " AND P.enabled=". SYS_DISABLED;
		}
		else if ( $this->filter['status'] == 'waiting' ){
			$where_sql	.= " AND P.start_date>". CURRENT_TIME;
		}
		else if ( $this->filter['status'] == 'showing' ){
			$where_sql	.= " AND P.start_date<=". CURRENT_TIME ." AND (P.end_date=0 OR P.end_date>=". CURRENT_TIME .")";
		}
		else if ( $this->filter['status'] == 'expired' ){
			$where_sql	.= " AND P.end_date>0 AND P.end_date<". CURRENT_TIME;
		}
		
		if ( $this->filter['cat_id'] ){
			$where_sql	.= " AND P.cat_id LIKE '%,". $this->filter['cat_id'] .",%'";
		}
		//-----------------------------------------

		//Generate pages
		$DB->query("SELECT count(P.poll_id) AS total FROM ". $DB->prefix ."poll AS P, ". $DB->prefix ."poll_option AS O $where_sql GROUP BY P.poll_id");
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=poll". $this->filter['url_append']));
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query("SELECT P.* FROM ". $DB->prefix ."poll AS P, ". $DB->prefix ."poll_option AS O $where_sql GROUP BY P.poll_id ORDER BY ". $this->filter['sort_by'] ." ". $this->filter['sort_order'] ." LIMIT ". $pageshow['start'] .",$itemperpage");
		$poll_count	= $DB->num_rows();
		$poll_data	= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$poll_count; $i++){
			if ($poll_data[$i]["start_date"] > CURRENT_TIME){
				$status		= $Lang->data["general_waiting"];
			}
			else if ( $poll_data[$i]["end_date"] && ($poll_data[$i]["end_date"] < CURRENT_TIME) ){
				$status		= $Lang->data["general_expired"];
			}
			else{
				$status		= $Lang->data["general_running"];
			}

			$Template->set_block_vars("pollrow", array(
				"ID"			=> $poll_data[$i]["poll_id"],
				"ORDER"			=> $poll_data[$i]["poll_order"],
				"QUESTION"		=> $poll_data[$i]["question"],
				"STATUS"		=> $status,
				"DATE"			=> $Func->translate_date(gmdate($date_format, $poll_data[$i]["start_date"] + $timezone)),
				"HITS"			=> $poll_data[$i]['total_hits'],
				"CSS"			=> $poll_data[$i]['enabled'] ? 'enabled' : 'disabled',
				'BG_CSS'		=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				'U_VIEW'		=> $Session->append_sid(ACP_INDEX .'?mod=poll&act=view&id='. $poll_data[$i]["poll_id"]),
				'U_EDIT'		=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=poll&act=preedit&id='. $poll_data[$i]["poll_id"] . $this->filter['url_append'] .'&page='. $this->page) .'" title="'. $Lang->data['general_edit'] .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
			));
		}

		$Template->set_vars(array(
			"PAGE_OUT"				=> $pageshow['page'],
			'S_ACTION_FILTER'		=> $Session->append_sid(ACP_INDEX .'?mod=poll'),
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=poll&act=update' . $this->filter['url_append'] .'&page='. $this->page),
			'U_UPDATE'				=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="javascript: updateForm2(\''. $Session->append_sid(ACP_INDEX .'?mod=poll&act=update' . $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/update.gif" alt="" title="'. $Lang->data['general_update'] .'" border="0" align="absbottom">'. $Lang->data['general_update'] .'</a> &nbsp; &nbsp;' : '',
			'U_ADD'					=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=poll&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="'. $Lang->data['general_add'] .'" border="0" align="absbottom">'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp;' : '',
			'U_RESYNC'				=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=poll&act=resync' . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/resync.gif" alt="" title="'. $Lang->data['general_resync'] .'" border="0" align="absbottom">'. $Lang->data['general_resync'] .'</a>' : '',
			'U_ENABLE'				=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript: updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=poll&act=enable'. $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/enable.gif" alt="" title="'. $Lang->data['general_enable'] .'" align="absbottom" border=0>'. $Lang->data['general_enable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DISABLE'				=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript: updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=poll&act=disable'. $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/disable.gif" alt="" title="'. $Lang->data['general_disable'] .'" align="absbottom" border=0>'. $Lang->data['general_disable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'				=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript: deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=poll&act=del'. $this->filter['url_append'] .'&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" align="absbottom" border=0>'. $Lang->data['general_del'] .'</a>' : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data["menu_miscell_poll"],
			"L_HOME_PAGE"			=> $Lang->data["general_home_page"],
			"L_WAITING"				=> $Lang->data["general_waiting"],
			"L_SHOWING"				=> $Lang->data["general_showing"],
			"L_EXPIRED"				=> $Lang->data["general_expired"],
			"L_SHOW_PAGE"			=> $Lang->data["general_show_page"],
			"L_ASC"					=> $Lang->data["general_asc"],
			"L_DESC"				=> $Lang->data["general_desc"],
			"L_DATE"				=> $Lang->data["general_start_date"],
			"L_BUTTON_SEARCH"		=> $Lang->data["button_search"],
			"L_ORDER"				=> $Lang->data["general_order"],
			"L_SEARCH_ORDER"		=> $Lang->data["poll_order"],
			"L_QUESTION"			=> $Lang->data["poll_question"],
			"L_QUESTION_DESC"		=> $Lang->data["poll_question_desc"],
			"L_HITS"				=> $Lang->data["poll_hits"],
			"L_UPDATE_ORDER"		=> $Lang->data["general_update"],
			'L_DEL_CONFIRM'			=> $Lang->data['poll_del_confirm'],
			'L_CHOOSE_ITEM'			=> $Lang->data['poll_error_not_check'],
		));
	}

	function pre_add_poll($msg = ""){
		global $Session, $Info, $DB, $Template, $Lang;

		$this->get_all_cats();
		$this->set_all_cats(0, 0);
		$this->set_lang();

		$this->poll_options($Info->option['poll_options']);
		$Info->tpl_main		= "poll_edit";

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

		$Template->set_vars(array(
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=poll&act=add'),
			"ERROR_MSG"				=> $msg,
			"ALLMOREOPT"			=> isset($this->data["allmoreopt"]) ? $this->data["allmoreopt"] : 0,
			"QUESTION"				=> isset($this->data["question"]) ? $this->data["question"] : '',
			"START_MONTH"			=> isset($this->data["startmonth"]) ? $this->data["startmonth"] : $month,
			"START_DAY"				=> isset($this->data["startday"]) ? $this->data["startday"] : $day,
			"START_YEAR"			=> isset($this->data["startyear"]) ? $this->data["startyear"] : $year,
			"START_TIME"			=> isset($this->data["starttime"]) ? $this->data["starttime"] : $time,
			"END_DATE"				=> isset($this->data["enddate"]) ? $this->data["enddate"] : 'never',
			"END_MONTH"				=> isset($this->data["endmonth"]) ? $this->data["endmonth"] : $month,
			"END_DAY"				=> isset($this->data["endday"]) ? $this->data["endday"] : $day,
			"END_YEAR"				=> isset($this->data["endyear"]) ? $this->data["endyear"] : $year,
			"END_TIME"				=> isset($this->data["endtime"]) ? $this->data["endtime"] : $time,
			"MULTIPLE"				=> isset($this->data["multiple"]) ? $this->data["multiple"] : 0,
			"CAT_ID"				=> isset($this->data["cat_id"]) ? $this->data["cat_id"] : -1,
			"STATUS"				=> isset($this->data["status"]) ? $this->data["status"] : 1,
			"L_PAGE_TITLE"			=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data["menu_miscell_poll"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			"L_BUTTON"				=> $Lang->data["button_add"],
		));
	}

	function set_lang(){
		global $Template, $Lang;

		$Template->set_vars(array(
			"L_QUESTION"			=> $Lang->data["poll_question"],
			"L_MORE_OPTIONS"		=> $Lang->data["poll_more_options"],
			"L_OPTION_ORDER"		=> $Lang->data["poll_option_order"],
			"L_GO"					=> $Lang->data["button_go"],
			"L_START_DATE"			=> $Lang->data["general_start_date"],
			"L_END_DATE"			=> $Lang->data["general_end_date"],
			"L_MULTIPLE_CHOICE"		=> $Lang->data["poll_multiple_choice"],
			"L_SHOW_CATS"			=> $Lang->data["poll_show_cat"],
			"L_ALL_CATS"			=> $Lang->data["poll_all_cats"],
			"L_HOME_PAGE"			=> $Lang->data["general_home_page"],
			"L_YES"					=> $Lang->data["general_yes"],
			"L_NO"					=> $Lang->data["general_no"],
			"L_PAST_FUTURE"			=> $Lang->data["general_past_future"],
			"L_DATE"				=> $Lang->data["general_date"],
			"L_TIME"				=> $Lang->data["general_time"],
			"L_TIME_EXPLAIN"		=> $Lang->data["general_time_desc"],
			"L_NEVER"				=> $Lang->data["general_never"],
			"L_POLL_STATUS"			=> $Lang->data["general_status"],
		));
	}

	function get_all_cats(){
		global $DB;

		$DB->query("SELECT * FROM ". $DB->prefix ."article_category WHERE redirect_url='' ORDER BY cat_order ASC");
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
					'ARTICLE_COUNTER'	=> $this->cat_data[$i]['article_counter'],
					'SUBCAT_COUNTER'	=> $this->cat_data[$i]['article_counter'],
					'PREFIX'			=> $str_prefix .$symbol,
					"U_EDIT_CAT"		=> $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=preedit&id='. $this->cat_data[$i]['cat_id'] .'&page='. $this->page),
					"U_DEL_CAT"			=> $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=predel&id='. $this->cat_data[$i]['cat_id'] .'&page='. $this->page),
				));
				$this->set_all_cats($this->cat_data[$i]['cat_id'], $except_cid, $level+1, $symbol, $prefix);
			}
		}
	}

	function poll_options($option_config){
		global $Template, $Info, $Lang;

		$options = (isset($this->data["allmoreopt"])) ? $option_config + $this->data["allmoreopt"] : $option_config;

		for ($i=1; $i<=$options; $i++){
			$Template->set_block_vars("optionrow",array(
				"NUMBER"		=> $i,
				"NAME"			=> $Lang->data["poll_option"] ." ". $i,
				"VALUE"			=> isset($this->data["options"][$i]) ? $this->data["options"][$i] : '',
			));
		}
	}

	function do_add_poll(){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$this->data["question"]		= isset($_POST["question"]) ? htmlspecialchars($_POST["question"]) : '';
		$this->data["options"]		= isset($_POST["opt"]) ? $_POST["opt"] : '';
		$this->data["startmonth"]	= (isset($_POST["start_month"]) && ($_POST["start_month"] >= 1) && ($_POST["start_month"] <= 12) ) ? intval($_POST["start_month"]) : '';
		$this->data["startday"]		= (isset($_POST["start_day"]) && ($_POST["start_day"] >= 1) && ($_POST["start_day"] <= 31) ) ? intval($_POST["start_day"]) : '';
		$this->data["startyear"]	= isset($_POST["start_year"]) ? intval($_POST["start_year"]) : '';
		$this->data["starttime"]	= isset($_POST["start_time"]) ? htmlspecialchars($_POST["start_time"]) : '0:0';
		$this->data["enddate"]		= isset($_POST["end_date"]) ? htmlspecialchars($_POST["end_date"]) : 'never';
		$this->data["endmonth"]		= (isset($_POST["end_month"]) && ($_POST["end_month"] >= 1) && ($_POST["end_month"] <= 12) ) ? intval($_POST["end_month"]) : '';
		$this->data["endday"]		= (isset($_POST["end_day"]) && ($_POST["end_day"] >=1 ) && ($_POST["end_day"] <= 31) ) ? intval($_POST["end_day"]) : '';
		$this->data["endyear"]		= isset($_POST["end_year"]) ? intval($_POST["end_year"]) : '';
		$this->data["endtime"]		= isset($_POST["end_time"]) ? htmlspecialchars($_POST["end_time"]) : '0:0';
		$this->data["multiple"]		= isset($_POST["smultiple"]) ? intval($_POST["smultiple"]) : 0;
		$this->data["cat_id"]		= isset($_POST["cat_id"]) ? $_POST["cat_id"] : '';
		$this->data["enabled"]		= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;

		$this->data["morecheck"]	= isset($_POST["morecheck"]) ? intval($_POST["morecheck"]) : 0;
		$this->data["allmoreopt"]	= isset($_POST["allmoreopt"]) ? intval($_POST["allmoreopt"]) : 0;
		$this->data["morenum"]		= isset($_POST["morenum"]) ? intval($_POST["morenum"]) : 0;

		if ( $this->data["morecheck"] ){
			$this->data["allmoreopt"]	+= $this->data["morenum"];
			$this->pre_add_poll();
			return false;
		}

		//Check if poll has at least 2 options
		$opt	= 0;
		$opt	= $this->count_options($this->data["options"], $opt);
		if ( empty($this->data["question"]) || ($opt < 2) || empty($this->data['cat_id']) ){
			$this->pre_add_poll($Lang->data["general_error_not_full"]);
			return false;
		}

		if ( !empty($this->data["startmonth"]) && !empty($this->data["startday"]) && !empty($this->data["startyear"]) && !empty($this->data["starttime"]) ){
			if (!checkdate($this->data["startmonth"], $this->data["startday"], $this->data["startyear"])){
				$this->pre_add_poll($Lang->data["general_error_startdate"]);
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
				$this->pre_add_poll($Lang->data["general_error_enddate"]);
				return false;
			}
//			$etime	= $Func->make_mydate($this->data["endmonth"], $this->data["endday"], $this->data["endyear"], $this->data["endtime"], 0);
			$etime	= $Func->make_mydate($this->data["endmonth"], $this->data["endday"], $this->data["endyear"], $this->data["endtime"], 0, $Info->option['timezone']);
		}
		else{
			$etime	= 0;//Never expire
		}

		//Check : is startdate before enddate?
		if ( $etime && ($stime >= $etime) ){
			$this->pre_add_poll($Lang->data["general_error_startenddate"]);
			return false;
		}

		//Get max poll order
		$DB->query("SELECT max(poll_order) AS max_order FROM ". $DB->prefix ."poll");
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$max_order	= $result["max_order"] + 1;
		}
		else{
			$max_order	= 1;
		}
		$DB->free_result();

		//Compile cats
		if ( is_array($this->data['cat_id']) ){
			$cat_id	= ',';
			reset($this->data['cat_id']);
			while ( list(, $cid) = each($this->data['cat_id']) ){
				$cat_id	.= intval($cid) . ',';
			}
		}
		else{
			$cat_id	= '';
		}

		//Insert poll
		$sql = "INSERT INTO ". $DB->prefix ."poll(cat_id, question, start_date, end_date, multiple, poll_order, total_hits, enabled)
								VALUES('". $cat_id ."', '". $this->data["question"] ."', $stime, $etime, ". $this->data["multiple"] .", $max_order, 0, ". $this->data["enabled"] .")";
		$DB->query($sql);
		$poll_id	= $DB->insert_id();

		//Insert options
		$i		= 1;
		reset($this->data["options"]);
		while ( list(, $val) = each($this->data["options"]) ){
			if ( !empty($val) ){
				$DB->query("INSERT INTO ". $DB->prefix ."poll_option(poll_id, option_title, option_order, option_hits) VALUES($poll_id, '". htmlspecialchars($val) ."', $i, 0)");
				$i++;
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $poll_id, ACP_INDEX .'?mod=poll&act='. FUNC_ACT_VIEW .'&id='. $poll_id);

		$this->list_polls();
		return true;
	}

	function pre_edit_poll($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$id = isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main	= "poll_edit";
		$this->get_all_cats();
		$this->set_all_cats(0, 0);
		$this->set_lang();

		$DB->query("SELECT * FROM ". $DB->prefix ."poll WHERE poll_id=$id");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["poll_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=poll"));
			return false;
		}
		$poll_info = $DB->fetch_array();
		$DB->free_result();

		$DB->query("SELECT * FROM ". $DB->prefix ."poll_option WHERE poll_id=$id ORDER BY option_order ASC");
		$option_count	= $DB->num_rows();
		$option_data	= $DB->fetch_all_array();
		$DB->free_result();

		//Set options
		for ($i=0; $i<$option_count; $i++){
			$Template->set_block_vars("oldoptrow", array(
				"ID"			=> $option_data[$i]['option_id'],
				"NAME"			=> $Lang->data["poll_old_option"] .' '. ($i + 1),
				"TITLE"			=> $option_data[$i]['option_title'],
				"ORDER"			=> $option_data[$i]['option_order'],
			));
		}
		$this->poll_options(3);

		//Compile cats
		if ( !empty($poll_info["cat_id"]) ){
			$cat_data	= explode(',', $poll_info["cat_id"]);
			reset($cat_data);
			while ( list(, $cid) = each($cat_data) ){
				if ( !empty($cid) ){
					$Template->set_block_vars('catvalrow', array(
						'ID'	=> $cid,
					));
				}
			}
		}

/*
		$tmpday		= getdate($poll_info['start_date']);
		$startmonth	= $tmpday['mon'];
		$startday	= $tmpday['mday'];
		$startyear	= $tmpday['year'];
		$starttime	= $tmpday['hours'] .":". $tmpday['minutes'];
*/
		$date		= explode('-', gmdate('m-d-Y-H-i', $poll_info['start_date'] + $Info->option['timezone']*3600));
		$startmonth	= intval($date[0]);
		$startday	= intval($date[1]);
		$startyear	= intval($date[2]);
		$starttime	= $date[3] .":". $date[4];

/*
		if ( $poll_info['end_date'] ){
			$tmpday	= getdate($poll_info['end_date']);
		}
		else{
			$tmpday	= getdate(CURRENT_TIME);
		}
		$endmonth	= $tmpday['mon'];
		$endday		= $tmpday['mday'];
		$endyear	= $tmpday['year'];
		$endtime	= $tmpday['hours'] .":". $tmpday['minutes'];
*/
		if ( $poll_info['end_date'] ){
			$date	= explode('-', gmdate('m-d-Y-H-i', $poll_info['end_date'] + $Info->option['timezone']*3600));
		}
		else{
			$date	= explode('-', gmdate('m-d-Y-H-i', CURRENT_TIME + $Info->option['timezone']*3600));
		}
		$endmonth	= intval($date[0]);
		$endday		= intval($date[1]);
		$endyear	= intval($date[2]);
		$endtime	= $date[3] .":". $date[4];

		$Template->set_vars(array(
			"ERROR_MSG"         	=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=poll&act=edit&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			"ALLMOREOPT"			=> isset($this->data["allmoreopt"]) ? $this->data["allmoreopt"] : 0,
			"QUESTION"				=> isset($this->data["question"]) ? $this->data["question"] : $poll_info['question'],
			"START_MONTH"			=> isset($this->data["startmonth"]) ? $this->data["startmonth"] : $startmonth,
			"START_DAY"				=> isset($this->data["startday"]) ? $this->data["startday"] : $startday,
			"START_YEAR"			=> isset($this->data["startyear"]) ? $this->data["startyear"] : $startyear,
			"START_TIME"			=> isset($this->data["starttime"]) ? $this->data["starttime"] : $starttime,
			"END_DATE"				=> isset($this->data["enddate"]) ? $this->data["enddate"] : ($poll_info["end_date"] ? 'past_future' : 'never'),
			"END_MONTH"				=> isset($this->data["endmonth"]) ? $this->data["endmonth"] : $endmonth,
			"END_DAY"				=> isset($this->data["endday"]) ? $this->data["endday"] : $endday,
			"END_YEAR"				=> isset($this->data["endyear"]) ? $this->data["endyear"] : $endyear,
			"END_TIME"				=> isset($this->data["endtime"]) ? $this->data["endtime"] : $endtime,
			"MULTIPLE"				=> isset($this->data["multiple"]) ? $this->data["multiple"] : $poll_info['multiple'],
			"ENABLED"				=> isset($this->data["enabled"]) ? $this->data["enabled"] : $poll_info['enabled'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data["menu_miscell_poll"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			"L_BUTTON"				=> $Lang->data["button_edit"],
		));
		$Template->set_block_vars("editrow",array());
		return true;
	}

	function do_edit_poll(){
		global $Session, $DB, $Info, $Template, $Lang, $Func;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$this->data["question"]		= isset($_POST["question"]) ? htmlspecialchars($_POST["question"]) : '';
		$this->data["oldoptions"]	= isset($_POST["oldopt"]) ? $_POST["oldopt"] : '';
		$this->data["oldorder"]		= isset($_POST["oldoptorder"]) ? $_POST["oldoptorder"] : '';
		$this->data["options"]		= isset($_POST["opt"]) ? $_POST["opt"] : '';
		$this->data["order"]		= isset($_POST["optorder"]) ? $_POST["optorder"] : '';
		$this->data["startmonth"]	= (isset($_POST["start_month"])&&($_POST["start_month"]>=1)&&($_POST["start_month"]<=12)) ? intval($_POST["start_month"]) : '';
		$this->data["startday"]		= (isset($_POST["start_day"])&&($_POST["start_day"]>=1)&&($_POST["start_day"]<=31)) ? intval($_POST["start_day"]) : '';
		$this->data["startyear"]	= isset($_POST["start_year"]) ? intval($_POST["start_year"]) : '';
		$this->data["starttime"]	= isset($_POST["start_time"]) ? htmlspecialchars($_POST["start_time"]) : '0:0';
		$this->data["enddate"]		= isset($_POST["end_date"]) ? htmlspecialchars($_POST["end_date"]) : 'never';
		$this->data["endmonth"]		= (isset($_POST["end_month"])&&($_POST["end_month"]>=1)&&$_POST["end_month"]<=12) ? intval($_POST["end_month"]) : '';
		$this->data["endday"]		= (isset($_POST["end_day"])&&($_POST["end_day"]>=1)&&($_POST["end_day"]<=31)) ? intval($_POST["end_day"]) : '';
		$this->data["endyear"]		= isset($_POST["end_year"]) ? intval($_POST["end_year"]) : '';
		$this->data["endtime"]		= isset($_POST["end_time"]) ? htmlspecialchars($_POST["end_time"]) : '0:0';
		$this->data["multiple"]		= isset($_POST["smultiple"]) ? intval($_POST["smultiple"]) : 0;
		$this->data["cat_id"]		= isset($_POST["cat_id"]) ? $_POST["cat_id"] : '';
		$this->data["enabled"]		= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;

		$this->data["morecheck"]	= isset($_POST["morecheck"]) ? intval($_POST["morecheck"]) : 0;
		$this->data["allmoreopt"]	= isset($_POST["allmoreopt"]) ? intval($_POST["allmoreopt"]) : 0;
		$this->data["morenum"]		= isset($_POST["morenum"]) ? intval($_POST["morenum"]) : 0;

		if ( $this->data["morecheck"] ){
			$this->data["allmoreopt"] += $this->data["morenum"];
			$this->pre_edit_poll();
			return false;
		}

		//Check if poll has at least 2 options
		$opt	= 0;
		$opt	= $this->count_options($this->data["oldoptions"], $opt);
		$opt	= $this->count_options($this->data["options"], $opt);
		if ( empty($this->data["question"]) || ($opt < 2) ){
			$this->pre_edit_poll($Lang->data["general_error_not_full"]);
			return false;
		}

		//Set Start Date and End Date of this poll
		//-------------------------------------------------------------
		if ( !empty($this->data["startmonth"]) && !empty($this->data["startday"]) && !empty($this->data["startyear"]) && !empty($this->data["starttime"]) ){
			if ( !checkdate($this->data["startmonth"], $this->data["startday"], $this->data["startyear"]) ){
				$this->pre_edit_poll($Lang->data["general_error_startdate"]);
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
				$this->pre_edit_poll($Lang->data["general_error_enddate"]);
				return false;
			}
//			$etime	= $Func->make_mydate($this->data["endmonth"], $this->data["endday"], $this->data["endyear"], $this->data["endtime"], 0);
			$etime	= $Func->make_mydate($this->data["endmonth"], $this->data["endday"], $this->data["endyear"], $this->data["endtime"], 0, $Info->option['timezone']);
		}
		else{
			$etime	= 0;//Never expire
		}

		//Check : is startdate before enddate?
		if ( ($etime != 0) && ($stime >= $etime) ){
			$this->pre_edit_poll($Lang->data["general_error_startenddate"]);
			return false;
		}
		//-------------------------------------------------------------

		//Compile cats
		if ( is_array($this->data['cat_id']) ){
			$cat_id	= ',';
			reset($this->data['cat_id']);
			while ( list(, $cid) = each($this->data['cat_id']) ){
				$cat_id	.= $cid . ',';
			}
		}
		else{
			$cat_id	= '';
		}

		$max_option_order	= 1;
		//Update Old Options
		reset($this->data["oldoptions"]);
		while (list($option_id, $title) = each($this->data["oldoptions"]) ){
			$option_id	= intval($option_id);
			if ( !$option_id ) continue;
			
			$title		= htmlspecialchars($title);
			$order		= isset($this->data['oldorder'][$option_id]) ? intval($this->data['oldorder'][$option_id]) : 1;
			if ( !empty($title) ){
				//Update old option
				$DB->query("UPDATE ". $DB->prefix ."poll_option SET option_title='". $title ."', option_order=$order WHERE option_id=$option_id");
				if ( $order > $max_option_order){
					$max_option_order	= $order;
				}
			}
			else{
				//Delete old option
				$DB->query("DELETE FROM ". $DB->prefix ."poll_option WHERE option_id=$option_id");
			}
		}
		//--------------------------------------

		//Insert new Options
		reset($this->data['options']);
		while (list($key, $title) = each($this->data["options"]) ){
			$title		= htmlspecialchars($title);
			if ( isset($this->data['order'][$key]) && $this->data['order'][$key] ){
				$order	= intval($this->data['order'][$key]);
			}
			else{
				$max_option_order++;
				$order	= $max_option_order;
			}
			
			if ( !empty($title) ){
				//Insert new option
				$DB->query("INSERT INTO ". $DB->prefix ."poll_option(poll_id, option_title, option_order, option_hits) VALUES($id, '". htmlspecialchars($title) ."', $order, 0)");
			}
		}
		//--------------------------------------

		//count total hits of this poll
		$DB->query("SELECT SUM(option_hits) as hits_counter FROM ". $DB->prefix ."poll_option WHERE poll_id=$id");
		if ( $DB->num_rows() ){
			$tmp_info	= $DB->fetch_array();
			$total_hits	= $tmp_info['hits_counter'];
		}
		else{
			$total_hits	= 0;
		}

		//Update poll
		$DB->query("UPDATE ". $DB->prefix ."poll SET cat_id='". $cat_id ."', question='". $this->data["question"] ."', start_date=$stime, end_date=$etime, multiple=". $this->data["multiple"] .", total_hits=$total_hits, enabled=".$this->data["enabled"]." WHERE poll_id=$id");

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=poll&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_polls();
		return true;
	}

	function count_options($optarray, $count){
		if ( !empty($optarray) && is_array($optarray) ){
			reset($optarray);
			while ( list(, $val) = each($optarray) ){
				if ( !empty($val) ){
					$count++;
				}
			}
		}
		return $count;
	}

	function active_polls($enabled=0){
		global $DB, $Template, $Func;

		$poll_ids	= isset($_POST['poll_ids']) ? $_POST['poll_ids'] : '';
		$ids_info	= $Func->get_array_value($poll_ids);

		if ( sizeof($ids_info) ){
			$log_act	= $enabled ? 'log_enable' : 'log_disable';
			$str_ids	= implode(',', $ids_info);

			//Update polls
			$DB->query("UPDATE ". $DB->prefix ."poll SET enabled=$enabled WHERE poll_id IN (". $str_ids .")");
			//Save log
			$Func->save_log(FUNC_NAME, $log_act, $str_ids);
		}

		$this->list_polls();
	}

	function delete_polls(){
		global $DB, $Template, $Func;

		$poll_ids	= isset($_POST['poll_ids']) ? $_POST['poll_ids'] : '';
		$ids_info	= $Func->get_array_value($poll_ids);

		if ( sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);
			$where_sql	= " WHERE poll_id IN (". $str_ids .")";

			$DB->query("DELETE FROM ". $DB->prefix ."poll_option $where_sql");
			$DB->query("DELETE FROM ". $DB->prefix ."poll $where_sql");

			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $str_ids);
		}

		$this->list_polls();
	}

	function view_poll(){
		global $Session, $DB, $Info, $Template, $Lang;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main		= "poll_view";

		//Get poll
		$DB->query("SELECT * FROM ". $DB->prefix ."poll WHERE poll_id=$id");
		if ( !$DB->num_rows() ){
			$Template->message_die($Lang->data['poll_not_exist']);
			return false;
		}
		$poll_info	= $DB->fetch_array();
		$DB->free_result();

		//Get poll option
		$DB->query("SELECT * FROM ". $DB->prefix ."poll_option WHERE poll_id=$id");
		$option_count		= $DB->num_rows();
		$option_data		= $DB->fetch_all_array();
		$DB->free_result();

		$total_hits = 0;
		for ($i=0; $i<$option_count; $i++){
			$total_hits += $option_data[$i]['option_hits'];
		}

		for ($i=0; $i<$option_count; $i++){
			$percent	= !$total_hits ? '0%' : round(($option_data[$i]['option_hits'] * 100) / $total_hits,1) .'%';
			$Template->set_block_vars("optionrow", array(
				"TITLE"			=> $option_data[$i]["option_title"],
				"HITS"			=> $option_data[$i]["option_hits"],
				"PERCENT"		=> $percent,
			));
		}

		$Template->set_vars(array(
			"QUESTION"				=> $poll_info['question'],
			"TOTAL_HITS"			=> $total_hits,
			"L_PAGE_TITLE"			=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data['menu_miscell_poll'] . $Lang->data['general_arrow'] . $Lang->data['general_view'],
			"L_TOTAL_HITS"			=> $Lang->data["poll_hits"],
		));
		return true;
	}

	function update_order(){
		global $Session, $Template, $Lang, $DB, $Func;

		$poll_orders	= isset($_POST["poll_orders"]) ? $_POST["poll_orders"] : '';

		if ( is_array($poll_orders) ){
			reset($poll_orders);
			while ( list($id, $num) = each($poll_orders) ){
				$DB->query("UPDATE ". $DB->prefix ."poll SET poll_order=". intval($num) ." WHERE poll_id=". intval($id));
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_update');

		$this->list_polls();
	}

	function resync_polls(){
		global $DB, $Template, $Lang, $Func;

		$DB->query('SELECT SUM(O.option_hits) AS hits_counter, P.poll_id, P.poll_order FROM '. $DB->prefix .'poll AS P, '. $DB->prefix .'poll_option AS O WHERE P.poll_id=O.poll_id GROUP BY P.poll_id ORDER BY P.poll_order ASC');
		$poll_count		= $DB->num_rows();
		$poll_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$poll_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'poll SET poll_order='. ($i + 1) .', total_hits='. $poll_data[$i]['hits_counter'] .' WHERE poll_id='. $poll_data[$i]['poll_id']);
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_resync');

		$this->list_polls();
	}
}

?>