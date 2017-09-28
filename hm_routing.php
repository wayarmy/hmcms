<?php
/** 
 * Đây là tệp tin xử lý đường dẫn
 * Vị trí : /hm_routing.php 
 */

if ( ! defined('BASEPATH')) exit('403');

/** Kiểm tra .htaccess */
if(!file_exists('.htaccess')){
	$htaccess = 
'<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteBase /
	RewriteRule ^index\.php$ - [L]
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule . '.FOLDER_PATH.'index.php [L]
</IfModule>

<FilesMatch "\.php$">
	Order Deny,Allow
	Deny from all
</FilesMatch>
<FilesMatch "^index\.php$">
	Order Allow,Deny
	Allow from all
</FilesMatch>

<IfModule pagespeed_module>
	ModPagespeed on
	ModPagespeedEnableFilters
	extend_cache,combine_css,combine_javascript,collapse_whitespace,move_css_to_head
</IfModule>

# BEGIN GZIP
<IfModule mod_deflate.c>
  # Compress HTML, CSS, JavaScript, Text, XML and fonts
  AddOutputFilterByType DEFLATE application/javascript
  AddOutputFilterByType DEFLATE application/rss+xml
  AddOutputFilterByType DEFLATE application/vnd.ms-fontobject
  AddOutputFilterByType DEFLATE application/x-font
  AddOutputFilterByType DEFLATE application/x-font-opentype
  AddOutputFilterByType DEFLATE application/x-font-otf
  AddOutputFilterByType DEFLATE application/x-font-truetype
  AddOutputFilterByType DEFLATE application/x-font-ttf
  AddOutputFilterByType DEFLATE application/x-javascript
  AddOutputFilterByType DEFLATE application/xhtml+xml
  AddOutputFilterByType DEFLATE application/xml
  AddOutputFilterByType DEFLATE font/opentype
  AddOutputFilterByType DEFLATE font/otf
  AddOutputFilterByType DEFLATE font/ttf
  AddOutputFilterByType DEFLATE image/svg+xml
  AddOutputFilterByType DEFLATE image/x-icon
  AddOutputFilterByType DEFLATE text/css
  AddOutputFilterByType DEFLATE text/html
  AddOutputFilterByType DEFLATE text/javascript
  AddOutputFilterByType DEFLATE text/plain
  AddOutputFilterByType DEFLATE text/xml

  # Remove browser bugs (only needed for really old browsers)
  BrowserMatch ^Mozilla/4 gzip-only-text/html
  BrowserMatch ^Mozilla/4\.0[678] no-gzip
  BrowserMatch \bMSIE !no-gzip !gzip-only-text/html
  Header append Vary User-Agent
</IfModule>
# END GZIP

# BEGIN Expire headers
<ifModule mod_expires.c>
  ExpiresActive On
  ExpiresDefault "access plus 5 seconds"
  ExpiresByType image/x-icon "access plus 2592000 seconds"
  ExpiresByType image/jpeg "access plus 2592000 seconds"
  ExpiresByType image/png "access plus 2592000 seconds"
  ExpiresByType image/gif "access plus 2592000 seconds"
  ExpiresByType application/x-shockwave-flash "access plus 2592000 seconds"
  ExpiresByType text/css "access plus 604800 seconds"
  ExpiresByType text/javascript "access plus 2592000 seconds"
  ExpiresByType application/javascript "access plus 2592000 seconds"
  ExpiresByType application/x-javascript "access plus 2592000 seconds"
  ExpiresByType text/html "access plus 2592000 seconds"
  ExpiresByType application/xhtml+xml "access plus 2592000 seconds"
</ifModule>
# END Expire headers';
	
	$fp=fopen('.htaccess','w');
	if($fp){
		fwrite($fp,$htaccess);
		fclose($fp);
	}else{
		echo '<p><strong>Không thể tạo tự động file .htaccess, vui lòng tạo 1 file .htaccess (ngang hàng index.php) trên host với nội dung như sau:</strong></p>';
		echo '<textarea class="form-control" rows="10">'.$htaccess.'</textarea>';
		exit();
	}
}

/** Lấy uri trang đang xem */
$request_slug = get_current_uri();
$segments = explode('/', $request_slug);

/** Kiểm tra segments 1 có phải là 1 module */
$modules = $hmmodule->hmmodule;
$module_key = FALSE;
if(isset($segments[1])){
	$module_key = $segments[1];
}

/** Quick link */
if(is_numeric(hm_get('c'))){
	$content_id = hm_get('c');
	$data = content_data_by_id($content_id);
	if(isset($data['content']->slug)){
		$url = SITE_URL.'/'.$data['content']->slug;
		header('Location: '.$url, true, 302);
		exit();
	}
}

if(is_numeric(hm_get('t'))){
	$tax_id = hm_get('t');
	$data = taxonomy_data_by_id($tax_id);
	if(isset($data['taxonomy']->slug)){
		$url = SITE_URL.'/'.$data['taxonomy']->slug;
		header('Location: '.$url, true, 302);
		exit();
	}
}

if($segments[0]==HM_ADMINCP_DIR AND !isset($segments[1]) ){
	
	/** Admin cp */
	require_once(BASEPATH . HM_ADMINCP_DIR . '/index.php');
	
}elseif( isset($modules[$module_key]) ){
	
	/** Module */
	$module = $modules[$module_key];
	if(is_array($module)){
		
		$module_name = $module['module_name'];
		$module_key = $module['module_key'];
		$module_dir = $module['module_dir'];
		$module_index = $module['module_index'];
		
		if( file_exists( BASEPATH . HM_MODULE_DIR .'/'. $module_dir .'/'. $module_index ) ){
			require_once(BASEPATH . HM_MODULE_DIR .'/'. $module_dir .'/'. $module_index);
		}else{
			hm_exit('Không tìm thấy file "'.$module_index.'" của module "'.$module_key.'"');
		}
		
	}else{
		hm_exit('Lỗi xử lý module'.' '.$module);
	}
	
}else{
	
	hook_action('before_display_view');
	
	if( isset($hmrequest[$request_slug]) ){
		if(!function_exists($hmrequest[$request_slug]['func'])) {
			die('Unknown function: '.$hmrequest[$request_slug]['func']);
		}else{
			call_user_func($hmrequest[$request_slug]['func']);
		}
	}else{
		/** Fontend */
		$theme = activated_theme();
		$args = array(
			'theme' => $theme,
			'request' => $request_slug,
		);
		load_theme($args);
	}
	
	hook_action('after_display_view');
	
}

?>