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
		$this->data['stats']						= 'Statistics';
		$this->data['stat_from_to_date']			= '%s - %s';
		$this->data['stat_report_months']			= 'Monthly Report';
		$this->data['stat_report_days']				= 'Daily Report';
		$this->data['stat_report_hours']			= 'Hourly Report';
		$this->data['stat_report_countries']		= 'Countries Report';
		$this->data['stat_report_browsers']			= 'Browsers Report';
		$this->data['stat_report_referers']			= 'Referers Report';
		$this->data['stat_date_from']				= 'From';
		$this->data['stat_date_to']					= 'To';
		$this->data['stat_months']					= 'Months';
		$this->data['stat_days']					= 'Days';
		$this->data['stat_hours']					= 'Hours';
		$this->data['stat_countries']				= 'Countries';
		$this->data['stat_browsers']				= 'Browsers';
		$this->data['stat_referers']				= 'Referers';
		$this->data['stat_visitors']				= 'Visitors';
		$this->data['stat_total_visitors']			= 'Total visitors';
		$this->data['stat_button_view']				= 'Report';
		$this->data['stat_unknown']					= 'Unknown';
	}
}

?>