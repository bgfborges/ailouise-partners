<div class="jet-apb-view">
    <cx-vui-button
        v-for="(view, index) in views"
        @click="updateView( index )"
        button-style="accent"
        size="mini"
        :key="index"
        :class="{ 'active': index === curentView, 'transition': true }"
    >
        <template slot="label"><span v-html="view.icon"></span>{{ view.label }}</template>
    </cx-vui-button>
</div>