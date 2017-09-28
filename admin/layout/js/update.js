$(document).ready(function(){
	
	$(document).on('click', '.update_plugin', function(){
		var href = $(this).attr('data-href');
		$.notify('Đang download plugin về máy chủ', { globalPosition: 'top right',className: 'info' } );
		$.post( '?run=plugin_ajax.php&action=add_plugin', { href:href }, function( data ) {
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
	
	$(document).on('click', '.update_core', function(){
		$.notify('Đang download phiên bản mới về máy chủ', { globalPosition: 'top right',className: 'info' } );
		$.post( '?run=update_ajax.php&action=update_core', { '1':'1' }, function( data ) {
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