<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Cache;

class Lang_Module_Cache
{
	var $data		= array();

	function Lang_Module_Cache(){
		//Cache
		$this->data['cache_html']				= 'HTML Caching';
		$this->data['cache_html_files']			= 'HTML files';
		$this->data['cache_html_size']			= 'HTML size';
		$this->data['cache_php']				= 'PHP Caching';
		$this->data['cache_php_files']			= 'PHP files';
		$this->data['cache_php_size']			= 'PHP size';
		$this->data['cache_total']				= 'Total Caching';
		$this->data['cache_total_files']		= 'Total Files';
		$this->data['cache_total_size']			= 'Total Size';
		$this->data['cache_clear']				= 'Clear Cache';
	}
}

?>