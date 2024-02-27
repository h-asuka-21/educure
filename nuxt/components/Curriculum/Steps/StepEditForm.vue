<template>
    <v-form
        ref="form"
    >
        <v-container>
            <v-row dense>
                <v-col cols="6">
                    <text-input
                        label="名前"
                        id="step_name"
                        v-model="data.name"
                        :rules="[v => $validation.required(v,'名前')]"
                    ></text-input>
                </v-col>
            </v-row>
            <v-row dense>
                <v-col cols="6">
                    <text-input
                        label="目標日数（1ヶ月）"
                        id="step_target_days"
                        v-model="data.target_days"
                        :rules="[v => $validation.required(v,'目標日数（1ヶ月）')]"
                        type="number"
                    ></text-input>
                </v-col>
                <v-col cols="6">
                    <text-input
                        label="目標日数（2ヶ月）"
                        id="step_deadline_days"
                        v-model="data.deadline_days"
                        :rules="[v => $validation.required(v,'目標日数（2ヶ月）')]"
                        type="number"
                    ></text-input>
                </v-col>
            </v-row>
            <v-row dense>
                <v-col>
                    <wysiwig-input
                        label="内容"
                        id="step_content"
                        v-model="data.content"
                        :rules="[v => $validation.required(v,'内容')]"
                    ></wysiwig-input>
                </v-col>
            </v-row>
            <v-row dense>
                <v-col>
                    <image-input
                        label="画像"
                        id="step_image"
                        v-model="data.image"
                    ></image-input>
                </v-col>
            </v-row>
            <v-row dense justify="center" >
                <v-btn dark color="info" @click="save">登録</v-btn>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
    import TextInput from "../../Molecules/Forms/TextInput";
    import TextArea from "../../Molecules/Forms/TextArea";
    import SelectInput from "../../Molecules/Forms/SelectInput";
    import ImageInput from "../../Molecules/Forms/ImageInput";
    import WysiwigInput from "../../Molecules/Forms/WysiwigInput";

    export default {
        name: "StepEditForm",
        components: {WysiwigInput, ImageInput, SelectInput, TextArea, TextInput},
        props: {
            value: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                data: this.value,
            };
        },
        watch: {
            value(val) {
                this.data = val;
            },
            data(val) {
                this.$emit('input', val);
            },
        },
        methods:{
            save(){
                if(this.$refs.form.validate()){
                    this.$emit('save');
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .subtitle{
        margin-bottom: 0;
    }
</style>
