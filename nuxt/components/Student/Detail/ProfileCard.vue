<template>
    <v-card outlined class="fill-height">
        <v-card-title>
            プロフィール
            <v-spacer></v-spacer>
            <v-btn outlined :color="$colors.main" @click="showDialog()">詳細</v-btn>
        </v-card-title>
        <v-expand-transition>
            <vertical-table
                v-if="student.id"
                :headers="headers"
                :item="student"
            >
            </vertical-table>
        </v-expand-transition>
        <student-form-dialog
            ref="dialog"
            v-model="student"
        ></student-form-dialog>
    </v-card>
</template>

<script>
import VerticalTable from "@/components/Molecules/VerticalTable";
import StudentFormDialog from "@/components/Student/StudentFormDialog";
export default {
    name: "ProfileCard",
    components: {StudentFormDialog, VerticalTable},
    data() {
        return {
            headers: [
                {text: '開始日', value: 'start_date' ,type:'date'},
                {text: '氏名', value: 'name'},
                {text: 'メールアドレス', value: 'email'},
                {text: '受講コース', value: 'course_name'},
            ]
        }
    },
    props:{
        student:{
            type:Object,
            required:true
        }
    },
    methods:{
        showDialog(){
            this.$refs.dialog.$refs.dialog.show();
        }
    }
}
</script>

<style scoped lang="scss">
    tr{
        font-size: 0.9rem;
        th {
            text-align: left;
            color: #757575;
        }
        th,td{
            padding-top: 4px;
            padding-bottom: 4px;
        }

    }
</style>
