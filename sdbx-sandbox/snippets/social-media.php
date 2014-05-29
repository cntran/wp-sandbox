<?php 
	$sdbx_sm_display = get_option( 'sdbx_sm_display', false );
?>

<?php if ( $sdbx_sm_display ) : ?>
	<table>
	<tr>
	<?php if ( '' !== get_option('sdbx_sm_facebook_url', '') ) : ?>
	<td><a href="<?php echo get_option('sdbx_sm_facebook_url', ''); ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/facebook.png" width="24" /></a>&nbsp;</td>
	<?php endif; ?>
	
	<?php if ( '' !== get_option('sdbx_sm_linkedin_url', '') ) : ?>
	<td><a href="<?php echo get_option('sdbx_sm_linkedin_url', ''); ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/linkedin.png" width="24" /></a>&nbsp;</td>
	<?php endif; ?>
	
	<?php if ( '' !== get_option('sdbx_sm_twitter_url', '') ) : ?>
	<td><a href="<?php echo get_option('sdbx_sm_twitter_url', ''); ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/twitter.png" width="24" /></a>&nbsp;</td>
	<?php endif; ?>
	
	<?php if ( '' !== get_option('sdbx_sm_googleplus_url', '') ) : ?>
	<td><a href="<?php echo get_option('sdbx_sm_googleplus_url', ''); ?>" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/gplus.png" width="24" /></a>&nbsp;</td>
	<?php endif; ?>
	
	<?php if ( get_option('sdbx_sm_googleplusone_display', false) ) : ?>
	<td><g:plusone annotation="none" href="<?php echo get_bloginfo('url'); ?>"></g:plusone>&nbsp;</td>
	<?php endif; ?>
	
	<?php if ( get_option('sdbx_sm_facebooklike_display', false) ) : ?>
	<td>
		<div style="position:relative; width:50px">
		<div style="position:absolute; top: 3px; left: 0;">
			<div class="fb-like" data-href="<?php echo get_option('sdbx_sm_facebook_url', ''); ?>" data-send="false" data-layout="button_count" data-width="24" data-show-faces="false"></div>
		</div>
		</div>
	</td>
	<?php endif; ?>
	</tr>
	</table>
<?php endif; ?>