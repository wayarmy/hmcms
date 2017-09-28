<?php
/** 
 * Tệp tin xử lý content trong admin
 * Vị trí : admin/optimize.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý optimize */
require_once( dirname( __FILE__ ) . '/optimize/optimize_model.php' );


$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
    case 'index':
		/** Hiển thị giao diện optimize */		
		function admin_content_page(){
			global $args;
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'optimize.php');
		}
		hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');
    break;
	
    default :
		/** Hiển thị giao diện optimize */		
		function admin_content_page(){
			global $args;
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'optimize.php');
		}
		hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');
}


