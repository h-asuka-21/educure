<template>
    <div>
        <v-row dense>
            <v-col class="title_col">
                <p class="title">日報管理</p>
            </v-col>
        </v-row>
        <search-form
            :keys="headers"
            v-model="params"
            @search="$refs.table.getItems()"
        ></search-form>
        <data-table
            ref="table"
            :headers="headers"
            :url="'/student' + $apis.report"
            :params="params"
            :click_row="clickRow"
        ></data-table>
    </div>
</template>
<script>
    import SearchForm from "../../../components/Molecules/SearchForm";
    import DataTable from "../../../components/Molecules/DataTable";

    export default {
        components: {
            SearchForm,
            DataTable
        },
        created() {
            this.$store.dispatch('page/setTitle','日報')
            this.$store.dispatch('page/setTabs',[
                {name:'一覧',url:'/report'},
                {name:'新規作成',url:'/report/add'},
            ]);
            this.$store.dispatch('page/hideBackButton');
            this.$store.dispatch('page/setBackUrl', '/report');
        },
        data() {
            return {
                headers: [
                    {text: '提出日', value: 'created_at', type: 'date'},
                    {text: '個人評価', value: 'personal_evaluation'},
                    {text: '取り組んだこと', value: 'worked'},
                    {text: 'コメント', value: 'note'},
                ],
                items: [],
                total: 0,
                params: {
                    created_at: null,
                    work: null,
                    note: null,
                },
                data: {}
            }
        },
        methods: {
            clickRow(item) {
                this.data = item;
                this.$refs.dialog.$refs.dialog.show();
            },
        }
    }
</script>
