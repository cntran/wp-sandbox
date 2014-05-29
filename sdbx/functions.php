<?php
/**
 * SDBX functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, sdbx_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'sdbx_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package SDBXStudio
 * @subpackage SDBX
 * @since SDBX 0.1
 */

/* Load the core theme framework. */
require_once( trailingslashit( TEMPLATEPATH ) . 'library/sdbx.php' );
$theme = new sdbx();

/** Tell WordPress to run sdbx_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'sdbx_setup', 1 );

if ( ! function_exists( 'sdbx_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override sdbx_setup() in a child theme, add your own sdbx_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since SDBX 0.1
 */

function sdbx_setup() {
	
	
	// INFRASTRUCTURE DEFINITIONS
	//define( 'WP_POST_REVISIONS', 5 );
	define( 'SDBX_EXCERPT_LENGTH', 40 );
	define( 'SDBX_PAGINATION_POSTS_PER_PAGE', 20 );
	
	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );
	
	// This theme uses sdbx multi post thumbnails (featured slides)
	//add_theme_support( 'sdbx-post-thumbnails' );
	
	// Add support for breadcrumbs
	add_theme_support( 'breadcrumb-trail' );

	// Add default posts and comments RSS feed links to head
	//add_theme_support( 'automatic-feed-links' );	
	
	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'sdbx', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'sdbx' ),
		'top' => __( 'Top Navigation', 'sdbx' ),
	) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( 295, 183, true );

	// Don't support text inside the header image.
	define( 'NO_HEADER_TEXT', true );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See sdbx_admin_header_style(), below.
	//add_custom_image_header( '', NULL );

}
endif;

add_action( 'after_setup_theme', 'sdbx_remove_actions' );
add_action( 'after_setup_theme', 'sdbx_add_actions' );

function sdbx_remove_actions() {
	// remove_action( 'sdbx_before_post_content', 'sdbx_breadcrumb' );
	// Remove WP version info

}
	

function sdbx_add_actions() {
 

} 

add_filter('the_generator','sdbx_hide_wp_vers');
function sdbx_hide_wp_vers()
{
    return '';
} // end hide_wp_vers function


?>