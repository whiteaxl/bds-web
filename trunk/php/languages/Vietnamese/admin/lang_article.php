<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Article;

class Lang_Module_Article
{
	var $data		= array();

	function Lang_Module_Article(){
		//Article
		$this->data['article']						= 'Bài viết';
		$this->data['articles']						= 'Bài viết';
		$this->data['article_id']					= 'ID Bài viết';
		$this->data['article_title']				= 'Tiêu đề';
		$this->data['article_content_desc']			= 'Mô tả ngắn';
		$this->data['article_content_detail']		= 'Chi tiết';
		$this->data['article_content_url']			= 'URL nội dung chi tiết';
		$this->data['article_author']				= 'Tác giả';
		$this->data['article_post_time']			= 'Ngày đăng';
		$this->data['article_post_time_tip']		= "Bạn có thể chọn ngày giờ ở một thời điểm trong tương lai";
		$this->data['article_ishot']				= 'Bài viết nổi bật';
		$this->data['article_ishot_tip']			= 'Bài viết nổi bật sẽ được hiển thị trên cùng của các trang';
		$this->data['article_type']					= 'Kiểu bài viết';
		$this->data['article_type_full']			= 'Đầy đủ';
		$this->data['article_type_summary']			= 'Tóm tắt';
		$this->data['article_type_link']			= 'Tóm tắt kèm liên kết';
		$this->data['article_type_tip']				= '* Bài viết đầy đủ sẽ được hiển thị với trang xem chi tiết.<br>* Bài viết tóm tắt được hiển thị mà không có trang xem chi tiết.<br>* Bài viết Tóm tắt kèm liên kết sẽ được hiển thị với liên kết dẫn đến trang web khác.';
		$this->data['article_poster']				= 'Người gửi';
		$this->data['article_hits']					= 'Lần xem';
		$this->data['article_normal']				= 'Bình thường';
		$this->data['article_hot']					= 'Nổi bật';
		$this->data['article_event']				= 'Sự kiện';
		$this->data['article_move']					= 'Di chuyển bài viết';
		$this->data['article_move_cat']				= 'Chuyển đến phân nhóm';
		$this->data['article_move_topic']			= 'Chuyển đến chủ đề';
		$this->data['article_del_confirm']			= 'Xóa các bài viết đã chọn?';
		$this->data['article_page_del_confirm']		= 'Xóa các trang đã chọn?';
		$this->data['article_page_order']			= 'Số thứ tự';

		//Article Cats
		$this->data['article_cat_code']				= 'Tên viết tắt';
		$this->data['article_cat_code_tip']			= 'Tên viết tắt sẽ được hiển thị trên thanh địa chỉ. Vd: tintuc/<strong>xahoi</strong>/';
		$this->data['article_cat_template']			= 'Giao diện';
		$this->data['article_cat_template_tip']		= 'Bạn có thể chỉ định mỗi phân nhóm hiển thị với giao diện khác nhau.';
		$this->data['article_cat_del_childs']		= 'Xóa các phân nhóm con';
		$this->data['article_cat_del_article']		= 'Xóa bài viết';
		$this->data['article_cat_move']				= 'Di chuyển bài viết';
		$this->data['article_cat_source']			= 'Phân nhóm nguồn';
		$this->data['article_cat_dest']				= 'Phân nhóm đích';
		$this->data['article_cat_move_to']			= 'Chuyển đến phân nhóm';
		$this->data['article_cat_index_display']	= 'Hiển thị trên trang chủ';
		$this->data['article_cat_index_display_tip']= 'Hiển thị hoặc ẩn phân nhóm này trên trang chủ (trong cột giữa)';

		//Article Topic
		$this->data['article_topic']				= 'Chủ đề';
		$this->data['article_topic_tip']			= 'Được sử dụng để gom nhóm các bài viết liên quan. Khi người sử dụng xem một tin bài, các bài viết liên quan sẽ được hiển thị ngay bên dưới, giùp người sử dụng nhanh chóng xem được các tin cần thiết.';
		$this->data['article_topic_search']			= 'hoặc nhập ID của Chủ đề';
		$this->data['article_topic_title']			= 'Tiêu đề';
		$this->data['article_topic_desc']			= 'Mô tả';
		$this->data['article_topic_move']			= 'Di chuyển bài viết';
		$this->data['article_topic_remove']			= '(Đánh dấu bài viết để xóa khỏi chủ đề)';
		$this->data['article_topic_source']			= 'Chủ đề nguồn';
		$this->data['article_topic_dest']			= 'Chủ đề đích';
		$this->data['article_topic_move_to']		= 'Chuyển đến chủ đề';
		$this->data['article_topic_del_confirm']	= 'Bạn có chắc xóa các chủ đề đã chọn?';

		//Article errors
		$this->data['article_success_move']			= 'Di chuyển bài viết thành công!';
		$this->data['article_error_not_exist']		= 'Không tìm thấy bài viết này!';
		$this->data['article_error_not_check']		= 'Vui lòng chọn bài viết!';
		$this->data['article_error_page_not_exist']	= 'Không tìm thấy trang này!';
		$this->data['article_error_page_not_check']	= 'Vui lòng chọn trang!';
		$this->data['article_error_page_order_exist']	= 'Đã có trang này. Vui lòng chọn trang khác.';
		$this->data['article_error_cat_source']		= 'Vui lòng chọn phân nhóm nguồn và phân nhóm đích.';
		$this->data['article_error_cat_not_exist']	= 'Không tìm thấy phân nhóm này!';
		$this->data['article_error_cat_exist']		= 'Đã có Tên viết tắt này! Vui lòng chọn tên khác.';
		$this->data['article_error_topic_not_exist']= 'Không tìm thấy chủ đề này!';
		$this->data['article_error_topic_exist']	= 'Đã có chủ đề này. Vui lòng chọn tên khác.';
		$this->data['article_error_topic_not_check']= 'Vui lòng chọn chủ đề!';
		$this->data['article_error_topic_source']	= 'Vui lòng chọn chủ đề nguồn và chủ đề đích.';

		//Article Comment
		$this->data['comment']						= 'Bình luận';
		$this->data['comment_title']				= 'Tiêu đề';
		$this->data['comment_content']				= 'Nội dung';
		$this->data['comment_author']				= 'Tác giả';
		$this->data['comment_name']					= 'Tên';
		$this->data['comment_email']				= 'Email';
		$this->data['comment_del_confirm']			= 'Bạn có chắc xóa các bình luận đã chọn?';
		$this->data['comment_error_not_check']		= 'Vui lòng chọn các bình luận!';
		$this->data['comment_error_not_exist']		= 'Không tìm thấy bình luận này!';

		return $this->data;
	}
}

?>