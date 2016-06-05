<?php
/* =============================================================== *\
|		Module name: Statistic										|
|		Module version: 1.1											|
|		Begin: 14 May 2006											|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
//Module language
$Func->import_module_language("admin/lang_statistic". PHP_EX);

$AdminStat = new Admin_Statistic;

class Admin_Statistic
{
	var $date_separate	= '/';
	var $filter			= array();

	function Admin_Statistic(){
		global $Info, $Func;

		$this->user_perm	= $Func->get_all_perms('menu_admin_stat');
		$this->get_filter();

		switch ($Info->act){
			case 'months':
				$this->view_stat_months();
				break;
			case 'days':
				$this->view_stat_days();
				break;
			case 'hours':
				$this->view_stat_hours();
				break;
			case 'countries':
				$this->view_stat_countries();
				break;
			case 'browsers':
				$this->view_stat_browsers();
				break;
			case 'referers':
				$this->view_stat_referers();
				break;
			default:
				$this->list_statistics();
		}
	}

	function get_filter(){
		global $Template, $Info, $Session, $Func;

		$this->filter['url_append']	= "";

		//Date from
		$this->filter['date_from']		= isset($_POST['fdate_from']) ? htmlspecialchars($_POST['fdate_from']) : '';
		if ( empty($this->filter['date_from']) ){
			$this->filter['date_from']		= isset($_GET["fdate_from"]) ? htmlspecialchars($_GET["fdate_from"]) : '';
		}
		if ( !empty($this->filter['date_from']) ){
			$this->filter['url_append']	.= '&fdate_from='. $this->filter['date_from'];
		}

		//Date to
		$this->filter['date_to']			= isset($_POST['fdate_to']) ? htmlspecialchars($_POST['fdate_to']) : '';
		if ( empty($this->filter['date_to']) ){
			$this->filter['date_to']		= isset($_GET["fdate_to"]) ? htmlspecialchars($_GET["fdate_to"]) : '';
		}
		if ( !empty($this->filter['date_to']) ){
			$this->filter['url_append']	.= '&fdate_to='. stripslashes($this->filter['date_to']);
		}

		$Template->set_vars(array(
			'S_FILTER_ACTION'	=> $Session->append_sid(ACP_INDEX .'?mod=stat&act='. $Info->act),
		));
	}

	function list_statistics(){
		global $Session, $Func, $Template, $Lang, $Info;

		$Info->tpl_main		= "statistic_list";

		$Template->set_vars(array(
			'U_STAT_MONTHS'				=> $Session->append_sid(ACP_INDEX .'?mod=stat&act=months'),
			'U_STAT_DAYS'				=> $Session->append_sid(ACP_INDEX .'?mod=stat&act=days'),
			'U_STAT_HOURS'				=> $Session->append_sid(ACP_INDEX .'?mod=stat&act=hours'),
			'U_STAT_COUNTRIES'			=> $Session->append_sid(ACP_INDEX .'?mod=stat&act=countries'),
			'U_STAT_BROWSERS'			=> $Session->append_sid(ACP_INDEX .'?mod=stat&act=browsers'),
			'U_STAT_REFERERS'			=> $Session->append_sid(ACP_INDEX .'?mod=stat&act=referers'),
			"L_PAGE_TITLE"				=> $Lang->data["menu_admin"] . $Lang->data['general_arrow'] . $Lang->data["menu_admin_stat"],
			"L_STATS"					=> $Lang->data["stats"],
			"L_STAT_MONTHS"				=> $Lang->data["stat_report_months"],
			"L_STAT_DAYS"				=> $Lang->data["stat_report_days"],
			"L_STAT_HOURS"				=> $Lang->data["stat_report_hours"],
			"L_STAT_COUNTRIES"			=> $Lang->data["stat_report_countries"],
			"L_STAT_BROWSERS"			=> $Lang->data["stat_report_browsers"],
			"L_STAT_REFERERS"			=> $Lang->data["stat_report_referers"],
		));
	}

	function view_stat_months(){
		global $Session, $DB, $Template, $Lang, $Info;

		$Info->tpl_main		= "statistic_months";
		$timezone	= $Info->option['timezone'] * 3600;
		$today		= getdate(CURRENT_TIME + $timezone);

		//Filter -------------------------
		//Date from
		$dfrom_info	= $this->get_date_info($this->filter['date_from'], 0);
		if ( ($dfrom_info['month'] < 1) || ($dfrom_info['month'] > 12) ){
			$dfrom_info['month']	= $today['mon'] - 6;
		}
		if ( ($dfrom_info['year'] < 1) || ($dfrom_info['year'] > 9999) ){
			$dfrom_info['year']		= $today['year'];
		}
		if ( $dfrom_info['month'] < 1 ){
			$dfrom_info['month']	+=	12;
			$dfrom_info['year']--;
		}

		//Date to
		$dto_info	= $this->get_date_info($this->filter['date_to'], 0);
		if ( ($dto_info['month'] < 1) || ($dto_info['month'] > 12) ){
			$dto_info['month']		= $today['mon'];
		}
		if ( ($dto_info['year'] < 1) || ($dto_info['year'] > 9999) ){
			$dto_info['year']		= $today['year'];
		}

		//Check dates
		if ( ($dto_info['year'] < $dfrom_info['year']) || (($dto_info['year'] == $dfrom_info['year']) && ($dto_info['month'] < $dfrom_info['month'])) ){
			$dfrom_info['month']	= $today['mon'] - 6;
			$dto_info['year']		= $today['year'];
			if ( $dfrom_info['month'] < 1 ){
				$dfrom_info['month']	+=	12;
				$dfrom_info['year']--;
			}
			$dto_info['month']		= $today['mon'];
			$dto_info['year']		= $today['year'];
		}
		$date_from	= $dfrom_info['year'] .'-'. $dfrom_info['month'] .'-01';
		$date_to	= $dto_info['year'] .'-'. $dto_info['month'] .'-31';
		//--------------------------------

		//Get stat info
		$DB->query("SELECT MONTH(stat_date) AS stat_month, YEAR(stat_date) AS stat_year, sum(day_visitors) AS month_visitors FROM ". $DB->prefix ."stat_day WHERE stat_date>='". $date_from ."' AND stat_date<='". $date_to ."' GROUP BY MONTH(stat_date) ORDER BY stat_date ASC");
		$stat_count		= $DB->num_rows();
		$stat_data		= $DB->fetch_all_array();
		$DB->free_result();

		//Count total visitors
		$total_visitors		= 0;
		for ($i=0; $i<$stat_count; $i++){
			$total_visitors	+= $stat_data[$i]['month_visitors'];
		}

		//List stats
		$month_names	= explode(',', $Lang->data['general_date_month']);
		for ($i=0; $i<$stat_count; $i++){
			$percent		= !$total_visitors ? '0%' : round(($stat_data[$i]['month_visitors'] * 100) / $total_visitors, 1) .'%';
			$month_number	= $stat_data[$i]["stat_month"] - 1; //Convert from 1-12 to 0-11 for month number
			$Template->set_block_vars("statrow", array(
				"MONTH"			=> isset($month_names[$month_number]) ? trim($month_names[$month_number]) : $stat_data[$i]["stat_month"],
				"YEAR"			=> $stat_data[$i]["stat_year"],
				"VISITORS"		=> $stat_data[$i]["month_visitors"],
				"PERCENT"		=> $percent,
			));
		}

		$Template->set_vars(array(
			'TOTAL_VISITORS'		=> $total_visitors,
			'FDATE_FROM'			=> $this->make_date_string($dfrom_info['month'], 0, $dfrom_info['year']),
			'FDATE_TO'				=> $this->make_date_string($dto_info['month'], 0, $dto_info['year']),
			"L_PAGE_TITLE"			=> $Lang->data["menu_admin"] . $Lang->data['general_arrow'] . $Lang->data["menu_admin_stat"] . $Lang->data['general_arrow'] . $Lang->data["stat_report_months"],
			'L_REPORT_TITLE'		=> sprintf($Lang->data['stat_from_to_date'], $month_names[$dfrom_info['month'] - 1] .' '. $dfrom_info['year'], $month_names[$dto_info['month'] - 1] .' '. $dto_info['year'] ),
			"L_DATE_FROM"			=> $Lang->data["stat_date_from"],
			"L_DATE_TO"				=> $Lang->data["stat_date_to"],
			"L_MONTHS"				=> $Lang->data["stat_months"],
			"L_VISITORS"			=> $Lang->data["stat_visitors"],
			"L_TOTAL_VISITORS"		=> $Lang->data["stat_total_visitors"],
			"L_BUTTON_VIEW"			=> $Lang->data["stat_button_view"],
		));
	}

	function view_stat_days(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$Info->tpl_main		= "statistic_days";
		$timezone	= $Info->option['timezone'] * 3600;
		$today		= getdate(CURRENT_TIME + $timezone);

		//Filter -------------------------
		//Date from
		$dfrom_info	= $this->get_date_info($this->filter['date_from']);
		if ( ($dfrom_info['day'] < 1) || ($dfrom_info['day'] > 31) ){
			$dfrom_info['day']	= 1;
		}
		if ( ($dfrom_info['month'] < 1) || ($dfrom_info['month'] > 12) ){
			$dfrom_info['month']	= $today['mon'] - 3;
		}
		if ( ($dfrom_info['year'] < 1) || ($dfrom_info['year'] > 9999) ){
			$dfrom_info['year']		= $today['year'];
		}
		if ( $dfrom_info['month'] < 1 ){
			$dfrom_info['month']	+=	12;
			$dfrom_info['year']--;
		}

		//Date to
		$dto_info	= $this->get_date_info($this->filter['date_to']);
		if ( ($dto_info['day'] < 1) || ($dto_info['day'] > 31) ){
			$dto_info['day']		= 31;
		}
		if ( ($dto_info['month'] < 1) || ($dto_info['month'] > 12) ){
			$dto_info['month']		= $today['mon'];
		}
		if ( ($dto_info['year'] < 1) || ($dto_info['year'] > 9999) ){
			$dto_info['year']		= $today['year'];
		}

		//Check dates
		while ( ($dto_info['day'] > 0) && !$Func->check_anydate($dto_info['month'], $dto_info['day'], $dto_info['year']) ){
			$dto_info['day']--;
		}
		if ( ($dto_info['year'] < $dfrom_info['year']) || (($dto_info['year'] == $dfrom_info['year']) && ($dto_info['month'] < $dfrom_info['month'])) ){
			$dfrom_info['month']	= $today['mon'] - 3;
			$dto_info['year']		= $today['year'];
			if ( $dfrom_info['month'] < 1 ){
				$dfrom_info['month']	+=	12;
				$dfrom_info['year']--;
			}
			$dto_info['month']		= $today['mon'];
			$dto_info['year']		= $today['year'];
		}
		$date_from	= $dfrom_info['year'] .'-'. $dfrom_info['month'] .'-'. $dfrom_info['day'];
		$date_to	= $dto_info['year'] .'-'. $dto_info['month'] .'-'. $dto_info['day'];
		//--------------------------------

		//Get stat info
		$DB->query("SELECT WEEKDAY(stat_date) AS wday, sum(day_visitors) AS wday_visitors FROM ". $DB->prefix ."stat_day WHERE stat_date>='". $date_from ."' AND stat_date<='". $date_to ."' GROUP BY wday ORDER BY wday ASC");
		$stat_count		= $DB->num_rows();
		$stat_data		= $DB->fetch_all_array();
		$DB->free_result();

		//Count total visitors
		$total_visitors		= 0;
		for ($i=0; $i<$stat_count; $i++){
			$total_visitors	+= $stat_data[$i]['wday_visitors'];
		}

		//List stats
		$week_names		= explode(',', $Lang->data['general_date_day']);
		for ($i=0; $i<7; $i++){
			$flag	= -1;
			for ($j=0; $j<$stat_count; $j++){
				if ( $stat_data[$j]["wday"] == $i ){
					$flag	= $j;
					break;
				}
			}

			if ( $flag != -1 ){
				$percent		= !$total_visitors ? '0%' : round(($stat_data[$flag]['wday_visitors'] * 100) / $total_visitors, 1) .'%';
				$Template->set_block_vars("statrow", array(
					"WDAY"			=> isset($week_names[$stat_data[$flag]["wday"]]) ? trim($week_names[$stat_data[$flag]["wday"]]) : $stat_data[$flag]["wday"],
					"VISITORS"		=> $stat_data[$flag]["wday_visitors"],
					"PERCENT"		=> $percent,
				));
			}
			else{
				$Template->set_block_vars("statrow", array(
					"WDAY"			=> isset($week_names[$i]) ? trim($week_names[$i]) : $i,
					"VISITORS"		=> 0,
					"PERCENT"		=> '0%',
				));
			}
		}

		$month_names	= explode(',', $Lang->data['general_date_month']);
		$dfrom_day		= sprintf('%02d', $dfrom_info['day']);
		$dto_day		= sprintf('%02d', $dto_info['day']);
		$Template->set_vars(array(
			'TOTAL_VISITORS'		=> $total_visitors,
			'FDATE_FROM'			=> $this->make_date_string($dfrom_info['month'], $dfrom_info['day'], $dfrom_info['year']),
			'FDATE_TO'				=> $this->make_date_string($dto_info['month'], $dto_info['day'], $dto_info['year']),
			"L_PAGE_TITLE"			=> $Lang->data["menu_admin"] . $Lang->data['general_arrow'] . $Lang->data["menu_admin_stat"] . $Lang->data['general_arrow'] . $Lang->data["stat_report_days"],
			'L_REPORT_TITLE'		=> sprintf($Lang->data['stat_from_to_date'], $dfrom_day .' '. $month_names[$dfrom_info['month'] - 1] .' '. $dfrom_info['year'], $dto_day .' '. $month_names[$dto_info['month'] - 1] .' '. $dto_info['year'] ),
			"L_DATE_FROM"			=> $Lang->data["stat_date_from"],
			"L_DATE_TO"				=> $Lang->data["stat_date_to"],
			"L_DAYS"				=> $Lang->data["stat_days"],
			"L_VISITORS"			=> $Lang->data["stat_visitors"],
			"L_TOTAL_VISITORS"		=> $Lang->data["stat_total_visitors"],
			"L_BUTTON_VIEW"			=> $Lang->data["stat_button_view"],
		));
	}

	function view_stat_hours(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$Info->tpl_main		= "statistic_hours";
		$timezone	= $Info->option['timezone'] * 3600;
		$today		= getdate(CURRENT_TIME + $timezone);

		//Filter -------------------------
		//Date from
		$dfrom_info	= $this->get_date_info($this->filter['date_from']);
		if ( ($dfrom_info['day'] < 1) || ($dfrom_info['day'] > 31) ){
			$dfrom_info['day']	= 1;
		}
		if ( ($dfrom_info['month'] < 1) || ($dfrom_info['month'] > 12) ){
			$dfrom_info['month']	= $today['mon'] - 3;
		}
		if ( ($dfrom_info['year'] < 1) || ($dfrom_info['year'] > 9999) ){
			$dfrom_info['year']		= $today['year'];
		}
		if ( $dfrom_info['month'] < 1 ){
			$dfrom_info['month']	+=	12;
			$dfrom_info['year']--;
		}

		//Date to
		$dto_info	= $this->get_date_info($this->filter['date_to']);
		if ( ($dto_info['day'] < 1) || ($dto_info['day'] > 31) ){
			$dto_info['day']		= 31;
		}
		if ( ($dto_info['month'] < 1) || ($dto_info['month'] > 12) ){
			$dto_info['month']		= $today['mon'];
		}
		if ( ($dto_info['year'] < 1) || ($dto_info['year'] > 9999) ){
			$dto_info['year']		= $today['year'];
		}

		//Check dates
		while ( ($dto_info['day'] > 0) && !$Func->check_anydate($dto_info['month'], $dto_info['day'], $dto_info['year']) ){
			$dto_info['day']--;
		}
		if ( ($dto_info['year'] < $dfrom_info['year']) || (($dto_info['year'] == $dfrom_info['year']) && ($dto_info['month'] < $dfrom_info['month'])) ){
			$dfrom_info['month']	= $today['mon'] - 3;
			$dto_info['year']		= $today['year'];
			if ( $dfrom_info['month'] < 1 ){
				$dfrom_info['month']	+=	12;
				$dfrom_info['year']--;
			}
			$dto_info['month']		= $today['mon'];
			$dto_info['year']		= $today['year'];
		}
		$date_from	= $dfrom_info['year'] .'-'. $dfrom_info['month'] .'-'. $dfrom_info['day'];
		$date_to	= $dto_info['year'] .'-'. $dto_info['month'] .'-'. $dto_info['day'];
		//--------------------------------

		//Get stat info
		$DB->query("SELECT stat_hour, SUM(hour_visitors) AS dhour_visitors FROM ". $DB->prefix ."stat_hour WHERE stat_date>='". $date_from ."' AND stat_date<='". $date_to ."' GROUP BY stat_hour ORDER BY stat_hour ASC");
		$stat_count		= $DB->num_rows();
		$stat_data		= $DB->fetch_all_array();
		$DB->free_result();

		//Count total visitors
		$total_visitors		= 0;
		for ($i=0; $i<$stat_count; $i++){
			$total_visitors	+= $stat_data[$i]['dhour_visitors'];
		}

		//List stats
		for ($i=0; $i<24; $i++){
			$flag	= -1;
			for ($j=0; $j<$stat_count; $j++){
				if ( $stat_data[$j]["stat_hour"] == $i ){
					$flag	= $j;
					break;
				}
			}

			if ( $flag != -1 ){
				$percent		= !$total_visitors ? '0%' : round(($stat_data[$flag]['dhour_visitors'] * 100) / $total_visitors, 1) .'%';
				$Template->set_block_vars("statrow", array(
					"HOUR"			=> sprintf('%02d', $stat_data[$flag]["stat_hour"]),
					"VISITORS"			=> $stat_data[$flag]["dhour_visitors"],
					"PERCENT"		=> $percent,
				));
			}
			else{
				$Template->set_block_vars("statrow", array(
					"HOUR"			=> sprintf('%02d', $i),
					"VISITORS"			=> 0,
					"PERCENT"		=> '0%',
				));
			}
		}

		$month_names	= explode(',', $Lang->data['general_date_month']);
		$dfrom_day		= sprintf('%02d', $dfrom_info['day']);
		$dto_day		= sprintf('%02d', $dto_info['day']);
		$Template->set_vars(array(
			'TOTAL_VISITORS'			=> $total_visitors,
			'FDATE_FROM'			=> $this->make_date_string($dfrom_info['month'], $dfrom_info['day'], $dfrom_info['year']),
			'FDATE_TO'				=> $this->make_date_string($dto_info['month'], $dto_info['day'], $dto_info['year']),
			"L_PAGE_TITLE"			=> $Lang->data["menu_admin"] . $Lang->data['general_arrow'] . $Lang->data["menu_admin_stat"] . $Lang->data['general_arrow'] . $Lang->data["stat_report_hours"],
			'L_REPORT_TITLE'		=> sprintf($Lang->data['stat_from_to_date'], $dfrom_day .' '. $month_names[$dfrom_info['month'] - 1] .' '. $dfrom_info['year'], $dto_day .' '. $month_names[$dto_info['month'] - 1] .' '. $dto_info['year'] ),
			"L_DATE_FROM"			=> $Lang->data["stat_date_from"],
			"L_DATE_TO"				=> $Lang->data["stat_date_to"],
			"L_HOURS"				=> $Lang->data["stat_hours"],
			"L_VISITORS"				=> $Lang->data["stat_visitors"],
			"L_TOTAL_VISITORS"			=> $Lang->data["stat_total_visitors"],
			"L_BUTTON_VIEW"			=> $Lang->data["stat_button_view"],
		));
	}

	function view_stat_countries(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$Info->tpl_main		= "statistic_countries";
		$timezone	= $Info->option['timezone'] * 3600;
		$today		= getdate(CURRENT_TIME + $timezone);

		//Filter -------------------------
		//Date from
		$dfrom_info	= $this->get_date_info($this->filter['date_from']);
		if ( ($dfrom_info['day'] < 1) || ($dfrom_info['day'] > 31) ){
			$dfrom_info['day']	= 1;
		}
		if ( ($dfrom_info['month'] < 1) || ($dfrom_info['month'] > 12) ){
			$dfrom_info['month']	= $today['mon'] - 3;
		}
		if ( ($dfrom_info['year'] < 1) || ($dfrom_info['year'] > 9999) ){
			$dfrom_info['year']		= $today['year'];
		}
		if ( $dfrom_info['month'] < 1 ){
			$dfrom_info['month']	+=	12;
			$dfrom_info['year']--;
		}

		//Date to
		$dto_info	= $this->get_date_info($this->filter['date_to']);
		if ( ($dto_info['day'] < 1) || ($dto_info['day'] > 31) ){
			$dto_info['day']		= 31;
		}
		if ( ($dto_info['month'] < 1) || ($dto_info['month'] > 12) ){
			$dto_info['month']		= $today['mon'];
		}
		if ( ($dto_info['year'] < 1) || ($dto_info['year'] > 9999) ){
			$dto_info['year']		= $today['year'];
		}

		//Check dates
		while ( ($dto_info['day'] > 0) && !$Func->check_anydate($dto_info['month'], $dto_info['day'], $dto_info['year']) ){
			$dto_info['day']--;
		}
		if ( ($dto_info['year'] < $dfrom_info['year']) || (($dto_info['year'] == $dfrom_info['year']) && ($dto_info['month'] < $dfrom_info['month'])) ){
			$dfrom_info['month']	= $today['mon'] - 3;
			$dto_info['year']		= $today['year'];
			if ( $dfrom_info['month'] < 1 ){
				$dfrom_info['month']	+=	12;
				$dfrom_info['year']--;
			}
			$dto_info['month']		= $today['mon'];
			$dto_info['year']		= $today['year'];
		}
		$date_from	= $dfrom_info['year'] .'-'. $dfrom_info['month'] .'-'. $dfrom_info['day'];
		$date_to	= $dto_info['year'] .'-'. $dto_info['month'] .'-'. $dto_info['day'];
		//--------------------------------

		//Get stat info
		$DB->query("SELECT country_id, SUM(country_visitors) AS tcountry_visitors FROM ". $DB->prefix ."stat_country WHERE stat_date>='". $date_from ."' AND stat_date<='". $date_to ."' GROUP BY country_id ORDER BY country_id ASC");
		$stat_count		= $DB->num_rows();
		$stat_data		= $DB->fetch_all_array();
		$DB->free_result();

		//Count total visitors
		$total_visitors		= 0;
		$where_sql		= "WHERE c2='..'";
		for ($i=0; $i<$stat_count; $i++){
			$total_visitors	+= $stat_data[$i]['tcountry_visitors'];
			$where_sql	.= " OR c2='". $stat_data[$i]['country_id'] ."'";
		}

		//Get country names
		$DB->query("SELECT c2, country FROM ". $DB->prefix ."country_code ". $where_sql);
		$country_info	= array();
		if ( $DB->num_rows() ){
			while ($tmp_info = $DB->fetch_array() ){
				$country_info[$tmp_info['c2']]	= $tmp_info['country'];
			}
		}

		//List stats
		for ($i=0; $i<$stat_count; $i++){
			$country	= $stat_data[$i]["country_id"];
			$percent	= !$total_visitors ? '0%' : round(($stat_data[$i]['tcountry_visitors'] * 100) / $total_visitors, 1) .'%';
			$Template->set_block_vars("statrow", array(
				"COUNTRY"		=> ($country == '..') ? $Lang->data['stat_unknown'] : (isset($country_info[$country]) ? $country_info[$country] : $country),
				"FLAG"			=> ($country == '..') ? '' : ' <img src="images/flags/'. $country .'.gif" border="0">',
				"VISITORS"			=> $stat_data[$i]["tcountry_visitors"],
				"PERCENT"		=> $percent,
			));
		}

		$month_names	= explode(',', $Lang->data['general_date_month']);
		$dfrom_day		= sprintf('%02d', $dfrom_info['day']);
		$dto_day		= sprintf('%02d', $dto_info['day']);
		$Template->set_vars(array(
			'TOTAL_VISITORS'		=> $total_visitors,
			'FDATE_FROM'			=> $this->make_date_string($dfrom_info['month'], $dfrom_info['day'], $dfrom_info['year']),
			'FDATE_TO'				=> $this->make_date_string($dto_info['month'], $dto_info['day'], $dto_info['year']),
			"L_PAGE_TITLE"			=> $Lang->data["menu_admin"] . $Lang->data['general_arrow'] . $Lang->data["menu_admin_stat"] . $Lang->data['general_arrow'] . $Lang->data["stat_report_countries"],
			'L_REPORT_TITLE'		=> sprintf($Lang->data['stat_from_to_date'], $dfrom_day .' '. $month_names[$dfrom_info['month'] - 1] .' '. $dfrom_info['year'], $dto_day .' '. $month_names[$dto_info['month'] - 1] .' '. $dto_info['year'] ),
			"L_DATE_FROM"			=> $Lang->data["stat_date_from"],
			"L_DATE_TO"				=> $Lang->data["stat_date_to"],
			"L_COUNTRIES"			=> $Lang->data["stat_countries"],
			"L_VISITORS"			=> $Lang->data["stat_visitors"],
			"L_TOTAL_VISITORS"		=> $Lang->data["stat_total_visitors"],
			"L_BUTTON_VIEW"			=> $Lang->data["stat_button_view"],
		));
	}

	function view_stat_browsers(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$Info->tpl_main		= "statistic_browsers";
		$timezone	= $Info->option['timezone'] * 3600;
		$today		= getdate(CURRENT_TIME + $timezone);

		//Filter -------------------------
		//Date from
		$dfrom_info	= $this->get_date_info($this->filter['date_from']);
		if ( ($dfrom_info['day'] < 1) || ($dfrom_info['day'] > 31) ){
			$dfrom_info['day']	= 1;
		}
		if ( ($dfrom_info['month'] < 1) || ($dfrom_info['month'] > 12) ){
			$dfrom_info['month']	= $today['mon'] - 3;
		}
		if ( ($dfrom_info['year'] < 1) || ($dfrom_info['year'] > 9999) ){
			$dfrom_info['year']		= $today['year'];
		}
		if ( $dfrom_info['month'] < 1 ){
			$dfrom_info['month']	+=	12;
			$dfrom_info['year']--;
		}

		//Date to
		$dto_info	= $this->get_date_info($this->filter['date_to']);
		if ( ($dto_info['day'] < 1) || ($dto_info['day'] > 31) ){
			$dto_info['day']		= 31;
		}
		if ( ($dto_info['month'] < 1) || ($dto_info['month'] > 12) ){
			$dto_info['month']		= $today['mon'];
		}
		if ( ($dto_info['year'] < 1) || ($dto_info['year'] > 9999) ){
			$dto_info['year']		= $today['year'];
		}

		//Check dates
		while ( ($dto_info['day'] > 0) && !$Func->check_anydate($dto_info['month'], $dto_info['day'], $dto_info['year']) ){
			$dto_info['day']--;
		}
		if ( ($dto_info['year'] < $dfrom_info['year']) || (($dto_info['year'] == $dfrom_info['year']) && ($dto_info['month'] < $dfrom_info['month'])) ){
			$dfrom_info['month']	= $today['mon'] - 3;
			$dto_info['year']		= $today['year'];
			if ( $dfrom_info['month'] < 1 ){
				$dfrom_info['month']	+=	12;
				$dfrom_info['year']--;
			}
			$dto_info['month']		= $today['mon'];
			$dto_info['year']		= $today['year'];
		}
		$date_from	= $dfrom_info['year'] .'-'. $dfrom_info['month'] .'-'. $dfrom_info['day'];
		$date_to	= $dto_info['year'] .'-'. $dto_info['month'] .'-'. $dto_info['day'];
		//--------------------------------

		//Get stat info
		$DB->query("SELECT browser_name, SUM(browser_visitors) AS tbrowser_visitors FROM ". $DB->prefix ."stat_browser WHERE stat_date>='". $date_from ."' AND stat_date<='". $date_to ."' GROUP BY browser_name ORDER BY browser_name ASC");
		$stat_count		= $DB->num_rows();
		$stat_data		= $DB->fetch_all_array();
		$DB->free_result();

		//Count total visitors
		$total_visitors		= 0;
		for ($i=0; $i<$stat_count; $i++){
			$total_visitors	+= $stat_data[$i]['tbrowser_visitors'];
		}

		//List stats
		for ($i=0; $i<$stat_count; $i++){
			$percent	= !$total_visitors ? '0%' : round(($stat_data[$i]['tbrowser_visitors'] * 100) / $total_visitors, 1) .'%';
			$Template->set_block_vars("statrow", array(
				"BROWSER"		=> ($stat_data[$i]["browser_name"] == '..') ? $Lang->data['stat_unknown'] : $stat_data[$i]["browser_name"],
				"VISITORS"			=> $stat_data[$i]["tbrowser_visitors"],
				"PERCENT"		=> $percent,
			));
		}

		$month_names	= explode(',', $Lang->data['general_date_month']);
		$dfrom_day		= sprintf('%02d', $dfrom_info['day']);
		$dto_day		= sprintf('%02d', $dto_info['day']);
		$Template->set_vars(array(
			'TOTAL_VISITORS'			=> $total_visitors,
			'FDATE_FROM'			=> $this->make_date_string($dfrom_info['month'], $dfrom_info['day'], $dfrom_info['year']),
			'FDATE_TO'				=> $this->make_date_string($dto_info['month'], $dto_info['day'], $dto_info['year']),
			"L_PAGE_TITLE"			=> $Lang->data["menu_admin"] . $Lang->data['general_arrow'] . $Lang->data["menu_admin_stat"] . $Lang->data['general_arrow'] . $Lang->data["stat_report_browsers"],
			'L_REPORT_TITLE'		=> sprintf($Lang->data['stat_from_to_date'], $dfrom_day .' '. $month_names[$dfrom_info['month'] - 1] .' '. $dfrom_info['year'], $dto_day .' '. $month_names[$dto_info['month'] - 1] .' '. $dto_info['year'] ),
			"L_DATE_FROM"			=> $Lang->data["stat_date_from"],
			"L_DATE_TO"				=> $Lang->data["stat_date_to"],
			"L_BROWSERS"			=> $Lang->data["stat_browsers"],
			"L_VISITORS"				=> $Lang->data["stat_visitors"],
			"L_TOTAL_VISITORS"			=> $Lang->data["stat_total_visitors"],
			"L_BUTTON_VIEW"			=> $Lang->data["stat_button_view"],
		));
	}

	function view_stat_referers(){
		global $Session, $DB, $Template, $Lang, $Info, $Func;

		$Info->tpl_main		= "statistic_referers";
		$timezone	= $Info->option['timezone'] * 3600;
		$today		= getdate(CURRENT_TIME + $timezone);

		//Filter -------------------------
		//Date from
		$dfrom_info	= $this->get_date_info($this->filter['date_from']);
		if ( ($dfrom_info['day'] < 1) || ($dfrom_info['day'] > 31) ){
			$dfrom_info['day']	= 1;
		}
		if ( ($dfrom_info['month'] < 1) || ($dfrom_info['month'] > 12) ){
			$dfrom_info['month']	= $today['mon'] - 3;
		}
		if ( ($dfrom_info['year'] < 1) || ($dfrom_info['year'] > 9999) ){
			$dfrom_info['year']		= $today['year'];
		}
		if ( $dfrom_info['month'] < 1 ){
			$dfrom_info['month']	+=	12;
			$dfrom_info['year']--;
		}

		//Date to
		$dto_info	= $this->get_date_info($this->filter['date_to']);
		if ( ($dto_info['day'] < 1) || ($dto_info['day'] > 31) ){
			$dto_info['day']		= 31;
		}
		if ( ($dto_info['month'] < 1) || ($dto_info['month'] > 12) ){
			$dto_info['month']		= $today['mon'];
		}
		if ( ($dto_info['year'] < 1) || ($dto_info['year'] > 9999) ){
			$dto_info['year']		= $today['year'];
		}

		//Check dates
		while ( ($dto_info['day'] > 0) && !$Func->check_anydate($dto_info['month'], $dto_info['day'], $dto_info['year']) ){
			$dto_info['day']--;
		}
		if ( ($dto_info['year'] < $dfrom_info['year']) || (($dto_info['year'] == $dfrom_info['year']) && ($dto_info['month'] < $dfrom_info['month'])) ){
			$dfrom_info['month']	= $today['mon'] - 3;
			$dto_info['year']		= $today['year'];
			if ( $dfrom_info['month'] < 1 ){
				$dfrom_info['month']	+=	12;
				$dfrom_info['year']--;
			}
			$dto_info['month']		= $today['mon'];
			$dto_info['year']		= $today['year'];
		}
		$date_from	= $dfrom_info['year'] .'-'. $dfrom_info['month'] .'-'. $dfrom_info['day'];
		$date_to	= $dto_info['year'] .'-'. $dto_info['month'] .'-'. $dto_info['day'];
		//--------------------------------

		//Get stat info
		$DB->query("SELECT referer_url, SUM(referer_visitors) AS treferer_visitors FROM ". $DB->prefix ."stat_referer WHERE stat_date>='". $date_from ."' AND stat_date<='". $date_to ."' GROUP BY referer_url ORDER BY referer_url ASC");
		$stat_count		= $DB->num_rows();
		$stat_data		= $DB->fetch_all_array();
		$DB->free_result();

		//Count total visitors
		$total_visitors		= 0;
		for ($i=0; $i<$stat_count; $i++){
			$total_visitors	+= $stat_data[$i]['treferer_visitors'];
		}

		//List stats
		for ($i=0; $i<$stat_count; $i++){
			$percent	= !$total_visitors ? '0%' : round(($stat_data[$i]['treferer_visitors'] * 100) / $total_visitors, 1) .'%';
			$Template->set_block_vars("statrow", array(
				"REFERER"		=> ($stat_data[$i]["referer_url"] == '..') ? $Lang->data['stat_unknown'] : $stat_data[$i]["referer_url"],
				"VISITORS"			=> $stat_data[$i]["treferer_visitors"],
				"PERCENT"		=> $percent,
			));
		}

		$month_names	= explode(',', $Lang->data['general_date_month']);
		$dfrom_day		= sprintf('%02d', $dfrom_info['day']);
		$dto_day		= sprintf('%02d', $dto_info['day']);
		$Template->set_vars(array(
			'TOTAL_VISITORS'			=> $total_visitors,
			'FDATE_FROM'			=> $this->make_date_string($dfrom_info['month'], $dfrom_info['day'], $dfrom_info['year']),
			'FDATE_TO'				=> $this->make_date_string($dto_info['month'], $dto_info['day'], $dto_info['year']),
			"L_PAGE_TITLE"			=> $Lang->data["menu_admin"] . $Lang->data['general_arrow'] . $Lang->data["menu_admin_stat"] . $Lang->data['general_arrow'] . $Lang->data["stat_report_referers"],
			'L_REPORT_TITLE'		=> sprintf($Lang->data['stat_from_to_date'], $dfrom_day .' '. $month_names[$dfrom_info['month'] - 1] .' '. $dfrom_info['year'], $dto_day .' '. $month_names[$dto_info['month'] - 1] .' '. $dto_info['year'] ),
			"L_DATE_FROM"			=> $Lang->data["stat_date_from"],
			"L_DATE_TO"				=> $Lang->data["stat_date_to"],
			"L_REFERERS"			=> $Lang->data["stat_referers"],
			"L_VISITORS"				=> $Lang->data["stat_visitors"],
			"L_TOTAL_VISITORS"			=> $Lang->data["stat_total_visitors"],
			"L_BUTTON_VIEW"			=> $Lang->data["stat_button_view"],
		));
	}

	function get_date_info($date_string, $get_day = 1){
		$date_info	= explode($this->date_separate, $date_string);
		$count		= 0;
		$result		= array();

		$result['month']	= isset($date_info[$count]) ? intval($date_info[$count]) : 0;
		$count++;

		if ( $get_day ){
			$result['day']	= isset($date_info[$count]) ? intval($date_info[$count]) : 0;
			$count++;
		}

		$result['year']		= isset($date_info[$count]) ? intval($date_info[$count]) : 0;
		if ( ($result['year'] > 1) && ($result['year'] < 100) ){
			$result['year']	+= 2000;
		}

		return $result;
	}

	function make_date_string($month, $day, $year){
		if ( $day > 0 ){
			return sprintf('%02d', $month) . $this->date_separate . sprintf('%02d', $day) . $this->date_separate . sprintf('%04d', $year);
		}
		else{
			return sprintf('%02d', $month) . $this->date_separate . sprintf('%04d', $year);
		}
	}
}
?>