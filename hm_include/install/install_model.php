<?php
session_start();
error_reporting(0);
if (!defined('PHP_VERSION_ID')) {
    $version = explode('.', PHP_VERSION);
    define('PHP_VERSION_ID', ($version[0] * 10000 + $version[1] * 100 + $version[2]));
}
if (PHP_VERSION_ID > 50207) {
    define('PHP_MAJOR_VERSION',   $version[0]);
    define('PHP_MINOR_VERSION',   $version[1]);
    define('PHP_RELEASE_VERSION', $version[2]);
}

function allow_version(){
	if(PHP_MAJOR_VERSION > 5){
		return TRUE;
	}elseif(PHP_MAJOR_VERSION == 5){
		if(PHP_MINOR_VERSION > 3){
			return TRUE;
		}else{
			return FALSE;
		}
	}else{
		return FALSE;
	}
}

function gdVersion($user_ver = 0){
    if (! extension_loaded('gd')) { return; }
    static $gd_ver = 0;
    if ($user_ver == 1) { $gd_ver = 1; return 1; }
    if ($user_ver !=2 && $gd_ver > 0 ) { return $gd_ver; }
    if (function_exists('gd_info')) {
        $ver_info = gd_info();
        preg_match('/\d/', $ver_info['GD Version'], $match);
        $gd_ver = $match[0];
        return $match[0];
    }
    if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
        if ($user_ver == 2) {
            $gd_ver = 2;
            return 2;
        } else {
            $gd_ver = 1;
            return 1;
        }
    }
    ob_start();
    phpinfo(8);
    $info = ob_get_contents();
    ob_end_clean();
    $info = stristr($info, 'gd version');
    preg_match('/\d/', $info, $match);
    $gd_ver = $match[0];
    return $match[0];
}

function check_all(){

	$check = array();
	if(allow_version()){
		$check['allow_version'] = '1';
	}else{
		$check['allow_version'] = '0';
	}

	if($gdv = gdVersion()) {
		if ($gdv >=2) {
			$check['gdVersion'] = '1';
		} else {
			$check['gdVersion'] = '0';
		}
	} else {
		$check['gdVersion'] = '0';
	}

	if(is_writable('hm_content/uploads')){
		$check['uploadWritable'] = '1';
	}else{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			$check['uploadWritable'] = '1';
		}else{
			$check['uploadWritable'] = '0';
		}
	}

	if(function_exists('mcrypt_encrypt')){
		$check['mcrypt'] = '1';
	}else{
		$check['mcrypt'] = '0';
	}
	
	if(function_exists('mysqli_connect')){
		$check['mysqli'] = '1';
	}else{
		$check['mysqli'] = '0';
	}
	
	
	
	if(!in_array('0',$check)){
		return TRUE;
	}else{
		return FALSE;
	}
			
}

function is_connect(){
	$host = $_POST['host'];
	$username = trim($_POST['username']);
	$password = trim($_POST['password']);
	$database = trim($_POST['database']);
	$prefix = trim($_POST['prefix']);
	$mysqlConnection = mysql_connect($host, $username, $password);
	if (!$mysqlConnection){
		return FALSE;
		session_destroy();
	}
	else{
		$db_selected = mysql_select_db($database, $mysqlConnection);
		if (!$db_selected) {
			return FALSE;
			session_destroy();
		}else{
			$_SESSION['db']['host'] = $host;
			$_SESSION['db']['username'] = $username;
			$_SESSION['db']['password'] = $password;
			$_SESSION['db']['database'] = $database;
			$_SESSION['db']['prefix'] = $prefix;
			return TRUE;
		}
	}
}


function generateRandomString($length = 30) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function hm_encode_str($str,$key){
	
	if(is_array($str)){$str = hm_json_encode($str);}
	$encoded = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $str, MCRYPT_MODE_CBC, md5(md5($key))));
	return $encoded;
	
}

function install_db(){
	$host = $_SESSION['db']['host'];
	$username = $_SESSION['db']['username'];
	$password = $_SESSION['db']['password'];
	$database = $_SESSION['db']['database'];
	$prefix = $_SESSION['db']['prefix'];
	$admin_username = trim($_POST['admin_username']);
	$admin_email = trim($_POST['admin_email']);
	$admin_password = trim($_POST['admin_password']);
	$encryption_key = trim($_POST['encryption_key']);
	$url_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	/** install */
	$mysqlConnection = mysql_connect($host, $username, $password);
	mysql_select_db($database, $mysqlConnection);
	mysql_query('SET NAMES "UTF8"');
	
	$sql = "
	CREATE TABLE IF NOT EXISTS `".$prefix."content` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(500) NOT NULL,
		  `slug` varchar(500) NOT NULL,
		  `key` varchar(255) NOT NULL,
		  `parent` int(11) NOT NULL,
		  `status` varchar(255) NOT NULL,
		  `content_order` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	";
	mysql_query($sql);
	echo '<p>Tạo bảng : '.$prefix.'content ...</p>';
	/**--------------------------------------------------------*/
	
	$sql = "
	CREATE TABLE IF NOT EXISTS `".$prefix."field` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) NOT NULL,
		  `val` longtext NOT NULL,
		  `object_id` int(11) NOT NULL,
		  `object_type` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `object_id` (`object_id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	";
	mysql_query($sql);
	echo '<p>Tạo bảng : '.$prefix.'field ...</p>';
	/**--------------------------------------------------------*/
	
	$sql = "
	CREATE TABLE IF NOT EXISTS `".$prefix."media` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `media_group_id` int(11) NOT NULL,
		  `file_info` text NOT NULL,
		  `file_is_image` varchar(5) NOT NULL,
		  `file_name` varchar(255) NOT NULL,
		  `file_folder` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	";
	mysql_query($sql);
	echo '<p>Tạo bảng : '.$prefix.'media ...</p>';
	/**--------------------------------------------------------*/
	
	$sql = "
	CREATE TABLE IF NOT EXISTS `".$prefix."media_groups` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) NOT NULL,
		  `folder` varchar(255) NOT NULL,
		  `parent` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	";
	mysql_query($sql);
	echo '<p>Tạo bảng : '.$prefix.'media_groups ...</p>';
	/**--------------------------------------------------------*/
	
	$sql = "
	CREATE TABLE IF NOT EXISTS `".$prefix."object` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) NOT NULL,
		  `key` varchar(255) NOT NULL,
		  `parent` int(11) NOT NULL,
		  `order_number` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	";
	mysql_query($sql);
	echo '<p>Tạo bảng : '.$prefix.'object ...</p>';
	/**--------------------------------------------------------*/
	
	$sql = "
	CREATE TABLE IF NOT EXISTS `".$prefix."option` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `section` varchar(500) NOT NULL,
		  `key` varchar(255) NOT NULL,
		  `value` text NOT NULL,
		  PRIMARY KEY (`id`),
		  KEY `section` (`section`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;
	";
	mysql_query($sql);
	echo '<p>Tạo bảng : '.$prefix.'option ...</p>';
	/**--------------------------------------------------------*/
	
	$sql = "
	CREATE TABLE IF NOT EXISTS `".$prefix."plugin` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `key` varchar(255) NOT NULL,
		  `active` int(1) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	";
	mysql_query($sql);
	echo '<p>Tạo bảng : '.$prefix.'plugin ...</p>';
	/**--------------------------------------------------------*/
	
	$sql = "
	CREATE TABLE IF NOT EXISTS `".$prefix."relationship` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `object_id` int(11) NOT NULL,
		  `target_id` int(1) NOT NULL,
		  `relationship` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	";
	mysql_query($sql);
	echo '<p>Tạo bảng : '.$prefix.'relationship ...</p>';
	/**--------------------------------------------------------*/
	
	$sql = "
	CREATE TABLE IF NOT EXISTS `".$prefix."request_uri` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `object_id` int(11) NOT NULL,
		  `object_type` varchar(255) NOT NULL,
		  `uri` varchar(1000) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	";
	mysql_query($sql);
	echo '<p>Tạo bảng : '.$prefix.'request_uri ...</p>';
	/**--------------------------------------------------------*/
	
	$sql = "
	CREATE TABLE IF NOT EXISTS `".$prefix."taxonomy` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) NOT NULL,
		  `slug` varchar(255) NOT NULL,
		  `key` varchar(255) NOT NULL,
		  `parent` int(11) NOT NULL,
		  `status` varchar(255) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	";
	mysql_query($sql);
	echo '<p>Tạo bảng : '.$prefix.'taxonomy ...</p>';
	/**--------------------------------------------------------*/
	
	$sql = "
	CREATE TABLE IF NOT EXISTS `".$prefix."users` (
		  `id` int(11) NOT NULL AUTO_INCREMENT,
		  `user_login` varchar(255) NOT NULL,
		  `user_pass` varchar(255) NOT NULL,
		  `salt` int(6) NOT NULL,
		  `user_nicename` varchar(255) NOT NULL,
		  `user_email` varchar(255) NOT NULL,
		  `user_activation_key` varchar(255) NOT NULL,
		  `user_role` int(11) NOT NULL,
		  `user_group` int(11) NOT NULL,
		  PRIMARY KEY (`id`)
		) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
	";
	mysql_query($sql);
	echo '<p>Tạo bảng : '.$prefix.'users ...</p>';
	/**--------------------------------------------------------*/
	
	/** user_admin */
	$admin_salt = rand(0,999999);
	$password_encode = hm_encode_str(md5($admin_password.$admin_salt),$encryption_key);
	
	
	$sql = "
		INSERT INTO `".$prefix."users` (`id`, `user_login`, `user_pass`, `salt`, `user_nicename`, `user_email`, `user_activation_key`, `user_role`, `user_group`) VALUES
		(1, '".$admin_username."', '".$password_encode."', '".$admin_salt."', '".$admin_username."', '".$admin_email."', '0', '1', '0');
	";
	mysql_query($sql);

	
	echo '<p>Tạo tài khoản quản trị : '.$admin_username.' ...</p>';
	/**--------------------------------------------------------*/
	
	
	$sql = "
		INSERT INTO `hm_option` (`id`, `section`, `key`, `value`) VALUES
		(1, 'system_setting', 'theme', 'dong'),
		(2, 'system_setting', 'post_per_page', '10'),
		(3, 'system_setting', 'from_email', '".$admin_email."');
	";
	mysql_query($sql);
	echo '<p>Kích hoạt cài đặt mặc định ...</p>';
	/**--------------------------------------------------------*/
	
	/** Tạo .htaccess */
	$htaccess = 
'<IfModule mod_rewrite.c>
	RewriteEngine On
	RewriteBase /
	RewriteRule ^index\.php$ - [L]
	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d
	RewriteRule . '.$url_path.'index.php [L]
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
		echo '<p><strong>Quá trình tạo file : .htaccess thất bại, vui lòng tạo 1 file .htaccess (ngang hàng index.php) trên host với nội dung như sau:</strong></p>';
		echo '<textarea class="form-control" rows="10">'.$htaccess.'</textarea>';
	}
	/** tạo file config */

	$hm_config = file_get_contents('hm_include/install/hm_config_sample.php');
	$hm_config = str_replace("define('DB_NAME', '');","define('DB_NAME', '".$database."');",$hm_config);
	$hm_config = str_replace("define('DB_USER', '');","define('DB_USER', '".$username."');",$hm_config);
	$hm_config = str_replace("define('DB_PASSWORD', '');","define('DB_PASSWORD', '".$password."');",$hm_config);
	$hm_config = str_replace("define('DB_HOST', '');","define('DB_HOST', '".$host."');",$hm_config);
	$hm_config = str_replace("define('DB_PREFIX', '');","define('DB_PREFIX', '".$prefix."');",$hm_config);
	$hm_config = str_replace("define('ENCRYPTION_KEY', '');","define('ENCRYPTION_KEY', '".$encryption_key."');",$hm_config);
	$hm_config = str_replace("define('FOLDER_PATH', '');","define('FOLDER_PATH', '".$url_path."');",$hm_config);
	if($_SERVER['SERVER_PORT']!='80'){
		$hm_config = str_replace("define('SERVER_PORT', '');","define('SERVER_PORT', ':".$_SERVER['SERVER_PORT']."');",$hm_config);
	}
	
	$fp=fopen('hm_config.php','w');
	if($fp){
		fwrite($fp,$hm_config);
		fclose($fp);
		echo '<p class="alert alert-success" role="alert">Cài đặt mã nguồn thành công</p>';
		echo '<p><a href="'.BASE_URL.'admin/" class="btn btn-default">Đăng nhập quản trị</a></p>';
	}else{
		echo '<p><strong>Quá trình tạo file : hm_config.php thất bại, vui lòng tạo 1 file hm_config.php (ngang hàng index.php) trên host với nội dung như sau:</strong></p>';
		echo '<textarea class="form-control" rows="10">'.$hm_config.'</textarea>';
	}
	
	
}

?>