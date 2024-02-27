<template>
    <simple-card-dialog
        ref="dialog"
    >
        <student-form
            class="pt-4"
            v-model="value"
            @hide="hideAndReload()"
        ></student-form>
    </simple-card-dialog>
</template>

<script>
import TextInput from "../Molecules/Forms/TextInput";
import SelectInput from "../Molecules/Forms/SelectInput";
import SimpleCardDialog from "../Molecules/SimpleCardDialog";
import StudentForm from "../Student/StudentForm";

export default {
    components: {
        StudentForm,
        SimpleCardDialog,
        TextInput,
        SelectInput
    },
    name: "Detail",
    data() {
        return {
            dialog: false,
            loading: false
        }
    },
    props: {
        value: {
            type: Object,
            required: true,
        }
    },
    methods: {
        show() {
            this.$refs.dialog.show();
        },
        hide() {
            this.$refs.dialog.hide();
        },
        hideAndReload(){
            this.$emit('reload');
            this.hide();
        }
    }
}
</script>

<style scoped>

</style>
