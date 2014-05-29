<?php
add_action( 'init', 'sdbx_ajax_init' );

function sdbx_ajax_init() {	
	wp_enqueue_script( 'sdbx-ajax-request', get_template_directory_uri() . '/library/functions/ajax/ajax.js' );
	wp_localize_script( 'sdbx-ajax-request', 'SdbxAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'ajaxNonce' => wp_create_nonce('sdbxajax-nonce') ) );
	
	// queue child theme ajax
	if ( file_exists( get_stylesheet_directory_uri() . '/ajax/ajax.js' ) ) 
		wp_enqueue_script( 'sdbx-ajax-request-child', get_stylesheet_directory_uri() . '/ajax/ajax.js' );
}

/*
 * Ajax Call stub
 */
/*
add_action( 'wp_ajax_sdbx-ajax-call', 'sdbx_ajax_call' );
add_action( 'wp_ajax_nopriv_sdbx-ajax-call', 'sdbx_ajax_call');
function sdbx_ajax_call() {
		
	$nonce = $_POST['ajaxNonce'];
	
	if ( ! wp_verify_nonce( $nonce, 'sdbxajax-nonce' ) )
        die ();			
	
	// Get form information
	$post_var = $_POST['post_var'];
	
	// Do something...
	
	
	$response = json_encode( array( 'success' => true ));
	
	header( "Content-Type: application/json" );
	echo $response;
	
	exit;
}
*/

/*
 * Update Option Ajax Call
 */
add_action( 'wp_ajax_sdbx-update-option', 'sdbx_update_option' );
add_action( 'wp_ajax_nopriv_sdbx-update-option', 'sdbx_update_option' );
function sdbx_update_option() {
		
	$nonce = $_POST['ajaxNonce'];
	
	if ( ! wp_verify_nonce( $nonce, 'sdbxajax-nonce' ) )
        die ();			
	
	// Get form information
	$option_name = $_POST['option_name'];
	$option_value = $_POST['option_value'];
	
	// Do something...
	update_option( $option_name, $option_value );
	
	$response = json_encode( array( 'success' => true ));
	
	header( "Content-Type: application/json" );
	echo $response;
	
	exit;
}

?>