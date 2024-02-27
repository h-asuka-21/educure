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
                    v-if="data.datasets[0].data.filter(count => count > 0).length || data.datasets[1].data.filter(count => count > 0).length"
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
    name: 'StackChart',
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
                title: this.graph_data.title,
                labels: this.graph_data.labels,
                datasets: [{
                    label: "達成数",
                    borderWidth:1,
                    backgroundColor: "#2196F3",
                    data: [],
                    categoryPercentage: 0.7,
                    barPercentage: 0.6,
                },
                {
                    label: "辞退者数",
                    borderWidth:1,
                    backgroundColor: "#F44336",
                    data: [],
                    categoryPercentage: 0.7,
                    barPercentage: 0.6,
                }]

            },
            options: {
                scales: {
                    xAxes: [{
                        stacked: true, //積み上げ棒グラフにする設定
                        scaleLabel: {
                            display: true,
                            labelString: this.graph_data.xlabel,
                        },
                        ticks: {
                            stepSize: 1
                        },
                    }],
                    yAxes: [{
                        stacked: true, //積み上げ棒グラフにする設定
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
                tooltips:{
                    mode:'label' //マウスオーバー時に表示されるtooltip
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
                this.data.datasets[0].data = val.achievement_data;
                this.data.datasets[1].data = val.retire_data;
                if (val.achievement_data.length > 0 || val.retire_data.length > 0) {
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
