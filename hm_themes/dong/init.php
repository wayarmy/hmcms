<?php
/*
Theme Name: Đông;
Description: Giao diện mặc định của Hoa Mai CMS;
Version: 1.0;
Version Number: 1;
*/

/** Đăng ký vị trí menu đầu trang */
$args = array(
			'name'				=>'topmenu',
			'nice_name' 		=> _('Menu đầu trang'),
			'wrapper' 			=> 'ul',
			'wrapper_class' 	=> 'nav navbar-nav',
			'wrapper_id' 		=> '',
			'item' 				=> 'li',
			'item_class' 		=> '',
			'item_id' 			=> '',
			'permalink_class' 	=> '',
			'permalink_attr' 	=> '',
			'permalink_before'	=> '',
			'permalink_after'	=> '',
			'echo'				=> TRUE,
		);
register_menu_location($args);

/* 
Đăng ký trang cài đặt cho giao diện
*/
$args=array(
	'label'=>'Giao diện Đông',
	'key'=>'hm_theme_dong_setting',
	'function'=>'hm_theme_dong_setting',
	'function_input'=>array(),
	'child_of'=>FALSE,
);
register_admin_setting_page($args);
function hm_theme_dong_setting(){
	
	if(isset($_POST['save_theme_setting'])){
		
		foreach($_POST as $key => $value){
			
			$args = array(
							'section'=>'theme_dong',
							'key'=>$key,
							'value'=>$value,
						);
			
			set_option($args);
			
		}
	
	}
	
	hm_include(BASEPATH.'/'.HM_THEME_DIR.'/dong/admincp/theme_setting.php');
}

/** Tạo trang tìm kiếm */
register_request('tim-kiem','theme_dong_search');
function theme_dong_search(){
	get_template_part('search');
}

?>