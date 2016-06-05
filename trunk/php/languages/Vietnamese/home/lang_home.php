<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

class Lang_Home extends Lang_Global
{
	var $data		= array();

	function Lang_Home(){
		//General
		$this->data['general_home']						= 'Trang chủ';
		$this->data['general_all_news']					= 'Tất cả tin bài';
		$this->data['general_archive']					= 'Lưu trữ';
		$this->data['general_back']						= 'Quay lại';
		$this->data['general_event']					= 'Sự kiện';
		$this->data['general_faq']						= 'Hỏi đáp';
		$this->data['general_weblink']					= 'Liên kết';
		$this->data['general_sitemap']					= 'Sơ đồ web';
		$this->data['general_rss']						= 'RSS';
		$this->data['general_about']					= 'Giới thiệu';
		$this->data['general_last_update']				= 'Lần cập nhật mới nhất: %s (GMT%s)';
		$this->data['general_today']					= 'Hôm nay: %s (GMT%s)';
		$this->data['general_contact']					= 'Liên hệ';
		$this->data['general_print']					= 'Bản in';
		$this->data['general_tellfriend']				= 'Gửi bạn bè';
		$this->data['general_notify']					= 'Thông báo';
		$this->data['general_contact_us']				= 'Liên hệ';
		$this->data['general_favorite_add']				= 'Thêm vào Favorites';
		$this->data['general_view_detail']				= 'Xem chi tiết';
		$this->data['general_gmt']						= 'GMT';
		$this->data['general_close_window']				= 'Đóng';
		$this->data['general_expand_all']				= 'Mở tất cả';
		$this->data['general_collapse_all']				= 'Đóng tất cả';
		$this->data['general_template']					= 'Đổi giao diện';
		$this->data['general_top_page']					= 'Đầu trang';
		$this->data['general_archive']					= 'Lưu trữ';

		//News picture
		$this->data['newspic']							= 'Tin qua ảnh';

		//Logo
		$this->data['logo']								= 'Quảng cáo';
		$this->data['logo_title']						= 'Tên Website: %s&#10;Ngày cập nhật: %s';

		//Search
		$this->data['search']							= 'Tìm kiếm';
		$this->data['search_advance']					= 'Tìm chi tiết';
		$this->data['search_result']					= '<strong>Tìm kiếm</strong> "%s"';
		$this->data['search_result2']					= 'Kết quả tìm kiếm';
		$this->data['search_keyword']					= 'Từ khóa';
		$this->data['search_date']						= 'Ngày đăng';
		$this->data['search_date_from']					= 'Từ ngày';
		$this->data['search_date_to']					= 'Đến ngày';
		$this->data['search_day']						= 'Ngày';
		$this->data['search_month']						= 'Tháng';
		$this->data['search_year']						= 'Năm';
		$this->data['search_cat']						= 'Chuyên mục';
		$this->data['search_cat_all']					= 'Tất cả chuyên mục';
		$this->data['search_in']						= 'Tìm trong';
		$this->data['search_in_all']					= 'Tiêu đề và Nội dung';
		$this->data['search_in_title']					= 'Tiêu đề';
		$this->data['search_in_content']				= 'Nội dung';
		$this->data['search_match_words']				= 'Tìm tương đối';
		$this->data['search_match_phrase']				= 'Tìm chính xác';
		$this->data['search_error_keyword']				= 'Vui lòng nhập từ khóa cần tìm.';

		//Button
		$this->data['button_go']						= ' Chuyển ';
		$this->data['button_submit']					= ' Cập nhật ';
		$this->data['button_send']						= '  Gửi  ';
		$this->data['button_search']					= '  Tìm  ';

		//FAQ
		$this->data['faqs']								= 'Hỏi đáp';

		//Logo
		$this->data['logo_error_not_exist']				= 'Không tìm thấy website này!';

		//RSS Export
		$this->data['rss_export_latest_articles']		= 'Tin bài mới nhất';

		//Article
		$this->data['article_hot']						= 'Tin nóng';
		$this->data['article_focus']					= 'Tiêu điểm';

		//Latest box
		$this->data['latest_articles']					= 'Các tin mới nhất';
		$this->data['today_articles']					= 'Các tin trong ngày';
		$this->data['general_no_article']				= 'Không tìm thấy bài viết nào.';
	}
}
?>