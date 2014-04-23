!function($){

/* ===========================================================
 * スクロールしたら「トップへ戻るボタン」を表示
 * ========================================================== */

	var con = $('html, body');
	var nav = $('#nav'); 
	var topLink = $('#top-link'); 
	var topLinkA = $('#top-link a');

	if ( topLink.length != 1 ) {
		topLink = $('<div>')
			.attr('id', 'top-link')
			.addClass('top-link')
			.addClass('top-link-hide')
			.html('<a href="#top" title="上へ戻る"><i class="fa fa-caret-up fa-lg"></i></a>')
			.appendTo($('body'));
	}

	$(window).scroll(function(){
		if ( $(this).scrollTop() > 105 ) {
			if ( topLink.hasClass('top-link-hide') ) {
				topLink.removeClass('top-link-hide');
			}
		} else {
			if ( !topLink.hasClass('top-link-hide') ) {
				topLink
					.addClass('top-link-hide')
					.find('a')
					.removeClass('top-link-clicked');
			}
		}
	});

	topLinkA.click(function(){
		if ( !$(this).hasClass('top-link-clicked') ) {
			$(this).addClass('top-link-clicked');
			con.animate({ scrollTop: con.offset().top }, 300);

			if ( nav.hasClass('nav-fixed') ) {
				nav.removeClass('nav-fixed');
			}
		}
	});

}(window.jQuery);

