<?php
/** 
 * Tệp tin xử lý ajax trong admin
 * Vị trí : admin/ajax.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

$key=hm_get('key');
	
$ajax_page = get_admin_ajax_page();

if( isset($ajax_page[$key]) ){
	if(!isset($ajax_page[$key]['function'])){ $ajax_page[$key]['function'] = FALSE; }
	if(!isset($ajax_page[$key]['function_input'])){ $ajax_page[$key]['function_input'] = FALSE; }
	$func = $ajax_page[$key]['function'];
	$func_input = $ajax_page[$key]['function_input'];
	if(!function_exists($func)) {
		echo(_('Không tìm thấy hàm : ').$func);
	}else{
		call_user_func($func,$func_input);
	}

}else{
	
	require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_404.php');
	
}