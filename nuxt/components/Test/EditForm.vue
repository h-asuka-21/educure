<template>
    <v-container>
        <v-row dense class="mb-4" justify="center">
            <v-col cols="12">
                <v-card
                    :disabled="loading"
                    :loading="loading"
                    outlined>
                    <v-card-title>テスト情報</v-card-title>
                    <detail
                        ref="detail"
                        v-model="data.test"
                        :disabled="disabled"
                    ></detail>
                </v-card>
            </v-col>
            <v-col cols="12">
                <v-card
                    :disabled="loading"
                    :loading="loading"
                    outlined>
                    <v-card-title>
                        <p class="subtitle">設問</p>
                        <v-spacer/>
                        <p class="overline">ドラッグアンドドロップで順序を変更できます。</p>
                    </v-card-title>
                    <questions
                        v-model="data.questions"
                        @deleted="v => data.deleted.push(v)"
                        :show="show"
                    ></questions>
                </v-card>
            </v-col>
            <v-btn v-if="show"
                   class="mt-3"
                   color="info" dark
                   id="save"
                   @click="save">
                登録
            </v-btn>

        </v-row>
    </v-container>
</template>

<script>
    import Detail from "./Detail";
    import Questions from "./Questions";

    export default {
        name: "EditForm",
        components: {Questions, Detail},
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
                        const result = await this.$axios.post(this.$utils.getApiUrl('/admin' + this.$apis.test), form_data, {
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
                        if(this.$route.path === '/master/test/add'){
                            this.$router.push('/master/test');
                        }
                    }
                }
            },
            getFormatData: function () {
                const data = new FormData();
                for (let key in this.data.test) {
                    const item = this.data.test[key];
                    if (item === null || item === undefined) {
                        continue;
                    }
                    data.append('test[' + key + ']', item);
                }
                this.data.questions.map((v, k) => {
                    console.log(v);
                    for (let key in v) {
                        const item = v[key];
                        if (item === null || item === undefined) {
                            continue;
                        }
                        data.append('questions[' + k + '][' + key + ']', item);
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
