<?php 
/** Định nghĩa BASEPATH là thư mục gốc chứa hm_header.php */
define( 'BASEPATH', dirname(__FILE__) . '/' );

if( !file_exists(BASEPATH.'hm_config.php') ){
	
	/** Gọi file install */
	require_once(BASEPATH.'/hm_include/install.php');	
	
}else{

	/** Load file cấu hình */
	require_once(BASEPATH.'hm_config.php');
	
	/** Load file ngôn ngữ */
	require_once(BASEPATH.HM_FRONTENT_DIR.'/languages/'.LANG.'.php');
	
	/** Gọi file load các thư viện */
	require_once(BASEPATH.'hm_loader.php');

	/** Xây dưng website */
	require_once(BASEPATH.'hm_setup.php');
	
	/** Chạy website dựa trên truy vấn URL */
	require_once(BASEPATH.'hm_routing.php');
	
}
?>

