<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Poll;

class Lang_Module_Poll
{
	var $data		= array();

	function Lang_Module_Poll(){
		//Polls
		$this->data['poll_options']				= 'Thông số bình chọn';
		$this->data['poll_option_name']			= 'Tên tùy chọn';
		$this->data['poll_multiple_choice']		= 'Nhiều lựa chọn';
		$this->data['poll_question']			= 'Câu hỏi';
		$this->data['poll_option']				= 'Tùy chọn';
		$this->data['poll_old_option']			= 'Tùy chọn cũ';
		$this->data['poll_option_order']		= '#';
		$this->data['poll_add_desc']			= 'Ghi chú: Bạn phải nhập ít nhất 2 tùy chọn cho mỗi bình chọn.';
		$this->data['poll_more_options']		= 'Thêm tùy chọn:';
		$this->data['poll_hits']				= 'Bình chọn';
		$this->data['poll_question_desc']		= 'Nhấn vào đây để xem bình chọn này';
		$this->data['poll_show_cat']			= 'Hiển thị trong phân nhóm';
		$this->data['poll_all_cats']			= 'Tất cả phân nhóm';
		$this->data['poll_order']				= 'STT';
		$this->data['poll_del_confirm']			= 'Xóa các bình chọn?';

		//Poll error
		$this->data['poll_error_not_exist']		= 'Không tìm thấy bình chọn này!';
		$this->data['poll_error_not_check']		= 'Vui lòng chọn các bình chọn!';
	}
}

?>