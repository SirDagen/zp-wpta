<?php

/*
 This is ZANAPRESS (ZP), an adapter to use WordPress themes (WPTA) to create small websites that don't need to be maintained 
 for years without posing major security risks.

 This file should remain the SAME over all domains/instances of use. 
 Please enhance it to include new themes/designs/layouts (tha you put in layout folder) while keeping backward compatibility. 
 A list of all integrated/compatible themes is below.
 
 (C) 2017 by ZANA
 

 -How to use:
 To use it on your website please modify "_conf.php" (root) and the "footer.php" of the template (layout folder).
 In footer.php you delete everything between <div class="site-info"> ... </div> and replace it with the following line:
 <?php echo $GLOBALS['zconf']['foot'][0]."  &nbsp; &ndash; &nbsp; ".$GLOBALS['zconf']['foot'][1]; ?>

 -How to add a theme:
 If you include a new theme and it doesnt work google the missing functions and include a hack for them
 https://www.google.com/search?q=wordpress+codex+wp_style_add_data

 -Themes that have already been tested successfully: 
 You will find in the respective file located in this directory.
*/


# ----- config -----
 
$GLOBALS['laufz_s']=microtime(true);
$GLOBALS['db_abfr']=0; 
include_once('./zp/_conf.php'); // config

$GLOBALS['zconf']['gen']='ZP 1.0.6 (WPTA)'; // ZANAPRESS - a WP theme adapter
$GLOBALS['conf']['url']=$_SERVER['SERVER_NAME'];
$GLOBALS['wp_version']='5.5'; // runs up to 
//$GLOBALS['jqueryfiles']=['jquery-1.12.4.min.js', 'jquery-migrate-1.4.1.min.js']; // located in /layout/scripts/ 
$GLOBALS['jqueryfiles']=['', '']; // if entries are empty, will get file from https://code.jquery.com/
define('HASSSL', false); // is SSL installed on your webspace? 
define('RUNSSSL', (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443)); // uses SSL? 

# ----- ini -----

define('DEBUG_OUTPUT', false); // ZP prints debug information  

if (empty($GLOBALS['zdata']['title'])) $GLOBALS['zdata']['title']=$GLOBALS['zdata']['h1'];
define('ABSPATH', true); // needed for theme: generatepress
	


# ----- main of this file -----

function out_page() {
    // include the files from the individual themes
	include_once('./layout'.$GLOBALS['zconf']['layout'].'/functions.php');
	include_once('./layout'.$GLOBALS['zconf']['layout'].'/header.php');
	include_once('./layout'.$GLOBALS['zconf']['layout'].'/page.php');
	include_once('./layout'.$GLOBALS['zconf']['layout'].'/footer.php');
 
    function _out_vars($t) {
        echo "-----".$t."-----:\n";
        print_r($GLOBALS[$t]);
    }

	if (DEBUG_OUTPUT) { // for error detection print out all WP actions, filters, styles
		$t='zaction'; _out_vars($t);
		$t='zfilter'; _out_vars($t);
		$t='zstyle'; _out_vars($t); 
	}
	
	out_stat();
}

function get_template_part($a, $b=false) {
	$t=$a; if (!empty($b)) $t.='-'.$b;
    include_once('./layout'.$GLOBALS['zconf']['layout']."/{$t}.php");
    // echo './layout'.$GLOBALS['zconf']['layout']."/{$t}.php"; // for error detection
}


# ----- ZP hacks to run the themes -----

$GLOBALS['zaction']=array();
function add_action($id, $fn) {
	if (!isset($GLOBALS['zaction'][$id])) $GLOBALS['zaction'][$id]=$fn;
}
$GLOBALS['zfilter']=array();
function add_filter($id, $fn) {
	$GLOBALS['zfilter'][$id]=$fn;
}
$GLOBALS['zstyle']=array();
function wp_style_add_data($id, $k0, $v0) { 
    $GLOBALS['zstyle'][$id]=[$k0, $v0]; // rtl - right-to-left language support
}





# ----- WP functions (from WP 4.8) -----
# ----- WP functions (from WP 4.8) -----
# ----- WP functions (from WP 4.8) -----


/*
Array
(
    [after_setup_theme] => graphy_setup
    [template_redirect] => graphy_content_width
    [widgets_init] => graphy_widgets_init
    [wp_enqueue_scripts] => graphy_scripts
    [wp_head] => graphy_customizer_css
    [add_meta_boxes] => graphy_add_meta_box
    [save_post] => graphy_save_meta_box_data
    [customize_register] => graphy_customize_register
    [customize_preview_init] => graphy_customize_preview_js
    [customize_controls_print_styles] => graphy_customizer_style
    [customize_controls_enqueue_scripts] => graphy_customizer_js
)
Array
(
    [body_class] => graphy_body_classes
    [user_contactmethods] => graphy_modify_user_contact_methods
    [excerpt_more] => graphy_change_excerpt_more
    [the_content_more_link] => graphy_modify_read_more_link
    [wp_page_menu_args] => graphy_page_menu_args
    [wp] => graphy_remove_related_posts
    [jetpack_relatedposts_filter_headline] => graphy_related_posts_headline
)
*/

function language_attributes() {
	if ($GLOBALS['zconf']['lang']=='de') echo 'lang="de-DE"';
    else echo 'lang="en-UK"';
}

function get_bloginfo($task) {
	switch($task) {
	case 'charset':
		return 'UTF-8';
	break;
	case 'description':
		return $GLOBALS['zconf']['subtitle'];
	break;
	case 'name':
		return $GLOBALS['zconf']['title'];
	break;	
    default:
        return '';
	break;
	}
}
function bloginfo($task) { 
    echo get_bloginfo($task); 
}


function mk_url($li, $forcefull=false) { // array('to'=>$t)
	if (!HASSSL) {
		if ($forcefull) return 'http://'.$GLOBALS['conf']['url'];
		else return '';
	}
	// $_SERVER['HTTP_HOST']
	if ($li{0}=='/') { $addsl=''; $li=substr($li,1); } else $addsl='/';
	if (($i=strpos($li, '?'))!==false) $lis=substr($li,0,$i); else $lis=$li; 
	if (false) {
		// needs SSL
		if (RUNSSSL) if (!$forcefull) return $addsl; // okay, is on HTTPS
		return 'https://'.$GLOBALS['conf']['url'].$addsl; // forward to HTTPS
	}
	// no SSL
	if (RUNSSSL or $forcefull) return 'http://'.$GLOBALS['conf']['url'].$addsl; // forward to HTTP
	return $addsl; // okay, in on HTTP
}

function esc_html_e($tx, $dom='') {
	switch($tx) {
    case 'Skip to content':
        if ($GLOBALS['zconf']['lang']=='de') echo 'Springe zum Inhalt';
        else echo $tx;
	break;
	case 'Menu':
		if ($GLOBALS['zconf']['lang']=='de') echo 'Menü';
        else echo $tx;
	break;
	default:
	break;
	}	
}

function get_theme_mod($k0, $v0=false) { return $v0; } // $v0=preset
function is_admin() { return false; }
function is_active_sidebar() { return false; }
function is_active_widget() { return false; }
function post_password_required() { return false; }
function is_attachment() { return false; }
function pings_open() { return false; }

function has_nav_menu() {
	return true;
}



function is_page() {
	return true;
}

function has_post_thumbnail() { return false; }

function the_post_thumbnail() { }
function get_the_post_thumbnail() { }
function get_header_image() {}

function get_header() { }
function get_footer() { }
function the_post() { }
function get_the_ID() { return str_replace('.php', '', basename($_SERVER['PHP_SELF'])); }
function the_ID() { echo get_the_ID(); }


function is_home() {
	$t=basename($_SERVER['PHP_SELF']);
	return ($t=='index.php');
}
function is_front_page() {
	$t=basename($_SERVER['PHP_SELF']);
	return ($t=='index.php');
}

 
function header_image() {
}

function get_custom_header() { // object
}

function get_sidebar() { 
}

function esc_url($rt) { return $rt; }

function home_url($url) { 
	switch($url) {
	case '/':
		echo '/';
	break;
	default:
	break;
	}	
}

function wp_kses(...$par) {  
//	print_r($par);
	return $par[0]; 
}


function _x($tx) { return $tx; }
function get_option() { return false; }


$GLOBALS['zconf']['postnum']=1;
function have_posts() {
	$rt=($GLOBALS['zconf']['postnum']>0);
	$GLOBALS['zconf']['postnum']--;
	return $rt;
}

function get_the_title($r0='', $r1='') { return $r0.$GLOBALS['zdata']['h1'].$r1; } 
function the_title($r0='', $r1='') { echo get_the_title($r0, $r1); } 
function the_content() { echo $GLOBALS['zdata']['body']; } 
function wp_link_pages() { } 
function esc_html__() { } 
function get_comments_number() { } 


// $GLOBALS['zdata']['title']

function get_post_meta() { return false; }





function apply_filters($a, $b) { return array(); } // return $b;
function get_template_directory() { 
	return get_template_directory_uri(); // == ./layout'.$GLOBALS['zconf']['layout'].'';
}
function get_stylesheet_directory_uri() { 
	return get_template_directory_uri(); 
}
function get_stylesheet_uri() { 
	return get_template_directory_uri().'/style.css'; 
}
function get_template_directory_uri() { 
	return './layout'.$GLOBALS['zconf']['layout'];
}
function is_singular() { return true; }
function comments_open() { return false; } 


$GLOBALS['iecss']=array();
class WP_Styles {
	public $nil = '';
	function add_data(...$par) {  
		if ($par[2]=='IE') $GLOBALS['iecss'][]=$par[0];
	} 
}

$wp_styles = new WP_Styles();


class WP_Widget {
}

function _wp_specialchars( $string, $quote_style = ENT_NOQUOTES, $charset = false, $double_encode = false ) {
    $string = (string) $string;
 
    if ( 0 === strlen( $string ) )
        return '';
 
    // Don't bother if there are no specialchars - saves some processing
    if ( ! preg_match( '/[&<>"\']/', $string ) )
        return $string;
 
    // Account for the previous behaviour of the function when the $quote_style is not an accepted value
    if ( empty( $quote_style ) )
        $quote_style = ENT_NOQUOTES;
    elseif ( ! in_array( $quote_style, array( 0, 2, 3, 'single', 'double' ), true ) )
        $quote_style = ENT_QUOTES;
 
    // Store the site charset as a static to avoid multiple calls to wp_load_alloptions()
    if ( ! $charset ) {
        static $_charset = null;
        if ( ! isset( $_charset ) ) {
            $alloptions = wp_load_alloptions();
            $_charset = isset( $alloptions['blog_charset'] ) ? $alloptions['blog_charset'] : '';
        }
        $charset = $_charset;
    }
 
    if ( in_array( $charset, array( 'utf8', 'utf-8', 'UTF8' ) ) )
        $charset = 'UTF-8';
 
    $_quote_style = $quote_style;
 
    if ( $quote_style === 'double' ) {
        $quote_style = ENT_COMPAT;
        $_quote_style = ENT_COMPAT;
    } elseif ( $quote_style === 'single' ) {
        $quote_style = ENT_NOQUOTES;
    }
 
    if ( ! $double_encode ) {
        // Guarantee every &entity; is valid, convert &garbage; into &amp;garbage;
        // This is required for PHP < 5.4.0 because ENT_HTML401 flag is unavailable.
        $string = wp_kses_normalize_entities( $string );
    }
 
    $string = @htmlspecialchars( $string, $quote_style, $charset, $double_encode );
 
    // Back-compat.
    if ( 'single' === $_quote_style )
        $string = str_replace( "'", '&#039;', $string );
 
    return $string;
}

function add_query_arg() {
    $args = func_get_args();
    if ( is_array( $args[0] ) ) {
        if ( count( $args ) < 2 || false === $args[1] )
            $uri = $_SERVER['REQUEST_URI'];
        else
            $uri = $args[1];
    } else {
        if ( count( $args ) < 3 || false === $args[2] )
            $uri = $_SERVER['REQUEST_URI'];
        else
            $uri = $args[2];
    }
 
    if ( $frag = strstr( $uri, '#' ) )
        $uri = substr( $uri, 0, -strlen( $frag ) );
    else
        $frag = '';
 
    if ( 0 === stripos( $uri, 'http://' ) ) {
        $protocol = 'http://';
        $uri = substr( $uri, 7 );
    } elseif ( 0 === stripos( $uri, 'https://' ) ) {
        $protocol = 'https://';
        $uri = substr( $uri, 8 );
    } else {
        $protocol = '';
    }
 
    if ( strpos( $uri, '?' ) !== false ) {
        list( $base, $query ) = explode( '?', $uri, 2 );
        $base .= '?';
    } elseif ( $protocol || strpos( $uri, '=' ) === false ) {
        $base = $uri . '?';
        $query = '';
    } else {
        $base = '';
        $query = $uri;
    }
 
    wp_parse_str( $query, $qs );
    $qs = urlencode_deep( $qs ); // this re-URL-encodes things that were already in the query string
    if ( is_array( $args[0] ) ) {
        foreach ( $args[0] as $k => $v ) {
            $qs[ $k ] = $v;
        }
    } else {
        $qs[ $args[0] ] = $args[1];
    }
 
    foreach ( $qs as $k => $v ) {
        if ( $v === false )
            unset( $qs[$k] );
    }
 
    $ret = build_query( $qs );
    $ret = trim( $ret, '?' );
    $ret = preg_replace( '#=(&|$)#', '$1', $ret );
    $ret = $protocol . $base . $ret . $frag;
    $ret = rtrim( $ret, '?' );
    return $ret;
}

function wp_parse_str( $string, &$array ) {
    parse_str( $string, $array );
    if ( get_magic_quotes_gpc() )
        $array = stripslashes_deep( $array );
    /**
     * Filters the array of variables derived from a parsed string.
     *
     * @since 2.3.0
     *
     * @param array $array The array populated with variables.
     */
    return $array;
}

function build_query( $data ) {
    return _http_build_query( $data, null, '&', '', false );
} 

function _http_build_query( $data, $prefix = null, $sep = null, $key = '', $urlencode = true ) {
    $ret = array();
 
    foreach ( (array) $data as $k => $v ) {
        if ( $urlencode)
            $k = urlencode($k);
        if ( is_int($k) && $prefix != null )
            $k = $prefix.$k;
        if ( !empty($key) )
            $k = $key . '%5B' . $k . '%5D';
        if ( $v === null )
            continue;
        elseif ( $v === false )
            $v = '0';
 
        if ( is_array($v) || is_object($v) )
            array_push($ret,_http_build_query($v, '', $sep, $k, $urlencode));
        elseif ( $urlencode )
            array_push($ret, $k.'='.urlencode($v));
        else
            array_push($ret, $k.'='.$v);
    }
 
    if ( null === $sep )
        $sep = ini_get('arg_separator.output');
 
    return implode($sep, $ret);
}

function urlencode_deep($rt) { 
	foreach ($rt as $k0=>$v0) $rt[$k0]=urlencode($v0);
	return $rt; 
}

function esc_html_x( $text, $context, $domain = 'default' ) {
    return esc_html( $text );
}

function esc_html( $text ) {
	$safe_text = _wp_specialchars( $text, ENT_QUOTES );
	return $safe_text;
}




# ----- WP classes (from WP 5.5) -----
# ----- WP classes (from WP 5.5) -----
# ----- WP classes (from WP 5.5) -----

final class WP_Theme implements ArrayAccess {
 
    /**
     * Whether the theme has been marked as updateable.
     *
     * @since 4.4.0
     * @var bool
     *
     * @see WP_MS_Themes_List_Table
     */
    public $update = false;
 
    /**
     * Headers for style.css files.
     *
     * @since 3.4.0
     * @since 5.4.0 Added `Requires at least` and `Requires PHP` headers.
     * @var array
     */
    private static $file_headers = array(
        'Name'        => 'Theme Name',
        'ThemeURI'    => 'Theme URI',
        'Description' => 'Description',
        'Author'      => 'Author',
        'AuthorURI'   => 'Author URI',
        'Version'     => 'Version',
        'Template'    => 'Template',
        'Status'      => 'Status',
        'Tags'        => 'Tags',
        'TextDomain'  => 'Text Domain',
        'DomainPath'  => 'Domain Path',
        'RequiresWP'  => 'Requires at least',
        'RequiresPHP' => 'Requires PHP',
    );
 
    /**
     * Default themes.
     *
     * @var array
     */
    private static $default_themes = array(
        'classic'         => 'WordPress Classic',
        'default'         => 'WordPress Default',
        'twentyten'       => 'Twenty Ten',
        'twentyeleven'    => 'Twenty Eleven',
        'twentytwelve'    => 'Twenty Twelve',
        'twentythirteen'  => 'Twenty Thirteen',
        'twentyfourteen'  => 'Twenty Fourteen',
        'twentyfifteen'   => 'Twenty Fifteen',
        'twentysixteen'   => 'Twenty Sixteen',
        'twentyseventeen' => 'Twenty Seventeen',
        'twentynineteen'  => 'Twenty Nineteen',
        'twentytwenty'    => 'Twenty Twenty',
    );
 
    /**
     * Renamed theme tags.
     *
     * @var array
     */
    private static $tag_map = array(
        'fixed-width'    => 'fixed-layout',
        'flexible-width' => 'fluid-layout',
    );
 
    /**
     * Absolute path to the theme root, usually wp-content/themes
     *
     * @var string
     */
    private $theme_root;
 
    /**
     * Header data from the theme's style.css file.
     *
     * @var array
     */
    private $headers = array();
 
    /**
     * Header data from the theme's style.css file after being sanitized.
     *
     * @var array
     */
    private $headers_sanitized;
 
    /**
     * Header name from the theme's style.css after being translated.
     *
     * Cached due to sorting functions running over the translated name.
     *
     * @var string
     */
    private $name_translated;
 
    /**
     * Errors encountered when initializing the theme.
     *
     * @var WP_Error
     */
    private $errors;
 
    /**
     * The directory name of the theme's files, inside the theme root.
     *
     * In the case of a child theme, this is directory name of the child theme.
     * Otherwise, 'stylesheet' is the same as 'template'.
     *
     * @var string
     */
    private $stylesheet;
 
    /**
     * The directory name of the theme's files, inside the theme root.
     *
     * In the case of a child theme, this is the directory name of the parent theme.
     * Otherwise, 'template' is the same as 'stylesheet'.
     *
     * @var string
     */
    private $template;
 
    /**
     * A reference to the parent theme, in the case of a child theme.
     *
     * @var WP_Theme
     */
    private $parent;
 
    /**
     * URL to the theme root, usually an absolute URL to wp-content/themes
     *
     * @var string
     */
    private $theme_root_uri;
 
    /**
     * Flag for whether the theme's textdomain is loaded.
     *
     * @var bool
     */
    private $textdomain_loaded;
 
    /**
     * Stores an md5 hash of the theme root, to function as the cache key.
     *
     * @var string
     */
    private $cache_hash;
 
    /**
     * Flag for whether the themes cache bucket should be persistently cached.
     *
     * Default is false. Can be set with the {@see 'wp_cache_themes_persistently'} filter.
     *
     * @var bool
     */
    private static $persistently_cache;
 
    /**
     * Expiration time for the themes cache bucket.
     *
     * By default the bucket is not cached, so this value is useless.
     *
     * @var bool
     */
    private static $cache_expiration = 1800;



    /**
     * Method to implement ArrayAccess for keys formerly returned by get_themes()
     *
     * @since 3.4.0
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet( $offset, $value ) {}
 
    /**
     * Method to implement ArrayAccess for keys formerly returned by get_themes()
     *
     * @since 3.4.0
     *
     * @param mixed $offset
     */
    public function offsetUnset( $offset ) {}
 
    /**
     * Method to implement ArrayAccess for keys formerly returned by get_themes()
     *
     * @since 3.4.0
     *
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists( $offset ) {
        static $keys = array(
            'Name',
            'Version',
            'Status',
            'Title',
            'Author',
            'Author Name',
            'Author URI',
            'Description',
            'Template',
            'Stylesheet',
            'Template Files',
            'Stylesheet Files',
            'Template Dir',
            'Stylesheet Dir',
            'Screenshot',
            'Tags',
            'Theme Root',
            'Theme Root URI',
            'Parent Theme',
        );
 
        return in_array( $offset, $keys, true );
    }
 

    /**
     * Get a raw, unformatted theme header.
     *
     * The header is sanitized, but is not translated, and is not marked up for display.
     * To get a theme header for display, use the display() method.
     *
     * Use the get_template() method, not the 'Template' header, for finding the template.
     * The 'Template' header is only good for what was written in the style.css, while
     * get_template() takes into account where WordPress actually located the theme and
     * whether it is actually valid.
     *
     * @since 3.4.0
     *
     * @param string $header Theme header. Name, Description, Author, Version, ThemeURI, AuthorURI, Status, Tags.
     * @return string|array|false String or array (for Tags header) on success, false on failure.
     */
    public function get( $header ) {
        if ( ! isset( $this->headers[ $header ] ) ) {
            return false;
        }
 
        if ( ! isset( $this->headers_sanitized ) ) {
            $this->headers_sanitized = $this->cache_get( 'headers' );
            if ( ! is_array( $this->headers_sanitized ) ) {
                $this->headers_sanitized = array();
            }
        }
 
        if ( isset( $this->headers_sanitized[ $header ] ) ) {
            return $this->headers_sanitized[ $header ];
        }
 
        // If themes are a persistent group, sanitize everything and cache it. One cache add is better than many cache sets.
        if ( self::$persistently_cache ) {
            foreach ( array_keys( $this->headers ) as $_header ) {
                $this->headers_sanitized[ $_header ] = $this->sanitize_header( $_header, $this->headers[ $_header ] );
            }
            $this->cache_add( 'headers', $this->headers_sanitized );
        } else {
            $this->headers_sanitized[ $header ] = $this->sanitize_header( $header, $this->headers[ $header ] );
        }
 
        return $this->headers_sanitized[ $header ];
    }
 

        
    public function offsetGet( $offset ) {
        switch ( $offset ) {
            case 'Name':
            case 'Title':
                /*
                    * See note above about using translated data. get() is not ideal.
                    * It is only for backward compatibility. Use display().
                    */
                return $this->get( 'Name' );
            case 'Author':
                return $this->display( 'Author' );
            case 'Author Name':
                return $this->display( 'Author', false );
            case 'Author URI':
                return $this->display( 'AuthorURI' );
            case 'Description':
                return $this->display( 'Description' );
            case 'Version':
            case 'Status':
                return $this->get( $offset );
            case 'Template':
                return $this->get_template();
            case 'Stylesheet':
                return $this->get_stylesheet();
            case 'Template Files':
                return $this->get_files( 'php', 1, true );
            case 'Stylesheet Files':
                return $this->get_files( 'css', 0, false );
            case 'Template Dir':
                return $this->get_template_directory();
            case 'Stylesheet Dir':
                return $this->get_stylesheet_directory();
            case 'Screenshot':
                return $this->get_screenshot( 'relative' );
            case 'Tags':
                return $this->get( 'Tags' );
            case 'Theme Root':
                return $this->get_theme_root();
            case 'Theme Root URI':
                return $this->get_theme_root_uri();
            case 'Parent Theme':
                return $this->parent() ? $this->parent()->get( 'Name' ) : '';
            default:
                return null;
        }
    }



}


# ----- new functions for indivduall themes -----
# ----- new functions for indivduall themes -----
# ----- new functions for indivduall themes -----

// name of theme: the function that is needs to run within here

/*syntax begin*/
// https://wordpress.org/themes/syntax/
function wp_title() { echo $GLOBALS['zdata']['title']; }
function wp_attachment_is_image() {}
function do_action() {}
function esc_attr($tx) { echo $tx; }
function _e($tx) { echo $tx; }
function esc_attr_e($rt) { return ($rt); }
function edit_post_link() {}
function comments_template() {}
/*syntax end*/


/*independent-publisher-2 begin*/
// https://wordpress.org/themes/independent-publisher/
function the_custom_logo() {}
function get_avatar_url() {}
function is_customize_preview() {}
function is_sticky() {}
function get_edit_post_link() {}
/*independent-publisher-2 end*/


/*generatepress begin*/
function wp_parse_args($a, $b) { return $b; } 
function is_rtl() {}
function absint() {}
function is_single() {}
function wp_add_inline_style() {}
function is_search() {}
/*generatepress end*/


// https://wordpress.org/themes/minnow/


/*seedlet begin*/
// https://wordpress.org/themes/seedlet/ 
function has_custom_logo() {}
function wp_body_open() {}
// function seedlet_get_icon_svg() {}
function get_nav_menu_locations() {}
function wp_get_nav_menu_object() {}
function esc_url_raw( $url, $protocols = null ) {
    return esc_url( $url, $protocols, 'db' );
}
function wp_get_theme( $stylesheet = '', $theme_root = '' ) {
    if ( empty( $stylesheet ) ) {
        $stylesheet = get_stylesheet();
    }
    return new WP_Theme( $stylesheet, $theme_root );
}
function get_stylesheet() {
    return apply_filters( 'stylesheet', get_option( 'stylesheet' ) );
}
/*seedlet end*/




# ----- HTLM related stuff -----
# ----- HTLM related stuff -----
# ----- HTLM related stuff -----


function wp_head() { 
	global $noindex;
	//echo '<title>'.$GLOBALS['zdata']['title'].'</title>'.chr(10); // not needed
	if ($noindex) echo '<meta name="robots" content="noindex,nofollow,noodp" />'.chr(10);
	else echo '<meta name="robots" content="index,follow,noodp" />'.chr(10);

	$t='wp_enqueue_scripts'; if (isset($GLOBALS['zaction'][$t])) { call_user_func($GLOBALS['zaction'][$t]); }
	$t='wp_head'; if (isset($GLOBALS['zaction'][$t])) call_user_func($GLOBALS['zaction'][$t]);
	echo '<meta name="generator" content="'.$GLOBALS['zconf']['gen'].'" />'.chr(10);
	$tc=str_replace('/index.php', '/', $_SERVER['PHP_SELF']); $tc=mk_url($tc, true).$tc; // $GLOBALS['conf']['protoc'].$GLOBALS['conf']['url'].str_replace('/index.php','/',$_SERVER['PHP_SELF']);
	echo '<link rel="canonical" href="'.$tc.'" />'.chr(10);
	echo $GLOBALS['zconf']['favicon'].'<link rel="stylesheet" id="zcss" href="./layout/super.css?ver='.date('ymd', @filemtime('./layout/super.css')).'" type="text/css" media="all">'.chr(10);
	$i=@filemtime(get_template_directory_uri().'/zana.css'); 
	if (!empty($i)) echo '<link rel="stylesheet" id="zmods" href="'.get_template_directory_uri().'/zana.css?ver='.date('ymd', $i).'" type="text/css" media="all">'.chr(10);
}

function wp_footer() { 
	echo $GLOBALS['zfoot'];
}

function body_class() {
	echo 'class="home page-template-default page page-id-X no-sidebar footer-0 has-avatars"'; 
}

function wp_nav_menu($op) {
	switch($op['theme_location']) { // 'menu_id' => 'primary-menu'
	case 'primary':
    case 'menu-1':
        if ($GLOBALS['zconf']['menutype']==0) {
            echo '						<div class="menu-std-container"><ul id="menu-std" class="menu">'; // <ul id="menu-std" class="menu nav-menu">
            foreach ($GLOBALS['zconf']['navi'] as $k0=>$v0) {
                if (empty($v0[1])) $v0[1]=$k0;
                echo '<li id="menu-item-'.$k0.'" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-'.$k0.'"><a href="'.$v0[1].'">'.$v0[0].'</a></li>';
            } 
        }
        if ($GLOBALS['zconf']['menutype']==1) {
            echo '						<div class="primary-menu-container"><ul id="menu-std" class="menu-wrapper">'; // <ul id="menu-std" class="menu nav-menu">
            foreach ($GLOBALS['zconf']['navi'] as $k0=>$v0) {
                if (empty($v0[1])) $v0[1]=$k0;
                echo '<li id="menu-item-'.$k0.'" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-'.$k0.'"><a href="'.$v0[1].'">'.$v0[0].'</a></li>';
                // add: menu-item-has-children 
            } 
        }

		echo '</ul></div>'; // $GLOBALS['zconf']['navi']
	break;
	default:
	break;
	}	
}

function get_search_form() {
	return; // none(!)
	echo '						<form role="search" method="get" class="search-form" action="./">
				<label>
					<span class="screen-reader-text">Suche nach:</span>
					<input type="search" class="search-field" placeholder="Suche …" value="" name="s">
				</label>
				<input type="submit" class="search-submit" value="Suche">
			</form>					';
}

function __($tx) { 
	return ''; // (!)
	$fnd=false;
	$t='owered by'; if (strpos($tx, $t)!==false) { $fnd=true; $tx=$GLOBALS['zconf']['foot'][0]."  &nbsp; &ndash; &nbsp; ".$GLOBALS['zconf']['foot'][1]; } // no, this one is not powered by WP. this is a (strange and outdated) hack if you dont want to edit the footer.php page of the theme
	if (!$fnd) $tx='';
	return $tx; 
}

function post_class() { echo 'class="post-'.get_the_ID().' page type-page status-publish hentry"'; }

function wp_enqueue_style(...$par) { 
	if (empty($par[1])) {
		$par[1]='*';
		if ($par[0]=='syntax-merriweather') $par[1]='//fonts.googleapis.com/css?family=Merriweather%3A400%2C300italic%2C300%2C400italic%2C700%2C700italic&#038;subset=latin%2Clatin-ext&#038;ver=4.7.5'; // syntax template
	}
	$r0=''; $r1=''; if (substr($par[0],0,2)=='ie') { $r0='<!--[if IE]>'.chr(10); $r1='<![endif]-->'.chr(10); } // in_array($par[0], $GLOBALS['iecss'])
	$t=''; if (isset($par[3]) and !empty($par[3])) { if (strpos($par[1],'?')===false) $t='?'; else $t='&#038;'; $t.='ver='.$par[3]; }
	echo $r0.'<link rel="stylesheet" id="'.$par[0].'-css" href="'.$par[1].$t.'" type="text/css" media="all">'.chr(10).$r1;
}

$GLOBALS['zfoot']='';
$GLOBALS['isjquery']=false;
function wp_enqueue_script(...$par) { 
	if (empty($par[1])) {
		$par[1]='*';
		if ($par[0]=='jquery') { 
            if (empty($GLOBALS['jqueryfiles'][0])) $par[1]='https://code.jquery.com/jquery-1.12.4.min.js'; // empty == no file in folder ./layout/scripts
            else $par[1]='./layout/scripts/'.$GLOBALS['jqueryfiles'][0];             
            $GLOBALS['isjquery']=true; 
        }
	}
	$t=''; if (isset($par[3]) and !empty($par[3])) { if (strpos($par[1],'?')===false) $t='?'; else $t='&#038;'; $t.='ver='.$par[3]; }
	$GLOBALS['zfoot'].='<script type="text/javascript" src="'.$par[1].$t.'"></script>'.chr(10);
	if (!$GLOBALS['isjquery'] and in_array('jquery', $par[2])) {
        if (empty($GLOBALS['jqueryfiles'][0])) $par[1]='https://code.jquery.com/jquery-1.12.4.min.js'; // empty == no file in folder ./layout/scripts
        else $par[1]='./layout/scripts/'.$GLOBALS['jqueryfiles'][0]; 
        $t=''; $GLOBALS['isjquery']=true;
        echo '<script type="text/javascript" src="'.$par[1].$t.'"></script>'.chr(10); 

        if (empty($GLOBALS['jqueryfiles'][1])) $t='https://code.jquery.com/jquery-migrate-1.4.1.min.js'; // empty == no file in folder ./layout/scripts
        else $t='./layout/scripts/'.$GLOBALS['jqueryfiles'][0]; 
		echo '<script type="text/javascript" src="'.$t.'"></script>'.chr(10);
	}
	if ($par[0]=='jquery') {
        if (empty($GLOBALS['jqueryfiles'][1])) $t='https://code.jquery.com/jquery-migrate-1.4.1.min.js'; // empty == no file in folder ./layout/scripts
        else $t='./layout/scripts/'.$GLOBALS['jqueryfiles'][0]; 
        $GLOBALS['zfoot'].='<script type="text/javascript" src="'.$t.'"></script>'.chr(10);
    }
}






# ----- ZP fns -----


$GLOBALS['var_siprefix']=array( // decimal and binary
-5=>array('f'),
-4=>array('p'),
-3=>array('n'),
-2=>array('µ'),
-1=>array('m'),
0=>array('',''),
1=>array('k','Ki'), // kilo 
2=>array('M','Mi'), // mega
3=>array('G','Gi'), // giga
4=>array('T','Ti'),
5=>array('P','Pi'),
); 

function out_val($val, $pdp=0, $md=array()) { // post decimal positions / -1==all
    $t='txt'; if (isset($md[$t]) and !empty($md[$t])) $$t=true; else $$t=false; // pos - NO &ndash;
	if ($val===false) {
		if ($txt) return '-';
		else return '&ndash;';
    }
//	$val=(double)str_replace(',', '.', $val);
	if ($pdp==-1) {
		$i=0; $q=$val;
		while ($q!=floor($q)) { $q*=10; $i++; }
		$pdp=$i;
    }
	return number_format($val, $pdp, ',', '.');
}
	
function out_sinum($val, $unit='B', $md=array()) { // outs vals with si 3240g->"3.24 kg"
    $t='bin'; if (isset($md[$t]) and !empty($md[$t])) $$t=true; else $$t=false; // pos - uses IEC binary instead of SI
    $t='pdp'; if (isset($md[$t])) $$t=$md[$t]; else $$t=-1;
    $t='txt'; if (isset($md[$t]) and !empty($md[$t])) $$t=true; else $$t=false; // pos - NO! &ndash;
	if ($bin===true) { $a=1024; $bin=1; }
	else { $a=1000; $bin=0; }
	$i=0;
	if (!empty($val)) {
        while (abs($val)<1) { $val=$val*$a; $i--; }
        while (abs($val)>$a) { $val=$val/$a; $i++; }
    }
	if ($pdp==-1) $pdp=3-strlen(floor(abs($val)));
	if ($txt) $t=' '; else $t='&#8239;'; // &thinsp; 
	$t.=$GLOBALS['var_siprefix'][$i][$bin]; 
	$rt=out_val($val, $pdp, $md).$t.$unit;
	return $rt;
}

function out_stat() { // statistics of page generation
	if (true) {
		$ts=''; 
		$ts.='<!-- '; if ($GLOBALS['is_adm']) $ts.='ADM: ';
		$ts.=out_sinum(memory_get_peak_usage(true)/*memory_get_usage(true)*/,'B',array('bin'=>true, 'txt'=>true)); // 123 kb
		$ts.=' - '.out_sinum(memory_get_usage(),'B',array('bin'=>true, 'txt'=>true)).''; // 123 kb
		$ts.=' | '.$GLOBALS['db_abfr'].' DBQ';
		$ts.=' | '.out_sinum(microtime(true)-$GLOBALS['laufz_s'],'s',array('txt'=>true));
		$ts.=' -->'.chr(10);
		echo $ts;
	}
}



?>
