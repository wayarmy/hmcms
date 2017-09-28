<?php
/** 
 * Đây là tệp tin xử lý quản trị admin
 * Vị trí : /admin/admin.php 
 */
 
if ( ! defined('BASEPATH')) exit('403');
define('IN_ADMINCP', TRUE);

$disallow_check = array('login.php','login_ajax.php');

if( !in_array( hm_get('run'),$disallow_check ) ){
	
	$cookie_admin_login = NULL;
	$session_admin_login = NULL;
	
	if(isset($_COOKIE['admin_login'])){
		$cookie_admin_login = $_COOKIE['admin_login'];
	}
	if(isset($_SESSION['admin_login'])){
		$session_admin_login = $_SESSION['admin_login'];
	}
	
	if($cookie_admin_login == NULL OR $session_admin_login == NULL){
		
		$login_page = BASE_URL.HM_ADMINCP_DIR.'?run=login.php&back='.urlencode(SITE_URL.$_SERVER['REQUEST_URI']);
		echo '<meta http-equiv="refresh" content="0;'.$login_page.'">';
		hm_exit(_('Đang chuyển hướng đến trang đăng nhập'));
	}
	
	if ( $cookie_admin_login == $session_admin_login ) {
		
		/** Làm mới cookie admin */
		setcookie('admin_login', $cookie_admin_login ,time() + COOKIE_EXPIRES, '/');
		define('ADMIN_LOGIN',$session_admin_login);
		
	}else{
		
		$login_page = BASE_URL.HM_ADMINCP_DIR.'?run=login.php&back='.SITE_URL.$_SERVER['REQUEST_URI'];
		echo '<meta http-equiv="refresh" content="0;'.$login_page.'">';
		hm_exit(_('Đang chuyển hướng đến trang đăng nhập'));
		
	}

}

if( in_array( hm_get('run'),$disallow_check ) AND hm_get('action')!='logout' ){
	
	$cookie_admin_login = NULL;
	$session_admin_login = NULL;
	if(isset($_COOKIE['admin_login'])){
		$cookie_admin_login = $_COOKIE['admin_login'];
	}
	if(isset($_SESSION['admin_login'])){
		$session_admin_login = $_SESSION['admin_login'];
	}

	if ( $cookie_admin_login == $session_admin_login AND $session_admin_login!=NULL ) {
		
		$admin_page = BASE_URL.HM_ADMINCP_DIR;
		echo '<meta http-equiv="refresh" content="0;'.$admin_page.'">';
		hm_exit(_('Đang chuyển hướng đến trang admin'));
		
	}
	
}

