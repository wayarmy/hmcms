<?php
/** 
 * Tệp tin model của update trong admin
 * Vị trí : admin/update/update_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');


function update_check($type='core',$key='',$server=''){
	
	switch ($type) {
		case 'core':
			$check_url = HM_API_SERVER.'/api/update/check';
			@$content = file_get_contents($check_url);
			$content = json_decode($content,TRUE);
			if(isset($content['newest']) AND $content['newest'] == HM_VERSION){
				return TRUE; 
			}else{
				return $content;
			}
		break;
		case 'plugin':
			$check_url = HM_API_SERVER.'/api/plugin/check/?plugin='.$key;
			@$content = file_get_contents($check_url);
			$content = json_decode($content,TRUE);
			return $content;
		break;
	}
	
}

/** Cài đặt update theo domain */
function update_core_for_domain(){
	$domain=$_SERVER['HTTP_HOST'];
	$args = array(
				'section'=>'update_core_for_domain',
				'key'=>'version',
				'default_value'=>'0',
			);
			
	$old_version = get_option($args);
	
	$available_plugin = json_decode(available_plugin(),TRUE);
	$available_plugin_send = array();
	foreach($available_plugin['plugins'] as $plugin_key => $plugin_val){
		$available_plugin_send[] = $plugin_key;
	}
	$available_plugin_send = implode(',',$available_plugin_send);
	
	$check_url = HM_API_SERVER.'/api/update/for_domain/'
	.'?domain='.$domain
	.'&old_version='.$old_version
	.'&cms_version='.HM_VERSION
	.'&available_plugin='.$available_plugin_send;
	
	
	@$content = file_get_contents($check_url);
	$content = json_decode($content,TRUE);

	if(isset($content['version']) AND isset($content['download'])){
		
		$version = $content['version'];
		$href = $content['download'];
		if($old_version < $version){
			if(is_url_exist($href)){
				
				$filename = basename($href);
				$saveto = BASEPATH.'/'.$filename;
				file_put_contents($saveto, fopen($href, 'r'));
				if(file_exists($saveto)){
					if (class_exists('ZipArchive')) {
						$zip = new ZipArchive;
						$res = $zip->open($saveto);
						if ($res === TRUE) {
							$zip->extractTo(BASEPATH.'/');
							$zip->close();
							unlink($saveto);
						}
					}
				}
				
			}
			$args = array(
					'section'=>'update_core_for_domain',
					'key'=>'version',
					'value'=>$version,
				);
			set_option($args);
		}
	}
}

/** Chạy các hàm xử lý sau khi update */
function update_auto_load(){
	if(file_exists(BASEPATH.HM_ADMINCP_DIR.'/update/update_mo.php')){
		unlink(BASEPATH.HM_ADMINCP_DIR.'/update/update_mo.php');
	}
	if(file_exists(BASEPATH.HM_ADMINCP_DIR.'/update/.htaccess')){
		unlink(BASEPATH.HM_ADMINCP_DIR.'/update/.htaccess');
	}
}

/** Cài đặt update */
function update_core($href=FALSE){
	if(!$href){
		$href = HM_API_SERVER.'/download/lastest.zip';
	}
	if(is_url_exist($href)){
		$filename = basename($href);
		$saveto = BASEPATH.'/'.$filename;
		file_put_contents($saveto, fopen($href, 'r'));
		
		if(file_exists($saveto)){
			if (class_exists('ZipArchive')) {
				$zip = new ZipArchive;
				$res = $zip->open($saveto);
				if ($res === TRUE) {
					$zip->extractTo(BASEPATH.'/');
					$zip->close();
					update_auto_load();
					unlink($saveto);
					return hm_json_encode(array( 'status'=>'success','mes'=>_('Đã tải về máy chủ').' : '.$filename ));
				}else{
					return hm_json_encode(array( 'status'=>'error','mes'=>_('Không thể giải nén tệp tin') ));
				}
			}else{
				return hm_json_encode(array( 'status'=>'error','mes'=>_('Máy chủ không hỗ trợ class ZipArchive, vui lòng giải nén '.$filename.' bằng tay') ));
			}
		}else{
			return hm_json_encode(array( 'status'=>'error','mes'=>_('Không lưu được file') ));
		}
		
	}else{
		return hm_json_encode(array( 'status'=>'error','mes'=>_('Không kết nối được với máy chủ download') ));
	}
}