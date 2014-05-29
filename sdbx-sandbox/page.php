<?php
/**
 * 
 * The default page template
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package SDBXStudio
 * @subpackage SDBX
 * @since SDBX 0.1
 */

// to add additional editors to a page template add this comment to top:
// * Exditors: one,two
// then to output content call like so :
// echo sdbx_content('one'); 				
// echo sdbx_content('two'); 

get_header(); ?>

<div class="template-default">

    <div class="row-fluid">
  		<div id="aside" class="span4">
  		<?php 
  			get_sidebar(); 
  		  echo sdbx_page_navigation(array('show_header'=>1)); 
  		?>
  		</div>
  
  		<div id="container" class="span8">
  					
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
		
</div>

<?php get_footer(); ?>