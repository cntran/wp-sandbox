<?php
/**
 * Template Name: Blog
 *
 * @package SDBXStudio
 * @subpackage SDBX
 * @since SDBX 0.1
 */

get_header(); ?>

<div class="template-default template-blog">

		<div id="featured-image"><img src="<?php echo sdbx_get_featured_image(); ?>" /></div>

		<div id="container">
			
			<div id="content-navigation">
			<?php echo get_sidebar('blog'); ?>
			</div>
				
			<div id="content" role="main">
				<?php 
					$template_part = 'blog';
					switch ( $post->post_type ) {
						case 'post':
							$template_part = get_option( 'sdbx_current_template', '' );
							break;
						default:
							$template_part = 'blog';
							break;
					}
					get_template_part( 'content', $template_part );
          var_dump($template_part);
				?>
			</div><!-- #content -->
			
			<div class="clear"></div>
		</div><!-- #container -->
		
</div>

<?php get_footer(); ?>
