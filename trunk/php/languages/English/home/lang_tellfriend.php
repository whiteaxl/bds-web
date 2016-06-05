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
		$this->data['tellfriend_your_name']				= 'Your Name';
		$this->data['tellfriend_your_email']			= 'Your Email';
		$this->data['tellfriend_friend_name']			= 'Your Friend\'s Name';
		$this->data['tellfriend_friend_email']			= 'Your Friend\'s Email';
		$this->data['tellfriend_message']				= 'Message';
		$this->data['tellfriend_message_subject']		= '{YOUR_NAME} send to {FRIEND_NAME}';
		$this->data['tellfriend_message_content']		= "Hi {FRIEND_NAME},\n\n<br>Your friend send you the following link with messages:\n<br><a href='{U_ARTICLE}'>{U_ARTICLE}</a>\n\n<br><br>{MESSAGE}";
		$this->data['tellfriend_success_send']			= 'The message has been sent to your friend successfully!<br>You can close this window or send to another friend.';
	}
}

?>