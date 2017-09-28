<!-- custom js page -->
<?php
hm_admin_js('js/user.js');
hm_admin_css('css/user.css');
?>

<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo hm_lang('manage_member'); ?></h1>
	</div>
	
	<div class="col-md-12">
		<p class="page_action">
			<a href="?run=user.php&action=add" class="btn btn-default btn-xs media_btn"><?php echo hm_lang('add_members'); ?></a>
		</p>
		<div class="table_action">
			<div class="table_action_left">
				<select name="action" class="css_selectbox btn btn-default btn-xs">
					<option value=""><?php echo hm_lang('choose'); ?></option>
					<option value="draft"><?php echo hm_lang('ban_account'); ?></option>
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
		<div class="table_user_all">
			<table class="table table-striped user_table">
				<tr>
					<th class="th_checkall"><input type="checkbox" class="checkall" data-id="1"></th>
					<th><?php echo hm_lang('name'); ?></th>
					<th><?php echo hm_lang('user_role'); ?></th>
					<th class="th_action"><?php echo hm_lang('action'); ?></th>
				</tr>
				<tbody class="content_table_user">

				</tbody>
			</table>
		</div>
		<div class="pagination_bar"></div>
	</div>
</div>

