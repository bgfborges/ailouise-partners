<?php


namespace JET_APB\Formbuilder_Plugin;


use JET_APB\Formbuilder_Plugin\Actions\Action_Manager;
use JET_APB\Formbuilder_Plugin\Blocks\Blocks_Manager;

class Form_Builder {

	const PACKAGE = 'https://downloads.wordpress.org/plugin/jetformbuilder.zip';
	const PLUGIN = 'jetformbuilder/jet-form-builder.php';

	public function __construct() {
		new Action_Manager();
		new Blocks_Manager();
		new Gateway_Manager();
	}

	public static function get_path( $path = '' ) {
		return JET_APB_PATH . '/includes/formbuilder-plugin/' . $path;
	}

	public static function install_and_activate() {
		if ( file_exists( WP_PLUGIN_DIR . '/' . self::PLUGIN ) ) {
			return self::activate_plugin();
		}

		$installed = self::install_plugin();
		if ( $installed['success'] ) {
			$activated = self::activate_plugin();

			if ( $activated['success'] && ! function_exists( 'jet_form_builder' ) ) {
				require_once WP_PLUGIN_DIR . '/' . self::PLUGIN;
			}

			return $activated;
		}

		return $installed;
	}

	public static function activate_plugin() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return [
				'success' => false,
				'message' => esc_html__( 'Sorry, you are not allowed to install plugins on this site.', 'jet-form-builder' ),
				'data'    => [],
			];
		}

		$activate  = null;
		$need_init = false;

		if ( ! is_plugin_active( self::PLUGIN ) ) {
			$activate  = activate_plugin( self::PLUGIN );
			$need_init = true;
		}

		return is_null( $activate ) ? [ 'success' => true, 'need_init' => $need_init ] : [ 'success' => false ];
	}

	public static function install_plugin() {
		if ( ! current_user_can( 'install_plugins' ) ) {
			return [
				'success' => false,
				'message' => esc_html__( 'Sorry, you are not allowed to install plugins on this site.', 'jet-form-builder' ),
				'data'    => [],
			];
		}

		include_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );

		$skin     = new \WP_Ajax_Upgrader_Skin();
		$upgrader = new \Plugin_Upgrader( $skin );
		$result   = $upgrader->install( self::PACKAGE );

		if ( is_wp_error( $result ) ) {
			$status['errorCode']    = $result->get_error_code();
			$status['errorMessage'] = $result->get_error_message();

			return [
				'success' => false,
				'message' => $result->get_error_message(),
				'data'    => [],
			];
		} elseif ( is_wp_error( $skin->result ) ) {
			$status['errorCode']    = $skin->result->get_error_code();
			$status['errorMessage'] = $skin->result->get_error_message();

			return [
				'success' => false,
				'message' => $skin->result->get_error_message(),
				'data'    => [],
			];
		} elseif ( $skin->get_errors()->get_error_code() ) {
			$status['errorMessage'] = $skin->get_error_messages();

			return [
				'success' => false,
				'message' => $skin->get_error_messages(),
				'data'    => [],
			];
		} elseif ( is_null( $result ) ) {
			global $wp_filesystem;

			$status['errorMessage'] = 'Unable to connect to the filesystem. Please confirm your credentials.';

			// Pass through the error from WP_Filesystem if one was raised.
			if ( $wp_filesystem instanceof \WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->get_error_code() ) {
				$status['errorMessage'] = esc_html( $wp_filesystem->errors->get_error_message() );
			}

			return [
				'success' => false,
				'message' => $status['errorMessage'],
				'data'    => [],
			];
		}

		return [
			'success' => true,
			'message' => esc_html__( 'JetFormBuilder has been installed.', 'jet-form-builder' ),
		];
	}

}