<?php
namespace JET_APB\Rest_API;

use JET_APB\Plugin;
use JET_APB\Time_Slots;

class Endpoint_Appointments_List extends \Jet_Engine_Base_API_Endpoint {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'appointments-list';
	}

	/**
	 * API callback
	 *
	 * @return void
	 */
	public function callback( $request ) {
		$params   = $request->get_params();

		$offset      = ! empty( $params['offset'] ) ? absint( $params['offset'] ) : 0;
		$per_page    = ! empty( $params['per_page'] ) ? absint( $params['per_page'] ) : 50;
		$sort        = ! empty( $params['sort'] ) ? json_decode( $params['sort'], true ) : array();
		$filter      = ! empty( $params['filter'] ) ? json_decode( $params['filter'], true ) : array();
		$search_in   = ! empty( $params['search_in'] ) ? str_replace( ',', ', ', $params['search_in'] )  : false ;
		$search      = ! empty( $filter['search'] ) ? $filter['search'] : false ;

		$filter = ( ! empty( $filter ) && is_array( $filter ) ) ? array_filter( $filter ) : array();
		$sort   = ( ! empty( $sort ) && is_array( $sort ) ) ? array_filter( $sort ) : array( 'orderby' => 'ID', 'order' => 'DESC', );

		if ( $search ) {
			unset( $filter['search'] );
		}

		if ( ! empty( $filter['date'] ) && ! is_int( $filter['date'] ) ) {
			$filter['date'] = strtotime( $filter['date'] );
		}

		$appointments = Plugin::instance()->db->appointments->query(
			$filter,
			$per_page,
			$offset,
			$sort,
			$search,
			$search_in
		);

		if ( empty( $appointments ) ) {
			$appointments = array();
		}

		return rest_ensure_response( array(
			'success' => true,
			'data'    => $this->format_dates( $appointments ),
			'total'   => intval( Plugin::instance()->db->appointments->count( $filter ) ),
			'on_page' => count( $appointments ),
		) );

	}

	public function format_dates( $appointments = array() ) {

		//$date_format = get_option( 'date_format', 'd/m/y' );
		$date_format = 'd/m/y';
		$time_format = get_option( 'time_format', 'H:i' );

		return array_map( function( $appointment ) use ( $date_format, $time_format ) {

			$appointment['date_timestamp']     = $appointment['date'];
			$appointment['slot_timestamp']     = $appointment['slot'];
			$appointment['slot_end_timestamp'] = $appointment['slot_end'];

			$appointment['date']     = date_i18n( $date_format, $appointment['date'] );
			$appointment['slot']     = date_i18n( $time_format, $appointment['slot'] );
			$appointment['slot_end'] = date_i18n( $time_format, $appointment['slot_end'] );
			return $appointment;
		}, $appointments );
	}

	/**
	 * Check user access to current end-popint
	 *
	 * @return bool
	 */
	public function permission_callback( $request ) {
		return current_user_can( 'manage_options' );
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
			'offset' => array(
				'default'  => 0,
				'required' => false,
			),
			'per_page' => array(
				'default'  => 50,
				'required' => false,
			),
			'filter' => array(
				'default'  => array(),
				'required' => false,
			),
			'sort' => array(
				'default'  => array(),
				'required' => false,
			),
		);
	}

}