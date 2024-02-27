<template>
    <v-form
        ref="form"
        lazy-validation
    >
        <v-container>
            <v-row dense justify="space-around">
                <v-col cols="6">
                    <text-input
                        id="name"
                        label="コース名"
                        v-model="data.name"
                        :rules="[v => $validation.required(v,'コース名')]"
                        hint="必須"
                        :persistent-hint="true"
                    ></text-input>
                </v-col>
                <v-col cols="6">
                    <autocomplete-with-api
                        label="CABテスト"
                        hint="受講開始時に受験させるテストを指定してください"
                        :persistent-hint="true"
                        :param="{type:2}"
                        v-model="data.first_test_id"
                        :url="$utils.getApiUrl($apis.autocomplete.test,true,true)"
                        :clearable="true"
                    ></autocomplete-with-api>
                </v-col>
                <v-col cols="6">
                    <autocomplete-with-api
                        label="総合テスト"
                        hint="全カリキュラム修了時に受験するテストを指定してください"
                        :persistent-hint="true"
                        :param="{type:1}"
                        v-model="data.general_test_id"
                        :url="$utils.getApiUrl($apis.autocomplete.test,true,true)"
                        :clearable="true"
                    ></autocomplete-with-api>
                </v-col>
            </v-row>
            <v-row class="justify-center">
                <v-btn dark :color="$colors.main" @click="submit()">{{ data.id === undefined ? '登録' : '更新' }}</v-btn>
            </v-row>
        </v-container>
    </v-form>
</template>

<script>
import TextInput from "../Molecules/Forms/TextInput";
import AutocompleteWithApi from "@/components/Molecules/Forms/AutocompleteWithApi";

export default {
    name: "CourseForm",
    components: {AutocompleteWithApi, TextInput},
    props: {
        value: {
            type: Object,
            default: {},
            required: false
        }
    },
    data() {
        return {
            data: this.value
        }
    },
    watch: {
        value(v) {
            this.data = v;
        },
        data(v) {
            this.$emit('input', v);
        }
    },
    methods: {
        async submit() {
            if (this.$refs.form.validate()) {
                const url = this.$utils.getApiUrl(this.$apis.course, true);
                this.$store.dispatch('loader/showSub')
                try {
                    let result = false;
                    if (!this.data.id) {
                        result = await this.$axios.post(url, this.data);
                    } else {
                        result = await this.$axios.put(url, this.data);
                    }
                    this.$utils.success(result);
                } catch (e) {
                    this.$utils.catchError(e);
                } finally {
                    this.$store.dispatch('loader/hideSub')
                    this.$emit('hide');
                }
            }
        }
    }
}
</script>

<style scoped>

</style>
