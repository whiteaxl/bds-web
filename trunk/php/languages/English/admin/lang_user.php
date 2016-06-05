<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_User;

class Lang_Module_User
{
	var $data		= array();

	function Lang_Module_User(){
		//User Groups
		$this->data['user_group_filter']			= 'Group';
		$this->data['user_group_name']				= 'Group Name';
		$this->data['user_group_desc']				= 'Group Description';
		$this->data['user_group_level']				= 'Group Level';
		$this->data['user_group_users']				= 'Users';
		$this->data['user_group_del_confirm']		= 'Deleting Group...';
		$this->data['user_group_move']				= 'Move users to group:';
		$this->data['user_group_del']				= 'Delete this group';
		$this->data['user_group_del_move']			= 'Delete or move users?';
		$this->data['user_group_del_users']			= 'Delete all users in this group.';
		$this->data['user_group_admin']				= 'Admin';
		$this->data['user_group_normal']			= 'Normal';
		$this->data['user_group_perm']				= 'Permission';
		$this->data['user_group_set_perm']			= 'Set permission for group "%s"';
		$this->data['user_group_source']			= 'Source Groups';
		$this->data['user_group_dest']				= 'Destination Group';
		$this->data['user_perm_view']				= 'View';
		$this->data['user_perm_add']				= 'Add';
		$this->data['user_perm_edit']				= 'Edit';
		$this->data['user_perm_del']				= 'Delete';
		$this->data['user_perm_active']				= 'Active';
		$this->data['user_perm_move_article']		= 'Move Articles';
		$this->data['user_perm_move_email']			= 'Move Emails';
		$this->data['user_perm_move_user']			= 'Move Users';
		$this->data['user_perm_move_weblink']		= 'Move Links';
		$this->data['user_perm_send_email']			= 'Send Email';
		$this->data['user_perm_reset']				= 'Reset';
		$this->data['user_perm_all']				= 'All';
		$this->data['user_perm_own']				= 'Own';
		$this->data['user_perm_enabled']			= 'Enabled';
		$this->data['user_perm_disabled']			= 'Disabled';
		$this->data['user_perm_backup']				= 'Backup';
		$this->data['user_perm_repair']				= 'Repair';
		$this->data['user_perm_restore']			= 'Restore';
		$this->data['user_perm_import']				= 'Import';
		$this->data['user_perm_export']				= 'Export';
		$this->data['user_perm_run_sql']			= 'Run SQL';
		$this->data['user_allow_actions']			= 'Allow Actions: ';
		$this->data['user_allow_items']				= 'Allow Items: &nbsp; ';

		//Users
		$this->data['user_in_group']				= 'In Groups';
		$this->data['user_move']					= 'Move users';
		$this->data['user_username']				= 'Username';
		$this->data['user_pass']					= 'Password';
		$this->data['user_verify_pass']				= 'Confirm Password';
		$this->data['user_old_pass']				= 'Old Password';
		$this->data['user_new_pass']				= 'New Password';
		$this->data['user_verify_new_pass']			= 'Confirm New Password';
		$this->data['user_email']					= 'Email';
		$this->data['user_articles']				= 'Articles';
		$this->data['user_timezone']				= 'Timezone';
		$this->data['user_hide_email']				= 'Hide Email';
		$this->data['user_in_group']				= 'In Group';
		$this->data['user_never']					= 'Never';
		$this->data['user_join_date']				= 'Joined Date';
		$this->data['user_last_login']				= 'Last Login';
		$this->data['user_online']					= 'Online';
		$this->data['user_kick']					= 'Kick and ban this user in some minutes';
		$this->data['user_kicked']					= 'Kicked';
		$this->data['user_kicked_by']				= "You was kicked by <strong>%s</strong> and you won't be able to login for next %d minutes.";
		$this->data['user_rescure']					= 'Allow this user to login again';
		$this->data['user_del_confirm']				= 'Delete checked users?';

		//User Errors
		$this->data['user_error_not_exist']			= 'User not found!';
		$this->data['user_error_not_check']			= 'Please check users!';
		$this->data['user_error_exist_group']		= 'The group <b>%s</b> existed!';
		$this->data['user_error_exist_username']	= 'This username existed. Please choose another one.';
		$this->data['user_error_not_exist_group']	= 'Group not found!';
		$this->data['user_error_oldpass']			= 'Your old password is invalid!';
		$this->data['user_error_verifypass']		= 'Your password and confirm password does not match!';

		//Profile Fields
		$this->data['field_type']					= 'Type';
		$this->data['field_required']				= 'Required';
		$this->data['field_required_desc']			= 'This field is required when add/edit users.';
		$this->data['field_textinput']				= 'Text Input';
		$this->data['field_textarea']				= 'Text Area';
		$this->data['field_dropdown']				= 'Drop Down List';
		$this->data['field_editable']				= 'Editable';
		$this->data['field_content']				= 'Field Content (for Drop downs)';
		$this->data['field_content_desc']			= 'Example:<br>To make "Gender" field like <select size= "1"><option value= "m">Male</option><option value= "f">Female</option></select><br>You type:<br> &nbsp; &nbsp; &nbsp; m=Male<br> &nbsp; &nbsp; &nbsp; f=Female<br>m,f stored in database. When showing field in profile, will use value from pair (f=Female, shows "Female")';
		$this->data['field_title']					= 'Field Title';
		$this->data['field_desc']					= 'Field Description';
		$this->data['field_type']					= 'Field Type';
		$this->data['field_max_input']				= 'Max Input';
		$this->data['field_max_input_desc']			= 'max characters for text input and text areas';
		$this->data['field_size']					= 'Field Size';
		$this->data['field_size_desc']				= 'Field size for input and textarea. Format:<br>- Input (size) : xx<br>- Textarea (cols,rows): xx,xx<br>(with xx is the number)';
		$this->data['field_del']					= 'Delete Field';
		$this->data['field_del_desc']				= 'All user\'s information regarding to this field will be deleted also. Are you sure to delete?';

		//Field errors
		$this->data['field_error_no_title']			= 'Please enter field title.';
		$this->data['field_error_exist_title']		= 'This field title existed! Please choose another one.';
		$this->data['field_error_not_exist']		= 'Field not found.';
	}
}

?>