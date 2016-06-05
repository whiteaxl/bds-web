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
		$this->data['rss_export']					= 'RSS Export';
		$this->data['rss_export_publish']			= 'Publish RSS';
		$this->data['rss_export_remove']			= 'Remove RSS';
		$this->data['rss_export_latest_articles']	= 'Latest Articles';
	}
}

?>