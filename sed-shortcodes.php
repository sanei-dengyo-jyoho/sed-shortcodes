<?php
/**
 * @package SED Shortcodes
 */
/*
Plugin Name: SED Shortcodes
Plugin URI: http://www.sanei-dengyo.com/
Description: Usefull Shortcodes For Any Corporation.
Author: Sanei Dengyo Co.,Ltd.
Author URI: http://www.sanei-dengyo.com/
Version: 1.0.2
*/


//******************************************************************************
//  バージョンの定義
//******************************************************************************
define( 'MY_PLUGIN_VERSION', '1.0.2' );


//******************************************************************************
//  Style Sheetの定義
//******************************************************************************
// Font Awesome
function regist_my_font_css() {
	// 管理画面では読み込まない
	if ( !is_admin() ) {
		wp_register_style( 'sed-font-awesome', plugins_url( 'css/font-awesome.min.css', __FILE__ ), array(), MY_PLUGIN_VERSION, 'all' );
		wp_enqueue_style( 'sed-font-awesome' );
	}
}

add_action( 'wp_print_styles', 'regist_my_font_css' );


// Shortcode
function regist_my_style_css() {
	// 管理画面では読み込まない
	if ( !is_admin() ) {
		wp_register_style( 'sed-shortcodes', plugins_url( 'css/style.min.css', __FILE__ ), array(), MY_PLUGIN_VERSION, 'all' );
		wp_enqueue_style( 'sed-shortcodes' );
	}
}

add_action( 'wp_print_styles', 'regist_my_style_css' );


//******************************************************************************
//  Java Scriptの定義
//******************************************************************************
// Shortcode
function regist_my_script_js() {
	if ( !is_admin() ) {
		$src = plugins_url( 'js/sed-shortcodes.min.js', __FILE__ );
		echo '<script type="text/javascript" src="'.$src.'"></script>';
	}
}

add_action( 'wp_head', 'regist_my_script_js' );


// Flat Shadow
function regist_my_flatshadow_js() {
	// 管理画面では読み込まない
	if ( !is_admin() ) {
		wp_register_script( 'flatshadow', plugins_url( 'js/jquery.flatshadow.min.js', __FILE__ ), array(), MY_PLUGIN_VERSION, 'all' );
		wp_enqueue_script( 'flatshadow' );
	}
}

add_action( 'wp_print_scripts', 'regist_my_flatshadow_js' );


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
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );


//******************************************************************************
//  抜粋でショートコードを使う
//******************************************************************************
add_filter( 'the_excerpt', 'shortcode_unautop' );
add_filter( 'the_excerpt', 'do_shortcode' );


//******************************************************************************
//  当社
//******************************************************************************
function fnc_my_company( $atts ) {
	$ret = $atts;
	$dic = array(
			'copyright' => 'Sanei Dengyo Co.,Ltd.',
			'name' => '三英電業株式会社',
			'url' => 'http://www.sanei-dengyo.com',
			);
	if ( isset( $dic[$atts]) ) {
    	$ret = $dic[$atts];
	} else {
    	$ret = $atts;
	}
	return $ret;
}


//******************************************************************************
//  当社のホームページへのリンク
//******************************************************************************
function sc_homepage_site_link( $atts ) {
	extract( shortcode_atts( array(
			'copyright' => 'true',
			'link' => 'false',
	), $atts ) );

	$ret = '';
	// Copyrightの表示
	if ( $copyright == 'true' ) {
		$ret .= 'Copyright &#169; ';
	}
	// HyperLinkの表示
	if ( $link == 'true' ) {
		$ret .= '<a class="site-link" href="';
		$ret .= fnc_my_company( 'url' );
		$ret .= '" title="';
		$ret .= fnc_my_company( 'name' );
		$ret .= '" target="_blank" rel="external nofollow">';
		$ret .= '</a>';
	}
	$ret .= fnc_my_company( 'copyright' );

	return $ret;
}

add_shortcode( 'homepage_site_link', 'sc_homepage_site_link' );


//******************************************************************************
//  編集画面だけに表示するコメント
//******************************************************************************
function sc_comment( $atts, $content = null ) {
	return '<div style="display: none;">'.do_shortcode( $content ).'</div>';
}

add_shortcode( 'comment', 'sc_comment' );


//******************************************************************************
//  @文字インデント
//******************************************************************************
function sc_indent( $atts ) {
	extract( shortcode_atts( array(
			'count' => 1,
	), $atts ) );
	return '<p style="text-indent: '.$count.'em;" />';
}

add_shortcode( 'indent', 'sc_indent' );


//******************************************************************************
//  改行
//******************************************************************************
function sc_line_break() {
	return '<br class="clear" />';
}

add_shortcode( 'line_break', 'sc_line_break' );


//******************************************************************************
// Blockquote
//******************************************************************************
function sc_blockquote( $atts, $content = null ) {
	$ret  = '';
	$ret .= '<blockquote>';
	$ret .= '<div>';
	$ret .= '<i class="fa fa-quote-left fa-2x pull-left color-white"></i>';
	$ret .= do_shortcode( $content );
	$ret .= '<i class="fa fa-quote-right fa-2x pull-right color-white"></i>';
	$ret .= '</div>';
	$ret .= '</blockquote>';
	return $ret;
}

add_shortcode( 'blockquote', 'sc_blockquote' );


//******************************************************************************
// Cite
//******************************************************************************
function sc_cite( $atts, $content = null ) {
	return '<cite>'.do_shortcode( $content ).'</cite>';
}

add_shortcode( 'cite', 'sc_cite' );


//******************************************************************************
//  HTML5 タグ
//******************************************************************************
function sc_tag_i( $atts ) {
	extract( shortcode_atts( array(
			'id' => '',
			'class' => '',
	), $atts ) );

	$ret  = '';
	$ret .= '<i';
	// idを追加
	if ( $id != '' ) {
		$ret .= ' id="'.$id.'"';
	}
	// classを追加
	if ( $class != '' ) {
		$ret .= ' class="'.$class.'"';
	}
	$ret .= '></i>';
	return $ret;
}

add_shortcode( 'tag_i', 'sc_tag_i' );


//******************************************************************************
//  Java Scripts Include
//******************************************************************************
// Java Scripts Source Link
function sc_jsInclude( $atts ) {
	extract( shortcode_atts( array(
			'js' => '',
			'delim' => '::::',
	), $atts ) );

	$ret = '';
	$str = $js;

	if ( $str != '' ) {
		if ( $delim != '' ) {
			// 区切り文字の有無？
			if ( strstr( $str, $delim ) ) {
				// 文字列中に区切り文字が存在する場合
				// 文字列を区切り文字を除いて分割する
				$array = explode( $delim, $str );
			} else {
				// 文字列をそのまま代入する
				$array = array( $str );
			}
			// タグを組み立てる
			$count = count( $array );
			for ( $i = 0; $i < $count; $i++ ) {
				$ret .= '<script type="text/javascript" src="'.$array[$i].'"></script>';
			}
		}
		return $ret;
	}
}

add_shortcode( 'jsInclude', 'sc_jsInclude' );


// Java Scripts Source Embed
function sc_jsScript( $atts, $content = null ) {
	extract( shortcode_atts( array(
	), $atts ) );

	$ret = '';
	$str = $content;

	if ( $str != '' ) {
		$ret .= "<script type='text/javascript'>"."\n";
		$str  = str_replace( "&#8216;", "'", $str );
		$str  = str_replace( "&#8217;", "'", $str );
		$str  = str_replace( "&#8242;", "'", $str );
		$str  = str_replace( "&gt;", ">", $str );
		$str  = str_replace( "&lt;", "<", $str );
		$str  = str_replace( "<br />", "", $str );
		$ret .= $str."\n";
		$ret .= "</script>"."\n";
		
		return $ret;
	}
}

add_shortcode( 'jsScript', 'sc_jsScript' );


//******************************************************************************
//  Color Code Convert
//******************************************************************************
function fnc_color_code( $color ) {
	$ret = $color;
	// 色名を色コードに対応させる辞書
	$dic = array(
			// Flat Shadow 標準色
			'color-grass' => '#1ABC9C',
			'color-forest' => '#2ecc71',
			'color-sky' => '#3498db',
			'color-violet' => '#9b59b6',
			'color-indigo' => '#34495e',
			'color-lemmon' => '#f1c40f',
			'color-gold' => '#e67e22',
			'color-blood' => '#e74c3c',
			// その他色
			'color-blue' => '#2daebf',
			'color-red' => '#e33100',
			'color-green' => '#1AC115',
			'color-magenta' => '#a9014b',
			'color-orange' => '#ff5c00',
			'color-yellow' => '#ffb515',
			'color-black' => '#222222',
			'color-gray' => '#808080',
			'color-darkgray' => '#a9a9a9',
			'color-white' => '#e1e1e1',
			'color-shadow' => '#dddddd',
			);
	if ( isset( $dic[$color]) ) {
    	$ret = $dic[$color];
	} else {
    	$ret = $color;
	}
	return $ret;
}


//******************************************************************************
//  Icon Font
//******************************************************************************
function sc_iconfont( $atts ) {
	extract( shortcode_atts( array(
			'name' => '',
			'class' => '',
			'color' => '',
			'li' => 'false',
			'tagname' => 'i',
			'size' => '',
			'basename' => '',
			'baseclass' => '',
			'basefront' => 'false',
			'stack_tagname' => 'span',
	), $atts ) );

	// li-classを追加
	$lidata = '';
	if ( $li == 'true' ) {
		$lidata = 'fa-li ';
	}
	// classを追加
	$classdata = '';
	if ( $class != '' ) {
		$classdata = ' '.$class;
	}
	// css styleを追加
	$styledata = '';
	if ( $color != '' ) {
		$styledata = ' style="color:'.fnc_color_code( $color ).';"';
	}
	// sizeのclassを追加
	$sizedata = '';
	if ( $size != '' ) {
		$sizedata = ' fa-'.$size;
	}

	$ret = '';

	if ( $basename == '' ) {
		// ... base無しの場合
		if ( $class == '' ) {
			$classdata = ' fa-fw';
		}
		$ret .= '<'.$tagname.' class="'.$lidata.'fa fa-'.$name.$classdata.$sizedata.'"'.$styledata.'>';
		$ret .= '</'.$tagname.'>';
	} else {
		// ... base有りの場合
		// baseのclassを追加
		$basedata = '';
		if ( $baseclass != '' ) {
			$basedata = ' '.$baseclass;
		}
		// stackのタグ
		if ( $size == '' ) {
			$sizedata = ' fa-lg';
		}
		$ret .= '<'.$stack_tagname.' class="fa-stack'.$sizedata.'">';

		$arr = array( '', '' );
		// base
		$arr[0] .= '<'.$tagname.' class="fa fa-'.$basename.' fa-stack-2x'.$basedata.'">';
		$arr[0] .= '</'.$tagname.'>';
		// icon
		$arr[1] .= '<'.$tagname.' class="fa fa-'.$name.$classdata.' fa-stack-1x"'.$styledata.'>';
		$arr[1] .= '</'.$tagname.'>';

		if ( $basefront == 'true' ) {
			// ... baseを前面に表示する場合
			$ret .= $arr[1].$arr[0];
		} else {
			// ... baseを背面に表示する場合（省略値）
			$ret .= $arr[0].$arr[1];
		}
		$ret .= '</'.$stack_tagname.'>';
	}
	return $ret;
}

add_shortcode( 'iconfont', 'sc_iconfont' );


//******************************************************************************
//  Flat Shadow
//******************************************************************************
// Flat Shadow Group
function sc_flatshadows( $atts, $content = null ) {
	extract( shortcode_atts( array(
			'class' => '',
			'id' => '',
			'color' => '',
			'angle' => '',
			'fade' => 'false',
			'boxshadow' => '',
			'style' => '',
			'tagname' => 'div',
	), $atts ) );

	// classを追加
	$dataclass = '';
	if ( $class != '' ) {
		$dataclass = $class.' ';
	}
	// idを追加
	$dataid = '';
	if ( $id != '' ) {
		$dataid = '-'.$id;
	}
	// css styleを追加
	$datastyle = '';
	if ( $style != '' ) {
		$datastyle = ' style="'.$style.'"';
	}

	$ret  = '';

	$ret .= '<'.$tagname.' class="'.$dataclass.'flat-shadows flat-icons'.$dataid.'"'.$datastyle.'>';
	$ret .= do_shortcode( $content );
	$ret .= '</'.$tagname.'>';
	// jQueryの追加
	$ret .= "\n";
	$ret .= "<script type='text/javascript'>"."\n";
	$ret .= "jQuery.noConflict();"."\n";
	$ret .= "jQuery(document).ready(function(){"."\n";
	$ret .= "jQuery('.flat-shadows.flat-icons".$dataid." .flat-icon').flatshadow({";

	// ... jQueryのオプションを追加
	$opt  = "";
	// colorの追加
	if ( $color != "" ) {
		if ( $opt != "" ) { $opt .= ", "; }
		$opt .= "color:'".fnc_color_code( $color )."'";
	}
	// angleの追加
	if ( $angle != "" ) {
		if ( $opt != "" ) { $opt .= ", "; }
		$opt .= "angle:'".$angle."'";
	}
	// fadeの追加
	if ( $fade != "" ) {
		if ( $opt != "" ) { $opt .= ", "; }
		$opt .= "fade:".$fade;
	}
	// box-shadowの追加
	if ( $boxshadow != "" ) {
		if ( $opt != "" ) { $opt .= ", "; }
		$opt .= "boxShadow:'".fnc_color_code( $boxshadow )."'";
	}

	$ret .= $opt."});"."\n";
	$ret .= "});"."\n";
	$ret .= "</script>"."\n";

	return $ret;
}

add_shortcode( 'flatshadows', 'sc_flatshadows' );


// Flat Shadow Icon
function sc_flatshadow( $atts, $content = null ) {
	extract( shortcode_atts( array(
			'color' => '',
			'angle' => '',
			'class' => '',
			'style' => '',
			'tagname' => 'div',
			'delim' => '::::',
	), $atts ) );

	// colorを追加
	$datacolor = '';
	if ( $color != '' ) {
		$datacolor = ' data-color="'.fnc_color_code( $color ).'"';
	}
	// angleを追加
	$dataangle = '';
	if ( $angle != '' ) {
		$dataangle = ' data-angle="'.$angle.'"';
	}
	// classを追加
	$dataclass = '';
	if ( $class != '' ) {
		$dataclass = ' '.$class;
	}
	// css styleを追加
	$datastyle = '';
	if ( $style != '' ) {
		$datastyle = ' style="'.$style.'"';
	}

	$ret = '';
	$str = $content;

	if ( $str != '' ) {
		if ( $delim != '' ) {
			// ... 区切り文字の有無？
			if (strstr( $str, $delim ) ) {
				// 文字列中に区切り文字が存在する場合
				// 文字列を区切り文字を除いて分割する
				$array = explode( $delim, $str );
			} else {
				// 文字列をそのまま代入する
				$array = array( $str );
			}
		} else {
			// 文字列を1文字づつ分解する
			$array = str_split( $str );
		}
		// タグを組み立てる
		$count = count( $array );
		for ( $i = 0; $i < $count; $i++ ) {
			$ret .= '<'.$tagname.$datacolor.$dataangle.' class="flat-icon'.$dataclass.'"'.$datastyle.'>';
			$ret .= do_shortcode( $array[$i] );
			$ret .= '</'.$tagname.'>';
		}
		return $ret;
	}
}

add_shortcode( 'flatshadow', 'sc_flatshadow' );


//******************************************************************************
//  URLのスクリーンショット
//******************************************************************************
function sc_snapshot( $atts ) {
	extract( shortcode_atts( array(
			'url' => '',
			'w' => 500,
			'caption' => '',
			'clear' => '',
	), $atts ) );

	$ret = '';

	if ( $url != '' ) {
		// URLのキャプチャ
		$src = en_sc_snapshot( $url, $w );

		if ( $src != '' ) {
			// classの追加
			$class = '';
			if ( $clear == 'true' ) {
				$class = ' clear';
			}
			// imgタグ
			$img = '<img src="'.$src.'" alt="'.$caption.'" />';
			// captionの追加
			$desc = '';
			if ( $caption != '' ) {
				$desc = '<div class="caption">'.$caption.'</div>';
			}

			$ret .= '<div class="snapshot'.$class.'" style="width:'.$w.'px">';
			$ret .= '<a href="'.$url.'">'.$img.'</a>';
			$ret .= $desc;
			$ret .= '</div>';
		}
	}
	return $ret;
}

function en_sc_snapshot( $url = '', $w = 500 ){
	return 'http://s.wordpress.com/mshots/v1/'.urlencode( clean_url( $url ) ).'?w='.$w;
}

add_shortcode( 'snapshot', 'sc_snapshot' );


//******************************************************************************
//  Note
//******************************************************************************
function fnc_note( $class, $icon, $color, $content = null ) {
	$ret  = '';
	$ret .= '<div class="note-wrap">';
	$ret .= '<div class="note-box '.$class.'">';
	$ret .= '<i class="fa fa-'.$icon.' fa-3x pull-left color-'.$color.' textshadow"></i> ';
	$ret .= do_shortcode( $content );
	$ret .= '</div>';
	$ret .= '</div>';
	return $ret;
}


// note
function sc_note( $atts, $content = null ) {
	return fnc_note( 'classic', 'book', 'white', $content );
}

add_shortcode( 'note', 'sc_note' );


// tip
function sc_tip( $atts, $content = null ) {
	return fnc_note( 'tip', 'lightbulb-o', 'blue', $content );
}

add_shortcode( 'tip', 'sc_tip' );


// important
function sc_important( $atts, $content = null ) {
	return fnc_note( 'important', 'lock', 'yellow', $content );
}

add_shortcode( 'important', 'sc_important' );


// warning
function sc_warning( $atts, $content = null ) {
	return fnc_note( 'warning', 'bullhorn', 'red', $content );
}

add_shortcode( 'warning', 'sc_warning' );


// help
function sc_help( $atts, $content = null ) {
	return fnc_note( 'help', 'medkit', 'green', $content );
}

add_shortcode( 'help', 'sc_help' );


//******************************************************************************
// Dropcap
//******************************************************************************
function sc_dropcap1( $atts, $content = null ) {
	return '<span class="dropcap1">'.$content.'</span>';
}

add_shortcode( 'dropcap1', 'sc_dropcap1' );


function sc_dropcap2( $atts, $content = null ) {
	return '<span class="dropcap2">'.$content.'</span>';
}

add_shortcode( 'dropcap2', 'sc_dropcap2' );


//******************************************************************************
// Highlight
//******************************************************************************
function fnc_highlight( $color, $content = null ) {
	$ret  = '';
	$ret .= '<span class="highlight '.$color.' textshadow">';
	$ret .= do_shortcode( $content );
	$ret .= '</span>';
	return $ret;
}


// blue
function sc_highlight_blue( $atts, $content = null ) {
	return fnc_highlight( 'blue', $content );
}

add_shortcode( 'highlight_blue', 'sc_highlight_blue' );


// red
function sc_highlight_red( $atts, $content = null ) {
	return fnc_highlight( 'red', $content );
}

add_shortcode( 'highlight_red', 'sc_highlight_red' );


// green
function sc_highlight_green( $atts, $content = null ) {
	return fnc_highlight( 'green', $content );
}

add_shortcode( 'highlight_green', 'sc_highlight_green' );


// yellow
function sc_highlight_yellow( $atts, $content = null ) {
	return fnc_highlight( 'yellow', $content );
}

add_shortcode( 'highlight_yellow', 'sc_highlight_yellow' );


// dark
function sc_highlight_dark( $atts, $content = null ) {
	return fnc_highlight( 'dark', $content );
}

add_shortcode( 'highlight_dark', 'sc_highlight_dark' );


// light
function sc_highlight_light( $atts, $content = null ) {
	return fnc_highlight( 'light', $content );
}

add_shortcode( 'highlight_light', 'sc_highlight_light' );


//******************************************************************************
//  アイキャッチ・アイコン
//******************************************************************************
function sc_catch_icon( $atts) {
	extract( shortcode_atts( array(
			'name' => '',
	), $atts ) );

	$ret  = '';
	$ret .= '<img class="img-short-icon" src="';
	$ret .= plugins_url( 'images/catch_icon/'.$name.'.png', __FILE__ );
	$ret .= '" />';
	return $ret;
}

add_shortcode( 'catch_icon', 'sc_catch_icon' );


//******************************************************************************
//  県名アイコン
//******************************************************************************
function sc_japan_icon( $atts ) {
	extract( shortcode_atts( array(
			'name' => '',
	), $atts ) );

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
	if ( isset( $dic[$name] ) ) {
    	$basename = $dic[$name];
	} else {
    	$basename = $name;
	}

	$ret  = '';
	$ret .= '<img class="img-short-icon" src="';
	$ret .= plugins_url( 'images/japan_icon/'.$basename.'.png', __FILE__ );
	$ret .= '" />';
	return $ret;
}

add_shortcode( 'japan_icon', 'sc_japan_icon' );


//******************************************************************************
// Gist Syntax Highlighter
//******************************************************************************
function gist_shortcode( $atts ) {
	return sprintf( '<script src="https://gist.github.com/%s.js%s"></script>', $atts['id'], $atts['file']?'?file='.$atts['file']:'' );
}

add_shortcode( 'gist', 'gist_shortcode' );


// [gist id="ID" file="FILE"]
function gist_shortcode_filter( $content ) {
	return preg_replace( '/https:\/\/gist.github.com\/([\d]+)[\.js\?]*[\#]*file[=|_]+([\w\.]+)(?![^<]*<\/a>)/i', '[gist id="${1}" file="${2}"]', $content );
}

add_filter( 'the_content', 'gist_shortcode_filter', 9 );
?>
