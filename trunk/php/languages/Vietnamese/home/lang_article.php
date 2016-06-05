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
		$this->data['article_cat']						= 'Phân nhóm';
		$this->data['article_next']						= 'Các tin khác';
		$this->data['article_related']					= 'Tin bài liên quan';
		$this->data['article_print']					= 'Bản in';
		$this->data['article_view']						= 'Xem tin';
		$this->data['article_cat_view']					= 'Xem phân nhóm';
		$this->data['article_error_cat_not_exist']		= 'Không tìm thấy phân nhóm này!';
		$this->data['article_error_not_exist']			= "Không tìm thấy bài viết này!";

		//Rating
		$this->data['rating']							= 'Đánh giá';
		$this->data['rating_votes']						= 'Lượt bình chọn';
		$this->data['rating_average']					= 'Kết quả';
		$this->data['rating_1_desc']					= 'Rất tệ';
		$this->data['rating_2_desc']					= 'Tệ';
		$this->data['rating_3_desc']					= 'Trung bình';
		$this->data['rating_4_desc']					= 'Tốt';
		$this->data['rating_5_desc']					= 'Xuất sắc';
		$this->data['rating_update']					= 'Đánh giá bài viết này?';
		$this->data['rating_success']					= 'Phiếu đánh giá của bạn đã được cập nhật';
		$this->data['rating_error']						= "Bạn không thể đánh giá nhiều lần cho một bài viết";

		//Comment
		$this->data['comment']							= 'Bình luận';
		$this->data['comments']							= 'Bình luận';
		$this->data['comment_new']						= 'Thêm ý kiến của bạn';
		$this->data['comment_name']						= 'Họ tên';
		$this->data['comment_email']					= 'Địa chỉ email';
		$this->data['comment_title']					= 'Tiêu đề';
		$this->data['comment_content']					= 'Nội dung';
		$this->data['comment_submit']					= '  Gửi  ';

		//Comment error
		$this->data['comment_error_flood']				= 'Quý khách không thể gửi ý kiến một cách liên tục trong thời gian ngắn.<br>.Xin vui lòng đợi giây lát và gửi lại lần nữa.';
		$this->data['comment_success_add']				= 'Ý kiến của quý khách đã được gửi đến chúng tôi thành công.<br>Tuy nhiên, ý kiến này phải đợi Ban quản trị kiểm duyệt mới được phép hiển thị trên website.';
	}
}

?>