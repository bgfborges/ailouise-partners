<?php

namespace Elementor;

if (!defined('ABSPATH')) exit;

/**
 * Elementor Blog widget.
 *
 * Elementor widget that displays an eye-catching headlines.
 *
 * @since 1.5.7
 */
class Area_chart extends Widget_Base
{

    /**
     * Get widget name.
     *
     * Retrieve heading widget name.
     *
     * @return string Widget name.
     * @since 1.5.7
     * @access public
     *
     */


    public function get_name()
    {
        return 'area_chart';
    }

    /**
     * Get widget Title.
     *
     * Retrieve heading widget Title.
     *
     * @return string Widget Title.
     * @since 1.5.7
     * @access public
     *
     */

    public function get_title()
    {
        return 'Area';
    }

    /**
     * Get widget categories.
     *
     * Retrieve the list of categories the heading widget belongs to.
     *
     * Used to determine where to display the widget in the editor.
     *
     * @return array Widget categories.
     * @since 1.5.7
     * @access public
     *
     */


    public function get_categories()
    {
        return ['iq-graphina-charts'];
    }


    /**
     * Get widget icon.
     *
     * Retrieve heading widget icon.
     *
     * @return string Widget icon.
     * @since 1.5.7
     * @access public
     *
     */

    public function get_icon()
    {
        return 'fas fa-chart-area';
    }

    public function get_chart_type()
    {
        return 'area';
    }

    protected function _register_controls()
    {
        $type = $this->get_chart_type();
        graphina_basic_setting($this, $type);

        graphina_chart_data_option_setting($this, $type, 0, true);

        $this->start_controls_section(
            'iq_' . $type . '_section_2',
            [
                'label' => esc_html__('Chart Setting', 'graphina-lang'),
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'iq_' . $type . '_chart_is_pro',
                                    'operator' => '==',
                                    'value' => 'false'
                                ],
                                [
                                    'name' => 'iq_' . $type . '_chart_data_option',
                                    'operator' => '==',
                                    'value' => 'manual'
                                ]
                            ]
                        ],
                        [
                            'relation' => 'and',
                            'terms' => [
                                [
                                    'name' => 'iq_' . $type . '_chart_is_pro',
                                    'operator' => '==',
                                    'value' => 'true'
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        );

        $this->add_control(
            'iq_' . $type . '_chart_area_curve',
            [
                'label' => esc_html__('Area Shape', 'graphina-lang'),
                'type' => Controls_Manager::SELECT,
                'default' => graphina_stroke_curve_type(true),
                'options' => graphina_stroke_curve_type(),

            ]
        );

        graphina_common_chart_setting($this, $type, false);

        graphina_tooltip($this, $type);

        graphina_animation($this, $type);

        graphina_dropshadow($this, $type);

        $this->add_control(
            'iq_' . $type . '_chart_hr_category_listing',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => [
                    'iq_' . $type . '_chart_data_option' => 'manual'
                ],
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'iq_' . $type . '_chart_category',
            [
                'label' => esc_html__('Category Value', 'graphina-lang'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Add Value', 'graphina-lang'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        /** Chart value list. */
        $this->add_control(
            'iq_' . $type . '_category_list',
            [
                'label' => esc_html__('Categories', 'graphina-lang'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['iq_' . $type . '_chart_category' => 'Jan'],
                    ['iq_' . $type . '_chart_category' => 'Feb'],
                    ['iq_' . $type . '_chart_category' => 'Mar'],
                    ['iq_' . $type . '_chart_category' => 'Apr'],
                    ['iq_' . $type . '_chart_category' => 'May'],
                    ['iq_' . $type . '_chart_category' => 'Jun'],
                ],
                'condition' => [
                    'iq_' . $type . '_chart_data_option' => 'manual'
                ],
                'title_field' => '{{{ iq_' . $type . '_chart_category }}}',
            ]
        );

        $this->end_controls_section();

        graphina_chart_label_setting($this, $type);

        graphina_advance_x_axis_setting($this, $type);

        graphina_advance_y_axis_setting($this, $type);

        graphina_series_setting($this, $type, ['tooltip', 'color'], true, ['classic', 'gradient', 'pattern'], true, true);

        for ($i = 0; $i < graphina_default_setting('max_series_value'); $i++) {
            $this->start_controls_section(
                'iq_' . $type . '_section_3_' . $i,
                [
                    'label' => esc_html__('Element ' . ($i + 1), 'graphina-lang'),
                    'condition' => [
                        'iq_' . $type . '_chart_data_series_count' => range(1 + $i, graphina_default_setting('max_series_value')),
                        'iq_' . $type . '_chart_data_option' => 'manual'
                    ],
                    'conditions' => [
                        'relation' => 'or',
                        'terms' => [
                            [
                                'relation' => 'and',
                                'terms' => [
                                    [
                                        'name' => 'iq_' . $type . '_chart_is_pro',
                                        'operator' => '==',
                                        'value' => 'false'
                                    ],
                                    [
                                        'name' => 'iq_' . $type . '_chart_data_option',
                                        'operator' => '==',
                                        'value' => 'manual'
                                    ]
                                ]
                            ],
                            [
                                'relation' => 'and',
                                'terms' => [
                                    [
                                        'name' => 'iq_' . $type . '_chart_is_pro',
                                        'operator' => '==',
                                        'value' => 'true'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            );
            $this->add_control(
                'iq_' . $type . '_chart_title_3_' . $i,
                [
                    'label' => 'Title',
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Add Tile', 'graphina-lang'),
                    'default' => 'Element ' . ($i + 1),
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $repeater = new Repeater();


            $repeater->add_control(
                'iq_' . $type . '_chart_value_3_' . $i,
                [
                    'label' => 'Element Value',
                    'type' => Controls_Manager::NUMBER,
                    'placeholder' => esc_html__('Add Value', 'graphina-lang'),
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            /** Chart value list. */
            $this->add_control(
                'iq_' . $type . '_value_list_3_1_' . $i,
                [
                    'label' => esc_html__('Values', 'graphina-lang'),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)]
                    ],
                    'condition' => [
                        'iq_' . $type . '_can_chart_negative_values!' => 'yes'
                    ],
                    'title_field' => '{{{ iq_' . $type . '_chart_value_3_' . $i . ' }}}',
                ]
            );

            $this->add_control(
                'iq_' . $type . '_value_list_3_2_' . $i,
                [
                    'label' => esc_html__('Values', 'graphina-lang'),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(-200, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(-200, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(-200, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(-200, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(-200, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(-200, 200)]
                    ],
                    'condition' => [
                        'iq_' . $type . '_can_chart_negative_values' => 'yes'
                    ],
                    'title_field' => '{{{ iq_' . $type . '_chart_value_3_' . $i . ' }}}',
                ]
            );

            $this->end_controls_section();

        }

        graphina_style_section($this, $type);

        graphina_card_style($this, $type);

        graphina_chart_style($this, $type);

        if (function_exists('graphina_pro_password_style_section')) {
            graphina_pro_password_style_section($this, $type);
        }
    }

    protected function render()
    {
        $type = $this->get_chart_type();
        $settings = $this->get_settings_for_display();
        $mainId = $this->get_id();
        $second_gradient = [];
        $fill_pattern = [];
        $dropShadowSeries = [];
        $tooltipSeries = [];
        $gradient = [];
        $markerSize = [];
        $markerStrokeColor = [];
        $markerStokeWidth = [];
        $markerShape = [];
        $data = ['series' => [], 'category' => []];
        $dataLabelPrefix = $dataLabelPostfix = $yLabelPrefix = $yLabelPostfix = $xLabelPrefix = $xLabelPostfix = '';
        $callAjax = false;
        $loadingText = esc_html__((isset($settings['iq_' . $type . '_chart_no_data_text']) ? $settings['iq_' . $type . '_chart_no_data_text'] : ''), 'graphina-lang');

        $exportFileName = (
            !empty($settings['iq_' . $type . '_can_chart_show_toolbar']) && $settings['iq_' . $type . '_can_chart_show_toolbar'] === 'yes'
            && !empty($settings['iq_' . $type . '_export_filename'])
        ) ? $settings['iq_' . $type . '_export_filename'] : $mainId;

        if ($settings['iq_' . $type . '_chart_datalabel_show'] === 'yes') {
            $dataLabelPrefix = $settings['iq_' . $type . '_chart_datalabel_prefix'];
            $dataLabelPostfix = $settings['iq_' . $type . '_chart_datalabel_postfix'];
        }

        if ($settings['iq_' . $type . '_chart_xaxis_label_show'] === 'yes') {
            $xLabelPrefix = $settings['iq_' . $type . '_chart_xaxis_label_prefix'];
            $xLabelPostfix = $settings['iq_' . $type . '_chart_xaxis_label_postfix'];
        }

        if ($settings['iq_' . $type . '_chart_yaxis_label_show'] === 'yes') {
            $yLabelPrefix = $settings['iq_' . $type . '_chart_yaxis_label_prefix'];
            $yLabelPostfix = $settings['iq_' . $type . '_chart_yaxis_label_postfix'];
        }
        $seriesCount = isset($settings['iq_' . $type . '_chart_data_series_count']) ? $settings['iq_' . $type . '_chart_data_series_count'] : 0;
        for ($i = 0; $i < $seriesCount; $i++) {
            $dropShadowSeries[] = $i;
            if (!empty($settings['iq_' . $type . '_chart_tooltip_enabled_on_1_' . $i]) && $settings['iq_' . $type . '_chart_tooltip_enabled_on_1_' . $i] === "yes") {
                $tooltipSeries[] = $i;
            }
            $gradient[] = strval($settings['iq_' . $type . '_chart_gradient_1_' . $i]);
            if (strval($settings['iq_' . $type . '_chart_gradient_2_' . $i]) === '') {
                $second_gradient[] = strval($settings['iq_' . $type . '_chart_gradient_1_' . $i]);
            } else {
                $second_gradient[] = strval($settings['iq_' . $type . '_chart_gradient_2_' . $i]);
            }
            if ($settings['iq_' . $type . '_chart_bg_pattern_' . $i] !== '') {
                $fill_pattern[] = $settings['iq_' . $type . '_chart_bg_pattern_' . $i];
            } else {
                $fill_pattern[] = 'verticalLines';
            }
            $markerSize[] = $settings['iq_' . $type . '_chart_marker_size_'.$i];
            $markerStrokeColor[] = strval($settings[ 'iq_' . $type . '_chart_marker_stroke_color_'.$i]);
            $markerStokeWidth[] = $settings[ 'iq_' . $type . '_chart_marker_stroke_width_'.$i];
            $markerShape[] = strval($settings['iq_' . $type . '_chart_chart_marker_stroke_shape_'.$i]);
        }
        $categoryList = $settings['iq_' . $type . '_category_list'];
        if (isGraphinaPro() && $settings['iq_' . $type . '_chart_data_option'] !== 'manual') {
            $new_settings = graphina_setting_sort($settings);
            $callAjax = true;
            $gradient = $second_gradient = ['#ffffff'];
            $loadingText = esc_html__('Loading...', 'graphina-lang');
        } else {
            $new_settings = [];
            if (gettype($categoryList) === "NULL") {
                $categoryList = [];
            }
            foreach ($categoryList as $v) {
                $data["category"][] = (string)graphina_get_dynamic_tag_data($v, 'iq_' . $type . '_chart_category');
            }
            for ($i = 0; $i < $settings['iq_' . $type . '_chart_data_series_count']; $i++) {
                $valueList = $settings['iq_' . $type . '_value_list_3_' . ($settings['iq_' . $type . '_can_chart_negative_values'] === 'yes' ? 2 : 1) . '_' . $i];
                $value = [];
                if (gettype($valueList) === "NULL") {
                    $valueList = [];
                }
                foreach ($valueList as $v) {
                    $value[] = (float)graphina_get_dynamic_tag_data($v, 'iq_' . $type . '_chart_value_3_' . $i);
                }
                $data['series'][] = [
                    'name' => (string)graphina_get_dynamic_tag_data($settings, 'iq_' . $type . '_chart_title_3_' . $i),
                    'data' => $value,
                ];
            }
            if ($settings['iq_' . $type . '_chart_data_option'] === 'dynamic') {
                $data = ['series' => [], 'category' => []];
            }
            $gradient_new = $second_gradient_new = $fill_pattern_new = [];
            $desiredLength = count($data['series']);
            while (count($gradient_new) < $desiredLength) {
                $gradient_new = array_merge($gradient_new, $gradient);
                $second_gradient_new = array_merge($second_gradient_new, $second_gradient);
                $fill_pattern_new = array_merge($fill_pattern_new, $fill_pattern);
            }
            $gradient = array_slice($gradient_new, 0, $desiredLength);
            $second_gradient = array_slice($second_gradient_new, 0, $desiredLength);
            $fill_pattern = array_slice($fill_pattern_new, 0, $desiredLength);
        }

        $markerSize =  implode('_,_', $markerSize);
        $markerStrokeColor =  implode('_,_', $markerStrokeColor);
        $markerStokeWidth =  implode('_,_', $markerStokeWidth);
        $markerShape =  implode('_,_', $markerShape);
        $gradient = implode('_,_', $gradient);
        $second_gradient = implode('_,_', $second_gradient);
        $fill_pattern = implode('_,_', $fill_pattern);
        $category = implode('_,_', $data['category']);
        $chartDataJson = json_encode($data['series']);
        $dropShadowSeries = implode(',', $dropShadowSeries);
        $tooltipSeries = implode(',', $tooltipSeries);

        require GRAPHINA_ROOT . '/elementor/charts/area/render/area_chart.php';
        if (isRestrictedAccess('area', $this->get_id(), $settings, false) === false) {
            ?>
            <script>
                var myElement = document.querySelector(".area-chart-<?php esc_attr_e($mainId); ?>");

                if (typeof isInit === 'undefined') {
                    var isInit = {};
                }
                isInit['<?php esc_attr_e($mainId); ?>'] = false;

                var areaOptions = {
                    series: <?php echo $chartDataJson; ?>,
                    chart: {
                        height: parseInt('<?php echo $settings['iq_' . $type . '_chart_height']; ?>'),
                        type: 'area',
                        toolbar: {
                            show: '<?php echo $settings['iq_' . $type . '_can_chart_show_toolbar'] === 'yes'; ?>',
                            export: {
                                csv: {
                                    filename: "<?php echo $exportFileName; ?>"
                                },
                                svg: {
                                    filename: "<?php echo $exportFileName; ?>"
                                },
                                png: {
                                    filename: "<?php echo $exportFileName; ?>"
                                }
                            }
                        },
                        dropShadow: {
                            enabled: '<?php echo($settings['iq_' . $type . '_is_chart_dropshadow'] === "yes"); ?>',
                            enabledOnSeries: [<?php esc_html_e($dropShadowSeries); ?>],
                            top: parseInt('<?php echo $settings['iq_' . $type . '_is_chart_dropshadow_top']; ?>'),
                            left: parseInt('<?php echo $settings['iq_' . $type . '_is_chart_dropshadow_left']; ?>'),
                            blur: parseInt('<?php echo $settings['iq_' . $type . '_is_chart_dropshadow_blur']; ?>'),
                            color: '<?php echo strval(isset($settings['iq_' . $type . '_is_chart_dropshadow_color']) ? $settings['iq_' . $type . '_is_chart_dropshadow_color'] : ''); ?>',
                            opacity: parseFloat('<?php echo $settings['iq_' . $type . '_is_chart_dropshadow_opacity']; ?>')
                        },
                        animations: {
                            enabled: '<?php echo($settings['iq_' . $type . '_chart_animation'] === "yes"); ?>',
                            speed: parseInt('<?php echo $settings['iq_' . $type . '_chart_animation_speed']; ?>'),
                            delay: parseInt('<?php echo $settings['iq_' . $type . '_chart_animation_delay']; ?>')
                        }
                    },
                    noData: {
                        text: '<?php echo $loadingText; ?>',
                        align: 'center',
                        verticalAlign: 'middle',
                        style: {
                            fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                            fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>',
                            color: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']); ?>'
                        }
                    },
                    dataLabels: {
                        enabled: '<?php echo $settings['iq_' . $type . '_chart_datalabel_show'] === 'yes'; ?>',
                        style: {
                            colors: ['<?php echo $settings['iq_' . $type . '_chart_datalabel_background_show'] === "yes" ? strval($settings['iq_' . $type . '_chart_datalabel_font_color_1']) : strval($settings['iq_' . $type . '_chart_datalabel_font_color']); ?>']
                        },
                        background: {
                            enabled: '<?php echo $settings['iq_' . $type . '_chart_datalabel_background_show'] === "yes"; ?>',
                            foreColor: ['<?php echo strval($settings['iq_' . $type . '_chart_datalabel_background_color']); ?>'],
                            borderWidth: parseInt('<?php echo $settings['iq_' . $type . '_chart_datalabel_border_width']; ?>'),
                            borderColor: '<?php echo strval($settings['iq_' . $type . '_chart_datalabel_border_color']); ?>'
                        },
                        formatter: function (val, opts) {
                            return '<?php esc_html_e($dataLabelPrefix); ?>' + val + '<?php esc_html_e($dataLabelPostfix); ?>';
                        }
                    },
                    stroke: {
                        curve: '<?php echo $settings['iq_' . $type . '_chart_area_curve']; ?>',
                    },
                    grid: {
                        yaxis: {
                            lines: {
                                show: '<?php echo $settings['iq_' . $type . '_chart_yaxis_line_show'] === "yes"; ?>'
                            }
                        }
                    },
                    xaxis: {
                        categories: '<?php echo $category; ?>'.split('_,_'),
                        position: '<?php esc_html_e($settings['iq_' . $type . '_chart_xaxis_datalabel_position']); ?>',
                        tickAmount: parseInt("<?php esc_html_e($settings['iq_' . $type . '_chart_xaxis_datalabel_tick_amount']); ?>"),
                        tickPlacement: "<?php esc_html_e($settings['iq_' . $type . '_chart_xaxis_datalabel_tick_placement']) ?>",
                        labels: {
                            show: '<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_show'] === "yes"; ?>',
                            rotateAlways: '<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_auto_rotate'] === "yes"; ?>',
                            rotate: '<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_rotate']; ?>',
                            offsetX: parseInt('<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_offset_x']; ?>'),
                            offsetY: parseInt('<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_offset_y']; ?>'),
                            trim: true,
                            style: {
                                colors: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']); ?>',
                                fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                                fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>',
                                fontWeight: '<?php echo $settings['iq_' . $type . '_chart_font_weight']; ?>'
                            },
                            formatter: function (val) {
                                return '<?php esc_html_e($xLabelPrefix); ?>' + val + '<?php esc_html_e($xLabelPostfix); ?>';
                            }
                        },
                        tooltip: {
                            enabled: "<?php echo !empty($settings['iq_' . $type . '_chart_xaxis_tooltip_show']) && $settings['iq_' . $type . '_chart_xaxis_tooltip_show'] === 'yes';?>"
                        },
                        crosshairs: {
                            show: "<?php echo !empty($settings['iq_' . $type . '_chart_xaxis_crosshairs_show']) && $settings['iq_' . $type . '_chart_xaxis_crosshairs_show'] === 'yes';?>"
                        }
                    },
                    yaxis: {
                        opposite: '<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_position'] === "yes"; ?>',
                        tickAmount: parseInt("<?php esc_html_e($settings['iq_' . $type . '_chart_yaxis_datalabel_tick_amount']); ?>"),
                        decimalsInFloat: parseInt("<?php esc_html_e($settings['iq_' . $type . '_chart_yaxis_datalabel_decimals_in_float']); ?>"),
                        labels: {
                            show: '<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_show'] === "yes"; ?>',
                            rotate: '<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_rotate']; ?>',
                            offsetX: parseInt('<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_offset_x']; ?>'),
                            offsetY: parseInt('<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_offset_y']; ?>'),
                            style: {
                                colors: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']); ?>',
                                fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                                fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>',
                                fontWeight: '<?php echo $settings['iq_' . $type . '_chart_font_weight']; ?>'
                            }
                        },
                        tooltip: {
                            enabled: "<?php echo !empty($settings['iq_' . $type . '_chart_yaxis_tooltip_show']) && $settings['iq_' . $type . '_chart_yaxis_tooltip_show'] === 'yes';?>"
                        },
                        crosshairs: {
                            show: "<?php echo !empty($settings['iq_' . $type . '_chart_yaxis_crosshairs_show']) && $settings['iq_' . $type . '_chart_yaxis_crosshairs_show'] === 'yes';?>"
                        }
                    },
                    colors: '<?php echo $gradient; ?>'.split('_,_'),
                    fill: {
                        type: '<?php echo $settings['iq_' . $type . '_chart_fill_style_type']; ?>',
                        opacity: parseFloat('<?php echo $settings['iq_' . $type . '_chart_fill_opacity']; ?>'),
                        colors: '<?php echo $gradient; ?>'.split('_,_'),
                        gradient: {
                            gradientToColors: '<?php echo $second_gradient; ?>'.split('_,_'),
                            type: '<?php echo $settings['iq_' . $type . '_chart_gradient_type']; ?>',
                            inverseColors: '<?php echo $settings['iq_' . $type . '_chart_gradient_inversecolor'] === "yes"; ?>',
                            opacityFrom: parseFloat('<?php echo $settings['iq_' . $type . '_chart_gradient_opacityFrom']; ?>'),
                            opacityTo: parseFloat('<?php echo $settings['iq_' . $type . '_chart_gradient_opacityTo']; ?>')
                        },
                        pattern: {
                            style: '<?php echo $fill_pattern; ?>'.split('_,_'),
                            width: 6,
                            height: 6,
                            strokeWidth: 2
                        }
                    },
                    legend: {
                        showForSingleSeries:true,
                        show: '<?php echo $settings['iq_' . $type . '_chart_legend_show'] === "yes"; ?>',
                        position: '<?php esc_html_e($settings['iq_' . $type . '_chart_legend_position']); ?>',
                        horizontalAlign: '<?php esc_html_e($settings['iq_' . $type . '_chart_legend_horizontal_align']); ?>',
                        fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                        fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>',
                        fontWeight: '<?php echo $settings['iq_' . $type . '_chart_font_weight']; ?>',
                        labels: {
                            colors: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']); ?>'
                        }
                    },
                    tooltip: {
                        enabled: '<?php echo $settings['iq_' . $type . '_chart_tooltip'] === "yes"; ?>',
                        shared: '<?php echo !empty($settings['iq_' . $type . '_chart_tooltip_shared']) ? $settings['iq_' . $type . '_chart_tooltip_shared'] : ''; ?>' === "yes",
                        theme: '<?php echo $settings['iq_' . $type . '_chart_tooltip_theme']; ?>',
                        style: {
                            fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                            fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>'
                        }
                    }
                };

                if ("<?php esc_html_e($settings['iq_' . $type . '_chart_yaxis_label_show']); ?>" === "yes") {
                    areaOptions.yaxis.labels.formatter = function (val) {
                        return '<?php esc_html_e($yLabelPrefix); ?>' + val + '<?php esc_html_e($yLabelPostfix); ?>';
                    }
                }
                if ("<?php echo !empty($settings['iq_' . $type . '_chart_tooltip_shared']) ? $settings['iq_' . $type . '_chart_tooltip_shared'] : '';?>" === "yes") {
                    areaOptions.tooltip['enabledOnSeries'] = [<?php esc_html_e($tooltipSeries); ?>];
                }
                if ("<?php esc_html_e($settings['iq_' . $type . '_chart_yaxis_0_indicator_show']); ?>" === "yes") {
                    areaOptions['annotations'] = {
                        yaxis: [
                            {
                                y: 0,
                                strokeDashArray: parseInt("<?php echo !empty($settings['iq_' . $type . '_chart_yaxis_0_indicator_stroke_dash']) ? $settings['iq_' . $type . '_chart_yaxis_0_indicator_stroke_dash'] : 0; ?>"),
                                borderColor: '<?php echo !empty($settings['iq_' . $type . '_chart_yaxis_0_indicator_stroke_color']) ? strval($settings['iq_' . $type . '_chart_yaxis_0_indicator_stroke_color']) : "#000000"; ?>'
                            }
                        ]
                    };
                }

                    areaOptions['markers'] ={
                        size: '<?php echo $markerSize; ?>'.split('_,_'),
                        strokeColors: '<?php echo $markerStrokeColor; ?>'.split('_,_'),
                        strokeWidth: '<?php echo $markerStokeWidth; ?>'.split('_,_'),
                        shape: '<?php echo $markerShape; ?>'.split('_,_'),
                        showNullDataPoints: true,
                        hover: {
                            size: 3,
                            sizeOffset: 1
                        }
                    }

                if (typeof initNowGraphina !== "undefined") {
                    initNowGraphina(
                        myElement,
                        {
                            ele: myElement,
                            options: areaOptions,
                            series: [{name: '', data: []}],
                            animation: true
                        },
                        '<?php esc_attr_e($mainId); ?>'
                    );
                }
                if (window.ajaxIntervalGraphina_<?php echo $mainId; ?> !== undefined) {
                    clearInterval(window.ajaxIntervalGraphina_<?php echo $mainId; ?>)
                }
            </script>
            <?php
        }
        if (isGraphinaPro() && $settings['iq_' . $type . '_chart_data_option'] !== 'manual') {
            graphina_ajax_reload($callAjax, $new_settings, $type, $mainId);
        }
    }
}

Plugin::instance()->widgets_manager->register_widget_type(new Area_chart());