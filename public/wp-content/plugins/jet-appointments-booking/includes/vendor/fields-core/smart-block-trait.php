<?php


namespace JET_APB\Vendor\Fields_Core;

use Jet_Form_Builder\Blocks\Types\Base as Block_Base;

/**
 * @property Block_Base block_type
 * @method render( $wp_block = null, $template = null )
 * @method get_custom_template( $object_id, $args, $checked = false )
 *
 * Trait Smart_Block_Trait
 * @package JET_APB\Vendor\Fields_Core
 */
trait Smart_Block_Trait {

	use Smart_Base_Field_Trait;

	public function getNamespace() {
		return 'jet-form-builder';
	}

	public function getBuilder() {
		return $this;
	}

	public function isRequired() {
		return $this->block_type->get_required_val();
	}

	public function getCustomTemplate( $provider_id, $args ) {
		return $this->get_custom_template( $provider_id, $args );
	}

	public function getFieldTemplate() {
		$this->_args = $this->block_type->block_attrs;

		return $this->render( null, $this->field_template() );
	}


}