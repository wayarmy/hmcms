<?php
/** 
 * Xử lý content
 * Vị trí : hm_include/content.php 
 */
if ( ! defined('BASEPATH')) exit('403');
/**
 * Gọi thư viện content
 */
require_once(BASEPATH . HM_INC . '/content/hm_content.php');
/**
 * Khởi tạo class
 */
$hmcontent = new content;
/**
 * Biến global để thêm layout vào giao diện
 */
$content_box =  array();
/**
 * Định nghĩa các hàm để thực hiện phương thức trong class kèm theo hook
 */
function isset_content( $args=array() ){
	
	$args = hook_filter('before_isset_content',$args);
	
	global $hmcontent;
	
	hook_action('isset_content');
	
	$return = $hmcontent->isset_content($args);
	
	$return = hook_filter('isset_content',$return);
	
	return $return;
} 
function isset_content_id( $id ){
	
	$id = hook_filter('before_isset_content_id',$id);
	
	global $hmcontent;
	
	hook_action('isset_content_id');
	
	$return = $hmcontent->isset_content_id($id);
	
	$return = hook_filter('isset_content_id',$return);
	
	return $return;
} 
 
function register_content( $args=array() ){
	
	$args = hook_filter('before_register_content',$args);
	
	global $hmcontent;
	
	hook_action('register_content');
	
	$return = $hmcontent->register_content($args);
	
	$return = hook_filter('register_content',$return);
	
	return $return;
}
function update_content( $args=array() ){
	
	$args = hook_filter('before_update_content',$args);
	
	global $hmcontent;
	
	hook_action('update_content');
	
	$return = $hmcontent->update_content($args);
	
	$return = hook_filter('update_content',$return);
	
	return $return;
}
function destroy_content( $args=array() ){
	
	$args = hook_filter('before_destroy_content',$args);
	
	global $hmcontent;
	
	hook_action('destroy_content');
	
	$return = $hmcontent->destroy_content($args);
	
	$return = hook_filter('destroy_content',$return);
	
	return $return;
}
function total_content( $args=array() ){
	
	$args = hook_filter('before_total_content',$args);
	
	global $hmcontent;
	
	hook_action('total_content');
	
	$return = $hmcontent->total_content($args);
	
	$return = hook_filter('total_content',$return);
	
	return $return;
}
function get_con_val( $args=array() ){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	$args = hook_filter('before_get_con_val',$args);
	
	global $hmcontent;
	
	hook_action('get_con_val');
	
	$return = $hmcontent->get_con_val($args);
	
	$return = do_all_shortcode_from_string($return);
	
	$return = hook_filter('after_get_con_val',$return,$args);
	
	return $return;
}
function get_primary_con_val( $id ){

	$args = hook_filter('before_get_primary_con_val',$id);
	
	global $hmcontent;
	
	hook_action('get_primary_con_val');
	
	$return = $hmcontent->get_primary_con_val($id);
	
	$return = do_all_shortcode_from_string($return);
	
	$return = hook_filter('after_get_primary_con_val',$return,$id);
	
	return $return;
}
function update_con_val( $args=array() ){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	$args = hook_filter('before_update_con_val',$args);
	
	global $hmcontent;
	
	hook_action('update_con_val');
	
	$return = $hmcontent->update_con_val($args);
	
	$return = hook_filter('after_update_con_val',$return,$args);
	
	return $return;
}
function get_con_tax( $args=array() ){
	
	$args = hook_filter('before_get_con_tax',$args);
	
	global $hmcontent;
	
	hook_action('get_con_tax');
	
	$return = $hmcontent->get_con_tax($args);
	
	$return = hook_filter('get_con_tax',$return);
	
	return $return;
	
}
function get_con_tag( $args=array() ){
	
	$args = hook_filter('before_get_con_tag',$args);
	
	global $hmcontent;
	
	hook_action('get_con_tag');
	
	$return = $hmcontent->get_con_tag($args);
	
	$return = hook_filter('get_con_tag',$return);
	
	return $return; 
	
}
function the_tags( $args=array() ){
	
	$args = hook_filter('before_the_tags',$args);
	
	global $hmcontent;
	
	hook_action('the_tags');
	
	$sep=$args['sep'];
	$tag_array = $hmcontent->get_con_tag($args);
	
	$array = array(
		'tags'=>$tag_array,
		'sep'=>$sep,
	);
	
	$return = $hmcontent->get_con_tag_string($array);
	
	$return = hook_filter('the_tags',$return);
	
	return $return; 
	
}

function content_data_by_id( $id = NULL ){
	
	if(is_numeric($id)){
		
		$id = hook_filter('before_content_data_by_id',$id);
		
		global $hmcontent;
	
		hook_action('content_data_by_id');
		
		$return = $hmcontent->content_data_by_id($id);
		
		$return = hook_filter('content_data_by_id',$return);
		
		return $return; 
		
	}
	
}
function replace_content_field($content_key = '',$field_array=array()){
	
	global $hmcontent;
	$all_hmcontent = $hmcontent->hmcontent;
	if(isset($all_hmcontent[$content_key])){
		$this_content = $all_hmcontent[$content_key];
		$this_content["content_field"] = $field_array;
		update_content($this_content);
	}
	
}
function content_number_revision($id){
	
	$id = hook_filter('before_content_number_revision',$id);
	
	global $hmcontent;
	hook_action('content_number_revision');
	
	$return = $hmcontent->content_number_revision($id);
	$return = hook_filter('content_number_revision',$return);
	
	return $return;
	
}
function content_number_chapter($id){
	
	$id = hook_filter('before_content_number_chapter',$id);
	
	global $hmcontent;
	hook_action('content_number_chapter');
	
	$return = $hmcontent->content_number_chapter($id);
	$return = hook_filter('content_number_chapter',$return);
	
	return $return;
	
}
function build_field_content_blockquote( $args=array() ){
	$args = hook_filter('before_build_field_content_blockquote',$args);
	
	global $hmcontent;
	hook_action('build_field_content_blockquote');
	
	$return = $hmcontent->build_field_content_blockquote($args);
	
	return $return;
	
}
function content_update_val( $args=array() ){
	
	$args = hook_filter('before_content_update_val',$args);
	global $hmcontent;
	hook_action('content_update_val');
	
	$return = $hmcontent->content_update_val($args);
	
	return $return;
}
function register_content_box( $args=array() ){
	
	$args = hook_filter('before_register_content_box',$args);
	hook_action('register_content_box');
	
	global $content_box;
	
	if(is_array($args)){
		$content_box[] = $args;
	}
}
function query_content( $args=array() ){
	
	global $hmcontent;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	$args = hook_filter('before_query_content',$args);
	hook_action('query_content');

	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	
	/** Lọc theo status */
	$where_status = " WHERE `status` = 'public' ";
	
	if(isset($args['status'])){
		
		$status = $args['status'];
		if(is_array($status)){
			$where_in_status = "('".implode("','",$status)."')";
			$where_status = " WHERE `status` IN ".$where_in_status." ";
		}else{
			$where_status = " WHERE `status` = '".$status."' ";
		}
	}
	
	
	/** Lọc theo content_key */
	$where_content_key = '';
	
	if( isset( $args['content_key'] ) ){
		
		/** Nếu yêu cầu content key thì lấy các id có key như query yêu cầu */
		$content_key = $args['content_key'];
		
		/** Nếu content key là một mảng */
		if(is_array($content_key)){
			
			$where_in_content_key = "('".implode("','",$content_key)."')";
			$where_content_key = " AND `key` IN ".$where_in_content_key." ";
			
		}else{
			
			$where_content_key = " AND `key` = '".$content_key."' ";
			
		}
		
	}else{
		
		/** Không yêu cầu content key, kiểm tra xem có đang ở template taxonomy không */
		if( is_taxonomy() == TRUE ){
			
			$taxonomy_id = get_id();
			$content_key = taxonomy_get_content_key($taxonomy_id);

			if( $content_key!=FALSE ){
				
				$where_content_key = " AND `key` = '".$content_key."' ";
				
			}
			
		}
		
	}
	
	$hmdb->Release();
	$query_content_key = "SELECT `id` FROM `".DB_PREFIX."content` ".$where_status.$where_content_key;
	$hmdb->Query($query_content_key);
	$query_content_key_ids = array('0');
	while( $row=$hmdb->Row() ){
		$query_content_key_ids[] = $row->id;
	}
	$query_content_key_ids_str = implode(',',$query_content_key_ids);
	/** Lọc theo taxonomy */
	$where_taxonomy = '';
	$query_in_taxonomy = '';
	if( isset( $args['taxonomy'] ) ){
		/** Nếu yêu cầu trong một taxonomy nhất định thì lấy các object_id có relationship như query yêu cầu */
		$taxonomy_id = $args['taxonomy'];
		
		/** Nếu taxonomy là một mảng */
		if(is_array($taxonomy_id)){
			
			$implode = implode($taxonomy_id,',');
			if($implode != ''){
				$where_taxonomy = ' WHERE `target_id` IN ('.$implode.') ';
			}
		
		}else{
			
			if(is_numeric($taxonomy_id)){
				$where_taxonomy = 'WHERE `target_id` = '.$taxonomy_id;
			}
			
		}
	}else{
		/** Không yêu cầu taxonomy nhất định, kiểm tra xem có đang ở template taxonomy không */
		if( is_taxonomy() == TRUE ){
			
			$taxonomy_id = get_id();
			$where_taxonomy = 'WHERE `target_id` = '.$taxonomy_id;
			
		}
		
	}
	
	if($where_taxonomy != ''){
		$hmdb->Release();
		$query_in_taxonomy = "SELECT `object_id` FROM `".DB_PREFIX."relationship` ".$where_taxonomy." AND `relationship` = 'contax'";
		$hmdb->Query($query_in_taxonomy);
		$query_in_taxonomy_ids = array('0');
		while( $row=$hmdb->Row() ){
			$query_in_taxonomy_ids[] = $row->object_id;
		}
		$query_in_taxonomy_ids_str = implode(',',$query_in_taxonomy_ids);
	}

	/** Lọc theo field */
	if( isset( $args['field_query'] ) ){
		$field_query = $args['field_query'];
	}else{
		$field_query = array(
							array( 
								'field'=>'public_time',
								'compare'=>'<=',
								'value'=>time(),
							),
					);
	}
	
	$all_field_query = array();
	
	foreach ( $field_query as $item ){
		
		/** check đủ điều kiện tạo field query */
		if( isset($item['field']) AND isset($item['compare']) AND isset($item['value']) ){
			
			$field = $item['field'];
			$compare = $item['compare'];
			$value = $item['value'];
			$numerically =  FALSE;
			
			/** build query */
			if( is_numeric($value) ){
				$value_query = $value;
			}else{
				$value_query = "'$value'";
			}
			
			if($compare == 'like%'){
				$all_field_query[] = " ( `name` = '$field' AND `val` LIKE '%$value%' )";
			}else{
				$all_field_query[] = " ( `name` = '$field' AND `val` $compare $value_query )";
			}
			
		}
		
	}
	/** nếu size của mảng chứa các kết quả của các field query >= 2 */ 
	
	$size = sizeof($all_field_query);
	$query_field = "SELECT `object_id` FROM `".DB_PREFIX."field` WHERE";
	
	if($size > 1){
		
		if( isset( $args['field_query_relation'] ) ){
			$field_query_relation = $args['field_query_relation'];
		}else{
			$field_query_relation = 'and';
		}
		
		switch ($field_query_relation) {
			case 'or':
				$i = 0;
				foreach($all_field_query as $single_field_query){
					if($i == 0){
						$query_field.= " ".$single_field_query." ";
					}else{
						$query_field.= " OR ".$single_field_query." ";
					}
					$i++;
				}
			break;
			case 'and':
				$i = 0;
				foreach($all_field_query as $single_field_query){
					if($i == 0){
						$query_field.= " ".$single_field_query." ";
					}else{
						$query_field.= " AND ".$single_field_query." ";
					}
					$i++;
				}
				$query_field.= " GROUP BY  `object_id`"; 
			break;
		}
		
		/** 
		 * Đưa ra kết quả dựa trên mối quan hệ giữa các field query ( field_query_relation )
		 * ( thỏa mãn tất cả các field query hay chỉ cần đáp ứng được 1 trong những field query )
		 */
		
	}else{
		@$query_field = $query_field.array_shift(array_values($all_field_query));
	}
	
	$hmdb->Query($query_field);
	$query_field_ids = array('0');
	while( $row=$hmdb->Row() ){
		$query_field_ids[] = $row->object_id;
	}
	$query_field_ids_str = implode(',',$query_field_ids);

	
	/** Kiểm tra yêu cầu kết hợp kết quả từ content key, in taxonomy, field query là tất cả hay chỉ 1 */
	if( isset($args['join']) ){
		$join = $args['join'];
	}else{
		$join = 'and';
	}
	$query_join = '';
	switch ($join) {
		case 'or':
			if($query_content_key){
				$query_join.= " AND `object_id` IN (".$query_content_key_ids_str.") ";
			}
			if($query_in_taxonomy){
				$query_join.= " OR `object_id` IN (".$query_in_taxonomy_ids_str.") ";
			}
			$query_join.= " OR `object_id` IN (".$query_field_ids_str.") ";
		break;
		case 'and':
			if($query_content_key){
				$query_join.= " AND `object_id` IN (".$query_content_key_ids_str.") ";
			}
			if($query_in_taxonomy){
				$query_join.= " AND `object_id` IN (".$query_in_taxonomy_ids_str.") ";
			}
			$query_join.= " AND `object_id` IN (".$query_field_ids_str.") ";
		break;
		default :
			if($query_content_key){
				$query_join.= " AND `object_id` IN (".$query_content_key_ids_str.") ";
			}
			if($query_in_taxonomy){
				$query_join.= " AND `object_id` IN (".$query_in_taxonomy_ids_str.") ";
			}
			$query_join.= " AND `object_id` IN (".$query_field_ids_str.") ";
	}
	/** Kết thúc các query lấy các content id thỏa mãn yêu cầu */	
	/** Order theo 1 field  và limit */
	
	if( isset( $args['order'] ) ){
		$order_by = $args['order'];
	}else{
		$order_by = 'number_order,asc,number';
	}
	
	if( isset( $args['limit'] ) ){
		$limit = $args['limit'];
	}else{
		$limit = get_option( array('section'=>'system_setting','key'=>'post_per_page','default_value'=>'12') );
	}
	
	if( isset( $args['offset'] ) AND is_numeric( $args['offset'] ) ){
		$offset = $args['offset'];
	}else{
		$offset = 0;
	}
	
	if( isset( $args['paged'] ) ){
		$paged = $args['paged'];
	}else{
		$paged = get_current_pagination();
	}
	$paged = $paged - 1;
	if($paged < 0){ $paged = 0;}
	
	/** Tạo query ORDER */
	$ex = explode(',',$order_by);
	$ex = array_map("trim", $ex);
	
	$order_field = $ex[0];
	$order = strtoupper($ex[1]);
	if( isset($ex[2]) ){
		$numerically = $ex[2];
	}else{
		$numerically = FALSE;
	}
	if($numerically == 'number'){
		$order_query = " AND `name` = '".$order_field."' ORDER BY CAST(val AS unsigned) ".$order." ";
	}else{
		$order_query = " AND `name` = '".$order_field."' ORDER BY `val` ".$order." ";
	}
	/** Tạo query LIMIT */
	if( is_numeric($limit) ){
		$limit_query = " LIMIT $limit ";
	}else{
		$limit_query = '';
	}
	
	/** Tạo query OFFSET */
	if($limit == FALSE){
		$offset_query = '';
	}else{
		$offset_query_page = $paged * $limit;
		$offset_query_page = $offset_query_page+$offset;
		$offset_query = " OFFSET $offset_query_page ";
	}
	
	/** Tạo câu lệnh select từ chuỗi các id thỏa mãn */
	$result = array();
	
	$sql = "SELECT `object_id`".
	" FROM `".DB_PREFIX."field`".
	" WHERE `object_type` = 'content'".
	" ".$query_join." ".
	" ".$order_query." ";
	$hmdb->Query($sql);
	$total_result = $hmdb->RowCount();
	
	$sql = "SELECT `object_id`".
	" FROM `".DB_PREFIX."field`".
	" WHERE `object_type` = 'content'".
	" ".$query_join." ".
	" ".$order_query." ".$limit_query." ".$offset_query." ";


	$hmdb->Query($sql);
	
	$base = get_current_uri();
	if($base == ''){
		$base = '/';
	}
	
	$hmcontent->set_val(array('key'=>'total_result','val'=>$total_result));
	$hmcontent->set_val(array('key'=>'paged','val'=>($paged+1)));
	$hmcontent->set_val(array('key'=>'perpage','val'=>$limit));
	$hmcontent->set_val(array('key'=>'base','val'=>$base));
	
	/** Nếu có query_id riêng cho query này , set để gọi đúng pagination của query */
	if( isset( $args['query_id'] ) ){
		$query_id = $args['query_id'];
		$hmcontent->set_val(array('key'=>$query_id.'_total_result','val'=>$total_result));
		$hmcontent->set_val(array('key'=>$query_id.'_paged','val'=>($paged+1)));
		$hmcontent->set_val(array('key'=>$query_id.'_perpage','val'=>$limit));
		$hmcontent->set_val(array('key'=>$query_id.'_base','val'=>$base));
	}
		
	while($row = $hmdb->Row()){
		$result[] = $row->object_id;
	}
	
	return $result;
}
function pagination($args=array()){
	
	global $hmcontent;
	
	$default_array = array(
			'query_id'			=> FALSE,
			'class'				=> 'pagination',
			'id'				=> '',
			'first'				=> _('Trang đầu'),
			'last'				=> _('Trang cuối'),
			'previous'			=> _('&laquo;'),
			'next'				=> _('&raquo;'),
			'wrapper' 			=> _('ul'),
			'active_class'		=> _('active'),
			'permalink_before'	=> _('<li>'),
			'permalink_after'	=> _('</li>'),
		);
	$args = hm_parse_args($args,$default_array);
	
	if($args['query_id']){
		$query_id = $args['query_id'];
		$total_result = $hmcontent->get_val($query_id.'_total_result');
	}else{
		$total_result = $hmcontent->get_val('total_result');
	}
	
	if( $total_result > 0 ){
		
		if($args['query_id']){
			$query_id = $args['query_id'];
			$page_now = $hmcontent->get_val($query_id.'_paged');
		}else{
			$page_now = $hmcontent->get_val('paged');
		}
		
		if(!is_numeric($page_now)){
			$paged = 1;
		}
		if($page_now < 1 ){$page_now=1;}
		
		if($args['query_id']){
			$query_id = $args['query_id'];
			$perpage = $hmcontent->get_val($query_id.'_perpage');
		}else{
			$perpage = $hmcontent->get_val('perpage');
		}
		
		if($args['query_id']){
			$query_id = $args['query_id'];
			$base = $hmcontent->get_val($query_id.'_base');
		}else{
			$base = $hmcontent->get_val('base');
		}
		
	
		if($base!='/'){
			$base = BASE_URL.$base.'/';
		}else{
			$base = BASE_URL;
		}
		$query_str = $_SERVER['QUERY_STRING'];
		
		if($args['query_id']){
			$query_id = $args['query_id'];
			$total = $hmcontent->get_val($query_id.'_total_result');
		}else{
			$total = $hmcontent->get_val('total_result');
		}
		
		
		$total_page = $total / $perpage;
		$total_page = ceil($total_page);
		
		
		if($query_str != ''){
			$first_link = $base.'?'.$query_str;	
		}else{
			$first_link = $base;
		}
		
		if($query_str != ''){
			$last_link =  $base.PAGE_BASE.$total_page.'?'.$query_str;	
		}else{
			$last_link =  $base.PAGE_BASE.$total_page;
		}
		
		
		$pre_page =  $page_now - 1;
		if($pre_page > 0){
			if($query_str != ''){
				$pre_link =  $base.PAGE_BASE.$pre_page.'?'.$query_str;
			}else{
				$pre_link =  $base.PAGE_BASE.$pre_page;
			}
		}else{
			$pre_link = FALSE;
		}
		
		$next_page =  $page_now + 1;
		if($next_page <= $total_page){
			if($query_str != ''){
				$next_link =  $base.PAGE_BASE.$next_page.'?'.$query_str;
			}else{
				$next_link =  $base.PAGE_BASE.$next_page;
			}
		}else{
			$next_link = FALSE;
		}
		
		$pagination_class = '';
		$pagination_id = '';
		if($args['class']){ $pagination_class = ' class="'.$args['class'].'"'; }
		if($args['id']){ $pagination_id = ' id="'.$args['class'].'"'; }
		echo '<'.$args['wrapper'].''.$pagination_class.$pagination_id.'>';
		
		echo $args['permalink_before'].'<a href="'.$first_link.'">'.$args['first'].'</a>'.$args['permalink_after'];
		
		if($pre_link!=FALSE){
			echo $args['permalink_before'].'<a href="'.$pre_link.'"> '.$args['previous'].' </a>'.$args['permalink_after'];
		}
		for($i=$page_now-3;$i<$page_now;$i++){
			if($i > 0){
				if($query_str != ''){
					$this_link = $base.PAGE_BASE.$i.'?'.$query_str;
				}else{
					$this_link = $base.PAGE_BASE.$i;
				}
				echo $args['permalink_before'].'<a href="'.$this_link.'"> '.$i.' </a>'.$args['permalink_after'];
			}
		}
		
		echo $args['permalink_before'].'<a href="#" class="'.$args['active_class'].'"> '.$page_now.' </a>'.$args['permalink_after'];
		
		for($i=$page_now+1;$i<=($page_now+3);$i++){
			if($i <= $total_page){
				if($query_str != ''){
					$this_link = $base.PAGE_BASE.$i.'?'.$query_str;
				}else{
					$this_link = $base.PAGE_BASE.$i;
				}
				echo $args['permalink_before'].'<a href="'.$this_link.'"> '.$i.' </a>'.$args['permalink_after'];
			}
		}
		
		if($next_link!=FALSE){
			echo $args['permalink_before'].'<a href="'.$next_link.'"> '.$args['next'].' </a>'.$args['permalink_after'];
		}
		
		echo $args['permalink_before'].'<a href="'.$last_link.'">'.$args['last'].'</a>'.$args['permalink_after'];
		
		echo '</'.$args['wrapper'].'>';
		
	}
}
function content_breadcrumb($id,$args=array()){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$bre = FALSE;
	$sub_bre = FALSE;
	$tax_bre = FALSE;
	$breadcrumb='';
	
	$whereArray = array (
		'id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER),
	);
	$tableName=DB_PREFIX."content";
	$hmdb->SelectRows($tableName, $whereArray);
	if( $hmdb->HasRecords() ){
		
		$row = $hmdb->Row();
		$bre[0]['name'] = $row->name;
		$bre[0]['id'] = $id;
		$bre[0]['type'] = 'content';
		$bre[0]['key'] = $row->key;
		$bre[0]['slug'] = $row->slug;
		$taxonomy = get_con_val("name=taxonomy&id=$id");
		$taxonomy = json_decode($taxonomy,TRUE);
		
		if(is_array($taxonomy)){
			$whereIn = implode(',',$taxonomy);
			$hmdb->Query("SELECT * FROM ".DB_PREFIX."taxonomy WHERE `id` IN ($whereIn) ORDER BY `parent` DESC ");
			$row = $hmdb->Row();
			$id_tax = $row->id;
			$tax_bre = taxonomy_parent_breadcrumb($id_tax,1);
		}
	}
	
	if(is_array($tax_bre)){
		$bre = array_merge($bre,$tax_bre);
	}
	
	if(is_array($bre)){
		
		$breadcrumb.='<'.$args['wrapper'].' class="'.$args['wrapper_class'].'" id="'.$args['wrapper_id'].'" itemscope itemtype="http://data-vocabpary.org/Breadcrumb">';
		
		if($args['show_home'] == TRUE){
			$breadcrumb.='<'.$args['item'].' class="'.$args['item_class'].'" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
			$breadcrumb.=$args['permalink_before'];
			$breadcrumb.='<a href="'.$args['home_url'].'" class="'.$args['permalink_class'].'" title="'.$args['home_name'].'" itemprop="url"><span itemprop="title">'.$args['home_name'].'</span></a>';
			$breadcrumb.=$args['permalink_after'];
			$breadcrumb.='</'.$args['item'].'>';
		}
		
		krsort($bre);
		foreach($bre as $item){
			$item_name = $item['name'];
			$item_id = $item['id'];
			$item_type = $item['type'];
			$item_slug = $item['slug'];
			switch ($item_type) {
				case 'content':
					$breadcrumb.='<'.$args['item'].' class="'.$args['item_class'].'" itemscope itemtype="http://data-vocabpary.org/Breadcrumb">';
					$breadcrumb.=$args['permalink_before'];
					$breadcrumb.='<a rel="bookmark" href="'.BASE_URL.$item_slug.'" class="'.$args['permalink_class'].'" title="'.$item_name.'" itemprop="url"><span itemprop="title">'.$item_name.'</span></a>';
					$breadcrumb.=$args['permalink_after'];
					$breadcrumb.='</'.$args['item'].'>';
				break;
				case 'taxonomy':
					$breadcrumb.='<'.$args['item'].' class="'.$args['item_class'].'" itemscope itemtype="http://data-vocabpary.org/Breadcrumb">';
					$breadcrumb.=$args['permalink_before'];
					$breadcrumb.='<a rel="category tag" href="'.BASE_URL.$item_slug.'" class="'.$args['permalink_class'].'" title="'.$item_name.'" itemprop="url"><span itemprop="title">'.$item_name.'</span></a>';
					$breadcrumb.=$args['permalink_after'];
					$breadcrumb.='</'.$args['item'].'>';
				break;
			}
			
		}
		
		$breadcrumb.='</'.$args['wrapper'].'>';
		
		return $breadcrumb;
	}
	
}

function content_thumbnail( $args=array() ){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	
	$default_array = array(
			'id'		=> FALSE,
			'w'		=> FALSE,
			'h'		=> FALSE,
			'crop'		=> FALSE,
		);
	$args = hm_parse_args($args,$default_array);
	
	$args = hook_filter('before_content_thumbnail',$args);
	hook_action('content_thumbnail');

	$return = FALSE;
	$id = $args['id'];
	$w = $args['w'];
	$h = $args['h'];
	$crop = $args['crop'];
	
	$content_thumbnail = get_con_val(array('name'=>'content_thumbnail','id'=>$id));
	
	if(is_numeric($content_thumbnail)){
		
		$file_data = get_file_data($content_thumbnail);
		$file_info = json_decode($file_data->file_info,TRUE);
		$primary_con_val = get_primary_con_val($id);
		$source_src_dir = get_file_url($content_thumbnail,FALSE);
		$source_src = $source_src_dir.$file_data->file_name;
		
		$srcset = array();
		$srcset[] = $source_src.' '.$file_info['image_dst_x'].'w';
		
		if(is_array($file_info['crop'])){
			foreach($file_info['crop'] as $crop_file_data){
				
				$crop_file_name = $crop_file_data['name'];
				$crop_w = $crop_file_data['w'];
				
				if( is_numeric($crop_w) ){
					$srcset[] = $source_src_dir.$crop_file_name.' '.$crop_w.'w';
				}
				
			}
		}
		
		if( is_numeric($w) AND is_numeric($h) ){
			$display_src = create_image(array('file'=>$content_thumbnail,'w'=>$w,'h'=>$h,'crop'=>$crop));
		}else{
			$w = $file_info['image_dst_x'];
			$h = $file_info['image_dst_y'];
			$display_src = $source_src;
		}
	}
	
	$return = '<img width="'.$w.'" height="'.$h.'" src="'.$display_src.'" class="hm-content-thumbnail" alt="'.$primary_con_val.'" title="'.$primary_con_val.'" srcset="'.implode(', ',$srcset).'">';
	
	$return = hook_filter('content_thumbnail',$return);
	return $return;
}

?>