<!DOCTYPE html>
<html lang="en">

<head>
	
	<?php hm_head(); ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap Core CSS -->
	<?php echo css('asset/css/bootstrap.css'); ?>

    <!-- Custom CSS -->
	<?php echo css('asset/css/menu.css'); ?>
	<?php echo css('asset/css/style.css'); ?>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <!-- Page Content -->
    <div class="container">
		<div class="row">
		
			<!-- Navigation -->
			<nav class="navbar_top">
				<div class="nav_wrapper">
					
					<div id="cssmenu">
						<?php menu_location('topmenu'); ?>
					</div>
					
				</div>
				<!-- /.container -->
			</nav>
			
			<div class="banner">
			
				<?php 
				$banner = get_option( array('section'=>'theme_dong','key'=>'theme_dong_banner') );
				if(is_numeric($banner)){
					echo img($banner);
				}else{
					echo img("asset/images/banner.jpg"); 
				}
				?>
			</div>
		
		</div>