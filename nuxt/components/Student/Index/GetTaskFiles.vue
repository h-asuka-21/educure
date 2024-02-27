<template>
    <v-card outlined class="fill-height"
            :loading="loading"
            :disabled="loading"
    >
        <v-card-title>現在の進捗
            <v-spacer></v-spacer>
            <v-btn outlined @click="$router.push('/curriculum')">過去の課題を確認する</v-btn>
        </v-card-title>
        <v-fade-transition>
            <v-card-text v-if="!loading && data !== null && data.curriculum !== undefined && data.step !== undefined">
                <v-row dense>
                    <v-col cols="6">
                        <v-row dense>
                            <v-col class="subtitle-2" cols="6">カリキュラム名</v-col>
                            <v-col cols="6">{{data.curriculum.name}}</v-col>
                            <v-col class="subtitle-2" cols="6">現在のステップ</v-col>
                            <v-col cols="6">{{data.step.name}}</v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="6">
                        <v-row dense>
                            <v-col class="subtitle-2" cols="6">進捗率</v-col>
                            <v-col cols="6">{{data.step.progress_status}}</v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="12" v-if="data.step">
                        <horizontal-bar-chart
                            :height="100"
                            :chart-data="graph_data"
                            :options="chartOpt"
                        ></horizontal-bar-chart>
                    </v-col>
                </v-row>
            </v-card-text>
            <v-card-text v-else-if="!loading && typeof data === 'object'">
                <v-row dense justify="center" id="success_message">
                    全てのカリキュラムが終了しました。<br>
                    お疲れさまでした！
                </v-row>
            </v-card-text>
        </v-fade-transition>
        <v-fade-transition>
            <v-card-actions class="justify-space-around" v-if="data !== null && data.curriculum !== undefined && data.step !== undefined">
                <v-btn
                    id="v-step-3"
                    :dark="data.curriculum.zip !== null"
                    :color="$colors.main"
                    :disabled="data.curriculum.zip === null"
                    @click="download()">課題ダウンロード</v-btn>
                <v-btn dark :color="$colors.sub" @click="$refs.dialog.show()">課題の詳細を確認</v-btn>
            </v-card-actions>
        </v-fade-transition>
        <simple-card-dialog
            v-if="data !== null && data.curriculum !== undefined && data.step !== undefined"
            ref="dialog"
            title="ステップ詳細"
        >
            <image-with-dialog
                v-if="data.step.image"
                v-model="data.step.image"
            />
            <html-element :content="data.step.content"/>
        </simple-card-dialog>
    </v-card>
</template>

<script>
import SimpleCardDialog from "~/components/Molecules/SimpleCardDialog";
import ImageWithDialog from "../../../components/Molecules/ImageWithDialog";
import HtmlElement from "@/components/Molecules/HtmlElement";
export default {
    name: "GetTaskFiles",
    components: {HtmlElement, ImageWithDialog, SimpleCardDialog},
    data(){
        return {
            data: null,
            loading:false,
            graph_data:{
                labels: ['あなたの日数','目標日数(1ヶ月)','目標日数(2ヶ月)','平均日数'],
                datasets:[{
                    backgroundColor: [
                        '#CDDC39',
                        '#FFC107',
                        '#FFAB69',
                        '#00BCD4',
                    ],
                    data: []
                }]
            },
            chartOpt: {
                legend: {
                    display: false
                },
                tooltips:{
                    enabled: false
                },
                scales: {
                    xAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1
                        }
                    }]
                }
            }
        }
    },
    created() {
        this.getCurrent();
    },
    methods:{
        async getCurrent(){
            try{
                this.loading = true;
                const ret = await this.$axios.get(this.$utils.getApiUrl(this.$apis.current_progress, true));
                this.data = ret.data;
                if(this.data.step !== undefined){
                    this.graph_data.datasets[0].data.push(this.data.step.date_count);
                    this.graph_data.datasets[0].data.push(this.data.step.target_days);
                    this.graph_data.datasets[0].data.push(this.data.step.deadline_days);
                    this.graph_data.datasets[0].data.push(this.data.step.average_count);
                }
                this.delayAlert();
            }catch (e){
                this.$utils.catchError(e);
            } finally {
                this.loading = false;
            }
        },
        delayAlert(){
            const step = this.data.step;
            if(step === undefined || step.id === undefined || step.id === null){
                this.$store.dispatch('home/delayAlert', {});
                return;
            }
            if(step.date_count > step.target_days){
                this.$store.dispatch('home/delayAlert', step);
                return;
            }
            this.$store.dispatch('home/delayAlert', {});
        },
        download(){
            location.href = this.data.curriculum.zip
        }
    }
}
</script>

<style scoped>
#success_message{
    font-weight: bold;
    font-size: 1.4rem;
    margin-top:30px;
    line-height: 37px;
}
</style>
