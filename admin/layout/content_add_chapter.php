<!-- Tree -->
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/tree.css">
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/tree.js"></script>
<!-- custom js page -->
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/content.js"></script>
<link rel="stylesheet" href="<?php echo ADMIN_LAYOUT_PATH; ?>/css/content.css">

<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo $args['content_name']; ?></h1>
	</div>
	<form action="?run=content_ajax.php&id=<?php echo hm_get('id'); ?>&action=add_chapter" method="post" class="ajaxForm ajaxFormcontentAdd">
		
		<div class="col-md-9">

			<p class="page_action"><?php echo _('Thêm chapter'); ?> : <?php echo $args_con['content']->name; ?></p>
			
			<div class="media_btn_wap form-group">
				<button type="button" class="btn btn-default media_btn" data-toggle="modal" data-target="#media_box_modal">
					<span class="glyphicon glyphicon-picture"></span> <?php echo _('Thư viện'); ?>
				</button>
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
		
		<div class="col-md-3 admin_sidebar">
			
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo _('Thông tin'); ?></p>
				<div class="form-group">
					<label for="status">
						<span class="glyphicon glyphicon-pushpin"></span> <?php echo _('Tình trạng'); ?>
					</label>
					<select name="status" id="status" class="form-control">
						<option value="public"><?php echo _('Công khai'); ?></option>
						<option value="draft"><?php echo _('Bản nháp'); ?></option>
						<option value="password"><?php echo _('Bảo vệ bằng mật khẩu'); ?></option>
					</select>
				</div>
				<div class="form-group input_password_content" style="display:none">
					<input name="password" type="password" class="form-control" placeholder="<?php echo _('Nhập mật khẩu để xem bài này'); ?>" value="">
				</div>
				<div class="form-group">
					<label for="status">
						<span class="glyphicon glyphicon-calendar"></span> <?php echo _('Thời gian'); ?>
					</label>
					<div class="change_content_time">
						<?php input_time(); ?>
					</div>
					<p class="input_description"><?php echo _('Bạn có thể hẹn giờ đăng bài bằng cách chọn thời gian đăng bài trong tương lai'); ?></p>
				</div>
			</div>
			
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo $args_tax['taxonomy_name']; ?></p>
				<ul class="taxnomy_list tree">
					<?php 
						$args['key']=$args['taxonomy_key'];
						taxonomy_checkbox_list($args); 
					?>
				</ul>
			</div>
			
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo _('Ảnh đại diện'); ?></p>
				<?php
					$args['id']='content_thumbnail';	
					$args['label']='Chọn ảnh đại diện';
					media_file_input($args);
				?>
			</div>
			
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo _('Từ khóa (cách nhau bằng dấu phẩy)'); ?></p>
				<div class="form-group">
					<input name="tags" type="text" class="form-control" placeholder="" value="">
				</div>
			</div>
			
			<div class="row admin_sidebar_box">
				<p class="admin_sidebar_box_title"><?php echo _('Tác vụ'); ?></p>
				<div class="form-group">
					<button name="submit" type="submit" class="btn btn-primary"><?php echo $args['add_new_item']; ?></button>
				</div>
			</div>
				
		</div>
		
	
	</form>
	
</div>
