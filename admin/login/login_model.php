<?php
/** 
 * Tệp tin model của login trong admin
 * Vị trí : admin/login/login_model.php 
 */
if ( ! defined('BASEPATH')) exit('403');

/** Đăng nhập admin cp */
function admin_cp_login(){
	
	global $hmuser;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('admin_cp_login');
	
	$user_login = hm_post('login');
	$password = hm_post('password');
	$logmein = hm_post('log-me-in');

	if( is_numeric($logmein) ){
	
		$tableName=DB_PREFIX."users";
		$whereArray=array('user_login'=>MySQL::SQLValue($user_login));
		$hmdb->SelectRows($tableName, $whereArray);
		if( $hmdb->HasRecords() ){
			
			$row=$hmdb->Row();
			$salt = $row->salt;
			$user_pass = $row->user_pass;
			$user_id = $row->id;
			
			$password_encode = hm_encode_str(md5($password.$salt));
			if(MD5_PASSWORD){
				$password_encode_md5 = md5($password);
			}else{
				$password_encode_md5 = rand(0,999999);
			}
			
			if($password_encode == $user_pass OR $password_encode_md5 == $user_pass){
				
				$time = time();
				$ip = hm_ip();
				
				$cookie_array = array(
										'user_id'=>$user_id,
										'time'=>$time,
										'ip'=>$ip,
										'user_login'=>$user_login,
										'admincp'=>'yes',
									);
				$cookie_user = hm_encode_str($cookie_array);
				
				setcookie('admin_login', $cookie_user ,time() + COOKIE_EXPIRES, '/');
				$_SESSION['admin_login'] = $cookie_user;
				
				return hm_json_encode(array( 'status'=>'success','mes'=>_('Đăng nhập thành công') ));
				
			}else{
				
				return hm_json_encode(array( 'status'=>'error','mes'=>_('Sai mật khẩu') ));
			
			}
			
		}else{
			return hm_json_encode(array( 'status'=>'error','mes'=>_('Không có tài khoản này') ));
		}
	
	}	
		
}

/** Quên mật khẩu admin cp */
function admin_cp_lostpw(){
	
	global $hmuser;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('admin_cp_lostpw');
	
	$user_email = hm_post('email');
	$reset_password = hm_post('reset-password');

	if( is_numeric($reset_password) ){
	
		$tableName=DB_PREFIX."users";
		$whereArray=array('user_email'=>MySQL::SQLValue($user_email));
		$hmdb->SelectRows($tableName, $whereArray);
		if( $hmdb->HasRecords() ){
			
			$row = $hmdb->Row();
			$user_id = $row->id;
			$user_login = $row->user_login;
			$lostpw_key = generateRandomString(10);
			$values["name"] = MySQL::SQLValue('lostpw_key');
			$values["val"] = MySQL::SQLValue($lostpw_key);
			$values["object_id"] = MySQL::SQLValue($user_id, MySQL::SQLVALUE_NUMBER);
			$values["object_type"] = MySQL::SQLValue('user');
			
			$whereArray = array (
								'object_id'=>MySQL::SQLValue($user_id, MySQL::SQLVALUE_NUMBER),
								'object_type'=>MySQL::SQLValue('user'),
								'name'=>MySQL::SQLValue('lostpw_key'),
								);
			$tableName=DB_PREFIX."field";
			$hmdb->AutoInsertUpdate($tableName, $values, $whereArray);
			
			$newpw_url = BASE_URL.HM_ADMINCP_DIR.'?run=login.php&action=newpw&key='.$lostpw_key;
			$mail_content = 'Một ai đó đã yêu cầu đặt lại mật khẩu cho tài khoản '.$user_login.', nếu bạn đã làm điều đó vui lòng <a href="'.$newpw_url.'">bấm vào đây để đặt mật khẩu mới</a>';
			
			//send mail
			$send_mail = hm_mail($user_email, _('Lấy lại mật khẩu:').BASE_URL, $mail_content);
			if($send_mail){
				return hm_json_encode(array( 'status'=>'success','mes'=>_('Mật khẩu mới đã được gửi về email') ));
			}else{
				return hm_json_encode(array( 'status'=>'error','mes'=>_('Máy chủ không thể gửi mail xác nhận đổi mật khẩu, vui lòng báo với chủ trang web') ));
			}
			
		}else{
			return hm_json_encode(array( 'status'=>'error','mes'=>_('Email này không khớp với bất kì tài khoản nào') ));
		}
	
	}
}

/** Kiểm tra mã đổi mật khẩu tồn tại */
function newpw_checkkey(){
	
	global $hmuser;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('newpw_checkkey');
	
	$key = hm_get('key');
	
	$tableName=DB_PREFIX."field";
	$whereArray = array (
					'name'=>MySQL::SQLValue('lostpw_key'),
					'object_type'=>MySQL::SQLValue('user'),
					'val'=>MySQL::SQLValue($key),
				);
	$hmdb->SelectRows($tableName, $whereArray);
	if( $hmdb->HasRecords() ){
		return TRUE;
	}else{
		hm_exit(_('Đường link đã hết hạn'));
	}
}

function admin_cp_newpw(){
	
	global $hmuser;
	$hmdb = new MySQL(true, DB_NAME, DB_HOST, DB_USER, DB_PASSWORD, DB_CHARSET);
	
	hook_action('newpw_checkkey');
	
	$key = hm_post('key');
	$password = hm_post('password');
	$password2 = hm_post('password2');
	
	if($password == $password2){
		
		$tableName=DB_PREFIX."field";
		$whereArray = array (
					'name'=>MySQL::SQLValue('lostpw_key'),
					'object_type'=>MySQL::SQLValue('user'),
					'val'=>MySQL::SQLValue($key),
				);
		$hmdb->SelectRows($tableName, $whereArray);
		$row = $hmdb->Row();
		$user_id = $row->object_id;
		$salt = rand(100000,999999);
		$password_encode = hm_encode_str(md5($password.$salt));
		
		$tableName=DB_PREFIX."users";
		$updateArray = array (
					'user_pass'=>MySQL::SQLValue($password_encode),
					'salt'=>MySQL::SQLValue($salt),
				);
		$whereArray = array (
								'id'=>MySQL::SQLValue($user_id, MySQL::SQLVALUE_NUMBER),
							);
		$hmdb->UpdateRows($tableName, $updateArray, $whereArray);
		return hm_json_encode(array( 'status'=>'success','mes'=>_('Đã đổi mật khẩu thành công') ));
		
	}else{
		return hm_json_encode(array( 'status'=>'error','mes'=>_('Hai mật khẩu bạn nhập vào không khớp') ));
	}
	
}

/** Đăng xuất admin cp */
function admin_cp_logout(){
	
	hook_action('admin_cp_logout');
	
	$back = hm_get('back',BASE_URL);
	setcookie('admin_login', $_SESSION['admin_login'], 1);
	unset($_SESSION['admin_login']);
	header('Location: '.$back);
	hm_exit('Đang thoát tài khoản');
	
}