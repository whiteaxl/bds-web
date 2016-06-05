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
		$this->data['article_cat']						= 'Category';
		$this->data['article_next']						= 'Other Articles';
		$this->data['article_related']					= 'Related Articles';
		$this->data['article_print']					= 'Print Version';
		$this->data['article_view']						= 'View Article';
		$this->data['article_cat_view']					= 'View Category';
		$this->data['article_error_cat_not_exist']		= 'Category not found!';
		$this->data['article_error_not_exist']			= "Article not found!";

		//Rating
		$this->data['rating']							= 'Rating';
		$this->data['rating_votes']						= 'Votes';
		$this->data['rating_average']					= 'Average';
		$this->data['rating_1_desc']					= 'Very bad';
		$this->data['rating_2_desc']					= 'Bad';
		$this->data['rating_3_desc']					= 'Average';
		$this->data['rating_4_desc']					= 'Good';
		$this->data['rating_5_desc']					= 'Excellent';
		$this->data['rating_update']					= 'Rate this article?';
		$this->data['rating_success']					= 'Your vote has been updated';
		$this->data['rating_error']						= "You can't rate this article many times";

		//Comment
		$this->data['comment']							= 'Comment';
		$this->data['comments']							= 'Comments';
		$this->data['comment_new']						= 'Add Your Comment';
		$this->data['comment_name']						= 'Your Name';
		$this->data['comment_email']					= 'Your Email';
		$this->data['comment_title']					= 'Title';
		$this->data['comment_content']					= 'Comment';
		$this->data['comment_submit']					= '  Send  ';

		//Comment error
		$this->data['comment_error_flood']				= 'You can not post several comments in a short time.<br>Please wait some seconds and post it again.';
		$this->data['comment_success_add']				= 'Your comment has been sucessfully sent, but it must be aproved before displaying on website.';
	}
}

?>