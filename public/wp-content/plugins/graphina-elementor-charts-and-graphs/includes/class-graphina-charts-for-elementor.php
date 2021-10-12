<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin line.
 *
 * @link       https://iqonic.design
 * @since      1.5.7
 *
 * @package    Graphina_Charts_For_Elementor
 * @subpackage Graphina_Charts_For_Elementor/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.5.7
 * @package    Graphina_Charts_For_Elementor
 * @subpackage Graphina_Charts_For_Elementor/includes
 * @author     Iqonic Design < hello@iqonic.design>
 */
class Graphina_Charts_For_Elementor
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.5.7
     * @access   protected
     * @var      Graphina_Charts_For_Elementor_Loader $loader Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.5.7
     * @access   protected
     * @var      string $plugin_name The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.5.7
     * @access   protected
     * @var      string $version The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin line and
     * the public-facing side of the site.
     *
     * @since    1.5.7
     */
    public function __construct()
    {
        if (defined('GRAPHINA_CHARTS_FOR_ELEMENTOR_VERSION')) {
            $this->version = GRAPHINA_CHARTS_FOR_ELEMENTOR_VERSION;
        } else {
            $this->version = '1.5.6';
        }
        $this->plugin_name = 'graphina-charts-for-elementor';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_elementor_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Graphina_Charts_For_Elementor_Loader. Orchestrates the hooks of the plugin.
     * - Graphina_Charts_For_Elementor_i18n. Defines internationalization functionality.
     * - Graphina_Charts_For_Elementor_Admin. Defines all hooks for the admin line.
     * - Graphina_Charts_For_Elementor_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.5.7
     * @access   private
     */
    private function load_dependencies()
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-graphina-charts-for-elementor-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-graphina-charts-for-elementor-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'elementor/class-graphina-charts-for-elementor-public.php';

        $this->loader = new Graphina_Charts_For_Elementor_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Graphina_Charts_For_Elementor_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.5.7
     * @access   private
     */
    private function set_locale()
    {

        $plugin_i18n = new Graphina_Charts_For_Elementor_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.5.7
     * @access   private
     */
    private function define_elementor_hooks()
    {
        $plugin_public = new Graphina_Charts_For_Elementor_Public($this->get_plugin_name(), $this->get_version());
        $this->loader->add_action('elementor/editor/before_enqueue_scripts', $plugin_public, 'admin_enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_filter('elementor/frontend/builder_content_data', $this, 'get_graphina_loaded_templates', 10, 2);
        $this->loader->add_action('elementor/init', $plugin_public, 'elementor_init');
        $this->loader->add_action('elementor/widgets/widgets_registered', $plugin_public, 'include_widgets');
        $this->loader->add_action('wp_ajax_get_graphina_chart_settings', $plugin_public, 'action_get_graphina_chart_settings');
        $this->loader->add_action('wp_ajax_nopriv_get_graphina_chart_settings', $plugin_public, 'action_get_graphina_chart_settings');
        $this->loader->add_action('wp_ajax_graphina_restrict_password_ajax', $plugin_public, 'action_graphina_restrict_password_ajax');
        $this->loader->add_action('wp_ajax_nopriv_graphina_restrict_password_ajax', $plugin_public, 'action_graphina_restrict_password_ajax');
        $this->loader->add_action('admin_notices', $plugin_public, 'check_required_plugins_for_graphina');
        if (!isGraphinaPro()) {
            $this->loader->add_filter('elementor/editor/localize_settings', $plugin_public, 'promote_pro_elements');
        }
    }

    function get_graphina_loaded_templates($content, $post_id)
    {
        $plugin_public = new Graphina_Charts_For_Elementor_Public($this->get_plugin_name(), $this->get_version());
        $plugin_public->enqueue_scripts($post_id);
        return $content;
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.5.7
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @return    string    The name of the plugin.
     * @since     1.5.7
     */
    public function get_plugin_name()
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @return    Graphina_Charts_For_Elementor_Loader    Orchestrates the hooks of the plugin.
     * @since     1.5.7
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @return    string    The version number of the plugin.
     * @since     1.5.7
     */
    public function get_version()
    {
        return $this->version;
    }

}
