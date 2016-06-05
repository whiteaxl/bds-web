<?php
/* =============================================================== *\
|		Module name:      Site Log									|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_admin_log');
define('FUNC_ACT_VIEW', 'preedit');
define("CODE_ENTER", chr(13));
define("CODE_TAB", chr(9));
define("LOG_FILENAME", "acplog");

$Admin_ACPLog = new Admin_ACPLog;

class Admin_ACPLog
{
	var $filter			= array();
	var $page			= 1;

	var $user_perm		= array();

	function Admin_ACPLog(){
		global $Info;

		$this->page		= isset($_GET['page']) ? intval($_GET['page']) : 1;
		$this->get_filter();

		switch ($Info->act){
			case "del":
				$this->do_delete_logs();
				break;
			case "export":
				$this->export_logs();
				break;
			default:
				$this->list_logs();
		}
	}

	function get_filter(){
		global $Template, $Func;

		$this->filter['func_name']		= htmlspecialchars($Func->get_request('ffunc_name', ''));
		$this->filter['func_action']	= htmlspecialchars($Func->get_request('ffunc_action', ''));
		$this->filter['user_id']		= intval($Func->get_request('fuser_id', 0));
		$this->filter['keyword']		= htmlspecialchars($Func->get_request('fkeyword', ''));

		//Url append
		$this->filter['url_append']			= "";
		if ( !empty($this->filter['func_name']) ){
			$this->filter['url_append']		.= '&ffunc_name='. $this->filter['func_name'];
		}
		if ( !empty($this->filter['func_act']) ){
			$this->filter['url_append']		.= '&ffunc_action='. $this->filter['func_act'];
		}
		if ( $this->filter['user_id'] ){
			$this->filter['url_append']		.= '&fuser_id='. $this->filter['user_id'];
		}
		if ( !empty($this->filter['keyword']) ){
			$this->filter['url_append']		.= '&fkeyword='. $this->filter['keyword'];
		}

		$Template->set_vars(array(
			'FFUNC_NAME'		=> stripslashes($this->filter['func_name']),
			'FFUNC_ACTION'		=> stripslashes($this->filter['func_action']),
			'FUSER_ID'			=> $this->filter['user_id'],
			'FKEYWORD'			=> stripslashes($this->filter['keyword']),
		));
	}

	function list_logs(){
		global $Session, $DB, $Template, $Lang, $Func, $Info;

		$Info->tpl_main	= "acplog_list";

		$itemperpage	= $Info->option['items_per_page'];
		$date_format	= $Info->option['time_format'];
		$timezone		= $Info->option['timezone'] * 3600;

		$this->get_search_users();
		$this->get_search_functions();
		$func_data = $this->get_function_names();

		$where_sql	= "";
		//Filter ---------------------------------
		if ( !empty($this->filter['func_name']) ){
			$where_sql .= " AND L.func_name='". $this->filter['func_name'] ."'";
		}
		if ( !empty($this->filter['func_action']) ){
			$where_sql	.= " AND L.func_action='". $this->filter['func_action'] ."'";
		}
		if ( $this->filter['user_id'] ){
			$where_sql .= " AND L.user_id='". $this->filter['user_id'] ."'";
		}
		if ( !empty($this->filter['keyword']) ){
			$where_sql .= " AND L.user_ip LIKE '". $this->filter['keyword'] ."%'";
		}
		//----------------------------------------

		//Generate pages
		$DB->query('SELECT count(L.log_time) AS total FROM '. $DB->prefix .'site_log AS L, '. $DB->prefix .'user AS U WHERE L.user_id=U.user_id '. $where_sql);
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=log" . $this->filter['url_append']));
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		//Get logs
		$DB->query('SELECT L.*, U.username FROM '. $DB->prefix .'site_log AS L, '. $DB->prefix .'user AS U WHERE L.user_id=U.user_id '. $where_sql .' ORDER BY L.log_time DESC LIMIT '. $pageshow['start'] .','. $itemperpage);
		$log_count		= $DB->num_rows();
		$log_data		= $DB->fetch_all_array();

		for ($i=0; $i<$log_count; $i++){
			if ( ($log_data[$i]['func_name'] == 'menu_general_login') ){
				$func_name	= $Lang->data['menu_general'] .' &raquo; '. $Lang->data['menu_general_login'];
			}
			else if ( ($log_data[$i]['func_name'] == 'menu_general_logout') ){
				$func_name	= $Lang->data['menu_general'] .' &raquo; '. $Lang->data['menu_general_logout'];
			}
			else{
				$func_name	= isset($func_data[$log_data[$i]['func_name']]) ? $func_data[$log_data[$i]['func_name']] : $log_data[$i]['func_name'];
			}
			$Template->set_block_vars("logrow",array(
				'LOG_TIME'				=> $Func->translate_date(gmdate($date_format, $log_data[$i]['log_time'] + $timezone)),
				'USERNAME'				=> $log_data[$i]['username'],
				'USER_ID'				=> $log_data[$i]['user_id'],
				'USER_IP'				=> $log_data[$i]['user_ip'],
				'FUNC_NAME'				=> $func_name,
				'ACTION'				=> isset($Lang->data[$log_data[$i]['func_action']]) ? $Lang->data[$log_data[$i]['func_action']] : $log_data[$i]['func_action'],
				'RECORD'				=> !empty($log_data[$i]['func_url_view']) ? '<a href="javascript: open_window(\''. $Session->append_sid($log_data[$i]['func_url_view']) . '\', 450, 400);">'. $log_data[$i]['record_ids'] .'</a>' : (!empty($log_data[$i]['record_ids']) ? str_replace(',', ', ', $log_data[$i]['record_ids']) : '&nbsp;'),
				'BG_CSS'				=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				'U_FUNCTION'			=> $Session->append_sid(ACP_INDEX .'?mod=log&ffunc_name='. $log_data[$i]['func_name']),
				'U_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=log&ffunc_action='. $log_data[$i]['func_action']),
				'U_USER'				=> $Session->append_sid(ACP_INDEX .'?mod=log&fuser_id='. $log_data[$i]['user_id']),
				'U_IP'					=> $Session->append_sid(ACP_INDEX .'?mod=log&fkeyword='. $log_data[$i]['user_ip']),
			));
		}

		$Template->set_vars(array(
			"PAGE_OUT"				=> $pageshow['page'],
			'S_FILTER_ACTION'		=> $Session->append_sid(ACP_INDEX .'?mod=log'),
			'U_DEL_ALL'				=> '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=log&act=del'. $this->filter['url_append'] .'&page='. $this->page) .'" onclick="javascript: return del_confirm();"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" border=0 alt="" title="'. $Lang->data['log_del_all'] .'"> '. $Lang->data['log_del_all'] .'</a>',
			'U_EXPORT_LOG'			=> '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=log&act=export'. $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/export.gif" border=0 alt="" title="'. $Lang->data['log_export'] .'"> '. $Lang->data['log_export'] .'</a>',
			'L_FUNC_LOGIN'			=> $Lang->data['menu_private'] .' &raquo; '. $Lang->data['menu_private_login'],
			'L_FUNC_LOGOUT'			=> $Lang->data['menu_private'] .' &raquo; '. $Lang->data['menu_private_logout'],
			'L_ADD'					=> $Lang->data['general_add'],
			'L_EDIT'				=> $Lang->data['general_edit'],
			'L_DEL'					=> $Lang->data['general_del'],
			'L_MOVE'				=> $Lang->data['general_move'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_admin"] . $Lang->data['general_arrow'] . $Lang->data["menu_admin_log"],
			'L_ENABLE'				=> $Lang->data['general_enable'],
			'L_DISABLE'				=> $Lang->data['general_disable'],
			'L_LOGIN'				=> $Lang->data['log_login'],
			'L_LOGOUT'				=> $Lang->data['log_logout'],
			'L_LOG_TIME'			=> $Lang->data['log_time'],
			'L_FUNCTION'			=> $Lang->data['log_function'],
			'L_ACTION'				=> $Lang->data['log_action'],
			'L_RECORD'				=> $Lang->data['log_record'],
			'L_USER'				=> $Lang->data['log_user'],
			'L_IP'					=> $Lang->data['log_ip'],
			'L_DEL_ALL'				=> $Lang->data['log_del_all'],
			'L_SEARCH'				=> $Lang->data['button_search'],
		));
	}

	function get_search_users(){
		global $DB, $Template;

		$DB->query('SELECT user_id, username FROM '. $DB->prefix .'user ORDER BY username ASC');
		$user_count	= $DB->num_rows();
		$user_data	= $DB->fetch_all_array();

		for ($i=0; $i<$user_count; $i++){
			$Template->set_block_vars("userrow",array(
				'ID'		=> $user_data[$i]['user_id'],
				'NAME'		=> $user_data[$i]['username'],
			));
		}
	}

	function get_search_functions(){
		global $DB, $Template, $Lang;

		$group_info	= array('menu_article', 'menu_user', 'menu_newslt', 'menu_miscell', 'menu_admin', 'menu_db');

		$where_sql	= " WHERE F.fgroup_id=G.fgroup_id AND (G.fgroup_name=' '";
		reset($group_info);
		while (list(, $name) = each($group_info)){
			$where_sql	.= " OR G.fgroup_name='". $name ."'";
		}
		$where_sql	.= ")";

		$DB->query('SELECT G.fgroup_name, F.func_name FROM '. $DB->prefix .'func_group AS G, '. $DB->prefix .'func AS F '. $where_sql .' ORDER BY G.fgroup_order, F.func_order ASC');
		$func_count		= $DB->num_rows();
		$func_data		= $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$func_count; $i++){
			$Template->set_block_vars("funcrow",array(
				'NAME'			=> $func_data[$i]['func_name'],
				'FULLNAME'		=> $Lang->data[$func_data[$i]['fgroup_name']] .' &raquo; '. $Lang->data[$func_data[$i]['func_name']],
			));
		}
	}

	function get_function_names($arrow = "&raquo;"){
		global $Lang, $DB, $Template;

		$DB->query('SELECT * FROM '. $DB->prefix .'func_group');
		$group_info		= array();
		if ( $DB->num_rows() ){
			while ($tmp_info = $DB->fetch_array()){
				$group_info[$tmp_info['fgroup_id']] = $Lang->data[$tmp_info['fgroup_name']];
			}
		}

		$DB->query('SELECT * FROM '. $DB->prefix .'func');
		$func_info		= array();
		if ( $DB->num_rows() ){
			while ($tmp_info = $DB->fetch_array()){
				if ( isset($group_info[$tmp_info['fgroup_id']]) ){
					$func_info[$tmp_info['func_name']] = $group_info[$tmp_info['fgroup_id']] ." $arrow ". $Lang->data[$tmp_info['func_name']];
				}
				else{
					$func_info[$tmp_info['func_name']] = $Lang->data[$tmp_info['func_name']];
				}
			}
		}

		//Extra functions
		$func_info['menu_article_page'] = $Lang->data['menu_article'] ." $arrow ". $Lang->data['menu_article_article'] ." $arrow ". $Lang->data['menu_article_page'];

		return $func_info;
	}

	function do_delete_logs(){
		global $DB, $Func;

		$DB->query('DELETE FROM '. $DB->prefix .'site_log');

		//Save log
		$Func->save_log(FUNC_NAME, 'log_del');

		$this->list_logs();
	}

	function export_logs(){
		global $Lang, $DB, $Template, $Info, $Func;

//		$itemperpage	= $Info->option['items_per_page'];
		$date_format	= $Info->option['time_format'];
		$timezone		= $Info->option['timezone'] * 3600;
		$func_data		= $this->get_function_names('>>');

		$where_sql	= "";
		//Filter ---------------------------------
		if ( !empty($this->filter['func_name']) ){
			$where_sql .= " AND L.func_name='". $this->filter['func_name'] ."'";
		}
		if ( !empty($this->filter['func_action']) ){
			$where_sql	.= " AND L.func_action='". $this->filter['func_action'] ."'";
		}
		if ( $this->filter['user_id'] ){
			$where_sql .= " AND L.user_id='". $this->filter['user_id'] ."'";
		}
		if ( !empty($this->filter['keyword']) ){
			$where_sql .= " AND L.user_ip LIKE '". $this->filter['keyword'] ."%'";
		}
		//----------------------------------------

		//Get logs
		$DB->query('SELECT L.*, U.username FROM '. $DB->prefix .'site_log AS L, '. $DB->prefix .'user AS U WHERE L.user_id=U.user_id '. $where_sql .' ORDER BY L.log_time DESC');
		$log_count		= $DB->num_rows();
		$log_data		= $DB->fetch_all_array();

		$filename	= LOG_FILENAME .'_'. $Func->translate_date(gmdate("Y_m_d", CURRENT_TIME + $timezone*3600));
	    @ob_start();
	    @ob_implicit_flush(0);	
	    header('Content-Type: text/x-delimtext; name="'. $filename .'.txt.gz"');
	    header('Content-disposition: attachment; filename='. $filename .'.txt.gz');
	    header("Pragma: no-cache");
	    header("Expires: 0");

//		echo "///////////////////////////////////////////////////////////////////////////////", CODE_ENTER;
//		echo "//Created on date ", gmdate($date_format, CURRENT_TIME + $timezone*3600) , CODE_ENTER;
//		echo "//", $Lang->data['log_time'], CODE_TAB, $Lang->data['log_user'], CODE_TAB, $Lang->data['log_ip'], CODE_TAB, $Lang->data['log_function'], CODE_TAB, $Lang->data['log_action'], CODE_TAB, $Lang->data['log_record'], CODE_ENTER;
//		echo "///////////////////////////////////////////////////////////////////////////////", CODE_ENTER;
		echo $Lang->data['log_time'], CODE_TAB, $Lang->data['log_user'], CODE_TAB, $Lang->data['log_ip'], CODE_TAB, $Lang->data['log_function'], CODE_TAB, $Lang->data['log_action'], CODE_TAB, $Lang->data['log_record'], CODE_ENTER;

		for ($i=0; $i<$log_count; $i++){
			echo CODE_ENTER;
			echo $Func->translate_date(gmdate($date_format, $log_data[$i]['log_time'] + $timezone)), CODE_TAB;
			echo $log_data[$i]['username'], CODE_TAB;
			echo $log_data[$i]['user_ip'], CODE_TAB;
			if ( ($log_data[$i]['func_name'] == 'menu_general_login') || ($log_data[$i]['func_name'] == 'menu_general_logout') ){
				echo $Lang->data['menu_general'], CODE_TAB;
			}
			else{
				echo isset($func_data[$log_data[$i]['func_name']]) ? $func_data[$log_data[$i]['func_name']] : $log_data[$i]['func_name'], CODE_TAB;
			}
			echo $Lang->data[$log_data[$i]['func_action']], CODE_TAB;
			echo ($log_data[$i]['record_ids']) ? $log_data[$i]['record_ids'] : '', CODE_TAB;
		}

	    $gzip_contents	= ob_get_contents();
	    ob_end_clean();

	    $gzip_size		= strlen($gzip_contents);
	    $gzip_crc		= crc32($gzip_contents);
	    $gzip_contents	= gzcompress($gzip_contents, 9);
	    $gzip_contents	= substr($gzip_contents, 0, strlen($gzip_contents) - 4);

	    echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
	    echo $gzip_contents;
	    echo pack('V', $gzip_crc);
	    echo pack('V', $gzip_size);

		//Save log
		$Func->save_log(FUNC_NAME, 'log_export');
	}
}
?>