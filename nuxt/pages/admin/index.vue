<template>
    <div>
        <v-row dense>
            <v-col cols="6">
                <EvaluationRankingCard/>
            </v-col>
            <v-col cols="12" v-show="delay || lowEvaluation || notAttended">
                <v-card>
                    <v-app-bar
                        dark
                        color="pink"
                    >
                        <v-toolbar-title>受講者アラート</v-toolbar-title>
                    </v-app-bar>
                    <v-container fluid>
                        <v-row dense>
                            <v-col cols="12">
                                <v-card color="#385F73">
                                    <delay-students
                                        @length="delayData"
                                    />
                                </v-card>
                            </v-col>
                            <v-col cols="8">
                                <v-card color="#385F73">
                                    <low-evaluation-students
                                        @length="lowEvaluationData"
                                    />
                                </v-card>
                            </v-col>
                            <v-col cols="4">
                                <v-card color="#385F73">
                                    <not-attended-students
                                        @length="notAttendedData"
                                    />
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card>
            </v-col>
            <v-col cols="12">
                <student-progress-list/>
            </v-col>
        </v-row>
    </div>
</template>

<script>
    import StudentProgressList from "~/components/Progress/StudentProgressList";
    import DelayStudents from "@/components/Student/DelayStudents";
    import LowEvaluationStudents from "@/components/Student/LowEvaluationStudents";
    import NotAttendedStudents from "@/components/Student/NotAttendedStudents";
    import EvaluationRankingCard from "@/components/Student/Index/EvaluationRankingCard";
    export default {
        name: "index.vue",
        components: {EvaluationRankingCard, LowEvaluationStudents, NotAttendedStudents, DelayStudents, StudentProgressList},
        data() {
            return {
                delay: false,
                lowEvaluation: false,
                notAttended: false
            };
        },
        methods: {
            delayData (value) {
                if (value > 0) {
                    this.delay = true;
                }
            },
            lowEvaluationData (value) {
                if (value > 0) {
                    this.lowEvaluation = true;
                }
            },
            notAttendedData (value) {
                if (value > 0) {
                    this.notAttended = true;
                }
            },
        }
    }
</script>

<style scoped>

</style>
