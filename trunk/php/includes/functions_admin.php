<?php
/* =============================================================== *\
|		Module name:      Functions									|
|																	|
\* =============================================================== */
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

class Func_Admin extends Func_Global
{
	function Func_Admin(){
		$this->check_gpc();
	}

	//Check user's login status
	function check_login($transfer = 1){
		global $Session, $Template, $Lang, $Info, $DB;

		$login_time		= CURRENT_TIME - $Info->option['time_login'];
		$cookie_time	= CURRENT_TIME - $Info->option['cookie_time'];
		$session_hash	= isset($_COOKIE['session_hash']) ? htmlspecialchars($_COOKIE['session_hash']) : '';

		//Don't check login if it is login module
		if ( $Info->mod == 'idx' ) return false;

		if ( !empty($Session->sid) ){
			//Check Session ID and Session Hash. Require cookie enabled
			$DB->query("SELECT U.*, S.user_groups, S.kicked_by, S.kicked_time FROM ". $DB->prefix ."user AS U, ". $DB->prefix ."session AS S WHERE S.session_id='". $Session->sid ."' AND S.session_hash='". $session_hash ."' AND S.user_id=U.user_id AND ((S.session_time >= $login_time) OR (S.session_time>$cookie_time AND S.auto_login=1))");
			if ( !$DB->num_rows() ){
				//Check Session ID and Client IP. In case client's browser does not support cookie
				$DB->query("SELECT U.*, S.user_groups, S.kicked_by, S.kicked_time FROM ". $DB->prefix ."user AS U, ". $DB->prefix ."session AS S WHERE S.session_id='". $Session->sid ."' AND U.user_ip='". $Session->ip ."' AND S.user_id=U.user_id AND ((S.session_time >= $login_time) OR (S.session_time>$cookie_time AND S.auto_login=1))");
			}

			if ($DB->num_rows()){
				$result = $DB->fetch_array();
				if ($result["user_id"]){
					if ( $result['kicked_time'] > 0){
						if ( $transfer ){
							$Template->page_transfer( sprintf($Lang->data["user_kicked_by"], $result['kicked_by'], $Info->option['kick_minutes']), $Session->append_sid(ACP_INDEX));
						}
						else{
							return false;
						}
					}
					return true;
				}
			}
		}
		$DB->free_result();

		if ( $transfer ){
			$Template->page_transfer($Lang->data["general_error_not_login"], $Session->append_sid(ACP_INDEX));
		}
		return false;
	}

	//Encode js codes
	function encode_js($str){
		$text		= array('/%/','/#/','/&/','/`/','/ /');
		$replace	= array('%25','%23','%26','%60','%20');
		return preg_replace($text, $replace, $str);
	}

	//Decode js codes
	function decode_js($str){
		$text		= array('/%25/','/%23/','/%26/','/%60/','/%20/');
		$replace	= array('%','#','&','`',' ');
		return preg_replace($text, $replace, $str);
	}

	//Remove js codes
	function clean_js($str){
		$text		= array('/%25/','/%23/','/%26/','/%60/','/%20/');
		$replace	= '_';
		return preg_replace($text, $replace, $str);
	}

	//Convert file size
	function compile_size($size){
		if ($size >= 1073741824){
			$size = round($size/1073741824,1). ' Gb';
		}
		else if ($size >= 1048576){
			$size = round($size/1048576,1). ' Mb';
		}
		else if ($size >= 1024){
			$size = round($size/1024,1). ' Kb';
		}
		else{
			$size .= ' bytes';
		}
		return $size;
	}

	//Make specific date time
	//We use constant 20 for checking timezone true or false
	function make_mydate($month, $day, $year, $time, $nowtime, $timezone = 20){
		if ( checkdate($month, $day, $year) ){
			$tmp	= explode(':', $time);
			if ( is_array($tmp) ){
				$hour		= ( isset($tmp[0]) && ($tmp[0]>=0) && ($tmp[0]<=24) ) ? $tmp[0] : 0;
				$minute		= ( isset($tmp[1]) && ($tmp[1]>=0) && ($tmp[1]<=60) ) ? $tmp[1] : 0;
			}
			else{
				$hour		= 0;
				$minute		= 0;
			}
			if ( $timezone != 20 ){
				return gmmktime($hour, $minute, 0, $month, $day, $year) - $timezone*3600;
			}
			else{
				return mktime($hour, $minute, 0, $month, $day, $year);
			}
		}
		return $nowtime;
	}

   //add function 10_8_2010
   
   //Move directory
	function move_dir($old_dir, $new_dir){
		if ( !is_dir($old_dir) ) die("$old_dir is not a directory.");
		if ( !is_dir($new_dir) ){
			@mkdir($new_dir, 0777);
			@chmod($new_dir, 0777);
		}
		if ( !is_dir($new_dir) ) return false;

		$handle		= opendir($old_dir);
		while ( ($file = readdir($handle)) != false ) {
			if ( ($file != ".") && ($file != "..") ) {
				if ( is_dir($old_dir .'/'. $file ) ){
					$this->move_dir($old_dir .'/'. $file, $new_dir .'/'. $file);
				}
				else{
					@copy($old_dir .'/'. $file, $new_dir .'/'. $file);
					@unlink($old_dir .'/'. $file);
				}
			}
		}
		closedir($handle);

		rmdir($old_dir);
		return true;
	}

	//Resize image
	function resize_image($filename, $max_width = 0, $max_height = 0, $resize_type = "all"){
		if ( empty($filename) || (($resize_type == "width") && !$max_width) || (($resize_type == "height") && !$max_height) || (($resize_type == "all") && !$max_height && !$max_height) ){
			return false;
		}

		$image_type	= array(1 => 'gif', 2 => 'jpeg', 3 => 'png');
		//Get image size
		if (!$size	= @getimagesize($filename)){
			return false;
		}
		if ( !isset($image_type[$size[2]]) ){
			return false;
		}

		if ( $resize_type == 'all' ){
			if ( !$max_width ){
				$resize_type	= "height";
			}
			else if ( !$max_height ){
				$resize_type	= "width";
			}
			else{
				$resize_type = ( $size[0]/$size[1] > $max_width/$max_height ) ? 'width' : 'height';
			}
		}

		$flag	= 0;
		if ($resize_type == 'width'){
			if ($size[0] > $max_width){
				$new_width	= $max_width;
				$new_height	= floor(($max_width * $size[1]) / $size[0]);
				$flag		= 1;
			}
		}
		else if ($resize_type == 'height'){
			if ($size[1] > $max_height){
				$new_height	= $max_height;
				$new_width	= floor(($max_height * $size[0]) / $size[1]);
				$flag		= 1;
			}
		}
		if ( !$flag ) return false;

		$imgcreate	= "imagecreatefrom". $image_type[$size[2]];
		if ( $img_source = $imgcreate($filename) )
		{
			$img_dest	= imagecreatetruecolor($new_width, $new_height);
			imagecopyresized($img_dest, $img_source, 0, 0, 0, 0, $new_width, $new_height, $size[0], $size[1]);
			imagedestroy($img_source);
			@unlink($filename);
			
			$imgcreate	= "image". $image_type[$size[2]];
			if ( !function_exists($imgcreate) ){
				$imgcreate	= "image". $image_type[2];
			}
			$imgcreate($img_dest, $filename);
			imagedestroy($img_dest);
			return true;
		}
		return false;
	}

	//Create image thumbnail
	function create_thumbnail($filename, $thumbname, $thumb_width, $thumb_height){
		if ( empty($filename) || empty($thumbname) || !$thumb_width || !$thumb_height ){
			return false;
		}

		$image_type	= array(1 => 'gif', 2 => 'jpeg', 3 => 'png');
		//Get image size
		if (!$size	= @getimagesize($filename)){
			return false;
		}
		if ( !isset($image_type[$size[2]]) ){
			return false;
		}

		$imgcreate	= "imagecreatefrom". $image_type[$size[2]];
		if ( $img_source = $imgcreate($filename) ){
			$img_dest	= imagecreatetruecolor($thumb_width, $thumb_height);
			imagecopyresampled($img_dest, $img_source, 0, 0, 0, 0, $thumb_width, $thumb_height, $size[0], $size[1]);
			imagedestroy($img_source);
			
			$imgcreate	= "image". $image_type[$size[2]];
			if ( !function_exists($imgcreate) ){
				$imgcreate	= "image". $image_type[2];
			}
			$imgcreate($img_dest, $thumbname);
			imagedestroy($img_dest);
			return true;
		}

		return false;
	}

	//Copy file to new folder
	function copy_file($source_file, $dest_file){
		global $Template;

		@copy($source_file, $dest_file) or $Template->message_die("Could not copy file from <em>$source_file</em> to <em>$dest_file</em>");
	}
	//Transfer files from temporary folder to module's folder
	function transfer_temp_files(&$used_files, $dest_dir, &$data_info){
		global $Info;

		if ( empty($used_files) ){
			return false;
		}
		$user_id	= $Info->user_info['user_id'];

		//Transfer used files
//		$used_files	= $this->encode_js($used_files);
//		$used_files	= str_replace('\\\\', '/', $used_files);
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
//					$source_filename	= "u". $user_id ."_". $this->decode_js($filename);//Decode js codes
//					$dest_filename		= $this->clean_js($filename);//Clean all js codes
					$source_filename	= $filename;
					$dest_filename		= $filename;
					unset($filename);

					$used_files			.= !empty($used_files) ? ','. $dest_filename : $dest_filename;
					$source_info[]		= "#". preg_quote($filepath) ."#";
					$replace_info[]		= $dest_dir .'/'. $dest_filename;

					//copy files from temp folder to destination folder
					if ( file_exists("./Upload/". $source_filename) ){
						@copy("./Upload/". $source_filename, $dest_dir .'/'. $dest_filename) or die('Could not copy file. Please chmod 777 to '. $dest_dir);
						unlink("./Upload/". $source_filename);
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
		$dir	= "./Upload/";
		$time	= time() - 18000;//5 hours x 60 minutes x 60 seconds

		$handle	= opendir($dir);
		while ( ($file = readdir($handle)) != false ) {
			if ( ($file != ".") && ($file != "..") && is_file($dir ."/". $file) ) {
				if ( fileatime($dir ."/". $file) > $time ){
					@unlink($dir ."/". $file);
				}
			}
		}
		closedir($handle);
	}

	// Clean, replace full URL by short URL
	// $used_files: the old files
	// $dest_dir: destination directory
	// $data_info: contents for replacing new URLs
	// $stored_files: new files which will be updated to db
	function clean_used_files($used_files, $dest_dir, &$data_info, &$stored_files){
		global $DB, $Info;

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
//						$data_info[$key]	= preg_replace($url, $replace_url, $val);
						$data_info[$key]	= str_replace($url, $replace_url, $val);
					}
				}
				else{
					//remove files from server
					@unlink($dest_dir .'/'. $filename);
				}
			}
		}
	}

	//Delete files which are not used
	function delete_files($new_files, $old_files, $dest_dir){
		$n_files	= explode(',', $new_files);
		$o_files	= explode(',', $old_files);

		reset($o_files);
		while (list(, $filename) = each($o_files)){
			$filename	= trim($filename);
			if ( !empty($filename) && !in_array($filename, $n_files) ){
				@unlink($dest_dir .'/'. $filename);
			}
		}
	}

	//Delete folder
	function delete_dir($dir){
		if ( !is_dir($dir) ){
			return false;
		}

		//Read dir
		$dh  = opendir($dir);
		while (false !== ($filename = readdir($dh))) {
			if ( ($filename != '.') && ($filename != '..') ){
				@unlink($dir .'/'. $filename);
			}
		}
		closedir($dh);
		@rmdir($dir);
		return true;
	}
	//Check allowed file types
	function check_filetype($filetype, $config_type = "image"){
		global $Info;

		$type = $Info->option[$config_type ."_type"];

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
   //the end add function 10_8_2010

	function get_all_perms($func_name, $pause = 1){
		global $Info, $DB, $Template, $Lang;

		$perm_info	= array();

		//Check if user is in admin group
		$DB->query('SELECT U.group_id FROM '. $DB->prefix .'user_group AS G, '. $DB->prefix .'user_group_ids AS U WHERE U.group_id=G.group_id AND U.user_id='. $Info->user_info['user_id'] .' AND G.group_level=1');
		if ( $DB->num_rows() ){
			$perm_info['action']['all']		= 1;
			$perm_info['item']['all']		= 1;
			return $perm_info;
		}
		//-------------------------------

		$DB->query('SELECT * FROM '. $DB->prefix .'func WHERE func_name="'. $func_name .'"');
		if ( !$DB->num_rows() ){
			if ( $pause ){
				$Template->message_die($Lang->data['perm_error_not_all']);
			}
			return false;
		}
		$func_info	= $DB->fetch_array();

		//This function allow all actions and all items
		if ( $func_info['func_allow_all'] ){
			$perm_info['action']['all']		= 1;
			$perm_info['item']['all']		= 1;
			return $perm_info;
		}
		//---------------------------------------------

		//Get user groups and actions, items ---------
		$group_info		= explode(',', $Info->user_info['user_groups']);
		$where_sql		= ' WHERE func_code="'. $func_info['func_code'] .'" AND (ugroup_id=0';
		reset($group_info);
		while (list(, $gid) = each($group_info)){
			$where_sql	.= " OR ugroup_id=". intval($gid);
		}
		$where_sql		.= ') AND allow_actions!=""';

		$DB->query('SELECT allow_actions, allow_items FROM '. $DB->prefix .'func_auth '. $where_sql);
		if ( !$DB->num_rows() ){
			if ( $pause ){
				$Template->message_die($Lang->data['perm_error_not_all']);
			}
			return false;
		}

		$allow_actions	= "";
		$allow_items	= "";
		while ($result = $DB->fetch_array()){
			if ( !empty($result['allow_actions']) ){
				$allow_actions	.= !empty($allow_actions) ? ','. $result['allow_actions'] : $result['allow_actions'];
			}
			if ( !empty($result['allow_items']) ){
				$allow_items	.= !empty($allow_items) ? ','. $result['allow_items'] : $result['allow_items'];
			}
		}
		$action_info			= explode(',', $allow_actions);
		$item_info				= explode(',', $allow_items);
		$perm_info['action']	= array_flip(array_unique($action_info));
		$perm_info['item']		= array_flip(array_unique($item_info));
		//------------------------------------------------------------

		return $perm_info;
	}

	//Check user's permissions
	function check_user_perm($perm_info, $act, $pause = 1){
		global $Lang, $Template;

		if ( isset($perm_info['action']['all']) || isset($perm_info['action'][$act]) ){
			return true;
		}

		if ( $pause ){
			$Template->message_die($Lang->data['perm_error_not_'. $act]);
		}
		return false;
	}

	//Log user's actions
	function save_log($func_name, $func_act, $record_ids = '', $url_view = ''){
		global $Session, $DB, $Info;

		if ( !$Info->option['log_save'] ) return false;

		$DB->query("INSERT INTO ". $DB->prefix ."site_log(log_time, user_id, user_ip, func_name, func_action, func_url_view, record_ids) VALUES(". CURRENT_TIME .", ". $Info->user_info['user_id'] .", '". $Session->ip ."', '". $func_name ."', '". $func_act ."', '". $url_view ."', '". $record_ids ."')");
		return true;
	}

	//Delete logged actions
	function del_logs(){
		global $DB, $Info;

		if ( !$Info->option['log_save'] || !$Info->option['log_days'] ) return false;

		$time	= CURRENT_TIME - $Info->option['log_days'] * 86400;
		$DB->query('DELETE FROM '. $DB->prefix .'site_log WHERE log_time<'. $time);
		return true;
	}

	//Update schedules
	function set_update_schedule(){
		global $DB, $Info;

		$DB->query('DELETE FROM '. $DB->prefix .'update_schedule');

		$DB->query('SELECT posted_date FROM '. $DB->prefix .'article WHERE posted_date>'. CURRENT_TIME .' AND enabled='. SYS_ENABLED .' GROUP BY posted_date ORDER BY posted_date ASC');
		$time_count	= $DB->num_rows();
		$time_data	= $DB->fetch_all_array();
		$DB->free_result();

		//Update cache schedule for articles
		for ($i=0; $i<$time_count; $i++){
			$DB->query('INSERT INTO '. $DB->prefix .'update_schedule(update_time) VALUES('. $time_data[$i]['posted_date'] .')');
		}
	}

	//Get page codes and names
	function get_webpage_list($blockname = "webpagerow"){
		global $Template, $DB, $cfg_webpage_list, $Lang;

		reset($cfg_webpage_list);
		while ( list($page_code, $page_name) = each($cfg_webpage_list) ){
			$Template->set_block_vars($blockname, array(
				'CODE'		=> $page_code,
				'NAME'		=> isset($Lang->data[$page_name]) ? $Lang->data[$page_name] : $page_name,
			));
		}
	}

	//Check and get keys from an array
	function get_array_key($data, $type = 'number'){
		$result_info	= array();
		if ( is_array($data) ){
			reset($data);
			while (list($key,) = each($data)){
				$key	= ($type == 'number') ? intval($key) : htmlspecialchars(trim($key));
				if ( $key ){
					$result_info[]	= $key;
				}
			}
		}
		return $result_info;
	}

	//Check and get values from an array
	function get_array_value($data, $type = 'number'){
		$result_info	= array();
		if ( is_array($data) ){
			reset($data);
			while (list(, $val) = each($data)){
				$val	= ($type == 'number') ? intval(trim($val)) : htmlspecialchars(trim($val));
				if ( $val ){
					$result_info[]	= $val;
				}
			}
		}
		return $result_info;
	}

	//Get POST/GET/FILES values
	function get_request($var_name, $default_value = '', $gpc = ''){
		if ( (empty($gpc) || ($gpc == 'POST')) && isset($_POST[$var_name]) ){
			return $_POST[$var_name];
		}
		if ( (empty($gpc) || ($gpc == 'GET')) && isset($_GET[$var_name]) ){
			return $_GET[$var_name];
		}
		if ( (empty($gpc) || ($gpc == 'FILES')) && isset($_FILES[$var_name]['name']) ){
			return $_FILES[$var_name]['name'];
		}
		return $default_value;
	}

	//Create random letters/numbers
	function create_random($limit){
		$chars		= array('a', 'A', 'b', 'B', 'c', 'C', 'd', 'D', 'e', 'E', 'f', 'F', 'g', 'G', 'h', 'H', 'i', 'I', 'j', 'J',  'k', 'K', 'l', 'L', 'm', 'M', 'n', 'N', 'o', 'O', 'p', 'P', 'q', 'Q', 'r', 'R', 's', 'S', 't', 'T',  'u', 'U', 'v', 'V', 'w', 'W', 'x', 'X', 'y', 'Y', 'z', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9', '0');
		$max_chars	= sizeof($chars) - 1;
		$str_rand	= "";
		for ($i=0; $i<$limit; $i++){
		    $str_rand	.= $chars[rand(0, $max_chars)];
		}
		return $str_rand;
	}
}
?>