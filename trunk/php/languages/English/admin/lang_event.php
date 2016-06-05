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
		$this->data['event']						= 'Event';
		$this->data['event_title']					= 'Title';
		$this->data['event_detail']					= 'Detail';
		$this->data['event_del_confirm']			= 'Are you sure to delete checked events?';

		//Event error
		$this->data['event_error_time']				= 'From Time and To Time are invalid!';
		$this->data['event_error_not_check']		= 'Please check events!';
	}
}

?>