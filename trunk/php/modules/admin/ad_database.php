<?php
/* =============================================================== *\
|		Module name: Database										|
|		Module version: 1.3											|
|		Begin: 10 April 2004										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
//Module language
$Func->import_module_language("admin/lang_database". PHP_EX);

$AdminDatabase = new Admin_Database;

class Admin_Database
{
	var $sql      = "";
	var $user_perm		= array();

	function Admin_Database(){
		global $Template, $Info, $Func, $Lang;

		@set_time_limit(1800);

		switch ($Info->act){
			case "prebk":
				$this->user_perm	= $Func->get_all_perms('menu_db_backup');
				$Info->tpl_main = "db_backup";
				$this->pre_backup_db();
				break;
			case "bk":
				$this->user_perm	= $Func->get_all_perms('menu_db_backup');
				$Func->check_user_perm($this->user_perm, 'backup');
				$this->do_backup_db();
				break;
			case "repair":
				$this->user_perm	= $Func->get_all_perms('menu_db_backup');
				$Func->check_user_perm($this->user_perm, 'repair');
				$Info->tpl_main = "db_backup";
				$this->do_repair_tables();
				break;
			case "prers":
				$this->user_perm	= $Func->get_all_perms('menu_db_restore');
				$Info->tpl_main = "db_restore";
				$this->pre_restore_db();
				break;
			case "rs":
				$this->user_perm	= $Func->get_all_perms('menu_db_restore');
				$this->do_restore_db();
				break;
			case "runtime":
				$this->user_perm	= $Func->get_all_perms('menu_db_runtime');
				$Info->tpl_main = "db_run_sql";
				$Template->set_vars(array(
					"L_PAGE_TITLE"		=> $Lang->data["menu_db"] . $Lang->data['general_arrow'] . $Lang->data["menu_db_runtime"],
				));
				$this->run_sql("SHOW STATUS", $Lang->data["db_sql_runtime"]);
				break;
			case "system":
				$this->user_perm	= $Func->get_all_perms('menu_db_system');
				$Info->tpl_main = "db_run_sql";
				$Template->set_vars(array(
					"L_PAGE_TITLE"		=> $Lang->data["menu_db"] . $Lang->data['general_arrow'] . $Lang->data["menu_db_system"],
				));
				$this->run_sql("SHOW VARIABLES",$Lang->data["db_sql_system"]);
				break;
			case "process":
				$this->user_perm	= $Func->get_all_perms('menu_db_process');
				$Info->tpl_main = "db_run_sql";
				$Template->set_vars(array(
					"L_PAGE_TITLE"		=> $Lang->data["menu_db"] . $Lang->data['general_arrow'] . $Lang->data["menu_db_process"],
				));
				$this->run_sql("SHOW PROCESSLIST",$Lang->data["db_sql_process"]);
				break;
			case "sql":
				$this->user_perm	= $Func->get_all_perms('menu_db_info');
				$Info->tpl_main = "db_run_usersql";
				$this->run_usersql();
				break;
			case "dbinfo":
			default:
				$this->user_perm	= $Func->get_all_perms('menu_db_info');
				$Info->tpl_main = "db_info";
				$this->show_dbinfo();
		}
	}

	function show_dbinfo(){
		global $Session, $Info, $Func, $Template, $Lang, $DB;

		$this->get_dbsql_info();

		if ( isset($this->user_perm['action']['all']) || isset($this->user_perm['action']['run_sql']) ){
			$Template->set_block_vars("permrunsql", array());
		}

		//List tables
		$DB->query("SHOW TABLE STATUS FROM `".$DB->db_name ."`");
//		$table			= array();
		$total_size		= 0;
		if ( $DB->num_rows() ){
			$i	= 0;
			while ( $result = $DB->fetch_array() ){
				//Check whether this is our tables
				if ( !preg_match( "/^".$DB->prefix."/",$result['Name']) ){
					continue;
				}
				$total_size	+= $result['Data_length'];
				$Template->set_block_vars("tablerow",array(
					"NAME"				=> $result["Name"],
					"ROWS"				=> $result["Rows"],
					"DTSIZE"			=> $Func->compile_size($result["Data_length"]),
					"MAXDTSIZE"			=> $Func->compile_size($result["Max_data_length"],-1),
					"INDEXSIZE"			=> $Func->compile_size($result["Index_length"],-1),
					"CREATETIME"		=> $result["Create_time"],
					"UPDATETIME"		=> $result["Update_time"],
					'BG_CSS'			=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				));
				$i++;
			}
		}
		$DB->free_result();

		$total_size = $Func->compile_size($total_size);

		$Template->set_vars(array(
			'S_RUN_SQL'					=> $Session->append_sid(ACP_INDEX .'?mod=db&act=sql'),
			"TOTAL_SIZE"				=> sprintf($Lang->data["db_total_size"], $total_size),
			"L_PAGE_TITLE"				=> $Lang->data["menu_db"] . $Lang->data['general_arrow'] . $Lang->data["menu_db_info"],
			"L_TABLE"					=> $Lang->data["db_table"],
			"L_ROWS"					=> $Lang->data["db_rows"],
			"L_DATA_SIZE"				=> $Lang->data["db_data_size"],
			"L_MAX_DATA_SIZE"			=> $Lang->data["db_max_data_size"],
			"L_INDEX_SIZE"				=> $Lang->data["db_index_size"],
			"L_CREATE_TIME"				=> $Lang->data["db_create_time"],
			"L_UPDATE_TIME"				=> $Lang->data["db_update_time"],
			"L_RUN_QUERY"				=> $Lang->data["db_run_query"],
			"L_RUN"						=> $Lang->data["db_run"],
			"L_RUN_NOTE"				=> $Lang->data["db_run_note"],
			"L_ERROR_NOT_SQL"			=> $Lang->data["db_error_not_sql"],
		));
	}

	function pre_backup_db(){
		global $Session, $Info, $Func, $Template, $Lang, $DB;

		$this->get_dbsql_info();

		//List tables
		$DB->query("SHOW TABLE STATUS FROM `". $DB->db_name ."`");
//		$table			= array();
		$total_size		= 0;
		if ( $DB->num_rows() ){
			$i	= 0;
			while ( $result = $DB->fetch_array() ){
				//Check whether this is our tables
				if ( !preg_match( "/^".$DB->prefix."/",$result['Name']) ){
					continue;
				}
				$total_size	+= $result['Data_length'];
				$Template->set_block_vars("tablerow",array(
					"NAME"			=> $result["Name"],
					"ROWS"			=> $result["Rows"],
					"DTSIZE"		=> $Func->compile_size($result["Data_length"]),
					'BG_CSS'		=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				));
				$i++;
			}
		}
		$DB->free_result();

		$total_size = $Func->compile_size($total_size);

		$Template->set_vars(array(
			'S_ACTION'					=> $Session->append_sid(ACP_INDEX .'?mod=db&act=bk'),
			'U_OPTIMIZE'				=> $Func->check_user_perm($this->user_perm, 'repair', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=db&act=repair&code=optimize') .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/update.gif" alt="" title="'. $Lang->data['db_optimize'] .'" align="absbottom" border=0>'. $Lang->data['db_optimize'] .'</a> &nbsp; &nbsp;' : '',
			'U_ANALYZE'					=> $Func->check_user_perm($this->user_perm, 'repair', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=db&act=repair&code=analyze') .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/update.gif" alt="" title="'. $Lang->data['db_analyze'] .'" align="absbottom" border=0>'. $Lang->data['db_analyze'] .'</a> &nbsp; &nbsp;' : '',
			'U_CHECK'					=> $Func->check_user_perm($this->user_perm, 'repair', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=db&act=repair&code=check') .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/update.gif" alt="" title="'. $Lang->data['db_check'] .'" align="absbottom" border=0>'. $Lang->data['db_check'] .'</a> &nbsp; &nbsp;' : '',
			'U_REPAIR'					=> $Func->check_user_perm($this->user_perm, 'repair', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=db&act=repair&code=repair') .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/update.gif" alt="" title="'. $Lang->data['db_repair'] .'" align="absbottom" border=0>'. $Lang->data['db_repair'] .'</a> &nbsp; &nbsp;' : '',
			"TOTAL_SIZE"				=> sprintf($Lang->data["db_total_size"], $total_size),
			"L_PAGE_TITLE"				=> $Lang->data["menu_db"] . $Lang->data['general_arrow'] . $Lang->data["menu_db_backup"],
			"L_SQL_RESULT"				=> $Lang->data["db_run_result"],
			"L_BACKUP_TBLS"				=> $Lang->data["db_backup_tbls"],
			"L_START_BACKUP"			=> $Lang->data["db_start_backup"],
			"L_STRUCTURE"				=> $Lang->data["db_structure"],
			"L_DATA"					=> $Lang->data["db_data"],
			"L_TABLE"					=> $Lang->data["db_table"],
			"L_ROWS"					=> $Lang->data["db_rows"],
			"L_DATA_SIZE"				=> $Lang->data["db_data_size"],
			"L_YES"						=> $Lang->data["general_yes"],
			"L_NO"						=> $Lang->data["general_no"],
			"L_USE_GZIP"				=> $Lang->data["db_use_gzip"],
			'L_CHOOSE_ITEM'				=> $Lang->data['db_error_not_check'],
		));
	}

	function get_dbsql_info(){
		global $DB, $Template, $Lang;

		$version = $DB->get_serverinfo();

		$Template->set_vars(array(
			"DBSQL_VERSION"			=> sprintf($Lang->data["db_version"], $version),
		));
	}

	function do_backup_db(){
		global $Session, $Info, $Template, $Lang, $DB, $Func;

		$tbl			= $Func->get_request('tbl', '', 'POST');
		$structure		= intval($Func->get_request('structure', 0, 'POST'));
		$data			= intval($Func->get_request('data', 0, 'POST'));
		$gzip			= intval($Func->get_request('gzip', 0, 'POST'));

		if ( empty($tbl) || !is_array($tbl) || (!$structure && !$data && !$gzip) ){
			$Template->page_transfer($Lang->data["db_error_not_choose"], $Session->append_sid(ACP_INDEX .'?mod=db&act=prebk'));
			return false;
		}

		$filename		= (sizeof($tbl) > 1) ? 'cms_backup' : '';
		if ( empty($filename) ){
			reset($tbl);
			while ( list(, $tbname) = each($tbl) ){
				$filename	= $tbname;
				break;
			}
		}

		if( $gzip ){
			@ob_start();
			@ob_implicit_flush(0);
			header('Content-Type: text/x-delimtext; name="'.$filename.'.sql.gz"');
			header('Content-disposition: attachment; filename='.$filename.'.sql.gz');
		}
		else{
			header('Content-Type: text/x-delimtext; name="'.$filename.'.sql"');
			header('Content-disposition: attachment; filename='.$filename.'.sql');
		}
		header("Pragma: no-cache");
		header("Expires: 0");

		reset($tbl);
		while ( list(, $tbname) = each($tbl) ){
			$this->sql	.= "\nDROP TABLE IF EXISTS $tbname;";
			$this->prepare_sql($tbname, $structure, $data);
		}
		echo $this->sql;

		if( $gzip ){
			$gzip_contents	= ob_get_contents();
			ob_end_clean();

			$gzip_size			= strlen($gzip_contents);
			$gzip_crc			= crc32($gzip_contents);

			$gzip_contents		= gzcompress($gzip_contents, 9);
			$gzip_contents		= substr($gzip_contents, 0, strlen($gzip_contents) - 4);

			echo "\x1f\x8b\x08\x00\x00\x00\x00\x00";
			echo $gzip_contents;
			echo pack('V', $gzip_crc);
			echo pack('V', $gzip_size);
		}

		//Save log
		$Func->save_log('menu_db_backup', 'log_backup');

		exit();
	}

	function do_repair_tables(){
		global $Session, $Info, $Template, $Lang, $DB, $Func;

		$code	= $Func->get_request('code', '', 'GET');
		$tbl	= $Func->get_request('tbl', '', 'POST');

		if ( empty($code) || empty($tbl) ){
			$Template->page_transfer($Lang->data["db_error_not_choose"], $Session->append_sid(ACP_INDEX .'?mod=db&act=prebk'));
			return false;
		}

		$flag_column	= 0;
		reset($tbl);
		while ( list(, $tbname) = each($tbl) ){
			//SQL Query
			if ( $code == 'repair' ){
				$sql	= "REPAIR TABLE $tbname;";
			}
			else if ( $code == 'analyze' ){
				$sql	= "ANALYZE TABLE $tbname;";
			}
			else if ( $code == 'check' ){
				$sql	= "CHECK TABLE $tbname;";
			}
			else {
				$sql	= "OPTIMIZE TABLE $tbname;";
			}

			//Run sql query
			$sql_query	= $DB->query($sql);

			//Columns -------------------------
			if ( !$flag_column ){
				$numfields	= $DB->num_fields($sql_query);
				for ($i=0; $i<$numfields; $i++){
					$Template->set_block_vars("fieldcol",array(
						'NAME'		=> $DB->field_name($sql_query, $i)
					));
				}
				$flag_column	= 1;
			}
			//---------------------------------

			//Details -------------------------
			$count			= $DB->num_rows();
//			$server_uptime	= array();
			if ( $count ){
				while ( $result = $DB->fetch_row() ){
					$Template->set_block_vars("recordrow", array());
					$css = 1;
					for ($i=0; $i<$numfields; $i++){
						$Template->set_block_vars("recordrow:recordcol", array(
							'CSS'		=> $css,
							'VALUE'		=> $result[$i],
						));
						$css = ($css == 1) ? 2 : 1;
					}
				}
			}
			//---------------------------------
		}

		//Save log
		$Func->save_log('menu_db_backup', 'log_repair');

		//Show backup page
		$Template->set_block_vars("displayresult", array());
		$this->pre_backup_db();
		return true;
	}

	function prepare_sql($tbname, $structure, $data){
		global $DB;

		if ( $structure ){
			$DB->query("SHOW CREATE TABLE `$tbname`");
			$result			= $DB->fetch_array();
			$this->sql		.= "\n". str_replace("`","",$result['Create Table']) .";\n";
		}

		if ( ($tbname == $DB->prefix ."sessions") || ($tbname == $DB->prefix ."regcodes") || ($tbname == $DB->prefix ."pwdcodes") ){
			return false;
		}

		//Get data
		if ( $data ){
			$str			= "INSERT INTO $tbname(";
			$sql_query		= $DB->query("SELECT * FROM $tbname");
			$numfields		= $DB->num_fields($sql_query);
			$tmp			= 0;
			for ($i=0; $i<$numfields; $i++){
				$str	.= ($tmp) ? ",". $DB->field_name($sql_query, $i) : $DB->field_name($sql_query, $i);
				$tmp	= 1;
			}
			$str .= ") VALUES(";

			if ( $DB->num_rows() ){
				while ($result = $DB->fetch_row()){
					$tmp		= 0;
					$tmpstr		= $str;
					for ($i=0; $i<$numfields; $i++){
						$tmpstr		.= ($tmp) ? "," : "";
						$tmpstr		.= "'". addslashes(preg_replace("/, $/","",$result[$i])) ."'";
						$tmp		= 1;
					}
					if ( $tmp ){
						$this->sql	.= $tmpstr .");\n";
					}
				}
			}
		}
		return true;
	}

	function pre_restore_db(){
		global $Session, $Info, $Template, $Lang, $DB;

		$this->get_dbsql_info();
		$Template->set_vars(array(
			'S_ACTION'					=> $Session->append_sid(ACP_INDEX .'?mod=db&act=rs'),
			"L_PAGE_TITLE"				=> $Lang->data["menu_db"] . $Lang->data['general_arrow'] . $Lang->data["menu_db_restore"],
			"L_SELECT_RS_FILE"			=> $Lang->data["db_select_rs_file"],
			"L_START_RESTORE"			=> $Lang->data["db_start_restore"],
		));
	}

	function do_restore_db(){
		global $Session, $Info, $Template, $Lang, $DB, $Func;

		$sqlfile	= isset($_FILES['sqlfile']['name']) ? htmlspecialchars($_FILES['sqlfile']['name']) : '';

		if ( empty($sqlfile) ){
			$Template->page_transfer($Lang->data["db_error_upload_file"], $Session->append_sid(ACP_INDEX .'?mod=db&act=prers'));
			return false;
		}

		$sql	= implode('', file($_FILES['sqlfile']['tmp_name']));
		if ( empty($sql) ){
			$Template->page_transfer($Lang->data["db_error_upload_file"], $Session->append_sid(ACP_INDEX .'?mod=db&act=prers'));
			return false;
		}

		$this->remove_remarks($sql);
		$sqlqueries		= $this->compile_sql($sql);
		$count			= sizeof($sqlqueries);

		for ($i=0; $i<$count; $i++){
			$sqlquery	= trim($sqlqueries[$i]);
			if ( !empty($sqlquery) && (substr($sqlquery, 0, 1) != '#') ){
				$DB->query($sqlquery);
			}
		}

		//Save log
		$Func->save_log('menu_db_restore', 'log_restore');

		$Template->page_transfer($Lang->data["db_success_restore"], $Session->append_sid(ACP_INDEX .'?mod=db&act=dbinfo'));
		return true;
	}

	function remove_remarks(&$sql){
		$lines		= explode("\n",$sql);
		$count		= count($lines);
		$sql		= "";
		for ($i = 0; $i<$count; $i++){
			if (($i != ($count - 1)) || (strlen($lines[$i]) > 0)){
				if (substr($lines[$i], 0, 1) != "#"){
					$sql	.= $lines[$i] . "\n";
				}
				else{
					$sql	.= "\n";
				}
				$lines[$i]	= "";
			}
		}
	}

	function compile_sql($sql){
		$tmp		= explode(";", $sql);
		$sql		= "";
		$output		= array();
		$matches	= array();
		$count		= count($tmp);

		for ($i=0; $i<$count; $i++){
			if ( ($i != ($count - 1)) || (strlen($tmp[$i] > 0)) ){
				$total_quotes		= preg_match_all("/'/", $tmp[$i], $matches);
				$escaped_quotes		= preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tmp[$i], $matches);
				$unescaped_quotes	= $total_quotes - $escaped_quotes;

				if (($unescaped_quotes % 2) == 0){
					$output[]	= $tmp[$i];
					$tmp[$i]	= "";
				}
				else{
					$temp		= $tmp[$i] .";";
					$tmp[$i]	= "";
					$complete_stmt	= false;
					
					for ($j = $i+1; (!$complete_stmt && ($j < $count)); $j++){
						$total_quotes		= preg_match_all("/'/", $tmp[$j], $matches);
						$escaped_quotes		= preg_match_all("/(?<!\\\\)(\\\\\\\\)*\\\\'/", $tmp[$j], $matches);
						$unescaped_quotes	= $total_quotes - $escaped_quotes;

						if (($unescaped_quotes % 2) == 1){
						    $output[]		= $temp . $tmp[$j];
						    $tmp[$j]		= "";
						    $temp			= "";
						    $complete_stmt	= true;
						    $i = $j;
						}
						else{
						    $temp			.= $tmp[$j] .";";
						    $tmp[$j]		= "";
						}
					}
				}
			}
		}
		return $output;
	}

	function run_usersql(){
		global $Session, $Template, $DB, $Lang, $Func;

		$sql	= stripslashes($Func->get_request('sql', '', 'POST'));

		if ( !empty($sql) ){
			$this->remove_remarks($sql);
			$sqlqueries		= $this->compile_sql($sql);
			$count			= sizeof($sqlqueries);

			if ($count > 1){
				$err_flag	= 0;
				for ($i=0; $i<$count-1; $i++){
					$sqlquery	= trim($sqlqueries[$i]);
					if ( !empty($sqlquery) && (substr(trim($sqlquery), 0, 1) != '#') ){
						if ( !$DB->query($sqlquery, 0) ){
							$this->run_sql($sqlquery, $Lang->data["db_run_query"], $sql);
							$err_flag	= 1;
							break;
						}
					}
				}
				if ( !$err_flag ){
					$this->run_sql(trim($sqlqueries[$count-1]), $Lang->data["db_run_query"], $sql);
				}
			}
			else{
				$this->run_sql($sql, $Lang->data["db_run_query"]);
			}
		}

		$Template->set_vars(array(
			'S_RUN_SQL'					=> $Session->append_sid(ACP_INDEX .'?mod=db&act=sql'),
			"SQL_VALUE"					=> $sql,
			"L_PAGE_TITLE"				=> $Lang->data["menu_db"] . $Lang->data['general_arrow'] . $Lang->data["db_run_query"],
			"L_RUN_QUERY"				=> $Lang->data["db_run_query"],
			"L_RUN_RESULT"				=> $Lang->data["db_run_result"],
			"L_RUN"						=> $Lang->data["db_run"],
			"L_RUN_NOTE"				=> $Lang->data["db_run_note"],
		));

		//Save log
		$Func->save_log('menu_db_info', 'log_run_sql');
	}

	function run_sql($sql, $title, $fullsql = ""){
		global $Session, $Info, $Template, $Lang, $DB;

		$sql_result	= !empty($fullsql) ? "<br><br>\n". $fullsql : $sql;

		$this->get_dbsql_info();
		$sql_query		= $DB->query($sql, 0);

		if ($DB->err_desc != ""){
			$Info->tpl_main		= "db_run_error";
			$Template->set_vars(array(
				"L_SQL_ERROR"		=> $Lang->data["db_error_sql"],
				"SQL_RESULT"		=> sprintf($Lang->data["db_error_run"], $sql_result, $DB->err_desc),
			));
			return false;
		}

		if ( preg_match("/^INSERT|UPDATE|DELETE|ALTER|CREATE|DROP/i", trim($sql)) ){
			$Info->tpl_main		= "db_run_noresult";
			$Template->set_vars(array(
				"SQL_RESULT"	=> sprintf($Lang->data["db_success_run"], $sql_result),
			));
			return false;
		}

		$numfields	= $DB->num_fields($sql_query);
		for ($i=0; $i<$numfields; $i++){
			$Template->set_block_vars("fieldcol",array(
				'NAME'		=> $DB->field_name($sql_query, $i)
			));
		}

		$count			= $DB->num_rows();
		$server_uptime	= array();
		if ( $count ){
			$k	= 0;
			while ( $result = $DB->fetch_row() ){
				$Template->set_block_vars("recordrow", array(
					'BG_CSS'	=> ($k % 2) ? 'tdtext2' : 'tdtext1',
				));
				$k++;
				for ($i=0; $i<$numfields; $i++){
					if ( $Info->act == "runtime" ){
						$server_uptime[$result[0]]	= $result[1];
					}
					$Template->set_block_vars("recordrow:recordcol",array(
						'VALUE'		=> $result[$i],
					));
				}
			}
		}

		if ( $Info->act == "runtime" ){//Show server Uptime
			$DB->query('SELECT UNIX_TIMESTAMP() - ' . $server_uptime['Uptime'] . ';');
			$result		= $DB->fetch_row();
			$uptime 	= sprintf($Lang->data["db_serveruptime"], $this->compile_seconds($server_uptime['Uptime']), $this->compile_timestamp($result[0]));
		}
		else{
			$uptime		= "";
		}

		$Template->set_vars(array(
			"L_SQL_TITLE"			=> $title,
			"COLSPAN"				=> ($numfields)*2,
			"SERVER_UPTIME"			=> $uptime,
		));

		return true;
	}

	function compile_seconds($seconds){
		global $Lang;

		//Based on phpMyAdmin
		$days		= floor($seconds / 86400);
		if ($days > 0) {
			$seconds	-= $days * 86400;
		}
		$hours		= floor($seconds / 3600);
		if ($days > 0 || $hours > 0) {
			$seconds	-= $hours * 3600;
		}
		$minutes	= floor($seconds / 60);
		if ($days > 0 || $hours > 0 || $minutes > 0) {
			$seconds	-= $minutes * 60;
		}

		return sprintf($Lang->data["db_servertmptime"], (string)$days, (string)$hours, (string)$minutes, (string)$seconds);
	}

	function compile_timestamp($timestamp){
		global $Lang;

		//based on phpMyadmin
		$day_of_week	= explode(",",$Lang->data["db_day_of_week"]);
		$month			= explode(",",$Lang->data["db_month"]);

		$date		= preg_replace('@%[aA]@', $day_of_week[(int)strftime('%w', $timestamp)], $Lang->data["db_dateformat"]);
		$date		= preg_replace('@%[bB]@', $month[(int)strftime('%m', $timestamp)-1], $date);

		return strftime($date, $timestamp);
	}
}

?>