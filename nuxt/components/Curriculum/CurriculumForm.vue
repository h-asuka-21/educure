<template>
    <v-form
        ref="form"
        lazy-validation
    >
        <div>
            <v-row dense>
                <v-col cols="6">
                    <text-input
                        id="name"
                        label="カリキュラム名"
                        v-model="data.name"
                        :rules="[v => $validation.required(v,'カリキュラム名')]"
                    ></text-input>
                </v-col>
                <v-col cols="6">
                    <autocomplete-with-api
                        id="course_id"
                        label="コース"
                        v-model="data.course_id"
                        :url="'/admin'+$apis.autocomplete.course"
                    ></autocomplete-with-api>
                </v-col>
            </v-row>
            <v-row class="justify-center">
                <v-btn dark :color="$colors.main" @click="submit()">{{ data.id === undefined ? '登録' : '更新' }}</v-btn>
            </v-row>
        </div>
    </v-form>
</template>

<script>
import TextInput from "../Molecules/Forms/TextInput";
import AutocompleteWithApi from "../Molecules/Forms/AutocompleteWithApi";

export default {
    name: "CurriculumForm",
    components: {AutocompleteWithApi, TextInput},
    props: {
        value: {
            type: Object,
            required: true
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
                const url = this.$utils.getApiUrl(this.$apis.curriculum, true);
                this.$store.dispatch('loader/showSub')
                try {
                    let result = false;
                    if (!this.data.id) {
                        result = await this.$axios.post(url, this.data);
                    } else {
                        result = await this.$axios.put(url, this.data);
                    }
                    this.$utils.success(result);
                    if (!this.data.id) {
                        this.$router.back();
                    }
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
