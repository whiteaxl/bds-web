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
		$this->data['setting_tab_system']			= 'Hệ thống';
		$this->data['setting_tab_modules']			= 'Chuyên mục';
		$this->data['setting_tab_open_close']		= 'Mở / Đóng';

		//Website
		$this->data['setting_site_name']			= 'Tên website';
		$this->data['setting_site_slogan']			= 'Khẩu hiệu';
		$this->data['setting_site_url']				= 'Địa chỉ URL';
		$this->data['setting_site_path']			= 'Đường dẫn thư mục';
		$this->data['setting_site_keywords']		= 'Từ khóa tìm kiếm';
		$this->data['setting_site_desc']			= 'Mô tả website';
		$this->data['setting_admin_email']			= 'Email của người quản trị';
		$this->data['setting_timezone']				= 'Múi giờ';
		$this->data['setting_date_format']			= 'Đạinh dạng ngày';
		$this->data['setting_time_format']			= 'Định dạng ngày giờ';
		$this->data['setting_full_date_time_format']= 'Định dạng ngày giờ đầy đủ';

		//System
		$this->data['setting_cookie']				= 'Cookie';
		$this->data['setting_cookie_domain']		= 'Cookie Domain';
		$this->data['setting_cookie_domain_desc']	= 'Chỉ định domain để lưu cookie.<br>(Vd: .yourdomain.com)';
		$this->data['setting_cookie_path']			= 'Đường dẫn thư mục cho cookie';
		$this->data['setting_cookie_time']			= 'Thời gian hiệu lực cookie';
		$this->data['setting_cookie_time_desc']		= '(tính theo giây)';
		$this->data['setting_time_login']			= 'Thời gian đăng nhập';
		$this->data['setting_time_login_desc']		= '(Tự động thoát sau <em>n</em> giây)';
		$this->data['setting_items_per_page']		= 'Số dòng trên trang';
		$this->data['setting_items_per_page_desc']	= 'Số dòng dữ liệu trên 1 trang, trong Bảng điểu khiển của người quản trị';
		$this->data['setting_smtp']					= 'Thông tin SMTP';
		$this->data['setting_smtp_host']			= 'Máy chủ SMTP';
		$this->data['setting_smtp_username']		= 'Tên truy cập';
		$this->data['setting_smtp_password']		= 'Mật khẩu';
		$this->data['setting_ftp']					= 'Thông tin FTP (sử dụng để tải file, tạo thư mục)';
		$this->data['setting_ftp_host']				= 'Địa chỉ FTP';
		$this->data['setting_ftp_port']				= 'Cổng FTP';
		$this->data['setting_ftp_username']			= 'Tên truy cập';
		$this->data['setting_ftp_password']			= 'Mật khẩu';
		$this->data['setting_turn_on']				= 'Bật';
		$this->data['setting_turn_off']				= 'Tắt';
		$this->data['setting_hidden_desc']			= '(Không hiển thị vì lý do bảo mật)';

		//Modules
		$this->data['setting_short_url']			= 'Thu ngắn URL';
		$this->data['setting_article']				= 'Cấu hình bài viết';
		$this->data['setting_article_image']		= 'Cấu hình cho hình ảnh của bài viết';
		$this->data['setting_cache']				= 'Cache Engine';
		$this->data['setting_cache_desc']			= 'Chức năng cache giúp website được tối ưu về tốc độ, vì không phải truy xuất đến cơ sở dữ liệu mysql.';
		$this->data['setting_website_close']		= 'Đóng website';
		$this->data['setting_website_close_desc']	= 'Tạm đóng cửa website';
		$this->data['setting_website_close_message']	= 'Lời nhắn';
		$this->data['setting_cache_expire']			= 'Thời gian hiệu lực của Cache';
		$this->data['setting_cache_expire_desc']	= '(tính theo giây)';

		$this->data['setting_poll']					= 'Bình chọn';
		$this->data['setting_poll_options']			= 'Số dòng lựa chọn khi nhập Bình chọn';
		$this->data['setting_revote_time']			= 'Thời gian được bình chọn lại lần 2';
		$this->data['setting_revote_time_desc']		= 'Cho phép người dùng được bình chọn lại sau khoảng thời gian nhất định.';

		$this->data['setting_latest_box']			= 'Ô bài viết mới nhất';
		$this->data['setting_latest_box_type']		= 'Kiểu hiện thỉ';
		$this->data['setting_latest_box_items']	= 'Số bài viết hiển thị';
		$this->data['setting_latest_articles']		= 'Tin bài mới nhất';
		$this->data['setting_today_articles']		= 'Tin bài trong ngày';
		$this->data['setting_not_display']			= 'Không hiển thị';

		$this->data['setting_other_boxes']			= 'Khác';
		$this->data['setting_newsletter']			= 'Bản tin định kỳ';
		$this->data['setting_event']				= 'Sự kiện';
		$this->data['setting_newspic']				= 'Tin qua ảnh';

//		$this->data['setting_gzip']					= 'Enable Gzip';
//		$this->data['setting_gzip_desc']			= 'All pages will be compressed to save bandwidth for your site. However, it will increase server load.';
//		$this->data['setting_gzip_level']			= 'Gzip Level';
//		$this->data['setting_gzip_level_desc']		= 'Set the level of compression for gzipping your pages, higher values will increase server load drimatically.';
		$this->data['setting_news_per_page']		= 'Số tin / trang';
		$this->data['setting_news_per_page_desc']	= 'Số tin được hiển thị trên 1 trang trong trang tin tức';
		$this->data['setting_image_type']			= 'Kiểu hình ảnh';
		$this->data['setting_image_type_desc']		= '(Giới hạn các kiểu hình được tải lên website. Sử dụng dấu "," để phân cách nhiều kiểu file, hoặc bỏ trống nếu muốn không giới hạn.)';
		$this->data['setting_image_max_size']		= 'Kích thước hình tối đa';
		$this->data['setting_image_max_size_desc']	= '(Kích thước hình tối đa được phép tải lên website. Bỏ trống nếu muốn không giới hạn.)';
		$this->data['setting_log']					= 'Nhật ký quản trị';
		$this->data['setting_log_days']				= 'Giới hạn ngày';
		$this->data['setting_log_days_desc']		= 'Nhập số 0 nếu muốn không giới hạn';
		$this->data['setting_rating']				= 'Đánh giá bài viết';
		$this->data['setting_rating_desc']			= 'Người xem có thể đánh giá bài viết từ 1 đến 5 sao.';
		$this->data['setting_comment']				= 'Bình luận cho bài viết';
		$this->data['setting_comment_desc']			= 'Người xem có thể gửi bình luận khi xem tin.';
		$this->data['setting_wysiwyg_title']		= 'Sử dụng trình soạn thảo cho tiêu đề?';
		$this->data['setting_wysiwyg_title_desc']	= 'Người sử dụng có thể định dạng cỡ chữ, màu sắc,... cho tiêu đề.';
		$this->data['setting_short_url_desc']		= 'Chỉ có tác dụng đối với web server Apache (và mod Rewrite được bật)';
		$this->data['setting_short_url_sep']				= 'Dấu phân cách URL';
		$this->data['setting_short_url_sep_desc']			= 'Yêu cầu chức năng rút gôn địa chỉ URL phải được bật.';
		$this->data['setting_menucat_level']		= 'Số cấp hiển thị menu các phân nhóm ngoài trang chính';
		$this->data['setting_archived_default']		= 'ACP - Danh sách bài viết';

		$this->data['setting_home_focus_limit']		= 'Trang chủ - Tiêu điểm';
		$this->data['setting_home_focus_cols']		= 'Trang chủ - Số cột hiển thị tin nóng';
		$this->data['setting_home_hot_limit']		= 'Trang chủ - Tin nóng';
		$this->data['setting_home_latest_limit']	= 'Trang chủ - Tin mới nhất';
		$this->data['setting_home_cat_cols']			= 'Trang chủ - Số cột hiển thị phân nhóm';
		$this->data['setting_home_cat_article_limit']	= 'Trang chủ - Số tin mới trong mỗi phân nhóm';

		$this->data['setting_limit_home_news']		= 'Giới hạn số tin trên 1 trang';
		$this->data['setting_limit_home_news_next']	= 'Giới hạ số tin kế tiếp';
		$this->data['setting_limit_home_hot']		= 'Giới hạn số tin nóng';
		$this->data['setting_limit_home_comment']	= 'Giới hạn số bình luận';
		$this->data['setting_newspic_rand_limit']	= 'Giới hạn số tin qua ảnh';
		$this->data['setting_newspic_rand_time']	= 'Thời gian hiển thi cho một Tin qua ảnh';
		$this->data['setting_thumb_large']			= 'Hình thu nhỏ - cỡ vừa';
		$this->data['setting_thumb_small']			= 'Hình thu nhỏ - cỡ nhỏ';
		$this->data['setting_thumb_icon']			= 'Hình thu nhỏ - cỡ rất nhỏ';
		$this->data['setting_newspic_thumb']		= 'Hình thu nhỏ của Tin qua ảnh';
		$this->data['setting_newspic_full']			= 'Hình phóng to của Tin qua ảnh';
		$this->data['setting_max_width']			= 'Chiều ngang tối đa';
		$this->data['setting_max_height']			= 'Chiều cao tối đa';

		$this->data['setting_error_template']		= 'Không tìm thấy thư mục chứa giao diện!';
		$this->data['setting_error_language']		= 'Không tìm thấy thư mục chứa ngôn ngữ!';
	}
}

?>