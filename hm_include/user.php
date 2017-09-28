<?php
/** 
 * Xử lý user
 * Vị trí : hm_include/user.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/**
 * Gọi thư viện user
 */
require_once(BASEPATH . HM_INC . '/user/hm_user.php');

/**
 * Khởi tạo class
 */
$hmuser = new user;

/**
 * Định nghĩa các hàm để thực hiện phương thức trong class kèm theo hook
 */
 
function register_user_field( $args=array() ){
	
	global $hmuser;

	hook_action('register_user_field');
	
	$return = $hmuser->register_user_field($args);
	
	$return = hook_filter('register_user_field',$return);
	
	return $return;

}

function get_user_field(){
	
	global $hmuser;

	hook_action('get_user_field');
	
	$return = $hmuser->get_user_field();
	
	$return = hook_filter('get_user_field',$return);
	
	return $return;

}

function user_role_id_to_nicename($user_role){
	
	global $hmuser;

	hook_action('user_role_id_to_nicename');
	
	$return = $hmuser->user_role_id_to_nicename($user_role);
	
	$return = hook_filter('user_role_id_to_nicename',$return);
	
	return $return;

}

function user_data( $id ){
	
	global $hmuser;
	
	hook_action('user_data');
	
	$return = $hmuser->user_data($id);

	$return = hook_filter('user_data',$return);
	
	return $return;

}

function user_field( $id=NULL ){
	
	global $hmuser;
	
	hook_action('user_field');
	
	$hmuser->user_field($id);

}

function user_field_data( $args = array() ){
	
	global $hmuser;
	
	hook_action('user_field_data');
	
	$return = $hmuser->user_field_data($id);

	$return = hook_filter('user_field_data',$return);
	
	return $return;

}

function isset_user( $id ){
	
	global $hmuser;
	
	hook_action('isset_user');
	
	$return = $hmuser->isset_user($id);

	$return = hook_filter('isset_user',$return);
	
	return $return;

}

function is_admin_login(){
	$cookie_admin_login = NULL;
	$session_admin_login = NULL;
	
	if(isset($_COOKIE['admin_login'])){
		$cookie_admin_login = $_COOKIE['admin_login'];
	}
	if(isset($_SESSION['admin_login'])){
		$session_admin_login = $_SESSION['admin_login'];
	}
	
	if($cookie_admin_login == NULL OR $session_admin_login == NULL){
		return FALSE;
	}else{
		return TRUE;
	}
	
}

function user_role($group){
	
	hook_action('user_role');
	
	$role_array = array();
	switch($group){
		case '1':
			/** Administrator */
			$role_array = array(
							'dashboard' => array(),
							'setting' => array(),
							'media' => array(),
							'update' => array(),
							'content' => array(),
							'taxonomy' => array(),
							'user' => array(),
							'plugin' => array(),
							'theme' => array(),
							'block' => array(),
							'menu' => array(),
							'command' => array(),
							'admin_page' => array(),
							'optimize' => array(),
						);
		break;
		case '2':
			/** Quản trị viên */
			$role_array = array(
							'dashboard' => array(),
							'media' => array(),
							'content' => array(),
							'taxonomy' => array(),
							'user' => array(),
							'admin_page' => array(),
						);
		break;
		case '3':
			/** Biên tập viên */
			$role_array = array(
							'dashboard' => array(),
							'media' => array(),
							'content' => array(),
							'taxonomy' => array(),
						);
		break;
		default:
			$role_array = array();
	
	}
	return $role_array;
	
}

function user_role_redirect($id=0,$controller=''){
	
	hook_action('user_role_redirect');
	$id = hook_filter('user_role_redirect_id',$id);
	$controller = hook_filter('user_role_redirect_controller',$controller);
	$return = FALSE;
	
	if(isset_user($id)){
		$user_data = user_data($id);
		$user_role = $user_data->user_role;
		$role_array = user_role($user_role);	
		foreach($role_array as $controller_name => $controller_action){
			if($controller==$controller_name){
				$return = TRUE;
			}
		}
	}else{
		$return = FALSE;
	}
	
	if($return == FALSE){
		hm_exit('Tài khoản của bạn không thể xem trang này');
	}
}

function current_user_role($controller=FALSE){
	$session_admin_login = json_decode(hm_decode_str($_SESSION['admin_login']),TRUE);
	$user_id = $session_admin_login['user_id'];
	$return = FALSE;
	if(isset_user($user_id)){
		$user_data = user_data($user_id);
		$user_role = $user_data->user_role;
		$role_array = user_role($user_role);	
		foreach($role_array as $controller_name => $controller_action){
			if($controller==$controller_name){
				$return = TRUE;
			}
		}
	}else{
		$return = FALSE;
	}
	return $return;
}

?>