<?php
/* =============================================================== *\
|		Module name: SMTP											|
|		Module version: 1.2											|
|		Begin: 16 February 2005										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
	die('Hacking attempt!');
}
define('SMTP_CODE_STOP', 0); //Stop sending if there is any error
define('SMTP_CODE_SKIP', 1); //Skip error emails
define('SMTP_CODE_RESEND', 2); //Try to resend emails

class SMTP
{
	var $subject			= "";
	var $message			= "";
	var $type				= "text/html";
	var	$charset			= "utf-8";
	var $email_info			= array();

	var $smtp_host			= "";
	var $smtp_port			= 25;
	var $smtp_username		= "";
	var $smtp_password		= "";
	var $smtp_socket		= null;

	var $debug_enabled	 	= true;
	var $debug_log			= "";

	var $email_skip			= SMTP_CODE_STOP;
	var $email_resend_time	= 5; //Seconds
	var $email_response		= "";

	function SMTP($smtp_host = "", $smtp_username = "", $smtp_password = ""){
		if ( !empty($smtp_host) ){
			$this->smtp_host		= $smtp_host;
			$this->smtp_username	= $smtp_username;
			$this->smtp_password	= $smtp_password;
		}
		$this->email_info['to']		= "";
	}

	function email_skip($skip){
		$this->email_skip	= $skip;
	}

	function email_from($email_from){
		$this->email_info['from']	= $email_from;
	}

	function email_to($email_to){
		$this->email_info['to']		= $email_to;
	}

	function email_bcc($email_bcc){
		$this->email_info['bcc'][]	= $email_bcc;
	}

	function email_cc($email_cc){
		$this->email_info['cc'][]	= $email_cc;
	}

	function message_type($type){
		$this->type	= $type;
	}

	function message_charset($charset = ""){
		$this->charset	= $charset;
	}

	function message_subject($subject){
		$this->subject	= trim($subject);
	}

	function message_content($message){
		$this->message	= trim($message);
	}

	function send(){
		if ( empty($this->email_info['from']) || (empty($this->email_info['to']) && empty($this->email_info['cc']) && empty($this->email_info['bcc'])) ){
			return false;
		}

		// Fix any bare linefeeds in the message to make it RFC821 Compliant.
		$this->message	= preg_replace("#(?<!\r)\n#si", "\r\n", $this->message);

		//Send emails
		if ( !empty($this->smtp_host) ){ //Using SMTP
			$errno		= "";
			$errstr		= "";
			if ( $this->debug_enabled ){
				$this->debug_log	= ""; //Reset log
				$this->debug_log	.= "[Recipient email: ". $this->email_info['to'] ."]<br>\n";
			}

			$socket	= fsockopen($this->smtp_host, $this->smtp_port, $errno, $errstr, 20);
			if ( !$socket ){
				if ( $this->email_skip == SMTP_CODE_STOP ){
					$this->halt("Could not connect to smtp host!");
					return false;
				}
				else{
					$this->email_response	= "Could not connect to smtp host!";
					return $this->email_skip;
				}
			}

			//Email headers
			$headers	= $this->email_headers();

			// Waiting for reply
			$result	= $this->server_waiting($socket, "220", "Waiting for reply", 1);
			if ( !$result ){
				//Stop sending or return an error code
				if ( $this->email_skip == SMTP_CODE_STOP ){
					$this->halt($this->email_response);
					return false;
				}
				else{
					return $this->email_skip;
				}
				//------------------------------------
			}

			// Do we want to use AUTH?, send RFC2554 EHLO, else send RFC821 HELO
			if( !empty($this->smtp_username) && !empty($this->smtp_password) ){ 
				fputs($socket, "EHLO " . $this->smtp_host . "\r\n");
				$result	= $this->server_waiting($socket, "250", "Sending RFC2554 EHLO", 1);
				if ( !$result ){
					//Stop sending or return an error code
					if ( $this->email_skip == SMTP_CODE_STOP ){
						$this->halt($this->email_response);
						return false;
					}
					else{
						return $this->email_skip;
					}
					//------------------------------------
				}

				fputs($socket, "AUTH LOGIN\r\n");
				$result	= $this->server_waiting($socket, "334", "AUTH Login");
				if ( !$result ){
					return false;
				}

				fputs($socket, base64_encode($this->smtp_username) . "\r\n");
				$result	= $this->server_waiting($socket, "334", "AUTH Username");
				if ( !$result ){
					return false;
				}

				fputs($socket, base64_encode($this->smtp_password) . "\r\n");
				$result	= $this->server_waiting($socket, "235", "AUTH Password");
				if ( !$result ){
					return false;
				}
			}
			else {
				fputs($socket, "HELO " . $this->smtp_host . "\r\n");
				$result	= $this->server_waiting($socket, "250", "Sending RFC821 EHLO", 1);
				if ( !$result ){
					//Stop sending or return an error code
					if ( $this->email_skip == SMTP_CODE_STOP ){
						$this->halt($this->email_response);
						return false;
					}
					else{
						return $this->email_skip;
					}
					//------------------------------------
				}
			}

			fputs($socket, "MAIL FROM: <" . $this->email_info['from'] . ">\r\n");
			$result	= $this->server_waiting($socket, "250", "MAIL FROM", 1);
			if ( !$result ){
				//Stop sending or return an error code
				if ( $this->email_skip == SMTP_CODE_STOP ){
					$this->halt($this->email_response);
					return false;
				}
				else{
					return $this->email_skip;
				}
				//------------------------------------
			}

			if ( preg_match('#[^ ]+\@[^ ]+#', $this->email_info['to']) ){
				fputs($socket, "RCPT TO: <". $this->email_info['to'] .">\r\n");
				$result	= $this->server_waiting($socket, "250", "RCPT TO", 1);
				if ( !$result ){
					//Stop sending or return an error code
					if ( $this->email_skip == SMTP_CODE_STOP ){
						$this->halt($this->email_response);
						return false;
					}
					else{
						return $this->email_skip;
					}
					//------------------------------------
				}
			}

			if ( isset($this->email_info['bcc']) ){
				@reset($this->email_info['bcc']);
				while(list(, $email_bcc)	= each($this->email_info['bcc']) ){
					if (preg_match('#[^ ]+\@[^ ]+#', $email_bcc)){
						fputs($socket, "RCPT TO: <$email_bcc>\r\n");
						$result	= $this->server_waiting($socket, "250", "RCPT TO [BCC]", 1);
						if ( !$result ){
							//Stop sending or return an error code
							if ( $this->email_skip == SMTP_CODE_STOP ){
								$this->halt($this->email_response);
								return false;
							}
							else{
								return $this->email_skip;
							}
							//------------------------------------
						}
					}
				}
			}

			if ( isset($this->email_info['cc']) ){
				@reset($this->email_info['cc']);
				while(list(, $email_cc)	= each($this->email_info['cc']) ){
					if (preg_match('#[^ ]+\@[^ ]+#', $email_cc)){
						fputs($socket, "RCPT TO: <$email_cc>\r\n");
						$result	= $this->server_waiting($socket, "250", "RCPT TO [CC]", 1);
						if ( !$result ){
							//Stop sending or return an error code
							if ( $this->email_skip == SMTP_CODE_STOP ){
								$this->halt($this->email_response);
								return false;
							}
							else{
								return $this->email_skip;
							}
							//------------------------------------
						}
					}
				}
			}

			//Tell the server we are ready to start sending data
			fputs($socket, "DATA\r\n");
			$result	= $this->server_waiting($socket, "354", "DATA");
			if ( !$result ){
				return false;
			}

			fputs($socket, "Subject: " . $this->subject . "\r\n");
//			if ( !empty($this->email_info['to']) ){
//				fputs($socket, "To: ". $this->email_info['to'] ."\r\n");
//			}
			fputs($socket, $headers ."\r\n\r\n");
			fputs($socket, $this->message . "\r\n");
			fputs($socket, "\r\n.\r\n");
			$result	= $this->server_waiting($socket, "250", "MESSAGE");
			if ( !$result ){
				return false;
			}

			fputs($socket, "QUIT\r\n");
			fclose($socket);
		}
		else{ //Using PHP Mail
			$email_cc	= isset($this->email_info['cc']) ? implode(', ', $this->email_info['cc']) : '';
			$email_bcc	= isset($this->email_info['bcc']) ? implode(', ', $this->email_info['bcc']) : '';
			$headers	= $this->email_headers($email_cc, $email_bcc);
			mail($this->email_info['to'], $this->subject, $this->message, $headers);
		}
		return true;
	}

	function email_headers($email_cc = "", $email_bcc = ""){
		// Build header
		$headers 	= "From: ". $this->email_info['from'] ."\n";
		if ( !empty($email_cc) ){
			$headers 	.= "CC: ". $email_cc ."\n";
		}
		if ( !empty($email_bcc) ){
			$headers 	.= "BCC: ". $email_bcc ."\n";
		}
		$headers 	.= "Reply-to: ". $this->email_info['from'] ."\n";
		$headers	.= !empty($this->smtp_host) ? "Message-ID: <" . md5(uniqid(CURRENT_TIME)) . "@" . $this->smtp_host . ">\n" : '';
		$headers	.= "MIME-Version: 1.0\nContent-type: " . $this->type . "; charset=" . $this->charset . "\nContent-transfer-encoding: 8bit\n";
		$headers	.= "Date: " . date('r', CURRENT_TIME) . "\n";
		$headers	.= "X-Priority: 3\nX-MSMail-Priority: Normal\nX-Mailer: PHP\n";
		$headers	.= "X-MimeOLE: Mailer\n";

		// Make sure there are no bare linefeeds in the headers
		$headers	= preg_replace('#(?<!\r)\n#si', "\r\n", $headers);
		return $headers;
	}

	function server_waiting($socket, $response, $debug, $skip = 0){
		$server_response		= "";
		while (substr($server_response, 3, 1) != ' '){
			$server_response 	= fgets($socket, 256);
			if ( $this->debug_enabled ){
				//Log server response
				$this->debug_log	.= "[". $debug ."] ". $server_response ."<br>\n";
			}
			if ( !$server_response ){
				if ( $skip ){
					$this->email_response	= "Could not get mail server response codes!";
				}
				else{
					$this->halt("Could not get mail server response codes!");
				}
				return false;
			}
		}
		if ( !(substr($server_response, 0, 3) == $response) ){
			if ( $skip ){
				$this->email_response	= "Ran into problems sending Mail. Response: $server_response!";
			}
			else{
				$this->halt("Ran into problems sending Mail. Response: $server_response!");
			}
			return false;
		}
		return true;
	}

	function reset_email(){
		$this->email_info	= array();
	}

	function reset(){
		$this->reset_email();
		$this->subject	= "";
		$this->message	= "";
	}

	function halt($msg){
		echo "<b>SMTP Error:</b>\n<br>$msg";
		if ( $this->debug_enabled ){
			echo "\n\n<br><br><strong>Bebug Logs:</strong>\n<br>", $this->debug_log;
		}
		die();
	}
}
?>