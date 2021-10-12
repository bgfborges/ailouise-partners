<?php
/**
 * Form appointment calendar
 */
?>
<div class="jet-apb-calendar-wrapper">
	<div class="appointment-calendar jet-apb-calendar" data-args="<?php echo htmlspecialchars( json_encode( $dataset ) ) ?>"></div>
	<div class="jet-apb-calendar-appointments-list-wrapper" style="display: none">
		<div class="jet-form__heading">
			<span class="jet-form__label-text"><?php esc_html_e( 'Appointment details:', 'jet-appointments-booking' ); ?></span>
		</div>
		<div class="jet-apb-calendar-appointments-list"></div>
	</div>
	<div class="jet-apb-calendar-notification" style="display: none">
		<div class="jet-apb-calendar-notification-service"><?php esc_html_e( 'Please, select the service before', 'jet-appointments-booking' ); ?></div>
		<div class="jet-apb-calendar-notification-provider"><?php esc_html_e( 'Please, select the provider before', 'jet-appointments-booking' ); ?></div>
		<div class="jet-apb-calendar-notification-service-field"><?php esc_html_e( 'Please set service field for current calendar', 'jet-appointments-booking' ); ?></div>
		<div class="jet-apb-calendar-notification-max-slots"><?php esc_html_e( 'Sorry. You have the max number of appointments.', 'jet-appointments-booking' ); ?></div>
	</div>
</div>