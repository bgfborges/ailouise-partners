import AppointmentDateEdit from "./edit";
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
	title: __( 'Appointment Date' ),
	icon: <span dangerouslySetInnerHTML={ { __html: icon } }></span>,
	edit: AppointmentDateEdit,
	useEditProps: [ 'uniqKey', 'blockName', 'attrHelp' ],
	example: {
		attributes: {
			label: 'Appointment Date',
			desc: 'Field description...',
		},
	},
};

export {
	metadata,
	name,
	settings
};