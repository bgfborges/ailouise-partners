<?php


namespace JET_APB\Formbuilder_Plugin;


use JET_APB\Plugin;
use Jet_Form_Builder\Actions\Types\Base;
use Jet_Form_Builder\Gateways\Base_Gateway;

class Gateway_Manager {

	public function __construct() {
		add_filter(
			'jet-form-builder/gateways/notifications-before',
			array( $this, 'before_form_gateway' ), 0, 2
		);
		add_action(
			'jet-form-builder/gateways/on-payment-success',
			array( $this, 'on_gateway_success' )
		);
	}

	public function before_form_gateway( $actions_ids, $actions_all ) {
		foreach ( $actions_all as $action ) {
			/** @var Base $action */
			if ( 'insert_appointment' === $action->get_id() && ! in_array( $action->_id, $actions_ids ) ) {
				$actions_ids[ $action->_id ] = array( 'active' => true );
			}
		}

		return $actions_ids;
	}

	/**
	 * Finalize booking on internal JetFormBuilder form gateway success
	 *
	 * @param Base_Gateway $gateway
	 *
	 * @return void
	 */
	public function on_gateway_success( Base_Gateway $gateway ) {
		$form_data = $gateway->property( 'data' )['form_data'];
		$appointment_id = isset( $form_data['appointment_id'] ) ? $form_data['appointment_id'] : false;

		if ( ! $appointment_id ) {
			return;
		}

		$appointment = Plugin::instance()->db->get_appointment_by( 'ID', $appointment_id );

		if ( ! $appointment ) {
			return;
		}

		Plugin::instance()->db->appointments->update(
			array( 'status' => 'completed', ),
			array( 'ID' => $appointment_id, )
		);

		Plugin::instance()->db->maybe_exclude_appointment_date( $appointment );
	}

}