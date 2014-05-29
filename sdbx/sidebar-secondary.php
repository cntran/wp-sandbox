<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package SDBXStudio
 * @subpackage SDBX
 * @since SDBX 0.1
 */

global $post;
$page_template = get_page_template();
$is_blog = false;
if ( preg_match('/sdbx_blog/', $post->post_content) )
	$is_blog = true;
 
?>

<?php 
if ( is_active_sidebar( 'primary-widget-area' ) ||  is_active_sidebar( 'blog-primary-widget-area' ) ) : ?>

<div id="primary" class="widget-area" role="complementary">

	<?php sdbx_before_primary_content(); ?>
	
	<ul class="xoxo">
		<?php 
			if ( 'post' === $post->post_type || true === $is_blog )
				dynamic_sidebar( 'blog-primary-widget-area' ); 
			else if ( is_active_sidebar( 'primary-widget-area' ) ) 
				dynamic_sidebar( 'primary-widget-area' );
		?>
		
	</ul>
</div><!-- #primary .widget-area -->

<?php endif; ?>

<?php
// A second sidebar for widgets, just because.
if ( is_active_sidebar( 'secondary-widget-area' ) || is_active_sidebar( 'blog-secondary-widget-area' ) ) : ?>

<div id="secondary" class="widget-area" role="complementary">
	<ul class="xoxo">
		<?php 
			if ( 'post' === $post->post_type || true === $is_blog )
				dynamic_sidebar( 'blog-secondary-widget-area' ); 
			else if ( is_active_sidebar( 'secondary-widget-area' ) ) 
				dynamic_sidebar( 'secondary-widget-area' );
		?>
	</ul>
</div><!-- #secondary .widget-area -->

<?php endif; ?>
