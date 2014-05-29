<?php
/**
 * Archive Page Template - Two columns, Left Sidebar
 * Layout: two-column-left
 * A custom page template with left sidebar.
 *
 * @package SDBXStudio
 * @subpackage SDBX
 * @since SDBX 0.1
 */

get_header(); ?>

<div class="two-column-left">

		<div id="aside">
		<?php 
			get_sidebar(); 
		?>
		</div>

		<div id="container">
					
			<div id="content" role="main">
			
<?php
	/* Queue the first post, that way we know
	 * what date we're dealing with (if that is the case).
	 *
	 * We reset this later so we can run the loop
	 * properly with a call to rewind_posts().
	 */
	if ( have_posts() )
		the_post();
?>

			<h1 class="page-title">
<?php if ( is_day() ) : ?>
				<?php printf( __( 'Daily Archives: <span>%s</span>', 'sdbx' ), get_the_date() ); ?>
<?php elseif ( is_month() ) : ?>
				<?php printf( __( 'Monthly Archives: <span>%s</span>', 'sdbx' ), get_the_date('F Y') ); ?>
<?php elseif ( is_year() ) : ?>
				<?php printf( __( 'Yearly Archives: <span>%s</span>', 'sdbx' ), get_the_date('Y') ); ?>
<?php else : ?>
				<?php _e( 'Blog Archives', 'sdbx' ); ?>
<?php endif; ?>
			</h1>

<?php
	/* Since we called the_post() above, we need to
	 * rewind the loop back to the beginning that way
	 * we can run the loop properly, in full.
	 */
	rewind_posts();

	/* Run the loop for the archives page to output the posts.
	 * If you want to overload this in a child theme then include a file
	 * called loop-archives.php and that will be used instead.
	 */
	 get_template_part( 'loop', 'archive' );
?>
	
			</div><!-- #content -->
		</div><!-- #container -->
		
</div>

<?php get_footer(); ?>
