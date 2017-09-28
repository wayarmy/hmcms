<?php
$ref = $_SERVER['HTTP_REFERER'];
$ref_domain = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
if ( strpos($ref, HM_ADMINCP_DIR) !== false AND $ref_domain == $_SERVER['SERVER_NAME']) {
    
}
?>
<link class="hm_footer_hide_in_iframe" rel="stylesheet" href="<?php echo BASE_URL . HM_FRONTENT_DIR; ?>/css/hm_footer_menu.css">
<link class="hm_footer_hide_in_iframe" rel="stylesheet" href="<?php echo BASE_URL . HM_FRONTENT_DIR; ?>/css/hm_footer.css">
<link class="hm_footer_hide_in_iframe" rel="stylesheet" href="<?php echo BASE_URL . HM_FRONTENT_DIR; ?>/css/hm_live_edit.css">
<script type="text/javascript" src="<?php echo BASE_URL . HM_FRONTENT_DIR; ?>/js/hm_footer_menu.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL . HM_FRONTENT_DIR; ?>/js/hm_live_edit.js"></script>
<div class="hm_footer_quickbar hm_footer_hide_in_iframe">
	<div class="hm_footer_wrapper">
		<div id="hm_footer_menu">
			<ul>
				<li><a href="<?php echo BASE_URL.HM_ADMINCP_DIR; ?>?run=dashboard.php"><?php echo _('Quản trị'); ?></a></li>
				<li>
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<?php echo _('Cài đặt'); ?>
					</a>
					<ul>
                        <?php
						$setting_page = get_admin_setting_page();
						foreach($setting_page as $page){
						?>
						<li><a href="<?php echo BASE_URL.HM_ADMINCP_DIR; ?>?run=setting.php&key=<?php echo $page['key']; ?>"><?php echo $page['label']; ?></a></li>
						<?php
						}
						?>
                    </ul>
				</li>
				<li><a href="<?php echo BASE_URL.HM_ADMINCP_DIR; ?>?run=media.php"><?php echo _('Thư viện'); ?></a></li>
				<?php
				if(ALLOW_UPDATE == TRUE){
				?>
				<li><a href="<?php echo BASE_URL.HM_ADMINCP_DIR; ?>?run=update.php"><?php echo _('Cập nhật'); ?></a></li>
				<?php
				}
				if(is_content()){
				?>
				<li><a href="<?php echo BASE_URL.HM_ADMINCP_DIR; ?>?run=content.php&action=edit&id=<?php echo get_id(); ?>"><?php echo _('Sửa bài này'); ?></a></li>
				<?php
				}
				if(is_taxonomy()){
				?>
				<li><a href="<?php echo BASE_URL.HM_ADMINCP_DIR; ?>?run=taxonomy.php&action=edit&id=<?php echo get_id(); ?>"><?php echo _('Sửa danh mục này'); ?></a></li>
				<?php
				}
				?>
			</ul>
		</div>
	</div>
</div>