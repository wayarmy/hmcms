<?php 
define('LAYOUT_DIR', 'layout');
define('TEMPLATE_DIR', 'template');
define('ADMIN_LAYOUT_PATH', BASE_URL.HM_ADMINCP_DIR . '/' . LAYOUT_DIR);
require_once(BASEPATH . HM_ADMINCP_DIR . '/' . 'function.php');
hm_admin_css('	css/jquery-ui.css,
				bootstrap/css/bootstrap.min.css,
				bootstrap/css/bootstrap-theme.css,
				wysiwyg/summernote/summernote.css,
				wysiwyg/font-awesome/css/font-awesome.min.css,
				css/loading.css,
				css/jquery.contextMenu.css,
				css/style.css
			');
hm_admin_js('	js/jquery-2.1.3.min.js,
				js/jquery-ui.js,
				js/jquery.form.js,
				bootstrap/js/bootstrap.min.js,
				notify/notify.min.js,
				wysiwyg/summernote/summernote.js,
				wysiwyg/summernote/lang/summernote-vi-VN.js,
				js/loading.js,
				js/jquery.contextMenu.js,
				js/custom.js
			');
hm_admin_head(); 
$noti = '';
if(isset($_POST['submit'])){
	$noti = live_submit();
}
?>
<link class="hm_footer_hide_in_iframe" rel="stylesheet" href="<?php echo BASE_URL . HM_FRONTENT_DIR; ?>/css/hm_live_edit.css">

<div class="live_edit_panel">
	<?php echo $noti; ?>
	<form action="" method="post">
		<?php
			$args = array(
						'section'=>'live_editor',
						'key'=>hm_get('option_name'),
						'default_value'=>'',
					);
			$value = get_option($args);
			$args = array(
				'nice_name'=>hm_lang('new_value'),
				'name'=>hm_get('option_name'),
				'input_type'=>'editor',
				'required'=>FALSE,
				'default_value'=>$value,
			);
			build_input_form($args);
		?>
		<div class="form-group">
			<button name="submit" type="submit" class="btn btn-primary pull-right"><?php echo _('Lưu lại'); ?></button>
		</div>
	</form>
</div>



<!-- Modal Media-->
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

<!-- Modal Popup-->
<div class="modal fade" id="popup_box_modal" tabindex="-1" role="dialog" aria-labelledby="popup_box_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="popup_box_modalLabel"><?php echo _('Chỉnh sửa'); ?></h4>
            </div>
            <div class="modal-body popup_box">
				<?php win8_loading(); ?>
            </div>
        </div>
    </div>
</div>

