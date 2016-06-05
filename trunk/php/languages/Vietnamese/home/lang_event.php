<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Event;

class Lang_Module_Event
{
	var $data		= array();

	function Lang_Module_Event(){
		//Event
		$this->data['event']							= 'Sự kiện';
		$this->data['event_sunday']						= 'CN';
		$this->data['event_monday']						= 'T2';
		$this->data['event_tuesday']					= 'T3';
		$this->data['event_wednesday']					= 'T4';
		$this->data['event_thurday']					= 'T5';
		$this->data['event_friday']						= 'T6';
		$this->data['event_saturday']					= 'T7';
		$this->data['event_sunday_full']				= 'CN';
		$this->data['event_monday_full']				= 'Hai';
		$this->data['event_tuesday_full']				= 'Ba';
		$this->data['event_wednesday_full']				= 'Tư';
		$this->data['event_thurday_full']				= 'Năm';
		$this->data['event_friday_full']				= 'Sáu';
		$this->data['event_saturday_full']				= 'Bảy';
		$this->data['event_prev_month']					= 'Tháng trước';
		$this->data['event_next_month']					= 'Tháng sau';
	}
}

?>