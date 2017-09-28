<?php
/**
 * Class này xử lý các shortcode
 */
if ( ! defined('BASEPATH')) exit('403');
$shortcode=array();
Class shortcode{
	
	public $shortcode = array();

	
	/** Đăng ký 1 shortcode  */
	public function register_shortcode($input=NULL){
		if( $input==NULL ) exit('Missing argument for register_shortcode');
		if(is_array($input)){
			return $this->register_shortcode_by_array($input);
		}
	}
	
	/** Đăng ký 1 shortcode  bằng cách truyền vào 1 array */
	public function register_shortcode_by_array($args=array()){
		if( $args['name'] AND $args['func'] ){
			if(!$this->isset_shortcode($args)){
				$this->set_shortcode($args);	
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}

	/** Trả về tổng số shortcode */
	public function total_shortcode(){
		return sizeof($this->shortcode);
	}

	/** kiểm tra shortcode đã tồn tại */
	public function isset_shortcode($args=array()){
		if(is_array($args)){
			
			if($this->total_shortcode() > 0 ){
				
				$all_shortcode = $this->shortcode;
				$input_shortcode_name=$args['name'];
				
				if( isset( $all_shortcode[$input_shortcode_name] ) ){
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

	/** Thêm shortcode đã đăng ký vào biến $shortcode */
	private function set_shortcode($args=array()){
		$this->shortcode[$args['name']]=$args;
	}

}

