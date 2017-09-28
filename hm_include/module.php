<?php
/** 
 * Xử lý module
 * Vị trí : hm_include/module.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/**
 * Gọi thư viện module
 */
require_once(BASEPATH . HM_INC . '/module/hm_module.php');

/**
 * Khởi tạo class
 */
$hmmodule = new module;

/**
 * Định nghĩa các hàm để thực hiện phương thức trong class kèm theo hook
 */

function isset_module( $args=array() ){

	$args = hook_filter('before_isset_module',$args);
	
	global $hmmodule;
	
	hook_action('isset_module');
	
	$return = $hmmodule->isset_module($args);
	
	$return = hook_filter('isset_module',$return);
	
	return $return;
} 

function register_module( $args=array() ){

	$args = hook_filter('before_register_module',$args);
	
	global $hmmodule;
	
	hook_action('register_module');
	
	$return = $hmmodule->register_module($args);
	
	$return = hook_filter('register_module',$return);
	
	return $return;
}

function destroy_module( $args=array() ){

	$args = hook_filter('before_destroy_module',$args);
	
	global $hmmodule;
	
	hook_action('destroy_module');
	
	$return = $hmmodule->destroy_module($args);
	
	$return = hook_filter('destroy_module',$return);
	
	return $return;
}

function total_module( $args=array() ){

	$args = hook_filter('before_total_module',$args);
	
	global $hmmodule;
	
	hook_action('total_module');
	
	$return = $hmmodule->total_module($args);
	
	$return = hook_filter('total_module',$return);
	
	return $return;
}