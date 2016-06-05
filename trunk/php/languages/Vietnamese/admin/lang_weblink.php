<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Weblink;

class Lang_Module_Weblink
{
	var $data		= array();

	function Lang_Module_Weblink(){
		//Weblink
		$this->data['weblink']						= 'Liên kết';
		$this->data['weblinks']						= 'Liên kết';
		$this->data['weblink_site_name']			= 'Tên website';
		$this->data['weblink_site_url']				= 'Địa chỉ URL';
		$this->data['weblink_del_confirm']			= 'Xóa các liên kết đã chọn?';
		$this->data['weblink_move']					= 'Di chuyển liên kết';
		$this->data['weblink_move_cat']				= 'Chuyển đến phân nhóm';

		//Weblink Cats
		$this->data['weblink_cat_desc']				= 'Mô tả';
		$this->data['weblink_cat_childs']			= 'Phân nhóm con';
		$this->data['weblink_cat_del_childs']		= 'Xóa các phân nhóm con';
		$this->data['weblink_cat_del_links']		= 'Xóa các liên kết';
		$this->data['weblink_cat_move']				= 'Di chuyển các liên kết';
		$this->data['weblink_cat_source']			= 'Phân nhóm nguồn';
		$this->data['weblink_cat_dest']				= 'Phân nhóm đích';
		$this->data['weblink_cat_move_to']			= 'Chuyển đến phân nhóm';

		//Weblink errors
		$this->data['weblink_success_move']			= 'Di chuyển liên kết thành công!';
		$this->data['weblink_error_not_exist']		= 'Không tìm thấy liên kết này!';
		$this->data['weblink_error_not_check']		= 'Vui lòng chọn liên kết!';
		$this->data['weblink_error_cat_source']		= 'Vui lòng chọn phân nhóm nguồn và phân nhóm đích.';
		$this->data['weblink_error_cat_not_exist']	= 'Không tìm thấy phân nhóm này!';
		$this->data['weblink_error_cat_exist']		= 'Đã có tên phân nhóm này. Vui lòng chọn tên khác.';
	}
}

?>