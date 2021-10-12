<div
        class="jet-apb-pagination-wrapper"
>
    <h4 class="jet-apb-prepage-count">
        {{perPageInfo}}
    </h4>
    <cx-vui-pagination
            v-if="perPage < totalItems"
            :total="totalItems"
            :page-size="perPage"
            :current="pageNumber"
            @on-change="changePage"
    ></cx-vui-pagination>

    <div class="jet-apb-prepage">
        <h4 class="jet-apb-prepage-text">
            <?php esc_html_e( 'Results per page', 'jet-appointments-booking' ); ?>
        </h4>
        <input
            class="jet-apb-prepage-input"
            type="number"
            min="1"
            max="1000"
            v-model.number.lazy="perPage"
        >
    </div>
</div>