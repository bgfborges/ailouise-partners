<?php
namespace JET_APB\Admin\Settings;

use JET_APB\Admin\Settings\Appointment_Settings_Base as Appointment_Settings_Base;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class Set_Up_Page extends Appointment_Settings_Base {

	public function __construct(){
		if ( $this->is_setup_page() && NULL === \JET_APB\Plugin::instance()->setup->setup_page ) {
			\JET_APB\Plugin::instance()->setup->register_setup_page( $this );
		}
	}

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_page_slug() {
		return 'jet-apb-set-up';
	}

	/**
	 * [get_page_name description]
	 * @return [type] [description]
	 */
	public function get_page_name() {
		return esc_html__( 'Set Up', 'jet-dashboard' );
	}

	/**
	 * Define that is setup page
	 *
	 * @return boolean [description]
	 */
	public function is_setup_page() {
		return true;
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {
		wp_enqueue_script( 'moment' );
		wp_enqueue_script( 'vuejs-datepicker' );

		wp_enqueue_script(
			$this->get_page_slug(),
			JET_APB_URL . 'assets/js/admin/settings.js',
			array( 'cx-vue-ui', 'vuejs-datepicker', 'moment' ),
			JET_APB_VERSION,
			true
		);

		wp_set_script_translations(
			$this->get_page_slug(),
			'jet-appointments-booking',
			JET_APB_PATH . 'languages'
		);

		wp_localize_script(
			$this->get_page_slug(),
			'JetAPBConfig',
			apply_filters( 'jet-appointments/admin/settings-page/localized-config', $this->page_settings()->get( 'config' ) )
		);

		wp_enqueue_style( 'jet-apb-set-up' );
		wp_enqueue_style( 'jet-apb-working-hours' );
	}

	/**
	 * [page_templates description]
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $page = false, $subpage = false ) {
		$templates[ $this->get_page_slug() ]           = JET_APB_PATH . 'templates/admin/jet-apb-settings/set-up.php';
		$templates[ 'jet-apb-set-up-working-hours-settings' ] = JET_APB_PATH . 'templates/admin/jet-apb-settings/settings-working-hours.php';

		return $templates;
	}
}