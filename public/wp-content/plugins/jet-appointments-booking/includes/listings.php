<?php
namespace JET_APB;

/**
 * JetEngine listings compatibility class
 */
class Listings {

	public $source = 'jet_apb_list';

	public function __construct() {

		add_filter(
			'jet-engine/templates/listing-sources',
			array( $this, 'add_source_option' )
		);

		add_filter(
			'jet-engine/listing/data/object-fields-groups',
			array( $this, 'add_source_fields' )
		);

		add_filter(
			'jet-engine/listing/grid/query/' . $this->source,
			array( $this, 'query' ), 10, 3
		);

		add_filter(
			'jet-engine/blocks-views/editor/config/object/' . $this->source,
			array( $this, 'setup_blocks_object' ), 10, 2
		);

		add_filter(
			'jet-engine/listing/render/object/' . $this->source,
			array( $this, 'get_block_preview_object' ), 10, 2
		);

		add_action(
			'jet-engine/listings/document/get-preview/' . $this->source,
			array( $this, 'setup_preview' )
		);

		add_action(
			'jet-engine/listing/custom-query-settings',
			array( $this, 'register_appointment_settings' )
		);

		add_filter(
			'jet-engine/listings/data/object-vars',
			array( $this, 'prepare_appointments_vars' ), 10
		);

		add_action( 'jet-engine/listings/frontend/reset-data', function( $data ) {
			if ( $this->source === $data->get_listing_source() ) {
				wp_reset_postdata();
			}
		} );

	}

	/**
	 * Prepare appintmnet variables
	 */
	public function prepare_appointments_vars( $vars ) {

		if ( isset( $vars['slot'] ) && isset( $vars['slot_end'] ) ) {

			$new_vars = array();

			foreach ( $vars as $key => $value ) {
				$new_vars[ $this->source . '__' . $key ] = $value;
			}

			$vars = array_merge( $vars, $new_vars );
		}

		return $vars;

	}

	/**
	 * Returns preview object
	 *
	 * @param  [type] $object    [description]
	 * @param  [type] $object_id [description]
	 * @return [type]            [description]
	 */
	public function get_block_preview_object( $object, $object_id ) {

		$flag = \OBJECT;
		Plugin::instance()->db->appointments->set_format_flag( $flag );

		return Plugin::instance()->db->get_appointment_by( 'ID', $object_id );
	}

	/**
	 * Set default blocks source
	 *
	 * @param [type] $object [description]
	 * @param [type] $editor [description]
	 */
	public function set_blocks_source( $object, $editor ) {

		$app_preview = $this->setup_preview();

		if ( ! empty( $app_preview ) ) {
			return $app_preview['ID'];
		} else {
			return false;
		}

	}

	/**
	 * Register appointment settings
	 *
	 * @return [type] [description]
	 */
	public function register_appointment_settings( $widget ) {

		$statuses = Plugin::instance()->statuses->get_all();
		$statuses = array( '' => __( 'Any status', 'jet-appointments-booking' ) ) + $statuses;

		$widget->start_controls_section(
			'section_apb_query',
			array(
				'label' => __( 'Appointments Query', 'jet-appointments-booking' ),
			)
		);

		$widget->add_control(
			$this->source . '_by_user',
			array(
				'label'   => __( 'User', 'jet-appointments-booking' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => 'all',
				'options' => array(
					'all'     => __( 'All', 'jet-appointments-booking' ),
					'current' => __( 'Current User', 'jet-appointments-booking' ),
					'id'      => __( 'Specific User ID', 'jet-appointments-booking' ),
					'queried' => __( 'Queried User', 'jet-appointments-booking' ),
				),
			)
		);

		$widget->add_control(
			$this->source . '_user_id',
			array(
				'label'     => __( 'User ID', 'jet-appointments-booking' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					$this->source . '_by_user' => array( 'id' ),
				),
			)
		);

		$widget->add_control(
			$this->source . '_status',
			array(
				'label'   => __( 'Status', 'jet-appointments-booking' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => $statuses,
			)
		);

		$widget->add_control(
			$this->source . '_date',
			array(
				'label'   => __( 'Date', 'jet-appointments-booking' ),
				'type'    => \Elementor\Controls_Manager::SELECT,
				'default' => '',
				'options' => array(
					''           => __( 'Any', 'jet-appointments-booking' ),
					'future'     => __( 'Future appointments', 'jet-appointments-booking' ),
					'past'       => __( 'Past appointments', 'jet-appointments-booking' ),
					'after_date' => __( 'After date', 'jet-appointments-booking' ),
					'up_to_date' => __( 'Up to date', 'jet-appointments-booking' ),
				),
			)
		);

		$widget->add_control(
			$this->source . '_custom_date',
			array(
				'label'     => __( 'Custom date', 'jet-appointments-booking' ),
				'type'      => \Elementor\Controls_Manager::TEXT,
				'default'   => '',
				'condition' => array(
					$this->source . '_date' => array( 'after_date', 'up_to_date' ),
				),
			)
		);

		$widget->end_controls_section();

	}

	/**
	 * Perform appointments query
	 *
	 * @param  [type] $query    [description]
	 * @param  [type] $settings [description]
	 * @return [type]           [description]
	 */
	public function query( $query, $settings, $widget ) {

		$flag = \OBJECT;
		Plugin::instance()->db->appointments->set_format_flag( $flag );

		$widget->query_vars['page']    = 1;
		$widget->query_vars['pages']   = 1;
		$widget->query_vars['request'] = false;

		$app_settings = array(
			'by_user'     => null,
			'user_id'     => null,
			'status'      => null,
			'date'        => null,
			'custom_date' => null,
		);

		foreach ( $app_settings as $setting => $value ) {
			$el_setting = $this->source . '_' . $setting;
			$app_settings[ $setting ] = ! empty( $settings[ $el_setting ] ) ? $settings[ $el_setting ] : null;
		}

		$query_args = array();

		if ( ! empty( $app_settings['status'] ) ) {
			$query_args['status'] = $app_settings['status'];
		}

		if ( ! empty( $app_settings['date'] ) ) {

			switch ( $app_settings['date'] ) {

				case 'future':
					$query_args['date>='] = strtotime( 'today' );
					break;

				case 'past':
					$query_args['date<='] = strtotime( 'today' );
					break;

				case 'after_date':
					$query_args['date>='] = strtotime( $app_settings['custom_date'] );
					break;

				case 'up_to_date':
					$query_args['date<='] = strtotime( $app_settings['custom_date'] );
					break;

			}

		}

		if ( ! empty( $app_settings['by_user'] ) ) {

			switch ( $app_settings['by_user'] ) {

				case 'current':
					$query_args['by_user'] = get_current_user_id();
					break;

				case 'id':
					$query_args['by_user'] = ! empty( $app_settings['user_id'] ) ? absint( $app_settings['user_id'] ) : false;
					break;

				case 'queried':

					$u_id = false;

					if ( is_author() ) {
						$u_id = get_queried_object_id();
					} elseif ( jet_engine()->modules->is_module_active( 'profile-builder' ) ) {
						$u_id = \Jet_Engine\Modules\Profile_Builder\Module::instance()->query->get_queried_user_id();
					}

					if ( ! $u_id ) {
						$u_id = get_current_user_id();
					}

					$query_args['by_user'] = $u_id;

					break;

			}

			if ( 'all' !== $app_settings['by_user'] && empty( $query_args['by_user'] ) ) {
				return array();
			}

		}

		$appointments = Plugin::instance()->db->appointments->query( $query_args, absint( $settings['posts_num'] ) );
		$appointments = array_map( function( $appointment ) {
			$appointment->post_type = $this->source;
			return $appointment;
		}, $appointments );

		return $appointments;

	}

	/**
	 * Setup preview
	 *
	 * @return [type] [description]
	 */
	public function setup_preview() {

		$flag = \OBJECT;
		Plugin::instance()->db->appointments->set_format_flag( $flag );

		$appointments = Plugin::instance()->db->appointments->query( array(), 1 );

		if ( ! empty( $appointments ) ) {
			$appointments[0]->post_type = $this->source;
			jet_engine()->listings->data->set_current_object( $appointments[0] );
			return $appointments[0];
		} else {
			return false;
		}

	}

	/**
	 * Setup blocks preview object ID
	 */
	public function setup_blocks_object() {

		$object = $this->setup_preview();

		if ( $object ) {
			return $object->ID;
		} else {
			return false;
		}
	}

	/**
	 * Register appointments source for create listing popup
	 *
	 * @param [type] $sources [description]
	 */
	public function add_source_option( $sources ) {
		$sources[ $this->source ] = __( 'User Appointments', 'jet-appointments-booking' );
		return $sources;
	}

	/**
	 * Register Appointment object fields
	 *
	 * @param [type] $groups [description]
	 */
	public function add_source_fields( $groups ) {

		$default_columns    = Plugin::instance()->db->appointments->schema();
		$default_columns    = array_keys( $default_columns );
		$additional_columns = Plugin::instance()->settings->get( 'db_columns' );
		$additional_columns = array_values( $additional_columns );
		$fields             = array_merge( $default_columns, $additional_columns );

		$prefixed_fields = array_map( function( $item ) {
			return $this->source . '__' . $item;
		}, $fields );

		$groups[] = array(
			'label'  => __( 'Appointment', 'jet-appointments-booking' ),
			'options' => array_combine( $prefixed_fields, $fields ),
		);

		return $groups;

	}

}
