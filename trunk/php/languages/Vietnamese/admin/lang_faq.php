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
		$this->data['faqs']						= 'Hỏi đáp';
		$this->data['faq_question']				= 'Câu hỏi';
		$this->data['faq_answer']				= 'Trả lời';
		$this->data['faq_view_faqs']			= 'Xem hỏi đáp';
		$this->data['faq_faqs_detail']			= 'Hỏi đáp';
		$this->data['faq_del_confirm']			= 'Xóa hỏi đáp?';
		
		//FAQ error
		$this->data['faq_error_not_exist']		= 'Không tìm thấy hỏi đáp này!';
		$this->data['faq_error_not_check']		= 'Vui lòng chọn hỏi đáp!';
	}
}

?>