<?php
/**
 * WooCommerce order details
 */
?>
<?php _e( 'Appointment Details', 'jet-appointments-booking' ); ?>
<?php
	foreach ( $details as $item ) {
		echo '- ';
			if ( ! empty( $item['key'] ) ) {
				echo $item['key'] . ': ';
			}

			if ( ! empty( $item['is_html'] ) ) {
				echo $item['display'];
			} else {
				echo '<strong>' . $item['display'] . '</strong>';
			}

		echo '
';
	}
?>
