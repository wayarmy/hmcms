<!-- Tree -->
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/tree.css">
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/tree.js"></script>
<!-- custom js page -->
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/content.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/content.css">


<div class="row" >

	<?php if(hm_get('mes')=='edit_success'){ ?>
		<div class="alert alert-success" role="alert"><?php echo _('Đã lưu lại chỉnh sửa'); ?></div>
	<?php } ?>
	
	<?php if(hm_get('mes')=='add_success'){ ?>
		<div class="alert alert-success" role="alert"><?php echo _('Đã đăng bài mới'); ?></div>
	<?php } ?>
	

	<form action="?run=content_ajax.php&id=<?php echo hm_get('id'); ?>&action=edit" method="post" class="ajaxForm ajaxFormcontentEditPopup">
		
		<div class="row admin_mainbar">

			<div class="row admin_mainbar_box" data-func="hm_content_fields" data-order="<?php echo mainbar_box_order('hm_content_fields'); ?>">
				
				<p class="admin_sidebar_box_title ui-sortable-handle" data-func="hm_content_fields">Thông số</p>
				
				<div class="list-form-input">
					<?php
						$fields=$args['content_field'];
						$fields_val=$args_con['field'];
						foreach($fields as $field){
							
							if(isset($fields_val[$field['name']])){
								$field['default_value'] = $fields_val[$field['name']];
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
		
		<div class="row admin_sidebar">
			
			<div class="row admin_sidebar_box" data-func="hm_content_info" data-order="<?php echo sidebar_box_order('hm_content_info'); ?>">
				<p class="admin_sidebar_box_title" data-func="hm_content_info"><?php echo _('Thông tin'); ?></p>
				<div class="admin_mainbar_boxcontent" data-func="hm_content_info">
					<?php
						$field_array['default_value']=$fields_val['status'];
						$field_array['input_type']='select';
						$field_array['name']='status';
						$field_array['nice_name']='<span class="glyphicon glyphicon-pushpin"></span> '._('Tình trạng');
						$field_array['input_option']=array  (
															array('value'=>'public','label'=>_('Công khai')),
															array('value'=>'draft','label'=>_('Bản nháp')),
															array('value'=>'password','label'=>_('Bảo vệ bằng mật khẩu')),
															);
						build_input_form($field_array);
						unset($field_array);
					?>
					<div class="form-group input_password_content" style="display:none">
						<input name="password" type="password" class="form-control" placeholder="<?php echo _('Nhập mật khẩu để xem bài này'); ?>" value="">
					</div>
					<div class="form-group">
						<label for="status">
							<span class="glyphicon glyphicon-calendar"></span> <?php echo _('Thời gian'); ?>
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
						<p class="input_description"><?php echo _('Bạn có thể hẹn giờ đăng bài bằng cách chọn thời gian đăng bài trong tương lai'); ?></p>
					</div>
					<div class="form-group">
						<label for="revision">
							<a href="?run=revision.php&id=<?php echo hm_get('id'); ?>">
								<span class="glyphicon glyphicon-repeat"></span> <?php echo _('Lịch sử thay đổi').' ('.content_number_revision(hm_get('id')).') '; ?>
							</a>
						</label>
					</div>
					<div class="form-group">
						<label for="number_order">
							<span class="glyphicon glyphicon-sort-by-order"></span> <?php echo _('Số thứ tự'); ?>
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
								<span class="glyphicon glyphicon-repeat"></span> <?php echo _('Danh sách chapter').' ('.content_number_chapter(hm_get('id')).') '; ?>
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
				<p class="admin_sidebar_box_title" data-func="hm_content_thumbnail"><?php echo _('Ảnh đại diện'); ?></p>
				<div class="admin_mainbar_boxcontent" data-func="hm_content_thumbnail">
					<?php
						$field_array['name']='content_thumbnail';	
						$field_array['label']='Chọn ảnh đại diện';
						$field_array['imageonly']=TRUE;
						if(isset($fields_val['content_thumbnail'])){$field_array['default_value']=$fields_val['content_thumbnail'];}
						media_file_input($field_array);
						unset($field_array);
					?>
				</div>
			</div>
			
			<div class="row admin_sidebar_box" data-func="hm_content_tags" data-order="<?php echo sidebar_box_order('hm_content_tags'); ?>">
				<p class="admin_sidebar_box_title" data-func="hm_content_tags"><?php echo _('Từ khóa (cách nhau bằng dấu phẩy)'); ?></p>
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
				<p class="admin_sidebar_box_title" data-func="hm_content_action"><?php echo _('Tác vụ'); ?></p>
				<div class="admin_mainbar_boxcontent" data-func="hm_content_action">
					<div class="form-group">
						<button name="submit" type="submit" class="btn btn-primary"><?php echo $args['edit_item']; ?></button>
					</div>
				</div>
			</div>
				
		</div>
		
	
	</form>
	
</div>
