<?php
/* =============================================================== *\
|		Module name: Mysql Engine									|
|		Module version: 1.6											|
|		Begin: 20 August 2003										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

class DBSql extends DB_Config
{
	var $sverconn_id     = 0;
	var $dbconn_id       = 0;

	var $query_id        = 0;
	var $insert_id       = 0;
	var $record_row      = array();
	var $query_count     = 0;

	var $err_no          = 0;
	var $err_desc        = "";

	function DBSql(){
		if ( !isset($this->db_host) || !isset($this->db_name) || !isset($this->db_username) || !isset($this->db_password) ){
			$this->halt("Not found database information: database host, database name,...");
		}
		$this->connect();
		$this->selectdb();
	}

	function connect(){
		if ( empty($this->db_password) ){
			if ($this->db_persistent){
				$this->sverconn_id = @mysql_pconnect($this->db_host,$this->db_username)  or  $this->halt("Couldn't connect to database.");
			}
			else{
				$this->sverconn_id = @mysql_connect($this->db_host,$this->db_username)  or  $this->halt("Couldn't connect to database.");
			}
		}
		else{
			if ($this->db_persistent){
				$this->sverconn_id = @mysql_pconnect($this->db_host,$this->db_username,$this->db_password)  or  $this->halt("Couldn't connect to database.");
			}
			else{
				$this->sverconn_id = @mysql_connect($this->db_host,$this->db_username,$this->db_password)  or  $this->halt("Couldn't connect to database.");
			}
		}
	}

	function selectdb($database=""){
		if ( !empty($database) ) $this->db_name=$database;

		$this->dbconn_id = @mysql_select_db($this->db_name,$this->sverconn_id)  or  $this->halt("Couldn't use this database:". $this->db_name);
		if (!$this->dbconn_id){
			$this->halt("Couldn't use this database: ".$this->database);
		}
	}

	function query($query_string = "", $ann = 1){
		if ( empty($query_string) ) $this->halt("Couldn't query an empty query string.");

		if ($this->query_show){
			echo "Query string: $query_string <br>";
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$start_time = $mtime;
		}

		if ($this->err_show){
			$this->query_id = mysql_query($query_string,$this->sverconn_id) or $this->halt("Couldn't query this query string: ". $query_string, $ann);
		}
		else{
			$this->query_id = mysql_query($query_string,$this->sverconn_id) or $this->halt("Couldn't query a query string.", $ann);
		}
		$this->query_count++;

		if ($this->query_show){
			$mtime = microtime();
			$mtime = explode(" ",$mtime);
			$mtime = $mtime[1] + $mtime[0];
			$end_time = $mtime;
			$totaltime = ($end_time - $start_time);
			echo "Time query: $totaltime<br>";
		}

		return $this->query_id;
	}

	function fetch_array($query_id=-1){
		if ( $query_id!=-1 ) $this->query_id=$query_id;
		if ( !$this->query_id ) $this->halt("Invalid query id");

		$this->record_row = mysql_fetch_array($this->query_id, MYSQL_ASSOC);

		return $this->record_row;
	}

	function fetch_all_array($query_id=-1){
		if ( $query_id!=-1 ) $this->query_id=$query_id;
		if ( !$this->query_id ) $this->halt("Invalid query id");

		$record = array();
		while ( $tmp_info = mysql_fetch_array($this->query_id, MYSQL_ASSOC) ){
			$record[] = $tmp_info;
		}

		return $record;
	}

	function fetch_row($query_id=-1){
		if ( $query_id!=-1 ) $this->query_id=$query_id;
		if ( !$this->query_id ) $this->halt("Invalid query id");

		$this->record_row = mysql_fetch_row($this->query_id);

		return $this->record_row;
	}

	function num_rows($query_id=-1){
		if ( $query_id!=-1 ) $this->query_id=$query_id;
		if ( !$this->query_id ) $this->halt("Invalid query id");

		return @mysql_num_rows($this->query_id);
	}

	function num_fields($query_id=-1){
		if ( $query_id!=-1 ) $this->query_id=$query_id;
		if ( !$this->query_id ) $this->halt("Invalid query id");

		return @mysql_num_fields($this->query_id);
	}

	function field_name($query_id,$field_id){
		return mysql_field_name($query_id,$field_id);
	}

	function list_tables($dbname=""){
		if (!empty($dbname)){
			$this->db_name = $dbname;
		}

		$this->query_id = mysql_list_tables($this->db_name);
		return $this->query_id;
	}

	function get_serverinfo(){
		return mysql_get_server_info();
	}

	function data_seek($row_number=0){
		return mysql_data_seek($this->sverconn_id, $row_number);
	}

	function insert_id(){
		$this->insert_id = mysql_insert_id();
		return $this->insert_id;
	}

	function get_query_count(){
		return $this->query_count;
	}

	function free_result($query_id=-1){
		if ($query_id!=-1) $this->query_id = $query_id;
		return @mysql_free_result($this->query_id);
	}

	function drop_db($dbname=""){
		if ( empty($dbname) ) $this->halt("Dropt DB: Invalid db name");
		return mysql_drop_db($dbname,$this->sverconn_id);
	}

	function fetch_field($query_id=-1){
		if ($query_id!=-1) $this->query_id = $query_id;
		return mysql_fetch_field($this->query_id);
	}

	function close(){
		return @mysql_close($this->sverconn_id);
	}

	function halt($msg, $ann=1){
		if ($ann == 0) return;

		$this->err_no   = mysql_errno();
		$this->err_desc = mysql_error();

		echo "<br><br><center><b>ERROR</b></center><br>";
		echo $msg;
		echo "<br>Please contact admin about this err. <a href='javascript:history.back(-1);'>Back</a>";

		if ($this->err_show){
			echo "<br><br><b>DETAIL</b><br>";
			echo $this->err_no," : ",$this->err_desc;
		}

		if ( ($this->err_report) && !empty($this->technical_email) ){
			$subject = "Database error in ". getenv("HTTP_HOST");
			$message = "Database error in ". getenv("HTTP_HOST");
			$message .= "\n\n<b>$msg</b>\n";
			$message .= "\nError Number: ". $this->err_id;
			$message .= "\nError Detail: ". $this->err_detail;
			$message .= "\nDate: ". date("l dS of F Y h:i:s A");
			$message .= "\nScript File: http://". getenv("HTTP_HOST") . getenv("SCRIPT_NAME");
			@mail($this->technical_email,$subject,$message,"From: $this->technical_email") or die("SMTP ERROR. Could not send email.");
		}

		$this->close($this->sverconn_id);
		die();
	}
}
?>