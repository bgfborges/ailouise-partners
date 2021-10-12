(function () {

	"use strict";

	Vue.component( 'jet-apb-go-to-setup', {
		template: '#jet-apb-go-to-setup',
		data: function() {
			return {
				setupURL: window.JetAPBConfig.setup.setup_url,

			};
		}
	} );

})();