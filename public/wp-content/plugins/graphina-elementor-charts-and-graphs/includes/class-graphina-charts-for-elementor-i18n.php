<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://iqonic.design
 * @since      1.5.7
 *
 * @package    Graphina_Charts_For_Elementor
 * @subpackage Graphina_Charts_For_Elementor/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.5.7
 * @package    Graphina_Charts_For_Elementor
 * @subpackage Graphina_Charts_For_Elementor/includes
 * @author     Iqonic Design < hello@iqonic.design>
 */
class Graphina_Charts_For_Elementor_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.5.7
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'graphina-charts-for-elementor',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
