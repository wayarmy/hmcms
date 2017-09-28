<!-- custom js page -->
<?php
hm_admin_js('js/block.js');
hm_admin_css('css/block.css');
hm_admin_css('css/animate.css');
?>
<div class="row">

	<div class="col-md-4">
		<p class="page_action"><?php echo hm_lang('block_list'); ?></p>

		<div class="block_container"> 
			<div class="block_container_name"><?php echo hm_lang('available'); ?></div>
			<div class="block_container_content_from">
				<?php
				$blocks = $hmblock->block;
				foreach($blocks as $block){
					$nice_name = $block['nice_name'];
					$name = $block['name'];
					$input = $block['input'];
				?>
				<div class="block_item" data-func="<?php echo $name; ?>">
					<div class="block_item_title" data-func="<?php echo $name; ?>"><?php echo $nice_name; ?></div>
					<div class="block_item_content" data-func="<?php echo $name; ?>">
						<form autocomplete="off" action="?run=block_ajax.php&action=update_block_value" class="ajaxForm ajaxFormBlockUpdate" method="post">
							<?php
							
							/** block input */
							foreach($input as $args){
								build_input_form($args);
							}
							
							/** block input system*/
							$args = array(
								'name'=>'block_func',
								'input_type'=>'hidden',
								'default_value' => $name,
							);
							build_input_form($args);
							
							$args = array(
								'name'=>'block_container',
								'input_type'=>'hidden',
							);
							build_input_form($args);
							
							$args = array(
								'name'=>'block_order',
								'input_type'=>'hidden',
							);
							build_input_form($args);
							
							$args = array(
								'name'=>'block_id',
								'input_type'=>'hidden',
							);
							build_input_form($args);
							
							?>
							<div class="form-group">
								<span class="remove_block_btn pull-left" data-func="<?php echo $name; ?>"><?php echo hm_lang('delete'); ?></span>
								<button name="submit" type="submit" class="btn btn-primary pull-right"><?php echo hm_lang('save'); ?></button>
							</div>
							
						</form>
					</div>
				</div>
				<?php
				}
				?>
				
			</div>
		</div>
			
	</div>
	
	<div class="col-md-8">
		<p class="page_action"><?php echo hm_lang('location_on_the_theme'); ?></p>
		<div class="row">
			<?php
				$block_container = $hmblock->block_container;
				foreach($block_container as $container){
					$container_name = $container['name'];
					$container_nice_name = $container['nice_name'];
			?>
				<div class="col-md-6">
					<div class="block_container"> 
						<div class="block_container_name">
							<?php echo $container_nice_name; ?>
							<span class="loading_icon pull-right"></span>
						</div>
						<div class="block_container_content block_container_content_to" data-container="<?php echo $container_name; ?>">
						
							<?php
							$isset_blocks = get_block($container_name);
							$block_order = 1;
							foreach($isset_blocks as $block){
								$block_id = $block['id'];
								$block_func = $block['func'];
								if(isset($blocks[$block_func])){
									$nice_name = $blocks[$block_func]['nice_name'];
									$input = $blocks[$block_func]['input'];
									$box_val_name = '';
									if(
										isset($blocks[$block_func]['box_val_name'])
										AND $blocks[$block_func]['box_val_name']!=''
										AND function_exists($blocks[$block_func]['box_val_name'])
									){
										$box_val_name = $blocks[$block_func]['box_val_name'];
										$box_val_name = call_user_func($box_val_name,$block_id);
									}
							?>
							<div class="block_item" data-func="<?php echo $block_func; ?>" data-container="<?php echo $container_name; ?>" data-id="<?php echo $block_id; ?>">
								<div class="block_item_title" data-func="<?php echo $block_func; ?>" data-container="<?php echo $container_name; ?>" data-id="<?php echo $block_id; ?>">
									<?php echo $nice_name; ?>
									<p class="block_item_description"><?php echo $box_val_name; ?></p>
								</div>
								<div class="block_item_content" data-func="<?php echo $block_func; ?>" data-container="<?php echo $container_name; ?>" data-id="<?php echo $block_id; ?>">
									<form autocomplete="off" action="?run=block_ajax.php&action=update_block_value" class="ajaxForm ajaxFormBlockUpdate" method="post">
										<?php
										
										/** block input */
										foreach($input as $args){
											$args['default_value'] = get_blo_val(array('name'=>$args['name'],'id'=>$block_id));
											build_input_form($args);
										}
										
										/** block input system*/
										$args = array(
											'name'=>'block_func',
											'input_type'=>'hidden',
											'default_value' => $name,
										);
										build_input_form($args);
										
										$args = array(
											'name'=>'block_container',
											'input_type'=>'hidden',
											'default_value' => $container_name,
										);
										build_input_form($args);
										
										$args = array(
											'name'=>'block_order',
											'input_type'=>'hidden',
											'default_value' => $block_order,
										);
										build_input_form($args);
										
										$args = array(
											'name'=>'block_id',
											'input_type'=>'hidden',
											'default_value'=>$block_id,
										);
										build_input_form($args);
										
										?>
										<div class="form-group">
											<span class="remove_block_btn pull-left" data-func="<?php echo $block_func; ?>" data-container="<?php echo $container_name; ?>" data-id="<?php echo $block_id; ?>"><?php echo hm_lang('delete'); ?></span>
											<button name="submit" type="submit" class="btn btn-primary pull-right"><?php echo hm_lang('save'); ?></button>
										</div>
									</form>
								</div>
							</div>
							<?php
								$block_order++;
								}
							}
							?>
							
						</div>
					</div>
				</div>
			<?php
				}
			?>
		</div>
	</div>

</div>