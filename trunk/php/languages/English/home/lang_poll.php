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
		$this->data['poll']								= 'Poll';
		$this->data['poll_result']						= 'Result';
		$this->data['poll_total_hits']					= 'Total votes';
		$this->data['poll_view']						= 'View Poll';
		$this->data['poll_hit']							= 'Vote';
		$this->data['poll_hits']						= 'Votes';
		$this->data['poll_hited']						= '(You have voted)';
		$this->data['poll_success_hit']					= 'Vote successfully!';
		$this->data['poll_error_not_exist']				= 'Poll not found!';
		$this->data['poll_error_hited']					= 'You have voted this poll before!';
		$this->data['poll_error_not_hit']				= '(This poll does not have any vote.)';
	}
}

?>