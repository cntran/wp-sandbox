<?php


add_action( 'admin_menu', 'sdbx_theme_menu' );  

function sdbx_theme_menu() {  
  
	global $themename, $shortname, $options;  
	  
	if ( isset( $_GET['page'] ) ) {
		if ( basename(__FILE__) === $_GET['page'] ) {  
		  	
		    
		    if ( $_REQUEST['saved'] == "save" ) {  	  
		    
		       // Get General Styles
		       $sdbx_head_script = $_REQUEST['sdbx_head_script'];
			   $sdbx_google_map = $_REQUEST['sdbx_google_map'];
		       		     
			   // Get Template Options
			   $sdbx_post_template = $_REQUEST['sdbx_post_template'];
			   $sdbx_author_template = $_REQUEST['sdbx_author_template'];
			   $sdbx_category_template = $_REQUEST['sdbx_category_template'];
			   $sdbx_tag_template = $_REQUEST['sdbx_tag_template'];
			   $sdbx_archive_template = $_REQUEST['sdbx_archive_template'];
		       	       
		       // Get Site Features
			   $sdbx_feature_blog = $_REQUEST['sdbx_feature_blog'];
			  
		       // Social Media Links
		       $sdbx_sm_display = $_REQUEST['sdbx_sm_display'];
		       $sdbx_sm_facebook_url = $_REQUEST['sdbx_sm_facebook_url'];
		       $sdbx_sm_linkedin_url = $_REQUEST['sdbx_sm_linkedin_url'];
		       $sdbx_sm_twitter_url = $_REQUEST['sdbx_sm_twitter_url'];
		       $sdbx_sm_googleplus_url = $_REQUEST['sdbx_sm_googleplus_url'];
		       $sdbx_sm_googleplusone_display = $_REQUEST['sdbx_sm_googleplusone_display'];
		       $sdbx_sm_facebooklike_display = $_REQUEST['sdbx_sm_facebooklike_display'];
			   
			   // Update General Styles
		  	   update_option( 'sdbx_head_script', $sdbx_head_script );	 
			   update_option( 'sdbx_google_map', $sdbx_google_map );	  	  	   		
		  	   	
			   // Update Template Options
			   update_option( 'sdbx_post_template', $sdbx_post_template );
			   update_option( 'sdbx_author_template', $sdbx_author_template );
			   update_option( 'sdbx_category_template', $sdbx_category_template );
			   update_option( 'sdbx_tag_template', $sdbx_tag_template );
			   update_option( 'sdbx_archive_template', $sdbx_archive_template );
			   
			   // Update Site Features   	
			   ( 'on' === $sdbx_feature_blog ) ? $sdbx_feature_blog = 1 : $sdbx_feature_blog = 0; 	
			   ( 'on' === $sdbx_feature_page_navigation ) ? $sdbx_feature_page_navigation = 1 : $sdbx_feature_page_navigation = 0; 
			   ( 'on' === $sdbx_feature_breadcrumbs ) ? $sdbx_feature_breadcrumbs = 1 : $sdbx_feature_breadcrumbs = 0; 
			  
			   update_option( 'sdbx_feature_blog', $sdbx_feature_blog );
			   update_option( 'sdbx_feature_page_navigation', $sdbx_feature_page_navigation );
			   update_option( 'sdbx_feature_breadcrumbs', $sdbx_feature_breadcrumbs );		
				
			   // Update Social Media Links
			   ( 'on' === $sdbx_sm_display ) ? $sdbx_sm_display = 1 : $sdbx_sm_display = 0; 
			   update_option( 'sdbx_sm_display', $sdbx_sm_display );
			   update_option( 'sdbx_sm_facebook_url', $sdbx_sm_facebook_url );
			   update_option( 'sdbx_sm_linkedin_url', $sdbx_sm_linkedin_url );
			   update_option( 'sdbx_sm_twitter_url', $sdbx_sm_twitter_url );
			   update_option( 'sdbx_sm_googleplus_url', $sdbx_sm_googleplus_url );
			   ( 'on' === $sdbx_sm_googleplusone_display ) ? $sdbx_sm_googleplusone_display = 1 : $sdbx_sm_googleplusone_display = 0; 
			   update_option( 'sdbx_sm_googleplusone_display', $sdbx_sm_googleplusone_display );
			   ( 'on' === $sdbx_sm_facebooklike_display ) ? $sdbx_sm_facebooklike_display = 1 : $sdbx_sm_facebooklike_display = 0; 
			   update_option( 'sdbx_sm_facebooklike_display', $sdbx_sm_facebooklike_display );
			   
			   header("Location: admin.php?page=admin.php&saved=true");  
			   die;
			}
		}  
    }
	add_menu_page( 'SDBX', 'SDBX', 'administrator', basename(__FILE__), 'sdbx_theme_admin' );  
}



function sdbx_theme_admin() {  
	
	if ( $_REQUEST['saved'] ) 
		echo '<div id="message" class="updated fade"><p><strong> settings saved.</strong></p></div>';  
	?>  
		<div class="wrap">
		<div id="icon-options-general" class="icon32"><br></div>
		<h2>SDBX Settings</h2><br />
		<form method="post">	  
			<table class="table-form">
				<!-- GENERAL -->
				
				<tr>
					<td colspan="2">
						<h3 class="title">General</h3>
					</td>
				</tr>	
				<tr>
					<td class="header" valign="top"><label for="sdbx_head_script">Head Script</label></td>
					<td>
						<textarea id="sdbx_head_script" name="sdbx_head_script" cols="40" rows="7"><?php echo stripslashes_deep( get_option('sdbx_head_script', '') ); ?></textarea>
					</td>
				</tr>
				<tr>
					<td class="header" valign="top"><label for="sdbx_google_map">Google Map</label></td>
					<td>
						<textarea id="sdbx_google_map" name="sdbx_google_map" cols="40" rows="7"><?php echo stripslashes_deep( get_option('sdbx_google_map', '') ); ?></textarea>
					</td>
				</tr>
				<!-- TEMPLATES -->
				<tr>
					<td colspan="2">
						<h3 class="title">Templates</h3>
					</td>
				</tr>
				<tr>
					<td class="header"><label for="sdbx_post_template">Post</label></td>
					<td>
						<select id="sdbx_post_template" name="sdbx_post_template">
							<option value=''></option>
							<?php
								$sdbx_post_template = get_option('sdbx_post_template', ''); 
								$templates = sdbx_get_page_templates( 'page' ); 
								
							    foreach ( $templates as $template ) {
							        $template = strtolower($template) ; 
									echo "<option value='" . $template . "' ";
									if ( $template === $sdbx_post_template )
										echo " selected='selected' ";
									echo ">" . str_replace( '.php', '', $template ) . "</option>";
								}	
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="header"><label for="sdbx_author_template">Author</label></td>
					<td>
						<select id="sdbx_author_template" name="sdbx_author_template">
							<option value=''></option>
							<?php
								$sdbx_author_template = get_option('sdbx_author_template', ''); 
								$templates = sdbx_get_page_templates( 'page' ); 
								
							    foreach ( $templates as $template ) {
							        $template = strtolower($template) ; 
									echo "<option value='" . $template . "' ";
									if ( $template === $sdbx_author_template )
										echo " selected='selected' ";
									echo ">" . str_replace( '.php', '', $template ) . "</option>";
								}	
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="header"><label for="sdbx_category_template">Category</label></td>
					<td>
						<select id="sdbx_category_template" name="sdbx_category_template">
							<option value=''></option>
							<?php
								$sdbx_category_template = get_option('sdbx_category_template', ''); 
								$templates = sdbx_get_page_templates( 'page' ); 
								
							    foreach ( $templates as $template ) {
							        $template = strtolower($template) ; 
									echo "<option value='" . $template . "' ";
									if ( $template === $sdbx_category_template )
										echo " selected='selected' ";
									echo ">" . str_replace( '.php', '', $template ) . "</option>";
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="header"><label for="sdbx_tag_template">Tag</label></td>
					<td>
						<select id="sdbx_tag_template" name="sdbx_tag_template">
							<option value=''></option>
							<?php
								$sdbx_tag_template = get_option('sdbx_tag_template', ''); 
								$templates = sdbx_get_page_templates( 'page' ); 
								
							    foreach ( $templates as $template ) {
							        $template = strtolower($template) ; 
									echo "<option value='" . $template . "' ";
									if ( $template === $sdbx_tag_template )
										echo " selected='selected' ";
									echo ">" . str_replace( '.php', '', $template ) . "</option>";
								}
							?>
						</select>
					</td>
				</tr>
				<tr>
					<td class="header"><label for="sdbx_archive_template">Archive</label></td>
					<td>
						<select id="sdbx_archive_template" name="sdbx_archive_template">
							<option value=''></option>
							<?php
								$sdbx_archive_template = get_option('sdbx_archive_template', ''); 
								$templates = sdbx_get_page_templates( 'page' ); 
								
							    foreach ( $templates as $template ) {
							        $template = strtolower($template) ; 
									echo "<option value='" . $template . "' ";
									if ( $template === $sdbx_archive_template )
										echo " selected='selected' ";
									echo ">" . str_replace( '.php', '', $template ) . "</option>";
								}
							?>
						</select>
					</td>
				</tr>
				<!-- FEATURES -->
				<tr>
					<td colspan="2">
						<h3 class="title">Features</h3>
					</td>
				</tr>
				<tr>
					<td class="header"><label for="sdbx_feature_blog">Include Blog</label></td>
					<td><input id="sdbx_feature_blog" name="sdbx_feature_blog" type="checkbox" <?php if ( get_option('sdbx_feature_blog', '') ) echo 'checked="checked"' ?> /></td>
				</tr>
				
				<tr>
					<td colspan="2">
						<h3 class="title">Social Media</h3>
					</td>
				</tr>
				<tr>
					<td class="header"><label for="sdbx_sm_display">Display Links</label></td>
					<td><input id="sdbx_sm_display" name="sdbx_sm_display" type="checkbox" <?php if ( get_option('sdbx_sm_display', false) ) echo 'checked="checked"' ?> /></td>
				</tr>
				<tr>
					<td class="header"><label for="sdbx_sm_facebook_url">Facebook Url</label></td>
					<td><input id="sdbx_sm_facebook_url" name="sdbx_sm_facebook_url" type="text" value="<?php echo get_option('sdbx_sm_facebook_url', ''); ?>" /></td>
				</tr>
				<tr>
					<td class="header"><label for="sdbx_sm_linkedin_url">LinkedIn Url</label></td>
					<td><input id="sdbx_sm_linkedin_url" name="sdbx_sm_linkedin_url" type="text" value="<?php echo get_option('sdbx_sm_linkedin_url', ''); ?>" /></td>
				</tr>
				<tr>
					<td class="header"><label for="sdbx_sm_twitter_url">Twitter Url</label></td>
					<td><input id="sdbx_sm_twitter_url" name="sdbx_sm_twitter_url" type="text" value="<?php echo get_option('sdbx_sm_twitter_url', ''); ?>" /></td>
				</tr>
				<tr>
					<td class="header"><label for="sdbx_sm_googleplus_url">Google Plus Url</label></td>
					<td><input id="sdbx_sm_googleplus_url" name="sdbx_sm_googleplus_url" type="text" value="<?php echo get_option('sdbx_sm_googleplus_url', ''); ?>" /></td>
				</tr>
				<tr>
					<td class="header"><label for="sdbx_sm_googleplusone_display">Display Google Plus One</label></td>
					<td><input id="sdbx_sm_googleplusone_display" name="sdbx_sm_googleplusone_display" type="checkbox" <?php if ( get_option('sdbx_sm_googleplusone_display', false) ) echo 'checked="checked"' ?> /></td>
				</tr>
				<tr>
					<td class="header"><label for="sdbx_sm_facebooklike_display">Display Facebook Like</label></td>
					<td><input id="sdbx_sm_facebooklike_display" name="sdbx_sm_facebooklike_display" type="checkbox" <?php if ( get_option('sdbx_sm_facebooklike_display', false) ) echo 'checked="checked"' ?> /></td>
				</tr>
				<tr>
					<td></td>
					<td>
						<input id="saved" name="saved" type="hidden" value="save" />
						<input type="submit" value="Save Changes" class="button-primary" />
					</td>
				</tr>
			</table>			
		</form>  
  		</div>
	<?php  
}  





?>