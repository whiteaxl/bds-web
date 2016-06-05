<?php
/* =============================================================== *\
|		Module name:      Admin Index								|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

$AIndex = new Admin_Index;

class Admin_Index
{
	function Admin_Index(){
		global $Info;

		$Info->tpl_main = "index";

		switch ($Info->act){
			case "login":
				$this->do_login();
				break;
			case "logout":
				$this->do_logout();
				break;
			case "image":
				$this->show_image_number();
				break;
			case "presendpass":
				$this->pre_send_password();
				break;
			case "sendpass":
				$this->do_send_password();
				break;
			case "confirm":
				$this->do_reset_password();
				break;
			default:
				$this->logined_transfer();
				$this->show_login_form();
		}
	}

	function logined_transfer(){
		global $Session, $Template, $Info, $DB;

		$login_time		= CURRENT_TIME - $Info->option['time_login'];
		$cookie_time	= CURRENT_TIME - $Info->option['cookie_time'];
		$kick_time		= CURRENT_TIME - $Info->option['kick_minutes']*60;

		//Check login
		$DB->query("SELECT user_id FROM ". $DB->prefix ."session WHERE session_id='". $Session->sid ."' AND ((session_time>=$login_time) OR (session_time>$cookie_time AND auto_login=1)) AND kicked_time<$kick_time");
		if ( $DB->num_rows() ){
			$Template->fast_transfer($Session->append_sid(ACP_INDEX ."?mod=frame"));
		}
		$DB->free_result();
		return true;
	}

	function show_login_form($msg = ""){
		global $Session, $Lang, $Template;

		$this->make_image_number();

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'L_ENTER_USERNAME'		=> $Lang->data['general_error_not_username'],
			'L_ENTER_PASSWORD'		=> $Lang->data['general_error_not_password'],
			'L_USERNAME'			=> $Lang->data['general_username'],
			'L_PASSWORD'			=> $Lang->data['general_password'],
			'L_REMEMBER'			=> $Lang->data['general_remember'],
			'L_YES'					=> $Lang->data['general_yes'],
			'L_LOGIN_BUTTON'		=> $Lang->data['button_login'],
			'L_NUMBER'				=> $Lang->data['general_number'],
			'L_FORGOT_PASS'			=> $Lang->data['general_forgot_pass'],
			'S_LOGIN_ACTION'		=> $Session->append_sid(ACP_INDEX .'?mod=idx&act=login'),
			'U_FORGOT_PASS'			=> $Session->append_sid(ACP_INDEX .'?mod=idx&act=presendpass'),
		));
	}

	function do_login(){
		global $Session, $Info, $DB, $Template, $Lang, $Func;

		$username		= isset($_POST["username"]) ? htmlspecialchars($_POST["username"]) : '';
		$password		= isset($_POST["password"]) ? md5($_POST["password"]) : '';
		$number_id		= isset($_POST["number_id"]) ? htmlspecialchars($_POST["number_id"]) : '';
		$number_value	= isset($_POST["number_value"]) ? intval($_POST["number_value"]) : 0;
		$remember		= isset($_POST["remember"]) ? intval($_POST["remember"]) : 0;
		$remember		= $remember ? 1: 0;

		$cookie_time	= $Info->option['cookie_time'] + CURRENT_TIME;
		$cookie_path	= $Info->option['cookie_path'];
		$cookie_domain	= $Info->option['cookie_domain'];
		$cookie_secure	= $Info->option['cookie_secure'];
		$kick_time		= CURRENT_TIME - $Info->option['kick_minutes']*60;

		if ( empty($username) || empty($password) || empty($number_id) || !$number_value ){
			$this->show_login_form($Lang->data['general_error_not_full']);
			return false;
		}

		//Check number ---------------
		$DB->query("SELECT * FROM ". $DB->prefix ."number WHERE num_id='". $number_id ."'");
		if ( !$DB->num_rows() ){
			$this->show_login_form($Lang->data["general_error_login_number"]);
			return false;
		}
		$number_info	= $DB->fetch_array();
		if ( $number_value != $number_info['num_value'] ){
			$this->show_login_form($Lang->data["general_error_login_number"]);
			return false;
		}
		//Delete login number
		$DB->query("DELETE FROM ". $DB->prefix ."number WHERE num_id='". $number_id ."'");
		//----------------------------

		//Check username and password
		$DB->query("SELECT * FROM ". $DB->prefix ."user WHERE username='". $username ."' AND enabled=". SYS_ENABLED);
		if ( !$DB->num_rows() ){
			$this->show_login_form(sprintf($Lang->data["general_error_username"], $username));
			return false;
		}
		$user_info = $DB->fetch_array();
		$DB->free_result();

		if ($password != $user_info["user_password"]){
			$this->show_login_form($Lang->data["general_error_password"], $Session->append_sid(ACP_INDEX .""));
			return false;
		}

		//Does this user was kicked?
		$DB->query('SELECT * FROM '. $DB->prefix .'session WHERE user_id='. $user_info['user_id'] .' AND kicked_time>'. $kick_time);
		if ( $DB->num_rows() ){
			$session_info	= $DB->fetch_array();
			//This user has been kicked and will be banned in some minutes
			$this->show_login_form( sprintf($Lang->data["user_kicked_by"], $session_info['kicked_by'], $Info->option['kick_minutes']));
			return false;
		}

		//Create session
		$Session->sess_create($user_info, $remember);

		//Update user_info
		$DB->query("UPDATE ". $DB->prefix ."user SET user_last_login=". CURRENT_TIME .", user_ip='". $Session->ip ."' WHERE user_id=". $user_info['user_id']);

		//Set cookie
		if ( !empty($cookie_domain) && ($cookie_domain != "localhost") ){
			if ( $remember ){
				setcookie('session_id', $Session->sid, $cookie_time, $cookie_path, $cookie_domain, $cookie_secure);
			}
			setcookie('session_hash', $Session->hash, $cookie_time, $cookie_path, $cookie_domain, $cookie_secure);
		}
		else{
			if ( $remember ){
				setcookie('session_id', $Session->sid, $cookie_time);
			}
			setcookie('session_hash', $Session->hash, $cookie_time);
		}

		//Save log
		$Info->user_info	= $user_info;
		$Func->save_log('menu_general_login', 'log_login');

		$Template->page_transfer($Lang->data["general_success_login"], $Session->append_sid(ACP_INDEX ."?mod=frame"));
		return true;
	}

	function do_logout(){
		global $Template, $Info, $DB, $Session, $Lang, $Func;

//		$cookie_time	= $Info->option['cookie_time'] + CURRENT_TIME;
		$cookie_path	= $Info->option['cookie_path'];
		$cookie_domain	= $Info->option['cookie_domain'];
		$cookie_secure	= $Info->option['cookie_secure'];

		//Delete session
		$Session->sess_del();

		if ( !empty($cookie_domain) && ($cookie_domain != "localhost") ){
			setcookie('session_id', '', CURRENT_TIME - 3600, $cookie_path, $cookie_domain, $cookie_secure);
			setcookie('session_hash', '', CURRENT_TIME - 3600, $cookie_path, $cookie_domain, $cookie_secure);
		}
		else{
			setcookie('session_id', '', CURRENT_TIME - 3600);
			setcookie('session_hash', '', CURRENT_TIME - 3600);
		}

		//Save log
		$Func->save_log('menu_general_logout', 'log_logout');

		$Template->page_transfer($Lang->data["general_success_logout"], $Session->append_sid(ACP_INDEX .''));
		return true;
	}

	function make_image_number(){
		global $Session, $Info, $DB, $Template, $Lang;

		$expired_time	= CURRENT_TIME	- 300; // 5'

		//Delete old image number of this client
//		$DB->query("DELETE FROM ". $DB->prefix ."number WHERE client_ip='". $Session->ip ."' OR num_time<$expired_time");
		$DB->query("DELETE FROM ". $DB->prefix ."number WHERE num_time<$expired_time");
		//--------------------------------------

		$num_id			= md5(uniqid(CURRENT_TIME . rand(1000, 9999)));
		$number			= rand(1,9);
		for ($i=2; $i<=4; $i++){
			$number			.= rand(0,9);
		}

		//Insert into db
		$DB->query("INSERT INTO ". $DB->prefix ."number(num_id, num_value, num_time, client_ip) VALUES('". $num_id ."', '". $number ."', ". CURRENT_TIME .", '". $Session->ip ."')");

		$Template->set_vars(array(
			"IMAGE_NUMBER"			=> '<img src="'. ACP_INDEX .'?act=image&num_id='. $num_id .'" align="absbottom">',
			'NUMBER_ID'				=> $num_id,
		));
	}

	function show_image_number(){
		global $DB, $Info, $Image;

		$num_id		= isset($_GET["num_id"]) ? $_GET["num_id"] : ' ';

		if ( !empty($num_id) ){
			$DB->query("SELECT num_value FROM ". $DB->prefix ."number WHERE num_id='". $num_id ."'");
			if ( $DB->num_rows() ){
				$number_info	= $DB->fetch_array();
				//Create image
				$Image->convert_text_to_image($number_info["num_value"], $Info->option['template_path'] .'/images/number/');
			}
		}
		die();
	}

	function pre_send_password($msg = ""){
		global $Session, $Lang, $Template, $Info;

		$Info->tpl_main		= "forgot_password";

		$Template->set_vars(array(
			'ERROR_MSG'				=> $msg,
			'S_ACTION'				=> $Session->append_sid(ACP_INDEX .'?mod=idx&act=sendpass'),
			'L_FORGOT_PASS'			=> $Lang->data['general_forgot_pass'],
			'L_USERNAME'			=> $Lang->data['general_username'],
			'L_BUTTON'				=> $Lang->data['button_send'],
		));
	}

	function do_send_password(){
		global $Session, $Lang, $Template, $Info, $DB, $Func;

		$username	= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '';

		if ( empty($username) ){
			$this->pre_send_password($Lang->data['general_error_not_full']);
			return false;
		}

		//Get this user info
		$DB->query("SELECT * FROM ". $DB->prefix ."user WHERE username='". $username ."'");
		if ( !$DB->num_rows() ){
			$Template->page_transfer($Lang->data['general_error_username'], $Session->append_sid(ACP_INDEX .'?mod=idx&act=presendpass'));
			return false;
		}
		$user_info		= $DB->fetch_array();

		//Check send times
		$time	= CURRENT_TIME - 900;
		$DB->query("SELECT send_id FROM ". $DB->prefix ."send_pass WHERE username='". $username ."' AND send_time>=$time");
		if ( $DB->num_rows() >= 2 ){
			$Template->page_transfer($Lang->data['general_error_send_pass'], $Session->append_sid(ACP_INDEX .'?mod=idx&act=presendpass'));
			return false;
		}

		// Create send id
		$send_id	= md5(CURRENT_TIME . rand(1000, 9999));

		//Send email
		$content	= $Lang->data['general_send_pass_content'];
		$content	= str_replace('{SITE_NAME}', $Info->option['site_name'], $content);
		$content	= str_replace('{SITE_URL}', $Info->option['site_url'], $content);
		$content	= str_replace('{USERNAME}', $user_info['username'], $content);
		$content	= str_replace('{U_CONFIRM}', '<a href="'. $Info->option['site_url'] .ACP_INDEX .'?mod=idx&act=confirm&username='. $username .'&send_id='. $send_id .'">'. $Info->option['site_url'] .ACP_INDEX .'?mod=idx&act=confirm&username='. $username .'&send_id='. $send_id .'</a>', $content);

//		$Func->send_email($user_info['user_email'], $Lang->data['general_send_pass_subject'], $content);
		//SMTP -------------------------------
		include('includes/smtp'. PHP_EX);
		$SMTP	= new SMTP($Info->option['smtp_host'], $Info->option['smtp_username'], $Info->option['smtp_password']);
		$SMTP->email_from($Info->option['admin_email']);
		$SMTP->email_to($user_info['user_email']);
		$SMTP->message_charset($Lang->charset);
		$SMTP->message_subject($Lang->data['general_send_pass_subject']);
		$SMTP->message_content($content);
		$SMTP->send();
		//------------------------------------


		//Remember this send into db
		$DB->query("INSERT INTO ". $DB->prefix ."send_pass(send_id, send_time, username) VALUES('". $send_id ."', ". CURRENT_TIME .", '". $username ."')");

		$Template->page_transfer($Lang->data["general_success_send_password"], $Session->append_sid(ACP_INDEX .''), 20);
		return true;
	}

	function do_reset_password(){
		global $Session, $Lang, $Template, $Info, $DB, $Func;

		$username	= isset($_GET['username']) ? htmlspecialchars($_GET['username']) : '';
		$send_id	= isset($_GET['send_id']) ? htmlspecialchars($_GET['send_id']) : '';

		if ( empty($username) || empty($send_id) ){
			$Template->page_transfer(sprintf($Lang->data['general_error_username'], $username), $Session->append_sid(ACP_INDEX .'?mod=idx&act=presendpass'));
			return false;
		}

		//Get this user info
		$DB->query("SELECT * FROM ". $DB->prefix ."user WHERE username='". $username ."'");
		if ( !$DB->num_rows() ){
			$Template->page_transfer(sprintf($Lang->data['general_error_username'], $username), $Session->append_sid(ACP_INDEX .'?mod=idx&act=presendpass'));
			return false;
		}
		$user_info		= $DB->fetch_array();

		//Check send id
		$time	= CURRENT_TIME - 900;
		$DB->query("SELECT send_id FROM ". $DB->prefix ."send_pass WHERE username='". $username ."' AND send_time>=$time");
		if ( $DB->num_rows() ){
			$send_info	= $DB->fetch_array();
			if ( $send_id == $send_info['send_id'] ){
				//Reset password
				$newpass	= $Func->create_random(10);
				$DB->query("UPDATE ". $DB->prefix ."user SET user_password='". md5($newpass) ."' WHERE username='". $username ."'");
				$DB->query("DELETE FROM ". $DB->prefix ."send_pass WHERE username='". $username ."' OR send_time<$time");

				//Send password to email
				$content	= $Lang->data['general_send_pass_content_2'];
				$content	= str_replace('{SITE_NAME}', $Info->option['site_name'], $content);
				$content	= str_replace('{SITE_URL}', $Info->option['site_url'], $content);
				$content	= str_replace('{USERNAME}', $user_info['username'], $content);
				$content	= str_replace('{NEW_PASS}', $newpass, $content);

//				$Func->send_email($user_info['user_email'], $Lang->data['general_send_pass_subject'], $content);
				//SMTP -------------------------------
				include('includes/smtp'. PHP_EX);
				$SMTP	= new SMTP($Info->option['smtp_host'], $Info->option['smtp_username'], $Info->option['smtp_password']);
				$SMTP->email_from($Info->option['admin_email']);
				$SMTP->email_to($user_info['user_email']);
				$SMTP->message_charset($Lang->charset);
				$SMTP->message_subject($Lang->data['general_send_pass_subject']);
				$SMTP->message_content($content);
				$SMTP->send();
				//------------------------------------

				$Template->page_transfer($Lang->data["general_success_reset_password"], $Session->append_sid(ACP_INDEX .''), 20);
				return true;
			}
		}

		$Template->page_transfer(sprintf($Lang->data['general_error_username'], $username), $Session->append_sid(ACP_INDEX .'?mod=idx&act=presendpass'));
		return false;
	}
}

?>