<?php
/** 
 * Tệp tin nhóm phát triển
 * Vị trí : admin/our_team.php 
 */
if ( ! defined('BASEPATH')) exit('403');


/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** Hiển thị giao diện dashboard */		
function admin_content_page(){
	
	hook_action('admin_our_team_before');
		
	require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'our_team.php');

	hook_action('admin_our_team_after');
	
}

/** fontend */
hm_admin_require_layout(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'layout.php');