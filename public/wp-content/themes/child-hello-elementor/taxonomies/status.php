<?php

/**
 * Registers the `status` taxonomy,
 * for use with 'lu_orders'.
 */
function status_init() {
	register_taxonomy( 'status', array( 'lu_orders' ), array(
		'hierarchical'      => false,
		'public'            => true,
		'show_in_nav_menus' => true,
		'show_ui'           => true,
		'show_admin_column' => false,
		'query_var'         => true,
		'rewrite'           => true,
		'capabilities'      => array(
			'manage_terms'  => 'edit_posts',
			'edit_terms'    => 'edit_posts',
			'delete_terms'  => 'edit_posts',
			'assign_terms'  => 'edit_posts',
		),
		'labels'            => array(
			'name'                       => __( 'Statuses', 'child-hello-elementor' ),
			'singular_name'              => _x( 'Status', 'taxonomy general name', 'child-hello-elementor' ),
			'search_items'               => __( 'Search Statuses', 'child-hello-elementor' ),
			'popular_items'              => __( 'Popular Statuses', 'child-hello-elementor' ),
			'all_items'                  => __( 'All Statuses', 'child-hello-elementor' ),
			'parent_item'                => __( 'Parent Status', 'child-hello-elementor' ),
			'parent_item_colon'          => __( 'Parent Status:', 'child-hello-elementor' ),
			'edit_item'                  => __( 'Edit Status', 'child-hello-elementor' ),
			'update_item'                => __( 'Update Status', 'child-hello-elementor' ),
			'view_item'                  => __( 'View Status', 'child-hello-elementor' ),
			'add_new_item'               => __( 'Add New Status', 'child-hello-elementor' ),
			'new_item_name'              => __( 'New Status', 'child-hello-elementor' ),
			'separate_items_with_commas' => __( 'Separate statuses with commas', 'child-hello-elementor' ),
			'add_or_remove_items'        => __( 'Add or remove statuses', 'child-hello-elementor' ),
			'choose_from_most_used'      => __( 'Choose from the most used statuses', 'child-hello-elementor' ),
			'not_found'                  => __( 'No statuses found.', 'child-hello-elementor' ),
			'no_terms'                   => __( 'No statuses', 'child-hello-elementor' ),
			'menu_name'                  => __( 'Statuses', 'child-hello-elementor' ),
			'items_list_navigation'      => __( 'Statuses list navigation', 'child-hello-elementor' ),
			'items_list'                 => __( 'Statuses list', 'child-hello-elementor' ),
			'most_used'                  => _x( 'Most Used', 'status', 'child-hello-elementor' ),
			'back_to_items'              => __( '&larr; Back to Statuses', 'child-hello-elementor' ),
		),
		'show_in_rest'      => true,
		'rest_base'         => 'luorder_status',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
	) );

}
add_action( 'init', 'status_init' );

/**
 * Sets the post updated messages for the `status` taxonomy.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `status` taxonomy.
 */
function status_updated_messages( $messages ) {

	$messages['status'] = array(
		0 => '', // Unused. Messages start at index 1.
		1 => __( 'Status added.', 'child-hello-elementor' ),
		2 => __( 'Status deleted.', 'child-hello-elementor' ),
		3 => __( 'Status updated.', 'child-hello-elementor' ),
		4 => __( 'Status not added.', 'child-hello-elementor' ),
		5 => __( 'Status not updated.', 'child-hello-elementor' ),
		6 => __( 'Statuses deleted.', 'child-hello-elementor' ),
	);

	return $messages;
}
add_filter( 'term_updated_messages', 'status_updated_messages' );
