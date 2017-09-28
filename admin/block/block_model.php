<?php
/** 
 * Tệp tin model của block trong admin
 * Vị trí : admin/block/block_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** Cập nhật option của 1 block */
function update_block_value($block_id){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	if(is_numeric($block_id)){
		
		/** lưu block field vào data */
		foreach ( $_POST as $post_key => $post_val ){
		
			if(is_array($post_val)){ $post_val = hm_json_encode($post_val); }
			
			$tableName=DB_PREFIX.'field';
			
			$values["name"] = MySQL::SQLValue($post_key);
			$values["val"] = MySQL::SQLValue($post_val);
			$values["object_id"] = MySQL::SQLValue($block_id, MySQL::SQLVALUE_NUMBER);
			$values["object_type"] = MySQL::SQLValue('block');
			
			$whereArray = array (
								'object_id'=>MySQL::SQLValue($block_id, MySQL::SQLVALUE_NUMBER),
								'object_type'=>MySQL::SQLValue('block'),
								'name'=>MySQL::SQLValue($post_key),
								);
			$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
			
			unset($values);
			
		}
		
	}
	
}