<?php
/**
 * ultrabootstrap functions and definitions
 *
 * @package ultrabootstrap
 */

if ( ! function_exists( 'ultrabootstrap_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function ultrabootstrap_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on ultrabootstrap, use a find and replace
	 * to change 'ultrabootstrap' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'ultrabootstrap', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	
	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'ultrabootstrap' ),
		'secondary' => esc_html__( 'Footer Menu', 'ultrabootstrap' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'custom-logo', array(
   'height'      => 45,
   'width'       => 250,
   'flex-width' => true,
	));

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'ultrabootstrap_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	add_theme_support( "custom-header", 
		array(
		'default-color' => 'ffffff',
		'default-image' => '',
			)  
		);
	add_editor_style() ;
}
endif; // ultrabootstrap_setup
add_action( 'after_setup_theme', 'ultrabootstrap_setup' );




/**
 * Enqueue scripts and styles.
 */
function ultrabootstrap_scripts() {
	wp_enqueue_style( 'ultrabootstrap-bootstrap', get_template_directory_uri().'/css/bootstrap.css' );	
	wp_enqueue_style( 'ultrabootstrap-fontawesome', get_template_directory_uri().'/css/font-awesome.css' );
	wp_enqueue_style( 'ultrabootstrap-googlefonts', '//fonts.googleapis.com/css?family=Roboto:400,300,700');
	wp_enqueue_style( 'ultrabootstrap-style', get_stylesheet_uri() );


	if(is_rtl()) {
		wp_enqueue_style( 'ultrabootstrap-rtl', get_template_directory_uri().'/css/rtl.css' );
		wp_enqueue_style( 'ultrabootstrap-css-rtl', get_template_directory_uri().'/css/bootstrap-rtl.css' );
		wp_enqueue_script( 'ultrabootstrap-js-rtl', get_template_directory_uri() . '/js/bootstrap.rtl.js', array(), '1.0.0', true );
	}

	wp_enqueue_script('jquery');
	wp_enqueue_script( 'ultrabootstrap-bootstrap', get_template_directory_uri() . '/js/bootstrap.js', array(), '1.0.0', true );
	wp_enqueue_script( 'ultrabootstrap-scripts', get_template_directory_uri() . '/js/script.js', array(), '1.0.0', true );


	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'ultrabootstrap_scripts' );





/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
if ( ! isset( $content_width ) ) $content_width = 900;
function ultrabootstrap_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'ultrabootstrap_content_width', 640 );

}
add_action( 'after_setup_theme', 'ultrabootstrap_content_width', 0 );


function ultrabootstrap_filter_front_page_template( $template ) {
    return is_home() ? '' : $template;
}
add_filter( 'front_page_template', 'ultrabootstrap_filter_front_page_template' );





/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */


function ultrabootstrap_widgets_init() {
		
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'ultrabootstrap' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );

	register_sidebar( array(
			'name'          => __('Footer One','ultrabootstrap'),
			'id'            => 'footer-1',
			'description'   => __('Footer First Widget','ultrabootstrap'),
			'before_widget' => '<div class="col-md-3 col-sm-6">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	register_sidebar( array(
			'name'          => __('Footer Two','ultrabootstrap'),
			'id'            => 'footer-2',
			'description'   => __('Footer Second Widget','ultrabootstrap'),
			'before_widget' => '<div class="col-md-3 col-sm-6">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	register_sidebar( array(
			'name'          => __('Footer There','ultrabootstrap'),
			'id'            => 'footer-3',
			'description'   => __('Footer Third Widget','ultrabootstrap'),
			'before_widget' => '<div class="col-md-3 col-sm-6">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
	register_sidebar( array(
			'name'          => __('Footer Four','ultrabootstrap'),
			'id'            => 'footer-4',
			'description'   => __('Footer Four Widget','ultrabootstrap'),
			'before_widget' => '<div class="col-md-3 col-sm-6">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		) );
}
add_action( 'widgets_init', 'ultrabootstrap_widgets_init' );


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/class.php';

//require get_template_directory() . '/inc/wp_bootstrap_navwalker.php';

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

// Register Custom Navigation Walker
require get_template_directory() . '/inc/wp_bootstrap_navwalker.php';