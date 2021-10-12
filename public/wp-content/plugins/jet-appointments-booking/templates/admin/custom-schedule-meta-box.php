<div id='jet-apb-custom-schedule-meta-box'>
	<cx-vui-switcher
		label="<?php esc_html_e( 'Use Custom Schedule', 'jet-appointments-booking' ); ?>"
		description="<?php esc_html_e( 'You can use a custom schedule for services or providers.', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:return-true="true"
		:return-false="false"
		v-model="settings.custom_schedule.use_custom_schedule"
		@input="updateSetting( $event, 'use_custom_schedule' )"
	></cx-vui-switcher>
	<jet-apb-working-hours-meta-box
		v-if="settings.custom_schedule.use_custom_schedule"
	></jet-apb-working-hours-meta-box>
</div>