<?php
/** 
 * Load các thư viện và file cần thiết
 * Vị trí : /hm_loader.php 
 */
if ( ! defined('BASEPATH')) exit('403');
if ( HM_DEBUG ){
	error_reporting(0);
	ini_set('display_errors', 1);
}else{
	ini_set('display_errors', 0);
	if (version_compare(PHP_VERSION, '5.3', '>='))
	{
		error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
	}
	else
	{
		error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
	}
}
if(!defined('ALLOW_PLUGIN_PAGE')){ define('ALLOW_PLUGIN_PAGE',TRUE); }
if(!defined('ALLOW_THEME_PAGE')){ define('ALLOW_THEME_PAGE',TRUE); }
if(!defined('ALLOW_MENU_PAGE')){ define('ALLOW_MENU_PAGE',TRUE); }
if(!defined('ALLOW_BLOCK_PAGE')){ define('ALLOW_BLOCK_PAGE',TRUE); }
if(!defined('ALLOW_COMMAND_PAGE')){ define('ALLOW_COMMAND_PAGE',TRUE); }
if(!defined('ALLOW_UPDATE')){ define('ALLOW_UPDATE',TRUE);}

session_start(); 

/** BASE_URL */
$base_url = SITE_URL.FOLDER_PATH.SERVER_PORT;
define('BASE_URL', $base_url);

/** Version Number */
define('HM_VERSION', '20');
define('HM_VERSION_NAME', '1.3.2 Hoa Thủy Tiên');

/** Api server */
define('HM_API_SERVER', 'http://hoamaisoft.com');

/** Database */
require_once(BASEPATH . HM_INC . '/database.php');

/** Hook */
require_once(BASEPATH . HM_INC . '/hook.php');

/** PHP Class */
require_once(BASEPATH . HM_INC . '/class.Diff.php');
require_once(BASEPATH . HM_INC . '/class.Security.php');
require_once(BASEPATH . HM_INC . '/class.SimpleCaptcha.php');
require_once(BASEPATH . HM_INC . '/class.MobileDetect.php');
require_once(BASEPATH . HM_INC . '/PHPMailer/PHPMailerAutoload.php');

/** Functions */
require_once(BASEPATH . HM_INC . '/functions.php');

/** Input_sanitization */
require_once(BASEPATH . HM_INC . '/input_sanitization.php');

/** String */
require_once(BASEPATH . HM_INC . '/string.php');

/** Upload */
require_once(BASEPATH . HM_INC . '/upload.php');

/** Media */
require_once(BASEPATH . HM_INC . '/media.php');

/** Taxonomy */
require_once(BASEPATH . HM_INC . '/taxonomy.php');

/** Content */
require_once(BASEPATH . HM_INC . '/content.php');

/** Menu */
require_once(BASEPATH . HM_INC . '/menu.php');

/** Block */
require_once(BASEPATH . HM_INC . '/block.php');

/** Shortcode */
require_once(BASEPATH . HM_INC . '/shortcode.php');

/** User */
require_once(BASEPATH . HM_INC . '/user.php');

/** Option */
require_once(BASEPATH . HM_INC . '/option.php');

/** Email */
require_once(BASEPATH . HM_INC . '/email.php');

/** Dashboard */
require_once(BASEPATH . HM_INC . '/dashboard.php');

/** Pluggable */
require_once(BASEPATH . HM_INC . '/pluggable.php');

/** Module */
require_once(BASEPATH . HM_INC . '/module.php');

/** Theme */
require_once(BASEPATH . HM_INC . '/theme.php');

/** Routing */
require_once(BASEPATH . HM_INC . '/routing.php');

/** Init */
require_once(BASEPATH . HM_INC . '/init.php');



?>