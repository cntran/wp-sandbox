<div id="access-top" role="navigation">
	
	<div id="socialmedia-top">
		<?php echo do_shortcode('[sdbx_snippet name="social-media"]'); ?>
	</div>
	
	<?php 
		$top_menu = wp_nav_menu( array( 'container_class' => 'menu-top', 'theme_location' => 'top' , 'depth' => 1, 'echo' => 0, 'fallback_cb' => '') ); 
		echo $top_menu;
	?>
	
	<div class="clear"></div>
</div><!-- #access-top -->


<div id="access" role="navigation">
		<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
		<?php 
			$main_menu = wp_nav_menu( array( 'container_class' => 'menu-main', 'theme_location' => 'primary', 'echo' => 0 ) ); 
			echo $main_menu; 
		?>
</div><!-- #access -->
<div class="clear"></div>