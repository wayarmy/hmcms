<?php
/** 
 * Tệp tin xử lý admin_page trong admin
 * Vị trí : admin/admin_page.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý admin_page */
require_once( dirname( __FILE__ ) . '/admin_page/admin_page_model.php' );

/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']),TRUE);
$user_id = $session_admin_login['user_id'];
user_role_redirect($user_id,'admin_page');

function admin_content_page(){
	
	$key=hm_get('key');
	$id=hm_get('id');
	$action=hm_get('action');
		
	$admin_page = get_admin_page();
	
	if( isset($admin_page[$key]) ){
		$func = FALSE;
		$func_input = FALSE;
		if( isset($admin_page[$key]['function']) ){
			$func = $admin_page[$key]['function'];
		}
		if( isset($admin_page[$key]['function_input']) ){
			$func_input = $admin_page[$key]['function_input'];
		}
		
		if(!function_exists($func)) {
			echo(_('Không tìm thấy hàm : ').$func);
		}else{
			call_user_func($func,$func_input);
		}
	
	}else{
		
		require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_404.php');
		
	}

}

/** fontend */
hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');