!function($) {

/* ===========================================================
 * jquery-flatshadow.js v1
 * ===========================================================
 * Copyright 2013 Pete Rojwongsuriya.
 * http://www.thepetedesign.com
 *
 * A small jQuery plugin that will automatically
 * cast a shadow creating depth for your flat UI elements
 * https://github.com/peachananr/flat-shadow
 *
 * ========================================================== */

	var colors = new Array(
		"#1ABC9C",
		"#2ecc71",
		"#3498db",
		"#9b59b6",
		"#34495e",
		"#f1c40f",
		"#e67e22",
		"#e74c3c"
	);

	var angles = new Array(
		"NE",
		"SE",
		"SW",
		"NW"
	);

	var defaults = {
		fade: false,
		color: "random",
		boxShadow: false,
		angle: "random"
	};

	function convertHex(hex, opacity) {
		hex = hex.replace( '#', '' );
		r = parseInt( hex.substring( 0, 2 ), 16 );
		g = parseInt( hex.substring( 2, 4 ), 16 );
		b = parseInt( hex.substring( 4, 6 ), 16 );

		result = 'rgba(' + r + ',' + g + ',' + b + ',' + opacity / 100 + ')';
		return result;
	}

	function colorLuminance(hex, lum) {
		// validate hex string
		hex = String(hex).replace( /[^0-9a-f]/gi, '' );
		if ( hex.length < 6 ) {
			hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
		}
		lum = lum || 0;
		// convert to decimal and change luminosity
		var rgb = "#", c, i;
		for ( i = 0; i < 3; i++ ) {
			c = parseInt( hex.substr( i*2, 2 ), 16 );
			c = Math.round( Math.min( Math.max( 0, c + (c * lum) ), 255) ).toString(16);
			rgb += ( "00" + c ).substr( c.length );
		}
		return rgb;
	}

	$.fn.flatshadow = function(options) {
		var settings = $.extend( {}, defaults, options );

		return this.each(function() {
			el = $(this);

			if ( settings.fade == true ) {
				width = Math.round( el.outerWidth() / 3 );
				height = Math.round( el.outerHeight() / 3 );
			} else {
				width = Math.round( el.outerWidth() );
				height = Math.round( el.outerHeight() );
			}

			if ( settings.boxShadow != false ) {
				var bg_color = settings.boxShadow;
			}

			if ( el.attr('data-color') ) {
				var color = el.attr('data-color');
			} else {
				var color = settings.color;
			}

			if ( color == "random" ) {
				color = colors[Math.floor( Math.random() * colors.length )];
			}

			if ( el.attr('data-angle') ) {
				var angle = el.attr('data-angle');
			} else {
				var angle = settings.angle;
			}

			if ( angle == "random" ) {
				angle = angles[Math.floor( Math.random() * angles.length )];
			}

			var darkercolor = colorLuminance( color, -0.3 );
			var text_shadow = "";

			if ( settings.boxShadow != false ) {
				var box_shadow = "";
			} else {
				var box_shadow = "none";
			}

			var iLimit = angle == 'E' ? width : height;
			var text_color = darkercolor;

			for( var i = 1; i <= iLimit; i++ ) {
				if ( settings.fade != false ) {
					text_color = convertHex( darkercolor, 100 - i/iLimit * 100 );
				}

				switch ( angle ) {
					case 'N':
						if ( settings.boxShadow != false ) {
							box_shadow += "0px " + (i * 2) * -1 + "px 0px " + convertHex( bg_color, (50 - i/ height * 100) );
						}
						text_shadow += "0px " + i * -1 + "px 0px " + text_color;
						break;
					case 'NE':
						if ( settings.boxShadow != false ) {
							box_shadow += i * 2 + "px " + (i * 2) * -1 + "px 0px " + convertHex( bg_color, (50 - i/ height * 100) );
						}
						text_shadow += i + "px " + i * -1 + "px 0px " + text_color;
						break;
					case 'E':
						if ( settings.boxShadow != false ) {
							box_shadow += i * 2 + "px " + "0px 0px " + convertHex( bg_color, (50 - i/ width * 100) );
						}
						text_shadow += i + "px " + "0px 0px " + text_color;
						break;
					case 'SE':
						if ( settings.boxShadow != false ) {
							box_shadow += i * 2 + "px " + i * 2 + "px 0px " + convertHex( bg_color, (50 - i/ height * 100) );
						}
						text_shadow += i + "px " + i + "px 0px " + text_color;
						break;
					case 'S':
						if ( settings.boxShadow != false ) {
							box_shadow += "0px " + i * 2 + "px 0px " + convertHex( bg_color, (50 - i/ height * 100) );
						}
						text_shadow += "0px " + i + "px 0px " + text_color;
						break;
					case 'SW':
						if ( settings.boxShadow != false ) {
							box_shadow += (i * 2) * -1 + "px " + i * 2 + "px 0px " + convertHex( bg_color, (50 - i/ height * 100) );
						}
						text_shadow += i * -1 + "px " + i + "px 0px " + text_color;
						break;
					case 'W':
						if ( settings.boxShadow != false ) {
							box_shadow += (i * 2) * -1 + "px " + "0px 0px " + convertHex( bg_color, (50 - i/ height * 100) );
						}
						text_shadow += i * -1 + "px " + "0px 0px " + text_color;
						break;
					case 'NW':
						if ( settings.boxShadow != false ) {
							box_shadow += (i * 2) * -1 + "px " + (i * 2) * -1 + "px 0px " + convertHex( bg_color, (50 - i/ height * 100) );
						}
						text_shadow += i * -1 + "px " + i * -1 + "px 0px " + text_color;
						break;
				}

				if ( i != iLimit ) {
					 text_shadow += ", ";
					 box_shadow += ", ";
				 }
			}

			el.css({
				"background": color,
				"color": colorLuminance( color, 1 ),
				"text-shadow": text_shadow,
				"box-shadow": box_shadow
			});

		});
	}

} (window.jQuery);


!function($) {

/* ===========================================================
 * リンク先のあるイメージにオーバーレイ・アイコンを表示する
 * ========================================================== */

	// 親要素
	var idx, cont;
	var content = new Array(
		'#page-container',
		'#content',
		'.content-inner'
	);
	// <a>要素
	var jdx, targ;
	var target = new Array(
		'a:not([rel*="shadowbox"])',
		'a[rel*="shadowbox"]'
	);
	// 一時変数
	var obj, temp, dom, img, ix, len, style, html, flg;
	var attr = new Array(
		'height',
		'width'
	);
	// 正規表現
	var reg_ignore = new RegExp('img\-short\-icon|printicon', 'gi');
	var reg_noplain = new RegExp('thumbnail\-anchor|mosaic', 'gi');
	var reg_size = new RegExp('size\-', 'gi');
	// ホバー・クラス
	var over = 'image-over';
	var wrap = new Array(
		'wrapper-link',
		'wrapper-zoom'
	);
	// ホバー・エフェクト
	var efon = new Array(
		'link-enter',
		'zoom-enter'
	);
	var efof = new Array(
		'link-exit',
		'zoom-exit'
	);
	// アイコン
	var pref = 'fa';
	var base = new Array(
		'circle',
		'circle'
	);
	var icon = new Array(
		'link',
		'camera'
	);
	// アニメーション
	var anon = new Array(
		'frombottom',
		'fromtop'
	);
	var anof = new Array(
		'fadeout',
		'fadeout'
	);

	// **************
	// *** 親要素 ***
	// **************
	$.each(content, function(idx, cont) {
		obj = $.find(cont);
		if ( $(obj[0]) ) {
			// ***************
			// *** <a>要素 ***
			// ***************
			$.each(target, function(jdx, targ) {
				// ... [href]判定？
				obj = $(cont).find('a[href]' + targ);
				if ( $(obj[0]) ) {
					// <a>要素ごと
					$(obj).each(function() {
						flg = 1;
						style = '';
						// <a>DOM
						dom = $(this);
						// ... [class]に例外あり？
						temp = $(dom).attr('class');
						if ( (typeof temp != 'undefined') && (temp != '') ) {
							if ( temp.match( reg_noplain ) ) {
								flg = 0;
							}
						}
						// ... <img>あり？
						temp = $(dom).find('img');
						if ( $(temp[0]) ) {
							// <img>DOM
							img = temp[0];
							// ... [src]あり？
							temp = $(img).attr('src');
							if ( (typeof temp != 'undefined') && (temp != '') ) {
								// ... [class]に例外あり？
								temp = $(img).attr('class');
								if ( (typeof temp != 'undefined') && (temp != '') ) {
									if ( temp.match( reg_ignore ) ) {
										// ... 以降の処理はスキップ
										return;
									} else {
										if ( jdx == 0 ) {
											// ********************
											// *** サイズを取得 ***
											// ********************
											for ( ix = 0, len = attr.length; ix < len; ix++ ) {
												temp = $(img).attr(attr[ix]);
												if ( (typeof temp != 'undefined') && (temp != '') ) {
													style += attr[ix] + ':' + parseInt(temp) + 'px;';
												}
											}
											// オーバーレイのサイズ指定
											if ( style != '' ) {
												style = ' style="' + style + '"';
											}
										}
									}
								}
								// *********************************
								// *** <img>をオーバーレイで囲む ***
								// *********************************
								html = '';
								html += '<div class="' + over + '-' + wrap[jdx] + '"' + style + '>';
								$(img).wrap(html);
								// ******************************
								// *** アイコン・クラスを追加 ***
								// ******************************
								html = '';
								// ... [class]に例外無し？
								if ( flg ) {
									html += '<div class="' + over + '-plain"' + style + '></div>';
								}
								html += '<div class="' + over + '"' + style + '>';
								html += '<span class="' + pref + '-stack ' + pref + '-lg">';
								html += '<i class="' + pref + ' ' + pref + '-' + base[jdx] + ' ' + pref + '-stack-2x"></i>';
								html += '<i class="' + pref + ' ' + pref + '-' + icon[jdx] + ' ' + pref + '-stack-1x '+pref + '-inverse"></i>';
								html += '</span>';
								html += '</div>';
								$(dom).find('.' + over + '-' + wrap[jdx]).append(html);
								// ******************************************
								// *** ホバー・エフェクトとアニメーション ***
								// ******************************************
								$(dom).hover(function() {
									temp = $(this);
									// ... [class]に例外無し？
									if ( flg ) {
										$(temp).find('.' + over + '-plain')
											.removeClass(over + '-plain-exit')
											.addClass(over + '-plain-enter');
									}
									$(temp).find('img')
										.removeClass(over + '-' + efof[jdx])
										.addClass(over + '-' + efon[jdx]);
									$(temp).find('.' + over)
										.removeClass(over + '-' + anof[jdx])
										.addClass(over + '-' + anon[jdx]);
								}, function() {
									temp = $(this);

									$(temp).find('.' + over)
										.removeClass(over + '-' + anon[jdx])
										.addClass(over + '-' + anof[jdx]);
									$(temp).find('img')
										.removeClass(over + '-' + efon[jdx])
										.addClass(over + '-' + efof[jdx]);
									// ... [class]に例外無し？
									if ( flg ) {
										$(temp).find('.' + over + '-plain')
											.removeClass(over + '-plain-enter')
											.addClass(over + '-plain-exit');
									}
								});
							}
						}
					});
				}
			});
		}
	});

} (window.jQuery);


!function($) {

/* ===========================================================
 * wikiの検索結果をツールチップに
 * ========================================================== */

	wikiQuantity = 0,

	DEFAULTS = {
		error: 'Error. Try again.',
		loading: 'Loading',
		lang: 'en'
	},

	wikiUp = function($element, options) {
		var containerId = 'wiki-' + (wikiQuantity++);

		$element
			.data('wikiUp', containerId)
			.bind('mouseover', function() {
				if ( !$element.children('.tooltip').length ) {
					$element.append('<div class="tooltip"><span></span><div id="' + containerId + '"></div></div>');
					wikiLoad($element, options);
				}
			});
	},

	wikiLoad = function($element, options) {
		var $container = $('#' + $element.data('wikiUp')),
			lang = $element.data('lang') || options.lang,
			page = $element.data('wiki'),
			url = 'http://' + lang + '.wikipedia.org/w/api.php?callback=?&action=parse&page=' + page + '&prop=text&format=json&section=0';

		$.ajax({
			type: 'GET',
			url: url,
			data: {},
			async: true,
			contentType: 'application/json; charset=utf-8',
			dataType: 'jsonp',
			success: function(response) {
				var found = false,
					paragraphCount = 0,
					$allText = $(response.parse.text['*']),
					intro;
				while ( found == false ) {
					found = true;
					intro = $allText.filter('p:eq(' + paragraphCount + ')').html();

					if ( intro.indexOf('<span') == 0 ) {
						paragraphCount++;
						found = false;
					}
				}

				$container
					.html(intro)
					.find('a')
						.attr('target', '_blank')
						.not('.references a')
							.attr('href', function(i, href) {
								if ( href.indexOf('http') != 0 ) {
									href = 'http://' + lang + '.wikipedia.org' + href;
								}
								return href;
							})
							.end()
						.end()
					.find('sup.reference')
						.remove();
			},

			error: function(XMLHttpRequest, textStatus, errorThrown) {
				 $container.html(options.error);
			},

			beforeSend: function(XMLHttpRequest) {
				$container.html(options.loading);
			}
		});
	};

	$.fn.wikiUp = function(options) {
		options = $.extend(true, {}, DEFAULTS, options);

		return this.each(function() {
			var $element = $(this);
			if ( !$element.data('wikiUp') ) {
				wikiUp($element, options);
			}
		});
	};

	$(function() {
		$('data[data-wiki]').wikiUp();
	});

} (window.jQuery);


!function($) {

/* ===========================================================
 * スクロールしたら「トップへ戻るボタン」を表示
 * ========================================================== */

	var cont = $('html, body');
	var pref = 'gototop';
	var text = '上へ戻る';
	var elem = $('#' + pref);
	var aref = $('#' + pref + ' a');
	var itvl = 205;

	var obj, navp = '#nav', navf = 'nav-fixed';
	// ******************************
	// *** トップへ戻るボタン ***
	// ******************************
	var html, icon = 'fa';
	html  = '';
	html += '<a title="' + text + '">';
	html += '<span class="' + icon + '-stack ' + icon + '-lg textshadow">';
	html += '<i class="' + icon + ' ' + icon + '-square ' + icon + '-stack-2x"></i>';
	html += '<i class="' + icon + ' ' + icon + '-arrow-up ' + icon + '-inverse ' + icon + '-stack-1x"></i>';
	html += '</span>';
	html += '</a>';

	if ( elem.length != 1 ) {
		elem = $('<div>')
			.attr('id', pref)
			.addClass(pref)
			.addClass(pref + '-hide')
			.html(html)
			.appendTo($('body'));
	}

	$(window).scroll(function() {
		if ( $(this).scrollTop() > parseInt(itvl) ) {
			if ( elem.hasClass(pref + '-hide') ) {
				// *** ボタンを可視 ***
				elem.removeClass(pref + '-hide')
					.addClass(pref + '-show');
			}
		} else {
			if ( !elem.hasClass(pref + '-hide') ) {
				// *** ボタンを非可視 ***
				elem.removeClass(pref + '-show')
					.addClass(pref + '-hide')
					.find('a')
					.removeClass(pref + '-clicked');
			}
		}
	});

	aref.click(function(e) {
		// *** ブラウザのアクションを中断させる ***
		e.preventDefault();

		if ( $(window).scrollTop() > 0 && !$(this).hasClass(pref + '-clicked') ) {
			$(this).addClass(pref + '-clicked');
			// *** スクロールして上へ戻る ***
			cont.animate( { scrollTop: cont.offset().top }, 300 );
			// ******************************
			// *** 位置固定メニューがある場合 ***
			// *** 位置固定クラスを削除 ***
			// ******************************
			obj = $.find(navp);
			if ( $(obj[0]) ) {
				obj = $(obj[0]);
				if ( obj.hasClass(navf) ) {
					obj.removeClass(navf);
				}
			}
		}
	});

} (window.jQuery);

