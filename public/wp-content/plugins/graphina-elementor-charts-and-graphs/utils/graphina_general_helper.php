<?php
function getGraphinaProFileUrl($file)
{
    return GRAPHINA_PRO_ROOT . '/elementor/' . $file;
}

function isGraphinaPro()
{
    return get_option('graphina_is_activate') === "1";
}

function isGraphinaProInstall()
{
    return get_option('graphina_pro_is_install') === "1";
}

function graphina_plugin_activation($is_deactivate = false)
{
    $pluginName = "Graphina";
    $arg = 'plugin=' . $pluginName . '&domain=' . get_bloginfo('wpurl') . '&site_name=' . get_bloginfo('name');
    if ($is_deactivate) {
        $arg .= '&is_deactivated=true';
    }
    wp_remote_get('https://innoquad.in/plugin-server/active-server.php?' . $arg);
}

function graphina_if_failed_load(){
    $latest_pro_version = '1.1.3';

    if (!current_user_can('activate_plugins')) {
        return;
    }

    // Get Graphina animation lite version basename
    $basename = '';
    $plugins = get_plugins();

    foreach ($plugins as $key => $data) {
        if ($data['TextDomain'] === "graphina-pro-charts-for-elementor") {
            $basename = $key;
        }
    }

    if (is_graphina_plugin_installed($basename) && is_plugin_active($basename) && version_compare(graphina_get_pro_plugin_version($basename), $latest_pro_version, '<')) {
        $message = sprintf(__('Required <strong>Version '.$latest_pro_version.' </strong>of<strong> Graphina – Elementor Dynamic Charts & Datatable</strong> plugin. Please update to continue.', 'graphina-lang'), '<strong>', '</strong>');
        $url = "https://themeforest.net/downloads";
        $button_text = __('Download Version '.$latest_pro_version, 'graphina-lang');
        $button = '<p><a target="_blank" href="' . $url . '" class="button-primary">' . $button_text . '</a></p>';
        printf('<div class="error"><p>%1$s</p>%2$s</div>', __($message), $button);
    }

}

function is_graphina_plugin_installed($basename)
{
    if (!function_exists('get_plugins')) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $plugins = get_plugins();
    return isset($plugins[$basename]);
}

function graphina_get_pro_plugin_version($basename)
{
    if (!function_exists('get_plugins')) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $plugins = get_plugins();
    return $plugins[$basename]['Version'];
}

function graphina_is_preview_mode()
{
    if (isset($_REQUEST['elementor-preview'])) {
        return false;
    }

    if (isset($_REQUEST['ver'])) {
        return false;
    }

    if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'elementor') {
        return false;
    }

    $url_params = !empty($_SERVER['HTTP_REFERER']) ?  parse_url($_SERVER['HTTP_REFERER'],PHP_URL_QUERY) : parse_url($_SERVER['REQUEST_URI'],PHP_URL_QUERY);
    parse_str($url_params,$params);
    if(!empty($params['action']) && $params['action'] == 'elementor'){
        return false;
    }

    if(!empty($params['preview']) && $params['preview'] == 'true'){
        return false;
    }

    if(!empty($params['elementor-preview'])){
        return false;
    }

    return true;
}

function graphina_ajax_reload($callAjax,$new_settings,$type,$mainId){
     ?><script>
            if(typeof getDataForChartsAjax !== "undefined" && '<?php echo $callAjax; ?>' === "1") {
                getDataForChartsAjax(<?php echo json_encode($new_settings); ?>, '<?php echo $type; ?>', '<?php echo $mainId; ?>');
              <?php if (isset($new_settings['iq_' . $type . '_can_chart_reload_ajax']) ? $new_settings['iq_' . $type . '_can_chart_reload_ajax'] : 'no' ==='yes') { ?>
                    let ajaxIntervalTime = parseInt('<?php echo $new_settings['iq_' . $type . '_interval_data_refresh']?>') * 1000;

                    window.ajaxIntervalGraphina_<?php echo $mainId; ?> = setInterval(function () {
                       getDataForChartsAjax(<?php echo json_encode($new_settings); ?>, '<?php echo $type; ?>', '<?php echo $mainId; ?>');
                   }, ajaxIntervalTime);
              <?php  } ?>

            }
        </script>
 <?php
}