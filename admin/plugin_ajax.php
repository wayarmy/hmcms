<?php
/** 
 * Tệp tin xử lý plugin trong admin
 * Vị trí : admin/plugin.php 
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
	
if(ALLOW_PLUGIN_PAGE != TRUE){
	hm_exit('Tính năng đã bị tắt');
}

/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý plugin */
require_once( dirname( __FILE__ ) . '/plugin/plugin_model.php' );

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
	
	case 'available':
		echo available_plugin();
	break;
	
	case 'disable_plugin':
		echo disable_plugin(0);
	break;
	
	case 'active_plugin':
		echo disable_plugin(1);
	break;
	
	case 'add_plugin':
		$href = hm_post('href');
		echo add_plugin($href);
	break;

}
