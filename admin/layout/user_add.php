<!-- custom js page -->
<?php
hm_admin_js('js/user.js');
hm_admin_css('css/user.css');
?>
<div class="row" >
	<div class="col-md-12">
		<h1 class="page_title"><?php echo hm_lang('account'); ?></h1>
	</div>
	<form action="?run=user_ajax.php&action=add" method="post" class="ajaxForm ajaxFormuserAdd">
		
		<div class="col-md-6 admin_user">
			<p class="page_action"><?php echo hm_lang('account_information'); ?></p>
			
			<div class="row add_user_noti">
			
			</div>
			<div class="row admin_user_box">
			
				<div class="list-form-input">
					<?php
					$args=array(
								'input_type'=>'text',
								'name'=>'user_login',
								'nice_name'=>hm_lang('user_name'),
								'description'=>hm_lang('account_name_is_used_for_login'),
								'required'=>TRUE,
								);
					build_input_form($args);
					
					
					$args=array(
								'input_type'=>'password',
								'name'=>'password',
								'nice_name'=>hm_lang('password'),
								'description'=>hm_lang('set_a_complex_password_to_protect_your_account'),
								'required'=>TRUE,
								);
					build_input_form($args);
					
					$args=array(
								'input_type'=>'password',
								'name'=>'password2',
								'nice_name'=>hm_lang('retype_the_password_again'),
								'description'=>hm_lang('retype_the_password_again_coincide_with_the_password_entered_above'),
								'required'=>TRUE,
								);
					build_input_form($args);
					
					$args=array(
								'input_type'=>'text',
								'name'=>'nicename',
								'nice_name'=>hm_lang('display_name'),
								'description'=>hm_lang('the_name_represents_you_when_displayed_on_the_website'),
								'required'=>TRUE,
								);
					build_input_form($args);
					
					$args=array(
								'input_type'=>'email',
								'name'=>'user_email',
								'nice_name'=>hm_lang('email'),
								'description'=>hm_lang('email_will_be_used_to_retrieve_the_password'),
								'required'=>TRUE,
								);
					build_input_form($args);
					
					$args=array(
								'input_type'=>'select',
								'name'=>'userrole',
								'nice_name'=>hm_lang('user_role'),
								'required'=>TRUE,
								'input_option'=>array(
													array('value'=>1,'label'=>hm_lang('administrator')),
													array('value'=>2,'label'=>hm_lang('webmaster')),
													array('value'=>3,'label'=>hm_lang('editor')),
													array('value'=>4,'label'=>hm_lang('member')),
													array('value'=>5,'label'=>hm_lang('banned_account')),
													),
								);
					build_input_form($args);
					?>
					
					<div class="form-group">
						<button name="submit" type="submit" class="btn btn-primary"><?php echo hm_lang('add_members'); ?></button>
					</div>
					
				</div>
			
			</div>
		</div>
		
		
		<div class="col-md-6 admin_user">
			<p class="page_action"><?php echo hm_lang('personal_information'); ?></p>
			<div class="row admin_user_box">
			
				<?php user_field(); ?>
			
			</div>
		</div>
		
	</form>
	
</div>
