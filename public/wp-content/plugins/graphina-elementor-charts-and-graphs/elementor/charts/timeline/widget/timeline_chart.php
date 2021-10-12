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
class Timeline_chart extends Widget_Base
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
        return 'timeline_chart';
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
        return 'Timeline';
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
        return 'fas fa-stream';
    }

    /***************************************
     * @param string $type
     * @param int $i
     * @param int $count
     * @return array
     */

    protected function timelineDataGenerator($type = '', $i = 0, $count = 20)
    {
        $result = [];
        for ($j = 0; $j < $count; $j++) {
            $start = graphina_getRandomDate(date('Y-m-d H:i'), 'Y-m-d h:i', ['day' => rand(0, 5), 'hour' => rand(0, 6), 'minute' => rand(0, 50)], ['day' => rand(0, 5), 'hour' => rand(0, 6), 'minute' => rand(0, 50)]);
            $end = graphina_getRandomDate(date('Y-m-d H:i', strtotime($start)), 'Y-m-d h:i', ['day' => rand(0, 5), 'hour' => rand(0, 6), 'minute' => rand(0, 50)]);
            $result[] = [
                'iq_' . $type . '_chart_from_date_' . $i => $start,
                'iq_' . $type . '_chart_to_date_' . $i => $end
            ];
        }
        return $result;
    }

    public function get_chart_type()
    {
        return 'timeline';
    }

    protected function _register_controls()
    {
        $type = $this->get_chart_type();
        graphina_basic_setting($this, $type);

        graphina_chart_data_option_setting($this, $type);

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

        graphina_common_chart_setting($this, $type, false, false);

        graphina_tooltip($this, $type, true, false);

        graphina_animation($this, $type);

        $this->add_control(
            'iq_' . $type . '_chart_hr_stroke_setting',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'iq_' . $type . '_chart_stroke_setting_title',
            [
                'label' => esc_html__('Stroke Settings', 'graphina-lang'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'iq_' . $type . '_chart_stroke_width',
            [
                'label' => 'Width',
                'type' => Controls_Manager::NUMBER,
                'default' => 5,
                'min' => 1,
                'max' => 20
            ]
        );

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
                'label' => 'Category Value',
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__('Add Value', 'graphina-lang'),
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        /** Chart category list. */
        $this->add_control(
            'iq_' . $type . '_category_list',
            [
                'label' => esc_html__('Categories', 'graphina-lang'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    ['iq_' . $type . '_chart_category' => 'Planning'],
                    ['iq_' . $type . '_chart_category' => 'Analysis'],
                    ['iq_' . $type . '_chart_category' => 'Testing'],
                    ['iq_' . $type . '_chart_category' => 'Release']
                ],
                'condition' => [
                    'iq_' . $type . '_chart_data_option' => 'manual'
                ],
                'title_field' => '{{{ iq_' . $type . '_chart_category }}}',
            ]
        );

        $this->end_controls_section();

        graphina_chart_label_setting($this, $type);

        graphina_advance_x_axis_setting($this, $type, false, false);

        graphina_advance_y_axis_setting($this, $type, false, false);

        graphina_series_setting($this, $type, ['color'], true, ['classic', 'gradient', 'pattern'], true, true);

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
                    'label' => 'Element Title',
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
                'iq_' . $type . '_chart_from_date_' . $i,
                [
                    'label' => esc_html__('From Date ( Y )', 'graphina-lang'),
                    'type' => Controls_Manager::DATE_TIME,
                    'placeholder' => esc_html__('Select Date', 'graphina-lang')
                ]
            );

            $repeater->add_control(
                'iq_' . $type . '_chart_to_date_' . $i,
                [
                    'label' => esc_html__('To Date ( Y )', 'graphina-lang'),
                    'type' => Controls_Manager::DATE_TIME,
                    'placeholder' => esc_html__('Select Date', 'graphina-lang')
                ]
            );

            /** Chart value list. **/
            $this->add_control(
                'iq_' . $type . '_value_list_' . $i,
                [
                    'label' => esc_html__('Chart Value list', 'graphina-lang'),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => $this->timelineDataGenerator('timeline', $i, 4)
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
        $data = ['series' => [], 'category' => []];
        $gradient = [];
        $second_gradient = [];
        $fill_pattern = [];
        $callAjax = false;
        $loadingText = esc_html__((isset($settings['iq_' . $type . '_chart_no_data_text']) ? $settings['iq_' . $type . '_chart_no_data_text'] : ''), 'graphina-lang');
        $seriesCount = isset($settings['iq_' . $type . '_chart_data_series_count']) ? $settings['iq_' . $type . '_chart_data_series_count'] : 0;

        $exportFileName = (
            !empty($settings['iq_' . $type . '_can_chart_show_toolbar']) && $settings['iq_' . $type . '_can_chart_show_toolbar'] === 'yes'
            && !empty($settings['iq_' . $type . '_export_filename'])
        ) ? $settings['iq_' . $type . '_export_filename'] : $mainId;

        for ($i = 0; $i < $seriesCount; $i++) {
            $gradient[] = strval($settings['iq_' . $type . '_chart_gradient_1_' . $i]);
            $second_gradient[] = isset($settings['iq_' . $type . '_chart_gradient_2_' . $i]) ? strval($settings['iq_' . $type . '_chart_gradient_1_' . $i]) : strval($settings['iq_' . $type . '_chart_gradient_2_' . $i]);
            $fill_pattern[] = $settings['iq_' . $type . '_chart_bg_pattern_' . $i] !== '' ? $settings['iq_' . $type . '_chart_bg_pattern_' . $i] : 'verticalLines';
        }
        if (isGraphinaPro() && $settings['iq_' . $type . '_chart_data_option'] !== 'manual') {
            $new_settings = graphina_setting_sort($settings);
            $callAjax = true;
            $loadingText = esc_html__('Loading...', 'graphina-lang');
            $gradient = $second_gradient = ['#ffffff'];
        } else {
            $new_settings = [];
            for ($i = 0; $i < $seriesCount; $i++) {
                $value = [];
                $valueList = $settings['iq_' . $type . '_value_list_' . $i];
                if (gettype($valueList) === "NULL") {
                    $valueList = [];
                }
                foreach ($valueList as $key => $val) {
                    if (count($settings['iq_' . $type . '_category_list']) > $key) {
                        $value[] = [
                            'x' => (string)graphina_get_dynamic_tag_data($settings['iq_' . $type . '_category_list'][$key], 'iq_' . $type . '_chart_category'),
                            'y' => [
                                strtotime((string)graphina_get_dynamic_tag_data($val, 'iq_' . $type . '_chart_from_date_' . $i)) * 1000,
                                strtotime((string)graphina_get_dynamic_tag_data($val, 'iq_' . $type . '_chart_to_date_' . $i)) * 1000,
                            ]
                        ];
                    }
                }
                $data['series'][] = [
                    'name' => (string)graphina_get_dynamic_tag_data($settings, 'iq_' . $type . '_chart_title_3_' . $i),
                    'data' => $value
                ];
            }
            if ($settings['iq_' . $type . '_chart_data_option'] !== 'manual') {
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

        $gradient = implode('_,_', $gradient);
        $second_gradient = implode('_,_', $second_gradient);
        $fill_pattern = implode('_,_', $fill_pattern);
        $chartDataJson = json_encode($data['series']);

        require GRAPHINA_ROOT . '/elementor/charts/timeline/render/timeline_chart.php';
        if (isRestrictedAccess('timeline', $this->get_id(), $settings, false) === false) {
            ?>

            <script>

                var myElement = document.querySelector(".timeline-chart-<?php esc_attr_e($mainId); ?>");

                if (typeof isInit === 'undefined') {
                    var isInit = {};
                }
                isInit['<?php esc_attr_e($mainId); ?>'] = false;

                var timelineOptions = {
                    series: <?php echo $chartDataJson ?>,
                    chart: {
                        height: parseInt('<?php echo $settings['iq_' . $type . '_chart_height'] ?>'),
                        type: 'rangeBar',
                        toolbar: {
                            show: '<?php echo $settings['iq_' . $type . '_can_chart_show_toolbar'] ?>',
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
                        animations: {
                            enabled: '<?php echo($settings['iq_' . $type . '_chart_animation'] === "yes") ?>',
                            speed: '<?php echo $settings['iq_' . $type . '_chart_animation_speed'] ?>',
                            delay: '<?php echo $settings['iq_' . $type . '_chart_animation_delay'] ?>'
                        }
                    },
                    noData: {
                        text: '<?php echo $loadingText; ?>',
                        align: 'center',
                        verticalAlign: 'middle',
                        style: {
                            fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit'] ?>',
                            fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family'] ?>',
                            color: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']) ?>'
                        }
                    },
                    plotOptions: {
                        bar: {
                            horizontal: true
                        }
                    },
                    colors: '<?php echo $gradient; ?>'.split('_,_'),
                    fill: {
                        type: '<?php echo $settings['iq_' . $type . '_chart_fill_style_type'] ?>',
                        opacity: parseFloat('<?php echo $settings['iq_' . $type . '_chart_fill_opacity']; ?>'),
                        colors: '<?php echo $gradient; ?>'.split('_,_'),
                        gradient: {
                            gradientToColors: '<?php echo $second_gradient; ?>'.split('_,_'),
                            type: '<?php echo $settings['iq_' . $type . '_chart_gradient_type'] ?>',
                            inverseColors: '<?php echo $settings['iq_' . $type . '_chart_gradient_inversecolor'] ?>',
                            opacityFrom: parseFloat('<?php echo $settings['iq_' . $type . '_chart_gradient_opacityFrom'] ?>'),
                            opacityTo: parseFloat('<?php echo $settings['iq_' . $type . '_chart_gradient_opacityTo'] ?>')
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
                        show: '<?php echo $settings['iq_' . $type . '_chart_legend_show'] ?>',
                        position: '<?php echo $settings['iq_' . $type . '_chart_legend_position'] ?>',
                        horizontalAlign: '<?php  echo $settings['iq_' . $type . '_chart_legend_horizontal_align'] ?>',
                        fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit'] ?>',
                        fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family'] ?>',
                        fontWeight: '<?php echo $settings['iq_' . $type . '_chart_font_weight'] ?>',
                        labels: {
                            colors: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']) ?>'
                        }
                    },
                    grid: {
                        yaxis: {
                            lines: {
                                show: '<?php echo $settings['iq_' . $type . '_chart_yaxis_line_show'] === "yes"; ?>'
                            }
                        }
                    },
                    xaxis: {
                        type: 'datetime',
                        position: '<?php esc_html_e($settings['iq_' . $type . '_chart_xaxis_datalabel_position']) ?>',
                        tickAmount: parseInt("<?php esc_html_e($settings['iq_' . $type . '_chart_xaxis_datalabel_tick_amount']); ?>"),
                        tickPlacement: "<?php esc_html_e($settings['iq_' . $type . '_chart_xaxis_datalabel_tick_placement']) ?>",
                        labels: {
                            show: '<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_show'] ?>',
                            rotateAlways: '<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_auto_rotate'] ?>',
                            rotate: '<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_rotate'] ?>',
                            offsetX: parseInt('<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_offset_x'] ?>'),
                            offsetY: parseInt('<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_offset_y'] ?>'),
                            trim: true,
                            style: {
                                colors: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']) ?>',
                                fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit'] ?>',
                                fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family'] ?>',
                                fontWeight: '<?php echo $settings['iq_' . $type . '_chart_font_weight'] ?>'
                            },
                            formatter: function (val) {
                                return '' + dateFormat(val, true) + '';
                            }
                        }
                    },
                    yaxis: {
                        opposite: '<?php esc_html_e($settings['iq_' . $type . '_chart_yaxis_datalabel_position']) ?>',
                        tickAmount: parseInt("<?php esc_html_e($settings['iq_' . $type . '_chart_yaxis_datalabel_tick_amount']); ?>"),
                        decimalsInFloat: parseInt("<?php esc_html_e($settings['iq_' . $type . '_chart_yaxis_datalabel_decimals_in_float']); ?>"),
                        labels: {
                            show: '<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_show'] ?>',
                            rotate: '<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_rotate'] ?>',
                            offsetX: parseInt('<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_offset_x'] ?>'),
                            offsetY: parseInt('<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_offset_y'] ?>'),
                            style: {
                                colors: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']) ?>',
                                fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit'] ?>',
                                fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family'] ?>',
                                fontWeight: '<?php echo $settings['iq_' . $type . '_chart_font_weight'] ?>'
                            }
                        }
                    },
                    dataLabels: {
                        enabled: '<?php echo $settings['iq_' . $type . '_chart_datalabel_show'] ?>',
                        style: {
                            colors: ['<?php echo $settings['iq_' . $type . '_chart_datalabel_background_show'] === "yes" ? strval($settings['iq_' . $type . '_chart_datalabel_font_color_1']) : strval($settings['iq_' . $type . '_chart_datalabel_font_color']); ?>']
                        },
                        background: {
                            enabled: '<?php echo $settings['iq_' . $type . '_chart_datalabel_background_show'] === "yes"; ?>',
                            foreColor: ['<?php echo strval($settings['iq_' . $type . '_chart_datalabel_background_color']); ?>'],
                            borderWidth: parseInt('<?php echo $settings['iq_' . $type . '_chart_datalabel_border_width']; ?>'),
                            borderColor: '<?php echo strval($settings['iq_' . $type . '_chart_datalabel_border_color']); ?>'
                        },
                        formatter: function (value, {seriesIndex, dataPointIndex, w}) {
                            return w.config.series[seriesIndex].name + ":  " + timeDifference(value[0], value[1]);
                        }
                    },
                    stroke: {
                        show: true,
                        width: parseInt('<?php echo isset($settings["iq_'.$type.'_chart_stroke_width"]) ? $settings["iq_'.$type.'_chart_stroke_width"] : 0; ?>')
                    },
                    tooltip: {
                        enabled: '<?php echo $settings['iq_' . $type . '_chart_tooltip'] ?>',
                        theme: '<?php echo $settings['iq_' . $type . '_chart_tooltip_theme'] ?>',
                        style: {
                            fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit'] ?>',
                            fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family'] ?>'
                        },
                        x: {
                            format: "d MMM H:mm"
                        }
                    }
                };

                if (typeof initNowGraphina !== "undefined") {
                    initNowGraphina(
                        myElement,
                        {
                            ele: document.querySelector(".timeline-chart-<?php esc_attr_e($mainId); ?>"),
                            options: timelineOptions,
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

Plugin::instance()->widgets_manager->register_widget_type(new Timeline_chart());