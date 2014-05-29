<?php
/**
 * Template Name: Home
 * 
 * A custom page template with left sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package SDBXStudio
 * @subpackage SDBX
 * @since SDBX 0.1
 */


// to add additional editors to a page template add this comment to top:
// * Editors: one,two
// then to output content call like so :
// echo sdbx_content('one'); 				
// echo sdbx_content('two'); 

get_header(); ?>

<div class="template-home">

		<div id="aside">
		<?php 
			get_sidebar(); 
		?>
		</div>

		<div id="container">
					
			<div id="content" role="main">
		
			<?php 
				$template_part = 'page';
				switch ( $post->post_type ) {
					case 'page':
						$template_part = 'page';
						break;
					case 'post':
						$template_part = get_option( 'sdbx_current_template', '' );
						break;
					default:
						$template_part = $post->post_type;
						break;
				}
				get_template_part( 'content', $template_part );
			
			?>

			</div><!-- #content -->
		</div><!-- #container -->
		
</div>

<?php get_footer(); ?>
