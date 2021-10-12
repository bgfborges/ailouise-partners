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
		  getFieldsWithoutCurrent
	  } = JetFBActions;

const {
		  TextControl,
		  SelectControl,
		  PanelBody,
		  ToggleControl,
		  RadioControl,
	  } = wp.components;
const { __ } = wp.i18n;

const {
		  InspectorControls,
		  useBlockProps,
	  } = wp.blockEditor;

function AppointmentProviderEdit( props ) {
	const blockProps = useBlockProps();

	const formFieldsList = getFieldsWithoutCurrent(  '--' );
	const source = window.JetAppointmentProviderField;

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
				<ToggleControl
					label={ label.appointment_provider_custom_template }
					checked={ attributes.appointment_provider_custom_template }
					onChange={ appointment_provider_custom_template => setAttributes( {
						appointment_provider_custom_template
					} ) }
				/>
				{ attributes.appointment_provider_custom_template && <SelectControl
					label={ label.appointment_provider_custom_template_id }
					labelPosition='top'
					value={ attributes.appointment_provider_custom_template_id }
					onChange={ appointment_provider_custom_template_id => {
						setAttributes( { appointment_provider_custom_template_id } );
					} }
					options={ withPlaceholder( source.listings ) }
				/> }
				<ToggleControl
					label={ label.switch_on_change }
					checked={ attributes.switch_on_change }
					onChange={ switch_on_change => setAttributes( { switch_on_change } ) }
				/>
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
				<RadioControl
					key={ 'gateways_radio_control' }
					selected=''
					options={ [
						{ label: 'Provider', value: '' },
					] }
					onChange={ () => {} }
				/>
			</FieldWrapper>
		</div>,
	];
}

export default AppointmentProviderEdit;