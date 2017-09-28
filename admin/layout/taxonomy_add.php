<!-- custom js page -->
<?php
hm_admin_js('js/taxonomy.js');
hm_admin_css('css/taxonomy.css');
?>
<script>
$(window).load(function(){
	$('input:-webkit-autofill').each(function(){
		$(this).val('');
	});
});
</script>

<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo $args['taxonomy_name']; ?></h1>
	</div>
	<div class="col-md-6">
		<p class="page_action"><?php echo $args['add_new_item']; ?></p>
		<form autocomplete="off" action="?run=taxonomy_ajax.php&key=<?php echo hm_get('key'); ?>&action=add" method="post" class="ajaxForm ajaxFormTaxonomyAdd">
			<div class="list-form-input">
				<?php
					$fields=$args['taxonomy_field'];
					taxonomy_select_parent($args['taxonomy_key']);
					foreach($fields as $field){
						build_input_form($field);
					}
				?>
			</div>
			<?php
			taxonomy_box(array('taxonomy_key'=>$args['taxonomy_key'],'position'=>'left'));
			?>
			<button name="submit" type="submit" class="btn btn-default"><?php echo $args['add_new_item']; ?></button>
		</form>
	</div>
	<div class="col-md-6">
		<p class="page_action">
			<span><?php echo $args['all_items']; ?></span>
			<button class="btn btn-default btn-xs taxonomy_status" data-status="public"><?php echo hm_lang('available'); ?></button>
			<button class="btn btn-default btn-xs taxonomy_status" data-status="draft"><?php echo hm_lang('trash'); ?></button>
		</p>
		
		<form autocomplete="off" action="?run=taxonomy_ajax.php&key=<?php echo hm_get('key'); ?>&action=multi" method="post" class="ajaxForm ajaxFormTaxonomyAction">
			<div class="table_action">
				<div class="table_action_left">
					<select name="action" class="css_selectbox btn btn-default btn-xs">
						<option value=""><?php echo hm_lang('choose'); ?></option>
						<option value="draft"><?php echo hm_lang('move_to_trash'); ?></option>
					</select>
					<button class="btn btn-default btn-xs multi_action" data-id="1"><?php echo hm_lang('submit'); ?></button>
				</div>
				<div class="table_action_right">
					<select data-status="public" name="perpage" class="select_perpage css_selectbox btn btn-default btn-xs">
						<option value="5"><?php echo _('5'); ?></option>
						<option value="10"><?php echo _('10'); ?></option>
						<option value="30" selected="selected"><?php echo _('30'); ?></option>
						<option value="50"><?php echo _('50'); ?></option>
						<option value="100"><?php echo _('100'); ?></option>
						<option value="500"><?php echo _('500'); ?></option>
						<option value="1000"><?php echo _('1000'); ?></option>
					</select>
				</div>
			</div>
			<div class="table_taxonomy_add">
				<table class="table table-striped taxonomy_table">
					<tr>
						<th class="th_checkall"><input type="checkbox" class="checkall" data-id="1"></th>
						<th><?php echo hm_lang('display'); ?></th>
						<th><?php echo hm_lang('name'); ?></th>
						<th><?php echo hm_lang('slug'); ?></th>
						<th><?php echo hm_lang('action'); ?></th>
					</tr>
					<tbody class="taxonomy_table_content">
						
					
					</tbody>
				</table>
			</div>
		</form>
		<div class="pagination_bar"></div>
		<?php
		taxonomy_box(array('taxonomy_key'=>$args['taxonomy_key'],'position'=>'right'));
		?>
	</div>
</div>
