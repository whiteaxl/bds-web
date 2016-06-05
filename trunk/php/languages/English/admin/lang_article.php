<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Article;

class Lang_Module_Article
{
	var $data		= array();

	function Lang_Module_Article(){
		//Articles
		$this->data['article']						= 'Article';
		$this->data['articles']						= 'Articles';
		$this->data['article_id']					= 'Article ID';
		$this->data['article_title']				= 'Title';
		$this->data['article_content_desc']			= 'Description';
		$this->data['article_content_detail']		= 'Content';
		$this->data['article_content_url']			= 'Content URL';
		$this->data['article_author']				= 'Author';
		$this->data['article_post_time']			= 'Post Time';
		$this->data['article_post_time_tip']		= "You can choose a future date. This article won't be displayed until that time.";
		$this->data['article_ishot']				= 'Hot Article';
		$this->data['article_ishot_tip']			= 'Hot article: be displayed at the top of pages (in center column)';
		$this->data['article_type']					= 'Article Type';
		$this->data['article_type_full']			= 'Full Content';
		$this->data['article_type_summary']			= 'Summary';
		$this->data['article_type_link']			= 'Summary & Link';
		$this->data['article_type_tip']				= '* Full Article: be displayed with detail pages.<br>* Summary Article: be displayed without detail page.<br>* Summary & Link Article: be displayed with a link to remote URL.';
		$this->data['article_poster']				= 'Poster';
		$this->data['article_hits']					= 'Hits';
		$this->data['article_normal']				= 'Normal';
		$this->data['article_hot']					= 'Hot';
		$this->data['article_event']				= 'Event';
		$this->data['article_move']					= 'Move Articles';
		$this->data['article_move_cat']				= 'Move to Category';
		$this->data['article_move_topic']			= 'Move to Topic';
		$this->data['article_del_confirm']			= 'Delete checked articles?';
		$this->data['article_page_del_confirm']		= 'Delete checked pages?';
		$this->data['article_page_order']			= 'Page Order';

		//Article Cats
		$this->data['article_cat_code']				= 'Short name';
		$this->data['article_cat_code_tip']			= 'This short name will be displayed on the URL. Ex: article/<strong>world</strong>/';
		$this->data['article_cat_template']			= 'Template';
		$this->data['article_cat_template_tip']		= 'You can choose different templates for different categories.';
		$this->data['article_cat_del_childs']		= 'Delete sub categories';
		$this->data['article_cat_del_articles']		= 'Delete articles';
		$this->data['article_cat_move']				= 'Move articles';
		$this->data['article_cat_source']			= 'Source categories';
		$this->data['article_cat_dest']				= 'Destination category';
		$this->data['article_cat_move_to']			= 'Move to Category';
		$this->data['article_cat_index_display']	= 'Displaying on Index Page';
		$this->data['article_cat_index_display_tip']= 'Display or hide this category on index page (center column)';

		//Article Topic
		$this->data['article_topic']				= 'Topic';
		$this->data['article_topic_tip']			= 'Use topic to group all related articles. When users view one article, they can view quickly all related articles.';
		$this->data['article_topic_search']			= 'or Topic ID';
		$this->data['article_topic_title']			= 'Title';
		$this->data['article_topic_desc']			= 'Description';
		$this->data['article_topic_move']			= 'Move articles';
		$this->data['article_topic_remove']			= '(Check to remove from topic)';
		$this->data['article_topic_source']			= 'Source Topics';
		$this->data['article_topic_dest']			= 'Destination Topic';
		$this->data['article_topic_move_to']		= 'Move to Topic';
		$this->data['article_topic_del_confirm']	= 'Are you sure to delete checked topics?';

		//Article errors
		$this->data['article_success_move']			= 'Move news successfully!';
		$this->data['article_error_not_exist']		= 'Article not found!';
		$this->data['article_error_not_check']		= 'Please check articles!';
		$this->data['article_error_page_not_exist']	= 'Page not found!';
		$this->data['article_error_page_not_check']	= 'Please check pages!';
		$this->data['article_error_page_order_exist']	= 'This page order exists. Please choose another one.';
		$this->data['article_error_cat_source']		= 'Please choose source and destination categories.';
		$this->data['article_error_cat_not_exist']	= 'Category not found!';
		$this->data['article_error_cat_exist']		= 'Category short name found! Please choose another name.';
		$this->data['article_error_topic_not_exist']= 'Topic not found!';
		$this->data['article_error_topic_exist']	= 'This topic title exists! Please choose another one.';
		$this->data['article_error_topic_not_check']= 'Please check topics!';
		$this->data['article_error_topic_source']	= 'Please choose source and destination topics.';

		//Article Comment
		$this->data['comment']						= 'Comment';
		$this->data['comment_title']				= 'Title';
		$this->data['comment_content']				= 'Content';
		$this->data['comment_author']				= 'Author';
		$this->data['comment_name']					= 'Name';
		$this->data['comment_email']				= 'Email';
		$this->data['comment_del_confirm']			= 'Are you sure to delete checked comment?';
		$this->data['comment_error_not_check']		= 'Please check comments!';
		$this->data['comment_error_not_exist']		= 'Comment not found!';

		return $this->data;
	}
}

?>