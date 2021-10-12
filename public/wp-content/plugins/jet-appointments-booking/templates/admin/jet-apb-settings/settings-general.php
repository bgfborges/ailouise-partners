<div>
	<cx-vui-select
		label="<?php esc_html_e( 'Services post type', 'jet-appointments-booking' ); ?>"
		description="<?php esc_html_e( 'Select post type to fill services from', 'jet-appointments-booking' ); ?>"
		:options-list="postTypes"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="settings.services_cpt"
		@input="updateSetting( $event, 'services_cpt' )"
	></cx-vui-select>
	<cx-vui-select
		label="<?php esc_html_e( 'Provider post type', 'jet-appointments-booking' ); ?>"
		description="<?php esc_html_e( 'Select post type to fill providers from', 'jet-appointments-booking' ); ?>"
		:options-list="postTypes"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="settings.providers_cpt"
		@input="updateSetting( $event, 'providers_cpt' )"
	></cx-vui-select>
	<cx-vui-switcher
		label="<?php esc_html_e( 'WooCommerce Integration', 'jet-appointments-booking' ); ?>"
		description="<?php esc_html_e( 'Check this to connect appointments with WooCommerce checkout', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:value="settings.wc_integration"
		@input="updateSetting( $event, 'wc_integration' )"
	></cx-vui-switcher>
	<cx-vui-switcher
		label="<?php esc_html_e( 'Manage Capacity', 'jet-appointments-booking' ); ?>"
		description="<?php esc_html_e( 'Allow to manage services capacity', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:value="settings.manage_capacity"
		@input="updateSetting( $event, 'manage_capacity' )"
	></cx-vui-switcher>
	<cx-vui-switcher
		label="<?php esc_html_e( 'Show Capacity Counter', 'jet-appointments-booking' ); ?>"
		description="<?php esc_html_e( 'Show service capacity count in slots', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		v-if="settings.manage_capacity"
		:value="settings.show_capacity_counter"
		@input="updateSetting( $event, 'show_capacity_counter' )"
	></cx-vui-switcher>
</div>