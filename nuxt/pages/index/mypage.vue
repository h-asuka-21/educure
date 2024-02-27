<template>
    <div>
        <p class="title">マイページ</p>
        <student-detail
            :student="true"
            v-model="data"
        ></student-detail>
    </div>
</template>

<script>
import StudentDetail from "@/components/Student/StudentDetail";
export default {
    name: "mypage",
    components: {StudentDetail},
    data(){
        return{
            data: {}
        }
    },
    created() {
        this.data = this.$store.getters["student/data"];
        this.$store.dispatch('page/sideBar', true);

    }
}
</script>

<style scoped>

</style>
