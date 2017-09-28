function getParameterByName(name) {
	name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
	var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
		results = regex.exec(location.search);
	return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}
function calcHeight(){
	var the_height=document.getElementById('fullheight_iframe').contentWindow.document.body.scrollHeight;
	document.getElementById('fullheight_iframe').height=the_height;
}
var reset_content = function(){
		$('input[type=text]').val('');
		$('input[type=email]').val('');
		$('input[type=number]').val('');
		$('input[type=password]').val('');
		$('input[type=checkbox]').prop('checked', false); 
		$('input[type=radio]').prop('checked', false); 
		$('textarea').val('');
		$('.note-editable').html('');
		$('.preview_media_file').html('');
	}
setTimeout(function () {
	$('[role=alert]').fadeOut(1000);
}, 5000);	
		
		
jQuery(document).ready(function(){
	
	//material wave click
	var parent, ink, d, x, y;
	$(".material_wave").click(function(e){
		parent = $(this);
		if(parent.find(".ink").length == 0){
			parent.prepend("<span class='ink'></span>");
		}
		ink = parent.find(".ink");
		ink.removeClass("animate");
		if(!ink.height() && !ink.width()){
			d = Math.max(parent.outerWidth(), parent.outerHeight());
			ink.css({height: d, width: d});
		}
		x = e.pageX - parent.offset().left - ink.width()/2;
		y = e.pageY - parent.offset().top - ink.height()/2;
		ink.css({top: y+'px', left: x+'px'}).addClass("animate");
	})
	
	$('.admin_sidebar_box_title').click(function(e) {
		e.preventDefault();
		var func = $(this).attr('data-func');
		if( $('.admin_mainbar_boxcontent[data-func='+func+']').hasClass('hide') ){
			$.post( '?run=admin_ajax.php', { action: 'closeBox', func:func })
				.done(function( data ) {
				$('.admin_mainbar_boxcontent[data-func='+func+']').removeClass('hide');
				$('.admin_sidebar_box_title[data-func='+func+']').removeClass('content_hided');
			});
		}else{
			$.post( '?run=admin_ajax.php', { action: 'openBox', func:func })
				.done(function( data ) {
				$('.admin_mainbar_boxcontent[data-func='+func+']').addClass('hide');
				$('.admin_sidebar_box_title[data-func='+func+']').addClass('content_hided');
			});
		}
	});
	
	$("#menu-toggle").click(function(e) {
		e.preventDefault();
		if( $("#wrapper").hasClass("active") ){
			$.post( '?run=admin_ajax.php', { action: 'closeSidebar' })
				.done(function( data ) {
				$("#wrapper").removeClass("active");
			});
		}else{
			$.post( '?run=admin_ajax.php', { action: 'openSidebar' })
				.done(function( data ) {
				$("#wrapper").addClass("active");
			});
		}
	});
	
	$(document).on('click', '.media_btn', function(e){
		var this_id = $(this).attr('id');
		var multi = $(this).attr('multi');
		var imageonly = $(this).attr('imageonly');
		$('.media_box_ajax_load .col-md-9').html('Đang tải dữ liệu');
		$('.media_box').load('?run=media_ajax.php&action=ajax_media_box&use_media_file='+this_id+'&multi='+multi+'&imageonly='+imageonly, function(){
			$('.media_box_ajax_load').hide();
			$('.media_box_ajax_load').fadeIn(1000);
		});
	});
	
	$(document).on('click', '.use_media_file', function(e){
		var this_id = $(this).attr('id');
		var multi = $(this).attr('multi');
		var imageonly = $(this).attr('imageonly');
		$(this).attr('waiting','1');
		$(this).parent().find('.preview_media_file').attr('waiting','1');
		$(this).parent().find('input').attr('waiting','1');
		$('.media_box_ajax_load .col-md-9').html('Đang tải dữ liệu');
		$('.media_box').load('?run=media_ajax.php&action=ajax_media_box&use_media_file='+this_id+'&multi='+multi+'&imageonly='+imageonly, function(){
			$('.media_box_ajax_load').hide();
			$('.media_box_ajax_load').fadeIn(1000);
		});
	});
	
	$('.wysiwyg').summernote({
		lang: 'vi-VN',
	});
		
	$(document).on('click', '.use_media_file_insert', function(e){
		var file_id = $('#file_id').val();
		var file_src = $('#file_src').val();
		var use_media_file = $(this).attr('use_media_file');
		if (($('.wysiwyg[name='+use_media_file+']').length > 0)){
			var img = '';
			var img = '<img src="'+file_src+'" />';
			var olddata = $('.wysiwyg[name='+use_media_file+']').summernote('code');
			var newdata = olddata + img;
			$('.wysiwyg[name='+use_media_file+']').summernote('code',newdata);
		}
	});
	
    
	$( ".admin_sidebar" ).sortable({
		placeholder: "list-form-input-placeholder",
		handle: ".admin_sidebar_box_title",
		items: ".admin_sidebar_box",
    });
	$(".admin_sidebar").on('sortupdate',function(){ 
		$('.admin_sidebar_box').each(function(index){ 
			var func = $(this).attr('data-func');
			$.post( '?run=content_ajax.php&action=update_sidebar_box_order&func='+func+'&order='+index, function( data ) {
				
			});
		});
	});
	var $wrapper = $('.admin_sidebar');
	$wrapper.find('.admin_sidebar_box').sort(function(a, b) {
		return +a.dataset.order - +b.dataset.order;
	}).appendTo($wrapper);
	
	$( ".admin_mainbar" ).sortable({
		placeholder: "list-form-input-placeholder",
		handle: ".admin_sidebar_box_title",
		items: ".admin_mainbar_box",
    });
	$(".admin_mainbar").on('sortupdate',function(){ 
		$('.admin_mainbar_box').each(function(index){ 
			var func = $(this).attr('data-func');
			$.post( '?run=content_ajax.php&action=update_mainbar_box_order&func='+func+'&order='+index, function( data ) {
				
			});
		});
	});
	var $wrapper = $('.admin_mainbar');
	$wrapper.find('.admin_mainbar_box').sort(function(a, b) {
		return +a.dataset.order - +b.dataset.order;
	}).appendTo($wrapper);
	
	
	$(document).on('click', 'input.checkall', function(){
		var id =  $(this).attr('data-id');
		if(this.checked) {
            $('.checkall_item_'+id).each(function() {
                this.checked = true;            
            });
        }else{
            $('.checkall_item_'+id).each(function() {
                this.checked = false;                    
            });         
        }
	});
	
	$(document).on('click', '.preview_media_file_wapper', function(){
		$('.preview_media_file_wapper').removeClass('checked');
		$(this).addClass('checked');
		var fileid = $(this).attr('file-id');
		var use_media_file  = $(this).attr('use_media_file');
		$('.preview_media_file_remove').hide();
		$('.preview_media_file_remove[file-id='+fileid+'][use_media_file='+use_media_file+']').show();
	});
	
	$(document).on('click', '.preview_media_file_remove', function(){
		var fileid = $(this).attr('file-id');
		var use_media_file  = $(this).attr('use_media_file');
		$('.preview_media_file_wapper[file-id='+fileid+'][use_media_file='+use_media_file+']').remove();
		var inputval = $('input[use_media_file='+use_media_file+']').val();
		var splited = inputval.split(',');
		$(splited).each(function(key){
			if(splited[key] == fileid){
				splited.splice(key,1);
			}
		});
		var newinputval = splited.join(',');
		$('input[use_media_file='+use_media_file+']').val(newinputval);
	});

	$(document).on('dblclick', '.file_item', function(){
		if ( $('.use_media_file_insert').is(":visible") ){
			$('.use_media_file_insert').click();
		}
	});
	
	//input request_uri
	$(document).on('keyup','input.request_uri', function(){
		var val = $(this).val();
		var input = $(this).attr('data-input');
		if(val!=''){
			$(this).parents('.hm-form-group').find('input[name='+input+']').val(val);
			$.post( '?run=request_ajax.php&action=suggest', { val:val, input:input }, function( data ) {
				$('.auto_suggest_of_'+input).show();
				$('.auto_suggest_of_'+input).html(data);
			});
		}else{
			$('.auto_suggest_of_'+input).html('');
		}
	});
	
	
	$(document).on('click', '.auto_suggest_result > li > p', function(){
		var id = $(this).attr('data-id');
		var input = $(this).attr('data-input');
		var name = $(this).attr('data-name');
		$(this).parents('.hm-form-group').find('input[name='+input+']').val(id);
		$(this).parents('.hm-form-group').find('input[data-input='+input+']').val(name);
		$('.auto_suggest_result').hide();
	});
	
	
	//input suggest_data
	$(document).on('keyup','input.suggest_data', function(){
		var val = $(this).val();
		var input = $(this).attr('data-input');
		var key = $(this).attr('data-key');
		var type = $(this).attr('data-type');
		if(val!=''){
			$.post( '?run=request_ajax.php&action=suggest_data', { val:val, input:input, key:key, type:type }, function( data ) {
				$('.auto_suggest_of_'+input).show();
				$('.auto_suggest_choiced_of_'+input).show();
				$('.auto_suggest_of_'+input).html(data);
			});
		}else{
			$('.auto_suggest_of_'+input).html('');
		}
	});
	
	$(document).on('click', '.auto_suggest_data_result > li > p', function(){
		var id = $(this).attr('data-id');
		var input = $(this).attr('data-input');
		var name = $(this).attr('data-name');
		var object_id = $(this).attr('object_id');
		var object_type = $(this).attr('object_type');
		$(this).parents('.hm-form-group').find('input[name='+input+']').attr('waiting','1');
		var thisval = $('input[name='+input+'][waiting=1]').val();
		var label = $(this).html();
		if(thisval != ''){
			
			var splited = thisval.split(',');
			found = $.inArray(object_id, splited);
			if(found == '-1'){
				var newval = thisval+','+object_id;
				$('input[name='+input+'][waiting=1]').val(newval);
			}	
			
		}else{
			$('input[name='+input+'][waiting=1]').val(object_id);
		}
		$(this).parents('.hm-form-group').find('.auto_suggest_choiced_of_'+input+' > li.data_item_choiced[data-id='+id+']').remove();
		var line = '<li data-id="'+id+'" data-input="'+input+'" data-name="'+name+'" object_id="'+object_id+'" object_type="'+object_type+'" class="data_item_choiced" >'+label+'<span class="ajax-del-input-data" data-id="'+id+'" input-name="'+name+'" data-input="'+input+'">xóa</span></li>';
		$(this).parents('.hm-form-group').find('.auto_suggest_choiced_of_'+input).append(line);
		$(this).parents('.hm-form-group').find('input[name='+input+']').removeAttr('waiting');
		$(this).parent().remove();
	});
	
	$(document).on('click', 'li.data_item_choiced > .ajax-del-input-data', function(){
		
		var name = $(this).attr('data-name');
		var input = $(this).attr('data-input');
		var id = $(this).attr('data-id');
		$(this).parents('.hm-form-group').find('input[name='+input+']').attr('waiting','1');
		var inputval = $('input[name='+input+'][waiting=1]').val();
		var splited = inputval.split(',');
		$(splited).each(function(key){
			if(splited[key] == id){
				splited.splice(key,1);
			}
		});
		var newval = splited.join(',');
		$('input[name='+input+'][waiting=1]').val(newval);
		$(this).parents('.hm-form-group').find('.auto_suggest_choiced_of_'+input+' > li.data_item_choiced[data-id='+id+']').remove();
		$(this).parents('.hm-form-group').find('input[name='+input+']').removeAttr('waiting');
	});
	
    var fixHelperModifiedRela = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
    
        $helper.children().each(function(index) {
            $(this).width($originals.eq(index).width())
        });
        
        return $helper;
    };
  
    $(".auto_suggest_data_choiced").sortable({
        helper: fixHelperModifiedRela      
    });
	
	$(".auto_suggest_data_choiced").on('sortupdate',function(){ 
		var input = $(this).find('.data_item_choiced').attr('data-input');
		$(this).parents('.hm-form-group').find('input[name='+input+']').attr('waiting','1');
		var splited = [];
		$(this).find('.data_item_choiced[data-input='+input+']').each(function(index){ 
			var id = $(this).attr('data-id');
			splited.push(id);     
		});
		var newval = splited.join(',');
		$('input[name='+input+'][waiting=1]').val(newval);
		$(this).parents('.hm-form-group').find('input[name='+input+']').removeAttr('waiting');
	});
	// END input suggest_data
	
	
	$(".preview_media_file_multi").sortable({
		placeholder: "preview_media_file_multi_placeholder",
    });
	
	$(".preview_media_file_multi").on('sortupdate',function(){ 
		var input = $(this).find('.preview_media_file_wapper').attr('use_media_file');
		$(this).parents('.hm-form-group').find('input[name='+input+']').attr('waiting','1');
		var splited = [];
		$(this).find('.preview_media_file_wapper[use_media_file='+input+']').each(function(index){ 
			var id = $(this).attr('file-id');
			splited.push(id);     
		});
		var newval = splited.join(',');
		$('input[name='+input+'][waiting=1]').val(newval);
		$(this).parents('.hm-form-group').find('input[name='+input+']').removeAttr('waiting');
	});	
	
});
