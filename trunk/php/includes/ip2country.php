<?php
/* =============================================================== *\
|		Module name: IP to Country									|
|		Module version: 1.0											|
|		Begin: 07 May 2006											|
|																	|
\* =============================================================== */

/*
	n records representing IP range and country relations
	the format of a record is:
	- starting IP number [10 bytes, padded with zeros from left]
	- ending IP number   [10 bytes, padded with zeros from left]
	- ISO country code   [3 bytes / letters]
	- newline            [1 byte: \n]
*/

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

class IP2Country
{
	var $flag_image	= './images/flags/';
	var $dbfile		= './includes/ip2country/ip2country.db';
	var $idxfile	= './includes/ip2country/ip2country.idx';

	function IP2Country($flag_image = ""){
		if ( !empty($flag_image) ){
			$this->flag_image	= $flag_image;
		}
	}

	function get_country_code($ip){
		if ( preg_match("/^(unknown|10|172\.16|192\.168)\.*/", $ip) ){
			return "";
		}

		// Convert the IP number to some useable form for searching
		$ipn	= (float) sprintf("%u", ip2long($ip));

		// Find the index to start search from
		$idx	= $this->search_in_index($ipn);

		// Find the country from idx
		if ($idx !== false){
			return $this->search_in_db($ipn, $idx);
		}
		return "";
	}

	function get_country_info($ip = ""){
		global $DB, $Info;

		if ( empty($ip) ){
			$ip	= $Info->client_ip;
		}

		//Get country code
		$c3		= $this->get_country_code($ip);

		if ( !empty($c3) ){
			$DB->query("SELECT * FROM ". $DB->prefix ."country_code WHERE c3='". $c3 ."'");
			if ($DB->num_rows()){
				return $DB->fetch_array();
			}
		}
		return false;
	}

	function get_country_flag($ip, $space = ''){
		$country_info	= $this->get_country_info($ip);
		if ( $country_info ){
			$left	= ($space == 'left') ? '&nbsp;' : '';
			$right	= ($space == 'right') ? '&nbsp;' : '';
			return $left . '<img src="'. $this->flag_image . $country_info['c2'] .'.gif" border="0" alt="" title="'. addslashes($country_info['country']) .'" hspace="2" align="absmiddle">' . $right;
		}
		return '';
	}

	// Find nearest index entry for IP number 
	function search_in_index($ipn){
		// Indexed part and record number to jump to
		$idx_part	= 0;
		$record_num	= 0;

		// Open the index file for reading
		$dbidx = fopen($this->idxfile, "r");
		if ( !$dbidx ){
			return false;
		}

		// Read in granularity from index file and
		// convert current IP to something useful
		$granularity	= intval(fgets($dbidx, 64));
		$ip_chunk		= $granularity ? intval($ipn / $granularity) : 0;

		// Loop till we can read the file
		while (!feof($dbidx)) {
			// Get CSV data from index file
			$data	= fgetcsv($dbidx, 100);

			// Compare current index part with our IP
			if ( ($ip_chunk >= $idx_part) && ($ip_chunk < (int) $data[0]) ){
				return array($record_num, (int) $data[1]);
			}

			// Store for next compare
			$idx_part		= (int) $data[0];
			$record_num 	= (int) $data[1];
		}

		// Return record number found
		return array($record_num, -1);
	}

	// Find the country
	// $ip should be an IP number and not an IP address
	function search_in_db($ipn, $idx){
		// Default range and country
		$range_start	= 0;
		$range_end		= 0;
		$country		= "";

		// Open DB for reading
		$ipdb	= fopen($this->dbfile, "r");

		// Return with blank country in case of we cannot open the db
		if ( !$ipdb ){
			return $country;
		}

		// Jump to record $idx
		fseek($ipdb, ($idx[0] ? (($idx[0]-1)*24) : 0));

		// Read records until we hit the end of the file,
		// or we find the range where this IP is, or we
		// reach the next indexed part [where the IP should
		// not be found, so there is no point in searching further]
		while (!feof($ipdb) && !($range_start <= $ipn && $range_end >= $ipn)){
			// We had run out of the indexed region,
			// where we expected to find the IP
			if ( ($idx[1] != -1) && ($idx[0] > $idx[1]) ){
				$country	= "";
				break;
			}

			// Try to read record
			$record		= fread($ipdb, 24);

			// Unable to read the record => error
			if (strlen($record) != 24){
				$country	= "";
				break;
			}

			// Split the record to it's parts
			$range_start	= (float) substr($record, 0, 10);
			$range_end		= (float) substr($record, 10, 10);
			$country		= substr($record, 20, 3);

			// Getting closer to the end of the indexed region
			$idx[0]	+= 1;
		}

		// Close datafile
		fclose($ipdb);

		// Return with the country found
		return $country;
	}
}

?>