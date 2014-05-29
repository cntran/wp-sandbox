<?php
require_once( 'ajax/ajax.php' );
add_action ( 'init', 'sdbx_init' );
add_action( 'admin_menu', 'sdbx_local_remove_menu_items');
add_action( 'wp_head', 'sandbox_load_references');

function sandbox_load_references() {
	?>
	<script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/bootstrap.min.js"></script>
	<?php
}


function sandbox_blogmenu() {
	
	// DISPLAY BLOG HEADING
	echo "<div class='page-nav-header ";
  	$requested = "http://" . trim($_SERVER['SERVER_NAME']) .  trim($_SERVER['REQUEST_URI']);
	if ( $requested == get_bloginfo('url') . "/blog/") {
		echo "page-nav-header-current_page_item";
	}
	echo "'>";
	echo "<h2><a href='" . get_bloginfo('url') . "/blog/'>Blog</a></h2>";
	echo "</div>";
	
	// DISPLAY CATEGORIES
	echo "<div class='page-nav'>";
	echo "<ul class='nav blog-nav'>";
	echo "<li class='heading'><h3>CATEGORIES</h3></li>";
	echo wp_list_categories('orderby=name&show_count=1&title_li=');
	echo "</ul>"; 
	echo "</div>";
	
	// DISPLAY ARCHIVES
	$args = array('type'            => 'monthly',
				  'format'          => 'html', 
				  'before'          => '',
				  'after'           => '',
				  'show_post_count' => false,
				  'echo'            => 0
		);
	$archives = wp_get_archives( $args );
	
	echo "<div class='page-nav'>";
	echo "<ul class='nav blog-nav'>";
	echo "<li class='heading'><h3>ARCHIVES</h3></li>";
	echo $archives;
	echo "</ul>";
	echo "</div>";
}

function sdbx_init() {

}

function sdbx_local_remove_menu_items() {
	  global $current_user;
      get_currentuserinfo();
      $role = $current_user->roles[0];  
	
	  if ( 'administrator' !== $role ) {
	  	  remove_meta_box( 'pageparentdiv', 'page', 'side' ); 
	  }
}




?>