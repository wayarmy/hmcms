<?php
/** 
 * Tệp tin dashboard trong admin
 * Vị trí : admin/hm_include/dashboard.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** Đăng ký dashboard box */
function register_dashboard_box( $args=array() ){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	
	$args = hook_filter('before_register_dashboard_box',$args);
	
	hook_action('register_dashboard_box');
	
	global $hmdashboard_box;
	
	if(is_array($args)){
		$hmdashboard_box[] = $args;
	}
}

/** Hiển thị dashboard box */
function dashboard_box($args=array()){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	
	global $hmdashboard_box;
	if(!is_array($hmdashboard_box)){
		$hmdashboard_box = array();
	}
	foreach ($hmdashboard_box as $box){
			
		$width = $box['width'];
		$func = $box['function'];
		$label = $box['label'];
		
		echo '<div class="col-md-'.$width.'">';
		echo '<div class="dashboard_box">';
			echo '<p class="dashboard_box_title ui-sortable-handle" ?>'.$label.'</p>';
			echo '<div class="dashboard_box_content">';
			if(function_exists($func)) {
				call_user_func($func, $args);
			}
			echo '</div>';
		echo '</div>';
		echo '</div>';
			
	}

}

?>