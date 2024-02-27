<template>
    <v-scale-transition origin="center center">
        <v-card
            v-if="data.length > 0"
            outlined
        >
            <v-card-title>遅延している受講者</v-card-title>
            <v-simple-table
                dense
                fixed-header
                height="190px"
            >
                <thead>
                <tr>
                    <th>氏名</th>
                    <th>コース</th>
                    <th>現在のカリキュラム</th>
                    <th>現在のステップ</th>
                    <th>現在の日数</th>
                    <th>目標</th>
                    <th>締め切り</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,key) in data" :key="key"
                    @click="showDetail(item)"
                    :class="{ 'red accent-1' : item.delay >= 5 }"
                >
                    <td>{{item.name}}</td>
                    <td>{{item.course_name}}</td>
                    <td>{{item.curriculum}}</td>
                    <td>{{item.step}}</td>
                    <td>{{item.date_count}}日</td>
                    <td>{{item.target_days}}日</td>
                    <td>{{item.deadline_days}}日</td>
                </tr>
                </tbody>
            </v-simple-table>
            <simple-card-dialog
                ref="dialog"
            >
                <progress-card
                    :student_id="student_id"
                ></progress-card>
            </simple-card-dialog>
        </v-card>
    </v-scale-transition>
</template>

<script>
import SimpleCardDialog from "@/components/Molecules/SimpleCardDialog";
import ProgressCard from "@/components/Student/Detail/ProgressCard";
export default {
    name: "DelayStudents",
    components: {ProgressCard, SimpleCardDialog},
    data() {
        return {
            loading: false,
            data: [],
            student_id:undefined
        }
    },
    created(){
        this.getData();
    },
    methods:{
        async getData(){
            try {
                this.loading = true;
                const ret = await this.$axios.get(this.$utils.getApiUrl(this.$apis.delay_students, true, false));
                this.data = ret.data;
                this.$emit('length', this.data.length)
            }catch (e) {
                this.$utils.catchError(e);
            }finally {
                this.loading = false;
            }
        },
        showDetail(item) {
            this.student_id = item.id;
            this.$refs.dialog.show();
        }
    }
}
</script>

<style scoped>

</style>
