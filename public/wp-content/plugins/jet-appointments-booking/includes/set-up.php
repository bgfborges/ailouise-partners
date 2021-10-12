<?php

namespace JET_APB;

use JET_APB\Admin\Settings\General;
use JET_APB\Formbuilder_Plugin\Form_Builder;
use JET_APB\Formbuilder_Plugin\Form_Manager;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin setup class
 */
class Set_Up {

	public $setup_page = null;

	public $success_page = null;
	
	public function __construct() {
		add_filter( 'jet-apb/admin/helpers/page-config/config', array( $this, 'check_setup' ) );
		add_action( 'wp_ajax_jet_apb_setup', array( $this, 'process_setup' ) );
		add_action( 'init', [ $this, 'register_setup_success_page' ], 11 );

		if ( isset( $_GET['jet_apb_upgrade'] ) ) {
			$this->upgrade_db();
		}
	}

	/**
	 * Upgrade DB to use slot_end logic
	 *
	 * @return [type] [description]
	 */
	public function upgrade_db() {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! Plugin::instance()->db->appointments->column_exists( 'slot_end' ) ) {
			Plugin::instance()->db->appointments->insert_table_columns( array( 'slot_end' ) );
		}

		$appointments = Plugin::instance()->db->appointments->query();

		if ( ! empty( $appointments ) ) {
			foreach ( $appointments as $appointment ) {

				$service_id = ! empty( $appointment['service'] ) ? $appointment['service'] : null;

				if ( ! $service_id ) {
					continue;
				}

				$duration = get_post_meta( $service_id, '_service_duration', true );

				if ( ! $duration ) {
					$duration = Plugin::instance()->settings->get( 'default_slot' );
				}

				if ( ! $duration ) {
					continue;
				}

				$slot_end = $appointment['slot'] + $duration;
				$where    = array( 'ID' => $appointment['ID'] );

				Plugin::instance()->db->appointments->update( array(
					'slot_end' => $slot_end,
				), $where );

			}
		}

	}

	/**
	 * Process setup
	 *
	 * @return [type] [description]
	 */
	public function process_setup() {

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error();
		}
		
		$default       = Plugin::instance()->settings->get_all();
		$setup_data    = ! empty( $_REQUEST['setup_data'] ) ? wp_parse_args( $_REQUEST['setup_data'], $default ) : array();
		$db_columns    = ! empty( $_REQUEST['db_columns'] ) ? wp_parse_args( $_REQUEST['db_columns'], $default['db_columns'] )  : array();
		$add_providers = false;
		$create_forms  = array();

		if ( ! isset( $setup_data['wc_integration'] ) ) {
			$setup_data['wc_integration'] = false;
		}

		$bool = array(
			'create_single_form',
			'create_page_form',
			'wc_integration',
			'use_custom_labels',
			'show_capacity_counter',
			'hide_setup',
			'is_set',
			'manage_capacity',
			'multi_booking',
			'several_days',
		);

		$form_actions = array(
			'create_single_form',
			'create_page_form',
		);

		$mixed = array(
			'days_off',
			'working_days',
			'working_hours',
		);

		if ( ! empty( $setup_data ) ) {
			foreach ( $setup_data as $setting => $value ) {

				if ( 'add_providers' === $setting ) {
					$add_providers = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
					continue;
				}

				if ( in_array( $setting, $mixed ) ) {
					if ( ! is_array( $value ) ) {
						$value = false;
					}
				} elseif ( in_array( $setting, $bool ) ) {
					$value = filter_var( $value, FILTER_VALIDATE_BOOLEAN );
				} else {
					$value = is_array( $value ) ? $value : esc_attr( $value );
				}

				if ( Plugin::instance()->settings->setting_registered( $setting ) ) {
					Plugin::instance()->settings->update( $setting, $value, false );
				} elseif ( in_array( $setting, $form_actions ) ) {
					if ( $value ) {
						$create_forms[] = $setting;
					}
				}
			}
		}

		$cols = array();
		foreach ( $db_columns as $column ) {
			if ( ! empty( $column['column'] ) ) {

				$col    = Plugin::instance()->settings->sanitize_column( $column['column'] );
				$cols[] = $col;

				Plugin::instance()->db->appointments->add_column( $col );

			}
		}
		Plugin::instance()->settings->update( 'db_columns', $cols, false );


		if ( ! $add_providers ) {
			Plugin::instance()->settings->update( 'providers_cpt', false, false );
		}

		Plugin::instance()->db->appointments->create_table( true );
		Plugin::instance()->db->excluded_dates->create_table( true );

		Plugin::instance()->settings->update( 'is_set', true, false );
		Plugin::instance()->settings->write();

		$created_forms = array();
		$form_provider = empty( $setup_data['form_provider'] ) ? false : $setup_data['form_provider'];
		$can_insert_jfb = false;

		if ( 'jfb' === $form_provider ) {
			$result = Form_Builder::install_and_activate();
			if ( ! empty( $result['need_init'] ) ) {
				jet_form_builder_init();
				jet_form_builder()->init_components();
				jet_form_builder()->post_type->register_post_type();
			}
			if ( ! empty( $result['success'] ) ) {
				$can_insert_jfb = true;
			}
		}

		if ( ! empty( $create_forms ) && $form_provider ) {
			foreach ( $create_forms as $form ) {
				switch ( $form_provider ) {
					case 'jfb':
						if ( $can_insert_jfb ) {
							$created_forms[] = Form_Manager::instance()->insert_form( $form );
						}
						break;
					case 'jef':
						$created_forms[] = $this->insert_form( $form, $add_providers );
						break;
				}
			}
		}

		$edit_link     = esc_url( admin_url( 'edit.php' ) );
		$services_cpt  = Plugin::instance()->settings->get( 'services_cpt' );
		$providers_cpt = Plugin::instance()->settings->get( 'providers_cpt' );

		$services_page_link = add_query_arg(
			array( 'post_type' => $services_cpt ),
			$edit_link
		);

		if ( $providers_cpt ) {
			$providers_page_link = add_query_arg(
				array( 'post_type' => $providers_cpt ),
				$edit_link
			);
		} else {
			$providers_page_link = false;
		}

		$product_id = Plugin::instance()->settings->get( 'wc_product_id' );

		if ( $product_id ) {
			$product_link = get_edit_post_link( $product_id, 'url' );
		} else {
			$product_link = false;
		}

		wp_send_json_success( array(
			'settings_url'   => $this->success_page->get_page_link(),
			'services_page'  => $services_page_link,
			'providers_page' => $providers_page_link,
			'forms'          => array_filter( $created_forms ),
			'wc'             => array(
				'enabled' => Plugin::instance()->settings->get( 'wc_integration' ),
				'link'    => $product_link,
			),
		) );

	}

	/**
	 * Insert form
	 *
	 * @return [type] [description]
	 */
	public function insert_form( $form, $add_providers = false ) {

		if ( ! jet_engine()->modules->is_module_active( 'booking-forms' ) ) {
			jet_engine()->modules->activate_module( 'booking-forms' );
		}

		$post_title         = __( 'Booking Form', 'jet-appointments-booking' );
		$form_data          = '[]';
		$notifications_data = '[]';

		switch ( $form ) {
			case 'create_single_form':

				$post_title = __( 'Single Service Booking Form', 'jet-appointments-booking' );

				if ( ! $add_providers ) {
					$form_data = '[{\"x\":0,\"y\":0,\"w\":12,\"h\":1,\"i\":\"0\",\"settings\":{\"name\":\"service_id\",\"desc\":\"\",\"required\":\"required\",\"type\":\"hidden\",\"hidden_value\":\"post_id\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"Current Post ID\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"default\":\"\"},\"moved\":false},{\"x\":0,\"y\":3,\"w\":12,\"h\":1,\"i\":\"1\",\"settings\":{\"label\":\"Book Now\",\"name\":\"Submit\",\"is_message\":false,\"is_submit\":true,\"type\":\"submit\",\"alignment\":\"right\",\"class_name\":\"\"},\"moved\":false},{\"x\":0,\"y\":2,\"w\":12,\"h\":1,\"i\":\"2\",\"settings\":{\"name\":\"appointment_date\",\"desc\":\"\",\"required\":\"required\",\"type\":\"appointment_date\",\"visibility\":\"all\",\"field_type\":\"text\",\"hidden_value\":\"\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"is_page_break\":false,\"class_name\":\"\",\"appointment_service_field\":\"current_post_id\"},\"moved\":false},{\"x\":0,\"y\":1,\"w\":12,\"h\":1,\"i\":\"3\",\"settings\":{\"name\":\"user_email\",\"desc\":\"\",\"required\":\"required\",\"type\":\"text\",\"visibility\":\"all\",\"field_type\":\"email\",\"hidden_value\":\"\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"User e-mail\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"is_page_break\":false,\"class_name\":\"\"},\"moved\":false}]';
					$notifications_data = '[{\"type\":\"insert_appointment\",\"mail_to\":\"admin\",\"hook_name\":\"\",\"custom_email\":\"\",\"from_field\":\"\",\"post_type\":\"\",\"fields_map\":{},\"log_in\":\"\",\"email\":{\"content\":\"Hi admin!\\r\\n\\r\\nThere are new order on your website.\\r\\n\\r\\nOrder details:\\r\\n- Post ID: %post_id%\",\"subject\":\"New order on website\"},\"appointment_email_field\":\"user_email\",\"appointment_service_field\":\"service_id\",\"appointment_date_field\":\"appointment_date\"}]';
				} else {
					$form_data = '[{\"x\":0,\"y\":0,\"w\":12,\"h\":1,\"i\":\"0\",\"settings\":{\"name\":\"service_id\",\"desc\":\"\",\"required\":\"required\",\"type\":\"hidden\",\"hidden_value\":\"post_id\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"Current Post ID\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"default\":\"\"},\"moved\":false},{\"x\":0,\"y\":4,\"w\":12,\"h\":1,\"i\":\"1\",\"settings\":{\"label\":\"Book Now\",\"name\":\"Submit\",\"is_message\":false,\"is_submit\":true,\"type\":\"submit\",\"alignment\":\"right\",\"class_name\":\"\"},\"moved\":false},{\"x\":0,\"y\":3,\"w\":12,\"h\":1,\"i\":\"2\",\"settings\":{\"name\":\"appointment_date\",\"desc\":\"\",\"required\":\"required\",\"type\":\"appointment_date\",\"visibility\":\"all\",\"field_type\":\"text\",\"hidden_value\":\"\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"is_page_break\":false,\"class_name\":\"\",\"appointment_service_field\":\"current_post_id\",\"appointment_provider_field\":\"form_field\",\"appointment_provider_form_field\":\"provider_id\"},\"moved\":false},{\"x\":0,\"y\":1,\"w\":12,\"h\":1,\"i\":\"3\",\"settings\":{\"name\":\"user_email\",\"desc\":\"\",\"required\":\"required\",\"type\":\"text\",\"visibility\":\"all\",\"field_type\":\"email\",\"hidden_value\":\"\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"User e-mail\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"is_page_break\":false,\"class_name\":\"\"},\"moved\":false},{\"x\":0,\"y\":2,\"w\":12,\"h\":1,\"i\":\"4\",\"settings\":{\"name\":\"provider_id\",\"desc\":\"\",\"required\":\"required\",\"type\":\"appointment_provider\",\"visibility\":\"all\",\"field_type\":\"text\",\"hidden_value\":\"\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"Select provider\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"is_page_break\":false,\"class_name\":\"\",\"appointment_service_field\":\"current_post_id\"},\"moved\":false}]';
					$notifications_data = '[{\"type\":\"insert_appointment\",\"mail_to\":\"admin\",\"hook_name\":\"\",\"custom_email\":\"\",\"from_field\":\"\",\"post_type\":\"\",\"fields_map\":{},\"log_in\":\"\",\"email\":{\"content\":\"Hi admin!\\r\\n\\r\\nThere are new order on your website.\\r\\n\\r\\nOrder details:\\r\\n- Post ID: %post_id%\",\"subject\":\"New order on website\"},\"appointment_email_field\":\"user_email\",\"appointment_service_field\":\"service_id\",\"appointment_date_field\":\"appointment_date\",\"appointment_provider_field\":\"provider_id\"}]';
				}

				break;

			case 'create_page_form':

				$post_title = __( 'Static Page Booking Form', 'jet-appointments-booking' );

				if ( ! $add_providers ) {
					$form_data          = '[{\"x\":0,\"y\":0,\"w\":12,\"h\":1,\"i\":\"0\",\"settings\":{\"name\":\"page_id\",\"desc\":\"\",\"required\":\"required\",\"type\":\"hidden\",\"hidden_value\":\"post_id\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"Current Post ID\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"default\":\"\"},\"moved\":false},{\"x\":0,\"y\":5,\"w\":12,\"h\":1,\"i\":\"1\",\"settings\":{\"label\":\"Book Now\",\"name\":\"Submit\",\"is_message\":false,\"is_submit\":true,\"type\":\"submit\",\"alignment\":\"right\",\"class_name\":\"\"},\"moved\":false},{\"x\":0,\"y\":2,\"w\":12,\"h\":1,\"i\":\"2\",\"settings\":{\"name\":\"appointment_date\",\"desc\":\"\",\"required\":\"required\",\"type\":\"appointment_date\",\"visibility\":\"all\",\"field_type\":\"text\",\"hidden_value\":\"\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"is_page_break\":false,\"class_name\":\"\",\"appointment_service_field\":\"form_field\",\"appointment_provider_field\":\"\",\"appointment_provider_form_field\":\"\",\"appointment_form_field\":\"service_id\"},\"moved\":false},{\"x\":0,\"y\":4,\"w\":12,\"h\":1,\"i\":\"3\",\"settings\":{\"name\":\"user_email\",\"desc\":\"\",\"required\":\"required\",\"type\":\"text\",\"visibility\":\"all\",\"field_type\":\"email\",\"hidden_value\":\"\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"User e-mail\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"is_page_break\":false,\"class_name\":\"\"},\"moved\":false},{\"x\":0,\"y\":1,\"w\":12,\"h\":1,\"i\":\"5\",\"settings\":{\"name\":\"service_id\",\"desc\":\"\",\"required\":\"required\",\"type\":\"select\",\"visibility\":\"all\",\"field_type\":\"text\",\"hidden_value\":\"\",\"hidden_value_field\":\"\",\"field_options_from\":\"posts\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"Select service\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"is_page_break\":false,\"class_name\":\"\",\"field_options_post_type\":\"services_cpt_here\",\"placeholder\":\"Select...\"},\"moved\":false}]';
					$notifications_data = '[{\"type\":\"insert_appointment\",\"mail_to\":\"admin\",\"hook_name\":\"\",\"custom_email\":\"\",\"from_field\":\"\",\"post_type\":\"\",\"fields_map\":{},\"log_in\":\"\",\"email\":{\"content\":\"Hi admin!\\r\\n\\r\\nThere are new order on your website.\\r\\n\\r\\nOrder details:\\r\\n- Post ID: %post_id%\",\"subject\":\"New order on website\"},\"appointment_email_field\":\"user_email\",\"appointment_service_field\":\"service_id\",\"appointment_date_field\":\"appointment_date\",\"appointment_provider_field\":\"\"}]';
				} else {
					$form_data          = '[{\"x\":0,\"y\":0,\"w\":12,\"h\":1,\"i\":\"0\",\"settings\":{\"name\":\"page_id\",\"desc\":\"\",\"required\":\"required\",\"type\":\"hidden\",\"hidden_value\":\"post_id\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"Current Post ID\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"default\":\"\"},\"moved\":false},{\"x\":0,\"y\":5,\"w\":12,\"h\":1,\"i\":\"1\",\"settings\":{\"label\":\"Book Now\",\"name\":\"Submit\",\"is_message\":false,\"is_submit\":true,\"type\":\"submit\",\"alignment\":\"right\",\"class_name\":\"\"},\"moved\":false},{\"x\":0,\"y\":3,\"w\":12,\"h\":1,\"i\":\"2\",\"settings\":{\"name\":\"appointment_date\",\"desc\":\"\",\"required\":\"required\",\"type\":\"appointment_date\",\"visibility\":\"all\",\"field_type\":\"text\",\"hidden_value\":\"\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"is_page_break\":false,\"class_name\":\"\",\"appointment_service_field\":\"form_field\",\"appointment_provider_field\":\"form_field\",\"appointment_provider_form_field\":\"provider_id\",\"appointment_form_field\":\"service_id\"},\"moved\":false},{\"x\":0,\"y\":4,\"w\":12,\"h\":1,\"i\":\"3\",\"settings\":{\"name\":\"user_email\",\"desc\":\"\",\"required\":\"required\",\"type\":\"text\",\"visibility\":\"all\",\"field_type\":\"email\",\"hidden_value\":\"\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"User e-mail\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"is_page_break\":false,\"class_name\":\"\"},\"moved\":false},{\"x\":0,\"y\":2,\"w\":12,\"h\":1,\"i\":\"4\",\"settings\":{\"name\":\"provider_id\",\"desc\":\"\",\"required\":\"required\",\"type\":\"appointment_provider\",\"visibility\":\"all\",\"field_type\":\"text\",\"hidden_value\":\"\",\"hidden_value_field\":\"\",\"field_options_from\":\"manual_input\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"Select provider\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"is_page_break\":false,\"class_name\":\"\",\"appointment_service_field\":\"form_field\",\"appointment_form_field\":\"service_id\"},\"moved\":false},{\"x\":0,\"y\":1,\"w\":12,\"h\":1,\"i\":\"5\",\"settings\":{\"name\":\"service_id\",\"desc\":\"\",\"required\":\"required\",\"type\":\"select\",\"visibility\":\"all\",\"field_type\":\"text\",\"hidden_value\":\"\",\"hidden_value_field\":\"\",\"field_options_from\":\"posts\",\"field_options_key\":\"\",\"field_options\":[],\"label\":\"Select service\",\"calc_formula\":\"\",\"precision\":2,\"is_message\":false,\"is_submit\":false,\"is_page_break\":false,\"class_name\":\"\",\"field_options_post_type\":\"services_cpt_here\"},\"moved\":false}]';
					$notifications_data = '[{\"type\":\"insert_appointment\",\"mail_to\":\"admin\",\"hook_name\":\"\",\"custom_email\":\"\",\"from_field\":\"\",\"post_type\":\"\",\"fields_map\":{},\"log_in\":\"\",\"email\":{\"content\":\"Hi admin!\\r\\n\\r\\nThere are new order on your website.\\r\\n\\r\\nOrder details:\\r\\n- Post ID: %post_id%\",\"subject\":\"New order on website\"},\"appointment_email_field\":\"user_email\",\"appointment_service_field\":\"service_id\",\"appointment_date_field\":\"appointment_date\",\"appointment_provider_field\":\"provider_id\"}]';
				}

				$form_data = str_replace(
					'services_cpt_here',
					Plugin::instance()->settings->get( 'services_cpt' ),
					$form_data
				);

				break;
		}

		$post_id = wp_insert_post( array(
			'post_title'  => $post_title,
			'post_type'   => 'jet-engine-booking',
			'post_status' => 'publish',
			'meta_input'  => array(
				'_captcha' => array(
					'enabled' => false,
					'key'     => '',
					'secret'  => '',
				),
				'_preset'  => array(
					'enabled'    => false,
					'from'       => 'post',
					'post_from'  => 'current_post',
					'user_from'  => 'current_user',
					'query_var'  => '_post_id',
					'fields_map' => array(),
				),
			),
		) );

		if ( ! $post_id || is_wp_error( $post_id ) ) {
			return false;
		} else {

			update_post_meta( $post_id, '_form_data', $form_data );
			update_post_meta( $post_id, '_notifications_data', $notifications_data );

			return array(
				'id'    => $post_id,
				'title' => $post_title,
				'link'  => get_edit_post_link( $post_id, 'url' ),
			);
		}

	}

	/**
	 * Register setup page for the plugin.
	 * If page already registerd will throw the error
	 *
	 * @param  [type] $setup [description]
	 *
	 * @return [type]        [description]
	 */
	public function register_setup_page( $setup_page ) {
		if ( null !== $this->setup_page ) {
			trigger_error( 'Setup page is already registered!' );
		} else {
			$this->setup_page = $setup_page;
		}
	}

	/**
	 * Register setup success page
	 *
	 * @return [type] [description]
	 */
	public function register_setup_success_page() {
		if ( class_exists( '\JET_APB\Admin\Settings\General' ) ) {
			$this->success_page = new \JET_APB\Admin\Settings\General();
		}
	}

	/**
	 * Check if plugin is correctly configured and pass this data into appropriate
	 * @return [type] [description]
	 */
	public function check_setup( $args = array() ) {

		$args['setup'] = [
			'is_set'                 => true,
			'setup_url'              => null,
			'is_active_form_builder' => function_exists( 'jet_form_builder' )
		];

		if ( $this->setup_page ) {

			$is_set = Plugin::instance()->settings->get( 'is_set' );

			if ( $is_set ) {
				$is_set = Plugin::instance()->db->appointments->is_table_exists();
			}

			$result = array(
				'is_set'    => $is_set,
				'setup_url' => $this->setup_page->get_page_link(),
			);

			$args['setup'] = $result;

			if ( $this->setup_page->is_setup_page() ) {

				$args['reset'] = array(
					'is_reset'  => ! empty( $_GET['jet_apb_reset'] ) ? true : false,
					'reset_url' => add_query_arg( array( 'jet_apb_reset' => 1 ), $this->setup_page->get_page_link() ),
				);

				$args['post_types'] = \Jet_Engine_Tools::get_post_types_for_js();
				$args['db_fields']  = array_keys( Plugin::instance()->db->appointments->schema() );

			}
		}

		return $args;

	}

}