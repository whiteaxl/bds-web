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
		$this->data['stat']								= 'STATISTICS';
		$this->data['stat_online']						= 'Visitors online: ';
		$this->data['stat_hits']						= 'Total hits: ';
		$this->data['stat_queries_use']					= 'Used queries: ';
		$this->data['stat_time_execute']				= 'Executed time: ';
		$this->data['stat_gzip_enabled']				= 'Gzip: Enabled';
		$this->data['stat_gzip_disabled']				= 'Gzip: Disabled';
		$this->data['stat_cache_enabled']				= 'Script caching: Enabled';
		$this->data['stat_cache_disabled']				= 'Script caching: Disabled';
	}
}

?>