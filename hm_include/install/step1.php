<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Cài đặt HoaMai CMS </title>
	<script src="<?php echo BASE_URL; ?>admin/layout/js/jquery-2.1.3.min.js"></script>
	<script src="<?php echo BASE_URL; ?>admin/layout/bootstrap/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>admin/layout/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>admin/layout/bootstrap/css/bootstrap-theme.min.css">
	<link rel="stylesheet" id=""  href="<?php echo BASE_URL; ?>hm_include/install/layout/style.css" media="all" />
</head>
<body>
	<div id="content">
		<p class="hello_world">Chào mừng bạn đến với HoaMai CMS, chúng ta cần cài đặt để bắt đầu sử dụng mã nguồn</p>
		<h1 class="title">Bước 1: Kiểm tra cấu hình máy chủ</h1>
		<div class="form_content">
			<?php
			$check = array();
			
			if(allow_version()){
				$check['allow_version'] = '1';
				echo '<div class="alert alert-success" role="alert">Phiên bản PHP lớn hơn 5.3</div>';
			}else{
				$check['allow_version'] = '0';
				echo '<div class="alert alert-danger" role="alert">Phiên bản PHP lớn hơn 5.3</div>';
			}
			
			if($gdv = gdVersion()) {
				if ($gdv >=2) {
					$check['gdVersion'] = '1';
					echo '<div class="alert alert-success" role="alert">Phiên bản thư viện GD lớn hơn 2.0</div>';
				} else {
					$check['gdVersion'] = '0';
					echo '<div class="alert alert-danger" role="alert">Phiên bản thư viện GD lớn hơn 2.0</div>';
				}
			} else {
				$check['gdVersion'] = '0';
				echo '<div class="alert alert-danger" role="alert">Thư viện GD chưa được cài đặt</div>';
			}
			
			
			if(is_writable('hm_content/uploads')){
				$check['uploadWritable'] = '1';
				echo '<div class="alert alert-success" role="alert">Thư mục hm_content/uploads được ghi</div>';
			}else{
				if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
					$check['uploadWritable'] = '1';
					echo '<div class="alert alert-success" role="alert">Thư mục hm_content/uploads được ghi</div>';
				}else{
					$check['uploadWritable'] = '0';
					echo '<div class="alert alert-danger" role="alert">Thư mục hm_content/uploads không được ghi, vui lòng cmod 777</div>';
				}
			}
			
			if(function_exists('mcrypt_encrypt')){
				$check['mcrypt'] = '1';
				echo '<div class="alert alert-success" role="alert">Hỗ trợ hàm mcrypt_encrypt</div>';
			}else{
				$check['mcrypt'] = '0';
				echo '<div class="alert alert-danger" role="alert">Không hỗ trợ hàm mcrypt_encrypt</div>';
			}
			
			if(function_exists('mysqli_connect')){
				$check['mysqli'] = '1';
				echo '<div class="alert alert-success" role="alert">Hỗ trợ hàm mysqli_connect</div>';
			}else{
				$check['mysqli'] = '0';
				echo '<div class="alert alert-danger" role="alert">Không hỗ trợ hàm mysqli_connect</div>';
			}
			
			if(!in_array('0',$check)){
				echo '<a href="?step=2" class="btn btn-default">Bước tiếp</a>';
			}
			?>
		</div>
	</div>
</body>

</html>
