<?php

/**
 * Custom the Registration Process
 */


// Always give the parameter ?finish_register=true while not finish the registration
add_action('template_redirect', 'lu_add_query_parms_registration', 1);
function lu_add_query_parms_registration(){

	global $post;
	// 5312 - The "Go" page that takes all the dashboard pages as children.
	if( $post->post_parent == 5312 ) {
		$user = wp_get_current_user();
		if( !$user->first_name ) {
			if( $_GET['finish_register'] ) return;
			header( 'location: ' . get_permalink() . '?finish_register=true');
			// wp_safe_redirect( get_permalink().'?finish_register=true'); exit;
			exit();
		} elseif( $_GET['finish_register'] ) {
			header( 'location: ' . get_permalink() );
			// wp_safe_redirect( get_permalink().'?finish_register=true'); exit;
			exit();
		}
	}
}
