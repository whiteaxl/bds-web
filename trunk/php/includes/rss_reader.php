<?php
/* =============================================================== *\
|		Module name: RSS Reader										|
|		Module version: 1.2											|
|		Begin: 30 July 2006											|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
	die('Hacking attempt!');
}

class Rss_Reader
{
	//Input variables
	var $output_encoding	= "utf-8";
	var $input_encoding		= "";
	var $detect_encoding	= true;
	var $convert_charset	= true;
	var $default_encodings	= array('utf-8', 'us-ascii', 'iso-8859-1');
	var $source_content		= "";

	//Flags
	var $in_channel			= false;
	var $in_item			= false;
	var $in_image			= false;
	var $current_channel	= -1;
	var $current_item		= -1;
	var $tagname			= "";
	var $attribs			= array();

	//Data
	var $rss_channels		= array();
	var $rss_items			= array();
	var $rss_images			= array();

	//Temporary data
	var $tmp_channels		= array();
	var $tmp_items			= array();
	var $tmp_images			= array();

	//Objects
	var $parser;
	var $snoopy;

	function Rss_Reader(){
		require_once('./includes/snoopy.php');
		$this->snoopy	= new Snoopy;
	}

	function reset_rss(){
		$this->source_content	= "";
		$this->in_channel		= false;
		$this->in_item			= false;
		$this->in_image			= false;
		$this->current_channel	= -1;
		$this->current_item		= -1;
		$this->tagname			= "";
		$this->rss_channels		= array();
		$this->rss_items		= array();
		$this->rss_images		= array();
	}

	function set_auth_info($username, $password){
		$this->snoopy->user	= $username;
		$this->snoopy->pass	= $password;
	}

	function fetch_rss($url, $username = "", $password = "", $output_encoding = "utf-8", $input_encoding = "", $detect_encoding = true, $convert_charset = "true"){
		$this->output_encoding	= $output_encoding;
		$this->input_encoding	= $input_encoding;
		$this->detect_encoding	= $detect_encoding;
		$this->convert_charset	= $convert_charset;
		$this->set_auth_info($username, $password);

		//Check XML support
		if ( !function_exists('xml_parser_create') ){
			$this->halt("Failed to load PHP's XML Extension. Please check http://www.php.net/manual/en/ref.xml.php for more detail.");
			return false;
		}

		//Get remote content
		$this->snoopy->fetch(html_entity_decode($url));
		if ( !$this->snoopy->results ){
			return false;
		}
		$this->source_content	= $this->snoopy->results;

/*
		$str_info	= explode("\n", $this->source_content);
		reset($str_info);
		while (list($key, $line) = each($str_info)){
			$str_info[$key]	= trim($line);
		}
		$this->source_content	= implode("\n", $str_info);
*/

		//Create xml parser
		$this->create_parser();
		if ( !is_resource($this->parser) ){
			$this->halt("Failed to create an instance of PHP's XML parser.");
			return false;
		}

		//Reset flag variables
		$this->in_channel	= false;
		$this->in_item		= false;
		$this->in_image		= false;

//		xml_set_object($this->parser, &$this);
		xml_set_object($this->parser, $this);
		xml_set_element_handler($this->parser, 'start_element', 'end_element');
        xml_set_character_data_handler($this->parser, 'parse_cdata');

		$status	= xml_parse($this->parser, $this->source_content);

		if ( !$status ){
			$error_code	= xml_get_error_code($this->parser);
			if ($error_code != XML_ERROR_NONE){
				$error_xml	= xml_error_string($error_code);
				$error_line	= xml_get_current_line_number($this->parser);
				$error_col	= xml_get_current_column_number($this->parser);
				$this->halt("$error_xml at line $error_line, column $error_col");
			}
		}

		xml_parser_free($this->parser);
	}

	function start_element($parser, $name, $attribs){
		$name			= strtoupper($name);
		$this->tagname	= $name;
		$this->attribs	= $attribs;

		switch ($name){
			case "CHANNEL":
				$this->in_channel		= true;
				$this->in_item			= false;
				$this->in_image			= false;
				$this->current_channel++;
				$this->current_item		= -1;
				break;
			case "ITEM":
				$this->in_item			= true;
				$this->in_image			= false;
				$this->current_item++;
				break;
			case "IMAGE":
				$this->in_image			= true;
				$this->in_item			= false;
				break;
		}
	}

	function end_element($parser, $name){
		$name		= strtoupper($name);
		switch ($name){
			case "IMAGE":
				$this->rss_images[$this->current_channel]	= $this->tmp_images;
				$this->tmp_images	= array();//Reset data
				break;
			case "ITEM":
				$this->rss_items[$this->current_channel][$this->current_item]	= $this->tmp_items;
				$this->rss_items[$this->current_channel][$this->current_item]['unix_date']    = (isset($this->tmp_items['date']) && $this->tmp_items['date']) ? @strtotime($this->tmp_items['date']) : CURRENT_TIME;
				//Check date time
				$tmpdate	= 31554000; //1971
				if ( ($this->rss_items[$this->current_channel][$this->current_item]['unix_date'] <= $tmpdate) && isset($this->tmp_items['date']) ){
					$this->rss_items[$this->current_channel][$this->current_item]['unix_date']	= $this->get_date_time($this->tmp_items['date']);

					if ( !$this->rss_items[$this->current_channel][$this->current_item]['unix_date'] ){
						$this->rss_items[$this->current_channel][$this->current_item]['unix_date']	= CURRENT_TIME;
					}
				}
				$this->tmp_items	= array();//Reset data
				break;
			case "CHANNEL":
				$this->rss_channels[$this->current_channel]	= $this->tmp_channels;
				$this->rss_channels[$this->current_channel]['unix_date']	= (isset($this->tmp_channels['date']) && $this->tmp_channels['date']) ? @strtotime($this->tmp_channels['date']) : CURRENT_TIME;
				//Check date time
				$tmpdate	= 31554000; //1971
				if ( ($this->rss_channels[$this->current_channel]['unix_date'] <= $tmpdate) && isset($this->tmp_channels['date']) ){
					$this->rss_channels[$this->current_channel]['unix_date']	= $this->get_date_time($this->tmp_channels['date']);
					if ( !$this->rss_channels[$this->current_channel]['unix_date'] ){
						$this->rss_channels[$this->current_channel]['unix_date']	= CURRENT_TIME;
					}
				}
				$this->tmp_channels	= array();//Reset data
				break;
		}
	}

	function parse_cdata($parser, $data){
		$data	= trim($data);
		$this->tagname	= strtoupper($this->tagname);

		if ( $this->in_image ){
			switch ($this->tagname){
				case "URL":
					$this->tmp_images['image']	= isset($this->tmp_images['image']) ? $this->tmp_images['image'] . $data : $data;
					break;
				case "TITLE":
					$this->tmp_images['title']	= isset($this->tmp_images['title']) ? $this->tmp_images['title'] . $data : $data;
					break;
				case "LINK":
					$this->tmp_images['link']	= isset($this->tmp_images['link']) ? $this->tmp_images['link'] . $data : $data;
					break;
				case "WIDTH":
					$this->tmp_images['width']	= isset($this->tmp_images['width']) ? $this->tmp_images['width'] . $data : $data;
					break;
				case "HEIGHT":
					$this->tmp_images['height']	= isset($this->tmp_images['height']) ? $this->tmp_images['height'] . $data : $data;
					break;
				case "DESCRIPTION":
					$this->tmp_images['desc']	= isset($this->tmp_images['desc']) ? $this->tmp_images['desc'] . $data : $data;
					break;
			}
		}

		else if ( $this->in_item ){
			switch ($this->tagname){
				case "TITLE":
					$this->tmp_items['title']	= isset($this->tmp_items['title']) ? $this->tmp_items['title'] . $data : $data;
					break;
				case "DESCRIPTION":
					$this->tmp_items['desc']	= isset($this->tmp_items['desc']) ? $this->tmp_items['desc'] . $data : $data;
					break;
				case "LINK":
					$this->tmp_items['link']	= isset($this->tmp_items['link']) ? $this->tmp_items['link'] . $data : $data;
					break;
				case "CONTENT":
					$this->tmp_items['content']	= isset($this->tmp_items['content']) ? $this->tmp_items['content'] . $data : $data;
					break;
				case "ENCLOSURE":
					if ( isset($this->attribs['URL']) && !empty($this->attribs['URL']) && isset($this->attribs['TYPE']) && (strpos($this->attribs['TYPE'], "image/") !== false) ){
//						$this->tmp_items['enclosure_image']	= isset($this->tmp_items['enclosure_image']) ? $this->tmp_items['enclosure_image'] . $this->attribs['URL'] : $this->attribs['URL'];
						$this->tmp_items['enclosure_image']	= $this->attribs['URL'];
					}
					break;
				case "DATE":
					$this->tmp_items['date']	= isset($this->tmp_items['date']) ? $this->tmp_items['date'] . $data : $data;
					break;
				case "PUBDATE":
					$this->tmp_items['date']	= isset($this->tmp_items['date']) ? $this->tmp_items['date'] . $data : $data;
					break;
				case "CATEGORY":
					$this->tmp_items['category']	= isset($this->tmp_items['category']) ? $this->tmp_items['category'] . $data : $data;
					break;
			}
		}

		else if ( $this->in_channel ){
			switch ($this->tagname){
				case "TITLE":
					$this->tmp_channels['title']	= isset($this->tmp_channels['title']) ? $this->tmp_channels['title'] . $data : $data;
					break;
				case "DESCRIPTION":
					$this->tmp_channels['desc']	= isset($this->tmp_channels['desc']) ? $this->tmp_channels['desc'] . $data : $data;
					break;
				case "LINK":
					$this->tmp_channels['link']	= isset($this->tmp_channels['link']) ? $this->tmp_channels['link'] . $data : $data;
					break;
				case "DATE":
					$this->tmp_channels['date']	= isset($this->tmp_channels['date']) ? $this->tmp_channels['date'] . $data : $data;
					break;
				case "PUBDATE":
					$this->tmp_channels['date']	= isset($this->tmp_channels['date']) ? $this->tmp_channels['date'] . $data : $data;
					break;
				case "LANGUAGE":
					$this->tmp_channels['language']	= isset($this->tmp_channels['language']) ? $this->tmp_channels['language'] . $data : $data;
					break;
			}
		}
	}

	function create_parser(){
		if ( substr(phpversion(),0,1) == 5 ){
			$this->php5_create_parser();
		}
		else {
			$this->php4_create_parser();
		}
		if ( $this->output_encoding && $this->convert_charset && in_array($this->output_encoding, $this->default_encodings) ){
			xml_parser_set_option($this->parser, XML_OPTION_TARGET_ENCODING, $this->output_encoding);
		}
	}

	//php5 have a good detection for input encodings
	function php5_create_parser() {
		if ( $this->input_encoding && !$this->detect_encoding ){
			$this->parser	= xml_parser_create($this->input_encoding);
		}
		else{
			$this->parser	= xml_parser_create();
		}
	}

	//php4 does not have a good detection. So we must detect ourselve
	function php4_create_parser() {
		if ( $this->input_encoding && !$this->detect_encoding ){
			$this->parser	= xml_parser_create($this->input_encoding);
			return true;
		}

		if ( !$this->input_encoding ){
			if ( preg_match('/<?xml.*encoding=[\'"](.*?)[\'"].*?>/m', $this->source_content, $m)) {
				$this->input_encoding	= isset($m[1]) ? strtolower($m[1]) : "";
			}
			else{
				$this->input_encoding	= 'utf-8';
			}
		}

		if ( in_array($this->input_encoding, $this->default_encodings) ){
			$this->parser	= xml_parser_create($this->input_encoding);
			return true;
		}

		if ($this->convert_charset){
			// The dectected encoding is not one of the simple encodings PHP knows
			// Attempt to use the iconv extension to cast the XML to a known encoding
			// @see http://php.net/iconv

			if ( function_exists('iconv') ){
				$encoded_source	= iconv($this->input_encoding, $this->output_encoding, $this->source_content);
				if ( $encoded_source){
					$this->source_content	= $encoded_source;
					$this->parser			= @xml_parser_create($this->output_encoding) or $this->myxml_error_recreate();
					return true;
				}
			}

			// iconv didn't work, try mb_convert_encoding
			// @see http://php.net/mbstring
			if ( function_exists('mb_convert_encoding') ){
				$encoded_source = mb_convert_encoding($this->source_content, $this->output_encoding, $this->input_encoding);
				if ( $encoded_source ){
					$this->source_content	= $encoded_source;
					$this->parser			= @xml_parser_create($this->output_encoding) or $this->myxml_error_recreate();
					return true;
				}
			}

			require_once('./includes/convert_charset.php');
			$convert	= new ConvertCharset();
			$this->source_content	= $convert->Convert($this->source_content, $this->input_encoding, $this->output_encoding, false);
			$this->parser			= @xml_parser_create($this->output_encoding) or $this->myxml_error_recreate();
		}
		else{
			$this->parser	= xml_parser_create($this->input_encoding);
		}
	}

	function get_date_time($str_date){
		//try to get date time with other patterns
		//Pattern: dd/mm/yyyy hours:minutes:seconds
		$newdate	= 0;
		if ( preg_match("!([0-9]{1,2})[\/\. -]+([0-9]{1,2})[\/\. -]+([0-9]{1,4})[, ]+([0-9]{1,2})[:]+([0-9]{1,2})[:]+([0-9]{1,2})!", $str_date, $matches) ){
			if ( sizeof($matches) == 7 ){
				$newdate		= @mktime($matches[4], $matches[5], $matches[6], $matches[2], $matches[1], $matches[3]);
			}
		}
		return $newdate;
	}

	function myxml_error_recreate(){
		$this->parser	= xml_parser_create("");
		return true;
	}

	function halt($msg){
		echo "<b>RSS Reader Error:</b>\n<br>$msg";
		die();
	}
}
?>