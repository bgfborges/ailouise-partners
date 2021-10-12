<?php


namespace JET_APB\Vendor\Fields_Core;


trait Smart_Base_Field_Trait {

	protected $_args;
	protected $_builder;

	abstract public function field_template();

	abstract public function isRequired();

	abstract public function getNamespace();

	public function scopeClass( $suffix = '' ) {
		return $this->getNamespace() . $suffix;
	}

	public function isNotEmptyArg( $key ) {
		return ( ! empty( $this->_args[ $key ] ) );
	}

	public function getArgs( $key = '', $ifNotExist = false ) {
		if ( ! $key ) {
			return $this->_args;
		}

		return ! empty( $this->_args[ $key ] ) ? $this->_args[ $key ] : $ifNotExist;
	}

	abstract public function getCustomTemplate( $provider_id, $args );

	public function is_block_editor() {
		$action = ! empty( $_GET['context'] ) ? $_GET['context'] : '';

		if ( isset( $_GET['action'] ) ) {
			$action = $action ? $action : $_GET['action'];
		}

		return in_array( $action, array( 'add', 'edit' ) );
	}

}