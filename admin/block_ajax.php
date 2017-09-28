<?php
/** 
 * Tệp tin xử lý block bằng ajax trong admin
 * Vị trí : admin/block_ajax.php 
 */
if ( ! defined('BASEPATH')) exit('403');
if(!defined('ALLOW_BLOCK_PAGE')){
	define('ALLOW_BLOCK_PAGE',TRUE);
}
if(ALLOW_BLOCK_PAGE != TRUE){
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

/** gọi model xử lý block */
require_once( dirname( __FILE__ ) . '/block/block_model.php' );

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
	
	case 'add_block':
		$container = hm_get('container');
		$function = hm_get('function');
		echo hm_json_encode(add_block($container,$function),TRUE);
	break;
	case 'remove_block':
		$container = hm_get('container');
		$id = hm_get('id');
		echo remove_block($container,$id);
	break;
	case 'update_order':
		$container = hm_get('container');
		$blocks = hm_post('blocks');
		echo update_block_order($container,$blocks);
	break;
	case 'update_block_value':
		$block_id = hm_post('block_id');
		echo update_block_value($block_id);
	break;
}


