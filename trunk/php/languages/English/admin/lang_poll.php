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
		$this->data['poll_options']				= 'Poll Options';
		$this->data['poll_option_name']			= 'Option Name';
		$this->data['poll_multiple_choice']		= 'Multi-selection';
		$this->data['poll_question']			= 'Question';
		$this->data['poll_option']				= 'Option';
		$this->data['poll_old_option']			= 'Old Option';
		$this->data['poll_option_order']		= '#';
		$this->data['poll_add_desc']			= 'Note: You must enter at least 2 options for a valid poll.';
		$this->data['poll_more_options']		= 'Add More Options:';
		$this->data['poll_hits']				= 'Votes';
		$this->data['poll_question_desc']		= 'Click here to view this Poll';
		$this->data['poll_show_cat']			= 'Show On Category Listing Pages';
		$this->data['poll_all_cats']			= 'All Categories';
		$this->data['poll_order']				= 'Order';
		$this->data['poll_del_confirm']			= 'Delete checked polls?';

		//Poll error
		$this->data['poll_error_not_exist']		= 'Poll not found!';
		$this->data['poll_error_not_check']		= 'Please check polls!';
	}
}

?>