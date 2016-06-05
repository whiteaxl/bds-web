<?php
class DB_Config
{
	var $db_host			= "localhost"; //DB host
	var $db_name			= "tedin_reland"; //DB name
	var $db_username		= "tedin_reland"; //DB username
	var $db_password		= "123456"; //DB password

	var $prefix				= 'news_';
	var $query_show			= 0; //1: show all excuted queries | 0: Not show
	var $err_show			= 1; //1: show the sql query that couldn't be excuted | 0: Not show
	var $err_report			= 0; //1: report any error in db to the technical_email | 0: Not report
	var $db_persistent		= 0; //1: use persistent connection | 0: Not use

	var $technical_email	= "websolutionvndt@yahoo.com.vn"; //This email will be used to report if your database has any error
}

?>