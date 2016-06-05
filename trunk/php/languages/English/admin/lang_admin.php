<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

class Lang_Admin extends Lang_Global
{
	var $data		= array();

	function Lang_Admin(){
		$this->data['menu_general']					= 'General';
		$this->data['menu_general_login']			= 'Login';
		$this->data['menu_general_logout']			= 'Logout';
		//Menu article
		$this->data['menu_article']					= 'News';
		$this->data['menu_article_cat']				= 'Categories';
		$this->data['menu_article_article']			= 'Articles';
		$this->data['menu_article_page']			= 'Pages';
		$this->data['menu_article_topic']			= 'Topics';
		$this->data['menu_article_comment']			= 'Comments';
		$this->data['menu_article_picture']			= 'News in Pictures';
		//Menu RSS
		$this->data['menu_rss']						= 'RSS Feeds';
		$this->data['menu_rss_export']				= 'RSS Export';
		$this->data['menu_rss_import']				= 'RSS Import';
		//Menu user
		$this->data['menu_user']					= 'User';
		$this->data['menu_user_group']				= 'Groups';
		$this->data['menu_user_user']				= 'Users';
		$this->data['menu_user_field']				= 'Profile Fields';
		//Menu newsletter
		$this->data['menu_newslt']					= 'Newsletter';
		$this->data['menu_newslt_cat']				= 'Categories';
		$this->data['menu_newslt_email']			= 'Emails';
		$this->data['menu_newslt_issue']			= 'Issues';
		//Menu miscellaneous
		$this->data['menu_miscell']					= 'Miscellaneous';
		$this->data['menu_miscell_event']			= 'Events';
		$this->data['menu_miscell_poll']			= 'Polls';
		$this->data['menu_miscell_faq']				= 'Faqs';
		$this->data['menu_miscell_logo']			= 'Logos';
		//Menu weblink
		$this->data['menu_weblink']					= 'Web Link';
		$this->data['menu_weblink_cat']				= 'Categories';
		$this->data['menu_weblink_weblink']			= 'Links';
		//Menu admin
		$this->data['menu_admin']					= 'System';
		$this->data['menu_admin_setting']			= 'Setting';
		$this->data['menu_admin_cache']				= 'Cache';
		$this->data['menu_admin_system']			= 'System';
		$this->data['menu_admin_log']				= 'ACP Logs';
		$this->data['menu_admin_stat']				= 'Statistics';
		//Menu database
		$this->data['menu_db']						= 'Database';
		$this->data['menu_db_info']					= 'DB Information';
		$this->data['menu_db_backup']				= 'DB Backup / Repair';
		$this->data['menu_db_restore']				= 'DB Restore';
		$this->data['menu_db_runtime']				= 'Runtime Information';
		$this->data['menu_db_system']				= 'System Variables';
		$this->data['menu_db_process']				= 'Processes';
		//Menu private
		$this->data['menu_private']					= 'Private';
		$this->data['menu_private_profile']			= 'Change Profile';
		$this->data['menu_private_changepass']		= 'Change Password';
		$this->data['menu_private_login']			= 'Login';
		$this->data['menu_private_logout']			= 'Logout';

		//General
		$this->data['general_admin_control']		= 'Admin Control Panel';
		$this->data['general_acp_home']				= 'Control Panel Home';
		$this->data['general_online_document']		= 'Online Document';
		$this->data['general_username']				= 'Username';
		$this->data['general_password']				= 'Password';
		$this->data['general_remember']				= 'Remember';
		$this->data['general_login']				= 'Log In';
		$this->data['general_logout']				= 'Log Out';
		$this->data['general_view']					= 'View';
		$this->data['general_preview']				= 'General Preview';
		$this->data['general_online']				= 'Administrators Using ACP';
		$this->data['general_ip']					= 'IP';
		$this->data['general_add']					= 'Add';
		$this->data['general_edit']					= 'Edit';
		$this->data['general_del']					= 'Delete';
		$this->data['general_archive']				= 'Archive';
		$this->data['general_unarchive']			= 'Unarchive';
		$this->data['general_archived']				= 'Archived';
		$this->data['general_unarchived']			= 'Unarchived';
		$this->data['general_move']					= 'Move';
		$this->data['general_update']				= 'Update';
		$this->data['general_resync']				= 'Resync';
		$this->data['general_enable']				= 'Enable';
		$this->data['general_disable']				= 'Disable';
		$this->data['general_enabled']				= 'Enabled';
		$this->data['general_disabled']				= 'Disabled';
		$this->data['general_appending']			= 'Not approved';
		$this->data['general_checkall']				= 'Check All';
		$this->data['general_uncheckall']			= 'UnCheck All';
		$this->data['general_default']				= 'Default';
		$this->data['general_confirmation']			= 'Confirmation';
		$this->data['general_home_page']			= 'Home Page';
		$this->data['general_cat']					= 'Category';
		$this->data['general_cat_tip']				= 'Choose category pages where news picture will be displayed.';
		$this->data['general_author']				= 'Author';
		$this->data['general_hits']					= 'Hits';
		$this->data['general_visitors']				= 'Visitors';
		$this->data['general_order']				= '#';
		$this->data['general_search_order']			= 'Order';
		$this->data['general_posted_date']			= 'Posted Date';
		$this->data['general_contact']				= 'Contact';
		$this->data['general_import']				= 'Import';
		$this->data['general_export']				= 'Export';
		$this->data['general_remove']				= 'Remove';
		$this->data['general_admin_function']		= 'Admin Function';
		$this->data['general_permission']			= 'Permission';
		$this->data['general_page']					= 'Page';
		$this->data['general_page_to']				= 'Go to page';
		$this->data['general_page_list']			= 'List rows';
		$this->data['general_page_add']				= 'Add another row';
		$this->data['general_page_counter']			= 'Pages';
		$this->data['general_page_desc']			= 'Click here to manage pages of this article';
		$this->data['general_comment_desc']			= 'Click here to search comments of this article';
		$this->data['general_view_desc']			= 'Click here to view detail';
		$this->data['general_copy']					= 'Copy as new row';
		$this->data['general_save']					= 'Save';
		$this->data['general_save_as']				= 'Save as';
		$this->data['general_status']				= 'Status';
		$this->data['general_title']				= 'Title';
		$this->data['general_date']					= 'Date';
		$this->data['general_day']					= 'Day';
		$this->data['general_month']				= 'Month';
		$this->data['general_year']					= 'Year';
		$this->data['general_addded_date']			= 'Added Date';
		$this->data['general_post_time']			= 'Post Time';
		$this->data['general_post_time_tip']		= "If you choose the date in the future, this post won't be displayed until that time.";
		$this->data['general_time']					= 'Time';
		$this->data['general_time_to']				= 'To';
		$this->data['general_hour']					= 'Hour';
		$this->data['general_minute']				= 'Minute';
		$this->data['general_time_desc']			= 'Use the 24-hour clock, format hh:mm';		
		$this->data['general_timezone']				= 'Timezone';
		$this->data['general_arrow']				= ' &raquo; ';
		$this->data['general_del_confirm']			= 'Are you sure to delete?';
		$this->data['general_asc']					= 'Asc';
		$this->data['general_desc']					= 'Desc';
		$this->data['general_help']					= 'Help';
		$this->data['general_close_window']			= 'Close';
		$this->data['general_never']				= 'Never';
		$this->data['general_start_date']			= 'Start Date';
		$this->data['general_end_date']				= 'End Date';
		$this->data['general_past_future']			= 'Custom Date';
		$this->data['general_expired']				= 'Expired';
		$this->data['general_waiting']				= 'Waiting';
		$this->data['general_running']				= 'Running';
		$this->data['general_showing']				= 'Showing';
		$this->data['general_user_ip']				= '%s (IP: %s)';
		$this->data['general_show_page']			= 'Page';
		$this->data['general_normal']				= 'Listing Mode';
		$this->data['general_expand']				= 'Expanding Mode';
		$this->data['general_expand_all']			= 'Expand All';
		$this->data['general_collapse_all']			= 'Collapse All';
		$this->data['general_show_panel']			= 'Show Menu Panel';
		$this->data['general_hide_panel']			= 'Hide Menu Panel';
		$this->data['general_forgot_pass']			= 'Forgot Password';
		$this->data['general_send_pass_subject']	= 'Reset your password';
		$this->data['general_send_pass_content']	= 'Hi {USERNAME},<br><br>You have just require send your password at our site ({SITE_URL}). Please click the link below to reset your password:<br><br>{U_CONFIRM}<br><br>{SITE_NAME}<br>{SITE_URL}';
		$this->data['general_send_pass_content_2']	= 'Hi {USERNAME},<br><br>You have just require send your password at our site ({SITE_URL}). And this is your new password: {NEW_PASS}<br>Now you can login to website.<br><br>{SITE_NAME}<br>{SITE_URL}';
		$this->data['general_rss']					= 'RSS';
		$this->data['general_rss_publish']			= 'Publish RSS';
		$this->data['general_cat']					= 'Category';
		$this->data['general_cats']					= 'Categories';
		$this->data['general_cat_root']				= 'Root';
		$this->data['general_cat_name']				= 'Name';
		$this->data['general_cat_parent']			= 'Parent category';
		$this->data['general_cat_childs']			= 'Sub categories';
		$this->data['general_language']				= 'Language';
		$this->data['general_template']				= 'Template';
		$this->data['search_engine_tip']			= 'Search engine optimization';
		$this->data['general_meta_keywords']		= 'Meta Keywords';
		$this->data['general_meta_desc']			= 'Meta Description';
		$this->data['general_optional']				= 'Optional';
		$this->data['general_redirect_url']			= 'Redirect URL';
		$this->data['general_id']					= 'ID';

		//General picture
		$this->data['general_pic']					= 'Picture';
		$this->data['general_pic_thumb']			= 'Thumbsize Picture';
		$this->data['general_pic_full']				= 'Fullsize Picture';
		$this->data['general_pic_remove']			= 'Remove';
		$this->data['general_pic_max_size']			= '(Maximum dimension: %d x %d pixels)';
		$this->data['general_pic_max_width']		= '(Maximum width: %d pixels)';
		$this->data['general_pic_max_height']		= '(Maximum height: %d pixels)';

		//Web pages
		$this->data['page_displaying']				= 'Displaying on Pages';
		$this->data['page_home']					= 'Home Page';
		$this->data['page_faq']						= 'FAQ Page';
		$this->data['page_weblink']					= 'Link Page';
		$this->data['page_sitemap']					= 'Site Map Page';
		$this->data['page_rss']						= 'RSS Page';
		$this->data['page_contact']					= 'Contact Page';
		$this->data['page_event']					= 'Event Page';
		$this->data['page_articles']				= 'Article Pages';

		//Upload
		$this->data['upload_error_not_file']		= 'Please choose file to upload';
		$this->data['upload_error_file_type']		= 'You can not upload file type <b>%s</b>';
		$this->data['upload_error_upload']			= 'Could not upload this file <b>%s</b>';
		$this->data['upload_error_mkdir']			= 'Could not create dir <b>%s</b>';

		//Functions
		$this->data['func_function']				= 'Functions';
		$this->data['func_action']					= 'Allowed Actions';
		$this->data['func_action_tip']				= 'Choose allowed actions for group.';
		$this->data['func_item']					= 'Allowed Items';
		$this->data['func_item_tip']				= 'Choose allowed items for group.';

		//ACP Log
		$this->data['log_login']					= 'Login';
		$this->data['log_logout']					= 'Logout';
		$this->data['log_time']						= 'Time';
		$this->data['log_function']					= 'Function';
		$this->data['log_action']					= 'Action';
		$this->data['log_record']					= 'Record';
		$this->data['log_user']						= 'User';
		$this->data['log_ip']						= 'User IP';
		$this->data['log_add']						= 'Add';
		$this->data['log_edit']						= 'Edit';
		$this->data['log_update']					= 'Update';
		$this->data['log_resync']					= 'Resync';
		$this->data['log_move']						= 'Move';
		$this->data['log_move_cat']					= 'Move to Cat';
		$this->data['log_move_topic']				= 'Move to Topic';
		$this->data['log_kill_user']				= 'Kill user';
		$this->data['log_rescure_user']				= 'Rescure user';
		$this->data['log_run_sql']					= 'Run sql';
		$this->data['log_del']						= 'Delete';
		$this->data['log_del_all']					= 'Delete All';
		$this->data['log_export']					= 'Export File';
		$this->data['log_backup']					= 'Backup';
		$this->data['log_restore']					= 'Restore';
		$this->data['log_repair']					= 'Repair tables';
		$this->data['log_enable']					= 'Enable';
		$this->data['log_disable']					= 'Disable';
		$this->data['log_archive']					= 'Archive';
		$this->data['log_unarchive']				= 'Unarchive';
		$this->data['log_set_perm']					= 'Set Permission';
		$this->data['log_rss_export_update']		= 'Update RSS';
		$this->data['log_rss_import_update']		= 'Update RSS';

		//Button
		$this->data['button_add']					= '    Add    ';
		$this->data['button_edit']					= '   Update   ';
		$this->data['button_delete']				= '   Delete  ';
		$this->data['button_back']					= '   Back   ';
		$this->data['button_move']					= '    Move    ';
		$this->data['button_reset']					= '    Reset   ';
		$this->data['button_login']					= '    Login   ';
		$this->data['button_search']				= 'Search';
		$this->data['button_go']					= '  Go  ';
		$this->data['button_send']					= '   Send   ';
		$this->data['button_import']				= '   Import   ';
		$this->data['button_remove']				= '   Remove   ';

		//Error Message
		$this->data['general_error_username']				= 'Username <b>%s</b> is invalid!';
		$this->data['general_error_password']				= 'Password is invalid!';
		$this->data['general_error_not_username']			= 'Please enter username!';
		$this->data['general_error_not_password']			= 'Please enter password!';
		$this->data['general_error_not_login']				= 'You are not logged in!';
		$this->data['general_error_not_permission']			= "You don't have permission to access this area!";
		$this->data['general_error_not_full']				= 'Please complete all required fields!';
		$this->data['general_error_not_exist_faq']			= 'FAQ not found!';
		$this->data['general_error_not_table']				= 'Please chose the table to backup.';
		$this->data['general_error_not_exist_logo']			= 'Logo not found!';
		$this->data['general_error_start_end_date']			= 'End date must be after Start date!';
		$this->data['general_error_start_date']				= 'Start date is invalid!';
		$this->data['general_error_end_date']				= 'End date is invalid!';
		$this->data['general_error_date']					= 'Date is invalid!';
		$this->data['general_error_send_pass']				= 'You can not send too many emails to this member account!';

		$this->data['perm_error_not_all']					= "You don't have permission to access this area!";
		$this->data['perm_error_not_view']					= "You don't have permission to view!";
		$this->data['perm_error_not_add']					= "You don't have permission to add!";
		$this->data['perm_error_not_edit']					= "You don't have permission to edit!";
		$this->data['perm_error_not_del']					= "You don't have permission to delete!";
		$this->data['perm_error_not_active']				= "You don't have permission to enable or disable!";
		$this->data['perm_error_not_move_article']			= "You don't have permission to move articles!";
		$this->data['perm_error_not_move_email']			= "You don't have permission to move emails!";
		$this->data['perm_error_not_move_user']				= "You don't have permission to move users!";
		$this->data['perm_error_not_backup']				= "You don't have permission to backup database!";
		$this->data['perm_error_not_restore']				= "You don't have permission to restore database!";
		$this->data['perm_error_not_run_sql']				= "You don't have permission to run sql!";

		//Successfull Message
		$this->data['general_success_login']				= 'Login successfully!';
		$this->data['general_success_logout']				= 'Logout successfully!';
		$this->data['general_success_add']					= 'Add Successfully!';
		$this->data['general_success_edit']					= 'Edit Successfully!';
		$this->data['general_success_update']				= 'Update Successfully!';
		$this->data['general_success_delete']				= 'Delete Successfully!';
		$this->data['general_success_resync']				= 'Resync Successfully!';
		$this->data['general_success_clean_cache']			= 'All cached files were deleted and Cache Engine was restarted.';
		$this->data['general_success_add_emails']			= 'Add %d emails successfully!';
		$this->data['general_success_send']					= 'The message has been sent to all emails!';
		$this->data['general_success_send_password']		= 'Please check your email in order to get new password.';
		$this->data['general_success_reset_password']		= 'Your new password has been sent to your email! Please check mail to get it.';

		//Rss Import Logs
		$this->data['rss_log_import_articles']			= 'Import articles';
		$this->data['rss_log_remove_articles']			= 'Remove articles';
	}
}

?>