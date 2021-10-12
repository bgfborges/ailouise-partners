<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit;

$settings =  $this->get_settings_for_display();
if(isRestrictedAccess('animated-radial',$this->get_id(),$settings, true)) {
    if($settings['iq_animated-radial_restriction_content_type'] ==='password'){
        return true;
    }
    echo html_entity_decode($settings['iq_animated-radial_restriction_content_template']);
    return true;
}
?>

<style>
    .full-width {
        width: 100%;
        text-align: center;
    }
</style>

<div class="<?php echo $settings['iq_animated-radial_chart_card_show'] === 'yes' ? 'chart-card' : ''; ?>">
    <div class="">
        <?php if ($settings['iq_animated-radial_is_card_heading_show'] && $settings['iq_animated-radial_chart_card_show']) { ?>
            <h4 class="heading graphina-chart-heading"
                style="text-align: <?php echo $settings['iq_animated-radial_card_title_align']; ?>; color: <?php echo strval($settings['iq_animated-radial_card_title_font_color']);?>"><?php echo esc_html__($settings['iq_animated-radial_chart_heading'], 'graphina-lang'); ?></h4>
        <?php }
        if ($settings['iq_animated-radial_is_card_desc_show'] && $settings['iq_animated-radial_chart_card_show']) { ?>
            <p class="sub-heading graphina-chart-sub-heading"
               style="text-align: <?php echo $settings['iq_animated-radial_card_subtitle_align']; ?>; color: <?php echo strval($settings['iq_animated-radial_card_subtitle_font_color']);?>;"><?php echo esc_html__($settings['iq_animated-radial_chart_content'], 'graphina-lang'); ?></p>
        <?php } ?>
    </div>
    <div class="full-width chart-texture" id="animated-radial_<?php esc_attr_e($this->get_id()); ?>">
        <div class="loader-default">
            <?php for($i=0;$i<12;$i++){
                echo "<div></div>";
            } ?>
        </div>
        <svg
                version="1.1"
                id="Layer_1_<?php esc_attr_e($this->get_id()); ?>"
                xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink"
                x="0px"
                y="0px"
        ></svg>
    </div>
</div>