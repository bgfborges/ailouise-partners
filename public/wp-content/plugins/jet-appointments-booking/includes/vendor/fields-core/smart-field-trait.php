<?php


namespace JET_APB\Vendor\Fields_Core;


trait Smart_Field_Trait {

	use Smart_Base_Field_Trait;

	public function getNamespace() {
		return 'jet-form';
	}

	public function getBuilder() {
		return $this->_builder;
	}

	public function isRequired() {
		return $this->getBuilder()->get_required_val( $this->_args );
	}

	public function getCustomTemplate( $provider_id, $args ) {
		return $this->getBuilder()->get_custom_template( $provider_id, $args );
	}

	public function getFieldTemplate( $template, $args, $builder ) {
		$this->_args = $args;
		$this->_builder = $builder;

		return $this->field_template();
	}
}