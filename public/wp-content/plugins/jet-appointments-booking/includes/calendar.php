<?php
namespace JET_APB;

/**
 * Calendar related data
 */
class Calendar {

	/**
	 * Get date slots
	 *
	 * @return [type] [description]
	 */
	public function get_date_slots( $service = 0, $provider = 0, $date = 0, $time = 0, $selected = [] ) {

		if ( ! $service || ! $date ) {
			return false;
		}


		$weekday       = strtolower( date( 'l', $date ) );
		$slots         = array();
		$result        = [
			'slots' => [],
			'booking_type' => 'slot'
			//'booking_type' => $this->get_schedule_settings( $provider, $service, 'slot', 'booking_type' )
		];
		$time         += $this->get_schedule_settings( $provider, $service, 0, 'locked_time' );
		$buffer_before = $this->get_schedule_settings( $provider, $service, 0, 'buffer_before' );
		$buffer_after  = $this->get_schedule_settings( $provider, $service, 0, 'buffer_after' );
		$duration      = $this->get_schedule_settings( $provider, $service, 0, 'default_slot' );
		$working_hours = $this->get_schedule_settings( $provider, $service, [], 'working_hours' );

		$day_schedule  = ! empty( $working_hours[ $weekday ] ) ? $working_hours[ $weekday ] : array();
		Time_Slots::set_starting_point( $date );

		if ( 0 < $time ) {
			Time_Slots::set_timenow( $time );
		}

		if ( 1 < count( $day_schedule ) ) {

			usort( $day_schedule, function( $a, $b ) {

				$a_from = strtotime( $a['from'] );
				$b_from = strtotime( $b['from'] );

				if ( $a_from === $b_from ) {
					return 0;
				}

				return ( $a_from < $b_from ) ? -1 : 1;

			} );
		}

		if( $selected ){
			$selected = array_filter( $selected, function( $item ) use ( &$service, &$provider ) {
				$checkService = $item->service === $service;
				$checkProvider = isset( $item->provider ) ? $item->provider === $provider : true  ;

				if( $checkService && $checkProvider ){
					return true;
				}else{
					return false;
				}
			});
		}

		foreach ( $day_schedule as $day_part ) {
			$slots = $slots + Time_Slots::generate_intervals( array(
				'from'          => $day_part['from'],
				'to'            => $day_part['to'],
				'duration'      => $duration,
				'buffer_before' => $buffer_before,
				'buffer_after'  => $buffer_after,
				'from_now'      => true,
				'selected'      => $selected,
			) );
		}

		$query_args = array(
			'date'    => $date,
			'status'  => Plugin::instance()->statuses->valid_statuses(),
		);

		if ( 'service' === Plugin::instance()->settings->get( 'check_by' ) ) {
			$query_args['service'] = $service;
		}

		if ( $provider ) {
			$query_args['provider'] = $provider;
		}

		$manage_capacity = Plugin::instance()->settings->get( 'manage_capacity' );
		$service_count   = 1;

		if ( $manage_capacity ) {
			$excluded      = Plugin::instance()->db->appointments->query_with_capacity( $query_args );
			$service_count = Plugin::instance()->tools->get_service_count( $service );
		} else {
			$excluded = Plugin::instance()->db->appointments->query( $query_args );
		}

		if ( ! empty( $excluded ) ) {
			foreach ( $excluded as $appointment ) {

				$excl_slot_from = absint( $appointment['slot'] );
				$excl_slot_to   = absint( $appointment['slot_end'] );
				$slot_count     = ! empty( $appointment['slot_count'] ) ? absint( $appointment['slot_count'] ) : 1;
				$excl_range     = range( $excl_slot_from, $excl_slot_to );

				if ( ! $excl_slot_from ) {
					continue;
				}

				if ( $manage_capacity ) {

					if ( isset( $slots[ $excl_slot_from ] ) && $slot_count >= $service_count ) {
						unset( $slots[ $excl_slot_from ] );
					}

				} elseif ( isset( $slots[ $excl_slot_from ] ) ) {
					unset( $slots[ $excl_slot_from ] );
				}
				
				foreach ( $slots as $slot => $slot_data ) {
					$slot_range = range( $slot_data['from'], $slot_data['to']);
					
					if( in_array( $slot_data['from'] + 1, $excl_range )
						|| in_array( $slot_data['to'] - 1, $excl_range )
						|| in_array( $excl_slot_from + 1, $slot_range )
						|| in_array( $excl_slot_to - 1, $slot_range )
					){
						if ( $manage_capacity && $slot_count >= $service_count ) {
							unset( $slots[ $slot ] );
						} elseif ( $manage_capacity ) {
							$slots[ $slot ]['slot_count'] = $slot_count;
						} elseif ( ! $manage_capacity ) {
							unset( $slots[ $slot ] );
						}
					}
				}

			}
		}

		if ( empty( $slots ) ) {
			Plugin::instance()->db->excluded_dates->insert( array(
				'service'  => $service,
				'provider' => $provider,
				'date'     => $date,
			) );
		}

		$result['slots'] = $slots;

		return $result;

	}

	/**
	 * Returns names of excluded week days
	 *
	 * @return [type] [description]
	 */
	public function get_available_week_days( $service = null, $provider = null ) {
		$working_hours = $this->get_schedule_settings( $provider, $service, [], 'working_hours' );
		$result        = array();

		foreach ( $working_hours as $week_day => $schedule ) {
			if ( ! empty( $schedule ) ) {
				$result[] = $week_day;
			}
		}

		return $result;
	}

	/**
	 * Returns week days list
	 *
	 * @return [type] [description]
	 */
	public function get_week_days() {
		return array(
			'sunday',
			'monday',
			'tuesday',
			'wednesday',
			'thursday',
			'friday',
			'saturday',
		);
	}

	/**
	 * Returns excluded dates - official days off and booked dates
	 *
	 * @return [type] [description]
	 */
	public function get_off_dates( $service = null, $provider = null ) {
		$result     = array();
		$days_off   = $this->get_schedule_settings( $provider, $service, null, 'days_off' );
		$query_args = array(
			'date>=' => time(),
		);

		if ( ! empty( $service ) ) {
			if( 'service' === Plugin::instance()->settings->get( 'check_by' ) ){
				$query_args['service'] = $service;
			}
		}

		if ( ! empty( $provider ) ) {
			$query_args['provider'] = $provider;
		}

		if ( ! empty( $days_off ) ) {
			foreach ( $days_off as $day ) {
				$result[] = [
					'start' => $day['startTimeStamp'] / 1000,
					'end' => $day['endTimeStamp'] / 1000,
				];
			}
		}

		$excluded = Plugin::instance()->db->excluded_dates->query( $query_args );

		if ( ! empty( $excluded ) ) {
			foreach ( $excluded as $date ) {
				if( ! isset( $date['start'] ) ){
					$date_period = [
						'start' => absint( $date['date'] ),
						'end' => absint( $date['date'] ),
					];

					if ( ! in_array( $date_period, $result ) ){
						$result[] = $date_period;
					}

				}else{
					$result[] = absint( $date['date'] );
				}
			}
		}

		return $result;
	}

	public function get_works_dates( $service = null, $provider = null ) {
		$result       = array();
		$working_days = $this->get_schedule_settings( $provider, $service, null, 'working_days' );

		if ( ! empty( $working_days ) ) {
			foreach ( $working_days as $day ) {
				$result[] = [
					'start' => $day['startTimeStamp'] / 1000,
					'end' => $day['endTimeStamp'] / 1000,
				];
			}
		}

		return $result;
	}

	public function get_schedule_settings( $provider = null, $service = null, $default_value  = null, $meta_key = null ){
		$value         = null;
		$post_meta     = get_post_meta( $provider, 'jet_apb_post_meta', true );
		$general_value = Plugin::instance()->settings->get( $meta_key );
		$general_value = $general_value ? $general_value : $default_value;

		if( ! isset( $post_meta[ 'custom_schedule' ] ) || ! $post_meta[ 'custom_schedule' ][ 'use_custom_schedule' ] ){
			$post_meta = get_post_meta( $service, 'jet_apb_post_meta', true );
		}

		if( ! isset( $post_meta[ 'custom_schedule' ] ) || ! $post_meta[ 'custom_schedule' ][ 'use_custom_schedule' ] ){
			$value =  $general_value;
		}else{
			if ( isset( $post_meta[ 'custom_schedule' ][ $meta_key ] ) ){
				$value = $post_meta[ 'custom_schedule' ][ $meta_key ];

				$value = NULL !== $value ? $value : $general_value ;
			}
		}

		return $value;
	}
}