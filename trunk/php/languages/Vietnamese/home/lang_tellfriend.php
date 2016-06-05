<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Tellfriend;

class Lang_Module_Tellfriend
{
	var $data		= array();

	function Lang_Module_Tellfriend(){
		//Tell friend
		$this->data['tellfriend_your_name']				= 'Tên của bạn';
		$this->data['tellfriend_your_email']			= 'Email của bạn';
		$this->data['tellfriend_friend_name']			= 'Tên người thân';
		$this->data['tellfriend_friend_email']			= 'Email người thân';
		$this->data['tellfriend_message']				= 'Lời nhắn';
		$this->data['tellfriend_message_subject']		= '{YOUR_NAME} gui den {FRIEND_NAME}';
		$this->data['tellfriend_message_content']		= "Xin chào {FRIEND_NAME},\n\n<br>Một người quen gửi tặng bạn địa chỉ này kèm lời nhắn:\n<br><a href='{U_ARTICLE}'>{U_ARTICLE}</a>\n\n<br><br>{MESSAGE}";
		$this->data['tellfriend_success_send']			= 'Lời nhắn của bạn đã được gửi đến bạn bè thành công!<br>Bạn có thể đóng cửa sổ này hoặc tiếp tục gửi đến người khác.';
	}
}

?>