<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Statistic;

class Lang_Module_Statistic
{
	var $data		= array();

	function Lang_Module_Statistic(){
		//Statistic
		$this->data['stat']								= 'THỐNG KÊ';
		$this->data['stat_online']						= 'Số người online: ';
		$this->data['stat_hits']						= 'Lượt xem: ';
		$this->data['stat_queries_use']					= 'Số truy vấn db: ';
		$this->data['stat_time_execute']				= 'Thời gian thực thi: ';
		$this->data['stat_gzip_enabled']				= 'Gzip: Bật';
		$this->data['stat_gzip_disabled']				= 'Gzip: Tắt';
		$this->data['stat_cache_enabled']				= 'Cache web: Bật';
		$this->data['stat_cache_disabled']				= 'Cache: Tắt';
	}
}

?>