<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Database;

class Lang_Module_Database
{
	var $data		= array();

	function Lang_Module_Database(){
		//Database
		$this->data['db_table']					= 'Bảng';
		$this->data['db_rows']					= 'Số dòng dữ liệu';
		$this->data['db_data_size']				= 'Kích thước dữ liệu';
		$this->data['db_max_data_size']			= 'Kích thước dữ liệu tối đa';
		$this->data['db_index_size']			= 'Kích thước chỉ mục';
		$this->data['db_create_time']			= 'Thời gian khởi tạo';
		$this->data['db_update_time']			= 'Thời gian cập nhật';
		$this->data['db_version']				= '<b>Mysql %s</b>';
		$this->data['db_total_size']			= 'Tổng kích thước dữ liệu: %s';
		$this->data['db_info']					= 'Thông tin cơ sở dữ liệu';
		$this->data['db_sql_runtime']			= 'Thông tin thời gian chạy SQL';
		$this->data['db_sql_system']			= 'Các biến hệ thống';
		$this->data['db_sql_process']			= 'Tiến trình hệ thống';
		$this->data['db_run_query']				= 'Thực thi câu SQL';
		$this->data['db_run_result']			= 'Kết quả';
		$this->data['db_run_sql']				= 'Thực thi SQL';
		$this->data['db_backup']				= 'Sao lưu';
		$this->data['db_restore']				= 'Phục hồi';
		$this->data['db_optimize']				= 'Tinh chỉnh';
		$this->data['db_analyze']				= 'Đồng bộ';
		$this->data['db_check']					= 'Kiểm tra';
		$this->data['db_repair']				= 'Sửa chữa';
		$this->data['db_run']					= ' Thực hiện ';
		$this->data['db_run_note']				= '<b>Ghi chú:</b> Chức năng này chỉ dành cho các thành viên am hiểu kỹ thuật. Xin bạn thật cẩn thận với các câu SQL, đặc biệt là DELETE, DROP hoặc câu UPDATE.';
		$this->data['db_backup_tbls']			= 'Sao lưu các table đã chọn';
		$this->data['db_start_backup']			= 'Bắt đầu sao lưu';
		$this->data['db_structure']				= 'Sao lưu cấu trúc';
		$this->data['db_data']					= 'Sao lưu dữ liệu';
		$this->data['db_select_rs_file']		= 'Chọn một file';
		$this->data['db_start_restore']			= 'Bắt đầu phục hồi';
		$this->data['db_serveruptime']			= 'Máy chủ SQL đã chạy trong khoảng thời gian %s. Nó được khởi động từ %s.';
		$this->data['db_servertmptime']			= '%s days, %s hours, %s minutes and %s seconds';
		$this->data['db_day_of_week']			= 'Sun,Mon,Tue,Wed,Thu,Fri,Sat';
		$this->data['db_month']					= 'Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec';
		$this->data['db_dateformat']			= '%B %d, %Y at %I:%M %p';
		$this->data['db_use_gzip']				= 'Use Gzip';

		//Db error
		$this->data['db_error_not_check']		= 'Vui lòng chọn các table!';
		$this->data['db_error_not_sql']			= 'Vui lòng nhập câu sql!';
		$this->data['db_error_sql']				= 'Lỗi SQL';
		$this->data['db_error_run']				= '<strong>Query:</strong> %s <br><br><strong>%s</strong>';
		$this->data['db_error_not_choose']		= 'Vui lòng chọn table!';
		$this->data['db_error_upload_file']		= 'Vui lòng chọn file cần tải lên!';
		$this->data['db_success_run']			= '<strong>Query:</strong> %s <br><br><strong>Thực hiện thành công!</strong>';
		$this->data['db_success_backup']		= 'Sao lưu dữ liệu thành công!';
		$this->data['db_success_restore']		= 'Phục hồi dữ liệu thành công!';
	}
}

?>