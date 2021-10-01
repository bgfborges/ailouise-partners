<?php

/**
 * Registers the `lu_orders` post type.
 */
function lu_orders_init() {
	register_post_type( 'lu_orders', array(
		'labels'                => array(
			'name'                  => __( 'Lu Orders', 'child-hello-elementor' ),
			'singular_name'         => __( 'Lu Order', 'child-hello-elementor' ),
			'all_items'             => __( 'All Lu Orders', 'child-hello-elementor' ),
			'archives'              => __( 'Lu Order Archives', 'child-hello-elementor' ),
			'attributes'            => __( 'Lu Order Attributes', 'child-hello-elementor' ),
			'insert_into_item'      => __( 'Insert into Lu Order', 'child-hello-elementor' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Lu Order', 'child-hello-elementor' ),
			'featured_image'        => _x( 'Featured Image', 'lu_orders', 'child-hello-elementor' ),
			'set_featured_image'    => _x( 'Set featured image', 'lu_orders', 'child-hello-elementor' ),
			'remove_featured_image' => _x( 'Remove featured image', 'lu_orders', 'child-hello-elementor' ),
			'use_featured_image'    => _x( 'Use as featured image', 'lu_orders', 'child-hello-elementor' ),
			'filter_items_list'     => __( 'Filter Lu Orders list', 'child-hello-elementor' ),
			'items_list_navigation' => __( 'Lu Orders list navigation', 'child-hello-elementor' ),
			'items_list'            => __( 'Lu Orders list', 'child-hello-elementor' ),
			'new_item'              => __( 'New Lu Order', 'child-hello-elementor' ),
			'add_new'               => __( 'Add New', 'child-hello-elementor' ),
			'add_new_item'          => __( 'Add New Lu Order', 'child-hello-elementor' ),
			'edit_item'             => __( 'Edit Lu Order', 'child-hello-elementor' ),
			'view_item'             => __( 'View Lu Order', 'child-hello-elementor' ),
			'view_items'            => __( 'View Lu Orders', 'child-hello-elementor' ),
			'search_items'          => __( 'Search Lu Orders', 'child-hello-elementor' ),
			'not_found'             => __( 'No Lu Orders found', 'child-hello-elementor' ),
			'not_found_in_trash'    => __( 'No Lu Orders found in trash', 'child-hello-elementor' ),
			'parent_item_colon'     => __( 'Parent Lu Order:', 'child-hello-elementor' ),
			'menu_name'             => __( 'Lu Orders', 'child-hello-elementor' ),
		),
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_nav_menus'     => true,
		'supports'              => array( 'title' ),
		'has_archive'           => true,
		'rewrite'               => true,
		'query_var'             => true,
		'menu_position'         => null,
		'menu_icon'             => 'dashicons-admin-post',
		'show_in_rest'          => true,
		'rest_base'             => 'lu_orders',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'lu_orders_init' );

/**
 * Sets the post updated messages for the `lu_orders` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `lu_orders` post type.
 */
function lu_orders_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['lu_orders'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Lu Order updated. <a target="_blank" href="%s">View Lu Order</a>', 'child-hello-elementor' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'child-hello-elementor' ),
		3  => __( 'Custom field deleted.', 'child-hello-elementor' ),
		4  => __( 'Lu Order updated.', 'child-hello-elementor' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Lu Order restored to revision from %s', 'child-hello-elementor' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Lu Order published. <a href="%s">View Lu Order</a>', 'child-hello-elementor' ), esc_url( $permalink ) ),
		7  => __( 'Lu Order saved.', 'child-hello-elementor' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Lu Order submitted. <a target="_blank" href="%s">Preview Lu Order</a>', 'child-hello-elementor' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Lu Order scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Lu Order</a>', 'child-hello-elementor' ),
		date_i18n( __( 'M j, Y @ G:i', 'child-hello-elementor' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Lu Order draft updated. <a target="_blank" href="%s">Preview Lu Order</a>', 'child-hello-elementor' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'lu_orders_updated_messages' );
