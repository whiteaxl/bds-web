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
		$this->data['contact']						= 'Contact Us';
		$this->data['contact_name']					= 'Name';
		$this->data['contact_email']				= 'Email';
		$this->data['contact_address']				= 'Address';
		$this->data['contact_tel']					= 'Tel';
		$this->data['contact_subject']				= 'Subject';
		$this->data['contact_message']				= 'Message';
		$this->data['contact_button_send']			= '  Send  ';
		$this->data['contact_success_send']			= 'Your message has been successfully sent!<br>We will have a response to you as soon as possible.<br><br>Thanks and regards.';
		$this->data['contact_error_flood']			= 'Please wait some seconds and before trying to contact again.';
	}
}

?>