import { label as globalLabel, options } from '../source';

const { __ } = wp.i18n;

const label = {
	appointment_provider_custom_template: __( 'Use Custom Template For Items:', 'jet-appointments-booking' ),
	appointment_provider_custom_template_id: __( 'Custom Template ID:', 'jet-appointments-booking' ),
	switch_on_change: __( 'Switch Page on Change:', 'jet-appointments-booking' ),
	...globalLabel
};


export {
	label,
	options
};

