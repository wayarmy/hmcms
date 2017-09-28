<?php
/** 
 * Xử lý block
 * Vị trí : hm_include/block.php 
 */
if ( ! defined('BASEPATH')) exit('403');
/**
 * Gọi thư viện block
 */
require_once(BASEPATH . HM_INC . '/block/hm_block.php');
/**
 * Khởi tạo class
 */
$hmblock = new block;


function register_block_container( $args=array() ){
	
	$args = hook_filter('before_register_block_container',$args);
	
	global $hmblock;
	
	hook_action('register_block_container');
	
	$return = $hmblock->register_block_container($args);
	
	$return = hook_filter('register_block_container',$return);
	
	return $return;
	
}

function block_container($block_container_name = NULL){
	
	$block_container_name = hook_filter('before_block_container',$block_container_name);
	
	global $hmblock;
	
	hook_action('block_container');
	
	$block_container = $hmblock->block_container;
	
	if(isset($block_container[$block_container_name])){
		
		$return = $hmblock->block_container($block_container[$block_container_name]);
		
		$return = hook_filter('block_container',$return);
		
		return $return;
		
	}else{
		
		return FALSE;
		
	}
	
}


function register_block( $args=array() ){
	
	$args = hook_filter('before_register_block',$args);
	
	global $hmblock;
	
	hook_action('register_block');
	
	$return = $hmblock->register_block($args);
	
	$return = hook_filter('register_block',$return);
	
	return $return;
	
}


function add_block($container,$function){

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$return = array();

	hook_action('add_block');
	
	$tableName=DB_PREFIX.'object';
	
	$data = array();
	$data["name"] = MySQL::SQLValue($function);
	$data["key"] = MySQL::SQLValue('block');
	$data["parent"] = MySQL::SQLValue('0');
	$data["order_number"] = MySQL::SQLValue('0');
	$block_id=$hmdb->InsertRow($tableName, $data);
	
	if(is_numeric($block_id)){
		
		$return['id']=$block_id;
		$bigest = 0;
		
		$tableName=DB_PREFIX.'option';
		$whereArray = array (
								'section'=>MySQL::SQLValue('block'),
								'key'=>MySQL::SQLValue($container),
							);
		$hmdb->SelectRows($tableName, $whereArray);
		
		if( $hmdb->HasRecords() ){
			
			$row=$hmdb->Row();
			$option_id = $row->id;
			$value = json_decode($row->value,TRUE);
			if(sizeof($value) > 1){
				$bigest = max(array_keys($value));
			}else{
				foreach($value as $key => $val){
					$bigest = $key;
				}
			}
			$new_order = $bigest+1;
			$value[$new_order] = $block_id;
			
			$data = array();
			$data["key"] = MySQL::SQLValue($container);
			$data["value"] = MySQL::SQLValue(hm_json_encode($value));
			$data["section"] = MySQL::SQLValue('block');
			$whereArray = array ('id'=>MySQL::SQLValue($option_id));
			$hmdb->UpdateRows($tableName, $data, $whereArray);
			
		}else{
			
			$value = array('1'=>$block_id);
			
			$data = array();
			$data["key"] = MySQL::SQLValue($container);
			$data["value"] = MySQL::SQLValue(hm_json_encode($value));
			$data["section"] = MySQL::SQLValue('block');
			$hmdb->InsertRow($tableName, $data);
		}
	
	}
	
	return hook_filter('add_block',$return);
	
}

function get_block($container){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$return = array();

	hook_action('get_block');
	
	$tableName=DB_PREFIX.'option';
	$whereArray = array (
							'section'=>MySQL::SQLValue('block'),
							'key'=>MySQL::SQLValue($container),
						);
	$hmdb->SelectRows($tableName, $whereArray);
	
	if( $hmdb->HasRecords() ){
		
		$row=$hmdb->Row();
		$value = json_decode($row->value,TRUE);
		foreach($value as $order => $block_id){
			
			$tableName=DB_PREFIX.'object';
			$whereArray = array (
									'key'=>MySQL::SQLValue('block'),
									'id'=>MySQL::SQLValue($block_id),
								);
			$hmdb->SelectRows($tableName, $whereArray);
			$row=$hmdb->Row();
			
			if(isset($row->name)){
				$block_func = $row->name;
				$return[$order]['id'] = $block_id;
				$return[$order]['func'] = $block_func;
			}

		}
		
	}
	
	return $return;
	
}

function remove_block($container,$id){

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$return = array();

	hook_action('remove_block');
	
	//delete object
	$tableName=DB_PREFIX.'object';
	$whereArray = array (
						'id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER),
					);
	$hmdb->DeleteRows($tableName, $whereArray);
	
	//delete field
	$tableName=DB_PREFIX.'field';
	$whereArray = array (
						'object_id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER),
						'object_type'=>MySQL::SQLValue('block'),
					);
	$hmdb->DeleteRows($tableName, $whereArray);
	
	//update block container option
	$tableName=DB_PREFIX.'option';
	$whereArray = array (
							'section'=>MySQL::SQLValue('block'),
							'key'=>MySQL::SQLValue($container),
						);
	$hmdb->SelectRows($tableName, $whereArray);
	
	if( $hmdb->HasRecords() ){
		
		$row=$hmdb->Row();
		$value = json_decode($row->value,TRUE);
		foreach($value as $order => $block_id){
			if($block_id == $id){
				unset($value[$order]);
			}
		}
		$value = hm_json_encode($value);
		$values = array();
		$values["value"] = MySQL::SQLValue($value);
		$return = $hmdb->UpdateRows($tableName, $values, $whereArray);
		
	}
	
	return $return;
	
}

function update_block_order($container,$blocks){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$return = array();
	
	hook_action('update_block_order');
	
	
	$tableName=DB_PREFIX.'option';
	$whereArray = array (
							'section'=>MySQL::SQLValue('block'),
							'key'=>MySQL::SQLValue($container),
						);
	$hmdb->SelectRows($tableName, $whereArray);
	
	if( $hmdb->HasRecords() ){
		
		$value = array();
		$i=1;
		foreach($blocks as $block_id){
			$value[$i] = $block_id;
			
			$tableName=DB_PREFIX.'object';
			$whereArray = array (
								'id'=>MySQL::SQLValue($block_id, MySQL::SQLVALUE_NUMBER),
							);
			$values = array();
			$values["order_number"] = MySQL::SQLValue($i);
			$hmdb->UpdateRows($tableName, $values, $whereArray);
			
			$i++;
		}
		
		$tableName=DB_PREFIX.'option';
		$whereArray = array (
							'section'=>MySQL::SQLValue('block'),
							'key'=>MySQL::SQLValue($container),
						);
		$value = hm_json_encode($value);
		$values = array();
		$values["value"] = MySQL::SQLValue($value);
		$return = $hmdb->UpdateRows($tableName, $values, $whereArray);
		
	}
	
	return $return;
	
}

function get_blo_val( $args=array() ){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	$args = hook_filter('before_get_blo_val',$args);
	
	global $hmblock;
	
	hook_action('get_blo_val');
	
	$return = $hmblock->get_blo_val($args);
	
	$return = do_all_shortcode_from_string($return);
	
	$return = hook_filter('after_get_blo_val',$return,$args);
	
	return $return;
}

function update_blo_val( $args=array() ){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	$args = hook_filter('before_update_blo_val',$args);
	
	global $hmblock;
	
	hook_action('update_blo_val');
	
	$return = $hmblock->update_blo_val($args);
	
	$return = hook_filter('after_update_blo_val',$return,$args);
	
	return $return;
}