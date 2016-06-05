<?php
/* =============================================================== *\
|		Module name: Debug											|
|		Module version: 1.0											|
|		Begin: 22 January 2004										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

class Debug
{
	var $exectime		= 0;
	var $starttime		= 0;
	var $endtime		= 0;

	function start_debug(){
		$mtime	= explode(" ", microtime());
		$mtime	= $mtime[1] + $mtime[0];
		$this->starttime	= $mtime;
	}

	function end_debug(){
		$mtime	= explode(" ", microtime());
		$mtime	= $mtime[1] + $mtime[0];
		$this->endtime = $mtime;
		$this->exectime = ($this->endtime - $this->starttime);
	}
}

?>