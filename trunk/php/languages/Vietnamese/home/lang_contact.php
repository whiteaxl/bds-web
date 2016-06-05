<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Contact;

class Lang_Module_Contact
{
	var $data		= array();

	function Lang_Module_Contact(){
		//Contact
		$this->data['contact']						= 'Liên hệ';
		$this->data['contact_name']					= 'Họ tên';
		$this->data['contact_email']				= 'Email';
		$this->data['contact_address']				= 'Địa chỉ';
		$this->data['contact_tel']					= 'Điện thoại';
		$this->data['contact_subject']				= 'Tiêu đề';
		$this->data['contact_message']				= 'Nội dung';
		$this->data['contact_button_send']			= '  Gửi  ';
		$this->data['contact_success_send']			= 'Lời nhắn của quý khách đã được gửi đến chúng tôi thành công.<br>Chúng tôi sẽ có phản hồi cho quý khách trong thời gian sớm nhất có thể.<br><br>Xin cảm ơn và chúc sức khỏe.';
		$this->data['contact_error_flood']			= 'Quý khách không thể gửi ý kiến một cách liên tục trong thời gian ngắn.<br>Xin vui lòng đợi giây lát và gửi lại lần nữa.';
	}
}

?>