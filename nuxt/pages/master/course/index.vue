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
            :url="'/admin' + $apis.course"
            :params="params"
            :click_row="clickRow"
        ></data-table>
        <simple-card-dialog
            ref="add"
            title="新規作成">
            <course-form
                @reload="v => {$refs.add.hide();$refs.table.getItems()}"/>
        </simple-card-dialog>
    </div>
</template>

<script>
import SearchForm from "../../../components/Molecules/SearchForm";
import DataTable from "../../../components/Molecules/DataTable";
import SimpleCardDialog from "@/components/Molecules/SimpleCardDialog";
import CourseForm from "@/components/Course/CourseForm";

export default {
    name: "index",
    components: {
        CourseForm,
        SimpleCardDialog,
        SearchForm,
        DataTable
    },
    data() {
        return {
            headers: [
                {text: 'コース名', value: 'name'},
                {text: '作成日', value: 'created_at', type: 'date'},
                {text: '最終更新日', value: 'updated_at', type: 'date'},
            ],
            items: [],
            total: 0,
            params: {
                name: null,
            },
            data: {}
        }
    },
    created() {
        this.$store.dispatch('page/setTitle', 'コース管理');
        this.$store.dispatch('page/hideTab');
        this.$store.dispatch('page/hideBackButton');
    },
    methods: {
        clickRow(item) {
            this.data = item;
            this.$router.push('/master/course/' + item.id);
        }
    }
}
</script>

<style scoped>

</style>
