<template>
<simple-card-dialog
    title="講師評価詳細"
    ref="dialog"
>
    <v-row dense>
        <v-col v-if="data.date" cols="12" class="d-flex">
            <v-row dense>
                <v-col cols="4">評価日</v-col>
                <v-col cols="8">{{$moment(data.date).format('YYYY年M月D日')}}</v-col>
            </v-row>
        </v-col>
        <v-col v-if="data.user_name" cols="12" class="d-flex">
            <v-row dense>
                <v-col cols="4">評価者</v-col>
                <v-col cols="8">{{data.user_name}}</v-col>
            </v-row>
        </v-col>
        <v-col cols="12">
            <v-row dense>
                <v-col cols="4" class="pt-3">評価</v-col>
                <v-col cols="8">
                    <star-rating
                        v-model="data.evaluation"
                        :read-only=true
                        :star-size="40"/>
                </v-col>
            </v-row>
        </v-col>
        <v-col cols="12">
            <v-row dense>
                <v-col cols="4">コメント</v-col>
                <v-col cols="8"><div v-html="data.reason"></div></v-col>
            </v-row>
        </v-col>
    </v-row>
</simple-card-dialog>
</template>

<script>
import SimpleCardDialog from "@/components/Molecules/SimpleCardDialog";
export default {
    name: "TeacherDetailDialog",
    components: {SimpleCardDialog},
    props:{
        value:{
            type:Object,
            required:true
        },
    },
    data(){
        return {
            data:this.value
        }
    },
    watch:{
        data(val){
            this.$emit('input', val);
        },
        value(val){
            this.data = val;
            this.data.reason = this.data.reason.replace(/\n/g, '<br/>');
        }
    },
    methods:{
        success(){
            this.$emit('reload');
            this.$refs.dialog.hide();
        }
    }
}
</script>

<style scoped>

</style>
