<?php
/** 
 * Tệp tin xử lý taxonomy trong admin
 * Vị trí : admin/taxonomy.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý taxonomy */
require_once( dirname( __FILE__ ) . '/taxonomy/taxonomy_model.php' );

/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']),TRUE);
$user_id = $session_admin_login['user_id'];
user_role_redirect($user_id,'taxonomy');

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
    case 'add':
		
		/** Thực hiện thêm taxonomy trả về array */
		
        $args=taxonomy_data($key);
		
		/** Hiển thị giao diện thêm taxonomy bằng array ở trên */		
		function admin_content_page(){
			global $args;
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'taxonomy_add.php');
		}
		
    break;
	
	case 'edit':
		
		$args_tax=taxonomy_data_by_id($id);
		$key=$args_tax['taxonomy']->key;
		$args=taxonomy_data($key);
	
		function admin_content_page(){
			global $args;
			global $args_tax;
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'taxonomy_edit.php');
		}
		
    break;
	
    default :
		$args=taxonomy_show_all($key);
		
		/** Hiển thị giao diện thêm tất cả taxonomy */		
		function admin_content_page(){
			global $args;
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'taxonomy_all.php');
		}
}


/** fontend */
hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');


