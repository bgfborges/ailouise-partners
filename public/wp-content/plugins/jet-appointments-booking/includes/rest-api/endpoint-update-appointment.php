<?php
namespace JET_APB\Rest_API;

use JET_APB\Plugin;
use JET_APB\Time_Slots;

class Endpoint_Update_Appointment extends \Jet_Engine_Base_API_Endpoint {

	/**
	 * Returns route name
	 *
	 * @return string
	 */
	public function get_name() {
		return 'update-appointment';
	}

	/**
	 * API callback
	 *
	 * @return void
	 */
	public function callback( $request ) {

		$params      = $request->get_params();
		$item_id     = ! empty( $params['id'] ) ? absint( $params['id'] ) : 0;
		$item        = ! empty( $params['item'] ) ? $params['item'] : array();
		$not_allowed = array(
			'order_id',
			'user_id',
			'ID',
			'group_ID',
			'date_timestamp',
			'slot_timestamp',
			'slot_end_timestamp',
			'isGroupChief',
		);

		foreach ( $item as $key => $value ) {
			$value = in_array( $key, [ 'date', 'slot', 'slot_end' ] ) ? $item[ $key . '_timestamp' ] : $value ;
			$item[ $key ] = ! empty( $value ) ? esc_attr( $value ) : '';
		}

		foreach ( $not_allowed as $key  ) {
			if ( isset( $item[ $key ] ) ) {
				unset( $item[ $key ] );
			}
		}

		if ( empty( $item ) ) {
			return rest_ensure_response( array(
				'success' => false,
				'data'    => __( 'No data to update', 'jet-appointments-booking' ),
			) );
		}

		$old_item = Plugin::instance()->db->get_appointment_by( 'ID', $item_id );

		$old_status = $old_item['status'];
		$new_status = ! empty( $item['status'] ) ? $item['status'] : $old_item['status'];

		if ( $new_status !== $old_status ) {

			if ( in_array( $new_status, Plugin::instance()->statuses->invalid_statuses() ) ) {
				Plugin::instance()->db->remove_appointment_date_from_excluded( $old_item );
			}

			if ( in_array( $old_status, Plugin::instance()->statuses->invalid_statuses() ) && in_array( $new_status, Plugin::instance()->statuses->valid_statuses() ) ) {
				Plugin::instance()->db->maybe_exclude_appointment_date( $old_item );
			}
		}

		$item = wp_parse_args(
			$item,
			$old_item
		);

		Plugin::instance()->db->appointments->update( $item, array( 'ID' => $item_id ) );

		return rest_ensure_response( array(
			'success' => true,
		) );

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
		return 'POST';
	}

	/**
	 * Get query param. Regex with query parameters
	 *
	 * @return string
	 */
	public function get_query_params() {
		return '(?P<id>[\d]+)';
	}

}