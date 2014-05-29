<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package SDBXStudio
 * @subpackage sdbx
 * @since sdbx 0.1
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<title><?php sdbx_meta_title(); ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php if ( function_exists( 'sdbx_meta_data') ) sdbx_meta_data(); ?>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap-responsive.min.css" />
<?php
	$sdbx_head_script = stripslashes_deep( get_option('sdbx_head_script', '') );
	echo $sdbx_head_script;
	
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	wp_head();
?>
</head>

<body id="body" <?php body_class(); ?>>	

  <div id="canvas">
  	<div id="header">
  		<div id="logo"></div>
  		<?php echo do_shortcode('[sdbx_snippet name="header"]'); ?>
  	</div><!-- #header -->
  	
  	<?php sdbx_before_main(); ?>
  	<div id="main">
  	<?php sdbx_before_container(); ?>