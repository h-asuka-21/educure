<template>
<simple-card-dialog
    title="プロフィール詳細"
    ref="dialog"
>
    <student-form
        v-model="data"
    ></student-form>
</simple-card-dialog>
</template>

<script>
import SimpleCardDialog from "~/components/Molecules/SimpleCardDialog";
import StudentForm from "~/components/Student/StudentForm";
export default {
    name: "StudentFormDialog",
    components: {StudentForm, SimpleCardDialog},
    props:{
        value:{
            type:Object,
            required:true
        }
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
        }
    }
}
</script>

<style scoped>

</style>
