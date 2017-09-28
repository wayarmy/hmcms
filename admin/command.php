<?php
/** 
 * Tệp tin xử lý command trong admin
 * Vị trí : admin/command.php 
 */
if ( ! defined('BASEPATH')) exit('403');
if(!defined('ALLOW_COMMAND_PAGE')){
	define('ALLOW_COMMAND_PAGE',TRUE);
}
if(ALLOW_COMMAND_PAGE != TRUE){
	hm_exit('Tính năng đã bị tắt');
}


/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']),TRUE);
$user_id = $session_admin_login['user_id'];
user_role_redirect($user_id,'command');


$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

/** Hiển thị giao diện command */
function admin_content_page(){
	require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'command.php');
}


/** fontend */
hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');