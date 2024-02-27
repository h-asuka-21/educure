<template>
    <v-card
        outlined
        class="fill-height"
        :loading="loading"
        :disabled="loading"
    >
        <v-card-title>面談履歴<v-spacer/><v-btn v-if="!$utils.isStudent()" outlined @click="clickRow({student_id:$route.params.id})">新規作成</v-btn></v-card-title>
        <interview-form-dialog ref="dialog" v-model="dialog_data" @reload="getData()"/>
        <v-expand-transition>
            <div v-if="!loading">
                <v-row v-if="!data.length" justify="center" class="pb-5">
                    <span class="text-center">面談履歴がありません</span>
                </v-row>
                <v-simple-table
                    v-else
                    fixed-header
                    :height="196">
                    <thead>
                    <tr>
                        <th>面談日</th>
                        <th>営業評価</th>
                        <th>コメント</th>
                    </tr>
                    </thead>
                    <tbody>
                    <v-tooltip bottom v-for="(item,key) in data" :key="key">
                        <template v-slot:activator="{ on, attrs }">
                            <tr v-bind="attrs" v-on="on" @click="clickRow(item)">
                                <td>{{$moment(item.updated_at).format('YYYY年M月D日')}}</td>
                                <td>
                                    <v-icon x-small v-for="i in item.sales_evaluation" :key="i" color="yellow accent-4">mdi-star</v-icon>
                                </td>
                                <td>{{getReason(item.evaluation_reason)}}</td>
                            </tr>
                        </template>
                        <span v-html="item.evaluation_reason.replace(/\r?\n/g, '<br>')"></span>
                    </v-tooltip>
                    </tbody>
                </v-simple-table>
            </div>
        </v-expand-transition>
    </v-card>
</template>
<script>
import VerticalTable from "~/components/Molecules/VerticalTable";
import InterviewFormDialog from "~/components/Interview/InterviewFormDialog";
import SimpleCardDialog from "@/components/Molecules/SimpleCardDialog";
import InterviewForm from "@/components/Interview/InterviewForm";

export default {
    components: {
        InterviewForm,
        SimpleCardDialog,
        VerticalTable,
        InterviewFormDialog,
    },
    data() {
        return {
            headers: [
                {text:'日付', value: 'created_at', type:'date'},
                {text:'評価', value: 'sales_evaluation'},
                {text:'コメント', value: 'evaluation_reason'},
            ],
            items: [],
            dialog_data: {},
            total: 0,
            params: {
                sales_evaluation: null,
                evaluation_reason: null,
            },
            data: [],
            url:'',
            loading: false,
        }
    },
    created() {
        this.getData();
    },
    methods:{
        async getData(){
            try{
                this.loading = true;
                let url = this.$utils.getApiUrl(this.$apis.interview_history_student, true, true);
                if(!this.$utils.isStudent()){
                    url += '/' + this.$route.params.id;
                }
                const result = await this.$axios.get(url);
                this.data = result.data;
            } catch (e){
                this.$utils.catchError(e);
            } finally {
                this.loading = false;
            }
        },
        showDialog(ref){
            this.$refs[ref][0].$refs.dialog.show();
        },
        getReason(reason){
            if(!reason){
                return '';
            }
            if(reason.length > 10){
                return reason.slice(0,10) + '…';
            }
            return reason;
        },
        clickRow(item){
            if(this.$utils.isStudent()){
                return;
            }
            this.dialog_data = item;
            this.$refs.dialog.$refs.dialog.show();
        }
    }
}
</script>
