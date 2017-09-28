<?php
/**
 * File template hiển thị media box
 */
if ( ! defined('BASEPATH')) exit('403');
$media_group_id = media_group_id();
?>
<?php
hm_admin_js('perfect-scrollbar/perfect-scrollbar.min.js');
hm_admin_css('perfect-scrollbar/perfect-scrollbar.min.css');
hm_admin_js('js/media_tree.js');
hm_admin_css('css/tree_media.css');
hm_admin_js('js/media_box.js');
hm_admin_css('css/media_box.css');
?>


<div class="row media_box_ajax_load">

	<form action="?run=media_ajax.php&action=add_media" method="post" enctype="multipart/form-data" class="ajaxFormMedia">
	
			
		<div class="col-md-12 media_error" style="display:none">
			<div class="alert alert-danger" role="alert">
			  
			</div>
		</div>
		
		<div class="col-md-12">
			<div class="progress media-progress" style="display:none">
				<div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
					0%
				</div>
			</div>
			<div id="status"></div>
		</div>
		<div class="col-md-3">
			<div class="row">
				<input class="new_media_group col-md-9" name="name" placeholder="<?php echo hm_lang('create_folder'); ?>" />
				<button name="submit" type="button" class="btn btn-default col-md-3 submit_new_media_group"><?php echo hm_lang('add'); ?></button>
			</div>
			<div class="row">
				<ul class="media_tree media_file_scroll tree_media">
					<li data-id="0" data-folder="anh-noi-dung" class="media_tree_item media_tree_item_0" >
						<label class="radio-inline"><input type="radio" name="media_group" value="0" checked="checked" > <?php echo hm_lang('all'); ?></label>
						<ul class="media_tree_sub_group media_tree_sub_group_of_0 tree">
							<?php media_group_tree(); ?>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<div class="col-md-9">
		
			<div class="top_media_box">
				<div class="input-group media-preview">
					<input type="text" class="form-control media-preview-filename" disabled="disabled"> 
					<span class="input-group-btn">
						<button type="submit" class="btn btn-default media-submit" style="display:none;">
							<span class="glyphicon glyphicon-cloud-upload"></span> <?php echo hm_lang('upload'); ?>
						</button>
						<button type="button" class="btn btn-default media-preview-clear" style="display:none;">
							<span class="glyphicon glyphicon-remove"></span> <?php echo hm_lang('cancel'); ?>
						</button>
						<div class="btn btn-default media-preview-input">
							<span class="glyphicon glyphicon-folder-open"></span>
							<span class="media-preview-input-title"><?php echo hm_lang('select_files'); ?></span>
							<input type="file" name="file[]" multiple />
						</div>
					</span>
				</div>
			</div>
			
			<div class="content_media_box">
				
				<div class="row">
					<ol class="breadcrumb">
					<?php 
					echo '<li data-id="0">'.hm_lang('all').'</li>';
					$bre = breadcrumb_folder($media_group_id); 
					foreach($bre as $item){
						echo '<li data-id="'.$item['id'].'">'.$item['name'].'</li>';
					}
					?>
					</ol>
				</div>
			
				<div class="row">
					<div class="col-md-12 media_file_show media_file_scroll" >
						<ul class="ajax_show_media_file">
							<?php show_media_file(); ?>
						</ul>
						<div class="show_more_media_file">
							<span class="btn btn-default show_more_media_file_btn" data-id="<?php echo $media_group_id; ?>" data-page="2"><?php echo hm_lang('load_more'); ?></span>
						</div>	
					</div>
					<div class="col-md-3 media_file_info" style="display:none" >
						<div class="form-group-file-line">
							<div class="media_file_file_preview">
							
							</div>
						</div>
						<div class="form-group-file-line">
							<label for="file_dst_name"><?php echo hm_lang('file_id'); ?></label>
							<input type="text" class="form-control-file-info" id="file_id" value="">
						</div>
						<div class="form-group-file-line">
							<label for="file_dst_name"><?php echo hm_lang('file_name'); ?></label>
							<input type="text" class="form-control-file-info" id="file_dst_name" value="">
						</div>
						<div class="form-group-file-line">
							<label for="file_src_name_ext"><?php echo hm_lang('extension'); ?></label>
							<input type="text" class="form-control-file-info" id="file_src_name_ext" value="">
						</div>
						<div class="form-group-file-line">
							<label for="file_src_mime"><?php echo hm_lang('file_type'); ?></label>
							<input type="text" class="form-control-file-info" id="file_src_mime" value="">
						</div>
						<div class="form-group-file-line">
							<label for="file_src_size"><?php echo hm_lang('size'); ?></label>
							<input type="text" class="form-control-file-info" id="file_src_size" value="">
						</div>
						<div class="form-group-file-line">
							<label for="file_src"><?php echo hm_lang('direct_link'); ?></label>
							<input type="text" class="form-control-file-info" id="file_src" value="">
						</div>
						<div class="row">
							<input type="checkbox" name="checkbox_delete_media_file" class="checkbox_delete_media_file" />
							<span data-id="" class="delete_media_file"><?php echo hm_lang('delete_permanently'); ?></span>
						</div>
						
						<div class="row">
							<?php
							if($_GET['run']=='media.php'){
								$_GET['run']='media_ajax.php';
								$_GET['action']='ajax_media_box';
							}
							$query_string = http_build_query($_GET);
							?>
							<input type="hidden" id="media_query" value="<?php echo $query_string; ?>" />
							<input type="hidden" id="group_id" value="<?php echo $media_group_id; ?>" />
							<input type="hidden" id="group_parent" value="<?php echo hm_get('group_parent','0'); ?>" />
							<input type="hidden" id="use_media_file" value="<?php echo hm_get('use_media_file'); ?>" />
							<input type="hidden" id="multi" value="<?php echo hm_get('multi'); ?>" />
							<input type="hidden" id="imageonly" value="<?php echo hm_get('imageonly'); ?>" />
							
							<?php
							if(hm_get('use_media_file') != NULL AND hm_get('use_media_file') != 'undefined'){
							?>
							<button type="button" class="btn btn-default use_media_file_insert" use_media_file="<?php echo hm_get('use_media_file'); ?>" multi="<?php echo hm_get('multi'); ?>" imageonly="<?php echo hm_get('imageonly'); ?>">
								<span class="glyphicon glyphicon-pencil"></span><?php echo hm_lang('use'); ?>
							</button>
							<?php
							}
							?>
							<button type="button" class="btn btn-default hide_media_file_info">
								<span class="glyphicon glyphicon-remove"></span> <?php echo hm_lang('hide'); ?>
							</button>
						</div>
						
					</div>
				</div>
			</div>

		</div>
	</form>
	
	<div class="new_dir_input_box">
		<span class="close-icon"></span>
		<input type="text" class="new_dir_input_name col-md-9" placeholder="Tên thư mục">
		<button class="new_dir_input_button btn btn-default col-md-3 "><?php echo hm_lang('add'); ?></button>
	</div>
	
	<div class="rename_dir_input_box">
		<span class="close-icon"></span>
		<input type="text" class="rename_dir_input_name col-md-9" placeholder="Nhập tên mới">
		<button class="rename_dir_input_button btn btn-default col-md-3 "><?php echo hm_lang('rename'); ?></button>
	</div>
	
	<div class="move_file_input_box">
		<span class="close-icon"></span>
		<?php media_group_select(array('class'=>'move_file_select_group col-md-9')); ?>
		<input type="hidden" class="move_file_input_item" />
		<button class="move_file_input_button btn btn-default col-md-3 "><?php echo hm_lang('move'); ?></button>
	</div>
	
</div>

