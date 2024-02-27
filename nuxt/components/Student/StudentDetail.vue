<template>
    <v-row dense>
        <v-col cols="6">
            <profile-card :student="data" />
        </v-col>
        <v-col cols="6">
            <progress-card />
        </v-col>
        <v-col cols="12" v-if="!student_flg">
            <interview-card />
        </v-col>
        <v-col cols="12" md="5">
            <progress-chart />
        </v-col>
        <v-col cols="12" md="7">
            <evaluation-card />
        </v-col>
    </v-row>
</template>

<script>
import ProfileCard from "@/components/Student/Detail/ProfileCard";
import ProgressCard from "@/components/Student/Detail/ProgressCard";
import ProgressChart from "@/components/Student/Detail/ProgressChart";
import EvaluationCard from "@/components/Student/Detail/EvaluationCard";
import InterviewCard from "@/components/Student/Detail/InterviewCard";
export default {
    name: "StudentDetail",
    components: {
        InterviewCard,
        EvaluationCard,
        ProgressChart,
        ProgressCard,
        ProfileCard
    },
    props: {
        value: {
            type: Object,
            required: true
        },
        student: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            data: this.value,
            student_flg: false
        };
    },
    watch: {
        value(v) {
            this.data = v;
        }
    },
    created() {
        if (this.$utils.getThemeClass() === "student") {
            this.student_flg = true;
        }
    }
};
</script>

<style scoped></style>
