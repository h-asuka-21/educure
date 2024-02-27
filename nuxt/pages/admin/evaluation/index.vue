<template>
    <div>
        <search-form
            :keys="headers"
            v-model="params"
            @search="$refs.table.getItems()"
            class="mb-1"
        ></search-form>
        <data-table
            class="mt-3"
            ref="table"
            :headers="headers"
            :url="'/user' + $apis.missing_evaluation_items"
            :params="params"
        ></data-table>
    </div>
</template>

<script>
import SearchForm from "../../../components/Molecules/SearchForm";
import DataTable from "../../../components/Molecules/DataTable";

export default {
    name: "index",
    components: {DataTable, SearchForm},
    data() {
        return {
            headers:[
                {text:'受講者名', value: 'student_name', sortable: false},
                {text:'不足種別', value: 'missing_type', type: 'missing_type', sortable: false},
                {text:'理由', value: 'reason', searchable: false, sortable: false},
            ],
            items: [],
            total: 0,
            params: {
                student_name: null,
                missing_type: null,
                reason: null,
            },
            data: {}
        }
    },
    created() {
        this.$store.dispatch('page/setTitle', '不足評価管理');
        this.$store.dispatch('page/hideTab');
        this.$store.dispatch('page/hideBackButton');
    },
    methods: {
    }
}
</script>

<style scoped>

</style>
