<?php
/*
 * Framework Action Hooks
 */

function sdbx_before_main() {
	do_action( 'sdbx_before_main' );
}
 
function sdbx_before_container() {
	do_action( 'sdbx_before_container' );
}

function sdbx_before_title() {
	do_action( 'sdbx_before_title' );
}

function sdbx_after_title() {
	do_action( 'sdbx_after_title' );
}

function sdbx_before_post_content() {
	do_action( 'sdbx_before_post_content' );
}

function sdbx_after_post_content() {
	do_action( 'sdbx_after_post_content' );
}

function sdbx_before_content() {
	do_action( 'sdbx_before_content' );
}
  
function sdbx_after_content() {
	do_action( 'sdbx_after_content' );
}

function sdbx_before_primary_content() {
	do_action( 'sdbx_before_primary_content' );
}

function sdbx_before_header_image_caption() {
	do_action( 'sdbx_before_header_image_caption' );
}

?>