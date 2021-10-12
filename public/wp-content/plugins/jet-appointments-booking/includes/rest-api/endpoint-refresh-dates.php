<?php
namespace JET_APB\Rest_API;

use JET_APB\Plugin;
use JET_APB\Time_Slots;

class Endpoint_Refresh_Dates extends \Jet_Engine_Base_API_Endpoint {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'appointment-refresh-date';
	}

	/**
	 * API callback
	 *
	 * @return void
	 */
	public function callback( $request ) {

		$params   = $request->get_params();
		$service  = ! empty( $params['service'] ) ? absint( $params['service'] ) : 0;
		$provider = ! empty( $params['provider'] ) ? absint( $params['provider'] ) : false;

		return rest_ensure_response( array(
			'success' => true,
			'data'    => array(
				'excludedDates'     => Plugin::instance()->calendar->get_off_dates( $service, $provider ),
				'worksDates'        => Plugin::instance()->calendar->get_works_dates( $service, $provider ),
				'availableWeekDays' => Plugin::instance()->calendar->get_available_week_days( $service, $provider ),
			),
		) );

	}

	/**
	 * Returns endpoint request method - GET/POST/PUT/DELTE
	 *
	 * @return string
	 */
	public function get_method() {
		return 'GET';
	}

	/**
	 * Returns arguments config
	 *
	 * @return array
	 */
	public function get_args() {
		return array(
			'service' => array(
				'default'  => '',
				'required' => false,
			),
			'provider' => array(
				'default'  => '',
				'required' => false,
			),
		);
	}

}
