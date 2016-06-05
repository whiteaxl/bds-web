<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

class Lang_Home extends Lang_Global
{
	var $data		= array();

	function Lang_Home(){
		//General
		$this->data['general_home']						= 'Home';
		$this->data['general_all_news']					= 'All News';
		$this->data['general_archive']					= 'Archive';
		$this->data['general_back']						= 'Back';
		$this->data['general_event']					= 'Events';
		$this->data['general_faq']						= 'FAQ';
		$this->data['general_weblink']					= 'Links';
		$this->data['general_sitemap']					= 'Site Map';
		$this->data['general_rss']						= 'RSS';
		$this->data['general_about']					= 'About Us';
		$this->data['general_last_update']				= 'Last updated: %s (GMT%s)';
		$this->data['general_today']					= 'Today: %s (GMT%s)';
		$this->data['general_contact']					= 'Contact';
		$this->data['general_print']					= 'Print';
		$this->data['general_tellfriend']				= 'Tell friend';
		$this->data['general_notify']					= 'Notify';
		$this->data['general_contact_us']				= 'Contact Us';
		$this->data['general_favorite_add']				= 'Add to Favorites';
		$this->data['general_view_detail']				= 'View Detail';
		$this->data['general_gmt']						= 'GMT';
		$this->data['general_close_window']				= 'Close';
		$this->data['general_expand_all']				= 'Expand All';
		$this->data['general_collapse_all']				= 'Collapse All';
		$this->data['general_template']					= 'Change Skin';
		$this->data['general_top_page']					= 'Page Top';
		$this->data['general_archive']					= 'Archive';

		//News picture
		$this->data['newspic']							= 'News in Pictures';

		//Logo
		$this->data['logo']								= 'Advertisement';
		$this->data['logo_title']						= 'Site Name: %s&#10;Added Date: %s';

		//Search
		$this->data['search']							= 'Search';
		$this->data['search_advance']					= 'Advance Search';
		$this->data['search_result']					= '<strong>Search</strong> "%s"';
		$this->data['search_result2']					= 'Search Result';
		$this->data['search_keyword']					= 'Keyword';
		$this->data['search_date']						= 'Posted Date';
		$this->data['search_date_from']					= 'From Date';
		$this->data['search_date_to']					= 'To Date';
		$this->data['search_day']						= 'Day';
		$this->data['search_month']						= 'Month';
		$this->data['search_year']						= 'Year';
		$this->data['search_cat']						= 'Category';
		$this->data['search_cat_all']					= 'All Categories';
		$this->data['search_in']						= 'Search in';
		$this->data['search_in_all']					= 'Title and Content';
		$this->data['search_in_title']					= 'Title only';
		$this->data['search_in_content']				= 'Content only';
		$this->data['search_match_words']				= 'Search for words';
		$this->data['search_match_phrase']				= 'Search for exact phrase';
		$this->data['search_error_keyword']				= 'Please enter keyword.';

		//Button
		$this->data['button_go']						= ' Go ';
		$this->data['button_submit']					= ' Submit ';
		$this->data['button_send']						= '  Send  ';
		$this->data['button_search']					= '  Search  ';

		//FAQ
		$this->data['faqs']								= 'Faqs';

		//Logo
		$this->data['logo_error_not_exist']				= 'Website not found!';

		//RSS Export
		$this->data['rss_export_latest_articles']		= 'Latest Articles';

		//Article
		$this->data['article_focus']					= 'Focus';
		$this->data['article_hot']						= 'Hot News';
		$this->data['article_latest']					= 'Latest News';

		//Latest box
		$this->data['latest_articles']					= 'Latest News';
		$this->data['today_articles']					= 'Today News';
		$this->data['general_no_article']				= 'Not have any news.';
	}
}
?>