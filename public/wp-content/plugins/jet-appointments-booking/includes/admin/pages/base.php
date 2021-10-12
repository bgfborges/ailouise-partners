<?php
namespace JET_APB\Admin\Pages;

/**
 * Base dashboard page
 */
abstract class Base {

	/**
	 * Page slug
	 * @return string
	 */
	abstract public function slug();

	/**
	 * Page title
	 * @return string
	 */
	abstract public function title();

	/**
	 * Page render funciton
	 * @return void
	 */
	abstract public function render();

	/**
	 * Return page config array
	 *
	 * @return [type] [description]
	 */
	abstract public function page_config();

	/**
	 * Page specific assets
	 *
	 * @return [type] [description]
	 */
	public function assets() {
	}

	/**
	 * Check if is setup page
	 *
	 * @return boolean [description]
	 */
	public function is_setup_page() {
		return false;
	}

	/**
	 * Check if is settings page
	 *
	 * @return boolean [description]
	 */
	public function is_settings_page() {
		return false;
	}

	/**
	 * Page components templates
	 *
	 * @return [type] [description]
	 */
	public function vue_templates() {
		return array();
	}

	/**
	 * Render vue templates
	 *
	 * @return [type] [description]
	 */
	public function render_vue_templates() {
		foreach ( $this->vue_templates() as $template ) {
			if ( is_array( $template ) ) {
				$this->render_vue_template( $template['file'], $template['dir'] );
			} else {
				$this->render_vue_template( $template );
			}
		}
	}

	/**
	 * Render vue template
	 *
	 * @return [type] [description]
	 */
	public function render_vue_template( $template, $path = null ) {

		if ( ! $path ) {
			$path = $this->slug();
		}

		$file = JET_APB_PATH . 'templates/admin/' . $path . '/' . $template . '.php';

		if ( ! is_readable( $file ) ) {
			return;
		}

		ob_start();
		include $file;
		$content = ob_get_clean();

		printf(
			'<script type="text/x-template" id="jet-apb-%1$s">%2$s</script>',
			$template,
			$content
		);

	}

	/**
	 * Enqueue script
	 *
	 * @param  [type] $handle    [description]
	 * @param  [type] $file_path [description]
	 * @return [type]            [description]
	 */
	public function enqueue_script( $handle = null, $file_path = null ) {

		wp_enqueue_script(
			$handle,
			JET_APB_URL . 'assets/js/' . $file_path,
			array( 'wp-api-fetch', 'wp-i18n' ),
			JET_APB_VERSION,
			true
		);

	}

	/**
	 * Enqueue style
	 *
	 * @param  [type] $handle    [description]
	 * @param  [type] $file_path [description]
	 * @return [type]            [description]
	 */
	public function enqueue_style( $handle = null, $file_path = null ) {

		wp_enqueue_style(
			$handle,
			JET_APB_URL . 'assets/css/' . $file_path,
			array(),
			JET_APB_VERSION
		);

	}

	/**
	 * Set to true to hide page from admin menu
	 * @return boolean [description]
	 */
	public function is_hidden() {
		return false;
	}

	/**
	 * Returns current page url
	 *
	 * @return [type] [description]
	 */
	public function get_url() {
		return add_query_arg(
			array( 'page' => $this->slug() ),
			esc_url( admin_url( 'admin.php' ) )
		);
	}

}