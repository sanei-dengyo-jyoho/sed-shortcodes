jQuery.noConflict();
jQuery(document).ready(function() {
	//**************************************************************************
	// リンク先のあるイメージにアイコンを表示する
	//**************************************************************************
	// 親要素
	var content = ['#content', '.content'];
	var i, cont;
	// <a>要素
	var element = ['a:not([rel*="shadowbox"])', 'a[rel*="shadowbox"]'];
	var j, elem;
	// 正規表現
	var reg = new RegExp('img\-short\-icon|printicon', 'gi');
	// 一時変数
	var obj, temp, targ, img, style, ws, hs, html;
	// スイッチ
	var exec;
	// オーバーレイ
	var over = 'image-overlay';
	// アイコン
	var base = ['icon-circle', 'icon-circle'];
	var icon = ['icon-link', 'icon-zoom-in'];
	// アニメーション
	var afon = ['image-overlay-fromleft', 'image-overlay-fromtop'];
	var afoff = ['image-overlay-fadeout', 'image-overlay-fadeout'];

	// *** 親要素 ***
	jQuery.each(content, function(i, cont) {
		obj = jQuery.find(cont);
		if (obj[0]) {
			// *** <a>要素 ***
			jQuery.each(element, function(j, elem) {
				// ... a[href]の要素
				obj = jQuery(cont).find('a[href]'+elem);
				if (obj[0]) {
					// <a>要素ごとに対象オブジェクトを判定
					jQuery(obj).each(function() {
						// <a>DOM
						targ = jQuery(this);
						// 変数を初期化
						style = '';
						ws = '';
						hs = '';
						// 処理スイッチＯＮ
						exec = 1;
						// ... <img>あり？
						temp = jQuery(targ).find('img');
						if (temp[0]) {
							// <img>DOM
							img = temp[0];
							// ... <img>srcあり？
							temp = jQuery(img).attr('src');
							if ((typeof temp !== 'undefined') && (temp !== '')) {
								// ... <img>に正規表現のクラス名あり？
								// ... →処理スイッチＯＦＦ
								temp = jQuery(img).attr('class');
								if ((typeof temp !== 'undefined') && (temp !== '')) {
									if (temp.match(reg)) {
										exec = 0;
									}
								}
								// ... 処理スイッチＯＮ？
								if (exec) {
									// <img>幅を取得
									temp = jQuery(img).attr('width');
									if ((typeof temp !== 'undefined') && (temp !== '')) {
										ws = parseInt(temp, 10)+'px';
									}
									// <img>高さを取得
									temp = jQuery(img).attr('height');
									if ((typeof temp !== 'undefined') && (temp !== '')) {
										hs = parseInt(temp, 10)+'px';
									}
									// オーバーレイにサイズを追加
									if ((ws !== '') || (hs !== '')) {
										if (ws === '') {
											ws = '100%';
										}
										if (hs === '') {
											hs = '100%';
										}
										style += ' style = "';
										style += 'width:'+ws+'!important;';
										style += 'height:'+hs+'!important;';
										style += '"';
									}
									// <img>をオーバーレイで囲む
									html = '';
									html += '<div class="'+over+'-wrap"'+style+'>';
									jQuery(img).wrap(html);
									// アイコン・クラスを追加
									html = '';
									html += '<p class="'+over+'"'+style+'>';
									html += '<span class="icon-stack">';
									html += '<i class="'+base[j]+' icon-stack-base"></i>';
									html += '<i class="'+icon[j]+' icon-light"></i>';
									html += '</span>';
									html += '</p>';
									jQuery(targ).find('.'+over+'-wrap').append(html);
									// ホバー・エフェクトにアニメーション
									jQuery(targ).hover(function() {
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
						}
					});
				}
			});
		}
	});
	// 変数を破棄
	delete cont, elem, obj, temp, targ, img;
});
