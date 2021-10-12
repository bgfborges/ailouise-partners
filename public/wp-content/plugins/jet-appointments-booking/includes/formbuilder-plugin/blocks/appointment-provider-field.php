<?php


namespace JET_APB\Formbuilder_Plugin\Blocks;


use Jet_Form_Builder\Blocks\Types\Base;

class Appointment_Provider_Field extends Base {

	/**
	 * @return string
	 */
	public function get_name() {
		return 'appointment-provider';
	}

	public function get_path_metadata_block() {
		$path_parts = array( 'assets', 'gutenberg', 'src', 'blocks', $this->get_name() );
		$path       = implode( DIRECTORY_SEPARATOR, $path_parts );

		return JET_APB_PATH . $path;
	}

	/**
	 * @param null $wp_block
	 *
	 * @return mixed
	 */
	public function get_block_renderer( $wp_block = null ) {
		return ( new Appointment_Provider_Field_Render( $this ) )->getFieldTemplate();
	}

	public function block_data( $editor, $handle ) {
		$listings = jet_engine()->listings->get_listings_for_options( 'blocks' );
		array_shift( $listings );

		wp_localize_script( $handle, 'JetAppointmentProviderField', array(
			'listings' => $listings
		) );
	}
}