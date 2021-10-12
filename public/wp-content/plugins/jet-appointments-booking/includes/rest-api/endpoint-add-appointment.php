<?php
namespace JET_APB\Rest_API;

use JET_APB\Plugin;
use JET_APB\Time_Slots;

class Endpoint_Add_Appointment extends \Jet_Engine_Base_API_Endpoint {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'appointment-add-appointment';
	}

	/**
	 * API callback
	 *
	 * @return void
	 */
	public function callback( $request ){
		$new_appointments = $request->get_params();

		if ( empty( $new_appointments ) || ! $new_appointments[0]['service'] || ! $new_appointments[0]['date'] || ! $new_appointments[0]['user_email'] ) {
			return rest_ensure_response( array(
				'success' => false,
				'data'    => esc_html__( 'The appointment could not be added.', 'jet-appointments-booking' ),
			) );
		}
		
		unset( $new_appointments['_locale'] );
		
		$appointments_count = count( $new_appointments );
		$multi_booking      = Plugin::instance()->settings->get( 'multi_booking' );
		$group_ID           = $multi_booking && $appointments_count > 1 ? Plugin::instance()->db->appointments->get_max_int( 'group_ID' ) + 1 : NULL ;
		$db_columns = Plugin::instance()->db->appointments->get_column_list();

		foreach ( $new_appointments as $appointment ) {
			if(! is_array( $appointment ) ){
				continue;
			}
			
			if (!empty($db_columns)) {
				foreach ($db_columns as $column) {
					$value = in_array($column, ['date', 'slot', 'slot_end']) ? $appointment[ $column . '_timestamp' ] : $appointment[$column];
					$appointment[ $column ] = !empty($value) ? esc_attr($value) : '';
				}
				
			}
			
			$exclude_columns = [
				'_locale',
				'date_timestamp',
				'slot_timestamp',
				'slot_end_timestamp',
			];
			
			foreach ($exclude_columns as $column) {
				if ( array_key_exists($column, $appointment) ) {
					unset( $appointment[ $column ] );
				}
			}
			
			$appointment[ 'order_id' ] = NULL;
			$appointment[ 'group_ID' ] = $group_ID;
			
			if ( !Plugin::instance()->db->appointment_available($appointment) ) {
				return rest_ensure_response(array(
					'success' => true,
					'data'    => esc_html__('Appointment time already taken', 'jet-appointments-booking'),
				));
			}

			$appointment_id = Plugin::instance()->db->add_appointment($appointment);
		}
		
		if( $group_ID ){
			$success_text = sprintf( esc_html__( 'Success! New appointments group ID:%s has been added', 'jet-appointments-booking' ), $group_ID );
		} else {
			$success_text = sprintf( esc_html__( 'Success! New appointment ID:%s has been added', 'jet-appointments-booking' ), $appointment_id );
		}
		
		return rest_ensure_response( array(
			'success' => true,
			'data'    => $success_text,
		) );
	}

	public function permission_callback( $request ) {
		return current_user_can( 'manage_options' );
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
		return [];
	}

}