<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
$Lang_Module	= new Lang_Module_Overview;

class Lang_Module_Overview
{
	var $data		= array();

	function Lang_Module_Overview(){
		$this->data['overview']					= 'General Overview';
		$this->data['online_overview']			= 'Administrators Using ACP';
		$this->data['system_overview']			= 'System Overview';
		$this->data['script_name']				= 'Script Name';
		$this->data['script_version']			= 'Script Version';
		$this->data['server_type']				= 'Server Type';
		$this->data['web_server']				= 'Web Server';
		$this->data['php_version']				= 'PHP Version';
		$this->data['mysql_version']			= 'MySQL Version';
		$this->data['data_overview']			= 'Data Overview';
		$this->data['acp_note']					= 'Administrator Notes';

		//New version
		$this->data['new_version']					= 'Newer version';
		$this->data['new_version_available']		= '<strong>%s</strong> is available.';
		$this->data['new_version_not_available']	= 'not available';
		$this->data['new_version_not_connect']		= 'Could not connect to website to check new version';
		$this->data['new_version_invalid']			= 'Your version is invalid!';
	}
}

?>