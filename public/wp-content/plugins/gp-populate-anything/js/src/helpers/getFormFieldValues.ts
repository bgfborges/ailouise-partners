const $ = window.jQuery;

/**
 * serializeArray() does not include disabled inputs by default.
 *
 * @credit https://stackoverflow.com/q/15958671
 */
function serializeAll($form: JQuery) {
	var data = $form.serializeArray();

	$(':disabled[name]', $form).each((index, el) => {
		if (!(el as HTMLInputElement).name) {
			return;
		}

		data.push({ name: (el as HTMLInputElement).name, value: $(el).val() });
	});

	return data;
}

export default function getFormFieldValues(formId?:number|string, isGravityView:boolean = false) {

	var $form = $('#gform_' + formId);
	var inputPrefix = 'input_';

	if (isGravityView) {
		inputPrefix = 'filter_';
	}

	/* Use entry form if we're in the Gravity Forms admin entry view. */
	if ( $('#wpwrap #entry_form').length ) {
		$form = $('#entry_form');
	}

	if ( isGravityView ) {
		$form = $( '.gv-widget-search' );
	}

	var inputsArray = $.grep(serializeAll($form), function (value?:JQuerySerializeArrayElement) {
		if (!value || value.name.indexOf(inputPrefix) !== 0) {
			return false;
		}

		return true;
	});

	var inputsObject:{ [input: string]: string[]|string } = {};

	$.each(inputsArray, function (index, input: any) {
		var value = input.value;
		input = input.name.replace(inputPrefix, '');

		/* Handle array-based inputs such as the Time field */
		if (input.indexOf('[]') !== -1) {
			input = input.replace('[]', '');

			if (!(input in inputsObject)) {
				inputsObject[input] = [];
			}

			(inputsObject[input] as string[]).push(value);
		/* Standard inputs */
		} else {
			inputsObject[input] = value;
		}
	});

	return inputsObject;

}
