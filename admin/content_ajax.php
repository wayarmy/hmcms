<?php
/** 
 * Tệp tin xử lý content bằng ajax trong admin
 * Vị trí : admin/content_ajax.php 
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

/** gọi model xử lý content */
require_once( dirname( __FILE__ ) . '/content/content_model.php' );
require_once( dirname( __FILE__ ) . '/taxonomy/taxonomy_model.php' );

$key=hm_get('key');
$id=hm_get('id');
$action=hm_get('action');

switch ($action) {
	
	case 'data':
		$args = array();
		$args['status'] = hm_get('status','public');
		$args['perpage'] = hm_get('perpage','30');
		$args['search_keyword'] = hm_get('search_keyword','');
		$args['search_target'] = hm_get('search_target','');
		$args['search_order_by'] = hm_get('search_order_by','');
		$args['search_order'] = hm_get('search_order','ASC');
		$args['taxonomy'] = hm_get('taxonomy','');
		if($args['status'] == 'public'){
			$args['status'] = array('public','hide');
		}
		echo content_show_data($key,$args);
	break;

    case 'add':
		/** Thực hiện thêm content */
		$args = array(
						'content_key'=>$key,
					 );
        echo content_ajax_add($args);
    break;
	
	case 'add_chapter':
		/** Thực hiện thêm chapter */
        echo content_ajax_add_chapter($id);
    break;
	
	case 'edit':
		/** Thực hiện sửa content */
       echo  content_ajax_edit($id);
    break;
	
	case 'draft':
		/** Thực hiện xóa content */
		update_con_val( array('id'=>hm_post('id'),'name'=>'status','value'=>'draft') );
        echo content_update_val( array( 'id'=>hm_post('id'),'value'=>array('status'=>'draft') ) );
    break;
	
	case 'delete_permanently':
		/** Thực hiện xóa vĩnh viễn content */
        echo content_delete_permanently(hm_post('id'));
    break;
	
	case 'public':
		/** Thực hiện khôi phục content */
        echo content_update_val( array( 'id'=>hm_post('id'),'value'=>array('status'=>'public') ) );
    break;
	
	case 'ajax_slug':
		/** Thực hiện tạo slug từ chuỗi */
		echo content_ajax_slug();
	break;	
	
	case 'update_order':
		/** Thực hiện cập nhật lại số thứ tự content */
		$id = hm_get('id',0);
		$order = hm_get('order',0);
		content_update_order($id,$order);
	break;	
	
	case 'update_sidebar_box_order':
		/** Thực hiện cập nhật lại số thứ tự sidebar box */
		$func = hm_get('func');
		$order = hm_get('order');
		update_sidebar_box_order($func,$order);
	break;	
	
	case 'update_mainbar_box_order':
		/** Thực hiện cập nhật lại số thứ tự mainbar box */
		$func = hm_get('func');
		$order = hm_get('order');
		update_mainbar_box_order($func,$order);
	break;	
	
	case 'update_form_input_order':
		/** Thực hiện cập nhật lại số thứ tự form input */
		$func = hm_get('func');
		$order = hm_get('order');
		update_form_input_order($func,$order);
	break;	
	
	case 'multi':
		/** Xử lý nhiều content cùng lúc */
		echo content_ajax_multi($key);
	break;	
}


