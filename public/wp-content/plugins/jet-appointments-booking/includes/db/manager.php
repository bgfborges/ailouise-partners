<?php
namespace JET_APB\DB;

use JET_APB\Plugin;

/**
 * Database manager class
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define Base DB class
 */
class Manager {

	public $appointments;
	public $appointments_meta;
	public $excluded_dates;

	/**
	 * Constructor for the class
	 */
	public function __construct() {

		$this->appointments      = new Appointments();
		$this->appointments_meta = new Appointments_Meta();
		$this->excluded_dates    = new Excluded_Dates();

	}

	/**
	 * Remove date of passed appoinemtnt from excluded dates
	 *
	 * @param  [type] $appointment [description]
	 * @return [type]              [description]
	 */
	public function remove_appointment_date_from_excluded( $appointment ) {

		if ( is_integer( $appointment ) ) {
			$appointment = $this->get_appointment_by( 'ID', $appointment );
		}

		if ( ! $appointment ) {
			return;
		}

		$excluded_where = array();

		if ( ! empty( $appointment['date'] ) ) {
			$excluded_where['date'] = $appointment['date'];
		}

		if ( ! empty( $appointment['service'] ) ) {
			$excluded_where['service'] = $appointment['service'];
		}

		if ( ! empty( $appointment['provider'] ) ) {
			$excluded_where['provider'] = $appointment['provider'];
		}

		$this->excluded_dates->delete( $excluded_where );

	}

	/**
	 * Check if this appointmetn is available
	 *
	 * @param  [type] $appointment_data [description]
	 * @return [type]                   [description]
	 */
	public function appointment_available( $appointment ) {

		$query       = array();
		$service_id  = ! empty( $appointment['service'] ) ? $appointment['service'] : null;
		$provider_id = ! empty( $appointment['provider'] ) ? $appointment['provider'] : null;
		$date        = ! empty( $appointment['date'] ) ? $appointment['date'] : null;
		$slot        = ! empty( $appointment['slot'] ) ? $appointment['slot'] : null;
		$slot_end    = ! empty( $appointment['slot_end'] ) ? $appointment['slot_end'] : null;

		if ( ! empty( $service_id ) && 'service' === Plugin::instance()->settings->get( 'check_by' ) ) {
			$query_args['service'] = $service_id;
		}

		if ( ! empty( $provider_id ) ) {
			$query_args['provider'] = $provider_id;
		}

		$query_args['slot']     = $slot;
		$query_args['slot_end'] = $slot_end;
		$query_args['date']     = $date;
		$query_args['status']   = array_merge( Plugin::instance()->statuses->valid_statuses() );

		$manage_capacity = Plugin::instance()->settings->get( 'manage_capacity' );
		$service_count   = 1;

		if ( $manage_capacity ) {

			$appointments  = Plugin::instance()->db->appointments->query_with_capacity( $query_args );
			$service_count = Plugin::instance()->tools->get_service_count( $service_id );

			if ( ! empty( $appointments ) ) {
				foreach ( $appointments as $slot => $appointment ) {
					$slot_count = ! empty( $appointment['slot_count'] ) ? absint( $appointment['slot_count'] ) : 1;
					if ( $slot_count < $service_count ) {
						unset( $appointments[ $slot ] );
					}
				}
			}

		} else {
			$appointments = $this->appointments->query( $query_args );
		}

		if ( empty( $appointments ) ) {
			return true;
		} else {
			return false;
		}

	}

	/**
	 * Delete appointment from DB
	 *
	 * @param  [type] $appointment_id [description]
	 * @return [type]                 [description]
	 */
	public function delete_appointment( $appointment_id ) {

		$appointment = $this->get_appointment_by( 'ID', $appointment_id );

		if ( ! $appointment ) {
			return;
		}

		$appointment_where = array(
			'ID' => $appointment_id,
		);

		$this->appointments->delete( $appointment_where );
		$this->remove_appointment_date_from_excluded( $appointment );

	}

	/**
	 * Insert new appointment and maybe add excluded date
	 *
	 * @param  array  $appointment [description]
	 * @return [type]              [description]
	 */
	public function add_appointment( $appointment = array() ) {

		if ( empty( $appointment['user_id'] ) && is_user_logged_in() ) {
			$appointment['user_id'] = get_current_user_id();
		}

		$appointment_id = $this->appointments->insert( $appointment );

		$this->maybe_exclude_appointment_date( $appointment );

		return $appointment_id;
	}

	/**
	 * Maybe add appointment date to excluded
	 *
	 * @param  [type] $appointment [description]
	 * @return [type]              [description]
	 */
	public function maybe_exclude_appointment_date( $appointment ) {

		if ( is_integer( $appointment ) ) {
			$appointment = $this->get_appointment_by( 'ID', $appointment );
		}

		if ( ! $appointment ) {
			return;
		}

		$service_id       = ! empty( $appointment['service'] ) ? $appointment['service'] : null;
		$provider_id      = ! empty( $appointment['provider'] ) ? $appointment['provider'] : null;
		$date             = ! empty( $appointment['date'] ) ? $appointment['date'] : null;
		$slot             = ! empty( $appointment['slot'] ) ? $appointment['slot'] : null;
		$capacity_is_full = true;

		if ( ! $slot ) {
			return;
		}

		$manage_capacity = Plugin::instance()->settings->get( 'manage_capacity' );
		if ( $manage_capacity ) {
			$query_args = array(
				'date'     => $date,
				'status'   => Plugin::instance()->statuses->valid_statuses(),
			);

			if ( $service_id ){
				$query_args['service'] = $service_id;
			}

			if ( $provider ){
				$query_args['provider'] = $provider_id;
			}

			$capacity       = Plugin::instance()->db->appointments->query_with_capacity( $query_args );
			$total_capacity = Plugin::instance()->tools->get_service_count($service_id);

			if( ! empty( $capacity ) && intval( $capacity['slot_count'] ) === $total_capacity ){
				$capacity_is_full = true;
			}else{
				$capacity_is_full = false;
			}
		}

		$all_slots = Plugin::instance()->calendar->get_date_slots( $service_id, $provider_id, $date );
		$all_slots = isset( $all_slots['slots'] ) ? $all_slots['slots'] : [] ;

		if ( ! empty( $all_slots ) && isset( $all_slots[ $slot ] ) && $capacity_is_full ) {
			unset( $all_slots[ $slot ] );
		}

		if ( empty( $all_slots ) ) {
			$this->excluded_dates->insert( array(
				'service'  => $service_id,
				'provider' => $provider_id,
				'date'     => $date,
			) );
		}
	}

	/**
	 * Returns appointments detail by order id
	 *
	 * @return [type] [description]
	 */
	public function get_appointments_by( $field = 'ID', $value = null ) {

		$appointments = $this->appointments->query( array( $field => $value ) );

		if ( empty( $appointments ) ) {
			return false;
		}

		return $appointments;

	}

	/**
	 * Returns appointment detail by order id
	 *
	 * @return [type] [description]
	 */
	public function get_appointment_by( $field = 'ID', $value = null ) {
		$appointment = $this->get_appointments_by( $field, $value );

		return ! $appointment ? false : $appointment[0];
	}

}