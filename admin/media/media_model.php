<?php
/** 
 * Tệp tin model của media trong admin
 * Vị trí : admin/media/media_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');
/** Load template media box */
function ajax_media_box(){
	
	hook_action('ajax_media_box');
	
	/** Tạo thư mục tự động theo tháng */
	if(!defined('MEDIA_FOLDER_BY_MONTH')){
		define('MEDIA_FOLDER_BY_MONTH',FALSE);
	}
	if(MEDIA_FOLDER_BY_MONTH == TRUE){
		$time = time();
		$folder_month = date('m_Y');
		$dir = BASEPATH.HM_CONTENT_DIR.'/uploads/'.$folder_month;
		if (!file_exists($dir) && !is_dir($dir)) {
			$args = array();
			$args['group_name'] = $folder_month;
			$args['group_parent'] = 0;
			add_media_group($args);
		} 
	}
	
	require_once(BASEPATH . HM_ADMINCP_DIR . '/' . TEMPLATE_DIR . '/' . 'ajax_media_box.php');
		
}
/** ID thư mục truy cập gần đây */
function media_group_id(){
	$media_group_id = hm_get('media_group_id');
	if(is_numeric($media_group_id)){
		$_SESSION['media_group_id'] = $media_group_id;
	}else{
		if(isset($_SESSION['media_group_id'])){
			$media_group_id = $_SESSION['media_group_id'];
		}else{
			
			if(!defined('MEDIA_FOLDER_BY_MONTH')){
				define('MEDIA_FOLDER_BY_MONTH',FALSE);
			}
			if(MEDIA_FOLDER_BY_MONTH == TRUE){
				$time = time();
				$folder_month = date('m_Y');
				$dir = BASEPATH.HM_CONTENT_DIR.'/uploads/'.$folder_month;
				if (file_exists($dir) && is_dir($dir)) {
					$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
					$tableName=DB_PREFIX.'media_groups';
					$whereArray=array('folder'=>MySQL::SQLValue($folder_month));
					$hmdb->SelectRows($tableName, $whereArray);
					$num_rows = $hmdb->RowCount();
					if($num_rows!=0){
						$row=$hmdb->Row();
						$media_group_id = $row->id;
					}
				} 
			}else{
				$media_group_id = 0;
			}
			
		}
	}
	return $media_group_id;
}

/** Tạo cây danh mục media */
function media_group_tree($parent=0){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	$tableName=DB_PREFIX."media_groups";
	$whereArray=array('parent'=>MySQL::SQLValue($parent));
	
	$hmdb->SelectRows($tableName, $whereArray);
	$rowCount = $hmdb->RowCount();
	
	$media_group_id = media_group_id();
	
	if($rowCount!=0){
		
		if( $parent!=0 ){
			echo '<ul class="media_tree_sub_group media_tree_sub_group_of_'.$parent.'">';
		}
		
		while( $row=$hmdb->Row() ){
			$groups[]=$row;
		}
		
		foreach ($groups as $group){
			
			if($group->id == $media_group_id){$checked = 'checked';}else{$checked = NULL;}
			
			echo '<li data-id="'.$group->id.'" data-folder="'.$group->folder.'" class="media_tree_item media_tree_item_'.$group->id.'">';
				
				echo '<label class="radio-inline"><input type="radio" '.$checked.' name="media_group" value="'.$group->id.'"> '.$group->name.'</label>';
				media_group_tree($group->id);
				
			echo '</li>';
			
		}
		
		unset($groups);
		
		if( $parent!=0 ){
			echo '</ul>';
		}
		
	}
	
}

/** Tạo select danh mục media */
function media_group_select($field_array = array()){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	$parent = '0';
	$defaults = array(
					'wrapper' => FALSE,
					'handle' => FALSE,
					'addClass' => 'media_group_select',
					'input_option' => $input_option,
				);
	$field_array = hm_parse_args( $field_array, $defaults );
	
	$input_option = array(
						array(
							'value' => '0',
							'label' => '/',
						),
					);
	
	$tableName=DB_PREFIX."media_groups";
	$whereArray=array('parent'=>MySQL::SQLValue($parent));
	$hmdb->SelectRows($tableName, $whereArray);
	$rowCount = $hmdb->RowCount();
	if($rowCount!=0){
		
		while( $row=$hmdb->Row() ){
			$groups[]=$row;
		}
		
		foreach ($groups as $group){
			
			$group_id = $group->id;
			$whereArray=array('parent'=>MySQL::SQLValue($group_id));
			$hmdb->SelectRows($tableName, $whereArray);
			$rowCount = $hmdb->RowCount();
			
			$input_option[] =	array(
									'value' => $group->id,
									'label' => '/'.$group->name,
								);
			
			if($rowCount!=0){
				$input_option = media_group_select_sub($group_id,$input_option);
			}
			
		}
		
	}
	
	$field_array['input_option'] = $input_option;
	$field_array['default_value'] = media_group_id();
	input_select($field_array);

}

function media_group_select_sub($parent = '0',$input_option = array()){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$tableName=DB_PREFIX."media_groups";
	$whereArray=array('parent'=>MySQL::SQLValue($parent));
	
	$hmdb->SelectRows($tableName, $whereArray);
	$rowCount = $hmdb->RowCount();
	if($rowCount!=0){
		
		while( $row=$hmdb->Row() ){
			$groups[]=$row;
		}
		
		foreach ($groups as $group){
			
			$group_id = $group->id;
			$group_name = '';
			$whereArray=array('parent'=>MySQL::SQLValue($group_id));
			$hmdb->SelectRows($tableName, $whereArray);
			$rowCount = $hmdb->RowCount();
			
			$bre = breadcrumb_folder($group_id); 
			foreach($bre as $item){
				$group_name = $group_name.$item['name'].'/';
			}
			
			$input_option[] =	array(
									'value' => $group_id,
									'label' => '/'.$group_name,
								);
			
			if($rowCount!=0){
				$input_option = media_group_select_sub($group_id,$input_option);
			}
			
		}
		
	}
	
	return $input_option;

}

/** Breadcrumb nhóm media */
function breadcrumb_folder($id=0,$i=1){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	$bre = array();
	$sub_bre = FALSE;
	
	$tableName=DB_PREFIX.'media_groups';
	$whereArray=array('id'=>MySQL::SQLValue($id));
	$hmdb->SelectRows($tableName, $whereArray);
	$row=$hmdb->Row();
	$num_rows = $hmdb->RowCount();
	if($num_rows!=0){
		
		$name = $row->name;
		$parent = $row->parent;
		
		$bre['level_'.$i]['name'] = $name;
		$bre['level_'.$i]['id'] = $id;
		if($parent != '0'){
			$inew = $i + 1;
			$sub_bre = breadcrumb_folder($parent,$inew);
		}
	}
	if(is_array($sub_bre)){
		$bre = array_merge($bre,$sub_bre);
	}
	krsort($bre);
	return $bre;
	
}


/** Ajax upload */
function add_media(){
	
	if (isset($_SERVER["CONTENT_LENGTH"])){
		if($_SERVER["CONTENT_LENGTH"]>((int)ini_get('post_max_size')*1024*1024)){
			return hm_json_encode(array('status'=>'error','content'=>_('Dung lượng tệp tin gửi lên vượt quá giới hạn cho phép của máy chủ')));
			hm_exit();
		}
	}
	
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	@$media_group=hm_post('media_group');
	
	if( !is_numeric($media_group) ){
		$media_group=0;
	}
	
	$tableName=DB_PREFIX.'media_groups';
	$whereArray=array('id'=>MySQL::SQLValue($media_group));
	$hmdb->SelectRows($tableName, $whereArray);
	$count=$hmdb->RowCount();
	
	if($count!='0'){
		$row=$hmdb->Row();
		$folder=$row->folder;
		$folder_part = get_media_group_part($media_group);
		$dir = BASEPATH.HM_CONTENT_DIR.'/uploads/'.$folder_part;
		if(!file_exists($dir)){
			mkdir($dir);
			chmod($dir,0777);
		}
		$dir_dest = BASEPATH.HM_CONTENT_DIR.'/uploads/'.$folder_part;
		
	}else{
		$folder="/";
		$media_group=0;
		$dir_dest = BASEPATH.HM_CONTENT_DIR.'/uploads';
	}
	
	/** tạo .htaccess */
	$fp=fopen($dir_dest.'/.htaccess','w');
	$content_htaccess='RemoveHandler .php .phtml .php3'."\n".'RemoveType .php .phtml .php3'."\n".'<Files *>'."\n".'SetHandler default-handler'."\n".'</Files>';
	fwrite($fp,$content_htaccess);
	fclose($fp);
	
	$dir_pics = $dir_dest;
	
	$files = array();
    foreach ($_FILES['file'] as $k => $l) {
        foreach ($l as $i => $v) {
            if (!array_key_exists($i, $files))
                $files[$i] = array();
            $files[$i][$k] = $v;
        }
    }
	
	$status = 'success';
	
    foreach ($files as $file) {
        $handle = new Upload($file,LANG);
        if ($handle->uploaded) {
            $handle->Process($dir_dest);
            if ($handle->processed) {
				
				/** upload thành công, lưu database thông số file */
				$file_is_image = 'false';
				$file_info=array();
				
				$file_info['file_src_name'] = $handle->file_src_name;
				$file_info['file_src_name_body'] = $handle->file_src_name_body;
				$file_info['file_src_name_ext'] = $handle->file_src_name_ext;
				$file_info['file_src_mime'] = $handle->file_src_mime;
				$file_info['file_src_size'] = $handle->file_src_size;
				$file_info['file_dst_name'] = $handle->file_dst_name;
				$file_info['file_dst_name_body'] = $handle->file_dst_name_body;
				$file_info['file_dst_name_ext'] = $handle->file_dst_name_ext;
				
				$file_info['file_is_image'] = $handle->file_is_image;
				
				$file_name=$file_info['file_src_name'];
				
				if($file_info['file_is_image']==TRUE){
					
					$file_is_image = 'true';
					$file_info['image_src_x'] = $handle->image_src_x;
					$file_info['image_src_y'] = $handle->image_src_y;
					$file_info['image_src_bits'] = $handle->image_src_bits;
					$file_info['image_src_pixels'] = $handle->image_src_pixels;
					$file_info['image_src_type'] = $handle->image_src_type;
					$file_info['image_dst_x'] = $handle->image_dst_x;
					$file_info['image_dst_y'] = $handle->image_dst_y;
					$file_info['image_dst_type'] = $handle->image_dst_type;
					
					$handle->image_resize          = true;
					$handle->image_ratio_crop      = true;
					$handle->image_y               = 512;
					$handle->image_x               = 512;
					$handle->Process($dir_dest);
					$file_info['thumbnail'] = $handle->file_dst_name;
					
				}
				
				$file_info=hm_json_encode($file_info);
				$tableName=DB_PREFIX.'media';
				
				$values["media_group_id"] = MySQL::SQLValue($media_group, MySQL::SQLVALUE_NUMBER);
				$values["file_info"] = MySQL::SQLValue($file_info);
				$values["file_name"] = MySQL::SQLValue($file_name);
				$values["file_folder"] = MySQL::SQLValue($folder);
				$values["file_is_image"] = MySQL::SQLValue($file_is_image);
				$insert_id=$hmdb->InsertRow($tableName, $values);
				
				unset($values);
				
				$status = 'success';
				$content[] = $insert_id;
				
            } else {
                $status = 'error';
				$content[] = $file_name.' : '.$handle->error;
            }
        } else {
			$status = 'error';
			$content[] = $file_name.' : '.$handle->error;
        }
    }
	
	if(is_array($content)){
		$content=implode(", ",$content);
	}
	return hm_json_encode(array('status'=>$status,'content'=>$content,'media_group'=>$media_group));
	
}
/** Ajax hiển thị danh sách file */
function show_media_file(){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	/** show theo trang */
	$page = hm_get('page',1);
	$per_page = hm_get('per_page',20);
	$start = $page-1;
	if($start<0){$start = 0;}
	$start = $start*$per_page;
	$end = $page*$per_page;
	$limit = $start.','.$end;
	
	if($page == 1){
	/** danh sách folder */
		
		$media_group_id = media_group_id();
		$tableName=DB_PREFIX."media_groups";
		$whereArray=array('parent'=>MySQL::SQLValue( $media_group_id ));
		$hmdb->SelectRows($tableName,$whereArray,NULL,'id',FALSE);
		$rowCount_folder = $hmdb->RowCount();
		if($rowCount_folder > 0){
			while( $row=$hmdb->Row() ){
				
				$folder_name = $row->name;
				$folder_slug = $row->folder;
				$folder_id = $row->id;
				$thumbnail_src = BASE_URL.HM_CONTENT_DIR.'/images/folder-icon.png';
				
				echo '<li class="file_thumbnail col-md-2">';
				echo '	<div class="folder_item" folder_id="'.$folder_id.'" folder_name="'.$folder_name.'" folder_slug="'.$folder_slug.'">';
				echo '		<span class="folder_item_name">'.$folder_name.'</span>';
				echo '		<img data-toggle="tooltip" data-placement="bottom" title="'.$folder_name.'" src="'.$thumbnail_src.'" class="img-responsive" />';
				echo '	</div>';
				echo '</li>';
			}
		}
	
	}
	
	/** danh sách file */
	
	$whereArray = NULL;
	$media_group_id = media_group_id();
	
	if( is_numeric($media_group_id) ){
		$whereArray['media_group_id']=MySQL::SQLValue($media_group_id, MySQL::SQLVALUE_NUMBER);
	}
	
	if( hm_get('imageonly') == 'true' ){
		$whereArray['file_is_image']=MySQL::SQLValue('true');
	}
 
	$tableName=DB_PREFIX."media";
	$hmdb->SelectRows($tableName,$whereArray,NULL,'id',FALSE,$limit);
	$rowCount_file = $hmdb->RowCount();
	
	if($media_group_id!=0){
		$file_folder_part = '/'.get_media_group_part($media_group_id).'/';
	}else{
		$file_folder_part = '/';
	}
	
	if($rowCount_file > 0){
		while( $row=$hmdb->Row() ){
			$id = $row->id;
			$file_info = $row->file_info;
			$file_name = $row->file_name;
			$file_folder = $row->file_folder;
			$media_group_id = $row->media_group_id;
			$file_info = json_decode($file_info,TRUE);
			$file_src = BASE_URL.HM_CONTENT_DIR.'/uploads'.$file_folder_part.$file_info['file_dst_name'];
			
			echo '<li class="file_thumbnail col-md-2">';
				if($file_info['file_is_image']==TRUE){$file_is_image=1;}else{$file_is_image=0;}
				if( isset($file_info['thumbnail']) ){
					$thumbnail_src = BASE_URL.HM_CONTENT_DIR.'/uploads'.$file_folder_part.$file_info['thumbnail'];
					echo '<div class="file_item" file_id="'.$id.'" file_is_image="'.$file_is_image.'" file_dst_name="'.$file_info['file_dst_name'].'" file_src_name_ext="'.$file_info['file_src_name_ext'].'" file_src_mime="'.$file_info['file_src_mime'].'" file_src_size="'.$file_info['file_src_size'].'" file_src="'.$file_src.'" >';
					echo '<input type="checkbox" class="hide file_deep_checkbox" value="'.$id.'">';
					echo '<img data-toggle="tooltip" data-placement="bottom" title="'.$file_info['file_src_name'].'" src="'.$thumbnail_src.'" class="img-responsive" />';
					echo '</div>';
				}else{
					$file_src_name_ext = strtolower ($file_info['file_src_name_ext']);
					$file_ext_icon = './'.HM_CONTENT_DIR.'/icon/fileext/'.$file_src_name_ext.'.png';
					if( file_exists($file_ext_icon) ){
						$thumbnail_src = BASE_URL.HM_CONTENT_DIR.'/icon/fileext/'.$file_src_name_ext.'.png';
					}else{
						$thumbnail_src = BASE_URL.HM_CONTENT_DIR.'/icon/fileext/blank.png';
					}
					echo '<div class="file_item" file_id="'.$id.'" file_is_image="'.$file_is_image.'" file_dst_name="'.$file_info['file_dst_name'].'" file_src_name_ext="'.$file_info['file_src_name_ext'].'" file_src_mime="'.$file_info['file_src_mime'].'" file_src_size="'.$file_info['file_src_size'].'" file_src="'.$file_src.'" >';
					echo '<input type="checkbox" class="hide file_deep_checkbox" value="'.$id.'">';
					echo '<img data-toggle="tooltip" data-placement="bottom" title="'.$file_info['file_src_name'].'" src="'.$thumbnail_src.'" class="img-responsive" />';
					echo '</div>';
				}
			echo '</li>';
			
		}
	}
	
	if($rowCount_file==0 AND $rowCount_folder==0){
		echo '<div class="alert alert-success" role="alert">'._('Bạn chưa tải lên tệp tin nào').'</div>';
	}
	
}
/** Ajax xóa file */
function delete_media($id){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	if( is_numeric($id) ){
		
		$tableName=DB_PREFIX."media";
		$whereArray=array('id'=>MySQL::SQLValue($id));
		$hmdb->SelectRows($tableName,$whereArray);
		$rowCount = $hmdb->RowCount();
		if($rowCount!=0){
			$row=$hmdb->Row();
			$file_info = $row->file_info;
			$file_name = $row->file_name;
			$file_folder = $row->file_folder;
			if($file_folder!='/'){
				$file_folder_part = '/'.get_media_group_part($file_folder).'/';
			}else{
				$file_folder_part = '/';
			}
			/** remove file */
			$file_info = json_decode($file_info,TRUE);
			$file_local = BASEPATH.HM_CONTENT_DIR.'/uploads'.$file_folder_part.$file_info['file_dst_name'];
			unlink($file_local);
			if( isset($file_info['thumbnail']) ){
				$thumbnail_local = BASEPATH.HM_CONTENT_DIR.'/uploads'.$file_folder_part.$file_info['thumbnail'];
				unlink($thumbnail_local);
			}
			if(is_array($file_info['crop'])){
				foreach($file_info['crop'] as $crop_file_data){
					$crop_file_name = $crop_file_data['name'];
					$crop_file_local = BASEPATH.HM_CONTENT_DIR.'/uploads'.$file_folder_part.$crop_file_name;
					if(file_exists($crop_file_local)){
						unlink($crop_file_local);
					}
				}
			}
			/** remove database */
			$hmdb->DeleteRows($tableName, $whereArray);
		}
	
	}
}

/** Ajax xóa thư mục */
function del_media_group($args){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$id = $args['group_id'];
	if( is_numeric($id) ){
		/** Xóa thư mục */
		$path = BASEPATH.'/'.HM_CONTENT_DIR.'/uploads/'.get_media_group_part($id);
		DeleteDir($path);
		
		$tableName=DB_PREFIX."media_groups";
		$whereArray=array('id'=>MySQL::SQLValue($id));
		$hmdb->DeleteRows($tableName, $whereArray);
		
		/** Xóa các file trong thư mục */
		$tableName=DB_PREFIX."media";
		$whereArray=array('media_group_id'=>MySQL::SQLValue($id));
		$hmdb->SelectRows($tableName, $whereArray);
		if( $hmdb->HasRecords() ){
			while( $row=$hmdb->Row() ){
				$id_media_file = $row->id;
				delete_media($id_media_file);
			}
		}
		
		/** Xóa thư mục con */
		$tableName=DB_PREFIX."media_groups";
		$whereArray=array('parent'=>MySQL::SQLValue($id));
		$hmdb->SelectRows($tableName, $whereArray);
		if( $hmdb->HasRecords() ){
			while( $row=$hmdb->Row() ){
				$id_sub_folder = $row->id;
				del_media_group(array('group_id'=>$id_sub_folder));
				
			}
		}
	}
	
}
/** Ajax xóa nhiều file */
function multi_delete_media($ids){
	if(is_array($ids)){
		foreach($ids as $id){
			delete_media($id);
		}
	}
}

/** Ajax move file */
function move_media($args){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	$id = $args['id'];
	$group_id = $args['group_id'];
	
	$tableName=DB_PREFIX.'media_groups';
	$whereArray = array(
	   'id' => MySQL::SQLValue($group_id),
	);
	$hmdb->SelectRows($tableName, $whereArray);
	$count=$hmdb->RowCount();
	$file_folder='/';
	if($count!='0'){
		$row=$hmdb->Row();
		$file_folder=$row->folder;
	}
	
	$ex = explode(',',$id);
	foreach($ex as $file_id){
		
		$tableName=DB_PREFIX.'media';
		$whereArray = array(
		   'id' => MySQL::SQLValue($file_id),
		);
		$hmdb->SelectRows($tableName, $whereArray);
		$count=$hmdb->RowCount();
		if($count!='0'){
			
			$row=$hmdb->Row();
			$old_group_id=$row->media_group_id;
			$file_name = $row->file_name;
			$file_info = $row->file_info;
			$file_info = json_decode($file_info,TRUE);
			$thumbnail = $file_info['thumbnail'];
			
			
			$old_part = BASEPATH.HM_CONTENT_DIR.'/uploads/'.get_media_group_part($old_group_id);
			$new_part = BASEPATH.HM_CONTENT_DIR.'/uploads/'.get_media_group_part($group_id);
			
			rename($old_part.'/'.$file_name,$new_part.'/'.$file_name);
			rename($old_part.'/'.$thumbnail,$new_part.'/'.$thumbnail);
			if(is_array($file_info['crop'])){
				foreach($file_info['crop'] as $crop_file_data){
					$crop_file_name = $crop_file_data['name'];
					rename($old_part.'/'.$crop_file_name,$new_part.'/'.$crop_file_name);
				}
			}
			
			if(file_exists($new_part.'/'.$file_name)){
				
				$valuesArray = array(
					'media_group_id' => MySQL::SQLValue($group_id),
					'file_folder' => MySQL::SQLValue($file_folder),
				    'file_info' => MySQL::SQLValue(json_encode($file_info)),
				);
				$whereArray = array(
				   'id' => MySQL::SQLValue($file_id),
				);
				$hmdb->UpdateRows($tableName, $valuesArray, $whereArray);
				
			}
		
		}
		
	}
}

/** Đổi tên nhóm media */
function rename_media_group($args=array()){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	$group_name = $args['group_name'];
	$group_id = $args['group_id'];
	$new_folder = sanitize_title($group_name);
	
	$tableName=DB_PREFIX.'media_groups';
	$whereArray = array(
	   'id' => MySQL::SQLValue($group_id),
	);
	$hmdb->SelectRows($tableName, $whereArray);
	$count=$hmdb->RowCount();
	if($count!='0'){
		
		$row=$hmdb->Row();
		$old_folder=$row->folder;
		$parent=$row->parent;
		$old_part = BASEPATH.HM_CONTENT_DIR.'/uploads/'.get_media_group_part($group_id);
		$parent_part = BASEPATH.HM_CONTENT_DIR.'/uploads/'.get_media_group_part($parent);
		
		if($old_folder!=$new_folder){
			$new_part = $parent_part.'/'.$new_folder;
			/** rename thư mục */
			$rename = rename($old_part,$new_part);
			if($rename==TRUE){
				
				/** update file */
				$tableName_media=DB_PREFIX.'media';
				$whereArray_media = array(
				   'media_group_id' => MySQL::SQLValue($group_id),
				);
				$valuesArray_media = array(
				   'file_folder' => MySQL::SQLValue($new_folder),
				);
				$hmdb->UpdateRows($tableName_media, $valuesArray_media, $whereArray_media);
				
				
				/** update media_groups */
				$valuesArray = array(
				   'name' => MySQL::SQLValue($group_name),
				   'folder' => MySQL::SQLValue($new_folder),
				);
			}else{
				
				/** update media_groups */
				$valuesArray = array(
				   'name' => MySQL::SQLValue($group_name),
				);
			}
		}
		
		
		return $hmdb->UpdateRows($tableName, $valuesArray, $whereArray);
		
	}else{
		return FALSE;
	}

}


/** Trả về link thumbnail của file */
function thumbnail_media($id){
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	if( isset($id) AND is_numeric($id) ){
		
		$tableName=DB_PREFIX."media";
		$whereArray=array('id'=>MySQL::SQLValue($id));
		$hmdb->SelectRows($tableName,$whereArray);
		$rowCount = $hmdb->RowCount();
		if($rowCount!=0){
			$row=$hmdb->Row();
			$file_info = $row->file_info;
			$file_name = $row->file_name;
			$file_id = $row->id;
			$file_folder_url = get_file_url($file_id,FALSE);
			$file_info = json_decode($file_info,TRUE);
			if($file_info['file_is_image']==TRUE){
				$thumbnail_src = $file_folder_url.$file_info['thumbnail'];
			}else{
				$file_src_name_ext = strtolower ($file_info['file_src_name_ext']);
				$file_ext_icon = './'.HM_CONTENT_DIR.'/icon/fileext/'.$file_src_name_ext.'.png';
				if( file_exists($file_ext_icon) ){
					$thumbnail_src = BASE_URL.HM_CONTENT_DIR.'/icon/fileext/'.$file_src_name_ext.'.png';
				}else{
					$thumbnail_src = BASE_URL.HM_CONTENT_DIR.'/icon/fileext/blank.png';
				}
			}
			return $thumbnail_src;
		}
	
	}
}
?>