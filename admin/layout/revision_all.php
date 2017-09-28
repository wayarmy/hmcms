<!-- custom js page -->
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/revision.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/revision.css">

<div class="row" >
	<div class="col-md-12">
	
		<p class="page_action"><?php echo _('Lịch sử thay đổi của ').' '.$args['content']->name; ?></p>

		<table class="table table-striped content_table">
			<tr>
				<th><?php echo _('Ngày đăng'); ?></th>
				<th><?php echo _('Tên'); ?></th>
				<th><?php echo _('Slug'); ?></th>
				<th><?php echo _('Hành động'); ?></th>
			</tr>
			<tbody class="content_table_revision">

			</tbody>
		</table>

	</div>
</div>