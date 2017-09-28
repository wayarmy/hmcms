<!-- custom js page -->
<?php
hm_admin_js('js/taxonomy.js');
hm_admin_css('css/taxonomy.css');
?>
<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo $args['taxonomy_name']; ?></h1>
	</div>
	<div class="col-md-12">
		<p class="page_action">
			<span><?php echo $args['all_items']; ?></span>
			<button class="btn btn-default btn-xs taxonomy_status" data-status="public"><?php echo hm_lang('available'); ?></button>
			<button class="btn btn-default btn-xs taxonomy_status" data-status="draft"><?php echo hm_lang('trash'); ?></button>
			<a href="?run=taxonomy.php&key=<?php echo hm_get('key'); ?>&action=add" class="btn btn-default btn-xs media_btn"><?php echo $args['add_new_item']; ?></a>
		</p>
		
		<form action="?run=taxonomy_ajax.php&key=<?php echo hm_get('key'); ?>&action=multi" method="post" class="ajaxForm ajaxFormTaxonomyAction">
			<div class="table_action">
				<div class="table_action_left">
					<select name="action" class="css_selectbox btn btn-default btn-xs">
						<option value=""><?php echo hm_lang('choose'); ?></option>
						<option value="draft"><?php echo hm_lang('move_to_trash'); ?></option>
					</select>
					<button class="btn btn-default btn-xs"><?php echo hm_lang('submit'); ?></button>
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
			<div class="table_taxonomy_all">
				<table class="table table-striped taxonomy_table">
					<tr>
						<th class="th_checkall"><input type="checkbox" class="checkall" data-id="1"></th>
						<th><?php echo hm_lang('display'); ?></th>
						<th><?php echo hm_lang('name'); ?></th>
						<th><?php echo hm_lang('slug'); ?></th>
						<th><?php echo hm_lang('action'); ?></th>
					</tr>
					<tbody class="taxonomy_table_content">
						<tr><td colspan="5"><?php win8_loading(); ?></td></tr>
					</tbody>
				</table>
			</div>
		</form>
		<div class="pagination_bar"></div>
	</div>
</div>