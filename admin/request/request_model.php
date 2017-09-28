<?php
/** 
 * Tệp tin model của request trong admin
 * Vị trí : admin/request/request_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');


function request_suggest($key){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	global $hmtaxonomy;
	global $hmcontent;
	
	$return = '';
	$input_name = hm_post('input','');
	$key = trim($key);
	$key = str_replace(' ','-',$key);

	if($key!=''){
		
		$tableName=DB_PREFIX.'request_uri';
		$hmdb->Query("SELECT * FROM `".$tableName."` WHERE `uri` LIKE '%".$key."%' LIMIT 10");
		while($row = $hmdb->Row()){
			$id = $row->id;
			$object_id = $row->object_id;
			$object_type = $row->object_type;
			$uri = $row->uri;
			
			$suggest_label = '';
			$object_name = '';
			
			switch ($object_type) {
				case 'taxonomy':
					$tax_data = taxonomy_data_by_id($object_id);
					$tax_key = $tax_data['taxonomy']->key;
					$taxonomy = $hmtaxonomy->hmtaxonomy;
					$suggest_label = $taxonomy[$tax_key]['taxonomy_name'];
					if( $tax_data['taxonomy']->parent > 0 ){
						$taxonomy_parent = taxonomy_parent_breadcrumb($tax_data['taxonomy']->id);
						krsort($taxonomy_parent);
						$object_name = array();
						foreach($taxonomy_parent as $taxonomy){
							$object_name[] = $taxonomy['name'];
						}
						$object_name = implode("&nbsp;&raquo;&nbsp;",$object_name);
					}else{
						$object_name = get_tax_val('name=name&id='.$object_id);
					}
				break;
				case 'content':
					$con_data = content_data_by_id($object_id);
					$con_key = $con_data['content']->key;
					$content = $hmcontent->hmcontent;
					$suggest_label = $content[$con_key]['content_name'];
					$object_name = get_con_val('name=name&id='.$object_id);
				break;
			}
						
			$return.='<li>';
			$return.='<p data-id="'.$id.'" data-input="'.$input_name.'" data-name="'.$object_name.'" object_id="'.$object_id.'" object_type="'.$object_type.'">';
			$return.='<span class="suggest_label">'.$suggest_label.': </span><b>'.$object_name.'</b>';
			$return.='</p>';
			$return.='</li>';
		}
		
	}

	return $return;
}

function suggest_data($args=array()){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	global $hmtaxonomy;
	global $hmcontent;
	$return = '';
	
	$val = $args['val'];
	$key = $args['key'];
	$type = $args['type'];
	$input_name = $args['input'];
	$key = explode(',',$key);
	$key = join("','",$key);   
	$val = trim($val);
	$val = str_replace(' ','-',$val);
	
	$tableName=DB_PREFIX.$type;
	$where_in_key = "SELECT `id` FROM `".$tableName."` WHERE `key` IN ('".$key."')";
	
	if($val!=''){
		
		$tableName=DB_PREFIX.'request_uri';
		$hmdb->Query("SELECT * FROM `".$tableName."` WHERE `object_id` IN (".$where_in_key.") AND `uri` LIKE '%".$val."%' AND `object_type` = '".$type."' LIMIT 10");
		
		while($row = $hmdb->Row()){
			$id = $row->id;
			$object_id = $row->object_id;
			$object_type = $row->object_type;
			$uri = $row->uri;
			
			$suggest_label = '';
			$object_name = '';
			
			switch ($object_type) {
				case 'taxonomy':
					$tax_data = taxonomy_data_by_id($object_id);
					$tax_key = $tax_data['taxonomy']->key;
					$taxonomy = $hmtaxonomy->hmtaxonomy;
					$suggest_label = $taxonomy[$tax_key]['taxonomy_name'];
					if( $tax_data['taxonomy']->parent > 0 ){
						$taxonomy_parent = taxonomy_parent_breadcrumb($tax_data['taxonomy']->id);
						krsort($taxonomy_parent);
						$object_name = array();
						foreach($taxonomy_parent as $taxonomy){
							$object_name[] = $taxonomy['name'];
						}
						$object_name = implode("&nbsp;&raquo;&nbsp;",$object_name);
					}else{
						$object_name = get_tax_val('name=name&id='.$object_id);
					}
					$id = $object_id;
				break;
				case 'content':
					$con_data = content_data_by_id($object_id);
					$con_key = $con_data['content']->key;
					$content = $hmcontent->hmcontent;
					$suggest_label = $content[$con_key]['content_name'];
					$object_name = get_con_val('name=name&id='.$object_id);
					$id = $object_id;
				break;
			}
						
			$return.='<li>';
			$return.='<p data-id="'.$id.'" data-input="'.$input_name.'" data-name="'.$object_name.'" object_id="'.$object_id.'" object_type="'.$object_type.'">';
			$return.='<span class="suggest_label">'.$suggest_label.': </span><b>'.$object_name.'</b>';
			$return.='</p>';
			$return.='</li>';
		}
		
	}

	return $return;
	
}

?>