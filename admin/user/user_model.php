<?php
/** 
 * Tệp tin model của user trong admin
 * Vị trí : admin/user/user_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** Load template user box */
function ajax_add_user($args=array()){
	
	global $hmuser;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('ajax_add_user');
	
	if( isset($args['id_update']) ){$id_update = $args['id_update'];}else{$id_update = NULL;}

		$user_login = hm_post('user_login');
		$password = hm_post('password');
		$password2 = hm_post('password2');
		$nicename = hm_post('nicename');
		$user_email = hm_post('user_email');
		$userrole = hm_post('userrole');
		$user_group = hm_post('user_group',0);
		$salt = rand(100000,999999);
		$user_activation_key = '0';
		
		if($password != $password2){
			return hm_json_encode(array( 'status'=>'error','mes'=>hm_lang('the_two_passwords_do_not_match') ));
		}
		
		$tableName=DB_PREFIX."users";
		
		
		/** check trùng user login */
		if( !is_numeric($id_update) ){
			
			$whereArray=array('user_login'=>MySQL::SQLValue($user_login));
			$hmdb->SelectRows($tableName, $whereArray);
			if( $hmdb->HasRecords() ){
				return hm_json_encode(array( 'status'=>'error','mes'=>hm_lang('this_account_already_exists') ));
			}
		
		}
		
		
		
		/** Thêm tài khoản */
		
		if(is_numeric($id_update)){
			if(trim($password) == ''){
				$whereArray=array('id'=>MySQL::SQLValue($id_update, MySQL::SQLVALUE_NUMBER));
				$hmdb->SelectRows($tableName, $whereArray);
				if( $hmdb->HasRecords() ){
					$row=$hmdb->Row();
					$password_encode = $row->user_pass;
					$salt = $row->salt;
				}
			}else{
				$password_encode = hm_encode_str(md5($password.$salt));
			}
		}else{
			$password_encode = hm_encode_str(md5($password.$salt));
		}
		
		$values = array();
		$values["user_login"] = MySQL::SQLValue($user_login);
		$values["user_nicename"] = MySQL::SQLValue($nicename);
		$values["user_email"] = MySQL::SQLValue($user_email);
		$values["user_activation_key"] = MySQL::SQLValue($user_activation_key);
		$values["user_role"] = MySQL::SQLValue($userrole);
		$values["user_group"] = MySQL::SQLValue($user_group);
		$values["user_pass"] = MySQL::SQLValue($password_encode);
		$values["salt"] = MySQL::SQLValue($salt);
		
		if(is_numeric($id_update)){
			
			$whereArray = array (
									'id'=>$id_update,
								);
			$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
			$insert_id=$id_update;
					
		}else{
			
			$values["user_pass"] = MySQL::SQLValue($password_encode);
			$values["salt"] = MySQL::SQLValue($salt);
			$insert_id=$hmdb->InsertRow($tableName, $values); 
			
		}
		
		
		/** Lưu user field */
		
		foreach ( $_POST as $post_key => $post_val ){
			
			if(is_numeric($insert_id)){
				
				if(is_array($post_val)){ $post_val = hm_json_encode($post_val); }
			
				$tableName=DB_PREFIX.'field';
				
				if($post_key != 'password' AND $post_key != 'password2'){
					
					$values["name"] = MySQL::SQLValue($post_key);
					$values["val"] = MySQL::SQLValue($post_val);
					$values["object_id"] = MySQL::SQLValue($insert_id, MySQL::SQLVALUE_NUMBER);
					$values["object_type"] = MySQL::SQLValue('user');
				
					if(is_numeric($id_update)){
						
						$whereArray = array (
											'object_id'=>MySQL::SQLValue($id_update, MySQL::SQLVALUE_NUMBER),
											'object_type'=>MySQL::SQLValue('user'),
											'name'=>MySQL::SQLValue($post_key),
											);
						$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
						
						
					}else{
						$hmdb->InsertRow($tableName, $values);
					}
				
				}
				
				unset($values);
			}
			
		}
		if( is_numeric($id_update) ){
			return hm_json_encode(array( 'status'=>'updated','mes'=>hm_lang('edit_account_information').' : '.$user_login ));
		}else{
			return hm_json_encode(array( 'status'=>'success','mes'=>hm_lang('account_added').' : '.$user_login ) );
		}

}

/** bảng danh sách thành viên */
function user_show_data($user_group,$perpage){
	
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('user_show_data');
	
	$request_paged = hm_get('paged',1);
	$paged = $request_paged - 1;
	$offset = $paged * $perpage;
	$limit  = "LIMIT $perpage OFFSET $offset";
	
	if (! $hmdb->Query("SELECT * FROM ".DB_PREFIX."users WHERE `user_group` = '$user_group' ORDER BY id DESC $limit")) $hmdb->Kill();
	
	if( $hmdb->HasRecords() ){
		
		/* Trả về các user */
		while ($row = $hmdb->Row()) {
			$array_use[]=array('id'=>$row->id,'user_nicename'=>$row->user_nicename,'user_role'=>user_role_id_to_nicename($row->user_role));
		}
		$array['user']=$array_use;
		
		/* Tạo pagination */
		$hmdb->Query(" SELECT * FROM ".DB_PREFIX."users WHERE `user_group` = '$user_group' ");
		$total_item  = $hmdb->RowCount();
		$total_page = ceil($total_item / $perpage);
		$first = '1';
		if($request_paged > 1){
			$previous = $request_paged - 1;
		}else{
			$previous = $first;
		}
		if($request_paged < $total_page){
			$next = $request_paged + 1;
		}else{
			$next = $total_page;
		}

		
		$array['pagination']=array(
								'first'=>$first,
								'previous'=>$previous,
								'next'=>$next,
								'last'=>$total_page,
								'total'=>$total_item,
								'paged'=>$request_paged,
		);
		
		
	}else{
		$array['user']=array();
		$array['pagination']=array();
	}
	
	return hook_filter('user_show_data',hm_json_encode($array,TRUE));
	
}

/** Load template user box */
function ajax_ban_user(){
	
	$id = hm_post('id');

	if( isset_user($id) == TRUE ){
		
		$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
		
		$tableName=DB_PREFIX."users";
		$whereArray = array ('id'=>$id);
		$values['user_role'] = MySQL::SQLValue(5, MySQL::SQLVALUE_NUMBER);
		$hmdb->UpdateRows($tableName, $values, $whereArray);
	
	}
	
}




?>