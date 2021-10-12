<?php


namespace JET_APB\Formbuilder_Plugin;


trait With_Form_Builder {

	public function __construct() {
		if ( $this->can_init() ) {
			$this->manager_init();
		}
	}

	public function manager_init() {
	}

	public function can_init() {
		return function_exists( 'jet_form_builder' )
		       && version_compare( jet_form_builder()->get_version(), '1.2.4', '>=' );
	}


}