<?php
/** 
 * Tệp tin xử lý live edit trong admin
 * Vị trí : admin/live_edit.php 
 */
if ( ! defined('BASEPATH')) exit('403');
if(!defined('ALLOW_live_edit_PAGE')){
	define('ALLOW_live_edit_PAGE',TRUE);
}
if(ALLOW_live_edit_PAGE != TRUE){
	hm_exit('Tính năng đã bị tắt');
}

/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý live edit */
require_once( dirname( __FILE__ ) . '/live_edit/live_edit_model.php' );


if(hm_get('live_edit')=='on'){
	$type = hm_get('type');
	$key = hm_get('key');
	if(is_admin_login()){
		require_once(BASEPATH . HM_FRONTENT_DIR . '/template/live_edit/'.$type.'.php');
		exit();
	}
}