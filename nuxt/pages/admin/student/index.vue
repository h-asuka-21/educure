<template>
    <student-list
        :show_create="true"
        :click-row-default="clickRow"
    />
</template>

<script>
    import StudentList from "../../../components/Student/StudentList";
    export default {
        components: {StudentList},
        methods:{
            clickRow(item){
                this.$router.push('/admin/student/' + item.id);
            }
        },
        created() {
            this.$store.dispatch('page/showTab');
            this.$store.dispatch('page/hideBackButton');
            this.$store.dispatch('page/setTitle','受講者一覧');
        }
    }
</script>

<style scoped>

</style>
