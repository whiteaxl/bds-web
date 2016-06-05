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
		$this->data['cache_html']				= 'Cache file HTML';
		$this->data['cache_html_files']			= 'Các file HTML';
		$this->data['cache_html_size']			= 'Kích thước các file HTML';
		$this->data['cache_php']				= 'Cache file PHP';
		$this->data['cache_php_files']			= 'Các file PHP';
		$this->data['cache_php_size']			= 'Kích thước các file PHP';
		$this->data['cache_total']				= 'Tổng cộng';
		$this->data['cache_total_files']		= 'Tổng số file';
		$this->data['cache_total_size']			= 'Tổng kích thước';
		$this->data['cache_clear']				= 'Xóa cache';
	}
}

?>