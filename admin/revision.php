<?php
/** 
 * Tệp tin xử lý lịch sử thay đổi content trong admin
 * Vị trí : admin/revision.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý content */
require_once( dirname( __FILE__ ) . '/content/content_model.php' );
require_once( dirname( __FILE__ ) . '/revision/revision_model.php' );
require_once( dirname( __FILE__ ) . '/taxonomy/taxonomy_model.php' );

/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']),TRUE);
$user_id = $session_admin_login['user_id'];
user_role_redirect($user_id,'content');

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
    case 'view':
		if(isset_content_id($id)){
			
			$args=content_data_by_id($id);
			
			/** Hiển thị nội dung bản revision */		
			function admin_content_page(){
				global $args;
				require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'revision_view.php');
			}
			
		}else{
			/** Không tồn tại content có id như request, hiển thị giao diện 404 */		
			function admin_content_page(){
				require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_404.php');
			}
		}	
    break;
	case 'restore':
	
    break;
    default :

		if(isset_content_id($id)){
			
			$args=content_data_by_id($id);
			
			/** Hiển thị các bản thay đổi của content có id như request */		
			function admin_content_page(){
				global $args;
				require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'revision_all.php');
			}
			
		}else{
			/** Không tồn tại content có id như request, hiển thị giao diện 404 */		
			function admin_content_page(){
				require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_404.php');
			}
		}		
}


/** fontend */
hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');