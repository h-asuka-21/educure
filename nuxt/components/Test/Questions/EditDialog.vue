<template>
    <simple-card-dialog title="設問"
                        ref="dialog"
    >
        <question-edit-form
            v-model="data"
            @save="save()"
        ></question-edit-form>
    </simple-card-dialog>
</template>

<script>
    import SimpleCardDialog from "../../Molecules/SimpleCardDialog";
    import QuestionEditForm from "./QuestionEditForm";
    export default {
        name: "EditDialog",
        components: {QuestionEditForm, SimpleCardDialog},
        props:{
            value:{
                type:Object,
                required:true
            }
        },
        data(){
            return {
                data: this.value
            };
        },
        watch:{
            value(val) {
                this.data = val;
            },
            data(val) {
                this.$emit('input', val);
            }
        },
        methods:{
            save(){
                this.$refs.dialog.hide();
                this.$emit('save');
            }
        }
    }
</script>

<style scoped>

</style>
