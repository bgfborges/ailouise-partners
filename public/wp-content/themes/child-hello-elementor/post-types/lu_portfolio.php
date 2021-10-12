<?php

/**
 * Registers the `lu_portfolio` post type.
 */
function lu_portfolio_init() {
	register_post_type( 'lu_portfolio', array(
		'labels'                => array(
			'name'                  => __( 'Portfolios', 'child-hello-elementor' ),
			'singular_name'         => __( 'Portfolio', 'child-hello-elementor' ),
			'all_items'             => __( 'All Portfolios', 'child-hello-elementor' ),
			'archives'              => __( 'Portfolio Archives', 'child-hello-elementor' ),
			'attributes'            => __( 'Portfolio Attributes', 'child-hello-elementor' ),
			'insert_into_item'      => __( 'Insert into Portfolio', 'child-hello-elementor' ),
			'uploaded_to_this_item' => __( 'Uploaded to this Portfolio', 'child-hello-elementor' ),
			'featured_image'        => _x( 'Featured Image', 'lu_portfolio', 'child-hello-elementor' ),
			'set_featured_image'    => _x( 'Set featured image', 'lu_portfolio', 'child-hello-elementor' ),
			'remove_featured_image' => _x( 'Remove featured image', 'lu_portfolio', 'child-hello-elementor' ),
			'use_featured_image'    => _x( 'Use as featured image', 'lu_portfolio', 'child-hello-elementor' ),
			'filter_items_list'     => __( 'Filter Portfolios list', 'child-hello-elementor' ),
			'items_list_navigation' => __( 'Portfolios list navigation', 'child-hello-elementor' ),
			'items_list'            => __( 'Portfolios list', 'child-hello-elementor' ),
			'new_item'              => __( 'New Portfolio', 'child-hello-elementor' ),
			'add_new'               => __( 'Add New', 'child-hello-elementor' ),
			'add_new_item'          => __( 'Add New Portfolio', 'child-hello-elementor' ),
			'edit_item'             => __( 'Edit Portfolio', 'child-hello-elementor' ),
			'view_item'             => __( 'View Portfolio', 'child-hello-elementor' ),
			'view_items'            => __( 'View Portfolios', 'child-hello-elementor' ),
			'search_items'          => __( 'Search Portfolios', 'child-hello-elementor' ),
			'not_found'             => __( 'No Portfolios found', 'child-hello-elementor' ),
			'not_found_in_trash'    => __( 'No Portfolios found in trash', 'child-hello-elementor' ),
			'parent_item_colon'     => __( 'Parent Portfolio:', 'child-hello-elementor' ),
			'menu_name'             => __( 'Portfolios', 'child-hello-elementor' ),
		),
		'public'                => true,
		'hierarchical'          => false,
		'show_ui'               => true,
		'show_in_nav_menus'     => true,
		'supports'              => array( 'title', 'thumbnail' ),
		'has_archive'           => true,
		'rewrite'               => true,
		'query_var'             => true,
		'menu_position'         => null,
		'menu_icon'             => 'dashicons-admin-post',
		'show_in_rest'          => true,
		'rest_base'             => 'lu_portfolio',
		'rest_controller_class' => 'WP_REST_Posts_Controller',
	) );

}
add_action( 'init', 'lu_portfolio_init' );

/**
 * Sets the post updated messages for the `lu_portfolio` post type.
 *
 * @param  array $messages Post updated messages.
 * @return array Messages for the `lu_portfolio` post type.
 */
function lu_portfolio_updated_messages( $messages ) {
	global $post;

	$permalink = get_permalink( $post );

	$messages['lu_portfolio'] = array(
		0  => '', // Unused. Messages start at index 1.
		/* translators: %s: post permalink */
		1  => sprintf( __( 'Portfolio updated. <a target="_blank" href="%s">View Portfolio</a>', 'child-hello-elementor' ), esc_url( $permalink ) ),
		2  => __( 'Custom field updated.', 'child-hello-elementor' ),
		3  => __( 'Custom field deleted.', 'child-hello-elementor' ),
		4  => __( 'Portfolio updated.', 'child-hello-elementor' ),
		/* translators: %s: date and time of the revision */
		5  => isset( $_GET['revision'] ) ? sprintf( __( 'Portfolio restored to revision from %s', 'child-hello-elementor' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		/* translators: %s: post permalink */
		6  => sprintf( __( 'Portfolio published. <a href="%s">View Portfolio</a>', 'child-hello-elementor' ), esc_url( $permalink ) ),
		7  => __( 'Portfolio saved.', 'child-hello-elementor' ),
		/* translators: %s: post permalink */
		8  => sprintf( __( 'Portfolio submitted. <a target="_blank" href="%s">Preview Portfolio</a>', 'child-hello-elementor' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
		/* translators: 1: Publish box date format, see https://secure.php.net/date 2: Post permalink */
		9  => sprintf( __( 'Portfolio scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Portfolio</a>', 'child-hello-elementor' ),
		date_i18n( __( 'M j, Y @ G:i', 'child-hello-elementor' ), strtotime( $post->post_date ) ), esc_url( $permalink ) ),
		/* translators: %s: post permalink */
		10 => sprintf( __( 'Portfolio draft updated. <a target="_blank" href="%s">Preview Portfolio</a>', 'child-hello-elementor' ), esc_url( add_query_arg( 'preview', 'true', $permalink ) ) ),
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'lu_portfolio_updated_messages' );
