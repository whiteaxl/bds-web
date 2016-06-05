<?php
/* =============================================================== *\
|		Module name: RSS Export										|
|		Module version: 1.1											|
|		Begin: 01 July 2006											|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('FUNC_NAME', 'menu_rss_export');
define('RSS_CODE_LATEST', 'latest');
//Module language
$Func->import_module_language("admin/lang_rss_export". PHP_EX);

$AdminRssExport = new Admin_Rss_Export;

class Admin_Rss_Export
{
	var $cat_count		= 0;
	var $cat_data		= array();
	var $data			= array();
	var $rss_info		= array();

	var $user_perm		= array();

	function Admin_Rss_Export(){
		global $Info, $Func;

		$this->user_perm	= $Func->get_all_perms('menu_rss_export');

		switch ($Info->act){
			case "publish":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->update_rss('insert');
				break;
			case "remove":
				$Func->check_user_perm($this->user_perm, 'edit');
				$this->update_rss('remove');
				break;
			default:
				$this->list_rss();
		}
	}

	function list_rss(){
		global $Session, $Func, $Info, $Lang, $Template, $DB;

		$Info->tpl_main	= "rss_export_list";

		//Get all published streams
		$DB->query("SELECT cat_id, rss_code FROM ". $DB->prefix ."rss_export");
		$rss_count = $DB->num_rows();
		$rss_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$rss_count; $i++){
			if ( $rss_data[$i]['cat_id'] ){
				$this->rss_info['cat_ids'][]	= $rss_data[$i]['cat_id'];
			}
			else{
				$this->rss_info['rss_codes'][]	= $rss_data[$i]['rss_code'];
			}
		}

		$this->get_all_cats();
		$this->set_all_cats(0, 0, 0);

		$Template->set_vars(array(
			'U_PUBLISH_RSS'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=rss_export&act=publish') .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/rss_export_publish.gif" alt="" title="'. $Lang->data['rss_export_publish'] .'" align="absbottom" border=0>'. $Lang->data['rss_export_publish'] .'</a> &nbsp; &nbsp;' : '',
			'U_REMOVE_RSS'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="javascript:updateForm(\''. $Session->append_sid(ACP_INDEX .'?mod=rss_export&act=remove') .'\');"><img src="'. $Info->option['template_path'] .'/images/admin/rss_export_remove.gif" alt="" title="'. $Lang->data['rss_export_remove'] .'" align="absbottom" border=0>'. $Lang->data['rss_export_remove'] .'</a> &nbsp; &nbsp;' : '',
			"L_PAGE_TITLE"			=> $Lang->data["menu_rss"] . $Lang->data['general_arrow'] . $Lang->data["menu_rss_export"],
			"L_CATEGORY"			=> $Lang->data["general_cat"],
			"L_LATEST_ARTICLES"	=> $Lang->data['rss_export_latest_articles'],
			"L_RSS"					=> $Lang->data["general_rss"],
			"L_NORMAL"				=> $Lang->data["general_normal"],
			"L_EXPAND"				=> $Lang->data["general_expand"],
			'RSS_CODE_LATEST'		=> RSS_CODE_LATEST,
			'RSS_LATEST_ARTICLES'	=> (isset($this->rss_info['rss_codes']) && in_array(RSS_CODE_LATEST, $this->rss_info['rss_codes'])) ? '<img src="'. $Info->option['template_path'] .'/images/admin/rss.gif" border="0">' : '&nbsp;',
		));
	}

	function get_all_cats(){
		global $DB;

		$DB->query("SELECT * FROM ". $DB->prefix ."article_category WHERE redirect_url='' ORDER BY cat_order ASC");
		$this->cat_count = $DB->num_rows();
		$this->cat_data  = $DB->fetch_all_array();
		$DB->free_result();
	}

	function set_all_cats($parent_id, $except_cid, $level=0, $symbol="|-- ", $prefix="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", $num = 0){
		global $Session, $Template, $Func, $Info, $Lang;

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
//				$counter	= ($this->cat_data[$i]['article_counter'] || $this->cat_data[$i]['children_counter'])  ?  'true' : 'false';
				$Template->set_block_vars("catrow",array(
					'ID'				=> $this->cat_data[$i]['cat_id'],
					'NAME'				=> $this->cat_data[$i]['cat_name'],
					'RSS'				=> (isset($this->rss_info['cat_ids']) && in_array($this->cat_data[$i]['cat_id'], $this->rss_info['cat_ids'])) ? '<img src="'. $Info->option['template_path'] .'/images/admin/rss.gif" border="0">' : '&nbsp;',
					'PREFIX'			=> $str_prefix .$symbol,
					'BG_CSS'			=> ($num % 2) ? 'tdtext1' : 'tdtext2',
				));
				$num++;
				$this->set_all_cats($this->cat_data[$i]['cat_id'], $except_cid, $level+1, $symbol, $prefix, $num);
			}
		}
	}

	function update_rss($act = ""){
		global $Session, $DB, $Template, $Lang, $Func;

		$rss_codes		= isset($_POST['rss_codes']) ? $_POST['rss_codes'] : '';
		$cat_ids		= isset($_POST['cat_ids']) ? $_POST['cat_ids'] : '';

		//Categories ---------------------
		if ( is_array($cat_ids) ){
			reset($cat_ids);
			if ( $act == 'insert' ){
				while ( list(,$cid) = each($cat_ids) ){
					$cid = intval($cid);
					if ( $cid ){
						//Check exist
						$DB->query("SELECT rss_id FROM ". $DB->prefix ."rss_export WHERE cat_id=". $cid);
						if ( !$DB->num_rows() ){
							$DB->query("INSERT INTO ". $DB->prefix ."rss_export(cat_id, rss_code) VALUES(". $cid .", '')");
						}
					}
				}
			}
			else{
				$ids_info	= $Func->get_array_value($cat_ids);
				if ( sizeof($ids_info) ){
					$DB->query("DELETE FROM ". $DB->prefix ."rss_export WHERE cat_id IN (". implode(',', $ids_info) .")");
				}
			}
		}
		//--------------------------------

		//Other page codes ---------------
		if ( is_array($rss_codes) ){
			reset($rss_codes);
			if ( $act == 'insert' ){
				while ( list(,$code) = each($rss_codes) ){
					if ( !empty($code) ){
						//Check exist
						$DB->query("SELECT rss_id FROM ". $DB->prefix ."rss_export WHERE rss_code='". $code ."'");
						if ( !$DB->num_rows() ){
							$DB->query("INSERT INTO ". $DB->prefix ."rss_export(cat_id, rss_code) VALUES(0, '". $code ."')");
						}
					}
				}
			}
			else{
				$where_sql	= "WHERE (rss_code='-1'";
				while ( list(,$code) = each($rss_codes) ){
					$where_sql	.= " OR rss_code='". $code ."'";
				}
				$where_sql	.= ")";

				$DB->query("DELETE FROM ". $DB->prefix ."rss_export ". $where_sql);
			}
		}
		//--------------------------------

		//Save log
		$Func->save_log(FUNC_NAME, "log_rss_export_update");

		$this->list_rss();
	}
}
?>