<?php
/**
 * The Sidebar containing the primary widget areas.
 *
 * @package SDBXStudio
 * @subpackage SDBX
 * @since SDBX 0.1
 */
 
global $post;
$page_template = get_page_template();

?>

<div id="primary" class="widget-area" role="complementary">
	
	<?php sdbx_before_primary_content(); ?>
	
	<ul class="xoxo">
		<?php 
			dynamic_sidebar( 'primary-widget-area' );
		?>
	</ul>
</div><!-- #primary .widget-area -->
