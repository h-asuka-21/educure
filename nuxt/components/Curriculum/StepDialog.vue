<template>
    <simple-card-dialog
        ref="dialog"
        :title="title"
    >
        <v-row
            dense
            v-if="data !== null">
            <v-col cols="12" v-for="(step,key) in data.steps" :key="key">
                <v-card outlined>
                    <v-card-title>{{step.name}}</v-card-title>
                    <v-card-text>
                        <v-row dense>
                            <v-col cols="12">
                                <v-row dense>
                                    <v-col cols="6" v-if="step.latest_date">
                                        <v-row dense>
                                            <v-col cols="6">最終受講日</v-col>
                                            <v-col cols="6">{{step.latest_date?$moment(step.latest_date).format('YYYY年M月D日'):''}}</v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col cols="6" v-if="step.latest_percent">
                                        <v-row dense>
                                            <v-col cols="6">進捗率</v-col>
                                            <v-col cols="6">{{step.latest_percent}}{{step.latest_percent?'％':''}}</v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col cols="6" v-if="step.date_count !== 0">
                                        <v-row dense>
                                            <v-col cols="6">日数</v-col>
                                            <v-col cols="6">{{step.date_count}}{{step.date_count?'日':''}}</v-col>
                                        </v-row>
                                    </v-col>
                                    <v-col cols="6">
                                        <v-row dense>
                                            <v-col cols="6">目標日数</v-col>
                                            <v-col cols="6">{{step.target_days}}{{step.target_days?'日':''}}</v-col>
                                        </v-row>
                                    </v-col>
                                </v-row>
                            </v-col>
                            <v-col cols="12" class="subtitle-1">
                                内容
                            </v-col>
                            <v-col cols="12">
                                <image-with-dialog
                                    v-if="step.image"
                                    v-model="step.image"
                                />
                            </v-col>
                            <v-col cols="12">
                                <html-element :content="step.content"/>
                            </v-col>
                        </v-row>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </simple-card-dialog>
</template>

<script>
import SimpleCardDialog from "@/components/Molecules/SimpleCardDialog";
import ImageWithDialog from "@/components/Molecules/ImageWithDialog";
import HtmlElement from "@/components/Molecules/HtmlElement";
export default {
    name: "StepDialog",
    components: {HtmlElement, ImageWithDialog, SimpleCardDialog},
    props:{
        data:{
            type:Object,
            default: {},
        }
    },
    data() {
        return {
            title: ''
        }
    },
    created() {
        console.log(this.data);
    },
    watch: {
        data(v) {
            console.log(v);
            this.title = v.name;
        }
    },
    methods:{
        show(){
            this.$refs.dialog.show();
        },
        hide(){
            this.$refs.dialog.hide();
        },
    }
}
</script>

<style scoped>

</style>
