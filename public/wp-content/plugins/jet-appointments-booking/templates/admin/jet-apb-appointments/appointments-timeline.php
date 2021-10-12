<div class="jet-apb-gantt-chart">
	<div class="jet-apb-gc-settings">
		<cx-vui-component-wrapper
			:wrapper-css="[ 'jet-apb-gc-datepicker' ]"
			label="<?php esc_html_e('Date', 'jet-appointments-booking'); ?>"
		>
			<vuejs-datepicker
				input-class="cx-vui-input size-default"
				v-model="selectedDate"
				:format="dateFormat"
				:monday-first="true"
			></vuejs-datepicker>
		</cx-vui-component-wrapper>
		<cx-vui-time
			class="jet-apb-gc-start-time"
			label="<?php esc_html_e( 'From', 'jet-appointments-booking' ); ?>"
			format="HH:mm"
			:hideClearButton="true"
			v-model="selectedStartTime"
		></cx-vui-time>
		<cx-vui-time
			class="jet-apb-gc-end-time"
			label="<?php esc_html_e( 'To', 'jet-appointments-booking' ); ?>"
			format="HH:mm"
			:hideClearButton="true"
			v-model="selectedEndTime"
		></cx-vui-time>
		<cx-vui-select
			label="<?php esc_html_e('Time Interval', 'jet-appointments-booking'); ?>"
			:wrapper-css="[ 'jet-apb-gc-time-interval' ]"
			:options-list="intervalOptions"
			v-model="timeInterval"
		>
		</cx-vui-select>
	</div>
	<v-gantt-chart
		:datas="itemsList"
		:startTime="startTime"
		:endTime="endTime"
		:scale="timeInterval"
		:timelines="timelines"
		:cellHeight="cellHeight"
		:cellWidth="cellWidth"
		:titleHeight="titleHeight"
		:titleWidth="titleWidth"
		:customGenerateBlocks="true"
		:enableGrab="false"
		:hideYScrollBar="true"
		:hideXScrollBar="false"
	>
		<template v-slot:title>
			<?php esc_html_e( 'Services', 'jet-appointments-booking' ); ?>
		</template>

		<template v-slot:block="{ data }">
			<div
				class="jet-apb-gantt-leftbar"
				:style="leftSidebarStyle()">
				{{ data.service }}
			</div>
			<div class="jet-apb-gantt-items"
			     :style="itemsWrapperStyle()">
				<div v-for="item in data.gtArray"
					v-if="itemVisible( item.start, item.end )"
					class="gantt-block-item"
					:key="item.ID"
					:style="itemStyle( item.start, item.end )"
					@click="callPopup( 'info', item.itemData )">
					<div
						:class="[ 'gantt-block-item-content', 'gantt-block-item-content-' + item.itemData.status  ]">
						<div v-if="item.provider">Provider: {{ item.provider }}</div>
						<div v-if="item.itemData.name">User: {{ item.itemData.name }}</div>
						<div v-else>Email: {{ item.itemData.user_email }}</div>
					</div>
				</div>
			</div>
		</template>
	</v-gantt-chart>
</div>