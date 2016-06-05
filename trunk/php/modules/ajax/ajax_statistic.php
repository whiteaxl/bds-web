<?php
/* =============================================================== *\
|		Module name: Statistic Global								|
|		Begin: 12 May 2006											|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
//IP2Country
include("./includes/ip2country.php");
$IP2Country	= new IP2Country;

$Statistics	= new Statistic_Global;

class Statistic_Global
{
	function Statistic_Global(){
		$this->update_stats();
	}

	function update_stats(){
		global $DB, $Info, $IP2Country;

		$update_time	= CURRENT_TIME - $Info->option['stat_time_update'];
		$today			= getdate(CURRENT_TIME + $Info->option['timezone']);

		//Check stat session from cookie
		if ( isset($_COOKIE['stat_time_update']) && ($_COOKIE['stat_time_update'] >= $update_time) ){
			return false;
		}

		//Check stat session from db
		$DB->query("SELECT session_time FROM ". $DB->prefix ."stat_session WHERE client_ip='". $Info->client_ip ."' AND session_time>=". $update_time ." ORDER BY session_time DESC LIMIT 0,1");
		if ( $DB->num_rows() ){
			return false;
		}

		//Update stat day
		$stat_date	= $today['year'] .'-'. $today['mon'] .'-'. $today['mday'];
		$DB->query("SELECT * FROM ". $DB->prefix ."stat_day WHERE stat_date='". $stat_date ."'");
		if ( $DB->num_rows() ){
			$DB->query("UPDATE ". $DB->prefix ."stat_day SET day_visitors=day_visitors+1 WHERE stat_date='". $stat_date ."'");
		}
		else{
			$DB->query("INSERT INTO ". $DB->prefix ."stat_day(stat_date, day_visitors) VALUES('". $stat_date ."', 1)");
		}

		//Update stat hour
		$DB->query("SELECT stat_hour FROM ". $DB->prefix ."stat_hour WHERE stat_date='". $stat_date ."' AND stat_hour=". $today['hours']);
		if ( $DB->num_rows() ){
			$DB->query("UPDATE ". $DB->prefix ."stat_hour SET hour_visitors=hour_visitors+1 WHERE stat_date='". $stat_date ."' AND stat_hour=". $today['hours']);
		}
		else{
			$DB->query("INSERT INTO ". $DB->prefix ."stat_hour(stat_date, stat_hour, hour_visitors) VALUES('". $stat_date ."', ". $today['hours'] .", 1)");
		}

		//Update stat country
		$country_info	= $IP2Country->get_country_info();
		$country_id		= isset($country_info['c2']) ? $country_info['c2'] : '..';
		$DB->query("SELECT country_id FROM ". $DB->prefix ."stat_country WHERE stat_date='". $stat_date ."' AND country_id='". $country_id ."'");
		if ( $DB->num_rows() ){
			$DB->query("UPDATE ". $DB->prefix ."stat_country SET country_visitors=country_visitors+1 WHERE stat_date='". $stat_date ."' AND country_id='". $country_id ."'");
		}
		else{
			$DB->query("INSERT INTO ". $DB->prefix ."stat_country(stat_date, country_id, country_visitors) VALUES('". $stat_date ."', '". $country_id ."', 1)");
		}

		//Update stat browser
		$browser_info	= $this->get_browser_info();
		$browser_name	= $browser_info ? $browser_info['browser'] : '..';
		$DB->query("SELECT browser_name FROM ". $DB->prefix ."stat_browser WHERE stat_date='". $stat_date ."' AND browser_name='". $browser_name ."'");
		if ( $DB->num_rows() ){
			$DB->query("UPDATE ". $DB->prefix ."stat_browser SET browser_visitors=browser_visitors+1 WHERE stat_date='". $stat_date ."' AND browser_name='". $browser_name ."'");
		}
		else{
			$DB->query("INSERT INTO ". $DB->prefix ."stat_browser(stat_date, browser_name, browser_visitors) VALUES('". $stat_date ."', '". $browser_name ."', 1)");
		}

		//Update stat referer
		$referer		= $this->get_referer();
		$referer_url	= $referer ? addslashes($referer) : '..';
		if ( $referer_url != 'ignored' ){
			$DB->query("SELECT referer_url FROM ". $DB->prefix ."stat_referer WHERE stat_date='". $stat_date ."' AND referer_url='". $referer_url ."'");
			if ( $DB->num_rows() ){
				$DB->query("UPDATE ". $DB->prefix ."stat_referer SET referer_visitors=referer_visitors+1 WHERE stat_date='". $stat_date ."' AND referer_url='". $referer_url ."'");
			}
			else{
				$DB->query("INSERT INTO ". $DB->prefix ."stat_referer(stat_date, referer_url, referer_visitors) VALUES('". $stat_date ."', '". $referer_url ."', 1)");
			}
		}

		//Update stat session
		$cookie_time	= CURRENT_TIME + $Info->option['stat_time_update'];
		$cookie_path	= $Info->option['cookie_path'];
		$cookie_domain	= $Info->option['cookie_domain'];
		$cookie_secure	= $Info->option['cookie_secure'];
		if ( !empty($cookie_domain) && ($cookie_domain != "localhost") ){
			setcookie('stat_time_update', CURRENT_TIME, $cookie_time, $cookie_path, $cookie_domain, $cookie_secure);
		}
		else{
			setcookie('stat_time_update', CURRENT_TIME, $cookie_time);
		}
		$DB->query("DELETE FROM ". $DB->prefix ."stat_session WHERE client_ip='". $Info->client_ip ."' OR session_time<=". $update_time);
		$DB->query("INSERT INTO ". $DB->prefix ."stat_session(client_ip, session_time) VALUES('". $Info->client_ip ."', ". CURRENT_TIME .")");
		return true;
	}

	function get_referer(){
		$referer	= isset($_SERVER['HTTP_REFERER']) ? htmlspecialchars($_SERVER['HTTP_REFERER']) : '';
		$domain		= isset($_SERVER["SERVER_NAME"]) ? $_SERVER["SERVER_NAME"] : '';
		if ( empty($referer) ){
			return false;
		}
		if ( strpos($referer, $domain) !== false ){
			return 'ignored';
		}
		return $referer;
	}

	function get_browser_info(){
		$user_agent		= isset($_SERVER["HTTP_USER_AGENT"]) ? trim($_SERVER["HTTP_USER_AGENT"]) : '';
		if ( empty($user_agent) ){
			return false;
		}

		$browser_info	= array();
		$browser_info['browser']	= '';
		$browser_info['version']	= '';

		//Check for Opera
		if ( eregi("opera", $user_agent) ){
			$val	= stristr($user_agent, "opera");
			if ( eregi("/", $val) ){
				$val	= explode("/", $val);
				$browser_info['browser']	= $val[0];
				$val	= explode(" ", $val[1]);
				$browser_info['version']	= $val[0];
			}
			else{
				$val	= explode(" ", stristr($val, "opera"));
				$browser_info['browser']	= $val[0];
				$browser_info['version']	= $val[1];
			}
		}
		//Check for WebTV
		else if ( eregi("webtv", $user_agent) ){
			$val	= explode("/", stristr($user_agent,"webtv"));
			$browser_info['browser']	= $val[0];
			$browser_info['version']	= $val[1];
		}
		//Check for NetPositive
		else if ( eregi("NetPositive", $user_agent) ){
			$val	= explode("/", stristr($user_agent, "NetPositive"));
			$browser_info['browser']	= $val[0];
			$browser_info['version']	= $val[1];
		}
		//Check for MS Internet Explorer
		else if ( eregi("msie", $user_agent) && !eregi("opera", $user_agent) ){
			$val	= explode(" ", stristr($user_agent, "msie"));
			$browser_info['browser']	= $val[0];
			$browser_info['version']	= $val[1];
		}
		//Check for MS Pocket Internet Explorer
		else if ( eregi("mspie", $user_agent) || eregi('pocket', $user_agent) ){
			$val	= explode(" ", stristr($user_agent, "mspie"));
			$browser_info['browser']	= "MSPIE";
//			$browser_info['platform']	= "WindowsCE";
			if ( eregi("mspie", $user_agent) ){
				$browser_info['version']	= $val[1];
			}
			else{
				$val	= explode("/", $user_agent);
				$browser_info['version']	= $val[1];
			}
		}
		//Check for Galeon
		else if ( eregi("galeon", $user_agent) ){
			$val	= explode(" ", stristr($user_agent,"galeon"));
			$val	= explode("/", $val[0]);
			$browser_info['browser']	= $val[0];
			$browser_info['version']	= $val[1];
		}
		//Check for Konqueror
		else if ( eregi("Konqueror", $user_agent) ){
			$val	= explode(" ", stristr($user_agent,"Konqueror"));
			$val	= explode("/", $val[0]);
			$browser_info['browser']	= $val[0];
			$browser_info['version']	= $val[1];
		}
		//Check for iCab
		else if ( eregi("icab", $user_agent) ){
			$val	= explode(" ", stristr($user_agent, "icab"));
			$browser_info['browser']	= $val[0];
			$browser_info['version']	= $val[1];
		}
		//Check for OmniWeb
		else if ( eregi("omniweb", $user_agent) ){
			$val	= explode("/", stristr($user_agent, "omniweb"));
			$browser_info['browser']	= $val[0];
			$browser_info['version']	= $val[1];
		}
		//Check for Phoenix
		else if( eregi("Phoenix", $user_agent) ){
			$browser_info['browser']	= "Phoenix";
			$val	= explode("/", stristr($user_agent, "Phoenix/"));
			$browser_info['version']	= $val[1];
		}
		//Check for Firebird
		else if ( eregi("firebird", $user_agent) ){
			$browser_info['browser']	= "Firebird";
			$val	= stristr($user_agent, "Firebird");
			$val	= explode("/", $val);
			$browser_info['version']	= $val[1];
		}
		//Check for Firefox
		else if ( eregi("Firefox", $user_agent) ){
			$browser_info['browser']	="Firefox";
			$val	= stristr($user_agent, "Firefox");
			$val	= explode("/", $val);
			$browser_info['version']	= $val[1];
		}
		//Check for Mozilla Stable Versions
		else if ( eregi("mozilla", $user_agent) && eregi("rv:[0-9]\.[0-9]", $user_agent) && !eregi("netscape", $user_agent) ){
			$browser_info['browser']	= "Mozilla";
			$val	= explode(" ", stristr($user_agent, "rv:"));
			eregi("rv:[0-9]\.[0-9]\.[0-9]", $user_agent, $val);
			$browser_info['version']	= str_replace("rv:", "", $val[0]);
		}
		//Check for Lynx & Amaya
		else if ( eregi("libwww", $user_agent) ){
			if ( eregi("amaya", $user_agent) ){
				$val	= explode("/", stristr($user_agent, "amaya"));
				$browser_info['browser']	= "Amaya";
				$val	= explode(" ", $val[1]);
				$browser_info['version']	= $val[0];
			}
			else{
				$val	= explode("/", $user_agent);
				$browser_info['browser']	= "Lynx";
				$browser_info['version']	= $val[1];
			}
		}
		//Check for Safari
		else if ( eregi("safari", $user_agent) ){
			$browser_info['browser'] = "Safari";
			$browser_info['version'] = "";
		}
		//Remaining two tests are for Netscape
		else if ( eregi("netscape", $user_agent) ){
			$val	= explode(" ", stristr($user_agent, "netscape"));
			$val	= explode("/", $val[0]);
			$browser_info['browser']	= $val[0];
			$browser_info['version']	= $val[1];
		}
		else if ( eregi("mozilla", $user_agent) && !eregi("rv:[0-9]\.[0-9]\.[0-9]", $user_agent) ){
			$val	= explode(" ", stristr($user_agent, "mozilla"));
			$val	= explode("/", $val[0]);
			$browser_info['browser']	= "Netscape";
			$browser_info['version']	= $val[1];
		}

		//Results
		if ( !empty($browser_info['browser']) ){
			// clean up extraneous garbage that may be in the name
			$browser_info['browser']	= ereg_replace("[^a-z,A-Z]", "", $browser_info['browser']);
			// clean up extraneous garbage that may be in the version        
			$browser_info['version']	= ereg_replace("[^0-9,.,a-z,A-Z]", "", $browser_info['version']);
			return $browser_info;
		}
		else{
			return false;
		}
	}
}

?>