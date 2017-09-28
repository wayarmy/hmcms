$(document).ready(function(){
	
	var options_ajax_form = { 
		beforeSubmit: function(arr, $form, options) { 
			var container = $form.parent().attr('data-container');
			$(".block_container_content_to[data-container="+container+"]").parent().find('.loading_icon').fadeIn();
		},
		success:function(data) {
			$('.loading_icon').fadeOut();
			$('.block_container_content_from input[type=text]').val('');
			$('.block_container_content_from input[type=email]').val('');
			$('.block_container_content_from input[type=number]').val('');
			$('.block_container_content_from input[type=password]').val('');
			$('.block_container_content_from input[type=checkbox]').prop('checked', false); 
			$('.block_container_content_from input[type=radio]').prop('checked', false); 
			$('.block_container_content_from textarea').val('');
			$('.block_container_content_from .note-editable').html('');
			$('.block_container_content_from .preview_media_file').html('');
			$.notify('Đã lưu thay đổi', { globalPosition: 'top right',className: 'success' } );
		}
    }; 
	//update block value
	$('.ajaxFormBlockUpdate').ajaxForm(options_ajax_form);
	
	//avalible block
	$( ".block_container_content_from" ).sortable({
		connectWith: ".block_container_content",
		placeholder: "block-container-placeholder",
		stop: function (event, ui){
			
			var container = ui.item.parent('.block_container_content_to').attr('data-container');
			$(".block_container_content_to[data-container="+container+"]").parent().find('.loading_icon').fadeIn();
			//ajax create block id
			var func = ui.item.attr('data-func');
			$.post( '?run=block_ajax.php&container='+container+'&function='+func+'&action=add_block', function( data ) {
				
				//return new block id
				var obj = jQuery.parseJSON(data);
				var id = obj.id;
				
				var func = ui.item.attr('data-func');
				ui.item.find('.block_item_title').attr('data-id',id);
				ui.item.find('.block_item_content').attr('data-id',id);
				ui.item.find('.block_item_title').attr('data-container',container);
				ui.item.find('.block_item_content').attr('data-container',container);
				ui.item.find('.block_item_content input[name=block_id]').val(id);
				ui.item.find('.remove_block_btn').attr('data-id',id);
				ui.item.find('.remove_block_btn').attr('data-container',container);
				
				//clone and append
				var item_html = '<div class="block_item ui-sortable-handle flash animated" data-container="'+container+'" data-func="'+func+'" data-id="'+id+'">'+ui.item.html()+'</div>';
				var item_new_html = item_html.replace(/\DefaultBlockId/g, id);
				$(".block_container_content_to[data-container="+container+"]").append(item_new_html);
				$(".block_container_content_to[data-container="+container+"]").parent().find('.loading_icon').fadeOut();
				$('.ajaxFormBlockUpdate').ajaxForm(options_ajax_form);
				
				//reset block id
				ui.item.removeAttr('data-id');
				ui.item.find('.block_item_title').removeAttr('data-id');
				ui.item.find('.block_item_content').removeAttr('data-id');
				ui.item.find('.block_item_content input[name=block_id]').val('');
				ui.item.find('.remove_block_btn').removeAttr('data-id');
				
				//window.location.href = window.location.href;
				
			});
				
			//re order
			$(".block_container_content_to[data-container="+container+"] .block_item").each(function(index){ 
				
				$(this).attr('data-container',container);
				$(this).find('.block_item_title').attr('data-container',container);
				$(this).find('.block_item_content').attr('data-container',container);
				$(this).find('.block_item_content input[name=block_container]').val(container);
				$(this).find('.block_item_content input[name=block_order]').val(index);
				
			});
			
			//cancel drop
			$(this).sortable('cancel');
		}
		
    }).disableSelection();
	
	
	//saved to theme block
	$( ".block_container_content_to" ).sortable({
		handle: ".block_item_title",
		connectWith: ".block_container_content",
		placeholder: "block-container-placeholder",
    });
	
	//drag order
	$(".block_container_content_to").on('sortupdate',function(){ 
		var blocks = [];
		var next = true;
		var container = $(this).attr('data-container');
		$(this).parent().find('.loading_icon').fadeIn();
		$(this).find('.block_item').each(function(index){ 
			var id = $(this).attr('data-id');
			if(id==null){
				next = false;
			}else{
				blocks.push(id);
			}
		});
	
		if(next==true){
			$.post( '?run=block_ajax.php&container='+container+'&action=update_order', { blocks:blocks }, function( data ) {
				$(".block_container_content_to[data-container="+container+"]").parent().find('.loading_icon').fadeOut();
				$.notify('Đã lưu thứ tự', { globalPosition: 'top right',className: 'success' } );
			});
		}
	});

	
	//toggle block
	$(document).on("click",".block_container_content_to .block_item_title",function(){ 
		var id = $(this).attr("data-id");
		var container = $(this).attr("data-container");
		var func = $(this).attr("data-func");
		if($(this).hasClass('opened')){
			$(this).removeClass('opened');
			$('.block_item_content[data-container='+container+'][data-id='+id+']').slideUp();
		}else{
			$(this).addClass('opened');
			$('.block_item_content[data-container='+container+'][data-id='+id+']').slideDown();
		}
	});
	
	
	//remove block
	$(document).on("click",".remove_block_btn",function(){ 
	
		var container = $(this).attr('data-container');
		var id = $(this).attr("data-id");
		
		$(".block_container_content_to[data-container="+container+"]").parent().find('.loading_icon').fadeIn();
		$.post( '?run=block_ajax.php&container='+container+'&id='+id+'&action=remove_block', function( data ) {
			
			$(".block_container_content_to[data-container="+container+"]").parent().find('.loading_icon').fadeOut();
			$('.block_item[data-id='+id+']').remove();
			$.notify('Đã xóa block', { globalPosition: 'top right',className: 'success' } );
			
		});
	});
	
	
	
});