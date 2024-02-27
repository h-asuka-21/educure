<template>
    <div>
        <v-row dense>
            <v-col cols="12">
                <v-card outlined>
                    <v-card-title>
                        コース情報
                    </v-card-title>
                    <v-card-text>
                        <course-form
                            v-model="data"
                            :loading="loading"
                            @reload="getDetail()"
                        />
                    </v-card-text>
                </v-card>
            </v-col>
            <v-col>
                <curriculums-list
                    :edit="true"/>
            </v-col>
        </v-row>
    </div>
</template>

<script>
    import CourseForm from "../../../components/Course/CourseForm";
    import CurriculumsList from "~/components/Course/CurriculumsList";

    export default {
        components: {CurriculumsList, CourseForm},
        data() {
            return {
                data:{
                    course: {},
                },
                loading: true
            }
        },
        created() {
            this.$store.dispatch('page/setTitle', 'コース詳細');
            this.$store.dispatch('page/hideTab');
            this.$store.dispatch('page/showBackButton');
            this.$store.dispatch('page/setBackUrl',null)
            this.getDetail();
        },
        methods:{
            async getDetail(){
                this.data = {
                    course: {},
                    deleted: []
                }
                this.loading = true;
                const url = '/admin' + this.$apis.course + '/' + this.$route.params.id;
                try{
                    const res = await this.$axios.get(url);
                    this.data = res.data;
                    this.loading = false;
                    this.data.deleted = [];
                } catch (e) {
                    this.$utils.catchError(e);
                }
            }
        }
    }
</script>

<style scoped>

</style>
