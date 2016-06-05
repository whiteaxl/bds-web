<?php
if (!defined('IN_SITE'))
{
		die('Hacking attempt!');
}
class Func_Global
{
		var $_3BB84 = array();
		var $_3BC13 = array();
		var $_3BCC6 = array();
		function check_gpc()
		{
				global $Session;
				if (!get_magic_quotes_gpc())
				{
						if (is_array($_GET))
						{
								while (list($key, $_3BE8A) = each($_GET))
								{
										if (is_array($_GET[$key]))
										{
												while (list($key2, $_3BF30) = each($_GET[$key]))
												{
														if (!is_array($_3BF30))
														{
																$_GET[$key][$key2] = addslashes(trim($_3BF30));
														}
												}
												@reset($_GET[$key]);
										}
										else
										{
												$_GET[$key] = addslashes(trim($_3BE8A));
										}
								}
								@reset($_GET);
						}
						if (is_array($_POST))
						{
								while (list($key, $_3BE8A) = each($_POST))
								{
										if (is_array($_POST[$key]))
										{
												while (list($key2, $_3BF30) = each($_POST[$key]))
												{
														if (!is_array($_3BF30))
														{
																$_POST[$key][$key2] = addslashes(trim($_3BF30));
														}
												}
												@reset($_POST[$key]);
										}
										else
										{
												$_POST[$key] = addslashes(trim($_3BE8A));
										}
								}
								@reset($_POST);
						}
						if (is_array($_COOKIE))
						{
								while (list($key, $_3BE8A) = each($_COOKIE))
								{
										if (is_array($_COOKIE[$key]))
										{
												while (list($key2, $_3BF30) = each($_COOKIE[$key]))
												{
														if (!is_array($_3BF30))
														{
																$_COOKIE[$key][$key2] = addslashes(trim($_3BF30));
														}
												}
												@reset($_COOKIE[$key]);
										}
										else
										{
												$_COOKIE[$key] = addslashes(trim($_3BE8A));
										}
								}
								@reset($_COOKIE);
						}
						if (is_array($_FILES))
						{
								while (list($key, $_3BE8A) = each($_FILES))
								{
										if (is_array($_FILES[$key]))
										{
												while (list($key2, $_3BF30) = each($_FILES[$key]))
												{
														if (!is_array($_3BF30))
														{
																if ($key2 != 'tmp_name')
																{
																		$_FILES[$key][$key2] = addslashes(trim($_3BF30));
																}
														}
												}
												@reset($_FILES[$key]);
										}
										else
										{
												$_FILES[$key] = addslashes(trim($_3BE8A));
										}
								}
								@reset($_FILES);
						}
				}
		}
		function check_email($email)
		{
				return (preg_match('#^[a-z0-9\._-]+@+[a-z0-9\._-]+\.+[a-z]{2,4}$#si', $email));
		}
		function pagination($total_count, $_3C00B, $current_page, $url, $_3C278 = 2)
		{
				global $Lang;
				$_3C34C['page'] = "";
				$_3C34C['start'] = 0;
				$total_page = ceil($total_count / $_3C00B);
				if ($total_page > 1)
				{
						$url .= (strpos($url, "?")) ? '&' : '?';
						$current_page = ($current_page > $total_page) ? $total_page : (($current_page > 0) ? $current_page : 1);
						if ($total_page <= 2 * $_3C278 + 3)
						{
								$_3C3D4 = 1;
								$_3C405 = $total_page;
						}
						else
						{
								$_3C3D4 = ($current_page < $total_page - $_3C278) ? $current_page - $_3C278 : $total_page - 2 * $_3C278;
								$_3C405 = ($current_page > $_3C278) ? $current_page + $_3C278 : 2 * $_3C278 + 1;
								if ($_3C3D4 < 1)
								{
										$_3C3D4 = 1;
								}
								if ($_3C405 > $total_page)
								{
										$_3C405 = $total_page;
								}
						}
						for ($_3BA12 = $_3C3D4; $_3BA12 <= $_3C405; $_3BA12++)
						{
								$_3C34C['page'] .= ($_3BA12 == $current_page) ? " <span class=pageBoxB>$_3BA12</span>" : " <span class=pageBox><a class='pageItem' href='" . $url . "page=" . $_3BA12 . "'>$_3BA12</a></span>";
						}
						if ($total_page > 2 * $_3C278 + 3)
						{
								if ($_3C3D4 > 1)
								{
										$_3C34C['page'] = '<span class=pageBox><a class="pageItem" href="' . $url . 'page=1" title="' . $Lang->data['page_title_first'] . '"><strong>::</strong></a></span> ... ' . $_3C34C['page'];
								}
								if ($_3C405 < $total_page)
								{
										$_3C34C['page'] .= ' ... <span class=pageBox><a class="pageItem" href="' . $url . 'page=' . $total_page . '" title="' . $Lang->data['page_title_last'] . '"><strong>::</strong></a></span>';
								}
								$_3C4AE = isset($Lang->data['general_page_box']) ? $Lang->data['general_page_box'] : ' » ';
								$_3C5A8 = '   <input class="form" name="_PageBox" id="_PageBox" value="' . $current_page . '" style="width: 32px"> <input type="button" class="submitS" name="_PageGo" value="' . $_3C4AE . '" onclick="javascript: window.location = \'' . $url . 'page=\'+ document.getElementById(\'_PageBox\').value;">';
						}
						else
						{
								$_3C5A8 = '';
						}
						if ($current_page > 1)
						{
								$_3C34C['page'] = "<span class=pageBox><a class='pageItem' href='" . $url . "page=" . ($current_page - 1) . "' title='" . $Lang->data['page_title_previous'] . "'><strong>«</strong></a></span> " . $_3C34C['page'];
						}
						if ($current_page < $total_page)
						{
								$_3C34C['page'] .= " <span class=pageBox><a class='pageItem' href='" . $url . "page=" . ($current_page + 1) . "' title='" . $Lang->data['page_title_next'] . "'><strong>»</strong></a></span>";
						}
						$_3C34C['page'] = '<span class="pageNav"><strong>' . $Lang->data['general_page'] . "</strong> " . $current_page . "/" . $total_page . ": " . $_3C34C['page'] . '</span>' . $_3C5A8;
						$_3C34C['start'] = ($current_page - 1) * $_3C00B;
				}
				return $_3C34C;
		}
		function compile_date()
		{
				global $Lang;
				$this->_3BB84 = array('d1' => "Sunday", 'd2' => "Monday", 'd3' => "Tuesday", 'd4' => "Wednesday", 'd5' => "Thursday", 'd6' => "Friday", 'd7' => "Saturday", 'm1' => "January", 'm2' => "February", 'm3' => "arch", 'm4' => "April", 'm5' => "May", 'm6' => "June", 'm7' => "July", 'm8' => "August", 'm9' => "September", 'm10' => "October", 'm11' => "November", 'm12' => "December", 'ds1' => "Sun", 'ds2' => "Mon", 'ds3' => "Tue", 'ds4' => "Wed", 'ds5' => "Thu", 'ds6' => "Fri", 'ds7' => "Sat", 'ms1' => "Jan", 'ms2' => "Feb", 'ms3' => "Mar", 'ms4' => "Apr", 'ms5' => "May", 'ms6' => "Jun", 'ms7' => "Jul", 'ms8' => "Aug", 'ms9' => "Sep", 'ms10' => "Oct", 'ms11' => "Nov", 'ms12' => "Dec");
				reset($this->_3BB84);
				while (list($key, ) = each($this->_3BB84))
				{
						$this->_3BC13[$key] = '.::' . $key . '::.';
				}
				$_3C65D = $Lang->data['general_date_day'];
				$_3C744 = explode(',', $_3C65D);
				$_3C778 = 1;
				reset($_3C744);
				while (list(, $_3C7CB) = each($_3C744))
				{
						$this->_3BCC6['d' . $_3C778] = trim($_3C7CB);
						$_3C778++;
				}
				$_3C65D = $Lang->data['general_date_month'];
				$_3C744 = explode(',', $_3C65D);
				$_3C778 = 1;
				reset($_3C744);
				while (list(, $_3C880) = each($_3C744))
				{
						$this->_3BCC6['m' . $_3C778] = trim($_3C880);
						$_3C778++;
				}
				$_3C65D = $Lang->data['general_date_day_short'];
				$_3C744 = explode(',', $_3C65D);
				$_3C778 = 1;
				reset($_3C744);
				while (list(, $_3C7CB) = each($_3C744))
				{
						$this->_3BCC6['ds' . $_3C778] = trim($_3C7CB);
						$_3C778++;
				}
				$_3C65D = $Lang->data['general_date_month_short'];
				$_3C744 = explode(',', $_3C65D);
				$_3C778 = 1;
				reset($_3C744);
				while (list(, $_3C880) = each($_3C744))
				{
						$this->_3BCC6['ms' . $_3C778] = trim($_3C880);
						$_3C778++;
				}
		}
		function translate_date($_3C65D)
		{
				if (!sizeof($this->_3BCC6))
				{
						$this->compile_date();
				}
				$_3C65D = str_replace($this->_3BB84, $this->_3BC13, $_3C65D);
				$_3C65D = str_replace($this->_3BC13, $this->_3BCC6, $_3C65D);
				return $_3C65D;
		}
		function check_anydate($_3C880, $_3C90C, $year)
		{
				if (($_3C880 >= 1) && ($_3C880 <= 12) && ($_3C90C >= 1) && ($_3C90C <= 31) && ($year >= 1) && ($year <= 9999))
				{
						if (in_array($_3C880, array(1, 3, 5, 7, 8, 10, 12)))
						{
								return true;
						}
						if (in_array($_3C880, array(4, 6, 9, 11)) && ($_3C90C <= 30))
						{
								return true;
						}
						if ($_3C880 == 2)
						{
								if (($_3C90C <= 28) || ($this->is_leapyear($year) && ($_3C880 <= 29)))
								{
										return true;
								}
						}
				}
				return false;
		}
		function count_month_days($_3C880, $year)
		{
				if (in_array($_3C880, array(1, 3, 5, 7, 8, 10, 12)))
				{
						return 31;
				}
				if (in_array($_3C880, array(4, 6, 9, 11)))
				{
						return 30;
				}
				if ($_3C880 == 2)
				{
						if ($this->is_leapyear($year))
						{
								return 29;
						}
						else
						{
								return 28;
						}
				}
				return 0;
		}
		function is_leapyear($year)
		{
				if (($year % 4 == 0) && (($year % 100 != 0) || ($year % 400 == 0)))
				{
						return true;
				}
				return false;
		}
		function get_days_of_week($year, $_3C880, $_3C90C = 1)
		{
				$_3C9B9 = floor((14 - $_3C880) / 12);
				$y = $year - $_3C9B9;
				$_3CA96 = $_3C880 + 12 * $_3C9B9 - 2;
				$d = ($_3C90C + $y + floor($y / 4) - floor($y / 100) + floor($y / 400) + (floor(31 * $_3CA96) / 12)) % 7;
				return $d;
		}
		function convert_month($_3CB19)
		{
				global $Lang;
				$_3CBCE = $_3CB19 - 1;
				$_3CC81 = explode(',', $Lang->data['general_date_month']);
				if (isset($_3CC81[$_3CBCE]))
				{
						return $_3CC81[$_3CBCE];
				}
				return $_3CB19;
		}
		function import_module_language($lang_file)
		{
				global $Lang, $Info;
				include ("./languages/" . $Info->option['language'] . '/' . $lang_file);
				$Lang->data = array_merge($Lang->data, $Lang_Module->data);
				unset($Lang_Module);
		}
		function string_to_date($_3CEAB, $_3CED9 = 12, $_3CF57 = 0, $_3CF7E = 0)
		{
				if (!empty($_3CEAB))
				{
						$_3C744 = strpos($_3CEAB, '/') ? explode('/', $_3CEAB) : explode('-', $_3CEAB);
						$_3C880 = isset($_3C744[0]) ? intval(trim($_3C744[0])) : 0;
						$_3C90C = isset($_3C744[1]) ? intval(trim($_3C744[1])) : 0;
						$year = isset($_3C744[2]) ? intval(trim($_3C744[2])) : 0;
						if ($_3C90C && $_3C880 && $year)
						{
								if ($year < 100)
								{
										$year += 2000;
								}
								if (checkdate($_3C880, $_3C90C, $year))
								{
										return mktime($_3CED9, $_3CF57, $_3CF7E, $_3C880, $_3C90C, $year);
								}
						}
				}
				return 0;
		}
		function date_to_string($_3C7CB)
		{
				if ($_3C7CB)
				{
						return gmdate('m/d/Y', $_3C7CB);
				}
				return "";
		}
		function get_dirs($root)
		{
				if (!is_dir($root)) die("$root is not a directory");
				$dir = array();
				$dir["count"] = 0;
				$_3D064 = opendir($root);
				while (($_3D06D = readdir($_3D064)) != false)
				{
						if (($_3D06D != ".") && ($_3D06D != "..") && is_dir($root . "/" . $_3D06D))
						{
								$dir["name"][] = $_3D06D;
								$dir["count"]++;
						}
				}
				return $dir;
		}
		function show_language_dirs()
		{
				global $Func, $Template;
				$dir = $Func->get_dirs("./languages");
				for ($_3BA12 = 0; $_3BA12 < $dir['count']; $_3BA12++)
				{
						$Template->set_block_vars("langrow", array("NAME" => $dir["name"][$_3BA12]));
				}
		}
		function show_template_dirs()
		{
				global $Func, $Template;
				$dir = $Func->get_dirs("./templates");
				for ($_3BA12 = 0; $_3BA12 < $dir['count']; $_3BA12++)
				{
						$Template->set_block_vars("templaterow", array("NAME" => $dir["name"][$_3BA12]));
				}
		}
}
?>