<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Picture;

class Lang_Module_Picture
{
	var $data		= array();

	function Lang_Module_Picture(){
		//Picture
		$this->data['picture']						= 'Hình ảnh';
		$this->data['picture_content']				= 'Nội dung';
		$this->data['picture_del_confirm']			= 'Bạn có chắc xóa các hình đã chọn?';
		$this->data['picture_error_not_check']		= 'Vui lòng chọn hình ảnh!';
	}
}

?>