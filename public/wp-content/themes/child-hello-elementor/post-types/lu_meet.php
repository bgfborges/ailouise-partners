<?php

/**
 * Registers the `lu_meet` post type.
 */
function lu_meet_init() {
	register_post_type( 'lu_meet', array(
		'labels'                => array(
			'name'                  => __( 'Meets', 'child-hello-elementor' ),
			'singular_name'         => __( 'Meet', 'child-hello-elementor' ),
			'all_items'             => __( 'All Meets', 'child-hello-elementor' ),
			'archives'              => __( 'Meet Archives', 'child-hello-elementor' ),
			'attributes'            => __( 'Meet Attributes', 'child-hello-elementor' ),
			'insert_into_item'      => __( 'Insert into Meet', 'child-hello-elementor' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Meet', 'child-hello-elementor' ),
			'featured_image'        => _x( 'Featured Image', 'lu_meet', 'child-hello-elementor' ),
			'set_featured_image'    => _x( 'Set featured image', 'lu_meet', 'child-hello-elementor' ),
			'remove_featured_image' => _x( 'Remove featured image', 'lu_meet', 'child-hello-elementor' ),
			'use_featured_image'    => _x( 'Use as featured image', 'lu_meet', 'child-hello-elementor' ),
			'filter_items_list'     => __( 'Filter Meets list', 'child-hello-elementor' ),
			'items_list_navigation' => __( 'Meets list navigation', 'child-hello-elementor' ),
			'items_list'            => __( 'Meets list', 'child-hello-elementor' ),
			'new_item'              => __( 'New Meet', 'child-hello-elementor' ),
			'add_new'               => __( 'Add New', 'child-hello-elementor' ),
			'add_new_item'          => __( 'Add New Meet', 'child-hello-elementor' ),
			'edit_item'             => __( 'Edit Meet', 'child-hello-elementor' ),
			'view_item'             => __( 'View Meet', 'child-hello-elementor' ),
			'view_items'            => __( 'View Meets', 'child-hello-elementor' ),
			'search_items'          => __( 'Search Meets', 'child-hello-elementor' ),
			'not_found'             => __( 'No Meets found', 'child-hello-elementor' ),
			'not_found_in_trash'    => __( 'No Meets found in trash', 'child-hello-elementor' ),
			'parent_item_colon'     => __( 'Parent Meet:', 'child-hello-elementor' ),
			'menu_name'             => __( 'Meets', 'child-hello-elementor' ),
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
		'rest_base'             => 'lu_meet',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'lu_meet_init' );

/**
 * Sets the post updated messages for the `lu_meet` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `lu_meet` post type.
 */
function lu_meet_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['lu_meet'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Meet updated. <a target="_blank" href="%s">View Meet</a>', 'child-hello-elementor' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'child-hello-elementor' ),
		3  => __( 'Custom field deleted.', 'child-hello-elementor' ),
		4  => __( 'Meet updated.', 'child-hello-elementor' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Meet restored to revision from %s', 'child-hello-elementor' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Meet published. <a href="%s">View Meet</a>', 'child-hello-elementor' ), esc_url( $permalink ) ),
		7  => __( 'Meet saved.', 'child-hello-elementor' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Meet submitted. <a target="_blank" href="%s">Preview Meet</a>', 'child-hello-elementor' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Meet scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Meet</a>', 'child-hello-elementor' ),
		date_i18n( __( 'M j, Y @ G:i', 'child-hello-elementor' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Meet draft updated. <a target="_blank" href="%s">Preview Meet</a>', 'child-hello-elementor' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'lu_meet_updated_messages' );
