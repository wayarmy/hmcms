<?php
/** 
 * Tệp tin xử lý update trong admin
 * Vị trí : admin/update.php 
 */
if ( ! defined('BASEPATH')) exit('403');
if(!defined('ALLOW_UPDATE')){
	define('ALLOW_UPDATE',TRUE);
}
if(ALLOW_UPDATE != TRUE){
	hm_exit('Tính năng đã bị tắt');
}
/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý update */
require_once( dirname( __FILE__ ) . '/update/update_model.php' );
/** gọi model xử lý plugin */
require_once( dirname( __FILE__ ) . '/plugin/plugin_model.php' );

/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']),TRUE);
$user_id = $session_admin_login['user_id'];
user_role_redirect($user_id,'update');


$args['check_core'] = update_check('core');
$available_plugin = json_decode(available_plugin(),TRUE);
$args['plugin'] = $available_plugin['plugins'];

/** Hiển thị giao diện update core */		
function admin_content_page(){
	global $args;
	require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'update.php');
}


/** fontend */
hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');


