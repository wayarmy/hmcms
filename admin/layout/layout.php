<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title><?php echo SITE_NAME; ?> - <?php echo hm_lang('admincp_title'); ?></title>
	<meta name="viewport" content="initial-scale=1">
	<?php 
	hm_admin_css('	css/jquery-ui.css,
					bootstrap/css/bootstrap.min.css,
					bootstrap/css/bootstrap-theme.css,
					wysiwyg/summernote/summernote.css,
					wysiwyg/font-awesome/css/font-awesome.min.css,
					css/loading.css,
					css/jquery.contextMenu.css,
					css/style.css
				');
	hm_admin_js('	js/jquery-2.1.3.min.js,
					js/jquery-ui.js,
					js/jquery.form.js,
					bootstrap/js/bootstrap.min.js,
					notify/notify.min.js,
					wysiwyg/summernote/summernote.js,
					wysiwyg/summernote/lang/summernote-vi-VN.js,
					js/loading.js,
					js/jquery.contextMenu.js,
					js/custom.js
				');
	hm_admin_head(); 
	?>
</head>

<body>
	<?php
	$sidebar_status = NULL;
	if( isset($_SESSION['sidebar_status']) ){
		$sidebar_status = $_SESSION['sidebar_status'];
	}
	$wrapperClass = 'class="active"';
	if($sidebar_status == 'close'){
		$wrapperClass = '';
	}
	?>
	<div id="wrapper"<?php echo $wrapperClass; ?>>
		
		<?php
			require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'sidebar.php');
		?>
		<!-- Page content -->
		<div id="page-content-wrapper">
			<!-- Keep all page content within the page-content inset div! -->
			<div class="page-content inset">
			
				<div class="row">
					<?php
						require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'topbar.php');
					?>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<header>
							<?php
								require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'header.php');
							?>
						</header>
					</div>
				</div>
				
				<div class="row min-height">
					<div class="col-md-12">
						<div class="notifications top-content"></div>
					</div>
					<div class="col-md-12">
						<div class="content-body">
							<div class="ajax-loading"></div>
							<?php
								admin_content_page();
							?>
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="col-md-12">
						<footer>
							<?php
								require_once(BASEPATH . HM_ADMINCP_DIR . '/' . LAYOUT_DIR . '/' . 'footer.php');
							?>
						</footer>
					</div>
					
				</div>
				
			</div>
		</div>
		<!-- Page content -->

	</div>
	<div class="popup_overlay"></div>
</body>