<?php
namespace JET_APB\Rest_API;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Manager {

	/**
	 * Register hooks
	 */
	public function __construct() {
		add_action( 'jet-engine/rest-api/init-endpoints', array( $this, 'init_rest' ) );
	}

	/**
	 * Initialize Rest API endpoints
	 *
	 * @return [type] [description]
	 */
	public function init_rest( $api_manager ) {

		$api_manager->register_endpoint( new Endpoint_Date_Slots() );
		$api_manager->register_endpoint( new Endpoint_Refresh_Dates() );
		$api_manager->register_endpoint( new Endpoint_Service_Providers() );
		$api_manager->register_endpoint( new Endpoint_Appointments_List() );
		$api_manager->register_endpoint( new Endpoint_Delete_Appointment() );
		$api_manager->register_endpoint( new Endpoint_Update_Appointment() );
		$api_manager->register_endpoint( new Endpoint_Add_Appointment() );

	}

	/**
	 * Returns all registered Rest URLs
	 * @return array
	 */
	public function get_urls( $full = true ) {
		return array(
			'date_slots'         => jet_engine()->api->get_route( 'appointment-date-slots', $full ),
			'refresh_dates'      => jet_engine()->api->get_route( 'appointment-refresh-date', $full ),
			'service_providers'  => jet_engine()->api->get_route( 'appointment-service-providers', $full ),
			'appointments_list'  => jet_engine()->api->get_route( 'appointments-list', $full ),
			'delete_appointment' => jet_engine()->api->get_route( 'delete-appointment', $full ),
			'update_appointment' => jet_engine()->api->get_route( 'update-appointment', $full ),
			'add_appointment'    => jet_engine()->api->get_route( 'appointment-add-appointment', $full ),
		);
	}

}