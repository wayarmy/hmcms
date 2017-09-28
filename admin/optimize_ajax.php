<?php
/** 
 * Tệp tin xử lý optimize trong admin
 * Vị trí : admin/optimize.php 
 */
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
/** gọi model xử lý optimize */
require_once( dirname( __FILE__ ) . '/optimize/optimize_model.php' );
$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');
switch ($action) {
	
	case 'database':
		echo optimize_database();
	break;
	case 'images':
		echo optimize_images();
	break;
	
}
