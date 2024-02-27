<template>
    <v-card outlined class="fill-height"
            :loading="loading"
            :disabled="loading"
            min-height="400"
    >
        <v-card-title>取り組み状況</v-card-title>
        <v-expand-transition>
            <v-card-text
                v-if="data.labels.length > 0">
                <horizontal-bar-chart
                    id="bar_graph"
                    :chart-data="data"
                    :options="options"
                    :height="graph_height"
                />
            </v-card-text>
        </v-expand-transition>
    </v-card>
</template>

<script>
import ExpandCard from "@/components/Molecules/ExpandCard";
export default {
    name: 'ProgressChart',
    components: {ExpandCard},
    data() {
        return {
            loading: false,
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'あなたの日数',
                        backgroundColor: '#CDDC39',
                        data: []
                    },
                    {
                        label: '目標日数(1ヶ月)',
                        backgroundColor: '#FFC107',
                        data: []
                    },
                    {
                        label: '目標日数(2ヶ月)',
                        backgroundColor: '#FFAB69',
                        data: []
                    },
                    {
                        label: '平均日数',
                        backgroundColor: '#00BCD4',
                        data: []
                    },
                ],
            },
            options:{
                scales: {
                    xAxes: [{
                        ticks: {
                            stepSize: 1
                        },
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: '日数',
                        },
                        beginAtZero: true,
                    }],
                    yAxes: [{
                        ticks: {
                            stepSize: 1
                        },
                        display: true,
                        categoryPercentage: 0.7,
                        barPercentage: 0.6,
                        beginAtZero: true,
                    }],
                },
                layout: {
                    padding: {
                        left: 5,
                        right: 5,
                        top: 0,
                        bottom:0
                    },

                }
            },
            graph_height: 0
        }
    },
    created() {
        this.getProgress()
    },
    methods: {
        async getProgress() {
            try {
                this.loading = true;
                const result = await this.$axios.get(this.$utils.getApiUrl(this.$apis.student_progress, true));
                this.makeGraphData(result.data);
            } catch (e) {
                this.$utils.catchError(e);
            } finally {
                this.loading = false;
            }
        },
        makeGraphData(data) {
            this.graph_height = data.curriculums.length * 60;
            data.curriculums.map((v, k) => {
                this.data.labels.push(v.name);
                this.data.datasets[0].data.push(data.status[k]);
                this.data.datasets[1].data.push(data.target[k]);
                this.data.datasets[2].data.push(data.deadline[k]);
                this.data.datasets[3].data.push(data.average[k]);
            });
        }
    }
}
</script>

<style scoped>
</style>
