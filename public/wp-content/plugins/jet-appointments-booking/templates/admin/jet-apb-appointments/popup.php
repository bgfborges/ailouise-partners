<div>
	<cx-vui-popup
			v-model="isShow"
			body-width="400px"
			:footer="false"
            @on-cancel="cancelPopup"
            class="jet-apb-popup"
	>
		<div class="cx-vui-subtitle" slot="title">
			<template v-if="popUpState === 'delete'">
				<?php esc_html_e('Are you sure? Deleted appointment can\'t be restored.', 'jet-appointments-booking'); ?>
			</template>
			<template v-if="popUpState === 'delete-group'">
				<?php esc_html_e('Do you want to delete ALL ITEMS IN THE GROUP? Deleted appointments can\'t be restored.', 'jet-appointments-booking'); ?>
			</template>
			<template v-else-if="popUpState === 'update'">
				<?php esc_html_e('Edit Appointment:', 'jet-appointments-booking'); ?>
			</template>
			<template v-else-if="popUpState === 'new'">
				<?php esc_html_e('Add New Appointment:', 'jet-appointments-booking'); ?>
			</template>
			<template v-else-if="popUpState === 'info'">
				<?php esc_html_e('Appointment Details:', 'jet-appointments-booking'); ?>
			</template>
		</div>

        <div :class="contentClass()" slot="content">
	        <div class="jet-apb-details-fields">
		        <template  v-if="popUpState !== 'delete' && popUpState !== 'delete-group' ">
		            <div class="jet-apb-details__appoinment">
		                <div class="jet-apb-details__appoinment-id">
		                    <div class="jet-apb-details__label">{{ getItemLabel( 'ID' ) }}:</div>
		                    <div class="jet-apb-details__content">{{ getItemValue( action.content, 'ID' ) }}</div>
		                </div>
		                <div v-if="getItemValue( action.content, 'order_id' )" class="jet-apb-details__appoinment-order-id">
		                    <div class="jet-apb-details__label">{{ getItemLabel( 'order_id' ) }}:</div>
		                    <div class="jet-apb-details__content">
		                        <a :href="getOrderLink( action.content[ 'order_id' ] )" target="_blank">#{{ getItemValue( action.content, 'order_id' ) }}</a>
		                    </div>
		                </div>
		            </div>
	
			        <template v-for="item in fields">
		                <div v-if="beVisible( item )"
							:key="item"
							:class="[ 'jet-apb-details__item', 'jet-apb-details__item-' + item ]"
		                >
			                <div class="jet-apb-details__label">{{ getItemLabel( item ) }}:</div>
			                <div class="jet-apb-details__content">
			                    <template v-if="popUpState === 'new' || popUpState === 'update'">
				                    <cx-vui-input
					                    v-if="fieldType( item, 'input' )"
					                    :value="content[ item ]"
					                    @input="changeValue( $event, item )"
				                    >
				                    </cx-vui-input>
				                    <cx-vui-select
					                    v-else-if="fieldType( item, 'select' )"
					                    :value="content[ item ]"
					                    :options-list="getOptionList( item )"
					                    @input="changeValue( $event, item )"
				                    >
				                    </cx-vui-select>
				                    <div
						                v-else-if="fieldType( item, 'date' )"
						                v-click-outside="hideDatepicker"
						                @click="showDatepicker"
						                class="vuejs-datepicker-wrapper"
				                    >
					                    <cx-vui-input
							                :readonly="true"
							                :value="content[ item ]"
					                    >
					                    </cx-vui-input>
					                    <vuejs-datepicker
							                 v-if="datePickerVisibility"
							                 @input="changeValue( $event, item, 'date-picker' )"
							                 input-class="cx-vui-input size-fullwidth"
							                 :format="dateFormat"
							                 :inline="true"
							                 :monday-first="true"
							                 placeholder="<?php esc_html_e( 'dd/mm/yyyy', 'jet-appointments-booking' ); ?>"
						                    >
						                    <div
							                    v-if="daySlots || daySlotsIsLoad"
							                    slot="beforeCalendarHeader"
							                    :class="{ 'day-slots':true, 'day-slots-is-load': daySlotsIsLoad }"
						                    >
							                    <svg width="16" class="loader-icon" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M15.9023 9.06541L14.4611 9.55376C14.3145 10.2375 14.0214 10.8967 13.6305 11.5316L14.2901 12.899C14.3145 12.9478 14.2901 12.9966 14.2656 13.0455L12.971 14.3396C12.9221 14.364 12.8733 14.3884 12.8244 14.364L11.4565 13.7047C10.8458 14.0954 10.1863 14.364 9.47786 14.5105L8.98931 15.9512C8.98931 15.9756 8.96489 16 8.8916 16H7.08397C7.03511 16 6.98626 15.9756 6.96183 15.9267L6.47328 14.4861C5.78931 14.3396 5.12977 14.0466 4.49466 13.6559L3.12672 14.3152C3.07786 14.3396 3.02901 14.3152 2.98015 14.2908L1.6855 12.9966C1.66107 12.9478 1.63664 12.899 1.66107 12.8501L2.32061 11.4828C1.92977 10.8723 1.66107 10.213 1.5145 9.50493L0.0732824 9.01658C0.0244275 8.99216 0 8.96774 0 8.89449L0 7.08759C0 7.03875 0.0244275 6.98992 0.0732824 6.9655L1.5145 6.47715C1.66107 5.79346 1.9542 5.13418 2.34504 4.49933L1.6855 3.13194C1.66107 3.08311 1.6855 3.03427 1.70992 2.98544L3.00458 1.69131C3.05344 1.66689 3.10229 1.64247 3.15114 1.66689L4.51908 2.32616C5.12977 1.93548 5.78931 1.66689 6.49771 1.52038L6.98626 0.0797482C7.01069 0.0309124 7.03511 0.00649452 7.1084 0.00649452L8.91603 0.00649452C8.96489 -0.0179234 9.01374 0.0309124 9.03817 0.0797482L9.52672 1.52038C10.2107 1.66689 10.8702 1.9599 11.5053 2.35058L12.8733 1.69131C12.9221 1.66689 12.971 1.69131 13.0198 1.71572L14.3145 3.00986C14.3389 3.05869 14.3634 3.10753 14.3389 3.15636L13.6794 4.52374C14.0702 5.13418 14.3389 5.79346 14.4855 6.50157L15.9267 6.98992C15.9756 7.01434 16 7.03875 16 7.11201V8.91891C15.9756 8.99216 15.9511 9.04099 15.9023 9.06541ZM11.5786 6.9655C10.9924 4.98768 8.91603 3.86447 6.96183 4.45049C4.98321 5.03651 3.85954 7.11201 4.4458 9.06541C5.03206 11.0432 7.1084 12.1664 9.0626 11.5804C11.0412 11.0188 12.1649 8.94332 11.5786 6.9655Z"/></svg>
							                    <cx-vui-button
								                    class="day-slots-cansel"
								                    @click.stop="hideDaySlots"
								                    button-style="link-accent"
								                    size="link"
							                    >
								                    <template slot="label"><svg width="20" height="20" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 3.00671L8.00671 7L12 10.9933L10.9933 12L7 8.00671L3.00671 12L2 10.9933L5.99329 7L2 3.00671L3.00671 2L7 5.99329L10.9933 2L12 3.00671Z"></path></svg></template>
							                    </cx-vui-button>
							                    <div class="day-slots-content">
								                    <div v-if="getContentType( daySlots ) != 'object'" class="no-available-slot">{{ daySlots }}</div>
													<div
														v-else
														v-for="( slot, day ) in daySlots"
														@click="changeValue( slot, 'slot', 'slot' )"
														:class="{ 'day-slot':true }"
													>
														{{ timestampToDate( slot.from, 'HH:mm') }} - {{ timestampToDate( slot.to, 'HH:mm') }}
													</div>
							                    </div>
						                    </div>
					                    </vuejs-datepicker>
				                    </div>
				                    <cx-vui-textarea
					                    v-else-if="fieldType( item, 'textarea' )"
					                    :value="content[ item ]"
					                    :rows="5"
					                    @input="changeValue( $event, item )"
				                    >
				                    </cx-vui-textarea>
			                        <template v-else>{{ getItemValue( content, item ) }}</template>
			                    </template>
			                    <template v-else="popUpState === 'info'">{{ getItemValue( content, item ) }}</template>
			                </div>
		                </div>
			            <div v-if="multi_booking && item === 'slot_end' && popUpState === 'new'"
			                 :class="[ 'jet-apb-details__item', 'jet-apb-details__item-app-details' ]"
			            >
				            <div class="jet-apb-details__label"><?php esc_html_e('Appointment details:', 'jet-appointments-booking'); ?></div>
				            <div class="jet-apb-details__content">

					            <div v-if="!action.appointmentsList.length">
						            <?php esc_html_e('No appointments selected :(', 'jet-appointments-booking'); ?>
					            </div>
					            <div
							        v-else
							        v-for="( item, index ) in action.appointmentsList"
							        class="jet-apb-details-app-list-item"
					            >
						            <div class="jet-apb-item-service-provider">{{ getItemValue( item, 'service' ) }}{{ getItemValue( item, 'provider' ) }}</div>
						            <div>{{ getItemValue( item, 'date' ) }}</div>
						            <div>{{ getItemValue( item, 'slot' ) }} - {{ getItemValue( item, 'slot_end' ) }}</div>
						            <div class="jet-apb-details-delete-item"
						                @click="deleteFromAppointmentsList( index )"
						            ><svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M1.23529 0L0 1.23529L5.76477 7.00007L0.000132676 12.7647L1.23543 14L7.00007 8.23536L12.7647 14L14 12.7647L8.23536 7.00007L14.0001 1.23529L12.7648 0L7.00007 5.76477L1.23529 0Z" fill="#8A8B8D"></path></svg></div>
					            </div>
				            </div>
			            </div>
			        </template>
		        </template>
	        </div>

            <div class="jet-apb-popup-actions">
                <template v-if="popUpState === 'new'">
                    <cx-vui-button
                        class="jet-apb-popup-button-add-new"
                        @click="addNewItem()"
                        button-style="accent"
                        size="mini"
                    >
                        <template slot="label"><?php esc_html_e('Add New', 'jet-appointments-booking'); ?></template>
                    </cx-vui-button>

                    <cx-vui-button
                        class="jet-apb-popup-button-cancel"
                        @click="cancelPopup()"
                        button-style="accent-border"
                        size="mini"
                    >
                        <template slot="label"><?php esc_html_e('Cancel', 'jet-appointments-booking'); ?></template>
                    </cx-vui-button>
                </template>
                <template v-else-if="popUpState === 'update'">
                    <cx-vui-button
                        class="jet-apb-popup-button-save"
                        @click="updateItem()"
                        button-style="accent"
                        size="mini"
                    >
                        <template slot="label"><?php esc_html_e('Save', 'jet-appointments-booking'); ?></template>
                    </cx-vui-button>

                    <cx-vui-button
                        class="jet-apb-popup-button-cancel"
                        @click="cancelPopup()"
                        button-style="accent-border"
                        size="mini"
                    >
                        <template slot="label"><?php esc_html_e('Cancel', 'jet-appointments-booking'); ?></template>
                    </cx-vui-button>
                </template>
	            <template v-else-if="popUpState === 'delete' || popUpState === 'delete-group'">
		            <cx-vui-button
			            class="jet-apb-popup-button-cancel"
			            @click="cancelPopup()"
			            button-style="accent-border"
			            size="mini"
		            >
			            <template slot="label"><?php esc_html_e('Cancel', 'jet-appointments-booking'); ?></template>
		            </cx-vui-button>

		            <cx-vui-button
			            class="jet-apb-popup-button-delete"
			            @click="deleteItem()"
			            button-style="accent-border"
			            size="mini"
		            >
			            <template slot="label">
				            <svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.999959 13.8333C0.999959 14.75 1.74996 15.5 2.66663 15.5H9.33329C10.25 15.5 11 14.75 11 13.8333V3.83333H0.999959V13.8333ZM2.66663 5.5H9.33329V13.8333H2.66663V5.5ZM8.91663 1.33333L8.08329 0.5H3.91663L3.08329 1.33333H0.166626V3H11.8333V1.33333H8.91663Z" fill="#007CBA"/></svg>
				            <?php esc_html_e('Delete', 'jet-appointments-booking'); ?>
			            </template>
		            </cx-vui-button>
	            </template>
                <template v-else="popUpState === 'info'">
                    <cx-vui-button
                        class="jet-apb-popup-button-edit"
                        @click="editItem()"
                        button-style="accent"
                        size="mini"
                    >
                        <template slot="label">
	                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.5 12.375V15.5H3.625L12.8417 6.28333L9.71667 3.15833L0.5 12.375ZM2.93333 13.8333H2.16667V13.0667L9.71667 5.51667L10.4833 6.28333L2.93333 13.8333ZM15.2583 2.69167L13.3083 0.741667C13.1417 0.575 12.9333 0.5 12.7167 0.5C12.5 0.5 12.2917 0.583333 12.1333 0.741667L10.6083 2.26667L13.7333 5.39167L15.2583 3.86667C15.5833 3.54167 15.5833 3.01667 15.2583 2.69167Z" fill="white"/></svg>
	                        <?php esc_html_e('Edit', 'jet-appointments-booking'); ?>
                        </template>
                    </cx-vui-button>

                    <cx-vui-button
                        class="jet-apb-popup-button-delete"
                        @click="deleteItem()"
                        button-style="accent-border"
                        size="mini"
                    >
                        <template slot="label">
	                        <svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.999959 13.8333C0.999959 14.75 1.74996 15.5 2.66663 15.5H9.33329C10.25 15.5 11 14.75 11 13.8333V3.83333H0.999959V13.8333ZM2.66663 5.5H9.33329V13.8333H2.66663V5.5ZM8.91663 1.33333L8.08329 0.5H3.91663L3.08329 1.33333H0.166626V3H11.8333V1.33333H8.91663Z" fill="#007CBA"/></svg>
	                        <?php esc_html_e('Delete', 'jet-appointments-booking'); ?>
                        </template>
                    </cx-vui-button>
                </template>
            </div>
        </div>
	</cx-vui-popup>
</div>