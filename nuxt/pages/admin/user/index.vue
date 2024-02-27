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
            :url="'/user/user/' + this.$store.state.user.data.company_id"
            :params="params"
            :click_row="clickRow"
        ></data-table>
        <user-detail
            ref="detail"
            v-model="data"
        ></user-detail>
    </div>
</template>
<script>
    import SearchForm from "@/components/Molecules/SearchForm";
    import DataTable from "@/components/Molecules/DataTable";
    import UserDetail from "@/components/CompanyDetail/UserDetail";
    export default {
        components: {
            UserDetail,
            SearchForm,
            DataTable,
        },
        data() {
            return {
                headers: [
                    {text: '名前', value: 'name'},
                    {text: 'メールアドレス', value: 'email'},
                ],
                items: [],
                total: 0,
                params: {
                    name: null,
                    email: null,
                },
                data: {},
                url: ''
            }
        },
        methods: {
            clickRow(item) {
                this.data = item;
                this.$refs.detail.show();
            },
        }
    }
</script>
