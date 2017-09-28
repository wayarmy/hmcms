<?php
/** 
 * Thư viện xử lý plugin
 * Vị trí : hm_include/pluggable.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** Định nghĩa đường dẫn đến thư mục plugin */
define( 'PLUGIN_URI', BASE_URL.HM_PLUGIN_DIR.'/' );


/**
 * Gọi thư viện pluggable
 */
require_once(BASEPATH . HM_INC . '/pluggable/hm_pluggable.php');

/**
 * Khởi tạo class
 */
$hmpluggable = new pluggable;


/**
 * Chạy pluggable
 */

$hmpluggable->run_plugin();



?>