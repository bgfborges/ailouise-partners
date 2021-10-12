<?php
/**
 * Uses JetEngine meta component to process meta
 */
namespace JET_APB\Admin\Cpt_Meta_Box;

use JET_APB\Plugin;
use JET_APB\Time_Slots;

class Providers_Meta extends Base_Vue_Meta_Box {

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
		parent::__construct( Plugin::instance()->settings->get( 'providers_cpt' ) );

		add_filter( 'jet-engine/relations/registered-relation', [ $this, 'register_providers_relation' ] );
		add_action( 'jet-engine/meta-boxes/register-instances', [ $this, 'register_meta_box' ] );
	}

	/**
	 * Regsiter services specific metabox on all services registration
	 *
	 * @param  [type] $meta_boxes_manager [description]
	 * @return [type]                     [description]
	 */
	public function register_providers_relation( $relations ){
		$services_cpt  = Plugin::instance()->settings->get( 'services_cpt' );
		$providers_cpt = Plugin::instance()->settings->get( 'providers_cpt' );

		if ( ! $services_cpt ) {
			return;
		}

		if ( empty( $relations ) ) {
			$relations = [];
		}

		$relations['item-0'] = [
			'name'                => 'services to providers',
			'post_type_1'         => $services_cpt,
			'post_type_2'         => $providers_cpt,
			'type'                => 'many_to_many',
			'post_type_1_control' => 1,
			'post_type_2_control' => 1,
			'parent_relation'     => '',
			'id'                  => 'item-0',
		];

		return $relations;
	}

	public function register_meta_box( $meta_boxes_manager ) {
		$provider_cpt = Plugin::instance()->settings->get( 'providers_cpt' );

		if ( ! $provider_cpt ) {
			return;
		}

		$object_name = $provider_cpt . '_jet_apb';

		$meta_boxes_manager->register_custom_group(
			$object_name,
			esc_html__( 'Appointments Settings', 'jet-appointments-booking' )
		);

		$meta_fields = array(
			array(
				'type'             => 'text',
				'name'             => '_app_price',
				'title'            => esc_html__( 'Price per provider slot', 'jet-appointments-booking' ),
			),
		);

		$meta_boxes_manager->register_metabox(
			$provider_cpt,
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