<?php

use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography as Scheme_Typography;

/****************
 * @param $key
 * @param string $dataType
 * @return int|mixed|string
 */
function graphina_default_setting($key, $dataType = "int")
{
    $list = [
        "max_series_value" => 15,
        "categories" => [
            'Jan', 'Feb', 'Mar', 'Apr', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec',
            'Jan1', 'Feb1', 'Mar1', 'Apr1', 'Jun1', 'July1', 'Aug1', 'Sep1', 'Oct1', 'Nov1', 'Dec1'
        ]
    ];
    return in_array($key, array_keys($list)) ? $list[$key] : (in_array($dataType, ['int', 'float']) ? 0 : '');
}

/***************
 * @param bool $first
 * @return array|mixed|string
 */
function graphina_stroke_curve_type($first = false)
{
    $options = [
        "smooth" => esc_html__('Smooth', 'graphina-lang'),
        "straight" => esc_html__('Straight', 'graphina-lang'),
        "stepline" => esc_html__('Stepline', 'graphina-lang')
    ];
    $keys = array_keys($options);
    return $first ? (count($keys) > 0 ? $keys[0] : '') : $options;
}

/****************
 * @param string $type
 * @param bool $first
 * @return array|mixed|string
 */
function graphina_position_type($type = "vertical", $first = false)
{
    $result = [];
    switch ($type) {
        case "vertical" :
            $result = [
                'top' => esc_html__('Top', 'graphina-lang'),
                'center' => esc_html__('Center', 'graphina-lang'),
                'bottom' => esc_html__('Bottom', 'graphina-lang')
            ];
            break;
        case "horizontal_boolean" :
            $result = [
                '' => [
                    'title' => esc_html__('Left', 'graphina-lang'),
                    'icon' => 'fa fa-arrow-left',
                ],
                'yes' => [
                    'title' => esc_html__('Right', 'graphina-lang'),
                    'icon' => 'fa fa-arrow-right',
                ]
            ];
            break;
        case "placement":
            $result = [
                'on' => esc_html__('On', 'graphina-lang'),
                'between' => esc_html__('Between', 'graphina-lang')
            ];
            break;
    }
    if ($first) {
        $keys = array_keys($result);
        return count($keys) > 0 ? $keys[0] : '';
    }
    return $result;
}

/****************
 * @param bool $first
 * @return array|mixed
 */
function graphina_get_fill_patterns($first = false)
{
    $patterns = [
        'verticalLines' => esc_html__('VerticalLines', 'graphina-lang'),
        'squares' => esc_html__('Squares', 'graphina-lang'),
        'horizontalLines' => esc_html__('HorizontalLines', 'graphina-lang'),
        'circles' => esc_html__('Circles', 'graphina-lang'),
        'slantedLines' => esc_html__('SlantedLines', 'graphina-lang'),
    ];
    if ($first) {
        $keys = array_keys($patterns);
        $patterns = $keys[rand(0, count($keys) - 1)];
    }
    return $patterns;
}

/****************
 * @param string $type
 * @param bool $first
 * @return array|mixed
 */
function graphina_chart_data_enter_options($type = '', $chartType = '', $first = false)
{
    $options = [];
    $type = !empty($type) ? $type : 'base';
    switch ($type) {
        case 'base':
            $options = [
                'manual' => esc_html__('Manual', 'graphina-lang'),
                'dynamic' => esc_html__('Dynamic', 'graphina-lang')
            ];

            if (get_option('graphina_firebase_addons') === '1') {
                $options['firebase'] = esc_html__('Firebase', 'graphina-lang');
            }
            break;
        case 'dynamic':
            $options = [
                'csv' => esc_html__('CSV', 'graphina-lang'),
                'remote-csv' => esc_html__('Remote CSV', 'graphina-lang'),
                'google-sheet' => esc_html__('Google Sheet', 'graphina-lang'),
                'api' => esc_html__('API', 'graphina-lang'),
            ];
            $sql_builder_for = ['line', 'column', 'area', 'pie', 'donut', 'radial', 'radar', 'polar'];
            if (in_array($chartType, $sql_builder_for)) {
                $options['sql-builder'] = esc_html__('SQL Builder', 'graphina-lang');
            }
            break;
    }
    if ($first) {
        return (count($options) > 0) ? array_keys($options)[0] : [];
    }
    return $options;
}

/****************
 * @param $types
 * @param bool $first
 * @return array|mixed
 */
function graphina_fill_style_type($types, $first = false)
{
    $options = [];

    if (in_array('classic', $types)) {
        $options['classic'] = [
            'title' => esc_html__('Classic', 'graphina-lang'),
            'icon' => 'fa fa-paint-brush',
        ];
    }
    if (in_array('gradient', $types)) {
        $options['gradient'] = [
            'title' => esc_html__('Gradient', 'graphina-lang'),
            'icon' => 'fa fa-barcode',
        ];
    }
    if (in_array('pattern', $types)) {
        $options['pattern'] = [
            'title' => esc_html__('Pattern', 'graphina-lang'),
            'icon' => 'fa fa-bars',
        ];
    }
    if ($first) {
        $keys = array_keys($options);
        return count($keys) > 0 ? $keys[0] : '';
    }
    return $options;
}

/****************
 * @param object $this_ele
 * @param string $type
 */
function graphina_basic_setting($this_ele, $type = 'chart_id')
{

    $this_ele->start_controls_section(
        'iq_' . $type . '_section_1',
        [
            'label' => esc_html__('Basic Setting', 'graphina-lang')
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_card_show',
        [
            'label' => esc_html__('Card', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Hide', 'graphina-lang'),
            'label_off' => esc_html__('Show', 'graphina-lang'),
            'default' => 'yes',
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_is_card_heading_show',
        [
            'label' => esc_html__('Heading', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'graphina-lang'),
            'label_off' => esc_html__('No', 'graphina-lang'),
            'default' => 'yes',
            'condition' => [
                'iq_' . $type . '_chart_card_show' => 'yes',
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_heading',
        [
            'label' => esc_html__('Card Heading', 'graphina-lang'),
            'type' => Controls_Manager::TEXT,
            'default' => 'My Example Heading',
            'condition' => [
                'iq_' . $type . '_is_card_heading_show' => 'yes',
                'iq_' . $type . '_chart_card_show' => 'yes',
            ],
            'dynamic' => [
                'active' => true,
            ],
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_is_card_desc_show',
        [
            'label' => esc_html__('Description', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'graphina-lang'),
            'label_off' => esc_html__('No', 'graphina-lang'),
            'default' => 'yes',
            'condition' => [
                'iq_' . $type . '_chart_card_show' => 'yes',
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_content',
        [
            'label' => 'Card Description',
            'type' => Controls_Manager::TEXTAREA,
            'default' => 'My Other Example Heading',
            'condition' => [
                'iq_' . $type . '_is_card_desc_show' => 'yes',
                'iq_' . $type . '_chart_card_show' => 'yes',
            ],
            'dynamic' => [
                'active' => true,
            ],
        ]
    );

    $this_ele->end_controls_section();
}

/****************
 * @param object $this_ele
 * @param string $type `
 * @param int $defaultCount
 * @param boolean $showNegative
 */
function graphina_chart_data_option_setting($this_ele, $type = 'chart_id', $defaultCount = 0, $showNegative = false)
{

    $this_ele->start_controls_section(
        'iq_' . $type . '_section_5_2',
        [
            'label' => esc_html__('Chart Data Options', 'graphina-lang')
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_is_pro',
        [
            'label' => esc_html__('Is Pro', 'graphina-lang'),
            'type' => Controls_Manager::HIDDEN,
            'default' => isGraphinaPro() === true ? 'true' : 'false',
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_data_option',
        [
            'label' => esc_html__('Type', 'graphina-lang'),
            'type' => Controls_Manager::SELECT,
            'default' => graphina_chart_data_enter_options('base', $type, true),
            'options' => graphina_chart_data_enter_options('base', $type)
        ]
    );

    $seriesTest = 'Elements';

    $this_ele->add_control(
        'iq_' . $type . '_chart_data_series_count',
        [
            'label' => esc_html__('Data ' . $seriesTest, 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => $defaultCount !== 0 ? $defaultCount : (in_array($type, ['pie', 'polar', 'donut', 'radial', 'bubble']) ? 5 : 1),
            'min' => 1,
            'max' => graphina_default_setting('max_series_value'),
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

    if ($showNegative) {
        $this_ele->add_control(
            'iq_' . $type . '_can_chart_negative_values',
            [
                'label' => esc_html__('Default Negative Value', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'graphina-lang'),
                'label_off' => esc_html__('No', 'graphina-lang'),
                'description' => esc_html__("Show default chart with some negative values", 'graphina-lang'),
                'default' => false,
                'condition' => [
                    'iq_' . $type . '_chart_data_option' => 'manual'
                ]
            ]
        );
    }

    if (!in_array($type, ['nested_column', 'mixed'])) {
        $this_ele->add_control(
            'iq_' . $type . '_can_chart_reload_ajax',
            [
                'label' => esc_html__('Reload Ajax', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('True', 'graphina-lang'),
                'label_off' => esc_html__('False', 'graphina-lang'),
                'default' => false,
                'condition' => [
                    'iq_' . $type . '_chart_data_option!' => ['manual'],
                ]
            ]
        );
    }

    $this_ele->add_control(
        'iq_' . $type . '_interval_data_refresh',
        [
            'label' => __('Set Interval(sec)', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'min' => 5,
            'step' => 5,
            'default' => 15,
            'condition' => [
                'iq_' . $type . '_can_chart_reload_ajax' => 'yes',
                'iq_' . $type . '_chart_data_option!' => ['manual'],
            ]
        ]
    );

    $this_ele->end_controls_section();

    do_action('graphina_addons_control_section', $this_ele, $type);

    $this_ele->start_controls_section(
        'iq_' . $type . '_section_5_2_1',
        [
            'label' => esc_html__('Dynamic Data Options', 'graphina-lang'),
            'condition' => [
                'iq_' . $type . '_chart_data_option' => ['dynamic']
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_dynamic_data_option',
        [
            'label' => esc_html__('Type', 'graphina-lang'),
            'type' => Controls_Manager::SELECT,
            'default' => graphina_chart_data_enter_options('dynamic', $type, true),
            'options' => graphina_chart_data_enter_options('dynamic', $type)
        ]
    );

    if (isGraphinaPro()) {
        graphina_pro_get_dynamic_options($this_ele, $type);
    }

    if (!isGraphinaPro()) {
        $this_ele->add_control(
            'iq_' . $type . 'get_pro',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => graphina_get_teaser_template([
                    'title' => esc_html__('Get New Exciting Features', 'graphina-lang'),
                    'messages' => ['Get Graphina Pro for above exciting features and more.'],
                    'link' => 'https://codecanyon.net/item/graphinapro-elementor-dynamic-charts-datatable/28654061'
                ]),
            ]
        );
    }
    $this_ele->end_controls_section();

    if (isGraphinaPro()) {
        graphina_restriction_content_options($this_ele, $type);
    }

}

function graphina_get_teaser_template($texts)
{
    ob_start();
    ?>
    <div class="elementor-nerd-box">
        <!--        <img class="elementor-nerd-box-icon" src="-->
        <?php //echo ELEMENTOR_ASSETS_URL . 'images/go-pro.svg';
        ?><!--" />-->
        <div class="elementor-nerd-box-title"><?php echo $texts['title']; ?></div>
        <?php foreach ($texts['messages'] as $message) { ?>
            <div class="elementor-nerd-box-message"><?php echo $message; ?></div>
        <?php }

        if ($texts['link']) { ?>
            <a class="elementor-nerd-box-link elementor-button elementor-button-default elementor-button-go-pro"
               href="<?php echo Utils::get_pro_link($texts['link']); ?>" target="_blank">
                <?php echo esc_html__('Get Pro', 'graphina-lang'); ?>
            </a>
        <?php } else { ?>
            <div style="font-style: italic;">Coming Soon...</div>
        <?php } ?>
    </div>
    <?php

    return ob_get_clean();
}

/****************
 * @param object $this_ele
 * @param string $type
 * @param boolean $showDataLabel
 * @param boolean $labelAddFixed
 * @param boolean $labelPosition
 * @param boolean $showLabelBackground
 * @param boolean $showLabelColor
 */
function graphina_common_chart_setting($this_ele, $type = 'chart_id', $showDataLabel = false, $labelAddFixed = true, $labelPosition = false, $showLabelBackground = true, $showLabelColor = true)
{
    $responsive = isGraphinaPro() ? "add_responsive_control" : "add_control";
    $this_ele->$responsive(
        'iq_' . $type . '_chart_height',
        [
            'label' => esc_html__('Height (px)', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 350,
            'step' => 5,
            'min' => 10,
            'desktop_default' => 350,
            'tablet_default' => 350,
            'mobile_default' => 350,
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_can_chart_show_toolbar',
        [
            'label' => esc_html__('Toolbar', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Hide', 'graphina-lang'),
            'label_off' => esc_html__('Show', 'graphina-lang'),
            'default' => false
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_export_filename',
        [
            'label' => esc_html__('Export Filename', 'graphina-lang'),
            'type' => Controls_Manager::TEXT,
            'condition' => [
                'iq_' . $type . '_can_chart_show_toolbar' => 'yes',
            ],
            'dynamic' => [
                'active' => true,
            ],
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_no_data_text',
        [
            'label' => esc_html__('No Data Text', 'graphina-lang'),
            'type' => Controls_Manager::TEXT,
            'placeholder' => esc_html__('Loading...', 'graphina-lang'),
            'default' => 'No Data Available',
            'description' => esc_html__("When chart is empty, this text appears", 'graphina-lang'),
            'dynamic' => [
                'active' => true,
            ],
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_hr_datalabel_setting',
        [
            'type' => Controls_Manager::DIVIDER,
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_datalabel_setting_title',
        [
            'label' => esc_html__('Label Settings', 'graphina-lang'),
            'type' => Controls_Manager::HEADING,
        ]
    );

    $this_ele->$responsive(
        'iq_' . $type . '_chart_datalabel_show',
        [
            'label' => esc_html__('Show', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Hide', 'graphina-lang'),
            'label_off' => esc_html__('Show', 'graphina-lang'),
            'default' => $showDataLabel === true ? "yes" : false,
            'desktop_default' => "yes",
            'tablet_default' => "yes",
            'mobile_default' => false,
        ]
    );

    /// Need to create condition for responsive controller

    $dataLabelFontColorCondition = [
        'relation' => 'and',
        'terms' => [
            [
                'relation' => 'and',
                'terms' => [
                    [
                        'name' => 'iq_' . $type . '_chart_datalabel_show',
                        'operator' => '==',
                        'value' => 'yes'
                    ]
                ]
            ]
        ]
    ];
    if (isGraphinaPro()) {
        $dataLabelFontColorCondition['terms'][0]['relation'] = 'or';
        $dataLabelFontColorCondition['terms'][0]['terms'][] = [
            'name' => 'iq_' . $type . '_chart_datalabel_show_tablet',
            'operator' => '==',
            'value' => 'yes'
        ];
        $dataLabelFontColorCondition['terms'][0]['terms'][] = [
            'name' => 'iq_' . $type . '_chart_datalabel_show_mobile',
            'operator' => '==',
            'value' => 'yes'
        ];
    }


    if ($labelPosition) {
        $this_ele->add_control(
            'iq_' . $type . '_chart_datalabel_position_show',
            [
                'label' => esc_html__('Position', 'graphina-lang'),
                'type' => Controls_Manager::SELECT,
                'default' => graphina_position_type("vertical", true),
                'options' => graphina_position_type("vertical"),
                'conditions' => $dataLabelFontColorCondition,
            ]
        );
    }

    if ($showLabelColor) {
        $dataLabelFontSetting = $dataLabelFontColorCondition;
        $dataLabelBackground = $dataLabelFontColorCondition;
        if ($showLabelBackground) {
            $dataLabelFontSetting['terms'][] = [
                'relation' => 'and',
                'terms' => [
                    [
                        'name' => 'iq_' . $type . '_chart_datalabel_background_show',
                        'operator' => '!=',
                        'value' => 'yes'
                    ]
                ]
            ];
        }

        $this_ele->add_control(
            'iq_' . $type . '_chart_datalabel_font_color',
            [
                'label' => esc_html__('Font Color', 'graphina-lang'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'conditions' => $dataLabelFontSetting
            ]
        );

    }

    if ($showLabelBackground) {

        $this_ele->add_control(
            'iq_' . $type . '_chart_datalabel_background_show',
            [
                'label' => esc_html__('Show Background', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Hide', 'graphina-lang'),
                'label_off' => esc_html__('Show', 'graphina-lang'),
                'default' => false,
                'conditions' => $dataLabelFontColorCondition
            ]
        );

        $dataLabelBackground['terms'][] = [
            'relation' => 'and',
            'terms' => [
                [
                    'name' => 'iq_' . $type . '_chart_datalabel_background_show',
                    'operator' => '==',
                    'value' => 'yes'
                ]
            ]
        ];

        $this_ele->add_control(
            'iq_' . $type . '_chart_datalabel_background_color',
            [
                'label' => esc_html__('Font Color', 'graphina-lang'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'conditions' => $dataLabelBackground
            ]
        );

        $this_ele->add_control(
            'iq_' . $type . '_chart_datalabel_font_color_1',
            [
                'label' => esc_html__('Background Color', 'graphina-lang'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'conditions' => $dataLabelBackground
            ]
        );

        $this_ele->add_control(
            'iq_' . $type . '_chart_datalabel_border_width',
            [
                'label' => esc_html__('Border Width', 'graphina-lang'),
                'type' => Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 0,
                'max' => 20,
                'conditions' => $dataLabelBackground
            ]
        );

        $this_ele->add_control(
            'iq_' . $type . '_chart_datalabel_border_color',
            [
                'label' => esc_html__('Border Color', 'graphina-lang'),
                'type' => Controls_Manager::COLOR,
                'default' => '#FFFFFF',
                'conditions' => $dataLabelBackground
            ]
        );
    }

    if ($labelAddFixed) {
        $this_ele->add_control(
            'iq_' . $type . '_chart_datalabel_prefix',
            [
                'label' => esc_html__('Label Prefix', 'graphina-lang'),
                'type' => Controls_Manager::TEXT,
                'conditions' => $dataLabelFontColorCondition,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this_ele->add_control(
            'iq_' . $type . '_chart_datalabel_postfix',
            [
                'label' => esc_html__('Label Postfix', 'graphina-lang'),
                'type' => Controls_Manager::TEXT,
                'conditions' => $dataLabelFontColorCondition,
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
    }

}

/*****************
 * @param object $this_ele
 * @param string $type
 * @param array $fill_styles like ['classic', 'gradient', 'pattern']
 * @param bool $showOpacity
 * @param int $i
 * @param array $condition
 * @param boolean $showNoteFillStyle
 */
function graphina_fill_style_setting($this_ele, $type = 'chart_id', $fill_styles = ['classic', 'gradient', 'pattern'], $showOpacity = false, $i = -1, $condition = [], $showNoteFillStyle = false)
{

    $this_ele->add_control(
        'iq_' . $type . '_chart_fill_setting_title' . ($i > -1 ? '_' . $i : ''),
        [
            'label' => esc_html__('Fill Settings', 'graphina-lang'),
            'type' => Controls_Manager::HEADING,
            'condition' => array_merge([], ($i > -1 ? $condition : []))
        ]
    );

    $description = "Pattern will not eligible for the line chart. So if you select it, it will consider as Classic";

    $this_ele->add_control(
        'iq_' . $type . '_chart_fill_style_type' . ($i > -1 ? '_' . $i : ''),
        [
            'label' => esc_html__('Style', 'graphina-lang'),
            'type' => Controls_Manager::CHOOSE,
            'default' => graphina_fill_style_type($fill_styles, true),
            'options' => graphina_fill_style_type($fill_styles),
            'description' => $showNoteFillStyle ? esc_html__($description, 'graphina-lang') : '',
            'condition' => array_merge([], ($i > -1 ? $condition : []))
        ]
    );

    if ($showOpacity) {
        $this_ele->add_control(
            'iq_' . $type . '_chart_fill_opacity' . ($i > -1 ? '_' . $i : ''),
            [
                'label' => esc_html__('Opacity', 'graphina-lang'),
                'type' => Controls_Manager::NUMBER,
                'default' => in_array($type, ['column', 'timeline']) ? 1 : 0.4,
                'min' => 0.00,
                'max' => 1,
                'step' => 0.05,
                'condition' => array_merge(['iq_' . $type . '_chart_fill_style_type' . ($i > -1 ? '_' . $i : '') => 'classic'], ($i > -1 ? $condition : []))
            ]
        );
    }
}

/*****************
 * @param $this_ele
 * @param string $type
 * @param bool $show_type
 * @param bool $usedAsSubPart
 * @param int $i
 * @param array $condition
 */
function graphina_gradient_setting($this_ele, $type = 'chart_id', $show_type = true, $usedAsSubPart = false, $i = -1, $condition = [])
{
    if (!$usedAsSubPart) {
        $this_ele->start_controls_section(
            'iq_' . $type . '_chart_section_3' . ($i > -1 ? '_' . $i : ''),
            [
                'label' => esc_html__('Gradient Setting', 'graphina-lang'),
                'condition' => array_merge(['iq_' . $type . '_chart_fill_style_type' . ($i > -1 ? '_' . $i : '') => 'gradient'], ($i > -1 ? $condition : []))
            ]
        );
    } else {
        $this_ele->add_control(
            'iq_' . $type . '_chart_hr_gradient_setting' . ($i > -1 ? '_' . $i : ''),
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => array_merge(['iq_' . $type . '_chart_fill_style_type' . ($i > -1 ? '_' . $i : '') => 'gradient'], ($i > -1 ? $condition : []))
            ]
        );

        $this_ele->add_control(
            'iq_' . $type . '_chart_gradient_setting_title' . ($i > -1 ? '_' . $i : ''),
            [
                'label' => esc_html__('Gradient Settings', 'graphina-lang'),
                'type' => Controls_Manager::HEADING,
                'condition' => array_merge(['iq_' . $type . '_chart_fill_style_type' . ($i > -1 ? '_' . $i : '') => 'gradient'], ($i > -1 ? $condition : []))
            ]
        );
    }

    if ($show_type) {
        $this_ele->add_control(
            'iq_' . $type . '_chart_gradient_type' . ($i > -1 ? '_' . $i : ''),
            [
                'label' => esc_html__('Type', 'graphina-lang'),
                'type' => Controls_Manager::SELECT,
                'default' => 'vertical',
                'options' => [
                    'vertical' => esc_html__('Vertical', 'graphina-lang'),
                    'horizontal' => esc_html__('Horizontal', 'graphina-lang')
                ],
                'condition' => array_merge(['iq_' . $type . '_chart_fill_style_type' . ($i > -1 ? '_' . $i : '') => 'gradient'], ($i > -1 ? $condition : []))
            ]
        );
    }

    $from_opacity = (in_array($type, ['radar', 'area'])) ? 0.6 : 1.0;
    $to_opacity = (in_array($type, ['radar', 'area'])) ? 0.6 : 1.0;

    $this_ele->add_control(
        'iq_' . $type . '_chart_gradient_opacityFrom' . ($i > -1 ? '_' . $i : ''),
        [
            'label' => esc_html__('From Opacity', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'step' => 0.1,
            'default' => $from_opacity,
            'min' => 0,
            'max' => 1,
            'condition' => array_merge(['iq_' . $type . '_chart_fill_style_type' . ($i > -1 ? '_' . $i : '') => 'gradient'], ($i > -1 ? $condition : []))
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_gradient_opacityTo' . ($i > -1 ? '_' . $i : ''),
        [
            'label' => esc_html__('To Opacity', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'step' => 0.1,
            'default' => $to_opacity,
            'min' => 0,
            'max' => 1,
            'condition' => array_merge(['iq_' . $type . '_chart_fill_style_type' . ($i > -1 ? '_' . $i : '') => 'gradient'], ($i > -1 ? $condition : []))
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_gradient_inversecolor' . ($i > -1 ? '_' . $i : ''),
        [
            'label' => esc_html__('Inverse Color', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'graphina-lang'),
            'label_off' => esc_html__('No', 'graphina-lang'),
            'default' => false,
            'condition' => array_merge(['iq_' . $type . '_chart_fill_style_type' . ($i > -1 ? '_' . $i : '') => 'gradient'], ($i > -1 ? $condition : []))
        ]
    );

    if (!$usedAsSubPart) {
        $this_ele->end_controls_section();
    }
}

/*****************
 * @param object $this_ele
 * @param string $type
 */
function graphina_chart_label_setting($this_ele, $type = 'chart_id')
{
    $this_ele->start_controls_section(
        'iq_' . $type . '_section_7',
        [
            'label' => esc_html__('Legend Setting', 'graphina-lang'),
//            'condition' => [
//                'iq_' . $type . '_chart_data_series_count!' => 1
//            ],
//            'conditions' => [
//                'relation' => 'or',
//                'terms' => [
//                    [
//                        'relation' => 'and',
//                        'terms' => [
//                            [
//                                'name' => 'iq_' . $type . '_chart_is_pro',
//                                'operator' => '==',
//                                'value' => 'false'
//                            ],
//                            [
//                                'name' => 'iq_' . $type . '_chart_data_option',
//                                'operator' => '==',
//                                'value' => 'manual'
//                            ]
//                        ]
//                    ],
//                    [
//                        'relation' => 'and',
//                        'terms' => [
//                            [
//                                'name' => 'iq_' . $type . '_chart_is_pro',
//                                'operator' => '==',
//                                'value' => 'true'
//                            ]
//                        ]
//                    ]
//                ]
//            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_legend_show',
        [
            'label' => esc_html__('Legend', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Hide', 'graphina-lang'),
            'label_off' => esc_html__('Show', 'graphina-lang'),
            'default' => 'yes'
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_legend_position',
        [
            'label' => esc_html__('Position', 'graphina-lang'),
            'type' => Controls_Manager::CHOOSE,
            'default' => 'bottom',
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'graphina-lang'),
                    'icon' => 'fa fa-arrow-up',
                ],
                'right' => [
                    'title' => esc_html__('Right', 'graphina-lang'),
                    'icon' => 'fa fa-arrow-right',
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'graphina-lang'),
                    'icon' => 'fa fa-arrow-down',
                ],
                'left' => [
                    'title' => esc_html__('Left', 'graphina-lang'),
                    'icon' => 'fa fa-arrow-left',
                ]
            ],
            'condition' => [
                'iq_' . $type . '_chart_legend_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_legend_horizontal_align',
        [
            'label' => esc_html__('Horizontal Align', 'graphina-lang'),
            'type' => Controls_Manager::CHOOSE,
            'default' => 'center',
            'options' => [
                'left' => [
                    'title' => esc_html__('Left', 'graphina-lang'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'graphina-lang'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => esc_html__('Right', 'graphina-lang'),
                    'icon' => 'fa fa-align-right',
                ]
            ],
            'condition' => [
                'iq_' . $type . '_chart_legend_position' => ['top', 'bottom'],
                'iq_' . $type . '_chart_legend_show' => 'yes'
            ]
        ]
    );

    $this_ele->end_controls_section();
}

/******************
 * @param object $this_ele
 * @param string $type
 * @param boolean $showFixed
 * @param bool $showTooltip
 */
function graphina_advance_x_axis_setting($this_ele, $type = 'chart_id', $showFixed = true, $showTooltip = true)
{
    $this_ele->start_controls_section(
        'iq_' . $type . '_section_5',
        [
            'label' => esc_html__('X-Axis Setting', 'graphina-lang'),
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

    if ($showTooltip) {
        $this_ele->add_control(
            'iq_' . $type . '_chart_xaxis_tooltip_show',
            [
                'label' => esc_html__('Tooltip', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Hide', 'graphina-lang'),
                'label_off' => esc_html__('Show', 'graphina-lang'),
                'default' => ''
            ]
        );

        $this_ele->add_control(
            'iq_' . $type . '_chart_xaxis_crosshairs_show',
            [
                'label' => esc_html__('Pointer Line', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Hide', 'graphina-lang'),
                'label_off' => esc_html__('Show', 'graphina-lang'),
                'default' => '',
                'condition' => [
                    'iq_' . $type . '_chart_xaxis_tooltip_show' => 'yes'
                ]
            ]
        );
    }

    $this_ele->add_control(
        'iq_' . $type . '_chart_xaxis_datalabel_show',
        [
            'label' => esc_html__('Labels', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Hide', 'graphina-lang'),
            'label_off' => esc_html__('Show', 'graphina-lang'),
            'default' => 'yes'
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_xaxis_datalabel_position',
        [
            'label' => esc_html__('Position', 'graphina-lang'),
            'type' => Controls_Manager::CHOOSE,
            'default' => 'bottom',
            'options' => [
                'top' => [
                    'title' => esc_html__('Top', 'graphina-lang'),
                    'icon' => 'fa fa-arrow-up',
                ],
                'bottom' => [
                    'title' => esc_html__('Bottom', 'graphina-lang'),
                    'icon' => 'fa fa-arrow-down',
                ]
            ],
            'condition' => [
                'iq_' . $type . '_chart_xaxis_datalabel_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_xaxis_datalabel_auto_rotate',
        [
            'label' => esc_html__('Labels Auto Rotate', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('False', 'graphina-lang'),
            'label_off' => esc_html__('True', 'graphina-lang'),
            'default' => false,
            'condition' => [
                'iq_' . $type . '_chart_xaxis_datalabel_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_xaxis_datalabel_rotate',
        [
            'label' => esc_html__('Rotate', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => -45,
            'max' => 360,
            'min' => -360,
            'condition' => [
                'iq_' . $type . '_chart_xaxis_datalabel_auto_rotate' => 'yes',
                'iq_' . $type . '_chart_xaxis_datalabel_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_xaxis_datalabel_offset_x',
        [
            'label' => esc_html__('Offset-X', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                'iq_' . $type . '_chart_xaxis_datalabel_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_xaxis_datalabel_offset_y',
        [
            'label' => esc_html__('Offset-Y', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                'iq_' . $type . '_chart_xaxis_datalabel_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_xaxis_datalabel_tick_amount',
        [
            'label' => esc_html__('Tick Amount', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 30,
            'max' => 30,
            'min' => 0,
            'condition' => [
                'iq_' . $type . '_chart_xaxis_datalabel_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_xaxis_datalabel_tick_placement',
        [
            'label' => esc_html__('Tick Placement', 'graphina-lang'),
            'type' => Controls_Manager::SELECT,
            'default' => graphina_position_type('placement', true),
            'options' => graphina_position_type('placement'),
            'condition' => [
                'iq_' . $type . '_chart_xaxis_datalabel_show' => 'yes'
            ]
        ]
    );

    if ($showFixed) {
        $this_ele->add_control(
            'iq_' . $type . '_chart_xaxis_label_show',
            [
                'label' => esc_html__('Labels Prefix/Postfix', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Hide', 'graphina-lang'),
                'label_off' => esc_html__('Show', 'graphina-lang'),
                'default' => false,
                'condition' => [
                    'iq_' . $type . '_chart_xaxis_datalabel_show' => 'yes'
                ]
            ]
        );

        $this_ele->add_control(
            'iq_' . $type . '_chart_xaxis_label_prefix',
            [
                'label' => esc_html__('Labels Prefix', 'graphina-lang'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'iq_' . $type . '_chart_xaxis_label_show' => 'yes',
                    'iq_' . $type . '_chart_xaxis_datalabel_show' => 'yes'
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this_ele->add_control(
            'iq_' . $type . '_chart_xaxis_label_postfix',
            [
                'label' => esc_html__('Labels Postfix', 'graphina-lang'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'iq_' . $type . '_chart_xaxis_label_show' => 'yes',
                    'iq_' . $type . '_chart_xaxis_datalabel_show' => 'yes'
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
    }
    $this_ele->end_controls_section();
}

/******************
 * @param object $this_ele
 * @param string $type
 * @param boolean $showFixed
 * @param bool $showTooltip
 */
function graphina_advance_y_axis_setting($this_ele, $type = 'chart_id', $showFixed = true, $showTooltip = true)
{
    $this_ele->start_controls_section(
        'iq_' . $type . '_section_6',
        [
            'label' => esc_html__('Y-Axis Setting', 'graphina-lang'),
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

    $this_ele->add_control(
        'iq_' . $type . '_chart_yaxis_line_show',
        [
            'label' => esc_html__('Line', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Hide', 'graphina-lang'),
            'label_off' => esc_html__('Show', 'graphina-lang'),
            'default' => 'yes'
        ]
    );

    if (in_array($type, ['line', 'area', 'column', 'bubble', 'candle'])) {
        $this_ele->add_control(
            'iq_' . $type . '_chart_yaxis_0_indicator_show',
            [
                'label' => esc_html__('Zero Indicator', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Hide', 'graphina-lang'),
                'label_off' => esc_html__('Show', 'graphina-lang'),
                'default' => false
            ]
        );

        $this_ele->add_control(
            'iq_' . $type . '_chart_yaxis_0_indicator_stroke_dash',
            [
                'label' => esc_html__('Stroke Dash', 'graphina-lang'),
                'type' => Controls_Manager::NUMBER,
                'default' => 0,
                'condition' => [
                    'iq_' . $type . '_chart_yaxis_0_indicator_show' => 'yes'
                ]
            ]
        );

        $this_ele->add_control(
            'iq_' . $type . '_chart_yaxis_0_indicator_stroke_color',
            [
                'label' => esc_html__('Stroke Color', 'graphina-lang'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000000',
                'condition' => [
                    'iq_' . $type . '_chart_yaxis_0_indicator_show' => 'yes'
                ]
            ]
        );
    }

    if ($showTooltip) {
        $this_ele->add_control(
            'iq_' . $type . '_chart_yaxis_tooltip_show',
            [
                'label' => esc_html__('Tooltip', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Hide', 'graphina-lang'),
                'label_off' => esc_html__('Show', 'graphina-lang'),
                'default' => ''
            ]
        );

        $this_ele->add_control(
            'iq_' . $type . '_chart_yaxis_crosshairs_show',
            [
                'label' => esc_html__('Pointer Line', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Hide', 'graphina-lang'),
                'label_off' => esc_html__('Show', 'graphina-lang'),
                'default' => '',
                'condition' => [
                    'iq_' . $type . '_chart_yaxis_tooltip_show' => 'yes'
                ]
            ]
        );

    }

    $this_ele->add_control(
        'iq_' . $type . '_chart_yaxis_datalabel_show',
        [
            'label' => esc_html__('Labels', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Hide', 'graphina-lang'),
            'label_off' => esc_html__('Show', 'graphina-lang'),
            'default' => 'yes'
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_yaxis_datalabel_position',
        [
            'label' => esc_html__('Position', 'graphina-lang'),
            'type' => Controls_Manager::CHOOSE,
            'default' => graphina_position_type('horizontal_boolean', true),
            'options' => graphina_position_type('horizontal_boolean'),
            'condition' => [
                'iq_' . $type . '_chart_yaxis_datalabel_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_yaxis_datalabel_offset_x',
        [
            'label' => esc_html__('Offset-X', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                'iq_' . $type . '_chart_yaxis_datalabel_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_yaxis_datalabel_offset_y',
        [
            'label' => esc_html__('Offset-Y', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                'iq_' . $type . '_chart_yaxis_datalabel_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_yaxis_datalabel_rotate',
        [
            'label' => esc_html__('Rotate', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'max' => 360,
            'min' => -360,
            'condition' => [
                'iq_' . $type . '_chart_yaxis_datalabel_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_yaxis_datalabel_tick_amount',
        [
            'label' => esc_html__('Tick Amount', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'max' => 30,
            'min' => 0,
            'condition' => [
                'iq_' . $type . '_chart_yaxis_datalabel_show' => 'yes'
            ]
        ]
    );

    $condition = ['iq_' . $type . '_chart_yaxis_datalabel_show' => 'yes'];
    $note = '';
    if ($showFixed) {
        $condition = [
            'iq_' . $type . '_chart_yaxis_datalabel_show' => 'yes',
            'iq_' . $type . '_chart_yaxis_label_show!' => 'yes'
        ];
        $note = esc_html__('If you enabled "Labels Prefix/Postfix", this won’t have any effect.', 'graphina-lang');
    }

    $this_ele->add_control(
        'iq_' . $type . '_chart_yaxis_datalabel_decimals_in_float',
        [
            'label' => esc_html__('Decimals In Float', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 2,
            'max' => 6,
            'min' => 0,
            'condition' => $condition,
            'description' => $note
        ]
    );

    if ($showFixed) {
        $this_ele->add_control(
            'iq_' . $type . '_chart_yaxis_label_show',
            [
                'label' => esc_html__('Labels Prefix/Postfix', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Hide', 'graphina-lang'),
                'label_off' => esc_html__('Show', 'graphina-lang'),
                'default' => false,
                'condition' => [
                    'iq_' . $type . '_chart_yaxis_datalabel_show' => 'yes'
                ]
            ]
        );

        $this_ele->add_control(
            'iq_' . $type . '_chart_yaxis_label_prefix',
            [
                'label' => esc_html__('Labels Prefix', 'graphina-lang'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'iq_' . $type . '_chart_yaxis_label_show' => 'yes',
                    'iq_' . $type . '_chart_yaxis_datalabel_show' => 'yes'
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this_ele->add_control(
            'iq_' . $type . '_chart_yaxis_label_postfix',
            [
                'label' => esc_html__('Labels Postfix', 'graphina-lang'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'iq_' . $type . '_chart_yaxis_label_show' => 'yes',
                    'iq_' . $type . '_chart_yaxis_datalabel_show' => 'yes'
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
    }

    $this_ele->end_controls_section();
}

/*******************
 * @param object $this_ele
 * @param string $type
 */
function graphina_style_section($this_ele, $type = 'chart_id')
{
    $this_ele->start_controls_section('iq_' . $type . '_style_section',
        [
            'label' => esc_html__('Style Section', 'graphina-lang'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'iq_' . $type . '_chart_card_show' => 'yes'
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
    /** Header settings. */
    $this_ele->add_control(
        'iq_' . $type . '_title_options',
        [
            'label' => esc_html__('Title', 'graphina-lang'),
            'type' => Controls_Manager::HEADING,
            'condition' => ['iq_' . $type . '_is_card_heading_show' => 'yes'],
        ]
    );
    /** Header typography. */
    $this_ele->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name' => 'iq_' . $type . '_card_title_typography',
            'label' => esc_html__('Typography', 'graphina-lang'),
            'scheme' => Scheme_Typography::TYPOGRAPHY_1,
            'selector' => '{{WRAPPER}} .graphina-chart-heading',
            'condition' => ['iq_' . $type . '_is_card_heading_show' => 'yes']
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_card_title_align',
        [
            'label' => esc_html__('Alignment', 'graphina-lang'),
            'type' => Controls_Manager::CHOOSE,
            'default' => 'left',
            'options' => [
                'left' => [
                    'title' => esc_html__('Left', 'graphina-lang'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'graphina-lang'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => esc_html__('Right', 'graphina-lang'),
                    'icon' => 'fa fa-align-right',
                ]
            ],
            'condition' => [
                'iq_' . $type . '_is_card_heading_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_card_title_font_color',
        [
            'label' => esc_html__('Font Color', 'graphina-lang'),
            'type' => Controls_Manager::COLOR,
            'default' => '#000000',
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_card_title_margin',
        [
            'label' => esc_html__('Margin', 'graphina-lang'),
            'size_units' => ['px', '%', 'em'],
            'type' => Controls_Manager::DIMENSIONS,
            'condition' => [
                'iq_' . $type . '_is_card_heading_show' => 'yes'
            ],
            'selectors' => [
                '{{WRAPPER}} .graphina-chart-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_card_title_padding',
        [
            'label' => esc_html__('Padding', 'graphina-lang'),
            'size_units' => ['px', '%', 'em'],
            'type' => Controls_Manager::DIMENSIONS,
            'condition' => [
                'iq_' . $type . '_is_card_heading_show' => 'yes'
            ],
            'selectors' => [
                '{{WRAPPER}} .graphina-chart-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_subtitle_options',
        [
            'label' => esc_html__('Description', 'graphina-lang'),
            'type' => Controls_Manager::HEADING,
            'condition' => ['iq_' . $type . '_is_card_desc_show' => 'yes']
        ]
    );

    $this_ele->add_group_control(
        Group_Control_Typography::get_type(),
        [
            'name' => 'iq_' . $type . '_subtitle_typography',
            'label' => esc_html__('Typography', 'graphina-lang'),
            'scheme' => Scheme_Typography::TYPOGRAPHY_2,
            'selector' => '{{WRAPPER}} .graphina-chart-sub-heading',
            'condition' => ['iq_' . $type . '_is_card_desc_show' => 'yes']
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_card_subtitle_align',
        [
            'label' => esc_html__('Alignment', 'graphina-lang'),
            'type' => Controls_Manager::CHOOSE,
            'default' => 'left',
            'options' => [
                'left' => [
                    'title' => esc_html__('Left', 'graphina-lang'),
                    'icon' => 'fa fa-align-left',
                ],
                'center' => [
                    'title' => esc_html__('Center', 'graphina-lang'),
                    'icon' => 'fa fa-align-center',
                ],
                'right' => [
                    'title' => esc_html__('Right', 'graphina-lang'),
                    'icon' => 'fa fa-align-right',
                ]
            ],
            'condition' => [
                'iq_' . $type . '_is_card_heading_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_card_subtitle_font_color',
        [
            'label' => esc_html__('Font Color', 'graphina-lang'),
            'type' => Controls_Manager::COLOR,
            'default' => '#000000',
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_card_subtitle_margin',
        [
            'label' => esc_html__('Margin', 'graphina-lang'),
            'size_units' => ['px', '%', 'em'],
            'type' => Controls_Manager::DIMENSIONS,
            'condition' => [
                'iq_' . $type . '_is_card_heading_show' => 'yes'
            ],
            'selectors' => [
                '{{WRAPPER}} .graphina-chart-sub-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_card_subtitle_padding',
        [
            'label' => esc_html__('Padding', 'graphina-lang'),
            'size_units' => ['px', '%', 'em'],
            'type' => Controls_Manager::DIMENSIONS,
            'condition' => [
                'iq_' . $type . '_is_card_heading_show' => 'yes'
            ],
            'selectors' => [
                '{{WRAPPER}} .graphina-chart-sub-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
            ],
        ]
    );
    $this_ele->end_controls_section();

}

/*******************
 * @param object $this_ele
 * @param string $type
 */
function graphina_card_style($this_ele, $type = 'chart_id')
{
    $this_ele->start_controls_section('iq_' . $type . '_card_style_section',
        [
            'label' => esc_html__('Card Style', 'graphina-lang'),
            'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                'iq_' . $type . '_chart_card_show' => 'yes'
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


    $this_ele->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name' => 'iq_' . $type . '_card_background',
            'label' => esc_html__('Background', 'graphina-lang'),
            'types' => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .chart-card',
            'condition' => [
                'iq_' . $type . '_chart_card_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_group_control(
        Group_Control_Box_Shadow::get_type(),
        [
            'name' => 'iq_' . $type . '_card_box_shadow',
            'label' => esc_html__('Box Shadow', 'graphina-lang'),
            'selector' => '{{WRAPPER}} .chart-card',
            'condition' => ['iq_' . $type . '_chart_card_show' => 'yes']
        ]
    );

    $this_ele->add_group_control(
        Group_Control_Border::get_type(),
        [
            'name' => 'iq_' . $type . '_card_border',
            'label' => esc_html__('Border', 'graphina-lang'),
            'selector' => '{{WRAPPER}} .chart-card',
            'condition' => ['iq_' . $type . '_chart_card_show' => 'yes']
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_card_border_radius',
        [
            'label' => esc_html__('Border Radius', 'graphina-lang'),
            'size_units' => ['px', '%', 'em'],
            'type' => Controls_Manager::DIMENSIONS,
            'condition' => [
                'iq_' . $type . '_chart_card_show' => 'yes'
            ],
            'selectors' => [
                '{{WRAPPER}} .chart-card' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden;',
            ],
        ]
    );

    $this_ele->end_controls_section();
}

/*******************
 * @param object $this_ele
 * @param string $type
 */
function graphina_chart_style($this_ele, $type = 'chart_id')
{
    $this_ele->start_controls_section(
        'iq_' . $type . '_chart_style_section',
        [
            'label' => esc_html__('Chart Style', 'graphina-lang'),
            'tab' => Controls_Manager::TAB_STYLE,
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

    $this_ele->add_control(
        'iq_' . $type . '_chart_font_family',
        [
            'label' => esc_html__('Font Family', 'graphina-lang'),
            'type' => Controls_Manager::FONT,
            'description' => esc_html__("Notice:If possible use same font as Chart Title & Description, Otherwise it may not show the actual font you selected.", 'graphina-lang'),
            'default' => "Poppins"
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_font_size',
        [
            'label' => esc_html__('Font Size', 'graphina-lang'),
            'type' => Controls_Manager::SLIDER,
            'size_units' => ['px', 'em', 'rem', 'vw'],
            'range' => [
                'px' => [
                    'min' => 1,
                    'max' => 200,
                ],
                'em' => [
                    'min' => 1,
                    'max' => 200,
                ],
                'rem' => [
                    'min' => 1,
                    'max' => 200,
                ],
                'vw' => [
                    'min' => 0.1,
                    'max' => 10,
                    'step' => 0.1,
                ],
            ],
            'default' => [
                'unit' => 'px',
                'size' => 12,
            ]
        ]
    );

    $typo_weight_options = [
        '' => esc_html__('Default', 'graphina-lang'),
    ];

    foreach (array_merge(['normal', 'bold'], range(100, 900, 100)) as $weight) {
        $typo_weight_options[$weight] = ucfirst($weight);
    }

    $this_ele->add_control(
        'iq_' . $type . '_chart_font_weight',
        [
            'label' => esc_html__('Font Weight', 'graphina-lang'),
            'type' => Controls_Manager::SELECT,
            'default' => '',
            'options' => $typo_weight_options,
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_font_color',
        [
            'label' => esc_html__('Font Color', 'graphina-lang'),
            'type' => Controls_Manager::COLOR,
            'default' => '#000000',
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_border_show',
        [
            'label' => esc_html__('Chart Box', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Hide', 'graphina-lang'),
            'label_off' => esc_html__('Show', 'graphina-lang'),
            'default' => 'yes'
        ]
    );

    $this_ele->add_group_control(
        Group_Control_Background::get_type(),
        [
            'name' => 'iq_' . $type . '_chart_background',
            'label' => esc_html__('Background', 'graphina-lang'),
            'types' => ['classic', 'gradient'],
            'selector' => '{{WRAPPER}} .chart-box',
            'condition' => [
                'iq_' . $type . '_chart_border_show' => 'yes'
            ]
        ]
    );

    $this_ele->add_group_control(
        Group_Control_Box_Shadow::get_type(),
        [
            'name' => 'iq_' . $type . '_chart_box_shadow',
            'label' => esc_html__('Box Shadow', 'graphina-lang'),
            'selector' => '{{WRAPPER}} .chart-box',
            'condition' => ['iq_' . $type . '_chart_border_show' => 'yes']
        ]
    );

    $this_ele->add_group_control(
        Group_Control_Border::get_type(),
        [
            'name' => 'iq_' . $type . '_chart_border',
            'label' => esc_html__('Border', 'graphina-lang'),
            'selector' => '{{WRAPPER}} .chart-box',
            'condition' => ['iq_' . $type . '_chart_border_show' => 'yes']
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_border_radius',
        [
            'label' => esc_html__('Border Radius', 'graphina-lang'),
            'size_units' => ['px', '%', 'em'],
            'type' => Controls_Manager::DIMENSIONS,
            'condition' => [
                'iq_' . $type . '_chart_border_show' => 'yes'
            ],
            'selectors' => [
                '{{WRAPPER}} .chart-box' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};overflow:hidden;',
            ],
        ]
    );
    $this_ele->end_controls_section();
}

/*******************
 * @param object $this_ele
 * @param string $type
 */
function graphina_stroke($this_ele, $type = 'chart_id')
{
    $this_ele->add_control(
        'iq_' . $type . '_chart_hr_stroke_setting',
        [
            'type' => Controls_Manager::DIVIDER,
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_stroke_setting_title',
        [
            'label' => esc_html__('Stroke Settings', 'graphina-lang'),
            'type' => Controls_Manager::HEADING,
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_stroke_show',
        [
            'label' => esc_html__('Show', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'graphina-lang'),
            'label_off' => esc_html__('No', 'graphina-lang'),
            'default' => false
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_stroke_width',
        [
            'label' => 'Stroke Width',
            'type' => Controls_Manager::NUMBER,
            'default' => 2,
            'min' => 0,
            'max' => 10,
            'condition' => [
                'iq_' . $type . '_chart_stroke_show' => 'yes'
            ]
        ]
    );
}

/*******************
 * @param object $this_ele
 * @param string $type
 */
function graphina_animation($this_ele, $type = 'chart_id')
{
    $this_ele->add_control(
        'iq_' . $type . '_chart_hr_animation_setting',
        [
            'type' => Controls_Manager::DIVIDER,
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_animation_setting_title',
        [
            'label' => esc_html__('Animation Settings', 'graphina-lang'),
            'type' => Controls_Manager::HEADING,
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_animation',
        [
            'label' => esc_html__('Custom', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'graphina-lang'),
            'label_off' => esc_html__('No', 'graphina-lang'),
            'default' => 'yes',
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_animation_speed',
        [
            'label' => esc_html__('Speed', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 800,
            'condition' => [
                'iq_' . $type . '_chart_animation' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_animation_delay',
        [
            'label' => esc_html__('Delay', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 150,
            'condition' => [
                'iq_' . $type . '_chart_animation' => 'yes'
            ]
        ]
    );
}

/********************
 * @param object $this_ele
 * @param string $type
 */
function graphina_plot_setting($this_ele, $type = 'chart_id')
{
    $this_ele->add_control(
        'iq_' . $type . '_chart_hr_plot_setting',
        [
            'type' => Controls_Manager::DIVIDER,
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_plot_setting_title',
        [
            'label' => esc_html__('Plot Settings', 'graphina-lang'),
            'type' => Controls_Manager::HEADING,
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_plot_options',
        [
            'label' => esc_html__('Show Options', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Hide', 'graphina-lang'),
            'label_off' => esc_html__('Show', 'graphina-lang'),
            'default' => 'yes',
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_plot_size',
        [
            'label' => esc_html__('Size', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 140,
            'condition' => [
                'iq_' . $type . '_chart_plot_options' => 'yes'
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_plot_stroke_color',
        [
            'label' => 'Stroke Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#e9e9e9'
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_plot_color',
        [
            'label' => 'Color',
            'type' => Controls_Manager::COLOR,
            'default' => '#ffffff'
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_stroke_size',
        [
            'label' => esc_html__('Stroke Size', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 1,
            'min' => 0
        ]
    );
}

/********************
 * @param object $this_ele
 * @param string $type
 */
function graphina_marker_setting($this_ele, $type = 'chart_id', $i)
{

    if($type == 'mixed'){
        $condition = [
            'iq_' . $type . '_chart_data_series_count' => range(1 + $i, graphina_default_setting('max_series_value')),
            'iq_' . $type . '_chart_type_3_' . $i.'!' => 'bar'
        ];
    }
    else{
        $condition = [
            'iq_' . $type . '_chart_data_series_count' => range(1 + $i, graphina_default_setting('max_series_value')),
        ];
    }

    $this_ele->add_control(
        'iq_' . $type . '_chart_marker_setting_title_'.$i,
        [
            'label' => esc_html__('Marker Settings', 'graphina-lang'),
            'type' => Controls_Manager::HEADING,
            'condition' =>  $condition
        ]
    );


    $this_ele->add_control(
        'iq_' . $type . '_chart_marker_size_'.$i,
        [
            'label' => esc_html__('Size', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => $type == 'radar' || $type == 'mixed' ? 3 : 0,
            'condition' => $condition
        ]
    );


    $this_ele->add_control(
        'iq_' . $type . '_chart_marker_stroke_color_'.$i,
        [
            'label' => esc_html__('Stroke Color', 'graphina-lang'),
            'type' => Controls_Manager::COLOR,
            'default' => '#fff',
            'condition' => $condition
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_marker_stroke_width_'.$i,
        [
            'label' => esc_html__('Stroke Width', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => $type == 'mixed' ? 1 :0,
            'min' => 0,
            'condition' =>  $condition
        ]
    );
    $this_ele->add_control(
        'iq_' . $type . '_chart_chart_marker_stroke_shape_'.$i,
        [
            'label' => esc_html__('Shape', 'graphina-lang'),
            'type' => Controls_Manager::SELECT,
            'default' => 'circle',
            'options' => [
                'circle' => esc_html__('Circle', 'graphina-lang'),
                'square' => esc_html__('Square', 'graphina-lang'),
            ],
            'condition' =>  $condition,
            'description' => esc_html__('Note: Hover will Not work in Square', 'graphina-lang'),

        ]
    );

}

/********************
 * @param object $this_ele
 * @param string $type
 * @param bool $showTheme
 * @param bool $shared
 */
function graphina_tooltip($this_ele, $type = 'chart_id', $showTheme = true, $shared = true)
{
    $this_ele->add_control(
        'iq_' . $type . '_chart_hr_tooltip_setting',
        [
            'type' => Controls_Manager::DIVIDER,
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_tooltip_setting_title',
        [
            'label' => esc_html__('Tooltip Settings', 'graphina-lang'),
            'type' => Controls_Manager::HEADING,
        ]
    );
    $notice = '';
    if ($type === 'radar') {
        $notice = esc_html__('Warning: This will may not work if markers are not shown.', 'graphina-lang');
    }
    $this_ele->add_control(
        'iq_' . $type . '_chart_tooltip',
        [
            'label' => esc_html__('Show', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'graphina-lang'),
            'label_off' => esc_html__('No', 'graphina-lang'),
            'default' => 'yes',
            'description' => $notice
        ]
    );

    if ($shared && $type != 'candle') {
        $notice = '';
        if ($type === 'column') {
            $notice = esc_html__('Warning: This will may not work for horizontal column chart.', 'graphina-lang');
        }
        $this_ele->add_control(
            'iq_' . $type . '_chart_tooltip_shared',
            [
                'label' => esc_html__('Shared', 'graphina-lang'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Yes', 'graphina-lang'),
                'label_off' => esc_html__('No', 'graphina-lang'),
                'description' => $notice,
                'default' => 'yes',
                'condition' => [
                    'iq_' . $type . '_chart_tooltip' => 'yes',
                ]
            ]
        );
    }
    if ($showTheme) {
        $this_ele->add_control(
            'iq_' . $type . '_chart_tooltip_theme',
            [
                'label' => esc_html__('Theme', 'graphina-lang'),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'light',
                'options' => [
                    'light' => [
                        'title' => esc_html__('Light', 'graphina-lang'),
                        'icon' => 'fas fa-sun',
                    ],
                    'dark' => [
                        'title' => esc_html__('Dark', 'graphina-lang'),
                        'icon' => 'fas fa-moon',
                    ]
                ],
                'condition' => [
                    'iq_' . $type . '_chart_tooltip' => 'yes'
                ]
            ]
        );
    }
}

/********************
 * @param object $this_ele
 * @param string $type
 * @param bool $condition
 */
function graphina_dropshadow($this_ele, $type = 'chart_id', $condition = true)
{

    $this_ele->add_control(
        'iq_' . $type . '_chart_hr_plot_drop_shadow_setting',
        [
            'type' => Controls_Manager::DIVIDER,
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_chart_plot_drop_shadow_setting_title',
        [
            'label' => esc_html__('Drop Shadow Settings', 'graphina-lang'),
            'type' => Controls_Manager::HEADING,
        ]
    );


    $this_ele->add_control(
        'iq_' . $type . '_is_chart_dropshadow',
        [
            'label' => esc_html__('Show', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'graphina-lang'),
            'label_off' => esc_html__('No', 'graphina-lang'),
            'default' => false,
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_is_chart_dropshadow_top',
        [
            'label' => esc_html__('Drop Shadow Top Position', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                'iq_' . $type . '_is_chart_dropshadow' => 'yes',
            ],
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_is_chart_dropshadow_left',
        [
            'label' => esc_html__('Drop Shadow Left Position', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                'iq_' . $type . '_is_chart_dropshadow' => 'yes',
            ],
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_is_chart_dropshadow_blur',
        [
            'label' => esc_html__('Drop Shadow Blur', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0,
            'condition' => [
                'iq_' . $type . '_is_chart_dropshadow' => 'yes',
            ],
        ]
    );

    if ($condition) {
        $this_ele->add_control(
            'iq_' . $type . '_is_chart_dropshadow_color',
            [
                'label' => esc_html__('Drop Shadow Color', 'graphina-lang'),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'condition' => [
                    'iq_' . $type . '_is_chart_dropshadow' => 'yes',
                ],
            ]
        );
    }

    $this_ele->add_control(
        'iq_' . $type . '_is_chart_dropshadow_opacity',
        [
            'label' => esc_html__('Drop Shadow Opacity', 'graphina-lang'),
            'type' => Controls_Manager::NUMBER,
            'default' => 0.35,
            'max' => 1,
            'min' => 0,
            'step' => 0.05,
            'condition' => [
                'iq_' . $type . '_is_chart_dropshadow' => 'yes',
            ],
        ]
    );
}

/********************
 * @param string $type color,gradientColor
 * @return string[]
 */
function graphina_colors($type = 'color'): array
{
    if ($type === 'gradientColor') {
        return ['#6C25FB', '#ff7179', '#654ae8', '#f8576f', '#31317a', '#fe6f7e', '#7D02EB', '#E02828', '#D56767', '#26A2D6', '#6C25FB', '#ff7179', '#654ae8', '#f8576f', '#31317a', '#fe6f7e', '#7D02EB', '#E02828', '#D56767', '#26A2D6'];
    }
    return ['#3499FF', '#e53efc', '#f9a243', '#46adfe', '#2c80ff', '#e23cfd', '#7D02EB', '#8D5B4C', '#F86624', '#2E294E', '#3499FF', '#e53efc', '#f9a243', '#46adfe', '#2c80ff', '#e23cfd', '#7D02EB', '#8D5B4C', '#F86624', '#2E294E'];
}

/********************
 * @param $start
 * @param $format
 * @param array $add
 * @param array $minus
 * @return false|string
 */
function graphina_getRandomDate($start, $format, $add = [], $minus = [])
{
    $date = '';
    foreach ($add as $i => $a) {
        $date .= ' + ' . $a . ' ' . $i;
    }
    foreach ($minus as $j => $b) {
        $date .= ' - ' . $b . ' ' . $j;
    }
    return date($format, strtotime($date, strtotime($start)));
}

/**********************
 * @param object $this_ele
 * @param string $type
 * @param array $ele_array show elements like ["color"]
 * @param boolean $showFillStyle
 * @param array $fillOptions like ['classic', 'gradient', 'pattern']
 * @param boolean $showFillOpacity
 * @param boolean $showGradientType
 */
function graphina_series_setting($this_ele, $type = 'chart_id', $ele_array = ['color'], $showFillStyle = true, $fillOptions = [], $showFillOpacity = false, $showGradientType = false)
{
    $colors = graphina_colors('color');
    $gradientColor = graphina_colors('gradientColor');
    $seriesTest = 'Element';

    $this_ele->start_controls_section(
        'iq_' . $type . '_section_11',
        [
            'label' => esc_html__('Elements Setting', 'graphina-lang'),
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

    if ($showFillStyle) {
        graphina_fill_style_setting($this_ele, $type, $fillOptions, $showFillOpacity);
    }

    if ($showFillStyle && in_array('gradient', $fillOptions)) {
        graphina_gradient_setting($this_ele, $type, $showGradientType, true);
    }

    for ($i = 0; $i < graphina_default_setting('max_series_value'); $i++) {

        if ($i !== 0 || $showFillStyle) {
            $this_ele->add_control(
                'iq_' . $type . '_chart_hr_series_count_' . $i,
                [
                    'type' => Controls_Manager::DIVIDER,
                    'condition' => [
                        'iq_' . $type . '_chart_data_series_count' => range(1 + $i, graphina_default_setting('max_series_value'))
                    ]
                ]
            );
        }

        $this_ele->add_control(
            'iq_' . $type . '_chart_series_title_' . $i,
            [
                'label' => esc_html__($seriesTest . ' ' . ($i + 1), 'graphina-lang'),
                'type' => Controls_Manager::HEADING,
                'condition' => [
                    'iq_' . $type . '_chart_data_series_count' => range(1 + $i, graphina_default_setting('max_series_value'))
                ]
            ]
        );

        if (in_array('tooltip', $ele_array)) {
            $this_ele->add_control(
                'iq_' . $type . '_chart_tooltip_enabled_on_1_' . $i,
                [
                    'label' => esc_html__('Tooltip Enabled', 'graphina-lang'),
                    'type' => Controls_Manager::SWITCHER,
                    'label_on' => esc_html__('Yes', 'graphina-lang'),
                    'label_off' => esc_html__('No', 'graphina-lang'),
                    'default' => 'yes',
                    'condition' => [
                        'iq_' . $type . '_chart_tooltip' => 'yes',
                        'iq_' . $type . '_chart_tooltip_shared' => 'yes',
                        'iq_' . $type . '_chart_data_series_count' => range(1 + $i, graphina_default_setting('max_series_value'))
                    ]
                ]
            );
        }

        if (in_array('color', $ele_array)) {
            $this_ele->add_control(
                'iq_' . $type . '_chart_gradient_1_' . $i,
                [
                    'label' => esc_html__('Color', 'graphina-lang'),
                    'type' => Controls_Manager::COLOR,
                    'default' => $colors[$i],
                    'condition' => [
                        'iq_' . $type . '_chart_data_series_count' => range(1 + $i, graphina_default_setting('max_series_value'))
                    ]
                ]
            );
            $this_ele->add_control(
                'iq_' . $type . '_chart_gradient_2_' . $i,
                [
                    'label' => esc_html__('Second Color', 'graphina-lang'),
                    'type' => Controls_Manager::COLOR,
                    'default' => $gradientColor[$i],
                    'condition' => [
                        'iq_' . $type . '_chart_fill_style_type' => 'gradient',
                        'iq_' . $type . '_chart_data_series_count' => range(1 + $i, graphina_default_setting('max_series_value'))
                    ]
                ]
            );
            $this_ele->add_control(
                'iq_' . $type . '_chart_bg_pattern_' . $i,
                [
                    'label' => esc_html__('Fill Pattern', 'graphina-lang'),
                    'type' => Controls_Manager::SELECT,
                    'default' => graphina_get_fill_patterns(true),
                    'options' => graphina_get_fill_patterns(),
                    'condition' => [
                        'iq_' . $type . '_chart_fill_style_type' => 'pattern',
                        'iq_' . $type . '_chart_data_series_count' => range(1 + $i, graphina_default_setting('max_series_value'))
                    ]
                ]
            );
        }

        if (in_array('dash', $ele_array)) {
            $this_ele->add_control(
                'iq_' . $type . '_chart_dash_3_' . $i,
                [
                    'label' => 'Dash',
                    'type' => Controls_Manager::NUMBER,
                    'default' => 0,
                    'min' => 0,
                    'max' => 100,
                    'condition' => [
                        'iq_' . $type . '_chart_data_series_count' => range(1 + $i, graphina_default_setting('max_series_value'))
                    ]
                ]
            );
        }

        if (in_array('width', $ele_array)) {
            $this_ele->add_control(
                'iq_' . $type . '_chart_width_3_' . $i,
                [
                    'label' => 'Stroke Width',
                    'type' => Controls_Manager::NUMBER,
                    'default' => 5,
                    'min' => 1,
                    'max' => 20,
                    'condition' => [
                        'iq_' . $type . '_chart_data_series_count' => range(1 + $i, graphina_default_setting('max_series_value'))
                    ]
                ]
            );
        }

        $chart_type = ['radar','line', 'area'];

        if(in_array($type,$chart_type)){

            graphina_marker_setting($this_ele, $type, $i);

        }

    }
    $this_ele->end_controls_section();
}


function graphina_setting_sort($settings)
{
    //    $typeArr = ['string' => 0,'boolean'=>1,'integer' => 2,'double' => 3,'NULL' => 9,'array' => 10,'lg_array' => 11];
    //    uasort($settings,function($a, $b) use($typeArr){
    //        $a_type = gettype($a);
    //        $b_type = gettype($b);
    //
    //        $a_type= ($a_type === 'array' && count((array)$a_type)>10) ? 'lg_array' : $a_type;
    //        $b_type= ($b_type === 'array' && count((array)$b_type)>10) ? 'lg_array' : $b_type;
    //
    //        $a_index = in_array($a_type,array_keys($typeArr)) ? $typeArr[$a_type] : 8;
    //        $b_index = in_array($b_type,array_keys($typeArr)) ? $typeArr[$b_type] : 8;
    //        return ($a_index > $b_index);
    //    });
    return array_filter($settings, function ($val, $key) {
        return strpos($key, '_value_list_') === false;
    }, ARRAY_FILTER_USE_BOTH);
}

function graphina_get_dynamic_tag_data($eleSettingVals, $mainKey)
{
    return $eleSettingVals[$mainKey];
}


if (!function_exists('get_editable_roles')) {
    require_once(ABSPATH . '/wp-admin/includes/user.php');
}

function graphina_fetch_roles_options()
{
    $roles = get_editable_roles();
    $tempneer = array();
    foreach ($roles as $rol => $rolname) {
        $tempneer[$rol] = $rolname['name'];
    }
    return $tempneer;
}


function graphina_fetch_user_roles($userId, $singleRole = true)
{
    $userRole = [];
    $currentUserRoles = get_user_meta(get_current_user_id(), 'wp_capabilities');

    foreach ($currentUserRoles[0] as $currentUserRole => $currentUserRoleAccess) {
        if ($currentUserRoleAccess) {
            $userRole[] = $currentUserRole;
        }
    }

    if ($singleRole) {
        return !empty($userRole[0]) ? $userRole[0] : '';
    }

    return $userRole;
}

function graphina_restriction_content_options($this_ele, $type = 'chart_id')
{

    $this_ele->start_controls_section(
        'iq_' . $type . '_restriction_content_control',
        [
            'label' => esc_html__('Restriction content access', 'graphina-lang')
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_restriction_content_type',
        [
            'label' => esc_html__('Restriction Based On', 'graphina-lang'),
            'type' => Controls_Manager::SELECT,
            'options' => [
                '' => __('No Restriction Access', 'graphina-lang'),
                'login' => __('Logged In User', 'graphina-lang'),
                'password' => __('Password Protected', 'graphina-lang'),
                'role' => __('Role Based Access', 'graphina-lang')
            ],
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_restriction_content_password',
        [
            'label' => __('Set Password', 'graphina-lang'),
            'type' => Controls_Manager::TEXT,
            'condition' => [
                'iq_' . $type . '_restriction_content_type' => 'password',
            ],
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_password_content_headline',
        [
            'label' => __('Headline', 'graphina-lang'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Protected Area', 'graphina-lang'),
            'condition' => [
                'iq_' . $type . '_restriction_content_type' => 'password',
            ],
            'dynamic' => [
                'active' => true,
            ],
        ]);
    $this_ele->add_control(
        'iq_' . $type . '_password_button_label',
        [
            'label' => __('Button Label', 'graphina-lang'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Submit', 'graphina-lang'),
            'condition' => [
                'iq_' . $type . '_restriction_content_type' => 'password',
            ],

        ]);

    $this_ele->add_control(
        'iq_' . $type . '_password_error_message_show',
        [
            'label' => esc_html__('Error', 'graphina-lang'),
            'type' => Controls_Manager::SWITCHER,
            'label_on' => esc_html__('Yes', 'graphina-lang'),
            'label_off' => esc_html__('No', 'graphina-lang'),
            'description' => esc_html__("Notice:Error message when incorrect password enter", 'graphina-lang'),
            'default' => 'yes',
            'condition' => [
                'iq_' . $type . '_restriction_content_type' => 'password',
            ]
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_password_error_message',
        [
            'label' => __('Error message', 'graphina-lang'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'default' => __('Password is invalid', 'graphina-lang'),
            'condition' => [
                'iq_' . $type . '_restriction_content_type' => 'password',
                'iq_' . $type . '_password_error_message_show' => 'yes',
            ],
            'dynamic' => [
                'active' => true,
            ],
        ]);
    $this_ele->add_control(
        'iq_' . $type . '_password_instructions_text', [
        'label' => __('Instructions Text', 'graphina-lang'),
        'type' => \Elementor\Controls_Manager::TEXTAREA,
        'rows' => 10,
        'default' => __('This content is password-protected. Please verify with a password to unlock the content.', 'graphina-lang'),
        'condition' => [
            'iq_' . $type . '_restriction_content_type' => 'password',
        ],
        'dynamic' => [
            'active' => true,
        ],
    ]);

    $this_ele->add_control(
        'iq_' . $type . '_restriction_content_role_type',
        [
            'label' => __('Select Roles', 'graphina-lang'),
            'type' => Controls_Manager::SELECT2,
            'multiple' => true,
            'condition' => [
                'iq_' . $type . '_restriction_content_type' => 'role',
            ],
            'options' => graphina_fetch_roles_options(),
        ]
    );

    $this_ele->add_control(
        'iq_' . $type . '_restriction_content_template',
        [
            'label' => __('Restricted Template View (shortcode)', 'graphina-lang'),
            'type' => Controls_Manager::WYSIWYG,
            'default' => esc_html__('<div style="padding: 30px; text-align: center;">' .
                '<h5>You don\'t have permission to see this content.</h5>' .
                '<a class="button" href="/wp-login.php">Unlock Access</a></div>', 'graphina-lang'),
            'condition' => [
                'iq_' . $type . '_restriction_content_type!' => ['', 'password'],
            ],
            'dynamic' => [
                'active' => true,
            ],
        ]
    );

    $this_ele->end_controls_section();
}

function isRestrictedAccess($type, $chartId, $settings, $flag = false)
{
    $restrictedTemplate = false;
    if (!empty($settings['iq_' . $type . '_restriction_content_type'])
        && $settings['iq_' . $type . '_restriction_content_type'] != '') {
        $restrictedTemplate = true;
        if (is_user_logged_in()) {
            $restrictedTemplate = false;
            if ($settings['iq_' . $type . '_restriction_content_type'] == 'role') {
                $currentUserRole = graphina_fetch_user_roles(get_current_user_id(), true);
                if (!is_array($settings['iq_' . $type . '_restriction_content_role_type'])
                    || !in_array($currentUserRole, $settings['iq_' . $type . '_restriction_content_role_type'])) {
                    $restrictedTemplate = true;
                }
            }
        }
        if ($settings['iq_' . $type . '_restriction_content_type'] === 'password'
            && (empty($_COOKIE['graphina_' . $type . '_' . $chartId]) || !$_COOKIE['graphina_' . $type . '_' . $chartId])) {
            if ($flag) {
                ?>
                <div class="graphina-restricted-content <?php echo $type === 'counter' ? 'graphina-card counter' : 'chart-card' ?>"
                     style="padding: 20px">
                    <form class="graphina-password-restricted-form" method="post" autocomplete="off" target="_top"
                          onsubmit="return graphinaRestrictedPasswordAjax(this,event)">
                        <h4 class="graphina-password-heading"><?php echo $settings['iq_' . $type . '_password_content_headline']; ?></h4>
                        <p class="graphina-password-message"><?php echo $settings['iq_' . $type . '_password_instructions_text']; ?></p>
                        <div class="graphina-input-wrapper">
                            <input type="hidden" name="chart_password"
                                   value="<?php echo wp_hash_password($settings['iq_' . $type . '_restriction_content_password']); ?>">
                            <input type="hidden" name="chart_type" value="<?php echo $type; ?>">
                            <input type="hidden" name="chart_id" value="<?php echo $chartId; ?>">
                            <input type="hidden" name="action" value="graphina_restrict_password_ajax">
                            <input class="form-control graphina-input " type="password" name="graphina_password"
                                   autocomplete="off" placeholder="Enter Password" style="outline: none">
                        </div>
                        <div class="button-box">
                            <button class="graphina-button" name="submit" type="submit"
                                    style="outline: none"><?php echo $settings['iq_' . $type . '_password_button_label']; ?></button>
                        </div>
                        <div class="graphina-error-div">
                            <?php
                            if (!graphina_is_preview_mode()) {
                                ?>
                                <div class=" elementor-alert-danger graphina-error "
                                     style="display: <?php echo $settings['iq_' . $type . '_password_error_message_show'] === 'yes' ? 'flex' : 'none'; ?>;align-items:center; ">
                                    <span><?php echo $settings['iq_' . $type . '_password_error_message']; ?></span>
                                </div>
                                <?php
                            } else {
                                ?>
                                <div class=" elementor-alert-danger graphina-error "
                                     style="display: none; align-items:center;">
                                    <span><?php echo $settings['iq_' . $type . '_password_error_message']; ?></span>
                                </div>
                            <?php } ?>
                        </div>
                    </form>
                </div>
                <?php
            }
            $restrictedTemplate = true;

        } elseif ($settings['iq_' . $type . '_restriction_content_type'] === 'password') {
            $restrictedTemplate = false;
        }
    }


    return $restrictedTemplate;
}