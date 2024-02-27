<template>
    <v-form
        ref="form"
    >
        <v-container>
            <v-row dense>
                <v-col cols="6">
                    <text-input
                        id="name"
                        label="テスト名"
                        v-model="data.name"
                        :rules="[v => $validation.required(v,'テスト名')]"
                        :disabled="disabled"
                    ></text-input>
                </v-col>
                <v-col cols="6">
                    <select-input
                        id="type"
                        label="タイプ"
                        v-model="data.test_type"
                        :rules="[v => $validation.required(v,'タイプ')]"
                        :items="$selects.test_type"
                        :disabled="disabled"
                    ></select-input>
                </v-col>
                <v-col cols="6">
                    <text-input
                        id="test_time"
                        label="制限時間(分)"
                        type="number"
                        hint="分"
                        v-model="data.test_time"
                        :rules="[v => $validation.required(v,'制限時間',false,true)]"
                        :disabled="disabled"
                    ></text-input>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
    import AutocompleteWithApi from "../Molecules/Forms/AutocompleteWithApi";
    import TextInput from "../Molecules/Forms/TextInput";
    import SelectInput from "../Molecules/Forms/SelectInput";
    export default {
        name: "Detail",
        components: {SelectInput, TextInput, AutocompleteWithApi},
        data() {
            return {
                loading:true,
                data:this.value,
                course_url: '',
                curriculum_url: ''
            }
        },
        props:{
            value:{
                type:Object,
                required: true
            },
            disabled:{
                type:Boolean,
                default: false
            }
        },
        created() {
            if ( this.$route.path.match('admin/test')) {
                this.course_url = '/user' + this.$apis.autocomplete.course;
                this.curriculum_url = '/user' + this.$apis.autocomplete.curriculum;
            } else {
                this.course_url = '/admin' + this.$apis.autocomplete.course;
                this.curriculum_url = '/admin' + this.$apis.autocomplete.curriculum;
            }
        },
        watch:{
            value(val){
                this.data = val;
                if(val.id !== undefined){
                    this.loading = false;
                }
            },
            data(val){
                this.$emit('input', val);
            }
        }
    }
</script>

<style scoped>

</style>
