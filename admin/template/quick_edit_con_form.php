<?php
/**
 * File template form edit nhanh 1 content
 */
?>
<!-- Tree -->
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/tree.css">
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/tree.js"></script>


<form action="?run=content_ajax.php&key=<?php echo $_GET['key']; ?>&action=edit&id=<?php echo $_GET['id']; ?>" method="post" class="ajaxForm ajaxFormcontentEdit">
	<div class="row">
		<div class="col-md-4">
			<?php
				$field = $args['content_field']['primary_name_field'];	
				$field['object_id']=$args['object_id'];
				$field['object_type']=$args['object_type']; 
				$field['default_value']=get_con_val(array('name'=>$field['name'],'id'=>$field['object_id']));
				build_input_form($field);
			?>
		</div>
		<div class="col-md-4">
			<ul class="taxnomy_list_ajax tree">
				<?php 
					$args['key']=$args['taxonomy_key'];
					$array = array(
							'id'=>$args['object_id'],
					);
					$args['default_value']=get_con_tax($array);
					taxonomy_checkbox_list($args); 
				?>
			</ul>
		</div>
		<div class="col-md-4">
			<div class="form-group">
				<label for="tags"><?php echo _('Từ khóa'); ?></label>
				<?php
					$array = array(
						'id'=>$args['object_id'],
						'sep'=>', ',
					);
					$tags = the_tags($array); 
				?>
				<textarea name="tags" id="tags" type="text" class="form-control"><?php echo $tags; ?></textarea>
			</div>
		</div>
	</div>
	<button name="submit" type="submit" class="btn btn-default"><?php echo $args['edit_item']; ?></button>
</form>