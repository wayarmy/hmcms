<?php
/** 
 * Tệp tin xử lý taxonomy bằng ajax trong admin
 * Vị trí : admin/taxonomy_ajax.php 
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

/** gọi model xử lý taxonomy */
require_once( dirname( __FILE__ ) . '/taxonomy/taxonomy_model.php' );

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
	
	case 'data':
		$status = hm_get('status','public');
		$perpage = hm_get('perpage','30');
		echo taxonomy_show_data($key,$status,$perpage);
	break;

    case 'add':
		/** Thực hiện thêm taxonomy */
        echo taxonomy_ajax_add($key);
    break;
	
	case 'edit':
		/** Thực hiện sửa taxonomy */
        echo taxonomy_ajax_edit($id);
    break;
	
	case 'draft':
		/** Thực hiện xóa taxonomy */
        taxonomy_update_val( array( 'id'=>hm_post('id'),'value'=>array('status'=>MySQL::SQLValue('draft'),'parent'=>MySQL::SQLValue('0') ) ) );
    break;
	
	case 'delete_permanently':
		/** Thực hiện xóa vĩnh viễn taxonomy */
        taxonomy_delete_permanently(hm_post('id'));
    break;
	
	case 'public':
		/** Thực hiện khôi phục taxonomy */
        taxonomy_update_val( array( 'id'=>hm_post('id'),'value'=>array('status'=>MySQL::SQLValue('public') ) ) );
    break;
	
	case 'ajax_slug':
		/** Thực hiện tạo slug từ chuỗi */
		echo taxonomy_ajax_slug();
	break;	
	
	case 'quick_edit':
		/** Tạo form quick edit taxonomy */
		quick_edit_tax_form();
	break;	
	
	case 'multi':
		/** Xử lý nhiều danh mục cùng lúc */
		echo taxonomy_ajax_multi($key);
	break;	

}


