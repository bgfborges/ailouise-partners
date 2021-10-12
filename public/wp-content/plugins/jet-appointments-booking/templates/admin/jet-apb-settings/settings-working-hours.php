<div class="jet-apb-schedule-settings">
	<div class="jet-apb-working-hours__heading" v-if="!settings.use_custom_schedule">
		<h4 class="cx-vui-subtitle"><?php esc_html_e( 'Booking Schedule', 'jet-appointments-booking' ); ?></h4>
	</div>
	<cx-vui-select
		v-if="!settings.use_custom_schedule"
		label="<?php esc_html_e( 'Slot time format', 'jet-appointments-booking' ); ?>"
		description="<?php esc_html_e( 'Select time format for available slots list', 'jet-appointments-booking' ); ?>"
		:options-list="slotTimeFormat"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		:value="settings.slot_time_format"
		@input="updateSetting( $event, 'slot_time_format' )"
	></cx-vui-select>
	</cx-vui-radio>
	<template v-if="settings.booking_type === 'range'">
		<cx-vui-component-wrapper
			:wrapper-css="[ 'equalwidth' ]"
			class="jet-apb-working-hours__paired-controls"
			:size="'fullwidth'"
			label="<?php esc_html_e( 'Slot Duration', 'jet-appointments-booking' ); ?>"
		>
			<cx-vui-time
				label="<?php esc_html_e( 'Min', 'jet-appointments-booking' ); ?>"
				format="HH:mm"
				placeholder="00:30"
				size="small"
				:value="getTimeSettings( 'min_slot_time' )"
				@input="onUpdateTimeSettings( {
					key: 'min_slot_time',
					value: $event,
				} )"
			></cx-vui-time>
			<cx-vui-time
				label="<?php esc_html_e( 'Max', 'jet-appointments-booking' ); ?>"
				format="HH:mm"
				placeholder="02:00"
				size="small"
				:value="getTimeSettings( 'max_slot_time' )"
				@input="onUpdateTimeSettings( {
					key: 'max_slot_time',
					value: $event,
				} )"
			></cx-vui-time>
		</cx-vui-component-wrapper>
		<cx-vui-switcher
				label="<?php esc_html_e( 'Several days', 'jet-appointments-booking' ); ?>"
				description="<?php esc_html_e( 'The slot can include several days. For example, the slot starts 10:00 - 02/05/21 ends of the slot 20:00 - 05/06/21.', 'jet-appointments-booking' ); ?>"
				key="several_days"
				:wrapper-css="[ 'equalwidth' ]"
				:return-true="true"
				:return-false="false"
				v-model="settings.several_days"
				@input="updateSetting( $event, 'several_days' )"
		></cx-vui-switcher>
	</template>
	<template v-if="settings.booking_type === 'recurring'">
		<cx-vui-f-select
				label="<?php esc_html_e( 'Rebooking every:', 'jet-appointments-booking' ); ?>"
				description="<?php esc_html_e( 'The client can choose the recurrence period of the appointments.', 'jet-appointments-booking' ); ?>"
				key="re_booking"
				:optionsList="rebookingOptions"
				:multiple="true"
				:wrapper-css="[ 'equalwidth' ]"
				:value="settings.re_booking"
				@input="updateSetting( $event, 're_booking' )"
		></cx-vui-f-select>
		<cx-vui-component-wrapper
				:wrapper-css="[ 'equalwidth' ]"
				:class="['jet-apb-working-hours__paired-controls', 'jet-apb-working-hours__multi_booking']"
				:size="'fullwidth'"
				label="<?php esc_html_e( 'Repeat Count', 'jet-appointments-booking' ); ?>"
		>
			<cx-vui-input
					label="<?php esc_html_e( 'Min', 'jet-appointments-booking' ); ?>"
					key="min_slot_count"
					type="number"
					:step="1"
					:min="2"
					:max="1000"
					v-model="settings.min_slot_count"
					@on-blur="updateSetting( $event, 'min_slot_count' )"
			>
			</cx-vui-input>
			<cx-vui-input
					label="<?php esc_html_e( 'Max', 'jet-appointments-booking' ); ?>"
					key="max_slot_count"
					type="number"
					:step="1"
					:min="1"
					:max="1000"
					v-model="settings.max_slot_count"
					@on-blur="updateSetting( $event, 'max_slot_count' )"
			>
			</cx-vui-input>
		</cx-vui-component-wrapper>
	</template>
	<cx-vui-time
			v-if="settings.booking_type !== 'range'"
			class="jet-apb-working-hours__main-settings"
			label="<?php esc_html_e( 'Slot Duration', 'jet-appointments-booking' ); ?>"
			description="<?php esc_html_e( 'Select the default duration for each service and provider time slot', 'jet-appointments-booking' ); ?>"
			:wrapper-css="[ 'equalwidth' ]"
			:size="'fullwidth'"
			placeholder="00:01"
			:value="getTimeSettings( 'default_slot' )"
			format="HH:mm"
			@input="onUpdateTimeSettings( {
				key: 'default_slot',
				value: $event,
			} )"
	></cx-vui-time>
	<cx-vui-time
		class="jet-apb-working-hours__main-settings"
		label="<?php esc_html_e( 'Buffer Time Before Slot', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		placeholder="00:00"
		:value="getTimeSettings( 'buffer_before' )"
		format="HH:mm"
		@input="onUpdateTimeSettings( {
			key: 'buffer_before',
			value: $event,
		} )"
	></cx-vui-time>
	<cx-vui-time
		class="jet-apb-working-hours__main-settings"
		label="<?php esc_html_e( 'Buffer Time After Slot', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		placeholder="00:00"
		:value="getTimeSettings( 'buffer_after' )"
		format="HH:mm"
		@input="onUpdateTimeSettings( {
			key: 'buffer_after',
			value: $event,
		} )"
	></cx-vui-time>
	<cx-vui-time
		class="jet-apb-working-hours__main-settings"
		label="<?php esc_html_e( 'Locked Time Before', 'jet-appointments-booking' ); ?>"
		description="<?php esc_html_e( 'The appointment cannot be made if there is less than this time left.', 'jet-appointments-booking' ); ?>"
		:wrapper-css="[ 'equalwidth' ]"
		:size="'fullwidth'"
		placeholder="00:00"
		:value="getTimeSettings( 'locked_time' )"
		format="HH:mm"
		@input="onUpdateTimeSettings( {
			key: 'locked_time',
			value: $event,
		} )"
	></cx-vui-time>
	<template v-if="! settings.use_custom_schedule && settings.booking_type !== 'recurring'">
		<cx-vui-switcher
			label="<?php esc_html_e( 'Multi Booking', 'jet-appointments-booking' ); ?>"
			description="<?php esc_html_e( 'The client can book several appointments at different times and different days.', 'jet-appointments-booking' ); ?>"
			key="multi_booking"
			:wrapper-css="[ 'equalwidth' ]"
			:return-true="true"
			:return-false="false"
			v-model="settings.multi_booking"
			@input="updateSetting( $event, 'multi_booking' )"
		></cx-vui-switcher>
		<cx-vui-component-wrapper
				v-if="settings.multi_booking"
				:wrapper-css="[ 'equalwidth' ]"
				:class="['jet-apb-working-hours__paired-controls', 'jet-apb-working-hours__multi_booking']"
				:size="'fullwidth'"
				label="<?php esc_html_e( 'Slot Count', 'jet-appointments-booking' ); ?>"
		>
			<cx-vui-input
				label="<?php esc_html_e( 'Min', 'jet-appointments-booking' ); ?>"
				key="min_slot_count"
				type="number"
				:step="1"
				:min="1"
				:max="1000"
				v-model="settings.min_slot_count"
				@input="updateSetting( $event, 'min_slot_count' )"
			>
			</cx-vui-input>
			<cx-vui-input
				label="<?php esc_html_e( 'Max', 'jet-appointments-booking' ); ?>"
				key="max_slot_count"
				type="number"
				:step="1"
				:min="1"
				:max="1000"
				v-model="settings.max_slot_count"
				@input="updateSetting( $event, 'max_slot_count' )"
			>
			</cx-vui-input>
		</cx-vui-component-wrapper>
	</template>

	<div class="jet-apb-working-hours">
		<div class="jet-apb-week-days jet-apb-working-hours__columns">
			<div class="jet-apb-working-hours__heading">
				<h4 class="cx-vui-subtitle"><?php esc_html_e( 'Work Hours', 'jet-appointments-booking' ); ?></h4>
			</div>
			<div class="jet-apb-week-day" v-for="( label, day ) in weekdays" :key="day">
				<div class="jet-apb-week-day__head">
					<div class="jet-apb-week-day__head-name">{{ label }}</div>
					<div class="jet-apb-week-day__head-actions">
						<cx-vui-button
							size="mini"
							button-style="accent"
							@click="newSlot( day )"
						>
							<span slot="label"><?php esc_html_e( '+ Add', 'jet-appointments-booking' ); ?></span>
						</cx-vui-button>
					</div>
				</div>
				<div class="jet-apb-week-day__body">
					<div
						class="jet-apb-week-day__slot"
						v-for="( daySlot, slotIndex ) in settings.working_hours[ day ]"
					>
						<div class="jet-apb-week-day__slot-name">
							{{ daySlot.from }}-{{ daySlot.to }}
						</div>
						<div class="jet-apb-working-hours__slot-actions">
							<span
								class="dashicons dashicons-edit"
								@click="editSlot( day, slotIndex, daySlot )"
							></span>
							<div class="jet-apb-week-day__slot-delete" style="position:relative;">
								<span
									class="dashicons dashicons-trash"
									@click="confirmDeleteSlot( day, slotIndex )"
								></span>
								<div
									class="cx-vui-tooltip"
									v-if="deleteSlotTrigger === day + '-' + slotIndex"
								>
									<?php esc_html_e( 'Are you sure?', 'jet-appointments-booking' ); ?>
									<br> <span
										class="cx-vui-repeater-item__confrim-del"
										@click="deleteSlot( day, slotIndex, daySlot )"
									><?php
										esc_html_e( 'Yes', 'jet-appointments-booking' );
									?></span>
									/
									<span
										class="cx-vui-repeater-item__cancel-del"
										@click="deleteSlotTrigger = null"
									><?php
										esc_html_e( 'No', 'jet-appointments-booking' );
									?></span></div>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>

		<div class="jet-apb-days-schedule jet-apb-working-hours__columns">
			<cx-vui-collapse
				:collapsed="false"
			>
				<h4 class="cx-vui-subtitle"  slot="title"><?php esc_html_e( 'Days Off', 'jet-appointments-booking' ); ?></h4>
				<div class="cx-vui-panel" slot="content">
					<div class="jet-apb-working-hours__heading">
						<div class="cx-vui-component__desc"><?php esc_html_e( 'Set the days that will be the weekend.', 'jet-appointments-booking' ); ?></div>
						<cx-vui-button
							size="mini"
							button-style="accent"
							@click="showEditDay( 'days_off' )"
						>
							<span slot="label"><?php esc_html_e( 'Add Days', 'jet-appointments-booking' ); ?></span>
						</cx-vui-button>
					</div>

					<div class="jet-apb-working-hours__body">
						<div
							class="jet-apb-days-schedule__slot"
							v-for="(offDate, key) in settings.days_off"
						>
							<div>
								{{ offDate.start }} — {{ offDate.end }} {{ offDate.name }}
							</div>
							<div class="jet-apb-working-hours__slot-actions">
								<span
									class="dashicons dashicons-edit"
									@click="showEditDay( 'days_off', offDate )"
								></span>
								<div style="position:relative;">
									<span
										class="dashicons dashicons-trash"
										@click="confirmDeleteDay( offDate )"
									></span>
									<div
										class="cx-vui-tooltip"
										v-if="deleteDayTrigger === offDate"
									>
										<?php esc_html_e( 'Are you sure?', 'jet-appointments-booking' ); ?>
										<br><span
											class="cx-vui-repeater-item__confrim-del"
											@click="deleteDay( 'days_off', offDate )"
										><?php
											esc_html_e( 'Yes', 'jet-appointments-booking' );
										?></span>
										/
										<span
											class="cx-vui-repeater-item__cancel-del"
											@click="deleteDayTrigger = null"
										><?php
											esc_html_e( 'No', 'jet-appointments-booking' );
										?></span></div>
									</div>
								</div>
						</div>
					</div>
				</div>
			</cx-vui-collapse>

			<cx-vui-collapse
				:collapsed="false"
			>
				<h4 class="cx-vui-subtitle"  slot="title"><?php esc_html_e( 'Working Days', 'jet-appointments-booking' ); ?></h4>
				<div class="cx-vui-panel" slot="content">
					<div class="jet-apb-working-hours__heading">
						<div class="cx-vui-component__desc"><?php esc_html_e( 'Set available days for booking.', 'jet-appointments-booking' ); ?></div>
						<cx-vui-button
							size="mini"
							button-style="accent"
							@click="showEditDay( 'working_days' )"
						>
							<span slot="label"><?php esc_html_e( 'Add Days', 'jet-appointments-booking' ); ?></span>
						</cx-vui-button>
					</div>

					<div class="jet-apb-working-hours__body">
						<div
							class="jet-apb-days-schedule__slot"
							v-for="(workingDate, key) in settings.working_days"
						>
							<div>
								{{ workingDate.start }} — {{ workingDate.end }} {{ workingDate.name }}
							</div>
							<div class="jet-apb-working-hours__slot-actions">
								<span
									class="dashicons dashicons-edit"
									@click="showEditDay( 'working_days', workingDate )"
								></span>
								<div style="position:relative;">
									<span
										class="dashicons dashicons-trash"
										@click="confirmDeleteDay( workingDate )"
									></span>
									<div
										class="cx-vui-tooltip"
										v-if="deleteDayTrigger === workingDate"
									>
										<?php esc_html_e( 'Are you sure?', 'jet-appointments-booking' ); ?>
										<br><span
											class="cx-vui-repeater-item__confrim-del"
											@click="deleteDay( 'working_days', workingDate )"
										><?php
											esc_html_e( 'Yes', 'jet-appointments-booking' );
										?></span>
										/
										<span
											class="cx-vui-repeater-item__cancel-del"
											@click="deleteDayTrigger = null"
										><?php
											esc_html_e( 'No', 'jet-appointments-booking' );
										?></span></div>
									</div>
								</div>
						</div>
					</div>
				</div>
			</cx-vui-collapse>
		</div>

		<cx-vui-popup
			v-model="isNewSlot"
			body-width="600px"
			ok-label="<?php esc_html_e( 'Save', 'jet-appointments-booking' ) ?>"
			cancel-label="<?php esc_html_e( 'Cancel', 'jet-appointments-booking' ) ?>"
			@on-cancel="handleCancel"
			@on-ok="handleOk"
		>
			<div class="cx-vui-subtitle" slot="title"><?php
				esc_html_e( 'Work Hours', 'jet-appointments-booking' );
			?></div>
			<cx-vui-time
				format="HH:mm"
				slot="content"
				label="<?php esc_html_e( 'From', 'jet-appointments-booking' ); ?>"
				description="<?php esc_html_e( 'Starts from time', 'jet-appointments-booking' ); ?>"
				size="fullwidth"
				:wrapper-css="[ 'equalwidth' ]"
				:value="getSlotTime( 'currentFrom' )"
				@input="setTimeSettings( {
					key: 'currentFrom',
					value: $event,
				} )"
			></cx-vui-time>

			<cx-vui-time
				format="HH:mm"
				slot="content"
				label="<?php esc_html_e( 'To', 'jet-appointments-booking' ); ?>"
				description="<?php esc_html_e( 'Work to time', 'jet-appointments-booking' ); ?>"
				size="fullwidth"
				:wrapper-css="[ 'equalwidth' ]"
				:value="getSlotTime( 'currentTo' )"
				@input="setTimeSettings( {
					key: 'currentTo',
					value: $event,
				} )"
			></cx-vui-time>
		</cx-vui-popup>

		<cx-vui-popup
			v-model="editDay"
			body-width="600px"
			ok-label="<?php esc_html_e( 'Save', 'jet-appointments-booking' ) ?>"
			cancel-label="<?php esc_html_e( 'Cancel', 'jet-appointments-booking' ) ?>"
			@on-cancel="handleDayCancel"
			@on-ok="handleDayOk"
		>
			<div class="cx-vui-subtitle" slot="title"><?php
				esc_html_e( 'Select Days', 'jet-appointments-booking' );
			?></div>
			<cx-vui-input
				label="<?php esc_html_e( 'Days Label', 'jet-appointments-booking' ); ?>"
				description="<?php esc_html_e( 'Name of the current day (eg. name of the holiday)', 'jet-appointments-booking' ); ?>"
				:wrapper-css="[ 'equalwidth' ]"
				size="fullwidth"
				v-model="date.name"
				slot="content"
			></cx-vui-input>

			<cx-vui-component-wrapper
				:wrapper-css="[ 'equalwidth' ]"
				label="<?php esc_html_e( 'Start Date *', 'jet-appointments-booking' ); ?>"
				description="<?php esc_html_e( 'Pick a start day', 'jet-appointments-booking' ); ?>"
				slot="content"
			>
				<vuejs-datepicker
					input-class="cx-vui-input size-fullwidth"
					placeholder="<?php esc_html_e( 'Select Date', 'jet-appointments-booking' ); ?>"
					:format="datePickerFormat"
					:disabled-dates="disabledDate"
					:value="date.startTimeStamp"
					@selected="selectedDate( $event, 'start' )"
				></vuejs-datepicker>
			</cx-vui-component-wrapper>

			<cx-vui-component-wrapper
				:wrapper-css="[ 'equalwidth' ]"
				label="<?php esc_html_e( 'End Date', 'jet-appointments-booking' ); ?>"
				description="<?php esc_html_e( 'Pick a end day', 'jet-appointments-booking' ); ?>"
				slot="content"
			>
				<vuejs-datepicker
					input-class="cx-vui-input size-fullwidth"
					placeholder="<?php esc_html_e( 'Select Date', 'jet-appointments-booking' ); ?>"
					:format="datePickerFormat"
					:disabled-dates="disabledDate"
					:value="date.endTimeStamp"
					@selected="selectedDate( $event, 'end' )"
				></vuejs-datepicker>
			</cx-vui-component-wrapper>
		</cx-vui-popup>
	</div>
</div>