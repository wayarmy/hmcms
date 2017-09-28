<!-- custom js page -->
<?php
hm_admin_js('js/tree.js');
hm_admin_css('css/tree.css');
hm_admin_js('js/content.js');
hm_admin_css('css/content.css');
?>

<div class="row" >

	<?php if(hm_get('mes')=='edit_success'){ ?>
		<div class="alert alert-success" role="alert"><?php echo hm_lang('the_edit_has_been_saved'); ?></div>
	<?php } ?>
	
	<?php if(hm_get('mes')=='add_success'){ ?>
		<div class="alert alert-success" role="alert"><?php echo hm_lang('posted_new_post'); ?></div>
	<?php } ?>
	
	<div class="col-md-12">
		<h1 class="page_title"><?php echo $args['content_name']; ?></h1>
		<p class="page_action"><?php echo $args['edit_item']; ?></p>
	</div>
	<form autocomplete="off" action="?run=content_ajax.php&id=<?php echo hm_get('id'); ?>&action=edit" method="post" class="ajaxForm ajaxFormcontentEdit">
		
		<div class="col-md-9 admin_mainbar">

			<div class="row admin_mainbar_box" data-func="hm_content_fields" data-order="<?php echo mainbar_box_order('hm_content_fields'); ?>">
			
				<div class="media_btn_wap form-group">
					<button type="button" class="btn btn-default media_btn" data-toggle="modal" data-target="#media_box_modal">
						<span class="glyphicon glyphicon-picture"></span> <?php echo hm_lang('file_manager'); ?>
					</button>
					<a href="<?php echo BASE_URL.HM_ADMINCP_DIR.'/?run=content.php&key='.$args['content_key'].'&status=public'; ?>" class="btn btn-default media_btn" >
						<span class="glyphicon glyphicon-list-alt"></span> <?php echo hm_lang('all').' '.$args['content_name']; ?>
					</a>
					<a href="<?php echo BASE_URL.HM_ADMINCP_DIR.'/?run=content.php&key='.$args['content_key'].'&action=add'; ?>" class="btn btn-default media_btn" >
						<span class="glyphicon glyphicon-plus"></span> <?php echo hm_lang('add').' '.$args['content_name']; ?>
					</a>
					<a href="<?php echo request_uri('type=content&id='.hm_get('id')); ?>" class="btn btn-default media_btn" target="_blank" >
						<span class="glyphicon glyphicon-eye-open"></span> <?php echo hm_lang('view'); ?>
					</a>
				</div>
				
				<div class="list-form-input">
					<?php
						$fields=$args['content_field'];
						$fields_val=$args_con['field'];
						foreach($fields as $field){
							
							if(isset($fields_val[$field['name']])){
								$field['default_value'] = get_con_val(array('name'=>$field['name'],'id'=>hm_get('id')));
							}else{
								$field['default_value'] = NULL;
							}
								
							$field['object_id'] = hm_get('id');
							$field['object_type'] = 'content';
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
				<p class="admin_sidebar_box_title" data-func="hm_content_info"><?php echo hm_lang('information'); ?>s</p>
				<div class="admin_mainbar_boxcontent" data-func="hm_content_info">
					<?php
						$field_array = array();
						$field_array['default_value']=$fields_val['status'];
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
						<input name="password" type="password" class="form-control" placeholder="<?php echo hm_lang('enter_password_to_view_this_post'); ?>" value="">
					</div>
					<div class="form-group">
						<label for="status">
							<span class="glyphicon glyphicon-calendar"></span> <?php echo hm_lang('time'); ?>
						</label>
						<div class="change_content_time">
							<?php 
								if(isset($fields_val['day'])) $field_array['default_value']['day'] = $fields_val['day'];
								if(isset($fields_val['month'])) $field_array['default_value']['month'] = $fields_val['month'];
								if(isset($fields_val['year'])) $field_array['default_value']['year'] = $fields_val['year'];
								if(isset($fields_val['hour'])) $field_array['default_value']['hour'] = $fields_val['hour'];
								if(isset($fields_val['minute'])) $field_array['default_value']['minute'] = $fields_val['minute'];
								input_time($field_array); 
								unset($field_array);
							?>
						</div>
						<p class="input_description"><?php echo hm_lang('you_can_schedule_a_post_by_selecting_the_post_time_in_the_future'); ?></p>
					</div>
					<div class="form-group">
						<label for="revision">
							<a href="?run=revision.php&id=<?php echo hm_get('id'); ?>">
								<span class="glyphicon glyphicon-repeat"></span> <?php echo hm_lang('history_changes').' ('.content_number_revision(hm_get('id')).') '; ?>
							</a>
						</label>
					</div>
					<div class="form-group">
						<label for="number_order">
							<span class="glyphicon glyphicon-sort-by-order"></span> <?php echo hm_lang('numerical_order'); ?>
						</label>
						<?php
						$number_order = get_con_val(array('name'=>'number_order','id'=>hm_get('id')));
						if(!is_numeric($number_order)){$number_order = 1;}
						?>
						<input name="number_order" id="number_order" type="number" class="form-control" placeholder="" value="<?php echo $number_order; ?>">
					</div>
					<?php
					if(isset($args['chapter'])){
					?>
					<div class="form-group">
						<label for="chapter">
							<a href="?run=chapter.php&id=<?php echo hm_get('id'); ?>">
								<span class="glyphicon glyphicon-repeat"></span> <?php echo hm_lang('chapter_list').' ('.content_number_chapter(hm_get('id')).') '; ?>
							</a>
						</label>
					</div>
					<?php
					}
					?>
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
							$field_array['key']=$args['taxonomy_key'];
							$field_array['object_id'] = hm_get('id');
							taxonomy_checkbox_list($field_array); 
							unset($field_array);
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
						$field_array['name']='content_thumbnail';	
						$field_array['label']=hm_lang('choose_avatar');
						$field_array['imageonly']=TRUE;
						if(isset($fields_val['content_thumbnail'])){$field_array['default_value']=$fields_val['content_thumbnail'];}
						media_file_input($field_array);
						unset($field_array);
					?>
				</div>
			</div>
			
			<div class="row admin_sidebar_box" data-func="hm_content_tags" data-order="<?php echo sidebar_box_order('hm_content_tags'); ?>">
				<p class="admin_sidebar_box_title" data-func="hm_content_tags"><?php echo hm_lang('keywords_separated_by_commas'); ?></p>
				<div class="admin_mainbar_boxcontent" data-func="hm_content_tags">
					<div class="form-group">
						<input name="tags" type="text" class="form-control" placeholder="" value="<?php if(isset($fields_val['tags'])) echo $fields_val['tags']; ?>">
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
						<button name="submit" type="submit" class="btn btn-primary"><?php echo $args['edit_item']; ?></button>
					</div>
				</div>
			</div>
				
		</div>
		
	
	</form>
	
</div>
