<?php
/**
 * Class này xử lý các plugin cài vào website
 */
if ( ! defined('BASEPATH')) exit('403');

$hmpluggable=array();


Class pluggable extends MySQL{
	
	public $hmpluggable = array();
	
	/** Khởi chạy tất cả plugin đã được active */
	public function run_plugin(){
		
		$tableName=DB_PREFIX."plugin";
		$whereArray=array('active'=>MySQL::SQLValue(1, MySQL::SQLVALUE_NUMBER));

		$this->SelectRows($tableName, $whereArray);
		if( $this->HasRecords() ){
			
			while($row = $this->Row()){
				
				$plugin = $row->key;
				$plugin_location = BASEPATH.HM_PLUGIN_DIR.'/'.$plugin.'/'.$plugin.'.php';
				if(file_exists($plugin_location)){
					
					require_once($plugin_location);
					
				}
				
			}
			
		}
		
	}
	
}

function require_plugin($theme_name,$plugins=array()){

	$request_slug = get_current_uri();
	if($request_slug!=HM_ADMINCP_DIR){
		
		foreach($plugins as $key){
		
			$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
			
			if(
				( is_dir(BASEPATH.HM_PLUGIN_DIR.'/'.$key) )
				AND 
				( is_file(BASEPATH.HM_PLUGIN_DIR.'/'.$key.'/'.$key.'.php') )
			){
				
				$tableName=DB_PREFIX."plugin";
				$whereArray=array('key'=>MySQL::SQLValue($key));
				$hmdb->SelectRows($tableName, $whereArray);
				if( $hmdb->HasRecords() ){
					
					$row = $hmdb->Row();
					$active = $row->active;
					if($active==1){
						
						
						
					}else{
						
						hm_exit('Giao diện '.$theme_name.' yêu cầu phải kích hoạt plugin : '.$key);
						
					}
					
				}else{
					hm_exit('Giao diện '.$theme_name.' yêu cầu phải kích hoạt plugin : '.$key);
				}
				
			}else{
				hm_exit('Giao diện '.$theme_name.' yêu cầu phải kích hoạt plugin : '.$key);
			}
			
		}
			
	}
}

?>