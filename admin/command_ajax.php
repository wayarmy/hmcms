<?php
/** 
 * Tệp tin xử lý command bằng ajax trong admin
 * Vị trí : admin/command_ajax.php 
 */
if ( ! defined('BASEPATH')) exit('403');
if(!defined('ALLOW_COMMAND_PAGE')){
	define('ALLOW_COMMAND_PAGE',TRUE);
}
if(ALLOW_COMMAND_PAGE != TRUE){
	hm_exit('Tính năng đã bị tắt');
}


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

/** Gọi các model */
require_once( dirname( __FILE__ ) . '/plugin/plugin_model.php' );
require_once( dirname( __FILE__ ) . '/theme/theme_model.php' );
 
/** Bắt đầu */ 
require(BASEPATH . HM_INC . '/json-rpc.php');

if (function_exists('xdebug_disable')) {
    xdebug_disable();
}

class Hmcommand {
		
	public function plugin($parameter1=NULL,$parameter2=NULL) {
		
		switch ($parameter1) {
			case 'showall':
				
				$return = '';
				
				$available_plugin = json_decode( available_plugin(),TRUE );
				$plugins = $available_plugin['plugins'];
				
				foreach ($plugins as $key => $val){
					
					if( is_plugin_active($key) ){ $key = '[active] '.$key; }else{ $key = '[deactive] '.$key; }
					
					$return[] = "	".$key."\n";
					
				}
				
				return (implode('',$return));
				
			break;
			case 'active':
				
				if( isset($parameter2) ){
				
					$return = disable_plugin('1',$parameter2);
					$args = json_decode($return,TRUE);
					return $args['mes'];
					
				}else{
					
					$return = '';
				
					$active_plugin = json_decode( list_plugin(1),TRUE );
					
					foreach ($active_plugin as $key => $val){
						
						$return[] = "	".$val."\n";
						
					}
					
					return (implode('',$return));
					
				}
				
			break;
			case 'deactive':
				
				if( isset($parameter2) ){
				
					$return = disable_plugin('0',$parameter2);
					$args = json_decode($return,TRUE);
					return $args['mes'];
					
				}else{
					
					$return = '';
				
					$active_plugin = json_decode( list_plugin(0),TRUE );
					
					foreach ($active_plugin as $key => $val){
						
						$return[] = "	".$val."\n";
						
					}
					
					return (implode('',$return));
					
				}
				
			break;
			case 'drop':
				
				if( isset($parameter2) ){
					
					$return = drop_plugin($parameter2);
					$args = json_decode($return,TRUE);
					return $args['mes'];
					
				}else{
					
					$mes = hm_lang('please_enter_the_plugin_key_you_want_to_delete');
					return $mes;
					
				}
				
			break;
			default :
				$help = "	plugin showall : ".hm_lang('show_all_existing_plugins')."\n".
						"	plugin active : "._('Hiển thị tất cả plugin đang chạy')."\n".
						"	plugin active [pluginkey] : "._('Kích hoạt plugin [pluginkey]')."\n".
						"	plugin deactive : "._('Hiển thị tất cả plugin đang tắt')."\n".
						"	plugin deactive [pluginkey] : "._('Tắt plugin [pluginkey]')."\n".
						"	plugin drop [pluginkey]: "._('Xóa plugin [pluginkey]')."\n".
						""."\n";
				return $help;
		}
		
	}
	
	public function theme($parameter1=NULL,$parameter2=NULL) {
		
		switch ($parameter1) {
			case 'showall':
				
				$return = '';
				
				$available_theme = json_decode( available_theme(),TRUE );
				$themes = $available_theme['themes'];
				
				foreach ($themes as $key => $val){
					
					if( is_theme_active($key) ){ $key = '[active] '.$key; }else{ $key = '[deactive] '.$key; }
					
					$return[] = "	".$key."\n";
					
				}
				
				return (implode('',$return));
				
			break;
			case 'active':
				
				if( isset($parameter2) ){
				
					$return = active_theme($parameter2);
					$args = json_decode($return,TRUE);
					return $args['mes'];
					
				}else{
					
					$mes = _('Vui lòng nhập theme key muốn kích hoạt');
					return $mes;
					
				}
				
			break;
			case 'drop':
				
				if( isset($parameter2) ){
					
					$return = drop_theme($parameter2);
					$args = json_decode($return,TRUE);
					return $args['mes'];
					
				}else{
					
					$mes = _('Vui lòng nhập theme key muốn xóa');
					return $mes;
					
				}
				
			break;
			default :
				$help = "	theme showall : "._('Hiển thị tất cả theme hiện có')."\n".
						"	theme active [themekey] : "._('Kích hoạt theme [themekey]')."\n".
						"	theme drop [themekey]: "._('Xóa theme [themekey]')."\n".
						""."\n";
				return $help;
		}
		
	}
	
	public function content($parameter1=NULL,$parameter2=NULL) {
		
		switch ($parameter1) {
			
			case 'type':
			
				global $hmcontent;
				
				if( isset($parameter2) ){
				
					$con=$hmcontent->hmcontent;

					if(isset($con[$parameter2])){
					
						$args=$con[$parameter2];
						foreach ($args as $key => $val){
							
							if( is_array($val) ){
							
								$return[] = "	".hm_array_to_list($key,$val);
								
							}else{
							
								$return[] = "	".$key." : ".$val."\n";
							
							}
							
						}
						
						return (implode('',$return));
						
					}
					
				}else{
				
					$content_type = $hmcontent->hmcontent;
					
					foreach ($content_type as $key => $val){
							
						$return[] = "	".$key."\n";
						
					}
					
					return (implode('',$return));
				
				}
				
				
			break;
			default :
				$help = "	content type : "._('Hiển thị tất cả content type')."\n".
						"	content type ['content key'] : "._('Hiển thị chi tiết content type')."\n".
						""."\n";
				return $help;
		}
		
	}
}

handle_json_rpc(new Hmcommand());