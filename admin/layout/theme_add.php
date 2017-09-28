<!-- custom js page -->
<?php
hm_admin_js('js/theme.js');
hm_admin_css('css/theme.css');
?>

<div class="row">
	<div class="col-md-6">
	
	</div>
	<div class="col-md-6">
		<form action="" method="post">
			<input autocomplete="false" name="keyword" type="text" class="form-control" id="keyword" placeholder="<?php echo hm_lang('find_the_theme'); ?>" value="<?php echo hm_post('keyword',''); ?>">
		</form>
	</div>
</div>

<div class="list_theme_item">
	<?php	if(is_array($args)){
		foreach($args as $item){
			$name = $item->name;
			$link = $item->link;
			$description = $item->description;
			$direct_link = $item->direct_link;
			$img = $item->img;
	?>
	
		<div class="col-md-3 theme_preview">
			<div class="theme_preview_wap">
				<div class="row">
					<div class="theme_thumbnail">
						<img src="<?php echo $img; ?>" />
					</div>
				</div>
				<div class="row">
					<h3 class="theme_name"><?php echo $name ; ?></h3>
				</div>
				<div class="row">
					<span class="btn btn-default btn-xs download_theme"  data-href="<?php echo $direct_link; ?>">
						<?php echo hm_lang('install'); ?>
					</span>
				</div>
			</div>
		</div>
		

	<?php
		}	}else{		echo $args;	}
	?>
</div>