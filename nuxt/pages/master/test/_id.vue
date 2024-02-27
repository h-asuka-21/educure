<template>
    <edit-form
        v-model="data"
        :loading="loading"
        @reload="getDetail()"
    >
    </edit-form>
</template>

<script>
    import Detail from "../../../components/Test/Detail";
    import Questions from "../../../components/Test/Questions";
    import EditForm from "../../../components/Test/EditForm";
    export default {
        components: {EditForm, Questions, Detail},
        data() {
            return {
                data:{
                    test: {},
                    questions: [],
                },
                loading: true
            }
        },
        created() {
            this.$store.dispatch('page/setTitle', 'テスト詳細');
            this.$store.dispatch('page/hideTab');
            this.$store.dispatch('page/showBackButton');
            this.$store.dispatch('page/setBackUrl',null)
            this.getDetail();
        },
        methods:{
            async getDetail(){
                this.$store.dispatch('loader/showSub');
                this.data = {
                    test: {},
                    questions: [],
                    deleted: []
                }
                this.loading = true;
                try{
                    const res = await this.$axios.get(this.$utils.getApiUrl(this.$apis.test,true));
                    this.data = res.data;
                    this.data.deleted = [];
                } catch (e) {
                    this.$utils.catchError(e);
                } finally {
                    this.loading = false;
                    this.$store.dispatch('loader/hideSub');
                }
            }
        }
    }
</script>

<style scoped>

</style>
