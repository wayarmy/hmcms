<!-- custom js page -->
<?php
hm_admin_js('js/menu.js');
hm_admin_css('css/menu.css');
?>

<div class="row" >

	<?php if(hm_get('mes')=='save_success'){ ?>
		<div class="alert alert-success" role="alert"><?php echo _('Đã lưu cài đặt vị trí trình đơn'); ?></div>
	<?php } ?>

	<div class="col-md-12">
		<h1 class="page_title"><?php echo _('Quản lý vị trí trình đơn'); ?></h1>
	</div>
		
	<form action="?run=menu_ajax.php&action=location" method="post" class="ajaxFormmenuLocation">
	<div class="col-md-12">
		<div class="row admin_fullbar_box">
			<?php
				$menu_location = $hmmenu->menu_location;
				foreach($menu_location as $location){
					$location_name = $location['name'];
			?>
				<div class="col-md-6">
					<div class="form_new_menu"> 
						<label><?php echo $location['nice_name']; ?></label>
					</div>
				</div>
				<div class="col-md-6">
					<?php 
					$checked = get_option( array('section'=>'theme_setting','key'=>$location_name) );
					menu_select_choise('name=menu['.$location_name.']&checked='.$checked); 
					?>
				</div>
			<?php
				}
			?>
			<div class="col-md-12">
				<div class="form-group"> 
					<button name="submit" type="submit" class="btn btn-primary fright"><?php echo _('Lưu vị trí trình đơn'); ?></button>
				</div>
			</div>
		</div>
	</div>
	</form>

</div>