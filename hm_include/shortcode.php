<?php
/** 
 * Xử lý shortcode
 * Vị trí : hm_include/shortcode.php 
 */
if ( ! defined('BASEPATH')) exit('403');
/**
 * Gọi thư viện shortcode
 */
require_once(BASEPATH . HM_INC . '/shortcode/hm_shortcode.php');
/**
 * Khởi tạo class
 */
$hmshortcode = new shortcode;


function register_shortcode( $args=array() ){
	
	$args = hook_filter('before_register_shortcode',$args);
	
	global $hmshortcode;
	
	hook_action('register_shortcode');
	
	$return = $hmshortcode->register_shortcode($args);
	
	$return = hook_filter('register_shortcode',$return);
	
	return $return;
	
}

function isset_shortcode( $args=array() ){
	$args = hook_filter('before_isset_shortcode',$args);
	
	global $hmshortcode;
	
	hook_action('isset_shortcode');
	
	$return = $hmshortcode->isset_shortcode($args);
	
	$return = hook_filter('isset_shortcode',$return);
	
	return $return;
}

function do_all_shortcode_from_string($string){
	
	$string = hook_filter('before_do_all_shortcode_from_string',$string);
	
	global $hmshortcode;
	
	hook_action('do_all_shortcode_from_string');
	
	preg_match_all("'\[shortcode=(.*?)\]'si", $string, $res);
	if($res[0]){
		$all_shortcode = $res[0];
		foreach($all_shortcode as $shortcode){
			$shortcode_result = do_shortcode($shortcode);
			$string = str_replace($shortcode,$shortcode_result,$string);
		}
	}
	
	$string = hook_filter('do_all_shortcode_from_string',$string);
	
	return $string;
	
}

function do_shortcode($shortcode){
	
	$shortcode = hook_filter('before_do_shortcode',$shortcode);
	
	global $hmshortcode;
	
	hook_action('do_shortcode');
	
	$return = $shortcode;
	$input_shortcode = $shortcode;
	$shortcode = str_replace('[','',$shortcode);
	$shortcode = str_replace(']','',$shortcode);
	$shortcode = html_entity_decode($shortcode);
	$args = null;
	$returnValue = parse_str($shortcode, $args);
	
	$shortcode_name = $args['shortcode'];
	if( isset_shortcode(array('name'=>$shortcode_name)) ){
		
		$all_shortcode = $hmshortcode->shortcode;
		if(isset($all_shortcode[$shortcode_name]['func'])){
			$shortcode_func = $all_shortcode[$shortcode_name]['func'];
			if( function_exists($shortcode_func)){
				
				$return = call_user_func($shortcode_func, $args);
				
			}
		}
		
	}
	
	$shortcode = hook_filter('do_shortcode',$shortcode);
	
	return $return;
	
}