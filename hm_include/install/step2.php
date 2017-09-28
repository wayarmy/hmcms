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
		<h1 class="title">Bước 2: Kết nối tới cơ sở dữ liệu</h1>
		<div class="form_content">
			<?php
			if(isset($error)){echo $error;}
			?>
			<form action="" method="post">
				<div class="form-group">
					<label>Tên cơ sở dữ liệu</label>
					<p class="input_des">Cơ sở dữ liệu bạn sẽ dùng để cài đặt HoaMai CMS</p>
					<input type="text" name="database" class="form-control" id="" placeholder="Tên cơ sở dữ liệu" value="">
				</div>
				<div class="form-group">
					<label>Tài khoản</label>
					<p class="input_des">Tài khoản để kết nối đến database</p>
					<input type="text" name="username" class="form-control" id="" placeholder="Tài khoản" value="">
				</div>
				<div class="form-group">
					<label>Mật khẩu</label>
					<p class="input_des">Mật khẩu của tài khoản database</p>
					<input type="password" name="password" class="form-control" id="" placeholder="Mật khẩu" value="">
				</div>
				<div class="form-group">
					<label>Máy chủ database</label>
					<p class="input_des">Địa chỉ máy chủ databse, thường là localhost</p>
					<input type="text" name="host" class="form-control" id="" placeholder="Host" value="localhost">
				</div>
				<div class="form-group">
					<label>Tiền tố bảng</label>
					<p class="input_des">Tiền tố của bảng trong trường hợp bạn dùng trên 1 database đã có sẵn dữ liệu khác</p>
					<input type="text" name="prefix" class="form-control" id="" placeholder="Tiền tố" value="hm_">
				</div>
				<div class="form-group">
					<input type="submit" class="btn btn-default" name="submit" value="Kết nối">
				</div>
			</form>
		</div>
	</div>
</body>

</html>
