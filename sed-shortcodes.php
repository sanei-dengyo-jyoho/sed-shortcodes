<?php
/**
 * @package SED Shortcodes
 */
/*
Plugin Name: SED Shortcodes
Plugin URI: http://www.sanei-dengyo.com/
Description: 会社で使える便利なショートコードを集めました。
Author: 三英電業株式会社　情報システム
Author URI: http://www.sanei-dengyo.com/
Version: 1.0
*/


//******************************************************************************
//  バージョンの定義
//******************************************************************************
define('MY_PLUGIN_VERSION', '1.0');


//******************************************************************************
//  Style Sheetの定義
//******************************************************************************
// Font Awesome
function regist_my_font_css() {
	// 管理画面では読み込まない
	if ( !is_admin() ) {
		wp_register_style('font-awesome-css', plugins_url('css/font-awesome.min.css', __FILE__), array(), MY_PLUGIN_VERSION, 'all');
		wp_enqueue_style('font-awesome-css');
	}
}

add_action('wp_print_styles', 'regist_my_font_css');

function regist_my_font_ie7_css() {
	// 管理画面では読み込まない
	if ( !is_admin() ) {
		wp_register_style('font-awesome-ie7-css', plugins_url('css/font-awesome-ie7.min.css', __FILE__), array(), MY_PLUGIN_VERSION, 'all');
		wp_enqueue_style('font-awesome-ie7-css');
		global $wp_styles;
		$wp_styles->add_data( 'font-awesome-ie7-css', 'conditional', 'lte IE 7' );
	}
}

add_action('wp_print_styles', 'regist_my_font_ie7_css');


// Shortcode
function regist_my_style_css() {
	// 管理画面では読み込まない
	if ( !is_admin() ) {
		wp_register_style('sed-shortcode-css', plugins_url('css/sed-shortcode.min.css', __FILE__), array(), MY_PLUGIN_VERSION, 'all');
		wp_enqueue_style('sed-shortcode-css');
	}
}

add_action('wp_print_styles', 'regist_my_style_css');


//******************************************************************************
//  Java Scriptの定義
//******************************************************************************
function regist_my_script_js() {
	// 管理画面では読み込まない
	if ( !is_admin() ) {
		wp_register_script('flatshadow-js', plugins_url('js/jquery.flatshadow.min.js', __FILE__), array(), MY_PLUGIN_VERSION, 'all');
		wp_enqueue_script('flatshadow-js');
	}
}

add_action('wp_print_scripts', 'regist_my_script_js');


//******************************************************************************
//  flush関数を実行
//******************************************************************************
function my_force_flush() {
	// flush関数を実行（バッファを吐かせる）
	flush();
}
// admin_head（管理画面のヘッダ）（優先度 9999=最低）
add_action ( 'admin_head', 'my_force_flush', 9999 );
// wp_head（サイトのヘッダ）（優先度 9999=最低）
add_action ( 'wp_head', 'my_force_flush', 9999 );


//******************************************************************************
//  ウィジェットでショートコードを使う
//******************************************************************************
add_filter('widget_text', 'shortcode_unautop');
add_filter('widget_text', 'do_shortcode');


//******************************************************************************
//  抜粋でショートコードを使う
//******************************************************************************
add_filter('the_excerpt', 'shortcode_unautop');
add_filter('the_excerpt', 'do_shortcode');


//******************************************************************************
//  当社のホームページへのリンク
//******************************************************************************
function sc_homepage_site_link($atts) {
	extract(shortcode_atts(array(
			'copyright' => 'true',
			'link' => 'false',
	), $atts));
	// Copyrightの表示
	$copyright_text .= '';
	if ($copyright == 'true') {
		$copyright_text .= 'Copyright &#169; ';
	}
	// HyperLinkの表示
	$link_tag_pre .= '';
	$link_tag_suf .= '';
	if ($link == 'true') {
		$link_tag_pre .= '<a class="site-link" href="http://www.sanei-dengyo.com" title="三英電業株式会社" target="_blank" rel="external nofollow">';
		$link_tag_suf .= '</a>';
	}
	return $copyright_text.$link_tag_pre.'Sanei Dengyo Co.,Ltd.'.$link_tag_suf;
}

add_shortcode('homepage_site_link', 'sc_homepage_site_link');


//******************************************************************************
//  編集画面だけに表示するコメント
//******************************************************************************
function sc_comment($atts, $content = null) {
	return '<div style="display: none;">'.do_shortcode($content).'</div>';
}

add_shortcode('comment', 'sc_comment');


//******************************************************************************
//  @文字インデント
//******************************************************************************
function sc_indent($atts) {
	extract(shortcode_atts(array(
			'count' => 1,
	), $atts));
	return '<p style="text-indent: '.$count.'em;" />';
}

add_shortcode('indent', 'sc_indent');


//******************************************************************************
//  改行
//******************************************************************************
function sc_line_break() {
	return '<br class="clear" />';
}

add_shortcode('line_break', 'sc_line_break');


//******************************************************************************
// Blockquote
//******************************************************************************
function sc_blockquote($atts, $content = null) {
	return '<blockquote><div><i class="icon-quote-left icon-2x pull-left icon-muted"></i>'.do_shortcode($content).'<i class="icon-quote-right icon-2x pull-right icon-muted"></i></div></blockquote>';
}

add_shortcode('blockquote', 'sc_blockquote');


//******************************************************************************
// Cite
//******************************************************************************
function sc_cite($atts, $content = null) {
	return '<cite>'.do_shortcode($content).'</cite>';
}

add_shortcode('cite', 'sc_cite');


//******************************************************************************
//  Icon Font
//******************************************************************************
function sc_iconfont($atts) {
	extract(shortcode_atts(array(
			'name' => '',
			'class' => 'icon-fixed-width',
			'color' => '',
	), $atts));
	// 追加のclassの定義
	$classdata = '';
	if ($class != '') {
		$classdata = ' '.$class;
	}
	// css styleの定義
	$styledata = '';
	if ($color != '') {
		$styledata = ' style="color: '.$color.';';
	}
	return '<i class="icon-'.$name.$classdata.'"'.$styledata.'></i>';
}

add_shortcode('iconfont', 'sc_iconfont');


//******************************************************************************
//  Flat Shadow
//******************************************************************************
// Flat Shadow Group
function sc_flatshadows($atts, $content = null) {
	extract(shortcode_atts(array(
			'id' => '',
			'color' => '',
			'angle' => '',
			'fade' => 'false',
			'boxshadow' => '',
			'style' => '',
	), $atts));
	$rt = "";
	// styleの定義
	$datastyle = '';
	if ($style != '') {
		$datastyle = ' style="'.$style.'"';
	}
	$rt .= '<div class="flat-shadows flat-icons'.$id.'"'.$datastyle.'>'.do_shortcode($content).'</div>';
	// jQueryの定義
	$rt .= "\n";
	$rt .= "<script type='text/javascript'>"."\n";
	$rt .= "jQuery.noConflict();"."\n";
	$rt .= "jQuery(document).ready(function(){"."\n";
	$rt .= "jQuery('.flat-shadows.flat-icons".$id." .flat-icon').flatshadow({";

	$opt = "";
	if ($opt != "") { $opt .= ", "; }
	if ($color != "") { $opt .= "color: '".$color."'"; }

	if ($opt != "") { $opt .= ", "; }
	if ($angle != "") { $opt .= "angle: '".$angle."'"; }

	if ($opt != "") { $opt .= ", "; }
	if ($fade != "") { $opt .= "fade: ".$fade; }

	if ($opt != "") { $opt .= ", "; }
	if ($boxshadow != "") { $opt .= "boxShadow: '".$boxshadow."'"; }

	$rt .= $opt."});"."\n";
	$rt .= "});"."\n";
	$rt .= "</script>"."\n";

	return $rt;
}

add_shortcode('flatshadows', 'sc_flatshadows');


// Flat Shadow Icon
function sc_flatshadow($atts, $content = null) {
	extract(shortcode_atts(array(
			'color' => '',
			'angle' => '',
			'class' => '',
			'style' => '',
	), $atts));
	// colorの定義
	$datacolor = '';
	if ($color != '') {
		$datacolor = ' data-color="'.$color.'"';
	}
	// angleの定義
	$dataangle = '';
	if ($angle != '') {
		$dataangle = ' data-angle="'.$angle.'"';
	}
	// classの定義
	$dataclass = '';
	if ($class != '') {
		$dataclass = ' '.$class;
	}
	// styleの定義
	$datastyle = '';
	if ($style != '') {
		$datastyle = ' style="'.$style.'"';
	}
	return '<div'.$datacolor.$dataangle.' class="flat-icon'.$dataclass.'"'.$datastyle.'>'.do_shortcode($content).'</div>';
}

add_shortcode('flatshadow', 'sc_flatshadow');


// Flat Shadow Icon Text
function sc_flatshadow_text($atts, $content = null) {
	extract(shortcode_atts(array(
			'color' => '',
			'angle' => '',
			'class' => '',
			'style' => '',
			'tagname' => 'div',
	), $atts));
	// colorの定義
	$datacolor = '';
	if ($color != '') {
		$datacolor = ' data-color="'.$color.'"';
	}
	// angleの定義
	$dataangle = '';
	if ($angle != '') {
		$dataangle = ' data-angle="'.$angle.'"';
	}
	// classの定義
	$dataclass = '';
	if ($class != '') {
		$dataclass = ' '.$class;
	}
	// styleの定義
	$datastyle = '';
	if ($style != '') {
		$datastyle = ' style="'.$style.'"';
	}

	$ret = ''; 
	$str = do_shortcode($content);
	if ($str != '') {
		$array = str_split($str);
		$count = count($array);
		for ($i = 0; $i < $count; $i++) {
			$ret .= '<'.$tagname.' '.$datacolor.$dataangle.' class="flat-icon'.$dataclass.'"'.$datastyle.'>'.$array[$i].'</'.$tagname.'>';
		}
		return $ret;
	}
}

add_shortcode('flatshadow_text', 'sc_flatshadow_text');


//******************************************************************************
//  URLのスクリーンショット
//******************************************************************************
function sc_snapshot($atts) {
	extract(shortcode_atts(array(
			'url' => '',
			'w' => 500,
			'caption' => '',
			'clear' => '',
	), $atts));
	if ($url == '') {
		return '';
	} else {
		$div_width = $w * 1.01 + 2 + 16 + 2;
		$src = en_sc_snapshot($url, $w);
		if ($src == '') {
			return '';
		} else {
			$img = '<img src="'.$src.'" alt="'.$caption.'" />';
			if ($caption == '') {
				$cap = '';
			} else {
				$cap_width = $w - 10;
				$cap = '<span style="width: '.$cap_width.'px;">'.$caption.'</span>';
			}
			$class .= 'class="img-desc';
			if ($clear == 'true') { $class .= ' clear'; }
			$class .= '"';
			return '<div '.$class.' style="width: '.$div_width.'px;"><a href="'.$url.'" style="text-decoration: none;">'.$img.$cap.'</a></div>';
		}
	}
}

function en_sc_snapshot($url = '', $w = 500){
	return 'http://s.wordpress.com/mshots/v1/'.urlencode(clean_url($url)).'?w='.$w;
}

add_shortcode('snapshot', 'sc_snapshot');


//******************************************************************************
//  Java Scripts Include
//******************************************************************************
// Java Scripts Source Link
function sc_jsInclude($atts) {
	extract(shortcode_atts(array(
			'js' => '',
			'delim' => '::::',
	), $atts));
	$js_tag = '';
	if ($js != '') {
		$array = explode($delim, $js);
		$count = count($array);
		for ($i = 0; $i < $count; $i++) {
			$js_tag = $js_tag.'<script type="text/javascript" src="'.$array[$i].'"></script>';
		}
	}
	return $js_tag;
}

add_shortcode('jsInclude', 'sc_jsInclude');


// Java Scripts Source Embed
function sc_jsScript($atts, $content = null) {
	extract(shortcode_atts(array(
	), $atts));
	$rt = "";
	
	$rt .= "<script type='text/javascript'>"."\n";
	$content = str_replace("&#8216;","'",$content);
	$content = str_replace("&#8217;","'",$content);
	$content = str_replace("&#8242;","'",$content);
	$content = str_replace("&gt;",">",$content);
	$content = str_replace("&lt;","<",$content);
	$content = str_replace("<br />","",$content);
	$rt .= $content."\n";
	$rt .= "</script>"."\n";
	
	return $rt;
}

add_shortcode('jsScript', 'sc_jsScript');


//******************************************************************************
//  Note
//******************************************************************************
function sc_note($atts, $content = null) {
	return '<div class="note-wrap"><div class="note-box classic"><i class="icon-file-text icon-3x pull-left color-white textshadow"></i>'.do_shortcode($content).'</div></div>';
}

add_shortcode('note', 'sc_note');


function sc_tip($atts, $content = null) {
	return '<div class="note-wrap"><div class="note-box tip"><i class="icon-lightbulb icon-3x pull-left color-blue textshadow"></i>'.do_shortcode($content).'</div></div>';
}

add_shortcode('tip', 'sc_tip');


function sc_important($atts, $content = null) {
	return '<div class="note-wrap"><div class="note-box important"><i class="icon-key icon-3x pull-left color-yellow textshadow"></i>'.do_shortcode($content).'</div></div>';
}

add_shortcode('important', 'sc_important');


function sc_warning($atts, $content = null) {
	return '<div class="note-wrap"><div class="note-box warning"><i class="icon-warning-sign icon-3x pull-left color-red textshadow"></i>'.do_shortcode($content).'</div></div>';
}

add_shortcode('warning', 'sc_warning');


function sc_help($atts, $content = null) {
	return '<div class="note-wrap"><div class="note-box help"><i class="icon-question-sign icon-3x pull-left color-green textshadow"></i>'.do_shortcode($content).'</div></div>';
}

add_shortcode('help', 'sc_help');


//******************************************************************************
// Dropcap
//******************************************************************************
function sc_dropcap1($atts, $content = null) {
	return '<span class="dropcap1">'.$content.'</span>';
}

add_shortcode('dropcap1', 'sc_dropcap1');


function sc_dropcap2($atts, $content = null) {
	return '<span class="dropcap2">'.$content.'</span>';
}

add_shortcode('dropcap2', 'sc_dropcap2');


//******************************************************************************
// Highlight
//******************************************************************************
function sc_highlight_blue($atts, $content = null) {
	return '<span class="highlight blue textshadow">'.do_shortcode($content).'</span>';
}

add_shortcode('highlight_blue', 'sc_highlight_blue');


function sc_highlight_red($atts, $content = null) {
	return '<span class="highlight red textshadow">'.do_shortcode($content).'</span>';
}

add_shortcode('highlight_red', 'sc_highlight_red');


function sc_highlight_green($atts, $content = null) {
	return '<span class="highlight green textshadow">'.do_shortcode($content).'</span>';
}

add_shortcode('highlight_green', 'sc_highlight_green');


function sc_highlight_yellow($atts, $content = null) {
	return '<span class="highlight yellow textshadow">'.do_shortcode($content).'</span>';
}

add_shortcode('highlight_yellow', 'sc_highlight_yellow');


function sc_highlight_dark($atts, $content = null) {
	return '<span class="highlight dark textshadow">'.do_shortcode($content).'</span>';
}

add_shortcode('highlight_dark', 'sc_highlight_dark');


function sc_highlight_light($atts, $content = null) {
	return '<span class="highlight light textshadow">'.do_shortcode($content).'</span>';
}

add_shortcode('highlight_light', 'sc_highlight_light');


//******************************************************************************
//  アイキャッチ・アイコン
//******************************************************************************
function sc_catch_icon($atts) {
	extract(shortcode_atts(array(
			'name' => '',
	), $atts));
	return '<img class="img-short-icon" src="'.plugins_url('images/catch_icon/'.$name.'.png', __FILE__).'" />';
}

add_shortcode('catch_icon', 'sc_catch_icon');


//******************************************************************************
//  県名アイコン
//******************************************************************************
function sc_japan_icon($atts) {
	extract(shortcode_atts(array(
			'name' => '',
	), $atts));
	$basename = $name;
	// 県名をファイル名に対応させる辞書
	$dic = array(
			'北海道' => 'hokkai-do',
			'青森県' => 'aomori-ken',
			'岩手県' => 'iwate-ken',
			'宮城県' => 'miyagi-ken',
			'秋田県' => 'akita-ken',
			'山形県' => 'yamagata-ken',
			'福島県' => 'fukushima-ken',
			'茨城県' => 'ibaraki-ken',
			'栃木県' => 'tochigi-ken',
			'群馬県' => 'gunma-ken',
			'埼玉県' => 'saitama-ken',
			'千葉県' => 'chiba-ken',
			'東京都' => 'tokyo-to',
			'神奈川県' => 'kanagawa-ken',
			'新潟県' => 'niigata-ken',
			'富山県' => 'toyama-ken',
			'石川県' => 'ishikawa-ken',
			'福井県' => 'fukui-ken',
			'山梨県' => 'yamanashi-ken',
			'長野県' => 'nagano-ken',
			'岐阜県' => 'gofu-ken',
			'静岡県' => 'shizuoka-ken',
			'愛知県' => 'aichi-ken',
			'三重県' => 'mie-ken',
			'滋賀県' => 'shiga-ken',
			'京都府' => 'kyoto-fu',
			'大阪府' => 'osaka-fu',
			'兵庫県' => 'hyogo-ken',
			'奈良県' => 'nara-ken',
			'和歌山県' => 'wakayama-ken',
			'鳥取県' => 'tottori-ken',
			'島根県' => 'shimane-ken',
			'岡山県' => 'okayama-ken',
			'広島県' => 'hiroshima-ken',
			'山口県' => 'yamaguchi-ken',
			'徳島県' => 'tokushima-ken',
			'香川県' => 'kagawa-ken',
			'愛媛県' => 'ehime-ken',
			'高知県' => 'kochi-ken',
			'福岡県' => 'fukuoka-ken',
			'佐賀県' => 'saga-ken',
			'長崎県' => 'nagasaki-ken',
			'熊本県' => 'kumamoto-ken',
			'大分県' => 'oita-ken',
			'宮崎県' => 'miyazaki-ken',
			'鹿児島県' => 'kagoshima-ken',
			'沖縄県' => 'okinawa-ken',
			);
	if ( isset( $dic[$name]) ) {
    	$basename = $dic[$name];
	} else {
    	$basename = $name;
	}
	return '<img class="img-short-icon" src="'.plugins_url('images/japan_icon/'.$basename.'.png', __FILE__).'" />';
}

add_shortcode('japan_icon', 'sc_japan_icon');


//******************************************************************************
// Gist Syntax Highlighter
//******************************************************************************
function gist_shortcode($atts) {
	return sprintf(
		'<script src="https://gist.github.com/%s.js%s"></script>',$atts['id'],$atts['file']?'?file='.$atts['file']:''
	);
}

add_shortcode('gist','gist_shortcode');


// [gist id="ID" file="FILE"]
function gist_shortcode_filter($content) {
	return preg_replace('/https:\/\/gist.github.com\/([\d]+)[\.js\?]*[\#]*file[=|_]+([\w\.]+)(?![^<]*<\/a>)/i', '[gist id="${1}" file="${2}"]', $content );
}

add_filter( 'the_content', 'gist_shortcode_filter', 9);
?>
