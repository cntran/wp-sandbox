<?php
/**
 * Tag Page Template - Two columns, Left Sidebar
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
			
				<h1 class="page-title"><?php
					printf( __( 'Tag Archives: %s', 'sdbx' ), '<span>' . single_tag_title( '', false ) . '</span>' );
				?></h1>

				<?php
				/* Run the loop for the tag archive to output the posts
				 * If you want to overload this in a child theme then include a file
				 * called loop-tag.php and that will be used instead.
				 */
				 get_template_part( 'loop', 'tag' );
				?>
				
			</div><!-- #content -->
		</div><!-- #container -->
		
</div>

<?php get_footer(); ?>
