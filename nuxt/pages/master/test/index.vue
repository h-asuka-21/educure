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
            :url="'/admin' + $apis.test"
            :params="params"
            :click_row="clickRow"
        ></data-table>
    </div>
</template>

<script>
    import SearchForm from "../../../components/Molecules/SearchForm";
    import DataTable from "../../../components/Molecules/DataTable";
    export default {
        components: {DataTable, SearchForm},
        data(){
            return{
                headers:[
                    {text:'テスト名', value: 'name'},
                    {text:'タイプ', value: 'test_type',type:'test_type'},
                    {text:'設問数', value: 'question_count',searchable: false, suffix:'問'},
                    {text:'平均正答数', value: 'avg_score',searchable: false,type:'int', suffix:'問'},
                ],
                params:{
                    name:null,
                    course_id:null,
                    curriculum_id:null,
                    test_type:null,
                }
            }
        },
        created() {
            this.$store.dispatch('page/setTitle', 'テスト管理');
            this.$store.dispatch('page/showTab');
            this.$store.dispatch('page/hideBackButton');
        },
        methods:{
            clickRow(item){
                this.$router.push('/master/test/' + item.id);
            }
        }
    }
</script>

<style scoped>

</style>
