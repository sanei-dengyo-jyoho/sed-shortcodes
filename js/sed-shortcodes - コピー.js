jQuery.noConflict();
jQuery(document).ready(function() {
	// ***************************************************************************
	// リンク先のあるイメージにアイコンを表示する
	// ***************************************************************************
	var obj, temp, style, swidth, sheight, width, height;
	// parent element
	var content = ['#content', '.content'];
	// <a> element
	var element = ['a:not([rel*="shadowbox"])', 'a[rel*="shadowbox"]'];
	// overlay class
	var over = 'image-overlay';
	// icon class
	var base = ['icon-circle', 'icon-circle'];
	var icon = ['icon-link', 'icon-zoom-in'];
	// animation class
	var afon = ['image-overlay-from-left', 'image-overlay-from-top'];
	var afoff = ['image-overlay-fadeout', 'image-overlay-fadeout'];

	jQuery.each(content, function(i, cont) {
		var obj = jQuery.find(cont);
		if (obj[0]) {
			jQuery.each(element, function(j, elem) {
				var obj = jQuery(cont).find(elem);
				if (obj[0]) {
					jQuery(obj).each(function() {
						style = '';
						swidth = '';
						sheight = '';
						width = '';
						height = '';
						// ... is <a> has <img> ?
						temp = jQuery(this).find('img').attr('src');
						if ((typeof temp != 'undefined') && (temp != '')) {
							// ... is <a> has [href] ?
							temp = jQuery(this).attr('href');
							if ((typeof temp != 'undefined') && (temp != '')) {
								// *******************************************************
								// main proccess
								// *******************************************************
								// get <img> widht
								width = jQuery(this).find('img').attr('width');
								if ((typeof width != 'undefined') && (width != '')) {
									swidth = 'width:'+parseInt(width)+'px !important;';
								} else {
									// ... in case of [rel*="shadowbox"]
									if (j != 0) {
										width = jQuery(this).find('img').width();
										if ((typeof width != 'undefined') && (width != '')) {
											swidth = 'width:'+parseInt(width)+'px !important;';
										}
									}
								}
								// get <img> height
								height = jQuery(this).find('img').attr('height');
								if ((typeof height != 'undefined') && (height != '')) {
									sheight = 'height:'+parseInt(height)+'px !important;';
								} else {
									// ... in case of [rel*="shadowbox"]
									if (j != 0) {
										height = jQuery(this).find('img').height();
										if ((typeof height != 'undefined') && (height != '')) {
											sheight = 'height:'+parseInt(height)+'px !important;';
										}
									}
								}
								// set overlay style
								if ((swidth != '') || (sheight != '')) {
									if (swidth == '') {
										swidth = 'width:100% !important;';
									}
									if (sheight == '') {
										sheight = 'height:100% !important;';
									}
									style = ' style = "'+swidth+sheight+'"';
								}
								// <img> wrap overlay
								jQuery(this).find('img').wrap(
									'<div class="'+over+'-wrap"'+style+'>'
								);
								// overlay plus icon
								jQuery(this).find('.'+over+'-wrap').append(
									'<p class="'+over+'"'+style+'><span class="icon-stack">'+
									'<i class="'+base[j]+' icon-stack-base"></i>'+
									'<i class="'+icon[j]+' icon-light"></i>'+
									'</span></p>'
								);
								// hover effect
								jQuery(this).hover(function() {
									jQuery(this).find('.'+over)
										.removeClass(afoff[j])
										.addClass(afon[j]);
								}, function() {
									jQuery(this).find('.'+over)
										.removeClass(afon[j])
										.addClass(afoff[j]);
								});
							}
						}
					});
				};
			});
		};
	});
});
