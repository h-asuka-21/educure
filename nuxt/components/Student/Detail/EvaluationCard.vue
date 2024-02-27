<template>
    <v-card outlined class="fill-height"
            :loading="loading"
            :disabled="loading"
    >
        <v-card-title>評価</v-card-title>
        <v-expand-transition>
            <v-card-text v-if="!loading">
                <v-row dense>
                    <v-col cols="5" xs="12">
                        <rank
                            v-if="total_scores !== null"
                            v-model="total_scores"
                        />
                    </v-col>
                    <v-col cols="7" xs="12">
                        <total-score-chart
                            v-if="total_scores !== null"
                            v-model="total_scores"
                        />
                    </v-col>
                    <v-col xs="12" sm="6" md="6" lg="6" xl="6">
                        <evaluation-line-chart
                            v-if="sales_scores !== null"
                            v-model="sales_scores"
                            title="担当者評価"/>
                    </v-col>
                </v-row>
            </v-card-text>
        </v-expand-transition>
    </v-card>
</template>

<script>
import ExpandCard from "@/components/Molecules/ExpandCard";
import Rank from "@/components/Student/Detail/Evaluation/Rank";
import TotalScoreChart from "@/components/Student/Detail/Evaluation/TotalScoreChart";
import EvaluationLineChart from "@/components/Student/Detail/Evaluation/EvaluationLineChart";
export default {
    name: "EvaluationCard",
    components: {EvaluationLineChart, TotalScoreChart, Rank, ExpandCard},
    data(){
        return{
            loading:false,
            total_scores:null,
            teacher_scores:null,
            sales_scores:null
        }
    },
    created() {
        this.getData();
    },
    methods:{
        async getData(){
            try{
                this.loading = true;
                const resp = await this.$axios.get(
                    this.$utils.getApiUrl(this.$apis.evaluation,true));
                const data = resp.data
                this.total_scores = {
                    teacher: data.teacher_score,
                    sales: data.sales_score,
                    comprehension: data.comprehension_score,
                    think: data.think_score,
                    attendance: data.attendance_score,
                    report: data.report_score,
                    progress: data.progress_score,
                    total: data.total_score,
                    rank: data.rank,
                }
                this.teacher_scores = data.teacher_scores;
                this.sales_scores = data.sales_scores;
            }catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>

<style scoped>

</style>
