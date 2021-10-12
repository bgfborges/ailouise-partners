<?php
namespace JET_APB\Admin;

use JET_APB\Plugin;

/**
 * Settings manager
 */
class Settings {

	/**
	 * Default settings array
	 *
	 * @var array
	 */
	public $defaults = [];

	/**
	 * Main settings array
	 *
	 * @var array
	 */
	public $main_settings = [
		'is_set'                => false,
		'services_cpt'          => '',
		'providers_cpt'         => '',
		'db_columns'            => [],
		'wc_integration'        => false,
		'wc_product_id'         => false,
		'hide_setup'            => false,
		'check_by'              => 'global',
		'manage_capacity'       => false,
		'show_capacity_counter' => false,
		'use_custom_labels'     => false,
		'custom_labels'         => [
			'Sun'       => 'Sun',
			'Mon'       => 'Mon',
			'Tue'       => 'Tue',
			'Wed'       => 'Wed',
			'Thu'       => 'Thu',
			'Fri'       => 'Fri',
			'Sat'       => 'Sat',
			'January'   => 'January',
			'February'  => 'February',
			'March'     => 'March',
			'April'     => 'April',
			'May'       => 'May',
			'June'      => 'June',
			'July'      => 'July',
			'August'    => 'August',
			'September' => 'September',
			'October'   => 'October',
			'November'  => 'November',
			'December'  => 'December',
		],
	];

	/**
	 * Working Hours Config array
	 *
	 * @var array
	 */
	public $working_hours = [
		'slot_time_format' => 'H:i',
		'buffer_before'  => 0,
		'buffer_after'   => 0,
		'default_slot'   => 1800,
		'locked_time'    => 0,
		'booking_type'   => 'slot',
		'min_slot_time'  => 1800,
		'max_slot_time'  => 3600,
		'several_days'   => false,
		'multi_booking'  => false,
		'min_slot_count' => 1,
		'max_slot_count' => 5,
		'days_off'       => [],
		'working_days'   => [],
		're_booking'     => [],
		'working_hours'  =>[
			'monday'    => [
				[ 'from' => '08:00', 'to' => '17:00' ]
			],
			'tuesday'   => [
				[ 'from' => '08:00', 'to' => '17:00' ]
			],
			'wednesday' => [
				[ 'from' => '08:00', 'to' => '17:00' ]
			],
			'thursday'  => [
				[ 'from' => '08:00', 'to' => '17:00' ]
			],
			'friday'    => [
				[ 'from' => '08:00', 'to' => '17:00' ]
			],
			'saturday'  => [],
			'sunday'    => [],
		],
	];

	/**
	 * Assets Config array
	 *
	 * @var array
	 */
	public $assets = [];

	/**
	 * Settings DB key
	 *
	 * @var string
	 */
	private $key = 'jet-apb-settings';

	/**
	 * Stored settings cache
	 *
	 * @var null
	 */
	public $settings = null;

	/**
	 * [__construct description]
	 * @param array $pages [description]
	 */
	public function __construct() {
		$this->set_assets();
		$this->set_default_config();

		add_action( 'wp_ajax_jet_apb_save_settings', array( $this, 'ajax_save_settings' ) );
		add_action( 'wp_ajax_jet_apb_clear_excluded', array( $this, 'reset_excluded_dates' ) );
	}

	/**
	 * Reset excluded dates data
	 *
	 * @return [type] [description]
	 */
	private function set_default_config() {
		$this->defaults = wp_parse_args( $this->main_settings, $this->working_hours );
	}

	/**
	 * Reset excluded dates data
	 *
	 * @return [type] [description]
	 */
	private function set_assets() {
		$this->assets = [
			'weekdays'   => [
				'monday'    => esc_html__( 'Monday', 'jet-appointments-booking' ),
				'tuesday'   => esc_html__( 'Tuesday', 'jet-appointments-booking' ),
				'wednesday' => esc_html__( 'Wednesday', 'jet-appointments-booking' ),
				'thursday'  => esc_html__( 'Thursday', 'jet-appointments-booking' ),
				'friday'    => esc_html__( 'Friday', 'jet-appointments-booking' ),
				'saturday'  => esc_html__( 'Saturday', 'jet-appointments-booking' ),
				'sunday'    => esc_html__( 'Sunday', 'jet-appointments-booking' ),
			],
			'booking_types' => [
				[
					'value' => 'slot',
					'label' => esc_html__( 'Slot', 'jet-appointments-booking' ),
				],
				[
					'value' => 'free_time',
					'label' => esc_html__( 'Free Time', 'jet-appointments-booking' ),
				],
				[
					'value' => 'range',
					'label' => esc_html__( 'Range', 'jet-appointments-booking' ),
				],
				[
					'value' => 'recurring',
					'label' => esc_html__( 'Recurring', 'jet-appointments-booking' ),
				],
			],
			'rebooking_options' => [
				[
					'value' => 'day',
					'label' => esc_html__( 'Day', 'jet-appointments-booking' ),
				],
				[
					'value' => 'week',
					'label' => esc_html__( 'Week', 'jet-appointments-booking' ),
				],
				[
					'value' => 'month',
					'label' => esc_html__( 'Month', 'jet-appointments-booking' ),
				],
				[
					'value' => 'year',
					'label' => esc_html__( 'Year', 'jet-appointments-booking' ),
				],
			],
			'slot_time_format' => [
				[
					'value' => '',
					'label' => esc_html__( 'Select...', 'jet--appointments-booking' ),
				],
				[
					'value' => 'H:i',
					'label' => '13:00 - 14:00',
				],
				[
					'value' => 'g:i a',
					'label' => '1:00 pm - 2:00 pm',
				],
				[
					'value' => 'g:i A',
					'label' => '1:00 PM - 2:00 PM',
				],
			]
		];
	}

	/**
	 * Reset excluded dates data
	 *
	 * @return [type] [description]
	 */
	public function reset_excluded_dates() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => 'Access denied',
			) );
		}

		Plugin::instance()->db->excluded_dates->clear();

	}

	/**
	 * Save settings by ajax request
	 *
	 * @return [type] [description]
	 */
	public function ajax_save_settings() {
		
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array(
				'message' => 'Access denied',
			) );
		}
		
		$data              = ! empty( $_REQUEST['settings'] ) ? json_decode( stripcslashes( $_REQUEST['settings'] ), true ) : [];
		$settings          = wp_parse_args( $data, $this->defaults );
		$update_db_columns = ! empty( $_REQUEST['update_db_columns'] ) ? $_REQUEST['update_db_columns'] : false;
		$update_db_columns = filter_var( $update_db_columns, FILTER_VALIDATE_BOOLEAN );
		
		if ( empty( $settings ) ) {
			wp_send_json_error( array(
				'message' => 'Empty data',
			) );
		}

		foreach ( $settings as $setting => $value ) {
			if ( $this->setting_registered( $setting ) ) {

				switch ( $setting ) {

					case 'working_days':
					case 'days_off':
					case 're_booking':

						if ( ! is_array( $value ) ) {
							$value = false;
						}

						break;

					case 'buffer_before':
					case 'buffer_after':
					case 'locked_time':
					case 'default_slot':
					case 'min_slot_time':
					case 'max_slot_time':
						$value = intval( abs( ceil( $value ) ) );
						break;
					case 'min_slot_count':
					case 'max_slot_count':
						if( empty( $value )){
							$value = 1;
						}else{
							$value = intval( ceil( $value ) );
							$value = $value <= 0 ? 1 : $value ;
						}
						break;
					case 'wc_integration':
					case 'hide_setup':
					case 'manage_capacity':
					case 'show_capacity_counter':
					case 'use_custom_labels':
					case 'several_days':
					case 'multi_booking':
						$value = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
						break;

					case 'db_columns':

						$old_columns = $this->get( 'db_columns' );

						if ( $update_db_columns ) {
							$value = $this->process_columns_diff( $value, $old_columns );
						} else {
							$value = $old_columns;
						}

						break;

				}

				$this->update( $setting, $value, false );

			}
		}

		$this->write();

		wp_send_json_success( array(
			'message' => __( 'Settings saved!', 'jet-appointments-booking' ),
		) );

	}

	/**
	 * The function processes data before localization. Added in version 1.2.0. Remove in version 1.5.0.
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function passe_settings( $settings ) {

		if ( empty( $settings ) ) {
			return $settings;
		}

		if ( isset( $settings['days_off'] ) && ! empty( $settings['days_off'] ) ) {
			$new_days_off = [];

			foreach ( $settings['days_off'] as $value ) {

				if( ! isset( $value["date"] ) ){
					$new_days_off[] = $value;
					continue;
				}

				$start          = $value["date"];
				$startTimeStamp = strtotime( $start );
				$new_days_off[] = [
					'start'          => $start,
					'startTimeStamp' => $startTimeStamp,
					'end'            => $start,
					'endTimeStamp'   => $startTimeStamp,
					'name'           => $value["name"],
					'type'           => 'days_off',
				];

			}

			$settings['days_off'] = $new_days_off;
		}

		return $settings;
	}

	/**
	 * Process columns difference and returns santizzed new columns list
	 * @param  [type] $new_columns [description]
	 * @param  [type] $old_columns [description]
	 * @return [type]              [description]
	 */
	public function process_columns_diff( $new_columns, $old_columns ) {

		$new_columns = $this->sanitize_columns( $new_columns );

		$to_delete = array_diff( $old_columns, $new_columns );
		$to_add    = array_diff( $new_columns, $old_columns );

		if ( ! empty( $to_delete ) ) {
			Plugin::instance()->db->appointments->delete_table_columns( $to_delete );
		}

		if ( ! empty( $to_add ) ) {
			Plugin::instance()->db->appointments->insert_table_columns( $to_add );
		}

		return $new_columns;

	}

	/**
	 * Sanitize SQL table columns list names
	 *
	 * @param  array  $columns [description]
	 * @return [type]          [description]
	 */
	public function sanitize_columns( $columns = [] ) {

		if ( empty( $columns ) ) {
			return [];
		}

		$sanitized = [];

		foreach ( array_filter( $columns ) as $column ) {
			$sanitized[] = $this->sanitize_column( $column );
		}

		return $sanitized;

	}

	/**
	 * Sanitize single DB column
	 * @param  [type] $column [description]
	 * @return [type]         [description]
	 */
	public function sanitize_column( $column ) {
		return sanitize_key( str_replace( ' ', '_', $column ) );
	}

	/**
	 * Return all settings and setup settings cache
	 *
	 * @return [type] [description]
	 */
	public function get_all() {
		if ( null === $this->settings ) {
			$this->settings = get_option( $this->key, [] );

			if( $this->settings && ! isset( $this->settings['buffer_before'] ) ){
				$this->settings['buffer_before'] = isset( $this->settings['default_buffer_before'] ) ? $this->settings['default_buffer_before'] : 0 ;
			}

			if( $this->settings && ! isset( $this->settings['buffer_after'] ) ){
				$this->settings['buffer_after'] = isset( $this->settings['default_buffer_after'] ) ? $this->settings['default_buffer_after'] : 0 ;
			}

			$this->settings = wp_parse_args( $this->settings, $this->defaults );
			$this->settings = $this->passe_settings( $this->settings );

			if ( empty( $this->settings['custom_labels'] ) ) {
				$this->settings['custom_labels'] = $this->defaults['custom_labels'];
			}

		}

		return $this->settings;
	}

	/**
	 * Get setting by name
	 *
	 * @param  [type] $setting [description]
	 * @return [type]          [description]
	 */
	public function get( $setting ) {
		$settings = $this->get_all();
		
		if ( isset( $settings[ $setting ] ) ) {
			return $settings[ $setting ];
		} else {
			return isset( $this->defaults[ $setting ] ) ? $this->defaults[ $setting ] : null;
		}

	}

	/**
	 * Update setting in cahce and database
	 *
	 * @param  [type]  $setting [description]
	 * @param  boolean $write   [description]
	 * @return [type]           [description]
	 */
	public function update( $setting = null, $value = null, $write = true ) {

		$this->get_all();

		/**
		 * Modify options before write into DB
		 */
		do_action( 'jet-apb/settings/before-update', $this->settings, $setting, $value );

		$this->settings[ $setting ] = $value;

		if ( $write ) {
			$this->write();
		}

	}

	/**
	 * Write settings cache
	 * @return [type] [description]
	 */
	public function write() {

		/**
		 * Modify options before write into DB
		 */
		do_action( 'jet-apb/settings/before-write', $this );

		update_option( $this->key, $this->settings, false );
	}

	/**
	 * Check if passed settings is registered in defaults
	 *
	 * @return [type] [description]
	 */
	public function setting_registered( $setting = null ) {
		return ( $setting && isset( $this->defaults[ $setting ] ) );
	}

}