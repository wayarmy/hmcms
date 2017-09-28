<?php
/** 
 * Tệp tin model của theme trong admin
 * Vị trí : admin/theme/theme_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** Load danh sách theme */
function available_theme(){ 
	
	$themes = array();
	$available_theme = scandir(BASEPATH.HM_THEME_DIR);
	
	foreach($available_theme as $theme){
		
		if(
			( is_dir(BASEPATH.HM_THEME_DIR.'/'.$theme) )
			AND 
			( is_file(BASEPATH.HM_THEME_DIR.'/'.$theme.'/init.php') )
		){
			/** lấy nội dung comment trong file */
			$source = file_get_contents( BASEPATH.HM_THEME_DIR.'/'.$theme.'/init.php' );
			$tokens = token_get_all( $source );
			$comment = array(
				T_COMMENT,
				T_DOC_COMMENT     
			);
			
			foreach( $tokens as $token ) {
				
				if( in_array($token[0], $comment) ){
					
					$string = $token[1];
					/** get thông tin theme */

					preg_match("'Theme Name:(.*?);'si", $string, $results);
					if(isset($results[1])){
						$theme_name= $results[1];
					}
					
					preg_match("'Description:(.*?);'si", $string, $results);		
					if(isset($results[1])){
						$theme_description= $results[1];
					}
					
					preg_match("'Version:(.*?);'si", $string, $results);
					if(isset($results[1])){
						$theme_version= $results[1];
					}
					
					if(is_theme_active($theme)==TRUE){$theme_active='1';}else{$theme_active='0';}
					
					if( file_exists(BASEPATH.HM_THEME_DIR.'/'.$theme.'/thumbnail.jpg') ){
						$theme_thumbnail = BASE_URL.HM_THEME_DIR.'/'.$theme.'/thumbnail.jpg';
					}else{
						$theme_thumbnail = BASE_URL.HM_CONTENT_DIR.'/images/theme_no_thumbnail.jpg';
					}
			
					$themes[$theme]=array(
											'theme_name'=>$theme_name,
											'theme_description'=>$theme_description,
											'theme_version'=>$theme_version,
											'theme_key'=>$theme,
											'theme_active'=>$theme_active,
											'theme_thumbnail'=>$theme_thumbnail,
											);											
					unset($string);
				}

			}

		}
		
	}
	ksort($themes);
	$args['themes']=$themes;
	return hm_json_encode($args);
	
}


function is_theme_active($theme){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	if(
		( is_dir(BASEPATH.HM_THEME_DIR.'/'.$theme) )
		AND 
		( is_file(BASEPATH.HM_THEME_DIR.'/'.$theme.'/init.php') )
	){

		$tableName=DB_PREFIX."option";
		$whereArray=array(
				'section'=>MySQL::SQLValue('system_setting'),
				'key'=>MySQL::SQLValue('theme'),
				'value'=>MySQL::SQLValue($theme),
		);
		$hmdb->SelectRows($tableName, $whereArray);
		if( $hmdb->HasRecords() ){
			
			return TRUE;
			
		}else{
			return FALSE;
		}
		
	}else{
		return FALSE;
	}

}

/** kích hoạt giao diện */
function active_theme($theme){
	
	if(
		( is_dir(BASEPATH.HM_THEME_DIR.'/'.$theme) )
		AND 
		( is_file(BASEPATH.HM_THEME_DIR.'/'.$theme.'/init.php') )
	){
		
		$args = array(
						'section'=>'system_setting',
						'key'=>'theme',
						'value'=>$theme,
					);
		
		set_option($args);
		
		$mes = hm_lang('theme_enabled').' '.$theme;
		$args=array(
						'status' => 'success',
						'mes' => $mes,
				   );
		return hm_json_encode($args);
	
	}else{
		
		$mes = hm_lang('no_theme_exists').' '.$theme;
		$args=array(
						'status' => 'error',
						'mes' => $mes,
				   );
		return hm_json_encode($args);
		
	}
	
}

/** Xóa theme */
function drop_theme($theme=FALSE){ 
	
	if(is_theme_active($theme)){
		$mes = hm_lang('you_can_not_delete_the_actived_theme');
		$args=array(
						'status' => 'error',
						'mes' => $mes,
				   );
		return hm_json_encode($args);
	}else{
		$path = BASEPATH.'/'.HM_THEME_DIR.'/'.$theme;
		DeleteDir($path);
		
		$mes = $theme.' deleted';
		$args=array(
						'status' => 'success',
						'mes' => $mes,
				   );
		return hm_json_encode($args);
	}
}


/** Danh sách theme */
function api_theme_data($server = FALSE,$args=array()){
	if(isset($args['keyword'])){
		$keyword = '?keyword='.$args['keyword'];
	}
	if(!$server){
		$server = HM_API_SERVER.'/api/themes/json'.$keyword;
	}
	$data = file_get_contents($server);
	$data = json_decode($data);
	if($data==NULL){
		return  '<div class="alert alert-danger" role="alert">'.hm_lang('can_not_connect_to_the_download_server').'</div>';;
	}else{
		return $data;
	}
	
}

/** Cài đặt theme */
function add_theme($href=FALSE){
	if(is_url_exist($href)){
		$filename = basename($href);
		$saveto = HM_THEME_DIR.'/'.$filename;
		file_put_contents($saveto, fopen($href, 'r'));
		
		if(file_exists($saveto)){
			if (class_exists('ZipArchive')) {
				$zip = new ZipArchive;
				$res = $zip->open($saveto);
				if ($res === TRUE) {
					$zip->extractTo(HM_THEME_DIR.'/');
					$zip->close();
					unlink($saveto);
					return hm_json_encode(array( 'status'=>'success','mes'=>hm_lang('downloaded_to_server').' : '.$filename ));
				}else{
					return hm_json_encode(array( 'status'=>'error','mes'=>hm_lang('unable_to_extract_the_file') ));
				}
			}else{
				return hm_json_encode(array( 'status'=>'error','mes'=>hm_lang('this_server_does_not_support_the_ZipArchive_class_unable_to_unpack_file') ));
			}
		}else{
			return hm_json_encode(array( 'status'=>'error','mes'=>hm_lang('can_not_save_file') ));
		}
		
	}else{
		return hm_json_encode(array( 'status'=>'error','mes'=>hm_lang('can_not_connect_to_the_download_server') ));
	}
}