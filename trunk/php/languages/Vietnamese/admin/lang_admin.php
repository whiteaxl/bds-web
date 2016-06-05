<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

class Lang_Admin extends Lang_Global
{
	var $data		= array();

	function Lang_Admin(){
		$this->data['menu_general']					= 'Tổng quát';
		$this->data['menu_general_login']			= 'Đăng nhập';
		$this->data['menu_general_logout']			= 'Thoát';
		//Menu article
		$this->data['menu_article']					= 'Tin tức';
		$this->data['menu_article_cat']				= 'Phân nhóm';
		$this->data['menu_article_article']			= 'Tin bài';
		$this->data['menu_article_page']			= 'Trang';
		$this->data['menu_article_topic']			= 'Chủ đề';
		$this->data['menu_article_comment']			= 'Bình luận';
		$this->data['menu_article_picture']			= 'Tin qua ảnh';
		//Menu RSS
		$this->data['menu_rss']						= 'Dữ liệu RSS';
		$this->data['menu_rss_export']				= 'Xuất RSS';
		$this->data['menu_rss_import']				= 'Nhập RSS';
		//Menu user
		$this->data['menu_user']					= 'Người sử dụng';
		$this->data['menu_user_group']				= 'Phân nhóm';
		$this->data['menu_user_user']				= 'Tài khoản';
		$this->data['menu_user_field']				= 'Thông tin hồ sơ';
		//Menu newsletter
		$this->data['menu_newslt']					= 'Bản tin định kỳ';
		$this->data['menu_newslt_cat']				= 'Phân nhóm';
		$this->data['menu_newslt_email']			= 'Emails';
		$this->data['menu_newslt_issue']			= 'Bản tin';
		//Menu miscellaneous
		$this->data['menu_miscell']					= 'Tổng hợp';
		$this->data['menu_miscell_event']			= 'Sự kiện';
		$this->data['menu_miscell_poll']			= 'Bình chọn';
		$this->data['menu_miscell_faq']				= 'Hỏi đáp';
		$this->data['menu_miscell_logo']			= 'Logo quảng cáo';
		//Menu weblink
		$this->data['menu_weblink']					= 'Liên kết website';
		$this->data['menu_weblink_cat']				= 'Phân nhóm';
		$this->data['menu_weblink_weblink']			= 'Liên kết';
		//Menu admin
		$this->data['menu_admin']					= 'Hệ thống';
		$this->data['menu_admin_setting']			= 'Cấu hình';
		$this->data['menu_admin_cache']				= 'Cache dữ liệu';
		$this->data['menu_admin_system']			= 'Hệ thống';
		$this->data['menu_admin_log']				= 'Nhật ký sử dụng';
		$this->data['menu_admin_stat']				= 'Thống kê';
		//Menu database
		$this->data['menu_db']						= 'Cơ sở dữ liệu';
		$this->data['menu_db_info']					= 'Thông tin Database';
		$this->data['menu_db_backup']				= 'Sao lưu dữ liệu';
		$this->data['menu_db_restore']				= 'Phục hồi dữ liệu';
		$this->data['menu_db_runtime']				= 'Thời gian thực thi';
		$this->data['menu_db_system']				= 'Thông số hệ thống';
		$this->data['menu_db_process']				= 'Tiến trình hoạt động';
		//Menu private
		$this->data['menu_private']					= 'Cá nhân';
		$this->data['menu_private_profile']			= 'Sửa thông tin';
		$this->data['menu_private_changepass']		= 'Đổi mật khẩu';
		$this->data['menu_private_login']			= 'Đăng nhập';
		$this->data['menu_private_logout']			= 'Thoát';

		//General
		$this->data['general_admin_control']		= 'Khu Vực Quản Trị';
		$this->data['general_acp_home']				= 'Trang quản trị';
		$this->data['general_online_document']		= 'Tài liệu';
		$this->data['general_username']				= 'Tên truy cập';
		$this->data['general_password']				= 'Mật khẩu';
		$this->data['general_remember']				= 'Ghi nhớ';
		$this->data['general_login']				= 'Đăng nhập';
		$this->data['general_logout']				= 'Thoát';
		$this->data['general_view']					= 'Xem';
		$this->data['general_preview']				= 'Tổng quan';
		$this->data['general_online']				= 'Các tài khoản đang sử dụng';
		$this->data['general_ip']					= 'IP';
		$this->data['general_add']					= 'Thêm mới';
		$this->data['general_edit']					= 'Sửa';
		$this->data['general_del']					= 'Xóa';
		$this->data['general_archive']				= 'Lưu trữ';
		$this->data['general_unarchive']			= 'Hủy lưu trữ';
		$this->data['general_archived']				= 'Đã lưu trữ';
		$this->data['general_unarchived']			= 'Chưa lưu trữ';
		$this->data['general_move']					= 'Di chuyển';
		$this->data['general_update']				= 'Cập nhật';
		$this->data['general_resync']				= 'Đồng bộ';
		$this->data['general_enable']				= 'Bật';
		$this->data['general_disable']				= 'Tắt';
		$this->data['general_enabled']				= 'Đã bật';
		$this->data['general_disabled']				= 'Đã tắt';
		$this->data['general_appending']			= 'Chưa duyệt';
		$this->data['general_checkall']				= 'Chọn tất cả';
		$this->data['general_uncheckall']			= 'Bỏ chọn tất cả';
		$this->data['general_default']				= 'Mặc định';
		$this->data['general_confirmation']			= 'Xác nhận';
		$this->data['general_home_page']			= 'Trang chủ';
		$this->data['general_cat']					= 'Phân nhóm';
		$this->data['general_cat_tip']				= 'Chọn phân nhóm hiển thị các tin ảnh';
		$this->data['general_author']				= 'Tác giả';
		$this->data['general_hits']					= 'Lần xem';
		$this->data['general_visitors']				= 'Lượt truy cập';
		$this->data['general_order']				= '#';
		$this->data['general_search_order']			= 'Thứ tự';
		$this->data['general_posted_date']			= 'Ngày đăng';
		$this->data['general_contact']				= 'Liên hệ';
		$this->data['general_import']				= 'Nhập';
		$this->data['general_export']				= 'Xuất';
		$this->data['general_remove']				= 'Xóa';
		$this->data['general_admin_function']		= 'Chức năng quản trị';
		$this->data['general_permission']			= 'Quyền hạn';
		$this->data['general_page']					= 'Trang';
		$this->data['general_page_to']				= 'Chuyển đến trang';
		$this->data['general_page_list']			= 'Trang danh sách';
		$this->data['general_page_add']				= 'Trang nhập mới';
		$this->data['general_page_counter']			= 'Số trang';
		$this->data['general_page_desc']			= 'Nhấn vào đây để quản lý các trang của tin bài này';
		$this->data['general_comment_desc']			= 'Nhấn vào đây để tìm các bình luận của tin bài này';
		$this->data['general_view_desc']			= 'Nhấn vào đây để xem chi tiết';
		$this->data['general_copy']					= 'Sao chép thành phiên bản mới';
		$this->data['general_save']					= 'Lưu';
		$this->data['general_save_as']				= 'Lưu với tên khác';
		$this->data['general_status']				= 'Tình trạng';
		$this->data['general_title']				= 'Tiêu đề';
		$this->data['general_date']					= 'Ngày tháng';
		$this->data['general_day']					= 'Ngày';
		$this->data['general_month']				= 'Tháng';
		$this->data['general_year']					= 'Năm';
		$this->data['general_addded_date']			= 'Ngày nhập';
		$this->data['general_post_time']			= 'Thời gian đăng';
		$this->data['general_post_time_tip']		= "Bạn có thể chọn ngày giờ bắt đầu hiển thị tin";
		$this->data['general_time']					= 'Thời gian';
		$this->data['general_time_to']				= 'Đến';
		$this->data['general_hour']					= 'Giờ';
		$this->data['general_minute']				= 'Phút';
		$this->data['general_time_desc']			= 'Sử dụng định dạng 24 giờ: hh:mm';		
		$this->data['general_timezone']				= 'Múi giờ';
		$this->data['general_arrow']				= ' &raquo; ';
		$this->data['general_del_confirm']			= 'Bạn có chắc xóa?';
		$this->data['general_asc']					= 'Tăng';
		$this->data['general_desc']					= 'Giảm';
		$this->data['general_help']					= 'Giúp đỡ';
		$this->data['general_close_window']			= 'Đóng';
		$this->data['general_never']				= 'Không  bao giờ';
		$this->data['general_start_date']			= 'Ngày bắt đầu';
		$this->data['general_end_date']				= 'Ngày kết thúc';
		$this->data['general_past_future']			= 'Ngày tự chọn';
		$this->data['general_expired']				= 'Đã hết hạn';
		$this->data['general_waiting']				= 'Đợi hiển thị';
		$this->data['general_running']				= 'Đang chạy';
		$this->data['general_showing']				= 'Đang hiển thị';
		$this->data['general_user_ip']				= '%s (IP: %s)';
		$this->data['general_show_page']			= 'Trang';
		$this->data['general_normal']				= 'Chế độ bình thường';
		$this->data['general_expand']				= 'Chế độ mở rộng';
		$this->data['general_expand_all']			= 'Mở tất cả';
		$this->data['general_collapse_all']			= 'Đóng tất cả';
		$this->data['general_show_panel']			= 'Hiển thị menu';
		$this->data['general_hide_panel']			= 'Che giấu menu';
		$this->data['general_forgot_pass']			= 'Quên mật khẩu';
		$this->data['general_send_pass_subject']	= 'Khởi tạo mật khẩu mới';
		$this->data['general_send_pass_content']	= 'Xin chào {USERNAME},<br><br>Bạn vừa yêu cầu khởi tạo mật khẩu mới trên website của chùng tôi ({SITE_URL}). Vui lòng nhấn vào liên kết bên dưới để hoàn tất:<br><br>{U_CONFIRM}<br><br>{SITE_NAME}<br>{SITE_URL}';
		$this->data['general_send_pass_content_2']	= 'Xin chào {USERNAME},<br><br>Bạn vừa yêu cầu khởi tạo mật khẩu mới trên website của chùng tôi ({SITE_URL}). Và đây là mật khẩu mới của bạn: {NEW_PASS}<br>Hiện tại bạn đã có thể sử dụng mật khẩu này để đăng nhập.<br><br>{SITE_NAME}<br>{SITE_URL}';
		$this->data['general_rss']					= 'RSS';
		$this->data['general_rss_publish']			= 'Xuất bản RSS';
		$this->data['general_cat']					= 'Phân nhóm';
		$this->data['general_cats']					= 'Phân nhóm';
		$this->data['general_cat_root']				= 'Gốc';
		$this->data['general_cat_name']				= 'Tên phân nhóm';
		$this->data['general_cat_parent']			= 'Phân nhóm cha';
		$this->data['general_cat_childs']			= 'Phân nhóm con';
		$this->data['general_language']				= 'Ngôn ngữ';
		$this->data['general_template']				= 'Giao diện';
		$this->data['search_engine_tip']			= 'Rất hiệu quả đối với các bộ máy tìm kiếm';
		$this->data['general_meta_keywords']		= 'Từ khóa cho thẻ Meta';
		$this->data['general_meta_desc']			= 'Mô tả cho thẻ Meta';
		$this->data['general_optional']				= 'Tùy chọn';
		$this->data['general_redirect_url']			= 'URL chuyển trang';
		$this->data['general_id']					= 'ID';

		//General picture
		$this->data['general_pic_thumb']			= 'Hình thu nhỏ';
		$this->data['general_pic_full']				= 'Hình phóng to';
		$this->data['general_pic_remove']			= 'Bỏ hình';
		$this->data['general_pic_max_size']			= '(Kích thước tối đa: %d x %d pixels)';
		$this->data['general_pic_max_width']		= '(Chiều ngang tối đa: %d pixels)';
		$this->data['general_pic_max_height']		= '(Chiều cao tối đa: %d pixels)';

		//Web pages
		$this->data['page_displaying']				= 'Trang hiển thị';
		$this->data['page_home']					= 'Trang Chủ';
		$this->data['page_faq']						= 'Trang Hỏi đáp';
		$this->data['page_weblink']					= 'Trang Liên kết';
		$this->data['page_sitemap']					= 'Trang Sơ đồ website';
		$this->data['page_rss']						= 'Trang RSS';
		$this->data['page_contact']					= 'Trang Liên hệ';
		$this->data['page_event']					= 'Trang Sự kiện';
		$this->data['page_articles']				= 'Trang tin tức';

		//Upload
		$this->data['upload_error_not_file']		= 'Vui lòng chọn tập tin cần tải lên';
		$this->data['upload_error_file_type']		= 'Bạn không thể kiểu tập tin ày <b>%s</b>';
		$this->data['upload_error_upload']			= 'Không thể tải tập được <b>%s</b>';
		$this->data['upload_error_mkdir']			= 'Không thể tạo thư mục <b>%s</b>';

		//Functions
		$this->data['func_function']				= 'Chức năng';
		$this->data['func_action']					= 'Chức năng cho phép';
		$this->data['func_action_tip']				= 'Vui lòng chọn các tác vụ cho nhóm.';
		$this->data['func_item']					= 'Dữ liệu được phép';
		$this->data['func_item_tip']				= 'Vui lòng chọn dữ liệu được phép cho nhóm.';

		//ACP Log
		$this->data['log_login']					= 'Đăng nhập';
		$this->data['log_logout']					= 'Thoát';
		$this->data['log_time']						= 'Thời gian';
		$this->data['log_function']					= 'Chức năng';
		$this->data['log_action']					= 'Tác vụ';
		$this->data['log_record']					= 'Dữ liệu';
		$this->data['log_user']						= 'Thành viên';
		$this->data['log_ip']						= 'Địa chỉ IP';
		$this->data['log_add']						= 'Thêm mới';
		$this->data['log_edit']						= 'Chỉnh sửa';
		$this->data['log_update']					= 'Cập nhật';
		$this->data['log_resync']					= 'Đồng bộ';
		$this->data['log_move']						= 'Di chuyển';
		$this->data['log_move_cat']					= 'Chuyển đến phân nhóm';
		$this->data['log_move_topic']				= 'Chuyển đến chủ đề';
		$this->data['log_kill_user']				= 'Dừng thành viên';
		$this->data['log_rescure_user']				= 'Phục hồi thành viên';
		$this->data['log_run_sql']					= 'Chạy SQL';
		$this->data['log_del']						= 'Xóa';
		$this->data['log_del_all']					= 'Xóa tất cả';
		$this->data['log_export']					= 'Xuất file';
		$this->data['log_backup']					= 'Sao lưu';
		$this->data['log_restore']					= 'Phục hồi';
		$this->data['log_repair']					= 'Chỉnh sửa bảng';
		$this->data['log_enable']					= 'Bật';
		$this->data['log_disable']					= 'Tắt';
		$this->data['log_archive']					= 'Lưu trữ';
		$this->data['log_unarchive']				= 'Hủy lưu trữ';
		$this->data['log_set_perm']					= 'Cấp quyền';
		$this->data['log_rss_export_update']		= 'Cập nhật RSS';
		$this->data['log_rss_import_update']		= 'Cập nhật RSS';

		//Button
		$this->data['button_add']					= ' Thêm mới ';
		$this->data['button_edit']					= ' Cập nhật ';
		$this->data['button_delete']				= '   Xóa  ';
		$this->data['button_back']					= ' Quay lại ';
		$this->data['button_move']					= ' Di chuyển ';
		$this->data['button_reset']					= ' Làm lại ';
		$this->data['button_login']					= ' Đăng nhập ';
		$this->data['button_search']				= 'Tìm';
		$this->data['button_go']					= ' Chuyển ';
		$this->data['button_send']					= '  Gửi  ';
		$this->data['button_import']				= '   Nhập   ';
		$this->data['button_remove']				= '   Xóa   ';

		//Error Message
		$this->data['general_error_username']				= 'Tên truy cập <b>%s</b> không chính xác!';
		$this->data['general_error_password']				= 'Mật khẩu không chính xác!';
		$this->data['general_error_not_username']			= 'Vui lòng nhập tên truy cập!';
		$this->data['general_error_not_password']			= 'Vui lòng nhập mật khẩu!';
		$this->data['general_error_not_login']				= 'Bạn chưa đăng nhập!';
		$this->data['general_error_not_permission']			= "Bạn không có quyền truy cập vào khu vực này!";
		$this->data['general_error_not_full']				= 'Vui lòng nhập đầy đủ thông tin yêu cầu!';
		$this->data['general_error_not_exist_faq']			= 'Không tìm thấy hỏi đáp này!';
		$this->data['general_error_not_table']				= 'Vui lòng chọn table cần sao lưu.';
		$this->data['general_error_not_exist_logo']			= 'Không tìm thấy logo này!';
		$this->data['general_error_start_end_date']			= 'Ngày kết thúc phải sau ngày bắt đầu!';
		$this->data['general_error_start_date']				= 'Ngày bắt đầu không hợp lệ!';
		$this->data['general_error_end_date']				= 'Ngày kết thúc không hợp lệ!';
		$this->data['general_error_date']					= 'Ngày tháng không hợp lệ!';
		$this->data['general_error_send_pass']				= 'Bạn không thể gửi quá nhiều email vào tài khoản này!';

		$this->data['perm_error_not_all']					= "Bạn không có quyền truy cập vào khu vực này!";
		$this->data['perm_error_not_view']					= "Bạn không có quyền xem!";
		$this->data['perm_error_not_add']					= "Bạn không có quyền nhập mới!";
		$this->data['perm_error_not_edit']					= "Bạn không có quyền chỉnh sửa!";
		$this->data['perm_error_not_del']					= "Bạn không có quyền xóa!";
		$this->data['perm_error_not_active']				= "Bạn không có quyền bật hoặc tắt!";
		$this->data['perm_error_not_move_article']			= "Bạn không có quyền di chuyển tin bài!";
		$this->data['perm_error_not_move_email']			= "Bạn không có quyền di chuyển email!";
		$this->data['perm_error_not_move_user']				= "Bạn không có quyền di chuyển thành viên!";
		$this->data['perm_error_not_backup']				= "Bạn không có quyền sao lưu dữ liệu!";
		$this->data['perm_error_not_restore']				= "Bạn không có quyền phục hồi dữ liệu!";
		$this->data['perm_error_not_run_sql']				= "Bạn không có quyền thực thi câu SQL!";

		//Successfull Message
		$this->data['general_success_login']				= 'Đăng nhập thành công!';
		$this->data['general_success_logout']				= 'Logout thành công!';
		$this->data['general_success_add']					= 'Thêm mới thành công!';
		$this->data['general_success_edit']					= 'Chỉnh sửa thành công!';
		$this->data['general_success_update']				= 'Cập nhật thành công!';
		$this->data['general_success_delete']				= 'Xóa thành công!';
		$this->data['general_success_resync']				= 'Đồng bộ thành công!';
		$this->data['general_success_clean_cache']			= 'Đã xóa các file cache.';
		$this->data['general_success_add_emails']			= 'Thêm mới %d email thành công!';
		$this->data['general_success_send']					= 'Bản tin đã được gửi đến tất cả email!';
		$this->data['general_success_send_password']		= 'Vui lòng kiểm tra email để nhận mật khẩu mới.';
		$this->data['general_success_reset_password']		= 'Mật khẩu mới đã được gửi đến email của bạn! Vui lòng kiểm tra email để biết mật khẩu.';

		//Rss Import Logs
		$this->data['rss_log_import_articles']			= 'Nhập tin bài';
		$this->data['rss_log_remove_articles']			= 'Xóa tin bài';
	}
}

?>