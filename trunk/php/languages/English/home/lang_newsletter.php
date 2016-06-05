<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Newsletter;

class Lang_Module_Newsletter
{
	var $data		= array();

	function Lang_Module_Newsletter(){
		//Newsletter and email
		$this->data['newslt']							= 'Newsletter';
		$this->data['newslt_subscribe']					= 'Subscribe';
		$this->data['newslt_unsubscribe']				= 'UnSubscribe';
		$this->data['newslt_subscribe_desc']			= 'Subscribe to our newsletter';
		$this->data['newslt_subscribe_confirm']			= 'Subscribe successfully. We will send an confirmation email to you shortly.';
		$this->data['newslt_name']						= 'Your Name';
		$this->data['newslt_email']						= 'Your Email';
		$this->data['newslt_error_email']				= 'Your email is invalid!';
		$this->data['newslt_validate_email']			= 'Newsletter &raquo; Validate Email:';
		$this->data['newslt_validate_number']			= 'Validate Number';
		$this->data['newslt_validate_type']				= 'Validate Type';

		//Newsletter success
		$this->data['newslt_success_subscribe']			= 'Your email has been successfully subscribed!';
		$this->data['newslt_success_unsubscribe']		= 'Your email has been successfully unsubscribed!';

		//Newsletter error
		$this->data['newslt_error_email']				= 'Please enter your email';
		$this->data['newslt_error_email_exist']			= 'This email exists.';
	}
}

?>