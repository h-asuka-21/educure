<template>
    <v-form
        ref="form"
        lazy-validation
    >
        <v-row dense>
            <v-col cols="6">
                <text-input
                    id="name"
                    label="氏名"
                    v-model="data.name"
                    :rules="[v => $validation.required(v,'氏名'),$validation.name]"
                    hint="必須"
                    :persistent-hint="true"
                ></text-input>
            </v-col>
            <v-col cols="6">
                <text-input
                    id="name_kana"
                    label="氏名（カナ）"
                    v-model="data.name_kana"
                    :rules="[v => $validation.required(v,'氏名（カナ）'),$validation.kana]"
                    hint="必須"
                    :persistent-hint="true"
                ></text-input>
            </v-col>
            <v-col cols="6">
                <text-input
                    id="email"
                    label="メールアドレス"
                    v-model="data.email"
                    :rules="[v => $validation.required(v,'メールアドレス'),$validation.email]"
                    hint="必須"
                    :persistent-hint="true"
                ></text-input>
            </v-col>
            <v-col cols="6" v-if="!data.id">
                <text-input
                    id="password"
                    label="パスワード"
                    type="password"
                    v-model="data.password"
                    :rules="[v => $validation.required(v,'パスワード'), $validation.password]"
                    hint="必須"
                    :persistent-hint="true"
                ></text-input>
            </v-col>
            <v-col cols="12">
                <password-change v-if="change_password" v-model="data"/>
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
        </v-row>
    </v-form>

</template>

<script>
import TextInput from "@/components/Molecules/Forms/TextInput";
import PasswordChange from "@/components/Molecules/PasswordChange";
export default {
    name: "UserForm",
    components: {PasswordChange, TextInput},
    props:{
        value: {
            type: Object,
            required: true,
        },
        change_password: {
            type:Boolean,
            default:false
        }
    },
    data(){
        return{
            data:this.value,
            button_label:'登録',
        }
    },
    watch:{
        value(v){
            this.data = v;
        }
    },
    created() {
        if (this.data.id) {
            this.button_label = '更新'
        }
    },
    methods: {
        async sendForm(delete_flg = false) {
            if (delete_flg) {
                this.delete();
                return;
            }
            if (this.$refs.form.validate()) {
                let result = null;
                try{
                    this.$store.dispatch('dialog/loading');
                    this.$store.dispatch('loader/showSub');
                    let url = this.$utils.getApiUrl(this.$apis.user, true,true);
                    if (!this.data.id) {
                        result = await this.$axios.post(url,this.data)
                    } else {
                        result = await this.$axios.put(url + '/' + this.data.id,this.data)
                    }
                    if(this.$utils.isUser() && this.data.password_change){
                        this.$utils.success({data: {message: 'パスワードを変更しました。新しいパスワードでログインし直してください。'}});
                        await this.$store.dispatch('student/logout')
                    } else {
                        this.$utils.success({data:{message:'管理者の登録に成功しました。'}})
                    }
                    this.$emit('success');
                } catch (e){
                    this.$utils.catchError(e);
                } finally {
                    this.$store.dispatch('dialog/loading',false);
                    this.$store.dispatch('loader/hideSub');
                }
            }
        },
    }
}
</script>

<style scoped>

</style>
