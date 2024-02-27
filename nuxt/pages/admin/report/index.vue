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
            :url="'/user' + $apis.report"
            :params="params"
            :click_row="clickRow"
        ></data-table>
        <report-form-dialog
            ref="dialog"
            v-model="data"
        ></report-form-dialog>
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
                    {text: '氏名', value: 'name'},
                    {text: '提出日', value: 'created_at', type: 'date'},
                    {text: '個人評価', value: 'personal_evaluation'},
                    {text: '取り組んだこと', value: 'worked'},
                ],
                items: [],
                total: 0,
                params: {
                    name: null,
                    created_at: null,
                    worked: null,
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
