<?php


namespace JET_APB\Formbuilder_Plugin\Actions;


use JET_APB\Formbuilder_Plugin\With_Form_Builder;

/**
 * The script for registering the action
 * is displayed in the
 * JET_APB\Formbuilder_Plugin\Blocks\Blocks_Manager class
 *
 * Class Action_Manager
 * @package JET_ABAF\Formbuilder_Plugin\Actions
 */
class Action_Manager {

	use With_Form_Builder;

	public function manager_init() {
		add_action(
			'jet-form-builder/actions/register',
			array( $this, 'register_actions' )
		);
		add_action(
			'jet-form-builder/editor-assets/before',
			array( $this, 'editor_assets' )
		);
	}

	public function register_actions( $manager ) {
		$manager->register_action_type( new Insert_Appointment_Action() );
	}

	public function editor_assets() {
		wp_enqueue_script(
			'jet-app-booking-form-builder-editor',
			JET_APB_URL . 'assets/js/builder.editor.js',
			array(),
			JET_APB_VERSION,
			true
		);
	}
}