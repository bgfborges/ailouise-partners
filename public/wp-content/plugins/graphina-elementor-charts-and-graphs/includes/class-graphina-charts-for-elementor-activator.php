<?php

/**
 * Fired during plugin activation
 *
 * @link       https://iqonic.design
 * @since      1.5.7
 *
 * @package    Graphina_Charts_For_Elementor
 * @subpackage Graphina_Charts_For_Elementor/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.5.7
 * @package    Graphina_Charts_For_Elementor
 * @subpackage Graphina_Charts_For_Elementor/includes
 * @author     Iqonic Design < hello@iqonic.design>
 */
class Graphina_Charts_For_Elementor_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.5.7
	 */
	public static function activate() {
        graphina_plugin_activation();
	}

}
