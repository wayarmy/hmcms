<?php
/**
 * Tệp tin chứa những hàm cơ bản
 * Vị trí : hm_include/functions.php
 */

if ( ! defined('BASEPATH')) exit('403');

/** load class */
$hmdiff = new Diff();
$hmsecurity = new Security();
$hmcaptcha = new SimpleCaptcha();

if (!function_exists('_')) {
    function _($str) {
        return $str;
    }
}
function hm_lang($str){
	global $hmlang;
	if(!is_array($hmlang)){
		require_once(BASEPATH.HM_FRONTENT_DIR.'/languages/'.LANG.'.php');
	}
	if(isset($hmlang[$str])){
		return $hmlang[$str];
	}else{
		return '{'.$str.'}';
	}
}
function hm_get($parameter,$default = NULL){

	if(isset($_GET[$parameter])){
		return $_GET[$parameter];
	}else{
		return $default;
	}

}

function hm_post($parameter,$default = NULL,$nullString = TRUE){

	if(isset($_POST[$parameter])){
		if($_POST[$parameter] == ''){
			if($nullString){
				return $_POST[$parameter];
			}else{
				return $default;
			}
		}else{
			if(is_array($_POST[$parameter])){
				return $_POST[$parameter];
			}else{
				return $_POST[$parameter];
			}
		}
	}else{
		return $default;
	}

}

function hm_exit($str=NULL){

	@header('Content-Type: text/html; charset=utf-8');

	?>
		<head>
			<meta charset="utf-8">
			<title>Lỗi xử lý</title>
			<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
		</head>
		<body>
			<style>
				body {
				  margin: 0px;
				  padding: 0px;
				  font-family: Arial;
				}
				.error_content {
				  width: 100%;
				  height: 100%;
				  position: relative;
				}
				p.error {
				  width: 800px;
				  position: absolute;
				  top: 0px;
				  left: 0px;
				  right: 0px;
				  bottom: 0px;
				  margin: auto;
				  height: 36px;
				  line-height: 36px;
				  text-align: center;
				  background: #e14d43;
				  font-family: Arial;
				  color: #ffffff;
				  font-size: 14px;
				  padding: 10px 20px 10px 20px;
				  text-decoration: none;
				  border-radius: 2px;
				}
			</style>
			<div class="error_content">
				<p class="error"><?php echo $str; ?></p>
			</div>
		</body>
	<?php

	exit();

}

function hm_redirect($to=BASE_URL,$time=0){
	echo '<meta http-equiv="refresh" content="'.$time.';'.$to.'">';
}

function hm_ip(){

	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return $ip;

}

function hm_agent(){

	if (!empty($_SERVER['HTTP_USER_AGENT'])) {
		$agent = $_SERVER['HTTP_USER_AGENT'];
	}
	return $agent;

}

function hm_parse_args( $args, $defaults = '' ) {
	if( is_object( $args ) ){
		$r = get_object_vars( $args );
	}elseif( is_array( $args ) ){
		$r =& $args;
	}else{
		hm_parse_args( $args, $r );
	}
	if ( is_array( $defaults ) ){
		return array_merge( $defaults, $r );
	}
	return $r;
}

function hm_include($file,$data=NULL){
	if(is_array($data)){
		foreach($data as $key => $val){
			$$key  = $val;
		}
	}
	if(file_exists($file)){
		include ($file);
	}

}

function hm_encode($str=NULL,$key=ENCRYPTION_KEY){

	global $hmsecurity;

	$encoded = $hmsecurity->encrypt($str, $key);

	return $encoded;

}

function hm_decode($str=NULL,$key=ENCRYPTION_KEY){

	global $hmsecurity;

	$decoded = $hmsecurity->decrypt($str, $key);

	return $decoded;

}

function hm_encode_str($str=NULL,$key=ENCRYPTION_KEY){

	if(is_array($str)){$str = hm_json_encode($str);}
	$encoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $str, MCRYPT_MODE_CBC, md5(md5($key))));
	return $encoded;

}

function hm_decode_str($str=NULL,$key=ENCRYPTION_KEY){

	$decoded = rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5($key), base64_decode($str), MCRYPT_MODE_CBC, md5(md5($key))), "\0");
	return $decoded;

}

function json_string_encode($str) {
	$str = str_replace(",","\,",$str);
	$str = str_replace(":","\:",$str);
	return $str;
}

function input_parse_arg($args){
	$default_array = 	array(
				'name' => FALSE,
				'object_id' => '0',
				'nice_name' => '',
				'object_type' => '',
				'description' => '',
				'placeholder' => '',
				'default_value' => '',
				'addClass' => FALSE,
				'class' => 'form-control',
				'addAttr' => 'autocomplete="off"',
				'input_type' => 'text',
				'create_slug' => FALSE,
				'required' => FALSE,
				'handle' => TRUE,
				'wrapper' => TRUE,
				'input_option' => array(),
			);
	return hm_parse_args($args,$default_array);
}

function build_input_form($field_array){

	hook_action('build_input_form');
	$field_array = hook_filter('build_input_form_args',$field_array);

	if($field_array['name']){

		if( isset($field_array['required']) AND ($field_array['required']==TRUE) ) $field_array['required']='required';

		switch ($field_array['input_type']) {

			case 'text':

				input_text($field_array);

			break;

			case 'email':

				input_email($field_array);

			break;

			case 'hidden':

				input_hidden($field_array);

			break;

			case 'request_uri':

				input_request_uri($field_array);

			break;

			case 'number':

				input_number($field_array);

			break;

			case 'password':

				input_password($field_array);

			break;

			case 'textarea':

				input_textarea($field_array);

			break;

			case 'wysiwyg':
			case 'editor':

				echo input_editor($field_array);

			break;

			case 'select':

				input_select($field_array);

			break;

			case 'radio':

				input_radio($field_array);

			break;

			case 'checkbox':

				input_checkbox($field_array);

			break;

			case 'multiimage':

				input_multiimage($field_array);

			break;

			case 'image':

				input_image($field_array);

			break;

			case 'file':

				input_file($field_array);

			break;

			case 'content':

				$field_array['data_type'] = 'content';
				input_data($field_array);

			break;

			case 'taxonomy':

				$field_array['data_type'] = 'taxonomy';
				input_data($field_array);

			break;

			case 'menu':

				input_menu($field_array);

			break;

			case 'taxonomy_select':

				input_taxonomy_select($field_array);

			break;

			case 'taxonomy_checkbox':

				input_taxonomy_checkbox($field_array);

			break;

			case 'captcha':

				input_captcha($field_array);

			break;

		}
	}

}

function form_input_order($func){

	$form_input_order = array();
	if(isset($_SESSION['form_input_order'])){
		$form_input_order = $_SESSION['form_input_order'];
	}
	if(isset($form_input_order[$func])){
		return $form_input_order[$func];
	}else{
		return 0;
	}

}

function input_text($field_array=array()){

	hook_action('input_text');

	$field_array=input_parse_arg($field_array);
	if($field_array['create_slug']==TRUE){

		$data_slug='slug-input="slug_of_'.$field_array['name'].'" slug-accented="accented_of_'.$field_array['name'].'" object-id="'.$field_array['object_id'].'"';
		$addClass=' input_have_slug field_'.$field_array['object_id'].' '.$field_array['addClass'];

	}else{

		$hav_slug='';
		$addClass=' '.$field_array['addClass'];
		$data_slug='';

	}

	$addAttr=' '.$field_array['addAttr'];
	if($field_array['wrapper']){
	echo '<div class="form-group hm-form-group" data-input-name="'.$field_array['name'].'" data-order="'.form_input_order($field_array['name']).'">'."\n";
	}
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<input'.$addAttr.' '.$data_slug.' '.$field_array['required'].' name="'.$field_array['name'].'" type="text" class="'.$field_array['class'].' '.$addClass.'" id="'.$field_array['name'].'" placeholder="'.$field_array['placeholder'].'" value="'.str_replace('"','&quot;',$field_array['default_value']).'">'."\n";


	if($field_array['create_slug']==TRUE){

		build_input_slug($field_array);

	}
	if($field_array['wrapper']){
	echo '</div>'."\n";
	}
}


function input_email($field_array=array()){

	hook_action('input_email');

	$field_array=input_parse_arg($field_array);

	$addClass=' '.$field_array['addClass'];
	$addAttr=' '.$field_array['addAttr'];
	if($field_array['wrapper']){
	echo '<div class="form-group hm-form-group" data-input-name="'.$field_array['name'].'" data-order="'.form_input_order($field_array['name']).'">'."\n";
	}
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<input'.$addAttr.' '.$field_array['required'].' name="'.$field_array['name'].'" type="email" class="'.$field_array['class'].' '.$addClass.'" id="'.$field_array['name'].'" placeholder="'.$field_array['placeholder'].'" value="'.str_replace('"','&quot;',$field_array['default_value']).'">'."\n";
	if($field_array['wrapper']){
	echo '</div>'."\n";
	}
}


function input_request_uri($field_array=array()){

	hook_action('input_request_uri');

	$field_array=input_parse_arg($field_array);

	$addClass=' '.$field_array['addClass'];
	$addAttr=' '.$field_array['addAttr'];

	if(isset($field_array['default_value'])){

		$default_value = $field_array['default_value'];
		if(is_numeric($default_value)){
			$data_uri = get_uri_data("id=$default_value");
			$field_array['placeholder'] = BASE_URL.$data_uri->uri;
		}else{
			$field_array['placeholder'] = $default_value;
		}

	}
	if($field_array['wrapper']){
	echo '<div class="form-group hm-form-group" data-input-name="'.$field_array['name'].'" data-order="'.form_input_order($field_array['name']).'">'."\n";
	}
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<input'.$addAttr.' '.$field_array['required'].' type="text" class="'.$field_array['class'].' request_uri'.$addClass.'" data-input="'.$field_array['name'].'" placeholder="'.$field_array['placeholder'].'" >'."\n";
	echo '	<input '.$field_array['required'].' name="'.$field_array['name'].'" id="'.$field_array['name'].'" type="hidden" class="'.$field_array['class'].' request_uri" value="'.str_replace('"','&quot;',$field_array['default_value']).'">'."\n";
	echo '  <ul class="auto_suggest_result auto_suggest_of_'.$field_array['name'].'"></ul>';
	if($field_array['wrapper']){
	echo '</div>'."\n";
	}
}

function input_data($field_array=array()){

	hook_action('input_data');

	$field_array=input_parse_arg($field_array);

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	global $hmtaxonomy;
	global $hmcontent;

	$addClass=' '.$field_array['addClass'];
	$addAttr=' '.$field_array['addAttr'];
	if(!is_array($field_array['data_key'])){
		$field_array['data_key'] = array();
	}
	$data_key = implode(',',$field_array['data_key']);
	$default_value = $field_array['default_value'];
	$data_type = $field_array['data_type'];
	if($field_array['wrapper']){
	echo '<div class="form-group hm-form-group" data-input-name="'.$field_array['name'].'" data-order="'.form_input_order($field_array['name']).'">'."\n";
	}
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<input'.$addAttr.' '.$field_array['required'].' type="text" class="'.$field_array['class'].' suggest_data'.$addClass.'" data-type="'.$data_type.'" data-key="'.$data_key.'" data-input="'.$field_array['name'].'" placeholder="'.$field_array['placeholder'].'" >'."\n";
	echo '	<input '.$field_array['required'].' value="'.$default_value.'" name="'.$field_array['name'].'" id="'.$field_array['name'].'" type="hidden" class="'.$field_array['class'].' suggest_data" >'."\n";
	echo '  <ul class="auto_suggest_data_choiced auto_suggest_choiced_of_'.$field_array['name'].'" style="display:block">'."\n";

	/** default value */
		$where_in = explode(',',$default_value);
		$where_in = implode("','",$where_in);
		$data_type = $field_array['data_type'];
		switch ($data_type) {
			case 'taxonomy':
				$tableName=DB_PREFIX.'taxonomy';
			break;
			case 'content':
				$tableName=DB_PREFIX.'content';
			break;
			default:
				$tableName=DB_PREFIX.'request_uri';
		}
		$hmdb->Query("SELECT * FROM `".$tableName."` WHERE `id` IN ('".$where_in."') ORDER BY FIELD(id,".$default_value.")");

		$return='';
		switch ($data_type) {
			case 'taxonomy':
				while($row = $hmdb->Row()){

					$id = $row->id;
					$tax_data = taxonomy_data_by_id($id);
					$tax_key = $tax_data['taxonomy']->key;
					$taxonomy = $hmtaxonomy->hmtaxonomy;
					$suggest_label = $taxonomy[$tax_key]['taxonomy_name'];
					$object_id = $id;
					$object_name = get_tax_val('name=name&id='.$id);
					$object_type = 'taxonomy';
					if( $tax_data['taxonomy']->parent > 0 ){
						$taxonomy_parent = taxonomy_parent_breadcrumb($tax_data['taxonomy']->id);
						krsort($taxonomy_parent);
						$object_name = array();
						foreach($taxonomy_parent as $taxonomy){
							$object_name[] = $taxonomy['name'];
						}
						$object_name = implode("&nbsp;&raquo;&nbsp;",$object_name);
					}else{
						$object_name = get_tax_val('name=name&id='.$id);
					}
					$return.='<li data-id="'.$id.'" data-input="'.$field_array['name'].'" data-name="'.$object_name.'" object_id="'.$object_id.'" object_type="'.$object_type.'" class="data_item_choiced">';
					$return.='<span class="suggest_label">'.$suggest_label.': </span><b>'.$object_name.'</b>';
					$return.='<span class="ajax-del-input-data" data-id="'.$id.'" data-input="'.$field_array['name'].'" data-name="'.$object_name.'" object_id="'.$object_id.'" object_type="'.$object_type.'">xóa</span>';
					$return.='</li>';
				}
			break;
			case 'content':
				while($row = $hmdb->Row()){
					$id = $row->id;
					$con_data = content_data_by_id($id);
					$con_key = $con_data['content']->key;
					$content = $hmcontent->hmcontent;
					$suggest_label = $content[$con_key]['content_name'];
					$object_id = $id;
					$object_name = get_con_val('name=name&id='.$id);
					$object_type = 'content';
					$return.='<li data-id="'.$id.'" data-input="'.$field_array['name'].'" data-name="'.$object_name.'" object_id="'.$object_id.'" object_type="'.$object_type.'" class="data_item_choiced">';
					$return.='<span class="suggest_label">'.$suggest_label.': </span><b>'.$object_name.'</b>';
					$return.='<span class="ajax-del-input-data" data-id="'.$id.'" data-input="'.$field_array['name'].'" data-name="'.$object_name.'" object_id="'.$object_id.'" object_type="'.$object_type.'">xóa</span>';
					$return.='</li>';
				}
			break;
			default:
				while($row = $hmdb->Row()){
					$id = $row->id;
					$object_id = $row->object_id;
					$object_type = $row->object_type;
					$uri = $row->uri;

					$suggest_label = '';
					$object_name = '';
					switch ($object_type) {
						case 'taxonomy':
							$tax_data = taxonomy_data_by_id($object_id);
							$tax_key = $tax_data['taxonomy']->key;
							$taxonomy = $hmtaxonomy->hmtaxonomy;
							$suggest_label = $taxonomy[$tax_key]['taxonomy_name'];
							if( $tax_data['taxonomy']->parent > 0 ){
								$taxonomy_parent = taxonomy_parent_breadcrumb($tax_data['taxonomy']->id);
								krsort($taxonomy_parent);
								$object_name = array();
								foreach($taxonomy_parent as $taxonomy){
									$object_name[] = $taxonomy['name'];
								}
								$object_name = implode("&nbsp;&raquo;&nbsp;",$object_name);
							}else{
								$object_name = get_tax_val('name=name&id='.$object_id);
							}
						break;
						case 'content':
							$con_data = content_data_by_id($object_id);
							$con_key = $con_data['content']->key;
							$content = $hmcontent->hmcontent;
							$suggest_label = $content[$con_key]['content_name'];
							$object_name = get_con_val('name=name&id='.$object_id);
						break;
					}

					$return.='<li data-id="'.$id.'" data-input="'.$field_array['name'].'" data-name="'.$object_name.'" object_id="'.$object_id.'" object_type="'.$object_type.'" class="data_item_choiced">';
					$return.='<span class="suggest_label">'.$suggest_label.': </span><b>'.$object_name.'</b>';
					$return.='<span class="ajax-del-input-data" data-id="'.$id.'" data-input="'.$field_array['name'].'" data-name="'.$object_name.'" object_id="'.$object_id.'" object_type="'.$object_type.'">xóa</span>';
					$return.='</li>';
				}
		}
		echo $return;
	echo '  </ul>'."\n";
	echo '  <ul class="auto_suggest_data_result  auto_suggest_of_'.$field_array['name'].'"></ul>'."\n";
	if($field_array['wrapper']){
	echo '</div>'."\n";
	}
}

function input_hidden($field_array=array()){

	hook_action('input_hidden');

	$field_array=input_parse_arg($field_array);

	$addClass=' '.$field_array['addClass'];
	$addAttr=' '.$field_array['addAttr'];
	echo '	<input'.$addAttr.' '.$field_array['required'].' name="'.$field_array['name'].'" type="hidden" class="'.$field_array['class'].' '.$addClass.'" id="'.$field_array['name'].'" placeholder="'.$field_array['placeholder'].'" value="'.$field_array['default_value'].'">'."\n";

}

function input_number($field_array=array()){

	hook_action('input_number');

	$field_array=input_parse_arg($field_array);

	$addClass=' '.$field_array['addClass'];
	$addAttr=' '.$field_array['addAttr'];
	if($field_array['wrapper']){
	echo '<div class="form-group hm-form-group" data-input-name="'.$field_array['name'].'" data-order="'.form_input_order($field_array['name']).'">'."\n";
	}
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<input'.$addAttr.' '.$field_array['required'].' name="'.$field_array['name'].'" type="number" class="'.$field_array['class'].' '.$addClass.'" id="'.$field_array['name'].'" placeholder="'.$field_array['placeholder'].'" value="'.$field_array['default_value'].'">'."\n";
	if($field_array['wrapper']){
	echo '</div>'."\n";
	}
}

function input_password($field_array=array()){

	hook_action('input_password');

	$field_array=input_parse_arg($field_array);

	$addClass=' '.$field_array['addClass'];
	$addAttr=' '.$field_array['addAttr'];
	if($field_array['wrapper']){
	echo '<div class="form-group hm-form-group" data-input-name="'.$field_array['name'].'" data-order="'.form_input_order($field_array['name']).'">'."\n";
	}
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<input'.$addAttr.' '.$field_array['required'].' name="'.$field_array['name'].'" type="password" class="'.$field_array['class'].' '.$addClass.'" id="'.$field_array['name'].'" placeholder="'.$field_array['placeholder'].'" value="'.$field_array['default_value'].'">'."\n";
	if($field_array['wrapper']){
	echo '</div>'."\n";
	}
}

function input_textarea($field_array=array()){

	hook_action('input_textarea');

	$field_array=input_parse_arg($field_array);

	$addClass=' '.$field_array['addClass'];
	$addAttr=' '.$field_array['addAttr'];
	if($field_array['wrapper']){
	echo '<div class="form-group hm-form-group" data-input-name="'.$field_array['name'].'" data-order="'.form_input_order($field_array['name']).'">'."\n";
	}
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '	<textarea'.$addAttr.' '.$field_array['required'].' name="'.$field_array['name'].'" class="'.$field_array['class'].' '.$addClass.'" id="'.$field_array['name'].'">'.$field_array['default_value'].'</textarea>'."\n";
	if($field_array['wrapper']){
	echo '</div>'."\n";
	}
}

function input_editor($field_array=array()){

	hook_action('input_editor');

	$field_array=input_parse_arg($field_array);

	$field_array = hook_filter('input_editor_input',$field_array);

	$return='';

	if( $field_array['addClass'] != NULL ){

		$addClass=$field_array['addClass'];

	}else{

		$addClass='wysiwyg';

	}

	$addAttr=' '.$field_array['addAttr'];

	$default_value = $field_array['default_value'];
	$default_value = str_replace('&lt;','&amp;lt;',$default_value);
	$default_value = str_replace('&gt;','&amp;gt;',$default_value);
	$default_value = str_replace('<pre>','&lt;pre&gt;',$default_value);
	$default_value = str_replace('</pre>','&lt;/pre&gt;',$default_value);

	if($field_array['wrapper']){
	$return = $return.'<div class="form-group hm-form-group" data-input-name="'.$field_array['name'].'" data-order="'.form_input_order($field_array['name']).'">'."\n";
	}
	if($field_array['handle']){
	$return = $return.'	<div class="form-group-handle"></div>';
	}
	$return = $return.'	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	$return = $return.'	<button id="'.$field_array['name'].'" multi="false" imageonly="true" type="button" class="btn btn-default media_btn btn-xs" data-toggle="modal" data-target="#media_box_modal">'."\n";
	$return = $return.'		<span class="glyphicon glyphicon-picture"></span> '._('Thư viện')."\n";
	$return = $return.'	</button>'."\n";
	$return = $return.'	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	$return = $return.'	<textarea'.$addAttr.' '.$field_array['required'].' name="'.$field_array['name'].'" class="'.$addClass.'" id="'.$field_array['name'].'">'.$default_value.'</textarea>'."\n";
	if($field_array['wrapper']){
	$return = $return.'</div>'."\n";
	}
	$return = hook_filter('input_editor_output',$return);

	return $return;

}

function input_taxonomy_select($field_array=array()){

	$field_array=input_parse_arg($field_array);

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$field_array['input_option'] = array();
	$tableName=DB_PREFIX.'taxonomy';
	$data_key = implode("','",$field_array['data_key']);
	$hmdb->Query("SELECT * FROM `".$tableName."` WHERE `key` IN ('".$data_key."') AND `status` = 'public' ORDER BY `parent` ");

	if ($hmdb->HasRecords()) {

		if(sizeof($field_array['data_key']) == 1){

			while($row = $hmdb->Row()){

				$object_id = $row->id;
				if( $row->parent > 0 ){
					$taxonomy_parent = taxonomy_parent_breadcrumb($object_id);
					krsort($taxonomy_parent);
					$object_name = array();
					foreach($taxonomy_parent as $taxonomy){
						$object_name[] = $taxonomy['name'];
					}
					$object_name = implode("&nbsp;&raquo;&nbsp;",$object_name);
				}else{
					$object_name = $row->name;
				}

				$field_array['input_option'][] = array(
													'value'=>$object_id,
													'label'=>$object_name,
												);
			}

		}else{

			global $hmtaxonomy;
			foreach($field_array['data_key'] as $key){

				$all_tax = $hmtaxonomy->hmtaxonomy;
				$taxonomy_name  = $all_tax[$key]['taxonomy_name'];
				$hmdb->Query("SELECT * FROM `".$tableName."` WHERE `key` = '".$key."' AND `status` = 'public' ORDER BY `parent` ");
				if ($hmdb->HasRecords()) {

					while($row = $hmdb->Row()){

						$object_id = $row->id;
						if( $row->parent > 0 ){
							$taxonomy_parent = taxonomy_parent_breadcrumb($object_id);
							krsort($taxonomy_parent);
							$object_name = array();
							foreach($taxonomy_parent as $taxonomy){
								$object_name[] = $taxonomy['name'];
							}
							$object_name = implode("&nbsp;&raquo;&nbsp;",$object_name);
						}else{
							$object_name = $row->name;
						}

						$field_array['input_option'][$taxonomy_name][] = array(
															'value'=>$object_id,
															'label'=>$object_name,
														);
					}

				}

			}

		}
	}
	input_select($field_array);
}

function input_taxonomy_checkbox($field_array){

	$field_array=input_parse_arg($field_array);

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	$field_array['input_option'] = array();
	$tableName=DB_PREFIX.'taxonomy';
	$data_key = implode("','",$field_array['data_key']);
	$hmdb->Query("SELECT * FROM `".$tableName."` WHERE `key` IN ('".$data_key."') AND `status` = 'public' ORDER BY `parent` ");

	if ($hmdb->HasRecords()) {

		if(sizeof($field_array['data_key']) == 1){

			while($row = $hmdb->Row()){

				$object_id = $row->id;
				if( $row->parent > 0 ){
					$taxonomy_parent = taxonomy_parent_breadcrumb($object_id);
					krsort($taxonomy_parent);
					$object_name = array();
					foreach($taxonomy_parent as $taxonomy){
						$object_name[] = $taxonomy['name'];
					}
					$object_name = implode("&nbsp;&raquo;&nbsp;",$object_name);
				}else{
					$object_name = $row->name;
				}

				$field_array['input_option'][] = array(
													'value'=>$object_id,
													'label'=>$object_name,
												);
			}

		}else{

			global $hmtaxonomy;
			foreach($field_array['data_key'] as $key){

				$all_tax = $hmtaxonomy->hmtaxonomy;
				$taxonomy_name  = $all_tax[$key]['taxonomy_name'];
				$hmdb->Query("SELECT * FROM `".$tableName."` WHERE `key` = '".$key."' AND `status` = 'public' ORDER BY `parent` ");
				if ($hmdb->HasRecords()) {

					while($row = $hmdb->Row()){

						$object_id = $row->id;
						if( $row->parent > 0 ){
							$taxonomy_parent = taxonomy_parent_breadcrumb($object_id);
							krsort($taxonomy_parent);
							$object_name = array();
							foreach($taxonomy_parent as $taxonomy){
								$object_name[] = $taxonomy['name'];
							}
							$object_name = implode("&nbsp;&raquo;&nbsp;",$object_name);
						}else{
							$object_name = $row->name;
						}

						$field_array['input_option'][$taxonomy_name][] = array(
															'value'=>$object_id,
															'label'=>$object_name,
														);
					}

				}

			}

		}
	}
	input_checkbox($field_array);
}

function input_captcha($field_array=array()){

	$field_array=input_parse_arg($field_array);

	echo '<div class="hm_captacha_img">'."\n\r";
	echo '	<p><img src="'.BASE_URL.'captcha.jpg" id="hm_captacha_img" /></p>'."\n\r";
	echo '	<p><button type="button" onclick="
			document.getElementById(\'hm_captacha_img\').src=\''.BASE_URL.'captcha.jpg?\'+Math.random();
			document.getElementById(\'captcha-form\').focus();"
			id="change-image">'._('Đổi ảnh khác').'</button></p>';
	echo '	</div>'."\n\r";

	input_text($field_array);
}

function input_select($field_array=array()){

	hook_action('input_select');

	$field_array=input_parse_arg($field_array);

	$addClass=' '.$field_array['addClass'];
	$addAttr=' '.$field_array['addAttr'];
	if($field_array['wrapper']){
	echo '<div class="form-group hm-form-group" data-input-name="'.$field_array['name'].'" data-order="'.form_input_order($field_array['name']).'">'."\n";
	}
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	$input_deep = array_depth($field_array['input_option']);

	echo '	<select'.$addAttr.' name="'.$field_array['name'].'" class="'.$field_array['class'].' '.$addClass.'" >'."\n";
			foreach($field_array['input_option'] as $option_key => $option){

				if( $input_deep == 2 ){
					if( $option['value'] == $field_array['default_value'] ){
						$selected='selected="selected"';
					}else{
						$selected='';
					}
					echo '<option '.$selected.' value="'.$option['value'].'">'.$option['label'].'</option>'."\n";
				}elseif( $input_deep == 3 ){

					echo '<optgroup label="'.$option_key.'">'."\n";
					foreach($option as $option_val){
						if( $option_val['value'] == $field_array['default_value'] ){
							$selected='selected="selected"';
						}else{
							$selected='';
						}
						echo '<option '.$selected.' value="'.$option_val['value'].'">'.$option_val['label'].'</option>'."\n";
					}
					echo '</optgroup>'."\n";

				}

			}
	echo '	</select>'."\n";
	if($field_array['wrapper']){
	echo '</div>'."\n";
	}
}

function input_radio($field_array=array()){

	hook_action('input_radio');

	$field_array=input_parse_arg($field_array);

	$addClass=' '.$field_array['addClass'];
	$addAttr=' '.$field_array['addAttr'];
	if($field_array['wrapper']){
	echo '<div class="form-group hm-form-group" data-input-name="'.$field_array['name'].'" data-order="'.form_input_order($field_array['name']).'">'."\n";
	}
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}
	echo '<ul class="taxnomy_list tree">'."\n";
	foreach($field_array['input_option'] as $option){
		if( $option['value'] == $field_array['default_value'] ){
			$checked='checked="checked"';
		}else{
			$checked='';
		}
		echo '<li><input '.$checked.' value="'.$option['value'].'" '.$addAttr.' name="'.$field_array['name'].'" class="'.$addClass.'" type="radio"><span>'.$option['label'].'</span></li>'."\n";
	}
	echo '</ul>'."\n";
	if($field_array['wrapper']){
	echo '</div>'."\n";
	}
}


function input_checkbox($field_array=array()){
	hook_action('input_checkbox');

	$field_array=input_parse_arg($field_array);

	$addClass=' '.$field_array['addClass'];
	$addAttr=' '.$field_array['addAttr'];

	if(is_array(json_decode($field_array['default_value']))){
		$field_array['default_value'] = json_decode($field_array['default_value']);
	}

	if($field_array['wrapper']){
	echo '<div class="form-group hm-form-group" data-input-name="'.$field_array['name'].'" data-order="'.form_input_order($field_array['name']).'">'."\n";
	}
	if($field_array['handle']){
	echo '	<div class="form-group-handle"></div>';
	}
	if($field_array['nice_name']!=''){
		echo '	<label for="'.$field_array['name'].'">'.$field_array['nice_name'].'</label>'."\n";
	}
	if($field_array['description']!=''){
		echo '	<p class="input_description">'.$field_array['description'].'</p>'."\n";
	}

	if(is_array($field_array['input_option'][0])){

		echo '<ul class="taxnomy_list tree">'."\n";
		foreach($field_array['input_option'] as $option){

			$checked='';
			if(is_array($field_array['default_value'])){
				if(in_array($option['value'],$field_array['default_value'])){
					$checked='checked="checked"';
				}
			}else{
				if( $option['value'] == $field_array['default_value'] ){
					$checked='checked="checked"';
				}
			}

			echo '<li><input '.$checked.' value="'.$option['value'].'" '.$addAttr.' name="'.$field_array['name'].'[]" class="'.$addClass.'" type="checkbox"><span>'.$option['label'].'</span></li>'."\n";
		}
		echo '</ul>'."\n";

	}else{

		$checked='';
		if(is_array($field_array['default_value'])){
			if(in_array($field_array['input_option']['value'],$field_array['default_value'])){
				$checked='checked="checked"';
			}
		}else{
			if( $field_array['input_option']['value'] == $field_array['default_value'] ){
				$checked='checked="checked"';
			}
		}

		echo '<input '.$checked.' value="'.$field_array['input_option']['value'].'" '.$addAttr.' name="'.$field_array['name'].'" class="'.$addClass.'" type="checkbox"><span>'.$field_array['input_option']['label'].'</span>'."\n";

	}

	if($field_array['wrapper']){
	echo '</div>'."\n";
	}
}

function media_file_input($args=NULL){

	$args=input_parse_arg($args);
	if(is_array($args)){

		@$id=$args['name'];
		@$label=$args['label'];
		@$default_value=$args['default_value'];
		@$multi=$args['multi'];
		@$imageonly=$args['imageonly'];
		if($label==''){$label=_('Chọn tệp tin');}


		$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);

		$tableName=DB_PREFIX.'media';
		$hmdb->Query("SELECT * FROM `$tableName` WHERE `id` IN($default_value) ORDER BY FIELD(id, $default_value)");

		if ($hmdb->HasRecords()) {

			$thumbnail_src = array();

			while($row = $hmdb->Row()){

				$file_id = $row->id;
				$file_info = $row->file_info;
				$file_name = $row->file_name;
				$file_folder = $row->file_folder;
        $media_group_id = $row->media_group_id;
				if($file_folder!='/'){
					$file_folder_part = '/'.get_media_group_part($media_group_id).'/';
				}else{
					$file_folder_part = '/';
				}
				$file_info = json_decode($file_info,TRUE);
				if($file_info['file_is_image']==TRUE){
					$thumbnail_src[$file_id] = BASE_URL.HM_CONTENT_DIR.'/uploads'.$file_folder_part.$file_info['thumbnail'];
				}else{
					$file_src_name_ext = strtolower ($file_info['file_src_name_ext']);
					$file_ext_icon = './'.HM_CONTENT_DIR.'/icon/fileext/'.$file_src_name_ext.'.png';
					if( file_exists($file_ext_icon) ){
						$thumbnail_src[$file_id] = BASE_URL.HM_CONTENT_DIR.'/icon/fileext/'.$file_src_name_ext.'.png';
					}else{
						$thumbnail_src[$file_id] = BASE_URL.HM_CONTENT_DIR.'/icon/fileext/blank.png';
					}
				}

			}

		}else{
			$thumbnail_src = NULL;
		}

		if($multi==TRUE){
			$multi_class=' preview_media_file_multi';
			$multi_data='multi="true"';
		}else{
			$multi_class = '';
			$multi_data='multi="false"';
		}

		if($imageonly==TRUE){
			$imageonly_class=' preview_media_file_imageonly';
			$imageonly_data='imageonly="true"';
		}else{
			$imageonly_data='imageonly="false"';
			$imageonly_class='';
		}
		if($args['wrapper']){
		echo '<div class="form-group hm-form-group" data-input-name="'.$args['name'].'" data-order="'.form_input_order($args['name']).'">'."\n";
		}
		if(isset($args['handle']) AND $args['handle'] == TRUE){
			echo '	<div class="form-group-handle"></div>';
		}
		if(isset($args['nice_name']) AND $args['nice_name']!=''){
			echo '	<label for="'.$args['name'].'">'.$args['nice_name'].'</label>'."\n";
		}
		if(isset($args['description']) AND $args['description']!=''){
			echo '	<p class="input_description">'.$args['description'].'</p>'."\n";
		}
		echo '<div class="preview_media_file'.$multi_class.' '.$imageonly_class.'" use_media_file="'.$id.'">'."\n";
			if(is_array($thumbnail_src)){
				foreach($thumbnail_src as $file_id => $thumb){
					echo '<div class="preview_media_file_wapper" file-id="'.$file_id.'" use_media_file="'.$id.'">'."\n";
					echo '<div class="preview_media_file_remove" file-id="'.$file_id.'" use_media_file="'.$id.'"><i class="fa fa-remove"></i></div>'."\n";
					echo '<img file-id="'.$file_id.'" src="'.$thumb.'" />'."\n";
					echo '</div>'."\n";
				}
			}
		echo '</div>'."\n";
		echo '<span id="'.$id.'" '.$multi_data.' '.$imageonly_data.' class="use_media_file btn btn-default btn-xs" data-toggle="modal" data-target="#media_box_modal">'."\n";
		echo 	_($label);
		echo '</span>'."\n";
		echo '<input use_media_file="'.$id.'" type="hidden" name="'.$id.'" value="'.$default_value.'" />'."\n";
		if($args['wrapper']){
		echo '</div>'."\n";
		}
	}

}

function build_input_slug($args){

	$args = hook_filter('before_build_input_slug',$args);

	$val_name = $args['name'];
	$val_nicename = $args['nice_name'];
	$object_id = $args['object_id'];
	$object_type = $args['object_type'];
	hook_action('build_input_slug');

	$checked_true=NULL;
	$checked_false=NULL;
	$slug=NULL;
	if(is_numeric($object_id) AND $object_id!=0 ){

		if($object_type=='taxonomy'){
			$slug = get_tax_val(array('name'=>'slug_of_'.$val_name,'id'=>$object_id));
			$checked = get_tax_val(array('name'=>'accented_of_'.$val_name,'id'=>$object_id));
		}
		if($object_type=='content'){
			$slug = get_con_val(array('name'=>'slug_of_'.$val_name,'id'=>$object_id));
			$checked = get_con_val(array('name'=>'accented_of_'.$val_name,'id'=>$object_id));
		}

		if($checked=='true'){$checked_true='checked="checked"';}
		if($checked=='false'){$checked_false='checked="checked"';}
		if($checked=='html'){$checked_html='checked="checked"';}

	}

	echo '<div class="form-group-sub">'."\n";
	echo '	<label for="slug_of_'.$val_name.'">'.hm_lang('slug').' ('.hm_lang('automatic_generation_from').' '.$val_nicename.')</label>'."\n";
	echo '	<p class="input_description">'.hm_lang('slug_is_a_search_engine_friendly_path_that_will_automatically_create_if_you_leave_it_blank').'</p>'."\n";
	echo '	<input autocomplete="off" required value="'.$slug.'" name="slug_of_'.$val_name.'" type="text" class="form-control ajax_slug slug_of_'.$val_name.' slug_of_'.$val_name.'_'.$object_id.'" id="slug_of_'.$val_name.'" >'."\n";
	echo '</div>'."\n";

	echo '<div class="form-group-sub">'."\n";
	echo '	<label class="radio-inline">'."\n";
	echo '		<input '.$checked_true.' type="radio" slug-input="slug_of_'.$val_name.'" data-field-name="'.$val_name.'" data-field-object="'.$object_id.'" class="accented accented_of_'.$val_name.'" name="accented_of_'.$val_name.'" value="true" >'.hm_lang('keep_accented').'</input>'."\n";
	echo '	</label>'."\n";
	echo '	<label class="radio-inline">'."\n";
	echo '		<input '.$checked_false.' type="radio" slug-input="slug_of_'.$val_name.'" data-field-name="'.$val_name.'" data-field-object="'.$object_id.'" class="accented accented_of_'.$val_name.'" name="accented_of_'.$val_name.'" value="false" >'.hm_lang('remove_accented').'</input>'."\n";
	echo '	</label>'."\n";
	echo '</div>'."\n";

}

function win8_loading($ball=8){

	echo '<div class="windows8_loading">';
		for($i=1;$i<=$ball;$i++){

			echo '<div class="wBall" id="wBall_'.$i.'">';
				echo '<div class="wInnerBall"></div>';
			echo '</div>';

		}
	echo '</div>';

}

function wave_loading(){

	echo '<div class="bubblingG">';
		for($i=1;$i<=3;$i++){

			echo '<span id="bubblingG_'.$i.'"></span>';

		}
	echo '</div>';

}

function input_time($field_array=array()){

	if(!isset($field_array['default_value'])) $field_array['default_value']='';


	$time = time();

	if(isset($field_array['default_value']['day'])){
		$day = $field_array['default_value']['day'];
	}else{
		$day = date('d',$time);
	}

	if(isset($field_array['default_value']['month'])){
		$month = $field_array['default_value']['month'];
	}else{
		$month = date('m',$time);
	}

	if(isset($field_array['default_value']['year'])){
		$year = $field_array['default_value']['year'];
	}else{
		$year = date('Y',$time);
	}

	if(isset($field_array['default_value']['hour'])){
		$hour = $field_array['default_value']['hour'];
	}else{
		$hour = date('H',$time);
	}

	if(isset($field_array['default_value']['minute'])){
		$minute = $field_array['default_value']['minute'];
	}else{
		$minute = date('i',$time);
	}

	$selected = '';

	echo '		<input type="text" name="day" id="day" value="'.$day.'" />';
	echo '		<select name="month" id="month" class="col-md-6">';
					if($month == '01') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="01" '.$selected.'>'.hm_lang('jan').'</option>';
					if($month == '02') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="02" '.$selected.'>'.hm_lang('feb').'</option>';
					if($month == '03') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="03" '.$selected.'>'.hm_lang('mar').'</option>';
					if($month == '04') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="04" '.$selected.'>'.hm_lang('apr').'</option>';
					if($month == '05') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="05" '.$selected.'>'.hm_lang('may').'</option>';
					if($month == '06') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="06" '.$selected.'>'.hm_lang('jun').'</option>';
					if($month == '07') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="07" '.$selected.'>'.hm_lang('jul').'</option>';
					if($month == '08') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="08" '.$selected.'>'.hm_lang('aug').'</option>';
					if($month == '09') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="09" '.$selected.'>'.hm_lang('sep').'</option>';
					if($month == '10') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="10" '.$selected.'>'.hm_lang('oct').'</option>';
					if($month == '11') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="11" '.$selected.'>'.hm_lang('nov').'</option>';
					if($month == '12') {$selected = 'selected';}else{$selected = '';};
	echo '			<option value="12" '.$selected.'>'.hm_lang('dec').'</option>';
	echo '		</select>';
	echo '		<input type="text" name="year" id="year" value="'.$year.'" />';
	echo '		<span>@</span>';
	echo '		<input type="text" name="hour" id="hour" value="'.$hour.'" />';
	echo '		<span> : </span>';
	echo '		<input type="text" name="minute" id="minute" value="'.$minute.'" />';


}

function input_multiimage($field_array=array()){

	$field_array['multi'] = TRUE;
	$field_array['imageonly'] = TRUE;
	media_file_input($field_array);

}

function input_image($field_array=array()){

	$field_array['imageonly'] = TRUE;
	media_file_input($field_array);

}

function input_file($field_array=array()){

	media_file_input($field_array);

}

function input_menu($field_array=array()){

	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);

	$tableName=DB_PREFIX.'object';
	$whereArray = array (
		'key'=>MySQL::SQLValue('menu'),
	);
	$hmdb->SelectRows($tableName, $whereArray);
	if ($hmdb->HasRecords()) {

		$option = array();
		$i=1;
		while($row = $hmdb->Row()){
			$option[$i]['value'] = $row->id;
			$option[$i]['label'] = $row->name;
			$i++;
		}
		$field_array['input_option'] = $option;
		input_select($field_array);

	}
}

function register_admin_setting_page($args=array()){

	hook_action('register_admin_setting_page');

	global $hmsetting_page;

	if(is_array($args)){
		$hmsetting_page[$args['key']] = $args;
	}

}

function register_admin_ajax_page($args=array()){

	hook_action('register_admin_ajax_page');

	global $hmajax_page;

	if(is_array($args)){
		$hmajax_page[$args['key']] = $args;
	}

}

function register_admin_page($args=array()){

	hook_action('register_admin_page');

	global $hmadmin_page;

	if(is_array($args)){
		$hmadmin_page[$args['key']] = $args;
	}

}

function get_admin_setting_page(){

	hook_action('get_admin_setting_page');

	global $hmsetting_page;

	return $hmsetting_page;

}

function get_admin_ajax_page(){

	hook_action('get_admin_ajax_page');

	global $hmajax_page;

	return $hmajax_page;

}

function get_admin_page(){

	hook_action('get_admin_page');

	global $hmadmin_page;

	return $hmadmin_page;

}

function hm_array_to_list( $key = '', $val = array(), $prefix = '' ){

	$return[] = $prefix.$key." : \n";

	if(is_array($val)){
		$prefix = $prefix.'-';
		foreach ($val as $sub_key => $sub_val){

			if( is_array($sub_val) ){

				$new_prefix =  $prefix.'-';
				$return[] = "	".hm_array_to_list($sub_key,$sub_val,$new_prefix);

			}else{

				$return[] = "	".$prefix.$sub_key." : ".$sub_val."\n";

			}

		}
	}

	return (implode('',$return));

}

/** Lấy user đang login */
function user_name(){

	$session_admin_login = $_SESSION['admin_login'];
	$json = hm_decode_str($session_admin_login);
	$array = json_decode($json,TRUE);
	return $array['user_login'];

}

/** Xóa thư mục và files bên trong */
function DeleteDir($path){
    if (is_dir($path) === true){
        $files = array_diff(scandir($path), array('.', '..'));
        foreach ($files as $file){
            DeleteDir(realpath($path) . '/' . $file);
        }
        return rmdir($path);
    }
    else if (is_file($path) === true){
        return unlink($path);
    }
    return false;
}

/** Kiểm tra tồn tại url */
function is_url_exist($url){
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if($code == 200){
		$status = true;
    }else{
		$status = false;
    }
    curl_close($ch);
    return $status;
}

/** Số chiều của mảng */
function array_depth(array $array) {
    $max_depth = 1;

    foreach ($array as $value) {
        if (is_array($value)) {
            $depth = array_depth($value) + 1;

            if ($depth > $max_depth) {
                $max_depth = $depth;
            }
        }
    }

    return $max_depth;
}

/** set field */
function hm_set_field($name,$value,$object_id,$object_type){

	if( is_numeric($object_id) AND $name!='' AND $value!='' AND $object_type!='' ){
		$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
		$tableName=DB_PREFIX.'field';

		$values["name"] = MySQL::SQLValue($name);
		$values["val"] = MySQL::SQLValue($value);
		$values["object_id"] = MySQL::SQLValue($object_id, MySQL::SQLVALUE_NUMBER);
		$values["object_type"] = MySQL::SQLValue($object_type);

		$whereArray = array (
							'object_id'=>MySQL::SQLValue($object_id, MySQL::SQLVALUE_NUMBER),
							'object_type'=>MySQL::SQLValue($object_type),
							'name'=>MySQL::SQLValue($name),
							);
		return $hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
	}
}

/** get field */
function hm_get_field($name,$object_id,$object_type){

	$tableName=DB_PREFIX."field";
	$whereArray=array(
						'name'=>MySQL::SQLValue($name),
						'object_type'=>MySQL::SQLValue($object_type),
						'object_id'=>MySQL::SQLValue($object_id)
					);

	$this->SelectRows($tableName, $whereArray);
	if ($this->HasRecords()) {
		$row = $this->Row();
		return $row->val;
	}else{
		return NULL;
	}

}

/** json encode utf-8 php < 5.4 */
function hm_json_encode($json){
	$encoded = json_encode($json);
	$unescaped = preg_replace_callback('/\\\\u(\w{4})/', function ($matches) {
		return html_entity_decode('&#x' . $matches[1] . ';', ENT_COMPAT, 'UTF-8');
	}, $encoded);
	return $unescaped;
}
