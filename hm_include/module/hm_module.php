<?php
/**
 * Class này xử lý các module, ví dụ như tích hợp codeigniter vào cùng
 */
if ( ! defined('BASEPATH')) exit('403');

$hmmodule=array();

Class module extends MySQL{
	
	public $hmmodule = array();
	
	/** Đăng ký 1 module */
	public function register_module($input=NULL){
		if( $input==NULL ) exit('Missing argument for register_module ');
		if(is_array($input)){
			return $this->register_module_by_array($input);
		}
	}
	
	/** Đăng ký 1 module bằng cách truyền vào 1 array */
	public function register_module_by_array($args=array()){
		if( $args['module_name'] AND $args['module_key'] AND $args['module_index'] AND $args['module_dir'] ){
			if(!$this->isset_module($args)){
				$this->set_module($args);	
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
	
	/** kiểm tra module đã tồn tại */
	public function isset_module($args=array()){

		if(is_array($args)){
			
			if($this->total_module() > 0 ){
				
				$all_module = $this->hmmodule;
				$input_module_key=$args['module_key'];
				
				if( isset( $all_module[$input_module_key] ) ){
					return TRUE;
				}else{
					return FALSE;
				}

			}else{
				return FALSE;
			}
		}else{
			return FALSE;
		}
	}
	
	/** Trả về tổng số module */
	public function total_module(){
		return sizeof($this->hmmodule);
	}
	
	/** hủy 1 module */
	public function destroy_module($args=array()){
		if($this->isset_module($args)){
			$this->unset_module($args);
			return TRUE;
		}else{
			return FALSE;
		}
	}
	
	/** Thêm module đã đăng ký vào biến $hmmodule */
	private function set_module($args=array()){
		$this->hmmodule[$args['module_key']]=$args;
	}
	
	/** Gỡ bỏ 1 module khỏi biến $hmmodule */
	private function unset_module($args=array()){
		if( in_array($args,$this->hmmodule) ){
			foreach ($this->hmmodule as $key => $module){
				if(
					$this->hmmodule[$key]['module_name'] == $args['module_name']
					AND
					$this->hmmodule[$key]['module_key'] == $args['module_key']
				){
					unset($this->hmmodule[$key]);
				}
			}
		}
	}

	
}