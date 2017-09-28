$(document).ready(function(){
	
	$('.ajaxFormTaxonomyAdd').ajaxForm(function(data) {
		$('.ajaxFormTaxonomyAdd input[type=text]').val('');
		$('.ajaxFormTaxonomyAdd textarea').val('');
		var status = 'public';
		build_table(data,status);
		var obj = jQuery.parseJSON(data);
		var latest = obj.latest;
		var value = latest.id;
		var label = latest.name;
		var option = '<option value="'+value+'">'+label+'</option>';
		$('select[name=parent]').append(option);
		reset_content();
	}); 
	
	var options = { 
		beforeSubmit: function(arr, $form, options) { 
			$('html, body').stop().animate({ scrollTop : 0 }, 500);
			$('.ajax-loading').fadeIn();
		},
		success:function() {
			var id = getParameterByName('id');
			function to_url(){
				window.location.href = '?run=taxonomy.php&action=edit&id='+id+'&mes=edit_success';
			};
			window.setTimeout( to_url, 1200 );
		}
    }; 
	$('.ajaxFormtaxonomyEdit').ajaxForm(options);
	
	
	$('.taxonomy_table_content').ready(function() { 
		var taxonomy_key = getParameterByName('key');
		var status = 'public';
		$.post( '?run=taxonomy_ajax.php&key='+taxonomy_key+'&status='+status+'&action=data', function( data ) {
			build_table(data,status);
		});
	});
	
	var build_table = function (data,status){
		$('.taxonomy_table .taxonomy_table_content').html('<tr><td colspan="4">{lang:loading} ...</td></tr>');
		var obj = jQuery.parseJSON(data);
		var html_content = [];
		var taxonomy = obj.taxonomy;
		$.each( taxonomy, function( number , value ) {
			var id = value.id;
			var name = value.name;
			var slug = value.slug;
			var status_obj = value.status;
			if(status_obj == 'public'){
				var line = 	'<td>'
							+'<input name="taxonomy_ids[]" value="'+id+'" type="checkbox" class="checkall_item_1">'
							+'</td>'
							+'<td>'
							+'<i class="fa fa-eye" aria-hidden="true"></i> {lang:public}'
							+'</td>'
							+'<td><a href="?run=taxonomy.php&action=edit&id='+id+'">'+name+'</a></td>'
							+'<td>'+slug+'</td>'
							+'<td class="td_action td_action_'+id+'">'
							+'<button type="button" data-id="'+id+'" class="quick_edit_taxonomy_button btn btn-default btn-xs">{lang:quick_edit}</button>'
							+'<a class="btn btn-default btn-xs" href="?run=taxonomy.php&action=edit&id='+id+'">{lang:edit}</a>'
							+'<button type="button" data-id="'+id+'" class="quick_delete_taxonomy_button btn btn-danger btn-xs">{lang:delete}</button>'
							+'</td>';
			}
			if(status_obj == 'hide'){
				var line = '<td>'
							+'<input name="taxonomy_ids[]" value="'+id+'" type="checkbox" class="checkall_item_1">'
							+'</td>'
							+'<td>'
							+'<span class="btn btn-xs btn-warning"><i class="fa fa-eye-slash" aria-hidden="true"></i> {lang:hiding}</span>'
							+'</td>'
							+'<td><a href="?run=taxonomy.php&action=edit&id='+id+'">'+name+'</a></td>'
							+'<td>'+slug+'</td>'
							+'<td class="td_action td_action_'+id+'">'
							+'<button type="button" data-id="'+id+'" class="quick_edit_taxonomy_button btn btn-default btn-xs">{lang:quick_edit}</button>'
							+'<a class="btn btn-default btn-xs" href="?run=taxonomy.php&action=edit&id='+id+'">{lang:edit}</a>'
							+'<button type="button" data-id="'+id+'" class="quick_delete_taxonomy_button btn btn-danger btn-xs">{lang:delete}</button>'
							+'</td>';
			}
			if(status_obj == 'draft'){
				var line = '<td>'
							+'<input name="taxonomy_ids[]" value="'+id+'" type="checkbox" class="checkall_item_1">'
							+'</td>'
							+'<td>'
							+'<i class="fa fa-trash" aria-hidden="true"></i> {lang:draft}'
							+'</td>'
							+'<td><a href="?run=taxonomy.php&action=edit&id='+id+'">'+name+'</a></td>'
							+'<td>'+slug+'</td>'
							+'<td class="td_action td_action_'+id+'">'
							+'<button type="button" data-id="'+id+'" class="quick_edit_taxonomy_button btn btn-default btn-xs">{lang:quick_edit}</button>'
							+'<a class="btn btn-default btn-xs" href="?run=taxonomy.php&action=edit&id='+id+'">{lang:edit}</a>'
							+'<button type="button" data-id="'+id+'" class="quick_delete_permanently_taxonomy_button btn btn-danger btn-xs">{lang:delete_permanently}</button>'
							+'<button type="button" data-id="'+id+'" class="quick_public_taxonomy_button btn btn-info btn-xs">{lang:rehibilitate}</button>'
							+'</td>';
			}
			html_content.push('<tr class="taxonomy_tr taxonomy_'+id+'">'+line+'</tr>');
		});
		html_content = html_content.join(' ');
		$('.taxonomy_table .taxonomy_table_content').html(html_content);
		
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
			
		var pagination_bar = '<a data-paged="'+first+'" class="btn btn-default btn-xs">{lang:first_page}</a>'+previous_link+'<a data-paged="'+paged+'" class="btn btn-default btn-xs custom_paged">'+paged+'</a>'+next_link+'<a data-paged="'+last+'" class="btn btn-default btn-xs">{lang:last_page}</a><span class="total_page">( {lang:page} '+paged+' {lang:in_total} '+last+' {lang:page}, '+total+' {lang:result} )</span>';
			$('.pagination_bar').html(pagination_bar);	
				
		}else{
			$('.pagination_bar').html('');	
		}
		
	};
	

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
		$.post( '?run=taxonomy_ajax.php&action=ajax_slug', { val:val , accented:accented , object:object }, function( data ) {
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

	$(document).on('change', 'select.select_perpage', function(){
		var perpage = $(this).val();
		var taxonomy_key = getParameterByName('key');
		var status = $(this).attr('data-status');
		$.post( '?run=taxonomy_ajax.php&key='+taxonomy_key+'&status='+status+'&perpage='+perpage+'&action=data', function( data ) {
			build_table(data,status);
		});
	});
	
	$(document).on('click', '.pagination_bar .btn', function(){
		var paged = $(this).attr('data-paged');
		var taxonomy_key = getParameterByName('key');
		var status = $('select.select_perpage').attr('data-status');
		var perpage = $('select.select_perpage').val();
		$.post( '?run=taxonomy_ajax.php&key='+taxonomy_key+'&status='+status+'&perpage='+perpage+'&paged='+paged+'&action=data', function( data ) {
			build_table(data,status);
		});
	});
	
	$('.taxonomy_status').click(function() { 
		var taxonomy_key = getParameterByName('key');
		var status = $(this).attr('data-status');
		var perpage = $('.select_perpage').val();
		$.post( '?run=taxonomy_ajax.php&key='+taxonomy_key+'&status='+status+'&perpage='+perpage+'&action=data', function( data ) {
			$('select.select_perpage').attr('data-status',status);
			build_table(data,status);
		});
	});
	
	$(document).on('click', '.quick_edit_taxonomy_button', function(){
		$('.quick_edit_tr').remove();
		var id = $(this).attr('data-id');
		var taxonomy_key = getParameterByName('key');
		$.post( '?run=taxonomy_ajax.php&key='+taxonomy_key+'&action=quick_edit&id='+id, function( form_template ) {
			$('.taxonomy_tr.taxonomy_'+id).after('<tr class="quick_edit_tr quick_edit_tr'+id+'"><td colspan="5" class="quick_edit_td"><div class="quick_edit_wapper">'+form_template+'</div></td></tr>');
		});
	});
	
	$(document).on('click', '.quick_delete_taxonomy_button', function(){
		var id = $(this).attr('data-id');
		$.post( '?run=taxonomy_ajax.php&action=draft', { id:id }, function( data ) {
			$('.taxonomy_tr.taxonomy_'+id).addClass('danger');
			$('.td_action_'+id+' .quick_delete_taxonomy_button').remove();
			$('.td_action_'+id).append('<button type="button" data-id="'+id+'" class="quick_public_taxonomy_button btn btn-info btn-xs">{lang:rehibilitate}</button>');
			$.notify('{lang:put_into_the_trash}', { globalPosition: 'top right',className: 'success' } );
		});
	});
	
	$(document).on('click', '.quick_delete_permanently_taxonomy_button', function(){
		var id = $(this).attr('data-id');
		$('.taxonomy_tr.taxonomy_'+id).addClass('warning');
		$('.td_action_'+id+' .quick_delete_permanently_taxonomy_button').remove();
	$('.td_action_'+id).append('<button type="button" data-id="'+id+'" class="confirm_delete_permanently_taxonomy_button btn btn-danger btn-xs">{lang:verify_permanent_deletion}</button>');
		$.notify('{lang:please_confirm}', { globalPosition: 'top right',className: 'success' } );
	});
	
	$(document).on('click', '.confirm_delete_permanently_taxonomy_button', function(){
		var id = $(this).attr('data-id');
		var status = 'draft';
		var taxonomy_key = getParameterByName('key');
		$.post( '?run=taxonomy_ajax.php&action=delete_permanently', { id:id }, function( data ) {
			$.notify('{lang:deleted_permanently}', { globalPosition: 'top right',className: 'success' } );
			$.post( '?run=taxonomy_ajax.php&key='+taxonomy_key+'&status='+status+'&action=data', function( data ) {
				build_table(data,status);
			});
		});
	});
	
	$(document).on('click', '.quick_public_taxonomy_button', function(){
		var id = $(this).attr('data-id');
		$.post( '?run=taxonomy_ajax.php&action=public', { id:id }, function( data ) {
			$('.taxonomy_tr.taxonomy_'+id).removeClass('danger');
			$('.td_action_'+id+' .quick_public_taxonomy_button').remove();
			$('.td_action_'+id).append('<button type="button" data-id="'+id+'" class="quick_delete_taxonomy_button btn btn-danger btn-xs">{lang:delete}</button>');
			$.notify('{lang:taxonomy_restored}', { globalPosition: 'top right',className: 'success' } );
		});
	});
	

	$('.ajaxFormTaxonomyAction').ajaxForm(function(data) {
		$.notify('{lang:put_into_the_trash}', { globalPosition: 'top right',className: 'success' } );
		var taxonomy_key = getParameterByName('key');
		var status = 'public';
		$.post( '?run=taxonomy_ajax.php&key='+taxonomy_key+'&status='+status+'&action=data', function( data ) {
			build_table(data,status);
		});
		reset_content();
	});
	
});