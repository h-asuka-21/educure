<template>
    <v-card class="fill-height"
            v-if="student_id === undefined"
            :loading="loading"
            :disabled="loading"
            outlined>
        <v-card-title>カリキュラム進捗</v-card-title>
        <data-table
            class="data_table"
            :url="url"
            :white="true"
            :headers="headers"
            :hide_footer="true"
            :fixed-header="true"
            @click_row="showDetail"
            :height="196"
            @loading="v => loading = v"
        ></data-table>
        <step-detail-dialog
            :data="data"
            ref="dialog"
        ></step-detail-dialog>
    </v-card>
    <div v-else>
        <v-card-title>カリキュラム進捗</v-card-title>
        <data-table
            class="data_table"
            :url="url"
            :headers="headers"
            :hide_footer="true"
            :fixed-header="true"
            @click_row="showDetail"
            :height="196"
            @loading="v => loading = v"
        ></data-table>
        <step-detail-dialog
            :data="data"
            ref="dialog"
        ></step-detail-dialog>
    </div>
</template>

<script>
import DataTable from "@/components/Molecules/DataTable";
import SimpleCardDialog from "@/components/Molecules/SimpleCardDialog";
import StepDetailDialog from "@/components/Student/Detail/StepDetailDialog";

export default {
    name: "ProgressCard",
    components: {StepDetailDialog, SimpleCardDialog, DataTable},
    data() {
        return {
            url: null,
            headers: [
                {text: '', value: 'order', sortable: false},
                {text: '名前', value: 'name', sortable: false},
                {text: '受講日数', value: 'date_count', suffix: '日', sortable: false},
                {text: '進捗率', value: 'percent', suffix: '％', sortable: false},
            ],
            data: {},
            loading:false
        }
    },
    props: {
        student_id: {
            type: Number,
            default: undefined
        }
    },
    created() {
        if(this.student_id){
            this.url = this.$utils.getApiUrl(this.$apis.student_progress_list, true,true)+'/'+this.student_id;
        } else {
            this.url = this.$utils.getApiUrl(this.$apis.student_progress_list, true);
        }
    },
    watch: {
        student_id(val){
            if(val){
                this.url = this.$utils.getApiUrl(this.$apis.student_progress_list, true,true)+'/'+this.student_id;
            }
        }
    },
    methods: {
        showDetail(v) {
            this.data = v;
            this.$refs.dialog.$refs.dialog.show();
        }
    }
}
</script>

<style scoped lang="scss">
.fill-height {
    padding-bottom: 0;
    .data_table {
        padding: 0 !important;
    }
}
</style>
