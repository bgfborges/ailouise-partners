<?php
namespace JET_APB\Rest_API;

use JET_APB\Plugin;
use JET_APB\Time_Slots;

class Endpoint_Date_Slots extends \Jet_Engine_Base_API_Endpoint {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'appointment-date-slots';
	}

	/**
	 * API callback
	 *
	 * @return void
	 */
	public function callback( $request ) {
		$params         = $request->get_params();
		$service        = ! empty( $params['service'] ) ? absint( $params['service'] ) : 0;
		$provider       = ! empty( $params['provider'] ) ? absint( $params['provider'] ) : 0;
		$date           = ! empty( $params['date'] ) ? absint( $params['date'] ) : 0;
		$time           = ! empty( $params['timestamp'] ) ? absint( $params['timestamp'] ) : 0;
		$admin          = ! empty( $params['admin'] ) ? filter_var( $params['admin'], FILTER_VALIDATE_BOOLEAN ) : false;
		$selected_slots = ! empty( $params['selected_slots'] ) ? json_decode( $params['selected_slots'] ) : [] ;
		$notification   = esc_html__( 'No available slots', 'jet-appointments-booking' );
		$price_source   = 0 !== $provider ? $provider : $service ;

		if ( ! $service || ! $date ) {
			return rest_ensure_response( array(
				'success' => false,
			) );
		}

		$result   = Plugin::instance()->calendar->get_date_slots( $service, $provider, $date, $time, $selected_slots );
		$slots = isset( $result['slots'] ) ? $result['slots'] : [] ;

		if( $admin ){
			$result['slots'] = ! empty( $slots ) ? $slots : $notification ;
		}else{
			ob_start();

			if ( ! empty( $slots ) ) {
				$service_price   = get_post_meta( $service, '_app_price', true );
				$provider_price  = get_post_meta( $provider, '_app_price', true );
				$price = $provider_price ? $provider_price : $service_price ;

				$dataset = array( 'data-price="' . $price . '"' );
				$format = Plugin::instance()->settings->get( 'slot_time_format' );
				Time_Slots::generate_slots_html( $slots, $format, $dataset, $date, $service );
			} else {
				echo $notification;
			}

			$result['slots'] = ob_get_clean() . '<div class="jet-apb-calendar-slots__close">&times;</div>';
		}

		return rest_ensure_response( array(
			'success' => true,
			'data'    => $result,
		) );
	}

	/**
	 * Returns endpoint request method - GET/POST/PUT/DELTE
	 *
	 * @return string
	 */
	public function get_method() {
		return 'POST';
	}

	/**
	 * Returns arguments config
	 *
	 * @return array
	 */
	public function get_args() {
		return array(
			'date' => array(
				'default'  => '',
				'required' => true,
			),
			'service' => array(
				'default'  => '',
				'required' => true,
			),
			'provider' => array(
				'default'  => '',
				'required' => false,
			),
			'timestamp' => array(
				'default'  => '',
				'required' => false,
			),
		);
	}

}