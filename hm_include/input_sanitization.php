<?php
/** 
 * Class tạo đường dẫn tĩnh (slug) từ chuỗi
 * Vị trí : hm_include/input_sanitization.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/**
 * Gọi thư viện input_sanitization
 */
require_once(BASEPATH . HM_INC . '/input_sanitization/hm_input_sanitization.php');

/**
 * Khởi tạo class
 */
$input_sanitization = new Input_Sanitization();

?>