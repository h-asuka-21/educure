<template>
    <v-skeleton-loader
        :loading="data === null"
        type="image"
        :class="data === null ? 'fill_height pt-2':''"
    >
        <student-detail v-model="data"/>
    </v-skeleton-loader>
</template>

<script>
    import StudentDetail from "@/components/Student/StudentDetail";
    export default {
        components: {StudentDetail},
        data() {
            return {
                data: null
            }
        },
        created() {
            this.$store.dispatch('page/hideTab');
            if (!this.$route.query.new_tab) {
                this.$store.dispatch('page/showBackButton');
            }
            this.getStudents();
        },
        methods:{
            async getStudents(){
                this.data = null;
                this.$store.dispatch('page/setTitle', null);
                try {
                    const result = await this.$axios.get(this.$utils.getApiUrl(this.$apis.student,true))
                    this.data = result.data;
                    this.$store.dispatch('page/setTitle', this.data.name);
                }catch (e) {
                    this.$utils.catchError(e);
                }finally {

                }

            }
        }

    }
</script>

<style scoped>

</style>
