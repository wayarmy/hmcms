<?php
/** 
 * Tệp tin model của revision trong admin
 * Vị trí : admin/revision/revision_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');

function revision_show_data($id){

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);

	hook_action('revision_show_data');
	
	if (! $hmdb->Query("SELECT * FROM ".DB_PREFIX."content WHERE `status` = 'revision' AND `parent` = '$id' ORDER BY id DESC")) $hmdb->Kill();
	
	$array_rev=array();
	while ($row = $hmdb->Row()) {
		$data_rev = content_data_by_id($row->id);
		$array_rev[]=array('id'=>$row->id,'name'=>$row->name,'slug'=>$row->slug,'public_time'=>date('d-m-Y H:i',$data_rev['field']['public_time']));
	}
	
	$array['revision']=$array_rev;

	return hook_filter('revision_show_data',hm_json_encode($array,TRUE));
	
}