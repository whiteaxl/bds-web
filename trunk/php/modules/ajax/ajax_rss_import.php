<?php
/* =============================================================== *\
|		Module name: RSS Import										|
|		Begin: 20 January 2007										|
|																	|
\* =============================================================== */

if (!defined('IN_SITE')){
     die('Hacking attempt!');
}
//=============================================
class Func_Global
{
	function check_gpc(){}
	function import_module_language($lang_file){
		global $Lang, $Info;
		include("./languages/". $Info->option['language'] .'/'. $lang_file);
		$Lang->data		= array_merge($Lang->data, $Lang_Module->data);
		unset($Lang_Module);
	}
}
//=============================================

//Lang
include("./languages/". $Info->option['language'] ."/lang_main". PHP_EX);
include("./languages/". $Info->option['language'] ."/home/lang_home". PHP_EX);
$Lang	= new Lang_Home;
$Lang->Lang_Global();//Construction

//Functions
include("./includes/functions_admin". PHP_EX);
$Func	= new Func_Admin;
//Module language
$Func->import_module_language("home/lang_rss_import". PHP_EX);

//Image
include("./includes/image". PHP_EX);
$Image	= new Image;

//File Management
include("./includes/file_web". PHP_EX);
$File  = new FileMan;

//RSS reader
include('./includes/rss_reader.php');
$RSS	= new Rss_Reader;

include('./modules/admin/ad_article_global.php');
$ArticleGlobal	= new Admin_Article_Global;

$RssImport	= new Rss_Import;

class Rss_Import
{
	var $data		= array();

	function Rss_Import(){
		$this->import_rss();
	}

	function import_rss(){
		global $DB, $Lang, $Func, $Info, $RSS, $ArticleGlobal, $Image, $File;

		$this->data['rss_id']		= isset($_GET["rss_id"]) ? intval($_GET["rss_id"]) : 0;
		$this->data['rss_code']		= isset($_GET["rss_code"]) ? htmlspecialchars($_GET["rss_code"]) : '';

		if ( !$this->data['rss_id'] || empty($this->data['rss_code']) ){
			echo $Lang->data['rss_import_error_notfull'];
			return false;
		}

		$total_counter	= 0;

		//Get rss info
		$DB->query("SELECT * FROM ". $DB->prefix ."rss_import WHERE rss_id=". $this->data['rss_id']);
		if ( !$DB->num_rows() ){
			echo "SELECT * FROM ". $DB->prefix ."rss_import WHERE rss_id=". $this->data['rss_id'] ."<br><br>\n\n";
			echo $Lang->data['rss_import_error_notfound'];
			return false;
		}
		$rss_info	= $DB->fetch_array();

		//Check rss code
		if ( md5($rss_info['rss_url'] .'__'. $rss_info['posted_date']) != $this->data['rss_code'] ){
			echo md5($rss_info['rss_url'] .'__'. $rss_info['posted_date']), " ------ ", $this->data['rss_code'],"<br><br>\n\n";
			echo $Lang->data['rss_import_error_notfound'];
			return false;
		}

		//Get rss contents
		if ( !$rss_info['rss_auth'] ){
			$rss_info['rss_auth_user']	= "";
			$rss_info['rss_auth_pass']	= "";
		}
		$RSS->fetch_rss($rss_info['rss_url'], $rss_info['rss_auth_user'], $rss_info['rss_auth_pass'], $Lang->charset, "", true, $rss_info['rss_convert_charset']);

		//Get user id
		$user_id	= 0;
		if ( !empty($rss_info['article_username']) ){
			$DB->query("SELECT user_id FROM ". $DB->prefix ."user WHERE username='". $rss_info['article_username'] ."'");
			if ( $DB->num_rows() ){
				$tmp_info	= $DB->fetch_array();
				$user_id	= $tmp_info['user_id'];
			}
		}
		$post_count	= 0;

		//Import Rss
		$flag	= true;
		$record_ids	= "";
		reset($RSS->rss_items);
		while (list(,$item_data) = each($RSS->rss_items)){
			if ( !is_array($item_data) ){
				continue;
			}
			while (list(,$item_info) = each($item_data)){
				if ( !isset($item_info['title']) || empty($item_info['title']) || !isset($item_info['desc']) || !isset($item_info['link']) || !isset($item_info['unix_date']) ){
					continue;
				}

				//Remove html tags
				if ( $rss_info['article_remove_html'] ){
					$item_info['desc']		= strip_tags($item_info['desc']);
				}
				$item_info["title"]			= addslashes($item_info["title"]);
				$item_info["desc"]			= addslashes($item_info["desc"]);
				$item_info["link"]			= addslashes($item_info["link"]);
				$item_info["thumb_large"]	= isset($item_info["enclosure_image"]) ? addslashes($item_info["enclosure_image"]) : "";
				$item_info['unix_date']		= intval($item_info['unix_date']);

				//Check exist
				$DB->query("SELECT article_id FROM ". $DB->prefix ."article WHERE title='". $item_info["title"] ."' AND cat_id=". $rss_info['article_cat_id'] ." AND content_desc LIKE '". $item_info['desc'] ."' AND content_url='". $item_info['link'] ."'");
				if ( $DB->num_rows() ){
					continue;
				}

				//Insert article
				$sql		= "INSERT INTO ". $DB->prefix ."article(cat_id, topic_id, thumb_large, thumb_small, thumb_icon, title, content_desc, content_url, poster_id, checker_id, posted_date, is_hot, article_type, page_counter, enabled)
										VALUES(". $rss_info["article_cat_id"] .", 0, '', '', '', '". $item_info["title"] ."', '". $item_info["desc"] ."', '". $item_info["link"] ."', $user_id, 0, ". $item_info["unix_date"] .", 0, ". $rss_info['article_type'] .", 1, ". $rss_info['article_enabled'] .")";

				$DB->query($sql);
				$article_id	= $DB->insert_id();
				$record_ids	.= !empty($record_ids) ? ", ". $article_id : $article_id;
				$post_count++;
				$total_counter++;

				//Insert article detail
				$DB->query("INSERT INTO ". $DB->prefix ."article_page(article_id, page_title, used_files, page_order, page_enabled) VALUES($article_id, '". $item_info['title'] ."', '', 1, 1)");
				$page_id	= $DB->insert_id();
				$DB->query("INSERT INTO ". $DB->prefix ."article_page_content(page_id, article_id, content_detail, author) VALUES($page_id, $article_id, '". $item_info['desc'] ."', '". $rss_info['article_author'] ."')");

				//Insert rss import logs
				$DB->query("INSERT INTO ". $DB->prefix ."rss_imported(rss_id, article_id, import_date) VALUES(". $rss_info['rss_id'] .", $article_id, ". CURRENT_TIME .")");

				if ( !empty($item_info['thumb_large']) ){
					$imgtype 		= substr($item_info['thumb_large'], -4);
					$allowed_types	= explode(',', $Info->option['image_type']);
					$filename		= "img". rand(100, 10000);
					if ( in_array($imgtype, $allowed_types) && @copy($item_info['thumb_large'], "./upload/". $filename) ){
						$imgsize	= getimagesize("./upload/". $filename);
						clearstatcache();
						if ( $imgsize[2] == 1 ){
							$item_info['thumb_large']	= $filename .'.gif';
						}
						else if ( $imgsize[2] == 2 ){
							$item_info['thumb_large']	= $filename .'.jpg';
						}
						else if ( $imgsize[2] == 6 ){
							$item_info['thumb_large']	= $filename .'.bmp';
						}
						else{
							$item_info['thumb_large']	= "";
							@unlink("./upload/". $filename);
						}

						if ( !empty($item_info['thumb_large']) ){
							//Make image dir for this article
							$ArticleGlobal->make_image_dir($item_info["unix_date"], $article_id);

							//Update pic thumb
							//Thumb large -----------------------
							if ( file_exists($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_large"]) ){
								$count		= 1;
								$thumb_large	= str_replace(".", $count .".", $item_info["thumb_large"]);
								while ( file_exists($ArticleGlobal->sysdir['id'] .'/'. $thumb_large) ){
									$count++;
									$thumb_large	= str_replace(".", $count .".", $item_info["thumb_large"]);
								}
								$item_info['thumb_large'] = $thumb_large;
							}
							$File->copy_file("./upload/". $filename, $ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_large"]);
							$Image->resize_image($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_large"], $Info->option['thumb_large_max_width'], $Info->option['thumb_large_max_height'], 'all');
							@unlink("./upload/". $filename);
							//-----------------------------------

							//Thumb small -----------------------
							$item_info['thumb_small']	= 'small_'. $item_info['thumb_large'];
							if ( file_exists($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_small"]) ){
								$count		= 1;
								$thumb_small	= str_replace(".", $count .".", $item_info["thumb_small"]);
								while ( file_exists($ArticleGlobal->sysdir['id'] .'/'. $thumb_small) ){
									$count++;
									$thumb_small	= str_replace(".", $count .".", $item_info["thumb_small"]);
								}
								$item_info['thumb_small'] = $thumb_small;
							}
							$File->copy_file($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_large"], $ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_small"]);
							$Image->resize_image($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_small"], $Info->option['thumb_small_max_width'], $Info->option['thumb_small_max_height'], 'all');
							//-----------------------------------

							//Thumb icon ------------------------
							$item_info['thumb_icon']	= 'icon_'. $item_info['thumb_large'];
							if ( file_exists($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_icon"]) ){
								$count		= 1;
								$thumb_icon	= str_replace(".", $count .".", $item_info["thumb_icon"]);
								while ( file_exists($ArticleGlobal->sysdir['id'] .'/'. $thumb_icon) ){
									$count++;
									$thumb_icon	= str_replace(".", $count .".", $item_info["thumb_icon"]);
								}
								$item_info['thumb_icon'] = $thumb_icon;
							}
							$Image->create_thumbnail($ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_large"], $ArticleGlobal->sysdir['id'] .'/'. $item_info["thumb_icon"], $Info->option['thumb_icon_max_width'], $Info->option['thumb_icon_max_height']);
							//-----------------------------------

							//Update article
							$DB->query("UPDATE ". $DB->prefix ."article SET thumb_large='". $item_info['thumb_large'] ."', thumb_small='". $item_info['thumb_small'] ."', thumb_icon='". $item_info['thumb_icon'] ."' WHERE article_id=$article_id");
						}
					}
				}

				//Limit articles
				if ( $rss_info['rss_import_pergo'] && ($post_count >= $rss_info['rss_import_pergo']) ){
					$flag	= false;
					break;
				}
			}
			if ( !$flag ){
				break;
			}
		}

		//Increase author' posts
		if ( $user_id && $rss_info['article_userpost_increase'] ){
			$DB->query("UPDATE ". $DB->prefix ."user SET article_counter=article_counter+". $post_count ." WHERE user_id=". $user_id);
		}

		//Update last import date
		$DB->query("UPDATE ". $DB->prefix ."rss_import SET last_import_date=". CURRENT_TIME ." WHERE rss_id=". $rss_info['rss_id']);

		//Resync categories' counters
		$this->resync_cats();

		//Resync rss counters
		$this->resync_rss_counters();

		echo sprintf($Lang->data['rss_import_success'], $total_counter);
		return true;
	}

	function resync_rss_counters(){
		global $DB, $Lang;

		//Delete expired data
		$DB->query('DELETE FROM '. $DB->prefix .'rss_imported WHERE article_id NOT IN (SELECT article_id FROM '. $DB->prefix .'article)', 0);

		//Reset counters
		$DB->query('UPDATE '. $DB->prefix .'rss_import SET import_counter=0');

		//Count articles
		$DB->query('SELECT count(article_id) AS counter, rss_id FROM '. $DB->prefix.'rss_imported GROUP BY rss_id');
		$rss_count = $DB->num_rows();
		$rss_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$rss_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'rss_import SET import_counter='. $rss_data[$i]['counter'] .' WHERE rss_id='. $rss_data[$i]['rss_id']);
		}
	}

	function resync_cats(){
		global $DB, $Lang;

		//Reset counters
		$DB->query('UPDATE '. $DB->prefix .'article_category SET article_counter=0');

		//Update article_counter
		$DB->query('SELECT count(article_id) AS counter, cat_id FROM '. $DB->prefix.'article GROUP BY cat_id');
		$cat_count = $DB->num_rows();
		$cat_data  = $DB->fetch_all_array();
		$DB->free_result();

		for ($i=0; $i<$cat_count; $i++){
			$DB->query('UPDATE '. $DB->prefix .'article_category SET article_counter='. $cat_data[$i]['counter'] .' WHERE cat_id='. $cat_data[$i]['cat_id']);
		}
	}
}

?>