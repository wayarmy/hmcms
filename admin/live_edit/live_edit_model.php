<?php
/** 
 * Tệp tin model live edit trong admin
 * Vị trí : admin/live_edit/live_edit_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');


function live_submit(){
	$option_name = hm_get('option_name');
	$type = hm_get('type');
	foreach($_POST as $post_key => $post_val){
		if($post_key!='submit' AND $post_val!=''){
			$args = array(
						'section'=>'live_'.$type,
						'key'=>$post_key,
						'value'=>$post_val,
					);
			$value = set_option($args);
		}
	}
	echo '<div class="alert alert-success">'.hm_lang('setting_saved').'</div>';
}

