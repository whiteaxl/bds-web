<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Setting;

class Lang_Module_Setting
{
	var $data		= array();

	function Lang_Module_Setting(){
		//Tabs
		$this->data['setting_tab_website']			= 'Website';
		$this->data['setting_tab_system']			= 'System';
		$this->data['setting_tab_modules']			= 'Modules';
		$this->data['setting_tab_open_close']		= 'Open / Close';

		//Website
		$this->data['setting_site_name']			= 'Site Name';
		$this->data['setting_site_slogan']			= 'Site Slogan';
		$this->data['setting_site_url']				= 'Site URL';
		$this->data['setting_site_path']			= 'Site Path';
		$this->data['setting_site_keywords']		= 'Site Keywords';
		$this->data['setting_site_desc']			= 'Site Description';
		$this->data['setting_admin_email']			= 'Admin Email';
		$this->data['setting_timezone']				= 'Timezone';
		$this->data['setting_date_format']			= 'Date Format';
		$this->data['setting_time_format']			= 'Date Time Format';
		$this->data['setting_full_date_time_format']= 'Full Date Time Format';

		//System
		$this->data['setting_cookie']				= 'Cookie';
		$this->data['setting_cookie_domain']		= 'Cookie Domain';
		$this->data['setting_cookie_domain_desc']	= 'Set domain to save cookies under.<br>(Ex: .yourdomain.com)';
		$this->data['setting_cookie_path']			= 'Cookie Path';
		$this->data['setting_cookie_time']			= 'Cookie Expired Time';
		$this->data['setting_cookie_time_desc']		= '(seconds)';
		$this->data['setting_time_login']			= 'Login time';
		$this->data['setting_time_login_desc']		= '(Auto logout after <em>n</em> seconds)';
		$this->data['setting_items_per_page']		= 'Items per page';
		$this->data['setting_items_per_page_desc']	= 'Number items per page in ACP';
		$this->data['setting_smtp']					= 'SMTP Information';
		$this->data['setting_smtp_host']			= 'SMTP Server';
		$this->data['setting_smtp_username']		= 'SMTP Username';
		$this->data['setting_smtp_password']		= 'SMTP Password';
		$this->data['setting_ftp']					= 'FTP Information';
		$this->data['setting_ftp_host']				= 'FTP URL';
		$this->data['setting_ftp_port']				= 'FTP Port';
		$this->data['setting_ftp_username']			= 'FTP Username';
		$this->data['setting_ftp_password']			= 'FTP Password';
		$this->data['setting_turn_on']				= 'Turn On';
		$this->data['setting_turn_off']				= 'Turn Off';
		$this->data['setting_hidden_desc']			= '(Hidden for security)';

		//Modules
		$this->data['setting_short_url']			= 'Friendly URL';
		$this->data['setting_article']				= 'Article Setting';
		$this->data['setting_article_image']		= 'Article Image Setting';
		$this->data['setting_cache']				= 'Cache Engine';
		$this->data['setting_cache_desc']			= 'Caching your results will improve speed of your website and reduce minimum connections to database.';
		$this->data['setting_website_close']		= 'Close Website';
		$this->data['setting_website_close_desc']	= 'Temporarily close your website.';
		$this->data['setting_website_close_message']	= 'Message';
		$this->data['setting_cache_expire']			= 'Expired time of Cache';
		$this->data['setting_cache_expire_desc']	= '(seconds)';

		$this->data['setting_poll']					= 'Poll Box';
		$this->data['setting_poll_options']			= 'Number options (default)';
		$this->data['setting_revote_time']			= 'Revoting Time';
		$this->data['setting_revote_time_desc']		= 'Allow user revote poll after number seconds.';

		$this->data['setting_latest_box']			= 'Latest Articles Box';
		$this->data['setting_latest_box_type']		= 'Displaying';
		$this->data['setting_latest_box_items']	= 'Number articles';
		$this->data['setting_latest_articles']		= 'Latest News';
		$this->data['setting_today_articles']		= 'Today News';
		$this->data['setting_not_display']			= 'Not display';

		$this->data['setting_other_boxes']			= 'Other Boxes';
		$this->data['setting_newsletter']			= 'Newsletter Box';
		$this->data['setting_event']				= 'Event Box';
		$this->data['setting_newspic']				= 'News Picture Box';

//		$this->data['setting_gzip']					= 'Enable Gzip';
//		$this->data['setting_gzip_desc']			= 'All pages will be compressed to save bandwidth for your site. However, it will increase server load.';
//		$this->data['setting_gzip_level']			= 'Gzip Level';
//		$this->data['setting_gzip_level_desc']		= 'Set the level of compression for gzipping your pages, higher values will increase server load drimatically.';
		$this->data['setting_news_per_page']		= 'News per page';
		$this->data['setting_news_per_page_desc']	= 'Items displayed per page in Home';
		$this->data['setting_image_type']			= 'Image Type';
		$this->data['setting_image_type_desc']		= '(Limit types of images which users can upload for their articles. Use "," to separate many types or leave blank for no limit.)';
		$this->data['setting_image_max_size']		= 'Max image size';
		$this->data['setting_image_max_size_desc']	= '(Maximum size of images which can be uploaded. Leave blank for no limit.)';
		$this->data['setting_log']					= 'ACP Logs';
		$this->data['setting_log_days']				= 'Limit days';
		$this->data['setting_log_days_desc']		= 'Use "0" to unlimit';
		$this->data['setting_rating']				= 'Article Rating';
		$this->data['setting_rating_desc']			= 'Visitors can rate articles from 1 to 5 stars.';
		$this->data['setting_comment']				= 'Article Comment';
		$this->data['setting_comment_desc']			= 'Visitors can add their comments for articles.';
		$this->data['setting_wysiwyg_title']		= 'WYSIWYG Editor for Article Title?';
		$this->data['setting_wysiwyg_title_desc']	= 'Writers can format article titles with wysiwyg editor.';
		$this->data['setting_short_url_desc']		= 'Only for Apache web server (mod Rewrite enabled)';
		$this->data['setting_short_url_sep']				= 'URL Separator';
		$this->data['setting_short_url_sep_desc']			= 'Friendly URL must be turned on';
		$this->data['setting_menucat_level']		= 'Category Menu Level';
		$this->data['setting_archived_default']		= 'ACP Article Listing';

		$this->data['setting_home_focus_limit']		= 'Home Page - Focus News';
		$this->data['setting_home_focus_cols']		= 'Home Page - Focus News Columns';
		$this->data['setting_home_hot_limit']		= 'Home Page - Hot News';
		$this->data['setting_home_latest_limit']	= 'Home Page - Latest News';
		$this->data['setting_home_cat_cols']			= 'Home Page - Category Columns';
		$this->data['setting_home_cat_article_limit']	= 'Home Page - Top News In Every Category';

		$this->data['setting_limit_home_news']		= 'Limit News Per Page';
		$this->data['setting_limit_home_news_next']	= 'Limit Next News';
		$this->data['setting_limit_home_hot']		= 'Limit Hot News';
		$this->data['setting_limit_home_comment']	= 'Limit Comments';
		$this->data['setting_newspic_rand_limit']	= 'Limit News Pictures Per Page';
		$this->data['setting_newspic_rand_time']	= 'Limit time (seconds) for one News Picture';
		$this->data['setting_thumb_large']			= 'Large Thumbnail';
		$this->data['setting_thumb_small']			= 'Small Thumbnail';
		$this->data['setting_thumb_icon']			= 'Icon Thumbnail';
		$this->data['setting_newspic_thumb']		= 'Thumbnail of News Picture';
		$this->data['setting_newspic_full']			= 'Fullsize of News Picture';
		$this->data['setting_max_width']			= 'Max Width';
		$this->data['setting_max_height']			= 'Max Height';

		$this->data['setting_error_template']		= 'Template folder is invalid!';
		$this->data['setting_error_language']		= 'Language folder is invalid!';
	}
}

?>