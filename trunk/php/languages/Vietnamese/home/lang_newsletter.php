<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Newsletter;

class Lang_Module_Newsletter
{
	var $data		= array();

	function Lang_Module_Newsletter(){
		//Newsletter and email
		$this->data['newslt']							= 'Bản tin định kỳ';
		$this->data['newslt_subscribe']					= 'Đăng ký';
		$this->data['newslt_unsubscribe']				= 'Hủy bỏ đăng ký';
		$this->data['newslt_subscribe_desc']			= 'Đăng ký nhận bản tin';
		$this->data['newslt_subscribe_confirm']			= 'Đăng ký thành công. Chúng tôi sẽ gửi email xác nhận đến cho bạn.';
		$this->data['newslt_name']						= 'Tên của bạn';
		$this->data['newslt_email']						= 'Địa chỉ email';
		$this->data['newslt_error_email']				= 'Email không hợp lệ!';
		$this->data['newslt_validate_email']			= 'Bản tin định kỳ &raquo; Kiểm tra email:';
		$this->data['newslt_validate_number']			= 'Mã kiểm tra';
		$this->data['newslt_validate_type']				= 'Kiểu kiểm tra';

		//Newsletter success
		$this->data['newslt_success_subscribe']			= 'Đăng ký nhận bản tin thành công!';
		$this->data['newslt_success_unsubscribe']		= 'Hủy bỏ đăng ký thành công!';

		//Newsletter error
		$this->data['newslt_error_email']				= 'Vui lòng nhập địa chỉ email';
		$this->data['newslt_error_email_exist']			= 'Đã có email này!';
	}
}

?>