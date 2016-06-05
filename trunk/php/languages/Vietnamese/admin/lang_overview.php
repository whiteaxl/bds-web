<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Overview;

class Lang_Module_Overview
{
	var $data		= array();

	function Lang_Module_Overview(){
		$this->data['overview']					= 'Thông tin tổng quan';
		$this->data['online_overview']			= 'Các thành viên đang sử dụng Bảng điều khiển';
		$this->data['system_overview']			= 'Thông tin hệ thống';
		$this->data['script_name']				= 'Tên phần mềm';
		$this->data['script_version']			= 'Phiên bản';
		$this->data['server_type']				= 'Thông tin máy chủ';
		$this->data['web_server']				= 'Phần mềm máy chủ web';
		$this->data['php_version']				= 'Phiên bản PHP';
		$this->data['mysql_version']			= 'Phiên bản MySQL';
		$this->data['data_overview']			= 'Thông tin dữ liệu';
		$this->data['acp_note']					= 'Ghi chú';

		//New version
		$this->data['new_version']					= 'Phiên bản mới hơn';
		$this->data['new_version_available']		= 'Đã có <strong>%s</strong>. Bạn có thể xem thông tin chi tiết trên website';
		$this->data['new_version_not_available']	= 'Phiên bản bạn đang sử dụng là mới nhất';
		$this->data['new_version_not_connect']		= 'Không kết nối được với máy chủ';
		$this->data['new_version_invalid']			= 'Phiên bản bạn đang sử dụng không tồn tại';
	}
}

?>