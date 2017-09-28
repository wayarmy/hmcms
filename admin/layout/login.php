<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Hoa Mai AdminCP</title>
	<!-- Jquery -->
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/jquery-2.1.3.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/bootstrap/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/bootstrap/css/bootstrap-theme.min.css">
	<!-- Style -->
	<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/style.css">
	<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/login.css">
	<!-- Notify -->
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/notify/notify.min.js"></script>
	<!-- Jquery form -->
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/jquery.form.js"></script>
	<!-- custom js -->
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/login.js"></script>
</head>

<body>

	<div class="login-body">
		<article class="container-login center-block">
			<section>
				<div class="login-logo">
					<h1><?php echo SITE_NAME; ?></h1>
				</div>
				<div class="login_shadow">
					<ul id="top-bar" class="nav nav-tabs nav-justified">
						<li class="active"><a href="#">Đăng nhập</a>
						</li>
						<li><a href="?run=login.php&action=lostpw">Quên mật khẩu</a>
						</li>
					</ul>
					<div class="tab-content tabs-login col-lg-12 col-md-12 col-sm-12 cols-xs-12">
						<div id="login-access" class="tab-pane fade active in">
							<form method="post" accept-charset="utf-8" autocomplete="off" role="form" class="form-horizontal ajaxFormAdminCpLogin" action="<?php echo BASE_URL.HM_ADMINCP_DIR; ?>/?run=login_ajax.php&action=log-me-in">
								<div class="form-group ">
									<label for="login" class="visible-xs">Tài khoản</label>
									<input type="text" class="form-control" name="login" id="login_value" placeholder="Tài khoản" tabindex="1" value="" />
								</div>
								<div class="form-group ">
									<label for="password" class="visible-xs">Mật khẩu</label>
									<input type="password" class="form-control" name="password" id="password" placeholder="Mật khẩu" value="" tabindex="2" />
								</div>
								<div class="form-group ">
									<button type="submit" name="log-me-in" id="submit" tabindex="5" class="btn btn-lg" value="<?php echo time(); ?>">Đăng nhập</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</section>
		</article>
	</div>
	
</body>