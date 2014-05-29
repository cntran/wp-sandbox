<?php
/**
 * Category Page Template - Two columns, Left Sidebar
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
			
				<h1 class="page-title"><?php printf( __( '%s', 'sdbx' ), single_cat_title( '', false )  ); ?></h1>
				<?php
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '<div class="archive-meta">' . $category_description . '</div>';

				/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-category.php and that will be used instead.
				 */
				get_template_part( 'loop', 'category' );
				?>

			</div><!-- #content -->
		</div><!-- #container -->
		
</div>

<?php get_footer(); ?>
