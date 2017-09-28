(function($) {
	
	
  $.fn.menumaker = function(options) {
      
      var hm_footer_menu = $(this), settings = $.extend({
        title: "Menu",
        format: "dropdown",
        sticky: false
      }, options);

      return this.each(function() {
        hm_footer_menu.prepend('<div id="menu-button">' + settings.title + '</div>');
        $(this).find("#menu-button").on('click', function(){
          $(this).toggleClass('menu-opened');
          var mainmenu = $(this).next('ul');
          if (mainmenu.hasClass('open')) { 
            mainmenu.hide().removeClass('open');
          }
          else {
            mainmenu.show().addClass('open');
            if (settings.format === "dropdown") {
              mainmenu.find('ul').show();
            }
          }
        });

        hm_footer_menu.find('li ul').parent().addClass('has-sub');

        multiTg = function() {
          hm_footer_menu.find(".has-sub").prepend('<span class="submenu-button"></span>');
          hm_footer_menu.find('.submenu-button').on('click', function() {
            $(this).toggleClass('submenu-opened');
            if ($(this).siblings('ul').hasClass('open')) {
              $(this).siblings('ul').removeClass('open').hide();
            }
            else {
              $(this).siblings('ul').addClass('open').show();
            }
          });
        };

        if (settings.format === 'multitoggle') multiTg();
        else hm_footer_menu.addClass('dropdown');

        if (settings.sticky === true) hm_footer_menu.css('position', 'fixed');

        resizeFix = function() {
          if ($( window ).width() > 768) {
            hm_footer_menu.find('ul').show();
          }

          if ($(window).width() <= 768) {
            hm_footer_menu.find('ul').hide().removeClass('open');
          }
        };
        resizeFix();
        return $(window).on('resize', resizeFix);

      });
  };
})(jQuery);

(function($){
	$(document).ready(function(){
		
		if(top != self){
			$('.hm_footer_hide_in_iframe').remove();
		}
		
		$("#hm_footer_menu").menumaker({
		   title: "Menu",
		   format: "multitoggle"
		});

	});
})(jQuery);
