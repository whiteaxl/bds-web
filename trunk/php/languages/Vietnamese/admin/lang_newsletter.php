<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Newsletter;

class Lang_Module_Newsletter
{
	var $data		= array();

	function Lang_Module_Newsletter(){
		//Newsletter
		$this->data['newslt_name']					= 'Tên';
		$this->data['newslt_email']					= 'Email';
		$this->data['newslt_emails']				= 'Số email';
		$this->data['newslt_subject']				= 'Tiêu đề';
		$this->data['newslt_content']				= 'Nội dung';
		$this->data['newslt_choose_file']			= 'Chọn file';
		$this->data['newslt_del_confirm']			= 'Xóa các email đã chọn?';
		$this->data['newslt_move']					= 'Di chuyển email';
		$this->data['newslt_move_cat']				= 'Chuyển đến phân nhóm';

		//Newsletter Cats
		$this->data['newslt_cat_desc']				= 'Mô tả';
		$this->data['newslt_cat_childs']			= 'Phân nhóm con';
		$this->data['newslt_cat_del_childs']		= 'Xóa các phân nhóm con';
		$this->data['newslt_cat_del_emails']		= 'Xóa email';
		$this->data['newslt_cat_move']				= 'Di chuyển email';
		$this->data['newslt_cat_source']			= 'Phân nhóm nguồn';
		$this->data['newslt_cat_dest']				= 'Phân nhóm đích';
		$this->data['newslt_cat_move_to']			= 'Chuyển đến phân nhóm';

		//Newsletter Issues
		$this->data['newslt_issue_title']			= 'Tiêu đề';
		$this->data['newslt_issue_content']			= 'Nội dung';
		$this->data['newslt_issue_send']			= 'Gửi email';
		$this->data['newslt_issue_last_sent']		= 'Lần gửi cuối';
		$this->data['newslt_issue_del_confirm']		= 'Xóa bản tin đã chọn?';
		$this->data['newslt_issue_note']			= '<strong>Ghi chú</strong>: Bạn có thể sử dụng các thẻ sau trong nội dung tin gửi đi.<br>
														<LI>{USER_NAME}: Hiển thị tên người dùng</LI>
														<LI>{USER_EMAIL}: Hiển thị email người dùng</LI>
														<LI>{URL_UNSUBSCRIBE}: Người dùng có thể click vào liên kết này để hủy đăng ký và ngưng nhận tin.</LI>
													  ';

		//Newsletter errors
		$this->data['newslt_success_move']			= 'Di chuyển email thành công!';
		$this->data['newslt_success_send_issue']	= 'Bản tin đã được gửi đi thành công!';
		$this->data['newslt_success_send_continue']	= 'Email từ %d đến %d đã được gửi. Vui lòng đợi trong giây lát để gửi tiếp các email khác.';
		$this->data['newslt_error_resend']			= 'Lỗi: "%s". Sẽ thử lại sau vài giây.';
		$this->data['newslt_error_not_exist']		= 'Không tìm thấy email này!';
		$this->data['newslt_error_not_check']		= 'Vui lòng chọn email!';
		$this->data['newslt_error_issue_not_check']	= 'Vui lòng chọn bản tin!';
		$this->data['newslt_error_issue_not_exist']	= 'Không tìm thấy bản tin này!';
		$this->data['newslt_error_email_not_exist']	= 'Không tìm thấy email này!';
		$this->data['newslt_error_cat_source']		= 'Vui lòng chọn phân nhóm nguồn và phân nhóm đích.';
		$this->data['newslt_error_cat_not_exist']	= 'Không tìm thấy phân nhóm này!';
		$this->data['newslt_error_cat_exist']		= 'Tên đầy đủ hoặc tên viết tắt của phân nhóm bị trùng lặp. Vui lòng chọn tên khác.';
	}
}

?>