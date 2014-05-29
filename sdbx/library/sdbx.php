<?php


class sdbx {

	function sdbx() {
		$this->__construct();
	}
	
	function __construct() {
	
		/* Define framework, parent theme, and child theme constants. */
		add_action( 'after_setup_theme', array( &$this, 'constants' ), 1 );
		
		/* Load the framework extensions. */
		add_action( 'after_setup_theme', array( &$this, 'extensions' ), 2 );
		
		/* Load the framework functions. */
		add_action( 'after_setup_theme', array( &$this, 'functions' ), 3 );
		
		/* Load the framework ajax functions. */
		add_action( 'after_setup_theme', array( &$this, 'ajax' ), 3 );
				
		/* Define framework shortcodes. */
		add_action( 'after_setup_theme', array( &$this, 'shortcodes' ), 4 );
		
		/* Define framework widgets. */
		add_action( 'after_setup_theme', array( &$this, 'widgets' ), 5 );
		
		/* Run setup finalization */
		add_action( 'after_setup_theme', array( &$this, 'finish' ), 6 );
				
		/* Register widgets */
		add_action( 'widgets_init', array( &$this, 'widgets_init' ) );
		
	}
	
	function constants() {

		/* Sets the path to the parent theme directory. */
		define( 'THEME_DIR', get_template_directory() );

		/* Sets the path to the parent theme directory URI. */
		define( 'THEME_URI', get_template_directory_uri() );

		/* Sets the path to the child theme directory. */
		define( 'CHILD_THEME_DIR', get_stylesheet_directory() );

		/* Sets the path to the child theme directory URI. */
		define( 'CHILD_THEME_URI', get_stylesheet_directory_uri() );

		/* Sets the path to the core framework directory. */
		define( 'SDBX_DIR', trailingslashit( THEME_DIR ) . basename( dirname( __FILE__ ) ) );

		/* Sets the path to the core framework directory URI. */
		define( 'SDBX_URI', trailingslashit( THEME_URI ) . basename( dirname( __FILE__ ) ) );

		/* Sets the path to the core framework admin directory. */
		define( 'SDBX_ADMIN', trailingslashit( SDBX_DIR ) . 'admin' );

		/* Sets the path to the core framework extensions directory. */
		define( 'SDBX_EXTENSIONS', trailingslashit( SDBX_DIR ) . 'extensions' );

		/* Sets the path to the core framework functions directory. */
		define( 'SDBX_FUNCTIONS', trailingslashit( SDBX_DIR ) . 'functions' );
				
		/* Sets the path to the core framework widgets directory. */
		define( 'SDBX_WIDGETS', trailingslashit( SDBX_DIR ) . 'widgets' );

	}
	
	function functions() {
		
		// Load functions
		$files = scandir( trailingslashit( SDBX_FUNCTIONS ) );
	    foreach ( $files as $file ) {
	        $file = strtolower($file) ; 
			$file_ext = split("[/\\.]", $file) ; 
			
			if ($file_ext[count($file_ext) - 1] == 'php') 
				require_once( trailingslashit( SDBX_FUNCTIONS ) . $file );	
			
	    }	
	}
	
	function extensions() {

		/* Load the Breadcrumb Trail extension if supported. */
		require_if_theme_supports( 'breadcrumb-trail', trailingslashit( SDBX_EXTENSIONS ) . 'breadcrumb-trail.php' );
		
		/* Load the Multi Post Thumbnail. */
		require_if_theme_supports( 'sdbx-post-thumbnails', trailingslashit( SDBX_EXTENSIONS ) . 'post-thumbnails/class-post-thumbnails.php' );
		
	}
	
	function ajax() {
		require_once( trailingslashit( SDBX_FUNCTIONS ) . 'ajax/ajax.php' );
	}
	
	function shortcodes() {	
		if ( function_exists( 'sdbx_snippet' ) )
			add_shortcode( 'sdbx_snippet', 'sdbx_snippet' );
		if ( function_exists( 'sdbx_page_navigation' ) )
			add_shortcode( 'sdbx_page_navigation', 'sdbx_page_navigation' );
		if ( function_exists( 'sdbx_document_list' ) )
			add_shortcode( 'sdbx_document_list', 'sdbx_document_list' );
		if ( function_exists( 'sdbx_site_map' ) )
			add_shortcode( 'sdbx_site_map', 'sdbx_site_map' );
		if ( function_exists( 'sdbx_blog' ) )
			add_shortcode( 'sdbx_blog', 'sdbx_blog' );
		
	}
	
	function widgets() {	
	
		// Load widgets
		$files = scandir( trailingslashit( SDBX_WIDGETS ) );
	    foreach ( $files as $file ) {
	        $file = strtolower($file) ; 
			$file_ext = split("[/\\.]", $file) ; 
			
			if ($file_ext[count($file_ext) - 1] == 'php') 
				require_once( trailingslashit( SDBX_WIDGETS ) . $file );	
	    }	
	}
		
	
	/**
	 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
	 *
	 * To override sdbx_widgets_init() in a child theme, remove the action hook and add your own
	 * function tied to the init hook.
	 *
	 */
	function widgets_init() {		
		// Area 1, located at the top of the sidebar.
		register_sidebar( array(
			'name' => __( 'Primary Widget Area', 'sdbx' ),
			'id' => 'primary-widget-area',
			'description' => __( 'The primary widget area', 'sdbx' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	
		// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
		register_sidebar( array(
			'name' => __( 'Secondary Widget Area', 'sdbx' ),
			'id' => 'secondary-widget-area',
			'description' => __( 'The secondary widget area', 'sdbx' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
	
		
		// Area 3, located at the top of the sidebar for blog pages.
		register_sidebar( array(
			'name' => __( 'Blog Primary Widget Area', 'sdbx' ),
			'id' => 'blog-primary-widget-area',
			'description' => __( 'The blog primary widget area', 'sdbx' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
		
		// Area 4, located below the blog primary widget area.
		register_sidebar( array(
			'name' => __( 'Blog Secondary Widget Area', 'sdbx' ),
			'id' => 'blog-secondary-widget-area',
			'description' => __( 'The blog secondary widget area', 'sdbx' ),
			'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
			'after_widget' => '</li>',
			'before_title' => '<h3 class="widget-title">',
			'after_title' => '</h3>',
		) );
				
		
	}
	
	/*
	 * Run upon completion of theme setup
	 */
	function finish() {
		
		if ( current_theme_supports( 'sdbx-post-thumbnails' ) ) {
			new sdbx_post_thumbnails( array('label' => 'empty', 'id' => '_sdbx_empty', 'post_type' => 'sdbx' ) );
			$sdbx_post_thumbnails = new sdbx_post_thumbnails( array('label' => 'Featured Slide', 'id' => '_sdbx_featured_slide', 'post_type' => 'post' ) );
			$sdbx_post_thumbnails = new sdbx_post_thumbnails( array('label' => 'Featured Slide', 'id' => '_sdbx_featured_slide', 'post_type' => 'page' ) );
		}
		
	}
		
}

?>