<?php
/**
 * Admin order details
 */
?>
<hr>
<h3><?php _e( 'Appointment Details', 'jet-appointment-booking' ); ?></h3>
<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
	<?php
		foreach ( $details as $item ) {
			?>
			<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
			<?php
			foreach ( $item as $field ){
				echo '<li>';
					if ( ! empty( $field['key'] ) ) {
						echo $field['key'] . ': ';
					}

					if ( ! empty( $field['is_html'] ) ) {
						echo $field['display'];
					} else {
						echo '<strong>' . $field['display'] . '</strong>';
					}

				echo '</li>';
			}?>
			</ul>
		<?php
		}
	?>
</ul>