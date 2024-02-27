<template>
    <edit-form
        v-model="data"
    >

    </edit-form>

</template>

<script>
import EditForm from "../../../components/Curriculum/EditForm";
export default {
    components: {EditForm},
    data(){
        return{
            data:{
                curriculum: {},
                steps: [],
                deleted: []
            },
        }
    },
    created() {
        this.$store.dispatch('page/setTitle', 'カリキュラム新規作成');
    }
}
</script>

<style scoped>

</style>
