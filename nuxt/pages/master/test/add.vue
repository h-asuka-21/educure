<template>
    <edit-form
        v-model="data"
    ></edit-form>
</template>

<script>
    import EditForm from "../../../components/Test/EditForm";
    export default {
        components: {EditForm},
        data(){
            return{
                data:{
                    test: {
                        test_time: 0
                    },
                    questions: [],
                    deleted: []
                },
            }
        },
        created() {
            this.$store.dispatch('page/setTitle', 'テスト新規作成');
        }
    }
</script>

<style scoped>

</style>
