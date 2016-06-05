<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_RSS_Import;

class Lang_Module_RSS_Import
{
	var $data		= array();

	function Lang_Module_RSS_Import(){
		$this->data['rss_import_success']				= 'Imported %d article(s)!';
		$this->data['rss_import_error_notfull']			= 'RSS ID or RSS Code is empty!';
		$this->data['rss_import_error_notfound']		= 'RSS Stream not found!';
	}
}

?>