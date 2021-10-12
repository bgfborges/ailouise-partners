<?php
/**
 * Uses JetEngine meta component to process meta
 */
namespace JET_APB\Admin\Cpt_Meta_Box;

use JET_APB\Plugin;
use JET_APB\Time_Slots;

class Services_Meta extends Base_Vue_Meta_Box {

	/**
	 * Default settings array
	 *
	 * @var array
	 */
	protected $defaults;

	/**
	 * Class constructor
	 */
	public function __construct() {
		parent::__construct( Plugin::instance()->settings->get( 'services_cpt' ) );

		// Needed for backward compatibility.
		$this->defaults['default_slot'] = isset( $_GET['post'] ) ? get_post_meta( $_GET['post'], '_service_duration', true ) : 1800 ;
		$this->defaults['buffer_before']    = isset( $_GET['post'] ) ? get_post_meta( $_GET['post'], '_buffer_before', true ) : 0 ;
		$this->defaults['buffer_after']     = isset( $_GET['post'] ) ? get_post_meta( $_GET['post'], '_buffer_after', true ) : 0 ;

		add_action( 'jet-engine/meta-boxes/register-instances', [ $this, 'register_meta_box' ] );
	}

	/**
	 * Regsiter services specific metabox on all services registration
	 *
	 * @param  [type] $meta_boxes_manager [description]
	 * @return [type]                     [description]
	 */
	public function register_meta_box( $meta_boxes_manager ) {
		$services_cpt = Plugin::instance()->settings->get( 'services_cpt' );

		if ( ! $services_cpt ) {
			return;
		}

		$object_name = $services_cpt . '_jet_apb';

		$meta_boxes_manager->register_custom_group(
			$object_name,
			esc_html__( 'Appointments Settings', 'jet-appointments-booking' )
		);

		$meta_fields = array(
			array(
				'type'             => 'text',
				'name'             => '_app_price',
				'title'            => esc_html__( 'Price per slot', 'jet-appointments-booking' ),
			),
		);

		$manage_capacity = Plugin::instance()->settings->get( 'manage_capacity' );

		if ( $manage_capacity ) {
			$meta_fields[] = array(
				'type'        => 'text',
				'input_type'  => 'number',
				'default_val' => 1,
				'name'        => '_app_capacity',
				'title'       => esc_html__( 'Capacity', 'jet-appointments-booking' ),
			);
		}

		$meta_boxes_manager->register_metabox(
			$services_cpt,
			$meta_fields,
			esc_html__( 'Appointments Settings', 'jet-appointments-booking' ),
			$object_name
		);
	}

	/**
	 * Add a meta box to post.
	 */
	public function add_meta_box(){

		if ( ! $this->is_cpt_page() ) {
			return;
		}

		add_meta_box(
			'schedule_meta_box',
			esc_html__( 'Custom Schedule', 'jet-appointments-booking' ),
			[ $this, 'custom_schedule_meta_box_callback' ],
			[ $this->current_screen_slug ],
			'normal',
			'low'
		);
	}

	/**
	 * Require metabox html.
	 */
	public function custom_schedule_meta_box_callback(){
		require_once( JET_APB_PATH .'templates/admin/custom-schedule-meta-box.php' );
	}

}