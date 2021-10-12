<?php
namespace JET_APB;

/**
 * Form controls and notifications class
 */
class Form_Widget {

	/**
	 * Constructor for the class
	 */
	public function __construct() {
		add_action(
			'elementor/element/jet-engine-booking-form/form_submit_style/before_section_start',
			function ( $widget ) {
				call_user_func( array( $this, 'calendar_styles_settings' ), $widget, array( $widget, 'css_selector' ) );
			}
		);
		add_action(
			'elementor/element/jet-form-builder-form/form_submit_style/before_section_start',
			function ( $widget ) {
				call_user_func( array( $this, 'calendar_styles_settings' ), $widget, array( $widget, 'selector' ) );
			}
		);
	}

	public function calendar_styles_settings( $widget, $selector ) {
		$widget->start_controls_section(
			'apb_calendar_styles',
			array(
				'label'      => esc_html__( 'Appointment Calendar', 'jet-appointments-booking' ),
				'tab'        => \Elementor\Controls_Manager::TAB_STYLE,
				'show_label' => false,
			)
		);

		$widget->add_responsive_control(
			'apb_calendar_width',
			array(
				'label' => __( 'Calendar Width', 'jet-appointments-booking' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'size_units' => array( 'px', '%' ),
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 600,
					),
				),
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar' ) => 'width: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$widget->add_responsive_control(
			'apb_calendar_padding',
			array(
				'label'      => __( 'Padding', 'jet-appointments-booking' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					call_user_func( $selector, ' .jet-apb-calendar' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$widget->add_responsive_control(
			'apb_calendar_margin',
			array(
				'label'      => __( 'Margin', 'jet-appointments-booking' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px' ),
				'selectors'  => array(
					call_user_func( $selector, ' .jet-apb-calendar' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			array(
				'name'     => 'apb_calendar_box_shadow',
				'selector' => call_user_func( $selector, ' .jet-apb-calendar' ),
			)
		);

		$widget->add_control(
			'apb_header',
			array(
				'label'     => esc_html__( 'Header', 'jet-appointments-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
			)
		);

		$widget->add_control(
			'apb_header_bg',
			array(
				'label'  => esc_html__( 'Header Background Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-header' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$widget->add_control(
			'apb_header_color',
			array(
				'label'  => esc_html__( 'Header Text Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-header' ) => 'color: {{VALUE}}',
				),
			)
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'apb_header_typography',
				'selector' => call_user_func( $selector, ' .jet-apb-calendar-header' ),
			)
		);

		$widget->add_control(
			'apb_header_arrow_color',
			array(
				'label'  => esc_html__( 'Arrows default color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-header .jet-apb-calendar-btn path' ) => 'fill: {{VALUE}}',
				),
			)
		);

		$widget->add_control(
			'apb_header_arrow_color_hover',
			array(
				'label'  => esc_html__( 'Arrows hover color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-header .jet-apb-calendar-btn:hover path' ) => 'fill: {{VALUE}}',
				),
			)
		);

		$widget->add_control(
			'apb_weekdays',
			array(
				'label'     => esc_html__( 'Weekdays/Names', 'jet-appointments-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'apb_weekdays_typography',
				'selector' => call_user_func( $selector, ' .jet-apb-calendar-week span' ),
			)
		);

		$widget->add_control(
			'apb_weekdays_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-week span' ) => 'color: {{VALUE}}',
				),
			)
		);

		$widget->add_control(
			'apb_weekdays_bg',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-week' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$widget->add_responsive_control(
			'apb_weekdays_vertical_gap',
			array(
				'label' => __( 'Vertical Gap', 'jet-appointments-booking' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-week span' ) => 'padding-top: calc({{SIZE}}{{UNIT}} / 2);padding-bottom: calc({{SIZE}}{{UNIT}} / 2);',
				),
			)
		);

		$widget->add_control(
			'apb_weekdays_dates',
			array(
				'label'     => esc_html__( 'Weekdays/Dates', 'jet-appointments-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'apb_weekdays_dates_typography',
				'selector' => call_user_func( $selector, ' .jet-apb-calendar-body' ),
			)
		);

		$widget->add_control(
			'apb_weekdays_dates_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-date .jet-apb-calendar-date-body' ) => 'color: {{VALUE}}',
					call_user_func( $selector, ' .jet-apb-calendar-date.jet-apb-calendar-date--disabled .jet-apb-calendar-date-body' ) => 'color: {{VALUE}} !important',
				),
			)
		);

		$widget->add_control(
			'apb_weekdays_dates_color_hover',
			array(
				'label'  => esc_html__( 'Hover Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-date:hover .jet-apb-calendar-date-body' ) => 'color: {{VALUE}}',
				),
			)
		);

		$widget->add_control(
			'apb_weekdays_dates_color_active',
			array(
				'label'  => esc_html__( 'Active Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-date--selected .jet-apb-calendar-date-body' ) => 'color: {{VALUE}}',
				),
			)
		);

		$widget->add_control(
			'apb_weekdays_dates_color_today',
			array(
				'label'  => esc_html__( 'Today Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-date--today .jet-apb-calendar-date-body' ) => 'color: {{VALUE}}',
				),
			)
		);

		$widget->add_control(
			'apb_weekdays_dates_bg',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-body' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$widget->add_responsive_control(
			'apb_weekdays_dates_vertical_gap',
			array(
				'label' => __( 'Vertical Gap', 'jet-appointments-booking' ),
				'type'  => \Elementor\Controls_Manager::SLIDER,
				'range' => array(
					'px' => array(
						'min' => 0,
						'max' => 100,
					),
				),
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-date-body' ) => 'padding-top: calc({{SIZE}}{{UNIT}} / 2);padding-bottom: calc({{SIZE}}{{UNIT}} / 2);',
				),
			)
		);

		$widget->add_control(
			'apb_slots',
			array(
				'label'     => esc_html__( 'Slots', 'jet-appointments-booking' ),
				'type'      => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			)
		);

		$widget->add_responsive_control(
			'apb_slots_container_padding',
			array(
				'label'      => __( 'Container Padding', 'jet-appointments-booking' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					call_user_func( $selector, ' .jet-apb-calendar .jet-apb-calendar-slots.jet-apb-calendar-slots--active' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			array(
				'name'     => 'apb_slots_typography',
				'selector' => call_user_func( $selector, ' .jet-apb-slot' ),
			)
		);

		$widget->start_controls_tabs( 'tabs_form_submit_style' );

		$widget->start_controls_tab(
			'apb_slots_normal',
			array(
				'label' => __( 'Normal', 'jet-appointments-booking' ),
			)
		);

		$widget->add_control(
			'apb_slots_color',
			array(
				'label'  => esc_html__( 'Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-slot' ) => 'color: {{VALUE}}',
				),
			)
		);

		$widget->add_control(
			'apb_slots_bg',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-slot' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$widget->end_controls_tab();

		$widget->start_controls_tab(
			'apb_slots_hover',
			array(
				'label' => __( 'Active', 'jet-appointments-booking' ),
			)
		);

		$widget->add_control(
			'apb_slots_color_hover',
			array(
				'label'  => esc_html__( 'Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-slot.jet-apb-slot--selected' ) => 'color: {{VALUE}}',
				),
			)
		);

		$widget->add_control(
			'apb_slots_bg_hover',
			array(
				'label'  => esc_html__( 'Background Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-slot.jet-apb-slot--selected' ) => 'background-color: {{VALUE}}',
				),
			)
		);

		$widget->add_control(
			'apb_slots_border_color_hover',
			array(
				'label' => __( 'Border Color', 'jet-appointments-booking' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => array(
					'apb_slots_border_border!' => '',
				),
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-slot.jet-apb-slot--selected' ) => 'border-color: {{VALUE}};',
				),
			)
		);

		$widget->end_controls_tab();

		$widget->end_controls_tabs();

		$widget->add_responsive_control(
			'apb_slots_padding',
			array(
				'label'      => __( 'Padding', 'jet-appointments-booking' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'separator'  => 'before',
				'selectors'  => array(
					call_user_func( $selector, ' .jet-apb-slot' ) => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$widget->add_responsive_control(
			'apb_slots_margin',
			array(
				'label'      => __( 'Margin', 'jet-appointments-booking' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%', 'em' ),
				'selectors'  => array(
					call_user_func( $selector, ' .jet-apb-slot' ) => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$widget->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			array(
				'name'           => 'apb_slots_border',
				'label'          => __( 'Border', 'jet-appointments-booking' ),
				'placeholder'    => '1px',
				'selector'       => call_user_func( $selector, ' .jet-apb-slot' ),
			)
		);

		$widget->add_responsive_control(
			'apb_slots_border_radius',
			array(
				'label'      => __( 'Border Radius', 'jet-appointments-booking' ),
				'type'       => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => array( 'px', '%' ),
				'selectors'  => array(
					call_user_func( $selector, ' .jet-apb-slot' ) => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$widget->add_control(
			'apb_slots_close_color',
			array(
				'label'  => esc_html__( 'Slots Close Button Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-slots__close' ) => 'color: {{VALUE}}',
				),
			)
		);

		$widget->add_control(
			'apb_slots_close_color_hover',
			array(
				'label'  => esc_html__( 'Slots Close Button Hover Color', 'jet-appointments-booking' ),
				'type'   => \Elementor\Controls_Manager::COLOR,
				'selectors' => array(
					call_user_func( $selector, ' .jet-apb-calendar-slots__close:hover' ) => 'color: {{VALUE}}',
				),
			)
		);

		$widget->add_control(
			'apb_slots_close_size',
			array(
				'label'       => esc_html__( 'Close Button Size', 'jet-appointments-booking' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min' => 10,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 35,
				),
				'selectors'  => array(
					call_user_func( $selector, ' .jet-apb-calendar-slots__close' ) => 'font-size: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$widget->add_control(
			'apb_slots_close_vp',
			array(
				'label'       => esc_html__( 'Close Button Vertical Position', 'jet-appointments-booking' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 10,
				),
				'selectors'  => array(
					call_user_func( $selector, ' .jet-apb-calendar-slots__close' ) => 'top: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$widget->add_control(
			'apb_slots_close_hp',
			array(
				'label'       => esc_html__( 'Close Button Horizontal Position', 'jet-appointments-booking' ),
				'type'        => \Elementor\Controls_Manager::SLIDER,
				'size_units'  => array( 'px' ),
				'range'       => array(
					'px' => array(
						'min' => -100,
						'max' => 100,
					),
				),
				'default' => array(
					'size' => 10,
				),
				'selectors'  => array(
					call_user_func( $selector, ' .jet-apb-calendar-slots__close' ) => 'right: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$widget->end_controls_section();

	}

}
