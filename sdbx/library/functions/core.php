<?php

/*
 * ACTION HOOKS
 */
add_action( 'parse_request', 'sdbx_parse_request' );
add_action( 'wp_enqueue_scripts', 'sdbx_load_references' );
add_action( 'admin_enqueue_scripts', 'sdbx_load_references_admin' );
add_action( 'login_head', 'sdbx_custom_login_logo' );
add_action( 'admin_head', 'sdbx_custom_admin_head' );
add_action( 'widgets_init', 'sdbx_remove_recent_comments_style' );
add_action( 'admin_init', 'sdbx_admin_init' );
add_action( 'save_post', 'sdbx_save_post' );	
add_action( 'init', 'sdbx_add_excerpts_to_pages' );
add_action( 'get_header', 'sdbx_redirect_child' );
add_action( 'wp_footer', 'sdbx_resize_footer' );
add_action( 'admin_menu', 'sdbx_remove_menu_items');
add_action( 'admin_bar_menu', 'sdbx_admin_bar_menu' );
add_action( 'admin_head', 'sdbx_profile_admin_buffer_start');
add_action( 'admin_footer', 'sdbx_profile_admin_buffer_end');
add_action( 'admin_head_media_upload_gallery_form', 'sdbx_mfields_remove_gallery_setting_div' );

function sdbx_parse_request() {
	
	$site_url = get_bloginfo('url');
	$site_url = str_replace( 'http://', '', $site_url );
	$site_url_www = substr( $site_url, 0, 3 );
	
	if ( 'www' === $site_url_www ) { // if site should have a www, make sure it is in the request
		
		$url = $_SERVER['HTTP_HOST'];
	
		$url_prefix = substr( $url, 0, 3 );
		
		if ( 'www' !== $url_prefix ) {
			$url = 'http://www.' . $url . $_SERVER['REQUEST_URI'];
		
			header ('HTTP/1.1 301 Moved Permanently');
			header( 'Location: ' . $url ) ;
			exit();
		}
	}
}


function sdbx_redirect_child() {
	// Check if need to redirect to child page:
	global $post, $wpdb;
	$sdbx_page_redirect_child = get_post_meta( $post->ID, '_sdbx_page_redirect_child', true );
	
	if ( "1" === $sdbx_page_redirect_child )  {
			$querystr = "SELECT wposts.* 
					     FROM $wpdb->posts wposts
					     WHERE wposts.post_parent = " . $post->ID . " 
					     AND wposts.post_status = 'publish' 
					     AND wposts.post_type = 'page'
					     ORDER BY wposts.menu_order 
					     LIMIT 0, 1";
						
			$pages = $wpdb->get_results( $querystr, OBJECT );		
			$redirect = '';
			foreach ($pages as $i) {
				$redirect = get_permalink( $i->ID );
			}
			if ($redirect != "") {
				wp_redirect($redirect);
			}
	}
}

function sdbx_load_references() {
?>
<link rel="stylesheet" type="text/css" href="<?php echo trailingslashit(get_stylesheet_directory_uri()) ?>style.css" />
<!--[if IE]><link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() ?>/css/ie.css" /><![endif]-->
<!--[if IE 7]><link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() ?>/css/ie7.css" /><![endif]-->
<!--[if IE 8]><link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() ?>/css/ie8.css" /><![endif]-->
<!--[if IE 9]><link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_directory_uri() ?>/css/ie9.css" /><![endif]-->
<?php	

$js_includes = sdbx_get_js_includes();
if ( count( $js_includes ) > 0 ) {
	foreach ( $js_includes as $include ) {
		wp_register_script( $include, trailingslashit(get_stylesheet_directory_uri()) . 'js/' . $include );
		wp_enqueue_script( $include );
	}
}
wp_enqueue_script( 'sdbx_index', THEME_URI . '/js/index.js' );
wp_enqueue_script( 'jquery' );

//add_thickbox();

// add G+ reference
if ( get_option('sdbx_sm_googleplusone_display', false) ) {
?>
<script type="text/javascript">(function() { var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true; po.src = 'https://apis.google.com/js/plusone.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s); })();</script>
<?php
}

// add FB reference
if ( get_option('sdbx_sm_facebooklike_display', false) ) {
?>
<div id="fb-root"></div><script>(function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));</script>
<?php
}

}

function sdbx_load_references_admin() {
	wp_register_style( 'sdbx_admin_css_child', CHILD_THEME_URI . '/css/admin.css' );
	wp_enqueue_style( 'sdbx_admin_css_child' );	
	wp_register_style( 'sdbx_admin_css_parent', THEME_URI . '/css/admin.css' );
	wp_enqueue_style( 'sdbx_admin_css_parent' );
	
	wp_enqueue_script( 'sdbx_admin_script_parent', THEME_URI . '/js/admin.js' );
	
	global $current_user;
	get_currentuserinfo();
	$role = $current_user->roles[0];  
	  
	if ( 'administrator' !== $role )  {
		
		wp_register_style( 'sdbx_admin_css_child_editor', CHILD_THEME_URI . '/css/admin-editor.css' );
		wp_enqueue_style( 'sdbx_admin_css_child_editor' );	
		wp_register_style( 'sdbx_admin_css_parent_editor', THEME_URI . '/css/admin-editor.css' );
		wp_enqueue_style( 'sdbx_admin_css_parent_editor' );	
	}
    
}

function sdbx_remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action( 'wp_head', array( $wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style' ) );
}

function sdbx_custom_login_logo() {
    echo '<style type="text/css">';
    echo 'h1 a { background-image:url('. THEME_URI . '/images/login.gif) !important; background-size: auto !important; }';
    echo '</style>';
}

function sdbx_custom_admin_head() {
    echo '<style type="text/css">';
    echo '#header-logo { background-image:url('. THEME_URI . '/images/logo-admin.gif) !important; }';
    echo '</style>';
	
	global $current_screen;
  
	if ( ( 'edit-page' === $current_screen->id  || 'page' === $current_screen->id ) && !current_user_can('publish_pages') )
	{
		echo '<style type="text/css">.add-new-h2{display: none;}</style>';  
	}
}

function sdbx_breadcrumb() {
	if ( current_theme_supports( 'breadcrumb-trail' ) ) {
		breadcrumb_trail();
	}
}

function sdbx_admin_init() {

	// Add meta boxes to post and page editors
	add_meta_box( 'sdbx-post-template', __('Post Template', 'sdbx'), 'sdbx_post_template_meta_box', 'post', 'side', 'high' );
	
	$post_types = get_post_types();	
	foreach ( $post_types as $post_type ) {
		add_meta_box( 'sdbx-post-meta', __('Meta Data', 'sdbx'), 'sdbx_post_meta_box', $post_type, 'advanced', 'high' );
	}
	
	global $current_user;
	get_currentuserinfo();
	$role = $current_user->roles[0];  
	  
	if ( 'administrator' === $role )  {
		add_meta_box( 'sdbx-page-attributes', __('SDBX Page Attributes', 'sdbx'), 'sdbx_page_attributes_meta_box', 'page', 'side', 'default' );
		add_meta_box( 'sdbx-post-attributes', __('SDBX Post Attributes', 'sdbx'), 'sdbx_page_attributes_meta_box', 'post', 'side', 'default' );
	}
	
	// adjust editor capabilities
	$editor_role = get_role('editor');
    $editor_role->add_cap('publish_pages');
  
  	// add additional editors if exists
  	$editors = sdbx_get_page_editors();
	
	$count = 0;
	foreach ( $editors as $editor ) {
		add_meta_box( 'sdbx-page-editor' . $count, __($editor, 'sdbx'), 'sdbx_page_editor_meta_box', 'page', 'normal', 'default' );
		$count++;
	}
	
	// add additional post thumbnails if exist
	if (function_exists(sdbx_post_thumbnails)) {
  	if ( isset($_REQUEST['post']) )
  		$post_id = $_REQUEST['post'];
  	else
  		$post_id = $_REQUEST['post_id'];	
  	
  	$thumbnails = sdbx_get_page_thumbnails();
  	$count = 0;
  	
  	foreach ( $thumbnails as $thumbnail ) {
  		if ( '' === $thumbnail )
  			return;
  		$thumbnail_id = trim($thumbnail);
  		$thumbnail_id = strtolower(preg_replace('/\s+/', '', $thumbnail_id));
  		$thumbnail_id = '_sdbx_' . $post_id . '_' . $thumbnail_id;
  
  		if ( isset($post_id) && '' !== $post_id )
  	    	$sdbx_post_thumbnails = new sdbx_post_thumbnails( array('label' => $thumbnail, 'id' => $thumbnail_id, 'post_type' => 'page' ) );
  		
  		$count++;
  	}	
  }
}

function sdbx_page_editor_meta_box( $post, $metabox ) {
	$editor_id = trim($metabox['title']);
	$editor_id = strtolower(preg_replace('/\s+/', '', $editor_id));
	
	$sdbx_page_editor_content = get_post_meta( $post->ID, '_sdbx_page_editor_content_' . $editor_id, true );
	
	wp_editor($sdbx_page_editor_content, 'textarea-' . $editor_id );
}
function sdbx_get_page_editors() {
	$page_template = sdbx_get_page_template();
 
	if ($page_template == 'default') {
	  if ( file_exists(  CHILD_THEME_DIR . '/page.php' ) )
	   $page_template = "page.php";
	}
	if ( file_exists(  CHILD_THEME_DIR . '/' . $page_template ) ) {
		$lines = file( CHILD_THEME_DIR . '/' . $page_template );
	}
  
	$editors = '';
	if ( count($lines) > 0 ) {
		foreach ($lines as $line_num => $line) {
		    if ( preg_match('/editors:/i', $line) ) {
				$editors = trim( preg_replace('/.*editors:/i', '', $line) );
				
				break;
			}
		}
	}
	return explode( ',', $editors );
}
function sdbx_get_page_thumbnails() {
	$page_template = sdbx_get_page_template();
	if ( file_exists(  CHILD_THEME_DIR . '/' . $page_template ) ) {
		$lines = file( CHILD_THEME_DIR . '/' . $page_template );
		
	}
	$editors = '';
	
	if ( count($lines) > 0 ) {
		foreach ($lines as $line_num => $line) {
		    if ( preg_match('/thumbnails:/i', $line) ) {
				$editors = trim( preg_replace('/.*thumbnails:/i', '', $line) );
				break;
			}
		}
	}
	
	return explode( ',', $editors );
}

function sdbx_save_post( $post_id ) {
	// Save meta box information
	sdbx_save_post_meta_box( $post_id );
	sdbx_save_template_meta_box( $post_id );
	
	global $current_user;
	get_currentuserinfo();
	$role = $current_user->roles[0];  
	  
	if ( 'administrator' === $role )  {
		sdbx_save_page_attributes_meta_box( $post_id );
	}
	sdbx_save_page_editors_meta_box( $post_id );
}

function sdbx_post_template_meta_box( $post ) {

	if ( 'page' != $post->post_type && 0 != count( sdbx_get_post_templates() ) ) {
	    $sdbx_post_template = get_post_meta( $post->ID, '_sdbx_post_template', true );
		
	    ?>
		<label class="screen-reader-text" for="sdbx_post_template"><?php _e('Post Template') ?></label>
		<select name="sdbx_post_template" id="sdbx_post_template">
			<option value='single.php'><?php _e('Default Template'); ?></option>
			<?php sdbx_post_template_dropdown( $sdbx_post_template ); ?>
		</select>
		<?php
	} 
}

function sdbx_post_meta_box( $post ) {

	    $sdbx_meta_title = get_post_meta( $post->ID, '_sdbx_meta_title', true );
	    $sdbx_meta_description = get_post_meta( $post->ID, '_sdbx_meta_description', true );
	    $sdbx_meta_keywords = get_post_meta( $post->ID, '_sdbx_meta_keywords', true );
	    	    	   
	    ?>
		<label class="screen-reader-text" for="sdbx_post_meta"><?php _e('Meta Data') ?></label>
		<table class="table-meta-box">
			<tr>
				<td width="120"><label for="sdbx_meta_title"><strong>Title</strong></label><br /><em>(max chars: 65)</em></td>
				<td><input class="inputText" type="text" name="sdbx_meta_title" id="sdbx_meta_title" value="<?php echo $sdbx_meta_title; ?>" maxlength="65" /></td>
			</tr>
			<tr>
				<td><label for="sdbx_meta_description"><strong>Description</strong></label><br /><em>(max chars: 155)</em></td>
				<td><input class="inputText" type="text" name="sdbx_meta_description" id="sdbx_meta_description" value="<?php echo $sdbx_meta_description; ?>" maxlength="155" /></td>
			</tr>
			<tr>
				<td><label for="sdbx_meta_keywords"><strong>Keywords</strong></label></td>
				<td><input class="inputText"  type="text" name="sdbx_meta_keywords" id="sdbx_meta_keywords" value="<?php echo $sdbx_meta_keywords; ?>" /></td>
			</tr>
		</table>
		
		<?php
}

function sdbx_page_attributes_meta_box( $post ) {

	if ( 'page' === $post->post_type || 'post' === $post->post_type ) {
		
		?>		
	    <div class="sdbx-meta-body">
		
		<?php
		
	    $sdbx_page_redirect_child = get_post_meta( $post->ID, '_sdbx_page_redirect_child', true );
		$checked = "";
	    	    
	    if ( "1" === $sdbx_page_redirect_child ) 
	    	$checked = "checked='checked'";	  		
		?>
		<p>
			<label for="sdbx_page_redirect_child"><strong>Redirect Child</strong>&nbsp;</label>
			<input type="checkbox" name="sdbx_page_redirect_child" id="sdbx_page_redirect_child" <?php echo $checked; ?> />
		</p>
		<?php
		$sdbx_page_hide_page_title = get_post_meta( $post->ID, '_sdbx_page_hide_page_title', true );
		 
		$checked = "";
	    	    
	    if ( "1" === $sdbx_page_hide_page_title ) 
	    	$checked = "checked='checked'";	  		
		?>
		<p>
			<label for="sdbx_page_hide_page_title"><strong>Hide Page Title</strong>&nbsp;</label>
			<input type="checkbox" name="sdbx_page_hide_page_title" id="sdbx_page_hide_page_title" <?php echo $checked; ?> />
		</p>
		
		
		<?php
		$sdbx_page_hide_page_thumbnail = get_post_meta( $post->ID, '_sdbx_page_hide_page_thumbnail', true );
		$checked = "";
	   
	    if ( "1" === $sdbx_page_hide_page_thumbnail ) 
	    	$checked = "checked='checked'";	  		
		?>
		<p>
			<label for="sdbx_page_hide_page_thumbnail"><strong>Hide Page Thumbnail</strong>&nbsp;</label>
			<input type="checkbox" name="sdbx_page_hide_page_thumbnail" id="sdbx_page_hide_page_thumbnail" <?php echo $checked; ?> />
		</p>
		
		</div>
		<?php
	} 
}

function sdbx_save_post_meta_box( $post_id ) {

	$post = get_post( $post_id );

	if ( $post->post_type == 'revision' ) 
		return;		
	    
	if (isset($_POST['sdbx_meta_title'])) {
		$sdbx_meta_title = $_POST['sdbx_meta_title'];
		$sdbx_meta_description = $_POST['sdbx_meta_description'];
		$sdbx_meta_keywords = $_POST['sdbx_meta_keywords'];			
	
		update_post_meta ( $post->ID, '_sdbx_meta_title', $sdbx_meta_title );
		update_post_meta ( $post->ID, '_sdbx_meta_description', $sdbx_meta_description );
		update_post_meta ( $post->ID, '_sdbx_meta_keywords', $sdbx_meta_keywords );
	}
}

function sdbx_save_page_attributes_meta_box( $post_id ) {
	
	$post = get_post( $post_id );

	error_log($post->post_type);
	if ( $post->post_type == 'revision' ) 
		return;
		
	if ( 'page' === $post->post_type || 'post' === $post->post_type ) {
		
		$sdbx_page_redirect_child = $_POST['sdbx_page_redirect_child'];
		$sdbx_page_hide_page_title = $_POST['sdbx_page_hide_page_title'];
		$sdbx_page_hide_page_thumbnail = $_POST['sdbx_page_hide_page_thumbnail'];
		error_log($sdbx_page_hide_page_title);
		
		$is_sdbx_page_redirect_child = "0";
		if ( "on" === $sdbx_page_redirect_child )
			$is_sdbx_page_redirect_child = "1";		
				
		update_post_meta( $post->ID, '_sdbx_page_redirect_child', $is_sdbx_page_redirect_child );	
		
		$is_sdbx_page_hide_page_title = "0";
		if ( "on" === $sdbx_page_hide_page_title )
			$is_sdbx_page_hide_page_title = "1";		
				
		update_post_meta( $post->ID, '_sdbx_page_hide_page_title', $is_sdbx_page_hide_page_title );	
		
		$is_sdbx_page_hide_page_thumbnail = "0";
		if ( "on" === $sdbx_page_hide_page_thumbnail )
			$is_sdbx_page_hide_page_thumbnail = "1";		
				
		update_post_meta( $post->ID, '_sdbx_page_hide_page_thumbnail', $is_sdbx_page_hide_page_thumbnail );	
				
	}
			
}

function sdbx_save_page_editors_meta_box( $post_id ) {
	$post = get_post( $post_id );

	if ( $post->post_type == 'revision' ) 
		return;
		
	if ( 'page' === $post->post_type ) {
		
		$editors = sdbx_get_page_editors();
		
		foreach ( $editors as $editor ) {
			
			$editor = trim($editor);
			$editor = strtolower(preg_replace('/\s+/', '', $editor));
			$editor_content = $_REQUEST['textarea-' . $editor];
			update_post_meta( $post_id, '_sdbx_page_editor_content_' . $editor, $editor_content );
		}		
	}
}

function sdbx_get_post_templates() {

	  $themes = get_themes();
	  $theme = get_current_theme();
	  $templates = $themes[$theme]['Template Files'];
	  $post_templates = array();
	
	  if ( is_array( $templates ) ) {
	    $base = array( trailingslashit(get_template_directory()), trailingslashit(get_stylesheet_directory()) );
	
	    foreach ( $templates as $template ) {
	    
	      $basename = str_replace($base, '', $template);
	      if ($basename != 'functions.php') {
	      	
	        // don't allow template files in subdirectories
	        if ( false !== strpos($basename, '/') )
	          continue;
	        $template_data = implode( '', file( $template ));
	
	        $name = '';
	        if ( preg_match( '|Template Name:(.*)$|mi', $template_data, $name ) ) {
	        	$name = _cleanup_header_comment($name[1]);
	        }
	        
	        if ( !empty( $name ) ) {
	          $post_templates[trim( $name )] = $basename;
	        }
	      }
	    }
	  }
	
	  return $post_templates;
}

function sdbx_post_template_dropdown( $default = '' ) {
	  $templates = sdbx_get_post_templates();
	  ksort( $templates );
	  foreach (array_keys( $templates ) as $template )
	    : if ( $default == $templates[$template] )
	      $selected = " selected='selected'";
	    else
	      $selected = '';
	  echo "\n\t<option value='".$templates[$template]."' $selected>$template</option>";
	  endforeach;
}

function sdbx_get_page_template() {
	
	$post_id;
	
	if ( isset($_REQUEST['post']) )
		$post_id = $_REQUEST['post'];
	else
		$post_id = $_REQUEST['post_id'];	
	
	if ( !isset( $post_id ) )
		$post_id = $_POST['post_ID'] ;
	//$post_id = $_GET['post'] ? $_GET['post'] : $_POST['post_ID'] ;
	$template_file = get_post_meta($post_id,'_wp_page_template',TRUE);
	// check for a template type
	return $template_file;
}

function sdbx_save_template_meta_box( $post_id ) {

	$post = get_post( $post_id );

	if ( $post->post_type == 'revision') 
		return;
		
	if ( 'page' != $post->post_type && !empty($_POST['sdbx_post_template']) )
		update_post_meta ($post->ID, '_sdbx_post_template', $_POST['sdbx_post_template'] );
			
}

function sdbx_add_excerpts_to_pages() {
	add_post_type_support( 'page', 'excerpt' );
}

function sdbx_resize_footer() {
?>
<script type="text/javascript">
jQuery(document).ready( function() {
	jQuery(window).bind("resize", resizeFooter);	
	window.setTimeout ( resizeFooter, 1000 );
});
</script>
<?php
}


function sdbx_remove_menu_items() {
	  
	  global $menu, $submenu;
	  
	  // remove links and media from menu
	  $restricted = array(__('Links'), __('Media'), );
	  end ($menu);
	  while (prev($menu)){
	  	$value = explode(' ',$menu[key($menu)][0]);
	    if(in_array($value[0] != NULL ? $value[0] : "" , $restricted) ){
	    	unset($menu[key($menu)]);}
	  }
	  
	  // remove posts and comments if blog disabled
	  $sdbx_feature_blog = get_option( 'sdbx_feature_blog', '0' );
	  if ( "0" === $sdbx_feature_blog )
	  	sdbx_remove_menu_items_blog();
 	  
	  global $current_user;
      get_currentuserinfo();
      $role = $current_user->roles[0];  
      
	  // clean up menu for non administrators
      if ( 'administrator' !== $role )  {
      	  
		  $restricted = array(__('Links'), __('Comments'), __('Plugins'), __('Tools'), __('Users'));
		  end ($menu);
		  while (prev($menu)){
		  	$value = explode(' ',$menu[key($menu)][0]);
		    if(in_array($value[0] != NULL ? $value[0] : "" , $restricted) ){
		    	unset($menu[key($menu)]);}
		  }
		  
		  // remove add new pages for non-admins
		  unset($submenu['edit.php?post_type=page'][10]);
		  
		  // remove page attributes meta box
		  // remove_meta_box('pageparentdiv', 'page', 'normal');
	  }
	  
	// remove dashboard widgets
	remove_meta_box( 'dashboard_browser_nag', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_incoming_links', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_plugins', 'dashboard', 'normal' );
	
	remove_meta_box( 'dashboard_quick_press', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_recent_drafts', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_primary', 'dashboard', 'normal' );
	remove_meta_box( 'dashboard_secondary', 'dashboard', 'normal' );

	// remove user profile options
	remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
	add_action( 'personal_options', 'sdbx_remove_personal_options');
	
    
 }


function sdbx_remove_menu_items_blog() {
	global $menu;
	 $restricted = array(__('Posts'), __('Comments'));
	  end ($menu);
	  while (prev($menu)){
	  	$value = explode(' ',$menu[key($menu)][0]);
	    if(in_array($value[0] != NULL ? $value[0] : "" , $restricted) ){
	    	unset($menu[key($menu)]);}
	  }
}

function sdbx_remove_personal_options() {
	global $current_user;
    get_currentuserinfo();
    $role = $current_user->roles[0];  
      
	// clean up menu for non administrators
   
?>
<script type="text/javascript">
  jQuery(document).ready(function(){
    jQuery("#your-profile .form-table:first, #your-profile h3:first").remove();
    
    <?php  if ( 'administrator' !== $role ) : ?>
    var user_login = jQuery("#user_login").val();
    jQuery("#your-profile .form-table:first, #your-profile h3:first").remove();
   // jQuery("#your-profile .form-table:first, #your-profile h3:first").remove();
    jQuery("#your-profile h3:first").prepend("Update ");
    <?php endif; ?>
  });
</script>
<?php
}

function sdbx_admin_bar_menu( $wp_admin_bar ) {
	
	$user_id = get_current_user_id();
	$current_user = wp_get_current_user();
	$profile_url = get_edit_profile_url( $user_id );
	
	if ( 0 != $user_id ) {
		/* Add the "My Account" menu */
		$avatar = get_avatar( $user_id, 28 );
		$howdy = sprintf( __('%1$s'), $current_user->display_name );
		$class = empty( $avatar ) ? '' : 'with-avatar';
		
		$wp_admin_bar->add_menu( array(
		'id' => 'my-account',
		'parent' => 'top-secondary',
		'title' => $howdy . $avatar,
		'href' => $profile_url,
		'meta' => array(
		'class' => $class,
		),
		) );
	
	}
}

function sdbx_remove_plain_bio($buffer) {
	$titles = array('#<h3>About Yourself</h3>#','#<h3>About the user</h3>#');
	$buffer=preg_replace($titles,'<h3>Password</h3>',$buffer,1);
	$biotable='#<h3>Password</h3>.+?<table.+?/tr>#s';
	$buffer=preg_replace($biotable,'<h3>Password</h3> <table class="form-table">',$buffer,1);
	return $buffer;
}

function sdbx_profile_admin_buffer_start() { ob_start("sdbx_remove_plain_bio"); }

function sdbx_profile_admin_buffer_end() { ob_end_flush(); }

function sdbx_mfields_remove_gallery_setting_div() {
    print '
        <style type="text/css">
            #gallery-settings *{
            display:none;
            }
        </style>';
}
/*
 * FILTER HOOKS
 */
add_filter( 'admin_init' , 'sdbx_register_settings' );
add_filter( 'excerpt_length', 'sdbx_excerpt_length' );
add_filter( 'gallery_style',  'sdbx_remove_gallery_css' );
add_filter( 'show_admin_bar', 'sdbx_hide_admin_bar' );
add_filter( 'login_headerurl', 'sdbx_login_headerurl' );
add_filter( 'breadcrumb_trail_args', 'sdbx_breadcrumb_trail_args' );
add_filter( 'excerpt_more', 'sdbx_auto_excerpt_more' );
add_filter( 'get_the_excerpt', 'sdbx_custom_excerpt_more' );
add_filter( 'attachment_fields_to_edit', 'sdbx_attachment_fields_edit', 10, 2);
add_filter( 'attachment_fields_to_save', 'sdbx_attachment_fields_to_save', 10, 2);
add_filter( 'widget_text', 'do_shortcode' );
add_filter( 'widget_text', 'sdbx_execute_php' );
add_filter( 'template_include', 'sdbx_set_post_template' );
add_filter( 'wp_nav_menu', 'sdbx_add_last_item_class');
add_filter( 'screen_options_show_screen', 'sdbx_remove_screen_options' );
add_filter( 'help_options_show_screen', 'sdbx_remove_screen_options' );
add_filter( 'user_contactmethods', 'sdbx_hide_add_contact_info');
add_filter( 'get_archives_link', 'get_archives_link_mod' );
add_filter( 'wp_mail_content_type', 'set_html_content_type' );

function set_html_content_type()
{
	return 'text/html';
}

function get_archives_link_mod ( $link_html ) {
   preg_match ("/href='(.+?)'/", $link_html, $url);
   $requested = "http://" . trim($_SERVER['SERVER_NAME']) .  trim($_SERVER['REQUEST_URI']);
 
   if ($requested == trim($url[1])) {
       $link_html = str_replace("<li>", "<li class='current_page_item'>", $link_html);
   }
   return $link_html;
}
  
function sdbx_register_settings() {
	register_setting( 'general', 'phone_number', 'esc_attr' );
    add_settings_field('phone_number', '<label for="phone_number">'.__('Phone Number' , 'phone_number' ).'</label>' , 'sdbx_custom_field_phone_number' , 'general' );
    
    register_setting( 'general', 'fax_number', 'esc_attr' );
    add_settings_field('fax_number', '<label for="fax_number">'.__('Fax Number' , 'fax_number' ).'</label>' , 'sdbx_custom_field_fax_number' , 'general' );
    
    register_setting( 'general', 'address_1', 'esc_attr' );
    add_settings_field('address_1', '<label for="address_1">'.__('Address 1' , 'address_1' ).'</label>' , 'sdbx_custom_field_address_1' , 'general' );
    
    register_setting( 'general', 'address_2', 'esc_attr' );
    add_settings_field('address_2', '<label for="address_2">'.__('Address 2' , 'address_2' ).'</label>' , 'sdbx_custom_field_address_2' , 'general' );
    
	register_setting( 'general', 'city', 'esc_attr' );
    add_settings_field('city', '<label for="city">'.__('City' , 'city' ).'</label>' , 'sdbx_custom_field_city' , 'general' );
	
	register_setting( 'general', 'state', 'esc_attr' );
    add_settings_field('state', '<label for="state">'.__('State' , 'state' ).'</label>' , 'sdbx_custom_field_state' , 'general' );
	
	register_setting( 'general', 'zip', 'esc_attr' );
    add_settings_field('zip', '<label for="zip">'.__('Zip' , 'zip' ).'</label>' , 'sdbx_custom_field_zip' , 'general' );
	
}

function sdbx_custom_field_phone_number() {
    $value = get_option( 'phone_number', '' );
    echo '<input type="text" id="phone_number" name="phone_number" value="' . $value . '" />';
}

function sdbx_custom_field_fax_number() {
    $value = get_option( 'fax_number', '' );
    echo '<input type="text" id="fax_number" name="fax_number" value="' . $value . '" />';
}

function sdbx_custom_field_address_1() {
    $value = get_option( 'address_1', '' );
    echo '<input type="text" id="address_1" name="address_1" value="' . $value . '" />';
}

function sdbx_custom_field_address_2() {
    $value = get_option( 'address_2', '' );
    echo '<input type="text" id="address_2" name="address_2" value="' . $value . '" />';
}

function sdbx_custom_field_city() {
    $value = get_option( 'city', '' );
    echo '<input type="text" id="city" name="city" value="' . $value . '" />';
}

function sdbx_custom_field_state() {
    $value = get_option( 'state', '' );
    echo '<input type="text" id="state" name="state" value="' . $value . '" />';
}

function sdbx_custom_field_zip() {
    $value = get_option( 'zip', '' );
    echo '<input type="text" id="zip" name="zip" value="' . $value . '" />';
}
	
function sdbx_excerpt_length( $length ) {
	if ( !defined('SDBX_EXCERPT_LENGTH') )
		define( 'SDBX_EXCERPT_LENGTH', 40 );		
	return SDBX_EXCERPT_LENGTH;
}

function sdbx_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}

function sdbx_hide_admin_bar( $showhide ) {
	$show_admin_bar = false;
	return false;
}

function sdbx_login_headerurl(){
    return ('http://www.sdbxstudio.com'); 
}

function sdbx_breadcrumb_trail_args( $args ) {
	$args['before'] = false;
	$args['separator'] = '&raquo;';
	return $args;
}

function sdbx_auto_excerpt_more( $more ) {
	return ' &hellip;' . sdbx_continue_reading_link();
}

function sdbx_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'sdbx' ) . '</a>';
}

function sdbx_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= sdbx_continue_reading_link();
	}
	return $output;
}

// Add a slide custom field to an image attachment
function sdbx_attachment_fields_edit( $form_fields, $post ) {

	// 0. Add caption link custom field
	$form_fields["sdbx_caption_link"]["label"] = __("Caption Link");  
	$form_fields["sdbx_caption_link"]["input"] = "html";  
	$sdbx_caption_link = get_post_meta($post->ID, "_sdbx_caption_link", true); 
	
	$form_fields["sdbx_caption_link"]["html"] = "<input type='text' name='attachments[{$post->ID}][sdbx_caption_link]' id='attachments[{$post->ID}][sdbx_caption_link]' value='" . $sdbx_caption_link . "' />"; 

	// 1. Add slide custom field
	$form_fields["sdbx_slide"]["label"] = __("Slide");  
	$form_fields["sdbx_slide"]["input"] = "html";  
	$sdbx_slide = get_post_meta($post->ID, "_sdbx_slide", true); 

	$checked = "";
	if ($sdbx_slide) $checked = "checked";
	$form_fields["sdbx_slide"]["html"] = "<input type='checkbox' name='attachments[{$post->ID}][sdbx_slide]' id='attachments[{$post->ID}][sdbx_slide]' $checked />"; 
	
	// 2. Add document custom field
	$form_fields["sdbx_document"]["label"] = __("Document");  
	$form_fields["sdbx_document"]["input"] = "html";  
	$sdbx_document = get_post_meta($post->ID, "_sdbx_document", true); 

	$checked = "";
	if ($sdbx_document) $checked = "checked";
	$form_fields["sdbx_document"]["html"] = "<input type='checkbox' name='attachments[{$post->ID}][sdbx_document]' id='attachments[{$post->ID}][sdbx_document]' $checked />"; 
	
	// 3. Add thumbnail custom field
	$form_fields["sdbx_thumbnail"]["label"] = __("Thumbnail");  
	$form_fields["sdbx_thumbnail"]["input"] = "html";  
	$sdbx_thumbnail = get_post_meta($post->ID, "_sdbx_thumbnail", true); 

	$checked = "";
	if ($sdbx_thumbnail) $checked = "checked";
	$form_fields["sdbx_thumbnail"]["html"] = "<input type='checkbox' name='attachments[{$post->ID}][sdbx_thumbnail]' id='attachments[{$post->ID}][sdbx_thumbnail]' $checked />"; 
		
	return $form_fields;
}

// Save slide custom field to post meta
function sdbx_attachment_fields_to_save( $post, $attachment ) {

	// 0. Update image link field
	update_post_meta($post['ID'], '_sdbx_caption_link', $attachment['sdbx_caption_link']);		
	
	// 1. Update slide custom field
	$is_sdbx_slide = false;
	if ($attachment['sdbx_slide'] == "on")
		$is_sdbx_slide = true;		
			
	update_post_meta($post['ID'], '_sdbx_slide', $is_sdbx_slide);		
	
	// 2. Update document custom field
	$is_sdbx_document = false;
	if ($attachment['sdbx_document'] == "on")
		$is_sdbx_document = true;		
			
	update_post_meta($post['ID'], '_sdbx_document', $is_sdbx_document);		
	
	// 3. Update thumbnail custom field
	$is_sdbx_thumbnail = false;
	if ($attachment['sdbx_thumbnail'] == "on")
		$is_sdbx_thumbnail = true;		
			
	update_post_meta($post['ID'], '_sdbx_thumbnail', $is_sdbx_thumbnail);
	
	return $post;
}

function sdbx_execute_php( $html ){

     if(strpos($html,"<"."?php") !== false){
          ob_start();
          eval("?".">".$html);
          $html=ob_get_contents();
          ob_end_clean();
     }
     return $html;
}

function sdbx_set_post_template( $template ) {

	global $post;
	
	$path_parts = pathinfo($template);
	
	update_option ( 'sdbx_current_template', $path_parts['filename'] );
	
	$template = sdbx_get_post_template( $path_parts['filename'] );

    return $template;
}
	
function sdbx_get_post_template( $type ) {
 
 	global $post;
 	global $wp_query;
	
    $object = $wp_query->get_queried_object();

	$sdbx_post_template = '';
	
	if ( 'single' === $type ) {
		$sdbx_post_template_default = get_option( 'sdbx_post_template', '' );
		$sdbx_post_template = get_post_meta( $post->ID, '_sdbx_post_template', true );
		
		if ( 'single.php' === $sdbx_post_template && '' !== $sdbx_post_template_default )
			$sdbx_post_template = $sdbx_post_template_default;
	
	}
	else {
		$sdbx_post_template = get_option( 'sdbx_' . $type . '_template', '' );
	}
	
    $templates = array(  $sdbx_post_template,	    
					     $type . '-'. $object->post_type .'.php',
					     $type . '.php' );
/*
    $page_template = get_post_meta( $object->ID , '_wp_page_template', true );


    if ( !empty( $page_template ) ) {
        $templates = array( $page_template ) + $templates;
    }*/

    return locate_template( $templates );
}

function sdbx_get_page_templates( $type ) {
	
	$files = scandir( trailingslashit( CHILD_THEME_DIR ) );	
	$template_files = array();
	foreach ( $files as $file ) {
		if ( preg_match('/^' . $type . '/', $file) ) {
	        $file = strtolower($file) ; 
			$file_ext = split("[/\\.]", $file) ; 
			
			if ($file_ext[count($file_ext) - 1] === 'php') {
				$template_files[] = $file;
			}	
		}
    }	
	
	$files = scandir( trailingslashit( THEME_DIR ) );

	foreach ( $files as $file ) {
		if ( preg_match('/^' . $type . '/', $file) ) {
			
	        $file = strtolower($file) ; 
			$file_ext = split("[/\\.]", $file) ; 
			
			if ($file_ext[count($file_ext) - 1] === 'php' && !in_array( $file, $template_files) ) {
				$template_files[] = $file;
			}
		}	
	}	
	
	return $template_files;							    
}


function sdbx_snippet( $atts ) {
	ob_start();
	
	extract( shortcode_atts( array(
		'name' => 'default'
	), $atts ) );
	
	if ( file_exists( trailingslashit( get_stylesheet_directory() ) . "/snippets/{$name}.php" ) )
		include( trailingslashit( get_stylesheet_directory() ) . "/snippets/{$name}.php" );
	else if ( file_exists( trailingslashit( get_template_directory() ) . "snippets/{$name}.php" ) )
		include( trailingslashit( get_template_directory() ) . "/snippets/{$name}.php" );
	else
		echo "<strong>[sdbx_snippet name='{$name}'] does not exist.</strong>";
		
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}


function sdbx_thumbnail( $post_id ) {
	$images =& get_children( 'orderby=menu_order&order=asc&post_type=attachment&post_mime_type=image&post_parent=' . $post_id );

	$thumbnail_set = false;
	$thumbnail = NULL;
	foreach( (array) $images as $attachment_id => $attachment )
	{
		$sdbx_thumbnail = get_post_meta($attachment_id, "_sdbx_thumbnail", true);
		
	   if ( "1" === $sdbx_thumbnail ) {
		   $thumbnail = wp_get_attachment_image( $attachment_id, 'full', 0 );	
		   $thumbnail_set = true;
		   break;					 
	   }
	}
	return $thumbnail;
}

function sdbx_layout( $atts ) {
	ob_start();
	
	extract( shortcode_atts( array(
		'name' => 'default'
	), $atts ) );
	
	if ( file_exists( trailingslashit( get_stylesheet_directory() ) . "/layouts/{$name}.php" ) )
		include( trailingslashit( get_stylesheet_directory() ) . "/layouts/{$name}.php" );
	else if ( file_exists( trailingslashit( get_template_directory() ) . "/layouts/{$name}.php" ) )
		include( trailingslashit( get_template_directory() ) . "/layouts/{$name}.php" );
	else
		echo "<strong>[sdbx_layout name='{$name}'] does not exist.</strong>";
		
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}

function sdbx_meta_title() {
	
	global $post, $page, $paged;
	
	$sdbx_meta_title = get_post_meta( $post->ID, '_sdbx_meta_title', true );
	
	if ( '' !== $sdbx_meta_title ) {
		echo $sdbx_meta_title;
	}
	else {

		wp_title( '|', true, 'right' );
	
		// Add the blog name.
		bloginfo( 'name' );
	
		// Add the blog description for the home/front page.
		$site_description = get_bloginfo( 'description', 'display' );
		if ( $site_description && ( is_home() || is_front_page() ) )
			echo " | $site_description";
	
		// Add a page number if necessary:
		if ( $paged >= 2 || $page >= 2 )
			echo ' | ' . sprintf( __( 'Page %s', 'sdbx' ), max( $paged, $page ) );
	}
	
}

function sdbx_meta_data() {

	global $post;

	$sdbx_meta_description = get_post_meta( $post->ID, '_sdbx_meta_description', true );
	$sdbx_meta_keywords = get_post_meta( $post->ID, '_sdbx_meta_keywords', true );
	
	if ( '' != $sdbx_meta_description ) { 
		echo "<meta name='description' content='$sdbx_meta_description' />\r\n";
	}
	
	if ( '' != $sdbx_meta_keywords ) { 
		echo "<meta name='keywords' content='$sdbx_meta_keywords' />\r\n";
	}
	
	
	$protocol = strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === FALSE ? 'http' : 'https';
	$host     = $_SERVER['HTTP_HOST'];
	$request_uri   = $_SERVER['REQUEST_URI'];
	 
	$current_url = $protocol . '://' . $host . $request_uri;
	
	// Facebook open graph meta data
?><meta property="og:title" content="<?php echo $post->post_title; ?>" />
<meta property="og:type" content="website" />
<?php
$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
if ( trim($image[0]) !== "" )
	echo "<meta property='og:image' content='" . $image[0] . "'/>";
?>
<meta property="og:url" content="<?php echo $current_url; ?>" />
<meta property="og:description" content="<?php echo $sdbx_meta_description; ?>" />
<link rel="canonical" href="<?php echo get_permalink( $post->ID ); ?>" /><?php
	
}

function sdbx_get_js_includes() {
	$files = scandir( trailingslashit( CHILD_THEME_DIR ) . '/js/' );
	
	$js_includes = array();
	foreach ( $files as $file ) {
        $file = strtolower($file);		
		if ( preg_match('/include.js/', $file) && is_file( trailingslashit( CHILD_THEME_DIR ) . '/js/' . $file) ) {
			$js_includes[] = $file;
		}	
    }	
	return $js_includes;
}

function sdbx_get_css_includes() {
	$files = scandir( trailingslashit( CHILD_THEME_DIR ) . '/css/' );
	
	$css_includes = array();
	foreach ( $files as $file ) {
        $file = strtolower($file);		
		if ( preg_match('/include.css/', $file) && is_file( trailingslashit( CHILD_THEME_DIR ) . '/css/' . $file) ) {
			$css_includes[] = $file;
		}	
    }	
	
	return $css_includes;
}


function sdbx_add_last_item_class( $menuHTML ) {
	
	$last_items_ids  = array();

	// Get all custom menus
  	$menus = wp_get_nav_menus();

    // For each menu find last items
  	foreach ( $menus as $menu_maybe ) {

	    // Get items of specific menu
	    if ( $menu_items = wp_get_nav_menu_items($menu_maybe->term_id) ) {
	
	      $items = array();
			
		  if ( count($menu_items) > 0 ) {
		      foreach ( $menu_items as $menu_item ) {
		        $items[$menu_item->menu_item_parent][] .= $menu_item->ID;
		      }
		  }
	
	      // Find IDs of last items
	      foreach ( $items as $item ) {
	        $last_items_ids[] .= end($item);
	      }
	   }
	}

  $items_add_class = array();
  $replacement = array();
  foreach( $last_items_ids as $last_item_id ) {
    $items_add_class[] .= ' menu-item-'.$last_item_id;
    $replacement[]     .= ' menu-item-'.$last_item_id . ' last-menu-item';
  }

  $menuHTML = str_replace($items_add_class, $replacement, $menuHTML);
  return $menuHTML;

}

function sdbx_remove_screen_options(){
	global $current_user;
    get_currentuserinfo();
    $role = $current_user->roles[0];  

	// remove 'screen options' tab for non editors
    if ( 'administrator' === $role || 'editor' === $role )
		return true;
	else
		return false;
}

function sdbx_hide_add_contact_info( $contactmethods ) {
	unset($contactmethods['aim']);
	unset($contactmethods['jabber']);
	unset($contactmethods['yim']);

	return $contactmethods;
}

function sdbx_content( $name, $post_id = 0 ) {
	ob_start();
	global $post;
	
	$name = trim($name);
	$name = strtolower(preg_replace('/\s+/', '', $name));
	
  if ($post_id == 0)
    $post_id = $post->ID;
 
	$sdbx_page_editor_content = get_post_meta( $post_id, '_sdbx_page_editor_content_' . $name, true );
	echo wpautop( do_shortcode($sdbx_page_editor_content) );
	
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}

function sdbx_content_string( $name, $post_id = 0 ) {
 
  global $post;
  
  $name = trim($name);
  $name = strtolower(preg_replace('/\s+/', '', $name));
  
  if ($post_id == 0)
    $post_id = $post->ID;
 
  $sdbx_page_editor_content = get_post_meta( $post_id, '_sdbx_page_editor_content_' . $name, true );
  
  $content =  wpautop( do_shortcode($sdbx_page_editor_content) );
  
  return $content;
}

function sdbx_post_thumbnail( $name ) {
	ob_start();
	global $post;
	
	$name = trim($name);
	$name = strtolower(preg_replace('/\s+/', '', $name));
	$name = "_sdbx_" . $post->ID . "_" . $name . '_thumbnail_id';
	
	$sidebar_thumb_id = get_post_meta( $post->ID, $name, true );
	
	echo wp_get_attachment_image( $sidebar_thumb_id, 'thumbnail', 0 );	
	
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}

?>
