<?php
/** 
 * Xử lý taxonomy
 * Vị trí : hm_include/taxonomy.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/**
 * Gọi thư viện taxonomy
 */
require_once(BASEPATH . HM_INC . '/taxonomy/hm_taxonomy.php');

/**
 * Khởi tạo class
 */
$hmtaxonomy = new taxonomy;

/**
 * Định nghĩa các hàm để thực hiện phương thức trong class kèm theo hook
 */
function isset_taxonomy( $args=array() ){

	$args = hook_filter('before_isset_taxonomy',$args);
	
	global $hmtaxonomy;
	
	hook_action('isset_taxonomy');
	
	$return = $hmtaxonomy->isset_taxonomy($args);
	
	$return = hook_filter('isset_taxonomy',$return);
	
	return $return;
} 

function isset_taxonomy_id( $id ){

	$id = hook_filter('before_isset_taxonomy_id',$id);
	
	global $hmtaxonomy;
	
	hook_action('isset_taxonomy_id');
	
	$return = $hmtaxonomy->isset_taxonomy_id($id);
	
	$return = hook_filter('isset_taxonomy_id',$return);
	
	return $return;
} 

function register_taxonomy( $args=array() ){

	$args = hook_filter('before_register_taxonomy',$args);
	
	global $hmtaxonomy;
	
	hook_action('register_taxonomy');
	
	$return = $hmtaxonomy->register_taxonomy($args);
	
	$return = hook_filter('register_taxonomy',$return);
	
	return $return;

}

function destroy_taxonomy( $args=array() ){

	$args = hook_filter('before_destroy_taxonomy',$args);
	
	global $hmtaxonomy;
	
	hook_action('destroy_taxonomy');
	
	$return = $hmtaxonomy->destroy_taxonomy($args);

	$return = hook_filter('destroy_taxonomy',$return);
	
	return $return;
}

function total_taxonomy( $args=array() ){

	$args = hook_filter('before_total_taxonomy',$args);
	
	global $hmtaxonomy;
	
	hook_action('total_taxonomy');
	
	$return = $hmtaxonomy->total_taxonomy($args);
	
	$return = hook_filter('total_taxonomy',$return);
	
	return $return; 
}

function get_tax_val( $args=array() ){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}

	$args = hook_filter('before_get_tax_val',$args);
	
	global $hmtaxonomy;
	
	hook_action('get_tax_val');
	
	$return = $hmtaxonomy->get_tax_val($args);
	
	$return = do_all_shortcode_from_string($return);
	
	$return = hook_filter('after_get_tax_val',$return,$args);
	
	return $return;  
}
function get_primary_tax_val( $id ){

	$args = hook_filter('before_get_primary_tax_val',$id);
	
	global $hmtaxonomy;
	
	hook_action('get_primary_tax_val');
	
	$return = $hmtaxonomy->get_primary_tax_val($id);
	
	$return = do_all_shortcode_from_string($return);
	
	$return = hook_filter('after_get_primary_tax_val',$return,$id);
	
	return $return;
}
function update_tax_val( $args=array() ){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	$args = hook_filter('before_update_tax_val',$args);
	
	global $hmtaxonomy;
	
	hook_action('update_tax_val');
	
	$return = $hmtaxonomy->update_tax_val($args);
	
	$return = hook_filter('after_update_tax_val',$return,$args);
	
	return $return;
}
function taxonomy_update_val( $args=array() ){

	$args = hook_filter('before_taxonomy_update_val',$args);
	
	global $hmtaxonomy;

	hook_action('taxonomy_update_val');
	
	$return = $hmtaxonomy->taxonomy_update_val($args);
	
	return $return;

}

function all_taxonomy_by_key( $key=FALSE ){

	$id = hook_filter('before_all_taxonomy_by_key',$id);
	
	global $hmtaxonomy;

	hook_action('all_taxonomy_by_key');
	
	$return = $hmtaxonomy->all_taxonomy_by_key($key);
	
	$return = hook_filter('after_all_taxonomy_by_key',$return,$key);
	
	return $return;

}

function taxonomy_data_by_id( $id=0 ){

	$id = hook_filter('before_taxonomy_data_by_id',$id);
	
	global $hmtaxonomy;

	hook_action('taxonomy_data_by_id');
	
	$return = $hmtaxonomy->taxonomy_data_by_id($id);
	
	$return = hook_filter('after_taxonomy_data_by_id',$return,$id);
	
	return $return;

}

function register_taxonomy_box( $args=array() ){

	$args = hook_filter('before_register_taxonomy_box',$args);
	
	hook_action('register_taxonomy_box');
	
	global $taxonomy_box;
	
	if(is_array($args)){
		$taxonomy_box[] = $args;
	}

}

function taxonomy_have_content( $tax_id ){
	
	$tax_id = hook_filter('taxonomy_have_content',$tax_id);
	
	global $hmtaxonomy;

	hook_action('taxonomy_have_content');
	
	$return = $hmtaxonomy->taxonomy_have_content($tax_id);
	
	return $return;
	
}

function taxonomy_get_content_key( $tax_id ){
	
	$tax_id = hook_filter('taxonomy_get_content_key',$tax_id);
	
	global $hmtaxonomy;

	hook_action('taxonomy_get_content_key');
	
	$return = $hmtaxonomy->taxonomy_get_content_key($tax_id);
	
	return $return;
	
}	

function get_tax( $args = array() ){
	
	$args = hook_filter('get_tax',$args);
	
	global $hmtaxonomy;

	hook_action('get_tax');
	
	$return = $hmtaxonomy->get_tax($args);
	
	return $return;
	
}	

function taxonomy_parent_breadcrumb($id,$i=1){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$bre = FALSE;
	$sub_bre = FALSE;
	
	$whereArray = array (
		'id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER),
	);
	$tableName=DB_PREFIX."taxonomy";
	$hmdb->SelectRows($tableName, $whereArray);
	if( $hmdb->HasRecords() ){
		
		$row = $hmdb->Row();
		$bre[$i]['name'] = $row->name;
		$bre[$i]['id'] = $id;
		$bre[$i]['type'] = 'taxonomy';
		$bre[$i]['key'] = $row->key;
		$bre[$i]['slug'] = $row->slug;
		$parent = $row->parent;
		
		if($parent != '0'){
			$inew = $i + 1;
			$sub_bre = taxonomy_parent_breadcrumb($parent,$inew);
		}
	}
	
	if(is_array($sub_bre)){
		$bre = array_merge($bre,$sub_bre);
	}
	
	return $bre;
}

function taxonomy_breadcrumb($id,$args=array()){
	
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
	$tableName=DB_PREFIX."taxonomy";
	$hmdb->SelectRows($tableName, $whereArray);
	if( $hmdb->HasRecords() ){
		
		$row = $hmdb->Row();
		$bre[0]['name'] = $row->name;
		$bre[0]['id'] = $id;
		$bre[0]['type'] = 'taxonomy';
		$bre[0]['key'] = $row->key;
		$bre[0]['slug'] = $row->slug;
		$parent = get_tax_val("name=parent&id=$id");
		
		if($parent != 0){
			$tax_bre = taxonomy_parent_breadcrumb($parent,1);
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

function get_sub_tax($parent=0,$key=FALSE){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$taxs = array();
	
	$whereArray = array (
		'parent'=>MySQL::SQLValue($parent, MySQL::SQLVALUE_NUMBER),
		'status'=>MySQL::SQLValue('public'),
	);
	if($key!=FALSE){
		$whereArray['key'] = MySQL::SQLValue($key);
	}
	$tableName=DB_PREFIX."taxonomy";
	$hmdb->SelectRows($tableName, $whereArray);
	if( $hmdb->HasRecords() ){
		
		while($row = $hmdb->Row()){
			$taxs[] = $row;
		}
		
	}
	$taxs = hook_filter('get_sub_tax',$taxs);
	return $taxs;
}

function get_biggest_parent_tax($tax_id=0){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$parent = get_tax_val(array('name'=>'parent','id'=>$tax_id));
	
	if($parent == '0'){
		return $tax_id;
	}else{
		if(isset_taxonomy_id($parent) AND $parent!=$tax_id ){
			return get_biggest_parent_tax($parent);
		}
	}
}
?>