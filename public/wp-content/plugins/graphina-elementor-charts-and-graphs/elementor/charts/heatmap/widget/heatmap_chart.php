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
class Heatmap_chart extends Widget_Base
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
        return 'heatmap_chart';
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
        return 'Heatmap';
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
        return 'fas fa-th';
    }

    public function get_chart_type()
    {
        return 'heatmap';
    }

    protected function _register_controls()
    {
        $type = $this->get_chart_type();
        graphina_basic_setting($this, $type);

        graphina_chart_data_option_setting($this, $type, 10);

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

        graphina_common_chart_setting($this, $type, false, true, false, false);

        graphina_tooltip($this, $type, true, false);

        $this->add_control(
            'iq_' . $type . '_chart_hr_plot_setting',
            [
                'type' => Controls_Manager::DIVIDER,
            ]
        );

        $this->add_control(
            'iq_' . $type . '_chart_plot_setting_title',
            [
                'label' => esc_html__('Plot Settings', 'graphina-lang'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'iq_' . $type . '_chart_radius',
            [
                'label' => esc_html__('Matrix Radius', 'graphina-lang'),
                'type' => Controls_Manager::NUMBER,
                'default' => 2,
                'min' => 0,
                'max' => 100,
                'step' => 5
            ]
        );

        graphina_stroke($this, $type);

        graphina_animation($this, $type);

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
                    ['iq_' . $type . '_chart_category' => 'w1'],
                    ['iq_' . $type . '_chart_category' => 'w2'],
                    ['iq_' . $type . '_chart_category' => 'w3'],
                    ['iq_' . $type . '_chart_category' => 'w4'],
                    ['iq_' . $type . '_chart_category' => 'w5'],
                    ['iq_' . $type . '_chart_category' => 'w6'],
                    ['iq_' . $type . '_chart_category' => 'w7'],
                    ['iq_' . $type . '_chart_category' => 'w8'],
                    ['iq_' . $type . '_chart_category' => 'w9'],
                    ['iq_' . $type . '_chart_category' => 'w10'],
                    ['iq_' . $type . '_chart_category' => 'w11'],
                    ['iq_' . $type . '_chart_category' => 'w12'],
                    ['iq_' . $type . '_chart_category' => 'w13'],
                    ['iq_' . $type . '_chart_category' => 'w14'],
                    ['iq_' . $type . '_chart_category' => 'w15']
                ],
                'condition' => [
                    'iq_' . $type . '_chart_data_option' => 'manual'
                ]
            ]
        );

        $this->end_controls_section();

        graphina_advance_x_axis_setting($this, $type, true, true);

        graphina_advance_y_axis_setting($this, $type, true, true);

        graphina_series_setting($this, $type, ['color'], false, ['classic'], false, false);

        for ($i = 0; $i < 20; $i++) {

            $this->start_controls_section(
                'iq_' . $type . '_section_3_' . $i,
                [
                    'label' => esc_html__('Element ' . ($i + 1), 'graphina-lang'),
                    'condition' => [
                        'iq_' . $type . '_chart_data_series_count' => range(1 + $i, 20),
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
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
                        ['iq_' . $type . '_chart_value_3_' . $i => rand(10, 200)],
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
        $settings = $this->get_settings_for_display();
        $mainId = $this->get_id();
        $type = $this->get_chart_type();
        $color = [];
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
            $color[] = strval($settings['iq_' . $type . '_chart_gradient_1_' . $i]);
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
            $gradient_new = [];
            $desiredLength = count($data['series']);
            while (count($gradient_new) < $desiredLength) {
                $gradient_new = array_merge($gradient_new, $color);
            }
            $gradient = array_slice($gradient_new, 0, $desiredLength);
        }
        $color = implode('_,_', $gradient);
        $category = implode('_,_', $data['category']);
        $chartDataJson = json_encode($data['series']);

        require GRAPHINA_ROOT . '/elementor/charts/heatmap/render/heatmap_chart.php';
        if (isRestrictedAccess('heatmap', $this->get_id(), $settings, false) === false) {
            ?>
            <script>

                var myElement = document.querySelector(".heatmap-chart-<?php esc_attr_e($mainId); ?>");

                if (typeof isInit === 'undefined') {
                    var isInit = {};
                }
                isInit['<?php esc_attr_e($mainId); ?>'] = false;

                var heatmapOptions = {
                    series: <?php echo $chartDataJson ?>,
                    chart: {
                        height: parseInt('<?php echo $settings['iq_' . $type . '_chart_height'] ?>'),
                        type: 'heatmap',
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
                    plotOptions: {
                        heatmap: {
                            radius: parseFloat('<?php echo $settings['iq_' . $type . '_chart_radius'] ?>')
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
                    dataLabels: {
                        enabled: '<?php echo $settings['iq_' . $type . '_chart_datalabel_show'] ?>',
                        style: {
                            colors: ['<?php echo $settings['iq_' . $type . '_chart_datalabel_font_color'] ?>'],
                        },
                        formatter: function (val, opts) {
                            return '<?php esc_html_e($dataLabelPrefix) ?>' + val + '<?php esc_html_e($dataLabelPostfix) ?>';
                        }
                    },
                    stroke: {
                        show: '<?php echo $settings['iq_' . $type . '_chart_stroke_show'] === "yes"; ?>',
                        width: parseInt('<?php echo  $settings['iq_' . $type . '_chart_stroke_show'] === "yes" ? $settings['iq_' . $type . '_chart_stroke_width'] : 0 ; ?>')
                    },
                    colors: '<?php echo $color; ?>'.split('_,_'),
                    xaxis: {
                        categories: '<?php echo $category; ?>'.split('_,_'),
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
                            formatter: function (val, index) {
                                return '<?php esc_html_e($xLabelPrefix) ?>' + val + '<?php esc_html_e($xLabelPostfix) ?>';
                            },
                        },
                        tooltip: {
                            enabled: "<?php echo !empty($settings['iq_' . $type . '_chart_xaxis_tooltip_show']) && $settings['iq_' . $type . '_chart_xaxis_tooltip_show'] === 'yes';?>"
                        },
                        crosshairs: {
                            show: "<?php echo !empty($settings['iq_' . $type . '_chart_xaxis_crosshairs_show']) && $settings['iq_' . $type . '_chart_xaxis_crosshairs_show'] === 'yes' && $settings['iq_' . $type . '_chart_xaxis_tooltip_show'] === 'yes';?>",
                            position: 'front',
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
                        },
                        tooltip: {
                            enabled: "<?php echo !empty($settings['iq_' . $type . '_chart_yaxis_tooltip_show']) && $settings['iq_' . $type . '_chart_yaxis_tooltip_show'] === 'yes';?>"
                        },
                        crosshairs: {
                            show: "<?php echo !empty($settings['iq_' . $type . '_chart_yaxis_crosshairs_show']) && $settings['iq_' . $type . '_chart_yaxis_crosshairs_show'] === 'yes' && $settings['iq_' . $type . '_chart_yaxis_tooltip_show'] === 'yes';?>",
                            position: 'front',
                        }
                    },
                    fill: {
                        opacity: 1
                    },
                    tooltip: {
                        enabled: '<?php echo $settings['iq_' . $type . '_chart_tooltip'] ?>',
                        theme: '<?php echo $settings['iq_' . $type . '_chart_tooltip_theme'] ?>',
                        style: {
                            fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit'] ?>',
                            fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family'] ?>'
                        }
                    }
                };

                if ("<?php esc_html_e($settings['iq_' . $type . '_chart_yaxis_label_show']); ?>" === "yes") {
                    heatmapOptions.yaxis.labels.formatter = function (val) {
                        if (val !== '') {
                            return '<?php esc_html_e($yLabelPrefix); ?>' + val + '<?php esc_html_e($yLabelPostfix); ?>';
                        }
                        return val;
                    }
                }

                if (typeof initNowGraphina !== "undefined") {
                    initNowGraphina(
                        myElement,
                        {
                            ele: document.querySelector(".heatmap-chart-<?php esc_attr_e($mainId); ?>"),
                            options: heatmapOptions,
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

Plugin::instance()->widgets_manager->register_widget_type(new Heatmap_chart());