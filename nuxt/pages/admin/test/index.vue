<template>
    <div>
        <v-row dense>
            <v-col class="title_col">
                <p class="title">テスト管理</p>
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
            :url="'/user' + $apis.test"
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
        data() {
            return {
                headers: [
                    {text:'テスト名', value: 'name'},
                    {text:'タイプ', value: 'test_type',search: 'test_type',type:'test_type'},
                    {text:'設問数', value: 'question_count',searchable: false, suffix:'問'},
                    {text:'平均正答数', value: 'avg_score',searchable: false,type:'int', suffix:'問'},
                ],
                items: [],
                total: 0,
                params: {
                    name: null,
                    course_id: null,
                    curriculum_id: null,
                    available_limit: null,
                    test_type: null,
                },
                data: {}
            }
        },
        methods: {
            clickRow(item) {
                this.data = item;
                this.$router.push('/admin/test/' + item.id);
            },
        }
    }
</script>
