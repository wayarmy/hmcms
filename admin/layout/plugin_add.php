<!-- custom js page -->
<?php
hm_admin_js('js/plugin.js');
hm_admin_css('css/plugin.css');
?>
<div class="row">
	<div class="col-md-6">
	
	</div>
	<div class="col-md-6">
		<form action="" method="post">
			<input autocomplete="false" name="keyword" type="text" class="form-control" id="keyword" placeholder="<?php echo hm_lang('find_the_plugin'); ?>" value="<?php echo hm_post('keyword',''); ?>">
		</form>
	</div>
</div>

<div class="list_plugin_item">
	<?php
	if(is_array($args)){
		foreach($args as $item){
			$name = $item->name;
			$link = $item->link;
			$description = $item->description;
			$direct_link = $item->direct_link;
			$img = $item->img;
	?>
	
		<div class="plugin_item col-md-4">
			<div class="plugin_item_wrapper">
				<div class="plugin_top_block">
					<div class="plugin_image">
						<img src="<?php echo $img; ?>" />
					</div>
					<h2>
						<?php echo $name ; ?>
					</h2>
					<p>
						<?php echo nl2br($description) ; ?>
					</p>
				</div>
				<div class="plugin_download_block">
					<span class="download_plugin_btn_inlist"  data-href="<?php echo $direct_link; ?>">
						<?php echo hm_lang('install'); ?>
					</span>
				</div>
			</div>
		</div>

	<?php
		}
	}else{
		echo $args;
	}
	?>
</div>