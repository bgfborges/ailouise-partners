<?php
/**
 * Edit fields template
 */
?>
<div class="jet-form-editor__row direction-column" v-if="'appointment_date' === currentItem.settings.type">
	<div class="jet-form-editor__row-title"><?php
		_e( 'Appointment Specific Settings', 'jet-appointmets-booking' );
	?></div>
	<div class="jet-form-editor__group">
		<div class="jet-form-editor__col">
			<div class="jet-form-editor__col-label"><?php
				_e( 'Get Service ID From:', 'jet-appointments-booking' );
			?></div>
			<div class="jet-form-editor__col-control">
				<select v-model="currentItem.settings.appointment_service_field">
					<option value=""><?php _e( 'Not Selected', 'jet-appointments-booking' ); ?></option>
					<option value="current_post_id"><?php _e( 'Current Post ID', 'jet-appointments-booking' ); ?></option>
					<option value="form_field"><?php _e( 'Form Field', 'jet-appointments-booking' ); ?></option>
					<option value="manual_input"><?php _e( 'Manual Input', 'jet-appointments-booking' ); ?></option>
				</select>
			</div>
		</div>
		<div class="jet-form-editor__col">
			<div class="jet-form-editor__col-label"><?php
				_e( 'Select Service Field <i>(for <b>Form Field</b> option)</i>:', 'jet-appointments-booking' );
			?></div>
			<div class="jet-form-editor__col-control">
				<select v-model="currentItem.settings.appointment_form_field">
					<option value="">--</option>
					<option v-for="field in availableFields" :value="field" v-if="field !== currentItem.settings.name">{{ field }}</option>
				</select>
			</div>
		</div>
		<div class="jet-form-editor__col">
			<div class="jet-form-editor__col-label"><?php _e( 'Set Service ID <i>(for <b>Manual Input</b> option)</i>:', 'jet-appointments-booking' ); ?></div>
			<div class="jet-form-editor__col-control">
				<input type="text" v-model="currentItem.settings.appointment_service_id">
			</div>
		</div>
	</div>
	<div class="jet-form-editor__group">
		<div class="jet-form-editor__col">
			<div class="jet-form-editor__col-label"><?php
				_e( 'Get Provider ID From:', 'jet-appointments-booking' );
			?></div>
			<div class="jet-form-editor__col-control">
				<select v-model="currentItem.settings.appointment_provider_field">
					<option value=""><?php _e( 'Not Selected', 'jet-appointments-booking' ); ?></option>
					<option value="current_post_id"><?php _e( 'Current Post ID', 'jet-appointments-booking' ); ?></option>
					<option value="form_field"><?php _e( 'Form Field', 'jet-appointments-booking' ); ?></option>
					<option value="manual_input"><?php _e( 'Manual Input', 'jet-appointments-booking' ); ?></option>
				</select>
			</div>
		</div>
		<div class="jet-form-editor__col">
			<div class="jet-form-editor__col-label"><?php
				_e( 'Select Provider Field <i>(for <b>Form Field</b> option)</i>:', 'jet-appointments-booking' );
			?></div>
			<div class="jet-form-editor__col-control">
				<select v-model="currentItem.settings.appointment_provider_form_field">
					<option value="">--</option>
					<option v-for="field in availableFields" :value="field" v-if="field !== currentItem.settings.name">{{ field }}</option>
				</select>
			</div>
		</div>
		<div class="jet-form-editor__col">
			<div class="jet-form-editor__col-label"><?php _e( 'Set Provider ID <i>(for <b>Manual Input</b> option)</i>:', 'jet-appointments-booking' ); ?></div>
			<div class="jet-form-editor__col-control">
				<input type="text" v-model="currentItem.settings.appointment_provider_id">
			</div>
		</div>
	</div>
</div>

<div class="jet-form-editor__row direction-column" v-if="'appointment_provider' === currentItem.settings.type">
	<div class="jet-form-editor__row-title"><?php
		_e( 'Provider Specific Settings', 'jet-appointmets-booking' );
	?></div>
	<div class="jet-form-editor__group">
		<div class="jet-form-editor__col">
			<div class="jet-form-editor__col-label"><?php
				_e( 'Get Service ID From:', 'jet-appointments-booking' );
			?></div>
			<div class="jet-form-editor__col-control">
				<select v-model="currentItem.settings.appointment_service_field">
					<option value=""><?php _e( 'Not Selected', 'jet-appointments-booking' ); ?></option>
					<option value="current_post_id"><?php _e( 'Current Post ID', 'jet-appointments-booking' ); ?></option>
					<option value="form_field"><?php _e( 'Form Field', 'jet-appointments-booking' ); ?></option>
					<option value="manual_input"><?php _e( 'Manual Input', 'jet-appointments-booking' ); ?></option>
				</select>
			</div>
		</div>
		<div class="jet-form-editor__col">
			<div class="jet-form-editor__col-label"><?php
				_e( 'Select Service Field <i>(for <b>Form Field</b> option)</i>:', 'jet-appointments-booking' );
			?></div>
			<div class="jet-form-editor__col-control">
				<select v-model="currentItem.settings.appointment_form_field">
					<option value="">--</option>
					<option v-for="field in availableFields" :value="field" v-if="field !== currentItem.settings.name">{{ field }}</option>
				</select>
			</div>
		</div>
		<div class="jet-form-editor__col">
			<div class="jet-form-editor__col-label"><?php _e( 'Set Service ID <i>(for <b>Manual Input</b> option)</i>:', 'jet-appointments-booking' ); ?></div>
			<div class="jet-form-editor__col-control">
				<input type="text" v-model="currentItem.settings.appointment_service_id">
			</div>
		</div>
	</div>
	<div class="jet-form-editor__group">
		<div class="jet-form-editor__col">
			<div class="jet-form-editor__col-label"><?php
				_e( 'Use Custom Template For Items:', 'jet-appointments-booking' );
			?></div>
			<div class="jet-form-editor__col-control">
				<select type="text" v-model="currentItem.settings.appointment_provider_custom_template">
					<option value=""><?php _e( 'Select...', 'jet-appointments-booking' ); ?></option>
					<option value="0"><?php _e( 'No', 'jet-appointments-booking' ); ?></option>
					<option value="1"><?php _e( 'Yes', 'jet-appointments-booking' ); ?></option>
				</select>
			</div>
		</div>
		<div class="jet-form-editor__col">
			<div class="jet-form-editor__col-label"><?php
				_e( 'Custom Template ID:', 'jet-appointments-booking' );
			?></div>
			<div class="jet-form-editor__col-control">
				<select type="text" v-model="currentItem.settings.appointment_provider_custom_template_id">
					<option v-for="( listingItemName, listingItemID ) in listingItems" :value="listingItemID" >
						{{ listingItemName }}
					</option>
				</select>
			</div>
		</div>
		<div class="jet-form-editor__col">
			<div class="jet-form-editor__col-label"><?php
				_e( 'Switch Page on Change:', 'jet-appointments-booking' );
			?></div>
			<div class="jet-form-editor__col-control">
				<select type="text" v-model="currentItem.settings.switch_on_change">
					<option value=""><?php _e( 'Select...', 'jet-appointments-booking' ); ?></option>
					<option value="0"><?php _e( 'No', 'jet-appointments-booking' ); ?></option>
					<option value="1"><?php _e( 'Yes', 'jet-appointments-booking' ); ?></option>
				</select>
			</div>
		</div>
	</div>
</div>
