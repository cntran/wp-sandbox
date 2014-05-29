<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package SDBXStudio
 * @subpackage SDBX
 * @since SDBX 0.1
 */
?>
	</div><!-- #main -->
		
	<div id="footer" role="contentinfo">
		<?php
			echo do_shortcode('[sdbx_snippet name="footer"]');
			wp_footer();
		?>

	</div><!-- #footer -->

</div><!-- #canvas -->
	

</body>
</html>
