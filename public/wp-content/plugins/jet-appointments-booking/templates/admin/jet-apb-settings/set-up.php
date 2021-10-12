<div class="wrap jet-apb-setup">
	<div v-if="!isSet || isReset">
		<div v-if="1 === currentStep">
			<h3 class="cx-vui-subtitle"><?php
				_e( 'Step 1 of 4. Connect post types', 'jet-appointments-booking' )
				?></h3>
			<div class="cx-vui-panel">
				<div class="cx-vui-component"><p class="jet-apb-setup-descr"><?php
						printf( __( 'To start you need to create a post type for appointment services (and providers if you need it). If you have already created the required post types, please select it from the list below. If no - please go to <a href="%1$s">Post Types</a> page and create it.', 'jet-appointments-booking' ), add_query_arg( array( 'page' => 'jet-engine-cpt' ), admin_url( 'admin.php' ) ) );
						?></p></div>
				<cx-vui-select
						label="<?php _e( 'Services post type', 'jet-appointments-booking' ); ?>"
						description="<?php _e( 'Select post type to fill services from', 'jet-appointments-booking' ); ?>"
						:options-list="postTypes"
						:wrapper-css="[ 'equalwidth' ]"
						:size="'fullwidth'"
						v-model="settings.services_cpt"
				></cx-vui-select>
				<cx-vui-switcher
						label="<?php _e( 'Add providers', 'jet-appointments-booking' ); ?>"
						description="<?php _e( 'Check this if you want to manage services providers', 'jet-appointments-booking' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						v-model="settings.add_providers"
				></cx-vui-switcher>
				<cx-vui-select
						label="<?php _e( 'Provider post type', 'jet-appointments-booking' ); ?>"
						description="<?php _e( 'Select post type to fill providers from', 'jet-appointments-booking' ); ?>"
						:options-list="postTypes"
						:wrapper-css="[ 'equalwidth' ]"
						:size="'fullwidth'"
						:conditions="[
						{
							input: this.settings.add_providers,
							compare: 'equal',
							value: true,
						}
					]"
						v-model="settings.providers_cpt"
				></cx-vui-select>
			</div>
		</div>
		<div v-if="2 === currentStep">
			<h3 class="cx-vui-subtitle"><?php
					_e( 'Step 2 of 4. Setup DB tables', 'jet-appointments-booking' );
				?></h3>
			<div class="cx-vui-panel">
				<div class="cx-vui-component">
					<div class="cx-vui-component__meta">
						<label class="cx-vui-component__label"><?php
								_e( 'Required columns', 'jet-appointments-booking' );
							?></label>
						<div class="cx-vui-component__desc"><?php
								_e( 'Minimum set of required DB columns', 'jet-appointments-booking' );
							?></div>
					</div>
					<div class="cx-vui-component__control">
						<ul class="jet-apb-setup__db-columns">
							<li v-for="field in dbFields">{{ field }}</li>
						</ul>
					</div>
				</div>
				<cx-vui-component-wrapper
						:wrapper-css="[ 'fullwidth-control' ]"
				>
					<div class="cx-vui-inner-panel">
						<cx-vui-repeater
								button-label="<?php _e( 'New DB Column', 'jet-appointments-booking' ); ?>"
								button-style="accent"
								button-size="mini"
								v-model="settings.db_columns"
								@add-new-item="addNewColumn"
						>
							<cx-vui-repeater-item
									v-for="( column, columnIndex ) in settings.db_columns"
									:title="settings.db_columns[ columnIndex ].column"
									:collapsed="isCollapsed( column )"
									:index="columnIndex"
									@clone-item="cloneColumn( $event, columnIndex )"
									@delete-item="deleteColumn( $event, columnIndex )"
									:key="'column' + columnIndex"
							>
								<cx-vui-input
										label="<?php _e( 'Column name', 'jet-appointments-booking' ); ?>"
										description="<?php _e( 'Name for additional DB column', 'jet-appointments-booking' ); ?>"
										:wrapper-css="[ 'equalwidth' ]"
										size="fullwidth"
										:value="settings.db_columns[ columnIndex ].column"
										@on-input-change="setColumnProp( columnIndex, 'column', $event.target.value )"
								></cx-vui-input>
							</cx-vui-repeater-item>
						</cx-vui-repeater>
					</div>
				</cx-vui-component-wrapper>
			</div>
		</div>
		<div v-if="3 === currentStep">
			<h3 class="cx-vui-subtitle"><?php
					_e( 'Step 3 of 4. Setup working hours and days off', 'jet-appointments-booking' );
				?></h3>
			<div class="cx-vui-panel jet-apb-panel">
				<keep-alive>
					<div>
						<jet-apb-set-up-working-hours-settings :workingHoursSettings="settings"></jet-apb-set-up-working-hours-settings>
					</div>
				</keep-alive>
			</div>
		</div>
		<div v-if="4 === currentStep">
			<h3 class="cx-vui-subtitle"><?php
					_e( 'Step 4 of 4. Setup additional options', 'jet-appointments-booking' )
				?></h3>
			<div class="cx-vui-panel">
				<cx-vui-switcher
						label="<?php _e( 'WooCommerce Integration', 'jet-appointments-booking' ); ?>"
						description="<?php _e( 'Check this to connect appointments with WooCommerce checkout', 'jet-appointments-booking' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						v-model="settings.wc_integration"
				></cx-vui-switcher>
				<cx-vui-switcher
						label="<?php _e( 'Create a Single Service Booking Form', 'jet-appointments-booking' ); ?>"
						description="<?php _e( 'Create a booking form for a single service page (without services select field). JetEngine forms module should be enabled', 'jet-appointments-booking' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						v-model="settings.create_single_form"
				></cx-vui-switcher>
				<cx-vui-switcher
						label="<?php _e( 'Create Sample Page Booking Form', 'jet-appointments-booking' ); ?>"
						description="<?php _e( 'Create booking form for any static page (with services select field). JetEngine forms module should be enabled', 'jet-appointments-booking' ); ?>"
						:wrapper-css="[ 'equalwidth' ]"
						v-model="settings.create_page_form"
				></cx-vui-switcher>
				<template v-if="!isActiveFormBuilder">
                    <cx-vui-select
                            v-if="settings.create_single_form || setupData.create_page_form"
                            label="<?php _e( 'Сreate a form in', 'jet-appointments-booking' ); ?>"
                            description="<?php _e( 'Select post type to fill services from', 'jet-appointments-booking' ); ?>"
                            :options-list="formProviders"
                            :wrapper-css="[ 'equalwidth' ]"
                            :size="'fullwidth'"
                            v-model="settings.form_provider"
                    ></cx-vui-select>
                </template>
			</div>
		</div>
		<div v-if="4 < currentStep">
			<h3 class="cx-vui-subtitle"><?php
					_e( 'Congratulations! You\'re all set!', 'jet-appointments-booking' );
				?></h3>
			<div class="cx-vui-panel jet-apb-panel">
				<div class="jet-apb-panel-block">
					<h3 class="cx-vui-subtitle"><?php
							_e( 'Post Types', 'jet-appointments-booking' );
						?></h3>
					<p><span class="dashicons dashicons-admin-post"></span><a :href="log.services_page" target="_blank"><?php
								_e( 'Manage Services', 'jet-appointments-booking' );
							?></a></p>
					<p v-if="log.providers_page"><span class="dashicons dashicons-admin-post"></span><a :href="log.providers_page" target="_blank"><?php
								_e( 'Manage Providers', 'jet-appointments-booking' );
							?></a></p>
				</div>
				<div class="jet-apb-panel-block">
					<h3 class="cx-vui-subtitle"><?php
							_e( 'WooCommerce Integration', 'jet-appointments-booking' );
						?></h3>
					<p v-if="log.wc.enabled" class="jet-apb-wc-active" style="color: #46B450;"><span class="dashicons dashicons-yes"></span><b><?php
								_e( 'Enabled', 'jet-appointments-booking' );
							?></b></p>
					<p v-else class="jet-apb-wc-inactive" style="color: #C92C2C;"><span class="dashicons dashicons-no"></span><b><?php
								_e( 'Disabled', 'jet-appointments-booking' );
							?></b></p>
					<p v-if="log.wc.enabled && log.wc.link"><span class="dashicons dashicons-cart"></span><a :href="log.wc.link" target="_blank"><?php
								_e( 'Related product', 'jet-appointments-booking' )
							?></a></p>
				</div>
				<div v-if="log.forms.length" class="jet-apb-panel-block">
					<h3 class="cx-vui-subtitle"><?php
							_e( 'Created Forms', 'jet-appointments-booking' );
						?></h3>
					<p v-for="form in log.forms" :key="form.id">
						<span class="dashicons dashicons-clipboard"></span>
						<a :href="form.link" target="_blank">{{ form.title }}</a>
					</p>
					<p>
						<b>*</b> <?php _e( '<b>Note:</b> If you added additional DB columns, you need to add appropriate fields to the forms and notification settings.', 'jet-appointments-booking' ); ?>
					</p>
				</div>
				<div class="jet-apb-panel-block">
					<p><?php
							_e( 'To make all settings safe, you can disable Set Up Wizard in plugin settings (<b>Advanced</b> tab)', 'jet-appointments-booking' );
						?></p>
					<cx-vui-button
							button-style="accent"
							tag-name="a"
							:url="log.settings_url"
					>
						<span slot="label">
							<?php _e( 'Go to plugin settings', 'jet-appointments-booking' ); ?>
						</span>
					</cx-vui-button>
				</div>
			</div>
		</div>
		<div v-else class="jet-apb-setup__actions">
			<cx-vui-button
					button-style="link-accent"
					@click="prevStep"
					v-if="1 < currentStep"
			>
				<span slot="label">
					<span class="dashicons dashicons-arrow-left-alt2"></span>
					<?php _e( 'Prev', 'jet-appointments-booking' ); ?>
				</span>
			</cx-vui-button>
			<cx-vui-button
					button-style="accent"
					:loading="loading"
					@click="nextStep"
			>
				<span slot="label" v-if="currentStep === lastStep">
					<?php _e( 'Finish', 'jet-appointments-booking' ); ?>
				</span>
				<span slot="label" v-else>
					<?php _e( 'Next', 'jet-appointments-booking' ); ?>
				</span>
			</cx-vui-button>
		</div>
	</div>
	<div class="cx-vui-panel" v-else>
		<div class="jet-apb-reset">
			<p>
				<b><?php _e( 'Plugin is already set up.', 'jet-appointments-booking' ) ?></b>
				<?php _e( 'If you want to reset current plugin data and set it again press the button below', 'jet-appointments-booking' ); ?>
			</p>
			<cx-vui-button
					:button-style="'default'"
					@click="goToReset"
			>
				<span slot="label"><?php _e( 'Reset', 'jet-appointments-booking' ); ?></span>
			</cx-vui-button>
		</div>
	</div>
</div>