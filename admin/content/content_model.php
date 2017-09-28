<?php
/** 
 * Tệp tin model của content trong admin
 * Vị trí : admin/content/content_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');

function content_show_all($key){
	
	global $hmcontent;
	
	$con=$hmcontent->hmcontent;
	
	if(isset($con[$key])){
		$args=$con[$key];
		return $args;
	}else{
		hm_exit( _('Không có kiểu nội dung này') );
	}
	
}
function content_data($key){
	
	hook_action('content_data');
	$key = hook_filter('before_content_data',$key);
	global $hmcontent;
	
	$con=$hmcontent->hmcontent;

	if(isset($con[$key])){
		$args=$con[$key];
		$args = hook_filter('content_data',$args);
		return $args;
	}else{
		return FALSE;
	}
}
function content_ajax_add($args=array()){
	
	global $hmcontent;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	$args = hook_filter('before_content_ajax_add',$args);
	$input_post = hook_filter('content_ajax_add_input_post',$_POST);
	hook_action('content_ajax_add');
	
	if( isset($args['content_key']) ){$key = $args['content_key'];}else{$key = NULL;}
	if( isset($args['id_update']) ){$id_update = $args['id_update'];}else{$id_update = NULL;}
	if( isset($args['parent']) ){$parent = $args['parent'];}else{$parent = 0;}
	
	$con=$hmcontent->hmcontent;
	if(isset($input_post['taxonomy'])){
		$method_post_taxonomy = $input_post['taxonomy'];
	}else{
		$method_post_taxonomy = array();
	}
	
	if( ($key!=NULL) AND isset($con[$key]) ){
		
		$this_con = content_data($key);

		foreach ( $input_post as $post_key => $post_val ){
			if(is_array($post_val)){ $post_val = hm_json_encode($post_val); }
			$primary = $this_con['content_field']['primary_name_field']['name'];
			if(isset($this_con['content_field']['primary_name_field']['create_slug'])){
				$create_slug = $this_con['content_field']['primary_name_field']['create_slug'];
			}else{
				$create_slug = FALSE;
			}
			
			
			/** input này được dùng làm khóa tên chính */
			if($post_key==$primary){
				
				$con_name = $post_val;
				
				if($create_slug==TRUE){
					if(isset($input_post['slug_of_'.$post_key])){
						
						if(is_numeric($id_update)){
							$con_slug = $input_post['slug_of_'.$post_key];
							$con_slug = str_replace('#','-',$con_slug);
						}else{
							$con_slug = add_request_uri_custom($input_post['slug_of_'.$post_key]);
						}
						
					}else{
						
						
						switch ($input_post['accented_of_'.$post_key]) {
							
							case 'true':
								if(is_numeric($id_update)){
									$con_slug = sanitize_title_with_accented($post_val);
								}else{
									$con_slug = add_request_uri_with_accented($post_val);
								}
							break;
							case 'false':
								if(is_numeric($id_update)){
									$con_slug = sanitize_title($post_val);
								}else{
									$con_slug = add_request_uri($post_val);
								}
							break;
							default:
								if(is_numeric($id_update)){
									$con_slug = sanitize_title($post_val);
								}else{
									$con_slug = add_request_uri($post_val);
								}
						}
						
					}
				}else{
					$con_slug = sanitize_title($post_val);
				}
				
				/** lưu content vào database */
				$tableName=DB_PREFIX.'content';
				$status = hm_post('status','public');
				$values["name"] = MySQL::SQLValue($con_name);
				$values["slug"] = MySQL::SQLValue($con_slug);
				$values["key"] = MySQL::SQLValue($key);
				$values["status"] = MySQL::SQLValue($status);
				$values["parent"] = MySQL::SQLValue($parent);
				$values["content_order"] = MySQL::SQLValue(hm_post('number_order'));
				
				if(is_numeric($id_update)){
					
					$args_con=content_data_by_id($id_update);
					
					$parent=$args_con['content']->parent;
					$values["parent"] = MySQL::SQLValue($parent);
						
					$whereArray = array (
											'id'=>$id_update,
										);
					$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
					$insert_id=$id_update;
					$args = array(
								'uri' => $con_slug,
								'object_id' => $insert_id,
								'object_type' => 'content',
								'type' => 'has_object',
							);
					up_date_request_uri_object($args);
					
				}else{
					$insert_id=$hmdb->InsertRow($tableName, $values);
					$args = array(
								'uri' => $con_slug,
								'object_id' => $insert_id,
								'object_type' => 'content',
								'type' => 'null_object',
							);
					up_date_request_uri_object($args);
				}
				
				unset($values);
			}
		}
		
		if(!isset($input_post['number_order']) OR !is_numeric($input_post['number_order'])){
			$input_post['number_order'] = 0;
		}
		foreach ( $input_post as $post_key => $post_val ){
			
			/** lưu content field vào data */
			if(is_numeric($insert_id)){
				if(is_array($post_val)){ $post_val = hm_json_encode($post_val); }
					$tableName=DB_PREFIX.'field';
					
					$values["name"] = MySQL::SQLValue($post_key);
					$values["val"] = MySQL::SQLValue($post_val);
					$values["object_id"] = MySQL::SQLValue($insert_id, MySQL::SQLVALUE_NUMBER);
					$values["object_type"] = MySQL::SQLValue('content');
					
					if(is_numeric($id_update)){
						
						$whereArray = array (
											'object_id'=>MySQL::SQLValue($id_update, MySQL::SQLVALUE_NUMBER),
											'object_type'=>MySQL::SQLValue('content'),
											'name'=>MySQL::SQLValue($post_key),
											);
						$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
						
						
					}else{
						$hmdb->InsertRow($tableName, $values);
					}
					
					unset($values);
				
			}
		}
		/** Lưu content number_order */
		if(is_numeric($id_update)){
			
		}else{
			if(is_numeric($insert_id)){
				$number_order = hm_post('number_order');
				if($number_order == 1){
					
					$tableName=DB_PREFIX.'content';
					$hmdb->Query("SELECT * FROM `".$tableName."` WHERE `id` !=".$insert_id." AND `key` = '".$key."'");
					
					$content_update_ids = array();
					while( $row=$hmdb->Row() ){
						$content_update_ids[] = $row->id;
					}
					foreach($content_update_ids as $content_update_id){
						$old_number_order = get_con_val(array('name'=>'number_order','id'=>$content_update_id));
						$new_number_order = $old_number_order+1;
						update_con_val(array('name'=>'number_order','id'=>$content_update_id,'value'=>$new_number_order));
					
						$values = array();
						$values["content_order"] = MySQL::SQLValue($new_number_order);
						$whereArray=array('id'=>MySQL::SQLValue($content_update_id));
						$hmdb->UpdateRows($tableName, $values, $whereArray);
					}
					
				}elseif($number_order > 1){
					
					$tableName=DB_PREFIX.'content';
					$hmdb->Query("SELECT * FROM `".$tableName."` WHERE `id` !=".$insert_id." AND `key` = '".$key."'");
					
					$content_update_ids = array();
					while( $row=$hmdb->Row() ){
						$content_update_ids[] = $row->id;
					}
					foreach($content_update_ids as $content_update_id){
						$old_number_order = get_con_val(array('name'=>'number_order','id'=>$content_update_id));
						if($old_number_order >= $number_order){
							$new_number_order = $old_number_order+1;
							update_con_val(array('name'=>'number_order','id'=>$content_update_id,'value'=>$new_number_order));
						
							$values = array();
							$values["content_order"] = MySQL::SQLValue($new_number_order);
							$whereArray=array('id'=>MySQL::SQLValue($content_update_id));
							$hmdb->UpdateRows($tableName, $values, $whereArray);
						}
					}
					
				}
			}
		}
		
		/** Lưu content time */
		if(is_numeric($insert_id)){
			
			$tableName=DB_PREFIX.'field';
			
			$day = $input_post['day']; if(!is_numeric($day)) $day = 0;
			$month = $input_post['month']; if(!is_numeric($month)) $month = 0;
			$year = $input_post['year']; if(!is_numeric($year)) $year = 0;
			$hour = $input_post['hour']; if(!is_numeric($hour)) $hour = 0;
			$minute = $input_post['minute']; if(!is_numeric($minute)) $minute = 0;
			
			$public_time = strtotime($day.'-'.$month.'-'.$year.' '.$hour.':'.$minute);
			
			$values = array();
			$values["name"] = MySQL::SQLValue('public_time');
			$values["val"] = MySQL::SQLValue($public_time);
			$values["object_id"] = MySQL::SQLValue($insert_id, MySQL::SQLVALUE_NUMBER);
			$values["object_type"] = MySQL::SQLValue('content');
			
			if(is_numeric($id_update)){
				
				$whereArray = array (
									'object_id'=>MySQL::SQLValue($id_update, MySQL::SQLVALUE_NUMBER),
									'object_type'=>MySQL::SQLValue('content'),
									'name'=>MySQL::SQLValue('public_time'),
									);
				$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);	
				
			}else{
				$hmdb->InsertRow($tableName, $values);
			}
			
			unset($values);
		
		}
		
		/** lưu relationship content - taxonomy */
		$tableName=DB_PREFIX.'relationship';
		
		if( isset($method_post_taxonomy) AND is_array($method_post_taxonomy) ){
				
			foreach ( $method_post_taxonomy as $taxonomy_id ){
				
				$values["object_id"] = MySQL::SQLValue($insert_id, MySQL::SQLVALUE_NUMBER);
				$values["target_id"] = MySQL::SQLValue($taxonomy_id, MySQL::SQLVALUE_NUMBER);
				$values["relationship"] = MySQL::SQLValue('contax');
				
				if(is_numeric($id_update)){
					
					$hmdb->AutoInsertUpdate($tableName, $values, $values);	
					
				}else{
					$hmdb->InsertRow($tableName, $values);
				}
				
				unset($values);
			}
			
		}
		
		/** Gỡ bỏ contax đã bỏ chọn */
		if(is_numeric($id_update)){
			
			$id_deletes = array();
			
			$whereArray = array (
									'object_id'=>MySQL::SQLValue($insert_id),
									'relationship'=>MySQL::SQLValue('contax'),
								);
			$hmdb->SelectRows($tableName, $whereArray);
			
			if( $hmdb->HasRecords() ){
				
				while( $row=$hmdb->Row() ){
				
					$id_relationship = $row->id;
					$target_id = $row->target_id;
					
					if(!in_array ($target_id,$method_post_taxonomy) ){
						
						$id_deletes[] = $id_relationship;
						
					}
					
				}
				
			}else{
				$id_deletes = array();
			}
			
			foreach($id_deletes as $id_delete){
				
				$whereArray = array (
									'id'=>MySQL::SQLValue($id_delete, MySQL::SQLVALUE_NUMBER),
								);
				$hmdb->DeleteRows($tableName, $whereArray);
				
			}
		}
		
		/** Gỡ bỏ contag cũ */
		if(is_numeric($id_update)){
			
				$whereArray = array (
									'object_id'=>MySQL::SQLValue($id_update, MySQL::SQLVALUE_NUMBER),
									'relationship'=>MySQL::SQLValue('contag'),
								);
				$hmdb->DeleteRows($tableName, $whereArray);
			
		}
		
		/** lưu tags vào data */
		if( isset($input_post['tags']) ){
			
			$tags=explode(',',$input_post['tags']);
			
			$tags=array_map("trim", $tags);
			
			foreach($tags as $tag){
				
				/** Lưu tag vào bảng taxonomy */
				if($tag!=''){
					
					$tableName = DB_PREFIX.'taxonomy';
					$tag_slug = sanitize_title($tag);
					
					$values["name"] = MySQL::SQLValue($tag);
					$values["slug"] = MySQL::SQLValue($tag_slug);
					$values["key"] = MySQL::SQLValue('tag');
					
					$whereArray=array('key'=>MySQL::SQLValue('tag'),'slug'=>MySQL::SQLValue($tag_slug));
					
					$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
					
					unset($values);
					
					/** lưu relationship content - tag */
					$tableName=DB_PREFIX."taxonomy";
					$whereArray=array('key'=>MySQL::SQLValue('tag'),'slug'=>MySQL::SQLValue($tag_slug));
		
					$hmdb->SelectRows($tableName, $whereArray);
					if( $hmdb->HasRecords() ){
						
						$row = $hmdb->Row();
						$tableName=DB_PREFIX.'relationship';
					
						$values["object_id"] = MySQL::SQLValue($insert_id, MySQL::SQLVALUE_NUMBER);
						$values["target_id"] = MySQL::SQLValue($row->id, MySQL::SQLVALUE_NUMBER);
						$values["relationship"] = MySQL::SQLValue('contag');
						
						if(is_numeric($id_update)){
						
							$hmdb->AutoInsertUpdate($tableName, $values, $values);	
							
						}else{
							$hmdb->InsertRow($tableName, $values);
						}
						
						unset($values);
				
					} 	

				}						
				
			}
			
		}
		
		/** show latest */
		$latest=array(	
						'id'=>$insert_id,
						'name'=>$con_name,
						'slug'=>$con_slug,
						'key'=>$key,
					);
		return hm_json_encode(array('latest'=>$latest));
	}
}

function content_ajax_add_chapter($id){
	
	global $hmcontent;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('content_ajax_add_chapter');
	
	if(isset_content_id($id) == TRUE){
		
		$args_con=content_data_by_id($id);
		$key=$args_con['content']->key;
		$args = array(
						'content_key'=>$key,
						'parent'=>$id,
						'status'=>'chapter',
					 );
        return content_ajax_add($args);
		
	}
	
}

function content_ajax_edit($id){
	
	global $hmcontent;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('content_ajax_edit');
	
	$tableName=DB_PREFIX."content";
	$whereArray=array('id'=>MySQL::SQLValue($id));
	
	$hmdb->SelectRows($tableName, $whereArray);
	if( $hmdb->HasRecords() ){
		
		$row=$hmdb->Row();
		$content_key = $row->key;
		
		/** lưu bản revision content vào database */
		content_create_revision($id);
	
		/** Hoàn thành lưu bản chỉnh sửa - Lưu bản cập nhật mới*/
		$args = array(
						'content_key'=>$content_key,
						'id_update'=>$id,
					 );
		return content_ajax_add($args);
	
	}
}

function content_create_revision($id){
	
	global $hmcontent;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('content_create_revision');
	$id = hook_filter('content_create_revision',$id);
	
	$tableName=DB_PREFIX."content";
	$whereArray=array('id'=>MySQL::SQLValue($id));

	$hmdb->SelectRows($tableName, $whereArray);
	if( $hmdb->HasRecords() ){
		
		$row=$hmdb->Row();
		$content_key = $row->key;
		
		$values["name"] = MySQL::SQLValue($row->name);
		$values["slug"] = MySQL::SQLValue($row->slug);
		$values["key"] = MySQL::SQLValue($row->key);
		$values["status"] = MySQL::SQLValue('revision');
		$values["parent"] = MySQL::SQLValue($row->id);

		$insert_revision_id=$hmdb->InsertRow($tableName, $values);
		
		unset($values);
		
		/** lưu field của bản revision content vào database */
		
		$tableName=DB_PREFIX."field";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
							'object_type'=>MySQL::SQLValue('content')
						 );

		$hmdb->SelectRows($tableName, $whereArray);
		
		if( $hmdb->HasRecords() ){
			$count = $hmdb->RowCount();
			$i=1;
			while( $row = $hmdb->Row() ){
				
				$row_name = $row->name;
				$row_val = $row->val;
				if($row_name == 'status'){$row_val = 'revision';}
				
				$values[$i]["name"] = MySQL::SQLValue($row_name);
				$values[$i]["val"] = MySQL::SQLValue($row_val);
				$values[$i]["object_id"] = MySQL::SQLValue($insert_revision_id);
				$values[$i]["object_type"] = MySQL::SQLValue('content');

				$i++;
			}
			foreach($values as $value){
				$hmdb->InsertRow($tableName, $value);
			}
		}
		
		/** lưu relationship của bản revision content vào database */
		
		$tableName=DB_PREFIX."relationship";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
						 );

		$hmdb->SelectRows($tableName, $whereArray);

		if( $hmdb->HasRecords() ){
			$count = $hmdb->RowCount();
			$i=1;
			while( $row = $hmdb->Row() ){
				
				$values[$i]["relationship"] = MySQL::SQLValue($row->relationship);
				$values[$i]["target_id"] = MySQL::SQLValue($row->target_id);
				$values[$i]["object_id"] = MySQL::SQLValue($insert_revision_id);

				$i++;
			}
			foreach($values as $value){
				$hmdb->InsertRow($tableName, $value);
			}
		}
		
		return $insert_revision_id;
	}else{
		return FALSE;
	}
	
}

function content_show_data($key,$args){

	global $hmcontent;
	$status = $args['status'];
	$perpage = $args['perpage'];
	$search_keyword = $args['search_keyword'];
	$search_target = $args['search_target'];
	$search_order_by = $args['search_order_by'];
	$search_order = $args['search_order'];
	$taxonomy = $args['taxonomy'];
	 
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('content_show_data');
	
	$paged = hm_get('paged',1);
	
	$args = array(
				'content_key'=>$key,
				'status'=>$status,
				'order'=>'number_order,asc,number',
				'limit'=>$perpage,
				'paged'=>$paged,
			);
	$search_keyword = trim($search_keyword);	
	if($search_keyword!=''){
		$args['field_query'][] = 	array( 
									'field'=>$search_target,
									'compare'=>'like%',
									'value'=>$search_keyword,
								);
		$args['field_query_relation'] = 'and';
		
	}
	if($search_order_by!=''){
		switch ($search_order) {
			case 'ASC':
				$args['order'] = $search_order_by.',asc';
			break;
			case 'DESC':
				$args['order'] = $search_order_by.',desc';
			break;
			case 'NUMASC':
				$args['order'] = $search_order_by.',asc,number';
			break;
			case 'NUMDESC':
				$args['order'] = $search_order_by.',desc,number';
			break;
		}
	}
	
	if($taxonomy!=''){
		$find_in_tax = array();
		$ex = explode(',',$taxonomy);
		foreach($ex as $tax_id){
			if(is_numeric($tax_id)){
				$find_in_tax[] = $tax_id;
			}
		}
		$args['taxonomy'] = $find_in_tax;
	}

	$contents = query_content($args);
	

	if( sizeof($contents) > 0 ){
		
		/* Trả về các content */
		foreach ($contents as $cid) {
			
			$tableName=DB_PREFIX."content";
			$whereArray=array('id'=>MySQL::SQLValue($cid));
			$hmdb->SelectRows($tableName, $whereArray);
			$row = $hmdb->Row();
			$slug = $row->slug;
			$status = $row->status;
			$name = get_con_val( array('id'=>$cid,'name'=>'name') );
			$number_order = get_con_val( array('id'=>$cid,'name'=>'number_order') );
			$content_thumbnail = get_con_val( array('id'=>$cid,'name'=>'content_thumbnail') );
			$thumbnail = create_image("file=$content_thumbnail&w=48&h=48");
			if($thumbnail == FALSE){
				$thumbnail = ADMIN_LAYOUT_PATH.'/images/noimage.png';
			}
			if(!is_numeric($number_order)){$number_order=0;}
			$array_con[]=array('id'=>$cid,'name'=>$name,'slug'=>$slug,'status'=>$status,'number_order'=>$number_order,'thumbnail'=>$thumbnail);
		
		}
		
		ksort($array_con); 
		$array['content']=$array_con;
		
		/* Tạo pagination */
		global $hmcontent;
		$total_item = $hmcontent->get_val('total_result');
		
		$total_page = ceil($total_item / $perpage);
		$first = '1';
		if($paged > 1){
			$previous = $paged - 1;
		}else{
			$previous = $first;
		}
		if($paged < $total_page){
			$next = $paged + 1;
		}else{
			$next = $total_page;
		}

		
		$array['pagination']=array(
								'first'=>$first,
								'previous'=>$previous,
								'next'=>$next,
								'last'=>$total_page,
								'total'=>$total_item,
								'paged'=>$paged,
		);
		
		$all_content = $hmcontent->hmcontent;
		if( isset($all_content[$key]['chapter']) AND $all_content[$key]['chapter'] == TRUE ){
			$array['chapter']=TRUE;
		}else{
			$array['chapter']=FALSE;
		}
		
	}else{
		$array['content']=array();
		$array['pagination']=array();
		$array['chapter']=FALSE;
	}
	
	return hook_filter('content_show_data',hm_json_encode($array,TRUE));
	
}

function content_ajax_slug(){

	if(isset($_POST['val'])){
		if( isset($_POST['accented']) AND $_POST['accented']=='true' ){
			return create_request_uri_with_accented(hm_post('val'),'',hm_post('object'));
		}elseif( isset($_POST['accented']) AND $_POST['accented']=='false' ){
			return create_request_uri(hm_post('val'),'',hm_post('object'));
		}
	}
	
}

function content_delete_permanently($id){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('content_delete_permanently');
	
	if(is_numeric($id)){
		
		/* xóa bảng content */
		$tableName=DB_PREFIX."content";
		$whereArray=array(
							'id'=>MySQL::SQLValue($id),
						 );
		$hmdb->DeleteRows($tableName, $whereArray);
		
		/** Tìm các sub content */
		$whereArray=array(
							'parent'=>MySQL::SQLValue($id),
						 );
		$hmdb->SelectRows($tableName, $whereArray);
		
		$ids = array();
		while( $row = $hmdb->Row() ){
			$ids[] = $row->id;
		}
		
		foreach($ids as $delete_id){
			/** xóa các sub content */
			$tableName=DB_PREFIX."content";
			$whereArray=array(
							'id'=>MySQL::SQLValue($delete_id),
						 );
			$hmdb->DeleteRows($tableName, $whereArray);
			/** xóa các sub content field */
			$tableName=DB_PREFIX."field";
			$whereArray=array(
							'object_id'=>MySQL::SQLValue($delete_id),
							'object_type'=>MySQL::SQLValue('content'),
						 );
			$hmdb->DeleteRows($tableName, $whereArray);
			/* xóa các relationship */
			$tableName=DB_PREFIX."relationship";
			$whereArray=array(
								'object_id'=>MySQL::SQLValue($delete_id),
							 );
			$hmdb->DeleteRows($tableName, $whereArray);
			/** xóa các sub request */
			$tableName=DB_PREFIX."request_uri";
			$whereArray=array(
							'object_id'=>MySQL::SQLValue($delete_id),
						 );
			$hmdb->DeleteRows($tableName, $whereArray);
		}
		
		/* xóa bảng field */
		$tableName=DB_PREFIX."field";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
							'object_type'=>MySQL::SQLValue('content'),
						 );
		$hmdb->DeleteRows($tableName, $whereArray);
		
		/* xóa bảng relationship */
		$tableName=DB_PREFIX."relationship";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
						 );
		$hmdb->DeleteRows($tableName, $whereArray);
		
		/* xóa bảng request uri */
		$tableName=DB_PREFIX."request_uri";
		$whereArray=array(
							'object_id'=>MySQL::SQLValue($id),
							'object_type'=>MySQL::SQLValue('content'),
						 );
		$hmdb->DeleteRows($tableName, $whereArray);
		
	}
	
}


function content_box($args=array()){

	global $content_box;
	
	
	$position = $args['position'];
	$content_key = $args['content_key'];
	
		
	foreach ($content_box as $box){
		
		if(!isset($box['content_key'])){ $box['content_key'] = NULL; }
		
		if($box['content_key'] == $content_key OR ( !isset($box['content_key']) )){
			
			$label = $box['label'];
			$func = $box['function'];
			
			if($box['position'] == $position ){
				
				$class='';
				if($position=='left'){$class='admin_mainbar_box';$data_order=mainbar_box_order($func);}
				if($position=='right'){$class='admin_sidebar_box';$data_order=sidebar_box_order($func);}
				
				echo '<div class="row '.$class.'" data-func="'.$func.'" id="'.$func.'" data-order="'.$data_order.'">';
					echo '<p class="admin_sidebar_box_title ui-sortable-handle" data-func="'.$func.'">'.$label.'</p>';
					echo '<div class="admin_mainbar_boxcontent" data-func="'.$func.'">';
					if(function_exists($func)) {
						call_user_func($func, $args);
					}
					echo '</div>';
				echo '</div>';
				
			}
			
		}
	}

}

function content_update_order($id,$order){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	update_con_val( array('id'=>$id,'name'=>'number_order','value'=>$order) );
	
	$tableName=DB_PREFIX.'content';
	$values = array();
	$values["content_order"] = MySQL::SQLValue($order);
	$whereArray=array('id'=>MySQL::SQLValue($id));
	$hmdb->UpdateRows($tableName, $values, $whereArray);
	
	
}

function update_sidebar_box_order($func,$order){
	
	$sidebar_box_order = array();
	if(isset($_SESSION['sidebar_box_order'])){
		$sidebar_box_order = $_SESSION['sidebar_box_order'];
	}
	$sidebar_box_order[$func]=$order;
	setcookie('sidebar_box_order', hm_encode_str($sidebar_box_order) ,time() + COOKIE_EXPIRES, '/');
	$_SESSION['sidebar_box_order'] = $sidebar_box_order;

}

function update_mainbar_box_order($func,$order){
	
	$mainbar_box_order = array();
	if(isset($_SESSION['mainbar_box_order'])){
		$mainbar_box_order = $_SESSION['mainbar_box_order'];
	}
	$mainbar_box_order[$func]=$order;
	setcookie('mainbar_box_order', hm_encode_str($mainbar_box_order) ,time() + COOKIE_EXPIRES, '/');
	$_SESSION['mainbar_box_order'] = $mainbar_box_order;
	
}

function update_form_input_order($func,$order){
	
	$form_input_order = array();
	if(isset($_SESSION['form_input_order'])){
		$form_input_order = $_SESSION['form_input_order'];
	}
	$form_input_order[$func]=$order;
	setcookie('form_input_order', hm_encode_str($form_input_order) ,time() + COOKIE_EXPIRES, '/');
	$_SESSION['form_input_order'] = $form_input_order;
	
}

function sidebar_box_order($func){
	
	$sidebar_box_order = array();
	if(isset($_SESSION['sidebar_box_order'])){
		$sidebar_box_order = $_SESSION['sidebar_box_order'];
	}
	if(isset($sidebar_box_order[$func])){
		return $sidebar_box_order[$func];
	}else{
		return 0;
	}
	
}
function mainbar_box_order($func){
	
	$sidebar_box_order = array();
	if(isset($_SESSION['sidebar_box_order'])){
		$sidebar_box_order = $_SESSION['sidebar_box_order'];
	}
	if(isset($mainbar_box_order[$func])){
		return $mainbar_box_order[$func];
	}else{
		return 0;
	}
	
}

function content_ajax_multi($key){
	$action = hm_post('action');
	switch ($action) {
		case 'draft':
			$content_ids = hm_post('content_ids');
			foreach($content_ids as $content_id){
				
				content_update_val( array( 'id'=>$content_id,'value'=>array('status'=>'draft') ) );

			}
		break;
		
	}
}

?>