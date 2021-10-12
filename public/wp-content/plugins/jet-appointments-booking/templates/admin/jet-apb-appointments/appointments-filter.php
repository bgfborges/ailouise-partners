<div class="jet-apb-filters">
    <h2 class="jet-apb-filters-title"><?php esc_html_e( 'Filter', 'jet-appointments-booking' ); ?></h2>
    <div class="cx-vui-panel">
	    <template v-if="!hideFilters">
	        <template v-for="( filter, name ) in filters">
	            <cx-vui-component-wrapper
	                v-if="isVisible( name, filter, 'date-picker' )"
	                :wrapper-css="[ 'jet-apb-filter' ]"
	                :label="filter.label"
	            >
	                <vuejs-datepicker
	                    input-class="cx-vui-input size-fullwidth"
	                    :value="curentFilters[ name ]"
	                    :format="dateFormat"
	                    :monday-first="true"
	                    placeholder="<?php esc_html_e( 'dd/mm/yyyy', 'jet-appointments-booking' ); ?>"
	                    @input="updateFilters( $event, name, filter.type )"
	                ></vuejs-datepicker>
	                <span
	                    v-if="curentFilters[ name ]"
	                    class="jet-apb-date-clear"
	                    @click="updateFilters( '', name, filter.type )"
	                >&times; <?php _e( 'Clear', 'jet-appointments-booking' ); ?></span>
	            </cx-vui-component-wrapper>
	            <cx-vui-select
	                v-else-if="isVisible( name, filter, 'select' )"
	                :key="name"
	                :label="filter.label"
	                :wrapper-css="[ 'jet-apb-filter' ]"
	                :options-list="prepareObjectForOptions( filter.value )"
	                :value="curentFilters[ name ]"
	                @input="updateFilters( $event, name, filter.type )"
	            >
	            </cx-vui-select>
	            <cx-vui-input
	                v-else-if="isVisible( name, filter, 'search' )"
	                :key="name"
	                :label="filter.label"
	                :wrapper-css="[ 'jet-apb-filter' ]"
	                :value="curentFilters[ name ]"
	                @on-blur="updateFilters( $event, name, filter.type )"
	                @on-keyup.enter="updateFilters( $event, name, filter.type )"
	            >
	            </cx-vui-input>
	        </template>
	        <cx-vui-button
	            v-if="curentFilters"
	            class="jet-apb-clear-filters"
	            @click="clearFilter()"
	            button-style="accent-border"
	            size="mini"
	        >
	            <template slot="label"><?php esc_html_e( 'Clear Filters', 'jet-appointments-booking' ); ?></template>
	        </cx-vui-button>
	    </template>
	    <template v-else>
		    <h3><?php esc_html_e( 'All filters are hidden', 'jet-appointments-booking' ); ?></h3>
	    </template>
    </div>
</div>