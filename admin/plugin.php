<?php
/** 
 * Tệp tin xử lý plugin trong admin
 * Vị trí : admin/plugin.php 
 */
if ( ! defined('BASEPATH')) exit('403');
if(defined('ALLOW_PLUGIN_PAGE') AND ALLOW_PLUGIN_PAGE != TRUE){
	hm_exit('Tính năng đã bị tắt');
}


/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý plugin */
require_once( dirname( __FILE__ ) . '/plugin/plugin_model.php' );

/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']),TRUE);
$user_id = $session_admin_login['user_id'];
user_role_redirect($user_id,'plugin');

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
    case 'add':
		
		$args = api_plugin_data(FALSE,array('keyword'=>hm_post('keyword','')));
		
		/** Hiển thị giao diện thêm plugin */
		function admin_content_page(){
			global $args;
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'plugin_add.php');
		}
		
    break;
    default :
		
		/** Hiển thị giao diện tất cả plugin */
		function admin_content_page(){
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'plugin_all.php');
		}
}


/** fontend */
hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');