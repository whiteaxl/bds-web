<?php
if (!defined('IN_SITE'))
{
		die('Hacking attempt!');
}
class Session_Page
{
		var $sid = "";
		var $hash = "";
		var $ip = "";
		function Session_Page()
		{
				global $REMOTE_ADDR;
				$this->sid = isset($_GET["s"]) ? $_GET["s"] : '';
				if (empty($this->sid))
				{
						$this->sid = isset($_COOKIE["session_id"]) ? $_COOKIE["session_id"] : '';
				}
				if (!empty($this->sid))
				{
						$this->sid = preg_replace("/([^a-zA-Z0-9])/", "", $this->sid);
				}
				$this->ip = !empty($_SERVER['HTTP_X_FORWARDED_FOR']) ? $_SERVER['HTTP_X_FORWARDED_FOR'] : (!empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : (!empty($REMOTE_ADDR) ? $REMOTE_ADDR : ''));
				if ($this->ip == '0.0.0.0')
				{
						$this->ip = '127.0.0.1';
				}
				$this->sess_update_time();
		}
		function sess_create($user_info, $cookie_remember)
		{
				global $DB, $Info, $Func;
				$cookie_time = CURRENT_TIME - $Info->option['cookie_time'];
				$this->sid = md5(uniqid(microtime()) . $this->ip . $Func->create_random(4));
				$this->hash = md5($this->ip . $Func->create_random(8));
				$DB->query('DELETE FROM ' . $DB->prefix . 'session WHERE user_id=' . $user_info['user_id'] . ' OR session_time<' . $cookie_time);
				$DB->query('SELECT group_id FROM ' . $DB->prefix . 'user_group_ids WHERE user_id=' . $user_info['user_id']);
				$_3D654 = $DB->num_rows();
				$_3D6BC = $DB->fetch_all_array();
				$DB->free_result();
				$user_groups = "";
				for ($_3BA12 = 0; $_3BA12 < $_3D654; $_3BA12++)
				{
						$user_groups .= $_3D6BC[$_3BA12]['group_id'];
						if ($_3BA12 < $_3D654 - 1) $user_groups .= ',';
				}
				$_3D7E4 = "INSERT INTO " . $DB->prefix . "session(session_id, session_time, session_hash, user_id, user_groups, auto_login, kicked_by, kicked_time)                                VALUES('" . $this->sid . "', " . CURRENT_TIME . ", '" . $this->hash . "', " . $user_info['user_id'] . ", '" . $user_groups . "', $cookie_remember, '', 0)";
				$DB->query($_3D7E4);
		}
		function sess_update($_3D9FD)
		{
				global $DB;
				if (empty($this->sid))
				{
						return false;
				}
				$_3D9FD .= " WHERE session_id='" . $this->sid . "'";
				$DB->query($_3D9FD);
				return true;
		}
		function sess_update_time()
		{
				global $DB;
				if (!empty($this->sid))
				{
						$DB->query("UPDATE " . $DB->prefix . "session SET session_time=" . CURRENT_TIME . " WHERE session_id='" . $this->sid . "'");
				}
		}
		function sess_del()
		{
				global $DB;
				$DB->query("DELETE FROM " . $DB->prefix . "session WHERE session_id='" . $this->sid . "'");
				return true;
		}
		function append_sid($url)
		{
				if (!empty($this->sid))
				{
						$url .= ((strpos($url, '?') != false) ? '&' : '?') . 's=' . $this->sid;
				}
				return $url;
		}
}
?>