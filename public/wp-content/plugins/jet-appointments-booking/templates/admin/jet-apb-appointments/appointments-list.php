<div class="jet-apb-listing">
    <jet-apb-pagination></jet-apb-pagination>

    <cx-vui-list-table
        :is-empty="! itemsList.length"
    >
        <cx-vui-list-table-heading
            :slots="columnsIDs"
            slot="heading"
        >
            <span
                 :key="column"
                 :slot="column"
                 :class="classColumn( column )"
                 v-for="column in columnsIDs"
                 @click="sortColumn( column )"
            >{{ getItemLabel( column ) }}<svg v-if="! notSortable.includes( column )" class="jet-apb-active-column-icon" width="10" height="5" viewBox="0 0 10 5" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.833374 0.333328L5.00004 4.5L9.16671 0.333328H0.833374Z" fill="#7B7E81"/></svg>
            </span>
        </cx-vui-list-table-heading>

	    <template slot="items">
		    <template
				v-for="( item, index ) in itemsList">
			    <div
					v-if="item.isGroupChief && groupView"
					:class="classGroupChief( item.group_ID )"
					@click="showGroup( item.group_ID )"
				>
					<div
						v-for="column in columnsIDs"
						:class="[ 'list-table-item__cell', 'cell--' + column ]"
					>
						<a v-if="column === 'order_id' && getItemValue( item, column )" :href="getOrderLink( item[ column ] )" target="_blank">#{{ getItemValue( item, column ) }}</a>
						<span v-else-if="column === 'status'"></span>
						<span
							v-else-if="column === 'actions'"
							class="jet-apb-actions"
						>
							<cx-vui-button
			                   button-style="link-error"
			                   size="link"
			                   @click="callPopup( 'delete-group', item )"
		                    >
		                        <span slot="label"><?php esc_html_e('Delete group', 'jet-appointments-booking'); ?></span>
							</cx-vui-button>
		                </span>
						<div v-else-if="column === 'ID'" class="group-toggl">
							<span class="group-toggl-arrow">
								<svg width="16" height="8" viewBox="0 0 16 8" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.799805 0.399902L7.9998 7.5999L15.1998 0.399902L0.799805 0.399902Z" fill="#7B7E81"/></svg>
							</span>
						</div>
						<div v-else-if="column === 'service'" class="group-title">
							<?php esc_html_e('Group', 'jet-appointments-booking'); ?> #{{item.group_ID}} - Contains {{ groupItemsCount[ item.group_ID ] }} <?php esc_html_e('appointments', 'jet-appointments-booking'); ?>
						</div>
						<template v-else-if="groupChiefColumnsIDs.includes(column)">{{ getItemValue( item, column ) }}</template>
						<template v-else></template>
					</div>
			    </div>
			    <div :class="classItem( item.group_ID )">
					<div
						v-for="column in columnsIDs"
						:class="[ 'list-table-item__cell', 'cell--' + column ]"
				    >
					    <a v-if="column === 'order_id' && getItemValue( item, column )" :href="getOrderLink( item[ column ] )" target="_blank">#{{ getItemValue( item, column ) }}</a>
					    <span
							v-else-if="column === 'status'"
							:class="{
		                        'notice': true,
		                        'notice-alt': true,
		                        'notice-success': isFinished( item.status ),
		                        'notice-warning': isInProgress( item.status ),
		                        'notice-error': isInvalid( item.status ),
		                    }"
					    >
		                   {{ getItemValue( item, column ) }}
		                </span>
						<span
							v-else-if="column === 'actions'"
							class="jet-apb-actions"
						>
		                   <cx-vui-button
			                   button-style="link-accent"
			                   size="link"
			                   @click="callPopup( 'update', item )"
		                   >
			                   <span slot="label"><svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.5 12.375V15.5H3.625L12.8417 6.28333L9.71667 3.15833L0.5 12.375ZM2.93333 13.8333H2.16667V13.0667L9.71667 5.51667L10.4833 6.28333L2.93333 13.8333ZM15.2583 2.69167L13.3083 0.741667C13.1417 0.575 12.9333 0.5 12.7167 0.5C12.5 0.5 12.2917 0.583333 12.1333 0.741667L10.6083 2.26667L13.7333 5.39167L15.2583 3.86667C15.5833 3.54167 15.5833 3.01667 15.2583 2.69167Z" fill="#007CBA"/></svg></span>
		                   </cx-vui-button>
		                   <cx-vui-button
			                   button-style="link-accent"
			                   size="link"
			                   @click="callPopup( 'info', item )"
		                   >
		                        <span slot="label"><svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M8.16667 4.83333H9.83333V6.5H8.16667V4.83333ZM8.16667 8.16666H9.83333V13.1667H8.16667V8.16666ZM9 0.666664C4.4 0.666664 0.666668 4.4 0.666668 9C0.666668 13.6 4.4 17.3333 9 17.3333C13.6 17.3333 17.3333 13.6 17.3333 9C17.3333 4.4 13.6 0.666664 9 0.666664ZM9 15.6667C5.325 15.6667 2.33333 12.675 2.33333 9C2.33333 5.325 5.325 2.33333 9 2.33333C12.675 2.33333 15.6667 5.325 15.6667 9C15.6667 12.675 12.675 15.6667 9 15.6667Z" fill="#007CBA"/></svg></span>
		                   </cx-vui-button>
		                   <cx-vui-button
			                   button-style="link-error"
			                   size="link"
			                   @click="callPopup( 'delete', item )"
		                   >
		                        <span slot="label"><svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M0.999998 13.8333C0.999998 14.75 1.75 15.5 2.66666 15.5H9.33333C10.25 15.5 11 14.75 11 13.8333V3.83333H0.999998V13.8333ZM2.66666 5.5H9.33333V13.8333H2.66666V5.5ZM8.91667 1.33333L8.08333 0.5H3.91666L3.08333 1.33333H0.166664V3H11.8333V1.33333H8.91667Z" fill="#D6336C"/></svg></span>
		                   </cx-vui-button>
		                </span>
						<template v-else>{{ getItemValue( item, column ) }}</template>
				    </div>
			    </div>
		    </template>
	    </template>
    </cx-vui-list-table>
    <jet-apb-pagination></jet-apb-pagination>
</div>