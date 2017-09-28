<?php
/** 
 * Load giao diện
 * Vị trí : hm_include/theme.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/**
 * Gọi thư viện theme
 */
require_once(BASEPATH . HM_INC . '/theme/hm_theme.php');

/**
 * Khởi tạo class
 */
$hmtheme = new theme;
	
function activated_theme(){
	
	global $hmtheme;
	
	hook_action('activated_theme');
	
	$return = $hmtheme->activated_theme();
	
	return $return;
	
}

function load_theme($args){
	
	$args = hook_filter('before_load_theme',$args);
	
	global $hmtheme;
	
	hook_action('load_theme');
	
	$hmtheme->load_theme($args);
	
}


function get_template_part($template_file,$data=array()){
	
	$template_file = hook_filter('before_get_template_part',$template_file);
	
	global $hmtheme;
	
	hook_action('get_template_part');
	
	$hmtheme->get_template_part($template_file,$data);
	
}

function css($file=NULL,$attr=array()){
	
	$is_url = filter_var($file, FILTER_VALIDATE_URL);
	$attr_str='';
	foreach($attr as $key => $val){
		$attr_str.=' '.$key.'="'.$val.'"';
	}
	if($is_url == FALSE){
		
		if(is_numeric($file)){
			
			$file_url = get_file_url($file);
			if($file_url){
				return '<link rel="stylesheet" type="text/css" href="'.$file_url.'"'.$attr_str.'>'."\n";
			}else{
				return FALSE;
			}
			
		}else{
			
			$theme = activated_theme();
			$file_local = './'.HM_THEME_DIR.'/'.$theme.'/'.$file;
			$optimizer_folder = './'.HM_THEME_DIR.'/'.$theme;
			if(file_exists($file_local)){
				
				/** thông số file css */
				$file_part = pathinfo($file,PATHINFO_DIRNAME);
				$file_name = pathinfo($file,PATHINFO_FILENAME);
				$file_ext = pathinfo($file,PATHINFO_EXTENSION);
				$file_time = filemtime($file_local);
				$mincss_file_name = $file_part.'/'.$file_name.'.'.$file_time.'.'.$file_ext;
				
				/** thời gian chỉnh sửa css này lần cuối */
				$log_file_local = $optimizer_folder.'/'.$file.'.log';
				if(!file_exists($log_file_local)){
					$dirname = dirname($log_file_local);
					if(!file_exists($dirname)){
						mkdir($dirname, 0777, true);
					}
					$fp=fopen($log_file_local,'w');
					fwrite($fp,$file_time);
					fclose($fp);
					$old_file_time = $file_time;	
				}else{
					$old_file_time = file_get_contents($log_file_local);
				}
			
				/** nếu thời gian chỉnh sửa hiện tại != thời gian log */
				if($file_time != $old_file_time){
					/** xóa css cũ */
					$old_mincss_file_name = $file_part.'/'.$file_name.'.'.$old_file_time.'.'.$file_ext;
					unlink($optimizer_folder.'/'.$old_mincss_file_name);
					/** log lại thời gian chỉnh sửa */
					$fp=fopen($log_file_local,'w');
					fwrite($fp,$file_time);
					fclose($fp);
				}
			
				$mincss_file_local = $optimizer_folder.'/'.$mincss_file_name;
				if(!file_exists($mincss_file_local)){
					$dirname = dirname($mincss_file_local);
					if(!file_exists($dirname)){
						mkdir($dirname, 0777, true);
					}
					$mincss = file_get_contents($file_local);
					$mincss = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $mincss);
					$mincss = str_replace(': ', ':', $mincss);
					$mincss = str_replace(array("\n","\r","\t",'    ','     '), '', $mincss);
					$mincss = str_replace(';}','}', $mincss);
					$fp=fopen($mincss_file_local,'w');
					fwrite($fp,$mincss);
					fclose($fp);
				}
				
				return '<link rel="stylesheet" type="text/css" href="'.BASE_URL.HM_THEME_DIR.'/'.$theme.'/'.$mincss_file_name.'"'.$attr_str.'>'."\n";
			}else{
				return FALSE;
			}
	
		}
		
	}else{
		
		return '<link rel="stylesheet" type="text/css" href="'.$is_url.'"'.$attr_str.'>'."\n";
		
	}
	
}

function style($file=NULL,$attr=array()){
	
	$content = '';
	$is_url = filter_var($file, FILTER_VALIDATE_URL);
	$attr_str='';
	foreach($attr as $key => $val){
		$attr_str.=' '.$key.'="'.$val.'"';
	}
	if($is_url == FALSE){
		
		if(is_numeric($file)){
			
			$file_url = get_file_url($file);
			if($file_url){
				$content = file_get_contents($file_url);
			}else{
				$content = '';
			}
			
		}else{
			
			$theme = activated_theme();
			$file_local = './'.HM_THEME_DIR.'/'.$theme.'/'.$file;
			$optimizer_folder = './'.HM_THEME_DIR.'/'.$theme;
			if(file_exists($file_local)){
				
				/** thông số file css */
				$file_part = pathinfo($file,PATHINFO_DIRNAME);
				$file_name = pathinfo($file,PATHINFO_FILENAME);
				$file_ext = pathinfo($file,PATHINFO_EXTENSION);
				$file_time = filemtime($file_local);
				$mincss_file_name = $file_part.'/'.$file_name.'.'.$file_time.'.'.$file_ext;
				
				/** thời gian chỉnh sửa css này lần cuối */
				$log_file_local = $optimizer_folder.'/'.$file.'.log';
				if(!file_exists($log_file_local)){
					$dirname = dirname($log_file_local);
					if(!file_exists($dirname)){
						mkdir($dirname, 0777, true);
					}
					$fp=fopen($log_file_local,'w');
					fwrite($fp,$file_time);
					fclose($fp);
					$old_file_time = $file_time;	
				}else{
					$old_file_time = file_get_contents($log_file_local);
				}
			
				/** nếu thời gian chỉnh sửa hiện tại != thời gian log */
				if($file_time != $old_file_time){
					/** xóa css cũ */
					$old_mincss_file_name = $file_part.'/'.$file_name.'.'.$old_file_time.'.'.$file_ext;
					unlink($optimizer_folder.'/'.$old_mincss_file_name);
					/** log lại thời gian chỉnh sửa */
					$fp=fopen($log_file_local,'w');
					fwrite($fp,$file_time);
					fclose($fp);
				}
			
				$mincss_file_local = $optimizer_folder.'/'.$mincss_file_name;
				if(!file_exists($mincss_file_local)){
					$dirname = dirname($mincss_file_local);
					if(!file_exists($dirname)){
						mkdir($dirname, 0777, true);
					}
					$mincss = file_get_contents($file_local);
					$mincss = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $mincss);
					$mincss = str_replace(': ', ':', $mincss);
					$mincss = str_replace(array("\n","\r","\t",'    ','     '), '', $mincss);
					$mincss = str_replace(';}','}', $mincss);
					$fp=fopen($mincss_file_local,'w');
					fwrite($fp,$mincss);
					fclose($fp);
				}
				
				$content = file_get_contents($mincss_file_local);
			}else{
				$content = '';
			}
	
		}
		
	}else{
		$content = file_get_contents($is_url);
	}
	
	return '<style>'.$content.'</style>'."\n";
}

function js($file=NULL,$attr=array()){
	
	$is_url = filter_var($file, FILTER_VALIDATE_URL);
	$attr_str='';
	foreach($attr as $key => $val){
		$attr_str.=' '.$key.'="'.$val.'"';
	}
	if($is_url == FALSE){
		
		if(is_numeric($file)){
			
			$file_url = get_file_url($file);
			if($file_url){
				return '<script type="text/javascript" src="'.$file_url.'"'.$attr_str.'></script>'."\n";
			}else{
				return FALSE;
			}
			
		}else{
			
			$theme = activated_theme();
			$file_local = './'.HM_THEME_DIR.'/'.$theme.'/'.$file;
			$optimizer_folder = './'.HM_THEME_DIR.'/'.$theme;
			if(file_exists($file_local)){
				
				/** thông số file js */
				$file_part = pathinfo($file,PATHINFO_DIRNAME);
				$file_name = pathinfo($file,PATHINFO_FILENAME);
				$file_ext = pathinfo($file,PATHINFO_EXTENSION);
				$file_time = filemtime($file_local);
				$minjs_file_name = $file_part.'/'.$file_name.'.'.$file_time.'.'.$file_ext;
			
				/** thời gian chỉnh sửa js này lần cuối */
				$log_file_local = $optimizer_folder.'/'.$file.'.log';
				if(!file_exists($log_file_local)){
					$dirname = dirname($log_file_local);
					if(!file_exists($dirname)){
						mkdir($dirname, 0777, true);
					}
					$fp=fopen($log_file_local,'w');
					fwrite($fp,$file_time);
					fclose($fp);
					$old_file_time = $file_time;	
				}else{
					$old_file_time = file_get_contents($log_file_local);
				}
			
				/** nếu thời gian chỉnh sửa hiện tại != thời gian log */
				if($file_time != $old_file_time){
					/** xóa js cũ */
					$old_minjs_file_name = $file_part.'/'.$file_name.'.'.$old_file_time.'.'.$file_ext;
					unlink($optimizer_folder.'/'.$old_minjs_file_name);
					/** log lại thời gian chỉnh sửa */
					$fp=fopen($log_file_local,'w');
					fwrite($fp,$file_time);
					fclose($fp);
				}
			
				$minjs_file_local = $optimizer_folder.'/'.$minjs_file_name;
				if(!file_exists($minjs_file_local)){
					$dirname = dirname($minjs_file_local);
					if(!file_exists($dirname)){
						mkdir($dirname, 0777, true);
					}
					$minjs = file_get_contents($file_local);
					$fp=fopen($minjs_file_local,'w');
					fwrite($fp,$minjs);
					fclose($fp);
				}
				return '<script type="text/javascript" src="'.BASE_URL.HM_THEME_DIR.'/'.$theme.'/'.$minjs_file_name.'"'.$attr_str.'></script>'."\n";
			}else{
				return FALSE;
			}
			
			$theme = activated_theme();
			$file_local = './'.HM_THEME_DIR.'/'.$theme.'/'.$file;
			$optimizer_folder = './'.HM_THEME_DIR.'/'.$theme.'/hm_asset_optimizer';
			if(file_exists($file_local)){
				return '<script type="text/javascript" src="'.BASE_URL.HM_THEME_DIR.'/'.$theme.'/'.$file.'"'.$attr_str.'></script>'."\n";
			}else{
				return FALSE;
			}
			
		}
		
	}else{
		
		return '<script type="text/javascript" src="'.$file.'"'.$attr_str.'></script>'."\n";
		
	}

}

function jsbyjs($files=array()){
	if(!is_array($files)){
		$files = array($files);
	}
	$files_fix = array();
	foreach($files as $file){
		if (filter_var($file, FILTER_VALIDATE_URL) === FALSE) {
			$files_fix[] = theme_uri($file);
		}else{
			$files_fix[] = $file;
		}
	}
	$str_js = implode("',\n\r  '",$files_fix);
	echo    "<script type=\"text/javascript\"> \n\r".
			"[ \n\r".
			"  '".$str_js."' \n\r".
			"].forEach(function(src) { \n\r".
			"  var script = document.createElement('script'); \n\r".
			"  script.src = src; \n\r".
			"  script.async = false; \n\r".
			"  document.head.appendChild(script); \n\r".
			"}); \n\r".
			"</script> \n\r";
}

function cssbyjs($files=array()){
	if(!is_array($files)){
		$files = array($files);
	}
	$files_fix = array();
	foreach($files as $file){
		if (filter_var($file, FILTER_VALIDATE_URL) === FALSE) {
			$files_fix[] = theme_uri($file);
		}else{
			$files_fix[] = $file;
		}
	}
	$str_css = implode("',\n\r  '",$files_fix);
	echo    "<script type=\"text/javascript\"> \n\r".
			"[ \n\r".
			"  '".$str_css."' \n\r".
			"].forEach(function(href) { \n\r".
			"  var script = document.createElement('link'); \n\r".
			"  script.href = href; \n\r".
			"  script.rel = 'stylesheet'; \n\r".
			"  script.type = 'text/css'; \n\r".
			"  script.async = false; \n\r".
			"  document.head.appendChild(script); \n\r".
			"}); \n\r".
			"</script> \n\r";
}

function cssdeferred($files=array(),$all_in_one=FALSE,$attr=array()){
	if(!is_array($files)){
		$files = array($files);
	}
	
	if($all_in_one==TRUE){
		
		$theme = activated_theme();
		$dir = dirname($files[0]);
		$content_css='';
		$all_in_one_local = './'.HM_THEME_DIR.'/'.$theme.'/'.$dir.'/all_in_one.css';
	
		if(!file_exists($all_in_one_local)){
			
			foreach($files as $file){
				$file_local = './'.HM_THEME_DIR.'/'.$theme.'/'.$file;
				if(isset($file_local)){
					$content_css.=file_get_contents($file_local);
				}
			}
			
			$fp=fopen($all_in_one_local,'w');
			fwrite($fp,$content_css);
			fclose($fp);
		}
	}
	
	echo '<noscript id="hm-deferred-styles">'."\n\r";
	if($all_in_one==TRUE){
		echo css($dir.'/all_in_one.css',$attr);
	}else{
		foreach($files as $file){
			echo css($file,$attr);
		}
	}
	echo '</noscript>'."\n\r";
	echo 	"<script> \n\r".
			"	var loadDeferredStyles = function() { \n\r".
			"	  var addStylesNode = document.getElementById('hm-deferred-styles'); \n\r".
			"	  var replacement = document.createElement('div'); \n\r".
			"	  replacement.innerHTML = addStylesNode.textContent; \n\r".
			"	  document.body.appendChild(replacement); \n\r".
			"	  addStylesNode.parentElement.removeChild(addStylesNode); \n\r".
			"	}; \n\r".
			"	var raf = requestAnimationFrame || mozRequestAnimationFrame || \n\r".
			"		webkitRequestAnimationFrame || msRequestAnimationFrame; \n\r".
			"	if (raf){ raf(function() { window.setTimeout(loadDeferredStyles, 0); }); \n\r".
			"	}else{ window.addEventListener('load', loadDeferredStyles); } \n\r".
			"</script> \n\r";
	
}

function img($file=NULL,$attr=array()){
	
	$return = FALSE;
	$is_url = filter_var($file, FILTER_VALIDATE_URL);
	$attr_str='';
	foreach($attr as $key => $val){
		$attr_str.=' '.$key.'="'.$val.'"';
	}
	if($is_url == FALSE){
		
		if(is_numeric($file)){
			
			if(isset_image($file)){
				$file_url = get_file_url($file);
				if($file_url){
					$return = '<img src="'.$file_url.'"'.$attr_str.' />'."\n";
				}
			}
			
			
		}else{
			
			$theme = activated_theme();
			if(file_exists('./'.HM_THEME_DIR.'/'.$theme.'/'.$file)){
				$return = '<img src="'.BASE_URL.HM_THEME_DIR.'/'.$theme.'/'.$file.'"'.$attr_str.' />'."\n";
			}
			
		}
		
		return $return;
		
	}else{
		
		return '<img src="'.$file.'"'.$attr_str.' />'."\n";
		
	}

}

function theme_uri($part=FALSE){
	$theme = activated_theme();
	if($part==FALSE){
		return BASE_URL.HM_THEME_DIR.'/'.$theme;
	}else{
		return BASE_URL.HM_THEME_DIR.'/'.$theme.'/'.$part;
	}
}

function get_id(){
	
	$request = get_current_uri();
	$request_data = get_uri_data( array('uri'=>$request) );
	
	if( $request_data != FALSE ){
		
		$object_type = $request_data->object_type;
		$object_id = $request_data->object_id;

		return  $object_id;
		 
	}else{
		return FALSE;
	}
	
}	


function have_content(){
	
	$request = get_current_uri();
	$request_data = get_uri_data( array('uri'=>$request) );
	
	if( $request_data != FALSE ){
		
		$object_type = $request_data->object_type;
		$object_id = $request_data->object_id;
		
		switch ($object_type) {
			case 'taxonomy':
				$return = taxonomy_have_content($object_id);
			break;
		}	
		 
		return  $return;
		 
	}else{
		return FALSE;
	}
	
}	

function is_home(){
	
	$request = get_current_uri();
	if($request == ''){
		return TRUE;
	}else{
		return FALSE;
	}
	
}

function is_taxonomy(){
	
	$request = get_current_uri();
	$request_data = get_uri_data( array('uri'=>$request) );
	
	if( $request_data != FALSE ){
		
		$object_type = $request_data->object_type;
		if($object_type == 'taxonomy'){
			return TRUE;
		}
		 
	}else{
		return FALSE;
	}
	
}

function is_content(){
	
	$request = get_current_uri();
	$request_data = get_uri_data( array('uri'=>$request) );

	if( $request_data != FALSE ){
		
		$object_type = $request_data->object_type;
		if($object_type == 'content'){
			return TRUE;
		}
		 
	}else{
		return FALSE;
	}
	
}

function is_404(){
	$request = get_current_uri();
	$request_data = get_uri_data( array('uri'=>$request) );
	if( $request_data == FALSE ){
		if(is_registed_request()){
			return FALSE;
		}else{
			return TRUE;
		}
	}else{
		return FALSE;
	}
}

function is_registed_request(){
	$request = get_current_uri();
	$check_uri = registed_request($request);
	if($check_uri!=FALSE AND is_array($check_uri)){
		return TRUE;
	}else{
		return FALSE;
	}
}


function hm_head($args=array()){
	
	if(!is_array($args)){
		parse_str($args, $args);
	}
	$default_array = array(
				'use_title' => TRUE,
			);
	$args = hm_parse_args($args,$default_array);
	
	hook_action('before_hm_head');
	
	
	/**
	 * Title
	*/
	if($args['use_title']==TRUE){
		hm_title();
	}
	
	
	/**
	 * Favicon
	*/
	$favicon = get_option( array('section'=>'system_setting','key'=>'favicon') );
	if(is_numeric($favicon)){
		$favicon = get_file_url($favicon);
		echo '<link rel="apple-touch-icon" href="'.$favicon.'">'."\n\r";
		echo '<link rel="mask-icon" sizes="any" href="'.$favicon.'">'."\n\r";
		echo '<link rel="icon" href="'.$favicon.'">'."\n\r";
	}

	
	echo get_option( array('section'=>'system_setting','key'=>'head_script','default_value'=>'') );
	hook_action('after_hm_head');
}

function hm_title(){
	
	hook_action('before_hm_title');
	
	global $hmcontent;
	global $hmtaxonomy;
	$hm_title = '';
	
	$request = get_current_uri();
	if($request == ''){
		/** Trang chủ */
		$hm_title = get_option( array('section'=>'system_setting','key'=>'website_name','default_value'=>'Trang chủ') );
	}else{
	
		$registed_request = registed_request($request);
		if($registed_request!=FALSE AND is_array($registed_request)){
			
			/** Request đăng ký bằng lệnh */
			if(isset($registed_request['args']['name'])){
				$hm_title = $registed_request['args']['name'];
			}
			
		}else{
			
			/** Request trong nội dung hoặc danh mục */
			$request_data = get_uri_data( array('uri'=>$request) );
			if($request == ''){
				$home_title = get_option( array('section'=>'system_setting','key'=>'website_name','default_value'=>'Một trang web sử dụng HoaMaiCMS') );
				if($home_title!=''){
					$hm_title = $home_title;
				}else{
					$hm_title = FALSE;
				}
			}else{
				if( $request_data != FALSE ){
					
					$object_type = $request_data->object_type;
					$object_id = $request_data->object_id;
					
					switch ($object_type) {
						case 'content':
							$content_data = content_data_by_id($object_id);
							$title = $content_data['content']->name;
							$hm_title = $title;
						break;
						case 'taxonomy':
							$taxonomy_data = taxonomy_data_by_id($object_id);
							$title = $taxonomy_data['taxonomy']->name;
							$hm_title = $title;
						break;
					}
					 
				}else{
					$hm_title = FALSE;
				}
			}
		}
		
	}
	$hm_title = hook_filter('hm_title',$hm_title);
	echo '<title>'.$hm_title.'</title>'."\n\r";
	hook_action('after_hm_title');
}


function breadcrumb($id=FALSE,$args=array()){
	
	$default_array = array(
				'show_home' => TRUE,
				'home_name' => _('Trang chủ'),
				'home_url' => BASE_URL,
				'404_name' => _('Trang không tồn tại'),
				'wrapper' 			=> 'ul',
				'wrapper_class' 	=> 'breadcrumb',
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
	$args = hm_parse_args($args,$default_array);
	$breadcrumb='';
	$request = get_current_uri();
	$registed_request = registed_request($request);
	if($registed_request!=FALSE AND is_array($registed_request)){
		
		/** Request đăng ký bằng lệnh */
		$breadcrumb.='<'.$args['wrapper'].' class="'.$args['wrapper_class'].'" id="'.$args['wrapper_id'].'" itemscope itemtype="http://data-vocabpary.org/Breadcrumb">';
		
		if($args['show_home'] == TRUE){
			$breadcrumb.='<'.$args['item'].' class="'.$args['item_class'].'" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
			$breadcrumb.=$args['permalink_before'];
			$breadcrumb.='<a href="'.$args['home_url'].'" class="'.$args['permalink_class'].'" title="'.$args['home_name'].'" itemprop="url"><span itemprop="title">'.$args['home_name'].'</span></a>';
			$breadcrumb.=$args['permalink_after'];
			$breadcrumb.='</'.$args['item'].'>';
		}
		if(isset($registed_request['args']['name'])){
			$breadcrumb.='<'.$args['item'].' class="'.$args['item_class'].'" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
			$breadcrumb.=$args['permalink_before'];
			$breadcrumb.='<a href="'.BASE_URL.$request.'" class="'.$args['permalink_class'].'" title="'.$registed_request['args']['name'].'" itemprop="url"><span itemprop="title">'.$registed_request['args']['name'].'</span></a>';
			$breadcrumb.=$args['permalink_after'];
			$breadcrumb.='</'.$args['item'].'>';	
		}
		
		$breadcrumb.='</'.$args['wrapper'].'>';
		
	}else{
		
		/** Request trong nội dung hoặc danh mục */
		if(!is_numeric($id)){ $id=get_id(); }
		
		if( is_content() ){
		
			echo content_breadcrumb($id,$args);
			
		}elseif( is_taxonomy() ){
			
			echo taxonomy_breadcrumb($id,$args);
			
		}elseif( is_404() ){
			
			$breadcrumb.='<'.$args['wrapper'].' class="'.$args['wrapper_class'].'" id="'.$args['wrapper_id'].'" itemscope itemtype="http://data-vocabpary.org/Breadcrumb">';
			
			if($args['show_home'] == TRUE){
				$breadcrumb.='<'.$args['item'].' class="'.$args['item_class'].'" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
				$breadcrumb.=$args['permalink_before'];
				$breadcrumb.='<a href="'.$args['home_url'].'" class="'.$args['permalink_class'].'" title="'.$args['home_name'].'" itemprop="url"><span itemprop="title">'.$args['home_name'].'</span></a>';
				$breadcrumb.=$args['permalink_after'];
				$breadcrumb.='</'.$args['item'].'>';
			}
			
			$breadcrumb.='<'.$args['item'].' class="'.$args['item_class'].'" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">';
			$breadcrumb.=$args['permalink_before'];
			$breadcrumb.='<a class="'.$args['permalink_class'].'" title="'.$args['404_name'].'" itemprop="url"><span itemprop="title">'.$args['404_name'].'</span></a>';
			$breadcrumb.=$args['permalink_after'];
			$breadcrumb.='</'.$args['item'].'>';
			
			$breadcrumb.='</'.$args['wrapper'].'>';
			
		}else{
			$breadcrumb.='<'.$args['wrapper'].' class="'.$args['wrapper_class'].'" id="'.$args['wrapper_id'].'"></'.$args['wrapper'].'>';
		}
		
	}
	
	if($args['echo']){
		echo $breadcrumb;
	}else{
		return $breadcrumb;
	}

}

function hm_footer(){
	
	hook_action('before_hm_footer');
	
	if(is_admin_login()){
		require_once(BASEPATH . HM_FRONTENT_DIR . '/template/' . 'hm_footer.php');
	}
	
	echo get_option( array('section'=>'system_setting','key'=>'footer_script','default_value'=>'') );
	hook_action('after_hm_footer');
	
}

function live_text($string,$key=''){
	
	$theme = activated_theme();
	$bt = debug_backtrace();
	$caller = array_shift($bt);
	$file = $caller['file'];
	$line = $caller['line'];
	$basename = basename($file,'.php');
	$slug = sanitize_title($string);
	if($key==''){
		$option_name = $theme.'_'.$basename.'_'.$slug;
	}else{
		$option_name = $theme.'_'.$basename.'_'.$slug.'_'.$key;
	}
	
	
	$args = array(
				'section'=>'live_text',
				'key'=>$option_name,
				'default_value'=>$string,
			);
	$value = get_option($args);
	
	if(is_admin_login()){
		echo '<live_text data-option_name="'.$option_name.'">'.$value.'<live_edit_btn data-option_name="'.$option_name.'"></live_edit_btn></live_text>';
	}else{
		echo $value;
	}
	
}

function live_textarea($string,$key=''){
	
	$theme = activated_theme();
	$bt = debug_backtrace();
	$caller = array_shift($bt);
	$file = $caller['file'];
	$line = $caller['line'];
	$basename = basename($file,'.php');
	$slug = sanitize_title($string);
	if($key==''){
		$option_name = $theme.'_'.$basename.'_'.$slug;
	}else{
		$option_name = $theme.'_'.$basename.'_'.$slug.'_'.$key;
	}
	
	$args = array(
				'section'=>'live_textarea',
				'key'=>$option_name,
				'default_value'=>$string,
			);
	$value = get_option($args);
	$value = nl2br($value);
	
	if(is_admin_login()){
		echo '<live_textarea data-option_name="'.$option_name.'">'.$value.'<live_edit_btn data-option_name="'.$option_name.'"></live_edit_btn></live_textarea>';
	}else{
		echo $value;
	}
	
}

function live_editor($string,$key=''){
	
	$theme = activated_theme();
	$bt = debug_backtrace();
	$caller = array_shift($bt);
	$file = $caller['file'];
	$line = $caller['line'];
	$basename = basename($file,'.php');
	$slug = sanitize_title($string);
	if($key==''){
		$option_name = $theme.'_'.$basename.'_'.$slug;
	}else{
		$option_name = $theme.'_'.$basename.'_'.$slug.'_'.$key;
	}
	
	$args = array(
				'section'=>'live_editor',
				'key'=>$option_name,
				'default_value'=>$string,
			);
	$value = get_option($args);
	
	if(is_admin_login()){
		echo '<live_editor data-option_name="'.$option_name.'">'.$value.'<live_edit_btn data-option_name="'.$option_name.'"></live_edit_btn></live_editor>';
	}else{
		echo $value;
	}
	
}

function live_multiimage($default_imgs,$key=''){
	$theme = activated_theme();
	$bt = debug_backtrace();
	$caller = array_shift($bt);
	$file = $caller['file'];
	$line = $caller['line'];
	$basename = basename($file,'.php');
	if($key==''){
		$option_name = $theme.'_'.$basename;
	}else{
		$option_name = $theme.'_'.$basename.'_'.$key;
	}
	
	$args = array(
				'section'=>'live_multiimage',
				'key'=>$option_name,
				'default_value'=>$string,
			);
	$value = get_option($args);
	
	if($value == '' AND is_array($default_imgs)){
		foreach($default_imgs as $img){
			$value.= img($img);
		}
	}else{
		$default_imgs = explode(',',$value);
		$value = '';
		foreach($default_imgs as $img){
			$value.= img($img);
		}
	}
	
	if(is_admin_login()){
		echo '<live_multiimage data-option_name="'.$option_name.'">'.$value.'<live_edit_btn data-option_name="'.$option_name.'"></live_edit_btn></live_multiimage>';
	}else{
		echo $value;
	}
}


function live_image($default_img,$key=''){
	$theme = activated_theme();
	$bt = debug_backtrace();
	$caller = array_shift($bt);
	$file = $caller['file'];
	$line = $caller['line'];
	$basename = basename($file,'.php');
	if($key==''){
		$option_name = $theme.'_'.$basename;
	}else{
		$option_name = $theme.'_'.$basename.'_'.$key;
	}
	
	$args = array(
				'section'=>'live_image',
				'key'=>$option_name,
				'default_value'=>$default_img,
			);
	$value = get_option($args);
	$value = img($value);
	
	if(is_admin_login()){
		echo '<live_image data-option_name="'.$option_name.'">'.$value.'<live_edit_btn data-option_name="'.$option_name.'"></live_edit_btn></live_image>';
	}else{
		echo $value;
	}
	
}

function live_ahref($value,$key=''){
	$theme = activated_theme();
	$bt = debug_backtrace();
	$caller = array_shift($bt);
	$file = $caller['file'];
	$line = $caller['line'];
	$basename = basename($file,'.php');
	if($key==''){
		$option_name = $theme.'_'.$basename;
	}else{
		$option_name = $theme.'_'.$basename.'_'.$key;
	}
	$option_name_href = $option_name.'_href';
	$option_name_text = $option_name.'_text';
	
	$args = array(
				'section'=>'live_ahref',
				'key'=>$option_name_href,
				'default_value'=>'#',
			);
	$href = get_option($args);
	
	$args = array(
				'section'=>'live_ahref',
				'key'=>$option_name_text,
				'default_value'=>'{text}',
			);
	$text = get_option($args);

	$value = str_replace('{href}',$href,$value);
	$value = str_replace('{text}',$text,$value);
	
	
	if(is_admin_login()){
		echo '<live_ahref data-option_name="'.$option_name.'">'.$value.'<live_edit_btn data-option_name="'.$option_name.'"></live_edit_btn></live_ahref>';
	}else{
		echo $value;
	}
}
?>