<?php
/* =============================================================== *\
|		Module name:      Functions									|
|																	|
\* =============================================================== */
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

class Func_Home extends Func_Global
{
	var $var_count		= 0;
	var $var_info		= array();
	var $cache_info		= array();

	function Func_Home(){
		$this->check_gpc();
		$this->get_all_vars();
	}

	function compile_url($url_vars = "", $url_prefix = ""){
		global $Info;

		if ( empty($url_vars) ){
			return HOME_INDEX;
		}

		$the_url	= !empty($url_prefix) ? $url_prefix : (!$Info->option['short_url_enabled'] ? HOME_INDEX : "");
		if ( is_array($url_vars) ){
			if ( !$Info->option['short_url_enabled'] ){
				if ( strpos($the_url, "?") === false ){
					$the_url	.= '?';
				}
				$flag		 = 0;
			}
			reset($url_vars);
			while ( list($var, $val) = each($url_vars) ){
				if ( $Info->option['short_url_enabled'] ){
					$the_url	.= $val . $Info->option['short_url_sep'];
				}
				else{
					$the_url	.= $flag ? '&'. $var .'='. $val : $var .'='. $val;
					$flag++;
				}
			}
		}

		//return $the_url;
		if ( $Info->option['short_url_enabled'] && empty($url_prefix)){
		//return substr($the_url,0,strlen($the_url)-1).'.html';
		return substr($the_url,0,strlen($the_url)-1);
		}else{
		return $the_url;
		}
	}

	function get_all_vars(){
		global $Info;

		$_SERVER['REQUEST_URI']	= isset($_SERVER['REQUEST_URI']) ? addslashes(trim($_SERVER['REQUEST_URI'])) : '';

		if (substr($_SERVER['REQUEST_URI'], -1) == '/'){
			$uri	= substr($_SERVER['REQUEST_URI'], 0, strlen($_SERVER['REQUEST_URI']) - 1);
		}
		/*else{
			$uri	= $_SERVER['REQUEST_URI'];
		}*/

        else{
		$uri = $_SERVER['REQUEST_URI'];
		}
		if(strpos($uri,'.html')>0)$uri = substr($uri,0,strpos($uri,'.html'));


		if ( !empty($Info->option['site_path']) ){
			$pos	= strpos($uri, $Info->option['site_path']) + strlen($Info->option['site_path']);
			$uri	= substr($uri, $pos);
		}

		if ( !empty($uri) ){
			$this->var_info		= @explode($Info->option['short_url_sep'], $uri);
			$this->var_count	= sizeof($this->var_info);
		}
	}

	function get_vars($var_name, $var_pos){
		global $Info;

		$var_val	= "";
		if ( $Info->option['short_url_enabled'] && isset($this->var_info[$var_pos]) ){
			$var_val		= $this->var_info[$var_pos];
		}
		else{
			$var_val		= isset($_GET[$var_name]) ? $_GET[$var_name] : '';
			if ( empty($var_val) ){
				$var_val	= isset($_POST[$var_name]) ? $_POST[$var_name] : '';
			}
		}

		return $var_val;
	}

	function get_article_dir($time, $id){
		global $Info;

		//Get month and year
		$date	= getdate($time);
		if ($date['mon'] < 10){
			$date['mon']	= '0'. $date['mon'];
		}
		return $Info->imgpath_article . $date['year'] ."_". $date['mon'] .'/'. $id;
	}
    
	function get_gallery_dir($time, $id){
		//Get month and year
		$date	= getdate($time);
		if ($date['mon'] < 10){
			$date['mon']	= '0'. $date['mon'];
		}
		return 'images/gallery/' . $date['year'] ."_". $date['mon'] .'/'. $id;
	}
	
	function get_update_schedule(){
		global $DB;

		$time_info	= array();
		$DB->query('SELECT * FROM '. $DB->prefix .'update_schedule WHERE update_time>'. CURRENT_TIME .' ORDER BY update_time ASC');
		if ( $DB->num_rows() ){
			while ($result = $DB->fetch_array()){
				$time_info[]	= $result['update_time'];
			}
		}
		$DB->free_result();

		return $time_info;
	}

	//for example: virtual_pagination(100, 30, 2,"Page", "article/5");
	function virtual_pagination($total_count, $numperpage, $current_page, $langpage, $url, $page_prefix = "", $pageside = 3){
		global $Lang, $Func, $Info;

		$pageshow['page']	= "";
		$pageshow['start']	= 0;
		$total_page			= ceil($total_count/$numperpage);

		if ($total_page > 1){
			if ( empty($Info->option['short_url_enabled']) ){
				$url .= (strpos($url, "?")) ? '&' : '?';
			}
			$current_page = ($current_page > $total_page) ? $total_page : (($current_page > 0) ? $current_page : 1);

			if ($total_page <= 2*$pageside + 3){
				$page_start  = 1;
				$page_end    = $total_page;
			}
			else{
				$page_start = ($current_page < $total_page - $pageside) ? $current_page - $pageside : $total_page - 2*$pageside;
				$page_end   = ($current_page > $pageside) ? $current_page + $pageside : 2*$pageside + 1;
				if ($page_start < 1){
					$page_start = 1;
				}
				if ($page_end > $total_page){
					$page_end = $total_page;
				}
			}

			for ($i=$page_start;$i<=$page_end;$i++){
				$pageshow['page'] .= ($i == $current_page) ? " <span class=pageBoxB>$i</span>" : " <span class=pageBox><a class=pageNav href='". $Func->compile_url(array('page' => $page_prefix . $i), $url) ."'>$i</a></span>";
			}

			if ($total_page > 2*$pageside + 3){
				if ($page_start > 1){
					$pageshow['page'] = '<span class=pageBox><a class=pageNav href="'. $Func->compile_url(array('page' => $page_prefix . 1), $url) .' title="'. $Lang->data['page_title_first'] .'"><strong>::</strong></a></span> ... '. $pageshow['page'];
				}
				if ($page_end < $total_page){
					$pageshow['page'] .= ' ... <span class=pageBox><a class=pageNav href="' . $Func->compile_url(array('page' => $page_prefix . $total_page), $url) .'" title="'. $Lang->data['page_title_last'] .'"><strong>::</strong></a></span>';
				}
			}

			if ( $current_page>1 ){
				$pageshow['page'] = "<span class=pageBox><a class=pageNav href='". $Func->compile_url(array('page' => $page_prefix . ($current_page - 1)), $url) ."' title='". $Lang->data['page_title_previous'] ."'><strong>&laquo;</strong></a></span> ". $pageshow['page'];
			}
			if ( $current_page<$total_page ){
				$pageshow['page'] .= " <span class=pageBox><a class=pageNav href='". $Func->compile_url(array('page' => $page_prefix . ($current_page + 1)), $url) ."' title='". $Lang->data['page_title_next'] ."'><strong>&raquo;</strong></a></span>";
			}

			$pageshow['page'] = $langpage ." ". $current_page ."/". $total_page .": ". $pageshow['page'];
			$pageshow['start'] = ($current_page-1)*$numperpage;
		}
		return $pageshow;
	}

	function make_article_link($str, $type, $url, $css = ""){
		switch ($type){
			case SYS_ARTICLE_SUMMARY:
				if ( !empty($css) ){
					return '<font class="'. $css .'">'. $str .'</font>';
				}
				return $str;
			case SYS_ARTICLE_LINK:
				if ( !empty($css) ){
					return '<a href="'. $url .'" class="'. $css .'" target="_blank">'. $str .'</a>';
				}
				return '<a href="'. $url .'" target="_blank">'. $str .'</a>';
			case SYS_ARTICLE_FULL:
			default:
				if ( !empty($css) ){
					return '<a href="'. $url .'" class="'. $css .'">'. $str .'</a>';
				}
				return '<a href="'. $url .'">'. $str .'</a>';
		}
	}

	function get_newspic_dir($time, $id){
		//Get month and year
		$date	= getdate($time);
		if ($date['mon'] < 10){
			$date['mon']	= '0'. $date['mon'];
		}
		return 'images/pictures/' . $date['year'] ."_". $date['mon'] .'/'. $id;
	}

	function get_disabled_cats($table_name){
		global $DB;

		$DB->query("SELECT cat_id, cat_parent_id, enabled FROM ". $DB->prefix . $table_name ." ORDER BY cat_order ASC");
		$cat_count	= $DB->num_rows();
		$cat_data	= $DB->fetch_all_array();
		$DB->free_result();

		$disabled_cats	= array();
		$this->get_disabled_subcats($disabled_cats, $cat_count, $cat_data);
		return $disabled_cats;
	}

	function get_disabled_subcats(&$disabled_cats, $cat_count, $cat_data, $parent_id = 0, $parent_enabled = 1){
		for ($i=0; $i<$cat_count; $i++){
			if ( $cat_data[$i]['cat_parent_id'] == $parent_id ){
				if ( !$parent_enabled || !$cat_data[$i]['enabled'] ){
					$disabled_cats[]	= $cat_data[$i]['cat_id'];
					$cat_enabled	= 0;
				}
				else{
					$cat_enabled	= 1;
				}
				$this->get_disabled_subcats($disabled_cats, $cat_count, $cat_data, $cat_data[$i]['cat_id'], $cat_enabled);
			}
		}
	}

	function make_image_number(){
		global $Info, $DB, $Template, $Func, $Lang;

		$expired_time	= CURRENT_TIME	- 300; // 5 minutes

		//Delete old image number of this client
//		$DB->query("DELETE FROM ". $DB->prefix ."number WHERE client_ip='". $Info->client_ip ."' OR num_time<$expired_time");
		$DB->query("DELETE FROM ". $DB->prefix ."number WHERE num_time<$expired_time");
		//--------------------------------------

		$num_id			= md5(uniqid(CURRENT_TIME . rand(1000, 9999)));
		$number			= rand(1,9);
		for ($i=2; $i<=4; $i++){
			$number			.= rand(0,9);
		}

		//Insert into db
		$DB->query("INSERT INTO ". $DB->prefix ."number(num_id, num_value, num_time, client_ip) VALUES('". $num_id ."', '". $number ."', ". CURRENT_TIME .", '". $Info->client_ip ."')");

		$Template->set_vars(array(
			"IMAGE_NUMBER"			=> '<img src="'. $Func->compile_url(array('mod' => MOD_SECURITY, 'act' => 'image', 'num_id' => $num_id)) .'" align="absbottom">',
			'NUMBER_ID'				=> $num_id,
			'L_NUMBER'				=> $Lang->data['general_number'],
		));
	}

	function get_request($var_name, $default_value = '', $gpc = ''){
		if ( (empty($gpc) || ($gpc == 'POST')) && isset($_POST[$var_name]) ){
			return $_POST[$var_name];
		}
		if ( (empty($gpc) || ($gpc == 'GET')) && isset($_GET[$var_name]) ){
			return $_GET[$var_name];
		}
		return $default_value;
	}

	function sub_string($str,$num_word)
	{
		$len=strlen($str);
		$str1=explode(" ", $str);
		if(count($str1)<=$num_word)return $str;
		$str_tmp="";
		for($i=0;$i<$num_word;$i++)
		{
			
			$str_tmp.=$str1[$i]." ";
		}
		
		return 	$str_tmp." ...";
	}


	function highlight_text($content, $keyword, $match = 'words'){
		$keyword	= trim($keyword);
		if ( empty($keyword) ){
			return $content;
		}

		//Search for words
		if ( $match == 'words' ){
			//Use cache
			if ( isset($this->cache_info[$keyword]['search']) && isset($this->cache_info[$keyword]['replace']) ){
				return preg_replace('#'. $this->cache_info[$keyword]['search'] .'#si', $this->cache_info[$keyword]['replace'], $content);
			}
			else{
				$str_search		= '';
				$str_replace	= '';
				$i	= 0;
				$keyword_info	= explode(' ', $keyword);
				reset($keyword_info);
				while (list(, $word) = each($keyword_info)){
					if ( !empty($word) ){
						if ( $i > 0 ){
							$str_search		.= '([^<>]*)';
							$str_replace	.= '\\'. $i .' ';
						}
						$str_search		.= $word;
						$str_replace	.= '<font class="highlight">'. $word .'</font>';
						$i++;
					}
				}

				//Store to cache
				$this->cache_info[$keyword]['search']	= $str_search;
				$this->cache_info[$keyword]['replace']	= $str_replace;

				return preg_replace('#'. $str_search .'#si', $str_replace, $content);
			}
		}

		//Search for phrase
		return preg_replace('#'. $keyword .'#si', '<font class="highlight">'. $keyword .'</font>', $content);
	}
}
?>