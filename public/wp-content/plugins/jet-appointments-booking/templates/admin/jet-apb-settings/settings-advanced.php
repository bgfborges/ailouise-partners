<div>
	<cx-vui-select
		label="<?php _e( 'Availability check by', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Select type of slots availability check - through all services or independent by each service', 'jet-appointments-booking' ); ?>"
		:options-list="[
			{
				value: 'global',
				label: '<?php _e( 'Through all services', 'jet-appointments-boooking' ); ?>',
			},
			{
				value: 'service',
				label: '<?php _e( 'By each service', 'jet-appointments-boooking' ); ?>',
			}
		]"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="settings.check_by"
		@input="updateSetting( $event, 'check_by' )"
	></cx-vui-select>
	<cx-vui-switcher
		label="<?php _e( 'Hide Set Up Wizard', 'jet-appointments-booking' ); ?>"
		description="<?php _e( 'Check this to hide Set Up page to avoid unnecessary plugin resets', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:value="settings.hide_setup"
		@input="updateSetting( $event, 'hide_setup' )"
	></cx-vui-switcher>
</div>
