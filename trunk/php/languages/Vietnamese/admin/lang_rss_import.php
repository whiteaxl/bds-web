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
		$this->data['rss_import']						= 'Nguồn dữ liệu RSS';
		$this->data['rss_imports']						= 'Nguồn dữ liệu RSS';
		$this->data['rss_import_feed']					= 'Sao chép dữ liệu';
		$this->data['rss_import_remove_feed']			= 'Xóa dư liệu';
		$this->data['rss_import_basics']				= 'Thông tin cơ bản';
		$this->data['rss_import_title']					= 'Tiêu đề';
		$this->data['rss_import_url']					= 'Địa chỉ URL của nguồn RSS';
		$this->data['rss_import_url_desc']				= 'Nguồn phải theo chuẩn RDF hoặc RSS';
		$this->data['rss_import_pergo']					= 'Số dữ liệu mỗi lần sao chép';
		$this->data['rss_import_pergo_desc']			= 'Sao chép n dữ liệu mỗi lần. Thông số này còn phụ thuộc vào tài nguyên của máy chủ.';
		$this->data['rss_import_convert_charset']		= 'Chuyển định dạng ngôn ngữ';
		$this->data['rss_import_convert_charset_desc']	= 'Tự động chuyển định dạng từ định dạng nguồn sang định dạng của bạn';
		$this->data['rss_import_status']				= 'Tình trạng nguồn RSS';
		$this->data['rss_import_auth']					= 'Đăng nhập qua htaccess';
		$this->data['rss_import_require_auth']			= 'Bắt buột đăng nhập';
		$this->data['rss_import_require_auth_desc']		= 'Hầu hết các nguồn RSS không yêu cầu đăng nhập';
		$this->data['rss_import_auth_user']				= 'Tên truy cập htaccess';
		$this->data['rss_import_auth_pass']				= 'Mật khẩu htaccess';
		$this->data['rss_import_contents']				= 'Nội dung sao chép';
		$this->data['rss_import_cat']					= 'Sao chép vào Phân nhóm tin';
		$this->data['rss_import_cat_desc']				= 'Chọn một phân nhóm để sao chép dữ liệu tương tự nhạp một tin bài mới.';
		$this->data['rss_import_article_type']			= 'Kiểu bài viết';
		$this->data['rss_import_article_type_full']		= 'Đầy đủ';
		$this->data['rss_import_article_type_summary']	= 'Tóm tắt';
		$this->data['rss_import_article_type_link']		= 'Tóm tắt kèm liên kết';
		$this->data['rss_import_article_type_tip']		= '* Bài viết đầy đủ sẽ được hiển thị với trang xem chi tiết.<br>* Bài viết tóm tắt được hiển thị mà không có trang xem chi tiết.<br>* Bài viết Tóm tắt kèm liên kết sẽ được hiển thị với liên kết dẫn đến trang web khác.';
		$this->data['rss_import_remove_html']			= 'Xóa các thẻ HTML';
		$this->data['rss_import_remove_html_desc']		= 'Nếu có, tất cả các thẻ HTML sẽ bị loại bỏ';
		$this->data['rss_import_show_link']				= 'Liên kết đến bài viết nguồn';
		$this->data['rss_import_show_link_desc']		= 'Nếu có, người sử dụng có thể lick vào tiêu đề bài viết để chuyển đến tin bài nguồn.';
		$this->data['rss_import_author']				= 'Tên tác giả';
		$this->data['rss_import_author_desc']			= 'Tác giả của các dữ liệu sẽ sao chép';
		$this->data['rss_import_username']				= 'Tên truy cập của tác giả';
		$this->data['rss_import_username_desc']			= 'Bài viết sẽ thuộc quyền quản lý của thành viên tương ứng';
		$this->data['rss_import_userpost_increase']		= 'Gia tăng số bài viết của thành viên';
		$this->data['rss_import_userpost_increase_desc']= 'Tăng số bài viết của thành viên dựa theo các bài viết mà thành viên được quyền quản lý';
		$this->data['rss_import_article_status']		= 'Tình trạng bài viết';
		$this->data['rss_import_article_status_desc']	= 'Nếu chọn "Hiệu lực" thì bài viết sẽ được hiển thi. Trường hợp ngườc lại, bài viết sẽ bị ẩn.';
		$this->data['rss_import_del_confirm']			= 'Xóa các nguồn dữ liệu đã chọn?';
		$this->data['rss_import_streams']				= 'Nguồn RSS';
		$this->data['rss_import_desc']					= 'Chọn nguồn RSS cho để sao chép.';
		$this->data['rss_import_remove_desc']			= 'Chọn nguồn RSS cho để xóa bài viết.';
		$this->data['rss_import_articles']				= 'Bài viết';
		$this->data['rss_import_last_date']				= 'Lần sao chép gần nhất';
		$this->data['rss_import_auto']					= 'Liên kết tự động import';

		$this->data['rss_import_remove_articles']		= 'Xóa bài viết';
		$this->data['rss_import_remove_streams']		= 'Xóa bài viết thuộc các nguồn';
		$this->data['rss_import_remove_number']			= 'Xóa <em>n</em> bài viết sau cùng';
		$this->data['rss_import_remove_number_desc']	= 'Xóa các bài viết sau cùng của mỗi phân nhóm. Bỏ trống nếu muốn xóa tất cả.';
		$this->data['rss_import_import_articles']		= 'Sao chép bài viết';
		$this->data['rss_import_import_streams']		= 'Sao chép bài viết từ các nguồn';
		$this->data['rss_import_import_number']			= 'Sao chép <em>n</em> bài viết';
		$this->data['rss_import_import_number_desc']	= 'Sao chép <em>n</em> bài viết sau cùng của mỗi phân nhóm. Bỏ trống nếu muốn sao chép tất cả.';

		//Success
		$this->data['rss_import_success']				= 'Đã sao chép %d bài viết!';
		$this->data['rss_import_success_remove']		= 'Đã xóa %d bài viết!';

		//RSS Import errors
		$this->data['rss_import_error_none']			= 'Chưa có nguồn RSS nào!';
		$this->data['rss_import_error_not_check']		= 'Vui lòng chọn nguồn RSS!';
	}
}

?>