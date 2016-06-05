<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Event;

class Lang_Module_Event
{
	var $data		= array();

	function Lang_Module_Event(){
		//Events
		$this->data['event']						= 'Sự kiện';
		$this->data['event_title']					= 'Tiêu đề';
		$this->data['event_detail']					= 'Chi tiết';
		$this->data['event_del_confirm']			= 'Bạn có chắc xóa các sự kiện đã chọn?';

		//Event error
		$this->data['event_error_time']				= 'Thời gian bắt đầu và thời gian kết thúc không hợp lý!';
		$this->data['event_error_not_check']		= 'Vui lòng chọn các sự kiện!';
	}
}

?>