<?php

namespace Jet_Form_Builder\Blocks\Render;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Define text field renderer class
 */
class Hidden_Field_Render extends Base {

	public function get_name() {
		return 'hidden-field';
	}

	public function render( $wp_block = null, $template = null ) {
		if ( isset( $this->block_type->block_attrs['field_value'] ) ) {
			$this->block_type->block_attrs['field_value'] = $this->block_type->get_field_value( $this->block_type->block_attrs['field_value'] );
		}

		return parent::render();
	}


}
