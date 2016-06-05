<?php
/* =============================================================== *\
|		Module name: Cache Engine									|
|		Module version:	1.2											|
|		Begin: 21 January 2004										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('CACHE_PHP_CONTENT', '<?php die("Hacking attemp!"); ?> ');

class Cache
{
	var $turn_on				= 1;
	var	$option					= array();
	var	$mod_cache				= array();

	var $root					= "cache/";
	var $dir_php				= "php/";
	var $dir_html				= "html/";

	var	$cache_name				= "";
	var	$cache_content			= "";

	var $expire_time			= 86400; //86400 seconds <=> 1 days | 0: no expire
	var $max_size				= 10485760; //(bytes) | 10Mb*1024Kb*1024B

	var $var_count	= 0;
	var $var_info	= array();

	//For date translation
	var $date_source			= array();
	var $date_source_tmp		= array();
	var $date_replace			= array();

	function Cache($root = ""){
		if ( !empty($root) ){
			$this->root		= $root;
		}
		if ( !is_dir($this->root) ){
			$this->halt("Cache Dir <b>". $this->root ."</b> does not exist!");
			return false;
		}
		if ( !is_writable($this->root . $this->dir_php) ){
			$this->halt("Cache Dir <b>". $this->root . $this->dir_php ."</b> does not have permission to write!");
			return false;
		}
		if ( !is_writable($this->root . $this->dir_html) ){
			$this->halt("Cache Dir <b>". $this->root . $this->dir_html ."</b> does not have permission to write!");
			return false;
		}

		if (substr($this->root,-1) != "/"){
			$this->root		.= "/";
		}
		return true;
	}

	function get_config($cache_name = "option"){
		if ( !$this->turn_on ) return false;

		$str	= $this->get_cache($cache_name, "php");
		if ( $str ){
			$this->option	= unserialize($str);

			//Check website close
			if ( $this->option['website_close'] ) return false;

			//Template
			$template_id	= isset($_COOKIE['global_template_id']) ? $_COOKIE['global_template_id'] : '';
			if ( !empty($template_id) ){
				$template_id	= preg_replace('/[^\w-_]/', '', $template_id);
				if ( is_dir('./templates/'. $template_id) ){
					$this->option['template']	= $template_id;
				}
			}

			//Config
			$this->turn_on		= $this->option['cache_enabled'];
			$this->expire_time	= $this->option['cache_expire'];
			$this->max_size		= $this->option['cache_maxsize'];
			return true;
		}
		return false;
	}

	function cache_onoff($status){
		$this->turn_on	= $status ? 1 : 0;
	}

	function set_expiretime($expire_time){
		$this->expire_time	= $expire_time;
	}

	function set_maxsize($max_size){
		$this->max_size	= $max_size;
	}

	function set_cache_name($cache_name){
		$this->cache_name		= $cache_name;
	}

	function set_cache_content($content){
		$this->cache_content	.= $content;
	}

	function reset_cache_content(){
		$this->cache_content	= "";
	}

	//Begin cache - Check whether there is cached content
	function begin_cache(){
		$this->cache_name	= "";

		//Get config options
		if ( $this->get_config() && !$this->check_update_schedule('schedule') ){
			$this->cache_name	= $this->get_page_id();
			if ( empty($this->cache_name) ){
				//Turn off
				$this->cache_onoff(0);
			}
			else{
				//Get cache
				if ( ($cached_content = $this->get_cache($this->cache_name)) != false ){
					$this->refresh_cached_data($cached_content);
					echo $cached_content;
					die();
				}
			}
		}
	}

	//Refresh some data in cached content
	function refresh_cached_data(&$cached_content){
		if ($this->option['timezone'] > 0){
			$gmt	= ' (GMT+'. $this->option['timezone'] .')';
		}
		else if ($this->option['timezone'] < 0){
			$gmt	= ' (GMT'. $this->option['timezone'] .')';
		}
		else{
			$gmt	= ' (GMT)';
		}

		//Replace date time
		$datetime			= $this->translate_date(gmdate($this->option['full_date_time_format'], CURRENT_TIME + $this->option['timezone']*3600)) . $gmt;
		$cached_content		= preg_replace('#(<!-- TodayDateBegin -->)(.*?)(<!-- TodayDateEnd -->)#si', '<!-- TodayDateBegin -->'. $datetime .'<!-- TodayDateEnd -->', $cached_content);
	}

	//End cache
	function end_cache(){
		global $DB, $Info, $Lang, $Func, $Template;

		//Options
		$Info->option['general_date_day']			= $Lang->data['general_date_day'];
		$Info->option['general_date_day_short']		= $Lang->data['general_date_day_short'];
		$Info->option['general_date_month']			= $Lang->data['general_date_month'];
		$Info->option['general_date_month_short']	= $Lang->data['general_date_month_short'];
		$this->set_cache_content(serialize($Info->option));
		$this->set_cache('option', 'php');

		//Update Schedule
		$this->reset_cache_content();
		$this->set_cache_content(serialize($Func->get_update_schedule()));
		$this->set_cache('schedule', 'php');

		//Close mysql
		$DB->close();

		if ( empty($cache_name) ){
			$this->option	= $Info->option;
			$cache_name		= $this->get_page_id();
			if ( empty($cache_name) ){
				$cache_name	= MOD_HOME;
			}
		}

		//Set html caching
		$this->reset_cache_content();
		$this->set_cache_content($Template->show("header", 1));
		$this->set_cache_content($Template->show("main", 1));
		$this->set_cache_content($Template->show("footer", 1));
		$this->set_cache($cache_name);
		echo $this->get_cache($cache_name);
	}

	//Set new cached content
	function set_cache($cache_name	= "", $cache_type = "html"){
		if ( !$this->turn_on ) return false;

		//Clear old cache
		$this->clear_cache();

		if ( !empty($cache_name) ){
			$this->cache_name		= $cache_name;
		}

		if ( empty($cache_name) ){
			$cache_name		= $this->get_cache_name();
		}
		if ( $cache_type == "php" ){
			$this->cache_content	= CACHE_PHP_CONTENT. $this->cache_content;
			$cache_file		= $this->root . $this->dir_php . $cache_name . '.php';
		}
		else{
			$cache_file		= $this->root . $this->dir_html . $cache_name . '.cache';
		}

		if ( !file_exists($cache_file) ){
			$f	= fopen($cache_file, "w") or $this->halt("Couldn't open file $cache_file to write.");
			if ( flock($f, LOCK_EX) ){
				fwrite($f, $this->cache_content);
				flock($f, LOCK_UN);
			}
			else{
				$this->halt("Couldn't lock the file: $cache_name");
			}
			fclose($f);
		}
		return true;
	}

	//Get cached content
	function get_cache($cache_name = "", $cache_type = "html"){
		if ( !$this->turn_on ) return false;

		if ( empty($cache_name) ){
			$cache_name		= $this->get_cache_name();
		}

		if ( $cache_type == 'php' ){
			$cache_file		= $this->root . $this->dir_php . $cache_name . '.php';
		}
		else{
			$cache_file		= $this->root . $this->dir_html . $cache_name . '.cache';
		}

		if ( file_exists($cache_file) ){
			$expire_time	= CURRENT_TIME - $this->expire_time;
			$mtime			= filemtime($cache_file);
			if ( $this->expire_time && ($mtime < $expire_time) ){
				//Delete expired cached files
				@unlink($cache_file);
				return false;
			}
			else{
				//Check to update if cache in old month
				$tmonth	= date('m');
				$fmonth	= date('m', $mtime);
				if ( $tmonth != $fmonth ){
					@unlink($cache_file);
					return false;
				}
			}
			$str	= implode('', @file($cache_file));
			if ( $cache_type == "html" ){ //HTML cache
//				echo trim($str);
//				return true;
				return trim($str);
			}
			else{ //PHP cache
				$str	= str_replace(CACHE_PHP_CONTENT, "", $str);
				return trim($str);
			}
		}
		return false;
	}

	function get_cache_name(){
		$cache_name		= isset($_SERVER['REQUEST_URI']) ? addslashes(trim($_SERVER['REQUEST_URI'])) : ' ';
		return md5($cache_name);
	}

	//Clear cache data
	function clear_cache($prefix_name = "", $cache_type = "html"){
		if ( !$this->turn_on ) return false;

		$total_size		= 0;
		$file_info		= array();
		$expire_time	= CURRENT_TIME - $this->expire_time;

		if ( !empty($prefix_name) ){
			$prefix_length	= strlen($prefix_name);
		}

		$cache_dir		= ($cache_type == 'php') ? $this->dir_php : $this->dir_html;
		$handle			= opendir($this->root . $cache_dir);
		while ( ($file = readdir($handle)) != false ) {
			if ( ($file != ".") && ($file != "..") && ($file != "index.html") ) {
				$fl		= $this->root . $cache_dir . $file;
				if ( !empty($prefix_name) ){
					if ( ($prefix_name == "all") || (substr($file, 0, $prefix_length) == $prefix_name) ){
					   @unlink($fl);
					   continue;
					}
				}

				$mtime	= @filemtime($fl);
				if ( $this->expire_time && ($mtime < $expire_time) ){
					//Delete expired cached files
					@unlink($fl);
				}
				else{
					$file_info[]	= $fl;
					$total_size		+= filesize($fl);
				}
			}
		}
		closedir($handle);

		//Clear all cached files If size of cachedir reaches maximum size => Anti flood disk space!
		if ($total_size > $this->max_size){
			reset($file_info);
			while ( list(, $filename) = each($file_info) ){
				@unlink($filename);
			}
		}
		return true;
	}

	function get_all_vars(){
		$_SERVER['REQUEST_URI']	= addslashes(trim($_SERVER['REQUEST_URI']));

		if (substr($_SERVER['REQUEST_URI'], -1) == '/'){
			$uri	= substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - 1);
		}
		else{
			$uri	= $_SERVER['REQUEST_URI'];
		}

		if ( !empty($this->option['site_path']) ){
			$pos	= strpos($uri, $this->option['site_path']) + strlen($this->option['site_path']);
			$uri	= substr($uri, $pos);
		}

		if ( !empty($uri) ){
			$this->var_info		= @explode($this->option['short_url_sep'], $uri);
			$this->var_count	= sizeof($this->var_info);
		}
	}

	function get_vars($var_name, $var_pos = 0){
		$var_val	= "";
		if ( $this->option['short_url_enabled'] && isset($this->var_info[$var_pos]) ){
			$var_val		= trim($this->var_info[$var_pos]);
		}
		else{
			$var_val		= isset($_GET[$var_name]) ? $_GET[$var_name] : '';
		}

		return $var_val;
	}

	function get_page_id(){
		$this->get_all_vars();
		$cache_name		= $this->get_vars('mod', 0);
		if ( empty($cache_name) || ($cache_name == 'index.php') ){
			$cache_name	= MOD_HOME;
		}

		if ( !in_array($cache_name, $this->mod_cache) ){
			return "";
		}

		if ( $cache_name == MOD_ARTICLE ){
			//Get cache name
			if ( !$this->option['short_url_enabled'] ){
				$cache_name	.= '_'. htmlspecialchars($this->get_vars("cat"));
				$article_id	= intval($this->get_vars("article"));
				$cat_page	= str_replace('page_', '', $this->get_vars("page"));
				$cat_page	= intval($cat_page);
				$page_order	= intval($this->get_vars("page_order"));
				if ( $article_id ){
					//print mod
					$act	= htmlspecialchars($this->get_vars("act"));
					if ( !empty($act) ){
						return "";
					}
					$cache_name	.= '_'. $article_id;
				}
				if ( $cat_page > 1 ){
					$cache_name	.= '_page'. $cat_page;
				}
				if ( $page_order > 1 ){
					$cache_name	.= '_porder'. $page_order;
				}
			}
			else{
				$article_id		= 0;
				$cat_code		= "";
				$cat_page		= "";
				for ($i=1; $i<$this->var_count; $i++){
					if ( !empty($this->var_info[$i]) ){
						if ( is_numeric($this->var_info[$i]) ){
							$article_id	= $this->var_info[$i];
							$page_order	= isset($this->var_info[$i+1]) ? intval($this->var_info[$i+1]) : 0;
							
							//print mod
							if ( ($i < $this->var_count - 2) && !empty($this->var_info[$i + 2]) ){
								return "";
							}
							break;
						}
						
						if ( substr($this->var_info[$i], 0, 5) == "page_" ){
							$cat_page	= str_replace('page_', '', $this->var_info[$i]);
							$cat_page	= intval($cat_page);
						}
						else{
							$cat_code	= htmlspecialchars($this->var_info[$i]);
						}
					}
				}
				$cache_name		.= '_'. $cat_code;
				if ( $cat_page > 1 ){
					$cache_name	.= '_page'. $cat_page;
				}
				if ( $article_id ){
					$cache_name	.= '_'. $article_id;
				}
				if ( isset($page_order) && ($page_order > 1) ){
					$cache_name	.= '_porder'. $page_order;
				}
			}
		}

		//template
		$cache_name		.= '_'. $this->option['template'];

		$cache_name		= str_replace('/', '', $cache_name);
		$cache_name		= str_replace('\\', '', $cache_name);
		return $cache_name;
	}

	function check_update_schedule($cache_name  = "schedule"){
		if ( !$this->turn_on ) return false;

		$str	= $this->get_cache($cache_name, "php");
		if ( $str ){
			$time_info	= unserialize($str);
			if ( isset($time_info[0]) && ($time_info[0] <= CURRENT_TIME) ){
				return true;
			}
		}
		return false;
	}

	function compile_date(){
		$this->date_source	= array(
			'd1'		=> "Sunday",
			'd2'		=> "Monday",
			'd3'		=> "Tuesday",
			'd4'		=> "Wednesday",
			'd5'		=> "Thursday",
			'd6'		=> "Friday",
			'd7'		=> "Saturday",

			'm1'		=> "January",
			'm2'		=> "February",
			'm3'		=> "arch",
			'm4'		=> "April",
			'm5'		=> "May",
			'm6'		=> "June",
			'm7'		=> "July",
			'm8'		=> "August",
			'm9'		=> "September",
			'm10'		=> "October",
			'm11'		=> "November",
			'm12'		=> "December",

			'ds1'		=> "Sun",
			'ds2'		=> "Mon",
			'ds3'		=> "Tue",
			'ds4'		=> "Wed",
			'ds5'		=> "Thu",
			'ds6'		=> "Fri",
			'ds7'		=> "Sat",

			'ms1'		=> "Jan",
			'ms2'		=> "Feb",
			'ms3'		=> "Mar",
			'ms4'		=> "Apr",
			'ms5'		=> "May",
			'ms6'		=> "Jun",
			'ms7'		=> "Jul",
			'ms8'		=> "Aug",
			'ms9'		=> "Sep",
			'ms10'		=> "Oct",
			'ms11'		=> "Nov",
			'ms12'		=> "Dec"
		);

		// Built date soure temp ------------
		reset($this->date_source);
		while (list($key, ) = each($this->date_source)){
			$this->date_source_tmp[$key]	= '.::'. $key .'::.';
		}
		//-----------------------------------

		//Get date from language file -------
		$date_str	= $this->option['general_date_day'];
		$date_info	= explode(',', $date_str);
		$count = 1;
		reset($date_info);
		while (list(, $date) = each($date_info)){
			$this->date_replace['d'. $count]	= trim($date);
			$count++;
		}

		$date_str	= $this->option['general_date_month'];
		$date_info	= explode(',', $date_str);
		$count = 1;
		reset($date_info);
		while (list(, $month) = each($date_info)){
			$this->date_replace['m'. $count]	= trim($month);
			$count++;
		}
		//-----------------------------------

		//Get date from language file -------
		$date_str	= $this->option['general_date_day_short'];
		$date_info	= explode(',', $date_str);
		$count = 1;
		reset($date_info);
		while (list(, $date) = each($date_info)){
			$this->date_replace['ds'. $count]	= trim($date);
			$count++;
		}

		$date_str	= $this->option['general_date_month_short'];
		$date_info	= explode(',', $date_str);
		$count = 1;
		reset($date_info);
		while (list(, $month) = each($date_info)){
			$this->date_replace['ms'. $count]	= trim($month);
			$count++;
		}
		//-----------------------------------
	}

	function translate_date($date_str){
		if ( !sizeof($this->date_replace) ){
			$this->compile_date();
		}
		$date_str	= str_replace($this->date_source, $this->date_source_tmp, $date_str);
		$date_str	= str_replace($this->date_source_tmp, $this->date_replace, $date_str);
		//Note: We can't replace directly as str_replace($this->date_source, $this->date_replace, $date_str)
		return $date_str;
	}

	function halt($msg){
		echo "<b>Cache Error:</b>\n<br>$msg";
		die();
	}
}
?>