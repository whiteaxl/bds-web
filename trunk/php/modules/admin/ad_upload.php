<?php
/* =============================================================== *\
|		Module name:      Upload									|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
define('DIR_EDITOR_POPUP', 'wysiwyg/popup');

$AdminUpload = new Admin_Upload;

class Admin_Upload
{
	function Admin_Upload(){
		global $Info, $Template;

		$Template->set_root(DIR_EDITOR_POPUP);
		$Info->tpl_header	= "";
		$Info->tpl_footer	= "";
		$Info->tpl_main		= "";

		$this->upload();
	}

	function upload($client_file = 'ClientFile'){
		global $Info, $Template, $Lang, $Func, $File;

		$user_file		= isset($_FILES[$client_file]["name"]) ? $_FILES[$client_file]["name"] : '';
		$html_id		= isset($_POST["html_id"]) ? $_POST["html_id"] : '';
		$img_result		= isset($_POST["img_result"]) ? $_POST["img_result"] : '';
		$user_id		= $Info->user_info['user_id'];
		$config_type	= $Info->code;

		if ( empty($user_file) ){
			$this->halt($Lang->data['upload_error_not_file']);
			return false;
		}

		//Get file type
		$start		= strrpos($user_file, ".");
		$filetype	= strtolower(substr($user_file, $start));
		if ( !$File->check_filetype($filetype, $config_type) ){
			$this->halt(sprintf($Lang->data['upload_error_file_type'], $filetype));
			return false;
		}

		//Template files
		$Template->set_files(array(
			"WAITING_BEGIN"		=> "upload_waiting_begin.html",
			"WAITING_END"		=> "upload_waiting_end.html",
		));

		//Header
		$Template->show("WAITING_BEGIN");

		//Upload file
		$file_url	= "upload/u". $user_id ."_". $user_file;
		$File->upload_file($_FILES[$client_file]['tmp_name'], $file_url);

		$img_result	= stripslashes($img_result);
		$img_result	= str_replace("'", "\'", $img_result);
		$img_result	= str_replace('[_FILE_URL_]', $Info->option['site_url'] . $file_url, $img_result);
		$Template->set_vars(array(
			"HTML_ID"			=> $html_id,
			'FILE_URL'			=> $Info->option['site_url'] . $file_url,
			"IMG_RESULT"		=> $img_result,
		));

		//Footer
		$Template->show("WAITING_END");
		$this->halt();
		return true;
	}

	function halt($msg = ""){
		global $DB;
		@$DB->close();

		echo $msg;
		die();
	}
}

?>