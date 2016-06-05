<?php
if (!defined('IN_SITE'))
{
		die('Hacking attempt!');
}
class Template
{
		var $root = ".";
		var $filename = array();
		var $filetype = array();
		var $_3DF4E = array();
		var $_3E00C = array();
		var $_3E088 = array();
		var $_3E0C5 = array();
		var $_3E108 = 0;
		var $_3E1A6 = array();
		var $_3E1BA = array();
		var $_3E295 = array();
		var $_3E2D3 = array();
		var $counter = 0;
		function Template($root = ".")
		{
				if (!is_dir($root))
				{
						$this->halt('Root folder "<b>' . $root . '</b>" does not exist or it is not a folder!');
				}
				$this->root = $root;
		}
		function set_root($root)
		{
				if (!is_dir($root))
				{
						$this->halt('Root folder "<b>' . $root . '</b>" does not exist or it is not a folder!');
				}
				$this->root = $root;
		}
		function set_files($_3E346, $filetype = 'dynamic')
		{
				if (is_array($_3E346))
				{
						reset($_3E346);
						while (list($key, $filename) = each($_3E346))
						{
								if (!isset($this->filename[$key]))
								{
										$this->filetype[$key] = $filetype;
										$this->filename[$key] = $this->check_file_exist($filename);
								}
						}
				}
				else
				{
						$this->halt("set_files() ==> File $_3E346 is not array");
				}
		}
		function unset_files($_3E3DD)
		{
				if (is_array($_3E3DD))
				{
						reset($_3E3DD);
						while (list(, $key) = each($_3E3DD))
						{
								$this->filename[$key] = "";
								$this->filetype[$key] = "";
								$this->_3DF4E[$key] = "";
						}
				}
				else
				{
						$this->filename[$_3E3DD] = "";
						$this->filetype[$_3E3DD] = "";
						$this->_3DF4E[$_3E3DD] = "";
				}
		}
		function set_vars($_3E499)
		{
				if (is_array($_3E499))
				{
						reset($_3E499);
						while (list($key, $_3E4FD) = each($_3E499))
						{
								$this->_3E1BA[$key] = "/" . preg_quote("{" . $key . "}") . "/";
								$this->_3E295[$key] = $_3E4FD;
								$this->_3E2D3[$key] = "'." . '$this->_3E295["' . $key . '"]' . ".'";
						}
				}
				else
				{
						$this->halt('set_vars()==> Var is not array');
				}
		}
		function set_block_vars($_3E5CF, $_3E499 = "")
		{
				if (empty($_3E5CF))
				{
						$this->halt("set_block_vars() ==> Blockname is empty.");
						return false;
				}
				if (!isset($this->_3E1A6[$_3E5CF]))
				{
						$this->_3E108++;
						$this->_3E1A6[$_3E5CF] = $this->_3E108;
				}
				$_3E642 = 'loop_' . $this->_3E1A6[$_3E5CF];
				$_3E64A = '$_var_' . $this->_3E1A6[$_3E5CF];
				if (strpos($_3E5CF, ':'))
				{
						$_3E793 = '';
						$_3E81E = '';
						$_3E8E0 = '';
						$_3E98E = '$this->_3E088["' . $_3E642 . '"]';
						$_3E9DD = strlen($_3E5CF);
						for ($_3BA12 = 0; $_3BA12 < $_3E9DD; $_3BA12++)
						{
								if (substr($_3E5CF, $_3BA12, 1) == ':')
								{
										$_3E9F4 = substr($_3E5CF, 0, $_3BA12);
										$parent = $this->count_parent_array($_3E9F4, $_3E81E);
										$_3E793 .= '[' . $parent['var'] . ']';
										$_3E98E .= '[' . $parent['count'] . ']';
										$_3E81E .= '[' . $parent['count'] . ']';
										$_3E8E0 .= '[' . $parent['var'] . ']';
								}
						}
						$_3E81E .= '[]';
						$_3E8E0 .= '[' . $_3E64A . ']';
						$_3EB61 = 'if (isset(' . $_3E98E . ')){' . $_3E98E . '++;}else{' . $_3E98E . '=1;}';
						eval($_3EB61);
				}
				else
				{
						if (!isset($this->_3E088[$_3E642]))
						{
								$this->_3E088[$_3E642] = 1;
						}
						else
						{
								$this->_3E088[$_3E642]++;
						}
						$_3E793 = '';
						$_3E81E = '[]';
						$_3E8E0 = '[' . $_3E64A . ']';
				}
				$this->_3E00C[$_3E642] = $_3E64A;
				$this->_3E0C5[$_3E642] = '$this->_3E088["' . $_3E642 . '"]' . $_3E793;
				if (is_array($_3E499))
				{
						reset($_3E499);
						while (list($key, $_3E4FD) = each($_3E499))
						{
								$this->_3E1BA[$_3E642 . ':' . $key] = "/" . preg_quote("{" . $_3E5CF . ':' . $key . "}") . "/";
								$_3EB61 = '$this->_3E295["' . $_3E642 . ':' . $key . '"]' . $_3E81E . '=$_3E4FD;';
								eval($_3EB61);
								$this->_3E2D3[$_3E642 . ':' . $key] = "'." . '$this->_3E295["' . $_3E642 . ':' . $key . '"]' . $_3E8E0 . ".'";
						}
				}
				return true;
		}
		function count_parent_array($_3E5CF, $_3E81E)
		{
				$_3E9F4['count'] = 0;
				if (isset($this->_3E1A6[$_3E5CF]))
				{
						$_3E642 = 'loop_' . $this->_3E1A6[$_3E5CF];
						$_3E9F4['var'] = '$_var_' . $this->_3E1A6[$_3E5CF];
						if (isset($this->_3E088[$_3E642]))
						{
								$_3EB61 = ' if (is_array($this->_3E088[$_3E642]' . $_3E81E . ')){ $_3E9F4["count"]=sizeof($this->_3E088[$_3E642]' . $_3E81E . ')-1;} else{ $_3E9F4["count"]=$this->_3E088[$_3E642]' . $_3E81E . '-1;}';
								eval($_3EB61);
						}
				}
				else
				{
						if (!isset($this->_3E1A6[$_3E5CF]))
						{
								$this->_3E108++;
								$this->_3E1A6[$_3E5CF] = $this->_3E108;
						}
						$_3E9F4['var'] = '$_var_' . $this->_3E1A6[$_3E5CF];
				}
				return $_3E9F4;
		}
		function show($_3D064 = '', $_3EB96 = 0)
		{
				if (empty($_3D064))
				{
						reset($this->filename);
						while (list($key, ) = each($this->filename))
						{
								if (!empty($key))
								{
										$this->show($key, $_3EB96);
								}
						}
						return true;
				}
				else
						if (is_array($_3D064))
						{
								reset($_3D064);
								while (list(, $key) = each($_3D064))
								{
										if (!empty($key))
										{
												$this->show($key, $_3EB96);
										}
								}
								return true;
						}
				if (!$this->loadfile($_3D064))
				{
						$this->halt("Could not load $_3D064");
						return false;
				}
				if (isset($this->filetype[$_3D064]) && ($this->filetype[$_3D064] == "static"))
				{
						print (implode("\n", $this->_3DF4E[$_3D064]));
				}
				else
				{
						$_3EC77 = count($this->_3DF4E[$_3D064]);
						$_3ECBF = $this->_3DF4E[$_3D064];
						$flag_phpcode = false;
						for ($_3BA12 = 0; $_3BA12 < $_3EC77; $_3BA12++)
						{
								if (preg_match("#<!-- START: (.*?) -->#", $_3ECBF[$_3BA12]))
								{
										$_3E9F4 = trim(preg_replace("#<!-- START: (.*?) -->#", "\\1", $_3ECBF[$_3BA12]));
										if (isset($this->_3E1A6[$_3E9F4]))
										{
												$_3E642 = 'loop_' . $this->_3E1A6[$_3E9F4];
												$_3ECBF[$_3BA12] = ' $_counter_' . $this->counter . '=isset(' . $this->_3E0C5[$_3E642] . ') ? ' . $this->_3E0C5[$_3E642] . ':0;for ($_var_' . $this->_3E1A6[$_3E9F4] . '=0;$_var_' . $this->_3E1A6[$_3E9F4] . '<$_counter_' . $this->counter . ';$_var_' . $this->_3E1A6[$_3E9F4] . '++){';
												$this->counter++;
										}
										else
										{
												$_3ECBF[$_3BA12] = "if (false){";
										}
								}
								else
										if (preg_match("#<!-- END: (.*?) -->#", $_3ECBF[$_3BA12]))
										{
												$_3ECBF[$_3BA12] = '}';
										}
										else
												if (preg_match("#<!-- START IF: (.*?) -->#", $_3ECBF[$_3BA12]))
												{
														$_3E9F4 = trim(preg_replace("#<!-- START IF: (.*?) -->#", "\\1", $_3ECBF[$_3BA12]));
														if (isset($this->_3E1A6[$_3E9F4]))
														{
																$_3E642 = 'loop_' . $this->_3E1A6[$_3E9F4];
																$_3ECBF[$_3BA12] = 'if ( isset(' . $this->_3E0C5[$_3E642] . ') && ' . $this->_3E0C5[$_3E642] . ' ){';
														}
														else
														{
																$_3ECBF[$_3BA12] = 'if (false){';
														}
												}
												else
														if (preg_match("#<!-- END IF: (.*?) -->#", $_3ECBF[$_3BA12]))
														{
																$_3ECBF[$_3BA12] = '}';
														}
														else
																if (preg_match("#<!-- START NOIF: (.*?) -->#", $_3ECBF[$_3BA12]))
																{
																		$_3E9F4 = trim(preg_replace("#<!-- START NOIF: (.*?) -->#si", "\\1", $_3ECBF[$_3BA12]));
																		if (isset($this->_3E1A6[$_3E9F4]))
																		{
																				$_3E642 = 'loop_' . $this->_3E1A6[$_3E9F4];
																				$_3ECBF[$_3BA12] = 'if ( !isset(' . $this->_3E0C5[$_3E642] . ') || !' . $this->_3E0C5[$_3E642] . '){';
																		}
																		else
																		{
																				$_3ECBF[$_3BA12] = 'if (true){';
																		}
																}
																else
																		if (preg_match("#<!-- END NOIF: (.*?) -->#", $_3ECBF[$_3BA12]))
																		{
																				$_3ECBF[$_3BA12] = '}';
																		}
																		else
																				if (preg_match("{@PHPINCLUDE: (.*?)}", $_3ECBF[$_3BA12]))
																				{
																						$_3ECBF[$_3BA12] = trim(preg_replace("#{@PHPINCLUDE: (.*?)}#si", "include('\\1');", $_3ECBF[$_3BA12]));
																				}
																				else
																						if (preg_match("#<!-- START @PHPCODE -->#", $_3ECBF[$_3BA12]))
																						{
																								$flag_phpcode = true;
																								$_3ECBF[$_3BA12] = '';
																						}
																						else
																								if (preg_match("#<!-- END @PHPCODE -->#", $_3ECBF[$_3BA12]))
																								{
																										$flag_phpcode = false;
																										$_3ECBF[$_3BA12] = '';
																								}
																								else
																								{
																										if ($flag_phpcode)
																										{
																												continue;
																										}
																										$this->replace_line($_3ECBF[$_3BA12], $_3EB96);
																								}
						}
						$_3EB61 = implode("\n", $_3ECBF);
						$_3EB61 = @preg_replace($this->_3E1BA, $this->_3E2D3, $_3EB61);
						if ($_3EB96)
						{
								$_3EB61 = '$_str="";' . "\n" . $_3EB61;
								eval($_3EB61);
								return $_str;
						}
						else
						{
								eval($_3EB61);
						}
				}
				return true;
		}
		function replace_line(&$_3EFAA, $_3EB96 = 0)
		{
				$_3EFAA = str_replace("\\", "\\\\", $_3EFAA);
				$_3EFAA = str_replace("'", "\'", $_3EFAA);
				if ($_3EB96)
				{
						$_3EFAA = "$" . "_str .= '" . $_3EFAA . "';";
				}
				else
				{
						$_3EFAA = "echo '" . $_3EFAA . "';";
				}
		}
		function check_file_exist($filename = "", $dir = "")
		{
				if (empty($filename))
				{
						$this->halt("Get empty filename");
						return false;
				}
				if (substr($filename, 0, 1) == "/")
				{
						if (!empty($dir))
						{
								$filename = $dir . $filename;
						}
						else
						{
								$filename = $this->root . $filename;
						}
				}
				else
				{
						if (!empty($dir))
						{
								$filename = $dir . "/" . $filename;
						}
						else
						{
								$filename = $this->root . "/" . $filename;
						}
				}
				if (!file_exists($filename))
				{
						$this->halt("$filename does not exist");
						return false;
				}
				return $filename;
		}
		function loadfile($_3D064)
		{
				if (isset($this->_3DF4E[$_3D064])) return true;
				if (!isset($this->filename[$_3D064]))
				{
						$this->halt("$_3D064 is not a valid handle");
						return false;
				}
				else
				{
						$this->_3DF4E[$_3D064] = @file($this->filename[$_3D064]);
				}
				return true;
		}
		function page_transfer($msg, $_3F019, $_3F0FC = 5, $transfer_file = "transfer.tpl")
		{
				global $DB;
				@$DB->close();
				if (empty($_3F019)) $this->halt("Page_to value does not exist");
				$this->set_files(array("transfer" => $transfer_file));
				$this->set_vars(array("MESSAGE" => $msg, "SECONDS" => $_3F0FC, "PAGE_TO" => $_3F019));
				$this->show("transfer");
				die();
		}
		function fast_transfer($_3F019)
		{
				global $DB;
				@$DB->close();
				if (empty($_3F019)) die("Not found any page");
				if (headers_sent())
				{
						echo "<br><br><b>Transfer Page Error: Header has been sent</b>.<br><a href='javascript:history.back(-1);'>Go Back</a>";
				}
				else
				{
						header("Location: $_3F019");
				}
				die();
		}
		function message_die($msg)
		{
				global $DB, $Lang;
				@$DB->close();
				$this->set_vars(array('MESSAGE' => $msg, 'L_PAGE_TITLE' => $Lang->data['general_message_die'], 'L_CLOSE' => $Lang->data['general_close_window'], ));
				$this->set_files(array('die' => 'message_die.tpl'));
				$this->show('die');
				die();
		}
		function halt($msg)
		{
				echo "<b>Template Error:</b>\n<br>$msg";
				die();
		}
}
?>