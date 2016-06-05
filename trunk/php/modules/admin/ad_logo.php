<?php
/* =============================================================== *\
|		Module name:      Admin Logo								|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_miscell_logo');
define('FUNC_ACT_VIEW', 'preedit');
//Module language
$Func->import_module_language("admin/lang_logo". PHP_EX);

$AdminLogo = new Admin_Logo;

class Admin_Logo
{
	var $data			= array();
	var $page			= 1;
	var $upload_path	= './images/logos/';

	var $user_perm		= array();

	function Admin_Logo(){
		global $Info, $Func, $Cache;

		$this->user_perm	= $Func->get_all_perms('menu_miscell_logo');
		$this->get_filter();

		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_logo();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$Cache->clear_cache('all');
				$this->do_add_logo();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_logo();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all');
				$this->do_edit_logo();
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$Cache->clear_cache('all');
				$this->delete_logos();
				break;
			case "update":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all');
				$this->update_order();
				break;
			case "resync":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->resync_logos();
				break;
			case "enable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all');
				$this->active_logos(1);
				break;
			case "disable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all');
				$this->active_logos(0);
				break;
			default:
				$this->list_logos();
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

		$this->filter['logo_pos']			= isset($_POST["flogo_pos"]) ? intval($_POST["flogo_pos"]) : -1;
		if ( $this->filter['logo_pos'] == -1 ){
			$this->filter['logo_pos']		= isset($_GET["flogo_pos"]) ? htmlspecialchars($_GET["flogo_pos"]) : -1;
		}
		if ( $this->filter['logo_pos'] != -1 ){
			$this->filter['url_append']	.= '&flogo_pos='. $this->filter['logo_pos'];
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
		if ( ($this->filter['sort_by'] != "logo_order") && ($this->filter['sort_by'] != "site_name") && ($this->filter['sort_by'] != "hits")){
			$this->filter['sort_by']	= "logo_order";
		}
		$this->filter['url_append']	.= '&fsort_by='. $this->filter['sort_by'];

		$this->filter['sort_order']		= isset($_POST["fsort_order"]) ? htmlspecialchars($_POST["fsort_order"]) : '';
		if (empty($this->filter['sort_order'])){
			$this->filter['sort_order']	= isset($_GET["fsort_order"]) ? htmlspecialchars($_GET["fsort_order"]) : '';
		}
		if ( ($this->filter['sort_order'] != "desc") && ($this->filter['sort_order'] != "asc") ){
			$this->filter['sort_order']	= strtolower(LOGO_SORT_ORDER);
		}
		$this->filter['url_append']	.= '&fsort_order='. $this->filter['sort_order'];

		$this->page		= (isset($_GET["page"]) && ($_GET["page"] > 0)) ? intval($_GET["page"]) : 1;

		$Template->set_vars(array(
			'FKEYWORD'		=> $this->filter['keyword'],
			'FLOGO_POS'		=> $this->filter['logo_pos'],
			'FSTATUS'		=> $this->filter['status'],
			'FSOFT_BY'		=> $this->filter['sort_by'],
			'FSOFT_ORDER'	=> $this->filter['sort_order'],
		));
	}

	function list_logos($last_page = false){
		global $Session, $Func, $DB, $Info, $Template, $Lang, $cfg_media_objects;

		$Info->tpl_main		= "logo_list";
		$itemperpage		= $Info->option['items_per_page'];
		$date_format		= $Info->option['date_format'];
		$timezone			= $Info->option['timezone'] * 3600;

		//Filter -----------------------------------
		$where_sql = " WHERE logo_id>0";
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

		//Logo pos
		if ( $this->filter['logo_pos'] != -1 ){
			$where_sql	.= " AND logo_pos=". $this->filter['logo_pos'];
		}

		//Keyword
		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*", "%", $this->filter['keyword']);
			$where_sql	.= " AND (site_name LIKE '%". $key ."%' OR site_url LIKE '%". $key ."%')";
		}
		//-----------------------------------------

		//Generate pages
		$DB->query("SELECT count(logo_id) AS total FROM ". $DB->prefix ."logo ". $where_sql);
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			if ( $last_page ){
				$this->page	= ceil($result['total']/$itemperpage);
			}
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX .'?mod=logo') );
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query("SELECT * FROM ". $DB->prefix ."logo ". $where_sql ." ORDER BY ". $this->filter['sort_by'] ." ". $this->filter['sort_order'] ." LIMIT ". $pageshow['start'] .", $itemperpage");
		$logo_count		= $DB->num_rows();
		$logo_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$logo_count; $i++){
			//Logo file
			if (substr($logo_data[$i]["logo_file"], 0, 7) != 'http://'){
				$logo_data[$i]["logo_file"]		= $this->upload_path . $logo_data[$i]["logo_file"];
			}
			if ( @substr($logo_data[$i]["logo_file"], -4) == '.swf' ){
				$swf_width	= $logo_data[$i]["logo_width"] ? $logo_data[$i]["logo_width"] : LOGO_SWF_WIDTH;
				$swf_height	= $logo_data[$i]["logo_height"] ? $logo_data[$i]["logo_height"] : LOGO_SWF_HEIGHT;
				$image		= str_replace("[_FILENAME_]", $logo_data[$i]["logo_file"], $cfg_media_objects['swf']);
				$image		= str_replace("[_WIDTH_]", $swf_width, $image);
				$image		= str_replace("[_HEIGHT_]", $swf_height, $image);
			}
			else{
				$image	 = '<img src="'. $logo_data[$i]["logo_file"] .'"';
				if ( $logo_data[$i]["logo_width"] || $logo_data[$i]["logo_height"] ){
					$image	.= $logo_data[$i]["logo_width"] ? ' width="'. $logo_data[$i]["logo_width"] .'"' : '';
					$image	.= $logo_data[$i]["logo_height"] ? ' height="'. $logo_data[$i]["logo_height"] .'"' : '';
				}
				else{
					$limit_width	= 200;
					$imgsize	= @getimagesize($logo_data[$i]["logo_file"]);
					if ( $imgsize[0] > $limit_width ){
						$image	.= ' width="'. $limit_width .'"';
					}
				}
				$image	.= !$logo_data[$i]['enabled'] ? ' style="FILTER: alpha(opacity=40); -moz-opacity:.40; opacity:.40;" ' : '';
				$image	.= ' vspace="4" border="0" alt="" title="'. $logo_data[$i]['site_name'] .'">';
			}

			//Status
			if ($logo_data[$i]["start_date"] > CURRENT_TIME){
				$status		= $Lang->data["general_waiting"];
			}
			else if ( $logo_data[$i]["end_date"] && ($logo_data[$i]["end_date"] < CURRENT_TIME) ){
				$status		= $Lang->data["general_expired"];
			}
			else{
				$status		= $Lang->data["general_showing"];
			}

			//Logo pos
			if ($logo_data[$i]["logo_pos"] == LOGO_POS_LEFT){
				$logo_pos	= $Lang->data["logo_pos_left"];
			}
			else {
				$logo_pos	= $Lang->data["logo_pos_right"];
			}

			$Template->set_block_vars("logorow", array(
				"ID"			=> $logo_data[$i]["logo_id"],
				"IMG"			=> $image,
				"SITE_URL"		=> $logo_data[$i]["site_url"],
				"SITE_NAME"		=> $logo_data[$i]["site_name"],
				'LOGO_POS'		=> $logo_pos,
				'STATUS'		=> $status,
				"DATE"			=> $Func->translate_date(gmdate($date_format, $logo_data[$i]["start_date"] + $timezone)),
				"HITS"			=> $logo_data[$i]["hits"],
				"ORDER"			=> $logo_data[$i]["logo_order"],
				"CSS"			=> $logo_data[$i]['enabled'] ? 'enabled' : 'disabled',
				'BG_CSS'		=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				'U_VIEW'		=> $Session->append_sid(ACP_INDEX .'?mod=logo&act=manage&lid='. $logo_data[$i]["logo_id"]),
				'U_EDIT'		=>  $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=logo&act=preedit&id='. $logo_data[$i]["logo_id"] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
			));
		}

		$Template->set_vars(array(
			"PAGE_OUT"				=> $pageshow['page'],
			'S_ACTION_FILTER'		=> $Session->append_sid(ACP_INDEX .'?mod=logo'),
			'U_UPDATE'				=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="javascript: updateForm2(\''. $Session->append_sid(ACP_INDEX .'?mod=logo&act=update&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/update.gif" alt="" title="'. $Lang->data['general_update'] .'" border="0" align="absbottom">'. $Lang->data['general_update'] .'</a> &nbsp; &nbsp;' : '',
			'U_ADD'					=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=logo&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="'. $Lang->data['general_add'] .'" border="0" align="absbottom">'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp;' : '',
			'U_RESYNC'				=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=logo&act=resync' . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/resync.gif" alt="" title="'. $Lang->data['general_resync'] .'" border="0" align="absbottom">'. $Lang->data['general_resync'] .'</a>' : '',
			'U_ENABLE'				=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript: updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=logo&act=enable&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/enable.gif" alt="" title="'. $Lang->data['general_enable'] .'" align="absbottom" border=0>'. $Lang->data['general_enable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DISABLE'				=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=logo&act=disable&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/disable.gif" alt="" title="'. $Lang->data['general_disable'] .'" align="absbottom" border=0>'. $Lang->data['general_disable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'				=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript: deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=logo&act=del&page='. $this->page) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" align="absbottom" border=0>'. $Lang->data['general_del'] .'</a>' : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data["menu_miscell_logo"],
			"L_WAITING"				=> $Lang->data["general_waiting"],
			"L_SHOWING"				=> $Lang->data["general_showing"],
			"L_EXPIRED"				=> $Lang->data["general_expired"],
			"L_ASC"					=> $Lang->data["general_asc"],
			"L_DESC"				=> $Lang->data["general_desc"],
			"L_DATE"				=> $Lang->data["general_start_date"],
			"L_BUTTON_SEARCH"		=> $Lang->data["button_search"],
			"L_SEARCH_ORDER"		=> $Lang->data["logo_order"],
			"L_ORDER"				=> $Lang->data["general_order"],
			"L_LOGO"				=> $Lang->data["logo"],
			'L_LOGO_POS'			=> $Lang->data['logo_pos'],
			"L_LOGO_POS_LEFT"		=> $Lang->data["logo_pos_left"],
			"L_LOGO_POS_RIGHT"		=> $Lang->data["logo_pos_right"],
			'LOGO_POS_LEFT'			=> LOGO_POS_LEFT,
			'LOGO_POS_RIGHT'		=> LOGO_POS_RIGHT,
			"L_ADDED_DATE"			=> $Lang->data["general_addded_date"],
			'L_DEL_CONFIRM'			=> $Lang->data['logo_del_confirm'],
			'L_CHOOSE_ITEM'			=> $Lang->data['logo_error_not_check'],
		));
	}

	function pre_add_logo($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$Info->tpl_main		= "logo_edit";
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
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=logo&act=add'),
			'LOGO_IMG'				=> '',
			'LOGO_TYPE'				=> isset($this->data['logo_type']) ? $this->data['logo_type'] : '',
			'LOGO_REMOTE'			=> isset($this->data['logo_remote']) ? $this->data['logo_remote'] : 'http://',
			'LOGO_WIDTH'			=> (isset($this->data['logo_width']) && $this->data['logo_width'] ) ? $this->data['logo_width'] : '',
			'LOGO_HEIGHT'			=> (isset($this->data['logo_height']) && $this->data['logo_height'] ) ? $this->data['logo_height'] : '',
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
			"LOGO_POS"				=> isset($this->data["logo_pos"]) ? $this->data["logo_pos"] : '',
			"ENABLED"				=> isset($this->data["enabled"]) ? $this->data["enabled"] : '',
			"PAGE_TO"				=> isset($this->data["page_to"]) ? $this->data["page_to"] : '',			
			"L_PAGE_TITLE"			=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data["menu_miscell_logo"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			"L_BUTTON"				=> $Lang->data["button_add"],
		));

		//Web Pages list
		$Func->get_webpage_list();

		//Article Pages list
		$this->get_all_cats();
		$this->set_all_cats(0, 0);

		//Logo pages
		if ( isset($this->data["logo_pages"]) && is_array($this->data["logo_pages"]) ){
			reset($this->data["logo_pages"]);
			while ( list(,$page_code) = each($this->data["logo_pages"]) ){
				if ( !empty($page_code) ){
					$Template->set_block_vars('logopagerow', array(
						'CODE'	=> $page_code,
					));
				}
			}
		}
	}

	function set_lang(){
		global $Template, $Lang;

		$Template->set_vars(array(
			"L_LOGO"				=> $Lang->data["logo"],
			"L_LOCAL"				=> $Lang->data["logo_local"],
			"L_REMOTE"				=> $Lang->data["logo_remote"],
			"L_LOGO_SIZE"			=> $Lang->data["logo_size"],
			"L_SITE_URL"			=> $Lang->data["logo_site_url"],
			"L_SITE_NAME"			=> $Lang->data["logo_site_name"],
			"L_WIDTH"				=> $Lang->data["logo_width"],
			"L_HEIGHT"				=> $Lang->data["logo_height"],
			"L_START_DATE"			=> $Lang->data["general_start_date"],
			"L_END_DATE"			=> $Lang->data["general_end_date"],
			"L_PAST_FUTURE"			=> $Lang->data["general_past_future"],
			"L_DATE"				=> $Lang->data["general_date"],
			"L_TIME"				=> $Lang->data["general_time"],
			"L_TIME_EXPLAIN"		=> $Lang->data["general_time_desc"],
			"L_NEVER"				=> $Lang->data["general_never"],
			"L_STATUS"				=> $Lang->data["general_status"],
			"L_PAGE_TO"				=> $Lang->data["general_page_to"],
			"L_PAGE_ADD"			=> $Lang->data["general_page_add"],
			"L_PAGE_LIST"			=> $Lang->data["general_page_list"],
			"L_LOGO_POS"			=> $Lang->data["logo_pos"],
			"L_LOGO_POS_LEFT"		=> $Lang->data["logo_pos_left"],
			"L_LOGO_POS_RIGHT"		=> $Lang->data["logo_pos_right"],
			"L_LOGO_PAGES"			=> $Lang->data["page_displaying"],
			"L_ARTICLE_PAGES"		=> $Lang->data["page_articles"],
			"L_ALL"					=> $Lang->data["general_all"],
			'LOGO_POS_LEFT'			=> LOGO_POS_LEFT,
			'LOGO_POS_RIGHT'		=> LOGO_POS_RIGHT,
		));
	}

	function do_add_logo(){
		global $Session, $DB, $Template, $Lang, $Func, $Info, $File;

		$this->data['logo_type']		= isset($_POST["logo_type"]) ? htmlspecialchars($_POST["logo_type"]) : '';
		if ($this->data['logo_type'] == "local"){
			$this->data['logo_img']		= isset($_FILES["logo_local"]['name']) ? htmlspecialchars($_FILES["logo_local"]['name']) : '';
		}
		else{
			$this->data['logo_img']		= isset($_POST["logo_remote"]) ? htmlspecialchars($_POST["logo_remote"]) : '';
			if (substr($this->data['logo_img'], 0, 7) != 'http://'){
				$this->data['logo_img']	= 'http://'. $this->data['logo_img'];
			}
		}
		$this->data['logo_width']		= isset($_POST["logo_width"]) ? intval($_POST["logo_width"]) : '0';
		$this->data['logo_height']		= isset($_POST["logo_height"]) ? intval($_POST["logo_height"]) : '0';
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
		$this->data["logo_pos"]			= isset($_POST["logo_pos"]) ? intval($_POST["logo_pos"]) : 0;
		$this->data["logo_pages"]		= isset($_POST["logo_pages"]) ? $_POST["logo_pages"] : '';
		$this->data["enabled"]			= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;
		$this->data["page_to"]			= isset($_POST["page_to"]) ? htmlspecialchars($_POST["page_to"]) : '';

//		if ( empty($this->data['logo_img']) || empty($this->data['site_url']) || ($this->data['site_url'] == 'http://') ){
		if ( empty($this->data['logo_img']) ){
			$this->pre_add_logo($Lang->data['general_error_not_full']);
			return false;
		}
		if ( $this->data['site_url'] == 'http://' ){
			$this->data['site_url'] == '';
		}

		//Check and compile time ------------------------------------
		if ( !empty($this->data["startmonth"]) && !empty($this->data["startday"]) && !empty($this->data["startyear"]) && !empty($this->data["starttime"]) ){
			if ( !checkdate($this->data["startmonth"], $this->data["startday"], $this->data["startyear"]) ){
				$this->pre_add_logo($Lang->data["general_error_startdate"]);
				return false;
			}
			$stime	= $Func->make_mydate($this->data["startmonth"], $this->data["startday"], $this->data["startyear"], $this->data["starttime"], CURRENT_TIME, $Info->option['timezone']);
		}
		else{
			$stime	= CURRENT_TIME;
		}

		if ( ($this->data["enddate"] != "never") && !empty($this->data["endmonth"]) && !empty($this->data["endday"]) && !empty($this->data["endyear"]) && !empty($this->data["endtime"]) ){
			if (!checkdate($this->data["endmonth"],$this->data["endday"],$this->data["endyear"])){
				$this->pre_add_logo($Lang->data["logo_error_enddate"]);
				return false;
			}
			$etime	= $Func->make_mydate($this->data["endmonth"], $this->data["endday"], $this->data["endyear"], $this->data["endtime"], 0, $Info->option['timezone']);
		}
		else{
			$etime	= 0;//Never expire
		}
		//---------------------------------------------------------

		//Upload logo
		if ($this->data['logo_type'] == "local"){
			//Get file type
			$start		= strrpos($this->data['logo_img'], ".");
			$filetype	= strtolower(substr($this->data['logo_img'], $start));
			if ( !$File->check_filetype($filetype, 'image', ',.swf') ){
				$this->pre_add_logo(sprintf($Lang->data["upload_error_file_type"], $filetype));
				return false;
			}
			
			if ( file_exists($this->upload_path . $this->data["logo_img"]) ){
				$count		= 1;
				$logo_img	= str_replace(".", $count .".", $this->data["logo_img"]);
				while ( file_exists($this->upload_path . $logo_img) ){
					$count++;
					$logo_img	= str_replace(".", $count .".", $this->data["logo_img"]);
				}
				$this->data['logo_img'] = $logo_img;
			}
			$File->upload_file($_FILES["logo_local"]['tmp_name'], $this->upload_path . $this->data["logo_img"]);
		}

		//Get max order
		$DB->query("SELECT max(logo_order) AS max_order FROM ". $DB->prefix ."logo");
		if ( $DB->num_rows() ){
			$tmp_info		= $DB->fetch_array();
			$max_order		= $tmp_info["max_order"] + 1;
		}
		else{
			$max_order		= 1;
		}
		$DB->free_result();

		$logo_pages	= is_array($this->data['logo_pages']) ? ','. implode(',', $this->data['logo_pages']) .',' : '';
		$DB->query("INSERT INTO ". $DB->prefix ."logo(logo_file, logo_width, logo_height, site_url, site_name, hits, start_date, end_date, logo_order, logo_pos, logo_pages, enabled) VALUES('". $this->data['logo_img'] ."', ". $this->data['logo_width'] .", ". $this->data['logo_height'] .", '". $this->data['site_url'] ."', '". $this->data['site_name'] ."',0 , $stime, $etime, $max_order, ". $this->data['logo_pos'] .", '". $logo_pages ."', ". $this->data['enabled'] .")");
		$logo_id	= $DB->insert_id();

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $logo_id, ACP_INDEX .'?mod=logo&act='. FUNC_ACT_VIEW .'&id='. $logo_id);

		if ( $this->data['page_to'] == 'pageadd' ){
			$this->data		= array();//Reset data
			$this->pre_add_logo($Lang->data['general_success_add']);
		}
		else{
			$last_page	= (strtolower(LOGO_SORT_ORDER) == 'asc') ? true : false;
			$this->list_logos($last_page);
		}
		return true;
	}

	function pre_edit_logo($msg = ""){
		global $Session, $DB, $Template, $Lang, $Func, $Info, $cfg_media_objects;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main	= "logo_edit";
		$this->set_lang();

		$DB->query("SELECT * FROM ". $DB->prefix ."logo WHERE logo_id=$id");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["logo_error_not_exist"], $Session->append_sid(ACP_INDEX .'?mod=logo&page='. $this->page));
			return false;
		}
		$logo_info	= $DB->fetch_array();
		$DB->free_result();

		//Get logo image ---------------------------
		if (substr($logo_info["logo_file"], 0, 7) != 'http://'){
			$logo_info["logo_file"]		= $this->upload_path . $logo_info["logo_file"];
		}

		if ( @substr($logo_info["logo_file"], -4) == '.swf' ){
			$swf_width	= $logo_info["logo_width"] ? $logo_info["logo_width"] : LOGO_SWF_WIDTH;
			$swf_height	= $logo_info["logo_height"] ? $logo_info["logo_height"] : LOGO_SWF_HEIGHT;
			$image		= str_replace("[_FILENAME_]", $logo_info["logo_file"], $cfg_media_objects['swf']);
			$image		= str_replace("[_WIDTH_]", $swf_width, $image);
			$image		= str_replace("[_HEIGHT_]", $swf_height, $image) .'<br><br>';
		}
		else{
			$image	 = '<img src="'. $logo_info["logo_file"] .'"';
			$image	.= $logo_info["logo_width"] ? ' width="'. $logo_info["logo_width"] .'"' : '';
			$image	.= $logo_info["logo_height"] ? ' height="'. $logo_info["logo_height"] .'"' : '';
			$image	.= ' vspace="4" border="0" alt="" title="'. $logo_info['site_name'] .'"><br><br>';
		}
		//------------------------------------------

		//Get date ---------------------------------
		$date		= explode('-', gmdate('m-d-Y-H-i', $logo_info['start_date'] + $Info->option['timezone']*3600));
		$startmonth	= intval($date[0]);
		$startday	= intval($date[1]);
		$startyear	= intval($date[2]);
		$starttime	= $date[3] .":". $date[4];

		if ( $logo_info['end_date'] ){
			$date	= explode('-', gmdate('m-d-Y-H-i', $logo_info['end_date'] + $Info->option['timezone']*3600));
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
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=logo&act=edit&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			"LOGO_IMG"				=> $image,
			'LOGO_TYPE'				=> isset($this->data['logo_type']) ? $this->data['logo_type'] : '',
			'LOGO_REMOTE'			=> isset($this->data['logo_remote']) ? $this->data['logo_remote'] : 'http://',
			'LOGO_WIDTH'			=> (isset($this->data['logo_width']) && $this->data['logo_width'] ) ? $this->data['logo_width'] : ($logo_info['logo_width'] ? $logo_info['logo_width'] : ''),
			'LOGO_HEIGHT'			=> (isset($this->data['logo_height']) && $this->data['logo_height'] ) ? $this->data['logo_height'] : ($logo_info['logo_height'] ? $logo_info['logo_height'] : ''),
			'SITE_URL'				=> isset($this->data['site_url']) ? $this->data['site_url'] :  $logo_info['site_url'],
			'SITE_NAME'				=> isset($this->data['site_name']) ? stripslashes($this->data['site_name']) :  $logo_info['site_name'],
			"START_MONTH"			=> isset($this->data["startmonth"]) ? $this->data["startmonth"] : $startmonth,
			"START_DAY"				=> isset($this->data["startday"]) ? $this->data["startday"] : $startday,
			"START_YEAR"			=> isset($this->data["startyear"]) ? $this->data["startyear"] : $startyear,
			"START_TIME"			=> isset($this->data["starttime"]) ? $this->data["starttime"] : $starttime,
			"END_DATE"				=> isset($this->data["enddate"]) ? $this->data["enddate"] : 'never',
			"END_MONTH"				=> isset($this->data["endmonth"]) ? $this->data["endmonth"] : $endmonth,
			"END_DAY"				=> isset($this->data["endday"]) ? $this->data["endday"] : $endday,
			"END_YEAR"				=> isset($this->data["endyear"]) ? $this->data["endyear"] : $endyear,
			"END_TIME"				=> isset($this->data["endtime"]) ? $this->data["endtime"] : $endtime,
			"LOGO_POS"				=> isset($this->data["logo_pos"]) ? $this->data["logo_pos"] : $logo_info['logo_pos'],
			"ENABLED"				=> isset($this->data["enabled"]) ? $this->data["enabled"] : $logo_info['enabled'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_miscell"] . $Lang->data['general_arrow'] . $Lang->data["menu_miscell_logo"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			"L_BUTTON"				=> $Lang->data["button_edit"],
		));

		//Web pages list
		$Func->get_webpage_list();

		//Article pages list
		$this->get_all_cats();
		$this->set_all_cats(0, 0);
		if ( !isset($this->data["logo_pages"]) || !is_array($this->data["logo_pages"]) ){
			$this->data["logo_pages"]	= explode(',', $logo_info["logo_pages"]);
		}

		//Logo pages
		if ( isset($this->data["logo_pages"]) && is_array($this->data["logo_pages"]) ){
			reset($this->data["logo_pages"]);
			while ( list(,$page_code) = each($this->data["logo_pages"]) ){
				if ( !empty($page_code) ){
					$Template->set_block_vars('logopagerow', array(
						'CODE'	=> $page_code,
					));
				}
			}
		}

		return true;
	}

	function do_edit_logo(){
		global $Session, $DB, $Template, $Lang, $Func, $Info, $File;

		$id			= isset($_GET["id"]) ? intval($_GET["id"]) : 0;
		$this->data['logo_type']		= isset($_POST["logo_type"]) ? htmlspecialchars($_POST["logo_type"]) : '';
		if ($this->data['logo_type'] == "local"){
			$this->data['logo_img']		= isset($_FILES["logo_local"]['name']) ? htmlspecialchars($_FILES["logo_local"]['name']) : '';
		}
		else{
			$this->data['logo_img']		= isset($_POST["logo_remote"]) ? htmlspecialchars($_POST["logo_remote"]) : '';
			if (substr($this->data['logo_img'], 0, 7) != 'http://'){
				$this->data['logo_img']	= 'http://'. $this->data['logo_img'];
			}
		}
		$this->data['logo_width']		= isset($_POST["logo_width"]) ? intval($_POST["logo_width"]) : '0';
		$this->data['logo_height']		= isset($_POST["logo_height"]) ? intval($_POST["logo_height"]) : '0';
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
		$this->data["logo_pos"]			= isset($_POST["logo_pos"]) ? intval($_POST["logo_pos"]) : 0;
		$this->data["logo_pages"]		= isset($_POST["logo_pages"]) ? $_POST["logo_pages"] : '';
		$this->data["enabled"]			= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;

		if ( $this->data['site_url'] == 'http://' ){
			$this->data['site_url'] == '';
		}

		//Get old info
		$DB->query('SELECT * FROM '. $DB->prefix .'logo WHERE logo_id='. $id);
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data['logo_error_not_exist'], $Session->append_sid(ACP_INDEX .'?mod=logo&page='. $this->page));
			return false;
		}
		$logo_info	= $DB->fetch_array();
		$DB->free_result();

		//Check and compile time ------------------------------------
		if ( !empty($this->data["startmonth"]) && !empty($this->data["startday"]) && !empty($this->data["startyear"]) && !empty($this->data["starttime"]) ){
			if ( !checkdate($this->data["startmonth"], $this->data["startday"], $this->data["startyear"]) ){
				$this->pre_add_logo($Lang->data["general_error_startdate"]);
				return false;
			}
			$stime	= $Func->make_mydate($this->data["startmonth"], $this->data["startday"], $this->data["startyear"], $this->data["starttime"], CURRENT_TIME, $Info->option['timezone']);
		}
		else{
			$stime	= CURRENT_TIME;
		}

		if ( ($this->data["enddate"] != "never") && !empty($this->data["endmonth"]) && !empty($this->data["endday"]) && !empty($this->data["endyear"]) && !empty($this->data["endtime"]) ){
			if (!checkdate($this->data["endmonth"],$this->data["endday"],$this->data["endyear"])){
				$this->pre_add_logo($Lang->data["logo_error_enddate"]);
				return false;
			}
			$etime	= $Func->make_mydate($this->data["endmonth"], $this->data["endday"], $this->data["endyear"], $this->data["endtime"], 0, $Info->option['timezone']);
		}
		else{
			$etime	= 0;//Never expire
		}
		//---------------------------------------------------------

		//Upload logo
		if ( ($this->data['logo_type'] == "local") && !empty($this->data['logo_img']) ){
			//Get file type
			$start		= strrpos($this->data['logo_img'], ".");
			$filetype	= strtolower(substr($this->data['logo_img'], $start));
			if ( !$File->check_filetype($filetype, 'image', ',.swf') ){
				$this->pre_add_logo(sprintf($Lang->data["upload_error_file_type"], $filetype));
				return false;
			}
			
			//Remove old file
			$File->delete_file($this->upload_path . $logo_info['logo_file']);
			
			//Upload new file
			if ( file_exists($this->upload_path . $this->data["logo_img"]) ){
				$count		= 1;
				$logo_img	= str_replace(".", $count .".", $this->data["logo_img"]);
				while ( file_exists($this->upload_path . $logo_img) ){
					$count++;
					$logo_img	= str_replace(".", $count .".", $this->data["logo_img"]);
				}
				$this->data['logo_img'] = $logo_img;
			}
			$File->upload_file($_FILES["logo_local"]['tmp_name'], $this->upload_path . $this->data["logo_img"]);
			
			$sql_logo	= "logo_file='". $this->data["logo_img"] ."', ";
		}
		else if ( !empty($this->data['logo_img']) && (substr($this->data['logo_img'], 0, 7) != 'http://') ){
			//Remove old file
			$File->delete_file($this->upload_path . $logo_info['logo_file']);
			
			$sql_logo	= "logo_file='". $this->data["logo_img"] ."', ";
		}
		else{
			$sql_logo	= "";
		}

		$logo_pages	= is_array($this->data['logo_pages']) ? ','. implode(',', $this->data['logo_pages']) .',' : '';
		$DB->query("UPDATE ". $DB->prefix ."logo SET $sql_logo logo_width=". $this->data['logo_width'] .", logo_height=". $this->data['logo_height'] .", site_url='". $this->data['site_url'] ."', site_name='". $this->data['site_name'] ."',start_date=$stime, end_date=$etime, logo_pos=". $this->data['logo_pos'] .", logo_pages='". $logo_pages ."', enabled=". $this->data['enabled'] ." WHERE logo_id=$id");

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=logo&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_logos();
		return true;
	}

	function active_logos($enabled=0){
		global $DB, $Template, $Func;

		$logo_ids	= isset($_POST['logo_ids']) ? $_POST['logo_ids'] : '';
		$ids_info	= $Func->get_array_value($logo_ids);

		if ( sizeof($ids_info) ){
			$log_act	= $enabled ? 'log_enable' : 'log_disable';
			$str_ids	= implode(',', $ids_info);

			//Update logos
			$DB->query("UPDATE ". $DB->prefix ."logo SET enabled=$enabled WHERE logo_id IN (". $str_ids .")");
			//Save log
			$Func->save_log(FUNC_NAME, $log_act, $str_ids);
		}

		$this->list_logos();
	}

	function delete_logos(){
		global $DB, $Template, $Func, $File;

		$logo_ids	= isset($_POST['logo_ids']) ? $_POST['logo_ids'] : '';
		$ids_info	= $Func->get_array_value($logo_ids);

		if ( sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);
			$where_sql	= " WHERE logo_id IN (". $str_ids .")";

			//Get and delete picture
			$DB->query("SELECT logo_file FROM ". $DB->prefix ."logo $where_sql");
			$logo_count	= $DB->num_rows();
			$logo_data	= $DB->fetch_all_array();
			for ($i=0; $i<$logo_count; $i++){
				$File->delete_file($this->upload_path . $logo_data[$i]['logo_file']);
			}

			//Delete from db
			$DB->query("DELETE FROM ". $DB->prefix ."logo $where_sql");
			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $str_ids);
		}

		$this->list_logos();
	}

	function update_order(){
		global $Session, $Template, $Lang, $DB, $Func;

		$logo_orders	= isset($_POST["logo_orders"]) ? $_POST["logo_orders"] : '';

		if ( is_array($logo_orders) ){
			reset($logo_orders);
			while ( list($id, $num) = each($logo_orders) ){
				$DB->query("UPDATE ". $DB->prefix ."logo SET logo_order=". intval($num) ." WHERE logo_id=". intval($id));
			}
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_update');

		$this->list_logos();
	}

	function resync_logos(){
		global $DB, $Template, $Lang, $Func;

		$DB->query('SELECT logo_id, logo_order FROM '. $DB->prefix .'logo ORDER BY logo_order ASC');
		$logo_count		= $DB->num_rows();
		$logo_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$logo_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'logo SET logo_order='. ($i + 1) .' WHERE logo_id='. $logo_data[$i]['logo_id']);
		}

		//Save log
		$Func->save_log(FUNC_NAME, 'log_resync');

		$this->list_logos();
	}

	//Get article cats
	function get_all_cats(){
		global $DB;

		$DB->query("SELECT * FROM ". $DB->prefix ."article_category WHERE redirect_url='' ORDER BY cat_order ASC");
		$this->cat_count = $DB->num_rows();
		$this->cat_data  = $DB->fetch_all_array();
		$DB->free_result();
	}

	//Display article cats
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
}

?>