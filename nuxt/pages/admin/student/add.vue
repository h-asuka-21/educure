<template>
    <student-form
        v-model="data"
    />
</template>

<script>
    import StudentForm from "../../../components/Student/StudentForm";
    export default {
        name: "add",
        components: {StudentForm},
        data(){
            return{
                data:{
                    name: null,
                    name_kana: null,
                    password: null,
                    email: null,
                    remember_token: null,
                    course_id: null,
                    start_date: null,
                    end_date: null,
                    birthday: null,
                    gender: null,
                    academic_type: null,
                    birthplace: null,
                    working_history: null,
                    former_job_type: null,
                    former_job_status: null,
                    change_job_count: null,
                    national_qualification_flg: 0,
                    qualification_flg: 0,
                    club_type: 0,
                    after_graduation_flg: 0,
                }
            }
        },
        created() {
            this.$store.dispatch('page/setTitle', '新規作成');
        }
    }
</script>

<style scoped>

</style>
