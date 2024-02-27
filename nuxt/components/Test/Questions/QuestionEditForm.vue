<template>
    <v-form
        ref="form"
    >
        <v-container>
            <v-row dense>
                <v-col cols="6">
                    <text-input
                        label="名前"
                        id="question_name"
                        v-model="data.name"
                        :rules="[v=>$validation.required(v,'名前')]"
                    ></text-input>
                </v-col>
            </v-row>
            <v-row dense>
                <v-col>
                    <wysiwig-input
                        label="問題文"
                        id="question_content"
                        v-model="data.content"
                        :rules="[v => $validation.required(v,'問題文')]"
                    ></wysiwig-input>
                </v-col>
            </v-row>
            <v-row dense>
                <v-col>
                    <image-input
                        label="画像"
                        id="question_image"
                        v-model="data.image"
                    ></image-input>
                </v-col>
            </v-row>
            <v-row dense>
                <v-col cols="12"><p class="subtitle">選択肢</p></v-col>
                <v-col cols="12" id="answer_col">
                    <v-radio-group v-model="data.answer" id="answers" row>
                        <v-col v-for="n in 4" :key="n" cols="6">
                            <v-row dense align="center">
                                <v-radio label="" :value="n"
                                         class="answer_radio"
                                ></v-radio>
                                <text-input
                                    :label="n.toLocaleString()"
                                    :id="'question_choice' + n"
                                    v-model="data['choice' + n]"
                                    :rules="[v=>$validation.required(v,'設問'+n)]"
                                ></text-input>
                            </v-row>
                        </v-col>
                    </v-radio-group>
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
        name: "QuestionEditForm",
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
    #answer_col{
        .v-input--radio-group--row{
            margin-top: 0;
        }
    }
    .subtitle{
        margin-bottom: 0;
    }
    .answer_radio {
        margin-top: -26px;
        margin-right: 4px;
    }
</style>
