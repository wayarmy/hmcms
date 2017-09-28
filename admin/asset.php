<?php
/** 
 * Tệp tin xử lý asset trong admin
 * Vị trí : admin/asset.php 
 */
if ( ! defined('BASEPATH')) exit('403');

$ext=hm_get('ext');
$file=hm_get('f');

switch ($ext) {
	case 'js':
		$file_part = BASEPATH.HM_ADMINCP_DIR.'/'.LAYOUT_DIR.'/'.$file;
		$ext = pathinfo($file_part, PATHINFO_EXTENSION);
		if(file_exists($file_part) AND $ext=='js'){
			$file_content = file_get_contents($file_part);
			
			preg_match_all("'\{lang:(.*?)\}'si", $file_content, $res);
			if($res[0]){
				$all_lang_str = $res[0];
				foreach($all_lang_str as $lang_str){
					
					$lang_str_clean = str_replace('{','',$lang_str);
					$lang_str_clean = str_replace('}','',$lang_str_clean);
					$explode = explode(':',$lang_str_clean);
					$lang_key = $explode[1];
					$text_lang = hm_lang($lang_key);
					
					$file_content = str_replace($lang_str,$text_lang,$file_content);
					
				}
			}
			
			header('Content-Type: application/javascript');
			echo $file_content;
		}
	break;
}

?>