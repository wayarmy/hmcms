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
		<h1 class="title">Bước 3: Thực hiện cài đặt</h1>
		<div class="form_content">
			<div class="alert alert-success" role="alert">Kiểm tra kết nối cơ sở dữ liệu (<?php echo $_SESSION['db']['database']; ?>) thành công, vui lòng nhập các thông tin cần thiết</div>
			<?php
			if(isset($error)){echo $error;}
			?>
			<form action="" method="post">
				<div class="form-group">
					<label>Tài khoản quản trị</label>
					<p class="input_des">Tài khoản dùng để đăng nhập vào trang quản trị</p>
					<input required type="text" name="admin_username" class="form-control" id="" placeholder="Tài khoản" value="">
				</div>
				<div class="form-group">
					<label>Email admin</label>
					<p class="input_des">Email để lấy lại mật khẩu</p>
					<input required type="text" name="admin_email" class="form-control" id="" placeholder="Email của bạn" value="">
				</div>
				<div class="form-group">
					<label>Mật khẩu quản trị</label>
					<p class="input_des">Mật khẩu của tài khoản quản trị</p>
					<input required type="password" name="admin_password" class="form-control" id="" placeholder="Mật khẩu" value="">
				</div>
				<div class="form-group">
					<label>Chuỗi mã hóa</label>
					<p class="input_des">Chuỗi dùng mã hóa dữ liệu, nếu bạn không chắc đây là gì, vui lòng không chỉnh sửa</p>
					<input required type="text" name="encryption_key" class="form-control" id="" placeholder="Tài khoản" value="<?php echo generateRandomString(); ?>">
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-default" name="submit" value="Cài đặt mã nguồn">
				</div>
			</form>
		</div>
	</div>
</body>

</html>
