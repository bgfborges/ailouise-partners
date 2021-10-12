<?php

namespace Elementor;
if (!defined('ABSPATH')) exit;

class Pie_chart extends Widget_Base
{

    private $defaultLabel = ['Jan', 'Feb', 'Mar', 'Apr', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan1', 'Feb1', 'Mar1', 'Apr1', 'Jun1', 'July1', 'Aug1', 'Sep1', 'Oct1', 'Nov1', 'Dec1'];
    private $color = ['#449DD1', '#F86624', '#546E7A', '#D4526E', '#775DD0', '#FF4560', '#7D02EB', '#8D5B4C', '#F86624', '#2E294E'];
    private $gradientColor = ['#D56767', '#E02828', '#26A2D6', '#40B293', '#69DFDD', '#F28686', '#7D02EB', '#E02828', '#D56767', '#26A2D6'];

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
        return 'pie_chart';
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
        return 'Pie';
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
        return 'fas fa-chart-pie';
    }

    public function get_chart_type()
    {
        return 'pie';
    }

    protected function _register_controls()
    {
        $type = $this->get_chart_type();
        $this->color = graphina_colors('color');

        $this->gradientColor = graphina_colors('gradientColor');

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

        graphina_common_chart_setting($this, $type, false, false, false, false);

        graphina_tooltip($this, $type, true, false);

        graphina_stroke($this, $type);

        graphina_animation($this, $type);

        $this->end_controls_section();

        graphina_chart_label_setting($this, $type);

        graphina_series_setting($this, $type, ['color'], true, ['classic', 'gradient', 'pattern'], false, false);

        for ($i = 0; $i < graphina_default_setting('max_series_value'); $i++) {

            $this->start_controls_section(
                'iq_' . $type . '_section_series' . $i,
                [
                    'label' => esc_html__('Element ' . ($i + 1), 'graphina-lang'),
                    'default' => rand(50, 200),
                    'condition' => [
                        'iq_' . $type . '_chart_data_series_count' => range($i + 1, graphina_default_setting('max_series_value')),
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
                'iq_' . $type . '_chart_label' . $i,
                [
                    'label' => 'Label',
                    'type' => Controls_Manager::TEXT,
                    'placeholder' => esc_html__('Add Label', 'graphina-lang'),
                    'default' => $this->defaultLabel[$i],
                    'dynamic' => [
                        'active' => true,
                    ],
                ]
            );

            $this->add_control(
                'iq_' . $type . '_chart_value' . $i,
                [
                    'label' => 'Value',
                    'type' => Controls_Manager::NUMBER,
                    'placeholder' => esc_html__('Add Value', 'graphina-lang'),
                    'default' => rand(25, 200),
                    'dynamic' => [
                        'active' => true,
                    ],
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
        $type = $this->get_chart_type();
        $mainId = $this->get_id();
        $valueList = $settings['iq_' . $type . '_chart_data_series_count'];
        $gradient = [];
        $second_gradient = [];
        $fill_pattern = [];
        $data = ['category' => [], 'series' => []];
        $callAjax = false;
        $loadingText = esc_html__((isset($settings['iq_' . $type . '_chart_no_data_text']) ? $settings['iq_' . $type . '_chart_no_data_text'] : ''), 'graphina-lang');

        $exportFileName = (
            !empty($settings['iq_' . $type . '_can_chart_show_toolbar']) && $settings['iq_' . $type . '_can_chart_show_toolbar'] === 'yes'
            && !empty($settings['iq_' . $type . '_export_filename'])
        ) ? $settings['iq_' . $type . '_export_filename'] : $mainId;

        if (gettype($valueList) === "NULL") {
            $valueList = 0;
        }
        for ($i = 0; $i < $valueList; $i++) {
            $gradient[] = strval($settings['iq_' . $type . '_chart_gradient_1_' . $i]);
            $second_gradient[] = !isset($settings['iq_' . $type . '_chart_gradient_2_' . $i]) ? strval($settings['iq_' . $type . '_chart_gradient_1_' . $i]) : strval($settings['iq_' . $type . '_chart_gradient_2_' . $i]);
            $fill_pattern[] = isset($settings['iq_' . $type . '_chart_bg_pattern_' . $i]) ? $settings['iq_' . $type . '_chart_bg_pattern_' . $i] : 'verticalLines';
        }
        if (isGraphinaPro() && $settings['iq_' . $type . '_chart_data_option'] !== 'manual') {
            $new_settings = graphina_setting_sort($settings);
            $callAjax = true;
            $gradient = $second_gradient = ['#ffffff'];
            $loadingText = esc_html__('Loading...', 'graphina-lang');
        } else {
            $new_settings = [];
            for ($i = 0; $i < $valueList; $i++) {
                $data["category"][] = (string)graphina_get_dynamic_tag_data($settings, 'iq_' . $type . '_chart_label' . $i);
                $data["series"][] = (float)graphina_get_dynamic_tag_data($settings, 'iq_' . $type . '_chart_value' . $i);
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
        $label = implode('_,_', $data['category']);
        $value = implode(',', $data['series']);

        require GRAPHINA_ROOT . '/elementor/charts/pie/render/pie_chart.php';
        if (isRestrictedAccess('pie', $this->get_id(), $settings, false) === false) {
            ?>
            <script>

                var myElement = document.querySelector(".pie-chart-<?php esc_attr_e($mainId); ?>");

                if (typeof isInit === 'undefined') {
                    var isInit = {};
                }
                isInit['<?php esc_attr_e($mainId); ?>'] = false;

                var pieOptions = {
                    series: [<?php esc_html_e($value); ?>],
                    chart: {
                        height: parseInt('<?php echo $settings['iq_' . $type . '_chart_height']; ?>'),
                        type: 'pie',
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
                            enabled: '<?php echo($settings['iq_' . $type . '_chart_animation'] === "yes") ?>',
                            speed: '<?php echo $settings['iq_' . $type . '_chart_animation_speed']; ?>',
                            delay: '<?php echo $settings['iq_' . $type . '_chart_animation_delay']; ?>',
                            dynamicAnimation: {
                                enabled: true
                            }
                        },
                    },
                    labels: '<?php echo $label; ?>'.split('_,_'),
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
                            colors: ['<?php echo isset($settings['iq_' . $type . '_chart_datalabel_font_color']) ? strval($settings['iq_' . $type . '_chart_datalabel_font_color']) : '#ffffff'; ?>'],
                        }
                    },
                    colors: '<?php echo $gradient; ?>'.split('_,_'),
                    fill: {
                        type: '<?php echo $settings['iq_' . $type . '_chart_fill_style_type']; ?>',
                        opacity: 1,
                        colors: '<?php echo $gradient; ?>'.split('_,_'),
                        gradient: {
                            gradientToColors: '<?php echo $second_gradient; ?>'.split('_,_'),
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
                    legend: {
                        showForSingleSeries:true,
                        show: '<?php echo $settings['iq_' . $type . '_chart_legend_show'] === "yes" && $label != '' && $value != ''; ?>',
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
                        enabled: '<?php echo $settings['iq_' . $type . '_chart_tooltip'] === "yes" ?>',
                        theme: '<?php echo $settings['iq_' . $type . '_chart_tooltip_theme']; ?>',
                        style: {
                            fontSize: '<?php echo $settings['iq_' . $type . '_chart_font_size']['size'] . $settings['iq_' . $type . '_chart_font_size']['unit']; ?>',
                            fontFamily: '<?php echo $settings['iq_' . $type . '_chart_font_family']; ?>'
                        }
                    },
                    stroke: {
                        show: '<?php echo $settings['iq_' . $type . '_chart_stroke_show'] === "yes"; ?>',
                        width: parseInt('<?php echo  $settings['iq_' . $type . '_chart_stroke_show'] === "yes" ? $settings['iq_' . $type . '_chart_stroke_width'] : 0 ; ?>')
                    }
                };

                if (typeof initNowGraphina !== "undefined") {
                    initNowGraphina(
                        myElement,
                        {
                            ele: document.querySelector(".pie-chart-<?php esc_attr_e($mainId); ?>"),
                            options: pieOptions,
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

Plugin::instance()->widgets_manager->register_widget_type(new \Elementor\Pie_chart());