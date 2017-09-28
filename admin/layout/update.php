<!-- custom js page -->
<script src="<?php echo ADMIN_LAYOUT_PATH; ?>/js/update.js"></script>

<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo _('Trung tâm cập nhật'); ?></h1>
	</div>
	<div class="col-md-12 admin_update">
		<p class="page_action"><?php echo _('Cập nhật mã nguồn'); ?></p>
		<div class="row admin_update_box">
			<div class="list-form-input">
				<div class="row">
					<?php
					if($args['check_core'] == NULL){
						echo '<div class="bg-danger notibar">Mất kết nối đến máy chủ cập nhật</div>';
					}else{
						if(is_array($args['check_core'])){
							echo '<div class="bg-warning notibar">Đã có phiên bản mới: '.$args['check_core']['newest_name'].' <span class="btn btn-xs btn-default update_core"><i class="glyphicon glyphicon-refresh"></i> Cập nhật</span></div>';
						}elseif($args['check_core'] == TRUE){
							echo '<div class="bg-success notibar">Bạn đang sử dụng phiên bản mới nhất, bạn cũng có thể <span class="btn btn-xs btn-default update_core"><i class="glyphicon glyphicon-refresh"></i> cài đặt lại</span></div>';
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-md-12 admin_fullbar">
		<p class="page_action"><?php echo _('Cập nhật plugin'); ?></p>
		<div class="row admin_update_box">
			<div class="list-form-input">
				<div class="row">
					<?php
					foreach($args['plugin'] as $plugin_key => $plugin){
						$plugin = array_map('trim',$plugin);
						$check = update_check('plugin',$plugin_key);
						if($check == NULL){
							echo '<div class="bg-danger notibar">';
							echo $plugin['plugin_name'];
							echo ' ('.$plugin['plugin_version'].'): ';
							echo ' Mất kết nối đến máy chủ cập nhật';
							echo '</div>';
						}else{
							if($check['type'] == 'success'){
								
								if( $plugin['plugin_version_number'] == 
									$check['info']['version_number']
								){
									echo '<div class="bg-success notibar">';
									echo $plugin['plugin_name'];
									echo ' ('.$plugin['plugin_version'].'): ';
									echo ' hiện chưa có phiên bản mới';
									echo ' <span class="btn btn-xs btn-default update_plugin" data-key="'.$plugin_key.'" data-href="'.$check['info']['download'].'"><i class="glyphicon glyphicon-refresh"></i> Cài đặt lại</span>';
									echo '</div>';
								}else{
									echo '<div class="bg-warning notibar">';
									echo $plugin['plugin_name'];
									echo ' ('.$plugin['plugin_version'].'): ';
									echo ' đã có phiên bản mới '.$check['info']['version'];
									echo ' (yêu cầu phiên bản HoaMai CMS '.$check['info']['version_cms'].')';
									echo ' <span class="btn btn-xs btn-default update_plugin" data-key="'.$plugin_key.'" data-href="'.$check['info']['download'].'"><i class="glyphicon glyphicon-refresh"></i> Cập nhật</span>';
									echo '</div>';
								}
								
								
							}elseif($check['type'] == 'error'){
								echo '<div class="bg-danger notibar">';
								echo $plugin['plugin_name'];
								echo ' ('.$plugin['plugin_version'].'): ';
								echo ' '.$check['mes'].'.';
								echo '</div>';
							}
						}
					}
					?>
				</div>
			</div>
		</div>
	</div>
	
</div>

