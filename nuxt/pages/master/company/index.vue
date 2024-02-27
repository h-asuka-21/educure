<template>
    <div>
        <search-form
            :keys="headers"
            v-model="params"
            @search="$refs.table.getItems()"
            :show_add="true"
            @add="$refs.add.show()"
        ></search-form>
        <data-table
            class="mt-3"
            ref="table"
            :headers="headers"
            :url="'/admin' + $apis.company"
            :params="params"
            :click_row="clickRow"
        ></data-table>
        <simple-card-dialog
            ref="add"
        title="新規作成">
            <company-form @reload="v => {$refs.add.hide();$refs.table.getItems()}"/>
        </simple-card-dialog>
    </div>
</template>
<script>
    import SearchForm from "../../../components/Molecules/SearchForm";
    import DataTable from "../../../components/Molecules/DataTable";
    import SimpleCardDialog from "@/components/Molecules/SimpleCardDialog";
    import CompanyForm from "@/components/Company/CompanyForm";

    export default {
        components: {
            CompanyForm,
            SimpleCardDialog,
            SearchForm,
            DataTable,
        },
        data() {
            return {
                headers: [
                    {text: '企業名', value: 'name'},
                    {text: '登録日', value: 'created_at', type: 'date'},
                    {text: '受講者数', value: 'student_count', searchable: false},
                    {text: '受講中人数', value: 'taking_student_count', searchable: false},
                    {text: '今月開始人数', value: 'active_this_month_count', searchable: false},
                    {text: '今月完了人数', value: 'graduated_this_month_count', searchable: false},
                    {text: '今月辞退者人数', value: 'retired_this_month_count', searchable: false},
                    {text: '担当者数', value: 'user_count', searchable: false},
                ],
                items: [],
                total: 0,
                params: {
                    name: null,
                    created_at: null,
                    student_count: null,
                    taking_student_count: null,
                    user_count: null,
                },
                data: {}
            }
        },
        created() {
            this.$store.dispatch('page/setTitle', '企業');
            this.$store.dispatch('page/hideBackButton');
            this.$store.dispatch('page/hideTab');
        },
        methods: {
            clickRow(item) {
                this.data = item;
                this.$router.push('/master/company/'+ item.id);
            },
        }
    }
</script>
