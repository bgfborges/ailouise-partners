<div class="jet-apb-calendar">
	<v-calendar
		class="max-w-full"
		:masks="masks"
		:attributes="itemsList"
	>
		<template v-slot:day-content="{ day, attributes }">
			<div :class="containerClass( attributes )">
				<div class="jet-apb-calendar-day-number">{{ day.day }}</div>
				<div class="jet-apb-calendar-day-content">
					<div
						v-for="attr in attributes"
						:key="attr.key"
						class="jet-apb-calendar-day-appointment"
						@click="callPopup( 'info', attr.customData )"
					>
						<div class="jet-apb-scroll-text">
							<span class="jet-apb-appointment-slot">{{ attr.customData.slot }} - {{ attr.customData.slot_end }}</span>
							<strong v-if="attr.customData.service && columns.includes('service') ">{{ getItemValue( attr.customData, 'service' ) }}</strong>
							<span v-if="attr.customData.provider && columns.includes('provider') "> - {{ getItemValue( attr.customData, 'provider' ) }}</span>
						</div>
					</div>
				</div>
				<div class="jet-apb-calendar-day-more-button">
					{{ getRemainingItemCount( attributes ) }} <?php esc_html_e( 'more', 'jet-appointments-booking' ); ?>
				</div>
			</div>
		</template>
	</v-calendar>
</div>