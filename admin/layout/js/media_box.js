$(document).ready(function(){
	
	$('.submit_new_media_group').click(function(e) {
		var group_name = $('input.new_media_group').val();
		if(group_name==''){
			$(".new_media_group").notify('Vui lòng nhập tên thư mục muốn tạo', { className: 'warning' });
			event.preventDefault(e);
		}else{
			var group_id = $('#group_id').val();
			$('.new_dir_input_box').hide();
			$.post( '?run=media_ajax.php&action=add_media_group', { group_name: group_name, group_parent: group_id })
				.done(function( data ) {
				var obj = jQuery.parseJSON(data);
				var id = obj.id;
				var use_media_file = $('#use_media_file').val();
				var multi = $('#multi').val();
				var imageonly = $('#imageonly').val();
				$('.media_file_show').html('{lang:loading}');
				$('.media_box').load('?run=media_ajax.php&action=ajax_media_box&media_group_id='+id+'&use_media_file='+use_media_file+'&multi='+multi+'&imageonly='+imageonly);
			});
			
		}
	});	
	
	$('.new_dir_input_button').click(function(e) {
		var group_name = $('input.new_dir_input_name').val();
		if(group_name==''){
			$(".new_dir_input_name").notify('Vui lòng nhập tên thư mục muốn tạo', { className: 'warning' });
			event.preventDefault(e);
		}else{
			var group_id = $('#group_id').val();
			$('.new_dir_input_box').hide();
			$.post( '?run=media_ajax.php&action=add_media_group', { group_name: group_name, group_parent: group_id })
				.done(function( data ) {
				var obj = jQuery.parseJSON(data);
				var id = obj.id;
				var use_media_file = $('#use_media_file').val();
				var multi = $('#multi').val();
				var imageonly = $('#imageonly').val();
				$('.media_file_show').html('{lang:loading}');
				$('.media_box').load('?run=media_ajax.php&action=ajax_media_box&media_group_id='+id+'&use_media_file='+use_media_file+'&multi='+multi+'&imageonly='+imageonly);
			});
			
		}
	});	
	
	$('.rename_dir_input_button').click(function(e) {
		var group_name = $('input.rename_dir_input_name').val();
		if(group_name==''){
			$(".rename_dir_input_name").notify('Vui lòng nhập tên thư mục', { className: 'warning' });
			event.preventDefault(e);
		}else{
			var group_id = $(this).attr('group_id');
			$.post( '?run=media_ajax.php&action=rename_media_group', { group_name: group_name, group_id: group_id })
				.done(function( data ) {
				var media_query = $('#media_query').val();
				$('.media_file_show').html('{lang:loading}');
				$('.media_box').load('?'+media_query);
			});
		}
	});	
	
	(function() {
    
		var bar = $('.progress-bar');
		var percent = $('.progress-bar');
		   
		$('.ajaxFormMedia').ajaxForm({
			beforeSend: function() {
				var percentVal = '0%';
				bar.width(percentVal)
				percent.html(percentVal);
			},
			uploadProgress: function(event, position, total, percentComplete) {
				$('.media-progress').fadeIn();
				var percentVal = percentComplete + '%';
				bar.width(percentVal)
				percent.html(percentVal);
			},
			success: function() {
				var percentVal = '100%';
				bar.width(percentVal)
				percent.html(percentVal);
			},
			complete: function(xhr) {
				$('.media-progress').fadeOut();
				clear_media_input();
				var data = xhr.responseText;
				var obj = jQuery.parseJSON(data);
				var status = obj.status;
				var content = obj.content;
				var media_group = obj.media_group;
				var use_media_file = $('#use_media_file').val();
				var multi = $('#multi').val();
				var imageonly = $('#imageonly').val();
				if(status=='error'){
					$('.media_error .alert').html(content);
					$('.media_error').show();
				};
				if(status=='success'){
					$('.media_file_show').html('{lang:loading}');
					$('.media_box').load('?run=media_ajax.php&action=ajax_media_box&media_group_id='+media_group+'&use_media_file='+use_media_file+'&multi='+multi+'&imageonly='+imageonly);
				};
			}
		}); 

	})(); 
	
	$(function() {
		// Create the close button
		var closebtn = $('<button/>', {
			type:"button",
			text: 'x',
			id: 'close-preview',
			style: 'font-size: initial;',
		});
		closebtn.attr("class","close pull-right");
		// Set the popover default content
		$('.media-preview').popover({
			trigger:'manual',
			html:true,
			placement:'bottom'
		});
		// Clear event
		$('.media-preview-clear').click(function(){
			clear_media_input();
		}); 
		// Create the preview image
		$(".media-preview-input input:file").change(function (){
			var file_name = [];
			$.each( this.files, function( number , value ) {
				file_name.push(value.name);
			});
			file_name = file_name.join(', ');
			$(".media-preview-clear").show();
			$(".media-submit").show();
			$(".media-preview-filename").val(file_name); 
		});  
	});
	
	function clear_media_input(){
		$('.media-preview-filename').val("");
		$('.media-preview-clear').hide();
		$(".media-submit").hide();
		$('.media-preview-input input:file').val("");
		$(".media-preview-input-title").text("Chọn file"); 
	}
	

	$('.hide_media_file_info').click(function(){
		$('.media_file_info').hide();
		$('.media_file_show').switchClass('col-md-9','col-md-12');
	});
	
	$('span.delete_media_file').click(function(){
		if ($('input.checkbox_delete_media_file').is(':checked')) {
			var id = $(this).attr('data-id');
			var media_group = $('input[name=media_group]:checked').val();
			var use_media_file = $('#use_media_file').val();
			var multi = $('#multi').val();
			var imageonly = $('#imageonly').val();
			$.post( '?run=media_ajax.php&action=delete_media', { id:id })
				.done(function( data ) {
				if($.isNumeric(media_group)==true){
					$('.media_file_show').html('{lang:loading}');
					$('.media_box').load('?run=media_ajax.php&action=ajax_media_box&media_group_id='+media_group+'&use_media_file='+use_media_file+'&multi='+multi+'&imageonly='+imageonly);
				}else{
					$('.media_file_show').html('{lang:loading}');
					$('.media_box').load('?run=media_ajax.php&action=ajax_media_box&media_group_id&use_media_file='+use_media_file+'&multi='+multi+'&imageonly='+imageonly);
				}
			});
		}else{
			alert('Check vào ô bên cạnh để xác nhận xóa, lưu ý: không thể khôi phục sau khi xóa');
		}
	});
	
	$('.media_file_scroll').perfectScrollbar();
	
	$('input[name=media_group]').click(function(){
		var media_group_id = $(this).val();
		var use_media_file = $('#use_media_file').val();
		var multi = $('#multi').val();
		var imageonly = $('#imageonly').val();
		$('.media_file_show').html('{lang:loading}');
		$('.media_box').load('?run=media_ajax.php&action=ajax_media_box&media_group_id='+media_group_id+'&use_media_file='+use_media_file+'&multi='+multi+'&imageonly='+imageonly);
	});
	
	
	
	$('.content_media_box .breadcrumb li').click(function(){
		var media_group_id = $(this).attr('data-id');
		var use_media_file = $('#use_media_file').val();
		var multi = $('#multi').val();
		var imageonly = $('#imageonly').val();
		$('.media_file_show').html('{lang:loading}');
		$('.media_box').load('?run=media_ajax.php&action=ajax_media_box&media_group_id='+media_group_id+'&use_media_file='+use_media_file+'&multi='+multi+'&imageonly='+imageonly);
	});
	
	$('.show_more_media_file_btn').click(function(){
		var page = $(this).attr('data-page');
		var media_group_id = $(this).attr('data-id');
		var use_media_file = $('#use_media_file').val();
		var multi = $('#multi').val();
		var imageonly = $('#imageonly').val();
		var href = '?run=media_ajax.php&action=show_media_file&page='+page;
		$.post( href, { page:page }, function( data ) {
			$('.ajax_show_media_file').append(data);
			var newpage = 1 + parseInt(page);
			$('.show_more_media_file_btn').attr('data-page',newpage);
		});	
	});
	
	$('.folder_item').click(function(){
		var media_group_id = $(this).attr('folder_id');
		var use_media_file = $('#use_media_file').val();
		var multi = $('#multi').val();
		var imageonly = $('#imageonly').val();
		$('.media_file_show').html('{lang:loading}');
		$('.media_box').load('?run=media_ajax.php&action=ajax_media_box&media_group_id='+media_group_id+'&use_media_file='+use_media_file+'&multi='+multi+'&imageonly='+imageonly);
	});

	$(function(){
		$.contextMenu({
			selector: '.media_file_show', 
			callback: function(key, options) {
				if(key == 'upload'){
					$('.media-preview-input input[type=file]').click();
				};
				if(key == 'new_dir'){
					var use_media_file = $('#use_media_file').val();
					var multi = $('#multi').val();
					var imageonly = $('#imageonly').val();
					$('.new_dir_input_box').fadeIn();
					$('.new_dir_input_box .new_dir_input_name').focus();
				};
			},
			items: {
				"new_dir": {name: "Tạo thư mục", icon: "new_dir"},
				"upload": {name: "Tải tệp tin lên", icon: "upload"},
			}
		});
	});
	
	$(function(){
		$.contextMenu({
			selector: '.folder_item', 
			callback: function(key, options) {
				if(key == 'open'){
					$(this).click();
				};
				if(key == 'delete'){
					var group_id = $(this).attr('folder_id');
					var r = confirm("Xóa thư mục này sẽ xóa tất cả tệp tin và thư mục con trong nó, bạn không thể phục hồi, xác nhận xóa ?");
					if (r == true) {
						$.post( '?run=media_ajax.php&action=del_media_group', { group_id : group_id })
							.done(function( data ) {
								var media_query = $('#media_query').val();
								$('.media_file_show').html('{lang:loading}');
								$('.media_box').load('?'+media_query);
						});
					}
				};
				if(key == 'rename'){
					var group_id = $(this).attr('folder_id');
					var folder_name = $(this).attr('folder_name');
					$('.rename_dir_input_box').fadeIn();
					$('.rename_dir_input_box .rename_dir_input_name').val(folder_name);
					$('.rename_dir_input_box .rename_dir_input_name').focus();
					$('.rename_dir_input_box .rename_dir_input_button').attr('group_id',group_id);
				};
			},
			items: {
				"open": {name: "Mở", icon: "open"},
				"delete": {name: "Xóa thư mục", icon: "del_dir"},
				"rename": {name: "Đổi tên thư mục", icon: "rename_dir"},
			}
		});
	});
	
	$(function(){
		$.contextMenu({
			selector: '.file_item', 
			callback: function(key, options) {
				if(key == 'delete'){
					var number_checked = $('.file_deep_checkbox:checked').size();
					if(number_checked > 0){
						
						var r = confirm('Bạn sắp xóa '+number_checked+' tệp tin, bạn có chắc không?');
						if (r == true) {
							var allVals = [];
							$('.file_deep_checkbox:checked').each(function() {
							   allVals.push($(this).val());
							});
							var href = '?run=media_ajax.php&action=multi_delete_media';
							$.post( href, { ids:allVals }, function( data ) {
								var media_query = $('#media_query').val();
								$('.media_file_show').html('{lang:loading}');
								$('.media_box').load('?'+media_query);
							});
						}
						
					}else{
						var id = $(this).attr('file_id');
						var href = '?run=media_ajax.php&action=delete_media';
						$.post( href, { id:id }, function( data ) {
							var media_query = $('#media_query').val();
							$('.media_file_show').html('{lang:loading}');
							$('.media_box').load('?'+media_query);
						});
					}
				};
				
				if(key == 'move'){
					var number_checked = $('.file_deep_checkbox:checked').size();
					if(number_checked > 0){
						var allVals = [];
						$('.file_deep_checkbox:checked').each(function() {
						   allVals.push($(this).val());
						});
						var id = allVals.toString();
						var type = 'multi';
					}else{
						var id = $(this).attr('file_id');
						var type = 'single';
					}
					$('.move_file_input_box .move_file_input_item').val(id);
					$('.move_file_input_box .move_file_input_item').attr('data-type',type);
					$('.move_file_input_box').fadeIn();
				};
				
			},
			items: {
				"move": {name: "Di chuyển tệp tin", icon: "move"},
				"delete": {name: "Xóa tệp tin", icon: "delete"},
			}
		});
	});
	
	$('.move_file_input_box .move_file_input_button').click(function(){
		var id = $('.move_file_input_box .move_file_input_item').val();
		var type = $('.move_file_input_box .move_file_input_item').attr('data-type');
		var group_id = $('.move_file_input_box .media_group_select').val();
		if(type == 'multi'){
			var number_checked = $('.file_deep_checkbox:checked').size();
			var r = confirm('Bạn sắp di chuyển '+number_checked+' tệp tin, bạn có chắc không?');
			if (r == true) {
				var href = '?run=media_ajax.php&action=move_media';
				$.post( href, { id:id,group_id:group_id }, function( data ) {
					var media_query = $('#media_query').val();
					$('.media_file_show').html('{lang:loading}');
					$('.media_box').load('?'+media_query);
				});
			}
		}else{
			var href = '?run=media_ajax.php&action=move_media';
			$.post( href, { id:id,group_id:group_id }, function( data ) {
				var media_query = $('#media_query').val();
				$('.media_file_show').html('{lang:loading}');
				$('.media_box').load('?'+media_query);
			});
		}
	});
	
	$('.new_dir_input_box .close-icon').click(function(){
		$('.new_dir_input_box').fadeOut();
	});
	
	$('.rename_dir_input_box .close-icon').click(function(){
		$('.rename_dir_input_box').fadeOut();
	});
	
	$('.move_file_input_box .close-icon').click(function(){
		$('.move_file_input_box').fadeOut();
	});
	 
	$('[data-toggle="tooltip"]').tooltip();
	
	var reset_waiting_input = function(){
		$('input[waiting=1]').removeAttr('waiting');
		$('.preview_media_file[waiting=1]').removeAttr('waiting');
		$('.use_media_file[waiting=1]').removeAttr('waiting');
	}
	
	$('.use_media_file_insert').click(function(){
		
		
		var file_id = $('#file_id').val();
		var use_media_file = $(this).attr('use_media_file');
		var multi = $(this).attr('multi');
		if(multi=='true'){
			var thisval = $('input[use_media_file="'+use_media_file+'"][waiting=1]').val();
			if(thisval != ''){
				var newval = thisval+','+file_id;
				$('input[use_media_file="'+use_media_file+'"][waiting=1]').val(newval);
			}else{
				$('input[use_media_file="'+use_media_file+'"][waiting=1]').val(file_id);
			}
			$('button[data-dismiss=modal]').click();
			$.post( '?run=media_ajax.php&action=thumbnail_media', { id:file_id })
				.done(function( data ) {
				var thumb = '<div class="preview_media_file_wapper" file-id="'+file_id+'" use_media_file="'+use_media_file+'">\
							 <div class="preview_media_file_remove" file-id="'+file_id+'" use_media_file="'+use_media_file+'"><i class="fa fa-remove"></i></div>\
								<img src="'+data+'" file-id="'+file_id+'" />\
						 	 </div>';
				$('.preview_media_file[use_media_file="'+use_media_file+'"][waiting=1]').append(thumb);
				console.log('.preview_media_file[use_media_file="'+use_media_file+'"][waiting=1]');
				
				reset_waiting_input();
				
			});
		}
		if(multi=='false'){
			$('input[use_media_file="'+use_media_file+'"][waiting=1]').val(file_id);
			$('button[data-dismiss=modal]').click();
			$.post( '?run=media_ajax.php&action=thumbnail_media', { id:file_id })
				.done(function( data ) {
				var thumb = '<div class="preview_media_file_wapper" file-id="'+file_id+'" use_media_file="'+use_media_file+'">\
							 <div class="preview_media_file_remove" file-id="'+file_id+'" use_media_file="'+use_media_file+'"><i class="fa fa-remove"></i></div>\
								<img src="'+data+'" file-id="'+file_id+'" />\
						 	 </div>';
				$('.preview_media_file[use_media_file="'+use_media_file+'"][waiting=1]').html(thumb);
				
				reset_waiting_input();
				
			});
		}
		
	});
	
	//multi click
	var cntrlIsPressed = false;
	$(document).keydown(function(event){
    if(event.which=="17")
		cntrlIsPressed = true;
	});
	$(document).keyup(function(){
		cntrlIsPressed = false;
	});
	
	
	
	$('.content_media_box').on('click', '.file_item', function(){
		if(cntrlIsPressed){
			var id = $(this).attr('file_id');

			if($('.file_deep_checkbox[value='+id+']').is(":checked")) {
				$(this).removeClass('item_selected');
				$('.file_deep_checkbox[value='+id+']').prop('checked',false);
			}else{
				$(this).addClass('item_selected');
				$('.file_deep_checkbox[value='+id+']').prop('checked',true);
			}
		
		}else{
			
			$('.media_file_show').switchClass('col-md-12','col-md-9');
			$('.media_file_info').show(500);
			var file_dst_name = $(this).attr('file_dst_name');
			var file_src_name_ext = $(this).attr('file_src_name_ext');
			var file_src_mime = $(this).attr('file_src_mime');
			var file_src_size = $(this).attr('file_src_size');
			var file_src = $(this).attr('file_src');
			var file_id = $(this).attr('file_id');
			var file_is_image = $(this).attr('file_is_image');
			$('#file_id').val(file_id);
			$('#file_dst_name').val(file_dst_name);
			$('#file_src_name_ext').val(file_src_name_ext);
			$('#file_src_mime').val(file_src_mime);
			$('#file_src_size').val(file_src_size);
			$('#file_src').val(file_src);
			$('span.delete_media_file').attr('data-id',file_id);
			if(file_is_image=='1'){
				var preview_img = '<img src="'+file_src+'" />';
			}else{
				var preview_img = '';
			}
			$('.media_file_file_preview').html(preview_img);
		}
	});
	
});







