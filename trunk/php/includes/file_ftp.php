<?php
/* =============================================================== *\
|		Module name: FTP											|
|		Module version: 1.0											|
|		Begin: 14 January 2008										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
	die('Hacking attempt!');
}

$FTP	= new FTP($Info->option['ftp_host'], $Info->option['ftp_username'], $Info->option['ftp_password'], $Info->option['site_path'], $Info->option['ftp_port']);

class FTP
{
	var $conn_id			= 0;
	var $turn_on			= 0;
	var $root				= "/";

	var $ftp_host			= "";
	var $ftp_port			= 21;
	var $ftp_user			= "";
	var $ftp_pass			= "";

	function FTP($host, $user, $pass, $root, $port = 21){
		$this->ftp_host		= $host;
		$this->ftp_port		= $port;
		$this->ftp_user		= $user;
		$this->ftp_pass		= $pass;

		$this->root			= $root;
		$this->turn_on		= !empty($host) ? 1 : 0;
	}

	function connect(){
		$this->conn_id	= ftp_connect($this->ftp_host, $this->ftp_port) or $this->halt('Could not connect to FTP Server '. $this->ftp_host);
		ftp_login($this->conn_id, $this->ftp_user, $this->ftp_pass) or $this->halt('FTP login failed!');
		$this->cd_dir($this->root);
	}

	function check_connect(){
		if ( !$this->conn_id ){
			$this->connect();
		}
	}

	//Create new directory
	function make_dir($dirname, $chmod){
		$this->check_connect();

		ftp_mkdir($this->conn_id, $dirname); //Create new directory
		$this->set_chmod($chmod, $dirname); //Chmod directory

		//Copy images/index.html to new directory
		ftp_get($this->conn_id, $dirname .'/index.html', 'images/index.html', FTP_BINARY);
	}

	//Delete existing directory
	function delete_dir($dirname){
		$this->check_connect();

		if ( ($file_list = ftp_nlist($this->conn_id, $dirname)) !== false ){
			reset($file_list);
			while (list(, $filename) = each($file_list)){
				if ( ($filename != '.') && ($filename != '..') ){
					if ( ftp_size($this->conn_id, $dirname .'/'. $filename) != -1 ){ //File
						ftp_delete($this->conn_id, $dirname .'/'. $filename);
					}
					else{
						$this->delete_dir($dirname .'/'. $filename);
					}
				}
			}
		}

		if ( file_exists($dirname) ){
			ftp_rmdir($this->conn_id, $dirname);
		}
	}

	//Move directory
	function move_dir($old_dir, $new_dir){
		$this->check_connect();

		if ($file_list	= ftp_nlist($this->conn_id, $old_dir)){
			reset($file_list);
			while (list(, $filename) = each($file_list)){
				if ( ($filename != '.') && ($filename != '..') ){
					if ( ftp_size($this->conn_id, $old_dir .'/'. $filename) != -1 ){ //File
						ftp_get($this->conn_id, $new_dir .'/'. $filename, $old_dir .'/'. $filename, FTP_BINARY);
						ftp_delete($this->conn_id, $old_dir .'/'. $filename);
					}
					else{ //Directory
						$this->make_dir($new_dir .'/'. $filename); //Create new folder
						$this->move_dir($old_dir .'/'. $filename, $new_dir .'/'. $filename); //Move all files
					}
				}
			}
		}

		if ( file_exists($old_dir) ){
			ftp_rmdir($this->conn_id, $old_dir);
		}
	}

	function copy_file($source_file, $dest_file, $mode = FTP_BINARY){
		$this->check_connect();

		if ( file_exists($source_file) ){
			ftp_get($this->conn_id, $dest_file, $source_file, $mode);
		}
	}

	function move_file($source_file, $dest_file){
		$this->check_connect();
		if ( file_exists($source_file) ){
			ftp_get($this->conn_id, $dest_file, $source_file, $mode);
			ftp_delete($this->conn_id, $source_file);
		}
	}

	function delete_file($filename){
		$this->check_connect();
		if ( file_exists($filename) ){
			ftp_delete($this->conn_id, $filename);
		}
	}

	//Remove expired files in temporary directory
	function remove_expired_files($dirname, $expired_time){
		$this->check_connect();

		if ( ($file_list = ftp_nlist($this->conn_id, $dirname)) !== false ){
			reset($file_list);
			while (list(, $filename) = each($file_list)){
				if ( ($filename != '.') && ($filename != '..') ){
					if ( ftp_mdtm($dirname .'/'. $filename) <= $expired_time ){
						ftp_delete($this->conn_id, $dirname .'/'. $filename);
					}
				}
			}
		}
	}

	function set_chmod($mode, $file){
		$this->check_connect();
		if ( function_exists('ftp_chmod') ){
			ftp_chmod($this->conn_id, $mode, $file); //php 5 only
		}
		else{
			ftp_site($this->conn_id, "CHMOD $chmod $file");
		}
	}

	function cd_dir($dir){
		if ( $dir == '..' ){
			ftp_cdup($this->conn_id);
		}
		else{
			ftp_chdir($this->conn_id, $dir);
		}
	}

	function upload_file($local_file, $remote_file, $chmod = 0644, $mode = FTP_BINARY){
		$this->check_connect();
		//Try to upload again
		ftp_put($this->conn_id, $remote_file, $local_file, $mode) or $this->halt("Could not upload file <em>$local_file</em> to <em>". $remote_file ."</em>");
		$this->set_chmod($chmod, $remote_file);
	}

	function upload_resource_file($local_resource, $remote_file, $chmod = 0644, $mode = FTP_BINARY){
		$this->check_connect();
		//Try to upload again
		ftp_fput($this->conn_id, $remote_file, $local_resource, $mode) or $this->halt("Could not upload file to <em>". $remote_file ."</em>");
	}

	function make_path($path){
		$this->check_connect();
		if ( !ftp_chdir($this->conn_id, $path) ){
			$path_info	= explode('/', $path);
			if ( is_array($path_info) ){
				//Get every directory name of a path
				reset($path_info);
				while ( list(, $name) = each($path_info) ){
					$name	= trim($name);
					if ( !empty($name) ){
						//Create directory if it does not exist
						if ( !ftp_chdir($this->conn_id, $name) ){
							$this->make_dir($name);
							$this->cd_dir($name);
							//Copy file index.html
							$this->upload_file('images/index.html', 'index.html');
						}
					}
				}
			}
		}

		$this->cd_dir($this->root);
	}

	function rename_file($old_name, $new_name){
		$this->check_connect();
		if ( file_exists($old_name) ){
			ftp_rename($this->conn_id, $old_name, $new_name);
		}
	}

	function close(){
		if ( $this->conn_id ){
			ftp_close($this->conn_id);
			$this->conn_id	= 0;
		}
	}

	function halt($msg){
		global $DB;

		$DB->close();
		echo "<b>FTP Error:</b>\n<br>$msg";
		die();
	}
}
?>