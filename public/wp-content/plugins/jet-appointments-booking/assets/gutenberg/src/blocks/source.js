const { __ } = wp.i18n;

const options = {
	appointment_service_field: [
		{
			value: 'current_post_id',
			label: __( 'Current Post ID', 'jet-appointments-booking' ),
		},
		{
			value: 'form_field',
			label: __( 'Form Field', 'jet-appointments-booking' ),
		},
		{
			value: 'manual_input',
			label: __( 'Manual Input', 'jet-appointments-booking' )
		}
	],
};

const label = {
	appointment_service_field: __( 'Get Service ID From:', 'jet-appointments-booking' ),
	appointment_form_field: __( 'Select Service Field:', 'jet-appointments-booking' ),
	appointment_service_id: __( 'Set Service ID:', 'jet-appointments-booking' ),
};

export {
	options,
	label
};