<template>
    <div>
        <v-row dense class="mb-4">
            <v-col cols="12">
                <v-card outlined class="fill-height"
                        :loading="loading"
                        :disabled="loading"
                >
                    <v-card-title>カリキュラム情報</v-card-title>
                    <curriculum
                        ref="detail"
                        v-model="data.curriculum"
                        :disabled="disabled"
                    ></curriculum>
                </v-card>
            </v-col>
            <v-col cols="12">
                <v-card
                    :loading="loading"
                    :disabled="loading"
                    outlined>
                    <v-card-title>ステップ</v-card-title>
                    <steps
                        :loading="loading"
                        v-model="data.steps"
                        @deleted="v => data.deleted.push(v)"
                        :show="show"
                        :disabled="disabled"
                    ></steps>
                </v-card>
            </v-col>
        </v-row>
        <v-row dense class="mb-4">
        </v-row>
        <v-row justify="center">
            <v-btn v-if="show"
                   color="info" dark
                   id="save"
                   @click="save">
                登録
            </v-btn>
        </v-row>
    </div>
</template>

<script>
    import Curriculum from "./Curriculum";
    import Steps from "./Steps";

    export default {
        name: "EditForm",
        components: {Steps, Curriculum},
        props: {
            value: {
                type: Object,
                required: true
            },
            loading: {
                type: Boolean,
                default: false
            },
            disabled: {
                type: Boolean,
                default: false
            },
            show: {
                type: Boolean,
                default: true
            }
        },
        data() {
            return {
                data: this.value
            }
        },
        watch: {
            value(val) {
                this.data = val;
            },
            data(val) {
                this.$emit('input', val);
            }
        },
        methods: {
            async save() {
                if (this.$refs.detail.$refs.form.validate()) {
                    this.$store.dispatch('loader/showSub');
                    const form_data = this.getFormatData();
                    try {
                        const result = await this.$axios.post(this.$utils.getApiUrl('/admin' + this.$apis.curriculum), form_data, {
                            headers: {
                                'content-type': 'multipart/form-data',
                                'X-HTTP-Method-Override': this.$utils.getHTTPMethod(),
                            }
                        });
                        this.$utils.success(result);
                    } catch (e) {
                        this.$utils.catchError(e);
                    } finally {
                        this.$store.dispatch('loader/hideSub');
                        this.$emit('reload');
                        if(this.$route.path === '/master/curriculum/add'){
                            this.$router.push('/master/curriculum');
                        }
                    }
                }
            },
            getFormatData: function () {
                const data = new FormData();
                for (let key in this.data.curriculum) {
                    const item = this.data.curriculum[key];
                    if (item === null || item === undefined) {
                        continue;
                    }
                    data.append('curriculum[' + key + ']', item);
                }
                this.data.steps.map((v, k) => {
                    console.log(v);
                    for (let key in v) {
                        const item = v[key];
                        if (item === null || item === undefined) {
                            continue;
                        }
                        data.append('steps[' + k + '][' + key + ']', item);
                    }
                });
                console.log(this.data.deleted);
                this.data.deleted.map((v,k) => {
                    data.append('deleted[' + k + ']', v);
                });
                return data;
            },
        }
    }
</script>

<style scoped>

</style>
