<?php
/* =============================================================== *\
|		Module name:      Picture News								|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_article_picture');
define('FUNC_ACT_VIEW', 'view');
//Module language
$Func->import_module_language("admin/lang_picture". PHP_EX);

$ANewsPic = new Admin_NewsPic;

class Admin_NewsPic
{
	var $data			= array();
	var $filter			= array();
	var $page			= 1;

	var $cat_count		= 0;
	var $cat_data		= array();

	var $sysdir			= array();
	var $upload_path	= "images/pictures/";

	var $user_perm		= array();

	function Admin_NewsPic(){
		global $Info, $Func, $Cache;

		$this->user_perm	= $Func->get_all_perms('menu_article_picture');

		$this->get_filter();
		$this->get_all_cats();
		$this->set_all_cats(0, 0);

		switch ($Info->act){
			case "preadd":
				$Func->check_user_perm($this->user_perm, 'add');
				$this->pre_add_picture();
				break;
			case "add":
				$Func->check_user_perm($this->user_perm, 'add');
				$Cache->clear_cache('all');
				$this->do_add_picture();
				break;
			case "preedit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->pre_edit_picture();
				break;
			case "edit":
				$Func->check_user_perm($this->user_perm, 'edit');
				$Cache->clear_cache('all');
				$this->do_edit_picture();
				break;
			case "enable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all');
				$this->active_pictures(1);
				break;
			case "disable":
				$Func->check_user_perm($this->user_perm, 'active');
				$Cache->clear_cache('all');
				$this->active_pictures(0);
				break;
			case "del":
				$Func->check_user_perm($this->user_perm, 'del');
				$Cache->clear_cache('all');
				$this->delete_pictures();
				break;
			case "view":
			case "picfull":
				$Func->check_user_perm($this->user_perm, 'view');
				$this->view_picture_full();
				break;
			default:
				$this->list_pictures();
		}
	}

	function get_filter(){
		global $Template;

		$this->filter['url_append']	= "";

		$this->filter['keyword']		= isset($_POST['fkeyword']) ? htmlspecialchars($_POST['fkeyword']) : '';
		if (empty($this->filter['keyword'])){
			$this->filter['keyword']	= isset($_GET["fkeyword"]) ? htmlspecialchars($_GET["fkeyword"]) : '';
		}
		if ( !empty($this->filter['keyword']) ){
			$this->filter['url_append']	.= '&fkeyword='. $this->filter['keyword'];
		}

		$this->filter['status']			= isset($_POST['fstatus']) ? intval($_POST['fstatus']) : -1;
		if ( $this->filter['status'] == -1 ){
			$this->filter['status']		= isset($_GET["fstatus"]) ? intval($_GET["fstatus"]) : -1;
		}
		if ( $this->filter['status'] != -1 ){
			$this->filter['url_append']	.= '&fstatus='. $this->filter['status'];
		}

		$this->filter['cat_id']			= isset($_POST['fcat_id']) ? intval($_POST['fcat_id']) : 0;
		if ( !$this->filter['cat_id'] ){
			$this->filter['cat_id']		= isset($_GET["fcat_id"]) ? intval($_GET["fcat_id"]) : 0;
		}
		if ( $this->filter['cat_id'] ){
			$this->filter['url_append']	.= '&fcat_id='. $this->filter['cat_id'];
		}

		$this->page			= isset($_GET["page"]) ? intval($_GET["page"]) : 1;

		$Template->set_vars(array(
			"FKEYWORD"		=> stripslashes($this->filter['keyword']),
			"FSTATUS"		=> $this->filter['status'],
			"FCAT_ID"		=> $this->filter['cat_id'],
		));
	}

	function list_pictures(){
		global $Session, $Func, $Info, $DB, $Template, $Lang;

		$Info->tpl_main	= "picture_list";
		$itemperpage	= 10;//$Info->option['items_per_page'];
		$date_format	= $Info->option['date_format'];
		$timezone		= $Info->option['timezone'] * 3600;

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['own']) ){
				$auth_where_sql	.= " AND poster_id=". $Info->user_info['user_id'];
			}
			
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		//Filter -------------------------
		$where_sql		= " WHERE picture_id>0";
		if ( ($this->filter['status'] == SYS_ENABLED) || ($this->filter['status'] == SYS_DISABLED) || ($this->filter['status'] == SYS_APPENDING) ){
			$where_sql	.= " AND enabled=". $this->filter['status'];
		}
		else if ( $this->filter['status'] == SYS_WAITING ){
			$where_sql	.= " AND posted_date>". CURRENT_TIME;
		}
		else if ( $this->filter['status'] == SYS_SHOWING ){
			$where_sql	.= " AND posted_date<=". CURRENT_TIME;
		}

		if ( $this->filter['cat_id'] ){
			$where_sql	.= " AND cat_id LIKE '%,". $this->filter['cat_id'] .",%'";
		}
		if ( !empty($this->filter['keyword']) ){
			$key		= str_replace("*", '%', $this->filter['keyword']);
			$where_sql	.= " AND pic_content LIKE('%". $key ."%')";
		}
		//------------------------------------

		//Generate pages
		$DB->query("SELECT count(picture_id) AS total FROM ". $DB->prefix ."picture $where_sql $auth_where_sql");
		if ( $DB->num_rows() ){
			$result		= $DB->fetch_array();
			$pageshow	= $Func->pagination($result['total'], $itemperpage, $this->page, $Session->append_sid(ACP_INDEX ."?mod=picture" . $this->filter['url_append']));
		}
		else{
			$pageshow['page']	= "";
			$pageshow['start']	= 0;
		}
		$DB->free_result();

		$DB->query("SELECT * FROM ". $DB->prefix ."picture $where_sql $auth_where_sql ORDER BY posted_date DESC LIMIT ". $pageshow['start'] .",$itemperpage");
		$picture_count		= $DB->num_rows();
		$picture_data		= $DB->fetch_all_array();

		for ($i=0; $i<$picture_count; $i++){
			if ($picture_data[$i]['enabled'] == SYS_APPENDING){
				$status	= $Lang->data['general_appending'];
			}
			else if ($picture_data[$i]['posted_date'] > CURRENT_TIME){
				$status	= $Lang->data['general_waiting'];
			}
			else{
				$status	= $Lang->data['general_showing'];
			}
			$this->get_image_dir($picture_data[$i]['posted_date'], $picture_data[$i]['picture_id']);

			$img_css	= !$picture_data[$i]['enabled'] ? ' style="FILTER: alpha(opacity=40); -moz-opacity:.40; opacity:.40;" ' : '';
			$Template->set_block_vars("picturerow",array(
				'PIC_THUMB'			=> '<img src="'. $this->sysdir['id'] .'/'. $picture_data[$i]['pic_thumb'] .'" '. $img_css .' border="0">',
				"ID"				=> $picture_data[$i]["picture_id"],
				"CONTENT"			=> nl2br($picture_data[$i]["pic_content"]),
				"CSS"				=> ($picture_data[$i]["enabled"] == SYS_ENABLED) ? "enabled" : "disabled",
				'BG_CSS'			=> ($i % 2) ? 'tdtext2' : 'tdtext1',
				'STATUS'			=> $status,
				"DATE"				=> $Func->translate_date(gmdate($date_format, $picture_data[$i]["posted_date"] + $timezone)),
				'U_VIEW'			=> $Session->append_sid(ACP_INDEX .'?mod=picture&act=view&id='. $picture_data[$i]["picture_id"]),
				'U_EDIT'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=picture&act=preedit&id='. $picture_data[$i]["picture_id"] . $this->filter['url_append'] .'&page='. $this->page) .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
			));
		}
		$DB->free_result();

		$Template->set_vars(array(
			"PAGE_OUT"					=> $pageshow['page'],
			'S_FILTER_ACTION'			=> $Session->append_sid(ACP_INDEX .'?mod=picture'),
			'S_LIST_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=picture&act=update'. $this->filter['url_append']),
			'U_ADD'						=> $Func->check_user_perm($this->user_perm, 'add', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=picture&act=preadd') .'"><img src="'. $Info->option['template_path'] .'/images/admin/add.gif" alt="" title="{'. $Lang->data['general_add'] .'" align="absbottom" border=0>'. $Lang->data['general_add'] .'</a> &nbsp; &nbsp; ' : '',
			'U_ENABLE'					=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=picture&act=enable' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/enable.gif" alt="" title="'. $Lang->data['general_enable'] .'" align="absbottom" border=0>'. $Lang->data['general_enable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DISABLE'					=> $Func->check_user_perm($this->user_perm, 'active', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=picture&act=disable' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/disable.gif" alt="" title="'. $Lang->data['general_disable'] .'" align="absbottom" border=0>'. $Lang->data['general_disable'] .'</a> &nbsp; &nbsp;' : '',
			'U_DELETE'					=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="javascript:deleteForm(\''. $Session->append_sid(ACP_INDEX .'?mod=picture&act=del' . $this->filter['url_append']) .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" alt="" title="'. $Lang->data['general_del'] .'" align="absbottom" border=0>'. $Lang->data['general_del'] .'</a> &nbsp; &nbsp;' : '',
			"L_PAGE_TITLE"				=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_picture"],
			"L_SHOW_PAGE"				=> $Lang->data["general_show_page"],
			"L_HOME_PAGE"				=> $Lang->data["general_home_page"],
			"L_PICTURE"					=> $Lang->data["picture"],
			"L_CONTENT"					=> $Lang->data["picture_content"],
			"L_DATE"					=> $Lang->data["general_date"],
			"L_SEARCH"					=> $Lang->data["button_search"],
			"L_DEL_CONFIRM"				=> $Lang->data['picture_del_confirm'],
			"L_CHOOSE_ITEM"				=> $Lang->data['picture_error_not_check'],
		));
	}

	function pre_add_picture($msg = ""){
		global $Session, $Info, $DB, $Template, $Lang, $Func;

		$Info->tpl_main	= "picture_edit";
		$this->set_lang();

/*
		$today = getdate();
		$month = $today['mon'];
		$day   = $today['mday'];
		$year  = $today['year'];
		$time  = $today['hours'] .":". $today['minutes'];
*/
		$date	= explode('-', gmdate('m-d-Y-H-i', CURRENT_TIME + $Info->option['timezone']*3600));
		$month	= intval($date[0]); //Remove zero from first letter
		$day	= intval($date[1]);
		$year	= intval($date[2]);
		$time	= $date[3] .":". $date[4];

		if ( isset($this->data['cat_id']) && is_array($this->data['cat_id']) ){
			//Compile cats
			reset($this->data['cat_id']);
			while ( list(, $cid) = each($this->data['cat_id']) ){
				if ( !empty($cid) ){
					$Template->set_block_vars('catvalrow', array(
						'ID'	=> $cid,
					));
				}
			}
		}

		$Template->set_block_vars("addrow",array());
		$Template->set_vars(array(
			"ERROR_MSG"				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=picture&act=add'. $this->filter['url_append']),
			"CONTENT"				=> isset($this->data["content"]) ? stripslashes($this->data["content"]) : '',
			"MONTH"					=> isset($this->data["month"]) ? $this->data["month"] : $month,
			"DAY"					=> isset($this->data["day"]) ? $this->data["day"] : $day,
			"YEAR"					=> isset($this->data["year"]) ? $this->data["year"] : $year,
			"TIME"					=> isset($this->data["time"]) ? $this->data["time"] : $time,
			"ENABLED"				=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : '',
			"PAGE_TO"				=> isset($this->data["page_to"]) ? $this->data["page_to"] : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_picture"] . $Lang->data['general_arrow'] . $Lang->data["general_add"],
			"L_BUTTON"				=> $Lang->data["button_add"],
		));
	}

	function set_lang(){
		global $Session, $Template, $Lang, $Info;

		if ( $Info->option['newspic_thumb_max_width'] && $Info->option['newspic_thumb_max_height'] ){
			$thumb_size		= '<br>'. sprintf($Lang->data['general_pic_max_size'], $Info->option['newspic_thumb_max_width'], $Info->option['newspic_thumb_max_height']);
		}
		else if ( $Info->option['newspic_thumb_max_width'] ){
			$thumb_size		= '<br>'. sprintf($Lang->data['general_pic_max_width'], $Info->option['newspic_thumb_max_width']);
		}
		else if ( $Info->option['newspic_thumb_max_height'] ){
			$thumb_size		= '<br>'. sprintf($Lang->data['general_pic_max_height'], $Info->option['newspic_thumb_max_height']);
		}
		else{
			$thumb_size		=	 "";
		}

		if ( $Info->option['newspic_full_max_width'] && $Info->option['newspic_full_max_height'] ){
			$full_size		= '<br>'. sprintf($Lang->data['general_pic_max_size'], $Info->option['newspic_full_max_width'], $Info->option['newspic_full_max_height']);
		}
		else if ( $Info->option['newspic_full_max_width'] ){
			$full_size		= '<br>'. sprintf($Lang->data['general_pic_max_width'], $Info->option['newspic_full_max_width']);
		}
		else if ( $Info->option['newspic_full_max_height'] ){
			$full_size		= '<br>'. sprintf($Lang->data['general_pic_max_height'], $Info->option['newspic_full_max_height']);
		}
		else{
			$full_size		=	 "";
		}

		$Template->set_vars(array(
			"L_PIC_THUMB"				=> $Lang->data["general_pic_thumb"],
			"L_REMOVE"					=> $Lang->data["general_pic_remove"],
			"L_PIC_FULL"				=> $Lang->data["general_pic_full"],
			"L_REMOVE"					=> $Lang->data["general_pic_remove"],
			"L_THUMB_SIZE"				=> $thumb_size,
			"L_FULL_SIZE"				=> $full_size,
			"L_CAT"						=> $Lang->data["general_cat"],
			"L_CAT_TIP"					=> $Lang->data["general_cat_tip"],
			"L_HOME_PAGE"				=> $Lang->data["general_home_page"],
			"L_CHOOSE"					=> $Lang->data["general_choose"],
			"L_PICTURE"					=> $Lang->data["picture"],
			"L_CONTENT"					=> $Lang->data["picture_content"],
			"L_POST_TIME"				=> $Lang->data["general_post_time"],
			"L_POST_TIME_TIP"			=> $Lang->data["general_post_time_tip"],
			"L_DATE"					=> $Lang->data["general_date"],
			"L_TIME"					=> $Lang->data["general_time"],
			"L_TIME_EXPLAIN"			=> $Lang->data["general_time_desc"],
			"L_YES"						=> $Lang->data["general_yes"],
			"L_NO"						=> $Lang->data["general_no"],
			"L_PAGE_TO"					=> $Lang->data["general_page_to"],
			"L_PAGE_ADD"				=> $Lang->data["general_page_add"],
			"L_PAGE_LIST"				=> $Lang->data["general_page_list"],
			"L_SAVE_AS"					=> $Lang->data["general_save_as"],
			"L_SAVE"					=> $Lang->data["general_save"],
			"L_COPY"					=> $Lang->data["general_copy"],
		));
	}

	function get_all_cats(){
		global $DB;

		$DB->query("SELECT * FROM ". $DB->prefix ."article_category WHERE redirect_url='' ORDER BY cat_order ASC");
		$this->cat_count = $DB->num_rows();
		$this->cat_data  = $DB->fetch_all_array();
		$DB->free_result();
	}

	function set_all_cats($parent_id, $except_cid, $level=0, $symbol="|-- ", $prefix="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"){
		global $Session, $Template;

		if ($level){
			$str_prefix	= "";
			for ($i=0; $i<$level; $i++){
				$str_prefix	.= $prefix;
			}
		}
		else{
			$str_prefix	= "";
		}

		for ($i=0; $i<$this->cat_count; $i++){
			if ( ($parent_id == $this->cat_data[$i]['cat_parent_id']) && ($except_cid != $this->cat_data[$i]['cat_id']) ){
				$Template->set_block_vars("catrow",array(
					'ID'				=> $this->cat_data[$i]['cat_id'],
					'NAME'				=> $this->cat_data[$i]['cat_name'],
					'PICTURE_COUNTER'	=> $this->cat_data[$i]['article_counter'],
					'SUBCAT_COUNTER'	=> $this->cat_data[$i]['article_counter'],
					'PREFIX'			=> $str_prefix .$symbol,
					"U_EDIT_CAT"		=> $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=preedit&id='. $this->cat_data[$i]['cat_id'] .'&page='. $this->page),
					"U_DEL_CAT"			=> $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=predel&id='. $this->cat_data[$i]['cat_id'] .'&page='. $this->page),
				));
				$this->set_all_cats($this->cat_data[$i]['cat_id'], $except_cid, $level+1, $symbol, $prefix);
			}
		}
	}

	function do_add_picture(){
		global $Session, $Info, $DB, $Template, $Lang, $Func, $Image, $File;

		$user_id						= $Info->user_info['user_id'];
		$this->data['pic_thumb']		= isset($_FILES["pic_thumb"]['name']) ? htmlspecialchars($_FILES["pic_thumb"]['name']) : '';
		$this->data['pic_full']			= isset($_FILES["pic_full"]['name']) ? htmlspecialchars($_FILES["pic_full"]['name']) : '';
		$this->data["content"]			= isset($_POST["content"]) ? $_POST["content"] : '';
		$this->data["cat_id"]			= isset($_POST["cat_id"]) ? $_POST["cat_id"] : '';
		$this->data["enabled"]			= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;
		$this->data["page_to"]			= isset($_POST["page_to"]) ? htmlspecialchars($_POST["page_to"]) : '';

		$this->data["ptime"]			= CURRENT_TIME;
		$this->data["month"]			= (isset($_POST["month"]) && ($_POST["month"] >=1 ) && ($_POST["month"] <= 12) ) ? $_POST["month"] : '';
		$this->data["day"]				= (isset($_POST["day"]) && ($_POST["day"] >=1 ) && ($_POST["day"] <= 31) ) ? $_POST["day"] : '';
		$this->data["year"]				= isset($_POST["year"]) ? intval($_POST["year"]) : '';
		$this->data["time"]				= isset($_POST["time"]) ? htmlspecialchars($_POST["time"]) : '0:0';

		//Check permission ---------------
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$this->data['enabled']	= SYS_DISABLED;
			}
		}
		//--------------------------------

		if ( !empty($this->data["month"]) && !empty($this->data["day"]) && !empty($this->data["year"]) ){
			if ( checkdate($this->data["month"], $this->data["day"], $this->data["year"]) ){
				$tmp	= explode(':', $this->data["time"]);
				if ( is_array($tmp) ){
					$hour		= ( isset($tmp[0]) && ($tmp[0] >= 0) && ($tmp[0] <= 24) ) ? $tmp[0] : 0;
					$minute		= ( isset($tmp[1]) && ($tmp[1] >= 0) && ($tmp[1] <= 60) ) ? $tmp[1] : 0;
				}
				else{
					$hour		= 0;
					$minute		= 0;
				}
				$this->data["ptime"]	= gmmktime($hour, $minute, 0, $this->data["month"], $this->data["day"], $this->data["year"]) - $Info->option['timezone']*3600;
			}
		}

		if ( empty($this->data["cat_id"]) || empty($this->data["pic_thumb"]) ){
			$this->pre_add_picture($Lang->data["general_error_not_full"]);
			return false;
		}

		if ( !empty($this->data['pic_thumb']) ){
			//Get file type
			$start		= strrpos($this->data['pic_thumb'], ".");
			$filetype	= strtolower(substr($this->data['pic_thumb'], $start));
			if ( !$File->check_filetype($filetype, 'image') ){
				$this->pre_add_picture(sprintf($Lang->data["upload_error_file_type"], $filetype));
				return false;
			}
		}

		if ( !empty($this->data['pic_full']) ){
			//Get file type
			$start		= strrpos($this->data['pic_full'], ".");
			$filetype	= strtolower(substr($this->data['pic_full'], $start));
			if ( !$File->check_filetype($filetype, 'image') ){
				$this->pre_add_picture(sprintf($Lang->data["upload_error_file_type"], $filetype));
				return false;
			}
		}

		//Compile cats
		if ( is_array($this->data['cat_id']) ){
			$cat_id	= ',';
			reset($this->data['cat_id']);
			while ( list(, $cid) = each($this->data['cat_id']) ){
				$cat_id	.= intval($cid) . ',';
			}
		}
		else{
			$cat_id	= '';
		}

		//Insert picture
		$sql	= "INSERT INTO ". $DB->prefix ."picture(cat_id, pic_thumb, pic_full, pic_content, poster_id, checker_id, posted_date, enabled)
						VALUES('$cat_id', '', '', '". $this->data["content"]."', $user_id, 0, ". $this->data["ptime"] .", 0)";
		$DB->query($sql);
		$picture_id	= $DB->insert_id();

		if ( !empty($this->data['pic_thumb']) || !empty($this->data['pic_full']) ){
			//Make image dir for this picture
			$this->make_image_dir($this->data["ptime"], $picture_id);
		}

		//Update pic thumb
		if ( !empty($this->data['pic_thumb']) ){
			//Pic_thumb -----------------------
			if ( file_exists($this->sysdir['id'] .'/'. $this->data["pic_thumb"]) ){
				$count		= 1;
				$pic_thumb	= str_replace(".", $count .".", $this->data["pic_thumb"]);
				while ( file_exists($this->sysdir['id'] .'/'. $pic_thumb) ){
					$count++;
					$pic_thumb	= str_replace(".", $count .".", $this->data["pic_thumb"]);
				}
				$this->data['pic_thumb'] = $pic_thumb;
			}
			$File->upload_file($_FILES["pic_thumb"]['tmp_name'], $this->sysdir['id'] .'/'. $this->data["pic_thumb"]);
			$Image->resize_image($this->sysdir['id'] .'/'. $this->data["pic_thumb"], $Info->option['newspic_thumb_max_width'], $Info->option['newspic_thumb_max_height'], 'all');
			//-----------------------------------

			//Pic_full -----------------------
			if ( file_exists($this->sysdir['id'] .'/'. $this->data["pic_full"]) ){
				$count		= 1;
				$pic_full	= str_replace(".", $count .".", $this->data["pic_full"]);
				while ( file_exists($this->sysdir['id'] .'/'. $pic_full) ){
					$count++;
					$pic_full	= str_replace(".", $count .".", $this->data["pic_full"]);
				}
				$this->data['pic_full'] = $pic_full;
			}
			$File->upload_file($_FILES["pic_full"]['tmp_name'], $this->sysdir['id'] .'/'. $this->data["pic_full"]);
			$Image->resize_image($this->sysdir['id'] .'/'. $this->data["pic_full"], $Info->option['newspic_full_max_width'], $Info->option['newspic_full_max_height'], 'all');
			//-----------------------------------
		}

		//Update picture
		$DB->query("UPDATE ". $DB->prefix ."picture SET pic_thumb='". $this->data['pic_thumb'] ."', pic_full='". $this->data['pic_full'] ."', enabled=". $this->data['enabled'] ." WHERE picture_id=$picture_id");

		//Save log
		$Func->save_log(FUNC_NAME, 'log_add', $picture_id, ACP_INDEX .'?mod=picture&act='. FUNC_ACT_VIEW .'&id='. $picture_id);

		if ( $this->data['page_to'] == 'pageadd' ){
			$tmp_data['page_to']	= $this->data['page_to'];
			$this->data	= $tmp_data;
			$this->pre_add_picture($Lang->data['general_success_add']);
		}
		else{
			$this->list_pictures();
		}

		return true;
	}

	function make_image_dir($time, $id){
		global $Template, $Lang, $File;

		$this->get_image_dir($time, $id);

		//Make dir
		if ( !file_exists($this->sysdir['id']) ){
			if ( !file_exists($this->sysdir['month']) ){
				$File->make_dir($this->sysdir['month'], 0777);
			}
			$File->make_dir($this->sysdir['id'], 0777);
		}
	}

	function get_image_dir($time, $id){
		global $Template, $Lang;

		//Get month and year
		$date	= getdate($time);
		if ($date['mon'] < 10){
			$date['mon']	= '0'. $date['mon'];
		}
		$this->sysdir['month']	= $this->upload_path. $date['year'] ."_". $date['mon'];
		$this->sysdir['id']		= $this->upload_path. $date['year'] ."_". $date['mon'] .'/'. $id;
	}

	function pre_edit_picture($msg = ""){
		global $Session, $DB, $Template, $Lang, $Info;

		$id		= isset($_GET["id"]) ? intval($_GET["id"]) : 0;

		$Info->tpl_main	= "picture_edit";

		$this->set_lang();

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['own']) ){
				$auth_where_sql	.= " AND poster_id=". $Info->user_info['user_id'];
			}
			
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		$DB->query("SELECT * FROM ". $DB->prefix ."picture WHERE picture_id=$id $auth_where_sql");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data["picture_error_not_exist"], $Session->append_sid(ACP_INDEX ."?mod=picture". $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
		$picture_info	= $DB->fetch_array();
		$DB->free_result();

		$this->get_image_dir($picture_info['posted_date'], $id);

		$date	= explode('-', gmdate('m-d-Y-H-i', $picture_info["posted_date"] + $Info->option['timezone']*3600));
		$month	= intval($date[0]); //Remove zero from first letter
		$day	= intval($date[1]);
		$year	= intval($date[2]);
		$time	= $date[3] .":". $date[4];

		if ( !empty($picture_info['pic_thumb']) || !empty($picture_info['pic_thumb']) ){
			$Template->set_block_vars("picthumb", array());
		}
		if ( !empty($picture_info['pic_full']) || !empty($picture_info['pic_full']) ){
			$Template->set_block_vars("picfull", array());
		}

		//Compile cats
		if ( !empty($picture_info["cat_id"]) ){
			$cat_data	= explode(',', $picture_info["cat_id"]);
			reset($cat_data);
			while ( list(, $cid) = each($cat_data) ){
				if ( !empty($cid) ){
					$Template->set_block_vars('catvalrow', array(
						'ID'	=> $cid,
					));
				}
			}
		}

		$Template->set_block_vars("editrow", array());
		$Template->set_vars(array(
			"ERROR_MSG"				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=picture&act=edit&id='. $id . $this->filter['url_append'] .'&page='. $this->page),
			'PIC_THUMB'				=> !empty($picture_info['pic_thumb']) ? '<img src="'. $this->sysdir['id'] .'/'. $picture_info['pic_thumb'] .'">' : '',
			'REMOVE_THUMB_CHECK'	=> (isset($this->data['remove_thumb']) && $this->data['remove_thumb']) ? 'checked' : '',
			'PIC_FULL'				=> !empty($picture_info['pic_full']) ? '<a href="javascript: open_window(\''. $Session->append_sid(ACP_INDEX .'?mod=picture&act=picfull&id='. $picture_info['picture_id']) .'\', 400, 350);"><strong>'. $picture_info['pic_full'] .'</strong></a> &nbsp;': '',
			'REMOVE_FULL_CHECK'		=> (isset($this->data['remove_full']) && $this->data['remove_full']) ? 'checked' : '',
			"CONTENT"				=> isset($this->data["content"]) ? stripslashes($this->data["content"]) : $picture_info['pic_content'],
			"MONTH"					=> isset($this->data["month"]) ? $this->data["month"] : $month,
			"DAY"					=> isset($this->data["day"]) ? $this->data["day"] : $day,
			"YEAR"					=> isset($this->data["year"]) ? $this->data["year"] : $year,
			"TIME"					=> isset($this->data["time"]) ? $this->data["time"] : $time,
			"ENABLED"				=> isset($this->data["enabled"]) ? intval($this->data["enabled"]) : $picture_info['enabled'],
			"L_PAGE_TITLE"			=> $Lang->data["menu_article"] . $Lang->data['general_arrow'] . $Lang->data["menu_article_picture"] . $Lang->data['general_arrow'] . $Lang->data["general_edit"],
			"L_BUTTON"				=> $Lang->data["button_edit"],
		));
		return true;
	}

	function do_edit_picture(){
		global $Session, $Info, $DB, $Template, $Lang, $Func, $Image, $File;

		$id								= isset($_GET['id']) ? intval($_GET['id']) : 0;
		$this->data["cat_id"]			= isset($_POST["cat_id"]) ? $_POST["cat_id"] : '';
		$this->data['pic_thumb']		= isset($_FILES["pic_thumb"]['name']) ? htmlspecialchars($_FILES["pic_thumb"]['name']) : '';
		$this->data["remove_thumb"]		= isset($_POST["remove_thumb"]) ? intval($_POST["remove_thumb"]) : 0;
		$this->data['pic_full']			= isset($_FILES["pic_full"]['name']) ? htmlspecialchars($_FILES["pic_full"]['name']) : '';
		$this->data["remove_full"]		= isset($_POST["remove_full"]) ? intval($_POST["remove_full"]) : 0;
		$this->data["content"]			= isset($_POST["content"]) ? $_POST["content"] : '';
		$this->data["enabled"]			= isset($_POST["enabled"]) ? intval($_POST["enabled"]) : 0;
		$this->data["page_to"]			= isset($_POST["page_to"]) ? htmlspecialchars($_POST["page_to"]) : '';

		$this->data["ptime"]			= CURRENT_TIME;
		$this->data["month"]			= (isset($_POST["month"]) && ($_POST["month"] >=1 ) && ($_POST["month"] <= 12) ) ? $_POST["month"] : '';
		$this->data["day"]				= (isset($_POST["day"]) && ($_POST["day"] >=1 ) && ($_POST["day"] <= 31) ) ? $_POST["day"] : '';
		$this->data["year"]				= isset($_POST["year"]) ? intval($_POST["year"]) : '';
		$this->data["time"]				= isset($_POST["time"]) ? htmlspecialchars($_POST["time"]) : '0:0';

		//Check permission ---------------
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$this->data['enabled']	= SYS_DISABLED;
			}
		}
		//--------------------------------

		if ( !empty($this->data["month"]) && !empty($this->data["day"]) && !empty($this->data["year"]) ){
			if ( checkdate($this->data["month"], $this->data["day"], $this->data["year"]) ){
				$tmp	= explode(':', $this->data["time"]);
				if ( is_array($tmp) ){
					$hour		= ( isset($tmp[0]) && ($tmp[0] >= 0) && ($tmp[0] <= 24) ) ? $tmp[0] : 0;
					$minute		= ( isset($tmp[1]) && ($tmp[1] >= 0) && ($tmp[1] <= 60) ) ? $tmp[1] : 0;
				}
				else{
					$hour		= 0;
					$minute		= 0;
				}
				$this->data["ptime"]	= gmmktime($hour, $minute, 0, $this->data["month"], $this->data["day"], $this->data["year"]) - $Info->option['timezone']*3600;
			}
		}

		if ( empty($this->data["cat_id"]) || ($this->data['remove_thumb'] && empty($this->data['pic_thumb'])) ){
			$this->pre_edit_picture($Lang->data["general_error_not_full"]);
			return false;
		}

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['own']) ){
				$auth_where_sql	.= " AND poster_id=". $Info->user_info['user_id'];
			}
			
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		//Get old info
		$DB->query('SELECT * FROM '. $DB->prefix .'picture WHERE picture_id='. $id . $auth_where_sql);
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data['picture_error_not_exist'], $Session->append_sid(ACP_INDEX .'?mod=picture'. $this->filter['url_append'] .'&page='. $this->page));
			return false;
		}
		$picture_info	= $DB->fetch_array();
		$this->make_image_dir($picture_info['posted_date'], $id);

		//Pic thumb
		$sql_pic_thumb	= "";
		if ( !empty($this->data['pic_thumb']) ){
			//Get file type
			$start		= strrpos($this->data['pic_thumb'], ".");
			$filetype	= strtolower(substr($this->data['pic_thumb'], $start));
			if ( !$File->check_filetype($filetype, 'image') ){
				$this->pre_edit_picture(sprintf($Lang->data["upload_error_file_type"], $filetype));
				return false;
			}
			//Delete old pic thumb
			if ( !empty($picture_info['pic_thumb']) ){
				$File->delete_file($this->sysdir['id'] .'/'. $picture_info['pic_thumb']);
			}

			//Pic thumb -----------------------
			if ( file_exists($this->sysdir['id'] .'/'. $this->data["pic_thumb"]) ){
				$count			= 1;
				$pic_thumb	= str_replace(".", $count .".", $this->data["pic_thumb"]);
				while ( file_exists($this->sysdir['id'] .'/'. $pic_thumb) ){
					$count++;
					$pic_thumb	= str_replace(".", $count .".", $this->data["pic_thumb"]);
				}
				$this->data['pic_thumb'] = $pic_thumb;
			}
			$File->upload_file($_FILES["pic_thumb"]['tmp_name'], $this->sysdir['id'] .'/'. $this->data["pic_thumb"]);
			$Image->resize_image($this->sysdir['id'] .'/'. $this->data["pic_thumb"], $Info->option['newspic_thumb_max_width'], $Info->option['newspic_thumb_max_height'], 'all');
			//-----------------------------------
			$sql_pic_thumb		= ", pic_thumb='". $this->data['pic_thumb'] ."'";
		}
		else if ( $this->data['remove_thumb'] ){
			//Delete old pic thumb
			if ( !empty($picture_info['pic_thumb']) ){
				$File->delete_file($this->sysdir['id'] .'/'. $picture_info['pic_thumb']);
			}
			$sql_pic_thumb	= ", pic_thumb=''";
		}

		//Pic full
		$sql_pic_full	= "";
		if ( !empty($this->data['pic_full']) ){
			//Get file type
			$start		= strrpos($this->data['pic_full'], ".");
			$filetype	= strtolower(substr($this->data['pic_full'], $start));
			if ( !$File->check_filetype($filetype, 'image') ){
				$this->pre_edit_picture(sprintf($Lang->data["upload_error_file_type"], $filetype));
				return false;
			}
			//Delete old pic full
			if ( !empty($picture_info['pic_full']) ){
				$File->delete_file($this->sysdir['id'] .'/'. $picture_info['pic_full']);
			}

			//Pic full -----------------------
			if ( file_exists($this->sysdir['id'] .'/'. $this->data["pic_full"]) ){
				$count			= 1;
				$pic_full	= str_replace(".", $count .".", $this->data["pic_full"]);
				while ( file_exists($this->sysdir['id'] .'/'. $pic_full) ){
					$count++;
					$pic_full	= str_replace(".", $count .".", $this->data["pic_full"]);
				}
				$this->data['pic_full'] = $pic_full;
			}
			$File->upload_file($_FILES["pic_full"]['tmp_name'], $this->sysdir['id'] .'/'. $this->data["pic_full"]);
			$Image->resize_image($this->sysdir['id'] .'/'. $this->data["pic_full"], $Info->option['newspic_full_max_width'], $Info->option['newspic_full_max_height'], 'all');
			//-----------------------------------
			$sql_pic_full		= ", pic_full='". $this->data['pic_full'] ."'";
		}
		else if ( $this->data['remove_full'] ){
			//Delete old pic full
			if ( !empty($picture_info['pic_full']) ){
				$File->delete_file($this->sysdir['id'] .'/'. $picture_info['pic_full']);
			}
			$sql_pic_full	= ", pic_full=''";
		}

		//Compile cats
		if ( is_array($this->data['cat_id']) ){
			$cat_id	= ',';
			reset($this->data['cat_id']);
			while ( list(, $cid) = each($this->data['cat_id']) ){
				$cat_id	.= $cid . ',';
			}
		}
		else{
			$cat_id	= '';
		}

		//Update picture
		$DB->query("UPDATE ". $DB->prefix ."picture SET cat_id='$cat_id'". $sql_pic_thumb . $sql_pic_full .", pic_content='". $this->data["content"]."', posted_date=". $this->data["ptime"] .", enabled=". $this->data['enabled']." WHERE picture_id=$id");

		//Save log
		$Func->save_log(FUNC_NAME, 'log_edit', $id, ACP_INDEX .'?mod=picture&act='. FUNC_ACT_VIEW .'&id='. $id);

		$this->list_pictures();
		return true;
	}

	function active_pictures($enabled = 0){
		global $DB, $Template, $Func, $Info;

		$picture_ids	= isset($_POST['picture_ids']) ? $_POST['picture_ids'] : '';

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['own']) ){
				$auth_where_sql	.= " AND poster_id=". $Info->user_info['user_id'];
			}
			
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		$ids_info	= $Func->get_array_value($picture_ids);
		if ( sizeof($ids_info) ){
			$log_act	= $enabled ? 'log_enable' : 'log_disable';
			$str_ids	= implode(',', $ids_info);

			//Update pictures
			$DB->query("UPDATE ". $DB->prefix ."picture SET enabled=$enabled WHERE picture_id IN (". $str_ids .") $auth_where_sql");
			//Save log
			$Func->save_log(FUNC_NAME, $log_act, $str_ids);
		}

		$this->list_pictures();
	}

	function delete_pictures(){
		global $DB, $Template, $Func, $Info, $File;

		$picture_ids	= isset($_POST['picture_ids']) ? $_POST['picture_ids'] : '';

		//Check permission ---------------
		$auth_where_sql		= "";
		if ( !isset($this->user_perm['item']['all']) ){
			if ( isset($this->user_perm['item']['own']) ){
				$auth_where_sql	.= " AND poster_id=". $Info->user_info['user_id'];
			}
			
			if ( isset($this->user_perm['item']['enabled']) && !isset($this->user_perm['item']['disabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_ENABLED;
			}
			else if ( isset($this->user_perm['item']['disabled']) && !isset($this->user_perm['item']['enabled']) ){
				$auth_where_sql	.= " AND enabled=". SYS_DISABLED;
			}
		}
		//--------------------------------

		$ids_info	= $Func->get_array_value($picture_ids);
		if ( sizeof($ids_info) ){
			$str_ids	= implode(',', $ids_info);
			$where_sql	= " WHERE picture_id IN (". $str_ids .")";

			//Get and delete image dirs --------
			$DB->query("SELECT * FROM ". $DB->prefix ."picture $where_sql $auth_where_sql");
			$picture_count	= $DB->num_rows();
			$picture_data	= $DB->fetch_all_array();

			for ($i=0; $i<$picture_count; $i++){
				$this->get_image_dir($picture_data[$i]['posted_date'], $picture_data[$i]['picture_id']);
				$File->delete_dir($this->sysdir['id']);
			}
			//------------------------------------

			//Delete pictures
			$DB->query("DELETE FROM ". $DB->prefix ."picture $where_sql");
			//Save log
			$Func->save_log(FUNC_NAME, 'log_del', $str_ids);
		}

		$this->list_pictures();
	}

	function view_picture_full(){
		global $DB, $Template, $Info, $Lang;

		$id		= isset($_GET['id']) ? intval($_GET['id']) : 0;

		$Info->tpl_header	= "";
		$Info->tpl_footer	= "";
		$Info->tpl_main		= "picture_full";

		$DB->query("SELECT * FROM ". $DB->prefix ."picture WHERE picture_id=$id");
		if ( !$DB->num_rows() ){
			$Template->message_die($Lang->data["picture_error_not_exist"]);
			return false;
		}
		$picture_info	= $DB->fetch_array();
		$DB->free_result();

		$this->get_image_dir($picture_info['posted_date'], $id);
		$Template->set_vars(array(
			'PIC_FULL'		=> !empty($picture_info['pic_full']) ? '<img src="'. $this->sysdir['id'] .'/'. $picture_info['pic_full'] .'" border="0">' : '',
		));
		return true;
	}
}
?>
