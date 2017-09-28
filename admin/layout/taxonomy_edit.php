<!-- custom js page -->
<?php
hm_admin_js('js/tree.js');
hm_admin_css('css/tree.css');
hm_admin_js('js/taxonomy.js');
hm_admin_css('css/taxonomy.css');
?>

<div class="row" >

	<?php if(hm_get('mes')=='edit_success'){ ?>
		<div class="alert alert-success" role="alert"><?php echo hm_lang('the_edit_has_been_saveds'); ?></div>
	<?php } ?>
	
	<div class="col-md-12">
		<h1 class="page_title"><?php echo $args['taxonomy_name']; ?></h1>
	</div>
	<form autocomplete="off" action="?run=taxonomy_ajax.php&id=<?php echo hm_get('id'); ?>&action=edit" method="post" class="ajaxForm ajaxFormtaxonomyEdit">
		
		<div class="col-md-9 admin_mainbar">

			<p class="page_action"><?php echo $args['edit_item']; ?></p>
			
			<div class="row admin_mainbar_box">
				
				<div class="media_btn_wap form-group">
					<button type="button" class="btn btn-default media_btn" data-toggle="modal" data-target="#media_box_modal">
						<span class="glyphicon glyphicon-picture"></span> <?php echo hm_lang('file_manager'); ?>
					</button>
					<a href="<?php echo BASE_URL.HM_ADMINCP_DIR.'/?run=taxonomy.php&key='.$args['taxonomy_key'].'&status=public'; ?>" class="btn btn-default media_btn" >
						<span class="glyphicon glyphicon-list-alt"></span> <?php echo _( hm_lang('all').' '.$args['taxonomy_name']); ?>
					</a>
					<a href="<?php echo BASE_URL.HM_ADMINCP_DIR.'/?run=taxonomy.php&key='.$args['taxonomy_key'].'&action=add'; ?>" class="btn btn-default media_btn" >
						<span class="glyphicon glyphicon-plus"></span> <?php echo _( hm_lang('add').' '.$args['taxonomy_name']); ?>
					</a>
					<a href="<?php echo request_uri('type=taxonomy&id='.hm_get('id')); ?>" class="btn btn-default media_btn" target="_blank" >
						<span class="glyphicon glyphicon-eye-open"></span> <?php echo hm_lang('view'); ?>
					</a>
				</div>
				
				<div class="list-form-input">
					<?php
						$fields=$args['taxonomy_field'];
						$fields_val=$args_tax['field'];
						taxonomy_select_parent($args['taxonomy_key'],hm_get('id'));
						foreach($fields as $field){
							
							if(isset($fields_val[$field['name']])){
								$field['default_value'] = $fields_val[$field['name']];
							}else{
								$field['default_value'] = NULL;
							}
								
							$field['object_id'] = hm_get('id');
							$field['object_type'] = 'taxonomy';
							build_input_form($field);
							
						}
					?>
				</div>
			
			</div>
			<?php
			taxonomy_box(array('taxonomy_key'=>$args['taxonomy_key'],'position'=>'left'));
			?>
		</div>
		
		<div class="col-md-3 admin_sidebar">
		
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo hm_lang('avatar'); ?></p>
				<?php
					$field_array = array();
					$field_array['name']='taxonomy_thumbnail';	
					$field_array['label']=hm_lang('choose_avatar');
					if(isset($fields_val['taxonomy_thumbnail'])){$field_array['default_value']=$fields_val['taxonomy_thumbnail'];}
					media_file_input($field_array);
					unset($field_array);
				?>
			</div>
			
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo hm_lang('information'); ?></p>
				<div class="admin_mainbar_boxcontent">
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
						<input name="password" type="password" class="form-control" placeholder="<?php echo hm_lang('enter_password_to_view_this_taxonomy'); ?>" value="">
					</div>
					<div class="form-group">
						<label for="number_order">
							<span class="glyphicon glyphicon-sort-by-order"></span> <?php echo hm_lang('numerical_order'); ?>
						</label>
						<?php
						$number_order = get_tax_val(array('name'=>'number_order','id'=>hm_get('id')));
						if(!is_numeric($number_order)){$number_order = 1;}
						?>
						<input name="number_order" id="number_order" type="number" class="form-control" placeholder="" value="<?php echo $number_order; ?>">
					</div>
				</div>
			</div>
			
			<?php
			taxonomy_box(array('taxonomy_key'=>$args['taxonomy_key'],'position'=>'right'));
			?>
			
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo hm_lang('action'); ?></p>
				<div class="form-group">
					<button name="submit" type="submit" class="btn btn-primary"><?php echo $args['edit_item']; ?></button>
				</div>
			</div>
			
		</div>
		
	</form>
	
</div>
