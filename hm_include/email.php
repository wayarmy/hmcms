<?php
/** 
 * Tệp tin chứa những hàm cơ bản
 * Vị trí : hm_include/functions.php 
 */
if ( ! defined('BASEPATH')) exit('403');
 
 
/** send mail */
function hm_mail($to = NULL,$subject = NULL,$message = NULL,$attachments = NULL){
	
	$email_protocol = get_option( array('section'=>'system_setting','key'=>'email_protocol','default_value'=>'mail') );
	$from_email = get_option( array('section'=>'system_setting','key'=>'from_email') );
	$from_name = get_option( array('section'=>'system_setting','key'=>'from_name') );
	$smtp_host = get_option( array('section'=>'system_setting','key'=>'smtp_host') );
	$smtp_port = get_option( array('section'=>'system_setting','key'=>'smtp_port') );
	$smtp_secure = get_option( array('section'=>'system_setting','key'=>'smtp_secure') );
	$smtp_user = get_option( array('section'=>'system_setting','key'=>'smtp_user') );
	$smtp_pass = get_option( array('section'=>'system_setting','key'=>'smtp_pass') );
	if($email_protocol == 'mail'){
		if(function_exists('mail')){
			$mail = new PHPMailer;
			$mail->CharSet = 'UTF-8';
			$mail->setFrom($from_email, $from_name);
			$mail->addReplyTo($from_email, $from_name);
			$mail->addAddress($to);
			$mail->Subject = $subject;
			$mail->msgHTML($message);
			$mail->AltBody = strip_tags($message);
			if (!$mail->send()) {
				return $mail->ErrorInfo;
			} else {
				return TRUE;
			}
		}
	}elseif($email_protocol == 'smtp'){
		$mail = new PHPMailer;
		$mail->CharSet = 'UTF-8';
		$mail->isSMTP();
		$mail->Host = $smtp_host;
		$mail->Port = $smtp_port;
		$mail->SMTPSecure = $smtp_secure;
		$mail->SMTPAuth = true;
		$mail->Username = $smtp_user;
		$mail->Password = $smtp_pass;
		$mail->setFrom($from_email, $from_name);
		$mail->addReplyTo($from_email, $from_name);
		$mail->addAddress($to);
		$mail->Subject = $subject;
		$mail->msgHTML($message);
		$mail->AltBody = strip_tags($message);
		if (!$mail->send()) {
			return $mail->ErrorInfo;
		} else {
			return TRUE;
		}
	}
	
}

