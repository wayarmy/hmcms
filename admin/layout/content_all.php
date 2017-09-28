<!-- custom js page -->
<?php
hm_admin_js('js/tree.js');
hm_admin_css('css/tree.css');
hm_admin_js('js/content.js');
hm_admin_css('css/content.css');
hm_admin_js('perfect-scrollbar/perfect-scrollbar.min.js');
hm_admin_css('perfect-scrollbar/perfect-scrollbar.min.css');
?>
<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo $args['content_name']; ?></h1>
	</div>
	<div class="col-md-12">
		<div class="page_action">
			<div class="pull-left">
				<span><?php echo $args['all_items']; ?></span>
				<button class="btn btn-default btn-xs content_status" data-status="public"><?php echo hm_lang('available'); ?></button>
				<button class="btn btn-default btn-xs content_status" data-status="draft"><?php echo hm_lang('trash'); ?></button>
				<a href="?run=content.php&key=<?php echo hm_get('key'); ?>&action=add" class="btn btn-default btn-xs media_btn"><?php echo $args['add_new_item']; ?></a>
			</div>
		</div>
		<div class="page_action">
			<div class="pull-left">
				<span><?php echo hm_lang('search'); ?></span>
				<input class="search_keyword" value="">
				<span><?php echo hm_lang('in'); ?></span>
				<select class="search_target css_selectbox btn btn-default btn-xs">
				<?php
				foreach($args['content_field'] as $field){
					echo '<option value="'.$field['name'].'">'.$field['nice_name'].'</option>';
				}
				?>
				</select>
				<span><?php echo hm_lang('sorted_by'); ?></span>
				<select class="search_order_by css_selectbox btn btn-default btn-xs">
				<?php
					echo '<option value="number_order">'.hm_lang('numerical_order').'</option>';
				foreach($args['content_field'] as $field){
					echo '<option value="'.$field['name'].'">'.$field['nice_name'].'</option>';
				}
				?>
				</select>
				<span><?php echo hm_lang('type'); ?></span>
				<select class="search_order css_selectbox btn btn-default btn-xs">
					<option value="ASC"><?php echo hm_lang('text_a_z'); ?></option>
					<option value="DESC"><?php echo hm_lang('text_z_a'); ?></option>
					<option value="NUMASC" selected><?php echo hm_lang('small_to_large'); ?></option>
					<option value="NUMDESC"><?php echo hm_lang('big_to_small'); ?></option>
				</select>
				<span><?php echo hm_lang('inside'); ?></span>
				<?php
				if(isset($args['taxonomy_key']) AND $args['taxonomy_key']!=''){
				?>
				<div class="search_tax_panel">
					<div class="search_tax_label btn btn-default btn-xs"><?php echo hm_lang('all'); ?></div>
					<ul class="taxnomy_list tree">
						<?php 
							$args['key']=$args['taxonomy_key'];
							taxonomy_checkbox_list($args); 
						?>
					</ul>
				</div>
				<?php
				}
				?>
			</div>
		</div>
		
		<form action="?run=content_ajax.php&key=<?php echo hm_get('key'); ?>&action=multi" method="post" class="ajaxForm ajaxFormContentAction">
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
			<div class="table_content_all">
				<table class="table table-striped content_table">
					<tr>
						<th class="th_checkall"><input type="checkbox" class="checkall" data-id="1"></th>
						<th><?php echo hm_lang('display'); ?></th>
						<th><?php echo hm_lang('numerical_order'); ?></th>
						<th><?php echo hm_lang('thumbnail'); ?></th>
						<th><?php echo hm_lang('name'); ?></th>
						<th><?php echo hm_lang('slug'); ?></th>
						<th class="th_action"><?php echo hm_lang('action'); ?></th>
					</tr>
					<tbody class="content_table_content">
						<tr><td colspan="7"><?php win8_loading(); ?></td></tr>
					</tbody>
				</table>
			</div>
		</form>
		<div class="pagination_bar"></div>
	</div>
</div>