<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Newsletter;

class Lang_Module_Newsletter
{
	var $data		= array();

	function Lang_Module_Newsletter(){
		//Newsletter
		$this->data['newslt_name']					= 'Name';
		$this->data['newslt_email']					= 'Email';
		$this->data['newslt_emails']				= 'Emails';
		$this->data['newslt_subject']				= 'Subject';
		$this->data['newslt_content']				= 'Content';
		$this->data['newslt_choose_file']			= 'Choose file';
		$this->data['newslt_del_confirm']			= 'Delete checked emails?';
		$this->data['newslt_move']					= 'Move Emails';
		$this->data['newslt_move_cat']				= 'Move to Category';

		//Newsletter Cats
		$this->data['newslt_cat_desc']				= 'Description';
		$this->data['newslt_cat_childs']			= 'Sub cats';
		$this->data['newslt_cat_del_childs']		= 'Delete sub cats';
		$this->data['newslt_cat_del_emails']		= 'Delete emails';
		$this->data['newslt_cat_move']				= 'Move emails';
		$this->data['newslt_cat_source']			= 'Source categories';
		$this->data['newslt_cat_dest']				= 'Destination category';
		$this->data['newslt_cat_move_to']			= 'Move to Category';

		//Newsletter Issues
		$this->data['newslt_issue_title']			= 'Title';
		$this->data['newslt_issue_content']			= 'Content';
		$this->data['newslt_issue_send']			= 'Send email';
		$this->data['newslt_issue_last_sent']		= 'Last sent date';
		$this->data['newslt_issue_del_confirm']		= 'Delete checked issues?';
		$this->data['newslt_issue_note']			= '<strong>Note</strong>: You can use following tags in the message.<br>
														<LI>{USER_NAME}: display users\' names</LI>
														<LI>{USER_EMAIL}: display users\' emails</LI>
														<LI>{URL_UNSUBSCRIBE}: Users can click on this link to unsubscribe.</LI>
													  ';

		//Newsletter errors
		$this->data['newslt_success_move']			= 'Move emails successfully!';
		$this->data['newslt_success_send_issue']	= 'This issue has been successfully sent!';
		$this->data['newslt_success_send_continue']	= 'Emails from %d to %d have been successfully sent. Please wait for sending next emails.';
		$this->data['newslt_error_resend']			= 'Error: "%s". Try to resend after some seconds.';
		$this->data['newslt_error_not_exist']		= 'Email not found!';
		$this->data['newslt_error_not_check']		= 'Please check emails!';
		$this->data['newslt_error_issue_not_check']	= 'Please check issues!';
		$this->data['newslt_error_issue_not_exist']	= 'Issue not found!';
		$this->data['newslt_error_email_not_exist']	= 'Email not found!';
		$this->data['newslt_error_cat_source']		= 'Please choose source categories and destination category.';
		$this->data['newslt_error_cat_not_exist']	= 'Category not found!';
		$this->data['newslt_error_cat_exist']		= 'Category found. Please choose another name.';
	}
}

?>