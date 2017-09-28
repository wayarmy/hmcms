<?php
/** 
 * Xử lý admin cp theo đường dẫn
 * Vị trí : admin/index.php 
 */
define('IN_ADMIN', TRUE);

if ( ! defined('BASEPATH')) {
	define( 'BASEPATH', dirname(__DIR__) . '/' );
	require_once(BASEPATH . 'hm_config.php');
	require_once(BASEPATH . 'hm_loader.php');
	require_once(BASEPATH . 'hm_setup.php');
};

/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/**
 * Cấu hình tệp tin fontend
 */
define('LAYOUT_DIR', 'layout');
define('TEMPLATE_DIR', 'template');
define('ADMIN_LAYOUT_PATH', BASE_URL.HM_ADMINCP_DIR . '/' . LAYOUT_DIR);

/** gọi model của admincp */
require_once(BASEPATH . HM_ADMINCP_DIR . '/' . 'function.php');
require_once(BASEPATH . HM_ADMINCP_DIR . '/' . 'user/user_model.php');

	
$dir = BASEPATH . HM_ADMINCP_DIR . '/' . hm_get('run','dashboard.php');
if( file_exists($dir) && !is_dir($dir) ){
	
	require_once(BASEPATH . HM_ADMINCP_DIR . '/' . hm_get('run','dashboard.php'));

	
}else{

	/** Không tồn tại file như request, hiển thị giao diện 404 */		
	function admin_content_page(){
		require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'admincp_404.php');
	}
	require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');
}




?>