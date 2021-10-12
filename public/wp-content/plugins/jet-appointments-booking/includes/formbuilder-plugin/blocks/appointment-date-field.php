<?php


namespace JET_APB\Formbuilder_Plugin\Blocks;


use JET_APB\Form_Fields\Static_Calendar_Trait;
use Jet_Form_Builder\Blocks\Types\Base;
use JET_SM\Gutenberg\Controls_Manager;

/**
 * @property Controls_Manager controls_manager
 *
 * Class Appointment_Date_Field
 * @package JET_APB\Formbuilder_Plugin\Blocks
 */
class Appointment_Date_Field extends Base {

	use Static_Calendar_Trait;

	/**
	 * @return string
	 */
	public function get_name() {
		return 'appointment-date';
	}

	public function get_path_metadata_block() {
		$path_parts = array( 'assets', 'gutenberg', 'src', 'blocks', $this->get_name() );
		$path       = implode( DIRECTORY_SEPARATOR, $path_parts );

		return JET_APB_PATH . $path;
	}

	/**
	 * @param null $wp_block
	 *
	 * @return mixed
	 */
	public function get_block_renderer( $wp_block = null ) {
		ob_start();
		include JET_APB_PATH . 'assets/css/public/vanilla-calendar.css';
		$inline_style = '<style>' . ob_get_clean() . '</style>';

		return $inline_style . ( new Appointment_Date_Field_Render( $this ) )->getFieldTemplate();
	}

	public function general_style_unregister() {
		return array( 'input' );
	}

	protected function _jsm_register_controls() {
		$this->controls_manager->start_section(
			'style_controls',
			array(
				'id'    => 'apb_calendar_styles',
				'title' => esc_html__( 'Calendar', 'jet-appointments-booking' )
			)
		);

		$this->controls_manager->add_responsive_control( [
			'id'           => 'apb_calendar_width',
			'type'         => 'range',
			'label'        => esc_html__( 'Calendar Width', 'jet-appointments-booking' ),
			'separator'    => 'after',
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 600,
					]
				],
				[
					'value'     => '%',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					]
				],
			],
			'css_selector' => [
				$this->selector( '-row .jet-apb-calendar' ) => 'width: {{VALUE}}{{UNIT}};',
			],
		] );

		$this->controls_manager->add_responsive_control( array(
			'id'           => 'apb_calendar_padding',
			'type'         => 'dimensions',
			'separator'    => 'after',
			'label'        => esc_html__( 'Padding', 'jet-appointments-booking' ),
			'units'        => array( 'px' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar' ) => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
			),
		) );

		$this->controls_manager->add_responsive_control( array(
			'id'           => 'apb_calendar_margin',
			'type'         => 'dimensions',
			'label'        => esc_html__( 'Margin', 'jet-appointments-booking' ),
			'units'        => array( 'px' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar' ) => 'margin: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
			),
		) );

		$this->controls_manager->end_section();
		$this->controls_manager->start_section(
			'style_controls',
			array(
				'id'    => 'apb_header',
				'title' => esc_html__( 'Header', 'jet-appointments-booking' )
			)
		);

		$this->controls_manager->add_control( array(
			'id'           => 'apb_header_bg',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Header Background Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar-header' ) => 'background-color: {{VALUE}}',
			),
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_header_color',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Header Text Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar-header' ) => 'color: {{VALUE}}',
			),
		) );


		$this->controls_manager->add_control( array(
			'id'           => 'apb_header_typography',
			'type'         => 'typography',
			'separator'    => 'after',
			'css_selector' => [
				$this->selector( '-row .jet-apb-calendar-header' ) => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',

			],
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_header_arrow_color',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Arrows default color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar-header .jet-apb-calendar-btn path' ) => 'fill: {{VALUE}}',
			),
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_header_arrow_color_hover',
			'type'         => 'color-picker',
			'label'        => esc_html__( 'Arrows hover color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar-header .jet-apb-calendar-btn:hover path' ) => 'fill: {{VALUE}}',
			),
		) );

		$this->controls_manager->end_section();
		$this->controls_manager->start_section(
			'style_controls',
			array(
				'id'    => 'apb_weekdays_names',
				'title' => esc_html__( 'Weekdays/Names', 'jet-appointments-booking' )
			)
		);

		$this->controls_manager->add_control( array(
			'id'           => 'apb_weekdays_typography',
			'type'         => 'typography',
			'separator'    => 'after',
			'css_selector' => [
				$this->selector( '-row .jet-apb-calendar-week span' ) => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',

			],
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_weekdays_color',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar-week span' ) => 'color: {{VALUE}}',
			),
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_weekdays_bg',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Background Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar-week' ) => 'background-color: {{VALUE}}',
			),
		) );

		$this->controls_manager->add_responsive_control( [
			'id'           => 'apb_weekdays_vertical_gap',
			'type'         => 'range',
			'label'        => esc_html__( 'Vertical Gap', 'jet-appointments-booking' ),
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					]
				],
			],
			'css_selector' => [
				$this->selector( '-row .jet-apb-calendar-week span' ) => 'padding-top: calc({{VALUE}}{{UNIT}} / 2);padding-bottom: calc({{VALUE}}{{UNIT}} / 2);',
			],
		] );

		$this->controls_manager->end_section();
		$this->controls_manager->start_section(
			'style_controls',
			array(
				'id'    => 'apb_weekdays_dates',
				'title' => esc_html__( 'Weekdays/Dates', 'jet-appointments-booking' )
			)
		);

		$this->controls_manager->add_control( array(
			'id'           => 'apb_weekdays_dates_typography',
			'type'         => 'typography',
			'separator'    => 'after',
			'css_selector' => [
				$this->selector( '-row .jet-apb-calendar-body' ) => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
			],
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_weekdays_dates_color',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar-date .jet-apb-calendar-date-body' )                                 => 'color: {{VALUE}}',
				$this->selector( '-row .jet-apb-calendar-date.jet-apb-calendar-date--disabled .jet-apb-calendar-date-body' ) => 'color: {{VALUE}} !important',
			),
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_weekdays_dates_color_hover',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Hover Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar-date:hover .jet-apb-calendar-date-body' ) => 'color: {{VALUE}}',
			),
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_weekdays_dates_color_active',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Active Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar-date--selected .jet-apb-calendar-date-body' ) => 'color: {{VALUE}}',
			),
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_weekdays_dates_color_today',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Today Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar-date--today .jet-apb-calendar-date-body' ) => 'color: {{VALUE}}',
			),
		) );


		$this->controls_manager->add_control( array(
			'id'           => 'apb_weekdays_dates_bg',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Background Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar-body' ) => 'background-color: {{VALUE}}',
			),
		) );

		$this->controls_manager->add_responsive_control( [
			'id'           => 'apb_weekdays_dates_vertical_gap',
			'type'         => 'range',
			'label'        => esc_html__( 'Vertical Gap', 'jet-appointments-booking' ),
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					]
				],
			],
			'css_selector' => [
				$this->selector( '-row .jet-apb-calendar-date-body' ) => 'padding-top: calc({{VALUE}}{{UNIT}} / 2);padding-bottom: calc({{VALUE}}{{UNIT}} / 2);',
			],
		] );

		$this->controls_manager->end_section();
		$this->controls_manager->start_section(
			'style_controls',
			array(
				'id'    => 'apb_slots',
				'title' => esc_html__( 'Slots', 'jet-appointments-booking' )
			)
		);

		$this->controls_manager->add_responsive_control( array(
			'id'           => 'apb_slots_container_padding',
			'type'         => 'dimensions',
			'separator'    => 'after',
			'label'        => esc_html__( 'Container Padding', 'jet-appointments-booking' ),
			'units'        => array( 'px', '%', 'em' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar .jet-apb-calendar-slots.jet-apb-calendar-slots--active' ) => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
			),
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_slots_typography',
			'type'         => 'typography',
			'separator'    => 'after',
			'css_selector' => [
				$this->selector( '-row .jet-apb-slot' ) => 'font-family: {{FAMILY}}; font-weight: {{WEIGHT}}; text-transform: {{TRANSFORM}}; font-style: {{STYLE}}; text-decoration: {{DECORATION}}; line-height: {{LINEHEIGHT}}{{LH_UNIT}}; letter-spacing: {{LETTERSPACING}}{{LS_UNIT}}; font-size: {{SIZE}}{{S_UNIT}};',
			],
		) );

		$this->controls_manager->start_tabs( 'style_controls', array(
			'id' => 'tabs_form_slots_style',
		) );

		$this->controls_manager->start_tab( 'style_controls', array(
			'id'    => 'apb_slots_normal',
			'title' => esc_html__( 'Normal', 'jet-appointments-booking' ),
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_slots_color',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-slot' ) => 'color: {{VALUE}}',
			),
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_slots_bg',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Background Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-slot' ) => 'background-color: {{VALUE}}',
			),
		) );

		$this->controls_manager->end_tab();

		$this->controls_manager->start_tab( 'style_controls', array(
			'id'    => 'apb_slots_active',
			'title' => esc_html__( 'Active', 'jet-appointments-booking' ),
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_slots_color_hover',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-slot.jet-apb-slot--selected' ) => 'color: {{VALUE}}',
			),
		) );
		$this->controls_manager->add_control( array(
			'id'           => 'apb_slots_bg_hover',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Background Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-slot.jet-apb-slot--selected' ) => 'background-color: {{VALUE}}',
			),
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_slots_border_color_hover',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Border Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-slot.jet-apb-slot--selected' ) => 'border-color: {{VALUE}}',
			),
		) );

		$this->controls_manager->end_tab();

		$this->controls_manager->end_tabs();

		$this->controls_manager->add_responsive_control( array(
			'id'           => 'apb_slots_padding',
			'type'         => 'dimensions',
			'separator'    => 'after',
			'label'        => esc_html__( 'Padding', 'jet-appointments-booking' ),
			'units'        => array( 'px', '%', 'em' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-slot' ) => 'padding: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
			),
		) );

		$this->controls_manager->add_responsive_control( array(
			'id'           => 'apb_slots_margin',
			'type'         => 'dimensions',
			'separator'    => 'after',
			'label'        => esc_html__( 'Margin', 'jet-appointments-booking' ),
			'units'        => array( 'px', '%', 'em' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-slot' ) => 'margin: {{TOP}} {{RIGHT}} {{BOTTOM}} {{LEFT}};',
			),
		) );

		$this->controls_manager->add_control( [
			'id'           => 'apb_slots_border',
			'type'         => 'border',
			'label'        => esc_html__( 'Border', 'jet-appointments-booking' ),
			'separator'    => 'after',
			'css_selector' => array(
				$this->selector( '-row .jet-apb-slot' ) => 'border-style:{{STYLE}};border-width:{{WIDTH}};border-radius:{{RADIUS}};border-color:{{COLOR}};',
			),
		] );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_slots_close_color',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Slots Close Button Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar-slots__close' ) => 'color: {{VALUE}}',
			),
		) );

		$this->controls_manager->add_control( array(
			'id'           => 'apb_slots_close_color_hover',
			'type'         => 'color-picker',
			'separator'    => 'after',
			'label'        => esc_html__( 'Slots Close Button Hover Color', 'jet-appointments-booking' ),
			'css_selector' => array(
				$this->selector( '-row .jet-apb-calendar-slots__close:hover' ) => 'color: {{VALUE}}',
			),
		) );

		$this->controls_manager->add_control( [
			'id'           => 'apb_slots_close_size',
			'type'         => 'range',
			'label'        => esc_html__( 'Close Button Size', 'jet-appointments-booking' ),
			'separator'    => 'after',
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => 10,
						'max'  => 100,
					]
				],
			],
			'css_selector' => [
				$this->selector( '-row .jet-apb-calendar-slots__close' ) => 'font-size: {{VALUE}}{{UNIT}};',
			],
			'attributes'   => [
				'default' => [
					'value' => 35
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'apb_slots_close_vp',
			'type'         => 'range',
			'label'        => esc_html__( 'Close Button Vertical Position', 'jet-appointments-booking' ),
			'separator'    => 'after',
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => - 100,
						'max'  => 100,
					]
				],
			],
			'css_selector' => [
				$this->selector( '-row .jet-apb-calendar-slots__close' ) => 'top: {{VALUE}}{{UNIT}};',
			],
			'attributes'   => [
				'default' => [
					'value' => 10
				],
			],
		] );

		$this->controls_manager->add_control( [
			'id'           => 'apb_slots_close_hp',
			'type'         => 'range',
			'label'        => esc_html__( 'Close Button Horizontal Position', 'jet-appointments-booking' ),
			'units'        => [
				[
					'value'     => 'px',
					'intervals' => [
						'step' => 1,
						'min'  => - 100,
						'max'  => 100,
					]
				],
			],
			'css_selector' => [
				$this->selector( '-row .jet-apb-calendar-slots__close' ) => 'right: {{VALUE}}{{UNIT}};',
			],
			'attributes'   => [
				'default' => [
					'value' => 10
				],
			],
		] );

		$this->controls_manager->end_section();
	}

	public function block_data( $editor, $handle ) {
		wp_localize_script( $handle, 'JetAppointmentDateField', array(
			'static_calendar' => $this->render_static_calendar()
		) );
	}
}