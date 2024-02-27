<template>
<main-container
    title="受講者プロフィール"
    :tabs="[]"
>
    <student-detail v-model="data"/>
</main-container>
</template>

<script>
import MainContainer from "@/components/Molecules/MainContainer";
import StudentDetail from "@/components/Student/StudentDetail";
export default {
    components: {StudentDetail, MainContainer},
    data(){
        return {
            data: {}
        }
    },
    created() {
        this.$store.dispatch('page/hideTab')
        if (!this.$route.query.new_tab) {
            this.$store.dispatch('page/showBackButton');
        }
        this.getData();
    },
    methods:{
        async getData(){
            try{
                await this.$store.dispatch('loader/showSub');
                const res = await this.$axios.get(this.$utils.getApiUrl(this.$apis.student_detail,true));
                this.data = res.data;
            }catch (e) {
                this.$utils.catchError(e);
            }finally {
                await this.$store.dispatch('loader/hideSub');
            }
        }
    }
}
</script>

<style scoped>

</style>
