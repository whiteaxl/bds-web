<?php
/* =============================================================== *\
|		Module name: FileMan										|
|		Module version: 1.0											|
|		Begin: 14 January 2008										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
	die('Hacking attempt!');
}
include('./includes/file_ftp.php');

class FileMan
{
	var $root			= "";

	function FileMan($root = ""){
		if ( !empty($root) ){
			$this->root		= $root;
		}
	}

	//Create new directory
	function make_dir($dirname, $chmod = 0777){
		global $FTP;

		if ( $FTP->turn_on ){
			$FTP->make_dir($dirname, $chmod);
		}
		else{
			@umask(0);
			mkdir($this->root . $dirname, $chmod) or $this->halt('Could not create folder <strong>'. $this->root . $dirname .'</strong>');
			$handle	= fopen($this->root . $dirname .'/index.html', 'w') or $this->halt('Could not copy index.html to <strong>'. $this->root . $dirname .'</strong>');
			fwrite($handle, " ");
			fclose($handle);
			@chmod($dirname, $chmod);
		}
	}

	//Delete existing directory
	function delete_dir($dirname){
		global $FTP;

		if ( $FTP->turn_on ){
			$FTP->delete_dir($dirname);
		}
		else{
			if ( is_dir($this->root . $dirname) ){
				$dh  = opendir($this->root . $dirname);
				while (($filename = readdir($dh)) !== false){
					if ( ($filename != '.') && ($filename != '..') ){
						@unlink($this->root . $dirname .'/'. $filename); //Delete files
					}
				}
				closedir($dh);
				rmdir($this->root . $dirname); //Delete directory
			}
		}
	}

	//Move directory
	function move_dir($old_dir, $new_dir){
		global $FTP;

		if ( $FTP->turn_on ){
			$FTP->move_dir($old_dir, $new_dir);
		}
		else{
			if ( !is_dir($old_dir) ){
				$this->halt("$old_dir is not a directory.");
			}
			if ( !is_dir($new_dir) ){
				$this->make_dir($new_dir);
			}

			$handle		= opendir($this->root . $old_dir);
			while ( ($file = readdir($handle)) != false ) {
				if ( ($file != ".") && ($file != "..") ) {
					if ( is_dir($this->root . $old_dir .'/'. $file ) ){
						$this->move_dir($this->root . $old_dir .'/'. $file, $this->root . $new_dir .'/'. $file);
					}
					else{
						copy($this->root . $old_dir .'/'. $file, $this->root . $new_dir .'/'. $file);
						@unlink($this->root . $old_dir .'/'. $file);
					}
				}
			}
			closedir($handle);
			rmdir($this->root . $old_dir);
		}
	}

	//Copy file to new folder
	function copy_file($source_file, $dest_file){
		global $FTP;

		if ( $FTP->turn_on ){
			$FTP->copy_file($source_file, $dest_file);
		}
		else{
			@copy($this->root . $source_file, $this->root . $dest_file) or $this->halt("Could not copy file from <em>$source_file</em> to <em>$dest_file</em>");
		}
	}

	//Delete file
	function delete_file($filename){
		global $FTP;

		if ( $FTP->turn_on ){
			$FTP->delete_file($filename);
		}
		else{
			@unlink($filename);
		}
	}

	//Transfer files from temporary folder to module's folder
	function transfer_temp_files(&$used_files, $dest_dir, &$data_info){
		global $Info, $FTP;

		if ( empty($used_files) ){
			return false;
		}
		$user_id	= $Info->user_info['user_id'];

		//Transfer used files
		$file_info	= explode(',', $used_files);
		$used_files	= "";

		if ( is_array($file_info) ){
			$source_info	= array();
			$replace_info	= array();
			$str_content	= implode("\n", $data_info);

			while ( list(, $filepath) = each($file_info) ){
				$filepath = trim($filepath);
				if ( !empty($filepath) && preg_match("#(.*?)$filepath(.*?)#", $str_content) ){
					$filename			= basename($filepath);
					$source_filename	= $filename;
					$dest_filename		= $filename;
					unset($filename);

					$used_files			.= !empty($used_files) ? ','. $dest_filename : $dest_filename;
					$source_info[]		= "#". preg_quote($filepath) ."#";
					$replace_info[]		= $dest_dir .'/'. $dest_filename;

					//Move files from temp folder to destination folder
					if ( $FTP->turn_on ){
						$FTP->copy_file("upload/". $source_filename, $dest_dir .'/'. $dest_filename);
						$FTP->delete_file("upload/". $source_filename);
					}
					else{
						if ( file_exists($this->root . "upload/". $source_filename) ){
							@copy($this->root . "upload/". $source_filename, $dest_dir .'/'. $dest_filename) or $this->halt('Could not copy file. Please chmod 777 to '. $dest_dir);
							unlink($this->root . "upload/". $source_filename);
						}
					}
				}
			}
			$this->remove_temp_files();

			reset($source_info);
			reset($replace_info);
			reset($data_info);
			while (list($key, $val) = each($data_info)){
				$data_info[$key]	= preg_replace($source_info, $replace_info, $val);
			}
			return true;
		}
		return false;
	}

	//Remove expired files in temporary folder
	function remove_temp_files(){
		global $FTP;

		$expired_time	= CURRENT_TIME - 18000;//5 hours x 60 minutes x 60 seconds

		if ( $FTP->turn_on ){
			$FTP->remove_expired_files("upload", $expired_time);
		}
		else{
			$dir	= $this->root . "upload/";
			$handle	= opendir($dir);
			while ( ($file = readdir($handle)) != false ) {
				if ( ($file != ".") && ($file != "..") && is_file($dir ."/". $file) ) {
					if ( fileatime($this->root . $dir ."/". $file) < $expired_time ){
						@unlink($this->root . $dir ."/". $file);
					}
				}
			}
			closedir($handle);
		}
	}

	// Clean, replace full URL by short URL
	// $used_files: the old files
	// $dest_dir: destination directory
	// $data_info: contents for replacing new URLs
	// $stored_files: new files which will be updated to db
	function clean_used_files($used_files, $dest_dir, &$data_info, &$stored_files){
		global $DB, $Info, $FTP;

		$site_url = $Info->option['site_url'];
		if ( substr($site_url, -1) != "/" ){
			$site_url .= "/";
		}

		if ( !empty($used_files) ){
			$file_info		= explode(",", $used_files);
			$str_content	= implode("\n", $data_info);
			$used_files		= "";

			reset($file_info);
			while (list(, $filename) = each($file_info)){
				if ( empty($filename) ) continue;

				if ( preg_match("#(.*?)$filename(.*?)#", $str_content) ){
					//Remove URL
					$stored_files	.= !empty($stored_files) ? ','. $filename : $filename;
					$url			= $site_url . $dest_dir .'/'. $filename;
					$replace_url	= $dest_dir .'/'. $filename;
					reset($data_info);
					while (list($key, $val) = each($data_info)){
						$data_info[$key]	= str_replace($url, $replace_url, $val);
					}
				}
				else{
					//remove files from server
					if ( $FTP->turn_on ){
						$FTP->delete_file($dest_dir .'/'. $filename);
					}
					else{
						unlink($this->root . $dest_dir .'/'. $filename);
					}
				}
			}
		}
	}

	//Delete unused files
	function delete_unused_files($new_files, $old_files, $dest_dir){
		global $FTP;

		$n_files	= explode(',', $new_files);
		$o_files	= explode(',', $old_files);

		reset($o_files);
		while (list(, $filename) = each($o_files)){
			$filename	= trim($filename);
			if ( !empty($filename) && !in_array($filename, $n_files) ){
				if ( $FTP->turn_on ){
					$FTP->delete_file($dest_dir .'/'. $filename);
				}
				else{
					unlink($this->root . $dest_dir .'/'. $filename);
				}
			}
		}
	}

	//Check directory
	function check_dir(&$dir, $root){
		$dir	= str_replace('/','',$dir);
		$dir	= str_replace('\\','',$dir);

		if ( is_dir($this->root . $root ."/". $dir)){
			return true;
		}
		return false;
	}

	function create_index($dirname){
		$handle	= fopen($this->root . $dirname .'/index.html', 'w') or $this->halt('Could not create index file <strong>'. $this->root . $dirname .'/index.html</strong>');
		fwrite($handle, "You don't have permission to view file list of this folder.");
		fclose($handle);
	}

	function upload_file($local_file, $remote_file, $chmod = 0644){
		global $FTP;

		if ( $FTP->turn_on ){
			$FTP->upload_file($local_file, $remote_file, $chmod);
		}
		else{
//			move_uploaded_file($local_file, $remote_file);
			copy($local_file, $remote_file);
			@chmod($remote_file, $chmod);
		}
	}

	//Check allowed file types
	function check_filetype($filetype, $config_type = "image", $extra_types = ""){
		global $Info;

		$type = $Info->option[$config_type ."_type"] . $extra_types;

		if ( empty($type) ){
			return false;
		}

		$tmp = explode(',', $type);
		if ( !is_array($tmp) ){
			$ftype[0] = $tmp;
		}
		else{
			$ftype = $tmp;
		}

		reset ($ftype);
		while ( list(, $val) = each($ftype) ){
			if ( $filetype == trim($val) ){
				return true;
			}
		}
		return false;
	}

	function halt($msg){
		global $DB;

		$DB->close();
		echo "<b>File Management Error:</b>\n<br>$msg";
		die();
	}
}
?>