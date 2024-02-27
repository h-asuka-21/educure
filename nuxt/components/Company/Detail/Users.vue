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
            :url="$utils.getApiUrl($apis.user,true)"
            :params="params"
            :click_row="clickRow"
        ></data-table>
        <user-dialog
            ref="detail"
            v-model="user"
            @reload="$refs.table.getItems()"
        ></user-dialog>
    </div>
</template>
<script>
import SearchForm from "~/components/Molecules/SearchForm";
import DataTable from "~/components/Molecules/DataTable";
import UserDialog from "~/components/User/UserDialog";
export default {
    name: 'Users',
    components: {UserDialog, DataTable, SearchForm},
    data() {
        return {
            headers: [
                {text: '名前', value: 'name'},
                {text: 'メールアドレス', value: 'email'},
                {text: '管理者', value: 'role',type:'flg',searchable:false},
            ],
            params: {
                name: null,
                email: null,
                role:null,
            },
            url: '',
            user: {}
        }
    },
    methods: {
        clickRow(item) {
            this.user = item;
            this.$refs.detail.show();
        },
    }
}
</script>
