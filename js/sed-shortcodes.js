jQuery.noConflict();
jQuery(document).ready(function() {
	//*****************************************************************
	// *** リンク先のあるイメージにオーバーレイ・アイコンを表示する ***
	//*****************************************************************
	// 親要素
	var content = ['#page-container', '#content', '.content'];
	var i, cont;
	// <a>要素
	var element = ['a:not([rel*="shadowbox"])', 'a[rel*="shadowbox"]'];
	var j, elem;
	// 正規表現
	var reg_ignore = new RegExp('img\-short\-icon|mosaic|printicon', 'gi');
	var reg_size = new RegExp('size\-', 'gi');
	// 一時変数
	var obj, temp, targ, img, attr, ix, style, html;
	// 処理スイッチ
	var exec;
	// オーバーレイ
	var over = 'image-overlay';
	// アイコン
	var base = ['icon-circle', 'icon-circle'];
	var icon = ['icon-link', 'icon-zoom-in'];
	// ホバー・エフェクト
	var hvon = 'image-hover-enter';
	var hvoff = 'image-hover-exit';
	// アニメーション
	var afon = ['image-overlay-fromleft', 'image-overlay-fromtop'];
	var afoff = ['image-overlay-fadeout', 'image-overlay-fadeout'];

	// **************
	// *** 親要素 ***
	// **************
	jQuery.each(content, function(i, cont) {
		obj = jQuery.find(cont);
		if (obj[0]) {
			// ***************
			// *** <a>要素 ***
			// ***************
			jQuery.each(element, function(j, elem) {
				// ... [href]判定？
				obj = jQuery(cont).find('a[href]'+elem);
				if (obj[0]) {
					// <a>要素ごと
					jQuery(obj).each(function() {
						style = '';
						// <a>DOM
						targ = jQuery(this);
						// 処理スイッチＯＮ
						exec = 1;
						// ... <img>あり？
						temp = jQuery(targ).find('img');
						if (temp[0]) {
							// <img>DOM
							img = temp[0];
							// ... [src]あり？
							temp = jQuery(img).attr('src');
							if ((typeof temp !== 'undefined') && (temp !== '')) {
								// ... [class]に例外あり？
								temp = jQuery(img).attr('class');
								if ((typeof temp !== 'undefined') && (temp !== '')) {
									if (temp.match(reg_ignore)) {
										// **************************
										// *** 処理スイッチＯＦＦ ***
										// **************************
										exec = 0;
									} else {
										// ... [class]にサイズ指定あり？
										if (temp.match(reg_size)) {
											// ********************
											// *** サイズを取得 ***
											// ********************
											attr = ['height', 'width'];
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
								// ... 処理スイッチＯＮ？
								if (exec) {
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
									html += '<span class="icon-stack">';
									html += '<i class="'+base[j]+' icon-stack-base"></i>';
									html += '<i class="'+icon[j]+' icon-light"></i>';
									html += '</span>';
									html += '</p>';
									jQuery(targ).find('.'+over+'-wrap').append(html);
									// ******************************************
									// *** ホバー・エフェクトとアニメーション ***
									// ******************************************
									jQuery(targ).hover(function() {
										temp = jQuery(this);
										jQuery(temp).find('img')
											.removeClass(hvoff)
											.addClass(hvon);
										jQuery(temp).find('.'+over)
											.removeClass(afoff[j])
											.addClass(afon[j]);
									}, function() {
										temp = jQuery(this);
										jQuery(temp).find('.'+over)
											.removeClass(afon[j])
											.addClass(afoff[j]);
										jQuery(temp).find('img')
											.removeClass(hvon)
											.addClass(hvoff);
									});
								}
							}
						}
					});
				}
			});
		}
	});
	// 変数を破棄
	delete content, cont, element, elem, obj, temp, targ, img, attr, reg_ignore, reg_size;
});
