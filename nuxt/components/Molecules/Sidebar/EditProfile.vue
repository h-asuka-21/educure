<template>
<simple-card-dialog
    title="プロフィール編集"
    ref="dialog"
    max_width="700"

>
    <admin-form v-if="$utils.isAdmin()" v-model="data" @hide="hide()"/>
    <user-form v-else-if="$utils.isUser()" v-model="data" :change_password="true" @success="hide()"/>
    <student-form v-else-if="$utils.isStudent()" v-model="data" :change_password="true" @hide="hide()"/>
</simple-card-dialog>
</template>

<script>
import SimpleCardDialog from "@/components/Molecules/SimpleCardDialog";
import UserForm from "@/components/User/UserForm";
import StudentForm from "@/components/Student/StudentForm";
import AdminForm from "@/components/Admin/AdminForm";
export default {
    name: "EditProfile",
    components: {AdminForm, StudentForm, UserForm, SimpleCardDialog},
    data(){
        return{
            data: {},
            loading: false
        }
    },
    created() {
        if (this.$utils.isAdmin()) {
            this.data = JSON.parse(JSON.stringify(this.$store.state.admin.data));
        } else if (this.$utils.isUser()) {
            this.data = JSON.parse(JSON.stringify(this.$store.state.user.data));
        } else if (this.$utils.isStudent()) {
            this.data = JSON.parse(JSON.stringify(this.$store.state.student.data));
        }
    },
    methods:{
        show() {
            this.$refs.dialog.show()
        },
        hide(){
            this.$refs.dialog.hide()
        }
    }
}
</script>

<style scoped>

</style>
