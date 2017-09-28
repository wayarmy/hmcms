<?php
/** 
 * Tệp tin xử lý thư viện trong admin
 * Vị trí : admin/media.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý media */
require_once( dirname( __FILE__ ) . '/media/media_model.php' );

/** check quyền của user */
$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']),TRUE);
$user_id = $session_admin_login['user_id'];
user_role_redirect($user_id,'media');


$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
	default :
		/** Hiển thị giao diện thư viện */
		function admin_content_page(){
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'media.php');
		}
}

/** fontend */
hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');

