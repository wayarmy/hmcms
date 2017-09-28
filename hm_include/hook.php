<?php
/** 
 * Định nghĩa các hook
 * Vị trí : hm_include/hook.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/**
 * Action Hooks
 */
 

$hm_action_events = array();


/**
 * Cho phép chạy các function theo sự kiện
 */
function hook_action($event)
{
    global $hm_action_events;
 
    if(isset($hm_action_events[$event]))
    {
        foreach($hm_action_events[$event] as $funcs)
        {
            $func=$funcs['function'];
            $args=$funcs['input_array'];
            if(!function_exists($func)) {
                die('Unknown function: '.$func);
            }else{
                call_user_func($func, $args);
            }
        }
    }
 
}


/**
 * Đăng ký function chạy theo sự kiện
 * register_action([sự kiện][function][mảng input cho function])
 */
function register_action($event, $func, $args=array())
{
    global $hm_action_events;
    $hm_action_events[$event][] = array('function'=>$func,'input_array'=>$args);
}


/**
 * Filter Hooks
 */
 
 
$hm_filter_events = array();


/**
 * Cho phép chạy các function filter
 * hook_filter([sự kiện][nội dung sự kiện])
 */
function hook_filter($event,$content=FALSE,$input=FALSE) {
	
    global $hm_filter_events;
	
    if(isset($hm_filter_events[$event]))
    {
        foreach($hm_filter_events[$event] as $func) {
            if(!function_exists($func)) {
				
                die('Unknown function: '.$func);
				
            }else{
				
				$content = call_user_func($func,$content,$input);
				
            }
        }
    }
	
    return $content;
}


/**
 * Đăng ký function filter
 * register_filter([sự kiện][function])
 */
function register_filter($event, $func)
{
    global $hm_filter_events;
    $hm_filter_events[$event][] = $func;
}


/**
 * Request Hooks
 */

$hmrequest = array();

/** Đăng ký 1 request cố định */
function register_request($request,$func,$args=array()){
	global $hmrequest;
    $hmrequest[$request] = array('func'=>$func,'args'=>$args);
}

/** Lấy Thông tin request */
function registed_request($request = FALSE){
	global $hmrequest;
	if($request != FALSE){
		if(isset($hmrequest[$request])){
			return $hmrequest[$request];
		}else{
			return FALSE;
		}
	}else{
		return ($hmrequest);
	}
	
}


?>