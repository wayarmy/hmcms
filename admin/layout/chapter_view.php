<!-- custom js page -->
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/chapter.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/chapter.css">

<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo $args['content']->name; ?></h1>
	</div>
	<div class="col-md-12">
		
		<p class="page_action"><?php echo _('Thời gian soạn thảo').' : '.date('d-m-Y / H:i',$args['field']['public_time']); ?></p>
		
		<?php
		$input_array = array('id'=>$args['content']->id);
		$before =  build_field_content_blockquote($input_array);
		$input_array = array('id'=>$args['content']->parent);
		$after =  build_field_content_blockquote($input_array);
		foreach($before as $key => $val){
		?>
		
		<blockquote class="content_field_blockquote">
			<div class="content_field">
				<label><?php echo $val['field_name']; ?></label>
				<div class="content_field_value">
					<?php 
					$before_val = $val['field_val']; 
					$after_val = $after[$key]['field_val']; 
					echo Diff::toTable(Diff::compare($before_val, $after_val));
					?>
				</div>
			</div>
		</blockquote>
		
		<?php
		}
		?>

	</div>

</div>