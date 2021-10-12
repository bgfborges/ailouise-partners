<?php
namespace JET_APB\Admin\Settings;

use JET_APB\Admin\Settings\Appointment_Settings_Base as Appointment_Settings_Base;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class General extends Appointment_Settings_Base {

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_page_slug() {
		return 'jet-apb-general-settings';
	}

	/**
	 * [get_page_name description]
	 * @return [type] [description]
	 */
	public function get_page_name() {
		return esc_html__( 'General Settings', 'jet-dashboard' );
	}

	/**
	 * [page_templates description]
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $page = false, $subpage = false ) {
		$templates['jet-apb-general-settings'] = JET_APB_PATH .'templates/admin/jet-apb-settings/settings-general.php' ;

		return $templates;
	}
}
