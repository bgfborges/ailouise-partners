<?php
/**
 * Child-hello-elementor Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package child-hello-elementor
 */

define ('CHILD_THEME_PATH', get_stylesheet_directory());

add_action( 'wp_enqueue_scripts', 'hello_elementor_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function hello_elementor_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'hello-elementor-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_script( 'theme-script', get_stylesheet_directory_uri() . '/assets/scripts/theme-scripts.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_style( 'child-hello-elementor-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'hello-elementor-style' )
	);

}

// Remove the WP Admin Bar
add_filter('show_admin_bar', '__return_false');

// Include Post Types
require CHILD_THEME_PATH . '/post-types/lu_orders.php';

// Include Taxonomies
require CHILD_THEME_PATH . '/taxonomies/status.php';

// Include Metaboxes
require CHILD_THEME_PATH . '/metaboxes/lu_orders/product-metabox.php';
require CHILD_THEME_PATH . '/metaboxes/lu_orders/prices-metabox.php';
require CHILD_THEME_PATH . '/metaboxes/shop_order/metabox.php';

// Custom functions
require CHILD_THEME_PATH . '/woocommerce/lu-projects/lu_orders_associate_product.php';
