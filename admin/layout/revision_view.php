<!-- custom js page -->
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/revision.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/revision.css">

<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo $args['content']->name; ?></h1>
	</div>
	<div class="col-md-12">
		
		<p class="page_action"><?php echo _('Thời gian soạn thảo').' : '.date('d-m-Y / H:i',$args['field']['public_time'])._(' - Đường dẫn tĩnh: ').$args['content']->slug; ?></p>

		<?php
		$input_array = array('id'=>$args['content']->id);
		echo build_field_content_blockquote($input_array);
		?>

	</div>
</div>