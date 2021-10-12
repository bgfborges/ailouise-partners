import './actions/insert-appointment';
import * as provider from 'blocks/appointment-provider';
import * as date from 'blocks/appointment-date';

const {
		  addFilter,
	  } = wp.hooks;

addFilter( 'jet.fb.register.fields', 'jet-form-builder', blocks => {
	blocks.push( provider, date );

	return blocks;
} );
