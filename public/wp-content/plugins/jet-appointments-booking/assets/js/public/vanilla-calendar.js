/*
	Vanilla AutoComplete v0.1
	Copyright (c) 2019 Mauro Marssola
	GitHub: https://github.com/marssola/jet-apb-calendar
	License: http://www.opensource.org/licenses/mit-license.php
*/
var VanillaCalendar = (function () {

	"use strict";

	function VanillaCalendar( options ) {
		var opts = {
			selector: null,
			pastDates: true,
			availableWeekDays: [],
			excludedDates: [],
			worksDates: [],
			date: new Date(),
			today: null,
			button_prev: null,
			button_next: null,
			month: null,
			month_label: null,
			weekDays: [],
			weekStart: 0,
			service: 0,
			provider: 0,
			providerIsset: false,
			api: '',
			inputName: '',
			isRequired: false,
			allowedServices: false,
			services: false,
			providers: false,
			onSelect: function( data, elem ) {},
			months: [ 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' ],
			shortWeekday: [ 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ],
			namespace: ''
		};

		opts.today = Date.UTC( opts.date.getFullYear(), opts.date.getMonth(), opts.date.getDate(), 0, 0, 0 ) / 1000;

		var xhr              = null;
		var initialized      = false;
		var instance         = null;
		var instanceInput    = null;
		var serviceID        = null;
		var serviceField     = null;
		var providerID       = null;
		var providerField    = null;
		var multiBooking     = options.multiBooking ? options.multiBooking : false ;
		var notification     = null;
		var notificationHTML = '';
		var appListWrapper   = null;

		for ( var k in options ) {
			if ( opts.hasOwnProperty( k ) ) {
				opts[ k ] = options[ k ];
			}
		}

		opts.weekStart = parseInt( opts.weekStart, 10 );

		instance = document.querySelector( opts.selector );

		if ( ! instance ) {
			return;
		}
		
		const setNotification = function( inst, html = '' ) {
			if ( ! inst ) {
				return;
			}

			let parent = inst.parentElement,
				notificationInstance = parent.querySelector( '.jet-apb-calendar-notification' ),
				output = notificationInstance ? notificationInstance.outerHTML : html ;

			notificationInstance.remove();

			return output;
		};


		const addEvent = function( el, type, handler ) {

			if ( ! el ) {
				return;
			}

			if ( el.attachEvent ) {
				el.attachEvent( 'on' + type, handler );
			} else {
				el.addEventListener( type, handler );
			}

		};

		const removeEvent = function( el, type, handler ){

			if ( ! el ) {
				return;
			}

			if ( el.detachEvent ) {
				el.detachEvent( 'on' + type, handler );
			} else {
				el.removeEventListener( type, handler );
			}
		};

		const getWeekDay = function ( day ) {
			return opts.weekDays[ day ];
		};

		const adjustWeekDay = function( day ) {

			day = day - opts.weekStart;

			if ( 0 > day ) {
				return day + 7;
			} else {
				return day;
			}

		};

		const setDayAvailability = function( el, timestamp, weekDay ) {
			var isAvailable = true;

			timestamp = timestamp || parseInt( el.dataset.calendarDate, 10 );
			weekDay = weekDay || el.dataset.weekDay;

			if ( opts.worksDates.length ) {
				for ( var dates in opts.worksDates ) {
					if ( timestamp >= opts.worksDates[dates].start && timestamp <= opts.worksDates[dates].end ){
						isAvailable = true;
						break;
					}else{
						isAvailable = false;
					}
				}
			}

			if ( opts.excludedDates.length ) {
				for ( var dates in opts.excludedDates ) {
					if ( timestamp >= opts.excludedDates[dates].start && timestamp <= opts.excludedDates[dates].end ){
						isAvailable = false;
						break;
					}
				}
			}

			if ( ! weekDay || ( 0 > opts.availableWeekDays.indexOf( weekDay ) ) ) {
				isAvailable = false;
			}

			el.classList.remove( 'jet-apb-calendar-date--disabled' );

			if ( timestamp <= opts.today - 1 && ! opts.pastDates ) {
				el.classList.add( 'jet-apb-calendar-date--disabled' );
			} else {

				if ( ! isAvailable ) {
					el.classList.add( 'jet-apb-calendar-date--disabled' );
				}

				el.setAttribute( 'data-status', isAvailable );

			}

		};

		const createDay = function( date ) {

			var newDayElem     = document.createElement( 'div' );
			var newDayBody     = document.createElement( 'div' );
			var weekDayNum     = adjustWeekDay( date.getDay() );
			var currentWeekDay = getWeekDay( date.getDay() );
			var timestamp      = Date.UTC( date.getFullYear(), date.getMonth(), date.getDate(), 0, 0, 0 );

			timestamp = timestamp / 1000;

			newDayElem.className = 'jet-apb-calendar-date';

			if ( date.getDate() === 1 ) {
				newDayElem.style.marginLeft = ( weekDayNum * 14.28 ) + '%';
			}

			setDayAvailability( newDayElem, timestamp, currentWeekDay );

			newDayElem.setAttribute( 'data-week-day', currentWeekDay );
			newDayElem.setAttribute( 'data-calendar-date', timestamp );

			if ( timestamp === opts.today ) {
				newDayElem.classList.add( 'jet-apb-calendar-date--today' );
			}

			newDayBody.innerHTML = date.getDate();
			newDayBody.className = 'jet-apb-calendar-date-body';

			newDayElem.appendChild( newDayBody );
			opts.month.appendChild( newDayElem );

			if ( 6 === weekDayNum ) {
				opts.month.appendChild( getNewSlotsWrapper() );
			}

		};

		const getNewSlotsWrapper = function() {

			var slotsEl = document.createElement( 'div' );

			slotsEl.className = 'jet-apb-calendar-slots';

			return slotsEl;

		};

		const removeActiveClass = function() {

			instance.querySelectorAll( '.jet-apb-calendar-date--selected' ).forEach( function( el ) {
				el.classList.remove( 'jet-apb-calendar-date--selected' );
			} );

			instance.querySelectorAll( '.jet-apb-calendar-slots' ).forEach( function( el ) {
				el.classList.remove( 'jet-apb-calendar-slots--active' );
				el.innerHTML = '';
			} );

			if( ! multiBooking ){
				instanceInput.val( '' ).data( 'price', 0 ).trigger( 'change.JetEngine' );
			}

		};

		const selectDate = function( el ) {

			removeActiveClass();
			el.classList.add( 'jet-apb-calendar-date--selected' );

			var slot     = getNextSlot( el ),
				service  = null,
				provider = null,
				datenow  = new Date();

			if ( ! slot ) {
				return;
			}

			slot.classList.add( 'jet-apb-calendar-slots--loading' );
			instance.classList.add( 'jet-apb-calendar--loading' );

			if ( xhr ) {
				xhr.abort();
			}
			if ( opts.service.id ) {
				service = opts.service.id;
			} else if( opts.service.field ) {
				serviceField = document.querySelectorAll( 'input[name="' + opts.service.field + '"]' );

				if ( 1 === serviceField.length ) {
					if ( serviceField[0].value ) {
						serviceID = serviceField[0].value;
					}
				} else if ( 1 < serviceField.length ) {
					for ( var i = 0; i < serviceField.length; i++ ) {
						if ( serviceField[ i ].checked ) {
							serviceID = serviceField[ i ].value;
						}
					};
				}
				service = serviceID;
			} else {
				service = serviceID;
			}

			if ( opts.provider.id ) {
				provider = opts.provider.id;
			} else {
				provider = providerID;
			}

			if ( ! service ) {
				showNotification( 'notification-service' );

				slot.classList.remove( 'jet-apb-calendar-slots--loading' );
				instance.classList.remove( 'jet-apb-calendar--loading' );
				return;
			}

			if ( opts.provider.field && ! providerID ) {

				if ( ! window.elementorFrontend || ! window.elementorFrontend.isEditMode() ) {
					showNotification( 'notification-provider' );
					return;
				}

				slot.classList.remove( 'jet-apb-calendar-slots--loading' );
				instance.classList.remove( 'jet-apb-calendar--loading' );

				if ( ! window.elementorFrontend || ! window.elementorFrontend.isEditMode() ) {
					return;
				}

			}

			xhr = jQuery.ajax({
				url: opts.api.date_slots,
				type: 'POST',
				dataType: 'json',
				data: {
					service: service,
					provider: provider,
					date: el.dataset.calendarDate,
					selected_slots: multiBooking ? instanceInput.val() : '' ,
					timestamp: Math.floor( ( datenow.getTime() - datenow.getTimezoneOffset() * 60 * 1000 ) / 1000 ),
				},
			}).done( function( response ) {
				xhr = false;
				if( response ){
					setSlots( slot, response.data, instance )
				}

				instance.classList.remove( 'jet-apb-calendar--loading' );
			} );

		};

		const showNotification = function ( notificationClass = '' ){

			if( ! notificationClass ){
				return;
			}

			notification.classList.add( notificationClass );
			notification.style.display = 'flex';
			setTimeout(function(){
				notification.classList.remove( notificationClass );
				notification.style.display = 'none';
			}, 2000);
		}

		const setSlots = function( slotsWrapper, data, inst ) {
			let slotsEvent,
				{ slots } = data;

			slotsWrapper.classList.remove( 'jet-apb-calendar-slots--loading' );
			slotsWrapper.classList.add( 'jet-apb-calendar-slots--active' );
			slotsWrapper.innerHTML = slots;

			slotsEvent = new CustomEvent( 'jet-apb-calendar-slots--loaded', { el: slotsWrapper, slotHtml: slots } );

			window.dispatchEvent( slotsEvent );
		};

		const updateAppointmentList = function() {
			let selectedSlots = instanceInput.val() ? JSON.parse( instanceInput.val() ) : [],
				slot,outputHTML   = '',
				wrapperVisibility = selectedSlots.length ? 'flex' : 'none',
				serviceName, providerName;

			for ( const slotIndex in selectedSlots ) {
				slot = selectedSlots[ slotIndex ];
				serviceName  = ! opts.services ? '' : opts.services[slot.service] ;
				providerName = ! opts.provider ? '' : ' - ' + opts.providers[slot.provider] ;

				outputHTML += `
					<div class="jet-apb-appointments-item">
						<div class="jet-apb-item-service-provider">${ serviceName } ${ providerName }</div>
						<div class="jet-apb-item-time">${ slot.friendlyDate }</div>
						<div class="jet-apb-item-date">${ slot.friendlyTime }</div>
						<span class="jet-apb-calendar-slot__delete" data-slot-index="${ slotIndex }"><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.23529 0L0 1.23529L5.76477 7.00007L0.000132676 12.7647L1.23543 14L7.00007 8.23536L12.7647 14L14 12.7647L8.23536 7.00007L14.0001 1.23529L12.7648 0L7.00007 5.76477L1.23529 0Z" fill="#8A8B8D"/></svg><span>
					</div>`;
			}

			appListWrapper.querySelector( '.jet-apb-calendar-appointments-list' ).innerHTML = outputHTML;
			appListWrapper.style.display = wrapperVisibility;
		}

		const getNextSlot = function( el ) {

			var nextEl = el.nextSibling;

			if ( ! nextEl ) {
				return null;
			}

			if ( nextEl.classList.contains( 'jet-apb-calendar-slots' ) ) {
				return nextEl;
			} else {
				return getNextSlot( nextEl );
			}

		};

		const createMonth = function () {
			clearCalendar();
			var currentMonth = opts.date.getMonth();

			while ( opts.date.getMonth() === currentMonth ) {
				createDay( opts.date );
				opts.date.setDate( opts.date.getDate() + 1 );
			}

			opts.month.appendChild( getNewSlotsWrapper() );

			opts.date.setDate( 1 );
			opts.date.setMonth( opts.date.getMonth() -1 );
			opts.month_label.innerHTML = opts.months[ opts.date.getMonth() ] + ' ' + opts.date.getFullYear();

		};

		const monthPrev = function () {
			opts.date.setMonth( opts.date.getMonth() - 1 );
			createMonth();
		}

		const monthNext = function () {
			opts.date.setMonth( opts.date.getMonth() + 1 );
			createMonth();
		}

		const clearCalendar = function () {
			opts.month.innerHTML = ''
		}

		const createInputs = function() {

			instanceInput = document.createElement( 'input' );

			instanceInput.setAttribute( 'type', 'hidden' );
			instanceInput.setAttribute( 'name', opts.inputName );
			instanceInput.setAttribute( 'data-price', '0' );
			instanceInput.setAttribute( 'data-field', 'appointment' );
			instanceInput.classList.add( 'jet-form__field' );
			instanceInput.classList.add( withNamespace( '__field' ) );

			if ( opts.isRequired ) {
				instanceInput.setAttribute( 'required', true );
			}
			instance.appendChild( instanceInput );

			instanceInput = jQuery( instanceInput );
		};

		const createCalendar = function () {
			instance.innerHTML = notificationHTML + `
			<div class="jet-apb-calendar-header">
				<button type="button" class="jet-apb-calendar-btn" data-calendar-toggle="previous"><svg height="24" version="1.1" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M20,11V13H8L13.5,18.5L12.08,19.92L4.16,12L12.08,4.08L13.5,5.5L8,11H20Z"></path></svg></button>
				<div class="jet-apb-calendar-header__label" data-calendar-label="month"></div>
				<button type="button" class="jet-apb-calendar-btn" data-calendar-toggle="next"><svg height="24" version="1.1" viewbox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg"><path d="M4,11V13H16L10.5,18.5L11.92,19.92L19.84,12L11.92,4.08L10.5,5.5L16,11H4Z"></path></svg></button>
			</div>
			<div class="jet-apb-calendar-week"></div>
			<div class="jet-apb-calendar-body" data-calendar-area="month"></div>`;

			notification = instance.querySelector( '.jet-apb-calendar-notification' );
		}

		const setWeekDayHeader = function () {

			var result = '';

			for ( var i = opts.weekStart; i <= opts.weekStart + 6; i++ ) {

				if ( i <= 6 ) {
					result += '<span>' + opts.shortWeekday[ i ] + '</span>';
				} else {
					result += '<span>' + opts.shortWeekday[ ( i - 7 ) ] + '</span>';
				}

			};

			instance.querySelector( '.jet-apb-calendar-week' ).innerHTML = result;

		}

		const setValue = function( { date, slot, slotEnd, price, friendlyTime, friendlyDate, provider, service }, multiBooking = false, action = 'add' ) {
			let selectedSlots = instanceInput.val() ? JSON.parse( instanceInput.val() ) : [] ,
				newPrice      = parseInt( price ),
				_providerID   = parseInt( provider || providerID ),
				_serviceID    = parseInt( service || serviceID ),
				appointment   = { date, slot, slotEnd, price, friendlyTime, friendlyDate, 'provider': _providerID, 'service': _serviceID };

			if( multiBooking ){
				if( 'remove' === action ){

					if( multiBooking.selected >= 1 ){
						multiBooking.selected--;

						selectedSlots = selectedSlots.filter(
							function( item ) {
								if( JSON.stringify( item ) !== JSON.stringify( appointment ) ){
									return true;
								}
								return false;
							}
						)

						newPrice = Number ( instanceInput.data( 'price' ) ) - Number( price );
					}
				}else{
					if( multiBooking.selected < multiBooking.max ){
						multiBooking.selected++

						newPrice = Number ( instanceInput.data( 'price' ) ) + Number( price );
						selectedSlots.push( appointment );
					}
				}
			}else{
				selectedSlots[0] = appointment;
			}

			instanceInput
				.data( 'price',newPrice )
				.val( JSON.stringify( selectedSlots ) )
				.trigger( 'change' );
		}

		const refreshDates = function( newService, newProvider ) {
			instance.classList.add( 'jet-apb-calendar--loading' );
			removeActiveClass();

			xhr = jQuery.ajax({
				url: opts.api.refresh_dates,
				type: 'GET',
				dataType: 'json',
				data: {
					service: newService,
					provider: newProvider,
				},
			}).done( function( response ) {
				xhr = false;
				instance.classList.remove( 'jet-apb-calendar--loading' );

				for ( var k in response.data ) {
					if ( opts.hasOwnProperty( k ) ) {
						opts[ k ] = response.data[ k ];
					}
				};

				instance.querySelectorAll( '.jet-apb-calendar-date' ).forEach( function( el ) {
					setDayAvailability( el );
				} );
			} );
		}

		const maybeRefreshDatesOnInit = function() {

			if ( opts.service.id ) {
				serviceID = opts.service.id;
			} else if ( opts.service.field ) {

				if ( ! serviceField ) {
					serviceField = document.querySelectorAll( '[name="' + opts.service.field + '"]' );
				}
				if ( 1 === serviceField.length ) {
					if ( serviceField.value ) {
						serviceID = serviceField.value;
					}
				} else if ( 1 < serviceField.length ) {
					for ( var i = 0; i < serviceField.length; i++ ) {
						if ( serviceField[ i ].checked ) {
							serviceID = serviceField[ i ].value;
						}
					};
				}
			}

			if ( opts.providerIsset ) {
				if ( opts.provider.id ) {
					providerID = opts.provider.id;
				} else {
					if ( ! providerField ) {
						providerField = document.querySelector( '[name="' + opts.provider.field + '"]' );
					}

					if ( providerField && providerField.value ) {
						providerID = providerField.value;
					}
				}

			}

			if ( serviceID ) {
				refreshDates( serviceID, providerID )
			}

		}

		const withNamespace = function( suffix = '' ) {
			return ( opts.namespace + suffix );
		}

		const className = function( suffix = '' ) {
			return ( '.' + withNamespace( suffix ) );
		}

		this.init = function () {

			notificationHTML = setNotification( instance, notificationHTML );
			appListWrapper   = instance.parentElement.querySelector( '.jet-apb-calendar-appointments-list-wrapper' );

			if ( ! opts.service ) {
				notification.classList.add( 'service-field' );
				notification.style.display = 'flex';
			}

			createCalendar();

			opts.button_prev = instance.querySelector( '[data-calendar-toggle=previous]' );
			opts.button_next = instance.querySelector( '[data-calendar-toggle=next]' );
			opts.month       = instance.querySelector( '[data-calendar-area=month]' );
			opts.month_label = instance.querySelector( '[data-calendar-label=month]' );

			opts.date.setDate( 1 );
			createInputs();
			createMonth();
			setWeekDayHeader();

			maybeRefreshDatesOnInit();

			addEvent( opts.button_prev, 'click', monthPrev );
			addEvent( opts.button_next, 'click', monthNext );

			document.addEventListener( 'click', function ( event ) {

				if ( ! event.target.matches( '.jet-apb-calendar-date-body' ) ) {
					return;
				}

				var day = event.target.parentNode;

				if ( ! day.matches( '[data-status="true"]' ) ) {
					return;
				}

				selectDate( day );

			}, false );

			document.addEventListener( 'click', function ( event ) {

				if ( ! event.target.matches( '.jet-apb-slot' ) ) {
					return;
				}

				if( multiBooking ){
					if( event.target.classList.contains( 'jet-apb-slot--selected' ) ){
						if( multiBooking.selected >= 1 ) {
							setValue(event.target.dataset, multiBooking, 'remove');
							event.target.classList.remove('jet-apb-slot--selected');
						}
					}else{
						if( multiBooking.selected < multiBooking.max ){
							setValue( event.target.dataset, multiBooking, 'add' );
							event.target.classList.add( 'jet-apb-slot--selected' );
						}else{
							showNotification( 'notification-max-slots' );
						}
					}

					updateAppointmentList();
				}else{
					instance.querySelectorAll( '.jet-apb-slot--selected' ).forEach( function( el ) {
						el.classList.remove( 'jet-apb-slot--selected' );
					} );

					event.target.classList.add( 'jet-apb-slot--selected' );
					setValue( event.target.dataset, multiBooking );
				}
			}, false );

			document.addEventListener( 'click', function ( event ) {
				if ( ! event.target.matches( '.jet-apb-calendar-slot__delete' ) ) {
					return;
				}

				let { slotIndex } = event.target.dataset,
					selectedSlots = instanceInput.val() ? JSON.parse( instanceInput.val() ) : [],
					slotButton = instance.querySelector( `[data-slot="${ selectedSlots[ slotIndex ].slot }"][data-slot-end="${ selectedSlots[ slotIndex ].slotEnd }"][data-date="${ selectedSlots[ slotIndex ].date }"]` );

				if(slotButton) {
					slotButton.classList.remove('jet-apb-slot--selected');
				}

				setValue( selectedSlots[ slotIndex ], multiBooking, 'remove' );
				updateAppointmentList();

			}, false );

			if ( opts.service.field ) {

				if ( ! serviceField ) {
					serviceField = document.querySelectorAll( '[name="' + opts.service.field + '"]' );
				}

				if ( serviceField ) {

					if ( opts.allowedServices && opts.allowedServices.length ) {
						for ( var i = 0; i < serviceField.length; i++ ) {

							if ( 'INPUT' === serviceField[ i ].nodeName ) {

								if ( 0 > opts.allowedServices.indexOf( serviceField[ i ].value ) ) {
									serviceField[ i ].closest( className( '__field-wrap.radio-wrap' ) ).remove();
								}

							} else {

								var toRemove = [];
								var service = jQuery( serviceField[ i ] );

								for ( var j = 0; j < serviceField[ i ].options.length; j++ ) {

									if ( ! serviceField[ i ].options[ j ].value ) {
										continue;
									}

									if ( 0 > opts.allowedServices.indexOf( serviceField[ i ].options[ j ].value ) ) {
										toRemove.push( serviceField[ i ].options[ j ].value );
									}
								};

								if ( toRemove.length ) {
									for ( var j = 0; j < toRemove.length; j++ ) {
										service.find( 'option[value="' + toRemove[ j ] + '"]' ).remove();
										//serviceField[ i ].remove( toRemove[ j ] );
									};
								}

							}

						}
					}

					function setServiceValue( eventValue ) {
						if ( eventValue !== serviceID ) {
							serviceID  = eventValue;
							if ( ! opts.provider.id ) {
								providerID = false;
							}
							refreshDates( serviceID, providerID );
						} else {
							serviceID  = eventValue;

							if ( ! opts.provider.id ) {
								providerID = false;
							}
						}
					}
					for ( var i = 0; i < serviceField.length; i++ ) {
						setServiceValue( serviceField[ i ].value );

						serviceField[ i ].addEventListener( 'change', function( event ) {
							setServiceValue( event.target.value );
						}, false );
					}

				}
			}

			if ( opts.provider.field && opts.providerIsset ) {

				if ( ! providerField ) {
					providerField = document.querySelector( '[name="' + opts.provider.field + '"]' );
				}

				if ( opts.provider.field ) {
					function setProviderValue( eventValue ) {
						if ( eventValue !== providerID ) {
							providerID = eventValue;
							refreshDates( serviceID, providerID );
						} else {
							providerID = eventValue;
						}
					}

					if ( providerField ) {
						setProviderValue( providerField.value );
					}

					jQuery( document ).on( 'change', '[name="' + opts.provider.field + '"]', function( event ) {
						setProviderValue( event.target.value );
					} );

					/*providerField.addEventListener( 'change', function( event ) {

						console.log( event );

						if ( event.target.value !== providerID ) {
							providerID = event.target.value;
							refreshDates( serviceID, providerID );
						} else {
							providerID = event.target.value;
						}

					}, false );*/
				}

			}

			document.addEventListener( 'click', function( event ) {

				if ( ! event.target.matches( '.jet-apb-calendar-slots__close' ) ) {
					return;
				}

				removeActiveClass();

			}, false );

			initialized = true;

		}

		this.destroy = function() {
			removeEvent( opts.button_prev, 'click', monthPrev );
			removeEvent( opts.button_next, 'click', monthNext );

			clearCalendar();

			instance.innerHTML = '';

		}

		this.reset = function () {
			initialized = false;
			this.destroy();
			this.init();
		}

		this.set = function( options ) {

			for ( var k in options ) {
				if ( opts.hasOwnProperty( k ) ) {
					opts[ k ] = options[ k ];
				}
			};

			if ( initialized ) {
				this.reset();
			}

		}

		let dataArgs = instance.dataset.args;

		if ( dataArgs ) {
			dataArgs = JSON.parse( dataArgs );
			this.set( dataArgs );
		}

		this.init();

	}

	return VanillaCalendar;

})()

window.VanillaCalendar = VanillaCalendar