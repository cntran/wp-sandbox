<?php 
	$footer_menu = wp_nav_menu( array( 'container_class' => 'menu-footer', 'menu' => 'footer', 'depth' => 1, 'echo' => 0, 'fallback_cb' => '') ); 
	echo $footer_menu;

	$site_title = get_option( 'blogname', '' );
	$phone_number = get_option( 'phone_number', '' );
	$fax_number = get_option( 'fax_number', '' );
	$email_address = get_option( 'admin_email', '' );
	$address_1 = get_option( 'address_1', '' );
	$address_2 = get_option( 'address_2', '' );
	$city = get_option( 'city', '' );
	$state = get_option( 'state', '' );
	$zip = get_option( 'zip', '' );
	$city_state_zip = $city . ', ' . $state . ' ' . $zip;
	
	$sdbx_sm_display = get_option( 'sdbx_sm_display', false );

?>

<div itemscope itemtype="http://schema.org/LocalBusiness">
	<p class="center">
	<?php if ('' !== $email_address ) : ?>
		<a href="mailto:<?php echo $email_address; ?>" itemprop="email"><?php echo $email_address; ?></a>&nbsp;&nbsp;&nbsp;&nbsp;&#042;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php endif; ?>
	<?php if ('' !== $phone_number ) : ?>	
		<span itemprop="telephone"><?php echo $phone_number; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;&#042;&nbsp;&nbsp;&nbsp;&nbsp;
	<?php endif; ?>
		<span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
	<?php if ('' !== $address_1 ) : ?>
		<span itemprop="streetAddress"><?php echo $address_1; ?></span>&nbsp;,	
  <?php endif; ?>
	<?php if ('' !== $address_2 ) : ?>
		<?php echo $address_2; ?>&nbsp;,
	<?php endif; ?>
	<?php if ('' !== $city_state_zip ) : ?>
		<span itemprop="addressLocality"><?php echo $city; ?></span>,&nbsp;	
		<span itemprop="addressRegion"><?php echo $state; ?></span>&nbsp;
		<span itemprop="postalCode"><?php echo $zip; ?></span>
	<?php endif; ?>
	</span>
	</p>
</div>	 	

<?php if ( $sdbx_sm_display ) : ?>	
<div style="padding-top:5px;"></div>
<table class="center">
<tr>
<td style="vertical-align:middle; text-align:right;">
	FOLLOW US:&nbsp;&nbsp;
</td> 
<td  style="vertical-align:middle">
<?php echo do_shortcode('[sdbx_snippet name="social-media"]'); ?>
</td>
</tr>
</table>
<?php endif; ?>