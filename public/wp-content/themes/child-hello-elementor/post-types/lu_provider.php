<?php

/**
 * Registers the `lu_provider` post type.
 */
function lu_provider_init() {
	register_post_type( 'lu_provider', array(
		'labels'                => array(
			'name'                  => __( 'Providers', 'child-hello-elementor' ),
			'singular_name'         => __( 'Provider', 'child-hello-elementor' ),
			'all_items'             => __( 'All Providers', 'child-hello-elementor' ),
			'archives'              => __( 'Provider Archives', 'child-hello-elementor' ),
			'attributes'            => __( 'Provider Attributes', 'child-hello-elementor' ),
			'insert_into_item'      => __( 'Insert into Provider', 'child-hello-elementor' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Provider', 'child-hello-elementor' ),
			'featured_image'        => _x( 'Featured Image', 'lu_provider', 'child-hello-elementor' ),
			'set_featured_image'    => _x( 'Set featured image', 'lu_provider', 'child-hello-elementor' ),
			'remove_featured_image' => _x( 'Remove featured image', 'lu_provider', 'child-hello-elementor' ),
			'use_featured_image'    => _x( 'Use as featured image', 'lu_provider', 'child-hello-elementor' ),
			'filter_items_list'     => __( 'Filter Providers list', 'child-hello-elementor' ),
			'items_list_navigation' => __( 'Providers list navigation', 'child-hello-elementor' ),
			'items_list'            => __( 'Providers list', 'child-hello-elementor' ),
			'new_item'              => __( 'New Provider', 'child-hello-elementor' ),
			'add_new'               => __( 'Add New', 'child-hello-elementor' ),
			'add_new_item'          => __( 'Add New Provider', 'child-hello-elementor' ),
			'edit_item'             => __( 'Edit Provider', 'child-hello-elementor' ),
			'view_item'             => __( 'View Provider', 'child-hello-elementor' ),
			'view_items'            => __( 'View Providers', 'child-hello-elementor' ),
			'search_items'          => __( 'Search Providers', 'child-hello-elementor' ),
			'not_found'             => __( 'No Providers found', 'child-hello-elementor' ),
			'not_found_in_trash'    => __( 'No Providers found in trash', 'child-hello-elementor' ),
			'parent_item_colon'     => __( 'Parent Provider:', 'child-hello-elementor' ),
			'menu_name'             => __( 'Providers', 'child-hello-elementor' ),
		),
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_nav_menus'     => true,
		'supports'              => array( 'title', 'editor' ),
		'has_archive'           => true,
		'rewrite'               => true,
		'query_var'             => true,
		'menu_position'         => null,
		'menu_icon'             => 'dashicons-admin-post',
		'show_in_rest'          => true,
		'rest_base'             => 'lu_provider',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'lu_provider_init' );

/**
 * Sets the post updated messages for the `lu_provider` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `lu_provider` post type.
 */
function lu_provider_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['lu_provider'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Provider updated. <a target="_blank" href="%s">View Provider</a>', 'child-hello-elementor' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'child-hello-elementor' ),
		3  => __( 'Custom field deleted.', 'child-hello-elementor' ),
		4  => __( 'Provider updated.', 'child-hello-elementor' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Provider restored to revision from %s', 'child-hello-elementor' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Provider published. <a href="%s">View Provider</a>', 'child-hello-elementor' ), esc_url( $permalink ) ),
		7  => __( 'Provider saved.', 'child-hello-elementor' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Provider submitted. <a target="_blank" href="%s">Preview Provider</a>', 'child-hello-elementor' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Provider scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Provider</a>', 'child-hello-elementor' ),
		date_i18n( __( 'M j, Y @ G:i', 'child-hello-elementor' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Provider draft updated. <a target="_blank" href="%s">Preview Provider</a>', 'child-hello-elementor' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'lu_provider_updated_messages' );
