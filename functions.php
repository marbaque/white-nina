<?php

/**
 * Nina functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package White_Nina
 */

if (!defined('white_nina_version')) {
	// Replace the version number of the theme on each release.
	define('white_nina_version', '1.2.3');
}

if (!function_exists('white_nina_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function white_nina_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on White Nina, use a find and replace
		 * to change 'white-nina' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('white-nina', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');
		add_image_size('white-nina-wide', 2000, 1200, true);

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(
			array(
				'primary' => esc_html__('Menu', 'white-nina'),
				'footer' => esc_html__( 'Footer Menu', 'white-nina' ),
				'social' => esc_html__( 'Social Links', 'white-nina' )
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		$logo_width  = 300;
		$logo_height = 100;

		add_theme_support(
			'custom-logo',
			array(
				'height'               => $logo_height,
				'width'                => $logo_width,
				'flex-width'           => true,
				'flex-height'          => true,
				'unlink-homepage-logo' => true,
			)
		);

		// Add support for Block Styles.
		add_theme_support('wp-block-styles');

		// Add support for editor styles.
		add_theme_support('editor-styles');
		$editor_stylesheet_path = './css/style-editor.css';
		// Enqueue editor styles.
		add_editor_style($editor_stylesheet_path);

		// Add support for responsive embedded content.
		add_theme_support('responsive-embeds');

		// Add support for experimental link color control.
		add_theme_support('experimental-link-color');

			
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) );
	}
endif;
add_action('after_setup_theme', 'white_nina_setup');

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $white_nina_content_width
 */
function white_nina_content_width()
{
	$GLOBALS['white_nina_content_width'] = apply_filters('white_nina_content_width', 640);
}
add_action('after_setup_theme', 'white_nina_content_width', 0);

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function white_nina_widgets_init()
{
	register_sidebar(
		array(
			'name'          => esc_html__('Sidebar', 'white-nina'),
			'id'            => 'sidebar-1',
			'description'   => esc_html__('Add widgets here.', 'white-nina'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
	register_sidebar(
		array(
			'name'          => esc_html__('Page Sidebar', 'white-nina'),
			'id'            => 'sidebar-2',
			'description'   => esc_html__('Add widgets here.', 'white-nina'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', 'white_nina_widgets_init');

/**
 * Register custom fonts.
 */
function white_nina_fonts_url()
{
	$fonts_url = '';

	/**
	 * Translators: If there are characters in your language that are not
	 * supported by Karla and Roboto+Slab, translate this to 'off'. Do not translate
	 * into your own language.
	 */
	$sansSerif = _x('on', 'Karla font: on or off', 'white-nina');
	$slab = _x('on', 'Roboto+Slab: on or off', 'white-nina');

	$font_families = array();

	if ('off' !== $sansSerif) {
		$font_families[] = 'Karla:400,400i,700,700i';
	}

	if ('off' !== $slab) {
		$font_families[] = 'Roboto Slab:400,600';
	}


	if (in_array('on', array($sansSerif,$slab))) {

		$query_args = array(
			'family' => urlencode(implode('|', $font_families)),
			'subset' => urlencode('latin,latin-ext'),
		);

		$fonts_url = add_query_arg($query_args, 'https://fonts.googleapis.com/css');
	}

	return esc_url_raw($fonts_url);
}

/**
 * Add preconnect for Google Fonts.
 *
 * @since Twenty Seventeen 1.0
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function white_nina_resource_hints($urls, $relation_type)
{
	if (wp_style_is('white-nina-fonts', 'queue') && 'preconnect' === $relation_type) {
		$urls[] = array(
			'href' => 'https://fonts.gstatic.com',
			'crossorigin',
		);
	}

	return $urls;
}
add_filter('wp_resource_hints', 'white_nina_resource_hints', 10, 2);

/**
 * Enqueue scripts and styles.
 */
function white_nina_scripts()
{
	// Enqueue Google Fonts: Karla y Roboto-Slab Serif Pro
	wp_enqueue_style('white-nina-fonts', white_nina_fonts_url());
	
	wp_enqueue_style('white-nina-style', get_stylesheet_uri(), array(), white_nina_version);
	wp_style_add_data('white-nina-style', 'rtl', 'replace');

	//wp_enqueue_script( 'white-nina-navigation', get_template_directory_uri() . '/js/navigation.js', array('jquery'), white_nina_version, true );

	wp_enqueue_script( 'white-nina-js', get_template_directory_uri() . '/js/index.js', array(), white_nina_version, false );
	
	wp_localize_script( 'white-nina-navigation', 'whiteninaScreenReaderText', array(
		'expand' => __( 'Expand child menu', 'white-nina'),
		'collapse' => __( 'Collapse child menu', 'white-nina'),
	));
	
	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', 'white_nina_scripts');


/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * @link https://git.io/vWdr2
 */
function white_nina_skip_link_focus_fix() {
	// The following is minified via `terser --compress --mangle -- assets/js/skip-link-focus-fix.js`.
	?>
	<script>
	/(trident|msie)/i.test(navigator.userAgent)&&document.getElementById&&window.addEventListener&&window.addEventListener("hashchange",function(){var t,e=location.hash.substring(1);/^[A-z0-9_-]+$/.test(e)&&(t=document.getElementById(e))&&(/^(?:a|select|input|button|textarea)$/i.test(t.tagName)||(t.tabIndex=-1),t.focus())},!1);
	</script>
	<?php
}
add_action( 'wp_print_footer_scripts', 'white_nina_skip_link_focus_fix' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if (defined('JETPACK__VERSION')) {
	require get_template_directory() . '/inc/jetpack.php';
}

// Custom page walker pages.
require get_template_directory() . '/classes/class-white-nina-walker-page.php';
