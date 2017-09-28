<?php
/** 
 * Đây là tệp tin khởi tạo cấu trúc website
 * Tất cả các hàm ở đây đều có thể dùng trong plugin hay theme
 * Điều khác biệt là các hàm ở /hm_setup.php luôn chạy không phụ thuộc vào
 * plugin hay theme bạn đang dùng, còn nếu khai báo ở plugin hay theme thì
 * chỉ chạy khi plugin hoặc theme đó đã kích hoạt.
 * Trong mã nguồn này HoaMai được build dưới dạng một blog
 * Cấu trúc cho blog gồm 1 taxonomy "Danh mục bài viết" và 1 content type "Bài viết"
 * Để thực hiện việc này chúng ta sử dụng hàm register_taxonomy(); và register_content();
 * Vị trí : /hm_setup.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/**
 * Khởi tạo 1 taxonomy mặc định có tên là "Danh mục bài viết" và key là "category" 
 * cho dạng nội dung "post" và định nghĩa các trường nhập vào
 * Lưu ý : Luôn phải có 1 trường có key là primary_name_field, trường này sẽ được dùng là tên của
 * content, taxonomy và trường này có 'create_slug'=>TRUE để tạo slug - đường dẫn tĩnh cho content, taxonomy này
 */
 
hook_action('before_web_setup');
 
$field_array=array(
	'primary_name_field'=>array(
		'nice_name'=>hm_lang('category_name'),
		'description'=>hm_lang('the_name_is_how_it_appears_on_your_site'),
		'name'=>'name',
		'create_slug'=>TRUE,
		'input_type'=>'text',
		'default_value'=>'',
		'placeholder'=>hm_lang('enter_title_here'),
		'required'=>TRUE,
	),
	array(
		'nice_name'=>hm_lang('category_description'),
		'description'=>hm_lang('a_short_text_description_of_this_category'),
		'name'=>'description',
		'input_type'=>'textarea',
		'default_value'=>'',
		'placeholder'=>'',
		'required'=>FALSE,
	),
);

$args=array(
	'taxonomy_name'=>hm_lang('category'),
	'taxonomy_key'=>'category',
	'content_key'=>'post',
	'all_items'=>hm_lang('all_category'),
	'edit_item'=>hm_lang('edit_category'),
	'view_item'=>hm_lang('view_category'),
	'update_item'=>hm_lang('update_category'),
	'add_new_item'=>hm_lang('add_new_category'),
	'new_item_name'=>hm_lang('new_category_name'),
	'parent_item'=>hm_lang('category_parent'),
	'no_parent'=>hm_lang('no_category_parent'),
	'search_items'=>hm_lang('search_category'),
	'popular_items'=>hm_lang('most_used_category'),
	'taxonomy_field'=>$field_array,
	'primary_name_field'=>$field_array['primary_name_field'],
);
register_taxonomy($args);


/**
 * Khởi tạo 1 content mặc định là "Bài viết" sử dụng kiểu taxonomy là "Danh mục bài viết" 
 * đã khởi tạo ở trên, vì ở trên taxonomy "Danh mục bài viết" đã đăng ký content_key là "post"
 * nên dạng nội dung này sẽ có content_key là "post" để dùng được trong "Danh mục bài viết"
 */
 
$field_array=array(
	'primary_name_field'=>array(
		'nice_name'=>hm_lang('post_name'),
		'name'=>'name',
		'create_slug'=>TRUE,
		'input_type'=>'text',
		'default_value'=>'',
		'placeholder'=>hm_lang('post_title'),
		'required'=>TRUE,
	),
	array(
		'nice_name'=>hm_lang('post_description'),
		'description'=>hm_lang('briefly_describe_the_contents_of_this_post'),
		'name'=>'description',
		'input_type'=>'textarea',
		'default_value'=>'',
		'placeholder'=>'',
		'required'=>FALSE,
	),
	array(
		'nice_name'=>hm_lang('post_content'),
		'name'=>'content',
		'input_type'=>'wysiwyg',
		'default_value'=>'',
		'placeholder'=>'',
		'required'=>FALSE,
	),
);
$args=array(
	'content_name'=>hm_lang('post'),
	'taxonomy_key'=>'category',
	'content_key'=>'post',
	'all_items'=>hm_lang('all_post'),
	'edit_item'=>hm_lang('edit_post'),
	'view_item'=>hm_lang('view_post'),
	'update_item'=>hm_lang('update_post'),
	'add_new_item'=>hm_lang('add_new_post'),
	'new_item_name'=>hm_lang('new_post_name'),
	'chapter'=>FALSE,
	'search_items'=>hm_lang('search_post'),
	'content_field'=>$field_array,
	'primary_name_field'=>$field_array['primary_name_field'],
);
register_content($args);



/**
 * Trong quản trị ngoài việc khai báo các trường bắt buộc cho thành viên như
 * tên đăng nhập, mật khẩu ... Bạn có thể bổ sung thêm các trường cần cho website của bạn
 * như skype, email, số điện thoại ... như dưới đây
 */

$args=array(
	array(
		'nice_name'=>hm_lang('real_name'),
		'name'=>'name',
		'input_type'=>'text',
		'default_value'=>'',
		'placeholder'=>hm_lang('name_of_user'),
		'required'=>FALSE,
	),
	array(
		'nice_name'=>hm_lang('skype'),
		'name'=>'skype',
		'input_type'=>'text',
		'default_value'=>'',
		'placeholder'=>hm_lang('skype_of_user'),
		'required'=>FALSE,
	),
	array(
		'nice_name'=>hm_lang('facebook'),
		'name'=>'facebook',
		'input_type'=>'text',
		'default_value'=>'',
		'placeholder'=>hm_lang('facebook_of_user'),
		'required'=>FALSE,
	),
	array(
		'nice_name'=>hm_lang('phone_number'),
		'name'=>'phone',
		'input_type'=>'text',
		'default_value'=>'',
		'placeholder'=>hm_lang('enter_your_phone_number'),
		'required'=>FALSE,
	),
	array(
		'nice_name'=>hm_lang('personal_information'),
		'name'=>'bio',
		'input_type'=>'textarea',
		'default_value'=>'',
		'placeholder'=>hm_lang('introduce_yourself'),
		'required'=>FALSE,
	),
);

register_user_field($args);

if(SYSTEM_DASHBOARD==TRUE){

	/**
	 * Dashboard box bài viết mới từ trang chủ
	 */
	 
	$args = array(
		'width'=>'4',
		'function'=>'hm_newsfeed',
		'label'=>hm_lang('new_post'),
	);
	register_dashboard_box($args);

	function hm_newsfeed(){
		$server=HM_API_SERVER.'/api/news/json';
		@$data = file_get_contents($server);
		$data = json_decode($data);
		if(is_array($data)){
		echo '<ul class="dashboard_box_list">';
		foreach($data as $item){
			$name = $item->name;
			$link = $item->link;
			echo '<li><a href="'.$link.'" target="_blank">'.$name.'</a></li>';
		}
		echo '</ul>';
		}
	}

	/**
	 * Dashboard box giao diện mới từ trang chủ
	 */
	 
	$args = array(
		'width'=>'4',
		'function'=>'hm_newthemes',
		'label'=>hm_lang('new_theme'),
	);
	register_dashboard_box($args);

	function hm_newthemes(){
		$server=HM_API_SERVER.'/api/themes/json';
		@$data = file_get_contents($server);
		$data = json_decode($data);
		if(is_array($data)){
		echo '<ul class="dashboard_box_list">';
		foreach($data as $item){
			$name = $item->name;
			$link = $item->link;
			echo '<li><a href="'.$link.'" target="_blank">'.$name.'</a></li>';
		}
		echo '</ul>';
		}
	}

	/**
	 * Dashboard box plugin mới từ trang chủ
	 */
	 
	$args = array(
		'width'=>'4',
		'function'=>'hm_newplugins',
		'label'=>hm_lang('new_plugin'),
	);
	register_dashboard_box($args);

	function hm_newplugins(){
		$server=HM_API_SERVER.'/api/plugins/json';
		@$data = file_get_contents($server);
		$data = json_decode($data);
		if(is_array($data)){
		echo '<ul class="dashboard_box_list">';
		foreach($data as $item){
			$name = $item->name;
			$link = $item->link;
			echo '<li><a href="'.$link.'" target="_blank">'.$name.'</a></li>';
		}
		echo '</ul>';
		}
	}

}


/**
 * Block text mặc định 
 */
function HmBlockText($block_id){
	echo get_blo_val(array('name'=>'content','id'=>$block_id));
}
 
$args = array(
			'name'			=> 	'HmBlockText',
			'nice_name' 	=> 	hm_lang('text'),
			'input'			=> 	array(
									array(
										'nice_name'=>hm_lang('content'),
										'description'=>hm_lang('you_can_use_html'),
										'name'=>'content',
										'input_type'=>'textarea',
										'required'=>FALSE,
									),
								),
			'function'	=> 	'HmBlockText',
		);
register_block($args);

/**
 * Block image mặc định 
 */
function HmBlockImage($block_id){
	$image = get_blo_val(array('name'=>'image','id'=>$block_id));
	$link = get_blo_val(array('name'=>'link','id'=>$block_id));
	if(is_numeric($link)){
		$data_uri = get_uri_data("id=$link");
		$href = SITE_URL.FOLDER_PATH.$data_uri->uri;
	}else{
		$href = $link;
	}
	if($link!=''){
		echo '<a href="'.$href.'">'."\n\r";
	}
		echo img($image);
	if($link!=''){
		echo '</a>'."\n\r";
	}
}

$args = array(
			'name'			=> 	'HmBlockImage',
			'nice_name' 	=> 	hm_lang('image'),
			'input'			=> 	array(
									array(
										'nice_name'=>hm_lang('image'),
										'name'=>'image',
										'input_type'=>'image',
										'required'=>FALSE,
									),
									array(
										'nice_name'=>hm_lang('permalink'),
										'description'=>hm_lang('link_when_clicking_on_the_photo_if_available'),
										'name'=>'link',
										'input_type'=>'request_uri',
										'required'=>FALSE,
									),
								),
			'function'	=> 	'HmBlockImage',
		);
register_block($args);


/**
 * Block menu mặc định 
 */
function HmBlockMenu($block_id){
	$menu = get_blo_val(array('name'=>'menu','id'=>$block_id));
	echo get_menu($menu);
}

$args = array(
			'name'			=> 	'HmBlockMenu',
			'nice_name' 	=> 	hm_lang('menu'),
			'input'			=> 	array(
									array(
										'nice_name'=>hm_lang('select_menu'),
										'name'=>'menu',
										'input_type'=>'menu',
										'required'=>FALSE,
									),
								),
			'function'	=> 	'HmBlockMenu',
		);
register_block($args);


/**
 * Đăng ký link ảnh captcha
*/

register_request('captcha.jpg','hm_create_captcha');
function hm_create_captcha(){
	global $hmcaptcha;
	$hmcaptcha->CreateImage();
	exit();
}

/**
 * shortcode hiển thị menu
*/
$args = array(
			'name'=>'menu',
			'func'=>'hm_shortcode_menu',
		);
register_shortcode($args);
function hm_shortcode_menu($args){
	if(isset($args['menu_id'])){
		$menu_id = $args['menu_id'];
		$args['name'] = get_menu_name($menu_id);
		$args['parent'] = $menu_id;
		if(is_numeric($menu_id)){
			echo get_menu($args);
		}
	}
}


hook_action('after_web_setup');
?>