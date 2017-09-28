$(document).ready(function(){
	$.post( '?run=optimize_ajax.php&action=database')
		.done(function( data ) {
		$('.optimize_step_1_item_result').html('<hr>'+data);
		$('.optimize_step_1_item_note').html('<span class="label label-success">{lang:processed}</span>');
		$('.optimize_step_1_item_result').delay(1000).slideUp();
		
		$('.optimize_step_2_item_note').html('<span class="label label-danger">{lang:processing_please_do_not_close_browser}</span>');
		$.post( '?run=optimize_ajax.php&action=images')
		.done(function( data ) {
			$('.optimize_step_2_item_result').html('<hr>'+data);
			$('.optimize_step_2_item_note').html('<span class="label label-success">{lang:processed}</span>');
			$('.optimize_step_2_item_result').delay(1000).slideUp();
		});
	});
});	