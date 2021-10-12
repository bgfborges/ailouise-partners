<?php
namespace JET_APB\Updater;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Updater class.
 *
 * @since 1.0.0
 */
class Plugin extends Base {

	/**
	 * Init class parameters.
	 *
	 * @since  1.0.0
	 * @param  array $attr Input attributes array.
	 * @return void
	 */
	public function __construct( $attr = array() ) {

		$this->base_init( $attr );

		/**
		 * Need for test update - set_site_transient( 'update_plugins', null );
		 */

		add_action( 'pre_set_site_transient_update_plugins', array( $this, 'update' ) );
	}

	/**
	 * Process update.
	 *
	 * @since  1.0.0
	 * @param  object $data Update data.
	 * @return object
	 */
	public function update( $data ) {

		$new_update = $this->check_update();

		if ( $new_update['version'] ) {

			$this->api['plugin'] = $this->api['slug'] . '/' . $this->api['slug'] . '.php';

			$update = new \stdClass();

			$update->slug        = $this->api['slug'];
			$update->plugin      = $this->api['plugin'];
			$update->new_version = $new_update['version'];
			$update->url         = isset( $this->api['details_url'] ) ? $this->api['details_url'] : false;
			$update->package     = $new_update['package'];

			$data->response[ $this->api['plugin'] ] = $update;
		}

		return $data;
	}

}
