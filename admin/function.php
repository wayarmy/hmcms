<?php
/** 
 * Tệp tin function trong admin
 * Vị trí : admin/function.php 
 */
if ( ! defined('BASEPATH')) exit('403');

function hm_admin_head()
{
	hook_action('hm_admin_head');
}
function hm_admin_css($file='')
{
	hook_action('hm_admin_css');
	$ex = explode(',',$file);
	foreach($ex as $file_name){
		$file_name = trim($file_name);
		$file_part = BASEPATH.HM_ADMINCP_DIR.'/'.LAYOUT_DIR.'/'.$file_name;
		if(file_exists($file_part)){
			echo '<link rel="stylesheet" href="'.ADMIN_LAYOUT_PATH.'/'.$file_name.'">'."\n\r";
		}
	}
}
function hm_admin_js($file='')
{
	hook_action('hm_admin_js');
	$ex = explode(',',$file);
	foreach($ex as $file_name){
		$file_name = trim($file_name);
		$file_part = BASEPATH.HM_ADMINCP_DIR.'/'.LAYOUT_DIR.'/'.$file_name;
		if(file_exists($file_part)){
			echo '<script type="text/javascript" src="'.BASE_URL.HM_ADMINCP_DIR.'/?run=asset.php&ext=js&f='.$file_name.'"></script>'."\n\r";
		}
	}
}
function hm_admin_require_layout($layout_file, $args = array())
{
	hook_action('hm_admin_require_layout_before');
	$layout_file = hook_filter('hm_admin_require_layout__layout_file', $layout_file);
	$args        = hook_filter('hm_admin_require_layout__args', $args);
	extract($args);
	require_once($layout_file);
	hook_action('hm_admin_require_layout_after');
}
function admin_menu_parse_args($args = array())
{
	$defaults = array(
		'li_class' => '',
		'ul_class' => '',
		'sub_li_class' => '',
		'sub_ul_class' => '',
		'level' => ''
	);
	$args     = hm_parse_args($args, $defaults);
	$args     = hook_filter('admin_menu_parse_args', $args);
	return $args;
}
function admin_menu_con_tax($args = array())
{
	if(current_user_role('content')){
		$args = admin_menu_parse_args($args);
		global $hmtaxonomy;
		global $hmcontent;
		hook_action('admin_menu_con_tax_before');
		foreach ($hmcontent->hmcontent as $con) {
			$content_key = $con['content_key'];
			echo '<li class="'.$args['li_class'].'"><a class="material_wave">'.$con['content_name'].'<span class="sub_icon fa fa-pencil"></span></a>'."\n";
			echo '<ul class="'.$args['sub_ul_class'].'">'."\n";
			echo '<li class="'.$args['sub_li_class'].'"><a class="material_wave" href="?run=content.php&key='.$con['content_key'].'&status=public">'.$con['all_items'].'</a>'."\n";
			echo '<li class="'.$args['sub_li_class'].'"><a class="material_wave" href="?run=content.php&key='.$con['content_key'].'&action=add">'.$con['add_new_item'].'</a>'."\n";
			if(current_user_role('taxonomy')){
				foreach ($hmtaxonomy->hmtaxonomy as $tax) {
					if ($tax['content_key'] == $content_key) {
						echo '<li class="'.$args['sub_li_class'].'"><a class="material_wave" href="?run=taxonomy.php&key='.$tax['taxonomy_key'].'&status=public">'.$tax['all_items'].'</a>'."\n";
						echo '<li class="'.$args['sub_li_class'].'"><a class="material_wave" href="?run=taxonomy.php&key='.$tax['taxonomy_key'].'&action=add">'.$tax['add_new_item'].'</a>'."\n";
					}
				}
			}
			if(current_user_role('admin_page')){
				
				$admin_page   = get_admin_page();
				if(!is_array($admin_page)){
					$admin_page = array();
				}
				$funtion_call = array();
				foreach ($admin_page as $page) {
					$child_of   = FALSE;
					$key        = FALSE;
					$label      = FALSE;
					$icon       = FALSE;
					$function   = FALSE;
					$admin_menu = FALSE;
					$segment 	= array();
					if (isset($page['child_of'])) {
						$child_of = $page['child_of'];
						if($child_of!=FALSE){
							$segment = explode('/',$child_of);
						}
					}
					if (isset($page['key'])) {
						$key = $page['key'];
					}
					if (isset($page['label'])) {
						$label = $page['label'];
					}
					if (isset($page['icon'])) {
						$icon = $page['icon'];
					}
					if (isset($page['function'])) {
						$function = $page['function'];
					}
					if (isset($page['admin_menu'])) {
						$admin_menu = $page['admin_menu'];
					}
					if(sizeof($segment)>1){
						if($segment[0]=='content' AND $segment[1]==$con['content_key']){
							if (function_exists($function)) {
								if ($admin_menu) {
									$funtion_call[] = array(
										'key' => $key,
										'label' => $label,
										'icon' => $icon,
										'function' => $function
									);
								}
							}
						}
					}
				}
				
				if (sizeof($funtion_call) > 0) {
					foreach ($funtion_call as $function) {
						echo '	<li class="'.$args['sub_li_class'].'">'."\n";
						echo '		<a class="material_wave" href="?run=admin_page.php&key='.$function['key'].'">'.$function['label']."\n";
						echo '			<span class="sub_icon fa '.$function['icon'].'"></span>'."\n";
						echo '		</a>'."\n";
						admin_page_sub($function['function'], $args);
						echo '	</li>'."\n";
					}
				}
				
			}
			echo '</ul>'."\n";
			echo '</li>'."\n";
		}
		hook_action('admin_menu_con_tax_after');
	}
}
function admin_menu_user($args = array())
{
	if(current_user_role('user')){
		$args = admin_menu_parse_args($args);
		hook_action('admin_menu_user_before');
		echo '<li class="'.$args['li_class'].'"><a class="material_wave" href="?run=user.php">'.hm_lang('member').'<span class="sub_icon fa fa-user"></span></a>'."\n";
		echo '<ul class="'.$args['sub_ul_class'].'">'."\n";
		echo '<li class="'.$args['sub_li_class'].'"><a class="material_wave" href="?run=user.php">'.hm_lang('members_list').'</a>'."\n";
		echo '<li class="'.$args['sub_li_class'].'"><a class="material_wave" href="?run=user.php&action=add">'.hm_lang('add_members').'</a>'."\n";
		echo '<li class="'.$args['sub_li_class'].'"><a class="material_wave" href="?run=user.php&action=edit">'.hm_lang('edit_personal_information').'</a>'."\n";
		echo '</ul>'."\n";
		echo '</li>'."\n";
		hook_action('admin_menu_user_after');
	}
}
function admin_menu_plugin($args = array())
{
	if(current_user_role('plugin')){
		$args = admin_menu_parse_args($args);
		hook_action('admin_menu_plugin_before');
		echo '<li class="'.$args['li_class'].'"><a class="material_wave" href="?run=plugin.php">'.hm_lang('plugins').'<span class="sub_icon fa fa-puzzle-piece"></span></a>'."\n";
		echo '<ul class="'.$args['sub_ul_class'].'">'."\n";
		echo '<li class="'.$args['sub_li_class'].'"><a class="material_wave" href="?run=plugin.php">'.hm_lang('list_plugins').'</a>'."\n";
		echo '<li class="'.$args['sub_li_class'].'"><a class="material_wave" href="?run=plugin.php&action=add">'.hm_lang('add_plugin').'</a>'."\n";
		echo '</ul>'."\n";
		echo '</li>'."\n";
		hook_action('admin_menu_plugin_after');
	}
}
function admin_menu_block($args = array())
{
	if(current_user_role('block')){
		$args = admin_menu_parse_args($args);
		hook_action('admin_menu_block_before');
		if ($args['level'] == 1) {
			echo '<li class="'.$args['li_class'].'"><a class="material_wave" href="?run=block.php">'.hm_lang('block_manager').'<span class="sub_icon fa fa-cube"></span></a>'."\n";
		} else {
			echo '<li class="'.$args['sub_li_class'].'"><a class="material_wave" href="?run=block.php">'.hm_lang('block_manager').'</a>'."\n";
		}
		hook_action('admin_menu_block_after');
	}
}
function admin_menu_menu($args = array())
{
	if(current_user_role('menu')){
		$args = admin_menu_parse_args($args);
		hook_action('admin_menu_menu_before');
		if ($args['level'] == 1) {
			echo '<li class="'.$args['li_class'].'"><a class="material_wave" href="?run=menu.php">'.hm_lang('menu_manager').'<span class="sub_icon fa fa-bars"></span></a>'."\n";
		} else {
			echo '<li class="'.$args['sub_li_class'].'"><a class="material_wave" href="?run=menu.php">'.hm_lang('menu_manager').'</a>'."\n";
		}
		hook_action('admin_menu_menu_after');
	}
}
function admin_menu_theme($args = array())
{
	if(current_user_role('theme')){
		$args = admin_menu_parse_args($args);
		hook_action('admin_menu_theme_before');
		echo '<li class="'.$args['li_class'].'"><a class="material_wave" href="?run=theme.php">'.hm_lang('theme').'<span class="sub_icon fa fa-paint-brush"></span></a>'."\n";
		echo '<ul class="'.$args['sub_ul_class'].'">'."\n";
		echo '<li class="'.$args['sub_li_class'].'"><a class="material_wave" href="?run=theme.php">'.hm_lang('list_theme').'</a>'."\n";
		echo '<li class="'.$args['sub_li_class'].'"><a class="material_wave" href="?run=theme.php&action=add">'.hm_lang('add_theme').'</a>'."\n";
		admin_menu_menu($args);
		admin_menu_block($args);
		echo '</ul>'."\n";
		echo '</li>'."\n";
		hook_action('admin_menu_theme_after');
	}
}
function admin_menu_command($args = array())
{
	if(current_user_role('command')){
		$args = admin_menu_parse_args($args);
		hook_action('admin_menu_command_before');
		echo '<li class="'.$args['li_class'].'"><a class="material_wave" href="?run=command.php">'.hm_lang('command_prompt').'<span class="sub_icon fa fa-terminal"></span></a>'."\n";
		echo '</li>'."\n";
		hook_action('admin_menu_command_after');
	}
}
function admin_page($child_of = FALSE, $args = array())
{
	if(current_user_role('admin_page')){
		$args = admin_menu_parse_args($args);
		hook_action('admin_page_before');
		$admin_page = get_admin_page();
		if (!is_array($admin_page)) {
			$admin_page = array();
		}
		foreach ($admin_page as $page) {
			$child_of   = FALSE;
			$key        = FALSE;
			$label      = FALSE;
			$icon       = FALSE;
			$function   = FALSE;
			$admin_menu = FALSE;
			if (isset($page['child_of'])) {
				$child_of = $page['child_of'];
			}
			if (isset($page['key'])) {
				$key = $page['key'];
			}
			if (isset($page['label'])) {
				$label = $page['label'];
			}
			if (isset($page['icon'])) {
				$icon = $page['icon'];
			}
			if (isset($page['function'])) {
				$function = $page['function'];
			}
			if (isset($page['admin_menu'])) {
				$admin_menu = $page['admin_menu'];
			}
			if ($child_of == FALSE) {
				if (function_exists($function)) {
					if ($admin_menu) {
						echo '	<li class="'.$args['li_class'].'">'."\n";
						echo '		<a class="material_wave" href="?run=admin_page.php&key='.$key.'">'.$label."\n";
						echo '			<span class="sub_icon fa '.$icon.'"></span>'."\n";
						echo '		</a>'."\n";
						admin_page_sub($function, $args);
						echo '	</li>'."\n";
					}
				}
			}
		}
		hook_action('admin_page_after');
	}
}
function admin_page_sub($parent_function, $args = array())
{
	if(current_user_role('admin_page')){
		$args = admin_menu_parse_args($args);
		hook_action('admin_page_sub_before');
		$admin_page   = get_admin_page();
		$funtion_call = array();
		foreach ($admin_page as $page) {
			$child_of   = FALSE;
			$key        = FALSE;
			$label      = FALSE;
			$icon       = FALSE;
			$function   = FALSE;
			$admin_menu = FALSE;
			if (isset($page['child_of'])) {
				$child_of = $page['child_of'];
			}
			if (isset($page['key'])) {
				$key = $page['key'];
			}
			if (isset($page['label'])) {
				$label = $page['label'];
			}
			if (isset($page['icon'])) {
				$icon = $page['icon'];
			}
			if (isset($page['function'])) {
				$function = $page['function'];
			}
			if (isset($page['admin_menu'])) {
				$admin_menu = $page['admin_menu'];
			}
		
			if ($child_of == $parent_function) {
				if (function_exists($function)) {
					if ($admin_menu) {
						$funtion_call[] = array(
							'key' => $key,
							'label' => $label,
							'icon' => $icon,
							'function' => $function
						);
					}
				}
			}
		}
		
		if (sizeof($funtion_call) > 0) {
			echo '<ul class="'.$args['sub_ul_class'].'">'."\n";
			foreach ($funtion_call as $function) {
				echo '	<li class="'.$args['sub_li_class'].'">'."\n";
				echo '		<a class="material_wave" href="?run=admin_page.php&key='.$function['key'].'">'.$function['label']."\n";
				echo '			<span class="sub_icon fa '.$function['icon'].'"></span>'."\n";
				echo '		</a>'."\n";
				admin_page_sub($function['function'], $args);
				echo '	</li>'."\n";
			}
			echo '</ul>'."\n";
		}
		hook_action('admin_page_sub_after');
	}
}
function hm_admin_topnavbar()
{
	hook_action('before_hm_admin_topnavbar');
	
		echo '<li><a href="?run=dashboard.php"><span class="glyphicon glyphicon-home"></span>'.hm_lang('dashboard').'</a></li>';
		if(current_user_role('setting')){
		echo '<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">
					<span class="glyphicon glyphicon-list-alt"></span>
					'.hm_lang('settings').'<b class="caret"></b>
				</a>
				<ul class="dropdown-menu">';
					$setting_page = get_admin_setting_page();
					foreach($setting_page as $page){
		echo 		'<li><a href="?run=setting.php&key='.$page['key'].'">'.$page['label'].'</a></li>';
					}
		echo 	'</ul>
			  </li>';
		}
		if(current_user_role('media')){
		echo '<li><a href="?run=media.php"><span class="glyphicon glyphicon-picture"></span>'.hm_lang('file_manager').'</a></li>';
		}
		if(ALLOW_UPDATE == TRUE){
			if(current_user_role('update')){
		echo '<li><a href="?run=update.php"><span class="glyphicon glyphicon-refresh"></span>'.hm_lang('update').'</a></li>';
			}
		}
		echo '<li><a href="'.BASE_URL.'" target="_blank"><span class="glyphicon glyphicon-eye-open"></span>'.hm_lang('view_website').'</a></li>';
	
	hook_action('after_hm_admin_topnavbar');
}
function admin_menu_optimize($args = array())
{
	if(current_user_role('optimize')){
		$args = admin_menu_parse_args($args);
		hook_action('admin_menu_optimize_before');
		echo '<li class="'.$args['li_class'].'"><a class="material_wave" href="?run=optimize.php">'.hm_lang('optimize').'<span class="sub_icon fa fa-eraser"></span></a>'."\n";
		echo '</li>'."\n";
		hook_action('admin_menu_optimize_after');
	}
}