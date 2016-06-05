<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_User;

class Lang_Module_User
{
	var $data		= array();

	function Lang_Module_User(){
		//User Groups
		$this->data['user_group_filter']			= 'Nhóm thành viên';
		$this->data['user_group_name']				= 'Tên nhóm';
		$this->data['user_group_desc']				= 'Mô tả nhóm';
		$this->data['user_group_level']				= 'Cấp bậc';
		$this->data['user_group_users']				= 'Tài khoản thành viên';
		$this->data['user_group_del_confirm']		= 'Xóa nhóm...';
		$this->data['user_group_move']				= 'Di chuyển thành viên đến nhóm:';
		$this->data['user_group_del']				= 'Xóa nhóm này';
		$this->data['user_group_del_move']			= 'Xóa hoặc di chuyển thành viên?';
		$this->data['user_group_del_users']			= 'Xóa tất cả thành viên thuộc nhóm này.';
		$this->data['user_group_admin']				= 'Tài khoản quản trị';
		$this->data['user_group_normal']			= 'Tài khoản thông thường';
		$this->data['user_group_perm']				= 'Quyền hạn';
		$this->data['user_group_set_perm']			= 'Cấp quyền cho nhóm "%s"';
		$this->data['user_group_source']			= 'Nhóm thành viên nguồn';
		$this->data['user_group_dest']				= 'Nhóm thành viên đích';
		$this->data['user_perm_view']				= 'Xem';
		$this->data['user_perm_add']				= 'Thêm mới';
		$this->data['user_perm_edit']				= 'Sửa';
		$this->data['user_perm_del']				= 'Xóa';
		$this->data['user_perm_active']				= 'Tình trạng';
		$this->data['user_perm_move_article']		= 'Di chuyển bài viết';
		$this->data['user_perm_move_email']			= 'Di chuyển email';
		$this->data['user_perm_move_user']			= 'Di chuyển thành viên';
		$this->data['user_perm_move_weblink']		= 'Di chuyển liên kết';
		$this->data['user_perm_send_email']			= 'Gửi email';
		$this->data['user_perm_reset']				= 'Làm lại';
		$this->data['user_perm_all']				= 'Tất cả';
		$this->data['user_perm_own']				= 'Thuộc cá nhân';
		$this->data['user_perm_enabled']			= 'Hiệu lực';
		$this->data['user_perm_disabled']			= 'Vô hiệu';
		$this->data['user_perm_backup']				= 'Sao lưu';
		$this->data['user_perm_repair']				= 'Sửa chữa';
		$this->data['user_perm_restore']			= 'Phục hồi';
		$this->data['user_perm_import']				= 'Nhập dữ liệu';
		$this->data['user_perm_export']				= 'Xuất dữ liệu';
		$this->data['user_perm_run_sql']			= 'Thực thi SQL';
		$this->data['user_allow_actions']			= 'Tác vụ cho phép: ';
		$this->data['user_allow_items']				= 'Dữ liệu cho phép: &nbsp; ';

		//Users
		$this->data['user_in_group']				= 'Thuộc nhóm';
		$this->data['user_move']					= 'Di chuyển thành viên';
		$this->data['user_username']				= 'Tên truy cập';
		$this->data['user_pass']					= 'Mật khẩu';
		$this->data['user_verify_pass']				= 'Xác nhận mật khẩu';
		$this->data['user_old_pass']				= 'Mật khẩu cũ';
		$this->data['user_new_pass']				= 'Mật khẩu mới';
		$this->data['user_verify_new_pass']			= 'Xác nhận mật khẩu mới';
		$this->data['user_email']					= 'Email';
		$this->data['user_articles']				= 'Bài viết';
		$this->data['user_timezone']				= 'Múi giờ';
		$this->data['user_hide_email']				= 'Che dấu email';
		$this->data['user_in_group']				= 'Thuộc nhóm';
		$this->data['user_never']					= 'Không bao giờ';
		$this->data['user_join_date']				= 'Ngày gia nhập';
		$this->data['user_last_login']				= 'Lần truy cập cuối';
		$this->data['user_online']					= 'Trực tuyến';
		$this->data['user_kick']					= 'Đá thành viên này ra khỏi hệ thống trong vài phút';
		$this->data['user_kicked']					= 'Bị đá';
		$this->data['user_kicked_by']				= "Bạn đã bị đá bở <strong>%s</strong> và bạn sẽ không thể đăng nhập trong khoảng %d phút.";
		$this->data['user_rescure']					= 'Cho phép thành viên này đăng nhập trở lại';
		$this->data['user_del_confirm']				= 'Xóa các thành viên đã chọn?';

		//User Errors
		$this->data['user_error_not_exist']			= 'Không tìm thấy thành viên này!';
		$this->data['user_error_not_check']			= 'Vui lòng chọn thành viên!';
		$this->data['user_error_exist_group']		= 'Nhóm thành viên <b>%s</b> đã tồn tại!';
		$this->data['user_error_exist_username']	= 'Đã có tên truy cập này. Vui lòng chọn tên khác.';
		$this->data['user_error_not_exist_group']	= 'Không tìm thấy nhóm thành viên này!';
		$this->data['user_error_oldpass']			= 'Mật khẩu cũ không chính xác!';
		$this->data['user_error_verifypass']		= 'Mật khẩu và xác nhận mật khẩu không trùng khớp!';

		//Profile Fields
		$this->data['field_type']					= 'Loại';
		$this->data['field_required']				= 'Bắt buột';
		$this->data['field_required_desc']			= 'Khi nhập hoặc chỉnh sửa tài khoản thành viên, bắt buột phải nhập thông tin này.';
		$this->data['field_textinput']				= 'Ô nhập liệu 1 dòng';
		$this->data['field_textarea']				= 'Ô nhập liệu nhiều dòng';
		$this->data['field_dropdown']				= 'Danh sách chọn';
		$this->data['field_editable']				= 'Có thể chỉnh sửa';
		$this->data['field_content']				= 'Nội dung ô nhập liệu (cho danh sách chọn)';
		$this->data['field_content_desc']			= 'Ví dụ:<br>Để tạo ô "Giới tính" như sau <select size= "1"><option value= "m">Nam</option><option value= "f">Nữ</option></select><br>Bạn có thể nhập nội dung:<br> &nbsp; &nbsp; &nbsp; m=Nam<br> &nbsp; &nbsp; &nbsp; f=Nữ<br>m,f là thông tin được lưu trong cơ sở dữ liệu.';
		$this->data['field_title']					= 'Tên ô nhập liệu';
		$this->data['field_desc']					= 'Mô tả';
		$this->data['field_type']					= 'Loại';
		$this->data['field_max_input']				= 'Số ký tự tối đa';
		$this->data['field_max_input_desc']			= 'Số ký tự tối đa được phép nhập vào ô thông tin';
		$this->data['field_size']					= 'Kích thước';
		$this->data['field_size_desc']				= 'Kích thước dành cho ô nhập liệu. Định dạng:<br>- Ô nhập liệu 1 dòng (kích thước) : xx<br>- Ô nhập liệu nhiều dòng (số cột,số hàng): xx,xx<br>(với xx là kiểu số nguyên)';
		$this->data['field_del']					= 'Xóa ô nhập liệu';
		$this->data['field_del_desc']				= 'Nếu bạn xóa ô nhập liệu, tất cả thông tin đã nhập vào ô này cũng sẽ bị xóa.';

		//Field errors
		$this->data['field_error_no_title']			= 'Vui lòng nhập tên của ô nhập liệu.';
		$this->data['field_error_exist_title']		= 'Đã có tên này. Vui lòng chọn tên khác.';
		$this->data['field_error_not_exist']		= 'Không tìm thấy ô nhập liệu này.';
	}
}

?>