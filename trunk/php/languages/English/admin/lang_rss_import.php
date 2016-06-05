<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_RSSImport;

class Lang_Module_RSSImport
{
	var $data		= array();

	function Lang_Module_RSSImport(){
		//RSS Import
		$this->data['rss_import']						= 'RSS Import Stream';
		$this->data['rss_imports']						= 'RSS Import Streams';
		$this->data['rss_import_feed']					= 'Import RSS Feeds';
		$this->data['rss_import_remove_feed']			= 'Remove RSS Feeds';
		$this->data['rss_import_basics']				= 'RSS Import Basics';
		$this->data['rss_import_title']					= 'RSS Stream Title';
		$this->data['rss_import_url']					= 'RSS Stream URL';
		$this->data['rss_import_url_desc']				= 'This must be an RDF or RSS feed';
		$this->data['rss_import_pergo']					= 'RSS Import Per Go';
		$this->data['rss_import_pergo_desc']			= 'Imports n articles per update. Importing is moderately resource intensive. This number is only used with auto import url.';
		$this->data['rss_import_convert_charset']		= 'Convert charset';
		$this->data['rss_import_convert_charset_desc']	= 'Automatically convert content from source charset to your charset';
		$this->data['rss_import_status']				= 'RSS Stream Status';
		$this->data['rss_import_auth']					= 'RSS Import htaccess Authentication';
		$this->data['rss_import_require_auth']			= 'Require htaccess Authentication';
		$this->data['rss_import_require_auth_desc']		= 'Most streams do not require authentication';
		$this->data['rss_import_auth_user']				= 'htaccess Username';
		$this->data['rss_import_auth_pass']				= 'htaccess Password';
		$this->data['rss_import_contents']				= 'RSS Import Contents';
		$this->data['rss_import_cat']					= 'Import Into Category';
		$this->data['rss_import_cat_desc']				= 'Choose a category to import each RSS item as a new article';
		$this->data['rss_import_article_type']			= 'Article Type';
		$this->data['rss_import_article_type_full']		= 'Full';
		$this->data['rss_import_article_type_summary']	= 'Summary';
		$this->data['rss_import_article_type_link']		= 'Summary & Link';
		$this->data['rss_import_article_type_tip']		= '* Full Article: be displayed with detail pages.<br>* Summary Article: be displayed without detail page.<br>* Summary & Link Article: be displayed with a link to URL.';
		$this->data['rss_import_remove_html']			= 'Remove HTML Tags';
		$this->data['rss_import_remove_html_desc']		= 'If yes, all html tags will be removed';
		$this->data['rss_import_show_link']				= 'Link to the source article';
		$this->data['rss_import_show_link_desc']		= 'If yes, users will click on the title to open and view the source article';
		$this->data['rss_import_author']				= 'Article Author Name';
		$this->data['rss_import_author_desc']			= 'The author name for all RSS items';
		$this->data['rss_import_username']				= 'Poster\'s Username';
		$this->data['rss_import_username_desc']			= 'This will post the RSS article under this person\'s account (member username)';
		$this->data['rss_import_userpost_increase']		= 'Increase Poster\'s Post Count';
		$this->data['rss_import_userpost_increase_desc']= 'This will increment this poster\'s article count';
		$this->data['rss_import_article_status']		= 'Article Status';
		$this->data['rss_import_article_status_desc']	= 'If "Enabled" the article will be posted as visible. "Disabled", article will be invisible.';
		$this->data['rss_import_del_confirm']			= 'Delete checked streams?';
		$this->data['rss_import_streams']				= 'RSS Streams';
		$this->data['rss_import_desc']					= 'Choose the streams for importing articles.';
		$this->data['rss_import_remove_desc']			= 'Choose the streams for removing articles.';
		$this->data['rss_import_articles']				= 'Articles';
		$this->data['rss_import_last_date']				= 'Last import';
		$this->data['rss_import_auto']					= 'Auto import URL';

		$this->data['rss_import_remove_articles']		= 'Remove Articles';
		$this->data['rss_import_remove_streams']		= 'Remove articles from the streams';
		$this->data['rss_import_remove_number']			= 'Remove the last <em>n</em> articles';
		$this->data['rss_import_remove_number_desc']	= 'Remove the last articles from every selected stream. Leave blank to remove them all.';
		$this->data['rss_import_import_articles']		= 'Import Articles';
		$this->data['rss_import_import_streams']		= 'Import articles from the streams';
		$this->data['rss_import_import_number']			= 'Import <em>n</em> articles';
		$this->data['rss_import_import_number_desc']	= 'Import <em>n</em> articles from every selected stream. Leave blank to import them all.';

		//Success
		$this->data['rss_import_success']				= 'Imported %d article(s)!';
		$this->data['rss_import_success_remove']		= 'Removed %d article(s)!';

		//RSS Import errors
		$this->data['rss_import_error_none']			= 'There is not any rss stream!';
		$this->data['rss_import_error_not_check']		= 'Please check streams!';
	}
}

?>