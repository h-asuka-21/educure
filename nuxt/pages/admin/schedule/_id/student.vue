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
            :url="data.url"
            :params="params"
        ></data-table>
    </div>
</template>
<script>
    import SearchForm from "../../../../components/Molecules/SearchForm";
    import DataTable from "../../../../components/Molecules/DataTable";

    export default {
        components: {
            SearchForm,
            DataTable,
        },
        data() {
            return {
                headers: [
                    {text: '名前', value: 'name'},
                    {text: 'メールアドレス', value: 'email'},
                    {text: '受講',search_label:'受講のみ表示', value: 'attendance_flg', type: 'flg'},
                    {text: '完了後進路', value: 'after_graduation_flg', type: 'after_graduation_flg'},
                    {text: '予約日', value: 'reserve_date', type:'date'},
                ],
                items: [],
                total: 0,
                params: {
                    company_code: null,
                    name: null,
                    after_graduation_flg: null,
                },
                data: {}
            }
        },
        created() {
            this.data.url = this.$utils.getApiUrl(this.$apis.schedule_student,true);
        },
    }
</script>
