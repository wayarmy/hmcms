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

$(document).ready(function(){
	
	$('live_text').parent().attr('href','#');
	$('live_textarea').parent().attr('href','#');
	$('live_editor').parent().attr('href','#');
	$('live_multiimage').parent().attr('href','#');
	$('live_ahref').parent().attr('href','#');
	$('live_image').parent().attr('href','#');
	
	
	var live_editing = getParameterByName('live_editing');
	if(live_editing!=''){
		var option_tag = $('*[data-option_name='+live_editing+']');
		if (option_tag.length) {
			$('html, body').animate({
				scrollTop: $('*[data-option_name='+live_editing+']').offset().top - 40
			}, 1000);
		}
	}
	
	$('body').append('<div class="hm_live_edit_bg"><div class="hm_live_edit_popup"></div></div>');
	var height = $(document).height();
	$('.hm_live_edit_bg').height($(document).height());
	
	$('live_text live_edit_btn').click(function(){
		$('.hm_live_edit_bg').show();
		$('.hm_live_edit_popup').show();
		var option_name = $(this).attr('data-option_name');
		$('.hm_live_edit_bg').attr('option_name',option_name);
		$('.hm_live_edit_popup').html('<iframe src="/admin/?run=live_edit.php&live_edit=on&type=text&option_name='+option_name+'" width="100%" height="600px" id="fullheight_iframe" onLoad="calcHeight();" frameborder="0"></iframe>');
		$("html, body").animate({ scrollTop: 0 }, "slow");
	});
	
	$('live_textarea live_edit_btn').click(function(){
		$('.hm_live_edit_bg').show();
		$('.hm_live_edit_popup').show();
		var option_name = $(this).attr('data-option_name');
		$('.hm_live_edit_bg').attr('option_name',option_name);
		$('.hm_live_edit_popup').html('<iframe src="/admin/?run=live_edit.php&live_edit=on&type=textarea&option_name='+option_name+'" width="100%" height="600px" id="fullheight_iframe" onLoad="calcHeight();" frameborder="0"></iframe>');
		$("html, body").animate({ scrollTop: 0 }, "slow");
	});
	
	$('live_editor live_edit_btn').click(function(){
		$('.hm_live_edit_bg').show();
		$('.hm_live_edit_popup').show();
		var option_name = $(this).attr('data-option_name');
		$('.hm_live_edit_bg').attr('option_name',option_name);
		$('.hm_live_edit_popup').html('<iframe src="/admin/?run=live_edit.php&live_edit=on&type=editor&option_name='+option_name+'" width="100%" height="600px" id="fullheight_iframe" onLoad="calcHeight();" frameborder="0"></iframe>');
		$("html, body").animate({ scrollTop: 0 }, "slow");
	});
	
	$('live_multiimage live_edit_btn').click(function(){
		$('.hm_live_edit_bg').show();
		$('.hm_live_edit_popup').show();
		var option_name = $(this).attr('data-option_name');
		$('.hm_live_edit_bg').attr('option_name',option_name);
		$('.hm_live_edit_popup').html('<iframe src="/admin/?run=live_edit.php&live_edit=on&type=multiimage&option_name='+option_name+'" width="100%" height="600px" id="fullheight_iframe" onLoad="calcHeight();" frameborder="0"></iframe>');
		$("html, body").animate({ scrollTop: 0 }, "slow");
	});
	
	$('live_image live_edit_btn').click(function(){
		$('.hm_live_edit_bg').show();
		$('.hm_live_edit_popup').show();
		var option_name = $(this).attr('data-option_name');
		$('.hm_live_edit_bg').attr('option_name',option_name);
		$('.hm_live_edit_popup').html('<iframe src="/admin/?run=live_edit.php&live_edit=on&type=image&option_name='+option_name+'" width="100%" height="600px" id="fullheight_iframe" onLoad="calcHeight();" frameborder="0"></iframe>');
		$("html, body").animate({ scrollTop: 0 }, "slow");
	});
	
	$('live_ahref live_edit_btn').click(function(){
		$('.hm_live_edit_bg').show();
		$('.hm_live_edit_popup').show();
		var option_name = $(this).attr('data-option_name');
		$('.hm_live_edit_bg').attr('option_name',option_name);
		$('.hm_live_edit_popup').html('<iframe src="/admin/?run=live_edit.php&live_edit=on&type=ahref&option_name='+option_name+'" width="100%" height="600px" id="fullheight_iframe" onLoad="calcHeight();" frameborder="0"></iframe>');
		$("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	});
	
	$('.hm_live_edit_bg').click(function(){
		$('.hm_live_edit_bg').hide();
		$('.hm_live_edit_popup').hide();
		var option_name = $('.hm_live_edit_bg').attr('option_name');
		window.location.href = window.location.pathname+'?live_editing='+option_name;
	});
	
});