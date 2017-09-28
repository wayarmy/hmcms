<?php
/** 
 * Tệp tin xử lý admin bằng ajax trong admin
 * Vị trí : admin/admin_ajax.php 
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

/** gọi model xử lý admin */
require_once( dirname( __FILE__ ) . '/admin/admin_model.php' );


$action=hm_post('action');

switch ($action) {
	
	case 'closeSidebar':
		echo closeSidebar();
	break;
	case 'openSidebar':
		echo openSidebar();
	break;

}


