<?php
/* =============================================================== *\
|		Module name:      Frame										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('VERSION_CHECKING', true);
define('VERSION_CHECKING_DAYS', 3);

$AFrame = new Admin_Frame;

class Admin_Frame
{
	function Admin_Frame(){
		global $Info;

		switch ($Info->code){
			case "menu":
					$Info->tpl_header = "";
					$Info->tpl_footer = "";
					$Info->tpl_main   = "frame_menu";
					$this->set_menu();
					break;
			case "main":
					$Info->tpl_main = "frame_main";
					$this->get_system_info();
					break;
			case "update_note":
					$Info->tpl_main = "frame_main";
					$this->update_acp_note();
					break;
			default:
					$Info->tpl_header = "";
					$Info->tpl_footer = "";
					$Info->tpl_main   = "frame";
					$this->show_frame();
		}
	}

	function show_frame(){
		global $Session, $Template;

		$Template->set_vars(array(
			'U_VIEW_MENU'	=> $Session->append_sid(ACP_INDEX .'?mod=frame&code=menu'),
			'U_VIEW_MAIN'	=> $Session->append_sid(ACP_INDEX .'?mod=frame&code=main'),
		));
	}

	function set_menu(){
		global $Session, $DB, $Template, $Lang;

		$DB->query('SELECT * FROM '. $DB->prefix .'func_group ORDER BY fgroup_order ASC');
		$group_count = $DB->num_rows();
		$group_data  = $DB->fetch_all_array();
		$DB->free_result();

		$DB->query('SELECT * FROM '. $DB->prefix .'func ORDER BY func_order ASC');
		$function_count = $DB->num_rows();
		$function_data  = $DB->fetch_all_array();
		$DB->free_result();

		$this->remove_not_auth_func($group_count, $group_data, $function_count, $function_data);

		for ($i=0; $i<$group_count; $i++){
			$Template->set_block_vars("fgroup",array(
				'ID'		=> $group_data[$i]['fgroup_id'],
				'NAME'      => isset($Lang->data[$group_data[$i]['fgroup_name']]) ? $Lang->data[$group_data[$i]['fgroup_name']] : '',
			));

			for ($j=0; $j<$function_count; $j++){
				if ( $function_data[$j]['fgroup_id'] == $group_data[$i]['fgroup_id'] ){
					$Template->set_block_vars("fgroup:func",array(
						'NAME'       => isset($Lang->data[$function_data[$j]['func_name']]) ? $Lang->data[$function_data[$j]['func_name']] : $function_data[$j]['func_name'],
						'U_LINK'     => $Session->append_sid($function_data[$j]['func_url']),
						'TARGET'     => $function_data[$j]['func_target'],
					));
				}
			}
		}

		$Template->set_vars(array(
			'U_ACP_HOME'		=> $Session->append_sid(ACP_INDEX .'?mod=frame&code=main'),
			'L_ACP_HOME'		=> $Lang->data['general_acp_home'],
			'U_ONLINE_DOCUMENT'	=> '',	//http://doc.s.o.s.ovn.com/sne/
			'L_ONLINE_DOCUMENT'	=> $Lang->data['general_online_document'],
			'L_EXPAND_ALL'		=> $Lang->data['general_expand_all'],
			'L_COLLAPSE_ALL'	=> $Lang->data['general_collapse_all'],
			'L_SHOW_PANEL'		=> $Lang->data['general_show_panel'],
			'L_HIDE_PANEL'		=> $Lang->data['general_hide_panel'],
		));
	}

	function remove_not_auth_func(&$group_count, &$group_data, &$function_count, &$function_data){
		global $Func;

		$f_count	= 0;
		$f_data		= array();
		//Remove functions
		for ($i=0; $i<$function_count; $i++){
			if ( $Func->get_all_perms($function_data[$i]['func_name'], 0) ){
				$f_count++;
				$f_data[]	= $function_data[$i];
			}
		}

		$g_count	= 0;
		$g_data		= array();
		//Remove group
		for ($i=0; $i<$group_count; $i++){
			for ($j=0; $j<$f_count; $j++){
				if ($f_data[$j]['fgroup_id'] == $group_data[$i]['fgroup_id']){
					$g_count++;
					$g_data[]	= $group_data[$i];
					break;
				}
			}
		}

		$function_count	= $f_count;
		$function_data	= $f_data;
		$group_count	= $g_count;
		$group_data		= $g_data;
	}

	function get_system_info($msg = ""){
		global $DB, $Template, $Lang, $Func, $Session, $Info;

		//Module language
		$Func->import_module_language("admin/lang_overview". PHP_EX);

		$this->get_online_users();
		$this->get_newer_version();
		$this->get_data_stats(array('menu_article_article' => 'article', 'menu_article_topic'	=> 'article_topic', 'menu_article_comment'	=> 'article_comment', 'menu_article_picture' => 'picture'));
		$this->get_data_stats(array('menu_picture_picture' => 'picture'));
		$this->get_data_stats(array('menu_newslt_email' => 'newsletter', 'menu_newslt_issue' => 'newsletter_issue'));
		$this->get_data_stats(array('menu_miscell_event' => 'event', 'menu_miscell_poll' => 'poll', 'menu_miscell_faq' => 'faq', 'menu_miscell_logo' => 'logo'));
		$this->get_data_stats(array('menu_weblink_cat' => 'weblink_category', 'menu_weblink_weblink' => 'weblink'));
		$this->get_data_stats(array('menu_user_group' => 'user_group', 'menu_user_user' => 'user'));

		$Template->set_vars(array(
			"ERROR_MSG"				=> $msg,
			'S_ACP_NOTE'			=> $Session->append_sid(ACP_INDEX .'?mod=frame&code=update_note'),
			"L_PAGE_TITLE"			=> $Lang->data["overview"],
			'L_ONLINE_OVERVIEW'		=> $Lang->data['online_overview'],
			'L_SYSTEM_OVERVIEW'		=> $Lang->data['system_overview'],
			'L_SERVER_TYPE'			=> $Lang->data['server_type'],
			'L_WEB_SERVER'			=> $Lang->data['web_server'],
			'L_PHP_VERSION'			=> $Lang->data['php_version'],
			'L_MYSQL_VERSION'		=> $Lang->data['mysql_version'],
			'L_DATA_OVERVIEW'		=> $Lang->data['data_overview'],
			'L_ACP_NOTE'			=> $Lang->data['acp_note'],
			'L_BUTTON_UPDATE'		=> $Lang->data['button_edit'],
			'SERVER_TYPE'			=> PHP_OS,
			'WEB_SERVER'			=> isset($_SERVER["SERVER_SOFTWARE"]) ? $_SERVER["SERVER_SOFTWARE"] : '',
			'PHP_VERSION'			=> PHP_VERSION, //phpversion()
			'MYSQL_VERSION'			=> $DB->get_serverinfo(),
			'ACP_NOTE'				=> $Info->option['acp_note'],
		));
	}

	function update_acp_note(){
		global $Session, $Info, $DB, $Template, $Lang, $Func;

		$acp_note	= isset($_POST["acp_note"]) ? htmlspecialchars($_POST["acp_note"]) : '';

		//Update
		$DB->query("UPDATE ". $DB->prefix ."config SET config_value='". $acp_note ."' WHERE config_name='acp_note'");

		$this->get_system_info($Lang->data['general_success_update']);
	}

	function get_online_users(){
		global $Session, $DB, $Info, $Template, $Lang;

		//IP2Country
		include("./includes/ip2country". PHP_EX);
		$IP2Country  = new IP2Country;

		$login_time		= CURRENT_TIME - $Info->option['time_login'];
//		$cookie_time	= CURRENT_TIME - $Info->option['cookie_time'];

		$DB->query("SELECT U.* FROM ". $DB->prefix ."user AS U, ". $DB->prefix ."session AS S WHERE S.user_id=U.user_id AND S.session_time >= $login_time ORDER BY U.username ASC");
		$user_count = $DB->num_rows();
		$user_data	= $DB->fetch_all_array();
		$DB->free_result();

		$online		= "";
		for ($i=0; $i<$user_count; $i++){
			if ( !empty($online) ){
				$online	.= ', ';
			}
			$online	.= sprintf($Lang->data['general_user_ip'], $user_data[$i]['username'], $user_data[$i]['user_ip'] . $IP2Country->get_country_flag($user_data[$i]['user_ip'], 'left'));
		}

		$Template->set_vars(array(
			'ONLINE_USERS'		=> $online
		));
	}

	function get_newer_version(){
		global $DB, $Lang, $Template, $Info;

		$new_version	= '';
/**
		if ( VERSION_CHECKING ){ //Enable checking
			$Template->set_block_vars("version_checking", array());

			//Check previous checking time
			if ( $Info->option['versionrpt'] + (VERSION_CHECKING_DAYS * 86400) < CURRENT_TIME ){
				$soso_url	= 'http://check.sosovn.com/version.php?pro=sne_pro&version='. $Info->option['script_version'];
				$soso_msg	= @file_get_contents($soso_url);
				if ( strpos($soso_msg, "[SoSoVN_Verified]") !== false ){
					$new_version	= trim(str_replace("[SoSoVN_Verified]", "", $soso_msg));
					if ( strpos($soso_msg, "[None]") !== false ){
						$new_version	= $Lang->data['new_version_not_available'];
					}
					else if ( strpos($soso_msg, "[Invalid]") !== false ){
						$new_version	= $Lang->data['new_version_invalid'];
					}
					else{
						$new_version	= sprintf($Lang->data['new_version_available'], $new_version);
					}
				}
				else{
					$new_version		= $Lang->data['new_version_not_connect'];
				}

				//Update checking status to database
				$DB->query("UPDATE ". $DB->prefix ."config SET config_value=". CURRENT_TIME ." WHERE config_name='versionrpt'");
				$DB->query("UPDATE ". $DB->prefix ."config SET config_value='". addslashes($new_version) ."' WHERE config_name='versionrpt_msg'");
			}
			else{
				//Get msg from previous checking
				$new_version			= $Info->option['versionrpt_msg'];
			}
		}
**/
		$new_version	= $Lang->data['new_version_not_available'];
		$Template->set_vars(array(
			'L_STAT_SCRIPT_NAME'		=> $Lang->data['script_name'],
			'L_STAT_SCRIPT_VERSION'		=> $Lang->data['script_version'],
			'L_STAT_NEW_VERSION'		=> $Lang->data['new_version'],
			'STAT_SCRIPT_NAME'			=> 'SoSo News Express Pro',
			//'STAT_SCRIPT_VERSION'		=> trim(str_replace('SoSo News Express Pro', '', $Info->option['script_name'])),
			'STAT_SCRIPT_VERSION'		=> '2.2.0 Beta 1',
			'STAT_NEW_VERSION'			=> $new_version,
		));
	}

	//Function array : function mod => sql table name
	function get_data_stats($func_array){
		global $Session, $Lang, $DB, $Template;

		if ( !is_array($func_array) ) return false;

		$counter_info	= array();

		$where_sql = " (F.func_name=''";
		reset($func_array);
		while (list($name, $tbl) = each($func_array) ){
			$where_sql .= " OR F.func_name='". $name ."'";
			//Count records
			$DB->query("SELECT count(*) AS total FROM ". $DB->prefix ."$tbl");
			if ( $DB->num_rows() ){
				$tmp_info	= $DB->fetch_array();
				$counter_info[$name]	= $tmp_info['total'];
			}
		}
		$where_sql .= ")";

		$DB->query("SELECT F.*, G.* FROM ". $DB->prefix ."func AS F, ". $DB->prefix ."func_group AS G WHERE F.fgroup_id=G.fgroup_id AND $where_sql ORDER BY func_order ASC");
		$func_count	= $DB->num_rows();
		$func_data	= $DB->fetch_all_array();
		$DB->free_result();

		if ( $func_count ){
			$Template->set_block_vars("grouprow", array(
				'L_GROUP_NAME'		=> $Lang->data[$func_data[0]['fgroup_name']],
			));

			$i		= 0;
			$cols	= 2;
			while ($i < $func_count){
				$Template->set_block_vars("grouprow:funcrow", array());
				for ($j=0; $j<$cols; $j++){
					if ( $i < $func_count ){
						$Template->set_block_vars("grouprow:funcrow:funccol", array(
							'L_FUNC_NAME'		=> $Lang->data[$func_data[$i]['func_name']],
							'U_FUNC_URL'		=> $Session->append_sid($func_data[$i]['func_url']),
							'COUNTER'			=> isset($counter_info[$func_data[$i]['func_name']]) ? $counter_info[$func_data[$i]['func_name']] : 0,
						));
					}
					else{
						$Template->set_block_vars("grouprow:funcrow:funccol", array(
							'L_FUNC_NAME'		=> "",
							'U_FUNC_URL'		=> "",
							'COUNTER'			=> "",
						));
					}
					$i++;
				}
			}
		}
		return true;
	}
}
?>