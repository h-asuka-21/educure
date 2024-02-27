<template>
    <div>
        <search-form
            :keys="headers"
            v-model="params"
            @search="$refs.table.getItems()"
        ></search-form>
        <data-table
            ref="table"
            :headers="headers"
            :url="'/user' + $apis.schedule"
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
            DataTable,
        },
        created() {
            this.$store.dispatch('page/setTitle','スケジュール管理')
            this.$store.dispatch('page/showTab');
            this.$store.dispatch('page/setTabs',[
                {name:'一覧',url:'/admin/schedule'},
                {name:'新規作成',url:'/admin/schedule/add'},
            ]);
            this.$store.dispatch('page/hideBackButton');
            this.$store.dispatch('page/setBackUrl', '/admin/schedule');
        },
        data() {
            return {
                headers: [
                    {text: 'スケジュール名', value: 'name'},
                    {text: '日付', value: 'date', type: 'date'},
                    {text: '開始時間', value: 'start_time',searchable:false},
                    {text: '終了時間', value: 'end_time',searchable:false},
                    {text: '席数', value: 'available_limit',searchable:false},
                    {text: '予約数', value: 'reserve_count',searchable:false},
                    {text: '受講数', value: 'attendance_count',searchable:false},
                    // {text: '講師・営業名', value: 'user_name'},
                ],
                items: [],
                total: 0,
                params: {
                    date: null,
                    start_time: null,
                    end_time: null,
                    available_limit: null,
                    user_name: null,
                },
                data: {}
            }
        },
        methods: {
            clickRow(item) {
                this.data = item;
                this.$router.push('schedule/' + item.id);
            },
        }
    }
</script>
