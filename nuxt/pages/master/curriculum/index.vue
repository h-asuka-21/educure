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
            :url="'/admin' + $apis.curriculum"
            :params="params"
            :click_row="clickRow"
        ></data-table>
    </div>
</template>

<script>
    import SearchForm from "../../../components/Molecules/SearchForm";
    import DataTable from "../../../components/Molecules/DataTable";
    export default {
        name: "index",
        components: {DataTable, SearchForm},
        data(){
            return{
                headers:[
                    {text:'カリキュラム名', value: 'name'},
                    {text:'作成日', value: 'created_at',type:'date'},
                    {text:'最終更新日', value: 'updated_at',type:'date'},
                ],
                params:{
                    name:null,
                }
            }
        },
        created() {
            this.$store.dispatch('page/setTitle', 'カリキュラム管理');
            this.$store.dispatch('page/showTab');
            this.$store.dispatch('page/hideBackButton');
        },
        methods:{
            clickRow(item){
                this.$router.push('/master/curriculum/' + item.id);
            }
        }
    }
</script>

<style scoped>

</style>
