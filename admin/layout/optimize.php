<!-- custom js page -->
<?php
hm_admin_js('js/optimize.js');
?>

<div class="col-md-12 column">
	<div class="list-group">
		<div class="list-group-item optimize_step_1_item">
			<h4 class="list-group-item-heading">
				<?php echo hm_lang('database_optimization'); ?>
			</h4>
			<p class="list-group-item-text optimize_step_1_item_note">
				<span class="label label-danger"><?php echo hm_lang('processing_please_do_not_close_browser'); ?></span>
			</p>
			<div class="list-group-item-text optimize_step_1_item_result">
				
			</div>
		</div>
		<div class="list-group-item optimize_step_2_item">
			<h4 class="list-group-item-heading">
				<?php echo hm_lang('optimize_images'); ?>
			</h4>
			<p class="list-group-item-text optimize_step_2_item_note">
				<span class="label label-warning"><?php echo hm_lang('pending'); ?></span>
			</p>
			<div class="list-group-item-text optimize_step_2_item_result">
				
			</div>
		</div>
	</div>
</div>