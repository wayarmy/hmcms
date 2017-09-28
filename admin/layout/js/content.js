$(document).ready(function(){
	
	var options = { 
		beforeSubmit: function(arr, $form, options) { 
			$('html, body').stop().animate({ scrollTop : 0 }, 500);
			$('.ajax-loading').fadeIn();
		},
		success:function(data) {
			var obj = jQuery.parseJSON(data);
			var latest = obj.latest;
			var id = latest.id;
			var name = latest.name;
			reset_content();
			var content_key = getParameterByName('key');
			function to_url(){	
				window.location.href = '?run=content.php&key='+content_key+'&action=edit&id='+id+'&mes=add_success';
			};
			window.setTimeout( to_url, 1200 );
		}
    }; 
	$('.ajaxFormcontentAdd').ajaxForm(options);
	
	var options = { 
		beforeSubmit: function(arr, $form, options) { 
			$('html, body').stop().animate({ scrollTop : 0 }, 500);
			$('.ajax-loading').fadeIn();
		},
		success:function() {
			var id = getParameterByName('id');
			function to_url(){
				window.location.href = '?run=content.php&action=edit&id='+id+'&mes=edit_success';
			};
			window.setTimeout( to_url, 1200 );
		}
    }; 
	$('.ajaxFormcontentEdit').ajaxForm(options);
	
	var options = { 
		beforeSubmit: function(arr, $form, options) { 
			$('html, body').stop().animate({ scrollTop : 0 }, 500);
			$('.ajax-loading').fadeIn();
		},
		success:function() {
			var id = getParameterByName('id');
			function to_url(){
				window.location.href = '?run=content.php&action=edit&layout=popup&id='+id+'&mes=edit_success';
			};
			window.setTimeout( to_url, 1200 );
		}
    }; 
	$('.ajaxFormcontentEditPopup').ajaxForm(options);
	
	$('.content_status').click(function() { 
		var content_key = getParameterByName('key');
		var status = $(this).attr('data-status');
		var perpage = $('.select_perpage').val();
		var search_keyword = $('.search_keyword').val();
		var search_target = $('.search_target').val();
		var search_order_by = $('.search_order_by').val();
		var search_order = $('.search_order').val();
		var taxonomy_args = $(".search_tax_panel input:checkbox:checked").map(function(){
		  return $(this).val();
		}).get();
		taxonomy =taxonomy_args.toString();
		
		$.post( '?run=content_ajax.php&key='+content_key+
		'&status='+status+
		'&perpage='+perpage+
		'&search_keyword='+search_keyword+
		'&search_target='+search_target+
		'&search_order_by='+search_order_by+
		'&search_order='+search_order+
		'&taxonomy='+taxonomy+
		'&action=data', function( data ) {
			$('select.select_perpage').attr('data-status',status);
			build_table(data,status);
		});
	});
	
	
	$('.content_table_content').ready(function() { 
		update_table();
	});
	
	$(document).on('change', 'select.select_perpage', function(){
		update_table();
	});
	
	$(document).on('change', 'select.search_target', function(){
		update_table();
	});
	
	$(document).on('change', 'select.search_order_by', function(){
		update_table();
	});
	
	$(document).on('keypress', '.search_keyword', function(){
		update_table();
	});
	
	$(document).on('change', '.search_keyword', function(){
		update_table();
	});
	
	$(document).on('click', '.search_tax_panel input', function(){
		update_table();
	});
	
	var update_table = function (){
		
		var content_key = getParameterByName('key');
		var status = $('select.select_perpage').attr('data-status');
		var perpage = $('.select_perpage').val();
		var search_keyword = $('.search_keyword').val();
		var search_target = $('.search_target').val();
		var search_order_by = $('.search_order_by').val();
		var search_order = $('.search_order').val();
		var taxonomy_args = $(".search_tax_panel input:checkbox:checked").map(function(){
		  return $(this).val();
		}).get();
		taxonomy =taxonomy_args.toString();
		$.post( '?run=content_ajax.php&key='+content_key+
		'&status='+status+
		'&perpage='+perpage+
		'&search_keyword='+search_keyword+
		'&search_target='+search_target+
		'&search_order_by='+search_order_by+
		'&search_order='+search_order+
		'&taxonomy='+taxonomy+
		'&action=data', function( data ) {
			build_table(data,status);
		});
		
	}
	
	var build_table = function (data,status){
		$('.content_table .content_table_content').html('<tr><td colspan="6">{lang:loading} ...</td></tr>');
		var obj = jQuery.parseJSON(data);
		var html_content = [];
		var content = obj.content;
		var chapter = obj.chapter;
		$.each( content, function( number , value ) {
			var id = value.id;
			var name = value.name;
			var slug = value.slug;
			var number_order = value.number_order;
			var thumbnail = value.thumbnail;
			if(chapter == true){
			var add_chapter_btn = '<a class="btn btn-info btn-xs" href="?run=content.php&action=add_chapter&id='+id+'">{lang:add_chapter}</a><a class="btn btn-info btn-xs" href="?run=chapter.php&id='+id+'">{lang:chapters}</a>';
			}else{
				var add_chapter_btn = '';
			}
			var status_obj = value.status;
			if(status_obj == 'public'){
				var line = 
				 '<td>'
				+'<input name="content_ids[]" value="'+id+'" type="checkbox" class="checkall_item_1">'
				+'</td>'
				+'<td>'
				+'<i class="fa fa-eye" aria-hidden="true"></i> {lang:public}'
				+'</td>'
				+'<td><span class="content_number_order" data-id="'+id+'">'+number_order+'</span></td>'
				+'<td><span class="content_thumbnail" data-id="'+id+'"><img src="'+thumbnail+'" /></span></td>'
				+'<td><a href="?run=content.php&action=edit&id='+id+'">'+name+'</a></td>'
				+'<td><a href="?run=content.php&action=view&id='+id+'">'+slug+'</a></td>'
				+'<td class="td_action td_action_'+id+'">'
				+'<div class="action_btn_wap">'
				+'<span class="action_btn_wap_button"><i class="glyphicon glyphicon-cog"></i></span>'
				+'<a class="btn btn-default btn-xs" href="?run=content.php&action=edit&id='+id+'">{lang:edit}</a>'
				+'<button type="button" data-id="'+id+'" class="quick_edit_popup_content_button btn btn-info btn-xs" data-toggle="modal" data-target="#popup_box_modal">{lang:quick_edit}</button>'
				+'<button type="button" data-id="'+id+'" class="quick_delete_content_button btn btn-danger btn-xs">{lang:delete}</button>'+add_chapter_btn
				+'</div>'
				+'</td>';
			}
			if(status_obj == 'hide'){
				var line = 
				 '<td>'
				+'<input name="content_ids[]" value="'+id+'" type="checkbox" class="checkall_item_1">'
				+'</td>'
				+'<td>'
				+'<span class="btn btn-xs btn-warning"><i class="fa fa-eye-slash" aria-hidden="true"></i> {lang:hiding}</span>'
				+'</td>'
				+'<td><span class="content_number_order" data-id="'+id+'">'+number_order+'</span></td>'
				+'<td><span class="content_thumbnail" data-id="'+id+'"><img src="'+thumbnail+'" /></span></td>'
				+'<td><a href="?run=content.php&action=edit&id='+id+'">'+name+'</a></td>'
				+'<td><a href="?run=content.php&action=view&id='+id+'">'+slug+'</a></td>'
				+'<td class="td_action td_action_'+id+'">'
				+'<div class="action_btn_wap">'
				+'<span class="action_btn_wap_button"><i class="glyphicon glyphicon-cog"></i></span>'
				+'<a class="btn btn-default btn-xs" href="?run=content.php&action=edit&id='+id+'">{lang:edit}</a>'
				+'<button type="button" data-id="'+id+'" class="quick_edit_popup_content_button btn btn-info btn-xs" data-toggle="modal" data-target="#popup_box_modal">{lang:quick_edit}</button>'
				+'<button type="button" data-id="'+id+'" class="quick_delete_content_button btn btn-danger btn-xs">{lang:delete}</button>'+add_chapter_btn
				+'</div>'
				+'</td>';
			}
			if(status_obj == 'draft'){
				var line = 
				 '<td>'
				+'<input name="content_ids[]" value="'+id+'" type="checkbox" class="checkall_item_1">'
				+'</td>'
				+'<td>'
				+'<i class="fa fa-trash" aria-hidden="true"></i> {lang:draft}'
				+'</td>'
				+'<td><span class="content_number_order" data-id="'+id+'">'+number_order+'</span></td>'
				+'<td><span class="content_thumbnail" data-id="'+id+'"><img src="'+thumbnail+'" /></span></td>'
				+'<td><a href="?run=content.php&action=edit&id='+id+'">'+name+'</a></td>'
				+'<td><a href="?run=content.php&action=view&id='+id+'">'+slug+'</a></td>'
				+'<td class="td_action td_action_'+id+'">'
				+'<div class="action_btn_wap">'
				+'<span class="action_btn_wap_button"><i class="glyphicon glyphicon-cog"></i></span>'
				+'<a class="btn btn-default btn-xs" href="?run=content.php&action=edit&id='+id+'">{lang:edit}</a>'
				+'<button type="button" data-id="'+id+'" class="quick_edit_popup_content_button btn btn-info btn-xs" data-toggle="modal" data-target="#popup_box_modal">{lang:quick_edit}</button>'
				+'<button type="button" data-id="'+id+'" class="quick_public_content_button btn btn-info btn-xs">{lang:rehibilitate}</button>'+add_chapter_btn
				+'<button type="button" data-id="'+id+'" class="quick_delete_permanently_content_button btn btn-danger btn-xs">{lang:delete_permanently}</button>'
				+'</div>'
				+'</td>';
			}
			html_content.push('<tr class="content_tr content_'+id+'" data-id="'+id+'">'+line+'</tr>');
		});
		html_content = html_content.join(' ');
		$('.content_table .content_table_content').html(html_content);
		
		var pagination = obj.pagination;
		if ("first" in pagination){
				
			var first = pagination.first;
			var previous = pagination.previous;
			var next = pagination.next;
			var last = pagination.last;
			var total = pagination.total;
			var paged = pagination.paged;
			if(previous == paged){
				var previous_link = '<a disabled="disabled" data-paged="'+previous+'" class="btn btn-default btn-xs">{lang:previous_page}</a>';
			}else{
				var previous_link = '<a data-paged="'+previous+'" class="btn btn-default btn-xs">{lang:previous_page}</a>';
			}
			if(next == paged){
				var next_link = '<a disabled="disabled" data-paged="'+next+'" class="btn btn-default btn-xs">{lang:next_page}</a>';
			}else{
				var next_link = '<a data-paged="'+next+'" class="btn btn-default btn-xs">{lang:next_page}</a>';
			}
			
			var pagination_bar = '<a data-paged="'+first+'" class="btn btn-default btn-xs">{lang:first_page}</a>'+previous_link+'<a data-paged="'+paged+'" class="btn btn-default btn-xs">'+paged+'</a>'+next_link+'<a data-paged="'+last+'" class="btn btn-default btn-xs">{lang:last_page}</a><span class="total_page">( {lang:page} '+paged+' {lang:in_total} '+last+' {lang:page}, '+total+' {lang:result} )</span>';
			$('.pagination_bar').html(pagination_bar);	
				
		}else{
			$('.pagination_bar').html('');	
		}
		
		
		//sortable list content
		$( ".content_table_content" ).sortable({
			placeholder: "content_tr_highlight"
		});
		$(".content_table_content").on('sortupdate',function(){ 
			var i = (paged - 1) * parseInt($('.select_perpage').val());
			i++;
			$('.content_tr').each(function(index){ 
				var id = $(this).attr('data-id');
				$('.content_number_order[data-id='+id+']').text(i);
				var content_key = getParameterByName('key');
				$.post( '?run=content_ajax.php&key='+content_key+'&action=update_order&id='+id+'&order='+i, function( data ) {
					
				});
				i++;
			});
			$.notify('Đã lưu lại thứ tự', { globalPosition: 'top right',className: 'success' } );
		});
		
	};
	
	$( ".list-form-input" ).sortable({
		placeholder: "list-form-input-placeholder",
		handle: ".form-group-handle",
    });
	$(".list-form-input").on('sortupdate',function(){ 
		$(".list-form-input").find('.form-group').each(function(index){ 
			var func = $(this).attr('data-input-name');
			$.post( '?run=content_ajax.php&action=update_form_input_order&func='+func+'&order='+index, function( data ) {
				
			});
		});
	});
	
	var $wrapper = $('.list-form-input');
	$wrapper.find('.hm-form-group').sort(function(a, b) {
		return +a.dataset.order - +b.dataset.order;
	}).appendTo($wrapper);
	
	$(document).on('change', '.input_have_slug', function(){
		var action = getParameterByName('action');
		if(action == 'add'){
			var val = $(this).val();
			var name = $(this).attr('name');
			var object = $(this).attr('object-id');
			var input_slug = 'slug_of_'+name+'_'+object;
			var input_accented = $(this).attr('slug-accented');
			var accented = $('input[name='+input_accented+']:checked').val();
			if(accented == undefined){accented = 'false';}
			ajax_slug(val,input_slug,accented,object);
		}
	});
		

	$(document).on('change', '.accented', function(){
		var object = $(this).attr('data-field-object');
		var name = $(this).attr('data-field-name');
		var val = $('input[object-id='+object+'][name='+name+']').val();
		var input_slug = 'slug_of_'+name+'_'+object;
		var accented = $(this).val();
		ajax_slug(val,input_slug,accented,object);
	});
	
	var ajax_slug = function (val,input_slug,accented,object){
		$.post( '?run=content_ajax.php&action=ajax_slug', { val:val , accented:accented , object:object }, function( data ) {
			$('.'+input_slug).val(data);
		});
	};

	
	$('select[name=status]').change(function(){
		var value = $(this).val();
		if(value == 'password'){
			$('.input_password_content').slideDown();
		}else{
			$('.input_password_content').slideUp();
		}
	}); 
	
	$(document).on('click', '.quick_delete_content_button', function(){
		var id = $(this).attr('data-id');
		$.post( '?run=content_ajax.php&action=draft', { id:id }, function( data ) {
			$('.content_tr.content_'+id).addClass('danger');
			$('.td_action_'+id+' .quick_delete_content_button').remove();
			$('.td_action_'+id+' .action_btn_wap').append('<button type="button" data-id="'+id+'" class="quick_public_content_button btn btn-info btn-xs">{lang:rehibilitate}</button>');
			$.notify('{lang:put_into_the_trash}', { globalPosition: 'top right',className: 'success' } );
		});
	});
	
	$(document).on('click', '.quick_delete_permanently_content_button', function(){
		var id = $(this).attr('data-id');
		$('.content_tr.content_'+id).addClass('warning');
		$('.td_action_'+id+' .quick_delete_permanently_content_button').remove();
		$('.td_action_'+id+' .action_btn_wap').append('<button type="button" data-id="'+id+'" class="confirm_delete_permanently_content_button btn btn-danger btn-xs">{lang:verify_permanent_deletion}</button>');
		$.notify('{lang:please_confirm}', { globalPosition: 'top right',className: 'success' } );
	});
	
	$(document).on('click', '.confirm_delete_permanently_content_button', function(){
		var id = $(this).attr('data-id');
		var status = 'draft';
		var content_key = getParameterByName('key');
		$.post( '?run=content_ajax.php&action=delete_permanently', { id:id }, function( data ) {
			$.notify('{lang:deleted_permanently}', { globalPosition: 'top right',className: 'success' } );
			$.post( '?run=content_ajax.php&key='+content_key+'&status='+status+'&action=data', function( data ) {
				build_table(data,status);
			});
		});
	});
	
	$(document).on('click', '.quick_public_content_button', function(){
		var id = $(this).attr('data-id');
		$.post( '?run=content_ajax.php&action=public', { id:id }, function( data ) {
			$('.content_tr.content_'+id).removeClass('danger');
			$('.td_action_'+id+' .quick_public_content_button').remove();
			$('.td_action_'+id+' .confirm_delete_permanently_content_button').remove();
			$('.td_action_'+id+' .action_btn_wap').append('<button type="button" data-id="'+id+'" class="quick_delete_content_button btn btn-danger btn-xs">{lang:delete}</button>');
			$.notify('{lang:content_restored}', { globalPosition: 'top right',className: 'success' } );
		});
	});
	
	
	$(document).on('click', '.pagination_bar .btn', function(){
		var paged = $(this).attr('data-paged');
		var content_key = getParameterByName('key');
		var status = $('select.select_perpage').attr('data-status');
		var perpage = $('select.select_perpage').val();
		var search_keyword = $('.search_keyword').val();
		var search_target = $('.search_target').val();
		var search_order_by = $('.search_order_by').val();
		var search_order = $('.search_order').val();
		var taxonomy_args = $(".search_tax_panel input:checkbox:checked").map(function(){
		  return $(this).val();
		}).get();
		taxonomy =taxonomy_args.toString();
		$.post( '?run=content_ajax.php&key='+content_key+
		'&status='+status+
		'&perpage='+perpage+
		'&paged='+paged+
		'&search_keyword='+search_keyword+
		'&search_target='+search_target+
		'&search_order_by='+search_order_by+
		'&search_order='+search_order+
		'&taxonomy='+taxonomy+
		'&action=data', function( data ) {
			build_table(data,status);
		});
	});
	
	$('.ajaxFormContentAction').ajaxForm(function(data) {
		$.notify('{lang:put_into_the_trash}', { globalPosition: 'top right',className: 'success' } );
		var content_key = getParameterByName('key');
		var paged = 1;
		var status = 'public';
		var perpage = $('select.select_perpage').val();
		$.post( '?run=content_ajax.php&key='+content_key+'&status='+status+'&perpage='+perpage+'&paged='+paged+'&action=data', function( data ) {
			build_table(data,status);
		});
		reset_content();
	});
	
	
	$(document).on('click', '.quick_edit_popup_content_button', function(){
		var content_id = $(this).attr('data-id');
		$('#popup_box_modal .modal-body').html('<iframe id="quick_edit_popup_content" src="?run=content.php&action=edit&layout=popup&id='+content_id+'" frame-border="0" width="100%" height="600px"></iframe>')
	});	
	
});
