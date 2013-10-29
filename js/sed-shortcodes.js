jQuery.noConflict();
jQuery(document).ready(function() {
	//*****************************************************************
	// *** リンク先のあるイメージにオーバーレイ・アイコンを表示する ***
	//*****************************************************************
	// 親要素
	var content = ['#page-container', '#content', '.content'];
	var i, cont;
	// <a>要素
	var target = ['a:not([rel*="shadowbox"])', 'a[rel*="shadowbox"]'];
	var j, targ;
	// 一時変数
	var obj, temp, dom, img, ix, style, html;
	var attr = ['height', 'width'];
	// 正規表現
	var reg_ignore = new RegExp('img\-short\-icon|mosaic|printicon', 'gi');
	var reg_size = new RegExp('size\-', 'gi');
	// オーバーレイ
	var over = 'image-overlay';
	// アイコン
	var base = ['fa-circle', 'fa-circle'];
	var icon = ['fa-link', 'fa-camera'];
	// ホバー・エフェクト
	var hvon = ['image-hover-link-enter', 'image-hover-zoom-enter'];
	var hvof = ['image-hover-link-exit', 'image-hover-zoom-exit'];
	// アニメーション
	var anon = ['image-overlay-fromleft', 'image-overlay-fromtop'];
	var anof = ['image-overlay-fadeout', 'image-overlay-fadeout'];

	// **************
	// *** 親要素 ***
	// **************
	jQuery.each(content, function(i, cont) {
		obj = jQuery.find(cont);
		if (jQuery(obj[0])) {
			// ***************
			// *** <a>要素 ***
			// ***************
			jQuery.each(target, function(j, targ) {
				// ... [href]判定？
				obj = jQuery(cont).find('a[href]'+targ);
				if (jQuery(obj[0])) {
					// <a>要素ごと
					jQuery(obj).each(function() {
						style = '';
						// <a>DOM
						dom = jQuery(this);
						// ... <img>あり？
						temp = jQuery(dom).find('img');
						if (jQuery(temp[0])) {
							// <img>DOM
							img = temp[0];
							// ... [src]あり？
							temp = jQuery(img).attr('src');
							if ((typeof temp !== 'undefined') && (temp !== '')) {
								// ... [class]に例外あり？
								temp = jQuery(img).attr('class');
								if ((typeof temp !== 'undefined') && (temp !== '')) {
									if (temp.match(reg_ignore)) {
										// ****************************
										// *** 以降の処理はスキップ ***
										// ****************************
										return;
									} else {
										// ... [class]にサイズ指定あり？
										if (temp.match(reg_size)) {
											// ********************
											// *** サイズを取得 ***
											// ********************
											for (ix = 0; ix < attr.length; ix++) {
												temp = jQuery(img).attr(attr[ix]);
												if ((typeof temp !== 'undefined') && (temp !== '')) {
													style += attr[ix]+':'+parseInt(temp)+'px!important;';
												}
											}
											// オーバーレイのサイズ指定
											if (style !== '') {
												style = ' style="'+style+'"';
											}
										}
									}
								}
								// *********************************
								// *** <img>をオーバーレイで囲む ***
								// *********************************
								html = '';
								html += '<div class="'+over+'-wrap"'+style+'>';
								jQuery(img).wrap(html);
								// ******************************
								// *** アイコン・クラスを追加 ***
								// ******************************
								html = '';
								html += '<p class="'+over+'"'+style+'>';
								html += '<span class="fa-stack fa-lg">';
								html += '<i class="fa '+base[j]+' fa-stack-2x"></i>';
								html += '<i class="fa '+icon[j]+' fa-stack-1x fa-inverse"></i>';
								html += '</span>';
								html += '</p>';
								jQuery(dom).find('.'+over+'-wrap').append(html);
								// ******************************************
								// *** ホバー・エフェクトとアニメーション ***
								// ******************************************
								jQuery(dom).hover(function() {
									temp = jQuery(this);
									jQuery(temp).find('img')
										.removeClass(hvof[j])
										.addClass(hvon[j]);
									jQuery(temp).find('.'+over)
										.removeClass(anof[j])
										.addClass(anon[j]);
								}, function() {
									temp = jQuery(this);
									jQuery(temp).find('.'+over)
										.removeClass(anon[j])
										.addClass(anof[j]);
									jQuery(temp).find('img')
										.removeClass(hvon[j])
										.addClass(hvof[j]);
								});
							}
						}
					});
				}
			});
		}
	});
	// 変数を破棄
	delete content, cont, target, targ, obj, temp, dom, img, attr, reg_ignore, reg_size;
	delete base, icon, hvon, hvof, anon, anof;
});
