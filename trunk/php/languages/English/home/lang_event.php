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
		$this->data['event']							= 'Events';
		$this->data['event_sunday']						= 'Su';
		$this->data['event_monday']						= 'Mo';
		$this->data['event_tuesday']					= 'Tu';
		$this->data['event_wednesday']					= 'We';
		$this->data['event_thurday']					= 'Th';
		$this->data['event_friday']						= 'Fr';
		$this->data['event_saturday']					= 'Sa';
		$this->data['event_sunday_full']				= 'Sun';
		$this->data['event_monday_full']				= 'Mon';
		$this->data['event_tuesday_full']				= 'Tue';
		$this->data['event_wednesday_full']				= 'Wed';
		$this->data['event_thurday_full']				= 'Thu';
		$this->data['event_friday_full']				= 'Fri';
		$this->data['event_saturday_full']				= 'Sat';
		$this->data['event_prev_month']					= 'Previous Month';
		$this->data['event_next_month']					= 'Next Month';
	}
}

?>