<template>
    <v-scale-transition origin="center center">
        <v-card
            v-if="data.length > 0"
            outlined
        >
            <v-card-title>1ヶ月以上カリキュラムに参加していない受講者</v-card-title>
            <v-simple-table
                dense
                fixed-header
                height="190px"
            >
                <thead>
                <tr>
                    <th>氏名</th>
                    <th>最終参加日</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(item,key) in data" :key="key"
                >
                    <td>{{item.name}}</td>
                    <td>{{item.last_date}}</td>
                </tr>
                </tbody>
            </v-simple-table>
        </v-card>
    </v-scale-transition>
</template>

<script>
import SimpleCardDialog from "@/components/Molecules/SimpleCardDialog";
import ProgressCard from "@/components/Student/Detail/ProgressCard";
export default {
    name: "NotAttendedStudents",
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
                const ret = await this.$axios.get(this.$utils.getApiUrl(this.$apis.not_attended_students, true, false));
                this.data = ret.data;
                this.$emit('length', this.data.length)
            }catch (e) {
                this.$utils.catchError(e);
            }finally {
                this.loading = false;
            }
        },
    }
}
</script>

<style scoped>

</style>
