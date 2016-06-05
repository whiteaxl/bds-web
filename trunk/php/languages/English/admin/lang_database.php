<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Database;

class Lang_Module_Database
{
	var $data		= array();

	function Lang_Module_Database(){
		//Database
		$this->data['db_table']					= 'Table';
		$this->data['db_rows']					= 'Rows';
		$this->data['db_data_size']				= 'Data Size';
		$this->data['db_max_data_size']			= 'Max Data Size';
		$this->data['db_index_size']			= 'Index Size';
		$this->data['db_create_time']			= 'Create Time';
		$this->data['db_update_time']			= 'Update Time';
		$this->data['db_version']				= '<b>Mysql %s</b>';
		$this->data['db_total_size']			= 'Total Data Size: %s';
		$this->data['db_info']					= 'Database Info';
		$this->data['db_sql_runtime']			= 'SQL Runtime Info';
		$this->data['db_sql_system']			= 'SQL System Vars';
		$this->data['db_sql_process']			= 'SQL Processes';
		$this->data['db_run_query']				= 'Run Query';
		$this->data['db_run_result']			= 'Result';
		$this->data['db_run_sql']				= 'Run Sql';
		$this->data['db_backup']				= 'Backup';
		$this->data['db_restore']				= 'Restore';
		$this->data['db_optimize']				= 'Optimize';
		$this->data['db_analyze']				= 'Analyze';
		$this->data['db_check']					= 'Check';
		$this->data['db_repair']				= 'Repair';
		$this->data['db_run']					= '   Run   ';
		$this->data['db_run_note']				= '<b>Note:</b> This is only for advanced user. You must be careful with your Sql Query, especially with DELETE, DROP or UPDATE Query.';
		$this->data['db_backup_tbls']			= 'Backup selected tables';
		$this->data['db_start_backup']			= 'Start Backup';
		$this->data['db_structure']				= 'Get Structure';
		$this->data['db_data']					= 'Get Data';
		$this->data['db_select_rs_file']		= 'Select a file';
		$this->data['db_start_restore']			= 'Start Restore';
		$this->data['db_serveruptime']			= 'This MySQL server has been running for %s. It started up on %s.';
		$this->data['db_servertmptime']			= '%s days, %s hours, %s minutes and %s seconds';
		$this->data['db_day_of_week']			= 'Sun,Mon,Tue,Wed,Thu,Fri,Sat';
		$this->data['db_month']					= 'Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec';
		$this->data['db_dateformat']			= '%B %d, %Y at %I:%M %p';
		$this->data['db_use_gzip']				= 'Use Gzip';

		//Db error
		$this->data['db_error_not_check']		= 'Please check tables!';
		$this->data['db_error_not_sql']			= 'Please enter your sql query!';
		$this->data['db_error_sql']				= 'SQL Error';
		$this->data['db_error_run']				= '<strong>Query:</strong> %s <br><br><strong>%s</strong>';
		$this->data['db_error_not_choose']		= 'Please choose tables!';
		$this->data['db_error_upload_file']		= 'Please choose file to upload!';
		$this->data['db_success_run']			= '<strong>Query:</strong> %s <br><br><strong>Executed successfully!</strong>';
		$this->data['db_success_backup']		= 'Backup Database Successfully!';
		$this->data['db_success_restore']		= 'Restore Database Successfully!';
	}
}

?>