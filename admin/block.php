<?php
/** 
 * Tệp tin xử lý block trong admin
 * Vị trí : admin/block.php 
 */
if ( ! defined('BASEPATH')) exit('403');
if(!defined('ALLOW_BLOCK_PAGE')){
	define('ALLOW_BLOCK_PAGE',TRUE);
}
if(ALLOW_BLOCK_PAGE != TRUE){
	hm_exit('Tính năng đã bị tắt');
}

/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );
/** gọi model xử lý block */
require_once( dirname( __FILE__ ) . '/block/block_model.php' );

/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']),TRUE);
$user_id = $session_admin_login['user_id'];
user_role_redirect($user_id,'block');

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');
switch ($action) {
    
	
	default :
	
		/** Hiển thị giao diện vị trí block */
		function admin_content_page(){
			global $hmblock;
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'block.php');
		}
}

/** fontend */
hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');