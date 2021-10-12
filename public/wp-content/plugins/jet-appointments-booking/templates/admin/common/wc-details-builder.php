<div
	:class="{ 'jet-apb-popup': true, 'jet-apb-popup--active': isActive }"
>
	<div class="jet-apb-popup__overlay" @click="isActive = ! isActive"></div>
	<div class="jet-apb-popup__body">
		<div class="jet-apb-popup__header">
			<h3><?php esc_html_e( 'Set up WooCommerce order details', 'jet-appointments-booking' ); ?></h3>
		</div>
		<div class="jet-apb-popup__content">
			<div class="jet-apb-wc-details">
				<div
					class="jet-apb-wc-details__item"
					v-for="( item, index ) in details"
					:key="'details-item-' + index"
				>
					<div class="jet-apb-wc-details-nav">
						<span class="dashicons dashicons-arrow-up-alt2" @click="moveItem( index, index - 1 )"></span>
						<span class="dashicons dashicons-arrow-down-alt2" @click="moveItem( index, index + 1 )"></span>
					</div>
					<div class="jet-apb-wc-details__col col-type">
						<label :for="'type_' + index"><?php esc_html_e( 'Type', 'jet-appointments-booking' ); ?></label>
						<select v-model="details[ index ].type" :id="'type_' + index">
							<option value=""><?php esc_html_e( 'Select type...', 'jet-appointments-booking' ); ?></option>
							<option value="service"><?php esc_html_e( 'Service name', 'jet-appointments-booking' ); ?></option>
							<option value="provider"><?php esc_html_e( 'Provider name', 'jet-appointments-booking' ); ?></option>
							<option value="date"><?php esc_html_e( 'Date', 'jet-appointments-booking' ); ?></option>
							<option value="slot"><?php esc_html_e( 'Time slot start', 'jet-appointments-booking' ); ?></option>
							<option value="slot_end"><?php esc_html_e( 'Time slot end', 'jet-appointments-booking' ); ?></option>
							<option value="start_end_time"><?php esc_html_e( 'Full time slot', 'jet-appointments-booking' ); ?></option>
							<option value="date_time"><?php esc_html_e( 'Full date and time', 'jet-appointments-booking' ); ?></option>
							<option value="field"><?php esc_html_e( 'Form field', 'jet-appointments-booking' ); ?></option>
							<option value="add_to_calendar"><?php esc_html_e( 'Add to Google calendar link', 'jet-appointments-booking' ); ?></option>
						</select>
					</div>
					<div class="jet-apb-wc-details__col col-label">
						<label :for="'label_' + index"><?php esc_html_e( 'Label', 'jet-appointments-booking' ); ?></label>
						<input type="text" v-model="details[ index ].label" :id="'label_' + index">
					</div>
					<div class="jet-apb-wc-details__col col-format" v-if="'date' === details[ index ].type">
						<label :for="'format_' + index"><?php esc_html_e( 'Date format', 'jet-appointments-booking' ); ?></label>
						<input type="text" v-model="details[ index ].date_format" :id="'format_' + index">
						<div class="jet-apb-wc-details__desc"><?php
							printf(
								'<a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank">%s</a>',
								__( 'Formatting docs', 'jet-appointments-booking' )
							);
						?></div>
					</div>
					<div class="jet-apb-wc-details__col col-format" v-if="'slot' === details[ index ].type || 'slot_end' === details[ index ].type || 'start_end_time' === details[ index ].type">
						<label :for="'format_' + index"><?php esc_html_e( 'Time format', 'jet-appointments-booking' ); ?></label>
						<input type="text" v-model="details[ index ].time_format" :id="'format_' + index">
						<div class="jet-apb-wc-details__desc"><?php
							printf(
								'<a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank">%s</a>',
								__( 'Formatting docs', 'jet-appointments-booking' )
							);
						?></div>
					</div>
					<div class="jet-apb-wc-details__col col-format" v-if="'date_time' === details[ index ].type">
						<label :for="'format_' + index"><?php esc_html_e( 'Date/Time format', 'jet-appointments-booking' ); ?></label>
						<div class="jet-apb-wc-details__inner-col">
							<input type="text" v-model="details[ index ].date_format" :id="'date_format_' + index">
							<input type="text" v-model="details[ index ].time_format" :id="'time_format_' + index">
						</div>
						<div class="jet-apb-wc-details__desc"><?php
							printf(
								'<a href="https://wordpress.org/support/article/formatting-date-and-time/" target="_blank">%s</a>',
								__( 'Formatting docs', 'jet-appointments-booking' )
							);
						?></div>
					</div>
					<div class="jet-apb-wc-details__col col-format" v-else-if="'field' === details[ index ].type">
						<label :for="'field_' + index"><?php esc_html_e( 'Select form field', 'jet-appointments-booking' ); ?></label>
						<select v-model="details[ index ].field" :id="'field_' + index">
							<option value=""><?php esc_html_e( 'Select field...', 'jet-appointments-booking' ); ?></option>
							<option :value="field" v-for="field in fieldsList" :key="'details-field-' + field">{{ field }}</option>
						</select>
					</div>
					<div class="jet-apb-wc-details__col col-placeholder" v-else-if="'add_to_calendar' === details[ index ].type">
						<label :for="'link_label_' + index"><?php esc_html_e( 'Link text', 'jet-appointments-booking' ); ?></label>
						<input type="text" v-model="details[ index ].link_label" :id="'format_' + index">
					</div>
					<div class="jet-apb-wc-details__col col-delete"><span @click="deleteItem( index )" class="dashicons dashicons-trash"></span></div>
				</div>
			</div>
			<a href="#" class="jet-apb-add-rate" @click.prevent="newItem">+&nbsp;<?php esc_html_e( 'Add new item', 'jet-appointments-booking' ); ?></a>
		</div>
		<div class="jet-apb-popup-actions">
			<button class="button button-primary" type="button" aria-expanded="true" @click="save">
				<span v-if="!saving"><?php esc_html_e( 'Save', 'jet-appointments-booking' ); ?></span>
				<span v-else><?php esc_html_e( 'Saving...', 'jet-appointments-booking' ); ?></span>
			</button>
			<button class="button-link" type="button" aria-expanded="true" @click="isActive = false"><?php esc_html_e( 'Cancel', 'jet-appointments-booking' ); ?></button>
		</div>
	</div>
</div>
