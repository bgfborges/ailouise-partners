<?php
/**
 * WooCommerce order details
 */
?>
<h2 class="woocommerce-order-details__title"><?php esc_html_e( 'Appointment Details', 'jet-appointments-booking' ); ?></h2>
	<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
	<?php
		foreach ( $details as $item ) {
			?>
			<?php
				if( ! empty( $item[ 'is_header' ] ) ){
					echo '</ul>
							<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">';
				}
				echo '<li>';
				if ( ! empty( $item['key'] ) ) {
					echo $item['key'] . ': ';
				}
				
				if ( ! empty( $item['is_html'] ) ) {
					echo $item['display'];
				} else {
					echo '<strong>' . $item['display'] . '</strong>';
				}
				echo '</li>';
			?>
		<?php
		}
	?>
	</ul>