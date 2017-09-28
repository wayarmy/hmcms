<?php
/** 
 * Tệp tin xử lý update trong admin
 * Vị trí : admin/update.php 
 */
if ( ! defined('BASEPATH')) exit('403');
if(!defined('ALLOW_UPDATE')){
	define('ALLOW_UPDATE',TRUE);
}
if(ALLOW_UPDATE != TRUE){
	hm_exit('Tính năng đã bị tắt');
}

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
/** gọi model xử lý update */
require_once( dirname( __FILE__ ) . '/update/update_model.php' );
$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');
switch ($action) {
	
	case 'update_core':
		echo update_core();
	break;
	
}
