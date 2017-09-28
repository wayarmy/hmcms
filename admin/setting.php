<?php
/** 
 * Tệp tin xử lý setting trong admin
 * Vị trí : admin/setting.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý setting */
require_once( dirname( __FILE__ ) . '/setting/setting_model.php' );

/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']),TRUE);
$user_id = $session_admin_login['user_id'];
user_role_redirect($user_id,'setting');

function admin_content_page(){
	
	$key=hm_get('key');
	$id=hm_get('id');
	$action=hm_get('action');
		
	$setting_page = get_admin_setting_page();
	
	if( isset($setting_page[$key]) ){
	
		$func = $setting_page[$key]['function'];
		if(isset($setting_page[$key]['function_input'])){
			$func_input = $setting_page[$key]['function_input'];
		}else{
			$func_input = array();
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