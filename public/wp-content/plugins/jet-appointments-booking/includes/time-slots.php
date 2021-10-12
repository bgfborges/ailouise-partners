<?php
namespace JET_APB;

/**
 * Time slots generator class
 */
class Time_Slots {

	private static $starting_point = false;
	private static $timenow        = false;

	/**
	 * Returns current starting point
	 *
	 * @return int
	 */
	public static function get_starting_point() {

		if ( ! self::$starting_point ) {
			self::$starting_point = strtotime( 'today midnight' );
		}

		return self::$starting_point;
	}

	/**
	 * Returns current starting point
	 *
	 * @return int
	 */
	public static function set_starting_point( $timestamp ) {
		self::$starting_point = $timestamp;
	}

	/**
	 * Returns current starting point
	 *
	 * @return int
	 */
	public static function set_timenow( $timestamp ) {
		self::$timenow = $timestamp;
	}

	/**
	 * Generate time slots array
	 *
	 * @param  array  $args [description]
	 * @return [type]       [description]
	 */
	public static function generate_slots( $args = array() ) {

		$args = wp_parse_args( $args, array(
			'from'     => 0,
			'to'       => 0,
			'interval' => 30 * MINUTE_IN_SECONDS,
			'format'   => 'G:i',
			'from_now' => false,
		) );

		$starting_point = self::get_starting_point();
		$result         = array();
		$from           = ! empty( $args['from'] ) ? $starting_point + $args['from'] : $starting_point;
		$to             = ! empty( $args['to'] ) ? $starting_point + $args['to'] : $starting_point + DAY_IN_SECONDS;
		$timestamp      = $from;

		if ( $args['from_now'] && self::$timenow ) {
			$from = self::$timenow;
		}

		if ( ! is_integer( $timestamp ) ) {
			return $result;
		}

		while ( $timestamp <= $to ) {
			$result[]  = date( $args['format'], $timestamp );
			$timestamp += $args['interval'];
		}

		return $result;

	}

	/**
	 * Generate intervals
	 *
	 * @param  array  $args [description]
	 * @return [type]       [description]
	 */
	public static function generate_intervals( $args = array() ) {

		$args = wp_parse_args( $args, array(
			'from'          => '00:00',
			'to'            => '24:00',
			'duration'      => HOUR_IN_SECONDS,
			'buffer_before' => 0,
			'buffer_after'  => 0,
			'from_now'      => false, 
			'selected'      => [],
		) );

		$result   = array();
		$from     = self::get_timestamp_from_time( $args['from'] );
		$to       = self::get_timestamp_from_time( $args['to'] );
		$i        = $from;
		$em_brake = 0;

		if ( $args['from_now'] && self::$timenow ) {
			while ( $i < self::$timenow ) {
				$i = $i + $args['buffer_before'] + $args['duration'] + $args['buffer_after'];
			}
		}

		if ( ! is_integer( $from ) || ! is_integer( $to ) ) {
			return $result;
		}

		if ( ! is_integer( $i ) || ! is_integer( $to ) ) {
			return $result;
		}

		$duration = ! empty( $args['duration'] ) ? absint( $args['duration'] ) : 0;

		if ( empty( $duration ) ) {
			return $result;
		}

		$buffer_before = ! empty( $args['buffer_before'] ) ? absint( $args['buffer_before'] ) : 0;
		$buffer_after  = ! empty( $args['buffer_after'] ) ? absint( $args['buffer_after'] ) : 0;

		while ( $i < $to ) {

			$start = $i + $buffer_before;
			$end   = $start + $duration;
			$i     = $end + $buffer_after;

			if ( $start >= $to ) {
				break;
			}

			if ( $end >= $to ) {
				$end = $to;
			}

			$isSelected = false !== array_search( $start, array_column($args['selected'], 'slot') ) ? true : false ;

			$result[ $start ] = array(
				'from'     => $start,
				'to'       => $end,
				'selected' => $isSelected,
			);

			$em_brake++;

			if ( 100 < $em_brake ) {
				break;
			}

		}

		return $result;
	}

	/**
	 * Generate slots HTML markup
	 *
	 * @param  array  $slots  [description]
	 * @param  string $format [description]
	 * @return [type]         [description]
	 */
	public static function generate_slots_html( $slots = array(), $format = 'H:i', $dataset = array(), $date = '', $service = false ) {

		$dataset         = implode( ' ', $dataset );
		$manage_capacity = Plugin::instance()->settings->get( 'manage_capacity' );
		$show_counter    = Plugin::instance()->settings->get( 'show_capacity_counter' );
		$date = date( get_option( 'date_format' ), $date );

		/**
		 * Available values:
		 * %1$d - booked num
		 * %2$d - total num
		 * %3$d - available num
		 */
		$capacity_format = apply_filters(
			'jet-apb/time-slots/slots-html/capacity-format',
			'<small>(%3$d/%2$d)</small>'
		);

		if ( $manage_capacity && $service && $show_counter ) {
			$service_count = Plugin::instance()->tools->get_service_count( $service );
		}

		foreach ( $slots as $timestamp => $slot ) {
			$class = $slot['selected'] ? 'jet-apb-slot--selected' : '' ;
			if ( $manage_capacity && $show_counter ) {
				$count           = ! empty( $slot['slot_count'] ) ? $slot['slot_count'] : 0;
				$available_count = $service_count - $count;
				$capacity_html   = sprintf( $capacity_format, $count, $service_count, $available_count );
			} else {
				$capacity_html = '';
			}

			printf(
				'<div class="jet-apb-slot %8$s" data-slot="%1$s" data-slot-end="%2$s" data-date="%3$s" data-friendly-time="%4$s-%5$s" data-friendly-date="%9$s" %6$s>%4$s-%5$s %7$s</div>',
				$slot['from'],
				$slot['to'],
				self::$starting_point,
				date( ltrim( $format ), $slot['from'] ),
				date( rtrim( $format ), $slot['to'] ),
				$dataset,
				$capacity_html,
				$class,
				$date
			);
		}

	}

	/**
	 * Returns timestamp from human readable time
	 *
	 * @return [type] [description]
	 */
	public static function get_timestamp_from_time( $time ) {

		$time  = explode( ':', $time );
		$hours = absint( $time[0] );
		$mins  = absint( $time[1] );

		return self::$starting_point + $hours * HOUR_IN_SECONDS + $mins * MINUTE_IN_SECONDS;

	}

	/**
	 * Generate time slots array
	 *
	 * @param  array  $args [description]
	 * @return [type]       [description]
	 */
	public static function prepare_slots_for_js( $slots = array(), $l_format = false, $plain = false, $diff = false ) {

		$result = array();

		foreach ( $slots as $slot ) {

			$value = $slot;
			$label = ! empty( $l_format ) ? date( $l_format, $slot ) : $slot;

			if ( $diff ) {
				$value = $slot - self::$starting_point;
			}

			if ( $plain ) {
				$result[ $value ] = $label;
			} else {
				$result[] = array(
					'value' => $value,
					'label' => $label,
				);
			}
		}

		return $result;

	}

}