<?php
/**
 * Notifications fields template
 */
?>
<div class="jet-form-editor__row" v-if="'insert_appointment' === currentItem.type">
	<div class="jet-form-editor__row-label"><?php
		esc_html_e( 'Service ID field:', 'jet-appointments-booking' );
	?></div>
	<div class="jet-form-editor__row-control">
		<select v-model="currentItem.appointment_service_field">
			<option value="">--</option>
			<option v-for="field in availableFields" :value="field">{{ field }}</option>
			<option value="_manual_input"><?php esc_html_e( 'Manual Input', 'jet-appointments-booking' ); ?></option>
		</select>
		<input v-if="'_manual_input' === currentItem.appointment_service_field" type="text" v-model="currentItem.appointment_service_id" placeholder="<?php esc_html_e( 'Service ID', 'jet-appintments-booking' ); ?>" style="margin: 0 0 0px 5px;">
	</div>
</div>
<div class="jet-form-editor__row" v-if="'insert_appointment' === currentItem.type">
	<div class="jet-form-editor__row-label"><?php
		esc_html_e( 'Provider ID field:', 'jet-appointments-booking' );
	?></div>
	<div class="jet-form-editor__row-control">
		<select v-model="currentItem.appointment_provider_field">
			<option value="">--</option>
			<option v-for="field in availableFields" :value="field">{{ field }}</option>
			<option value="_manual_input"><?php esc_html_e( 'Manual Input', 'jet-appointments-booking' ); ?></option>
		</select>
		<input v-if="'_manual_input' === currentItem.appointment_provider_field" type="text" v-model="currentItem.appointment_provider_id" placeholder="<?php esc_html_e( 'Provider ID', 'jet-appintments-booking' ); ?>" style="margin: 0 0 0px 5px;">
	</div>
</div>
<div class="jet-form-editor__row" v-if="'insert_appointment' === currentItem.type">
	<div class="jet-form-editor__row-label"><?php
		_e( 'Appointment date field:', 'jet-appointments-booking' );
	?></div>
	<div class="jet-form-editor__row-control">
		<select v-model="currentItem.appointment_date_field">
			<option value="">--</option>
			<option v-for="field in availableFields" :value="field">{{ field }}</option>
		</select>
	</div>
</div>
<div class="jet-form-editor__row" v-if="'insert_appointment' === currentItem.type">
	<div class="jet-form-editor__row-label"><?php
		_e( 'User e-mail field:', 'jet-appointments-booking' );
	?></div>
	<div class="jet-form-editor__row-control">
		<select v-model="currentItem.appointment_email_field">
			<option value="">--</option>
			<option v-for="field in availableFields" :value="field">{{ field }}</option>
		</select>
	</div>
</div>
<?php if ( ! empty( $additional_db_columns ) ) : ?>
	<?php foreach ( $additional_db_columns as $column ) : ?>
		<div class="jet-form-editor__row" v-if="'insert_appointment' === currentItem.type">
			<div class="jet-form-editor__row-label"><?php
				echo '<b>' . $column . '</b>' . __( ' field:', 'jet-appointments-booking' );
			?></div>
			<div class="jet-form-editor__row-control">
				<select v-model="currentItem.appointment_custom_field_<?php echo $column; ?>">
					<option value="">--</option>
					<option v-for="field in availableFields" :value="field">{{ field }}</option>
				</select>
			</div>
		</div>
	<?php endforeach; ?>
<?php endif;

if ( $wc_integration ) {
	?>
	<div class="jet-form-editor__row" v-if="'insert_appointment' === currentItem.type">
		<div class="jet-form-editor__row-label">
			<?php esc_html_e( 'WooCommerce Price field:', 'jet-appointments-booking' ); ?>
			<div class="jet-form-editor__row-notice"><?php
				esc_html_e( 'Select field to get total price from. If not selected price will be get from post meta value.', 'jet-appointments-booking' );
			?></div>
		</div>
		<div class="jet-form-editor__row-control">
			<select v-model="currentItem.appointment_wc_price">
				<option value="">--</option>
				<option v-for="field in availableFields" :value="field">{{ field }}</option>
			</select>
		</div>
	</div>

	<div class="jet-form-editor__row" v-if="'insert_appointment' === currentItem.type">
		<div class="jet-form-editor__row-label">
			<?php esc_html_e( 'WooCommerce order details:', 'jet-appointments-booking' ); ?>
			<div class="jet-form-editor__row-notice"><?php
				esc_html_e( 'Set up booking-related info you want to add to the WooCommerce orders and e-mails', 'jet-appointments-booking' );
			?></div>
		</div>
		<div class="jet-form-editor__row-control">
			<button type="button" class="button button-secondary" id="jet-apb-wc-details"><?php esc_html_e( 'Set up', 'jet-appointments-booking' ); ?></button>
		</div>
	</div>

	<div class="jet-form-editor__row" v-if="'insert_appointment' === currentItem.type">
		<div class="jet-form-editor__row-label">
			<?php esc_html_e( 'WooCommerce checkout fields map:', 'jet-appointments-booking' ); ?>
			<div class="jet-form-editor__row-notice"><?php
				esc_html_e( 'Connect WooCommerce checkout fields to appropriate form fields. This allows you to pre-fill WooCommerce checkout fields after redirect to checkout.', 'jet-appointments-booking' );
			?></div>
		</div>
		<div class="jet-form-editor__row-fields jet-wc-checkout-fields">
			<?php foreach ( $checkout_fields as $field ) {
			?>
			<div class="jet-form-editor__row-map">
				<span><?php echo $field; ?></span>
				<select v-model="currentItem.wc_fields_map__<?php echo $field; ?>">
					<option value="">--</option>
					<option v-for="field in availableFields" :value="field">{{ field }}</option>
				</select>
			</div>
			<?php
			} ?>
		</div>
	</div>
	<?php
}