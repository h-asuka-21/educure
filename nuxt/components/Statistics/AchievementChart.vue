<template>
    <v-card outlined class="fill-height"
            :loading="loading"
            :disabled="loading"
            min-height="400"
    >
        <v-card-title>{{ graph_data.title }}</v-card-title>
        <v-expand-transition>
            <v-card-text>
                <bar-chart
                    v-if="data.datasets[0].data.filter(count => count > 0).length"
                    id="bar_graph"
                    :chart-data="data"
                    :options="options"
                />
                <p v-else class="subtitle">データがありません</p>
            </v-card-text>
        </v-expand-transition>
    </v-card>
</template>

<script>
import ExpandCard from "@/components/Molecules/ExpandCard";
export default {
    name: 'AchievementChart',
    components: {ExpandCard},
    props: {
        graph_data: {
            type: Object,
            required: true
        },
    },
    data() {
        return {
            loading: false,
            data: {
                labels: this.graph_data.labels,
                datasets: [{
                    label: this.graph_data.label,
                    borderWidth:1,
                    backgroundColor: this.graph_data.color,
                    data: [],
                    categoryPercentage: 0.7,
                    barPercentage: 0.6,
                },]

            },
            options: {
                scales: {
                    xAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: this.graph_data.xlabel,
                        },
                        ticks: {
                            stepSize: 1
                        },
                    }],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: '人数',
                        },
                        ticks: {
                            stepSize: 1
                        },
                    }]
                },
                layout: {
                    padding: {
                        left: 5,
                        right: 5,
                        top: 0,
                        bottom: 0
                    }
                },
                legend: {
                    labels: {
                        boxWidth:30,
                        padding:20 //凡例の各要素間の距離
                    },
                    display: true
                },
            },
        }
    },
    created() {
    },
    methods: {
    },
    watch: {
        graph_data: {
            immediate: true,
            deep: true,
            handler: function (val) {
                this.data.labels = val.labels;
                this.data.datasets[0].data = val.data;
                if (val.data.length > 0) {
                    this.loading = false;
                } else {
                    this.loading = true;
                }
            }
        },
    }
}
</script>

<style scoped>
</style>
