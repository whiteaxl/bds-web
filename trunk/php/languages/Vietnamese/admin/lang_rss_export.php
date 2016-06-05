<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_RSSExport;

class Lang_Module_RSSExport
{
	var $data		= array();

	function Lang_Module_RSSExport(){
		//RSS Export
		$this->data['rss_export']					= 'Xuất bãn RSS';
		$this->data['rss_export_publish']			= 'Xuất bản RSS';
		$this->data['rss_export_remove']			= 'Xóa RSS';
		$this->data['rss_export_latest_articles']	= 'Tin mới nhất';
	}
}

?>