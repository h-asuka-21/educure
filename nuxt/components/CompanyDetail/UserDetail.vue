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
                                id="name"
                                label="管理者名"
                                v-model="data.name"
                                :rules="[v => $validation.required(v,'管理者名')]"
                            ></text-input>
                        </v-col>
                        <v-col cols="6">
                            <text-input
                                id="name_kana"
                                label="管理者名（カナ）"
                                v-model="data.name_kana"
                                :rules="[v => $validation.required(v,'管理者名（カナ）')]"
                            ></text-input>
                        </v-col>
                    </v-row>
                    <v-row dense>
                        <v-col cols="6">
                            <text-input
                                id="email"
                                label="メールアドレス"
                                v-model="data.email"
                                :rules="[v => $validation.required(v,'メールアドレス')]"
                            ></text-input>
                        </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="12">
                            <v-switch
                                data_test='password'
                                v-model="enable_change_password"
                                class="ma-2"
                                label="パスワードを変更する"></v-switch>
                        </v-col>
                    </v-row>
                    <v-row v-if="enable_change_password"
                           transition="scale-transition">
                        <v-col cols="6">
                            <text-input
                                id="password"
                                label="パスワード"
                                v-model="data.password"
                            ></text-input>
                        </v-col>
                        <v-col cols="6">
                            <text-input
                                id="password_confirm"
                                label="パスワード（確認）"
                                v-model="data.password_confirm"
                            ></text-input>
                        </v-col>
                    </v-row>

                    <v-row class="justify-center">
                        <v-btn
                            dark
                            :color="$colors.main"
                            @click="sendForm(false)"
                        >
                            {{button_label}}
                        </v-btn>
                        <delete-button
                            @delete="sendForm(true)"
                            :message="delete_confirm_message"
                        >
                        </delete-button>
                    </v-row>
                </v-container>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
    import TextInput from "../Molecules/Forms/TextInput";
    import SelectInput from "../Molecules/Forms/SelectInput";
    import DeleteButton from "../Molecules/DeleteButton";
    export default {
        name: "UserDetail",
        components: {
            TextInput,
            SelectInput,
            DeleteButton
        },
        data(){
            return{
                dialog: false,
                data: this.value,
                loading: false,
                enable_change_password: false,
                button_label:'更新',
                delete_confirm_message:'<p>この管理者を削除してもよろしいですか？</p>',
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
            validate () {
                if (this.$refs.form.validate()) {
                    return true;
                }
                return false;
            },
            sendForm(delete_flg = false) {
                if(delete_flg ){
                    this.delete();
                    return;
                }
                if(this.validate()){
                    this.update();
                }
            },
            update(){
                const url = '/admin' + this.$apis.user + '/' + this.data.id;
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
            delete(){
                const url = '/admin' + this.$apis.user + '/' + this.data.id;
                this.loading = true;
                this.$axios.delete(url, this.data).then(resp => {
                    this.$utils.success(resp);
                }).catch(err => {
                    this.$utils.catchError(err);
                });
            },
        }
    }
</script>

<style scoped>

</style>
