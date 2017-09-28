<?php
/** 
 * Tệp tin model của chapter trong admin
 * Vị trí : admin/chapter/chapter_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');

function chapter_show_data($id){

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);

	hook_action('chapter_show_data');
	
	if (! $hmdb->Query("SELECT * FROM ".DB_PREFIX."content WHERE `status` = 'chapter' AND `parent` = '$id' ORDER BY id DESC")) $hmdb->Kill();
	
	$array_cha=array();
	while ($row = $hmdb->Row()) {
		$data_cha = content_data_by_id($row->id);
		$array_cha[]=array('id'=>$row->id,'name'=>$row->name,'slug'=>$row->slug,'public_time'=>date('d-m-Y H:i',$data_cha['field']['public_time']));
	}
	
	$array['chapter']=$array_cha;

	return hook_filter('chapter_show_data',hm_json_encode($array,TRUE));
	
}