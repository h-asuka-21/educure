<template>
    <simple-card-dialog
        title="進捗報告"
        ref="dialog"
    >
        <v-form
            ref="form"
        >
            <v-row dense v-for="(obj, key) in data.progress_data" :key="key">
                <v-col cols="4">
                    {{ obj.text }}
                </v-col>
                <v-col>
                    <select-input
                        label="進捗"
                        :items="$selects.student_progress_status"
                        v-model="obj.progress_status"
                        v-if="obj.progress_status != 3"
                        :rules="[v => $validation.required(v,'進捗')]"
                    ></select-input>
                    <p v-if="obj.progress_status === 3">
                        完了
                    </p>
                </v-col>
            </v-row>
            <v-row dense justify="space-around">
                <v-btn :color="$colors.main" dark @click="cancel()">キャンセル</v-btn>
                <v-btn :waiting='processing' :color="$colors.main" dark @click="submit()">登録</v-btn>
            </v-row>
        </v-form>
    </simple-card-dialog>
</template>
<script>
import SimpleCardDialog from "~/components/Molecules/SimpleCardDialog";
import SelectInput from "~/components/Molecules/Forms/SelectInput";
import IconCloseButton from "@/components/Atoms/IconCloseButton";

export default {
    name: "StudentProgressDialog",
    components: {IconCloseButton, SelectInput, SimpleCardDialog},

    data(){
        return {
            data: this.value,
            processing: false
        }
    },
    props: {
        value:{
            type:Object,
            required:true
        }
    },
    watch: {
        value:{
            handler(val) {
                this.data = val;
            },
            deep:true
        },
        data:{
            handler(val) {
                this.$emit('input', val);
            },
            deep: true
        }
    },
    methods: {
        async submit() {
            this.processing = true;
            if(this.$refs.form.validate()){
                try {
                    this.$emit('loading');
                    const data = this.getPostData();
                    const resp = await this.$axios.post(this.$utils.getApiUrl(this.$apis.save_student_progoress, true,true), data);
                    this.$utils.success(resp);
                    this.$emit('reload');
                    this.$refs.dialog.hide();
                    this.processing = false;
                }　catch (e) {
                    this.$utils.catchError(e);
                }　finally {
                    this.$store.dispatch('dialog/loading', false);
                }
            }
        },
        cancel() {
            this.$emit('reload');
            this.$refs.dialog.hide();
        },
        getPostData() {

            const last = this.data.progress_data.slice(-1)[0];

            const result = {
                'id': last.id,
                'reservation_id': this.data.reservation_id,
                'step_id': last.step_id,
                'progress_status': last.progress_status,
            }

            return result;
        }
    }
}
</script>