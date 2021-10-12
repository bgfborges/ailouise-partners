<?php
namespace JET_APB;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * @property WC_Integration wc
 * @property DB\Manager db
 * @property Form form
 * @property Admin\Settings settings
 * @property Rest_API\Manager rest_api
 * @property Tools tools
 * @property Calendar calendar
 *
 * Main file
 */
class Plugin {

	/**
	 * Instance.
	 *
	 * Holds the plugin instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var Plugin
	 */
	public static $instance = null;

	/**
	 * @var DB\Manager
	 */
	public $db = null;

	/**
	 * [$data description]
	 * @var boolean
	 */
	public $data = false;

	/**
	 * [$working_hours_config description]
	 * @var array
	 */
	public $working_hours_config = false;

	/**
	 * Plugin constructor.
	 */
	private function __construct() {

		if ( ! function_exists( 'jet_engine' ) ) {

			add_action( 'admin_notices', function() {
				$class = 'notice notice-error';
				$message = __( '<b>WARNING!</b> <b>JetAppointmentsBooking</b> plugin requires <b>JetEngine</b> plugin to work properly!', 'jet-appointments-booking' );
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
			} );

			return;
		}

		$this->register_autoloader();

		// Jet Dashboard Init
		add_action( 'after_setup_theme', array( $this, 'init_components' ), 0 );

		// Jet Dashboard Init
		add_action( 'init', array( $this, 'jet_dashboard_init' ), -999 );
	}

	/**
	 * Returns plugin version
	 *
	 * @return string
	 */
	public function get_version() {

		if ( is_admin() ) {

			if( ! function_exists('get_plugin_data') ){
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			}

			if( ! $this->data ){
				$this->data = get_plugin_data( JET_APB__FILE__ );
			}
		}

		return $this->data['Version'];
	}

	/**
	 * Page slug
	 *
	 * @return string
	 */
	public function slug() {
		return 'jet-appointments-booking';
	}

	/**
	 * Register autoloader.
	 */
	private function register_autoloader() {
		require JET_APB_PATH . 'includes/autoloader.php';
		Autoloader::run();
	}


	/**
	 * [jet_dashboard_init description]
	 * @return [type] [description]
	 */
	public function jet_dashboard_init() {

		if ( is_admin() ) {

			if( ! class_exists( 'Jet_Dashboard\Dashboard' ) ){
				return;
			}

			$jet_dashboard = \Jet_Dashboard\Dashboard::get_instance();

			$jet_dashboard->init( array(
				'path'           => $jet_dashboard->get_dashboard_path(),
				'url'            => $jet_dashboard->get_dashboard_url(),
				'cx_ui_instance' => array( $this, 'jet_dashboard_ui_instance_init' ),
				'plugin_data'    => array(
					'slug'    => $this->slug(),
					'file'    => JET_APB_PLUGIN_BASE,
					'version' => $this->get_version(),
					'plugin_links' => array(
						array(
							'label'  => esc_html__( 'Go to settings', 'jet-elements' ),
							'url'    => add_query_arg( array( 'page' => 'jet-dashboard-settings-page', 'subpage' => 'jet-apb-general-settings' ), admin_url( 'admin.php' ) ),
							'target' => '_self',
						),
					),
				),
			) );
		}
	}

	/**
	 * [jet_dashboard_ui_instance_init description]
	 * @return [type] [description]
	 */
	public function jet_dashboard_ui_instance_init() {
		$cx_ui_module_data = jet_engine()->framework->get_included_module_data( 'cherry-x-vue-ui.php' );

		return new \CX_Vue_UI( $cx_ui_module_data );
	}

	/**
	 * Initialize plugin parts
	 *
	 * @return void
	 */
	public function init_components() {
		add_action( 'admin_enqueue_scripts', [ $this, 'register_assets' ],        0 );
		add_action( 'wp_enqueue_scripts',    [ $this, 'register_public_assets' ], 0 );

		$this->db                   = new DB\Manager();
		$this->settings             = new Admin\Settings();
		$this->tools                = new Tools();
		$this->services_meta        = new Admin\Cpt_Meta_Box\Services_Meta();
		$this->form                 = new Form();
		$this->calendar             = new Calendar();
		$this->rest_api             = new Rest_API\Manager();
		$this->google_cal           = new Google_Calendar();
		$this->wc                   = new WC_Integration();
		$this->elementor            = new Elementor_Integration\Manager();
		$this->statuses             = new Statuses();

		if ( $this->settings->get( 'providers_cpt' ) ) {
			$this->providers_meta = new Admin\Cpt_Meta_Box\Providers_Meta();
		}

		if ( is_admin() ) {
			$this->setup                = new Set_Up();

			//Init Settings Manager
			new \JET_APB\Admin\Settings\Manager();

			$this->dashboard = new Admin\Dashboard( array(
				new Admin\Pages\Appointments(),
			) );
			
			new DB_Upgrader();
		}

		new Listings();
		new Formbuilder_Plugin\Form_Builder();
	}

	/**
	 * Page specific assets
	 *
	 * @return [type] [description]
	 */
	public function register_assets() {
		$this->register_script( 'momentjs', 'admin/lib/moment.min.js' );
		$this->register_script( 'vuejs-datepicker', 'admin/lib/vuejs-datepicker.min.js' );
		$this->register_script( 'jet-apb-wc-details-builder', 'admin/wc-details-builder.js' );

		$this->register_style( 'jet-apb-working-hours', 'admin/working-hours.css' );
		$this->register_style( 'jet-apb-set-up', 'admin/set-up.css' );
		$this->register_style( 'jet-appointments-booking-admin', 'admin/jet-appointments-booking-admin.css' );
	}
	/**
	 * Page specific assets
	 *
	 * @return [type] [description]
	 */
	public function register_public_assets() {
		$this->register_style( 'jet-appointments-booking-front-style', 'public/jet-appointments-booking.css' );
	}
	
	/**
	 * Register script
	 *
	 * @param  [type] $handle    [description]
	 * @param  [type] $file_path [description]
	 * @return [type]            [description]
	 */
	public function register_script( $handle = null, $file_path = null ) {
		wp_register_script(
			$handle,
			JET_APB_URL . 'assets/js/' . $file_path,
			array( 'wp-api-fetch' ),
			JET_APB_VERSION . time(),
			true
		);
	}

	/**
	 * Register style
	 *
	 * @param  [type] $handle    [description]
	 * @param  [type] $file_path [description]
	 * @return [type]            [description]
	 */
	public function register_style( $handle = null, $file_path = null ) {
		wp_register_style(
			$handle,
			JET_APB_URL . 'assets/css/' . $file_path,
			array(),
			JET_APB_VERSION . time()
		);
	}

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$instance ) ) {

			self::$instance = new self();

		}

		return self::$instance;
	}
}

Plugin::instance();