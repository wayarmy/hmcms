$(document).ready(function(){
	
	$('.theme_include_here').ready(function() { 
		$.post( '?run=theme_ajax.php&action=available', function( data ) {
			build_list(data);
		});
	});
	
	var build_list = function (data){
		
		var obj = jQuery.parseJSON(data);
		var html_theme = [];
		var themes = obj.themes;
		$.each( themes, function( number , value ) {
			var theme_name = value.theme_name;
			var theme_description = value.theme_description;
			var theme_version = value.theme_version;
			var theme_key = value.theme_key;
			var theme_active = value.theme_active;
			var theme_thumbnail = value.theme_thumbnail;
			if (theme_active=='0'){ 
			var theme_action = '<button class="btn btn-default btn-xs active_theme" data-key="'+theme_key+'">{lang:activate}</button>';
			}else{
			var theme_action = '<span class="btn btn-success btn-xs">{lang:using}</span>';	
			}
			var line = '<div class="row"><div class="theme_thumbnail"><img src="'+theme_thumbnail+'"></div></div><div class="row"><h3 class="theme_name">'+theme_name+'</h3></div><div class="row"><span class="theme_action">'+theme_action+'</span></div>';
			html_theme.push('<div class="col-md-3 theme_preview"><div class="theme_preview_wap theme_preview_wap_'+theme_key+'">'+line+'</div></div>');
		});
		html_theme = html_theme.join(' ');
		$('.theme_include_here').html(html_theme);
		
	}
	
	$(document).on('click', '.active_theme', function(){
		var theme  = $(this).attr('data-key');
		$.post( '?run=theme_ajax.php&action=active_theme', { theme:theme }, function( data ) {
			$.notify('{lang:theme_enabled}', { globalPosition: 'top right',className: 'success' } );
			setTimeout(function () {
				location.reload();
			}, 1000);
		});
	});
	
	$(document).on('click', '.download_theme', function(){
		var href = $(this).attr('data-href');
		$.notify('{lang:downloading_theme_to_server}', { globalPosition: 'top right',className: 'info' } );
		$.post( '?run=theme_ajax.php&action=add_theme', { href:href }, function( data ) {
			var obj = jQuery.parseJSON(data);
			var status = obj.status;
			var mes = obj.mes;
			if(status == 'error'){
				$.notify(mes, { globalPosition: 'top right',className: 'error' });
			}
			if(status == 'success'){
				$.notify(mes, { globalPosition: 'top right',className: 'success' } );
			}			
		});
	});
	
});