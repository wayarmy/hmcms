<?php
/**
 * Tệp tin cấu hình các cài đặt chính
 * Vị trí : /hm_config.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** Tên database */
define('DB_NAME', '');

/** Tài khoản database */
define('DB_USER', '');

/** Mật khẩu database */
define('DB_PASSWORD', '');

/** Hostname */
define('DB_HOST', '');

/** Database Charset */
define('DB_CHARSET', 'utf8');

/** Tiếp tiền tố */
define('DB_PREFIX', '');

/** Thư mục chứa các file thư viện và xử lý */
define('HM_INC', 'hm_include');

/** Thư mục chứa các file frontend */
define('HM_FRONTENT_DIR', 'hm_frontend');

/** Thư mục chứa các file upload và nội dung */
define('HM_CONTENT_DIR', 'hm_content');

/** Thư mục chứa các giao diện */
define('HM_THEME_DIR', 'hm_themes');

/** Thư mục chứa các plugin */
define('HM_PLUGIN_DIR', 'hm_plugins');

/** Thư mục chứa các module tích hợp */
define('HM_MODULE_DIR', 'hm_module');

/** Thư mục quản trị */
define('HM_ADMINCP_DIR', 'admin');

/** Cổng kết nối (:80, :8080, :443 etc)*/
define('SERVER_PORT', '');

/** Tên website */
define('SITE_NAME', 'HoaMai CMS');

/** Xóa copyright ở trong admin */
define('REMOVE_ADMINCP_COPYRIGHT', FALSE);

/** Hiển trị trang plugin trong admin */
define('ALLOW_PLUGIN_PAGE', TRUE);

/** Hiển trị trang theme trong admin */
define('ALLOW_THEME_PAGE', TRUE);

/** Hiển trị trang command trong admin */
define('ALLOW_COMMAND_PAGE', TRUE);

/** Nhắc nhở và cho phép update */
define('ALLOW_UPDATE', TRUE);

/** Nội dung cập nhật */
define('SYSTEM_DASHBOARD', TRUE);

/** Địa chỉ website */
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === true ? 'https://' : 'http://';
define('SITE_URL', $protocol.$_SERVER['SERVER_NAME'].SERVER_PORT);

/** Đường dẫn thư mục */
define('FOLDER_PATH', '');

/** Ngôn ngữ */
define('LANG', 'vi_VN');

/** Mã hóa */
define('ENCRYPTION_KEY', '');

/** Cho phép login bằng mật khẩu mã hóa md5 */
define('MD5_PASSWORD', FALSE);

/** Thời gian cookie */
define('COOKIE_EXPIRES', 3600);

/** Giao diện mặc định */
define('DEFAULT_THEME', 'dong');

/** Báo lỗi */
define('HM_DEBUG', FALSE);

/** Cơ sở để phân trang */
define('PAGE_BASE', 'trang-');

/** Time zone */
date_default_timezone_set('Asia/Ho_Chi_Minh');

?>
