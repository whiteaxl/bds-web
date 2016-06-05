<?php
/* =============================================================== *\
|		Module name:      Image										|
|																	|
\* =============================================================== */
if (!defined('IN_SITE')){
     die('Hacking attempt!');
}

class Image
{
	//Resize image
	function resize_image($filename, $max_width = 0, $max_height = 0, $resize_type = "all"){
		if ( empty($filename) || (($resize_type == "width") && !$max_width) || (($resize_type == "height") && !$max_height) || (($resize_type == "all") && !$max_height && !$max_height) ){
			return false;
		}

		$image_type	= array(1 => 'gif', 2 => 'jpeg', 3 => 'png');
		//Get image size
		if (!$size	= @getimagesize($filename)){
			return false;
		}
		if ( !isset($image_type[$size[2]]) ){
			return false;
		}

		if ( $resize_type == 'all' ){
			if ( !$max_width ){
				$resize_type	= "height";
			}
			else if ( !$max_height ){
				$resize_type	= "width";
			}
			else{
				$resize_type = ( $size[0]/$size[1] > $max_width/$max_height ) ? 'width' : 'height';
			}
		}

		$flag	= 0;
		if ($resize_type == 'width'){
			if ($size[0] > $max_width){
				$new_width	= $max_width;
				$new_height	= floor(($max_width * $size[1]) / $size[0]);
				$flag		= 1;
			}
		}
		else if ($resize_type == 'height'){
			if ($size[1] > $max_height){
				$new_height	= $max_height;
				$new_width	= floor(($max_height * $size[0]) / $size[1]);
				$flag		= 1;
			}
		}
		if ( !$flag ) return false;

		$imgcreate	= "imagecreatefrom". $image_type[$size[2]];
		if ( $img_source = $imgcreate($filename) ){
			$img_dest	= imagecreatetruecolor($new_width, $new_height);
			imagecopyresized($img_dest, $img_source, 0, 0, 0, 0, $new_width, $new_height, $size[0], $size[1]);
			imagedestroy($img_source);
			@unlink($filename);
			
			$imgcreate	= "image". $image_type[$size[2]];
			if ( !function_exists($imgcreate) ){
				$imgcreate	= "image". $image_type[2];
			}
			$imgcreate($img_dest, $filename);
			imagedestroy($img_dest);
			return true;
		}
		return false;
	}

	//Create image thumbnail
	function create_thumbnail($filename, $thumbname, $thumb_width, $thumb_height){
		if ( empty($filename) || empty($thumbname) || !$thumb_width || !$thumb_height ){
			return false;
		}

		$image_type	= array(1 => 'gif', 2 => 'jpeg', 3 => 'png');
		//Get image size
		if (!$size	= @getimagesize($filename)){
			return false;
		}
		if ( !isset($image_type[$size[2]]) ){
			return false;
		}

		$imgcreate	= "imagecreatefrom". $image_type[$size[2]];
		if ( $img_source = $imgcreate($filename) ){
			$img_dest	= imagecreatetruecolor($thumb_width, $thumb_height);
			imagecopyresampled($img_dest, $img_source, 0, 0, 0, 0, $thumb_width, $thumb_height, $size[0], $size[1]);
			imagedestroy($img_source);
			
			$imgcreate	= "image". $image_type[$size[2]];
			if ( !function_exists($imgcreate) ){
				$imgcreate	= "image". $image_type[2];
			}
			$imgcreate($img_dest, $thumbname);
			imagedestroy($img_dest);
			return true;
		}

		return false;
	}

	function convert_text_to_image($counter, $img_path){
		//Get digit dimension
		$digit_info	= getimagesize($img_path .'0.gif');
		if ( !$digit_info ){
			return false;
		}
		$digit_width	= $digit_info[0];
		$digit_height	= $digit_info[1];
		$counter_length	= strlen("$counter");

		$image_width	= $counter_length * $digit_width;
		$imagestream	= imagecreate($image_width, $digit_height);
		$black			= ImageColorAllocate($imagestream, 0, 0, 0);
		imagefill($imagestream, 0, 0, $black);
		imagecolortransparent($imagestream, $black);
		for ($i=0; $i<$counter_length; $i++) {
			$digit			= substr($counter, $i, 1);
			$digit_file		= $img_path . $digit .".gif";
			$digit_image	= imagecreatefromgif($digit_file);
			$digit_location = $i * $digit_width;
			imagecopy($imagestream, $digit_image, $digit_location, 0, 0, 0, $digit_width, $digit_height);
			imagedestroy($digit_image);
		}

		//Display counter
		header("Content-type: image/gif");
		imagegif($imagestream);
		imagedestroy($imagestream);
	}
}
?>