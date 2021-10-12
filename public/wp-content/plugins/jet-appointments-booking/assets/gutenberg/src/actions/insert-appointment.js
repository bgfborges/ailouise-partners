const {
		  SelectControl,
		  TextControl,
		  BaseControl,
		  Button,
		  Notice,
	  } = wp.components;

const {
		  useState,
		  useEffect,
	  } = wp.element;

const {
		  addAction,
		  getFormFieldsBlocks,
	  } = JetFBActions;

const {
		  ActionFieldsMap,
		  WrapperRequiredControl,
		  RepeaterWithState,
		  ActionModal,
	  } = JetFBComponents;

const addNewOption = {
	type: '',
	label: '',
	format: '',
	field: '',
	link_label: '',
};

const withManualInput = fieldsList => {
	return [ ...fieldsList, { value: '_manual_input', label: 'Manual Input' } ];
};

addAction( 'insert_appointment', function InsertAppointment( {
																 settings,
																 source,
																 label,
																 help,
																 onChangeSetting,
															 } ) {

	const [ formFieldsList, setFormFieldsList ] = useState( [] );
	const [ columnsMap, setColumnsMap ] = useState( [] );
	const [ wcFields, setWcFields ] = useState( [] );
	const [ wcDetailsModal, setWcDetailsModal ] = useState( false );
	const [ isLoading, setLoading ] = useState( false );

	useEffect( () => {
		const columnsMap = {};
		source.columns.forEach( col => {
			columnsMap[ col ] = { label: col };
		} );

		const wcColumnsMap = {};
		source.wc_fields.forEach( col => {
			wcColumnsMap[ col ] = { label: col };
		} );

		setColumnsMap( Object.entries( columnsMap ) );
		setWcFields( Object.entries( wcColumnsMap ) );
		setFormFieldsList( getFormFieldsBlocks( [], '--' ) );
	}, [] );

	function saveWCDetails( items ) {
		setLoading( true );

		jQuery.ajax( {
			url: window.ajaxurl,
			type: 'POST',
			dataType: 'json',
			data: {
				action: 'jet_appointments_save_wc_details',
				post_id: source.apartment,
				nonce: source.nonce,
				details: items,
			},
		} ).done( function( response ) {
			setLoading( false );

			if ( ! response.success ) {
				alert( response.data.message );
			} else {
				JetAppointmentActionData.details = items;
				setWcDetailsModal( false );
			}

		} ).fail( function( jqXHR, textStatus, errorThrown ) {
			setLoading( false );
			alert( errorThrown );
		} );
	}

	function formatDocsLink() {
		return <a href="https://codex.wordpress.org/Formatting_Date_and_Time" target="_blank">
			Formatting docs
		</a>;
	}

	return <>
		<SelectControl
			label={ label( 'appointment_service_field' ) }
			labelPosition='side'
			value={ settings.appointment_service_field }
			onChange={ val => onChangeSetting( val, 'appointment_service_field' ) }
			options={ withManualInput( formFieldsList ) }
		/>
		{ '_manual_input' === settings.appointment_service_field && <>
			<TextControl
				label={ label( 'appointment_service_manual' ) }
				value={ settings.appointment_service_id }
				onChange={ val => onChangeSetting( val, 'appointment_service_id' ) }
			/>
		</> }
		<SelectControl
			label={ label( 'appointment_provider_field' ) }
			labelPosition='side'
			value={ settings.appointment_provider_field }
			onChange={ val => onChangeSetting( val, 'appointment_provider_field' ) }
			options={ withManualInput( formFieldsList ) }
		/>
		{ '_manual_input' === settings.appointment_provider_field && <>
			<TextControl
				label={ label( 'appointment_provider_manual' ) }
				value={ settings.appointment_provider_id }
				onChange={ val => onChangeSetting( val, 'appointment_provider_id' ) }
			/>
		</> }
		<SelectControl
			label={ label( 'appointment_date_field' ) }
			labelPosition='side'
			value={ settings.appointment_date_field }
			onChange={ val => onChangeSetting( val, 'appointment_date_field' ) }
			options={ formFieldsList }
		/>
		<SelectControl
			label={ label( 'appointment_email_field' ) }
			labelPosition='side'
			value={ settings.appointment_email_field }
			onChange={ val => onChangeSetting( val, 'appointment_email_field' ) }
			options={ formFieldsList }
		/>
		{ Boolean( columnsMap.length ) && <ActionFieldsMap
			label={ label( 'db_columns_map' ) }
			fields={ columnsMap }
		>
			{ ( { fieldId, fieldData, index } ) => <WrapperRequiredControl
				field={ [ fieldId, fieldData ] }
			>
				<TextControl
					key={ fieldId + index }
					value={ settings[ `appointment_custom_field_${ fieldId }` ] }
					onChange={ val => onChangeSetting( val, `appointment_custom_field_${ fieldId }` ) }
				/>
			</WrapperRequiredControl> }
		</ActionFieldsMap> }
		{ Boolean( source.wc_integration ) && <>
			<SelectControl
				label={ label( 'appointment_wc_price' ) }
				help={ help( 'appointment_wc_price' ) }
				labelPosition='side'
				value={ settings.appointment_wc_price }
				onChange={ val => onChangeSetting( val, 'appointment_wc_price' ) }
				options={ formFieldsList }
			/>
			<BaseControl
				label={ label( 'wc_order_details' ) }
				help={ help( 'wc_order_details' ) }
			>
				<Button
					isSecondary
					onClick={ () => setWcDetailsModal( true ) }
				>{ 'Set up' }</Button>
			</BaseControl>
			<ActionFieldsMap
				label={ label( 'wc_fields_map' ) }
				fields={ wcFields }
				plainHelp={ help( 'wc_fields_map' ) }
			>
				{ ( { fieldId, fieldData, index } ) => <WrapperRequiredControl
					field={ [ fieldId, fieldData ] }
				>
					<SelectControl
						key={ fieldId + index }
						labelPosition='side'
						value={ settings[ `wc_fields_map__${ fieldId }` ] }
						onChange={ val => onChangeSetting( val, `wc_fields_map__${ fieldId }` ) }
						options={ formFieldsList }
					/>
				</WrapperRequiredControl> }
			</ActionFieldsMap>
			{ wcDetailsModal && <ActionModal
				title={ 'Set up WooCommerce order details' }
				onRequestClose={ () => setWcDetailsModal( false ) }
				classNames={ [ 'width-60' ] }
				style={ { opacity: isLoading ? '0.5' : '1' } }
				updateBtnProps={ { isBusy: isLoading } }
			>
				{ ( { actionClick, onRequestClose } ) => <RepeaterWithState
					items={ source.details }
					onSaveItems={ saveWCDetails }
					newItem={ addNewOption }
					onUnMount={ () => {
						if ( ! actionClick ) {
							onRequestClose();
						}
					} }
					isSaveAction={ actionClick }
					addNewButtonLabel={ isLoading ? 'Saving...' : 'Add new item +' }
				>
					{ ( { currentItem, changeCurrentItem } ) => {
						return <>
							<SelectControl
								label={ label( 'wc_details__type' ) }
								labelPosition='side'
								value={ currentItem.type }
								onChange={ type => changeCurrentItem( { type } ) }
								options={ source.details_types }
							/>
							<TextControl
								label={ label( 'wc_details__label' ) }
								value={ currentItem.label }
								onChange={ label => changeCurrentItem( { label } ) }
							/>
							{ 'date' === currentItem.type && <>
								<TextControl
									label={ label( 'wc_details__date_format' ) }
									value={ currentItem.date_format }
									onChange={ date_format => changeCurrentItem( { date_format } ) }
								/>
								{ formatDocsLink() }
							</> }
							{ [ 'slot', 'slot_end', 'start_end_time' ].includes( currentItem.type ) && <>
								<TextControl
									label={ label( 'wc_details__time_format' ) }
									value={ currentItem.time_format }
									onChange={ time_format => changeCurrentItem( { time_format } ) }
								/>
								{ formatDocsLink() }
							</> }
							{ 'date_time' === currentItem.type && <>
								<TextControl
									label={ label( 'wc_details__date_format' ) }
									value={ currentItem.date_format }
									onChange={ date_format => changeCurrentItem( { date_format } ) }
								/>
								<TextControl
									label={ label( 'wc_details__time_format' ) }
									value={ currentItem.time_format }
									onChange={ time_format => changeCurrentItem( { time_format } ) }
								/>
								{ formatDocsLink() }
							</> }
							{ 'field' === currentItem.type && <SelectControl
								label={ label( 'wc_details__field' ) }
								labelPosition='side'
								value={ currentItem.field }
								onChange={ field => changeCurrentItem( { field } ) }
								options={ formFieldsList }
							/> }
							{ 'add_to_calendar' === currentItem.type && <TextControl
								label={ label( 'wc_details__link_label' ) }
								value={ currentItem.link_label }
								onChange={ link_label => changeCurrentItem( { link_label } ) }
							/> }
						</>;
					} }
				</RepeaterWithState> }
			</ActionModal> }
		</> }
	</>;
} );