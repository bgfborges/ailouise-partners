<?php
namespace JET_APB\Admin\Settings;

use JET_APB\Plugin;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * Define Controller class
 */
class Manager {

	/**
	 * A reference to an instance of this class.
	 *
	 * @since 1.0.0
	 * @var   object
	 */
	private static $instance = null;

	/**
	 * [$subpage_modules description]
	 * @var array
	 */
	public $subpage_modules = array();

	/**
	 * Returns the instance.
	 *
	 * @since  1.0.0
	 * @return object
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	// Here initialize our namespace and resource name.
	public function __construct() {
		$subpages = [
			'jet-apb-general-settings' => [
				'class' => '\\JET_APB\\Admin\\Settings\\General',
				'args'  => array(),
			],
			'jet-apb-working-hours-settings' => [
				'class' => '\\JET_APB\\Admin\\Settings\\Working_Hours',
				'args'  => array(),
			],
			'jet-apb-labels-settings' => [
				'class' => '\\JET_APB\\Admin\\Settings\\Labels',
				'args'  => array(),
			],
			'jet-apb-advanced-settings' => [
				'class' => '\\JET_APB\\Admin\\Settings\\Advanced',
				'args'  => array(),
			],
			'jet-apb-tools-settings' => [
				'class' => '\\JET_APB\\Admin\\Settings\\Tools',
				'args'  => array(),
			],
		];

		if( ! Plugin::instance()->settings->get( 'hide_setup' ) ){
			$subpages[ 'jet-apb-set-up' ] = [
				'class' => '\\JET_APB\\Admin\\Settings\\Set_Up_Page',
				'args'  => array(),
			];
		}

		$this->subpage_modules = apply_filters( 'jet-elements/settings/registered-subpage-modules', $subpages);

		add_action( 'init', array( $this, 'register_settings_category' ), 10 );
		add_action( 'init', array( $this, 'init_plugin_subpage_modules' ), 10 );
	}

	/**
	 * [init description]
	 * @return [type] [description]
	 */
	public function register_settings_category() {
		\Jet_Dashboard\Dashboard::get_instance()->module_manager->register_module_category( array(
			'name'     => esc_html__( 'JetAppointments', 'jet-elements' ),
			'slug'     => 'jet-appointments-booking',
			'priority' => 1
		) );
	}

	/**
	 * [init_plugin_subpage_modules description]
	 * @return [type] [description]
	 */
	public function init_plugin_subpage_modules() {
		require_once( JET_APB_PATH .'includes/admin/settings/pages/appointment-settings-base.php' );
		require_once( JET_APB_PATH .'includes/admin/settings/pages/general.php' );
		require_once( JET_APB_PATH .'includes/admin/settings/pages/working-hours.php' );
		require_once( JET_APB_PATH .'includes/admin/settings/pages/labels.php' );
		require_once( JET_APB_PATH .'includes/admin/settings/pages/advanced.php' );
		require_once( JET_APB_PATH .'includes/admin/settings/pages/tools.php' );
		require_once( JET_APB_PATH .'includes/admin/settings/pages/set-up.php' );

		foreach ( $this->subpage_modules as $subpage => $subpage_data ) {
			\Jet_Dashboard\Dashboard::get_instance()->module_manager->register_subpage_module( $subpage, $subpage_data );
		}
	}

}

