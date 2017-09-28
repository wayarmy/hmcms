<?php
/** 
 * Tệp tin xử lý user trong admin
 * Vị trí : admin/user.php 
 */
if ( ! defined('BASEPATH')) exit('403');
ini_set('display_errors', 0);
if (version_compare(PHP_VERSION, '5.3', '>='))
{
	error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
}
else
{
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
}
	
/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý user */
require_once( dirname( __FILE__ ) . '/user/user_model.php' );

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
	
	case 'data':
		/** Show list user */
		$user_group = hm_get('user_group','1');
		$perpage = hm_get('perpage','30');
		echo user_show_data($user_group,$perpage);
	break;

    case 'add':
		/** Thêm user mới */	
		echo ajax_add_user();
    break;
	
	case 'edit':
		/** Lấy thông tin user trả về array */
		if(!is_numeric($id)){
			$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']),TRUE);
			$id = $session_admin_login['user_id'];
		}
		/** Sửa thông tin user */	
		$args = array(
			'id_update'=>$id,		
		);
		echo ajax_add_user($args);
    break;
	
	case 'ban':
		/** Khóa user */	
		echo ajax_ban_user();
    break;
}
