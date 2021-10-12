<?php
/**
 * Plugin Name: Jet Appointments Booking
 * Plugin URI:  https://crocoblock.com/plugins/jetappointment/
 * Description: A must-have solution to create forms for booking the appointments.
 * Version:     1.5.7
 * Author:      Crocoblock
 * Author URI:  https://crocoblock.com/
 * Text Domain: jet-appointments-booking
 * License:     GPL-3.0+
 * License URI: http://www.gnu.org/licenses/gpl-3.0.txt
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

add_action( 'plugins_loaded', 'jet_apb_init' );

function jet_apb_init() {

	define( 'JET_APB_VERSION', '1.5.7' );
	define( 'JET_APB__FILE__', __FILE__ );
	define( 'JET_APB_PLUGIN_BASE', plugin_basename( JET_APB__FILE__ ) );
	define( 'JET_APB_PATH', plugin_dir_path( JET_APB__FILE__ ) );
	define( 'JET_APB_URL', plugins_url( '/', JET_APB__FILE__ ) );

	require JET_APB_PATH . 'includes/plugin.php';

}

add_action('plugins_loaded', 'jet_apb_lang');

function jet_apb_lang() {
	load_plugin_textdomain('jet-appointments-booking', false, dirname(plugin_basename(__FILE__)) . '/languages');
}

function jet_apb() {
	return JET_APB\Plugin::instance();
}