<?php
if(REMOVE_ADMINCP_COPYRIGHT==FALSE){
?>

<p class="footer_thank">Cảm ơn bạn đã sử dụng Hoa Mai CMS</p>
<p class="footer_version">Phiên bản <?php echo HM_VERSION_NAME; ?> </p>
<p class="footer_link">
	<a href="http://hoamaisoft.com/" target="_blank">Trang chủ</a> | 
	<a href="?run=our_team.php">Nhóm phát triển</a> | 
</p>

<?php
}
?>

<!-- Modal Media-->
<div class="modal fade" id="media_box_modal" tabindex="-1" role="dialog" aria-labelledby="media_box_modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="media_box_modalLabel"><?php echo hm_lang('file_manager'); ?></h4>
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
                <h4 class="modal-title" id="popup_box_modalLabel"><?php echo hm_lang('edit'); ?></h4>
            </div>
            <div class="modal-body popup_box">
				<?php win8_loading(); ?>
            </div>
        </div>
    </div>
</div>

