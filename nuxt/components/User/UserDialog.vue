<template>
    <simple-card-dialog
        ref="dialog"
    >
        <user-form class="pt-4" v-model="value"
                   @success="v => {$emit('reload');hide()}"
        />
    </simple-card-dialog>
</template>

<script>
import SimpleCardDialog from "~/components/Molecules/SimpleCardDialog";
import UserForm from "~/components/User/UserForm";
export default {
    name: "UserDialog",
    components: {UserForm, SimpleCardDialog},
    props:{
        value:{
            type:Object,
            default:{}
        }
    },
    methods:{
        show() {
            this.$refs.dialog.show();
        },
        hide(){
            this.$refs.dialog.hide();
        }
    }
}
</script>

<style scoped>

</style>
