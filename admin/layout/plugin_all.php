<!-- custom js page -->
<?php
hm_admin_js('js/plugin.js');
hm_admin_css('css/plugin.css');
?>
<div class="row" >
	<div class="col-md-12">
	
		<table class="table content_table bg-white sortable">
			<tr>
				<th><?php echo hm_lang('name'); ?></th>
				<th><?php echo hm_lang('description'); ?></th>
				<th><?php echo hm_lang('action'); ?></th>
			</tr>
			<tbody class="content_table_plugin">
				<tr><td colspan="3"><?php win8_loading(); ?></td></tr>	
			</tbody>
		</table>

	</div>
</div>