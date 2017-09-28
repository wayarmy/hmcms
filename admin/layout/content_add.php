<!-- custom js page -->
<?php
hm_admin_js('js/tree.js');
hm_admin_css('css/tree.css');
hm_admin_js('js/content.js');
hm_admin_css('css/content.css');
?>

<script>
$(window).load(function(){
	$('input:-webkit-autofill').each(function(){
		$(this).val(" ");
		$(this).val("");
	});
});
</script>

<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo $args['content_name']; ?></h1>
		<p class="page_action"><?php echo $args['add_new_item']; ?></p>
	</div>
	<form autocomplete="off" action="?run=content_ajax.php&key=<?php echo hm_get('key'); ?>&action=add" method="post" class="ajaxForm ajaxFormcontentAdd">
		
		<div class="col-md-9 admin_mainbar">

			<div class="row admin_mainbar_box" data-func="hm_content_fields" data-order="<?php echo mainbar_box_order('hm_content_fields'); ?>">
			
				<div class="media_btn_wap form-group">
					<button type="button" class="btn btn-default media_btn" data-toggle="modal" data-target="#media_box_modal">
						<span class="glyphicon glyphicon-picture"></span> <?php echo hm_lang('file_manager'); ?>
					</button>
					<a href="<?php echo BASE_URL.HM_ADMINCP_DIR.'/?run=content.php&key='.$args['content_key'].'&status=public'; ?>" class="btn btn-default media_btn" >
						<span class="glyphicon glyphicon-list-alt"></span> <?php echo hm_lang('all').' '.$args['content_name']; ?>
					</a>
				</div>
				
				<div class="list-form-input">
					<?php
						$fields=$args['content_field'];

						foreach($fields as $field){
							build_input_form($field);
						}
					?>
				</div>
			
			</div>
			
			<?php
			content_box(array('content_key'=>$args['content_key'],'position'=>'left'));
			?>
			
		</div>
		
		<div class="col-md-3 admin_sidebar">
			
			<div class="row admin_sidebar_box" data-func="hm_content_info" data-order="<?php echo sidebar_box_order('hm_content_info'); ?>">
				<p class="admin_sidebar_box_title" data-func="hm_content_info"><?php echo hm_lang('information'); ?></p>
				<div class="admin_mainbar_boxcontent" data-func="hm_content_info">
					<?php
						$field_array = array();
						$field_array['default_value']='public';
						$field_array['input_type']='select';
						$field_array['name']='status';
						$field_array['nice_name']='<span class="glyphicon glyphicon-pushpin"></span> '.hm_lang('status');
						$field_array['input_option']=array  (
															array('value'=>'public','label'=>hm_lang('public')),
															array('value'=>'hide','label'=>hm_lang('hide')),
															array('value'=>'draft','label'=>hm_lang('draft')),
															array('value'=>'password','label'=>hm_lang('password_protection')),
															);
						build_input_form($field_array);
						unset($field_array);
					?>
					<div class="form-group input_password_content" style="display:none">
						<input autocomplete="off" name="password" type="password" class="form-control" placeholder="<?php echo hm_lang('enter_password_to_view_this_post'); ?>" value="">
					</div>
					<div class="form-group">
						<label for="status">
							<span class="glyphicon glyphicon-calendar"></span> <?php echo hm_lang('time'); ?>
						</label>
						<div class="change_content_time">
							<?php input_time(); ?>
						</div>
						<p class="input_description"><?php echo hm_lang('you_can_schedule_a_post_by_selecting_the_post_time_in_the_future'); ?></p>
					</div>
					<div class="form-group">
						<label for="number_order">
							<span class="glyphicon glyphicon-sort-by-order"></span> <?php echo hm_lang('numerical_order'); ?>
						</label>
						<input name="number_order" id="number_order" type="number" class="form-control" placeholder="" value="1">
					</div>
				</div>
			</div>
			<?php
			if($args_tax){
			?>
			<div class="row admin_sidebar_box" data-func="hm_content_taxonomy" data-order="<?php echo sidebar_box_order('hm_content_taxonomy'); ?>">
				<p class="admin_sidebar_box_title" data-func="hm_content_taxonomy"><?php echo $args_tax['taxonomy_name']; ?></p>
				<div class="admin_mainbar_boxcontent" data-func="hm_content_taxonomy">
					<ul class="taxnomy_list tree">
						<?php 
							$args['key']=$args['taxonomy_key'];
							taxonomy_checkbox_list($args); 
						?>
					</ul>
				</div>
			</div>
			<?php
			}
			?>
			<div class="row admin_sidebar_box" data-func="hm_content_thumbnail" data-order="<?php echo sidebar_box_order('hm_content_thumbnail'); ?>">
				<p class="admin_sidebar_box_title" data-func="hm_content_thumbnail"><?php echo hm_lang('avatar'); ?></p>
				<div class="admin_mainbar_boxcontent" data-func="hm_content_thumbnail">
				<?php
					$args['name']='content_thumbnail';	
					$args['label']=hm_lang('choose_avatar');
					$args['imageonly']=TRUE;
					media_file_input($args);
				?>
				</div>
			</div>
			
			<div class="row admin_sidebar_box" data-func="hm_content_tags" data-order="<?php echo sidebar_box_order('hm_content_tags'); ?>">
				<p class="admin_sidebar_box_title" data-func="hm_content_tags"><?php echo hm_lang('keywords_separated_by_commas'); ?></p>
				<div class="admin_mainbar_boxcontent" data-func="hm_content_tags">
					<div class="form-group">
						<input name="tags" type="text" class="form-control" placeholder="" value="">
					</div>
				</div>
			</div>
			
			<?php
			content_box(array('content_key'=>$args['content_key'],'position'=>'right'));
			?>
			
			<div class="row admin_sidebar_box" data-func="hm_content_action" data-order="<?php echo sidebar_box_order('hm_content_action'); ?>">
				<p class="admin_sidebar_box_title" data-func="hm_content_action"><?php echo hm_lang('action'); ?></p>
				<div class="admin_mainbar_boxcontent" data-func="hm_content_action">
					<div class="form-group">
						<button name="submit" type="submit" class="btn btn-primary"><?php echo $args['add_new_item']; ?></button>
					</div>
				</div>
			</div>
				
		</div>
		
	
	</form>
	
</div>
