<template>
    <div>
        <v-row dense>
            <v-col v-for="(item,key) in data" :key="key" cols="6">
                <v-skeleton-loader
                    type="image"
                    :loading="item.name === undefined"
                >
                    <v-card
                        :color="getColor(item,key)"
                        dark
                        outlined>
                        <v-card-title>
                            No.{{item.order}} {{item.name}}
                            <v-spacer></v-spacer>
                            <v-icon>
                                {{getIcon(item,key)}}
                            </v-icon>
                        </v-card-title>
                        <v-card-text>
                            <v-row dense>
                                <v-col cols="6">クリア日</v-col>
                                <v-col cols="6">{{item.latest_date?$moment(item.latest_date).format('YYYY年M月D日'):''}}</v-col>
                                <v-col cols="6">日数</v-col>
                                <v-col cols="6">{{item.total_date||''}}{{item.total_date?'日':''}}</v-col>
                                <v-col cols="6">進捗率</v-col>
                                <v-col cols="6">{{item.total_percent||''}}{{item.total_percent?'%':''}}</v-col>
                            </v-row>
                        </v-card-text>
                        <v-card-actions class="d-flex justify-space-between">
                            <v-btn small outlined :disabled="isZipDisabled(item,key)" @click="download(item)">ダウンロード</v-btn>
                            <v-btn small outlined :disabled="isDisabled(item,key)" @click="showSteps(item)">ステップを確認</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-skeleton-loader>
            </v-col>
        </v-row>
        <step-dialog
            ref="dialog"
            :data="dialog_data"
        />
    </div>
</template>

<script>
import DataTable from "@/components/Molecules/DataTable";
import StepDialog from "@/components/Curriculum/StepDialog";

export default {
    components: {StepDialog, DataTable},
    data() {
        return {
            headers: [
                {text: 'No', value: 'order'},
                {text: 'タイトル', value: 'name'},
                {text: '達成率', value: 'total_percent', suffix: '％'},
                {text: '日数', value: 'total_date', suffix: '日'},
                {text: 'クリア日', value: 'latest_date', type: 'date'},
                {
                    text: '課題ダウンロード', value: 'zip',
                    type: 'link_button', label: 'ダウンロード', class: 'text-center', color: this.$colors.main, dark: true
                }
            ],
            data: {
                data:[
                    {id:null},
                    {id:null},
                    {id:null},
                    {id:null},
                ]
            },
            dialog_data: null
        }
    },
    created() {
        this.getData();
    },
    methods: {
        async getData(){
            try{
                const ret = await this.$axios.get(this.$utils.getApiUrl(this.$apis.curriculum,true,false))
                console.log(ret);
                this.data = ret.data;
            }catch (e) {
                this.$utils.catchError(e);
            }
        },
        showSteps(v) {
            this.dialog_data = v;
            this.$refs.dialog.show();
        },
        getColor(item,key){
            if(item.total_percent === 100){
                return 'teal'
            }
            if (key === 0) {
                return 'cyan'
            }
            if(this.data[key - 1] !== undefined){
                const before_item = this.data[key - 1];
                if(before_item.total_percent === 100){
                    return 'cyan'
                }
            }
            return 'grey'
        },
        getIcon(item,key){
            if(item.total_percent === 100){
                return 'mdi-check'
            }
            if(this.data[key - 1] !== undefined){
                const before_item = this.data[key - 1];
                if(before_item.total_percent === 100){
                    return 'mdi-pencil'
                }
            }
            return 'mdi-lock'
        },
        isZipDisabled(item, key) {
            if (key === 0) {
                return item.zip === null;
            }
            if (this.data[key - 1] !== undefined) {
                const before_item = this.data[key - 1];
                if (before_item.total_percent === 100) {
                    return item.zip === null;
                }
            }
            return true;
        },
        isDisabled(item,key){
            if(key === 0){
                return false;
            }
            if(this.data[key - 1] !== undefined){
                const before_item = this.data[key - 1];
                if(before_item.total_percent === 100){
                    return false;
                }
            }
            return true;
        },
        download(v){
            location.href = v.zip;
        }
    }
}
</script>

<style scoped lang="scss">
.v-card__text{
    color: #fff!important;
}
</style>
