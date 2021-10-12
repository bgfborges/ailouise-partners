<div>
	<div class="cx-vui-component cx-vui-component--equalwidth">
		<div class="cx-vui-component__meta">
			<label class="cx-vui-component__label"><?php esc_html_e( 'Clear excluded dates cache', 'jet-appointments-booking' ); ?></label>
			<div class="cx-vui-component__desc"><?php esc_html_e( 'Remove all dates from excluded dates table', 'jet-appointments-booking' ); ?></div>
		</div>
		<div class="cx-vui-component__control">
			<cx-vui-button
				@click="clearExcludedDates"
				:loading="clearingExcluded"
			>
				<span slot="label"><?php esc_html_e( 'Clear', 'jet-appointments-booking' ); ?></span>
			</cx-vui-button>
		</div>
	</div>
	<cx-vui-component-wrapper
		:wrapper-css="[ 'fullwidth-control' ]"
	>
		<div class="cx-vui-inner-panel">
			<div class="cx-vui-subtitle"><?php esc_html_e( 'Additional table columns' ); ?></div>
			<div class="cx-vui-component__desc"><?php esc_html_e( 'You can add any columns you want to Appointments table. We recommend add cloumns only on plugin set up. When you added new columns, you need to map these columns to apopriate form field in related booking form.' ); ?></div><br>

			<cx-vui-repeater
				button-label="<?php esc_html_e( 'New DB Column', 'jet-appointments-booking' ); ?>"
				button-style="accent"
				button-size="mini"
				v-model="settings.db_columns"
				@add-new-item="addNewColumn"
			>
				<cx-vui-repeater-item
					v-for="( column, columnIndex ) in settings.db_columns"
					:title="settings.db_columns[ columnIndex ]"
					:index="columnIndex"
					:collapsed="false"
					@clone-item="cloneColumn( $event, columnIndex )"
					@delete-item="deleteColumn( $event, columnIndex )"
					:key="'column' + columnIndex"
				>
					<cx-vui-input
						label="<?php esc_html_e( 'Column name', 'jet-appointments-booking' ); ?>"
						description="<?php esc_html_e( 'Name for additional DB column', 'jet-appointments-booking' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						size="fullwidth"
						:value="settings.db_columns[ columnIndex ]"
						@on-input-change="setColumnProp( columnIndex, $event.target.value )"
					></cx-vui-input>
				</cx-vui-repeater-item>
			</cx-vui-repeater>
			<div style="margin: 20px 0 -5px;"><?php
				printf( '<b>%s</b> %s', esc_html__( 'Warning:', 'jet-appointments-booking' ), esc_html__( 'If you change or remove any columns, all data stored in this columns will be lost!', 'jet-appointments-booking' ) );
			?></div>
		</div>
		<br>
		<cx-vui-button
			@click="saveDBColumns"
			:loading="savingDBColumns"
			button-style="accent"
		>
			<span slot="label"><?php
				esc_html_e( 'Save and update appointments table', 'jet-appointments-booking' );
			?></span>
		</cx-vui-button>
	</cx-vui-component-wrapper>
</div>
