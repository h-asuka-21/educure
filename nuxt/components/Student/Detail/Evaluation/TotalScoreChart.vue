<template>
    <radar-chart
        :chart-data="data"
        :options="options"
    />
</template>

<script>
export default {
    name: "TotalScoreChart",
    props: {
        value:{
            type:Object,
            required: true
        }
    },
    data(){
        return{
            data:{
                labels: ['担当者評価', '理解度', '思考力', '報告率', '進捗率'],
                datasets: [{
                    data: [this.value.sales||0,this.value.comprehension||0,this.value.think||0,this.value.report||0,this.value.progress||0],
                    borderColor: '#00BCD4',
                    pointBackgroundColor:'#00BCD4',
                    fill:false
                }]
            },
            options:{
                legend: {
                    display: false
                },
                title: {
                    display: false,
                },
                tooltips: {
                    enabled:false
                },
                scale: {
                    ticks: {
                        min: 0,
                        max: 5,
                        fontSize: 0,
                        stepSize: 1
                    },
                },
                layout:{
                    padding: {
                    }
                }
            }
        }
    }
}
</script>

<style scoped>

</style>
