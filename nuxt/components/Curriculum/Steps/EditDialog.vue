<template>
    <simple-card-dialog title="ステップ"
                        ref="dialog"
    >
        <step-edit-form
            v-model="data"
            @save="save()"
        ></step-edit-form>
    </simple-card-dialog>
</template>

<script>
    import SimpleCardDialog from "../../Molecules/SimpleCardDialog";
    import StepEditForm from "./StepEditForm";
    export default {
        name: "EditDialog",
        components: {StepEditForm, SimpleCardDialog},
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
