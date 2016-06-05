<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Poll;

class Lang_Module_Poll
{
	var $data		= array();

	function Lang_Module_Poll(){
		//Poll
		$this->data['poll']								= 'Bình chọn';
		$this->data['poll_result']						= 'Kết quả';
		$this->data['poll_total_hits']					= 'Tổng số bình chọn';
		$this->data['poll_view']						= 'Xem bình chọn';
		$this->data['poll_hit']							= 'Bình chọn';
		$this->data['poll_hits']						= 'Số bình chọn';
		$this->data['poll_hited']						= '(Quý khách đã bình chọn)';
		$this->data['poll_success_hit']					= 'Bình chọn thành công!';
		$this->data['poll_error_not_exist']				= 'Không tìm thấy bình chọn này!';
		$this->data['poll_error_hited']					= 'Quý khách đã bình chọn trước đây!';
		$this->data['poll_error_not_hit']				= '(Chưa có ai bình chọn)';
	}
}

?>