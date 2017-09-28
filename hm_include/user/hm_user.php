<?php
/**
 * Class này xử lý các hàm liên quan đến tài khoản
 * 
 */
if ( ! defined('BASEPATH')) exit('403');

$hmuser=array();


Class user extends MySQL{
	
	public $hmuser = array();
	
	/** Đăng ký các trường cho user */
	public function register_user_field($args=array()){
		
		if(is_array($args)){
			$this->hmuser['field'] = $args;
		}
		
	}
	
	/** Lấy các trường của user */
	public function get_user_field(){
		
		return $this->hmuser['field'];
		
	}
	
	/** Tạo form điền thông tin của user */
	
	public function user_field($id){
				
		$user_field = $this->get_user_field();
		
		if( $this->isset_user($id) ){
			
			foreach ($user_field as $field){
				
				$args = array('id'=>$id,'field'=>$field['name']);
				$field['default_value']=$this->user_field_data($args);
				build_input_form($field);
				
			}
			
		}else{
			
			foreach ($user_field as $field){
				
				build_input_form($field);
				
			}
			
		}
		
	}
	
	
	/** Kiểm tra user tồn tại */
	public function isset_user($id){
		
		$tableName=DB_PREFIX."users";
		$whereArray=array('id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER));
		$this->SelectRows($tableName, $whereArray);
		if( $this->HasRecords() ){
			
			return TRUE;
			
		}else{
			
			return FALSE;
			
		}
		
	}
	
	/** Lấy thông tin user */
	public function user_data($id){
		
		if( $this->isset_user($id) ){
				
			$tableName=DB_PREFIX."users";
			$whereArray=array('id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER));
			$this->SelectRows($tableName, $whereArray);
			return $this->Row();
			
		}else{
			
			return array();
			
		}
		
	}
	
	public function user_field_data( $args ){
		
		$id=$args['id'];
		$field=$args['field'];
		
		if( $this->isset_user($id) ){
				
			$tableName=DB_PREFIX."field";
			$whereArray=array(
								'object_id'=>MySQL::SQLValue($id, MySQL::SQLVALUE_NUMBER),
								'name'=>MySQL::SQLValue($field),
								'object_type'=>MySQL::SQLValue('user'),
							);
			$this->SelectRows($tableName, $whereArray);
			if( $this->HasRecords() ){
				$row = $this->Row();
				return $row->val;
			}else{
				return FALSE;
			}
		}else{
			
			return FALSE;
			
		}
		
	}
	
	public function user_role_id_to_nicename($user_role){
		
		$default_role = array(1,2,3,4,5);
		if( in_array($user_role,$default_role) ){
			switch ($user_role) {
				case 1:
					return hm_lang('administrator');
				break;
				case 2:
					return hm_lang('webmaster');
				break;
				case 3:
					return hm_lang('editor');
				break;
				case 4:
					return hm_lang('member');
				break;
				case 5:
					return hm_lang_('banned_account');
				break;
			}
		}else{
			return $user_role;
		}
		
	}
	
}