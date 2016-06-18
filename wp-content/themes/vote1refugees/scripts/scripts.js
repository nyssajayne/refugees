(function($) {
	$(document).ready(function(){
		/*var target = $("#menu-main-menu").offset().top,
		timeout = null;

		console.log($('.nav-desktop').position().left);

		var left = $('.nav-desktop').position().left - 31;

		$(window).scroll(function () {
			if (!timeout) {
				timeout = setTimeout(function () {
					clearTimeout(timeout);
					timeout = null;
					if ($(window).scrollTop() >= target) {
						$('#menu-main-menu').addClass('nav-sticky');
						//$('#menu-main-menu').css('left', left);
					}
					else if($(window).scrollTop() < target) {
						$('#menu-main-menu').removeClass('nav-sticky');
					}
				}, 250);
			}
		});*/

		$('#nav-menu-mob').on('click', function(){
			if($('#menu-main-menu-1').children().is(':hidden')) {
				$('#menu-main-menu-1').children().each(function(){
					if($(this).attr('id') == 'nav-menu-mob')
						return true;
					else
						$(this).slideDown();
				})
			}
			else {
				$('#menu-main-menu-1').children().each(function(){
					if($(this).attr('id') == 'nav-menu-mob')
						return true;
					else
						$(this).slideUp();
				})
			}
		})
	});
})(jQuery);