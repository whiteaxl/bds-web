<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Faq;

class Lang_Module_Faq
{
	var $data		= array();

	function Lang_Module_Faq(){
		//FAQ
		$this->data['faqs']						= 'FAQs';
		$this->data['faq_question']				= 'Question';
		$this->data['faq_answer']				= 'Answer';
		$this->data['faq_view_faqs']			= 'View FAQs';
		$this->data['faq_faqs_detail']			= 'Frequently Asked Questions';
		$this->data['faq_del_confirm']			= 'Delete checked faqs?';
		
		//FAQ error
		$this->data['faq_error_not_exist']		= 'Faq not found!';
		$this->data['faq_error_not_check']		= 'Please check faqs!';
	}
}

?>