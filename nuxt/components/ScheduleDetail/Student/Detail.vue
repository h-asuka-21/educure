<template>
    <v-dialog
        v-if="dialog"
        v-model="dialog"
    >
        <v-card
            :loading="loading"
            :disabled="loading"
        >
            <v-card-title>{{data.name}}詳細</v-card-title>
            <v-form
                ref="form"
                lazy-validation
            >
                <v-container>
                    <v-row dense>
                        <v-col cols="6">
                            <text-input
                                label="名前"
                                v-model="data.name"
                                :rules="[v => $validation.required(v,'管理者名')]"
                            ></text-input>
                        </v-col>
                        <v-col cols="6">
                            <text-input
                                label="名前（カナ）"
                                v-model="data.name_kana"
                                :rules="[v => $validation.required(v,'管理者名（カナ）')]"
                            ></text-input>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col cols="6">
                            <text-input
                                label="メールアドレス"
                                v-model="data.email"
                                :rules="[v => $validation.required(v,'メールアドレス')]"
                            ></text-input>
                        </v-col>
                    </v-row>
                    <v-row class="justify-center">
                        <v-btn dark :color="$colors.main" @click="submit()">登録</v-btn>
                    </v-row>
                </v-container>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
    import TextInput from "../../Molecules/Forms/TextInput";
    import SelectInput from "../../Molecules/Forms/SelectInput";
    export default {
        components: {
            TextInput,
            SelectInput
        },
        name: "Detail.vue",
        data(){
            return{
                dialog: false,
                data: this.value,
                loading: false
            }
        },
        watch: {
            data(val){
                console.log(val);
            },
            value(val){
                this.data = val;
            }
        },
        props: {
            value:{
                type:Object,
                required: true,
            }
        },
        methods:{
            show(){
                this.dialog = true;
            },
            hide(){
                this.dialog = false;
            },
            submit(){
                if(!this.$refs.form.validate()){
                    return;
                }
                const url = '/admin' + this.$apis.student + '/' + this.data.id;
                this.loading = true;
                this.$axios.put(url, this.data).then(resp => {
                    this.loading = false;
                    this.$utils.success(resp);
                    this.hide();
                }).catch(err => {
                    this.$utils.catchError(err);
                    this.loading = false;
                });
            },
        }
    }
</script>

<style scoped>

</style>
