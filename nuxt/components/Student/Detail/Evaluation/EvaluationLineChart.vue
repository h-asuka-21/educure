<template>
    <v-container>
        <v-row dense>
            <v-col cols="12" class="subtitle-1">
                {{ title }}
            </v-col>
            <v-col cols="12">
                <line-chart
                    v-if="value !== null && value.length > 0"
                    :chart-data="data"
                    :options="options"
                ></line-chart>
                <p v-else class="subtitle">データがありません</p>
            </v-col>
        </v-row>
    </v-container>

</template>

<script>
export default {
    name: "EvaluationLineChart",
    props: {
        title: {
            type: String,
            required: true
        },
        value: {
            type: Array,
            required: true
        },
        color: {
            type: String,
            default: '#03A9F4'
        }
    },
    created() {
        console.log(this.value);
        this.setData();
    },
    methods: {
        setData() {
            this.value.map(v => {
                this.data.labels.push(this.$moment(v.date).format('M/D'))
                this.data.datasets[0].data.push(v.evaluation);
                this.data.datasets[0].reason.push(v.reason);
            });
        }
    },
    data() {
        return {
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    reason: [],
                    borderColor: this.color,
                    fill: false,
                    lineTension: 0
                }]
            },
            options: {
                legend: {
                    display: false
                },
                title: {
                    display: false,
                },
                tooltips: {
                    titleFontSize: 12,
                    bodyFontSize: 10,
                    callbacks: {
                        title: (tooltipItem, data) => {
                            return tooltipItem[0].xLabel;
                        },
                        label: (tooltipItem, data) => {
                            const score = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                            const stars = '★'
                            return stars.repeat(score);
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        display: true,
                        stacked: true,
                        ticks: {
                            min: 1,
                            max: 5,
                            fontSize: 14,
                            stepSize: 1
                        },
                    }],
                    xAxes: [{
                        display: true,
                        gridLines: {
                            display: false
                        }
                    }],
                },
            }
        }
    }

}
</script>

<style scoped>

</style>
