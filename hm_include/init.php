<?php
/** 
 * Khởi tạo mặc định
 * Vị trí : hm_include/init.php 
 */
if ( ! defined('BASEPATH')) exit('403');

$args=array(
	'label'=>'Cài đặt tổng quan',
	'key'=>'hm_main_setting',
	'function'=>'hm_main_setting',
	'function_input'=>array(),
	'child_of'=>FALSE,
);
register_admin_setting_page($args);

/** Tạo trang cài đặt tổng quan trong admincp */
function hm_main_setting(){

	if(isset($_POST['save_system_setting'])){
		
		foreach($_POST as $key => $value){
			
			if($key!='save_system_setting'){
				$args = array(
								'section'=>'system_setting',
								'key'=>$key,
								'value'=>$value,
							);
				
				set_option($args);
			}
			
		}
	
	}
	
	hm_include(BASEPATH.'/'.HM_ADMINCP_DIR.'/layout/main_setting.php');

}

/** load init.php của giao diện */
$theme = activated_theme();
if(file_exists(BASEPATH . HM_THEME_DIR . '/'.$theme.'/init.php')){
	require_once(BASEPATH . HM_THEME_DIR . '/'.$theme.'/init.php');
}