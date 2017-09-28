<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo SITE_NAME; ?> ACP</title>
	<meta name="viewport" content="initial-scale=1">
	<!-- Jquery -->
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/jquery-2.1.3.min.js"></script>
	<!-- Jquery UI-->
	<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/jquery-ui.css">
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/jquery-ui.js"></script>
	<!-- Jquery form -->
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/jquery.form.js"></script>
	<!-- Bootstrap -->
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/bootstrap/js/bootstrap.min.js"></script>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/bootstrap/css/bootstrap.min.css">
	<!-- Optional theme -->
	<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/bootstrap/css/bootstrap-theme.min.css">
	<!-- Notify -->
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/notify/notify.min.js"></script>
	<!-- Wysiwyg -->
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/wysiwyg/summernote/summernote.js"></script>
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/wysiwyg/summernote/lang/summernote-vi-VN.js"></script>
	<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/wysiwyg/summernote/summernote.css">
	<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/wysiwyg/font-awesome/css/font-awesome.min.css">
	<!-- Loading -->
	<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/loading.css">
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/loading.js"></script>
	<!-- ContextMenu -->
	<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/jquery.contextMenu.css">
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/jquery.contextMenu.js"></script>
	<!-- Style -->
	<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/style.css">
	<!-- custom js -->
	<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/custom.js"></script>
	
	<?php hm_admin_head(); ?>
	
</head>

<body>

	<div id="wrapper_no_sidebar">
		
		<!-- Page content -->
		<div id="page-content-wrapper">
			<!-- Keep all page content within the page-content inset div! -->
			<div class="page-content inset">
				
				<div class="row">
					<header>
						<?php
							require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'header.php');
						?>
					</header>
				</div>
				
				<div class="row min-height">
					<div class="row">
						<div class="notifications top-content"></div>
					</div>
					<div class="row">
						<div class="content-body">
							<div class="ajax-loading"></div>
							<?php
								admin_content_page();
							?>
						</div>
					</div>
				</div>
				
				<div class="row">
					<footer>
						<?php
							require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'footer.php');
						?>
					</footer>
				</div>
				
			</div>
		</div>
		<!-- Page content -->

	</div>
	<div class="popup_overlay"></div>
</body>