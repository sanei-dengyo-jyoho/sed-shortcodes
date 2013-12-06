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
Version: 1.0.3
*/


require_once( 'includes/core-functions.php' );
require_once( 'includes/admin-functions.php' );


/********************************************************************************/
/* Style Sheet */
/********************************************************************************/
function sed_load_stylesheet_files() {
	if ( !is_admin() ) {
		wp_register_style( 'font-awesome', plugins_url( 'css/font-awesome.min.css', __FILE__ ) );
		wp_register_style( 'sed-shortcodes', plugins_url( 'css/style.min.css', __FILE__ ) );
		
		wp_enqueue_style( 'font-awesome' );
		wp_enqueue_style( 'sed-shortcodes' );
	}
}

add_action( 'wp_print_styles', 'sed_load_stylesheet_files' );


/********************************************************************************/
/* Java Script */
/********************************************************************************/
function sed_load_javascript_files() {
	if ( !is_admin() ) {
		wp_register_script( 'sed-flatshadow', plugins_url( 'js/jquery.flatshadow.min.js', __FILE__ ), array('jquery'), '', true );
		wp_register_script( 'sed-wikiup', plugins_url( 'js/wikiup.min.js', __FILE__ ), array('jquery'), '', true );
		wp_register_script( 'sed-shortcodes', plugins_url( 'js/sed-shortcodes.min.js', __FILE__ ), array('jquery'), '', true );
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'sed-flatshadow' );
		wp_enqueue_script( 'sed-wikiup' );
		wp_enqueue_script( 'sed-shortcodes' );
	}
}

add_action( 'wp_enqueue_scripts', 'sed_load_javascript_files' );


/********************************************************************************/
/* ウィジェットでショートコードを使う */
/********************************************************************************/
add_filter( 'widget_text', 'shortcode_unautop' );
add_filter( 'widget_text', 'do_shortcode' );


/********************************************************************************/
/* 抜粋でショートコードを使う */
/********************************************************************************/
add_filter( 'the_excerpt', 'shortcode_unautop' );
add_filter( 'the_excerpt', 'do_shortcode' );


/********************************************************************************/
/* 当社ホームページへのリンク */
/********************************************************************************/
function fnc_my_company( $atts ) {
	$ret = $atts;
	$dic = array(
			'copyright'	=>	'Sanei Dengyo Co.,Ltd.',
			'name'		=>	'三英電業株式会社',
			'url'		=>	'http://www.sanei-dengyo.com',
			);

	if ( isset( $dic[ $atts ]) ) {
    	$ret = $dic[ $atts ];
	} else {
    	$ret = $atts;
	}
	return $ret;
}

function sed_sc_homepage_site_link( $atts ) {
	extract(shortcode_atts(array(
			'copyright'	=>	'true',
			'link'		=>	'false',
	), $atts));

	$ret = '';
	if ( $copyright == 'true' ) {
		$ret .= 'Copyright &#169; ';
	}
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

add_shortcode( 'homepage_site_link', 'sed_sc_homepage_site_link' );


/********************************************************************************/
/* 編集画面だけに表示するコンテンツ */
/********************************************************************************/
function sed_sc_comment( $atts, $content = null ) {
	$content = do_shortcode( $content );
	return '<div style="display: none;">'.$content.'</div>';
}

add_shortcode( 'comment', 'sed_sc_comment' );


/********************************************************************************/
/* @文字インデント */
/********************************************************************************/
function sed_sc_indent( $atts ) {
	extract(shortcode_atts(array(
			'count'	=>	1,
	), $atts));

	return '<p style="text-indent: '.$count.'em;" />';
}

add_shortcode( 'indent', 'sed_sc_indent' );


/********************************************************************************/
/* 改行 */
/********************************************************************************/
function sed_sc_line_break() {
	return '<br class="clear" />';
}

add_shortcode( 'line_break', 'sed_sc_line_break' );


/********************************************************************************/
/* div タグ */
/********************************************************************************/
function sed_sc_tag_div( $atts ) {
	extract(shortcode_atts(array(
			'id'		=>	'',
			'class'		=>	'',
			'tagname'	=>	'div',
	), $atts));

	$ret  = '';
	$ret .= '<'.$tagname;
	if ( $id != '' ) {
		$ret .= ' id="'.$id.'"';
	}
	if ( $class != '' ) {
		$ret .= ' class="'.$class.'"';
	}
	$ret .= '></'.$tagname.'>';
	return $ret;
}

add_shortcode( 'tag_div', 'sed_sc_tag_div' );


/********************************************************************************/
/* a タグ */
/********************************************************************************/
function sed_sc_tag_a( $atts ) {
	extract(shortcode_atts(array(
			'type'		=>	'',
			'id'		=>	'',
			'class'		=>	'',
			'href'		=>	'',
	), $atts));

	$ret  = '';
	$ret .= '<a';
	if ( $type != '' ) {
		$ret .= ' type="'.$type.'"';
	}
	if ( $id != '' ) {
		$ret .= ' id="'.$id.'"';
	}
	if ( $class != '' ) {
		$ret .= ' class="'.$class.'"';
	}
	if ( $href != '' ) {
		$ret .= ' href="'.$href.'"';
	}
	$ret .= '></a>';
	return $ret;
}

add_shortcode( 'tag_a', 'sed_sc_tag_a' );


/********************************************************************************/
/* Java Scripts File Link */
/********************************************************************************/
function sed_sc_js_include( $atts ) {
	extract(shortcode_atts(array(
			'js'	=>	'',
			'delim'	=>	'::::',
	), $atts));

	$str = $js;
	$ret = '';

	if ( $str != '' ) {
		if ( $delim != '' ) {
			/* 区切り文字の有無？ */
			if ( strstr( $str, $delim ) ) {
				/* 文字列中に区切り文字が存在する場合 */
				/* 文字列を区切り文字を除いて分割する */
				$array = explode( $delim, $str );
			} else {
				/* 文字列をそのまま代入する */
				$array = array( $str );
			}
			/* タグを組み立てる */
			$count = count( $array );
			for ( $i = 0; $i < $count; $i++ ) {
				$ret .= '<script type="text/javascript" src="'.$array[ $i ].'"></script>';
			}
		}
	}
	return $ret;
}

add_shortcode( 'js_include', 'sed_sc_js_include' );


/********************************************************************************/
/* Java Scripts Source Embed */
/********************************************************************************/
function sed_sc_js_embed( $atts, $content = null ) {
	$str = $content;
	$ret = '';

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
	}
	return $ret;
}

add_shortcode( 'js_embed', 'sed_sc_js_embed' );


/********************************************************************************/
/* Color Code Convert */
/********************************************************************************/
function fnc_color_code( $color ) {
	$ret = $color;
	$dic = array(
			'color-blue'		=>	'#428bca',
			'color-red'			=>	'#d9534f',
			'color-green'		=>	'#5cb85c',
			'color-yellow'		=>	'#f0ad4e',
			'color-light'		=>	'#faf0e6',
			'color-dark'		=>	'#999999',
			'color-black'		=>	'#222222',
			'color-gray'		=>	'#808080',
			'color-darkgray'	=>	'#a9a9a9',
			'color-smoke'		=>	'#e6e6e6',
			'color-white'		=>	'#f5f5f5',
			'color-shadow'		=>	'#dddddd',
			// Flat Shadow
			'color-grass'		=>	'#1ABC9C',
			'color-forest'		=>	'#2ecc71',
			'color-sky'			=>	'#3498db',
			'color-violet'		=>	'#9b59b6',
			'color-indigo'		=>	'#34495e',
			'color-lemmon'		=>	'#f1c40f',
			'color-gold'		=>	'#e67e22',
			'color-blood'		=>	'#e74c3c',
			);
	if ( isset( $dic[ $color ]) ) {
    	$ret = $dic[ $color ];
	} else {
    	$ret = $color;
	}
	return $ret;
}


/********************************************************************************/
/* Blockquote */
/********************************************************************************/
function sed_sc_blockquote( $atts, $content = null ) {
	$content = do_shortcode( $content );
	$ret  = '';
	$ret .= '<blockquote>';
	$ret .= '<div>';
	$ret .= '<i class="fa fa-quote-left fa-2x pull-left color-shadow">';
	$ret .= '</i>';
	$ret .= $content;
	$ret .= '<i class="fa fa-quote-right fa-2x pull-right color-shadow">';
	$ret .= '</i>';
	$ret .= '</div>';
	$ret .= '</blockquote>';
	return $ret;
}

add_shortcode( 'blockquote', 'sed_sc_blockquote' );


/********************************************************************************/
/* cite */
/********************************************************************************/
function sed_sc_cite( $atts, $content = null ) {
	$content = do_shortcode( $content );
	return '<small><cite>'.$content.'</cite></small>';
}

add_shortcode( 'cite', 'sed_sc_cite' );


/********************************************************************************/
/* Font Awesome Icon Font */
/********************************************************************************/
function sed_sc_iconfont( $atts ) {
	extract(shortcode_atts(array(
			'name'			=>	'',
			'class'			=>	'',
			'color'			=>	'',
			'li'			=>	'false',
			'tagname'		=>	'i',
			'size'			=>	'',
			'basename'		=>	'',
			'baseclass'		=>	'',
			'basecolor'		=>	'',
			'basefront'		=>	'false',
			'stack_tagname'	=>	'span',
	), $atts));

	$data_li = '';
	if ( $li == 'true' ) {
		$data_li = 'fa-li ';
	}
	$classdata = '';
	if ( $class != '' ) {
		$data_class = ' '.$class;
	}
	$colordata = '';
	if ( $color != '' ) {
		$data_color = ' style="color:'.fnc_color_code( $color ).';"';
	}
	$data_size = '';
	if ( $size != '' ) {
		$data_size = ' fa-'.$size;
	}

	$ret = '';

	if ( $basename == '' ) {
		/* base無しの場合 */
		if ( $class == '' ) {
			if ( $data_li == '' ) {
				$data_class = ' fa-fw';
			}
		}
		$ret .= '<'.$tagname.' class="'.$data_li.'fa fa-'.$name.$data_class.$data_size.'"'.$data_color.'>';
		$ret .= '</'.$tagname.'>';
	} else {
		/* base有りの場合 */
		$data_base_class = '';
		if ( $baseclass != '' ) {
			$data_base_class = ' '.$baseclass;
		}
		$data_base_color = '';
		if ( $basecolor != '' ) {
			$data_base_color = ' style="color:'.fnc_color_code( $basecolor ).';"';
		}
		if ( $size == '' ) {
			$data_size = ' fa-lg';
		}
		$ret .= '<'.$stack_tagname.' class="fa-stack'.$data_size.'">';

		$arr = array( '', '' );
		/* base */
		$arr[0] .= '<'.$tagname.' class="fa fa-'.$basename.' fa-stack-2x'.$data_base_class.'"'.$data_base_color.'>';
		$arr[0] .= '</'.$tagname.'>';
		/* icon */
		$arr[1] .= '<'.$tagname.' class="fa fa-'.$name.$data_class.' fa-stack-1x"'.$data_color.'>';
		$arr[1] .= '</'.$tagname.'>';

		if ( $basefront == 'true' ) {
			/* baseを前面に表示する場合 */
			$ret .= $arr[ 1 ].$arr[ 0 ];
		} else {
			/* baseを背面に表示する場合（省略値） */
			$ret .= $arr[ 0 ].$arr[ 1 ];
		}
		$ret .= '</'.$stack_tagname.'>';
	}
	return $ret;
}

add_shortcode( 'iconfont', 'sed_sc_iconfont' );


/********************************************************************************/
/* Long Shadow */
/********************************************************************************/
/* Long Shadow Group */
function sed_sc_longshadows( $atts, $content = null ) {
	extract(shortcode_atts(array(
			'class'		=>	'',
			'number'	=>	'',
			'color'		=>	'',
			'angle'		=>	'',
			'fade'		=>	'false',
			'boxshadow'	=>	'',
			'style'		=>	'',
			'tagname'	=>	'div',
	), $atts));

	$data_class = '';
	if ( $class != '' ) {
		$data_class = $class.' ';
	}
	$data_number = '';
	if ( $number != '' ) {
		$data_number = $number;
	}
	$data_style = '';
	if ( $style != '' ) {
		$data_style = ' style="'.$style.'"';
	}

	$content = do_shortcode( $content );
	$ret  = '';

	/* タグの追加 */
	$ret .= '<'.$tagname.' class="'.$data_class.'longshadows-wrapper longshadows'.$data_number.'"'.$data_style.'>';
	$ret .= $content;
	$ret .= '</'.$tagname.'>';

	/* jQueryの追加 */
	$ret .= "\n";
	$ret .= "<script type='text/javascript'>"."\n";
	$ret .= "jQuery.noConflict();"."\n";
	$ret .= "jQuery(document).ready(function(){"."\n";
	$ret .= "jQuery('.longshadows-wrapper.longshadows".$data_number." .longshadow').flatshadow({";
	/* jQueryのオプションを追加 */
	$opt  = "";
	if ( $color != "" ) {
		if ( $opt != "" ) { $opt .= ", "; }
		$opt .= "color:'".fnc_color_code( $color )."'";
	}
	if ( $angle != "" ) {
		if ( $opt != "" ) { $opt .= ", "; }
		$opt .= "angle:'".$angle."'";
	}
	if ( $fade != "" ) {
		if ( $opt != "" ) { $opt .= ", "; }
		$opt .= "fade:".$fade;
	}
	if ( $boxshadow != "" ) {
		if ( $opt != "" ) { $opt .= ", "; }
		$opt .= "boxShadow:'".fnc_color_code( $boxshadow )."'";
	}
	$ret .= $opt."});"."\n";
	$ret .= "});"."\n";
	$ret .= "</script>"."\n";

	return $ret;
}

add_shortcode( 'longshadows', 'sed_sc_longshadows' );


/* Flat Shadow Icon */
function sed_sc_longshadow( $atts, $content = null ) {
	extract(shortcode_atts(array(
			'color'		=>	'',
			'angle'		=>	'',
			'class'		=>	'',
			'style'		=>	'',
			'tagname'	=>	'div',
			'delim'		=>	'::::',
	), $atts));

	$data_color = '';
	if ( $color != '' ) {
		$data_color = ' data-color="'.fnc_color_code( $color ).'"';
	}
	$data_angle = '';
	if ( $angle != '' ) {
		$data_angle = ' data-angle="'.$angle.'"';
	}
	$data_class = '';
	if ( $class != '' ) {
		$data_class = ' '.$class;
	}
	$data_style = '';
	if ( $style != '' ) {
		$data_style = ' style="'.$style.'"';
	}

	$str = $content;
	$ret = '';

	if ( $str != '' ) {
		if ( $delim != '' ) {
			/* 区切り文字の有無？ */
			if (strstr( $str, $delim ) ) {
				/* 文字列中に区切り文字が存在する場合 */
				/* 文字列を区切り文字を除いて分割する */
				$array = explode( $delim, $str );
			} else {
				/* 文字列をそのまま代入する */
				$array = array( $str );
			}
		} else {
			/* 文字列を1文字づつ分解する */
			$array = str_split( $str );
		}
		/* タグを組み立てる */
		$count = count( $array );
		for ( $i = 0; $i < $count; $i++ ) {
			$ret .= '<'.$tagname.$data_color.$data_angle.' class="longshadow'.$data_class.'"'.$data_style.'>';
			$content = do_shortcode( $array[ $i ] );
			$ret .= $content;
			$ret .= '</'.$tagname.'>';
		}
	}
	return $ret;
}

add_shortcode( 'longshadow', 'sed_sc_longshadow' );


/********************************************************************************/
/* Touch Me Information */
/********************************************************************************/
function sed_sc_touchme_info( $atts ) {
	extract(shortcode_atts(array(
			'vcard_img'	=>	'',
			'postal'	=>	'',
			'address'	=>	'',
			'user'		=>	'',
			'group'		=>	'',
			'mobile'	=>	'',
			'phone'		=>	'',
			'fax'		=>	'',
			'email'		=>	'',
			'url'		=>	'',
			'li'		=>	'true',
			'color'		=>	'',
	), $atts));

	$data_class = '';
	if ( $vcard_img != '' ) {
		$data_class = ' floatleft';
	}
	$data_li = '';
	if ( $li == 'true' ) {
		$data_li = ' li="true"';
	}
	$data_color = '';
	if ( $color != '' ) {
		$data_color = ' style="color:'.fnc_color_code( $color ).';"';
	}

	$map_icon = 'map-marker';
	$ret  = '';

	$ret .= '<ul class="fa-ul touchme-info'.$data_class.'">';
	if ( $postal != '' ) {
		$content = do_shortcode( $postal );
		$ret .= '<li>'.do_shortcode( '[iconfont name="'.$map_icon.'"'.$data_li.$data_color.']' ).$content.'</li>';
		$map_icon = 'dummy';
	}
	if ( $address != '' ) {
		$content = do_shortcode( $address );
		$ret .= '<li>'.do_shortcode( '[iconfont name="'.$map_icon.'"'.$data_li.$data_color.']' ).$content.'</li>';
	}
	if ( $user != '' ) {
		$content = do_shortcode( $user );
		$ret .= '<li>'.do_shortcode( '[iconfont name="user"'.$data_li.$data_color.']' ).$content.'</li>';
	}
	if ( $group != '' ) {
		$content = do_shortcode( $group );
		$ret .= '<li>'.do_shortcode( '[iconfont name="group"'.$data_li.$data_color.']' ).$content.'</li>';
	}
	if ( $mobile != '' ) {
		$content = do_shortcode( $mobile );
		$ret .= '<li>'.do_shortcode( '[iconfont name="mobile"'.$data_li.$data_color.']' ).$content.'</li>';
	}
	if ( $phone != '' ) {
		$content = do_shortcode( $phone );
		$ret .= '<li>'.do_shortcode( '[iconfont name="phone"'.$data_li.$data_color.']' ).$content;
		if ( $fax != '' ) {
			$content = do_shortcode( $fax );
			$ret .= ' '.do_shortcode( '[iconfont name="print"'.$data_color.']' ).$content;
		}
		$ret .= $suffix.'</li>';
	}
	if ( $email != '' ) {
		$content = do_shortcode( $email );
		$ret .= '<li>'.do_shortcode( '[iconfont name="envelope"'.$data_li.$data_color.']' ).$content.'</li>';
	}
	if ( $url != '' ) {
		$content = do_shortcode( $url );
		$ret .= '<li>'.do_shortcode( '[iconfont name="globe"'.$data_li.$data_color.']' ).$content.'</li>';
	}
	$ret .= '</ul>';

	if ( $vcard_img != '' ) {
		$tmp  = '';
		$tmp .= '<ul class="vcard-info">';
		$tmp .= '<li class="vcard-info-col'.$data_class.'"><img src="'.$vcard_img.'" /></li>';
		$ret  = $tmp.$ret;
		$ret .= '</ul>';
	}

	return $ret;
}
add_shortcode( 'touchme_info', 'sed_sc_touchme_info' );


/********************************************************************************/
/* Note */
/********************************************************************************/
function fnc_note( $class, $icon, $color, $content = null ) {
	$content = do_shortcode( $content );
	$ret  = '';
	$ret .= '<div class="note-box '.$class.'">';
	$ret .= '<i class="fa fa-'.$icon.' fa-3x pull-left textshadow '.$color.'">';
	$ret .= '</i>';
	$ret .= $content;
	$ret .= '</div>';
	return $ret;
}


function sed_sc_note_default( $atts, $content = null ) {
	return fnc_note( 'default', 'book', 'color-shadow', $content );
}

add_shortcode( 'note', 'sed_sc_note_default' );


function sed_sc_note_tip( $atts, $content = null ) {
	return fnc_note( 'tip', 'lightbulb-o', 'color-blue', $content );
}

add_shortcode( 'tip', 'sed_sc_note_tip' );


function sed_sc_note_important( $atts, $content = null ) {
	return fnc_note( 'important', 'lock', 'color-yellow', $content );
}

add_shortcode( 'important', 'sed_sc_note_important' );


function sed_sc_note_warning( $atts, $content = null ) {
	return fnc_note( 'warning', 'bullhorn', 'color-red', $content );
}

add_shortcode( 'warning', 'sed_sc_note_warning' );


function sed_sc_note_help( $atts, $content = null ) {
	return fnc_note( 'help', 'medkit', 'color-green', $content );
}

add_shortcode( 'help', 'sed_sc_note_help' );


/********************************************************************************/
/* Dropcap */
/********************************************************************************/
function sed_sc_dropcap1( $atts, $content = null ) {
	$content = do_shortcode( $content );
	return '<span class="dropcap">'.$content.'</span>';
}

add_shortcode( 'dropcap1', 'sed_sc_dropcap1' );


function sed_sc_dropcap2( $atts, $content = nul ) {
	$content = do_shortcode( $content );
	return '<span class="dropcap thick circle">'.$content.'</span>';
}

add_shortcode( 'dropcap2', 'sed_sc_dropcap2' );


/********************************************************************************/
/* Highlight */
/********************************************************************************/
function fnc_highlight( $color, $content = null ) {
	$content = do_shortcode( $content );
	$ret  = '';
	$ret .= '<span class="highlight '.$color.'">';
	$ret .= $content;
	$ret .= '</span>';
	return $ret;
}


function sed_sc_highlight_blue( $atts, $content = null ) {
	return fnc_highlight( 'blue', $content );
}

add_shortcode( 'highlight_blue', 'sed_sc_highlight_blue' );


function sed_sc_highlight_red( $atts, $content = null ) {
	return fnc_highlight( 'red', $content );
}

add_shortcode( 'highlight_red', 'sed_sc_highlight_red' );


function sed_sc_highlight_green( $atts, $content = null ) {
	return fnc_highlight( 'green', $content );
}

add_shortcode( 'highlight_green', 'sed_sc_highlight_green' );


function sed_sc_highlight_yellow( $atts, $content = null ) {
	return fnc_highlight( 'yellow', $content );
}

add_shortcode( 'highlight_yellow', 'sed_sc_highlight_yellow' );


function sed_sc_highlight_dark($atts, $content = null) {
	return fnc_highlight( 'dark', $content );
}

add_shortcode( 'highlight_dark', 'sed_sc_highlight_dark' );


function sed_sc_highlight_light($atts, $content = null) {
	return fnc_highlight( 'light', $content );
}

add_shortcode( 'highlight_light', 'sed_sc_highlight_light' );


/********************************************************************************/
/* wiki Tooltip */
/********************************************************************************/
function sed_sc_wikiup( $atts, $content = null ) {
	extract(shortcode_atts(array(
			'id'	=>	'',
			'style'	=>	'',
			'wiki'	=>	'',
			'lang'	=>	'ja',
	), $atts));

	$data_id = '';
	if ( $id != '' ) {
		$data_id = ' id="'.$id.'"';
	}
	$data_style = '';
	if ( $style != '' ) {
		$data_style = ' style="'.$style.'"';
	}

	$content = do_shortcode( $content );
	$ret  = '';
	$ret .= '<data';
	$ret .= $data_id;
	$ret .= $data_style;
	$ret .= ' data-wiki="'.$wiki.'"';
	$ret .= ' data-lang="'.$lang.'"> ';
	$ret .= $content;
	$ret .= ' </data>';
	return $ret;
}
add_shortcode( 'wikiup', 'sed_sc_wikiup' );


/********************************************************************************/
/* 都道府県アイコン */
/********************************************************************************/
function sed_sc_japan_icon( $atts ) {
	extract(shortcode_atts(array(
			'name'	=>	'',
			'ext'	=>	'png',
	), $atts));

	$base_name = $name;
	$dic = array(
			'北海道'	=>	'hokkai-do',
			'青森県'	=>	'aomori-ken',
			'岩手県'	=>	'iwate-ken',
			'宮城県'	=>	'miyagi-ken',
			'秋田県'	=>	'akita-ken',
			'山形県'	=>	'yamagata-ken',
			'福島県'	=>	'fukushima-ken',
			'茨城県'	=>	'ibaraki-ken',
			'栃木県'	=>	'tochigi-ken',
			'群馬県'	=>	'gunma-ken',
			'埼玉県'	=>	'saitama-ken',
			'千葉県'	=>	'chiba-ken',
			'東京都'	=>	'tokyo-to',
			'神奈川県'	=>	'kanagawa-ken',
			'新潟県'	=>	'niigata-ken',
			'富山県'	=>	'toyama-ken',
			'石川県'	=>	'ishikawa-ken',
			'福井県'	=>	'fukui-ken',
			'山梨県'	=>	'yamanashi-ken',
			'長野県'	=>	'nagano-ken',
			'岐阜県'	=>	'gofu-ken',
			'静岡県'	=>	'shizuoka-ken',
			'愛知県'	=>	'aichi-ken',
			'三重県'	=>	'mie-ken',
			'滋賀県'	=>	'shiga-ken',
			'京都府'	=>	'kyoto-fu',
			'大阪府'	=>	'osaka-fu',
			'兵庫県'	=>	'hyogo-ken',
			'奈良県'	=>	'nara-ken',
			'和歌山県'	=>	'wakayama-ken',
			'鳥取県'	=>	'tottori-ken',
			'島根県'	=>	'shimane-ken',
			'岡山県'	=>	'okayama-ken',
			'広島県'	=>	'hiroshima-ken',
			'山口県'	=>	'yamaguchi-ken',
			'徳島県'	=>	'tokushima-ken',
			'香川県'	=>	'kagawa-ken',
			'愛媛県'	=>	'ehime-ken',
			'高知県'	=>	'kochi-ken',
			'福岡県'	=>	'fukuoka-ken',
			'佐賀県'	=>	'saga-ken',
			'長崎県'	=>	'nagasaki-ken',
			'熊本県'	=>	'kumamoto-ken',
			'大分県'	=>	'oita-ken',
			'宮崎県'	=>	'miyazaki-ken',
			'鹿児島県'	=>	'kagoshima-ken',
			'沖縄県'	=>	'okinawa-ken',
			);

	if ( isset( $dic[ $name ] ) ) {
    	$base_name = $dic[ $name ];
	} else {
    	$base_name = $name;
	}

	$ret  = '';
	$ret .= '<img class="img-short-icon" src="';
	$ret .= plugins_url( 'images/japan_icon/'.$base_name.'.'.$ext, __FILE__ );
	$ret .= '" />';
	return $ret;
}

add_shortcode( 'japan_icon', 'sed_sc_japan_icon' );


/********************************************************************************/
/* URLのスクリーンショット */
/********************************************************************************/
function fnc_snapshot( $url = '', $w = 500 ){
	$ret  = '';
	if ( $url != '' ) {
		$ret .= 'http://s.wordpress.com/mshots/v1/';
		$ret .= urlencode(clean_url( $url ));
		$ret .= '?w=';
		$ret .= $w;
	}
	return $ret;
}

function sed_sc_snapshot( $atts ) {
	extract(shortcode_atts(array(
			'url'		=>	'',
			'w'			=>	500,
			'caption'	=>	'',
			'clear'		=>	'',
	), $atts));

	$ret = '';

	if ( $url != '' ) {
		/* キャプチャ */
		$src = fnc_snapshot( $url, $w );
		if ( $src != '' ) {
			$class = '';
			if ( $clear == 'true' ) {
				$class = ' clear';
			}
			$img = '<img src="'.$src.'" alt="'.$caption.'" />';
			$desc = '';
			if ( $caption != '' ) {
				$desc = '<span class="caption">'.$caption.'</span>';
			}

			$ret .= '<div class="snapshot'.$class.'" style="width:'.$w.'px">';
			$ret .= '<a href="'.$url.'">'.$img.'</a>';
			$ret .= $desc;
			$ret .= '</div>';
		}
	}
	return $ret;
}

add_shortcode( 'snapshot', 'sed_sc_snapshot' );


/********************************************************************************/
/* Gist Syntax Highlighter */
/********************************************************************************/
function sed_sc_gist( $atts ) {
	$ret = '';
	$link = '<script src="https://gist.github.com/%s.js%s"></script>';
	$ret .= sprintf( $link, $atts[ 'id' ], $atts[ 'file' ]?'?file='.$atts[ 'file' ]:'' );
	return $ret;
}

add_shortcode( 'gist', 'sed_sc_gist' );
