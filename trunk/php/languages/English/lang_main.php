<?php
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

class Lang_Global
{
	var $charset	= "UTF-8";
	var $data		= array();

	function Lang_Global(){
		//General
		$this->data['general_close']				= 'Close';
		$this->data['general_all']					= 'All';
		$this->data['general_yes']					= 'Yes';
		$this->data['general_no']					= 'No';
		$this->data['general_choose']				= 'Choose';
		$this->data['general_cat']					= 'Category';
		$this->data['general_back']					= 'Back';
		$this->data['general_view_more']			= 'More ++';
		$this->data['general_page']					= 'Page';
		$this->data['general_page_prev']			= 'Previous Page';
		$this->data['general_page_next']			= 'Next Page';
		$this->data['general_go']					= '     GO     ';
		$this->data['general_message_die']			= 'Display Message';
		$this->data['general_number']				= 'Security Number';

		//General error
		$this->data['general_error_not_full']		= 'Please complete all required fields!';
		$this->data['general_error_email']			= 'Email is invalid!';
		$this->data['general_error_login_number']	= 'Security number does not match!';

		//Pagination
		$this->data['page_previous']				= '&lt;&lt; Previous';
		$this->data['page_next']					= 'Next &gt;&gt;';
		$this->data['page_title_previous']			= 'Previous Page';
		$this->data['page_title_first']				= 'First Page';
		$this->data['page_title_last']				= 'Last Page';
		$this->data['page_title_next']				= 'Next Page';

		//General date
		$this->data['general_date_day']				= 'Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday';
		$this->data['general_date_day_short']		= 'Sun, Mon, Tue, Wed, Thu, Fri, Sat';
		$this->data['general_date_month']			= 'January, February, March, April, May, June, July, August, September, October, November, December';
		$this->data['general_date_month_short']		= 'Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec';
	}
}
?>