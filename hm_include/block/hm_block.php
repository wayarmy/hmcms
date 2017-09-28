<?php
/**
 * Class này xử lý các block
 */
if ( ! defined('BASEPATH')) exit('403');
$block_container=array();
$block=array();
Class block extends MySQL{
	
	public $block_container = array();
	public $block = array();
	
	/** Đăng ký 1 vị trí block container */
	public function register_block_container($input=NULL){
		if( $input==NULL ) exit('Missing argument for register_block_container');
		if(is_array($input)){
			return $this->register_block_container_by_array($input);
		}
	}
	
	/** Đăng ký 1 vị trí block container bằng cách truyền vào 1 array */
	public function register_block_container_by_array($args=array()){
		if( $args['name'] AND $args['nice_name'] ){
			if(!$this->isset_block_container($args)){
				$this->set_block_container($args);
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
	
	/** Đăng ký 1 vị trí block  */
	public function register_block($input=NULL){
		if( $input==NULL ) exit('Missing argument for register_block');
		if(is_array($input)){
			return $this->register_block_by_array($input);
		}
	}
	
	/** Đăng ký 1 vị trí block  bằng cách truyền vào 1 array */
	public function register_block_by_array($args=array()){
		if( $args['name'] AND $args['nice_name'] ){
			if(!$this->isset_block($args)){
				$this->set_block($args);	
				return TRUE;
			}else{
				return FALSE;
			}
		}
	}
	
	/** Trả về tổng số block container */
	public function total_block_container(){
		return sizeof($this->block_container);
	}
	
	/** Trả về tổng số block */
	public function total_block(){
		return sizeof($this->block);
	}
	
	/** kiểm tra block container đã tồn tại */
	public function isset_block_container($args=array()){
		if(is_array($args)){
			
			if($this->total_block_container() > 0 ){
				
				$all_block_container = $this->block_container;
				$input_block_container_name=$args['name'];
				
				if( isset( $all_block_container[$input_block_container_name] ) ){
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
	
	/** kiểm tra block đã tồn tại */
	public function isset_block($args=array()){
		if(is_array($args)){
			
			if($this->total_block() > 0 ){
				
				$all_block = $this->block;
				$input_block_name=$args['name'];
				
				if( isset( $all_block[$input_block_name] ) ){
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
	
	/** Thêm vị trí block container đã đăng ký vào biến $block_container */
	private function set_block_container($args=array()){
		$this->block_container[$args['name']]=$args;
	}
	
	/** Thêm vị trí block đã đăng ký vào biến $block */
	private function set_block($args=array()){
		$this->block[$args['name']]=$args;
	}
	
	
	/** Hiển thị block theo vị trí đã đăng ký */
	public function block_container($args=array()){
		
		$container_name = $args['name'];
		$isset_blocks = get_block($container_name);
		foreach($isset_blocks as $block){
			$block_id = $block['id'];
			$block_func = $block['func'];
			call_user_func($block_func, $block_id);
		}
	}
	
	public function get_block($args=array()){
		
	}
	
	/** Lấy giá trị 1 field của block */
	public function get_blo_val($args=array()){
					
		$name=$args['name'];
		$id=$args['id'];
		$tableName=DB_PREFIX."field";
		$whereArray=array(
							'name'=>MySQL::SQLValue($name),
							'object_type'=>MySQL::SQLValue('block'),
							'object_id'=>MySQL::SQLValue($id)
						);
		
		$this->SelectRows($tableName, $whereArray);
		if ($this->HasRecords()) {
			$row = $this->Row();
			return $row->val;
		}else{
			return NULL;
		}

	}
	
	/** Update giá trị 1 field của block */
	public function update_blo_val($args=array()){
		
		$name = $args['name'];
		$id = $args['id'];
		$value = $args['value'];
		if(is_array($value)){
			$value = json_encode($value);
		}
		
		$values["name"] = MySQL::SQLValue($name);
		$values["val"] = MySQL::SQLValue($value);
		$values["object_id"] = MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER);
		$values["object_type"] = MySQL::SQLValue('block');
		
		$tableName=DB_PREFIX."field";
		$whereArray = array (
							'object_id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER),
							'object_type'=>MySQL::SQLValue('block'),
							'name'=>MySQL::SQLValue($name),
							);
		
		$this->AutoInsertUpdate($tableName, $values, $whereArray);

	}
	
	
}

