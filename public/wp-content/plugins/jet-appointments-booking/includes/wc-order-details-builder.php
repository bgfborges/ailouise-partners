<?php
namespace JET_APB;

/**
 * WooCommerce ordersdetils builder class
 */
class WC_Order_Details_Builder {

	public $meta_key = '_jet_apb_wc_details';

	/**
	 * Constructor for the class
	 */
	public function __construct() {
		add_action( 'jet-engine/forms/editor/assets', [ $this, 'enqueue_builder' ] );
		add_action( 'wp_ajax_jet_appointments_save_wc_details', array( $this, 'save_wc_details' ) );
		add_filter( 'jet-appointment/wc-integration/pre-cart-info', array( $this, 'set_cart_details' ), 10, 4 );
		add_filter( 'jet-appointment/wc-integration/pre-get-order-details', array( $this, 'set_order_details' ), 10, 3 );
		add_action( 'jet-appointment/wc-integration/process-order', array( $this, 'set_order_meta' ), 10, 3 );
	}

	/**
	 * Set cart order details
	 */
	public function set_cart_details( $result, $data, $form_data, $form_id ) {
		
		if ( ! $form_id ) {
			return $result;
		}

		return $this->get_order_details( $form_id, $data, $form_data );

	}

	/**
	 * Set order details info
	 *
	 * @param [type] $details  [description]
	 * @param [type] $order_id [description]
	 * @param [type] $booking  [description]
	 */
	public function set_order_details( $details, $order_id, $appointment ) {

		$meta = get_post_meta( $order_id, $this->meta_key, true );

		if ( empty( $meta ) ) {
			return $details;
		}

		$form_id   = ! empty( $meta['form_id'] ) ? $meta['form_id'] : false;
		$form_data = ! empty( $meta['form_data'] ) ? $meta['form_data'] : array();

		if ( ! $form_id ) {
			return $details;
		}

		return $this->get_order_details( $form_id, $appointment, $form_data );

	}

	/**
	 * Get order details for pased form, booking and form data
	 *
	 * @param  [type] $form_id   [description]
	 * @param  array  $appointment   [description]
	 * @param  array  $form_data [description]
	 * @return [type]            [description]
	 */
	public function get_order_details( $form_id = null, $appointment = array(), $form_data = array() ) {
		$details = $this->get_details_schema( $form_id );
		
		if ( false === $details ) {
			return false;
		}

		$result = [
			[
				'key'       => esc_html__( 'Appointment Information', 'jet-appointments-booking' ),
				'display'   => sprintf( esc_html__( '#%1$s', 'jet-appointments-booking' ), $appointment['ID'] ),
				'is_header' => true,
			]
		];
		
		foreach ( $details as $item ) {
			$key = ! empty( $item['label'] ) ? $item['label'] : ' ' ;
			
			switch ( $item['type'] ) {

				case 'service':
				case 'provider':

					if ( ! empty( $appointment[ $item['type'] ] ) ) {
						$result[] = array(
							'key'     => $key,
							'display' => get_the_title( $appointment[ $item['type'] ] ),
						);
					}

					break;

				case 'slot':
				case 'slot_end':

					if ( ! empty( $appointment[ $item['type'] ] ) ) {

						$value = absint( $appointment[ $item['type'] ] );
						$format = isset( $item['time_format'] ) ? $item['time_format'] : get_option( 'time_format' );

						if ( $value ) {
							$result[] = array(
								'key'     => $key,
								'display' => date_i18n( $format, $value ),
							);
						}
					}
					
					break;

				case 'date':

					if ( ! empty( $appointment['date'] ) ) {
						$value = absint( $appointment['date'] );
						$format = isset( $item['date_format'] ) ? $item['date_format'] : get_option( 'date_format' );

						if ( $value ) {
							$result[] = array(
								'key'     => $key,
								'display' => date_i18n( $format, $value ),
							);
						}
					}

					break;

				case 'start_end_time':

					if ( ! empty( $appointment['slot'] ) && ! empty( $appointment['slot_end'] ) ) {

						$start  = absint( $appointment['slot'] );
						$end    = absint( $appointment['slot_end'] );
						$format = isset( $item['time_format'] ) ? $item['time_format'] : get_option( 'time_format' );

						if ( $start && $end ) {
							$result[] = array(
								'key'     => $key,
								'display' => sprintf(
									'%1$s - %2$s',
									date_i18n( $format, $start ),
									date_i18n( $format, $end )
								),
							);
						}
					}

					break;

				case 'date_time':

					if ( ! empty( $appointment['date'] ) && ! empty( $appointment['slot'] ) && ! empty( $appointment['slot_end'] ) ) {

						$date     = absint( $appointment['date'] );
						$start    = absint( $appointment['slot'] );
						$end      = absint( $appointment['slot_end'] );
						$d_format = isset( $item['date_format'] ) ? $item['date_format'] : get_option( 'date_format' );
						$t_format = isset( $item['time_format'] ) ? $item['time_format'] : get_option( 'time_format' );

						if ( $date && $start && $end ) {
							$result[] = array(
								'key'     => $key,
								'display' => sprintf(
									'%1$s, %2$s - %3$s',
									date_i18n( $d_format, $date ),
									date_i18n( $t_format, $start ),
									date_i18n( $t_format, $end )
								),
							);
						}
					}

					break;

				case 'field':

					$field = isset( $item['field'] ) ? $item['field'] : false;

					if ( $field ) {
						$value = isset( $form_data[ $field ] ) ? $form_data[ $field ] : '';

						switch ( true ){
							case $value === 0:
							case $value === '0':
								$value = ' 0';

								break;
							case is_array( $value ):
								$value = implode( ', ', $value );

								break;
							case ! $value :
								$value = '-';

								break;
							default:
								$value = $value;

								break;
						}

						$result[] = array(
							'key'     => $key,
							'display' => $value,
						);

					}

					break;

				case 'add_to_calendar':

					$url = Plugin::instance()->google_cal->get_internal_link( $appointment['ID'] );

					if ( $url ) {
						$link_text = ! empty( $item['link_label'] ) ? $item['link_label'] : esc_html__( 'Add', 'jet-appointments-booking' );
						$link_format = '<strong><a href="%1$s" target="_blank">%2$s</a></strong>';
						$result[] = array(
							'key'           => $key,
							'is_html'       => true,
							'display'       => sprintf( $link_format, $url, $link_text ),
							'display_plain' => $url
						);
					}

					break;

				default:
					if ( ! empty( $appointment[ $item['type'] ] ) ) {
						$result[] = array(
							'key'     => $key,
							'display' => $appointment[ $item['type'] ],
						);
					}

					break;

			}

		}

		return $result;

	}

	/**
	 * Stroe form ID and details into order meta
	 *
	 * @param [type] $order_id  [description]
	 * @param [type] $order     [description]
	 * @param [type] $cart_item [description]
	 */
	public function set_order_meta( $order_id, $order, $cart_item ) {

		$id_key  = Plugin::instance()->wc->form_id_key;
		$form_id = ! empty( $cart_item[ $id_key ] ) ? $cart_item[ $id_key ] : false;

		if ( ! $form_id ) {
			return;
		}

		$data_key  = Plugin::instance()->wc->form_data_key;
		$form_data = ! empty( $cart_item[ $data_key ] ) ? $cart_item[ $data_key ] : false;

		if ( empty( $form_data ) ) {
			return;
		}

		$meta = array(
			'form_id'   => $form_id,
			'form_data' => $form_data,
		);

		update_post_meta( $order_id, $this->meta_key, $meta );
	}

	/**
	 * Save WC details settings
	 *
	 * @return [type] [description]
	 */
	public function save_wc_details() {

		$nonce = ! empty( $_REQUEST['nonce'] ) ? $_REQUEST['nonce'] : false;

		if ( ! $nonce || ! wp_verify_nonce( $nonce, $this->meta_key ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'Link is expired', 'jet-appointments-booking' ) ) );
		}

		$post_id = ! empty( $_REQUEST['post_id'] ) ? absint( $_REQUEST['post_id'] ) : false;

		if ( ! $post_id || ! current_user_can( 'edit_post', $post_id ) ) {
			wp_send_json_error( array( 'message' => esc_html__( 'You don`t have access to this post', 'jet-appointments-booking' ) ) );
		}

		$details = isset( $_REQUEST['details'] ) ? $_REQUEST['details'] : array();

		update_post_meta( $post_id, $this->meta_key, $details );

		wp_send_json_success();
	}

	/**
	 * Returns details config for current form
	 *
	 * @param  [type] $post_id [description]
	 * @return [type]          [description]
	 */
	public function get_details_schema( $post_id = null ) {

		if ( ! $post_id ) {
			global $post;
			$post_id = $post ? $post->ID : false;
		}

		if ( ! $post_id ) {
			return false;
		}

		$details = get_post_meta( $post_id, $this->meta_key );

		if ( ! $details ) {

			$details = array(
				array(
					'type'   => 'service',
					'label'  => esc_html__( 'Service', 'jet-appointments-booking' ),
				),
				array(
					'type'        => 'date_time',
					'label'       => esc_html__( 'Date', 'jet-appointments-booking' ),
					'date_format' => 'F j, Y',
					'time_format' => 'g:i',
				),
			);
		} else {
			$details = $details[0];
		}

		return $details;

	}

	/**
	 * Enqueue order details builder
	 */
	public function enqueue_builder() {

		wp_enqueue_style( 'jet-appointments-booking-admin' );
		wp_enqueue_script( 'jet-apb-wc-details-builder' );

		global $post;

		wp_localize_script( 'jet-apb-wc-details-builder', 'JetAPBWCDetails', array(
			'apartment'       => $post->ID,
			'details'         => $this->get_details_schema( $post->ID ),
			'confirm_message' => esc_html__( 'Are you sure?', 'jet-appointments-booking' ),
			'nonce'           => wp_create_nonce( $this->meta_key ),
		) );

		add_action( 'admin_footer', array( $this, 'builder_template' ) );

	}

	/**
	 * Include builder compoent template
	 */
	public function builder_template() {
		ob_start();
		include JET_APB_PATH . 'templates/admin/common/wc-details-builder.php';
		$content = ob_get_clean();
		printf( '<div id="jet_apb_wc_details_builder_popup"></div><script type="text/x-template" id="jet-apb-wc-details-builder">%s</script>', $content );
	}
}