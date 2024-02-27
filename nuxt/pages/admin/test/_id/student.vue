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
                    {text: '点数', value: 'score'},
                    {text: '受講日時', value: 'created_at', type: 'date'},
                ],
                items: [],
                total: 0,
                params: {
                    name: null,
                    score: null,
                    created_at: null,
                },
                data: {}
            }
        },
        created() {
            const id = this.$route.params.id;
            this.data.url = '/user/test/student/' + id;
        },
        methods: {
            // clickRow(item) {
            //     this.data = item;
            //     this.$refs.detail.show();
            // },
        }
    }
</script>
