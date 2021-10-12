import {
	label,
	options,
} from './source';

const {
		  AdvancedFields,
		  GeneralFields,
		  ToolBarFields,
		  FieldWrapper,
		  HorizontalLine,
	  } = JetFBComponents;

const {
		  Tools: { withPlaceholder },
		  getFieldsWithoutCurrent,
	  } = JetFBActions;

const {
		  TextControl,
		  SelectControl,
		  PanelBody,
	  } = wp.components;

const { __ } = wp.i18n;

const {
		  InspectorControls,
		  useBlockProps,
	  } = wp.blockEditor;

const {
		  RawHTML,
	  } = wp.element;

function AppointmentDateEdit( props ) {
	const blockProps = useBlockProps();
	const formFieldsList = getFieldsWithoutCurrent(  '--' );

	const { static_calendar } = window.JetAppointmentDateField;
	const {
			  attributes,
			  setAttributes,
			  isSelected,
			  editProps: { uniqKey },
		  } = props;

	return [
		<ToolBarFields
			key={ uniqKey( 'ToolBarFields' ) }
			{ ...props }
		/>,
		isSelected && <InspectorControls
			key={ uniqKey( 'InspectorControls' ) }
		>
			<GeneralFields
				key={ uniqKey( 'GeneralFields' ) }
				{ ...props }
			/>
			<PanelBody
				title={ __( 'Field Settings' ) }
				key={ uniqKey( 'PanelBody' ) }
			>
				<SelectControl
					label={ label.appointment_service_field }
					labelPosition='top'
					value={ attributes.appointment_service_field }
					onChange={ appointment_service_field => {
						setAttributes( { appointment_service_field } );
					} }
					options={ withPlaceholder( options.appointment_service_field ) }
				/>
				{ 'form_field' === attributes.appointment_service_field && <SelectControl
					label={ label.appointment_form_field }
					labelPosition='top'
					value={ attributes.appointment_form_field }
					onChange={ appointment_form_field => {
						setAttributes( { appointment_form_field } );
					} }
					options={ formFieldsList }
				/> }
				{ 'manual_input' === attributes.appointment_service_field && <TextControl
					label={ label.appointment_service_id }
					value={ attributes.appointment_service_id }
					onChange={ appointment_service_id => {
						setAttributes( { appointment_service_id } );
					} }
				/> }
				<HorizontalLine/>
				<SelectControl
					label={ label.appointment_provider_field }
					labelPosition='top'
					value={ attributes.appointment_provider_field }
					onChange={ appointment_provider_field => {
						setAttributes( { appointment_provider_field } );
					} }
					options={ withPlaceholder( options.appointment_service_field ) }
				/>
				{ 'form_field' === attributes.appointment_provider_field && <SelectControl
					label={ label.appointment_provider_form_field }
					labelPosition='top'
					value={ attributes.appointment_provider_form_field }
					onChange={ appointment_provider_form_field => {
						setAttributes( { appointment_provider_form_field } );
					} }
					options={ formFieldsList }
				/> }
				{ 'manual_input' === attributes.appointment_provider_field && <TextControl
					label={ label.appointment_provider_id }
					value={ attributes.appointment_provider_id }
					onChange={ appointment_provider_id => {
						setAttributes( { appointment_provider_id } );
					} }
				/> }
			</PanelBody>
			<AdvancedFields
				key={ uniqKey( 'AdvancedFields' ) }
				{ ...props }
			/>
		</InspectorControls>,
		<div { ...blockProps } key={ uniqKey( 'viewBlock' ) }>
			<FieldWrapper
				key={ uniqKey( 'FieldWrapper' ) }
				{ ...props }
			>
				<RawHTML>{ static_calendar }</RawHTML>
			</FieldWrapper>
		</div>,
	];
}

export default AppointmentDateEdit;