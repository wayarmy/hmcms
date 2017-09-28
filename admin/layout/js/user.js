$(document).ready(function(){
	
	$('.ajaxFormuserAdd').ajaxForm(function(data) {
		var obj = jQuery.parseJSON(data);
		var status = obj.status;
		var mes = obj.mes;
		if(status == 'error'){
			$('.add_user_noti').html('<div class="alert alert-danger" role="alert">'+mes+'</div>');
		}
		if(status == 'success'){
			$('.add_user_noti').html('<div class="alert alert-success" role="alert">'+mes+'</div>');
			reset_content();
		}
		if(status == 'updated'){
			$('.add_user_noti').html('<div class="alert alert-success" role="alert">'+mes+'</div>');
		}
	}); 
	
	$('.content_table_user').ready(function() { 
		var user_group = '0';
		$.post( '?run=user_ajax.php&user_group='+user_group+'&action=data', function( data ) {
			build_table(data,user_group);
		});
	});
	
	
	var build_table = function (data,user_group){
		$('.content_table_user').html('<tr><td colspan="4">{lang:loading} ...</td></tr>');
		var obj = jQuery.parseJSON(data);
		var html_content = [];
		var user = obj.user;
		$.each( user, function( number , value ) {
			var id = value.id;
			var user_nicename = value.user_nicename;
			var user_role = value.user_role;
			var line = '<td><input name="content_ids[]" value="'+id+'" type="checkbox" class="checkall_item_1"></td><td>'+user_nicename+'</td><td>'+user_role+'</td><td class="td_action td_action_'+id+'"><div class="action_btn_wap"><span class="action_btn_wap_button"><i class="glyphicon glyphicon-cog"></i></span><a class="btn btn-default btn-xs" href="?run=user.php&action=edit&id='+id+'">{lang:edit}</a><button type="button" data-id="'+id+'" class="quick_deactive_user_button btn btn-danger btn-xs">{lang:ban_account}</button></div></td>';
			html_content.push('<tr class="content_tr content_'+id+'">'+line+'</tr>');
		});
		html_content = html_content.join(' ');
		$('.content_table_user').html(html_content);
		
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
		
	};
	
	$(document).on('change', 'select.select_perpage', function(){
		var perpage = $(this).val();
		var status = $(this).attr('data-status');
		var user_group = '0';
		$.post( '?run=user_ajax.php&user_group='+user_group+'&status='+status+'&perpage='+perpage+'&action=data', function( data ) {
			build_table(data,status);
		});
	});
	
	$(document).on('click', '.pagination_bar .btn', function(){
		var paged = $(this).attr('data-paged');
		var status = $('select.select_perpage').attr('data-status');
		var perpage = $('select.select_perpage').val();
		var user_group = '0';
		$.post( '?run=user_ajax.php&user_group='+user_group+'&status='+status+'&perpage='+perpage+'&paged='+paged+'&action=data', function( data ) {
			build_table(data,status);
		});
	});
	
	$(document).on('click', '.quick_deactive_user_button', function(){
		var id = $(this).attr('data-id');
		$.post( '?run=user_ajax.php&action=ban', { id:id }, function( data ) {
			$('.content_tr.content_'+id).addClass('danger');
			$('.content_tr.content_'+id).remove();
			$('.td_action_'+id+' .quick_deactive_user_button').remove();
			$.notify('Đã khóa tài khoản', { globalPosition: 'top right',className: 'success' } );
		});
	});
	
});