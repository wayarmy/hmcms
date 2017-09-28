<?php
/** 
 * Tệp tin model của admin trong admin
 * Vị trí : admin/admin/admin_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');

function closeSidebar(){
	
	setcookie('sidebar_status', 'close' ,time() + COOKIE_EXPIRES, '/');
	$_SESSION['sidebar_status'] = 'close';
	
}
function openSidebar(){
	
	setcookie('sidebar_status', 'open' ,time() + COOKIE_EXPIRES, '/');
	$_SESSION['sidebar_status'] = 'open';
	
}
?>