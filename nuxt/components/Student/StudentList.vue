<template>
    <div>
        <search-form
            :keys="headers"
            v-model="params"
            @search="$refs.table.getItems()"
            :show_add="show_create"
            @add="$refs.detail.show()"
            class="mb-1"
        ></search-form>
        <data-table
            ref="table"
            :headers="headers"
            :url="$utils.getApiUrl($apis.student,true)"
            :params="params"
            @click_row="clickRow"
        ></data-table>
        <detail
            ref="detail"
            v-model="data"
            @reload="$refs.table.getItems()"
        ></detail>
    </div>
</template>
<script>
    import SearchForm from "../Molecules/SearchForm";
    import DataTable from "../Molecules/DataTable";
    import Detail from "../CompanyDetail/StudentDetail";
    export default {
        name: 'StudentList',
        components: {
            Detail,
            SearchForm,
            DataTable,
        },
        data() {
            return {
                headers: [
                    {text: '名前', value: 'name'},
                    {text:'受講中コース', value: 'course_name',search:'course_id',type:'course'},
                    {text: '受講開始', value: 'start_date', type: 'date'},
                    {text: '終了日', value: 'end_date', type: 'date'},
                    {text: '完了後進路', value: 'after_graduation_flg', type: 'after_graduation_flg'},
                ],
                items: [],
                total: 0,
                params: {
                    company_code: null,
                    name: null,
                    start_date: null,
                    end_date: null,
                    after_graduation_flg: null,
                },
                url: null,
                data: {}
            }
        },
        props: {
            clickRowDefault: {
                default: undefined
            },
            show_create:{
                type:Boolean,
                default:false
            },
            admin:{
                type:Boolean,
                default: false
            }
        },
        created() {
            if(this.admin){
                this.headers.push({
                    text: '',
                    label: 'ステータス',
                    value: '' ,
                    type:'btn',
                    color:this.$colors.main,
                    dark:true,
                    searchable:false,
                    action:this.myPage
                })
            }
            this.$store.dispatch('page/hideTab');
        },
        methods: {
            clickRow(v){
                if(this.clickRowDefault){
                    this.clickRowDefault(v);
                } else {
                    this.data = v;
                    this.$refs.detail.show();
                }
            },
            myPage(v){
                this.$router.push('/master/student/' + v.id);
            }
        }
    }
</script>
