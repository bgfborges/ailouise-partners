<?php


namespace Elementor;
if (!defined('ABSPATH')) exit;


class Radar_chart extends Widget_Base
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
        return 'radar_chart';
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
        return 'Radar';
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
        return 'fab fa-connectdevelop';
    }

    public function get_chart_type()
    {
        return 'radar';
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

        graphina_common_chart_setting($this, $type, false);

        graphina_tooltip($this, $type, true, false);

        graphina_animation($this, $type);

        graphina_plot_setting($this, $type);

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

        $this->start_controls_section(
            'iq_' . $type . '_section_5',
            [
                'label' => esc_html__('Advance X-Axis Setting', 'graphina-lang'),
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
            'iq_' . $type . '_chart_xaxis_datalabel_show',
            [
                'label' => esc_html__('Labels', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Hide', 'graphina-lang'),
                'label_off' => esc_html__('Show', 'graphina-lang'),
                'default' => 'yes'
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'iq_' . $type . '_section_6',
            [
                'label' => esc_html__('Advance Y-Axis Setting', 'graphina-lang'),
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
            'iq_' . $type . '_chart_yaxis_datalabel_show',
            [
                'label' => esc_html__('Labels', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Hide', 'graphina-lang'),
                'label_off' => esc_html__('Show', 'graphina-lang'),
                'default' => 'yes'
            ]
        );

        $this->end_controls_section();

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
                'iq_' . $type . '_chart_value_3_' . $i,
                [
                    'label' => 'Chart Value',
                    'type' => Controls_Manager::NUMBER,
                    'placeholder' => esc_html__('Add Value', 'graphina-lang'),
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            /** Chart value list. */
            $this->add_control(
                'iq_' . $type . '_value_list_3_' . $i,
                [
                    'label' => esc_html__('Chart value list', 'graphina-lang'),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
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
        $markerSize = [];
        $markerStrokeColor = [];
        $markerStokeWidth = [];
        $markerShape = [];
        $gradient = [];
        $second_gradient = [];
        $fill_pattern = [];
        $xaxisFontColor = [];
        $data = ['series' => [], 'category' => []];
        $callAjax = false;
        $loadingText = esc_html__((isset($settings['iq_' . $type . '_chart_no_data_text']) ? $settings['iq_' . $type . '_chart_no_data_text'] : ''), 'graphina-lang');
        $dataLabelPrefix = $dataLabelPostfix = '';

        $exportFileName = (
            !empty($settings['iq_' . $type . '_can_chart_show_toolbar']) && $settings['iq_' . $type . '_can_chart_show_toolbar'] === 'yes'
            && !empty($settings['iq_' . $type . '_export_filename'])
        ) ? $settings['iq_' . $type . '_export_filename'] : $mainId;

        if ($settings['iq_' . $type . '_chart_datalabel_show'] === 'yes') {
            $dataLabelPrefix = $settings['iq_' . $type . '_chart_datalabel_prefix'];
            $dataLabelPostfix = $settings['iq_' . $type . '_chart_datalabel_postfix'];
        }
        $seriesCount = isset($settings['iq_' . $type . '_chart_data_series_count']) ? $settings['iq_' . $type . '_chart_data_series_count'] : 0;
        for ($i = 0; $i < $seriesCount; $i++) {
            $dropShadowSeries[] = $i;
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
        if (isGraphinaPro() && $settings['iq_' . $type . '_chart_data_option'] !== 'manual') {
            $new_settings = graphina_setting_sort($settings);
            $callAjax = true;
            $gradient = $second_gradient = ['#ffffff'];
            $loadingText = esc_html__('Loading...', 'graphina-lang');
        } else {
            $new_settings = [];
            $categoryList = $settings['iq_' . $type . '_category_list'];
            if (gettype($categoryList) === "NULL") {
                $categoryList = [];
            }
            foreach ($categoryList as $v) {
                $data['category'][] = (string)graphina_get_dynamic_tag_data($v, 'iq_' . $type . '_chart_category');
            }
            for ($i = 0; $i < $seriesCount; $i++) {
                $valueList = $settings['iq_' . $type . '_value_list_3_' . $i];
                $value = [];
                if (gettype($valueList) === "NULL") {
                    $valueList = [];
                }
                foreach ($valueList as $v) {
                    $value[] = (float)graphina_get_dynamic_tag_data($v, 'iq_' . $type . '_chart_value_3_' . $i);
                }
                $data['series'][] = [
                    'name' => (string)graphina_get_dynamic_tag_data($settings, 'iq_' . $type . '_chart_title_3_' . $i),
                    'data' => $value
                ];
            }
            if ($settings['iq_' . $type . '_chart_data_option'] !== 'manual') {
                $data = ['series' => [], 'category' => []];
            }
            $gradient_new = $second_gradient_new = $fill_pattern_new = $xaxis_font_color_new = [];
            $desiredLength = count($data['series']);
            while (count($gradient_new) < $desiredLength) {
                $gradient_new = array_merge($gradient_new, $gradient);
                $second_gradient_new = array_merge($second_gradient_new, $second_gradient);
                $fill_pattern_new = array_merge($fill_pattern_new, $fill_pattern);
            }
            while (count($xaxis_font_color_new) <= count($data['category'])) {
                $xaxis_font_color_new = array_merge($xaxis_font_color_new, [strval($settings['iq_' . $type . '_chart_font_color'])]);
            }

            $gradient = array_slice($gradient_new, 0, $desiredLength);
            $second_gradient = array_slice($second_gradient_new, 0, $desiredLength);
            $fill_pattern = array_slice($fill_pattern_new, 0, $desiredLength);
            $xaxisFontColor = array_slice($xaxis_font_color_new, 0, count($data['category']));
        }

        $markerSize =  implode('_,_', $markerSize);
        $markerStrokeColor =  implode('_,_', $markerStrokeColor);
        $markerStokeWidth =  implode('_,_', $markerStokeWidth);
        $markerShape =  implode('_,_', $markerShape);
        $gradient = implode('_,_', $gradient);
        $second_gradient = implode('_,_', $second_gradient);
        $fill_pattern = implode('_,_', $fill_pattern);
        $xaxisFontColor = implode('_,_', $xaxisFontColor);

        $category = implode('_,_', $data['category']);
        $chartDataJson = json_encode($data['series']);

        require GRAPHINA_ROOT . '/elementor/charts/radar/render/radar_chart.php';
        if (isRestrictedAccess('radar', $this->get_id(), $settings, false) === false) {
            ?>
            <script>

                var myElement = document.querySelector(".radar-chart-<?php esc_attr_e($mainId); ?>");

                if (typeof isInit === 'undefined') {
                    var isInit = {};
                }
                isInit['<?php esc_attr_e($mainId); ?>'] = false;

                var radarOptions = {
                    series: <?php echo $chartDataJson; ?>,
                    chart: {
                        height: parseInt('<?php echo $settings['iq_' . $type . '_chart_height']; ?>'),
                        type: 'radar',
                        toolbar: {
                            show: '<?php echo $settings['iq_' . $type . '_can_chart_show_toolbar'] === "yes"; ?>',
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
                            enabled: '<?php echo($settings['iq_' . $type . '_chart_animation'] === "yes"); ?>',
                            speed: '<?php echo $settings['iq_' . $type . '_chart_animation_speed']; ?>',
                            delay: '<?php echo $settings['iq_' . $type . '_chart_animation_delay']; ?>'
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
                        enabled: '<?php echo $settings['iq_' . $type . '_chart_datalabel_show'] === "yes"; ?>',
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
                    plotOptions: {
                        radar: {
                            size: parseInt('<?php echo $settings['iq_' . $type . '_chart_plot_options'] === 'yes' ? $settings['iq_' . $type . '_chart_plot_size'] : 140; ?>'),
                            polygons: {
                                strokeColors: '<?php echo strval($settings['iq_' . $type . '_chart_plot_stroke_color']); ?>',
                                connectorColors: '<?php echo strval($settings['iq_' . $type . '_chart_plot_stroke_color']); ?>',
                                fill: {
                                    colors: ['<?php echo strval($settings['iq_' . $type . '_chart_plot_color']); ?>']
                                }
                            }
                        }
                    },
                    stroke: {
                        width: parseFloat('<?php echo $settings['iq_' . $type . '_chart_stroke_size']; ?>')
                    },
                    colors: '<?php echo $gradient; ?>'.split('_,_'),
                    xaxis: {
                        categories: '<?php echo $category; ?>'.split('_,_'),
                        labels: {
                            show: '<?php echo $settings['iq_' . $type . '_chart_xaxis_datalabel_show'] === "yes"; ?>',
                            trim: true,
                            style: {
                                colors: '<?php echo $xaxisFontColor; ?>'.split('_,_'),
                                fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                                fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>',
                                fontWeight: '<?php echo $settings['iq_' . $type . '_chart_font_weight']; ?>'
                            }
                        }
                    },
                    yaxis: {
                        show: '<?php echo $settings['iq_' . $type . '_chart_yaxis_datalabel_show'] === "yes"; ?>',
                        labels: {
                            style: {
                                colors: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']); ?>',
                                fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                                fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>',
                                fontWeight: '<?php echo $settings['iq_' . $type . '_chart_font_weight']; ?>'
                            }
                        }
                    },
                    fill: {
                        type: '<?php echo $settings['iq_' . $type . '_chart_fill_style_type']; ?>',
                        opacity: parseFloat('<?php echo $settings['iq_' . $type . '_chart_fill_opacity']; ?>'),
                        colors: '<?php echo $gradient; ?>'.split('_,_'),
                        gradient: {
                            gradientToColors: '<?php echo $second_gradient; ?>'.split('_,_'),
                            type: '<?php echo $settings['iq_' . $type . '_chart_gradient_type']; ?>',
                            inverseColors: '<?php echo $settings['iq_' . $type . '_chart_gradient_inversecolor']; ?>',
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
                    tooltip: {
                        enabled: '<?php echo $settings['iq_' . $type . '_chart_tooltip']; ?>',
                        theme: '<?php echo $settings['iq_' . $type . '_chart_tooltip_theme']; ?>',
                        style: {
                            fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                            fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>'
                        }
                    },
                    legend: {
                        showForSingleSeries:true,
                        show: '<?php echo $settings['iq_' . $type . '_chart_legend_show']; ?>',
                        position: '<?php echo isset($settings['iq_' . $type . '_chart_legend_position']) ? $settings['iq_' . $type . '_chart_legend_position'] : 'bottom'; ?>',
                        horizontalAlign: '<?php  echo $settings['iq_' . $type . '_chart_legend_horizontal_align']; ?>',
                        fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                        fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>',
                        fontWeight: '<?php echo $settings['iq_' . $type . '_chart_font_weight']; ?>',
                        labels: {
                            colors: '<?php echo strval($settings['iq_' . $type . '_chart_font_color']); ?>'
                        }
                    },
                };

                    radarOptions['markers'] ={
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
                            ele: document.querySelector(".radar-chart-<?php esc_attr_e($mainId); ?>"),
                            options: radarOptions,
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

Plugin::instance()->widgets_manager->register_widget_type(new Radar_chart());