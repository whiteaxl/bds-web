<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_RSS_Import;

class Lang_Module_RSS_Import
{
	var $data		= array();

	function Lang_Module_RSS_Import(){
		$this->data['rss_import_success']				= 'Đã import %d bài viết!';
		$this->data['rss_import_error_notfull']			= 'ID hoặc Mã RSS không có giá trị!';
		$this->data['rss_import_error_notfound']		= 'Không tìm thấy ID hoặc Mã RSS tương ứng!';
	}
}

?>