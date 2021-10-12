import { label as globalLabel, options } from '../source';

const { __ } = wp.i18n;

const label = {
	appointment_provider_field: __( 'Get Provider ID From:', 'jet-appointments-booking' ),
	appointment_provider_form_field: __( 'Select Provider Field:', 'jet-appointments-booking' ),
	appointment_provider_id: __( 'Set Provider ID:', 'jet-appointments-booking' ),
	...globalLabel
};


export {
	label,
	options,
};

