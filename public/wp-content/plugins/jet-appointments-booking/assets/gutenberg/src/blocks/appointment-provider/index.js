import AppointmentProviderEdit from "./edit";
import metadata from "./block.json";

const { __ } = wp.i18n;

const { name, icon } = metadata;

/**
 * Available items for `useEditProps`:
 *  - uniqKey
 *  - formFields
 *  - blockName
 *  - attrHelp
 */
const settings = {
	title: __( 'Appointment Provider' ),
	icon: <span dangerouslySetInnerHTML={ { __html: icon } }></span>,
	edit: AppointmentProviderEdit,
	useEditProps: [ 'uniqKey', 'blockName', 'attrHelp' ],
	example: {
		attributes: {
			label: 'Appointment Provider',
			desc: 'Field description...',
		},
	},
};

export {
	metadata,
	name,
	settings
};