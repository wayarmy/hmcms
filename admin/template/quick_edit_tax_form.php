<?php
/**
 * File template form edit nhanh 1 taxonomy
 */
?>
<form action="?run=taxonomy_ajax.php&key=<?php echo hm_get('key'); ?>&action=edit&id=<?php echo hm_get('id'); ?>" method="post" class="ajaxForm ajaxFormTaxonomyEdit">
	<?php
		taxonomy_select_parent($args['taxonomy_key'],$args['object_id']);
		foreach($args['taxonomy_field'] as $field){
			$field['object_id']=$args['object_id'];
			$field['object_type']=$args['object_type']; 
			$field['default_value']=get_tax_val(array('name'=>$field['name'],'id'=>$field['object_id']));
			build_input_form($field);
		}
	?>
	<button name="submit" type="submit" class="btn btn-default"><?php echo $args['edit_item']; ?></button>
	<span class="btn btn-danger close_quick_edit"><?php echo _('Đóng'); ?></span>
</form>
<script type="text/javascript">
	$('.ajaxFormTaxonomyEdit').ajaxForm(function(data) {
		$('.quick_edit_tr').remove();
		$('.custom_paged').click();
		$.notify('Đã lưu lại chỉnh sửa', { globalPosition: 'top right',className: 'success' } );
	});
	$('.close_quick_edit').click(function(){
		var id = '<?php echo hm_get('id'); ?>';
		$('.quick_edit_tr'+id).remove();
	});
</script>