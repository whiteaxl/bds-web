<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Statistic;

class Lang_Module_Statistic
{
	var $data		= array();

	function Lang_Module_Statistic(){
		//Statistics
		$this->data['stats']						= 'Thống kê';
		$this->data['stat_from_to_date']			= '%s - %s';
		$this->data['stat_report_months']			= 'Thống kê hàng tháng';
		$this->data['stat_report_days']				= 'Thống kê hàng ngày';
		$this->data['stat_report_hours']			= 'Thống kê hàng giờ';
		$this->data['stat_report_countries']		= 'Thống kê theo quốc gia';
		$this->data['stat_report_browsers']			= 'Thống kê theo trình duyệt';
		$this->data['stat_report_referers']			= 'Thống kê theo địa chỉ đi';
		$this->data['stat_date_from']				= 'Từ';
		$this->data['stat_date_to']					= 'Đến';
		$this->data['stat_months']					= 'Tháng';
		$this->data['stat_days']					= 'Ngày';
		$this->data['stat_hours']					= 'Giờ';
		$this->data['stat_countries']				= 'Quốc gia';
		$this->data['stat_browsers']				= 'Trình duyệt';
		$this->data['stat_referers']				= 'Địa chỉ đi';
		$this->data['stat_visitors']				= 'Lượt truy cập';
		$this->data['stat_total_visitors']			= 'Tổng lượt truy cập';
		$this->data['stat_button_view']				= 'Thống kê';
		$this->data['stat_unknown']					= 'Không rõ quốc gia';
	}
}

?>