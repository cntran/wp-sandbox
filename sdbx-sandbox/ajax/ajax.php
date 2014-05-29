<?php

/*
 * Ajax Call stub
 */
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

?>