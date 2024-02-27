<template>
    <v-data-table
        class="px-0 data-table"
        v-bind:class="getClass()"
        :loading="loading"
        :headers="headers"
        :items="items"
        :server-items-length="total"
        :options.sync="options"
        :footer-props="{itemsPerPageOptions: [30, 50]}"
        :hide-default-footer="hide_footer"
        :hide-default-header="hide_header"
        :fixed-header="fixedHeader"
        :height="height"
    >
        <template v-slot:item="{item,headers}">
            <tr>
                <td v-for="(header,key) in headers" :key="key" v-bind:class="header.class"
                    @click="click_row(item,header)"
                >
                    <v-btn
                        v-if="header.type === 'btn'"
                        :color="header.color"
                        :dark="header.dark"
                        @click="header.action(item)"
                    >
                        {{header.label}}
                    </v-btn>
                    <v-btn
                        v-else-if="header.type === 'link_button'"
                        :color="header.color"
                        :dark="!!header.dark && !empty(item[header.value])"
                        @click="link(item[header.value])"
                        :disabled="empty(item[header.value])"
                    >
                        {{header.label}}
                    </v-btn>
                    <p v-else class="mb-0">{{ item[header.value]?header.prefix:'' }}{{ showData(item[header.value], header) }}{{item[header.value]?header.suffix:'' }}</p>
                </td>
            </tr>
        </template>
    </v-data-table>
</template>
<script>
export default {
    name: 'DataTable',
    data() {
        return {
            items: [],
            options: {},
            total: 0,
            loading: true
        }
    },
    props: {
        // th
        headers: {
            type: Array,
            required: true,
        },
        // APIのURL
        url: {
            type: String,
            required: true,
        },
        // 検索パラメータなど
        params: {
            type: Object,
            required: false,
        },
        // デフォルト表示件数
        per_page: {
            type: Number,
            default: 10,
            required: false,
        },
        // 行をクリックしたときに発火するイベント
        click_row: {
            type: Function,
            default(v,h) {
                if(h.type !== 'btn' && h.type !== 'link_button'){
                    this.$emit('click_row', v);
                }
            }
        },
        // 行数が多い場合フォントサイズなどを落とすprops
        dense: {
            type: Boolean,
            default: false
        },
        hide_footer: {
            type: Boolean,
            default: false
        },
        hide_header: {
            type: Boolean,
            default: false
        },
        fixedHeader: {
            type: Boolean,
            default: false
        },
        height: {
            default: undefined
        },
        white:{
            type:Boolean,
            default: false
        }
    },
    watch: {
        options(val, old) {
            this.getItems(val);
        },
        url(val){
            this.getItems(this.options);
        }

    },
    methods: {
        /**
         * 一覧を取得（laravelのpaginate）
         * @param options
         */
        async getItems(options = null) {
            try {
                this.$emit('loading', true);
                this.loading = true;
                this.items = [];
                const params = this.getParams(options);
                const resp = await this.$axios.get(this.url, {params: params})
                console.log(resp);
                console.log(resp.data.total);
                if (resp.data.total !== undefined) {
                    this.total = resp.data.total;
                    this.items = resp.data.data;
                } else {
                    this.total = resp.total;
                    this.items = resp.data;
                }
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.loading = false;
                this.$emit('loading', false);
            }
        },
        getParams(options) {
            let params = {};
            if (options === null) {
                params.page = 1;
            } else {
                params = {
                    per_page: options.itemsPerPage,
                    page: options.page
                };
                if (options.sortBy !== undefined && options.sortBy.length > 0) {
                    params.sort_by = options.sortBy[0];
                    if (options.sortDesc[0] !== undefined) {
                        if (options.sortDesc[0]) {
                            params.sort_order = 'desc';
                        } else {
                            params.sort_order = 'asc';
                        }
                    }
                }
            }
            if (this.params !== undefined) {
                for (let key in this.params) {
                    params[key] = this.params[key];
                }
            }
            return params;
        },
        getClass() {
            if (this.white) {
                return '';
            }
            return this.$utils.getThemeClass();
        },
        showData(item, header) {
            if (item === undefined || item === null) {
                return '';
            }
            if (header.type === 'date') {
                return this.$moment(item).format('YYYY年M月D日');
            } else if (header.type === 'date_time') {
                return this.$moment(item).format('YYYY年M月D日 HH:mm');
            } else if (header.type === 'time') {
                return this.$moment(item).format('HH:mm');
            } else if (header.type === 'price') {
                if (typeof item !== 'number') {
                    return item;
                }
                return item.toLocaleString();
            } else if (header.type === 'after_graduation_flg') {
                return this.$selects.after_graduation_flg[item].text;
            } else if (header.type === 'flg') {
                return item ? '○' : ''
            } else if (header.type === 'test_type') {
                return this.$selects.test_type[item].text;
            } else if (header.type === 'missing_type') {
                return this.$selects.missing_type[item].text;
            } else if (header.type === 'int'){
                return Math.round(item * 10) /10;
            }
            return item;

        },

        empty(item){
            return item === undefined
                || item === null
                || item === ''
                || (Array.isArray(item) && item.length === 0);
        },
        link(item){
            location.href = item;
        }
    }
}
</script>
<style lang="scss">
.data-table {
    padding: 0;
    border-radius: 4px;

    &.small_font {
        th, td {
            font-size: 0.72rem !important;
        }
    }
    background-color: transparent!important;
    .v-data-table-header{
        th{
            border-bottom: none!important;
        }
    }
    .v-data-table__wrapper{
        border-radius: 4px;
    }
    &.master,
    &.admin,
    &.student{
        .v-data-footer{
            margin-top: 8px;
            border-radius: 4px;
            border-top :0
        }
    }
    &.master{
        .v-data-table-header ,.v-data-footer{
            background-color: #e8cdec;
        }
        tbody{
            tr{
                background-color: #f1e0f3;
                &:hover{
                    background-color: #e0bbe5 !important;
                }
            }
        }
    }
    &.admin{
        .v-data-table-header ,.v-data-footer{
            background-color: #9ee6ef!important;
        }
        tbody{
            tr{
                background-color: #caf1f6 !important;
                &:hover{
                    background-color: #b4ecf3 !important;
                }
            }
        }
    }
    &.student{
        tbody{
            tr{
                &:hover{
                    background-color: #B2EBF2!important;
                }
            }
        }
    }
}

td {
    &.no_warp {
        white-space: nowrap;
    }

    &.ellipsis {
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    &.mw-300 {
        max-width: 300px;
    }

    &.mw-250 {
        max-width: 250px;
    }

    &.mw-4rem {
        max-width: 4rem;
    }

    &.mw-5rem {
        max-width: 5rem;
    }

    &.min-w140 {
        min-width: 140px;
    }

    &.min-w110 {
        min-width: 110px;
    }

    &.min-w124 {
        min-width: 124px;
    }
}
</style>
