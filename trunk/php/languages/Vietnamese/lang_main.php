<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

class Lang_Global
{
	var $charset	= "UTF-8";
	var $data		= array();

	function Lang_Global(){
		//General
		$this->data['general_close']				= 'Đóng';
		$this->data['general_all']					= 'Tất cả';
		$this->data['general_yes']					= 'Có';
		$this->data['general_no']					= 'Không';
		$this->data['general_choose']				= 'Chọn';
		$this->data['general_cat']					= 'Phân nhóm';
		$this->data['general_back']					= 'Quay lại';
		$this->data['general_view_more']			= 'Xem tiếp ++';
		$this->data['general_page']					= 'Trang';
		$this->data['general_page_prev']			= 'Trang trước';
		$this->data['general_page_next']			= 'Trang sua';
		$this->data['general_go']					= '   Chuyển   ';
		$this->data['general_message_die']			= 'Hiển thị thông điệp';
		$this->data['general_number']				= 'Mã bảo vệ';

		//General error
		$this->data['general_error_not_full']		= 'Vui lòng hoàn tất các thông tin yêu cầu!';
		$this->data['general_error_email']			= 'Email không hợp lệ!';
		$this->data['general_error_login_number']	= 'Mã bảo vệ không chính xác!';

		//Pagination
		$this->data['page_previous']				= '&lt;&lt; Trước';
		$this->data['page_next']					= 'Sau &gt;&gt;';
		$this->data['page_title_previous']			= 'Trang trước';
		$this->data['page_title_first']				= 'Trang đầu';
		$this->data['page_title_last']				= 'Trang cuối';
		$this->data['page_title_next']				= 'Trang sau';

		//General date
		$this->data['general_date_day']				= 'Chủ nhật, Thứ hai, Thứ ba, Thứ tư, Thứ năm, Thứ sáu, Thứ bảy';
		$this->data['general_date_day_short']		= 'CN, T2, T3, T4°, T5, T6, T7';
		$this->data['general_date_month']			= 'Tháng giêng, Tháng hai, Tháng ba, Tháng tư, Tháng năm, Tháng sáu, Tháng bảy, Tháng tám, Tháng chín, Tháng mười, Tháng mười một, Tháng mười hai';
		$this->data['general_date_month_short']		= 'Giêng, Hai, Ba, Tư, Năm, Sáu, Bảy, Tám, Chín, Mười, M. Một, M. Hai';
	}
}
?>