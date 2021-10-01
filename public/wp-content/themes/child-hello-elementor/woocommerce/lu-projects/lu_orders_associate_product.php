<?php

add_action( 'save_post_lu_orders', 'generate_wooorder_from_luorder' );
function generate_wooorder_from_luorder($post_id) {

	$comission_amount = $_REQUEST['lu_absolute-number-value-defined'];
	if( !$comission_amount || $comission_amount < 0 ) return;

	$choosen_products = array(
		'landing' => array(
			'active' 	=> $_REQUEST['lu_landing-page-standard'],
			'product_id' => 218,
		),
		'institutional' => array(
			'active' 	=> $_REQUEST['lu_institutional-website-standard'],
			'product_id' => 3621,
		),
		'ecommerce' => array(
			'active' 	=> $_REQUEST['lu_ecommerce-standard'],
			'product_id' => 3623,
		),
	);

	foreach( $choosen_products as $key => $val ){
		if( $val['active'] !== 'on' ){
			unset( $choosen_products[$key] );
		}
	}

	if( !$choosen_products ) return;

	$loop = new WP_Query( array(
		'post_type'         => 'shop_order',
		'post_status'       =>  array_keys( wc_get_order_statuses() ),
		'posts_per_page'    => -1,
		'meta_query'	=> array(
			array(
				'key'       => 'lu_lu-id',
				'value'     => strval($post_id),
			),
		),
	) );

	// The Wordpress post loop
	if ( $loop->have_posts() ) return;

	// Author is the person who created the order, then the client id to be associated
	$author_id = get_post_field ('post_author', $post_id);

	$args = array(
		'status' => 'pending',
		'customer_id' => $author_id,
	);

	$new_order = wc_create_order($args);

	foreach( $choosen_products as $key ){

		$product = wc_get_product($key['product_id']);

		$new_order->add_product($product, 1, [
			'subtotal'     => $product->get_price(),
			'total'        => $product->get_price(),
		]);
	}

	$comission = wc_get_product(4612);

	$new_order->add_product($comission, 1, [
		'subtotal'     => $comission->get_price(),
		'total'        => $comission->get_price() + $comission_amount,
	]);

	$new_order->calculate_totals();
	$new_order->save();
	update_post_meta( $new_order->get_id(), 'lu_lu-id', $post_id );

}
