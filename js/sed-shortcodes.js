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
	var obj, temp, dom, img, ix, style, html, flg;
	var attr = ['height', 'width'];
	// 正規表現
	var reg_ignore = new RegExp('img\-short\-icon|printicon', 'gi');
	var reg_noplain = new RegExp('thumbnail\-anchor|mosaic', 'gi');
	var reg_size = new RegExp('size\-', 'gi');
	// ホバー・クラス
	var over = 'image-over';
	var wrap = ['wrapper-link', 'wrapper-zoom'];
	// ホバー・エフェクト
	var efon = ['link-enter', 'zoom-enter'];
	var efof = ['link-exit', 'zoom-exit'];
	// アイコン
	var base = ['fa-circle', 'fa-circle'];
	var icon = ['fa-link', 'fa-camera'];
	// アニメーション
	var anon = ['fromleft', 'fromtop'];
	var anof = ['fadeout', 'fadeout'];

	// **************
	// *** 親要素 ***
	// **************
	jQuery.each(content, function(i, cont) {
		obj = jQuery.find(cont);
		if ( jQuery(obj[0]) ) {
			// ***************
			// *** <a>要素 ***
			// ***************
			jQuery.each(target, function(j, targ) {
				// ... [href]判定？
				obj = jQuery(cont).find('a[href]'+targ);
				if ( jQuery(obj[0]) ) {
					// <a>要素ごと
					jQuery(obj).each(function() {
						flg = 1;
						style = '';
						// <a>DOM
						dom = jQuery(this);
						// ... [class]に例外あり？
						temp = jQuery(dom).attr('class');
						if ( (typeof temp !== 'undefined') && (temp !== '') ) {
							if ( temp.match(reg_noplain) ) {
								flg = 0;
							}
						}
						// ... <img>あり？
						temp = jQuery(dom).find('img');
						if ( jQuery(temp[0]) ) {
							// <img>DOM
							img = temp[0];
							// ... [src]あり？
							temp = jQuery(img).attr('src');
							if ( (typeof temp !== 'undefined') && (temp !== '') ) {
								// ... [class]に例外あり？
								temp = jQuery(img).attr('class');
								if ( (typeof temp !== 'undefined') && (temp !== '') ) {
									if ( temp.match(reg_ignore) ) {
										// ... 以降の処理はスキップ
										return;
									} else {
										// ... [class]にサイズ指定あり？
										if ( temp.match(reg_size) ) {
											// ********************
											// *** サイズを取得 ***
											// ********************
											for ( ix = 0; ix < attr.length; ix++ ) {
												temp = jQuery(img).attr(attr[ix]);
												if ( (typeof temp !== 'undefined') && (temp !== '') ) {
													style += attr[ix]+':'+parseInt(temp)+'px!important;';
												}
											}
											// オーバーレイのサイズ指定
											if ( style !== '' ) {
												style = ' style="'+style+'"';
											}
										}
									}
								}
								// *********************************
								// *** <img>をオーバーレイで囲む ***
								// *********************************
								html = '';
								html += '<div class="'+over+'-'+wrap[j]+'"'+style+'>';
								jQuery(img).wrap(html);
								// ******************************
								// *** アイコン・クラスを追加 ***
								// ******************************
								html = '';
								if ( flg ) {
									html += '<div class="'+over+'-plain"'+style+'>';
									html += '</div>';
								}
								html += '<div class="'+over+'"'+style+'>';
								html += '<span class="fa-stack fa-lg">';
								html += '<i class="fa '+base[j]+' fa-stack-2x"></i>';
								html += '<i class="fa '+icon[j]+' fa-stack-1x fa-inverse"></i>';
								html += '</span>';
								html += '</div>';
								jQuery(dom).find('.'+over+'-'+wrap[j]).append(html);
								// ******************************************
								// *** ホバー・エフェクトとアニメーション ***
								// ******************************************
								jQuery(dom).hover(function() {
									temp = jQuery(this);
									jQuery(temp).find('img')
										.removeClass(over+'-'+efof[j])
										.addClass(over+'-'+efon[j]);
									if ( flg ) {
										jQuery(temp).find('.'+over+'-plain')
											.removeClass(over+'-plain-exit')
											.addClass(over+'-plain-enter');
									}
									jQuery(temp).find('.'+over)
										.removeClass(over+'-'+anof[j])
										.addClass(over+'-'+anon[j]);
								}, function() {
									temp = jQuery(this);
									jQuery(temp).find('.'+over)
										.removeClass(over+'-'+anon[j])
										.addClass(over+'-'+anof[j]);
									if ( flg ) {
										jQuery(temp).find('.'+over+'-plain')
											.removeClass(over+'-plain-enter')
											.addClass(over+'-plain-exit');
									}
									jQuery(temp).find('img')
										.removeClass(over+'-'+efon[j])
										.addClass(over+'-'+efof[j]);
								});
							}
						}
					});
				}
			});
		}
	});
	// 変数を破棄
	delete content, cont, target, targ, obj, temp, dom, img;
	delete reg_ignore, reg_noplain, reg_size;
});
