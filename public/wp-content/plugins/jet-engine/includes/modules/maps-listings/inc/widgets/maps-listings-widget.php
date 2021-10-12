<?php
namespace Jet_Engine\Modules\Maps_Listings;

use \Elementor\Controls_Manager;
use \Elementor\Group_Control_Background;
use \Elementor\Group_Control_Typography;
use \Elementor\Group_Control_Border;
use \Elementor\Group_Control_Box_Shadow;
use \Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Maps_Listings_Widget extends \Elementor\Jet_Listing_Grid_Widget {

	public function get_name() {
		return 'jet-engine-maps-listing';
	}

	public function get_title() {
		return __( 'Map Listing', 'jet-engine' );
	}

	public function get_icon() {
		return 'jet-engine-icon-map-listing';
	}

	public function get_categories() {
		return array( 'jet-listing-elements' );
	}

	public function get_help_url() {
		return 'https://crocoblock.com/knowledge-base/articles/jetengine-maps-listing-overview/?utm_source=jetengine&utm_medium=maps-listing&utm_campaign=need-help';
	}

	protected function _register_controls() {
		$this->register_general_settings();
		$this->register_query_settings();
		$this->register_terms_query_settings();

		do_action( 'jet-engine/map-listing/custom-query-settings', $this );

		$this->register_visibility_settings();
		$this->register_style_settings();
	}

	public function register_general_settings() {

		$this->start_controls_section(
			'section_general',
			array(
				'label' => __( 'General', 'jet-engine' ),
			)
		);

		$this->add_control(
			'lisitng_id',
			array(
				'label'   => __( 'Listing', 'jet-engine' ),
				'type'       => 'jet-query',
				'query_type' => 'post',
				'query'      => array(
					'post_type' => jet_engine()->post_type->slug(),
				),
				'edit_button' => array(
					'active' => true,
					'label'  => __( 'Edit Listing', 'jet-engine' ),
				),
			)
		);

		$this->add_control(
			'address_field',
			array(
				'label'       => __( 'Address Meta Field', 'jet-engine' ),
				'description' => __( 'Set meta field key to get address from (for human-readable addresses). To get address from multiple meta fields, combine these fields names with "+" sign. For example: state+city+street', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
			)
		);

		$this->add_control(
			'add_lat_lng',
			array(
				'label'   => esc_html__( 'Use Lat Lng Address Meta Field', 'jet-engine' ),
				'description' => __( 'Check this if you want to get item address for the map by latitude and longitude stored directly in the meta field', 'jet-engine' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => '',
			)
		);

		$this->add_control(
			'lat_lng_address_field',
			array(
				'label'       => __( 'Lat Lng Address Meta Field', 'jet-engine' ),
				'description' => __( 'Set meta field key to get latitude and longitude from. To get address from latitude and longitude meta fields, combine these fields names with "+" sign. For example: _lat+_lng. Latitude field always should be first', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'condition'   => array(
					'add_lat_lng' => 'yes',
				),
			)
		);

		$this->add_responsive_control(
			'map_height',
			array(
				'label' => esc_html__( 'Map Height', 'jet-engine' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 100,
						'max' => 900,
					),
					'vh' => array(
						'min' => 1,
						'max' => 200,
					),
				),
				'size_units' => array( 'px', 'vh' ),
				'default'    => array(
					'unit' => 'px',
					'size' => 500,
				),
				'render_type' => 'template',
				'selectors' => array(
					'{{WRAPPER}} .jet-map-listing' => 'height: {{SIZE}}{{UNIT}}',

				),
			)
		);

		$this->add_control(
			'posts_num',
			array(
				'label'   => __( 'Posts number', 'jet-engine' ),
				'type'    => Controls_Manager::NUMBER,
				'default' => 6,
				'min'     => -1,
				'max'     => 1000,
				'step'    => 1,
			)
		);

		$this->add_control(
			'auto_center',
			array(
				'label'   => esc_html__( 'Automatically detect map center', 'jet-engine' ),
				'type'    => Controls_Manager::SWITCHER,
				'default' => 'yes',
			)
		);

		$this->add_control(
			'max_zoom',
			array(
				'label' => __( 'Max Zoom', 'jet-engine' ),
				'type'  => Controls_Manager::NUMBER,
				'min'   => 1,
				'max'   => 20,
				'step'  => 1,
				'condition' => array(
					'auto_center' => 'yes',
				),
			)
		);

		$this->add_control(
			'custom_center',
			array(
				'label'       => __( 'Map Center', 'jet-engine' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'label_block' => true,
				'condition'    => array(
					'auto_center' => '',
				),
			)
		);

		$this->add_control(
			'custom_zoom',
			array(
				'label'       => __( 'Custom Zoom', 'jet-engine' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 11,
				'min'         => 1,
				'max'         => 20,
				'step'        => 1,
				'condition'    => array(
					'auto_center' => '',
				),
			)
		);

		$this->add_control(
			'custom_style',
			array(
				'label'       => __( 'Custom Map Style', 'jet-engine' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '',
				'description' => __( 'Find a free map styles at <a href="https://snazzymaps.com/explore" target="_blank" rel="nofollow">Snazzy Maps</a>', 'jet-engine' ),
				'label_block' => true,
			)
		);

		$this->add_control(
			'zoom_control',
			array(
				'separator'   => 'before',
				'label'       => __( 'Zoom & Pan Control', 'jet-engine' ),
				'type'        => Controls_Manager::SELECT,
				'description' => __( 'Controls how the API handles gestures on the map. More details <a href="https://developers.google.com/maps/documentation/javascript/interaction#gestureHandling" target="_blank">here</a>', 'jet-engine' ),
				'default'     => 'auto',
				'options'     => array(
					'auto'        => __( 'Auto', 'jet-engine' ),
					'greedy'      => __( 'Greedy', 'jet-engine' ),
					'cooperative' => __( 'Cooperative', 'jet-engine' ),
					'none'        => __( 'None', 'jet-engine' ),
				),
			)
		);

		$this->add_control(
			'zoom_controls',
			array(
				'label'        => __( 'Zoom Controls', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'jet-engine' ),
				'label_off'    => __( 'Hide', 'jet-engine' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'fullscreen_control',
			array(
				'label'        => __( 'Fullscreen Control', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'jet-engine' ),
				'label_off'    => __( 'Hide', 'jet-engine' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'street_view_controls',
			array(
				'label'        => __( 'Street View Controls', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'jet-engine' ),
				'label_off'    => __( 'Hide', 'jet-engine' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->add_control(
			'map_type_controls',
			array(
				'label'        => __( 'Map Type Controls (Map/Satellite)', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'jet-engine' ),
				'label_off'    => __( 'Hide', 'jet-engine' ),
				'return_value' => 'true',
				'default'      => 'true',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_marker_settings',
			array(
				'label' => __( 'Marker', 'jet-engine' ),
			)
		);

		$this->add_control(
			'marker_type',
			array(
				'label'   => __( 'Marker Type', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => Module::instance()->get_marker_types(),
			)
		);

		$this->add_control(
			'marker_image',
			array(
				'label'     => esc_html__( 'Image', 'jet-engine' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'marker_type' => 'image',
				),
			)
		);

		$this->add_control(
			'marker_icon',
			array(
				'label'            => __( 'Icon', 'jet-engine' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'condition' => array(
					'marker_type' => 'icon',
				),
			)
		);

		$this->add_control(
			'marker_image_field',
			array(
				'label'     => __( 'Meta Field', 'jet-engine' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'groups'    => $this->get_meta_fields_list(),
				'condition' => array(
					'marker_type' => 'dynamic_image',
				),
			)
		);

		$this->add_control(
			'marker_image_field_custom',
			array(
				'label'       => __( 'Or enter meta field key', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => __( 'Note: this filed will override Meta Field value', 'jet-engine' ),
				'condition'   => array(
					'marker_type' => 'dynamic_image',
				),
			)
		);

		$this->add_control(
			'marker_label_type',
			array(
				'label'   => __( 'Marker Label', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'post_title',
				'options' => Module::instance()->get_marker_label_types(),
				'condition' => array(
					'marker_type' => 'text',
				),
			)
		);

		$this->add_control(
			'marker_label_field',
			array(
				'label'     => __( 'Meta Field', 'jet-engine' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'groups'    => $this->get_meta_fields_list(),
				'condition' => array(
					'marker_type'       => 'text',
					'marker_label_type' => 'meta_field',
				),
			)
		);

		$this->add_control(
			'marker_label_field_custom',
			array(
				'label'       => __( 'Or enter meta field key', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => __( 'Note: this filed will override Meta Field value', 'jet-engine' ),
				'condition'   => array(
					'marker_type'       => 'text',
					'marker_label_type' => 'meta_field',
				),
			)
		);

		$this->add_control(
			'marker_label_text',
			array(
				'label'       => __( 'Marker Label', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'condition'   => array(
					'marker_type'       => 'text',
					'marker_label_type' => 'static_text',
				),
			)
		);

		do_action( 'jet-engine/maps-listing/widget/custom-marker-label-controls', $this );

		$callbacks = jet_engine()->listings->get_allowed_callbacks();
		$callbacks = array( 0 => __( 'Select...', 'jet-engine' ) ) + $callbacks;

		$this->add_control(
			'marker_label_format_cb',
			array(
				'label'     => __( 'Callback', 'jet-engine' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 0,
				'options'   => $callbacks,
				'condition' => array(
					'marker_type' => 'text',
				),
			)
		);

		$glossaries = array(
			'' => __( 'Select glossary...', 'jet-engine' ),
		);

		foreach ( jet_engine()->glossaries->settings->get() as $glossary ) {
			$glossaries[ $glossary['id'] ] = $glossary['name'];
		}

		$this->add_control(
			'labels_by_glossary',
			array(
				'label'     => __( 'Select glossary', 'jet-engine' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'options'   => $glossaries,
				'condition' => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => array( 'jet_engine_label_by_glossary' ),
				),
			)
		);

		$this->add_control(
			'date_format',
			array(
				'label'       => esc_html__( 'Format', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'description' => sprintf( '<a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">%s</a>', __( 'Documentation on date and time formatting', 'jet-engine' ) ),
				'default'     => 'F j, Y',
				'condition'   => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => array( 'date', 'date_i18n' ),
				),
			)
		);

		$this->add_control(
			'num_dec_point',
			array(
				'label'       => esc_html__( 'Decimal point', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '.',
				'description' => __( 'Sets the separator for the decimal point', 'jet-engine' ),
				'condition'   => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => 'number_format',
				),
			)
		);

		$this->add_control(
			'num_thousands_sep',
			array(
				'label'       => esc_html__( 'Thousands separator', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => ',',
				'condition'   => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => 'number_format',
				),
			)
		);

		$this->add_control(
			'num_decimals',
			array(
				'label'       => esc_html__( 'Decimal points', 'jet-engine' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 10,
				'step'        => 1,
				'default'     => 2,
				'description' => __( 'Sets the number of visible decimal points', 'jet-engine' ),
				'condition'   => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => 'number_format',
				),
			)
		);

		$this->add_control(
			'human_time_diff_from_key',
			array(
				'label'       => esc_html__( 'Additional meta key', 'jet-engine' ),
				'description' => esc_html__( 'Pass additional date meta key for calculating time diff.', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'condition'   => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => 'human_time_diff',
				),
			)
		);

		$this->add_control(
			'zeroise_threshold',
			array(
				'label'       => esc_html__( 'Threshold', 'jet-engine' ),
				'type'        => Controls_Manager::NUMBER,
				'min'         => 0,
				'max'         => 10,
				'step'        => 1,
				'default'     => 3,
				'description' => __( 'Digit places number needs to be to not have zeros added', 'jet-engine' ),
				'condition'   => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => 'zeroise',
				),
			)
		);

		$this->add_control(
			'child_path',
			array(
				'label'       => __( 'Child item name', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'label_block' => true,
				'default'     => '',
				'description' => __( 'Name of the child item to get. Or path to the nested child item. Separate nesting levels with "/". For example - level-1-name/level-2-name/child-item-name', 'jet-engine' ),
				'condition'   => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => 'jet_engine_get_child',
				),
			)
		);

		$this->add_control(
			'related_list_is_single',
			array(
				'label'        => esc_html__( 'Single value', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => 'jet_related_posts_list',
				),
			)
		);

		$this->add_control(
			'related_list_is_linked',
			array(
				'label'        => esc_html__( 'Add links to related posts', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => 'jet_related_posts_list',
				),
			)
		);

		$this->add_control(
			'related_list_tag',
			array(
				'label'   => __( 'Related list HTML tag', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'ul',
				'options' => array(
					'ul'   => 'UL',
					'ol'   => 'OL',
					'div'  => 'DIV',
				),
				'condition' => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => 'jet_related_posts_list',
				),
			)
		);

		$this->add_control(
			'multiselect_delimiter',
			array(
				'label'     => esc_html__( 'Delimiter', 'jet-engine' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => ', ',
				'condition' => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => array( 'jet_engine_render_multiselect', 'jet_related_posts_list', 'jet_engine_render_post_titles', 'jet_engine_render_checkbox_values', 'jet_engine_label_by_glossary' ),
				),
			)
		);

		$this->add_control(
			'switcher_true',
			array(
				'label'     => esc_html__( 'Text if enabled', 'jet-engine' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => '',
				'condition' => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => 'jet_engine_render_switcher',
				),
			)
		);

		$this->add_control(
			'switcher_false',
			array(
				'label'     => esc_html__( 'Text if disabled', 'jet-engine' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => '',
				'condition' => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => 'jet_engine_render_switcher',
				),
			)
		);

		$this->add_control(
			'checklist_cols_num',
			array(
				'label'     => __( 'Columns number', 'jet-engine' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 1,
				'min'       => 1,
				'max'       => 6,
				'step'      => 1,
				'condition' => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => 'jet_engine_render_checklist',
				),
			)
		);

		$this->add_control(
			'checklist_divider',
			array(
				'label'        => esc_html__( 'Add divider between items', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => '',
				'condition'    => array(
					'marker_type' => 'text',
					'marker_label_format_cb' => 'jet_engine_render_checklist',
				),
			)
		);

		$this->add_control(
			'checklist_divider_color',
			array(
				'label' => __( 'Divider color', 'jet-engine' ),
				'type'  => Controls_Manager::COLOR,
				'condition' => array(
					'marker_label_format_cb' => 'jet_engine_render_checklist',
					'checklist_divider'      => 'yes',
				),
				'selectors' => array(
					'marker_type' => 'text',
					'{{WRAPPER}} .jet-check-list__item' => 'border-color: {{VALUE}}',
				),
			)
		);

		/**
		 * Add custom controls for Callbacks
		 */
		do_action( 'jet-engine/map-listing/callback-controls', $this );

		$this->add_control(
			'marker_label_custom',
			array(
				'label'        => esc_html__( 'Customize output', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
				'condition'    => array(
					'marker_type' => 'text',
				),
			)
		);

		$this->add_control(
			'marker_label_custom_output',
			array(
				'label'       => __( 'Label format', 'jet-engine' ),
				'type'        => Controls_Manager::TEXTAREA,
				'default'     => '%s',
				'description' => __( '%s will be replaced with field value', 'jet-engine' ),
				'condition' => array(
					'marker_type'         => 'text',
					'marker_label_custom' => 'yes',
				),
			)
		);

		$this->add_control(
			'multiple_marker_types',
			array(
				'label'       => esc_html__( 'Use different markers by conditions', 'jet-engine' ),
				'type'        => Controls_Manager::SWITCHER,
				'description' => __( 'Previously set marker will be used as default if conditions not met', 'jet-engine' ),
				'default'     => '',
			)
		);

		$markers_repeater = new Repeater();

		$markers_repeater->add_control(
			'marker_type',
			array(
				'label'   => __( 'Marker Type', 'jet-engine' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'image',
				'options' => array(
					'image' => __( 'Image', 'jet-engine' ),
					'icon'  => __( 'Icon', 'jet-engine' ),
				),
			)
		);

		$markers_repeater->add_control(
			'marker_image',
			array(
				'label'     => esc_html__( 'Image', 'jet-engine' ),
				'type'      => Controls_Manager::MEDIA,
				'condition' => array(
					'marker_type' => 'image',
				),
			)
		);

		$markers_repeater->add_control(
			'marker_icon',
			array(
				'label'            => __( 'Icon', 'jet-engine' ),
				'type'             => Controls_Manager::ICONS,
				'label_block'      => true,
				'condition' => array(
					'marker_type' => 'icon',
				),
			)
		);

		$markers_repeater->add_control(
			'apply_type',
			array(
				'label'       => __( 'Apply this marker if', 'jet-engine' ),
				'type'        => Controls_Manager::SELECT,
				'label_block' => true,
				'separator'   => 'before',
				'default'     => 'meta_field',
				'options'     => array(
					'meta_field' => __( 'Post meta field is equal to value', 'jet-engine' ),
					'post_term'  => __( 'Post has term', 'jet-engine' ),
				),
			)
		);

		$markers_repeater->add_control(
			'field_name',
			array(
				'label'     => __( 'Meta Field', 'jet-engine' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => '',
				'groups'    => $this->get_meta_fields_list(),
				'condition' => array(
					'apply_type' => 'meta_field',
				),
			)
		);

		$markers_repeater->add_control(
			'field_name_custom',
			array(
				'label'       => __( 'Or enter meta field key', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => __( 'Note: this filed will override Meta Field value', 'jet-engine' ),
				'condition'   => array(
					'apply_type' => 'meta_field',
				),
			)
		);

		$markers_repeater->add_control(
			'field_value',
			array(
				'label'       => __( 'Field value', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'condition'   => array(
					'apply_type' => 'meta_field',
				),
			)
		);

		$markers_repeater->add_control(
			'tax_name',
			array(
				'label'       => __( 'Taxonomy slug', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'description' => __( 'You can find this slug in the address bar of taxonomy edit page', 'jet-engine' ),
				'condition'   => array(
					'apply_type' => 'post_term',
				),
			)
		);

		$markers_repeater->add_control(
			'term_name',
			array(
				'label'       => __( 'Term name, slug or ID', 'jet-engine' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'label_block' => true,
				'condition'   => array(
					'apply_type' => 'post_term',
				),
			)
		);

		$this->add_control(
			'multiple_markers',
			array(
				'type'    => Controls_Manager::REPEATER,
				'fields'  => $markers_repeater->get_controls(),
				'default' => array(),
				'condition' => array(
					'multiple_marker_types' => 'yes',
				),
			)
		);

		$this->add_control(
			'marker_clustering',
			array(
				'label'        => __( 'Marker Clustering', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'true',
				'default'      => 'true',
				'separator'    => 'before',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_popup_settings',
			array(
				'label' => __( 'Popup', 'jet-engine' ),
			)
		);

		$this->add_control(
			'popup_width',
			array(
				'label'       => __( 'Marker Popup Width', 'jet-engine' ),
				'description' => __( 'Set marker popup width in pixels', 'jet-engine' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 320,
				'min'         => 150,
				'max'         => 600,
				'step'        => 1,
			)
		);

		$this->add_control(
			'popup_offset',
			array(
				'label'       => __( 'Vertical Offset', 'jet-engine' ),
				'description' => __( 'Set vertical popup offset in pixels', 'jet-engine' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => 40,
				'min'         => 0,
				'max'         => 200,
				'step'        => 1,
			)
		);

		$this->add_control(
			'popup_pin',
			array(
				'label'        => esc_html__( 'Add popup pin', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => '',
			)
		);

		$this->add_control(
			'popup_preloader',
			array(
				'label'        => esc_html__( 'Add popup preloader', 'jet-engine' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__( 'Yes', 'jet-engine' ),
				'label_off'    => esc_html__( 'No', 'jet-engine' ),
				'return_value' => 'yes',
				'default'      => '',
				'description'  => esc_html__( 'Add box with loading animation while popup data is fetching from the server', 'jet-engine' ),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_popup_style',
			array(
				'label'      => __( 'Popup Pin', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'popup_pin' => 'yes',
				),
			)
		);

		$this->add_control(
			'popup_pin_size',
			array(
				'label' => __( 'Pin Size', 'jet-engine' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 4,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .popup-has-pin .jet-map-box:after' => 'margin: 0 0 0 -{{SIZE}}{{UNIT}}; border-width: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'popup_pin_color',
			array(
				'label'     => __( 'Pin Color', 'jet-engine' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .popup-has-pin .jet-map-box:after' => 'border-color: {{VALUE}} transparent transparent transparent;',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_popup_preloader_style',
			array(
				'label'      => __( 'Popup Preloader', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'condition'  => array(
					'popup_preloader' => 'yes',
				),
			)
		);

		$this->add_control(
			'popup_preloader_bg_color',
			array(
				'label'     => __( 'Background Color', 'jet-engine' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-map-preloader.is-active' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'popup_preloader_color',
			array(
				'label'     => __( 'Loader Color', 'jet-engine' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-map-preloader.is-active .jet-map-loader' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'popup_preloader_height',
			array(
				'label' => __( 'Height', 'jet-engine' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 700,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-map-preloader.is-active' => 'height: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_marker_style',
			array(
				'label'      => __( 'Marker', 'jet-engine' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$this->add_control(
			'marker_width',
			array(
				'label' => __( 'Width', 'jet-engine' ),
				'type' => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 200,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-map-marker-wrap' => 'width: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-map-marker-image' => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'marker_typography',
				'selector' => '{{WRAPPER}} .jet-map-marker-wrap',
			)
		);

		$this->add_control(
			'marker_icon_size',
			array(
				'label' => esc_html__( 'Icon Size', 'jet-engine' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 10,
						'max' => 300,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-map-marker' => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->start_controls_tabs( 'tabs_marker_state' );

		$this->start_controls_tab(
			'marker_state_normal',
			array(
				'label' => __( 'Normal', 'jet-engine' ),
			)
		);

		$this->add_control(
			'marker_color',
			array(
				'label'  => __( 'Text Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-map-marker-wrap' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'marker_bg_color',
			array(
				'label'     => __( 'Background Color', 'jet-engine' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-map-marker-wrap' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .jet-map-marker-wrap:after' => 'border-color: {{VALUE}} transparent transparent transparent;',
				),
			)
		);

		$this->add_control(
			'marker_icon_color',
			array(
				'label'  => __( 'Icon Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-map-marker' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jet-map-marker path' => 'fill: {{VALUE}} !important',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'marker_state_hover',
			array(
				'label' => __( 'Hover', 'jet-engine' ),
			)
		);

		$this->add_control(
			'marker_color_hover',
			array(
				'label'  => __( 'Text Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-map-marker-wrap:hover' => 'color: {{VALUE}}',
				),
			)
		);

		$this->add_control(
			'marker_bg_color_hover',
			array(
				'label'     => __( 'Background Color', 'jet-engine' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-map-marker-wrap:hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .jet-map-marker-wrap:hover:after' => 'border-color: {{VALUE}} transparent transparent transparent;',
				),
			)
		);

		$this->add_control(
			'marker_icon_color_hover',
			array(
				'label'  => __( 'Icon Color', 'jet-engine' ),
				'type'   => Controls_Manager::COLOR,
				'selectors' => array(
					'{{WRAPPER}} .jet-map-marker:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .jet-map-marker:hover path' => 'fill: {{VALUE}} !important',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'marker_padding',
			array(
				'label'      => __( 'Padding', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					'{{WRAPPER}} .jet-map-marker-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'marker_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-engine' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					'{{WRAPPER}} .jet-map-marker-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'marker_box_shadow',
				'selector' => '{{WRAPPER}} .jet-map-marker-wrap',
			)
		);

		$this->add_control(
			'marker_pin_size',
			array(
				'label' => __( 'Pin Size', 'jet-engine' ),
				'type'  => Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 4,
						'max' => 60,
					),
				),
				'selectors' => array(
					'{{WRAPPER}} .jet-map-marker-wrap:after' => 'margin: 0 0 0 -{{SIZE}}{{UNIT}}; border-width: {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0 {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jet-map-marker-wrap' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->add_responsive_control(
			'link_alignment',
			array(
				'label'   => __( 'Alignment', 'jet-engine' ),
				'type'    => Controls_Manager::CHOOSE,
				'default' => 'center',
				'options' => array(
					'left'    => array(
						'title' => __( 'Left', 'jet-engine' ),
						'icon'  => 'fa fa-align-left',
					),
					'center' => array(
						'title' => __( 'Center', 'jet-engine' ),
						'icon'  => 'fa fa-align-center',
					),
					'right' => array(
						'title' => __( 'Right', 'jet-engine' ),
						'icon'  => 'fa fa-align-right',
					),
				),
				'selectors'  => array(
					'{{WRAPPER}} .jet-map-marker-wrap' => 'text-align: {{VALUE}};',
				),
			)
		);

		$this->end_controls_section();

	}

	/**
	 * Get meta fields for post type
	 *
	 * @return array
	 */
	public function get_meta_fields_list() {

		if ( jet_engine()->meta_boxes ) {
			return jet_engine()->meta_boxes->get_fields_for_select( 'plain' );
		} else {
			return array();
		}

	}

	public function register_style_settings() {

	}

	/**
	 * Render grid posts
	 *
	 * @return void
	 */
	public function render_posts() {
		$instance = jet_engine()->listings->get_render_instance( 'maps-listing', $this->get_widget_settings() );
		$instance->render_content();
	}

	protected function render() {
		$this->render_posts();
	}

}
