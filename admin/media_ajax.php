<?php
/** 
 * Tệp tin xử lý media bằng ajax trong admin
 * Vị trí : admin/media_ajax.php 
 */
if ( ! defined('BASEPATH')) exit('403');
ini_set('display_errors', 0);
if (version_compare(PHP_VERSION, '5.3', '>='))
{
	error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
}
else
{
	error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
}
	

/** gọi tệp tin admin base */
require_once( dirname( __FILE__ ) . '/admin.php' );

/** gọi model xử lý media */
require_once( dirname( __FILE__ ) . '/media/media_model.php' );

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
	
    case 'ajax_media_box':
		/** Load giao diện media box */
        ajax_media_box();
    break;
	case 'show_media_file':
		/** Show nội dung thư mục */
        show_media_file();
    break;
	case 'add_media_group':
		/** Tạo nhóm media */
		$args = array();
		$args['group_name'] = hm_post('group_name');
		$args['group_parent'] = hm_post('group_parent',0);
        echo add_media_group($args);
    break;
	case 'rename_media_group':
		/** Đổi tên nhóm media */
		$args = array();
		$args['group_name'] = hm_post('group_name');
		$args['group_id'] = hm_post('group_id',0);
        echo rename_media_group($args);
    break;
	case 'del_media_group':
		/** Xóa nhóm media */
		$args = array();
		$args['group_id'] = hm_post('group_id',0);
        echo del_media_group($args);
    break;
	case 'add_media':
		/** Upload media */
        echo add_media();
    break;
	case 'delete_media':
		/** Delete media */
        delete_media(hm_post('id'));
    break;
	case 'move_media':
		/** Move media */
		$args = array();
		$args['group_id'] = hm_post('group_id',0);
		$args['id'] = hm_post('id',0);
        move_media($args);
    break;
	case 'multi_delete_media':
		/** Delete multi media */
        multi_delete_media(hm_post('ids'));
    break;	
	case 'thumbnail_media':
		/** Trả về link thumbnail của file */
		echo thumbnail_media(hm_post('id'));
	break;	
}


