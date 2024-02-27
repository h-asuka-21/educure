<template>
    <v-form
        ref="form"
        lazy-validation
    >
        <v-container>
            <v-row dense justify="center">
                <v-col cols="6">
                    <text-input
                        id="name"
                        label="カリキュラム名"
                        v-model="data.name"
                        :disabled="disabled"
                        :rules="[v => $validation.required(v,'カリキュラム名')]"/>
                </v-col>
                <v-col cols="6">
                    <autocomplete-with-api
                        label="テスト"
                        hint="全ステップ修了時に受験するテストを指定してください"
                        :persistent-hint="true"
                        :param="{type:0}"
                        v-model="data.test_id"
                        :url="$utils.getApiUrl($apis.autocomplete.test,true,true)"
                        :disabled="disabled"
                        :clearable="true"/>
                </v-col>
                <v-col cols="11">
                    <zip-input
                        id="zip"
                        label="ファイル"
                        v-model="data.zip"
                        :file_name="data.zip_name"
                        :disabled="disabled"
                        @name="v => data.zip_name = v"/>
                </v-col>
                <v-col cols="1">
                  <v-btn color="info" :disabled="isZipDisabled(data)" @click="download(data)">ダウンロード</v-btn>
                </v-col>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
    import TextInput from "../Molecules/Forms/TextInput";
    import ZipInput from "../Molecules/Forms/ZipInput";
    import AutocompleteWithApi from "@/components/Molecules/Forms/AutocompleteWithApi";
    export default {
        name: "Curriculum",
        components: {AutocompleteWithApi, TextInput, ZipInput},
        data() {
            return {
                loading:true,
                data:this.value,
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
        },
        methods:{
            isZipDisabled(data){
                data.zip_name !== null ? true :false;
            },
            download(v){
                if(v.zip_name !== null){
                  location.href = v.zip;
                }
                return false;
            }
        }
    }
</script>

<style scoped>

</style>
