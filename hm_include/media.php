<?php
/**
 * Xử lý media
 * Vị trí : hm_include/media.php
 */
if ( ! defined('BASEPATH')) exit('403');

/** Kiểm tra nhóm media tồn tại */
function isset_media_group($args=array()){
	if(!is_array($args)){
		parse_str($args, $args);
	}

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);

	$group_name = $args['group_name'];
	$group_parent = $args['group_parent'];
	$folder = sanitize_title($group_name);
	if($group_parent > 0){
		$folder_part = get_media_group_part($group_parent).'/'.$folder;
	}else{
		$folder_part = $folder;
	}
	$dir = BASEPATH.HM_CONTENT_DIR.'/uploads/'.$folder_part;

	if(file_exists($dir) && is_dir($dir)){
		return TRUE;
	}else{
		return FALSE;
	}
}

/** Check file tồn tại */
function isset_file($id){
	if( is_numeric($id) ){

		$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);

		$tableName=DB_PREFIX."media";
		$whereArray=array('id'=>MySQL::SQLValue($id));
		$hmdb->SelectRows($tableName,$whereArray);
		$rowCount = $hmdb->RowCount();
		if($rowCount!=0){
			return TRUE;
		}else{
			return FALSE;
		}
	}else{
		return FALSE;
	}
}


/** Thêm nhóm media */
function add_media_group($args=array()){

	if(!is_array($args)){
		parse_str($args, $args);
	}

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);

	$group_name = $args['group_name'];
	$group_parent = $args['group_parent'];
	$folder = sanitize_title($group_name);

	if($group_parent > 0){
		$folder_part = get_media_group_part($group_parent).'/'.$folder;
	}else{
		$folder_part = $folder;
	}
	$dir = BASEPATH.HM_CONTENT_DIR.'/uploads/'.$folder_part;

	/* Tạo fodel */
	if(!file_exists($dir) && !is_dir($dir)){

		mkdir($dir);
		chmod($dir,0777);

		/** tạo .htaccess */
		$fp=fopen($dir.'/.htaccess','w');
		$content_htaccess='RemoveHandler .php .phtml .php3'."\n".'RemoveType .php .phtml .php3'."\n".'<Files *>'."\n".'SetHandler default-handler'."\n".'</Files>';
		fwrite($fp,$content_htaccess);
		fclose($fp);

		/** lưu nhóm vào data base */
		$tableName=DB_PREFIX.'media_groups';

		$values["name"] = MySQL::SQLValue($group_name);
		$values["folder"] = MySQL::SQLValue($folder);
		$values["parent"] = MySQL::SQLValue($group_parent);
		$insert_id=$hmdb->InsertRow($tableName, $values);

		$array=array('type'=>'success','name'=>$group_name,'id'=>$insert_id);
		return hm_json_encode($array);

	}else{
		$array=array('type'=>'error','mes'=>'Có một thư mục cùng tên (hoặc có tên gần giống) đã tồn tại');
		return hm_json_encode($array);
	}

}

/** Lấy thông tin file */
function get_file_data($id){
	if(isset_file($id)){
		$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
		$tableName=DB_PREFIX."media";
		$whereArray=array('id'=>MySQL::SQLValue($id));
		$hmdb->SelectRows($tableName,$whereArray);
		return $hmdb->Row();
	}else{
		return FALSE;
	}
}

/** Check file tồn tại và là ảnh*/
function isset_image($id){
	if( is_numeric($id) ){
		$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
		$tableName=DB_PREFIX."media";
		$whereArray = array(
						'id' => MySQL::SQLValue($id),
						'file_is_image' => MySQL::SQLValue('true'),
					);
		$hmdb->SelectRows($tableName,$whereArray);
		$rowCount = $hmdb->RowCount();
		if($rowCount!=0){
			return TRUE;
		}else{
			return FALSE;
		}
	}else{
		return FALSE;
	}
}

/** Lấy đường dẫn nhóm media */
function get_media_group_part($id=0,$i=1,$deepest=FALSE){

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);

	if(!is_numeric($id)){
		$tableName=DB_PREFIX.'media_groups';
		$whereArray=array('folder'=>MySQL::SQLValue($id));
		$hmdb->SelectRows($tableName, $whereArray);
		$row=$hmdb->Row();
		$id = $row->id;
	}


	$bre = array();
	$sub_bre = FALSE;

	if($deepest == FALSE){
		$deepest=$id;
	}

	$tableName=DB_PREFIX.'media_groups';
	$whereArray=array('id'=>MySQL::SQLValue($id));
	$hmdb->SelectRows($tableName, $whereArray);
	$row=$hmdb->Row();
	$num_rows = $hmdb->RowCount();

	if($num_rows!=0){

		$this_id = $row->id;
		$folder = $row->folder;
		$parent = $row->parent;

		$bre['level_'.$i] = $folder;
		if($parent != '0'){
			$inew = $i + 1;
			$sub_bre = get_media_group_part($parent,$inew,$deepest);
		}
	}

	if(is_array($sub_bre)){
		$bre = array_merge($bre,$sub_bre);
	}

	krsort($bre);
	$part = implode("/",$bre);

	if($deepest == $id){
		return $part;
	}else{
		return $bre;
	}
}

/** Đường dẫn tĩnh của file */
function get_file_url($id,$include_file_name=TRUE){

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);

	if(isset_file($id)){
		$row=get_file_data($id);
		$file_info = $row->file_info;
		$file_name = $row->file_name;
		$file_folder = $row->file_folder;
		$media_group_id = $row->media_group_id;
		if($file_folder!='/'){
			$file_folder_part = '/'.get_media_group_part($media_group_id).'/';
		}else{
			$file_folder_part = '/';
		}
		$file_info = json_decode($file_info,TRUE);
		if($include_file_name){
			$file_url = BASE_URL.HM_CONTENT_DIR.'/uploads'.$file_folder_part.$file_info['file_dst_name'];
		}else{
			$file_url = BASE_URL.HM_CONTENT_DIR.'/uploads'.$file_folder_part;
		}
		return $file_url;

	}else{
		return FALSE;
	}

}

/** location file */
function get_file_location($id,$include_file_name=TRUE){

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);

	if(isset_file($id)){
		$row=get_file_data($id);
		$file_info = $row->file_info;
		$file_name = $row->file_name;
		$file_folder = $row->file_folder;
		$media_group_id = $row->media_group_id;
		if($file_folder!='/'){
			$file_folder_part = '/'.get_media_group_part($media_group_id).'/';
		}else{
			$file_folder_part = '/';
		}

		$file_info = json_decode($file_info,TRUE);
		if($include_file_name){
			$file_location = BASEPATH.HM_CONTENT_DIR.'/uploads'.$file_folder_part.$file_info['file_dst_name'];
		}else{
			$file_location = BASEPATH.HM_CONTENT_DIR.'/uploads'.$file_folder_part;
		}
		return $file_location;

	}

}

/** Cắt ảnh theo cỡ tùy chọn */
function create_image($args){

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$args = hook_filter('before_create_image',$args);
	hook_action('create_image');

	if(!is_array($args)){
		parse_str($args, $args);
	}

	$default_array = array(
						'file' => 0,
						'crop' => FALSE,
						'w' => '200',
						'h' => '200',
						'q' => 100,
					);
	$args = hm_parse_args($args,$default_array);

	$file_id = $args['file'];
	$crop = $args['crop'];
	$image_quality = $args['q'];
	if($crop == 'true'){
		$crop = TRUE;
	}
	if(isset_image($file_id)){

		$row = get_file_data($file_id);
		$file_info = json_decode($row->file_info,TRUE);
		$file_dst_name_body = $file_info['file_dst_name_body'];
		$file_dst_name_ext = $file_info['file_dst_name_ext'];
		$source_file = get_file_location($file_id);
		$source_file_dir = get_file_location($file_id,FALSE);
		$label_image_quality = '';
		if($image_quality!=100){
			$label_image_quality = '-'.$image_quality;
		}
		if($crop==TRUE){
			$new_image_name = $file_dst_name_body.'-'.$args['w'].'x'.$args['h'].$label_image_quality.'-crop.'.$file_dst_name_ext;
		}else{
			$new_image_name = $file_dst_name_body.'-'.$args['w'].'x'.$args['h'].$label_image_quality.'.'.$file_dst_name_ext;
		}
		if( file_exists($source_file_dir.$new_image_name) ){
			return get_file_url($file_id,FALSE).$new_image_name;
		}else{

			/** resize file */
			$type = getimagesize($source_file);

			$type = $type['mime'];
			switch($type){
				case 'image/png': $type = IMAGETYPE_PNG; break;
				case 'image/jpeg': $type = IMAGETYPE_JPEG; break;
				case 'image/gif': $type = IMAGETYPE_GIF; break;
				case 'image/bmp': $type = IMAGETYPE_BMP; break;
				case 'image/x-ms-bmp': $type = IMAGETYPE_BMP; break;
			};
			/* fix func exif_imagetype not avaiable */

			switch ($type) {
				case 1 :
					$source = imageCreateFromGif($source_file);
				break;
				case 2 :
					$source = imageCreateFromJpeg($source_file);
				break;
				case 3 :
					$source = imageCreateFromPng($source_file);
				break;
				case 6 :
					$source = imageCreateFromBmp($source_file);
				break;
			}

			if($crop==TRUE){

				$thumb_width = $args['w'];
				$thumb_height = $args['h'];
				$width = imagesx($source);
				$height = imagesy($source);
				$original_aspect = $width / $height;
				$thumb_aspect = $thumb_width / $thumb_height;
				if ( $original_aspect >= $thumb_aspect )
				{
				   $new_height = $thumb_height;
				   $new_width = $width / ($height / $thumb_height);
				}
				else
				{
				   $new_width = $thumb_width;
				   $new_height = $height / ($width / $thumb_width);
				}
				$thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
				imagecopyresampled($thumb,
								   $source,
								   0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
								   0 - ($new_height - $thumb_height) / 2, // Center the image vertically
								   0, 0,
								   $new_width, $new_height,
								   $width, $height);
				$saveto = $source_file_dir.$new_image_name;
				imagejpeg($thumb,$saveto, $image_quality);

			}else{

				/** resize file gốc về cùng 1 cỡ */
				$size = getimagesize($source_file);
				$source_width = $size[0];
				$source_height = $size[1];

				$fix_width = $args['w'];
				$fix_height = $args['h'];

				$thumb = imagecreatetruecolor($fix_width, $fix_height);

				/* Fix black background */
				$white = imagecolorallocate($thumb, 255, 255, 255);
				imagefill($thumb,0,0,$white);
				/* Fix black background */

				/* fix quality with imagecopyresampled , repalce imagecopyresized */
				imagecopyresampled($thumb, $source, 0, 0, 0, 0, $fix_width, $fix_height, $source_width, $source_height);

				$saveto = $source_file_dir.$new_image_name;
				imagejpeg($thumb,$saveto, $image_quality);

			}

			/** update file data */
			if(!is_array($file_info['crop'])){
				$file_info['crop'] = array();
			}
			if(!in_array($new_image_name,$file_info['crop'])){
				$file_info['crop'][] =  array(
											'file_id'=>$file_id,
											'w'=>$args['w'],
											'h'=>$args['h'],
											'q'=>$image_quality,
											'crop'=>$crop,
											'name'=>$new_image_name,
										);
			}
			$tableName=DB_PREFIX.'media';
			$valuesArray = array(
				'file_info' => MySQL::SQLValue(json_encode($file_info)),
			);
			$whereArray = array(
				'id' => MySQL::SQLValue($file_id),
			);
			$hmdb->UpdateRows($tableName, $valuesArray, $whereArray);

			return get_file_url($file_id,FALSE).$new_image_name;

		}
	}else{
		return BASE_URL.HM_FRONTENT_DIR.'/images/lostimage.png';
	}

}

?>
