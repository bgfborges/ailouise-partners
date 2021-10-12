(function () {
	"use strict";

//Mixin jetApbWorkHoursSettings
	var config = ( window.JetAPBConfig ) ? window.JetAPBConfig : window.jetApbPostMeta,
		settings = config.settings || config.custom_schedule,
		jetApbWorkHoursSettings = {
			data: function() {
				return {
					isNewSlot: false,
					editDay: false,
					deleteDayTrigger: null,
					date: {
						start: null,
						startTimeStamp: null,
						end: null,
						endTimeStamp: null,
						name: null,
						type: null,
						editIndex: null,
					},
					datePickerFormat: 'dd-MM-yyyy',
					dateMomentFormat: "DD-MM-YYYY",
					currentDay: null,
					currentFrom: '00:00',
					currentTo: '00:00',
					currentIndex: null,
					deleteSlotTrigger: null,
					disabledDate: {},
					bookingTypes: config.booking_types,
                    weekdays: config.weekdays,
					rebookingOptions: config.rebooking_options,
					slotTimeFormat: config.slot_time_format,
                    settings: {
						...settings
					}
				};
			},
			components: {
				vuejsDatepicker: window.vuejsDatepicker
			},
			methods: {
                checkDefaultValue: function( settyngs = {}, key = '' ){
                	if( settyngs && settyngs[ key ] ){
                        return true;
                    }
                    return false;
                },
				onUpdateSettings: function( valueObject ) {
					this.$set( this.settings, valueObject.key, valueObject.value );

					this.$nextTick( function() {
						this.saveSettings();
					} );
				},
				onUpdateTimeSettings: function( valueObject ) {
					var timeStamp = moment.duration( valueObject.value ).asSeconds();

					if( 'default_slot' === valueObject.key && timeStamp < 60 ){
						this.$CXNotice.add( {
							message: wp.i18n.__( 'The slot duration cannot be less than one minute!', 'jet-appointments-booking' ),
							type: 'error',
							duration: 7000,
						} );

						timeStamp = 60;
					}

					this.$set( this.settings, valueObject.key, timeStamp );

					this.$nextTick( function() {
						this.saveSettings();
					} );
				},
				getTimeSettings: function( key ) {
					var dateObject = moment.duration( parseInt( this.settings[ key ] ), 'seconds' ),
						minutes    = dateObject._data.minutes < 10 ? `0${dateObject._data.minutes}` : dateObject._data.minutes ,
						hours      = dateObject._data.hours < 10 ? `0${dateObject._data.hours}` : dateObject._data.hours ;

					return `${hours}:${minutes}`;
				},
				setTimeSettings: function( valueObject ) {
					this.$set( this, valueObject.key, valueObject.value );
				},
				getSlotTime: function( key ) {
					return ( -1 === this[ key ].search( /^[\d{1}]:/ ) ) ? this[ key ] : '0' + this[ key ] ;
				},
				formatDate: function( date ) {
					return moment( date ).format( this.dateMomentFormat );
				},
				getTimeStamp: function( date ) {
					return moment( date ).valueOf();
				},
				getTimeStamp_time: function( time ) {
					return moment( time, 'hh:mm' ).valueOf();
				},
				handleDayOk: function() {
					if ( ! this.date.start || this.date.startTimeStamp > this.date.endTimeStamp ) {
						this.$CXNotice.add( {
							message: wp.i18n.__( 'Date is not correct', 'jet-appointments-booking' ),
							type: 'error',
							duration: 7000,
						} );

						return;
					}

					let date = Object.assign( {}, this.date ),
						dates = this.settings[ date.type ],
						index = null !== date.editIndex ? date.editIndex : dates.length;

					this.$set( dates, index, date );

					this.updateSetting( date.type, dates );
					this.handleDayCancel();
				},

				handleDayCancel: function() {
					for ( var key in this.date ) {
						this.$set( this.date, key, null );
					}

					this.editDay = false;
				},

				confirmDeleteDay: function( dateObject ) {
					this.deleteDayTrigger = dateObject;
				},

				deleteDay: function( daysType = false , date = false  ) {
					var index = this.settings[ daysType ].indexOf( date );

					this.$delete( this.settings[ daysType ], index );

					this.$nextTick( function() {
						this.saveSettings();
					} );
				},

				showEditDay: function( daysType = false , date = false ) {
					if ( date && daysType ) {
						var index = this.settings[ daysType ].indexOf( date );

						this.date = Object.assign( {}, date );
						this.date.editIndex = index;
					}

					this.updateDisabledDates( daysType, date );

					this.date.type  = daysType;
					this.editDay    = true;
				},

				selectedDate: function( date, daysType ){
                	let dateTimestamp = this.dateToTimestamp( date ),
						formattedDate = this.parseDate( date );

					this.$set( this.date, daysType, formattedDate );
					this.$set( this.date, `${ daysType }TimeStamp`, dateTimestamp );
				},

				updateDisabledDates: function( daysType = false, excludedDate = false ) {
					let newDisabledDates = [],
						daysFrom,
						toFrom,
						_excludedDate = JSON.stringify( excludedDate );

					for ( let date in this.settings[ daysType ] ) {
						if( JSON.stringify( this.settings[ daysType ][ date ] )  === _excludedDate ){
							continue;
						}

						let daysFrom  = moment.unix( this.settings[ daysType ][ date ].startTimeStamp ).utc(),
							toFrom    = moment.unix( this.settings[ daysType ][ date ].endTimeStamp ).utc().add( 1, 'days' ); //plus one day in seconds

						//Fixes datapicker bug. If set by value, the disabled date is shifted by one day.
						if( excludedDate ){
							//minus one day in seconds
							daysFrom.add( -1, 'days' )
						}

						newDisabledDates.push( {
							from: daysFrom.toDate(),
							to: toFrom.toDate(),
						} );
					}

					this.$set( this.disabledDate, 'ranges', newDisabledDates );
				},
				newSlot: function( day ) {
					this.isNewSlot  = true;
					this.currentDay = day;
				},
				editSlot: function( day, slotIndex, daySlot ) {
					this.isNewSlot    = true;
					this.currentDay   = day;
					this.currentFrom  = daySlot.from;
					this.currentTo    = daySlot.to;
					this.currentIndex = slotIndex;
				},
				confirmDeleteSlot: function( day, slotIndex ) {
					this.deleteSlotTrigger = day + '-' + slotIndex;
				},
				deleteSlot: function( day, slotIndex ) {
					var dayData = this.settings.working_hours[ day ] || [];

					this.deleteSlotTrigger = null;

					dayData.splice( slotIndex, 1 );

					this.$set( this.settings.working_hours, day, dayData );

					this.$nextTick( function() {
						this.saveSettings();
					} );

				},
				handleCancel: function() {
					this.currentDay   = null;
					this.currentFrom  = '00:00';
					this.currentTo    = '00:00';
					this.currentIndex = null;
				},
				handleOk: function() {
					if ( this.getTimeStamp_time( this.currentFrom ) >= this.getTimeStamp_time( this.currentTo ) ) {
						this.$CXNotice.add( {
							message: wp.i18n.__( 'Time is not correct', 'jet-appointments-booking' ),
							type: 'error',
							duration: 7000,
						} );

						return;
					}

					var dayData = this.settings.working_hours[ this.currentDay ] || [];

					if ( null === this.currentIndex ) {
						dayData.push( {
							from: this.currentFrom,
							to: this.currentTo,
						} );
					} else {
						dayData.splice( this.currentIndex, 1, {
							from: this.currentFrom,
							to: this.currentTo,
						} );
					}

					this.$set( this.settings.working_hours, this.currentDay, dayData );

					this.$nextTick( function() {
						this.saveSettings();
						this.handleCancel();
					} );

				},
			}
		},
		dateMethods = {
			methods: {
				parseDate: function ( date, fornat = this.dateMomentFormat ) {
					return moment( date ).format( fornat );
				},
				timestampToDate: function ( timestamp, fornat = this.dateMomentFormat ) {
					return moment.unix( timestamp ).utc().format( fornat );
				},
				timeToTimestamp: function ( time, fornat = 'hh:mm' ) {
					return moment( time, fornat ).valueOf() / 1000;
				},
				dateToTimestamp: function ( date ) {
					let timestamp = Date.UTC( date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0 );

					return timestamp;
				},
			}
		};

//Mixin jetApbSettingsPage
	var jetApbSettingsPage = {
		data: function() {
			return {
				postTypes: window.JetAPBConfig.post_types || {},
				settings: window.JetAPBConfig.settings || {}
			};
		},
		methods: {
			updateSetting: function( value, key ) {
				this.$set( this.settings, key, value );

				this.$nextTick( function() {
					this.saveSettings();
				} );
			},
			saveSettings: function( updateDBColumns = false ) {
				var self = this;

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: "json",
					data: {
						action: 'jet_apb_save_settings',
						settings: JSON.stringify( this.settings ),
						update_db_columns: updateDBColumns,
					},
				}).done( function( response ) {

					if ( response.success ) {
						self.$CXNotice.add( {
							message: response.data.message,
							type: 'success',
							duration: 7000,
						} );
					}

					self.savingDBColumns = false;

				} ).fail( function( jqXHR, textStatus, errorThrown ) {

					self.$CXNotice.add( {
						message: errorThrown,
						type: 'error',
						duration: 7000,
					} );

					self.savingDBColumns = false;

				} );
			}
		}
	}

//General Component
	Vue.component( 'jet-apb-general-settings', {
		template: '#jet-dashboard-jet-apb-general-settings',
		mixins: [ jetApbSettingsPage ],
	} );

//Working Hours Component
	Vue.component( 'jet-apb-working-hours-settings', {
		template: '#jet-dashboard-jet-apb-working-hours-settings',
		mixins: [ jetApbSettingsPage, jetApbWorkHoursSettings, dateMethods ],
	} );

//Labels Component
	Vue.component( 'jet-apb-labels-settings', {
		template: '#jet-dashboard-jet-apb-labels-settings',
		mixins: [ jetApbSettingsPage ],
		methods: {
			updateLabel: function( value, key ) {
				this.$set( this.settings.custom_labels, key, value )

				this.$nextTick( function() {
					this.saveSettings();
				} );
			}
		}
	} );

//Advanced Component
	Vue.component( 'jet-apb-advanced-settings', {
		template: '#jet-dashboard-jet-apb-advanced-settings',
		mixins: [ jetApbSettingsPage ],
	} );

//Tools Component
	Vue.component( 'jet-apb-tools-settings', {
		template: '#jet-dashboard-jet-apb-tools-settings',
		mixins: [ jetApbSettingsPage ],
		data: function() {
			return {
				settings: window.JetAPBConfig.settings || {},
				clearingExcluded: false,
				savingDBColumns: false,
			};
		},
		methods: {
			clearExcludedDates: function() {
				var self = this;

				self.clearingExcluded = true;

				jQuery.ajax({
					url: ajaxurl,
					type: 'POST',
					dataType: 'json',
					data: {
						action: 'jet_apb_clear_excluded',
					},
				}).done( function( response ) {
					self.clearingExcluded = false;
					self.$CXNotice.add( {
						message: wp.i18n.__( 'Done!', 'jet-appointments-booking' ),
						type: 'success',
						duration: 7000,
					} );
				} ).fail( function( jqXHR, textStatus, errorThrown ) {
					self.clearingExcluded = false;
					self.$CXNotice.add( {
						message: errorThrown,
						type: 'error',
						duration: 7000,
					} );
				} );

			},
			addNewColumn: function() {
				this.settings.db_columns.push( '' );
			},
			cloneColumn: function( data, index ) {
				var column = this.columnNewName( this.settings.db_columns[ index ], this.settings.db_columns );

				this.$set( this.settings.db_columns, this.settings.db_columns.length, column );
			},
			deleteColumn: function( data, index ) {
				this.settings.db_columns.splice( index, 1 );
			},
			setColumnProp: function( index, column) {
				if( column.search( /[-\s]/gi ) !== -1 ){
					column = column.replace( /[-\s]/gi, '_');

					this.$CXNotice.add( {
						message: wp.i18n.__( 'You cannot use the "-" or "space" character in the table name, it was automatically changed to the "_" character!', 'jet-appointments-booking' ),
						type: 'error',
						duration: 7000,
					} );
				}

				column = column.toLowerCase();

				if( -1 !== jQuery.inArray( column, this.settings.db_columns ) ){
					this.$CXNotice.add( {
						message: wp.i18n.__( 'This column already exists in the table!', 'jet-appointments-booking' ),
						type: 'error',
						duration: 7000,
					} );
					column = this.columnNewName( column, this.settings.db_columns );
				}

				this.$set( this.settings.db_columns, index, column );
			},
			saveDBColumns: function() {
				if ( window.confirm( wp.i18n.__( 'Are you sure? If you change or remove any columns, all data stored in this columns will be lost!', 'jet-appointments-booking' ) ) ) {
					this.savingDBColumns = true;
					this.saveSettings( true );
				}
			},
			columnNewName: function( name, columnArray ){
				var newName = name;

				if( -1 === jQuery.inArray( newName, columnArray ) ){
					return newName;
				}else{
					return this.columnNewName( newName + '_copy', columnArray );
				}
			}
		}
	} );

	Vue.component( 'jet-apb-advanced-settings', {
		template: '#jet-dashboard-jet-apb-advanced-settings',
		mixins: [ jetApbSettingsPage ],
	} );

//Set Up Component
	var setUpEventHub = new Vue()

	Vue.component( 'jet-apb-set-up-working-hours-settings', {
			template: '#jet-dashboard-jet-apb-set-up-working-hours-settings',
			props: {
				workingHoursSettings: {
					type: Object,
					default: {}
				},
			},
			mixins: [ jetApbWorkHoursSettings, dateMethods ],
			mounted: function(){
				this.settings = this.workingHoursSettings;
				this.saveSettings();
			},
			methods: {
				updateSetting: function( value, key ) {
					this.saveSettings();
				},
				saveSettings: function() {
					setUpEventHub.$emit( 'update-settings', this.settings );
				}
			}
		}
	);

	Vue.component(
		'jet-apb-set-up', {
		template: '#jet-dashboard-jet-apb-set-up',
		data: function() {
			return {
				isSet: window.JetAPBConfig.setup.is_set,
				isReset: window.JetAPBConfig.reset.is_reset,
				resetURL: window.JetAPBConfig.reset.reset_url,
				postTypes: window.JetAPBConfig.post_types,
				dbFields: window.JetAPBConfig.db_fields,
				currentStep: 1,
				lastStep: 4,
				loading: false,
				log: false,
                settings: {
                    create_single_form: true,
                    create_page_form:  true,
					form_provider: '',
                    ...settings
                },
				additionalDBColumns: [],
				formProviders: [
					{ label: 'JetFormBuilder', value: 'jfb' },
					{ label: 'JetEngine Forms', value: 'jef' },
				],
				isActiveFormBuilder: window.JetAPBConfig.setup.is_active_form_builder
			}
		},
		mounted: function () {
			this.$nextTick(function () {
				setUpEventHub.$on( 'update-settings', this.updateScheduleSettings );
			})
		},
		methods: {
			updateScheduleSettings: function( scheduleSettings ) {
				var updSettings = Object.assign( {}, this.settings, scheduleSettings );

				this.$set( this, 'settings', updSettings );
			},
			nextStep: function() {

				var self = this;

				if ( 1 === self.currentStep ) {

					if ( ! self.settings.services_cpt ) {

						self.$CXNotice.add( {
							message: 'Please select post type for provided services.',
							type: 'error',
							duration: 7000,
						} );

						return;
					}

					if ( self.settings.add_providers && ! self.settings.providers_cpt ) {

						self.$CXNotice.add( {
							message: 'Please select post type for service providers.',
							type: 'error',
							duration: 7000,
						} );

						return;

					}

				}

				if ( self.currentStep === self.lastStep ) {

					self.loading = true;

					jQuery.ajax({
						url: ajaxurl,
						type: 'POST',
						dataType: 'json',
						data: {
							action: 'jet_apb_setup',
							setup_data: self.settings,
							db_columns: self.settings.db_columns,
						},
					}).done( function( response ) {
						self.loading = false;

						if ( response.success ) {
							self.currentStep++;
							self.log = response.data;
						}
					} ).fail( function( jqXHR, textStatus, errorThrown ) {
						self.loading = false;

						self.$CXNotice.add( {
							message: errorThrown,
							type: 'error',
							duration: 7000,
						} );
					} );

				} else {
					self.currentStep++;
				}

			},
			prevStep: function() {
				if ( 1 < this.currentStep ) {
					this.currentStep--;
				}
			},
			addNewColumn: function( event ) {

				var col = {
					column: '',
					collapsed: false,
				};

				this.settings.db_columns.push( col );

			},
			setColumnProp: function( index, key, value ) {
				if( value.search( /[-\s]/gi ) !== -1 ){
					value = value.replace( /[-\s]/gi, '_');

					this.$CXNotice.add( {
						message: wp.i18n.__( 'You cannot use the "-" or "space" character in the table name, it was automatically changed to the "_" character!', 'jet-appointments-booking' ),
						type: 'error',
						duration: 7000,
					} );
				}

				value = value.toLowerCase();

				var double = jQuery.grep( this.settings.db_columns, function( item ){ return item.column === value; } );

				if ( ! double[0] ) {
					this.settings.db_columns[ index ][ key ] = value;
				}else{
					this.settings.db_columns[ index ][ key ] = this.columnNewName( value, this.settings.db_columns );
					this.$CXNotice.add( {
						message: 'This column already exists in the table!',
						type: 'error',
						duration: 7000,
					} );
				}
			},
			cloneColumn: function( index ) {
				var col    = this.settings.db_columns[ index ],
					newCol = {
						'column': col.column + '_copy',
					};

				this.settings.db_columns.splice( index + 1, 0, newCol );

			},
			deleteColumn: function( index ) {
				this.settings.db_columns.splice( index, 1 );
			},
			isCollapsed: function( object ) {
				if ( undefined === object.collapsed || true === object.collapsed ) {
					return true;
				} else {
					return false;
				}
			},
			goToReset: function() {
				if ( confirm( 'Are you sure? All previously booked appoinments will be removed!' ) ) {
					window.location = this.resetURL;
				}
			},
			columnNewName: function( name, columnArray ){
				var double = jQuery.grep( columnArray, function( item ){ return item.column === name; } );

				if( ! double[0] ){
					return name;
				}else{
					return this.columnNewName( name + '_copy', columnArray );
				}
			}
		}
	});

//Custom Schedule Meta Box
	if( document.getElementById('jet-apb-custom-schedule-meta-box') ){
		var metaBoxEventHub = new Vue();

		Vue.component( 'jet-apb-working-hours-meta-box', {
				template: '#jet-apb-settings-working-hours',
				mixins: [ jetApbWorkHoursSettings, dateMethods ],
				mounted: function () {
					if( ! this.settings.working_days ){
						this.settings.working_days = []
					}

					if( ! this.settings.days_off ){
						this.settings.days_off = []
					}

					if( ! this.settings.working_hours ){
						this.settings.working_hours = []
					}
				},
				methods: {
					updateSetting: function( value, key ) {
						this.saveSettings();
					},
					saveSettings: function() {
						metaBoxEventHub.$emit( 'update-settings', this.settings );
					}
				}
			}
		);

		new Vue( {
			el: '#jet-apb-custom-schedule-meta-box',
			data: function() {
				return {
					settings: window.jetApbPostMeta,
				}
			},
			mounted: function () {
				this.$nextTick(function () {
					metaBoxEventHub.$on( 'update-settings', this.updateScheduleSettings );
				})
			},
			methods: {
				updateScheduleSettings: function( scheduleSettings ) {
					var updSettings = Object.assign( this.settings.custom_schedule, scheduleSettings );

					this.$nextTick(function () {
						this.$set( this.settings, 'custom_schedule', updSettings );
						this.saveSettings();
					})
				},
				updateSetting: function( value, key ) {
					this.$set( this.settings['custom_schedule'], key, value );

					this.$nextTick( function() {
						this.saveSettings();
					} );

				},
				saveSettings: function() {
					var self = this;

					jQuery.ajax({
						url: ajaxurl,
						type: 'POST',
						dataType: 'json',
						data: {
							action: 'jet_apb_save_post_meta',
							jet_apb_post_meta: self.settings,
						},
					}).done( function( response ) {

						if ( response.success ) {
							self.$CXNotice.add( {
								message: response.data.message,
								type: 'success',
								duration: 7000,
							} );
						}

					} ).fail( function( jqXHR, textStatus, errorThrown ) {

						self.$CXNotice.add( {
							message: errorThrown,
							type: 'error',
							duration: 7000,
						} );

					} );
				}
			}
		});
	}

})();