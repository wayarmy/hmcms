<!-- custom js page -->
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/chapter.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/chapter.css">

<div class="row" >
	<div class="col-md-12">
	
		<p class="page_action"><?php echo _('Chapter của ').' '.$args['content']->name; ?></p>

		<table class="table table-striped content_table sortable">
			<tr>
				<th><?php echo _('Ngày đăng'); ?></th>
				<th><?php echo _('Tên'); ?></th>
				<th><?php echo _('Slug'); ?></th>
				<th><?php echo _('Hành động'); ?></th>
			</tr>
			<tbody class="content_table_chapter">

			</tbody>
		</table>

	</div>
</div>