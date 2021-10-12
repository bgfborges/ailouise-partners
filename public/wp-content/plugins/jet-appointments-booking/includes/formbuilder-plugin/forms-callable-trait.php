<?php

namespace JET_APB\Formbuilder_Plugin;

use JET_APB\Formbuilder_Plugin\Form_Builder as JFB;

trait Forms_Callable_Trait {

	public function form_types_map() {
		return array(
			'create_single_form' => array( $this, 'get_single_form' ),
			'create_page_form' => array( $this, 'get_static_form' )
		);
	}

	public function get_single_form( $args = array() ) {
		if ( $args['providers_cpt'] ) {
			$form = file_get_contents( JFB::get_path( 'forms/single-service-booking-form.json' ) );
		} else {
			$form = file_get_contents( JFB::get_path( 'forms/single-service-booking-form-no-provider.json' ) );
		}

		return json_decode( $form, true );
	}

	public function get_static_form( $args = array() ) {
		if ( $args['providers_cpt'] ) {
			$form = file_get_contents( JFB::get_path( 'forms/static-page-booking-form.json' ) );
		} else {
			$form = file_get_contents( JFB::get_path( 'forms/static-page-booking-form-no-provider.json' ) );
		}
		$form = str_replace( 'service-post-type', $args['services_cpt'], $form );

		return json_decode( $form, true );
	}

}