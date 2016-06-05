<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Logo;

class Lang_Module_Logo
{
	var $data		= array();

	function Lang_Module_Logo(){
		//Logo
		$this->data['logo_old_logo']			= 'Logo cũ';
		$this->data['logo_new_logo']			= 'Logo mới';
		$this->data['logo']						= 'Logo';
		$this->data['logo_order']				= 'STT';
		$this->data['logo_local']				= 'Hình trên máy';
		$this->data['logo_remote']				= 'Hình trên web';
		$this->data['logo_size']				= 'Kích thước logo';
		$this->data['logo_width']				= 'Ngang';
		$this->data['logo_height']				= 'Dọc';
		$this->data['logo_site_url']			= 'Địa chỉ URL';
		$this->data['logo_site_name']			= 'Tên website';
		$this->data['logo_del_confirm']			= 'Bạn có chắc xóa các logo đã chọn?';

		//Mod AdsBox begin
		$this->data['logo_pos']					= 'Vị trí hiển thị';
		$this->data['logo_pos_left']			= 'Bên trái';
		$this->data['logo_pos_right']			= 'Bên phải';

		//Logo error
		$this->data['logo_error_not_exist']		= 'Không tìm thấy logo này!';
		$this->data['logo_error_not_check']		= 'Vui lòng chọn các logo!';
	}
}

?>