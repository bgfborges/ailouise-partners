<?php


namespace JET_APB\Formbuilder_Plugin;


use JET_APB\Plugin;

class Form_Manager {

	use Forms_Callable_Trait;

	private static $instance = false;

	public function default_form_data() {
		return array(
			'post_title'  => 'Booking Form',
			'post_type'   => 'jet-form-builder',
			'post_status' => 'publish',
		);
	}

	public function insert_form( $form_type ) {
		$services_cpt = Plugin::instance()->settings->get( 'services_cpt' );
		$providers_cpt = Plugin::instance()->settings->get( 'providers_cpt' );

		$form = $this->prepare_form_data( $form_type, array(
			'services_cpt' => $services_cpt,
			'providers_cpt' => $providers_cpt
		) );

		$post_id = wp_insert_post( $form );

		if ( ! $post_id || is_wp_error( $post_id ) ) {
			return false;
		} else {
			return array(
				'id'    => $post_id,
				'title' => $form['post_title'],
				'link'  => get_edit_post_link( $post_id, 'url' ),
			);
		}
	}

	public function prepare_form_data( $name, $args_user_func = array() ) {
		$map = $this->form_types_map();

		if ( ! isset( $map[ $name ] ) ) {
			return $this->default_form_data();
		}
		$form = call_user_func( $map[ $name ], $args_user_func );

		return array_merge( $this->default_form_data(), $form );
	}

	public static function instance() {
		if ( false === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


}