<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo _('Cài đặt giao diện Đông'); ?></h1>
	</div>
	<form action="" method="post">
		<div class="col-md-12">
			<p class="page_action"><?php echo _('Banner'); ?></p>
			<div class="row admin_mainbar_box">
				<?php
					$args['name']='theme_dong_banner';	
					$args['label']='Chọn banner';
					$args['imageonly']=TRUE;
					$banner = get_option( array('section'=>'theme_dong','key'=>'theme_dong_banner') );
					if(is_numeric($banner)){
						$args['default_value'] = $banner;
					}
					media_file_input($args);
				?>
			</div>
			
			<div class="row admin_mainbar_box">
				<p class="admin_sidebar_box_title"><?php echo _('Tác vụ'); ?></p>
				<div class="form-group">
					<button name="save_theme_setting" type="submit" class="btn btn-primary"><?php echo _('Lưu lại'); ?></button>
				</div>
			</div>
			
		</div>
	</form>
</div>

<!-- Modal -->
<div class="modal fade" id="media_box_modal" tabindex="-1" role="dialog" aria-labelledby="media_box_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="media_box_modalLabel"><?php echo _('Thư viện'); ?></h4>
            </div>
            <div class="modal-body media_box">
				<?php win8_loading(); ?>
            </div>
        </div>
    </div>
</div>