<?php
/** 
 * Xử lý kết nối database
 * Vị trí : hm_include/database.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/**
 * Gọi thư viện mysql
 */
require_once(BASEPATH . HM_INC . '/database/mysql.php');

/**
 * Kết nối mysql
 */

$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
if (!$hmdb->IsConnected()){
	exit('Không thể kết nối đến cơ sở dữ liệu');
}else{
	if ($hmdb->Error()){
		exit($hmdb->Kill());
	}
	$hmdb->Query('SET NAMES "UTF8"');
}



?>