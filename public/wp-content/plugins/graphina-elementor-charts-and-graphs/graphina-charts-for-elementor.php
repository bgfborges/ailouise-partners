<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin line. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://iqonic.design
 * @since             1.5.7
 * @package           Graphina_Charts_For_Elementor
 *
 * @wordpress-plugin
 * Plugin Name:       Graphina - Elementor Charts and Graphs
 * Plugin URI:        https://iqonicthemes.com
 * Description:       Your ultimate charts and graphs solution to enhance visual effects. Create versatile, advanced and interactive charts on your website.
 * Version:           1.5.7
 * Author:            Iqonic Design
 * Author URI:        https://iqonic.design/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       graphina-charts-for-elementor
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
if (!defined('GRAPHINA_ROOT'))
    define('GRAPHINA_ROOT', plugin_dir_path(__FILE__));

if (!defined('GRAPHINA_URL'))
    define('GRAPHINA_URL', plugins_url('', __FILE__));

if (!defined('GRAPHINA_BASE_PATH'))
    define('GRAPHINA_BASE_PATH', plugin_basename(__FILE__));

/**
 * Currently plugin version.
 * Start at version 1.5.7 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('GRAPHINA_CHARTS_FOR_ELEMENTOR_VERSION', '1.5.6');

if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
} else {
    die('Something went wrong');
}


/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-graphina-charts-for-elementor-activator.php
 */
function activate_graphina_charts_for_elementor()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-graphina-charts-for-elementor-activator.php';
    Graphina_Charts_For_Elementor_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-graphina-charts-for-elementor-deactivator.php
 */
function deactivate_graphina_charts_for_elementor()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-graphina-charts-for-elementor-deactivator.php';
    Graphina_Charts_For_Elementor_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_graphina_charts_for_elementor');
register_deactivation_hook(__FILE__, 'deactivate_graphina_charts_for_elementor');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */

require plugin_dir_path(__FILE__) . 'includes/class-graphina-charts-for-elementor.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.5.7
 */
function run_graphina_charts_for_elementor() {
	$plugin = new Graphina_Charts_For_Elementor();
	$plugin->run();
}

run_graphina_charts_for_elementor();

function graphina_plugin_settings_link($links){
    $links[] = sprintf('<a href="https://iqonic.design/docs/product/graphina-elementor-charts-and-graphs/getting-started/"  target="_blank">' . esc_html__('Docs', 'graphina-lang') . '</a>');
    if(!isGraphinaProInstall()) {
        $links[] = sprintf('<a href="https://codecanyon.net/item/graphinapro-elementor-dynamic-charts-datatable/28654061" target="_blank" style="font-weight: bold; color: #93003c;">' . esc_html__('Get Pro', 'graphina-lang') . '</a>');
    }

    return $links;
}

$plugin = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin", 'graphina_plugin_settings_link');


add_action('admin_notices', function () {
    graphina_if_failed_load();
});