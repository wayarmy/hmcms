<?php
/** 
 * Tệp tin xử lý login bằng ajax trong admin
 * Vị trí : admin/login_ajax.php 
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

/** gọi model xử lý login */
require_once( dirname( __FILE__ ) . '/login/login_model.php' );

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
	case 'log-me-in':
		echo admin_cp_login();
	break;
	case 'lostpw':
		echo admin_cp_lostpw();
	break;	
	case 'newpw':
		echo admin_cp_newpw();
	break;	
}