<?php
/* =============================================================== *\
|		Module name: Global Article, Category						|
|		Module version: 1.7											|
|		Begin: 12 August 2003										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('SEARCH_TYPE', 0); //0--Normal Search | 1--Fulltext Search

class Admin_Article_Global
{
	var $data			= array();
	var $sysdir			= array();
	var $upload_path	= "images/articles/";

	var $cat_count		= 0;
	var $cat_data		= array();

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
		$this->sysdir['month']	= $this->upload_path . $date['year'] ."_". $date['mon'];
		$this->sysdir['id']		= $this->upload_path . $date['year'] ."_". $date['mon'] .'/'. $id;
	}

	function get_all_cats(){
		global $DB;

		$DB->query("SELECT * FROM ". $DB->prefix ."article_category WHERE redirect_url='' ORDER BY cat_order ASC");
		$this->cat_count = $DB->num_rows();
		$this->cat_data  = $DB->fetch_all_array();
		$DB->free_result();
	}

	function set_all_cats($parent_id, $except_cid, $level=0, $url_append = "", $symbol="|-- ", $prefix="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;", &$num = 0){
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
				$counter	= ($this->cat_data[$i]['article_counter'] || $this->cat_data[$i]['children_counter'])  ?  'true' : 'false';
				$Template->set_block_vars("catrow", array(
					'ID'				=> $this->cat_data[$i]['cat_id'],
					'ORDER'				=> $this->cat_data[$i]['cat_order'],
					'CODE'				=> $this->cat_data[$i]['cat_code'],
					'NAME'				=> $this->cat_data[$i]['cat_name'],
					'TEMPLATE'			=> $this->cat_data[$i]['cat_template'],
					'ARTICLE_COUNTER'	=> $this->cat_data[$i]['article_counter'],
					'SUBCAT_COUNTER'	=> $this->cat_data[$i]['article_counter'],
					'PREFIX'			=> $str_prefix .$symbol,
					"CSS"				=> ($this->cat_data[$i]["enabled"] == SYS_ENABLED) ? "enabled" : "disabled",
					'BG_CSS'			=> ($num % 2) ? 'tdtext2' : 'tdtext1',
					'U_EDIT'			=> $Func->check_user_perm($this->user_perm, 'edit', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=preedit&id='. $this->cat_data[$i]['cat_id']) . $url_append .'"><img src="'. $Info->option['template_path'] .'/images/admin/edit.gif" border=0 alt="" title="'. $Lang->data['general_edit'] .'"></a>' : '&nbsp;',
					'U_DEL'				=> $Func->check_user_perm($this->user_perm, 'del', 0) ? '<a href="'. $Session->append_sid(ACP_INDEX .'?mod=article_cat&act=predel&id='. $this->cat_data[$i]['cat_id'] . $url_append) .'" onclick="javascript: if ('. $counter .'){ return del_confirm(\''. $Lang->data['general_del_confirm'] .'\');}"><img src="'. $Info->option['template_path'] .'/images/admin/delete.gif" border=0 alt="" title="'. $Lang->data['general_del'] .'"></a>' : '&nbsp;',
				));
				$num++;
				$this->set_all_cats($this->cat_data[$i]['cat_id'], $except_cid, $level+1, $url_append, $symbol, $prefix, $num);
			}
		}
	}
}

?>