<template>
<simple-card-dialog
    title="面談詳細"
    ref="dialog"
>
    <interview-form
        :value="value"
        @success="success"
    ></interview-form>
</simple-card-dialog>
</template>

<script>
import SimpleCardDialog from "~/components/Molecules/SimpleCardDialog";
import InterviewForm from "~/components/Interview/InterviewForm";
export default {
    name: "InterviewFormDialog",
    components: {InterviewForm, SimpleCardDialog},
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
