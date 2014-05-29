<?php
function sdbx_get_featured_image($post_id = 0) {
  global $post;
  if ($post_id == 0)
    $post_id = $post->ID;
  
  
  $post_root = get_post($post_id);
  $count = 0;
  
  $featured_image_url = wp_get_attachment_url( get_post_thumbnail_id( $post_root->ID ) );
  
  if ($featured_image_url == false) {
    while ($post_root->post_parent != 0 && $count < 10) {     
      $post_root = get_post($post_root->post_parent);
      $count++;   
    }
    
    $featured_image_url = wp_get_attachment_url( get_post_thumbnail_id( $post_root->ID ) );
  }
  
  
  if ($featured_image_url === false) {
    if (file_exists(get_stylesheet_directory() . '/images/featuredimage-default.jpg')) {
      $featured_image_url = get_stylesheet_directory_uri() . '/images/featuredimage-default.jpg';
    }
  }
  
  return $featured_image_url;
}

function sdbx_get_featured_slides() {
	global $wpdb;
	$querystr = "SELECT p.* 
		             FROM $wpdb->posts p
		             JOIN ( SELECT wposts.* 
				     		FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
				     		WHERE wposts.ID = wpostmeta.post_id 
				     		AND wpostmeta.meta_key = '_sdbx_featured_slide_thumbnail_id'
				     		AND wposts.post_status = 'publish' 
				     		AND wpostmeta.meta_value != '' ) sp ON p.ID = sp.ID
				     JOIN $wpdb->postmeta pm ON sp.ID = pm.post_id
				     WHERE pm.meta_key = '_sdbx_featured_slide_order'
				     ORDER BY pm.meta_value";
						
	$featured = $wpdb->get_results($querystr, OBJECT);
	$slides = array();
	foreach ($featured as $feature) {
		$slides[] = $feature;
	}
	return $slides;
	
}

function sdbx_get_page_slides( $page_id = 0 ) {

	global $post;
	if ( $page_id == 0 )
		$page_id = $post->ID;
	
	$images =& get_children( 'orderby=menu_order&order=asc&post_type=attachment&post_mime_type=image&post_parent=' . $page_id );	

	$slides = array();
	foreach( (array) $images as $attachment_id => $attachment )
	{
	   $sdbx_slide = get_post_meta($attachment_id, "_sdbx_slide", true);
		
	   if ( "1" === $sdbx_slide ) {
	   		$slides[] = $attachment;	 
	   }
	}
	return $slides;
}

function sdbx_get_page_images( $page_id = 0 ) {

  global $post;
  if ( $page_id == 0 )
    $page_id = $post->ID;
  
  $images =& get_children( 'orderby=menu_order&order=asc&post_type=attachment&post_mime_type=image&post_parent=' . $page_id );  

  $slides = array();
  foreach( (array) $images as $attachment_id => $attachment )
  {
     $slides[] = $attachment;   
  }
  return $slides;
}

function sdbx_document_list( $atts ) {

	extract( shortcode_atts( array(
		'header' => 'Documents'
	), $atts ) );
	
	global $post;
	
	$attachments =& get_children( 'orderby=menu_order&order=asc&post_type=attachment&post_parent=' . $post->ID );
	
	$document_list = '';
	$counter = 0;
	foreach( (array) $attachments as $attachment_id => $attachment )
	{
		$sdbx_document = get_post_meta($attachment_id, "_sdbx_document", true);
		if ( "1" === $sdbx_document )
			$counter++;
	}
	if ($counter > 0) {
	
		$document_list .= "<h2>{$header}</h2>";
	
		$counter = 0;
		$document_list .= "<ul class='sdbx-document-list'>";
		foreach( (array) $attachments as $attachment_id => $attachment )
		{
			$sdbx_document = get_post_meta($attachment_id, "_sdbx_document", true);
			if ( "1" === $sdbx_document ) {
				$document_list .= "<li><a href='" . wp_get_attachment_url( $attachment_id ) . "' target='_blank'>" . $attachment->post_title . "</a></li>";
				$counter++;
			}
		}
		$document_list .= "</ul>";
		
	}
	
	return $document_list;
}

function sdbx_subpages($post_id = 0) {
  global $post, $wpdb;

  if ($post_id == 0) {
    $post_id = $post->ID;
  }
  $querystr = "SELECT wposts.* 
           FROM $wpdb->posts wposts
           WHERE wposts.post_parent = " . $post_id. " 
           AND wposts.post_status = 'publish' 
           AND wposts.post_type = 'page'
           ORDER BY wposts.menu_order ASC";
  
  $child_pages = $wpdb->get_results($querystr, OBJECT);
  
  return $child_pages;
}

function sdbx_blog( ) {
	
	ob_start();
	if ( file_exists(  CHILD_THEME_DIR . '/content-blog.php' ) )
	 include_once( trailingslashit( CHILD_THEME_DIR ) . 'content-blog.php' );  
  else
	 include_once( trailingslashit( THEME_DIR ) . 'content-blog.php' );	
	
	$output_string = ob_get_contents();;
	ob_end_clean();
	return $output_string;
	
}

function sdbx_login() {
	
	ob_start();

	global $post;
	if ( is_user_logged_in() ) {
	?>
		<a href="<?php echo wp_logout_url( get_permalink() ); ?>">logout</a><br />		
		<a href="<?php echo get_bloginfo('url'); ?>/wp-admin/" ?>admin</a>
	<?php									
	}
	else {
	?>							
		<form action="<?php echo get_option('home'); ?>/wp-login.php?redirect_to=<?php echo get_permalink($post->ID); ?>" method="post">
			
			<table class="table">
				<tr><td width="80">Username</td><td><input type="text" name="log" id="log" value="<?php echo wp_specialchars(stripslashes($user_login), 1) ?>" size="20" /></td></tr>
				<tr>
					<td>Password</td>
					<td>
						<input type="password" name="pwd" id="pwd" size="20" />&nbsp;
						<input type="submit" name="submit" value="Login" class="button" />
					</td>
				</tr>
			</table>
		    <p>
		       <label for="rememberme"><input name="rememberme" id="rememberme" type="checkbox" value="forever" /> Remember me</label>
		       <input type="hidden" name="redirect_to" value="<?php echo get_permalink($post->ID); ?>" />
		    </p>
		</form>			
	<?php					
	}
	
	$output_string = ob_get_contents();;
	ob_end_clean();
	return $output_string;
}


function sdbx_page_navigation( $atts ) {
				
		global $post;
		
		if ( "" === $atts['depth'] || !isset($atts['depth']) )
			$atts['depth'] = 0;
		
		if ( "" === $atts['show_header'] || !isset($atts['show_header']) )
			$atts['show_header'] = 0;
		
		if ( "page" === $post->post_type ) {
					
			$post_root = get_post($post->ID);	
		
			$nav_header = $post->post_title;
			$count = 0;
			while ($post_root->post_parent != 0 && $count < 10) {				
				$post_root = get_post($post_root->post_parent);
				$nav_header = $post_root->post_title;	
				$count++;		
			}
						
			$args = array('depth'        => $atts['depth'],
						  'show_date'    => '',
						  'date_format'  => get_option('date_format'),
						  'child_of'     => $post_root->ID,
						  'exclude'      => '',
						  'include'      => '',
						  'title_li'     => __(''),
						  'echo'         => 0,
						  'authors'      => '',
						  'sort_column'  => 'menu_order, post_title',
						  'link_before'  => '',
						  'link_after'   => '',
						  'walker' => '' ); 
	
			$page_navigation = wp_list_pages( $args );
			
			if ( "" !== $atts['header'] && isset($atts['header']) ) {
				$nav_header = $atts['header'];
			}

			if ( $atts['show_header'] == 1) {
				echo "<div class='page-nav-header";
				if ( $post_root->ID === $post->ID )
					echo " page-nav-header-current_page_item";
				echo "'><h2><a class='text' href='" . get_permalink($post_root->ID) . "'>" . $nav_header . "</a></h2></div>";	
			}
				
			if ( "" !== $page_navigation ) {
				echo "<div class='page-nav'>";
				echo "<ul class='nav'>";												
				echo $page_navigation;															
				echo "</ul>";
				echo "</div>";	
			}
		
		}
				
}


function sdbx_pagination( $current_page, $page_count ) {
	
	if ($current_page == 0) $current_page = 1;
	
	if( $page_count > 1) { ?>
	    <ul class="pagination">
		    <?php
		      if ($current_page > 1) { ?>
		        <li><a href="<?php echo '?paged=' . ($current_page -1); //prev link ?>">Prev</a></li>
		                        <?php }
		    for( $i = 1; $i <= $page_count; $i++){?>
		        <li <?php echo ($current_page==$i)? 'class="active"':'';?>><a href="<?php echo '?paged=' . $i; ?>"><?php echo $i;?></a></li>
		        <?php
		    }
		    if($current_page < $page_count){?>
		        <li><a href="<?php echo '?paged=' . ($current_page + 1); //next link ?>">Next</a></li>
		    <?php } ?>
	    </ul>
		<?php 
	}
}

function sdbx_site_map() {
	return '<ul class="site-map">' . wp_list_pages( array( 'title_li' => false, 'echo' => 0 ) ) . '</ul>';
}


function sdbx_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">', 'sdbx' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			sprintf( esc_attr__( 'View all posts by %s', 'sdbx' ), get_the_author() ),
			get_the_author()
		)
	);
}

function sdbx_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'sdbx' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'sdbx' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'sdbx' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}

function sdbx_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">says:</span>', 'sdbx' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em><?php _e( 'Your comment is awaiting moderation.', 'sdbx' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'sdbx' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'sdbx' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'sdbx' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __('(Edit)', 'sdbx'), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}


?>